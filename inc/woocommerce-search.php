<?php
/**
 * WooCommerce Search Enhancements (Drop-in Replacement with Global Normalization + SKU/Product Code)
 *
 * @package elevator
 */

if (! defined('ABSPATH')) {
    exit;
}

// ─────────────────────────────────────────────────────────────
// Helper: Normalize search terms
// ─────────────────────────────────────────────────────────────
function elevator_normalize_search_term($term) {
    // Replace en dash and em dash with normal hyphen
    $term = str_replace(["–", "—"], "-", $term);

    // Replace curly quotes with straight quotes
    $term = str_replace(
        ["“", "”", "„", "‟", "‘", "’", "‚", "‛"],
        ['"', '"', '"', '"', "'", "'", "'", "'"],
        $term
    );

    // Collapse multiple whitespace
    $term = preg_replace('/\s+/u', ' ', $term);

    // Strip accents/diacritics
    if (class_exists('Normalizer')) {
        $term = Normalizer::normalize($term, Normalizer::FORM_D);
        $term = preg_replace('/\p{Mn}/u', '', $term);
    }

    return trim($term);
}

// ─────────────────────────────────────────────────────────────
// GLOBAL: Normalize search term before queries run (Frontend)
// ─────────────────────────────────────────────────────────────
function elevator_global_normalize_search($query) {
    if (!is_admin() && $query->is_search() && $query->is_main_query()) {
        $search = $query->get('s');
        if (!empty($search)) {
            $query->set('s', elevator_normalize_search_term($search));
        }
    }
}
add_action('pre_get_posts', 'elevator_global_normalize_search');

// ─────────────────────────────────────────────────────────────
// ADMIN: Search products by Title + OEM Number + SKU (Product Code)
// ─────────────────────────────────────────────────────────────
function elevator_admin_oem_search(WP_Query $query)
{
    global $pagenow, $wpdb;

    if (!is_admin() || $pagenow !== 'edit.php' || !$query->is_main_query()) {
        return;
    }
    if (empty($_GET['post_type']) || $_GET['post_type'] !== 'product') {
        return;
    }
    if (empty($_GET['s'])) {
        return;
    }

    $search = sanitize_text_field(wp_unslash($_GET['s']));
    $search = elevator_normalize_search_term($search);
    if ($search === '') {
        return;
    }

    $cache_key   = 'elevator_oem_search_' . md5($search);
    $cache_group = 'elevator_search';
    $merged_ids  = wp_cache_get($cache_key, $cache_group);

    if (false === $merged_ids) {
        $like = '%' . $wpdb->esc_like($search) . '%';

        // OEM Number search
        $oem_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT post_id
                 FROM {$wpdb->postmeta}
                 WHERE meta_key = 'oem_number'
                 AND meta_value LIKE %s COLLATE utf8mb4_general_ci",
                $like
            )
        );

        // SKU (Product Code) search
        $sku_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT post_id
                 FROM {$wpdb->postmeta}
                 WHERE meta_key = '_sku'
                 AND meta_value LIKE %s COLLATE utf8mb4_general_ci",
                $like
            )
        );

        // Title search
        $title_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID
                 FROM {$wpdb->posts}
                 WHERE post_type = 'product'
                 AND post_status != 'trash'
                 AND post_title LIKE %s COLLATE utf8mb4_general_ci",
                $like
            )
        );

        $merged_ids = array_values(array_unique(array_map('intval', array_merge($oem_ids, $sku_ids, $title_ids))));
        wp_cache_set($cache_key, $merged_ids, $cache_group, 0);
    }

    if (empty($merged_ids)) {
        $query->set('post__in', array(0));
        $query->set('s', '');
        return;
    }

    $query->set('post__in', $merged_ids);
    $query->set('s', '');
}
add_action('pre_get_posts', 'elevator_admin_oem_search');

// ─────────────────────────────────────────────────────────────
// FRONTEND: Extend WooCommerce product search (SKU, brand, OEM)
// ─────────────────────────────────────────────────────────────
function elevator_woocommerce_search_meta($sql, $search, $sql_where)
{
    global $wpdb;
    if (empty($search)) {
        return $sql;
    }

    $search = elevator_normalize_search_term($search);
    $like = '%' . $wpdb->esc_like($search) . '%';

    $sql .= $wpdb->prepare(' OR sku_meta.meta_value LIKE %s', $like);
    $sql .= $wpdb->prepare(' OR brand_meta.meta_value LIKE %s', $like);
    $sql .= $wpdb->prepare(' OR oem_meta.meta_value LIKE %s', $like);

    add_filter('posts_join', 'elevator_search_meta_joins');
    return $sql;
}
add_filter('woocommerce_product_data_store_cpt_get_search_sql', 'elevator_woocommerce_search_meta', 10, 3);

function elevator_search_meta_joins($join)
{
    global $wpdb;
    if (strpos($join, ' sku_meta') === false) {
        $join .= " LEFT JOIN {$wpdb->postmeta} AS sku_meta ON ({$wpdb->posts}.ID = sku_meta.post_id AND sku_meta.meta_key = '_sku')";
    }
    if (strpos($join, ' brand_meta') === false) {
        $join .= " LEFT JOIN {$wpdb->postmeta} AS brand_meta ON ({$wpdb->posts}.ID = brand_meta.post_id AND brand_meta.meta_key = 'brand_name')";
    }
    if (strpos($join, ' oem_meta') === false) {
        $join .= " LEFT JOIN {$wpdb->postmeta} AS oem_meta ON ({$wpdb->posts}.ID = oem_meta.post_id AND oem_meta.meta_key = 'oem_number')";
    }
    return $join;
}

// ─────────────────────────────────────────────────────────────
// FRONTEND: Relevance ordering — Title match > High Priority > Alphabetical
// ─────────────────────────────────────────────────────────────
function elevator_search_custom_orderby($orderby, $q)
{
    global $wpdb;

    if (!is_admin() && $q->is_search() && $q->is_main_query() &&
        ($q->get('post_type') === 'product' || (is_array($q->get('post_type')) && in_array('product', (array) $q->get('post_type'), true)))
    ) {
        $search = $q->get('s');
        $search = elevator_normalize_search_term($search);
        if ($search) {
            $like = '%' . $wpdb->esc_like($search) . '%';
            $orderby = "
                (CASE 
                    WHEN {$wpdb->posts}.post_title LIKE " . $wpdb->prepare('%s', $like) . " THEN 1 ELSE 0 
                END) DESC,
                (SELECT meta_value FROM {$wpdb->postmeta}
                 WHERE post_id = {$wpdb->posts}.ID
                 AND meta_key = 'high_priority_search' LIMIT 1)+0 DESC,
                {$wpdb->posts}.post_title ASC
            ";
        }
    }
    return $orderby;
}
add_filter('posts_orderby', 'elevator_search_custom_orderby', 10, 2);
