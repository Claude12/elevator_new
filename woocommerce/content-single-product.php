<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );
	?>
<div class="customized-section">
	
		
<h1 class="product_title entry-title"><?php the_title(); ?></h1>

	<?php
    // Add the short description manually
    $short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);
    if ( ! empty($short_description) ) : ?>
        <div class="product-short-description">
            <?php echo $short_description; ?>
        </div>
    <?php endif; ?>

    <?php 
    // Check if the user is logged in or not
    if ( ! is_user_logged_in() ) : ?>
        <!-- Display the sign-up message if the user is logged out -->
        <p class="price-out"><a class="tooltip"
       data-tooltip="Login to see the product price" href="/my-account/">LOGIN TO SEE PRICES<img class="login-icon" src="/wp-content/uploads/2025/02/login.png"></a></p>
    <?php else : ?>
        <!-- Display the actual price if the user is logged in -->
        <p class="price"><?php echo $product->get_price_html(); ?></p>
    <?php endif; ?>
</div>
	<div class="summary entry-summary">

		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );
		?>

		<?php if( get_field('how_to_file','options') ): ?>
<!-- Trigger Button -->
<div class="how-to">
    <a href="#how-to-modal" 
       class="how-to-popup-trigger"
       data-tooltip="How to use order form on product page">
       <?php echo esc_html(get_field('how_to_button_text', 'options')); ?>
    </a>
</div>

<!-- Modal Markup -->
<div id="custom-modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="model-inner-wrap" >   
		<h2 class="modal-title">
		  <?php 
			echo esc_html( get_field('order_form_title', 'options') );  
			 ?>
		</h2>  
		<div class="modal-body">
		  <?php echo get_field('order_form_content', 'options'); ?>
		</div>
	 </div>
  </div>
</div>



	</div>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
   // Get the modal element
   var modal = document.getElementById("custom-modal");
   
   // Get the button that opens the modal
   var btn = document.querySelector(".how-to-popup-trigger");
   
   // Get the <span> element that closes the modal
   var span = document.querySelector(".modal .close");

   // When the user clicks the button, open the modal 
   btn.addEventListener("click", function(e) {
      e.preventDefault();  // Prevent default link behavior
      modal.style.display = "block";
   });

   // When the user clicks on <span> (x), close the modal
   span.addEventListener("click", function() {
      modal.style.display = "none";
   });

   // When the user clicks anywhere outside of the modal content, close it
   window.addEventListener("click", function(event) {
      if (event.target == modal) {
         modal.style.display = "none";
      }
   });
});
</script>



<?php do_action( 'woocommerce_after_single_product' ); ?>
