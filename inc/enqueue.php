<?php
/**
 * Enqueue scripts and styles.
 *
 * @package elevator
 */

/**
 * Enqueue scripts and styles.
 */
function elevator_scripts() {

	// --- Vendor CSS (CDN) ---

	// Bootstrap 5.3 CSS.
	wp_enqueue_style(
		'elevator-bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
		array(),
		'5.3.3'
	);

	// Swiper CSS.
	wp_enqueue_style(
		'elevator-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		array(),
		'11.0.0'
	);

	// --- Custom CSS ---

	// Main custom CSS with filemtime cache-busting.
	$main_css_path    = get_template_directory() . '/assets/css/main.css';
	$main_css_version = file_exists( $main_css_path ) ? filemtime( $main_css_path ) : _S_VERSION;
	wp_enqueue_style(
		'elevator-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'elevator-bootstrap' ),
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

	// Bootstrap 5.3 JS (bundle includes Popper).
	wp_enqueue_script(
		'elevator-bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.3',
		true
	);

	// Swiper JS.
	wp_enqueue_script(
		'elevator-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		array(),
		'11.0.0',
		true
	);

	// --- Custom JS ---

	// Toggle navigation (Bootstrap responsive nav helper).
	$toggle_nav_path = get_template_directory() . '/assets/js/toggle-navigation.js';
	if ( file_exists( $toggle_nav_path ) ) {
		wp_enqueue_script(
			'elevator-toggle-navigation',
			get_template_directory_uri() . '/assets/js/toggle-navigation.js',
			array( 'elevator-bootstrap' ),
			filemtime( $toggle_nav_path ),
			true
		);
	}

	// Main JS with jQuery dependency and filemtime cache-busting.
	$main_js_path    = get_template_directory() . '/assets/js/main.js';
	$main_js_version = file_exists( $main_js_path ) ? filemtime( $main_js_path ) : _S_VERSION;
	wp_enqueue_script(
		'elevator-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery', 'elevator-bootstrap' ),
		$main_js_version,
		true
	);

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
			'elevator-main',
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