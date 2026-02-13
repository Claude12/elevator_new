<?php
/**
 * The template for displaying single posts.
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
<section id="internal-banner-v2" class="carousel slide" data-bs-ride="carousel">
	<div class="container">

		<div class="banner-image">
			<img src="/wp-content/uploads/2025/02/AI-elevator-2.png"
				alt="<?php esc_attr_e( 'Elevator banner', 'elevator' ); ?>" />
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
		<?php
		if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
			rank_math_the_breadcrumbs();
		}
		?>
	</div>
</section>

<div id="single-post" class="container">

	<div id="single-post-content-wrapper" class="container py-5">

		<?php the_content(); ?>

		<p><a class="link-Button primary-button" href="#"><?php esc_html_e( 'Got A Question', 'elevator' ); ?></a></p>
		<div class="navigation">
			<?php
			previous_post_link( '%link', '<i class="fa-solid fa-circle-arrow-left"></i>' . esc_html__( 'Previous', 'elevator' ) );
			next_post_link( '%link', esc_html__( 'Next', 'elevator' ) . '<i class="fa-solid fa-circle-arrow-right"></i>' );
			?>
		</div>

	</div>

	<section id="news-sidebar">
		<div class="container">
			<?php get_sidebar( 'news' ); ?>
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
				<p class="title"><?php echo esc_html( get_field( 'whatsapp_title', 'option' ) ); ?></p>
				<p class="text"><?php echo esc_html( get_field( 'whatsapp_text', 'option' ) ); ?></p>
			</span>
			<span class="contacts">
				<?php $wa_number = get_field( 'whatsapp_number', 'options' ); ?>
				<a class="whatsapp-phone"
					href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $wa_number ) ); ?>"
					target="_blank" rel="noopener noreferrer">
					<?php echo esc_html( $wa_number ); ?>
				</a>
				<?php $wa_email = get_field( 'whatsapp_email', 'options' ); ?>
				<a class="whatsapp-email"
					href="mailto:<?php echo esc_attr( $wa_email ); ?>"
					title="<?php esc_attr_e( 'Email us', 'elevator' ); ?>">
					<?php echo esc_html( $wa_email ); ?>
				</a>
			</span>
			<span class="image-area">
				<?php $parts_logo = get_field( 'parts_logo', 'option' ); ?>
				<?php if ( $parts_logo && is_array( $parts_logo ) ) : ?>
					<img src="<?php echo esc_url( $parts_logo['url'] ); ?>"
						alt="<?php echo esc_attr( $parts_logo['alt'] ); ?>" />
				<?php endif; ?>
			</span>
		</div>
	</div>
</div>

<?php get_footer(); ?>