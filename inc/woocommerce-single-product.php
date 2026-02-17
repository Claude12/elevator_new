<?php
/**
 * WooCommerce Single Product Customizations
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add custom product tabs.
 *
 * @param array $tabs Product tabs.
 * @return array Modified tabs.
 */
function elevator_custom_product_tabs( $tabs ) {
	// Technical Downloads tab.
	$tabs['custom_tab_1'] = array(
		'title'    => __( 'Technical Downloads', 'elevator' ),
		'priority' => 50,
		'callback' => 'elevator_custom_product_tab_1_content',
	);

	// Videos tab.
	$tabs['custom_tab_2'] = array(
		'title'    => __( 'Videos', 'elevator' ),
		'priority' => 60,
		'callback' => 'elevator_custom_product_tab_2_content',
	);

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'elevator_custom_product_tabs' );

/**
 * Content for the Technical Downloads tab.
 */
function elevator_custom_product_tab_1_content() {
	global $post;

	$custom_info = get_field( 'technical_downloads', $post->ID );

	if ( $custom_info ) {
		echo '<h2>' . esc_html__( 'Technical Information', 'elevator' ) . '</h2>';
		echo '<p>' . wp_kses_post( $custom_info ) . '</p>';
	} else {
		echo '<p>' . esc_html__( 'No technical downloads available.', 'elevator' ) . '</p>';
	}
}

/**
 * Content for the Videos tab.
 */
function elevator_custom_product_tab_2_content() {
	global $post;

	if ( have_rows( 'product_videos', $post->ID ) ) {
		echo '<div class="product-videos-wrapper">';

		while ( have_rows( 'product_videos', $post->ID ) ) {
			the_row();

			$video_title = get_sub_field( 'video_text' );
			$video_url   = get_sub_field( 'video_url' );

			echo '<div class="product-video">';

			if ( ! empty( $video_title ) ) {
				echo '<h2>' . esc_html( $video_title ) . '</h2>';
			}

			if ( ! empty( $video_url ) ) {
				echo '<div class="video-container">';
				echo wp_oembed_get( $video_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}

			echo '</div>';
		}

		echo '</div>';
	} else {
		echo '<p>' . esc_html__( 'No videos available.', 'elevator' ) . '</p>';
	}
}

/**
 * Remove Additional Information tab.
 *
 * @param array $tabs Product tabs.
 * @return array Modified tabs.
 */
function elevator_remove_additional_information_tab( $tabs ) {
	unset( $tabs['additional_information'] );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'elevator_remove_additional_information_tab' );

/**
 * Duplicate product meta (categories, tags).
 */
function elevator_duplicate_product_meta() {
	echo '<div class="duplicated-product-meta">';

	do_action( 'woocommerce_product_meta_start' );
	echo '<br>';
	the_terms( get_the_ID(), 'product_cat', esc_html__( 'Categories: ', 'elevator' ), ', ' );
	echo '<br>';
	the_terms( get_the_ID(), 'product_tag', esc_html__( 'Tags: ', 'elevator' ), ', ' );
	do_action( 'woocommerce_product_meta_end' );

	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'elevator_duplicate_product_meta', 40 );

// Remove SKU from its default position.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Add SKU above the variable options.
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 25 );

/**
 * Buffer variations form and inject first variation attribute label.
 */
function elevator_custom_variation_buffer_start() {
	ob_start();
}
add_action( 'woocommerce_before_variations_form', 'elevator_custom_variation_buffer_start', 1 );

/**
 * End buffer and inject variation label.
 */
function elevator_custom_variation_buffer_end() {
	global $product;

	$content = ob_get_clean();

	if ( ! $product || ! is_a( $product, 'WC_Product_Variable' ) ) {
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	$attributes = $product->get_attributes();
	$label      = '';

	// Loop through attributes to find the first one used for variations.
	foreach ( $attributes as $attribute ) {
		if ( $attribute->get_variation() ) {
			// Get attribute name (taxonomy or custom).
			if ( $attribute->is_taxonomy() ) {
				$taxonomy = $attribute->get_name();
				$label    = wc_attribute_label( $taxonomy );
			} else {
				$label = $attribute->get_name();
			}
			break;
		}
	}

	if ( ! $label ) {
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	$search  = '<div class="woovr-variation-selector">';
	$replace = '<div class="woovr-variation-selector"><label class="variation-label">' . esc_html( $label ) . ':</label>';

	echo str_replace( $search, $replace, $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'woocommerce_after_variations_form', 'elevator_custom_variation_buffer_end', 999 );

/**
 * Swap featured image ID to first GIF in gallery (only on single product pages).
 *
 * @param int        $image_id Image ID.
 * @param WC_Product $product  Product object.
 * @return int Modified image ID.
 */
function elevator_gif_gallery_featured_image( $image_id, $product ) {
	static $called = false;
	if ( $called ) {
		return $image_id;
	}
	$called = true;

	// Only override image on single product page.
	if ( ! is_product() ) {
		$called = false;
		return $image_id;
	}

	if ( ! $product || ! is_object( $product ) ) {
		$called = false;
		return $image_id;
	}

	$gallery_ids = $product->get_gallery_image_ids();
	$all_ids     = array_merge( array( $image_id ), $gallery_ids );

	foreach ( $all_ids as $id ) {
		$url = wp_get_attachment_url( $id );
		if ( $url && preg_match( '/\.gif$/i', $url ) ) {
			$called = false;
			return $id; // Use GIF as main image on single product page.
		}
	}

	$called = false;
	return $image_id;
}
add_filter( 'woocommerce_product_get_image_id', 'elevator_gif_gallery_featured_image', 10, 2 );

/**
 * Modify gallery to add featured image back (as second image if GIF is used).
 *
 * @param array      $gallery_ids Gallery image IDs.
 * @param WC_Product $product     Product object.
 * @return array Modified gallery IDs.
 */
function elevator_gif_gallery_reorder( $gallery_ids, $product ) {
	if ( ! $product || ! is_object( $product ) ) {
		return $gallery_ids;
	}

	$real_featured_id       = get_post_thumbnail_id( $product->get_id() );
	$filtered_main_image_id = apply_filters( 'woocommerce_product_get_image_id', $real_featured_id, $product );

	$gif_ids   = array();
	$other_ids = array();

	foreach ( $gallery_ids as $id ) {
		// Avoid duplication of featured.
		if ( $id === $real_featured_id ) {
			continue;
		}

		$url = wp_get_attachment_url( $id );
		if ( $url && preg_match( '/\.gif$/i', $url ) ) {
			$gif_ids[] = $id;
		} else {
			$other_ids[] = $id;
		}
	}

	// Remove main image (GIF) if it's already in gif_ids.
	$gif_ids = array_filter(
		$gif_ids,
		function( $id ) use ( $filtered_main_image_id ) {
			return $id !== $filtered_main_image_id;
		}
	);

	// Build new gallery order.
	$new_gallery = $gif_ids;

	// Only add the real featured image if it wasn't used as main image.
	if ( $filtered_main_image_id !== $real_featured_id && $real_featured_id ) {
		array_unshift( $other_ids, $real_featured_id );
	}

	// Merge in: GIFs (minus main), then real featured image, then the rest.
	$new_gallery = array_merge( $new_gallery, $other_ids );

	return $new_gallery;
}
add_filter( 'woocommerce_product_get_gallery_image_ids', 'elevator_gif_gallery_reorder', 20, 2 );

/**
 * Replace Reviews tab with Upsell Products tab.
 *
 * @param array $tabs Product tabs.
 * @return array Modified tabs.
 */
function elevator_replace_reviews_with_upsell_products_tab( $tabs ) {
	// Remove the reviews tab.
	if ( isset( $tabs['reviews'] ) ) {
		unset( $tabs['reviews'] );
	}

	// Add upsell products tab using the 'reviews' key to keep position.
	$tabs['reviews'] = array(
		'title'    => __( 'Related Products', 'elevator' ),
		'priority' => 50,
		'callback' => 'elevator_custom_upsell_products_tab_content',
	);

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'elevator_replace_reviews_with_upsell_products_tab', 98 );

/**
 * Content for Upsell Products tab.
 */
function elevator_custom_upsell_products_tab_content() {
	global $product, $woocommerce_loop;

	$upsells = $product->get_upsell_ids();

	if ( $upsells ) {
		$args = array(
			'post_type'           => 'product',
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => 1,
			'posts_per_page'      => 4,
			'orderby'             => 'rand',
			'post__in'            => $upsells,
		);

		$products = new WP_Query( $args );

		if ( $products->have_posts() ) {
			// Set WooCommerce loop columns to 4.
			$woocommerce_loop['columns'] = 4;

			echo '<div class="custom-upsell-products woocommerce">';
			woocommerce_product_loop_start();

			while ( $products->have_posts() ) {
				$products->the_post();
				wc_get_template_part( 'content', 'product' );
			}

			woocommerce_product_loop_end();
			echo '</div>';

			// Reset the global columns after the loop.
			unset( $woocommerce_loop['columns'] );
		}

		wp_reset_postdata();
	} else {
		echo '<p>' . esc_html__( 'No recommended products at this time.', 'elevator' ) . '</p>';
	}
}

/**
 * Guest login button next to add-to-cart.
 */
function elevator_add_custom_button_for_guests() {
	if ( ! is_user_logged_in() ) {
		echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="button custom-login-button" style="margin-left: 10px;">' . esc_html__( 'Members - Please Login', 'elevator' ) . '</a>';
	}
}
add_action( 'woocommerce_after_add_to_cart_button', 'elevator_add_custom_button_for_guests' );

/**
 * Rename "SKU" to "Product Code".
 *
 * @param string $translated_text Translated text.
 * @param string $text            Original text.
 * @param string $domain          Text domain.
 * @return string Modified text.
 */
function elevator_change_sku_label_to_product_code( $translated_text, $text, $domain ) {
	if ( $domain === 'woocommerce' ) {
		switch ( $translated_text ) {
			case 'SKU':
				$translated_text = __( 'Product Code', 'elevator' );
				break;
			case 'SKU:':
				$translated_text = __( 'Product Code:', 'elevator' );
				break;
		}
	}
	return $translated_text;
}
add_filter( 'gettext', 'elevator_change_sku_label_to_product_code', 20, 3 );

/**
 * Display in/out of stock availability status.
 */
function elevator_display_availability_status_with_classes() {
	global $product;

	$availability = $product->get_availability();
	$class        = ! empty( $availability['class'] ) ? $availability['class'] : '';

	if ( ! empty( $availability['availability'] ) ) {
		echo '<p class="product-availability ' . esc_attr( $class ) . '">' . esc_html( $availability['availability'] ) . '</p>';
	}
}
add_action( 'woocommerce_single_product_summary', 'elevator_display_availability_status_with_classes', 15 );

/**
 * "Select Option" label above variation form for variable products.
 */
function elevator_variable_product_select_option_label() {
	global $product;
	if ( $product->is_type( 'variable' ) ) {
		echo '<div class="customized-section"><span class="price poa">' . esc_html__( 'Select Option', 'elevator' ) . '</span></div>';
	}
}
add_action( 'woocommerce_single_product_summary', 'elevator_variable_product_select_option_label', 6 );

/**
 * Remove "Add to Cart" from related/upsell product loops.
 *
 * @param string     $html    Add to cart HTML.
 * @param WC_Product $product Product object.
 * @return string Modified HTML.
 */
function elevator_remove_add_to_cart_buttons_from_related_and_upsell( $html, $product ) {
	if ( is_product() ) {
		// Check if we're in the related or upsell product section.
		if ( did_action( 'woocommerce_after_single_product_summary' ) && ( is_woocommerce() || is_single() ) ) {
			return '';
		}
	}
	return $html;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'elevator_remove_add_to_cart_buttons_from_related_and_upsell', 10, 2 );

// Remove the default hooks for upsells and related products.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

// Add upsell products at a lower priority.
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

/**
 * Insert custom div with template parts between upsells and related products.
 */
function elevator_insert_custom_div() {
	echo '<div class="custom-div">';
	get_template_part( 'modules/whatsapp', 'module' );
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'elevator_insert_custom_div', 17 );

// Add related products at a higher priority.
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 16 );

/**
 * Custom div after tabs (devide module).
 */
function elevator_add_custom_div_below_tabs() {
	echo '<div class="custom-div-devide">';
	get_template_part( 'modules/devide' );
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'elevator_add_custom_div_below_tabs', 11 );
