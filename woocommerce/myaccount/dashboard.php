<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);

// Custom: Show company branding from ACF user fields.
if ( function_exists( 'get_field' ) ) {
	$user_id      = get_current_user_id();
	$company_name = get_field( 'company_name', 'user_' . $user_id );
	$company_logo = get_field( 'company_logo', 'user_' . $user_id );
} else {
	$company_name = false;
	$company_logo = false;
}
?>

<?php if ( $company_logo || $company_name ) : ?>
	<div class="account-branding">
		<?php if ( $company_logo && is_array( $company_logo ) ) : ?>
			<img src="<?php echo esc_url( $company_logo['url'] ); ?>"
				alt="<?php echo esc_attr( $company_name ?: '' ); ?>"
				class="account-branding__logo" width="100" height="100" />
		<?php endif; ?>
		<?php if ( $company_name ) : ?>
			<h2 class="account-branding__name"><?php echo esc_html( $company_name ); ?></h2>
		<?php endif; ?>
	</div>
<?php endif; ?>

<p>
	<?php
	printf(
		/* translators: 1: user display name 2: logout url */
		wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ), $allowed_html ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
		esc_url( wc_logout_url() )
	);
	?>
</p>

<p>
	<?php
	/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
	$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your account details</a>.', 'woocommerce' );
	if ( wc_shipping_enabled() ) {
		/* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
		$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your account details</a>.', 'woocommerce' );
	}
	printf(
		wp_kses( $dashboard_desc, $allowed_html ),
		esc_url( wc_get_endpoint_url( 'orders' ) ),
		esc_url( wc_get_endpoint_url( 'edit-address' ) ),
		esc_url( wc_get_endpoint_url( 'edit-account' ) )
	);
	?>
</p>

<p class="lost_password">
	<?php
	printf(
		/* translators: %s: reset password URL */
		wp_kses( __( 'Need to change your password? <a href="%s">Reset your password</a>.', 'elevator' ), $allowed_html ),
		esc_url( wc_get_endpoint_url( 'edit-account' ) . '#password' )
	);
	?>
</p>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_dashboard' );

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_before_my_account' );

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_after_my_account' );