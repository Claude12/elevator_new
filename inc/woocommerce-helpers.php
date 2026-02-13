<?php
/**
 * WooCommerce helper functions used by template modules.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get products from a specific WooCommerce category and display them.
 *
 * Used by modules/product-spotlight-feed.php and modules/new-products-feed.php.
 *
 * @param string $category_slug The product category slug.
 * @param bool   $return_array  If true, return an array of HTML strings instead of echoing.
 * @return array Array of product HTML strings (only when $return_array is true).
 */
function get_products_from_category( $category_slug, $return_array = false ) {
	// Bail early if WooCommerce is not active.
	if ( ! class_exists( 'WooCommerce' ) ) {
		return array();
	}

	$args = array(
		'status'   => 'publish',
		'limit'    => 4,
		'orderby'  => 'rand',
		'category' => array( $category_slug ),
	);

	$product_query = new WC_Product_Query( $args );
	$products      = $product_query->get_products();
	$product_items = array();

	foreach ( $products as $product ) {
		$product_html  = '<div class="product col-12 col-sm-6 col-md-6 col-lg-3">';
		$product_html .= '<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">';

		// Display the featured image.
		if ( $product->get_image_id() ) {
			$image_url     = wp_get_attachment_image_src( $product->get_image_id(), 'medium' );
			$product_html .= '<img src="' . esc_url( $image_url[0] ) . '" alt="' . esc_attr( $product->get_name() ) . '">';
		} else {
			$product_html .= '<img src="' . esc_url( wc_placeholder_img_src( 'medium' ) ) . '" alt="' . esc_attr__( 'Placeholder Image', 'elevator' ) . '">';
		}

		$product_html .= '<span class="product-feed-text">';
		$product_html .= '<h2>' . esc_html( $product->get_name() ) . '</h2>';
		$product_html .= '<span class="primary-button">' . esc_html__( 'View Product', 'elevator' ) . '</span>';
		$product_html .= '</span>';
		$product_html .= '</a>';
		$product_html .= '</div>';

		if ( $return_array ) {
			$product_items[] = $product_html;
		} else {
			echo $product_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	return $product_items;
}