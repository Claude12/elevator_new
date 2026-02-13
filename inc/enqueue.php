<?php
/**
 * Enqueue scripts and styles
 *
 * @package elevator
 */

/**
 * Enqueue scripts and styles.
 */
function elevator_scripts() {
	// Enqueue Bootstrap CSS.
	wp_enqueue_style(
		'elevator-bootstrap',
		get_template_directory_uri() . '/assets/css/bootstrap.min.css',
		array(),
		_S_VERSION
	);

	// Enqueue Font Awesome.
	wp_enqueue_style(
		'elevator-font-awesome',
		get_template_directory_uri() . '/assets/css/faall.min.css',
		array(),
		_S_VERSION
	);

	// Enqueue Swiper CSS.
	wp_enqueue_style(
		'elevator-swiper',
		get_template_directory_uri() . '/assets/css/swiper-bundle.min.css',
		array(),
		_S_VERSION
	);

	// Enqueue main CSS with filemtime cache-busting.
	$main_css_path    = get_template_directory() . '/assets/css/main.css';
	$main_css_version = file_exists( $main_css_path ) ? filemtime( $main_css_path ) : _S_VERSION;
	wp_enqueue_style(
		'elevator-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$main_css_version
	);

	// Enqueue theme stylesheet.
	wp_enqueue_style( 'elevator-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'elevator-style', 'rtl', 'replace' );

	// Enqueue Bootstrap JS.
	wp_enqueue_script(
		'elevator-bootstrap',
		get_template_directory_uri() . '/assets/js/bootstrap.min.js',
		array(),
		_S_VERSION,
		true
	);

	// Enqueue toggle navigation JS.
	wp_enqueue_script(
		'elevator-toggle-navigation',
		get_template_directory_uri() . '/assets/js/toggle-navigation.js',
		array(),
		_S_VERSION,
		true
	);

	// Enqueue Swiper JS.
	wp_enqueue_script(
		'elevator-swiper',
		get_template_directory_uri() . '/assets/js/swiper-bundle.min.js',
		array(),
		_S_VERSION,
		true
	);

	// Enqueue main JS with jQuery dependency and filemtime cache-busting.
	$main_js_path    = get_template_directory() . '/assets/js/main.js';
	$main_js_version = file_exists( $main_js_path ) ? filemtime( $main_js_path ) : _S_VERSION;
	wp_enqueue_script(
		'elevator-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery' ),
		$main_js_version,
		true
	);

	// Enqueue navigation script.
	wp_enqueue_script(
		'elevator-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array(),
		_S_VERSION,
		true
	);

	// Localize script for AJAX on WooCommerce account/product pages.
	if ( class_exists( 'WooCommerce' ) && ( is_account_page() || is_product() ) ) {
		wp_localize_script(
			'elevator-main',
			'elevator_ajax',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	// Enqueue comment reply script on singular posts/pages with comments.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'elevator_scripts' );
