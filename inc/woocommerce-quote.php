<?php
/**
 * WooCommerce Quote Page Customizations
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output shared CSS for continue-shopping buttons (only once).
 */
function elevator_output_quote_button_styles() {
	static $styles_printed = false;

	if ( $styles_printed ) {
		return;
	}

	$styles_printed = true;
	?>
	<style>
	.continue-shopping {
		background-color: #FFF !important;
		color: #154ed3 !important;
		padding: 0 !important;
		text-decoration: none;
		border-radius: 4px;
	}
	.continue-shopping:hover {
		color: #154ed3 !important;
		text-decoration: underline !important;
	}
	.continue-shopping-back {
		margin: 10px !important;
	}
	.continue-shopping-terms {
		margin: 0px 20px 0 0 !important;
	}
	</style>
	<?php
}

/**
 * Add "Continue Shopping" button before Addify's Update Quote button.
 */
function elevator_add_continue_shopping_before_update_quote() {
	if ( ! is_page( 'request-a-quote' ) ) {
		return;
	}

	elevator_output_quote_button_styles();
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		var updateButton = $('.afrfq_update_quote_btn');
		if (updateButton.length) {
			var continueButton = $('<a/>', {
				text: '<?php esc_html_e( 'Continue Shopping', 'elevator' ); ?>',
				href: '<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>',
				class: 'button continue-shopping continue-shopping-back'
			});
			updateButton.before(continueButton);
		}
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'elevator_add_continue_shopping_before_update_quote' );

/**
 * Add "Terms and Conditions" link before Addify's Place Quote button.
 */
function elevator_terms_link_update_quote() {
	if ( ! is_page( 'request-a-quote' ) ) {
		return;
	}

	elevator_output_quote_button_styles();
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		var updateButton = $('.addify_checkout_place_quote');
		if (updateButton.length) {
			var continueButton = $('<a/>', {
				text: '<?php esc_html_e( 'Terms and Conditions', 'elevator' ); ?>',
				href: '<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>',
				class: 'button continue-shopping continue-shopping-terms'
			});
			updateButton.before(continueButton);
		}
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'elevator_terms_link_update_quote' );

