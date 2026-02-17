<?php
/**
 * WooCommerce Archive and Category Page Customizations
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set number of products per row on shop/archive pages.
 *
 * @param int $columns Number of columns.
 * @return int Modified number of columns.
 */
function elevator_loop_columns( $columns ) {
	return 3;
}
add_filter( 'loop_shop_columns', 'elevator_loop_columns', 999 );

/**
 * Move category description below product list.
 */
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 10 );

/**
 * Remove category page title.
 *
 * @param bool $show_title Whether to show the page title.
 * @return bool Modified show title flag.
 */
function elevator_remove_category_page_title( $show_title ) {
	if ( is_product_category() ) {
		return false;
	}
	return $show_title;
}
add_filter( 'woocommerce_show_page_title', 'elevator_remove_category_page_title' );

/**
 * Remove "Add to Cart" button on category pages.
 */
function elevator_remove_add_to_cart_button_on_category_page() {
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}
}
add_action( 'wp', 'elevator_remove_add_to_cart_button_on_category_page' );
