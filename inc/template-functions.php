<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package elevator
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function elevator_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'elevator_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function elevator_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'elevator_pingback_header' );

/**
 * Add custom tooltip script.
 */
function elevator_add_custom_tooltip_script() {
	$script = '
		document.addEventListener("DOMContentLoaded", function() {
			document.querySelectorAll(".tooltip").forEach(function(el) {
				el.addEventListener("mouseenter", function() {
					let tooltipText = el.getAttribute("data-tooltip");
					if (!tooltipText) return;

					// Remove existing tooltips
					document.querySelectorAll(".custom-tooltip").forEach(t => t.remove());

					// Create tooltip element
					let tooltip = document.createElement("div");
					tooltip.className = "custom-tooltip";
					tooltip.textContent = tooltipText;
					document.body.appendChild(tooltip);

					// Get element position
					let rect = el.getBoundingClientRect();
					let scrollTop = window.scrollY || document.documentElement.scrollTop;
					let scrollLeft = window.scrollX || document.documentElement.scrollLeft;

					// Set tooltip position (Above the element)
					let tooltipWidth = tooltip.offsetWidth;
					let tooltipHeight = tooltip.offsetHeight;

					tooltip.style.left = (rect.left + scrollLeft - 50) + "px";
					tooltip.style.top = (rect.top + scrollTop - tooltipHeight - 50) + "px";

					// Adjust if tooltip goes out of viewport
					if (tooltip.getBoundingClientRect().top < 0) {
						tooltip.style.top = (rect.bottom + scrollTop + 20) + "px"; // Move below if necessary
					}

					el.addEventListener("mouseleave", function() {
						tooltip.remove();
					});
				});
			});
		});
	';

	// Use wp_add_inline_script if elevator-main-js is registered.
	if ( wp_script_is( 'elevator-main-js', 'registered' ) ) {
		wp_add_inline_script( 'elevator-main-js', $script );
	} else {
		// Fallback to direct output if script not available.
		echo '<script>' . $script . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_footer', 'elevator_add_custom_tooltip_script' );

/**
 * Allow shortcodes in taxonomy descriptions.
 */
add_filter( 'term_description', 'do_shortcode' );

/**
 * Allow more HTML tags in taxonomy description.
 *
 * Note: This function is hooked to multiple taxonomy edit actions:
 * edited_category, edited_post_tag, edited_product_cat, edited_product_tag.
 * These actions fire after WordPress has already verified the nonce in
 * wp-admin/edit-tags.php, so we only need to check capabilities here.
 *
 * @param int $term_id Term ID.
 */
function elevator_allow_html_in_taxonomy_description( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified by WordPress core in edit-tags.php before taxonomy edit actions fire.
	if ( isset( $_POST['description'] ) ) {
		// Define allowed HTML tags.
		$allowed_html = array(
			'a'      => array(
				'href'  => array(),
				'title' => array(),
			),
			'strong' => array(),
			'em'     => array(),
			'p'      => array(),
			'br'     => array(),
			'ul'     => array(),
			'ol'     => array(),
			'li'     => array(),
			'img'    => array(
				'src'    => array(),
				'alt'    => array(),
				'width'  => array(),
				'height' => array(),
			),
			'iframe' => array(
				'src'             => array(),
				'frameborder'     => array(),
				'width'           => array(),
				'height'          => array(),
				'allowfullscreen' => array(),
			),
		);

		// Sanitize description and save it.
		$description = wp_kses( wp_unslash( $_POST['description'] ), $allowed_html );
		wp_update_term( $term_id, 'category', array( 'description' => $description ) );
	}
}
add_action( 'edited_category', 'elevator_allow_html_in_taxonomy_description' );
add_action( 'edited_post_tag', 'elevator_allow_html_in_taxonomy_description' );
add_action( 'edited_product_cat', 'elevator_allow_html_in_taxonomy_description' );
add_action( 'edited_product_tag', 'elevator_allow_html_in_taxonomy_description' );

/**
 * Set custom excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int Modified length.
 */
function elevator_excerpt_length( $length ) {
	return 17;
}
add_filter( 'excerpt_length', 'elevator_excerpt_length' );
