<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$logo_main        = function_exists( 'get_field' ) ? get_field( 'logo', 'options' ) : false;
$background_image = function_exists( 'get_field' ) ? get_field( 'error_background_image', 'options' ) : false;
?>

<section id="internal-banner-v2" class="carousel slide" data-bs-ride="carousel">
	<div class="container">

		<div class="banner-image">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/AI-elevator-2.png' ); ?>"
				alt="<?php esc_attr_e( 'Elevator image', 'elevator' ); ?>" />
		</div>

		<div class="banner-row">
			<div class="banner-logo">
				<?php if ( $logo_main && is_array( $logo_main ) ) : ?>
					<img id="logo-banner"
						src="<?php echo esc_url( $logo_main['url'] ); ?>"
						alt="<?php echo esc_attr( $logo_main['alt'] ); ?>">
				<?php endif; ?>
			</div>
			<div class="banner-row-title">
				<div class="icon">
					<i class="fa-sharp-duotone fa-solid fa-ban"></i>
				</div>
				<div class="banner-text">
					<h1 class="text-white woo-custom-title">
						<?php esc_html_e( 'Elevator Not Found, But We\'ll Lift You Up!', 'elevator' ); ?>
					</h1>
				</div>
			</div>
		</div>

	</div>
</section>

<div class="error-page-wrapper d-flex flex-column">
	<div id="internal-banner-404"
		class="container-fluid py-5 d-flex justify-content-center align-items-center animated-bg"
		style="<?php echo ( $background_image && is_array( $background_image ) ) ? 'background: url(' . esc_url( $background_image['url'] ) . '); background-position: center; background-size: cover; background-attachment: fixed;' : 'background-color: #154ed3;'; ?>">
		<div class="container text-white text-center">
			<p class="error-2-text p-3 animated-text" style="animation-delay: 0.4s;">
				<?php esc_html_e( "The Door Didn't Open, But We're Here to Help!", 'elevator' ); ?>
			</p>

			<div class="animation-container bounce-animation" style="margin: 30px auto; width: 120px;">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
					<circle fill="#FFFFFF" cx="32" cy="32" r="30" stroke="#154ed3" stroke-width="2" />
					<text x="32" y="40" font-size="20" text-anchor="middle" fill="#000">404</text>
				</svg>
			</div>

			<div style="height:30px;"></div>
			<a class="error-2-text primary-button animated-text" style="animation-delay: 0.6s;"
				href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Return to Homepage', 'elevator' ); ?>
			</a>
		</div>
	</div>

	<?php get_template_part( 'modules/social-media', 'strip' ); ?>
</div>

<?php get_footer(); ?>