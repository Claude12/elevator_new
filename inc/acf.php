<?php
/**
 * ACF Options Page
 *
 * @package elevator
 */

/**
 * Register ACF Options Page.
 */
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page(
		array(
			'page_title' => 'Theme General Settings',
			'menu_title' => 'Theme Settings',
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
		)
	);
}
