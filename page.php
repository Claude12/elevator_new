<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package elevator
 */

// If this is a WooCommerce page (Cart, Checkout, My Account), use singular template.
if (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page())) {
	get_template_part('singular');
	return;
}

// If this is the Request a Quote page, use singular template (no sidebar).
if (is_page('request-a-quote')) {
	get_template_part('singular');
	return;
}

get_header();
?>

<main id="primary" class="site-main">

	<?php
	while (have_posts()) :
		the_post();

		get_template_part('template-parts/content', 'page');

		// If comments are open or we have at least one comment, load up the comment template.
		if (comments_open() || get_comments_number()) :
			comments_template();
		endif;

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
