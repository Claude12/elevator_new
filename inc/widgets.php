<?php
/**
 * Register widget areas / sidebars.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function elevator_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Primary Sidebar', 'elevator' ),
			'id'            => 'primary-sidebar',
			'description'   => esc_html__( 'Main sidebar that appears on the right.', 'elevator' ),
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'News Sidebar', 'elevator' ),
			'id'            => 'news-sidebar',
			'description'   => esc_html__( 'Sidebar displayed on news/blog pages.', 'elevator' ),
			'before_widget' => '<div class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'elevator_widgets_init' );