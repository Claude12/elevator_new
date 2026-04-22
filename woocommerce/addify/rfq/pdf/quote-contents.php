<?php
/**
 * Addify quote-contents-table for PDF - V3 WITH PRODUCT IMAGES
 * Shows product images and reorganized pricing columns
 *
 * @package addify-request-a-quote
 * @version 1.6.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'addify_rfq_before_pdf_quote_contents' );

$allowed_user_roles = get_option('afrfq_enable_for_specific_user_role');

$user_id        = get_post_meta( $quote_id, '_customer_user', true );
$user           = ! empty( $user_id ) ? get_user_by( 'id', intval( $user_id ) ) : null;
$user_role      = is_object( $user ) ? $user->roles : array( 'guest' );

$price_display    = 'yes' === get_option( 'afrfq_enable_pro_price' ) && ( empty($allowed_user_roles) || !empty(array_intersect($user_role, $allowed_user_roles)) ) ? true : false;
$of_price_display = 'yes' === get_option( 'afrfq_enable_off_price' ) && ( empty($allowed_user_roles) || !empty(array_intersect($user_role, $allowed_user_roles)) ) ? true : false;
$tax_display      = 'yes' === get_option( 'afrfq_enable_tax' ) && ( empty($allowed_user_roles) || !empty(array_intersect($user_role, $allowed_user_roles)) ) ? true : false;

$total_quanity = 0;
?>
<h3><?php echo esc_html__( 'Quote Contents', 'addify_b2b' ); ?></h3>
<table style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" cellspacing="0" cellpadding="6" border="1">
	<thead>
		<tr>
			<th scope="col" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:center;width:12%">
				<strong><?php echo esc_html__( 'Image', 'addify_b2b' ); ?></strong>
			</th>
			<th scope="col" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;width:25%">
				<strong><?php echo esc_html__( 'Product', 'addify_b2b' ); ?></strong>
			</th>
			<th scope="col" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;width:12%">
				<strong><?php echo esc_html__( 'SKU', 'addify_b2b' ); ?></strong>
			</th>
			<?php if ( $price_display ) : ?>
				<th scope="col" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;width:12%">
					<strong><?php echo esc_html__( 'Price', 'addify_b2b' ); ?></strong>
				</th>
				<th scope="col" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;width:10%">
					<strong><?php echo esc_html__( 'Discount %', 'addify_b2b' ); ?></strong>
				</th>
			<?php endif; ?>
			<th scope="col" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;width:10%">
				<strong><?php echo esc_html__( 'Qty', 'addify_b2b' ); ?></strong>
			</th>
			<?php if ( $price_display ) : ?>
				<th scope="col" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;width:12%">
					<strong><?php echo esc_html__( 'Net Value', 'addify_b2b' ); ?></strong>
				</th>
			<?php endif; ?>
			<?php if ( $of_price_display ) : ?>
				<th scope="col" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left">
					<strong><?php echo esc_html__( 'Offered Net Value', 'addify_b2b' ); ?></strong>
				</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( (array) $quote_contents as $key => $item ) :

			if ( ! isset( $item['data'] ) || ! is_object( $item['data'] ) ) {
				continue;
			}

			$product        = isset( $item['data'] ) ? $item['data'] : '';
			$total_quanity += $item['quantity'];
			$price          = empty( $item['addons_price'] ) ? $product->get_price() : $item['addons_price'];
			$offered_price  = isset( $item['offered_price'] ) ? floatval( $item['offered_price'] ) : $price;
			
			// Calculate discount percentage
			$discount_percentage = 0;
			if ( $of_price_display && $price > 0 ) {
				$discount_percentage = round(( ( $price - $offered_price ) / $price ) * 100, 2);
			}
			
			// Get product image
			$product_image = '';
			if ( has_post_thumbnail( $product->get_id() ) ) {
				$product_image = get_the_post_thumbnail_url( $product->get_id(), 'thumbnail' );
			}
			?>
			<tr>
				<!-- Product Image -->
				<td style="color:#000000;border:1px solid #e5e5e5;padding:8px;text-align:center;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
					<?php if ( $product_image ) : ?>
						<img src="<?php echo esc_url( $product_image ); ?>" width="60" height="60" alt="<?php echo esc_attr( $product->get_name() ); ?>">
					<?php else : ?>
						<span style="color: #999; font-size: 10px;">No image</span>
					<?php endif; ?>
				</td>

				<!-- Product Name & SKU -->
				<td style="color:#000000;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word">
					<strong><?php echo esc_html( $product->get_name() ); ?></strong>
					<?php echo wp_kses_post( wc_get_formatted_cart_item_data( $item ) ); ?>
				</td>

				<!-- SKU -->
				<td style="color:#000000;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word">
					<?php echo esc_html( $product->get_sku() ); ?>
				</td>

				<!-- Price -->
				<?php if ( $price_display ) : ?>
					<td style="color:#000000;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
						<?php echo wp_kses_post( wc_price( $price ) ); ?>
					</td>
					
					<!-- Discount % -->
					<td style="color:#000000;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
						<?php echo esc_html( $discount_percentage > 0 ? $discount_percentage . '%' : '-' ); ?>
					</td>
				<?php endif; ?>

				<!-- Quantity -->
				<td style="color:#000000;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
					<?php echo esc_attr( $item['quantity'] ); ?>
				</td>

				<!-- Net Value (Price × Qty) -->
				<?php if ( $price_display ) : ?>
					<td style="color:#000000;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
						<?php echo wp_kses_post( wc_price( $price * $item['quantity'] ) ); ?>
					</td>
				<?php endif; ?>

				<!-- Offered Net Value -->
				<?php if ( $of_price_display && ( !isset($item['composite_child_products']) || empty($item['composite_child_products']) ) ) : ?>
					<td style="color:#000000;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
						<?php echo wp_kses_post( wc_price( $offered_price * $item['quantity'] ) ); ?>
					</td>
				<?php elseif ($of_price_display) : ?>
					<td></td>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<?php if ( $price_display ) : ?>
			<tr>
				<th scope="row" colspan="6" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">
					<strong><?php echo esc_html__( 'Total (Standard)', 'addify_b2b' ); ?>:</strong>
				</th>
				<td style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">
					<?php echo wp_kses_post( wc_price( $quote_total ) ); ?>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ( $of_price_display ) : ?>
			<tr>
				<th scope="row" colspan="6" style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">
					<strong><?php echo esc_html__( 'Offered Total', 'addify_b2b' ); ?>:</strong>
				</th>
				<td style="color:#000000;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px">
					<?php echo wp_kses_post( wc_price( isset( $offered_total ) ? $offered_total : 0 ) ); ?>
				</td>
			</tr>
		<?php endif; ?>
	</tfoot>
</table>

<?php do_action( 'addify_rfq_after_pdf_quote_contents' ); ?>