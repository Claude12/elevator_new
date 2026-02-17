<?php
/**
 * Admin Customizations
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hide Addify tabs in product editor.
 */
function elevator_custom_admin_css() {
	echo '<style>
		#woocommerce-product-data .product_data_tabs li.addify_csp_customer_options {
			display: none !important;
		}
		#woocommerce-product-data .product_data_tabs li.addify_csp_role_options {
			display: none !important;
		}
	</style>';
}
add_action( 'admin_head', 'elevator_custom_admin_css' );

/**
 * Normalize dash characters in product titles on save.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an update.
 */
function elevator_normalize_product_title_dashes( $post_id, $post, $update ) {
	if ( $post->post_type !== 'product' ) {
		return;
	}

	// Map of "fancy" dashes → normal hyphen.
	$replacements = array(
		'–' => '-', // en dash.
		'—' => '-', // em dash.
		'−' => '-', // minus sign.
	);

	$original_title   = $post->post_title;
	$normalized_title = strtr( $original_title, $replacements );

	if ( $normalized_title !== $original_title ) {
		// Avoid infinite loop.
		remove_action( 'save_post_product', 'elevator_normalize_product_title_dashes', 10 );

		wp_update_post(
			array(
				'ID'         => $post_id,
				'post_title' => $normalized_title,
			)
		);

		add_action( 'save_post_product', 'elevator_normalize_product_title_dashes', 10, 3 );
	}
}
add_action( 'save_post_product', 'elevator_normalize_product_title_dashes', 10, 3 );
