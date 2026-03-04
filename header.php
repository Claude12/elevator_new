<?php

/**
 * The header for our theme.
 *
 * @package elevator
 */

if (! defined('ABSPATH')) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<!-- Font Awesome Kit -->
	<script src="https://kit.fontawesome.com/a0506526cd.js" crossorigin="anonymous"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'elevator'); ?></a>

	<!-- Background decorative images -->
	<!-- TODO: These background image paths should ideally be managed via ACF options to avoid hardcoded upload paths. -->
	<img class="bg bg-strip-1 bg-strip" src="/wp-content/uploads/2025/01/strip-right-1.png" alt="">
	<img class="bg bg-image-1" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png" alt="">
	<img class="bg bg-image-2" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png" alt="">
	<img class="bg bg-strip-2 bg-strip" src="/wp-content/uploads/2025/01/strip-left-1.png" alt="">
	<img class="bg bg-image-3" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png" alt="">
	<img class="bg bg-image-4" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png" alt="">
	<img class="bg bg-image-5" src="/wp-content/uploads/2025/03/AdobeStock_65699394-tech-drawing.png" alt="">
	<img class="bg bg-strip-3 bg-strip" src="/wp-content/uploads/2025/01/strip-right-1.png" alt="">
	<img class="bg bg-strip-4 bg-strip" src="/wp-content/uploads/2025/01/strip-left-1.png" alt="">
	<img class="bg bg-logo-1" src="/wp-content/uploads/2025/10/cropped-J12535_EEL_Logo-Final_Brandmark-Logo-Byzantine-Blue-Transparent-1.png" alt="">
	<img class="bg bg-logo-2" src="/wp-content/uploads/2025/10/cropped-J12535_EEL_Logo-Final_Brandmark-Logo-Byzantine-Blue-Transparent-1.png" alt="">
	<img class="bg-logo-3" src="/wp-content/uploads/2025/10/cropped-J12535_EEL_Logo-Final_Brandmark-Logo-Byzantine-Blue-Transparent-1.png" alt="">

	<!-- Header Section Start -->
	<?php $bg_color = function_exists('get_field') ? get_field('background_color', 'options') : ''; ?>
	<header id="header" class="navigation-wrapper container-fluid sticky-top px-0" style="background: <?php echo esc_attr($bg_color); ?>;">

		<?php
		$logo               = function_exists('get_field') ? get_field('logo', 'options') : false;
		$header_number      = function_exists('get_field') ? get_field('header_number', 'options') : '';
		$header_contact_link = function_exists('get_field') ? get_field('header_contact_link', 'options') : '';
		$account_url        = class_exists('WooCommerce') ? esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))) : '';
		$login_url          = class_exists('WooCommerce') ? esc_url(wc_get_page_permalink('myaccount')) : '';
		?>

		<div class="container">
			<div class="row">

				<div class="site-header__inner">

					<!-- Logo -->
					<div class="site-header__logo">
						<?php if ($logo && is_array($logo)) : ?>
							<a href="<?php echo esc_url(home_url('/')); ?>" class="site-header__logo-link">
								<img src="<?php echo esc_url($logo['url']); ?>"
									alt="<?php echo esc_attr($logo['alt']); ?>"
									class="site-header__logo-img" />
							</a>
						<?php endif; ?>
					</div>

					<div class="site-header__inner-actions">

						<!-- Actions -->
						<div class="site-header__actions">

							<!-- Mobile Search Toggle -->
							<button class="hdr-action hdr-action--search-toggle site-header__actions-mobile-only"
								id="hdr-mobile-search-toggle"
								aria-label="<?php esc_attr_e('Open search', 'elevator'); ?>"
								aria-expanded="false">
								<i class="fas fa-search"></i>
							</button>

							<!-- Login / My Account -->
							<?php if (class_exists('WooCommerce')) : ?>
								<?php if (is_user_logged_in()) : ?>
									<a class="hdr-action hdr-action--account"
										href="<?php echo $account_url; ?>"
										data-tooltip="<?php esc_attr_e('Go to My Account', 'elevator'); ?>">
										<i class="fas fa-user"></i>
										<span class="hdr-action__label"><?php esc_html_e('My Account', 'elevator'); ?></span>
									</a>
								<?php else : ?>
									<a class="hdr-action hdr-action--account"
										href="<?php echo $login_url; ?>"
										data-tooltip="<?php esc_attr_e('Login', 'elevator'); ?>">
										<i class="fas fa-user"></i>
										<span class="hdr-action__label"><?php esc_html_e('Login', 'elevator'); ?></span>
									</a>
								<?php endif; ?>
							<?php endif; ?>

							<!-- Cart / Basket -->
							<?php if (function_exists('WC')) : ?>
								<a href="<?php echo esc_url(wc_get_cart_url()); ?>"
									class="hdr-action hdr-action--cart"
									data-tooltip="<?php esc_attr_e('View basket', 'elevator'); ?>">
									<i class="fas fa-shopping-basket"></i>
									<?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
									<span class="hdr-action__badge<?php echo $cart_count ? '' : ' hdr-action__badge--hidden'; ?>">
										<?php echo esc_html($cart_count); ?>
									</span>
								</a>
							<?php endif; ?>

							<!-- Quote Button -->
							<div class="hdr-action hdr-action--quote" data-tooltip="<?php esc_attr_e('Go to quote area', 'elevator'); ?>">
								<?php echo do_shortcode('[addify-mini-quote]'); ?>
							</div>

							<!-- Wishlist -->
							<a class="hdr-action hdr-action--wishlist"
								href="<?php echo esc_url(home_url('/wishlist')); ?>"
								data-tooltip="<?php esc_attr_e('Your Favourites list', 'elevator'); ?>">
								<i class="fa-solid fa-heart"></i>
							</a>

							<!-- Phone -->
							<a class="hdr-action hdr-action--phone"
								href="tel:<?php echo esc_attr($header_number); ?>"
								data-tooltip="<?php esc_attr_e('Call us', 'elevator'); ?>">
								<i class="fas fa-phone-alt"></i>
							</a>

							<!-- Email — hidden on mobile -->
							<?php if ($header_contact_link) : ?>
								<a class="hdr-action hdr-action--email site-header__actions-desktop-only"
									href="<?php echo esc_url($header_contact_link); ?>"
									data-tooltip="<?php esc_attr_e('Send us a message', 'elevator'); ?>">
									<i class="fas fa-envelope"></i>
								</a>
							<?php endif; ?>

						</div><!-- .site-header__actions -->
					</div>

					<!-- Desktop Search -->
					<div class="woo-search d-block-tablet">
						<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
							<label class="screen-reader-text" for="woocommerce-product-search-field-desktop"><?php esc_html_e('Search for:', 'elevator'); ?></label>
							<input type="search" id="woocommerce-product-search-field-desktop" class="search-field"
								placeholder="<?php esc_attr_e('What are you looking for?', 'elevator'); ?>"
								value="<?php echo esc_attr(get_search_query()); ?>" name="s"
								style="background: <?php echo esc_attr($bg_color); ?>;" />
							<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
							<input type="hidden" name="post_type" value="product" />
						</form>
					</div>

				</div><!-- .site-header__inner -->
			</div>
		</div>

		<!-- Mobile Search Drawer -->
		<div class="hdr-search-drawer" id="hdr-search-drawer" aria-hidden="true">
			<div class="hdr-search-drawer__inner">
				<button class="hdr-search-drawer__close" id="hdr-search-drawer-close"
					aria-label="<?php esc_attr_e('Close search', 'elevator'); ?>">
					<i class="fas fa-times"></i>
				</button>
				<form role="search" method="get" class="hdr-search-form" action="<?php echo esc_url(home_url('/')); ?>">
					<label class="screen-reader-text" for="hdr-search-mobile"><?php esc_html_e('Search for:', 'elevator'); ?></label>
					<input type="search"
						id="hdr-search-mobile"
						class="hdr-search-form__input"
						placeholder="<?php esc_attr_e('What are you looking for?', 'elevator'); ?>"
						value="<?php echo esc_attr(get_search_query()); ?>"
						name="s" />
					<button type="submit" class="hdr-search-form__btn" aria-label="<?php esc_attr_e('Search', 'elevator'); ?>">
						<i class="fas fa-search"></i>
					</button>
					<input type="hidden" name="post_type" value="product" />
				</form>
			</div>
		</div>

		<!-- Navigation -->
		<nav id="nav-section" aria-label="<?php esc_attr_e('Primary navigation', 'elevator'); ?>">
			<div class="container">
				<div class="row">
					<div class="navbar-wrapper mx-auto" id="navbarResponsive">
						<div id="main-navbar" class="navbar navbar-expand-md">
							<?php
							wp_nav_menu(
								array(
									'theme_location'  => 'primary',
									'depth'           => 2,
									'container'       => 'div',
									'container_class' => 'collapse navbar-collapse',
									'container_id'    => 'navbarSupportedContent',
									'menu_class'      => 'navbar-nav',
									'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
									'walker'          => new WP_Bootstrap_Navwalker(),
								)
							);
							?>
						</div>
					</div>
				</div>
			</div>
		</nav>

	</header><!-- #header -->

	<div id="entire-content-wrap">