<?php if (!defined('ABSPATH'))
  exit; // Exit if accessed directly ?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!--  Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Font Awesome Fonts -->
  <script src="https://kit.fontawesome.com/a0506526cd.js" crossorigin="anonymous"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const intervalId = setInterval(() => {
        const widgetAnchor = document.querySelector('a[href^="https://elfsight.com/linkedin-feed-widget"]');
        if (widgetAnchor) {
          widgetAnchor.style.setProperty('display', 'none', 'important');
          // Optionally clear the interval once it's found
          clearInterval(intervalId);
        }
      }, 500); // Check every 500 milliseconds

      // Optionally, stop checking after a certain time to prevent endless loops
      setTimeout(() => {
        clearInterval(intervalId);
      }, 10000); // Stop after 10 seconds
    });


  </script>


  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

  <img class="bg bg-strip-1 bg-strip" src="/wp-content/uploads/2025/01/strip-right-1.png">
  <img class="bg bg-image-1" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png">
  <img class="bg bg-image-2" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png">
  <img class="bg bg-strip-2 bg-strip" src="/wp-content/uploads/2025/01/strip-left-1.png">
  <img class="bg bg-image-3" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png">
  <img class="bg bg-image-4" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png">
  <img class="bg bg-image-5" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png">
  <img class="bg bg-strip-3 bg-strip" src="/wp-content/uploads/2025/01/strip-right-1.png">
  <img class="bg bg-strip-4 bg-strip" src="/wp-content/uploads/2025/01/strip-left-1.png">
  <img class="bg bg-logo-1" src="/wp-content/uploads/2025/10/cropped-J12535_EEL_Logo-Final_Brandmark-Logo-Byzantine-Blue-Transparent-1.png">
  <img class="bg bg-logo-2" src="/wp-content/uploads/2025/10/cropped-J12535_EEL_Logo-Final_Brandmark-Logo-Byzantine-Blue-Transparent-1.png">
  <img class=" bg-logo-3" src="/wp-content/uploads/2025/10/cropped-J12535_EEL_Logo-Final_Brandmark-Logo-Byzantine-Blue-Transparent-1.png">

  <!-- Header Section Start -->
  <section id="header" class="navigation-wrapper container-fluid sticky-top px-0 " style="background: <?php echo esc_attr( get_field('background_color', 'options') ); ?>;">
    <!--TOP BAR -->
    <div class="container header-container">
      <div class="d-flex justify-content-between align-items-center">

        <div class="header__logo-wrapper">
          <?php $logo = get_field('logo', 'options');
          if ($logo): ?>
            <a id="logo-wrapper-head" class="text-left" href="/"><img id="logo-1"
                src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>"></a>
          <?php endif; ?>
        </div>

        <div class="header-contact-area">

          <div class="woo-search d-block-desktop">
            <form role="search" method="get" class="woocommerce-product-search"
              action="<?php echo esc_url(home_url('/')); ?>">
              <label class="screen-reader-text"
                for="woocommerce-product-search-field"><?php esc_html_e('Search for:', 'woocommerce'); ?></label>
              <input type="search" id="woocommerce-product-search-field" class="search-field"
                placeholder="<?php esc_attr_e('What are your looking for?', 'woocommerce'); ?>"
                value="<?php echo get_search_query(); ?>" name="s" style="background: <?php echo esc_attr( get_field('background_color', 'options') ); ?>;" />
              <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
              <input type="hidden" name="post_type" value="product" />
            </form>
          </div>

          <div class="d-block-mobile">
            <!-- Search Icon Trigger -->
            <button id="mobile-search-toggle" class="mobile-search-icon" aria-label="Open search">
              <i class="fas fa-search"></i>
            </button>

            <!-- Search Popup -->
            <div class="woo-search-popup" style="display: none;">
              <div class="woo-search-popup__inner">
                <button class="woo-search-popup__close" aria-label="Close search">&times;</button>
                <div class="woo-search">
                  <form role="search" method="get" class="woocommerce-product-search"
                    action="<?php echo esc_url(home_url('/')); ?>">
                    <label class="screen-reader-text"
                      for="woocommerce-product-search-field"><?php esc_html_e('Search for:', 'woocommerce'); ?></label>
                    <input type="search" id="woocommerce-product-search-field" class="search-field"
                      placeholder="<?php esc_attr_e('What are your looking for?', 'woocommerce'); ?>"
                      value="<?php echo get_search_query(); ?>" name="s" />
                    <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
                    <input type="hidden" name="post_type" value="product" />
                  </form>
                </div>
              </div>
            </div>
          </div>




          <div class="header-login">
            <?php
            // Check if the user is logged in
            if (is_user_logged_in()) {
              // Display a message and a link to the My Account page if the user is logged in
              echo '<a class="tooltips d-block-desktop"
       data-tooltip="Linked to My account page" href="' . esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))) . '">Go to My Account <i class="fas fa-user"></i></a>
       <a class="tooltips d-block-mobile"
       data-tooltip="Linked to My account page" href="' . esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))) . '"><i class="fas fa-user"></i></a>';
            } else {
              // Display a login link if the user is not logged in
              $login_url = wc_get_page_permalink('myaccount');
              echo '<a class="tooltips d-block-desktop"
       data-tooltip="Linked to login page" href="' . esc_url($login_url) . '">Login <i class="fas fa-user"></i></a>
       <a class="tooltips d-block-mobile"
       data-tooltip="Linked to login page" href="' . esc_url($login_url) . '"><i class="fas fa-user"></i></a>';
            }
            ?>
          </div>




          <div class="header-basket">
            <?php
            // Ensure WooCommerce functions are available
            if (function_exists('WC')) {
              ?>
              <div class="header-cart">
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link">
                  <span class="cart-icon d-block-desktop">Basket <i class="fas fa-shopping-basket"></i></span>
                  <span class="cart-icon d-block-mobile"><i class="fas fa-shopping-basket"></i></span>
                  <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </a>
                <!-- <div class="cart-details">
              <p class="cart-total">
                  Total: <?php // echo wp_kses_post( WC()->cart->get_cart_total() ); ?>
              </p>
            </div> -->
              </div>
              <?php
            }
            ?>

          </div>


          <ul class="quote-button tooltips" data-tooltip="Go to quote area">
            <?php
            // Assuming you're using WordPress and have a shortcode like [example_shortcode]
            echo do_shortcode('[addify-mini-quote]');
            ?>
            </ul>

          <a class="wishlist header-phone tooltips" data-tooltip="Your Favourites List" href="/wishlist"><i
              class="fa-solid fa-heart"></i></a>
          <a class="header-phone tooltips" data-tooltip="Call us"
            href="tel:<?php echo esc_html(get_field('header_number', 'options')); ?>" title="Direct call number"><i
              class="fas fa-phone-alt"></i></a>
          <a class="header-message tooltips d-block-desktop" data-tooltip="Send us a message"
            href="<?php echo esc_html(get_field('header_contact_link', 'options')); ?>" title="Direct message link"><i
              class="fas fa-envelope"></i></a>


        </div>

      </div>
    </div>
    <!-- NAVBAR -->
    <div id="nav-section" class="">
      <div class="container">
        <div class="row">
          <div class="navbar-wrapper mx-auto" id="navbarResponsive">
            <nav id="main-navbar" class="navbar navbar-expand-md">

              <?php
              wp_nav_menu(array(
                'theme_location' => 'primary',
                'depth' => 2,
                'container' => 'div',
                'container_class' => 'collapse navbar-collapse',
                'container_id' => 'navbarSupportedContent',
                'menu_class' => 'navbar-nav',
                'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
                'walker' => new WP_Bootstrap_Navwalker(),
              ));
              ?>
            </nav>
          </div>
        </div>
      </div>
    </div>



  </section>
  <div id="entire-content-wrap">