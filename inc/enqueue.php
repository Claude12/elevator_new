<?php
/**
 * Enqueue scripts and styles.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end scripts and styles.
 */
function elevator_scripts() {

	// --- Vendor CSS (CDN) ---

	wp_enqueue_style(
		'elevator-bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
		array(),
		'5.3.3'
	);

	wp_enqueue_style(
		'elevator-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		array(),
		'11.0.0'
	);

	// --- Custom CSS ---

	// Main custom CSS — file lives at /assets/css/main.css.
	$main_css_path    = get_template_directory() . '/assets/css/main.css';
	$main_css_version = file_exists( $main_css_path ) ? filemtime( $main_css_path ) : _S_VERSION;
	wp_enqueue_style(
		'elevator-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'elevator-bootstrap', 'elevator-swiper' ),
		$main_css_version
	);

	// Theme stylesheet (style.css) — loaded last so it can override everything.
	wp_enqueue_style(
		'elevator-style',
		get_stylesheet_uri(),
		array( 'elevator-main' ),
		_S_VERSION
	);
	wp_style_add_data( 'elevator-style', 'rtl', 'replace' );

	// --- Vendor JS (CDN) ---

	wp_enqueue_script(
		'elevator-bootstrap-js',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.3',
		true
	);

	wp_enqueue_script(
		'elevator-swiper-js',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		array(),
		'11.0.0',
		true
	);

	// --- Custom JS ---

	$main_js_path    = get_template_directory() . '/assets/js/main.js';
	$main_js_version = file_exists( $main_js_path ) ? filemtime( $main_js_path ) : _S_VERSION;
	if ( file_exists( $main_js_path ) ) {
		wp_enqueue_script(
			'elevator-main-js',
			get_template_directory_uri() . '/assets/js/main.js',
			array( 'jquery', 'elevator-bootstrap-js' ),
			$main_js_version,
			true
		);
	}

	// Underscores navigation script.
	$nav_js_path = get_template_directory() . '/js/navigation.js';
	if ( file_exists( $nav_js_path ) ) {
		wp_enqueue_script(
			'elevator-navigation',
			get_template_directory_uri() . '/js/navigation.js',
			array(),
			_S_VERSION,
			true
		);
	}

	// Localize script for AJAX on WooCommerce account/product pages.
	if ( class_exists( 'WooCommerce' ) && ( is_account_page() || is_product() ) ) {
		wp_localize_script(
			'elevator-main-js',
			'elevator_ajax',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'elevator_ajax_nonce' ),
			)
		);
	}

	// Comment reply script.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'elevator_scripts' );