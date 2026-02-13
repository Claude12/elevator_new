<?php
/**
 * Admin new order email
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\HTML
 * @version 10.4.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php
echo $email_improvements_enabled ? '<div class="email-introduction">' : '';
/* translators: %s: Customer billing full name */
$text = __( 'You’ve received the following order from %s:', 'woocommerce' );
if ( $email_improvements_enabled ) {
    $text = __( 'You’ve received a new order from %s:', 'woocommerce' );
}
?>
<p><?php printf( esc_html( $text ), esc_html( $order->get_formatted_billing_full_name() ) ); ?></p>

<?php
// === Custom addition: Show company logo from ACF (image array) ===
$customer_id  = $order->get_customer_id();
$company_logo = get_field( 'company_logo', 'user_' . $customer_id );

if ( $company_logo && is_array( $company_logo ) ) {
    $logo_url = $company_logo['url'];
    $logo_alt = ! empty( $company_logo['alt'] ) ? $company_logo['alt'] : 'Company Logo';

    echo '<p><img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $logo_alt ) . '" style="max-width:150px; height:auto;" /></p>';
}
?>

<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
    echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation"><tr><td class="email-additional-content">' : '';
    echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
    echo $email_improvements_enabled ? '</td></tr></table>' : '';
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
