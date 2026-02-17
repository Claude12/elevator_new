<?php
/**
 * ACF Options Page
 *
 * @package elevator
 */

/**
 * Register ACF Options Page.
 *
 * Uses the 'acf/init' hook which fires after ACF is fully loaded,
 * preventing the _load_textdomain_just_in_time notice.
 */
function elevator_acf_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => __( 'Theme General Settings', 'elevator' ),
				'menu_title' => __( 'Theme Settings', 'elevator' ),
				'menu_slug'  => 'theme-general-settings',
				'capability' => 'manage_options',
				'redirect'   => false,
			)
		);
	}
}
add_action( 'acf/init', 'elevator_acf_options_page' );

/**
 * Dynamically load all product categories into ACF select field.
 *
 * @param array $field Field array.
 * @return array Modified field array.
 */
function elevator_acf_load_product_categories( $field ) {
	$field['choices'] = array();

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		)
	);

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$field['choices'][ $term->slug ] = $term->name;
		}
	}

	return $field;
}
add_filter( 'acf/load_field/name=homepage_product_category', 'elevator_acf_load_product_categories' );

/**
 * Modify main homepage WooCommerce product query based on selected category.
 *
 * @param WP_Query $query Query object.
 */
function elevator_homepage_product_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && is_front_page() ) {
		if ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'product' ) {
			// Get selected product category from ACF.
			$selected_category = get_field( 'homepage_product_category', get_option( 'page_on_front' ) );

			if ( $selected_category ) {
				// If category is selected, filter products by that category.
				$query->set( 'product_cat', $selected_category );
			}

			// Always random.
			$query->set( 'orderby', 'rand' );
			$query->set( 'posts_per_page', 8 );
		}
	}
}
add_action( 'pre_get_posts', 'elevator_homepage_product_query' );