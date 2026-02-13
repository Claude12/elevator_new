<?php
/**
 * The Template for displaying product archives, including the main shop page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$logo_main = function_exists( 'get_field' ) ? get_field( 'logo', 'options' ) : false;
$term      = get_queried_object();
?>

<!-- Banner start -->
<section id="internal-banner" class="carousel slide" data-bs-ride="carousel">
	<div class="container">
		<div class="row">

			<div class="col-lg-2 col-sm-12 banner-logo">
				<?php if ( $logo_main && is_array( $logo_main ) ) : ?>
					<img id="logo-banner"
						src="<?php echo esc_url( $logo_main['url'] ); ?>"
						alt="<?php echo esc_attr( $logo_main['alt'] ); ?>">
				<?php endif; ?>
			</div>

			<div class="col-lg-1 col-sm-2 icon d-flex flex-column justify-content-center">
				<?php
				if ( $term instanceof WP_Term && function_exists( 'get_field' ) ) {
					$acf_icon = get_field( 'category_icon', 'product_cat_' . $term->term_id );
					if ( $acf_icon ) {
						echo '<div class="icon"><i class="fas ' . esc_attr( $acf_icon ) . '"></i></div>';
					}
				}
				?>
			</div>

			<div class="col-lg-9 col-sm-10 banner-text d-flex flex-column justify-content-center">
				<div class="banner-text">
					<?php
					if ( is_product_category() && $term instanceof WP_Term && isset( $term->name ) ) {
						echo '<h1 class="woocommerce-category-title">' . esc_html( $term->name ) . '</h1>';
					}
					?>
				</div>
			</div>

		</div>
	</div>
</section>

<section class="breadcrumbs py-3">
	<div class="container">
		<?php
		if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
			rank_math_the_breadcrumbs();
		}
		?>
	</div>
</section>

<section class="container-fluid product-category-main-wrap py-5 px-0">
	<div class="container">
		<div class="row product-category-row">
			<?php
			/**
			 * Hook: woocommerce_before_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 * @hooked WC_Structured_Data::generate_website_data() - 30
			 */
			do_action( 'woocommerce_before_main_content' );

			/**
			 * Hook: woocommerce_shop_loop_header.
			 *
			 * @since 8.6.0
			 *
			 * @hooked woocommerce_product_taxonomy_archive_header - 10
			 */
			do_action( 'woocommerce_shop_loop_header' );

			if ( woocommerce_product_loop() ) {

				/**
				 * Hook: woocommerce_before_shop_loop.
				 *
				 * @hooked woocommerce_output_all_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );

				woocommerce_product_loop_start();

				if ( wc_get_loop_prop( 'total' ) ) {
					while ( have_posts() ) {
						the_post();

						/**
						 * Hook: woocommerce_shop_loop.
						 */
						do_action( 'woocommerce_shop_loop' );

						wc_get_template_part( 'content', 'product' );
					}
				}

				woocommerce_product_loop_end();

				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			}

			/**
			 * Hook: woocommerce_after_main_content.
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action( 'woocommerce_after_main_content' );

			/**
			 * Hook: woocommerce_sidebar.
			 *
			 * @hooked woocommerce_get_sidebar - 10
			 */
			do_action( 'woocommerce_sidebar' );
			?>
		</div>
	</div>
</section>

<div class="container">
	<div class="product-cat-whatsapp-contact">
		<?php get_template_part( 'modules/whatsapp', 'module' ); ?>
	</div>
</div>

<?php get_footer( 'shop' ); ?>