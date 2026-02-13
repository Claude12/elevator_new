<?php if (!defined('ABSPATH'))
  exit; // Exit if accessed directly ?>
<?php get_header(); ?>
<section id="internal-banner-v2" class="carousel slide" data-bs-ride="carousel">
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
            <i class="fa-sharp-duotone fa-solid fa-ban"></i>
          </div>
        </div>
        <div class="banner-text">
          <div class="banner-text">
            <h1 class="text-white woo-custom-title"> Elevator Not Found, But We’ll Lift You Up! </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
<div class="error-page-wrapper d-flex flex-column">
  <div id="internal-banner-404"
    class="container-fluid py-5 d-flex justify-content-center align-items-center animated-bg"
    style="background: url(<?php echo esc_url($background_image); ?>); background-position: center; background-size: cover; background-attachment: fixed;">
    <div class="container text-white text-center">
      <p class="error-2-text p-3 animated-text" style="animation-delay: 0.4s;">The Door Didn’t Open, But We’re Here to
        Help!</p>


      <div class="animation-container bounce-animation" style="margin: 30px auto; width: 120px;">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
          viewBox="0 0 64 64" xml:space="preserve">
          <circle fill="#FFFFFF" cx="32" cy="32" r="30" stroke="#154ed3" stroke-width="2" />
          <text x="32" y="40" font-size="20" text-anchor="middle" fill="#000">404</text>
        </svg>
      </div>

      <div style="height:30px;"></div>
      <a class="error-2-text primary-button animated-text" style="animation-delay: 0.6s;" href="/">Return to
        Homepage</a>
    </div>
  </div>

  <?php get_template_part('modules/social-media', 'strip'); ?>

  <?php get_footer(); ?>
</div>