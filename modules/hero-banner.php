<?php if (! defined('ABSPATH')) exit; ?>

<!-- Banner start -->
<section id="hero-banner" class="carousel slide" data-bs-ride="carousel">

  <div class="banner-area">

    <div class="container">

      <div class="banner-image">
        <?php $banner_image = get_field('banner_image'); ?>
        <img src="<?php echo esc_url($banner_image['url']); ?>" alt="<?php echo esc_attr($banner_image['alt']); ?>" />
      </div>

      <div class="row">

        <!-- Left Column -->
        <div class="col-12 col-lg-6">
          <div class="banner-content">
            <?php echo get_field('banner_content_left'); ?>
          </div>
          <div class="banner-buttons">
            <a class="whatsapp" href="https://wa.me/447507940266" onclick="window.open(this.href, 'LiftPartsIDWhatsApp', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
              Find My Part <i class="fab fa-whatsapp"></i>
            </a>
            <?php $parts_logo = get_field('banner_logo'); ?>
            <img src="<?php echo esc_url($parts_logo['url']); ?>" alt="<?php echo esc_attr($parts_logo['alt']); ?>" />
          </div>
        </div>

        <!-- Right Column - Random Products -->
        <div class="col-12 col-lg-6">
          <h2 class="banner-slider-title">Top essential parts</h2>

          <?php
          $products_args = array(
            'post_type'      => 'product',
            'posts_per_page' => 3,
            'orderby'        => 'rand',
            'tax_query'      => array(
              array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'top-essential-parts',
              ),
            ),
          );

          $products_query = new WP_Query($products_args);

          if ($products_query->have_posts()) : ?>

            <!-- Swiper wrapper (mobile only) -->
            <div class="banner-products swiper d-lg-none">
              <div class="swiper-wrapper">

                <?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
                  <div class="banner-product-item swiper-slide">
                    <a href="<?php the_permalink(); ?>">
                      <?php the_post_thumbnail('medium'); ?>
                      <span class="info-wrap">
                        <h3><?php the_title(); ?></h3>
                        <p class="primary-button"><?php esc_html_e('View Product', 'elevator'); ?></p>
                      </span>
                    </a>
                  </div>
                <?php endwhile; ?>

              </div>

              <!-- Pagination -->
              <div class="swiper-pagination"></div>
            </div>

            <!-- Desktop grid (no slider) -->
            <div class="banner-products d-none d-lg-flex">
              <?php
              $products_query->rewind_posts();
              while ($products_query->have_posts()) : $products_query->the_post(); ?>
                <div class="banner-product-item">
                  <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium'); ?>
                    <span class="info-wrap">
                      <h3><?php the_title(); ?></h3>
                      <div class="info-wrap__cta">
                        <button class="primary-button"><?php esc_html_e('View Product', 'elevator'); ?></button>
                      </div>
                    </span>
                  </a>
                </div>
              <?php endwhile; ?>
            </div>

          <?php else : ?>
            <p><?php esc_html_e('No products found in this category.', 'elevator'); ?></p>
          <?php endif;

          wp_reset_postdata(); ?>

        </div>
        <!-- /Right Column -->

        <a class="scroll-to-button" href="#usp-module" title="scroll to categories">
          <i class="fas fa-arrow-circle-down"></i><span>Scroll Down</span>
        </a>

      </div><!-- /.row -->

    </div><!-- /.container -->

  </div><!-- /.banner-area -->

</section>
<!-- /Banner end -->

<!-- Swiper Init -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (window.innerWidth < 992) {
      new Swiper(".banner-products.swiper", {
        slidesPerView: 1.2,
        spaceBetween: 16,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
      });
    }
  });
</script>

<style>
  .banner-products {
    gap: 24px;
    padding-bottom: 100px;
    margin-top: 24px;
  }

  .banner-product-item {
    flex: 1;
    background-color: #f2f2f2;
    border-radius: 20px;
    overflow: hidden;
    text-align: center;
  }

  .banner-product-item img {
    height: 200px;
    width: 100%;
    object-fit: cover;
  }

  .banner-product-item h3 {
    font-size: 14px;
    height: 75px;
    padding: 24px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .banner-product-item .primary-button {
    font-size: 12px;
    padding: 10px 24px;
    background: #021338;
  }

  .banner-product-item .info-wrap__cta {
    width: 100%;
    text-align: center;
    margin-top: 16px;
    margin-bottom: 16px;
  }

  .banner-product-item .primary-button:hover {
    background: #154ed3;
  }

  @media (max-width: 992px) {
    .banner-products {
      padding-bottom: 48px;
    }

    .banner-product-item {
      flex: none;
    }

    .banner-product-item h3 {
      height: 26px;
    }
  }
</style>