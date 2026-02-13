<?php
/**
 * Theme setup — supports, menus, image sizes.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function elevator_setup() {

	load_theme_textdomain( 'elevator', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'primary'       => esc_html__( 'Primary Menu', 'elevator' ),
			'top'           => esc_html__( 'Top Menu', 'elevator' ),
			'footer-menu-1' => esc_html__( 'Footer Menu 1', 'elevator' ),
			'footer-menu-2' => esc_html__( 'Footer Menu 2', 'elevator' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'elevator_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'elevator_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function elevator_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'elevator_content_width', 1140 );
}
add_action( 'after_setup_theme', 'elevator_content_width', 0 );