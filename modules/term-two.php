<?php
/**
 * Terms content two module.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'get_field' ) && get_field( 'terms_content_two' ) ) : ?>
<section id="term-two" class="container-fluid py-5 px-0">
	<div class="container">
		<div class="row">
			<?php echo wp_kses_post( get_field( 'terms_content_two' ) ); ?>
		</div>
	</div>
</section>
<?php get_template_part( 'modules/devide' ); ?>
<?php endif; ?>