<?php if (!defined('ABSPATH'))
  exit; // Exit if accessed directly ?>

<section id="suppliers-logo" class="container-fluid py-md-5">
  <div class="container">
    <h2>The suppliers we work with</h2>
    <p>Some of the great companies we work with to give you the best options for parts.</p>

    <?php if (have_rows('supplier_logo_repeater')): ?>
      <div class="swiper supplier-swiper">
        <div class="swiper-wrapper">
          <?php while (have_rows('supplier_logo_repeater')):
            the_row(); ?>
            <?php
            $supplierImage = get_sub_field('supplier_logo_image');
            $supplierLink = get_sub_field('supplier_logo_link');

            $supplierUrl = is_array($supplierLink) && isset($supplierLink['url']) ? $supplierLink['url'] : '';
            $supplierTarget = is_array($supplierLink) && isset($supplierLink['target']) ? $supplierLink['target'] : '_self';
            ?>
            <div class="swiper-slide">
              <?php if ($supplierUrl): ?>
                <a href="<?php echo esc_url($supplierUrl); ?>" target="<?php echo esc_attr($supplierTarget); ?>"
                  rel="noopener noreferrer">
                  <span class="image-wrap">
                    <img src="<?php echo esc_url($supplierImage['url']); ?>"
                      alt="<?php echo esc_attr($supplierImage['alt']); ?>" />
                  </span>
                </a>
              <?php else: ?>
                <span class="image-wrap">
                  <img src="<?php echo esc_url($supplierImage['url']); ?>"
                    alt="<?php echo esc_attr($supplierImage['alt']); ?>" />
                </span>
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const supplierSwiper = new Swiper('.supplier-swiper', {
      loop: true,
      slidesPerView: 3,
      spaceBetween: 20,
      speed: 5000,
      autoplay: {
        delay: 0,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },
      grabCursor: true,
      breakpoints: {
        768: { slidesPerView: 4 },
        992: { slidesPerView: 5 },
        1200: { slidesPerView: 6 },
      }
    });
  });
</script>

<style>
  #suppliers-logo {
    padding-top: 60px;
    padding-bottom: 60px;
  }

  .supplier-swiper {
    position: relative;
    overflow: hidden;
    padding: 20px 0;
  }

  .supplier-swiper .swiper-slide {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .supplier-swiper .swiper-slide img {
    max-width: 100%;
    max-height: 150px;
    width: auto;
    height: auto;
    object-fit: contain;
  }

  .supplier-swiper .swiper-slide img:hover {
    opacity: 0.7;
  }

  .supplier-swiper .swiper-wrapper {
    transition-timing-function: linear !important;
  }
</style>