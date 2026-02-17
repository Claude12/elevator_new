<?php
/**
 * WooCommerce Continuous-Length Guard System
 *
 * Complete system for cable/reel products using ACF field 'max_continuous_length_m'.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Front-end input name.
 */
if ( ! defined( 'WOO_MCL_REQUEST_KEY' ) ) {
	define( 'WOO_MCL_REQUEST_KEY', 'length_m' );
}

/**
 * Get max continuous length (m) for a product or variation.
 *
 * Prefers variation value; falls back to parent product.
 *
 * @param int $product_id   Product ID.
 * @param int $variation_id Variation ID (optional).
 * @return float Maximum continuous length in meters.
 */
function elevator_woo_mcl_get_max_for_product_or_variation( $product_id, $variation_id = 0 ) {
	$read_max = function( $post_id ) {
		// ACF first.
		if ( function_exists( 'get_field' ) ) {
			$v = get_field( 'max_continuous_length_m', $post_id );
			if ( $v !== null && $v !== '' ) {
				return (float) $v;
			}
		}
		// Fallback raw meta.
		$v = get_post_meta( $post_id, 'max_continuous_length_m', true );
		return ( $v !== '' ) ? (float) $v : 0.0;
	};

	if ( $variation_id ) {
		$max = $read_max( $variation_id );
		if ( $max > 0 ) {
			return $max;
		}
	}

	return $read_max( $product_id );
}

/**
 * SIMPLE products: render the Length field only if ACF max is set (> 0).
 */
function elevator_woo_mcl_simple_product_field() {
	if ( ! is_product() ) {
		return;
	}

	global $product;
	if ( ! $product || ! $product->is_type( 'simple' ) ) {
		return;
	}

	$max = elevator_woo_mcl_get_max_for_product_or_variation( $product->get_id(), 0 );
	if ( $max <= 0 ) {
		return; // No ACF max set => don't render field.
	}
	?>
	<div class="product-length-field" style="margin:10px 0;">
		<label for="length_m"><?php esc_html_e( 'Length (m)', 'elevator' ); ?></label><br>
		<input type="number" id="length_m" name="<?php echo esc_attr( WOO_MCL_REQUEST_KEY ); ?>" step="0.01" min="0.01" required>
		<small class="product-length-max-note" style="display:block;opacity:.75;margin-top:4px;">
			<?php
			/* translators: %s: maximum available length in meters */
			echo esc_html( sprintf( __( 'Maximum available: %s m', 'elevator' ), number_format( $max, 2 ) ) );
			?>
		</small>
	</div>
	<?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'elevator_woo_mcl_simple_product_field', 18 );

/**
 * VARIABLE products: build a variation->max map, render a hidden field,
 * and toggle visibility when a variation (with max>0) is selected.
 */
function elevator_woo_mcl_variable_product_field() {
	if ( ! is_product() ) {
		return;
	}

	global $product;
	if ( ! $product || ! $product->is_type( 'variable' ) ) {
		return;
	}

	$variation_max_map = array();
	foreach ( $product->get_children() as $variation_id ) {
		$variation_max_map[ $variation_id ] = (float) elevator_woo_mcl_get_max_for_product_or_variation( $product->get_id(), $variation_id );
	}

	$has_any_max = array_reduce(
		$variation_max_map,
		function( $carry, $v ) {
			return $carry || ( $v > 0 );
		},
		false
	);

	if ( ! $has_any_max ) {
		return; // No variations with max set => don't render at all.
	}
	?>
	<div class="product-length-field product-length-field--variable" style="margin:10px 0; display:none;">
		<label for="length_m"><?php esc_html_e( 'Length (m)', 'elevator' ); ?></label><br>
		<input type="number" id="length_m" name="<?php echo esc_attr( WOO_MCL_REQUEST_KEY ); ?>" step="0.01" min="0.01">
		<small class="product-length-max-note" style="display:block;opacity:.75;margin-top:4px;"></small>
	</div>

	<script>
		(function() {
			var map = <?php echo wp_json_encode( $variation_max_map ); ?>;
			var fieldWrap = document.querySelector('.product-length-field--variable');
			if (!fieldWrap) return;

			var input = fieldWrap.querySelector('#length_m');
			var note = fieldWrap.querySelector('.product-length-max-note');

			function hideField() {
				fieldWrap.style.display = 'none';
				if (input) {
					input.required = false;
					input.value = '';
					input.removeAttribute('max');
				}
				if (note) note.textContent = '';
			}

			function showField(maxVal) {
				fieldWrap.style.display = '';
				if (input) {
					input.required = true;
					input.setAttribute('max', maxVal);
				}
				if (note) {
					var m = parseFloat(maxVal);
					note.textContent = '<?php esc_html_e( 'Maximum available:', 'elevator' ); ?> ' + (isFinite(m) ? m.toFixed(2) : maxVal) + ' m';
				}
			}

			// Use jQuery events instead of native DOM events for WooCommerce compatibility.
			jQuery(document).on('found_variation', '.variations_form', function(e, variation) {
				if (!variation || !('variation_id' in variation)) {
					hideField();
					return;
				}

				var vid = String(variation.variation_id);
				var max = (vid in map) ? parseFloat(map[vid]) : 0;

				(max > 0) ? showField(max) : hideField();
			});

			jQuery(document).on('reset_data', '.variations_form', function() {
				hideField();
			});
		})();
	</script>
	<?php
}
add_action( 'woocommerce_before_add_to_cart_button', 'elevator_woo_mcl_variable_product_field', 18 );

/**
 * Validator: block add-to-cart if requested length exceeds max continuous length.
 *
 * @param bool $passed       Whether validation passed.
 * @param int  $product_id   Product ID.
 * @param int  $quantity     Quantity.
 * @param int  $variation_id Variation ID.
 * @param array $variations  Variation attributes.
 * @return bool Whether validation passed.
 */
function elevator_woo_mcl_validate_add_to_cart( $passed, $product_id, $quantity, $variation_id = 0, $variations = array() ) {
	// Read requested length from POST.
	$requested_m = 0.0;
	if ( isset( $_POST[ WOO_MCL_REQUEST_KEY ] ) ) {
		$requested_m = (float) wc_format_decimal( wp_unslash( $_POST[ WOO_MCL_REQUEST_KEY ] ) );
	}

	// No length provided => allow.
	if ( $requested_m <= 0 ) {
		return $passed;
	}

	$max = elevator_woo_mcl_get_max_for_product_or_variation( $product_id, $variation_id );

	// If no max set => do not apply limits.
	if ( $max <= 0 ) {
		return $passed;
	}

	if ( $requested_m > $max ) {
		wc_add_notice(
			sprintf(
				/* translators: 1: requested length, 2: max length */
				__( 'Requested length (%1$.2fm) exceeds the maximum continuous length available (%2$.2fm). Please reduce the length or split into multiple reels.', 'elevator' ),
				$requested_m,
				$max
			),
			'error'
		);
		return false;
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'elevator_woo_mcl_validate_add_to_cart', 10, 5 );

/**
 * Persist requested length in cart data.
 *
 * @param array $cart_item_data Cart item data.
 * @param int   $product_id     Product ID.
 * @param int   $variation_id   Variation ID.
 * @return array Modified cart item data.
 */
function elevator_woo_mcl_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
	if ( isset( $_POST[ WOO_MCL_REQUEST_KEY ] ) ) {
		$cart_item_data['woo_mcl_requested_m'] = (float) wc_format_decimal( wp_unslash( $_POST[ WOO_MCL_REQUEST_KEY ] ) );
	}
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'elevator_woo_mcl_add_cart_item_data', 10, 3 );

/**
 * Display requested length in cart and checkout.
 *
 * @param array $item_data Item data.
 * @param array $cart_item Cart item.
 * @return array Modified item data.
 */
function elevator_woo_mcl_get_item_data( $item_data, $cart_item ) {
	if ( isset( $cart_item['woo_mcl_requested_m'] ) ) {
		$item_data[] = array(
			'name'  => __( 'Length', 'elevator' ),
			'value' => wc_clean( number_format( (float) $cart_item['woo_mcl_requested_m'], 2 ) ) . ' m',
		);
	}
	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'elevator_woo_mcl_get_item_data', 10, 2 );

/**
 * Cable quantity limit for product ID 11947.
 *
 * @param array      $args    Quantity input args.
 * @param WC_Product $product Product object.
 * @return array Modified args.
 */
function elevator_limit_cable_quantity_per_product( $args, $product ) {
	if ( $product->get_id() == 11947 ) {
		$args['min_value'] = 1;
		$args['max_value'] = 40;
	}
	return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'elevator_limit_cable_quantity_per_product', 10, 2 );
