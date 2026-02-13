<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>


<!-- Banner start -->
<section id="hero-banner" class="carousel slide" data-bs-ride="carousel">


      <div class="banner-area">
        

      <div class="container">
                <div class="banner-image">
        <?php $banner_image = get_field('banner_image'); ?>
            <img src="<?php echo esc_url($banner_image['url']); ?>" alt="<?php echo esc_attr($banner_image['alt']); ?>" />
        </div>
        <div class="row">
          <div class="col-12 col-lg-6">
            <div class="banner-content">
              <?php echo get_field('banner_content_left'); ?>
            </div>
            <div class="banner-buttons">
              <a class="whatsapp" href="https://wa.me/447507940266" onclick="window.open(this.href, 'LiftPartsIDWhatsApp', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">Find My Part <i class="fab fa-whatsapp"></i></a>
              <?php $parts_logo = get_field('banner_logo'); ?>
              <img src="<?php echo esc_url($parts_logo['url']); ?>" alt="<?php echo esc_attr($parts_logo['alt']); ?>" />
            </div>
          </div>
          <div class="col-12 col-lg-6">
            <h2 class="banner-slider-title">Top essential parts</h2>
            <?php echo do_shortcode('[product_slider category="top-essential-parts" posts_per_page="3"]'); ?>



          </div>
           <a class="scroll-to-button" href="#usp-module" title="scroll to categories"><i class="fas fa-arrow-circle-down"></i><span>Scroll Down</span></a>
        </div>

      </div>


      </div>


</section>





