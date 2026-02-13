<?php
/**
 * New products feed module.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section id="new-products-feed" class="container-fluid py-md-5">
	<div class="container">
		<div class="row">
			<h2><?php esc_html_e( 'New products....', 'elevator' ); ?></h2>

			<!-- Desktop Grid View -->
			<div class="feed-wrap row d-none d-md-flex">
				<?php get_products_from_category( 'new-products' ); ?>
			</div>

			<!-- Mobile Slider -->
			<div id="newProductCarousel" class="carousel slide d-md-none unique-arrow-new-product-feed" data-bs-ride="carousel">
				<div class="carousel-inner">
					<?php
					// get_products_from_category returns HTML strings, not WC_Product objects.
					$products = get_products_from_category( 'new-products', true );

					if ( ! empty( $products ) ) :
						foreach ( $products as $index => $product_html ) :
							?>
							<div class="carousel-item <?php echo 0 === $index ? 'active' : ''; ?>">
								<div class="d-flex justify-content-center">
									<?php echo $product_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
				<div class="carousel-controls-right">
					<button class="carousel-control-prev" type="button" data-bs-target=".unique-arrow-new-product-feed" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target=".unique-arrow-new-product-feed" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
					</button>
				</div>
			</div>

			<a class="simple-link" href="<?php echo esc_url( home_url( '/product-category/new-products/' ) ); ?>"
				title="<?php esc_attr_e( 'Link to product category', 'elevator' ); ?>">
				<?php esc_html_e( 'View all new products', 'elevator' ); ?>
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
</section>