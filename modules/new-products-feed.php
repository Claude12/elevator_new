<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<section id="new-products-feed" class="container-fluid py-md-5">
  <div class="container">
    <div class="row">
    	<h2>New products....</h2>
		 <!-- Desktop Grid View -->
      <div class="feed-wrap row d-none d-md-flex">
        <?php get_products_from_category('new-products'); ?>
      </div>

      <!-- Mobile Slider -->
      <div id="productCarousel" class="carousel slide d-md-none unique-arrow-new-product-feed" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
$products = get_products_from_category('new-products', true);

if (!empty($products)) {
  foreach ($products as $index => $product) {
    if (!$product instanceof WC_Product) continue;

    // Get the 2nd image from the gallery
    $gallery_ids = $product->get_gallery_image_ids();
    if (isset($gallery_ids[1])) {
      $image_html = wp_get_attachment_image($gallery_ids[1], 'woocommerce_thumbnail');
    } else {
      // Fallback to featured image
      $image_html = $product->get_image('woocommerce_thumbnail');
    }
    ?>
    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
      <div class="d-flex justify-content-center">
        <?php echo $image_html; ?>
      </div>
    </div>
    <?php
  }
}
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
    <a class="simple-link" href="/product-category/new-products/" title="Link to product category">View all new products <i class="fas fa-arrow-circle-right"></i></a>
  </div>
  </div>
</section>