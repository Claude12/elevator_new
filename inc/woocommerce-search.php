<?php

/**
 * WooCommerce Search Enhancements
 *
 * @package elevator
 */

if (! defined('ABSPATH')) {
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN: Search products by OEM Number in the WP Admin Products list
//
// Uses pre_get_posts to:
//  1. Run a single targeted postmeta query for oem_number matches.
//  2. Run a single targeted posts query for title matches.
//  3. Merge both result sets and set post__in — bypassing WordPress's
//     expensive full-text LIKE scan across post_title + post_content.
//
// Results are object-cached per search term for the duration of the request
// so repeated calls (e.g. pagination count query) hit cache, not the DB.
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Intercept the admin product list query and inject OEM-matched product IDs.
 *
 * @param WP_Query $query The current WP_Query object.
 */
function elevator_admin_oem_search(WP_Query $query)
{
    global $pagenow, $wpdb;

    // ── Bail out as cheaply as possible ──────────────────────────────────────

    // 1. Must be admin.
    if (! is_admin()) {
        return;
    }

    // 2. Must be the product list page.
    if ($pagenow !== 'edit.php') {
        return;
    }

    // 3. Must be the main query.
    if (! $query->is_main_query()) {
        return;
    }

    // 4. Must be searching products. phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (empty($_GET['post_type']) || $_GET['post_type'] !== 'product') { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return;
    }

    // 5. Must have a search term. phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if (empty($_GET['s'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        return;
    }

    $search = sanitize_text_field(wp_unslash($_GET['s'])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

    if ($search === '') {
        return;
    }

    // ── Object cache — avoid duplicate DB hits on the same request ────────────
    $cache_key   = 'elevator_oem_search_' . md5($search);
    $cache_group = 'elevator_search';
    $merged_ids  = wp_cache_get($cache_key, $cache_group);

    if (false === $merged_ids) {

        $like = '%' . $wpdb->esc_like($search) . '%';

        // Single targeted postmeta query — only touches meta_key + meta_value.
        // Performance note: ACF stores oem_number as a plain postmeta row.
        // Adding a DB index on (meta_key, meta_value(20)) would make this
        // sub-millisecond. See note at bottom of file.
        $oem_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT post_id
                 FROM {$wpdb->postmeta}
                 WHERE meta_key = 'oem_number'
                 AND meta_value LIKE %s",
                $like
            )
        );

        // Single targeted posts query — title only, no content/excerpt scan.
        $title_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID
                 FROM {$wpdb->posts}
                 WHERE post_type = 'product'
                 AND post_status != 'trash'
                 AND post_title LIKE %s",
                $like
            )
        );

        // Merge and deduplicate as integers.
        $merged_ids = array_values(
            array_unique(
                array_map('intval', array_merge($oem_ids, $title_ids))
            )
        );

        // Cache for the duration of this request.
        wp_cache_set($cache_key, $merged_ids, $cache_group, 0);
    }

    if (empty($merged_ids)) {
        // Search term found no matches — force empty result set.
        $query->set('post__in', array(0));
        $query->set('s', '');
        return;
    }

    // Set post__in and clear 's' so WordPress skips its full-text scan entirely.
    $query->set('post__in', $merged_ids);
    $query->set('s', '');
}
add_action('pre_get_posts', 'elevator_admin_oem_search');

// ───────────────────────────────���─────────────────────────────────────────────
// FRONTEND: WooCommerce product data store search — SKU, brand_name, oem_number
//
// Uses the correct WooCommerce hook which fires for both frontend and the
// WooCommerce AJAX product search (used in orders, coupons, etc).
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Extend WooCommerce product search SQL to include SKU, brand, and OEM meta.
 *
 * @param string $sql      The search SQL fragment.
 * @param string $search   The search term.
 * @param string $sql_where SQL WHERE clause.
 * @return string Modified SQL.
 */
function elevator_woocommerce_search_meta($sql, $search, $sql_where)
{
    global $wpdb;

    if (empty($search)) {
        return $sql;
    }

    $like = '%' . $wpdb->esc_like($search) . '%';

    $sql .= $wpdb->prepare(' OR sku_meta.meta_value LIKE %s', $like);
    $sql .= $wpdb->prepare(' OR brand_meta.meta_value LIKE %s', $like);
    $sql .= $wpdb->prepare(' OR oem_meta.meta_value LIKE %s', $like);

    // Register the JOIN only when this filter actually fires.
    add_filter('posts_join', 'elevator_search_meta_joins');

    return $sql;
}
add_filter('woocommerce_product_data_store_cpt_get_search_sql', 'elevator_woocommerce_search_meta', 10, 3);

/**
 * Add the postmeta JOINs needed for SKU, brand, and OEM frontend search.
 * Named function — safe to add_filter multiple times (WordPress deduplicates).
 *
 * @param string $join SQL JOIN clause.
 * @return string
 */
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

// ─────────────────────────────────────────────────────────────────────────────
// FRONTEND: Relevance orderby — High Priority flag > Title
// ─────────────────────────────────────────────────────────────────────────────

/**
 * @param string   $orderby Orderby clause.
 * @param WP_Query $q       Query object.
 * @return string
 */
function elevator_search_custom_orderby($orderby, $q)
{
    global $wpdb;

    if (
        ! is_admin() &&
        $q->is_search() &&
        $q->is_main_query() &&
        (
            $q->get('post_type') === 'product' ||
            (is_array($q->get('post_type')) && in_array('product', (array) $q->get('post_type'), true))
        )
    ) {
        $orderby = "
            (SELECT meta_value FROM {$wpdb->postmeta}
             WHERE post_id = {$wpdb->posts}.ID
             AND meta_key = 'high_priority_search' LIMIT 1)+0 DESC,
            {$wpdb->posts}.post_title ASC
        ";
    }

    return $orderby;
}
add_filter('posts_orderby', 'elevator_search_custom_orderby', 10, 2);

/*
 * ─────────────────────────────────────────────────────────────────────────────
 * RECOMMENDED: Add a database index for oem_number searches.
 *
 * The admin OEM search queries: WHERE meta_key = 'oem_number' AND meta_value LIKE '%x%'
 * MySQL cannot use a standard index for leading-wildcard LIKE ('%x%'), but an
 * index on meta_key alone will dramatically reduce the rows scanned.
 *
 * If not already present, run this once in phpMyAdmin or WP-CLI:
 *
 *   ALTER TABLE wp_postmeta ADD INDEX oem_number_lookup (meta_key(32));
 *
 * Or with WP-CLI:
 *   wp db query "ALTER TABLE $(wp db prefix)postmeta ADD INDEX oem_number_lookup (meta_key(32));"
 *
 * WordPress/WooCommerce already adds an index on meta_key by default in most
 * installations — verify with: SHOW INDEX FROM wp_postmeta;
 * ─────────────────────────────────────────────────────────────────────────────
 */