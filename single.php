<?php

defined('ABSPATH') or die('Scripts are not allowed!');



get_header(); ?>

<!-- Banner start -->
<section id="internal-banner-v2" class="carousel slide" data-bs-ride="carousel">
  <div class="container">

    <div class="banner-image">
      <?php $banner_image = get_field('internal_banner_image'); ?>
      <img src="/wp-content/uploads/2025/02/AI-elevator-2.png" />
    </div>

    <div class="banner-row">

      <div class="banner-logo">
        <?php $logoMain = get_field('logo', 'options'); ?>
        <img id="logo-banner" src="<?php echo esc_url($logoMain['url']); ?>"
          alt="<?php echo esc_attr($logoMain['alt']); ?>">
      </div>

      <div class="banner-row-title">
        <div class="icon">
          <div class="icon"><i class="fas fa-newspaper"></i></div>
        </div>
        <div class="banner-text">
          <div class="banner-text">
            <h2 class="post-title"><?php the_title(); ?></h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="breadcrumbs py-3">
  <div class="container">
    <?php if (function_exists('rank_math_the_breadcrumbs'))
      rank_math_the_breadcrumbs(); ?>
  </div>
</section>




<div id="single-post" class="container">

  <div id="single-post-content-wrapper" class="container py-5">

    <?php the_content(); ?>

    <p class=""><a class="link-Button primary-button" href="#">Got A Question</a></p>
    <div class="navigation">
      <?php previous_post_link('%link', '<i class="fa-solid fa-circle-arrow-left"></i>Previous');
      next_post_link('%link', 'Next<i class="fa-solid fa-circle-arrow-right"></i>'); ?>
    </div>

  </div>

  <section id="news-sidebar">
    <div class="container">
      <?php get_sidebar('news'); ?>
    </div>
  </section>

</div>
<div class="container single-post-page-whatsapp">
  <div class="whatsapp-area">
    <div class="whatsapp-logo">
      <i class="fab fa-whatsapp"></i>
    </div>
    <div class="whatsapp-text d-flex justify-content-center align-items-center">
      <span>
        <p class="title"><?php echo esc_html(get_field('whatsapp_title', 'option')); ?></p>
        <p class="text"><?php echo esc_html(get_field('whatsapp_text', 'option')); ?></p>
      </span>
      <span class="contacts">
        <a class="whatsapp-phone" href="https://wa.me/447507940266"
          onclick="window.open(this.href, 'LiftPartsIDWhatsApp', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><?php echo esc_html(get_field('whatsapp_number', 'options')); ?></a>
        <a class="whatsapp-email" href="mailto:<?php echo esc_html(get_field('whatsapp_email', 'options')); ?>"
          title="Whatsapp spare email"><?php echo esc_html(get_field('whatsapp_email', 'options')); ?></a>
      </span>
      <span class="image-area">
        <?php $parts_logo = get_field('parts_logo', 'option'); ?>
        <img src="<?php echo esc_url($parts_logo['url']); ?>" alt="<?php echo esc_attr($parts_logo['alt']); ?>" />
      </span>
    </div>



  </div>
</div>


<?php get_footer();

