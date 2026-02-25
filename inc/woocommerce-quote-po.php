<?php
/**
 * Purchase Order Number — Addify "Convert to Order" flow
 *
 * Injects a required PO Number field next to the "Convert to Order" button.
 * The button is disabled until a PO number is entered.
 * The error message is always visible when the field is empty.
 * Warns (or blocks) if the PO number has been used on a previous order.
 * Saves the PO number to the order meta on conversion.
 * Displays the PO number in the WooCommerce admin order screen.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// 1. Inject PO field, disable button until filled, warn on duplicate PO
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Localise AJAX vars and output the PO field + script in the footer.
 */
function elevator_po_field_output() {
	if ( ! is_user_logged_in() ) {
		return;
	}
	?>
	<script>
	jQuery(document).ready(function($) {

		var $wrap = $('.addify_converty_to_order_button');
		if ( ! $wrap.length ) return;

		var $btn     = $wrap.find('#addify_convert_to_order_customer');
		var debounce = null;

		// Inject field above the button.
		$wrap.before(
			'<div id="elevator_po_wrap" style="margin-bottom:10px;">' +
				'<label for="elevator_po_number" style="display:block;font-weight:600;margin-bottom:4px;">' +
					'<?php echo esc_js( __( 'Purchase Order Number', 'elevator' ) ); ?> <abbr title="required">*</abbr>' +
				'</label>' +
				'<input type="text" id="elevator_po_number" name="additional_purchase_order" class="input-text" placeholder="<?php echo esc_js( __( 'Enter PO Number', 'elevator' ) ); ?>" style="width:100%;max-width:400px;padding:8px 12px;font-size:14px;border:1px solid #ccc;border-radius:4px;" />' +
				'<span id="elevator_po_error" style="color:#c00;font-size:13px;margin-top:4px;display:block;"><?php echo esc_js( __( 'A Purchase Order Number is required.', 'elevator' ) ); ?></span>' +
				'<span id="elevator_po_duplicate" style="display:none;color:#b45309;font-size:13px;margin-top:4px;background:#fffbeb;border:1px solid #fcd34d;border-radius:4px;padding:6px 10px;">' +
					'&#9888; <?php echo esc_js( __( 'This PO Number has already been used on a previous order. Please confirm this is correct before proceeding.', 'elevator' ) ); ?>' +
				'</span>' +
			'</div>'
		);

		// Disable button on load.
		$btn.prop('disabled', true).css('opacity', '0.5');

		// Re-evaluate on every keystroke.
		$(document).on('input', '#elevator_po_number', function() {
			var val    = $.trim( $(this).val() );
			var filled = val !== '';

			// Reset duplicate warning on each change.
			$('#elevator_po_duplicate').hide();

			if ( filled ) {
				$(this).css('border-color', '');
				$('#elevator_po_error').hide();
				$btn.prop('disabled', false).css('opacity', '1');

				// Debounce duplicate check — wait 600ms after user stops typing.
				clearTimeout( debounce );
				debounce = setTimeout(function() {
					checkDuplicate( val );
				}, 600);

			} else {
				$(this).css('border-color', '#c00');
				$('#elevator_po_error').show();
				$btn.prop('disabled', true).css('opacity', '0.5');
				clearTimeout( debounce );
			}
		});

		// Duplicate check via AJAX.
		function checkDuplicate( po ) {
			$.post(
				'<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>',
				{
					action : 'elevator_check_po_duplicate',
					po     : po,
					nonce  : '<?php echo esc_js( wp_create_nonce( 'elevator_po_check' ) ); ?>'
				},
				function( response ) {
					if ( response.success && response.data.duplicate ) {
						$('#elevator_po_duplicate').show();
						// ── To hard-block instead of warn, uncomment these two lines: ──
						// $btn.prop('disabled', true).css('opacity', '0.5');
						// return;
					}
				}
			);
		}

		// Final guard on submit.
		var $form = $btn.closest('form');
		$form.on('submit', function(e) {
			if ( $.trim( $('#elevator_po_number').val() ) === '' ) {
				e.preventDefault();
				$('#elevator_po_number').css('border-color', '#c00');
				$('#elevator_po_error').show();
				return false;
			}
		});

	});
	</script>
	<?php
}
add_action( 'wp_footer', 'elevator_po_field_output', 20 );

// ─────────────────────────────────────────────────────────────────────────────
// 2. AJAX handler — check if PO number already exists on any order
// ─────────────────────────────────────────────────────────────────────────────

/**
 * AJAX: check whether a PO number has already been used on a previous order.
 * Returns { duplicate: true/false }.
 */
function elevator_ajax_check_po_duplicate() {
	check_ajax_referer( 'elevator_po_check', 'nonce' );

	$po = isset( $_POST['po'] ) ? sanitize_text_field( wp_unslash( $_POST['po'] ) ) : '';

	if ( empty( $po ) ) {
		wp_send_json_success( array( 'duplicate' => false ) );
		wp_die();
	}

	// Search all orders for this meta value.
	$orders = wc_get_orders( array(
		'meta_key'     => 'additional_purchase_order',
		'meta_value'   => $po,
		'meta_compare' => '=',
		'limit'        => 1,
		'return'       => 'ids',
	) );

	wp_send_json_success( array( 'duplicate' => ! empty( $orders ) ) );
}
add_action( 'wp_ajax_elevator_check_po_duplicate', 'elevator_ajax_check_po_duplicate' );

// ─────────────────────────────────────────────────────────────────────────────
// 3. Save PO number to order meta
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Store PO number in session when Convert to Order is POSTed.
 */
function elevator_capture_po_on_convert() {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( empty( $_POST['addify_convert_to_order_customer'] ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	$po = isset( $_POST['additional_purchase_order'] )
		? sanitize_text_field( wp_unslash( $_POST['additional_purchase_order'] ) )
		: '';

	if ( ! empty( $po ) && function_exists( 'WC' ) && WC()->session ) {
		WC()->session->set( 'elevator_po_number', $po );
	}
}
add_action( 'wp_loaded', 'elevator_capture_po_on_convert', 5 );

/**
 * Write PO number to order meta when the order is created.
 *
 * @param int|\WC_Order $order_or_id Order ID or object.
 */
function elevator_save_po_to_order( $order_or_id ) {
	$order_id = $order_or_id instanceof WC_Order ? $order_or_id->get_id() : (int) $order_or_id;
	if ( ! $order_id ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	$po = isset( $_POST['additional_purchase_order'] )
		? sanitize_text_field( wp_unslash( $_POST['additional_purchase_order'] ) )
		: '';

	if ( empty( $po ) && function_exists( 'WC' ) && WC()->session ) {
		$po = (string) WC()->session->get( 'elevator_po_number', '' );
	}

	if ( empty( $po ) ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	$order->update_meta_data( 'additional_purchase_order', $po );
	$order->save();

	if ( function_exists( 'WC' ) && WC()->session ) {
		WC()->session->__unset( 'elevator_po_number' );
	}
}
add_action( 'woocommerce_new_order',              'elevator_save_po_to_order', 20 );
add_action( 'woocommerce_checkout_order_created', 'elevator_save_po_to_order', 20 );

// ─────────────────────────────────────────────────────────────────────────────
// 4. Display PO number in the WooCommerce admin order screen
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Show the Purchase Order Number in the admin order billing section.
 *
 * @param \WC_Order $order The order object.
 */
function elevator_admin_display_po( $order ) {
	$po = $order->get_meta( 'additional_purchase_order' );
	if ( empty( $po ) ) {
		return;
	}
	?>
	<p>
		<strong><?php esc_html_e( 'Purchase Order Number', 'elevator' ); ?>:</strong>
		<?php echo esc_html( $po ); ?>
	</p>
	<?php
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'elevator_admin_display_po', 10 );