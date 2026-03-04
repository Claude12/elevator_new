<?php

/**
 * WooCommerce Email Customizations
 *
 * @package elevator
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Rename "Billing address" → "Invoice address" and "Shipping address" → "Delivery address".
 *
 * @param string $translated_text Translated text.
 * @param string $text            Original text.
 * @param string $domain          Text domain.
 * @return string Modified text.
 */
function elevator_custom_woocommerce_address_labels($translated_text, $text, $domain)
{
	// Early return if not WooCommerce domain.
	if ('woocommerce' !== $domain) {
		return $translated_text;
	}

	if ($text === 'Billing address') {
		$translated_text = __('Invoice address', 'elevator');
	}
	if ($text === 'Branch address') {
		$translated_text = __('Delivery address', 'elevator');
	}

	return $translated_text;
}

/**
 * Conditionally hook gettext filter only on WooCommerce pages for performance.
 */
function elevator_conditionally_hook_gettext_filters()
{
	// Only hook on WooCommerce-specific pages.
	if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
		add_filter('gettext', 'elevator_custom_woocommerce_address_labels', 20, 3);
	}
}
add_action('template_redirect', 'elevator_conditionally_hook_gettext_filters');

/**
 * Override email heading to "ORDER ACKNOWLEDGMENT" for all customer-facing order emails.
 *
 * @return string
 */
function elevator_email_heading_order_acknowledgment() {
	return __( 'ORDER ACKNOWLEDGMENT', 'elevator' );
}

$elevator_order_email_heading_filters = array(
	'woocommerce_email_heading_order_received',
	'woocommerce_email_heading_customer_processing_order',
	'woocommerce_email_heading_customer_completed_order',
	'woocommerce_email_heading_customer_on_hold_order',
);
foreach ( $elevator_order_email_heading_filters as $filter ) {
	add_filter( $filter, 'elevator_email_heading_order_acknowledgment', 10, 3 );
}
