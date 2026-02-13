<?php
/**
 * Category module — shows top/bottom category links and WhatsApp area.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The original checked 'usp_repeater' which is the wrong field. Use category fields instead.
$has_top_cats    = function_exists( 'have_rows' ) && have_rows( 'top_cat_links_repeater', 'option' );
$has_bottom_cats = function_exists( 'have_rows' ) && have_rows( 'bottom_cat_link_repeater', 'option' );

if ( $has_top_cats || $has_bottom_cats ) : ?>
<section id="category-module" class="container-fluid py-5 px-0">
	<div class="container">
		<div class="row">

			<div class="top-row">
				<h2 class="section-title"><?php echo esc_html( get_field( 'cat_heading', 'option' ) ); ?></h2>
				<div class="d-flex module justify-content-between align-items-center">
					<p class="title-intro"><?php echo esc_html( get_field( 'cat_support_text', 'option' ) ); ?></p>

					<div class="woo-search">
						<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<label class="screen-reader-text" for="woocommerce-product-search-field-cat">
								<?php esc_html_e( 'Search for:', 'elevator' ); ?>
							</label>
							<input type="search" id="woocommerce-product-search-field-cat" class="search-field"
								placeholder="<?php esc_attr_e( 'What are you looking for?', 'elevator' ); ?>"
								value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
							<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
							<input type="hidden" name="post_type" value="product" />
						</form>
					</div>
				</div>
			</div>

			<?php if ( $has_top_cats ) : ?>
			<div class="top-cats">
				<ul class="row cat-list">
					<?php
					while ( have_rows( 'top_cat_links_repeater', 'option' ) ) :
						the_row();
						$product_image = get_sub_field( 'top_cat_image' );
						$cat_title     = get_sub_field( 'top_cat_title' );
						$cat_link      = get_sub_field( 'top_cat_link' );
						?>
						<li class="col-6 col-sm-6 col-lg-3 col-xl-3">
							<a href="<?php echo esc_url( $cat_link ); ?>"
								title="<?php echo esc_attr( sprintf( __( 'Link to %s category page', 'elevator' ), $cat_title ) ); ?>">
								<h2 class="cat-title"><?php echo esc_html( $cat_title ); ?></h2>
								<?php if ( $product_image && is_array( $product_image ) ) : ?>
									<span class="image-wrap">
										<img src="<?php echo esc_url( $product_image['url'] ); ?>"
											alt="<?php echo esc_attr( $product_image['alt'] ); ?>" />
									</span>
								<?php endif; ?>
								<span class="primary-button-area">
									<span class="primary-button"><?php esc_html_e( 'View Products', 'elevator' ); ?></span>
								</span>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
			<?php endif; ?>

			<div class="whatsapp-area">
				<div class="whatsapp-logo">
					<i class="fab fa-whatsapp"></i>
				</div>
				<div class="whatsapp-text d-flex justify-content-center align-items-center">
					<span>
						<p class="title"><?php echo esc_html( get_field( 'whatsapp_title', 'option' ) ); ?></p>
						<p class="text"><?php echo esc_html( get_field( 'whatsapp_text', 'option' ) ); ?></p>
					</span>
					<span class="contacts">
						<?php $wa_number = get_field( 'whatsapp_number', 'options' ); ?>
						<a class="whatsapp-phone" href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $wa_number ) ); ?>"
							target="_blank" rel="noopener noreferrer">
							<?php echo esc_html( $wa_number ); ?>
						</a>
						<?php $wa_email = get_field( 'whatsapp_email', 'options' ); ?>
						<a class="whatsapp-email" href="mailto:<?php echo esc_attr( $wa_email ); ?>"
							title="<?php esc_attr_e( 'Email us', 'elevator' ); ?>">
							<?php echo esc_html( $wa_email ); ?>
						</a>
					</span>
					<span class="image-area">
						<?php $parts_logo = get_field( 'parts_logo', 'option' ); ?>
						<?php if ( $parts_logo && is_array( $parts_logo ) ) : ?>
							<img src="<?php echo esc_url( $parts_logo['url'] ); ?>"
								alt="<?php echo esc_attr( $parts_logo['alt'] ); ?>" />
						<?php endif; ?>
					</span>
				</div>
			</div>

			<?php if ( $has_bottom_cats ) : ?>
			<div class="bottom-cats">
				<ul class="row cat-list">
					<?php
					while ( have_rows( 'bottom_cat_link_repeater', 'option' ) ) :
						the_row();
						$product_image = get_sub_field( 'bottom_cat_image' );
						$cat_title     = get_sub_field( 'bottom_cat_title' );
						$cat_link      = get_sub_field( 'bottom_cat_link' );
						?>
						<li class="col-6 col-sm-6 col-lg-3 col-xl-3">
							<a href="<?php echo esc_url( $cat_link ); ?>"
								title="<?php echo esc_attr( sprintf( __( 'Link to %s category page', 'elevator' ), $cat_title ) ); ?>">
								<h2 class="cat-title"><?php echo esc_html( $cat_title ); ?></h2>
								<?php if ( $product_image && is_array( $product_image ) ) : ?>
									<span class="image-wrap">
										<img src="<?php echo esc_url( $product_image['url'] ); ?>"
											alt="<?php echo esc_attr( $product_image['alt'] ); ?>" />
									</span>
								<?php endif; ?>
								<span class="primary-button-area">
									<span class="primary-button"><?php esc_html_e( 'View Products', 'elevator' ); ?></span>
								</span>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
			<?php endif; ?>

		</div>
	</div>
</section>
<?php endif; ?>