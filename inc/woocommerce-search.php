<?php
/**
 * WooCommerce Search Enhancements
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * FRONTEND: WooCommerce product data store search: include SKU, brand_name, and oem_number meta.
 *
 * Searches the following fields:
 * - _sku (WooCommerce SKU)
 * - brand_name (Brand meta field)
 * - oem_number (ACF field under "Supplier Information – Products" group)
 *
 * @param string $sql       Search SQL.
 * @param string $search    Search term.
 * @param string $sql_where SQL WHERE clause.
 * @return string Modified search SQL.
 */
function elevator_woocommerce_search_meta( $sql, $search, $sql_where ) {
    global $wpdb;

    if ( ! empty( $search ) ) {
        $like = '%' . $wpdb->esc_like( $search ) . '%';

        $sku_sql   = $wpdb->prepare( ' OR sku_meta.meta_value LIKE %s', $like );
        $brand_sql = $wpdb->prepare( ' OR brand_meta.meta_value LIKE %s', $like );
        $oem_sql   = $wpdb->prepare( ' OR oem_meta.meta_value LIKE %s', $like );

        $sql .= $sku_sql . $brand_sql . $oem_sql;

        add_filter(
            'posts_join',
            function( $join_sql ) use ( $wpdb ) {
                if ( strpos( $join_sql, 'sku_meta' ) === false ) {
                    $join_sql .= " LEFT JOIN {$wpdb->postmeta} AS sku_meta
                                   ON ({$wpdb->posts}.ID = sku_meta.post_id AND sku_meta.meta_key = '_sku')";
                }
                if ( strpos( $join_sql, 'brand_meta' ) === false ) {
                    $join_sql .= " LEFT JOIN {$wpdb->postmeta} AS brand_meta
                                   ON ({$wpdb->posts}.ID = brand_meta.post_id AND brand_meta.meta_key = 'brand_name')";
                }
                if ( strpos( $join_sql, 'oem_meta' ) === false ) {
                    $join_sql .= " LEFT JOIN {$wpdb->postmeta} AS oem_meta
                                   ON ({$wpdb->posts}.ID = oem_meta.post_id AND oem_meta.meta_key = 'oem_number')";
                }
                return $join_sql;
            }
        );
    }

    return $sql;
}
add_filter( 'woocommerce_product_data_store_cpt_get_search_sql', 'elevator_woocommerce_search_meta', 10, 3 );

/**
 * ADMIN: Join postmeta once for oem_number when searching (product list search).
 */
add_filter( 'posts_join', function( $join, $q ) {
    if ( is_admin() && $q->is_main_query() && $q->is_search() ) {
        global $wpdb;

        if ( strpos( $join, 'oem_meta' ) === false ) {
            $join .= " LEFT JOIN {$wpdb->postmeta} AS oem_meta
                       ON ({$wpdb->posts}.ID = oem_meta.post_id
                       AND oem_meta.meta_key = 'oem_number')";
        }
    }
    return $join;
}, 10, 2 );

/**
 * ADMIN: Add OEM match into the core search WHERE.
 *
 * Makes search behave like:
 * AND ( core_title/content_match OR oem_meta.meta_value LIKE '%s%' )
 */
add_filter( 'posts_search', function( $search, \WP_Query $q ) {
    if ( ! is_admin() || ! $q->is_main_query() || ! $q->is_search() ) {
        return $search;
    }

    $s = $q->get( 's' );
    if ( $s === '' || $s === null ) {
        return $search;
    }

    global $wpdb;
    $like = '%' . $wpdb->esc_like( $s ) . '%';

    // $search usually looks like: " AND (( ...core LIKEs... ))"
    // Inject " OR (oem_meta.meta_value LIKE '%...%')" before the last closing parenthesis.
    if ( $search && strpos( $search, ')' ) !== false ) {
        $search = preg_replace(
            '/\)\s*$/',
            $wpdb->prepare( ' OR (oem_meta.meta_value LIKE %s))', $like ),
            $search,
            1
        );
    }

    return $search;
}, 10, 2 );

/**
 * ADMIN: Avoid duplicate rows due to extra LEFT JOINs.
 *
 * @param string   $distinct Distinct clause.
 * @param WP_Query $q        Query object.
 * @return string Modified distinct clause.
 */
function elevator_search_posts_distinct( $distinct, $q ) {
    if ( is_admin() && $q->is_main_query() && $q->is_search() ) {
        return 'DISTINCT';
    }
    return $distinct;
}
add_filter( 'posts_distinct', 'elevator_search_posts_distinct', 10, 2 );

/**
 * FRONTEND: Custom order: High Priority > Title.
 *
 * @param string   $orderby Orderby clause.
 * @param WP_Query $q       Query object.
 * @return string Modified orderby clause.
 */
function elevator_search_custom_orderby( $orderby, $q ) {
    global $wpdb;

    if (
        ! is_admin() &&
        $q->is_search() &&
        $q->is_main_query() &&
        (
            $q->get( 'post_type' ) === 'product' ||
            ( is_array( $q->get( 'post_type' ) ) && in_array( 'product', (array) $q->get( 'post_type' ), true ) )
        )
    ) {
        $orderby = "
            (SELECT meta_value FROM {$wpdb->postmeta}
             WHERE post_id = {$wpdb->posts}.ID
             AND meta_key = 'high_priority_search')+0 DESC,
            {$wpdb->posts}.post_title ASC
        ";
    }

    return $orderby;
}
add_filter( 'posts_orderby', 'elevator_search_custom_orderby', 10, 2 );

/**
 * ADMIN: Product list relevance ordering (title > startswith > contains > SKU).
 *
 * @param string   $orderby Orderby clause.
 * @param WP_Query $q       Query object.
 * @return string Modified orderby clause.
 */
function elevator_admin_search_orderby( $orderby, $q ) {
    if ( ! is_admin() || ! $q->is_main_query() || ! $q->is_search() ) {
        return $orderby;
    }

    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || $screen->id !== 'edit-product' ) {
        return $orderby;
    }

    global $wpdb;
    $s = trim( (string) $q->get( 's' ) );
    if ( $s === '' ) {
        return $orderby;
    }

    $like       = '%' . $wpdb->esc_like( $s ) . '%';
    $like_start = $wpdb->esc_like( $s ) . '%';

    // Ensure SKU join exists.
    add_filter(
        'posts_join',
        function( $join ) use ( $wpdb ) {
            if ( strpos( $join, 'sku_meta' ) === false ) {
                $join .= " LEFT JOIN {$wpdb->postmeta} AS sku_meta
                           ON ({$wpdb->posts}.ID = sku_meta.post_id AND sku_meta.meta_key = '_sku')";
            }
            return $join;
        },
        20
    );

    // Ensure DISTINCT.
    add_filter(
        'posts_distinct',
        function( $distinct ) {
            return 'DISTINCT';
        },
        20
    );

    $orderby = $wpdb->prepare(
        " (CASE
            WHEN {$wpdb->posts}.post_title = %s THEN 400
            WHEN {$wpdb->posts}.post_title LIKE %s THEN 300
            WHEN {$wpdb->posts}.post_title LIKE %s THEN 200
            WHEN sku_meta.meta_value LIKE %s THEN 100
            ELSE 0
          END) DESC,
          {$wpdb->posts}.post_title ASC ",
        $s,
        $like_start,
        $like,
        $like
    );

    return $orderby;
}
add_filter( 'posts_orderby', 'elevator_admin_search_orderby', 10, 2 );
