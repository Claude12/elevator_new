<?php
/**
 * PDF Header with Logo - V3 FIXED
 * Displays company logo and store information
 *
 * @package addify-request-a-quote
 * @version 1.6.0
 */

defined( 'ABSPATH' ) || exit;

// Get WooCommerce store logo
$custom_logo_id = get_theme_mod( 'custom_logo' );
$logo_url = '';

if ( $custom_logo_id ) {
	$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
}

// Fallback to WooCommerce email header image
if ( ! $logo_url ) {
	$logo_url = get_option( 'woocommerce_email_header_image' );
}

// Get store info
$store_name = get_bloginfo( 'name' );
$store_description = get_bloginfo( 'description' );
$store_address = get_option( 'woocommerce_store_address' );
$store_address_2 = get_option( 'woocommerce_store_address_2' );
$store_city = get_option( 'woocommerce_store_city' );
$store_postcode = get_option( 'woocommerce_store_postcode' );
$store_state = get_option( 'woocommerce_store_state' );
$store_country = get_option( 'woocommerce_store_country' );
$store_email = get_option( 'woocommerce_email_from_address', get_option( 'admin_email' ) );
$store_phone = get_option( 'woocommerce_store_phone' );

?>
<div>
	<table width="100%" cellspacing="0" cellpadding="10" border="0" style="text-align: left; vertical-align: top;">
		<tbody>
			<tr>
				<!-- Logo Section -->
				<td width="30%" style="text-align: left; vertical-align: top;">
					<?php if ( $logo_url ) : ?>
						<img src="<?php echo esc_url( $logo_url ); ?>" width="120" alt="<?php echo esc_attr( $store_name ); ?>" style="max-height: 100px;">
					<?php else : ?>
						<h3 style="margin: 0; color: #333;"><?php echo esc_html( $store_name ); ?></h3>
					<?php endif; ?>
				</td>

				<!-- Company Info Section -->
				<td width="40%" style="text-align: left; vertical-align: top; padding-left: 20px;">
					<h4 style="margin: 0 0 8px 0; color: #000;"><?php echo esc_html( $store_name ); ?></h4>
					<?php if ( $store_address ) : ?>
						<p style="margin: 4px 0; color: #666; font-size: 11px;">
							<?php echo esc_html( $store_address ); ?>
							<?php if ( $store_address_2 ) echo '<br>' . esc_html( $store_address_2 ); ?>
						</p>
					<?php endif; ?>
					<?php if ( $store_city || $store_postcode ) : ?>
						<p style="margin: 4px 0; color: #666; font-size: 11px;">
							<?php 
							echo esc_html( $store_city );
							if ( $store_state ) echo ', ' . esc_html( $store_state );
							if ( $store_postcode ) echo ' ' . esc_html( $store_postcode );
							?>
						</p>
					<?php endif; ?>
					<?php if ( $store_country ) : ?>
						<p style="margin: 4px 0; color: #666; font-size: 11px;"><?php echo esc_html( $store_country ); ?></p>
					<?php endif; ?>
					<?php if ( $store_email ) : ?>
						<p style="margin: 4px 0; color: #666; font-size: 11px;"><?php echo esc_html( $store_email ); ?></p>
					<?php endif; ?>
					<?php if ( $store_phone ) : ?>
						<p style="margin: 4px 0; color: #666; font-size: 11px;"><?php echo esc_html( $store_phone ); ?></p>
					<?php endif; ?>
				</td>

				<!-- Quote Info Section -->
				<td width="30%" style="text-align: right; vertical-align: top;">
					<h2 style="margin: 0; color: #333; font-size: 24px;">
						<?php echo esc_html__( 'Quotation #', 'addify_b2b' ); ?>
						<br>
						<?php echo esc_html( $quote_id ); ?>
					</h2>
				</td>
			</tr>
		</tbody>
	</table>
	<br>
	<hr style="border: none; border-top: 2px solid #ddd; margin: 20px 0;">
</div>