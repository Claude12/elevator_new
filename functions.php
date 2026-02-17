<?php
/**
 * Elevator theme functions and definitions.
 *
 * This file should only contain require statements.
 * All theme logic belongs in the inc/ directory.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Theme setup — supports, menus, etc.
 */
require get_template_directory() . '/inc/theme-setup.php';

/**
 * Register widget areas / sidebars.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Bootstrap Navwalker.
 */
require get_template_directory() . '/class-wp-bootstrap-navwalker.php';

/**
 * ACF Options Page.
 */
require get_template_directory() . '/inc/acf.php';

/**
 * Shortcodes.
 */
require get_template_directory() . '/inc/shortcodes.php';

/**
 * Admin customizations.
 */
require get_template_directory() . '/inc/admin.php';

/**
 * WooCommerce helpers (product query functions used by modules).
 */
require get_template_directory() . '/inc/woocommerce-helpers.php';

/**
 * WooCommerce compatibility (only if WooCommerce is active).
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
	require get_template_directory() . '/inc/woocommerce-archive.php';
	require get_template_directory() . '/inc/woocommerce-single-product.php';
	require get_template_directory() . '/inc/woocommerce-search.php';
	require get_template_directory() . '/inc/woocommerce-account.php';
	require get_template_directory() . '/inc/woocommerce-continuous-length.php';
	require get_template_directory() . '/inc/woocommerce-emails.php';
	require get_template_directory() . '/inc/woocommerce-quote.php';
	require get_template_directory() . '/inc/woocommerce-guest-restrictions.php';
}

/**
 * Load Jetpack compatibility file (only if Jetpack is active).
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}