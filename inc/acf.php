<?php
/**
 * ACF Options Page
 *
 * @package elevator
 */

/**
 * Register ACF Options Page.
 *
 * Uses the 'acf/init' hook which fires after ACF is fully loaded,
 * preventing the _load_textdomain_just_in_time notice.
 */
function elevator_acf_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title' => __( 'Theme General Settings', 'elevator' ),
				'menu_title' => __( 'Theme Settings', 'elevator' ),
				'menu_slug'  => 'theme-general-settings',
				'capability' => 'manage_options',
				'redirect'   => false,
			)
		);
	}
}
add_action( 'acf/init', 'elevator_acf_options_page' );