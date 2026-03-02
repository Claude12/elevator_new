<?php
defined('ABSPATH') or die('Scripts are not allowed!');

get_header(); ?>

<!-- Banner start -->
<section id="internal-banner-v2" class=" short-banner carousel slide" data-bs-ride="carousel">
  <div class="container">

    <div class="banner-image">
      <?php $banner_image = get_field('internal_banner_image'); ?>
      <img src="/wp-content/uploads/2025/02/AI-elevator-2.png" alt="Elevator image created through AI" />
    </div>

    <div class="banner-row">
      <div class="banner-logo">
        <?php $logoMain = get_field('logo', 'options'); ?>
        <img id="logo-banner" src="<?php echo esc_url($logoMain['url']); ?>"
          alt="<?php echo esc_attr($logoMain['alt']); ?>">
      </div>

      <div class="banner-row-title">

        <div class="icon">
          <div class="icon">
            <?php if (is_account_page()) { ?>
              <i class="fa-solid fa-user"></i>
            <?php } else { ?>
              <i class="fas fa-basket-shopping"></i>
            <?php } ?>
          </div>
        </div>

        <div class="banner-text">
          <div class="banner-text">
            <h1 class="text-white woo-custom-title"><?php the_title(); ?></h1>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>

<div class="container-fluid">
  <div class="container py-5">
    <?php the_content(); ?>
  </div>
</div>

<?php get_footer();
