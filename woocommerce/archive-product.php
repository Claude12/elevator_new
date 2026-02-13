<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>


<!-- Banner start -->
<section id="internal-banner" class="carousel slide" data-bs-ride="carousel">
  <div class="container">	  
    <div class="row">
      
      <div class="col-lg-2 col-sm-12 banner-logo">
        <?php $logoMain = get_field('logo', 'options'); ?>
        <img id="logo-banner" src="<?php echo esc_url($logoMain['url']); ?>" alt="<?php echo esc_attr($logoMain['alt']); ?>">
      </div>
      <div class="col-lg-1 col-sm-2  icon d-flex flex-column justify-content-center">
      	<?php
					// Get the current category
					$term = get_queried_object();
					// Get the ACF field value
					$acf_field_value = get_field('category_icon', 'product_cat_' . $term->term_id);
					// Display the field value if it exists
					if ($acf_field_value) {
						echo '<div class="icon"><i class="fas ' . esc_html($acf_field_value) . '"></i></div>';
					}
		?> 
      </div>
      <div class="col-lg-9 col-sm-10 banner-text d-flex flex-column justify-content-center">
        <div class="banner-text">
          <?php
if (is_product_category()) {
    // Get the current product category object
    $term = get_queried_object();

    if ($term && isset($term->name)) {
        // Output the category title
        echo '<h1 class="woocommerce-category-title">' . esc_html($term->name) . '</h1>';
    }
}
?>
        </div>
      </div>

    </div>
  </div>
</section>
<section class="breadcrumbs py-3">
  <div class="container">
     <?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
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
<script> // Equal Height For Columns
	window.onload = function () {
    let maxHeight = 0;
    let products = document.querySelectorAll('.archive ul.products li.product a.woocommerce-LoopProduct-link');

    if (products.length == 0) {
        products = document.querySelectorAll('.archive ul.products li.product a[aria-label^="Visit product"]');
    }    
    if (products.length > 0) {
        setTimeout(() => { 
            products.forEach(product => {
                let productHeight = product.offsetHeight;
                if (productHeight > maxHeight) {
                    maxHeight = productHeight;
                }
            });
            products.forEach(product => {
                product.style.height = maxHeight + "px";
            });
        }, 100); 
    }
}



</script>
<div class="container">
	<div class="product-cat-whatsapp-contact">
	   <?php get_template_part('modules/whatsapp', 'module'); ?>
   </div>
</div>


<?php get_footer( 'shop' );?>

