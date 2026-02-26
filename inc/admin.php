<?php
/**
 * Admin Customizations
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hide Addify tabs in product editor.
 */
function elevator_custom_admin_css() {
	echo '<style>
		#woocommerce-product-data .product_data_tabs li.addify_csp_customer_options {
			display: none !important;
		}
		#woocommerce-product-data .product_data_tabs li.addify_csp_role_options {
			display: none !important;
		}
	</style>';
}
add_action( 'admin_head', 'elevator_custom_admin_css' );

/**
 * Normalize dash characters in product titles on save.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an update.
 */
function elevator_normalize_product_title_dashes( $post_id, $post, $update ) {
	if ( $post->post_type !== 'product' ) {
		return;
	}

	// Map of "fancy" dashes → normal hyphen.
	$replacements = array(
		'–' => '-', // en dash.
		'—' => '-', // em dash.
		'−' => '-', // minus sign.
	);

	$original_title   = $post->post_title;
	$normalized_title = strtr( $original_title, $replacements );

	if ( $normalized_title !== $original_title ) {
		// Avoid infinite loop.
		remove_action( 'save_post_product', 'elevator_normalize_product_title_dashes', 10 );

		wp_update_post(
			array(
				'ID'         => $post_id,
				'post_title' => $normalized_title,
			)
		);

		add_action( 'save_post_product', 'elevator_normalize_product_title_dashes', 10, 3 );
	}
}
add_action( 'save_post_product', 'elevator_normalize_product_title_dashes', 10, 3 );


// =============================================================================
// WooCommerce Order List — Status Highlighting & Inline Status Changer
// =============================================================================

/**
 * Highlight order rows by status and style the inline status select.
 */
function elevator_order_list_admin_styles() {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'edit-shop_order', 'woocommerce_page_wc-orders' ), true ) ) {
		return;
	}
	echo '<style>
		/* Row highlight by status */
		.wp-list-table tr.status-completed td {
			background-color: #d4f8d4 !important;
		}
		.wp-list-table tr.status-processing td {
			background-color: #fff7cc !important;
		}
		.wp-list-table tr.status-on-hold td {
			background-color: #ffe0e0 !important;
		}
		.wp-list-table tr.status-cancelled td {
			background-color: #f2f2f2 !important;
			color: #999 !important;
		}
		.wp-list-table tr.status-pending td {
			background-color: #fce8d5 !important;
		}
		.wp-list-table tr.status-refunded td {
			background-color: #e8d5fc !important;
		}

		/* Inline status select */
		.elevator-status-select {
			font-size: 12px;
			padding: 2px 4px;
			border-radius: 3px;
			border: 1px solid #ccc;
			cursor: pointer;
		}
		.elevator-status-saving {
			opacity: 0.5;
			pointer-events: none;
		}
	</style>';
}
add_action( 'admin_head', 'elevator_order_list_admin_styles' );

/**
 * Add "Change Status" column to the orders list table.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function elevator_add_status_change_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $label ) {
		$new_columns[ $key ] = $label;
		if ( 'order_status' === $key || 'wc_actions' === $key ) {
			$new_columns['elevator_change_status'] = __( 'Change Status', 'elevator' );
		}
	}
	if ( ! isset( $new_columns['elevator_change_status'] ) ) {
		$new_columns['elevator_change_status'] = __( 'Change Status', 'elevator' );
	}
	return $new_columns;
}
add_filter( 'manage_edit-shop_order_columns', 'elevator_add_status_change_column' );
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'elevator_add_status_change_column' );

/**
 * Render the inline status dropdown in the "Change Status" column.
 *
 * @param string         $column           Column slug.
 * @param int|WC_Order   $post_id_or_order Post ID (legacy) or WC_Order (HPOS).
 */
function elevator_render_status_change_column( $column, $post_id_or_order ) {
	if ( 'elevator_change_status' !== $column ) {
		return;
	}

	$order = ( $post_id_or_order instanceof WC_Order )
		? $post_id_or_order
		: wc_get_order( $post_id_or_order );

	if ( ! $order ) {
		return;
	}

	$current_status = $order->get_status();
	$statuses       = wc_get_order_statuses();
	$order_id       = $order->get_id();
	$nonce          = wp_create_nonce( 'elevator_change_status_' . $order_id );

	echo '<select class="elevator-status-select" data-order-id="' . esc_attr( $order_id ) . '" data-nonce="' . esc_attr( $nonce ) . '">';
	foreach ( $statuses as $status_key => $status_label ) {
		$slug = str_replace( 'wc-', '', $status_key );
		echo '<option value="' . esc_attr( $slug ) . '"' . selected( $current_status, $slug, false ) . '>'
			. esc_html( $status_label )
			. '</option>';
	}
	echo '</select>';
}
add_action( 'manage_shop_order_posts_custom_column', 'elevator_render_status_change_column', 10, 2 );
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'elevator_render_status_change_column', 10, 2 );

/**
 * AJAX handler — save the new order status.
 */
function elevator_ajax_update_order_status() {
	$order_id   = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
	$new_status = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : '';
	$nonce      = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

	if ( ! $order_id || ! $new_status ) {
		wp_send_json_error( 'Missing data' );
	}
	if ( ! wp_verify_nonce( $nonce, 'elevator_change_status_' . $order_id ) ) {
		wp_send_json_error( 'Security check failed' );
	}
	if ( ! current_user_can( 'edit_shop_orders' ) ) {
		wp_send_json_error( 'Insufficient permissions' );
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		wp_send_json_error( 'Order not found' );
	}

	$order->update_status( $new_status, __( 'Status updated via order list.', 'elevator' ) );
	wp_send_json_success( array( 'new_status' => $new_status ) );
}
add_action( 'wp_ajax_elevator_update_order_status', 'elevator_ajax_update_order_status' );

/**
 * JavaScript — handle the AJAX call and update row colour instantly.
 */
function elevator_order_list_admin_js() {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'edit-shop_order', 'woocommerce_page_wc-orders' ), true ) ) {
		return;
	}
	?>
	<script>
	jQuery( function ( $ ) {
		$( document ).on( 'change', '.elevator-status-select', function () {
			var $select   = $( this );
			var orderId   = $select.data( 'order-id' );
			var newStatus = $select.val();
			var nonce     = $select.data( 'nonce' );
			var $row      = $select.closest( 'tr' );

			$select.addClass( 'elevator-status-saving' );

			$.post( ajaxurl, {
				action:   'elevator_update_order_status',
				order_id: orderId,
				status:   newStatus,
				nonce:    nonce
			}, function ( response ) {
				$select.removeClass( 'elevator-status-saving' );
				if ( response.success ) {
					var classList = $row.attr( 'class' ) || '';
					classList = classList.replace( /\bstatus-\S+/g, '' );
					$row.attr( 'class', $.trim( classList ) + ' status-' + response.data.new_status );
				} else {
					alert( 'Error updating status: ' + response.data );
				}
			} );
		} );
	} );
	</script>
	<?php
}
add_action( 'admin_footer', 'elevator_order_list_admin_js' );