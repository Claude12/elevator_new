<?php
/**
 * Email Header
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.4.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );
$store_name                 = $store_name ?? get_bloginfo( 'name', 'display' );

// WooCommerce store address settings
$store_address  = get_option( 'woocommerce_store_address' );
$store_city     = get_option( 'woocommerce_store_city' );
$store_postcode = get_option( 'woocommerce_store_postcode' );
$store_country  = get_option( 'woocommerce_store_country' );

// Store email (falls back to admin email if not set)
$store_email = get_option( 'woocommerce_email_from_address', get_option( 'admin_email' ) );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title><?php echo esc_html( $store_name ); ?></title>
    </head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <table width="100%" id="outer_wrapper" role="presentation">
            <tr>
                <td></td>
                <td width="600">
                    <div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="inner_wrapper" role="presentation">
                            <tr>
                                <td align="center" valign="top">
                                    <?php
                                    $img = get_option( 'woocommerce_email_header_image' );
                                    if ( apply_filters( 'woocommerce_is_email_preview', false ) ) {
                                        $img_transient = get_transient( 'woocommerce_email_header_image' );
                                        $img           = false !== $img_transient ? $img_transient : $img;
                                    }
                                    ?>
                                    <!-- Custom header with logo + store info -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" role="presentation" style="margin-bottom:20px;">
                                        <tr>
                                            <!-- Logo -->
                                            <td style="width:50%; text-align:left; vertical-align:middle;">
                                                <?php
                                                if ( $img ) {
                                                    echo '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $store_name ) . '" style="max-height:80px;" />';
                                                } else {
                                                    echo '<p class="email-logo-text" style="margin:0; font-size:20px; font-weight:bold;">' . esc_html( $store_name ) . '</p>';
                                                }
                                                ?>
                                            </td>
                                            <!-- Store title, address, email -->
                                            <td style="width:50%; text-align:right; vertical-align:middle; font-size:14px; color:#555;">
                                                <p style="margin:0; font-size:18px; font-weight:bold; color:#000;">
                                                    <?php echo esc_html( $store_name ); ?>
                                                </p>
                                                <?php
                                                if ( $store_address ) {
                                                    echo esc_html( $store_address ) . '<br>';
                                                }
                                                if ( $store_city || $store_postcode ) {
                                                    echo esc_html( $store_city ) . ( $store_postcode ? ', ' . esc_html( $store_postcode ) : '' ) . '<br>';
                                                }
                                                if ( $store_country ) {
                                                    echo esc_html( $store_country ) . '<br>';
                                                }
                                                if ( $store_email ) {
                                                    echo '<span style="color:#333;">' . esc_html( $store_email ) . '</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container" role="presentation">
                                        <tr>
                                            <td align="center" valign="top">
                                                <!-- Header -->
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" role="presentation">
                                                    <tr>
                                                        <td id="header_wrapper">
                                                            <h1><?php echo esc_html( $email_heading ); ?></h1>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- End Header -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">
                                                <!-- Body -->
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body" role="presentation">
                                                    <tr>
                                                        <td valign="top" id="body_content">
                                                            <!-- Content -->
                                                            <table border="0" cellpadding="20" cellspacing="0" width="100%" role="presentation">
                                                                <tr>
                                                                    <td valign="top" id="body_content_inner_cell">
                                                                        <div id="body_content_inner">
