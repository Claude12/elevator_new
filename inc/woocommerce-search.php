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
 * Front-end product search: include taxonomies using posts_join and posts_where.
 *
 * @param WP_Query $q Query object.
 */
function elevator_search_include_taxonomies( $q ) {
	if ( is_admin() || ! $q->is_main_query() || ! $q->is_search() ) {
		return;
	}

	// Only affect product searches.
	$pt = $q->get( 'post_type' );
	if ( ! ( $pt === 'product' || ( is_array( $pt ) && in_array( 'product', (array) $pt, true ) ) ) ) {
		return;
	}

	$term = trim( (string) $q->get( 's' ) );
	if ( $term === '' ) {
		return;
	}

	// Store the search term for use in filters.
	$q->set( 'elevator_tax_search_term', $term );
}
add_action( 'pre_get_posts', 'elevator_search_include_taxonomies', 9 );

/**
 * Join taxonomy tables for product search.
 *
 * @param string   $join Join clause.
 * @param WP_Query $q    Query object.
 * @return string Modified join clause.
 */
function elevator_search_taxonomy_join( $join, $q ) {
	if (
		! $q->is_main_query() ||
		! $q->is_search() ||
		! ( $q->get( 'post_type' ) === 'product' ||
			( is_array( $q->get( 'post_type' ) ) && in_array( 'product', (array) $q->get( 'post_type' ), true ) ) ) ||
		! $q->get( 'elevator_tax_search_term' )
	) {
		return $join;
	}

	global $wpdb;

	// Add taxonomy joins only if not already present.
	if ( strpos( $join, 'etr_tax_search' ) === false ) {
		$join .= " LEFT JOIN {$wpdb->term_relationships} AS etr_tax_search ON ({$wpdb->posts}.ID = etr_tax_search.object_id)";
		$join .= " LEFT JOIN {$wpdb->term_taxonomy} AS ett_tax_search ON (etr_tax_search.term_taxonomy_id = ett_tax_search.term_taxonomy_id AND ett_tax_search.taxonomy IN ('product_cat','product_tag','product_brand'))";
		$join .= " LEFT JOIN {$wpdb->terms} AS et_tax_search ON (ett_tax_search.term_id = et_tax_search.term_id)";
	}

	return $join;
}
add_filter( 'posts_join', 'elevator_search_taxonomy_join', 10, 2 );

/**
 * Add taxonomy name match to WHERE clause for product search.
 *
 * @param string   $where Where clause.
 * @param WP_Query $q     Query object.
 * @return string Modified where clause.
 */
function elevator_search_taxonomy_where( $where, $q ) {
	if (
		! $q->is_main_query() ||
		! $q->is_search() ||
		! ( $q->get( 'post_type' ) === 'product' ||
			( is_array( $q->get( 'post_type' ) ) && in_array( 'product', (array) $q->get( 'post_type' ), true ) ) ) ||
		! $q->get( 'elevator_tax_search_term' )
	) {
		return $where;
	}

	global $wpdb;
	$term = $q->get( 'elevator_tax_search_term' );
	$like = '%' . $wpdb->esc_like( $term ) . '%';

	// Inject OR condition for taxonomy name match before the last closing parenthesis.
	if ( $where && strpos( $where, ')' ) !== false ) {
		$where = preg_replace(
			'/\)\s*$/',
			$wpdb->prepare( ' OR et_tax_search.name LIKE %s)', $like ),
			$where,
			1
		);
	}

	return $where;
}
add_filter( 'posts_where', 'elevator_search_taxonomy_where', 10, 2 );

/**
 * WooCommerce product data store search: include SKU and brand_name meta.
 *
 * @param string $sql    Search SQL.
 * @param string $search Search term.
 * @param string $sql_where SQL WHERE clause.
 * @return string Modified search SQL.
 */
function elevator_woocommerce_search_meta( $sql, $search, $sql_where ) {
	global $wpdb;

	if ( ! empty( $search ) ) {
		// Extra conditions for SKU and brand_name.
		$sku_sql   = $wpdb->prepare( ' OR sku_meta.meta_value LIKE %s', '%' . $wpdb->esc_like( $search ) . '%' );
		$brand_sql = $wpdb->prepare( ' OR brand_meta.meta_value LIKE %s', '%' . $wpdb->esc_like( $search ) . '%' );

		// Append conditions to WooCommerce search SQL.
		$sql .= $sku_sql . $brand_sql;

		// Join meta tables for SKU and brand_name.
		add_filter(
			'posts_join',
			function( $join_sql ) use ( $wpdb ) {
				if ( strpos( $join_sql, 'sku_meta' ) === false ) {
					$join_sql .= " LEFT JOIN {$wpdb->postmeta} AS sku_meta ON ({$wpdb->posts}.ID = sku_meta.post_id AND sku_meta.meta_key = '_sku')";
				}
				if ( strpos( $join_sql, 'brand_meta' ) === false ) {
					$join_sql .= " LEFT JOIN {$wpdb->postmeta} AS brand_meta ON ({$wpdb->posts}.ID = brand_meta.post_id AND brand_meta.meta_key = 'brand_name')";
				}
				return $join_sql;
			}
		);
	}

	return $sql;
}
add_filter( 'woocommerce_product_data_store_cpt_get_search_sql', 'elevator_woocommerce_search_meta', 10, 3 );

/**
 * Join postmeta for oem_number when searching products.
 *
 * @param string   $join Join clause.
 * @param WP_Query $q    Query object.
 * @return string Modified join clause.
 */
function elevator_search_join_oem_meta( $join, $q ) {
	if (
		$q->is_main_query() &&
		$q->is_search() &&
		(
			$q->get( 'post_type' ) === 'product' ||
			( is_array( $q->get( 'post_type' ) ) && in_array( 'product', (array) $q->get( 'post_type' ), true ) )
		)
	) {
		global $wpdb;
		// Add only if not already joined.
		if ( strpos( $join, 'oem_meta' ) === false ) {
			$join .= " LEFT JOIN {$wpdb->postmeta} AS oem_meta
					   ON ({$wpdb->posts}.ID = oem_meta.post_id
					   AND oem_meta.meta_key = 'oem_number')";
		}
	}
	return $join;
}
add_filter( 'posts_join', 'elevator_search_join_oem_meta', 10, 2 );

/**
 * Add OEM match into core search WHERE clause.
 *
 * @param string   $search Search clause.
 * @param WP_Query $q      Query object.
 * @return string Modified search clause.
 */
function elevator_search_include_oem( $search, $q ) {
	if (
		! $q->is_main_query() ||
		! $q->is_search() ||
		! ( $q->get( 'post_type' ) === 'product' ||
			( is_array( $q->get( 'post_type' ) ) && in_array( 'product', (array) $q->get( 'post_type' ), true ) ) )
	) {
		return $search;
	}

	$s = $q->get( 's' );
	if ( $s === '' || $s === null ) {
		return $search;
	}

	global $wpdb;
	$like = '%' . $wpdb->esc_like( $s ) . '%';

	// Inject OR (oem_meta.meta_value LIKE '%...%') before the last closing parenthesis.
	if ( $search && strpos( $search, ')' ) !== false ) {
		$search = preg_replace(
			'/\)\s*$/',
			$wpdb->prepare( ' OR (oem_meta.meta_value LIKE %s))', $like ),
			$search,
			1
		);
	}

	return $search;
}
add_filter( 'posts_search', 'elevator_search_include_oem', 10, 2 );

/**
 * Avoid duplicate rows due to extra LEFT JOINs.
 *
 * @param string   $distinct Distinct clause.
 * @param WP_Query $q        Query object.
 * @return string Modified distinct clause.
 */
function elevator_search_posts_distinct( $distinct, $q ) {
	if (
		$q->is_main_query() &&
		$q->is_search() &&
		( $q->get( 'post_type' ) === 'product' ||
			( is_array( $q->get( 'post_type' ) ) && in_array( 'product', (array) $q->get( 'post_type' ), true ) ) )
	) {
		return 'DISTINCT';
	}
	return $distinct;
}
add_filter( 'posts_distinct', 'elevator_search_posts_distinct', 10, 2 );

/**
 * Custom order: High Priority > Total Sales > SKU > Title.
 *
 * @param string   $orderby Orderby clause.
 * @param WP_Query $q       Query object.
 * @return string Modified orderby clause.
 */
function elevator_search_custom_orderby( $orderby, $q ) {
	global $wpdb;

	if ( $q->is_search() && $q->is_main_query() && is_post_type_archive( 'product' ) ) {
		$orderby = "
			(SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'high_priority_search')+0 DESC,
			(SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'total_sales')+0 DESC,
			(SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = '_sku') ASC,
			{$wpdb->posts}.post_title ASC
		";
	}

	return $orderby;
}
add_filter( 'posts_orderby', 'elevator_search_custom_orderby', 10, 2 );

/**
 * Admin product list: order search results by best title match, then SKU, then title ASC.
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

	// Safely build LIKEs.
	$like      = '%' . $wpdb->esc_like( $s ) . '%';
	$like_start = $wpdb->esc_like( $s ) . '%';

	// Join _sku only for this query.
	add_filter(
		'posts_join',
		function( $join ) use ( $wpdb ) {
			if ( strpos( $join, 'sku_meta' ) === false ) {
				$join .= " LEFT JOIN {$wpdb->postmeta} AS sku_meta
						   ON ({$wpdb->posts}.ID = sku_meta.post_id AND sku_meta.meta_key = '_sku')";
			}
			return $join;
		}
	);

	// DISTINCT just in case.
	add_filter(
		'posts_distinct',
		function( $distinct ) {
			return 'DISTINCT';
		}
	);

	// Relevance: exact title > startswith title > contains title > SKU contains.
	$orderby = $wpdb->prepare(
		" (CASE
			WHEN {$wpdb->posts}.post_title = %s THEN 400
			WHEN {$wpdb->posts}.post_title LIKE %s THEN 300
			WHEN {$wpdb->posts}.post_title LIKE %s THEN 200
			WHEN sku_meta.meta_value LIKE %s THEN 100
			ELSE 0
		  END) DESC, {$wpdb->posts}.post_title ASC ",
		$s,
		$like_start,
		$like,
		$like
	);

	return $orderby;
}
add_filter( 'posts_orderby', 'elevator_admin_search_orderby', 10, 2 );

/**
 * Boost featured products in search results.
 *
 * @param WP_Query $query Query object.
 */
function elevator_boost_featured_products_in_search( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() && is_woocommerce() ) {
		$query->set(
			'meta_query',
			array(
				'relation' => 'OR',
				array(
					'key'     => '_featured',
					'value'   => 'yes',
					'compare' => '=',
				),
				array(
					'key'     => '_featured',
					'compare' => 'NOT EXISTS',
				),
			)
		);
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'DESC' );
	}
}
add_action( 'pre_get_posts', 'elevator_boost_featured_products_in_search' );
