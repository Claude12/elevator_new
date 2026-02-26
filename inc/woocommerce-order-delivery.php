<?php
/**
 * Order Delivery Fields
 *
 * Adds editable delivery fields to the WooCommerce admin order screen:
 *  - Number of Packages
 *  - Total Weight
 *  - Courier
 *  - Packed By
 *
 * These are saved as order meta and pulled into the delivery note PDF template.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display delivery fields in the admin order billing section.
 *
 * @param WC_Order $order The order object.
 */
function elevator_delivery_fields_display( WC_Order $order ) {
    $fields = elevator_delivery_field_definitions();
    ?>
    <div class="elevator-delivery-fields" style="margin-top:12px;">
        <h4 style="margin:0 0 8px;padding:8px 0 4px;border-top:1px solid #eee;color:#154ed3;">
            <?php esc_html_e( 'Despatch Information', 'elevator' ); ?>
        </h4>
        <?php foreach ( $fields as $key => $label ) :
            $value = $order->get_meta( $key );
        ?>
        <p style="margin:0 0 8px;">
            <label for="elevator_<?php echo esc_attr( $key ); ?>" style="display:block;font-weight:600;font-size:12px;margin-bottom:2px;">
                <?php echo esc_html( $label ); ?>
            </label>
            <input
                type="text"
                id="elevator_<?php echo esc_attr( $key ); ?>"
                name="elevator_<?php echo esc_attr( $key ); ?>"
                value="<?php echo esc_attr( $value ); ?>"
                style="width:100%;padding:4px 6px;"
            />
        </p>
        <?php endforeach; ?>
    </div>
    <?php
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'elevator_delivery_fields_display', 20 );

/**
 * Save delivery fields when the admin order is saved.
 *
 * @param int $order_id The order ID being saved.
 */
function elevator_delivery_fields_save( int $order_id ) {
    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce handles nonce verification.
    $fields = elevator_delivery_field_definitions();

    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }

    foreach ( $fields as $key => $label ) {
        $post_key = 'elevator_' . $key;
        if ( isset( $_POST[ $post_key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
            $value = sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) );
            $order->update_meta_data( $key, $value );
        }
    }

    $order->save();
}
add_action( 'woocommerce_process_shop_order_meta', 'elevator_delivery_fields_save' );

/**
 * Field definitions — meta key => label.
 * Single source of truth used by both display and save functions.
 *
 * @return array
 */
function elevator_delivery_field_definitions(): array {
    return array(
        'number_of_packages' => __( 'Number of Packages', 'elevator' ),
        'total_weight'       => __( 'Total Weight', 'elevator' ),
        'courier'            => __( 'Courier', 'elevator' ),
        'packed_by'          => __( 'Packed By', 'elevator' ),
    );
}