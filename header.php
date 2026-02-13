<?php
/**
 * The header for our theme.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
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

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'elevator' ); ?></a>

<!-- Background decorative images -->
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
<header id="header" class="navigation-wrapper container-fluid sticky-top px-0" style="background: <?php echo esc_attr( get_field( 'background_color', 'options' ) ); ?>;">

	<!-- Top Bar -->
	<div class="container header-container">
		<div class="d-flex justify-content-between align-items-center">

			<!-- Logo -->
			<div class="header__logo-wrapper">
				<?php
				$logo = get_field( 'logo', 'options' );
				if ( $logo ) :
					?>
					<a id="logo-wrapper-head" class="text-left" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img id="logo-1" src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ); ?>">
					</a>
				<?php endif; ?>
			</div>

			<!-- Header Contact Area -->
			<div class="header-contact-area">

				<!-- Desktop Search -->
				<div class="woo-search d-block-desktop">
					<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<label class="screen-reader-text" for="woocommerce-product-search-field-desktop"><?php esc_html_e( 'Search for:', 'elevator' ); ?></label>
						<input type="search" id="woocommerce-product-search-field-desktop" class="search-field"
							placeholder="<?php esc_attr_e( 'What are you looking for?', 'elevator' ); ?>"
							value="<?php echo get_search_query(); ?>" name="s"
							style="background: <?php echo esc_attr( get_field( 'background_color', 'options' ) ); ?>;" />
						<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
						<input type="hidden" name="post_type" value="product" />
					</form>
				</div>

				<!-- Mobile Search -->
				<div class="d-block-mobile">
					<button id="mobile-search-toggle" class="mobile-search-icon" aria-label="<?php esc_attr_e( 'Open search', 'elevator' ); ?>">
						<i class="fas fa-search"></i>
					</button>
					<div class="woo-search-popup" style="display: none;">
						<div class="woo-search-popup__inner">
							<button class="woo-search-popup__close" aria-label="<?php esc_attr_e( 'Close search', 'elevator' ); ?>">&times;</button>
							<div class="woo-search">
								<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
									<label class="screen-reader-text" for="woocommerce-product-search-field-mobile"><?php esc_html_e( 'Search for:', 'elevator' ); ?></label>
									<input type="search" id="woocommerce-product-search-field-mobile" class="search-field"
										placeholder="<?php esc_attr_e( 'What are you looking for?', 'elevator' ); ?>"
										value="<?php echo get_search_query(); ?>" name="s" />
									<button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
									<input type="hidden" name="post_type" value="product" />
								</form>
							</div>
						</div>
					</div>
				</div>

				<!-- Login / My Account -->
				<div class="header-login">
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<?php if ( is_user_logged_in() ) : ?>
							<?php $account_url = esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>
							<a class="tooltips d-block-desktop" data-tooltip="<?php esc_attr_e( 'Go to My Account', 'elevator' ); ?>" href="<?php echo $account_url; ?>">
								<?php esc_html_e( 'My Account', 'elevator' ); ?> <i class="fas fa-user"></i>
							</a>
							<a class="tooltips d-block-mobile" data-tooltip="<?php esc_attr_e( 'Go to My Account', 'elevator' ); ?>" href="<?php echo $account_url; ?>">
								<i class="fas fa-user"></i>
							</a>
						<?php else : ?>
							<?php $login_url = esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>
							<a class="tooltips d-block-desktop" data-tooltip="<?php esc_attr_e( 'Login', 'elevator' ); ?>" href="<?php echo $login_url; ?>">
								<?php esc_html_e( 'Login', 'elevator' ); ?> <i class="fas fa-user"></i>
							</a>
							<a class="tooltips d-block-mobile" data-tooltip="<?php esc_attr_e( 'Login', 'elevator' ); ?>" href="<?php echo $login_url; ?>">
								<i class="fas fa-user"></i>
							</a>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<!-- Basket -->
				<div class="header-basket">
					<?php if ( function_exists( 'WC' ) ) : ?>
						<div class="header-cart">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-link">
								<span class="cart-icon d-block-desktop"><?php esc_html_e( 'Basket', 'elevator' ); ?> <i class="fas fa-shopping-basket"></i></span>
								<span class="cart-icon d-block-mobile"><i class="fas fa-shopping-basket"></i></span>
								<span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
							</a>
						</div>
					<?php endif; ?>
				</div>

				<!-- Quote Button (Addify) -->
				<ul class="quote-button tooltips" data-tooltip="<?php esc_attr_e( 'Go to quote area', 'elevator' ); ?>">
					<?php echo do_shortcode( '[addify-mini-quote]' ); ?>
				</ul>

				<!-- Wishlist -->
				<a class="wishlist header-phone tooltips" data-tooltip="<?php esc_attr_e( 'Your Favourites List', 'elevator' ); ?>" href="/wishlist"><i class="fa-solid fa-heart"></i></a>

				<!-- Phone -->
				<a class="header-phone tooltips" data-tooltip="<?php esc_attr_e( 'Call us', 'elevator' ); ?>"
					href="tel:<?php echo esc_attr( get_field( 'header_number', 'options' ) ); ?>" title="<?php esc_attr_e( 'Direct call number', 'elevator' ); ?>">
					<i class="fas fa-phone-alt"></i>
				</a>

				<!-- Email -->
				<a class="header-message tooltips d-block-desktop" data-tooltip="<?php esc_attr_e( 'Send us a message', 'elevator' ); ?>"
					href="<?php echo esc_url( get_field( 'header_contact_link', 'options' ) ); ?>" title="<?php esc_attr_e( 'Contact us', 'elevator' ); ?>">
					<i class="fas fa-envelope"></i>
				</a>

			</div><!-- .header-contact-area -->

		</div>
	</div><!-- .header-container -->

	<!-- Navigation -->
	<nav id="nav-section" aria-label="<?php esc_attr_e( 'Primary navigation', 'elevator' ); ?>">
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