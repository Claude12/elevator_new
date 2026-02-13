<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
// Get selected category from ACF options, fallback to default slug
$selected_category = get_field('feed_category', 'option');
$category_slug = ($selected_category && is_object($selected_category)) ? $selected_category->slug : 'products-spotlight';
$category_link = ($selected_category && is_object($selected_category)) ? get_term_link($selected_category) : '/product-category/products-spotlight/';
?>

<section id="products-spotlight-feed" class="container-fluid py-md-5">
  <div class="container">
    <div class="row">
      <h2>Products spotlight....</h2>

      <!-- Desktop Grid View -->
      <div class="feed-wrap row d-none d-md-flex">
        <?php get_products_from_category($category_slug); ?>
      </div>

      <!-- Mobile Slider -->
      <div id="productCarousel" class="carousel slide d-md-none unique-arrow-product-spotlight-feed" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php
          $products = get_products_from_category($category_slug, true);
          if (!empty($products)) {
            foreach ($products as $index => $product) {
              ?>
              <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <div class="d-flex justify-content-center">
                  <?php echo $product; ?>
                </div>
              </div>
              <?php
            }
          }
          ?>
        </div>

        <div class="carousel-controls-right">
          <button class="carousel-control-prev" type="button" data-bs-target=".unique-arrow-product-spotlight-feed" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target=".unique-arrow-product-spotlight-feed" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
          </button>
        </div>
      </div>

      <a class="simple-link" href="<?php echo esc_url($category_link); ?>" title="Link to product category">
        View all spotlight products <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</section>
