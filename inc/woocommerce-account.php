<?php
/**
 * WooCommerce My Account Customizations
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print Order button on order details page.
 *
 * @param WC_Order $order Order object.
 */
function elevator_add_print_order_button( $order ) {
	if ( ! is_account_page() ) {
		return;
	}

	$order_id   = $order->get_id();
	$print_url  = wp_nonce_url(
		add_query_arg( 'print-order', $order_id, home_url() ),
		'elevator_print_order_' . $order_id
	);
	?>
	<div class="woocommerce-order-actions">
		<a href="<?php echo esc_url( $print_url ); ?>" target="_blank" class="button">
			<?php esc_html_e( 'Print Order', 'elevator' ); ?>
		</a>
	</div>
	<?php
}
add_action( 'woocommerce_order_details_after_order_table', 'elevator_add_print_order_button' );

/**
 * Handle print order page.
 */
function elevator_handle_print_order_page() {
	if ( isset( $_GET['print-order'] ) ) {
		$order_id = intval( $_GET['print-order'] );

		// Security: verify nonce.
		$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'elevator_print_order_' . $order_id ) ) {
			wp_die( esc_html__( 'Security check failed.', 'elevator' ) );
		}

		$order = wc_get_order( $order_id );

		// Security: check if user can view this order.
		if ( ! $order || ! current_user_can( 'view_order', $order_id ) ) {
			wp_die( esc_html__( 'You do not have permission to view this order.', 'elevator' ) );
		}

		// Pass order to the template.
		global $print_order;
		$print_order = $order;

		// Load custom print-order.php template.
		$template_path = get_template_directory() . '/woocommerce/print-order.php';
		if ( file_exists( $template_path ) ) {
			include $template_path;
		}
		exit;
	}
}
add_action( 'template_redirect', 'elevator_handle_print_order_page' );

/**
 * Repeat Order button on order details page.
 *
 * @param WC_Order $order Order object.
 */
function elevator_add_repeat_order_button( $order ) {
	if ( ! is_account_page() ) {
		return;
	}

	$order_id   = $order->get_id();
	$repeat_url = wp_nonce_url(
		add_query_arg( 'repeat_order', $order_id, wc_get_cart_url() ),
		'repeat_order_' . $order_id
	);
	?>
	<div class="woocommerce-order-actions">
		<a href="<?php echo esc_url( $repeat_url ); ?>" class="button">
			<?php esc_html_e( 'Repeat Order', 'elevator' ); ?>
		</a>
	</div>
	<?php
}
add_action( 'woocommerce_order_details_after_order_table', 'elevator_add_repeat_order_button' );

/**
 * Handle repeat order — supports both simple and variable products.
 */
function elevator_handle_repeat_order() {
	if ( ! isset( $_GET['repeat_order'] ) ) {
		return;
	}

	$order_id = absint( $_GET['repeat_order'] );
	$nonce    = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

	if ( ! wp_verify_nonce( $nonce, 'repeat_order_' . $order_id ) ) {
		wp_die( esc_html__( 'Security check failed.', 'elevator' ) );
	}

	$order = wc_get_order( $order_id );

	if ( ! $order || ! current_user_can( 'view_order', $order_id ) ) {
		wp_die( esc_html__( 'Invalid order.', 'elevator' ) );
	}

	// Ensure WooCommerce cart is loaded.
	if ( is_null( WC()->cart ) ) {
		wc_load_cart();
	}

	WC()->cart->empty_cart();

	foreach ( $order->get_items() as $item ) {
		$product_id   = $item->get_product_id();
		$variation_id = $item->get_variation_id(); // 0 for simple products.
		$quantity     = $item->get_quantity();

		// For variable products, get the exact variation attributes from the order item.
		$variation = array();
		if ( $variation_id ) {
			$variation_product = wc_get_product( $variation_id );
			if ( ! $variation_product ) {
				continue; // Variation no longer exists, skip.
			}

			// Get the variation attributes stored on the order item meta.
			$item_meta = $item->get_meta_data();
			foreach ( $item_meta as $meta ) {
				$meta_data = $meta->get_data();
				$key       = $meta_data['key'];
				// Variation attributes are stored as 'pa_size', 'pa_colour', etc.
				if ( taxonomy_exists( $key ) || strpos( $key, 'pa_' ) === 0 ) {
					$variation[ 'attribute_' . $key ] = $meta_data['value'];
				}
			}

			// Fallback: if no variation attributes found from meta, try the variation product itself.
			if ( empty( $variation ) && $variation_product ) {
				$variation = $variation_product->get_variation_attributes();
			}

			// Check the variation product is purchasable and in stock.
			if ( ! $variation_product->is_purchasable() || ! $variation_product->is_in_stock() ) {
				continue;
			}

			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
		} else {
			// Simple product.
			$product = wc_get_product( $product_id );
			if ( $product && $product->is_purchasable() && $product->is_in_stock() ) {
				WC()->cart->add_to_cart( $product_id, $quantity );
			}
		}
	}

	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
add_action( 'template_redirect', 'elevator_handle_repeat_order' );

/**
 * Disable password reset functionality.
 *
 * @param bool $allow  Whether to allow password reset.
 * @param int  $user_id User ID.
 * @return bool Always false.
 */
function elevator_disable_password_reset( $allow, $user_id ) {
	return false;
}
add_filter( 'allow_password_reset', 'elevator_disable_password_reset', 10, 2 );

/**
 * Remove "Lost your password?" link from login form.
 *
 * @param string $text Login form bottom text.
 * @return string Modified text.
 */
function elevator_remove_lost_password_text( $text ) {
	return str_replace( '<a href="' . wp_lostpassword_url() . '">' . esc_html__( 'Lost your password?', 'elevator' ) . '</a>', '', $text );
}
add_filter( 'login_form_bottom', 'elevator_remove_lost_password_text' );

/**
 * Redirect lost-password endpoint to My Account page.
 */
function elevator_redirect_lost_password_endpoint() {
	if ( is_wc_endpoint_url( 'lost-password' ) ) {
		wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
		exit;
	}
}
add_action( 'template_redirect', 'elevator_redirect_lost_password_endpoint' );

/**
 * Helper: Get user's purchased product quantities.
 *
 * @param int   $user_id     User ID.
 * @param array $product_ids Array of product IDs.
 * @return array Product ID => quantity mapping.
 */
function elevator_get_user_purchase_counts( $user_id, $product_ids ) {
	global $wpdb;

	if ( empty( $product_ids ) ) {
		return array();
	}

	$placeholders = implode( ',', array_fill( 0, count( $product_ids ), '%d' ) );
	$query        = $wpdb->prepare(
		"SELECT product_id, SUM(product_qty) as total_qty
		FROM {$wpdb->prefix}wc_order_product_lookup
		WHERE customer_id = %d AND product_id IN ($placeholders)
		GROUP BY product_id",
		array_merge( array( $user_id ), $product_ids )
	);

	$results = $wpdb->get_results( $query );

	$counts = array();
	foreach ( $results as $row ) {
		$counts[ $row->product_id ] = (int) $row->total_qty;
	}

	return $counts;
}
