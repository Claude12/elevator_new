<?php
/**
 * WooCommerce Single Product Customizations (Cleaned)
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ---------------------------------------------------------
 * CUSTOM PRODUCT TABS
 * ---------------------------------------------------------
 */

function elevator_custom_product_tabs( $tabs ) {

    $tabs['custom_tab_1'] = array(
        'title'    => __( 'Technical Information', 'elevator' ),
        'priority' => 50,
        'callback' => 'elevator_custom_product_tab_1_content',
    );

    $tabs['custom_tab_2'] = array(
        'title'    => __( 'Videos', 'elevator' ),
        'priority' => 60,
        'callback' => 'elevator_custom_product_tab_2_content',
    );

    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'elevator_custom_product_tabs' );

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

function elevator_custom_product_tab_2_content() {
    global $post;

    if ( have_rows( 'product_videos', $post->ID ) ) {
        echo '<div class="product-videos-wrapper">';

        while ( have_rows( 'product_videos', $post->ID ) ) {
            the_row();

            $video_title = get_sub_field( 'video_text' );
            $video_url   = get_sub_field( 'video_url' );

            echo '<div class="product-video">';

            if ( $video_title ) {
                echo '<h2>' . esc_html( $video_title ) . '</h2>';
            }

            if ( $video_url ) {
                echo '<div class="video-container">';
                echo wp_oembed_get( $video_url );
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
 */
function elevator_remove_additional_information_tab( $tabs ) {
    unset( $tabs['additional_information'] );
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'elevator_remove_additional_information_tab' );


/**
 * ---------------------------------------------------------
 * VARIATION LABEL INJECTION
 * ---------------------------------------------------------
 */

function elevator_custom_variation_buffer_start() {
    ob_start();
}
add_action( 'woocommerce_before_variations_form', 'elevator_custom_variation_buffer_start', 1 );

function elevator_custom_variation_buffer_end() {
    global $product;

    $content = ob_get_clean();

    if ( ! $product || ! is_a( $product, 'WC_Product_Variable' ) ) {
        echo $content;
        return;
    }

    $attributes = $product->get_attributes();
    $label      = '';

    foreach ( $attributes as $attribute ) {
        if ( $attribute->get_variation() ) {
            $label = $attribute->is_taxonomy()
                ? wc_attribute_label( $attribute->get_name() )
                : $attribute->get_name();
            break;
        }
    }

    if ( ! $label ) {
        echo $content;
        return;
    }

    $search  = '<div class="woovr-variation-selector">';
    $replace = '<div class="woovr-variation-selector"><label class="variation-label">' . esc_html( $label ) . ':</label>';

    echo str_replace( $search, $replace, $content );
}
add_action( 'woocommerce_after_variations_form', 'elevator_custom_variation_buffer_end', 999 );


/**
 * ---------------------------------------------------------
 * GIF FEATURED IMAGE LOGIC
 * ---------------------------------------------------------
 */

function elevator_gif_gallery_featured_image( $image_id, $product ) {
    static $called = false;
    if ( $called ) return $image_id;
    $called = true;

    if ( ! is_product() ) {
        $called = false;
        return $image_id;
    }

    $gallery_ids = $product->get_gallery_image_ids();
    $all_ids     = array_merge( array( $image_id ), $gallery_ids );

    foreach ( $all_ids as $id ) {
        $url = wp_get_attachment_url( $id );
        if ( $url && preg_match( '/\.gif$/i', $url ) ) {
            $called = false;
            return $id;
        }
    }

    $called = false;
    return $image_id;
}
add_filter( 'woocommerce_product_get_image_id', 'elevator_gif_gallery_featured_image', 10, 2 );

function elevator_gif_gallery_reorder( $gallery_ids, $product ) {
    if ( ! $product ) return $gallery_ids;

    $real_featured_id       = get_post_thumbnail_id( $product->get_id() );
    $filtered_main_image_id = apply_filters( 'woocommerce_product_get_image_id', $real_featured_id, $product );

    $gif_ids   = array();
    $other_ids = array();

    foreach ( $gallery_ids as $id ) {
        if ( $id === $real_featured_id ) continue;

        $url = wp_get_attachment_url( $id );
        if ( $url && preg_match( '/\.gif$/i', $url ) ) {
            $gif_ids[] = $id;
        } else {
            $other_ids[] = $id;
        }
    }

    $gif_ids = array_filter( $gif_ids, function( $id ) use ( $filtered_main_image_id ) {
        return $id !== $filtered_main_image_id;
    });

    $new_gallery = array_merge( $gif_ids, $other_ids );

    return $new_gallery;
}
add_filter( 'woocommerce_product_get_gallery_image_ids', 'elevator_gif_gallery_reorder', 20, 2 );


/**
 * ---------------------------------------------------------
 * GUEST LOGIN BUTTON
 * ---------------------------------------------------------
 */

function elevator_add_custom_button_for_guests() {
    if ( ! is_user_logged_in() ) {
        echo '<a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="button custom-login-button" style="margin-left: 10px;">' . esc_html__( 'Members - Please Login', 'elevator' ) . '</a>';
    }
}
add_action( 'woocommerce_after_add_to_cart_button', 'elevator_add_custom_button_for_guests' );


/**
 * ---------------------------------------------------------
 * SKU LABEL RENAME
 * ---------------------------------------------------------
 */

function elevator_change_sku_label_to_product_code( $translated_text, $text, $domain ) {
    if ( $domain === 'woocommerce' ) {
        if ( $translated_text === 'SKU' ) {
            return __( 'Product Code', 'elevator' );
        }
        if ( $translated_text === 'SKU:' ) {
            return __( 'Product Code:', 'elevator' );
        }
    }
    return $translated_text;
}
add_filter( 'gettext', 'elevator_change_sku_label_to_product_code', 20, 3 );
