<?php
/**
 * WooCommerce Guest Restrictions
 *
 * Hide prices and add-to-cart buttons for logged-out users.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Replace price with login link for logged-out users.
 *
 * @param string     $price   Price HTML.
 * @param WC_Product $product Product object.
 * @return string Modified price HTML or login link.
 */
function elevator_guest_replace_price_with_login_link( $price, $product ) {
	if ( ! is_user_logged_in() ) {
		$login_url = wc_get_page_permalink( 'myaccount' );
		return sprintf(
			'<a href="%s" class="login-to-see-price">%s</a>',
			esc_url( $login_url ),
			esc_html__( 'Login to see prices', 'elevator' )
		);
	}
	return $price;
}
add_filter( 'woocommerce_get_price_html', 'elevator_guest_replace_price_with_login_link', 10, 2 );

/**
 * Remove add-to-cart buttons for logged-out users.
 */
function elevator_guest_remove_add_to_cart_buttons() {
	if ( ! is_user_logged_in() ) {
		// Remove add-to-cart button from shop/archive pages.
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

		// Remove add-to-cart button from single product pages.
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	}
}
add_action( 'wp', 'elevator_guest_remove_add_to_cart_buttons' );

/**
 * Add login notice on single product pages for logged-out users.
 */
function elevator_guest_add_login_notice_on_single_product() {
	if ( ! is_user_logged_in() && is_product() ) {
		$login_url = wc_get_page_permalink( 'myaccount' );
		?>
		<div class="woocommerce-info">
			<?php
			printf(
				/* translators: %s: login URL */
				wp_kses_post( __( 'Please <a href="%s">log in</a> to purchase this product.', 'elevator' ) ),
				esc_url( $login_url )
			);
			?>
		</div>
		<?php
	}
}
add_action( 'woocommerce_single_product_summary', 'elevator_guest_add_login_notice_on_single_product', 31 );

/**
 * Hide "Add to Quote" button for logged-out users (Addify B2B plugin).
 */
function elevator_guest_hide_add_to_quote_button() {
	if ( ! is_user_logged_in() && wp_style_is( 'elevator-style', 'enqueued' ) ) {
		$custom_css = '.afpq-add-to-quote-button, .afpq-quote-button { display: none !important; }';
		wp_add_inline_style( 'elevator-style', $custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'elevator_guest_hide_add_to_quote_button', 20 );
