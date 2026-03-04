<?php

/**
 * Shortcodes
 *
 * @package elevator
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Divider Shortcode - [divider]
 *
 * @return string HTML output.
 */
function elevator_divider_shortcode()
{
	ob_start();
	get_template_part('modules/devide');
	return ob_get_clean();
}
add_shortcode('divider', 'elevator_divider_shortcode');

/**
 * LinkedIn Profile Shortcode - [linkedin_profile]
 *
 * @return string HTML output.
 */
function elevator_linkedin_profile_shortcode()
{
	static $script_enqueued = false;

	$linkedin_url = get_field('linkedin_url', 'option');

	if (! $linkedin_url) {
		return '<p>' . esc_html__('LinkedIn profile URL not set.', 'elevator') . '</p>';
	}

	// Extract the company username or ID from URL.
	$parsed_url   = wp_parse_url($linkedin_url);
	$path_parts   = explode('/', trim($parsed_url['path'], '/'));
	$company_name = end($path_parts);

	// Enqueue LinkedIn script only once.
	if (! $script_enqueued) {
		wp_enqueue_script(
			'linkedin-platform',
			'https://platform.linkedin.com/in.js',
			array(),
			null,
			array(
				'strategy' => 'async',
				'in_footer' => true,
			)
		);
		$script_enqueued = true;
	}

	return '<script type="IN/FollowCompany" data-id="' . esc_attr($company_name) . '" data-counter="right"></script>';
}
add_shortcode('linkedin_profile', 'elevator_linkedin_profile_shortcode');
