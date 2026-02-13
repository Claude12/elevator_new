<?php
/**
 * The template for displaying singular pages (WooCommerce pages, etc.).
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$logo_main = function_exists( 'get_field' ) ? get_field( 'logo', 'options' ) : false;
?>

<!-- Banner start -->
<section id="internal-banner-v2" class="short-banner carousel slide" data-bs-ride="carousel">
	<div class="container">

		<div class="banner-image">
			<img src="/wp-content/uploads/2025/02/AI-elevator-2.png"
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
					<div class="icon">
						<?php if ( function_exists( 'is_account_page' ) && is_account_page() ) : ?>
							<i class="fa-solid fa-user"></i>
						<?php else : ?>
							<i class="fas fa-basket-shopping"></i>
						<?php endif; ?>
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

<?php get_footer(); ?>