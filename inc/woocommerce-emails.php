<?php
/**
 * WooCommerce Email Customizations
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
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
function elevator_custom_woocommerce_address_labels( $translated_text, $text, $domain ) {
	if ( 'woocommerce' === $domain ) {
		if ( $text === 'Billing address' ) {
			$translated_text = __( 'Invoice address', 'elevator' );
		}
		if ( $text === 'Shipping address' ) {
			$translated_text = __( 'Delivery address', 'elevator' );
		}
	}
	return $translated_text;
}
add_filter( 'gettext', 'elevator_custom_woocommerce_address_labels', 20, 3 );

/**
 * Override email heading for order received email.
 *
 * @param string   $heading Email heading.
 * @param WC_Order $order   Order object.
 * @param WC_Email $email   Email object.
 * @return string Modified heading.
 */
function elevator_email_heading_order_received( $heading, $order, $email ) {
	return __( 'ORDER ACKNOWLEDGMENT', 'elevator' );
}
add_filter( 'woocommerce_email_heading_order_received', 'elevator_email_heading_order_received', 10, 3 );

/**
 * Override email heading for customer processing order email.
 *
 * @param string   $heading Email heading.
 * @param WC_Order $order   Order object.
 * @param WC_Email $email   Email object.
 * @return string Modified heading.
 */
function elevator_email_heading_customer_processing_order( $heading, $order, $email ) {
	return __( 'ORDER ACKNOWLEDGMENT', 'elevator' );
}
add_filter( 'woocommerce_email_heading_customer_processing_order', 'elevator_email_heading_customer_processing_order', 10, 3 );

/**
 * Override email heading for customer completed order email.
 *
 * @param string   $heading Email heading.
 * @param WC_Order $order   Order object.
 * @param WC_Email $email   Email object.
 * @return string Modified heading.
 */
function elevator_email_heading_customer_completed_order( $heading, $order, $email ) {
	return __( 'ORDER ACKNOWLEDGMENT', 'elevator' );
}
add_filter( 'woocommerce_email_heading_customer_completed_order', 'elevator_email_heading_customer_completed_order', 10, 3 );

/**
 * Override email heading for customer on-hold order email.
 *
 * @param string   $heading Email heading.
 * @param WC_Order $order   Order object.
 * @param WC_Email $email   Email object.
 * @return string Modified heading.
 */
function elevator_email_heading_customer_on_hold_order( $heading, $order, $email ) {
	return __( 'ORDER ACKNOWLEDGMENT', 'elevator' );
}
add_filter( 'woocommerce_email_heading_customer_on_hold_order', 'elevator_email_heading_customer_on_hold_order', 10, 3 );
