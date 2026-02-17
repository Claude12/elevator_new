<?php
/**
 * Shortcodes
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Slider Shortcode - [product_slider]
 *
 * Renders a product category slider using Swiper.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function elevator_product_slider_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'category'       => '',
			'posts_per_page' => 10,
		),
		$atts
	);

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => $atts['posts_per_page'],
		'tax_query'      => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $atts['category'],
			),
		),
	);

	$query = new WP_Query( $args );
	ob_start();

	if ( $query->have_posts() ) {
		?>
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
					<div class="swiper-slide">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'medium' ); ?>
							<span class="info-wrap">
								<h3><?php the_title(); ?></h3>
								<p class="primary-button"><?php esc_html_e( 'View Product', 'elevator' ); ?></p>
							</span>
						</a>
					</div>
				<?php endwhile; ?>
			</div>
			<!-- Add Pagination and Navigation -->
			<div class="swiper-pagination"></div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				new Swiper('.swiper-container', {
					slidesPerView: 3,
					spaceBetween: 20,
					pagination: {
						el: '.swiper-pagination',
						clickable: true,
					},
					navigation: {
						nextEl: '.swiper-button-next',
						prevEl: '.swiper-button-prev',
					},
					loop: true,
					breakpoints: {
						1400: {
							slidesPerView: 3,
							spaceBetween: 15,
						},
						768: {
							slidesPerView: 2,
							spaceBetween: 20,
						},
						576: {
							slidesPerView: 2,
							spaceBetween: 10,
						},
						200: {
							slidesPerView: 1,
							spaceBetween: 10,
						}
					}
				});
			});
		</script>
		<?php
	} else {
		echo '<p>' . esc_html__( 'No products found in this category.', 'elevator' ) . '</p>';
	}

	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'product_slider', 'elevator_product_slider_shortcode' );

/**
 * Divider Shortcode - [divider]
 *
 * @return string HTML output.
 */
function elevator_divider_shortcode() {
	ob_start();
	get_template_part( 'modules/devide' );
	return ob_get_clean();
}
add_shortcode( 'divider', 'elevator_divider_shortcode' );

/**
 * LinkedIn Profile Shortcode - [linkedin_profile]
 *
 * @return string HTML output.
 */
function elevator_linkedin_profile_shortcode() {
	$linkedin_url = get_field( 'linkedin_url', 'option' );

	if ( ! $linkedin_url ) {
		return '<p>' . esc_html__( 'LinkedIn profile URL not set.', 'elevator' ) . '</p>';
	}

	// Extract the company username or ID from URL.
	$parsed_url  = wp_parse_url( $linkedin_url );
	$path_parts  = explode( '/', trim( $parsed_url['path'], '/' ) );
	$company_name = end( $path_parts );

	return '<script src="https://platform.linkedin.com/in.js" type="text/javascript"></script>
			<script type="IN/FollowCompany" data-id="' . esc_attr( $company_name ) . '" data-counter="right"></script>';
}
add_shortcode( 'linkedin_profile', 'elevator_linkedin_profile_shortcode' );
