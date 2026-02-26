<?php
/**
 * Order Delivery Fields
 *
 * Displays delivery meta fields on the WooCommerce admin order screen (read-only).
 * Fields are set via ACF on the order:
 *  - number_of_packages
 *  - total_weight
 *  - courier
 *  - packed_by
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display delivery fields in the admin order screen — read-only.
 *
 * @param WC_Order $order The order object.
 */
function elevator_delivery_fields_display( WC_Order $order ) {
    $fields = elevator_delivery_field_definitions();

    // Only show the section if at least one field has a value.
    $has_values = false;
    foreach ( $fields as $key => $label ) {
        if ( $order->get_meta( $key ) !== '' ) {
            $has_values = true;
            break;
        }
    }

    if ( ! $has_values ) {
        return;
    }
    ?>
    <div class="elevator-delivery-fields" style="margin-top:12px;">
        <h4 style="margin:0 0 8px;padding:8px 0 4px;border-top:1px solid #eee;color:#154ed3;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">
            <?php esc_html_e( 'Despatch Information', 'elevator' ); ?>
        </h4>
        <?php foreach ( $fields as $key => $label ) :
            $value = $order->get_meta( $key );
            if ( $value === '' ) continue;
        ?>
        <p style="margin:0 0 6px;font-size:12px;">
            <span style="font-weight:600;color:#555;"><?php echo esc_html( $label ); ?>:</span>
            <?php echo esc_html( $value ); ?>
        </p>
        <?php endforeach; ?>
    </div>
    <?php
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'elevator_delivery_fields_display', 20 );

/**
 * Field definitions — meta key => label.
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