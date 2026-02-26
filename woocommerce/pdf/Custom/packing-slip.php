<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
// ── Pull custom meta ──────────────────────────────────────────────────────────
$order_id      = $this->order->get_id();
$po_number     = $this->order->get_meta( 'additional_purchase_order' );
$num_packages  = $this->order->get_meta( 'number_of_packages' );
$total_weight  = $this->order->get_meta( 'total_weight' );
$courier       = $this->order->get_meta( 'courier' );
$packed_by     = $this->order->get_meta( 'packed_by' );

$completed     = $this->order->get_date_completed();
$despatch_date = $completed
    ? $completed->date_i18n( get_option( 'date_format' ) )
    : wp_date( get_option( 'date_format' ) );

$has_despatch_info = ( $num_packages || $total_weight || $courier || $packed_by );
?>

<?php do_action( 'wpo_wcpdf_before_document', $this->get_type(), $this->order ); ?>

<table class="head container">
	<tr>
		<td class="header">
			<?php if ( $this->has_header_logo() ) : ?>
				<?php do_action( 'wpo_wcpdf_before_shop_logo', $this->get_type(), $this->order ); ?>
				<?php $this->header_logo(); ?>
				<?php do_action( 'wpo_wcpdf_after_shop_logo', $this->get_type(), $this->order ); ?>
			<?php else : ?>
				<?php $this->title(); ?>
			<?php endif; ?>
		</td>
		<td class="shop-info">
			<?php do_action( 'wpo_wcpdf_before_shop_name', $this->get_type(), $this->order ); ?>
			<div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
			<?php do_action( 'wpo_wcpdf_after_shop_name', $this->get_type(), $this->order ); ?>
			<?php do_action( 'wpo_wcpdf_before_shop_address', $this->get_type(), $this->order ); ?>
			<div class="shop-address"><?php $this->shop_address(); ?></div>
			<?php do_action( 'wpo_wcpdf_after_shop_address', $this->get_type(), $this->order ); ?>
			<?php if ( ! empty( $this->get_shop_phone_number() ) ) : ?>
				<div class="shop-phone-number"><?php $this->shop_phone_number(); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $this->get_shop_email_address() ) ) : ?>
				<div class="shop-email-address"><?php $this->shop_email_address(); ?></div>
			<?php endif; ?>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_document_label', $this->get_type(), $this->order ); ?>
<?php if ( $this->has_header_logo() ) : ?>
	<h1 class="document-type-label"><?php $this->title(); ?></h1>
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document_label', $this->get_type(), $this->order ); ?>

<table class="order-data-addresses">
	<tr>
		<!-- DESPATCH TO -->
		<td class="address shipping-address">
			<h3><?php esc_html_e( 'Despatch To', 'elevator' ); ?></h3>
			<?php do_action( 'wpo_wcpdf_before_shipping_address', $this->get_type(), $this->order ); ?>
			<p><?php $this->shipping_address(); ?></p>
			<?php do_action( 'wpo_wcpdf_after_shipping_address', $this->get_type(), $this->order ); ?>
			<?php if ( isset( $this->settings['display_email'] ) ) : ?>
				<div class="billing-email"><?php $this->billing_email(); ?></div>
			<?php endif; ?>
			<?php if ( isset( $this->settings['display_phone'] ) ) : ?>
				<div class="shipping-phone"><?php $this->shipping_phone( ! $this->show_billing_address() ); ?></div>
			<?php endif; ?>
		</td>

		<!-- BILLING ADDRESS (if different) -->
		<td class="address billing-address">
			<?php if ( $this->show_billing_address() ) : ?>
				<h3><?php $this->billing_address_title(); ?></h3>
				<?php do_action( 'wpo_wcpdf_before_billing_address', $this->get_type(), $this->order ); ?>
				<p><?php $this->billing_address(); ?></p>
				<?php do_action( 'wpo_wcpdf_after_billing_address', $this->get_type(), $this->order ); ?>
				<?php if ( isset( $this->settings['display_phone'] ) && ! empty( $this->get_billing_phone() ) ) : ?>
					<div class="billing-phone"><?php $this->billing_phone(); ?></div>
				<?php endif; ?>
			<?php endif; ?>
		</td>

		<!-- ORDER META -->
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->get_type(), $this->order ); ?>
				<tr class="order-number">
					<th><?php esc_html_e( 'Delivery Note No.', 'elevator' ); ?></th>
					<td><?php $this->order_number(); ?></td>
				</tr>
				<tr class="despatch-date">
					<th><?php esc_html_e( 'Despatch Date', 'elevator' ); ?></th>
					<td><?php echo esc_html( $despatch_date ); ?></td>
				</tr>
				<?php if ( $po_number ) : ?>
				<tr class="purchase-order">
					<th><?php esc_html_e( 'Purchase Order No.', 'elevator' ); ?></th>
					<td><?php echo esc_html( $po_number ); ?></td>
				</tr>
				<?php endif; ?>
				<tr class="order-date">
					<th><?php $this->order_date_title(); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
				<?php if ( $this->get_shipping_method() ) : ?>
					<tr class="shipping-method">
						<th><?php $this->shipping_method_title(); ?></th>
						<td><?php $this->shipping_method(); ?></td>
					</tr>
				<?php endif; ?>
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->get_type(), $this->order ); ?>
			</table>
		</td>
	</tr>
</table>

<?php if ( $has_despatch_info ) : ?>
<!-- DESPATCH INFORMATION BAR -->
<table class="despatch-info">
	<tr>
		<?php if ( $num_packages ) : ?>
		<td>
			<span class="label"><?php esc_html_e( 'No. of Packages', 'elevator' ); ?>:</span>
			<?php echo esc_html( $num_packages ); ?>
		</td>
		<?php endif; ?>
		<?php if ( $total_weight ) : ?>
		<td>
			<span class="label"><?php esc_html_e( 'Total Weight', 'elevator' ); ?>:</span>
			<?php echo esc_html( $total_weight ); ?>
		</td>
		<?php endif; ?>
		<?php if ( $courier ) : ?>
		<td>
			<span class="label"><?php esc_html_e( 'Courier', 'elevator' ); ?>:</span>
			<?php echo esc_html( $courier ); ?>
		</td>
		<?php endif; ?>
		<?php if ( $packed_by ) : ?>
		<td>
			<span class="label"><?php esc_html_e( 'Packed By', 'elevator' ); ?>:</span>
			<?php echo esc_html( $packed_by ); ?>
		</td>
		<?php endif; ?>
	</tr>
</table>
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->get_type(), $this->order ); ?>

<!-- ITEMS TABLE: Product Code | Qty | Description -->
<table class="order-details">
	<thead>
		<tr>
			<th class="product-code"><?php esc_html_e( 'Product Code', 'elevator' ); ?></th>
			<th class="quantity"><?php esc_html_e( 'Qty', 'elevator' ); ?></th>
			<th class="product"><?php esc_html_e( 'Description', 'elevator' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $this->get_order_items() as $item_id => $item ) : ?>
			<tr class="<?php echo esc_attr( $item['row_class'] ); ?>">
				<td class="product-code"><?php echo ! empty( $item['sku'] ) ? esc_html( $item['sku'] ) : '&mdash;'; ?></td>
				<td class="quantity"><?php echo esc_html( $item['quantity'] ); ?></td>
				<td class="product">
					<p class="item-name"><?php echo esc_html( $item['name'] ); ?></p>
					<?php if ( ! empty( $item['meta'] ) ) : ?>
						<div class="item-meta"><?php echo wp_kses_post( $item['meta'] ); ?></div>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<div class="bottom-spacer"></div>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->get_type(), $this->order ); ?>

<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->get_type(), $this->order ); ?>
<?php if ( $this->get_shipping_notes() ) : ?>
	<div class="customer-notes">
		<h3><?php $this->customer_notes_title(); ?></h3>
		<?php $this->shipping_notes(); ?>
	</div>
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->get_type(), $this->order ); ?>

<?php if ( $this->get_footer() ) : ?>
	<htmlpagefooter name="docFooter">
		<div id="footer">
			<?php $this->footer(); ?>
		</div>
	</htmlpagefooter>
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_after_document', $this->get_type(), $this->order ); ?>