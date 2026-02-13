<?php
/**
 * Terms content one module.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'get_field' ) && get_field( 'terms_content_one' ) ) : ?>
<section id="term-one" class="container-fluid py-5 px-0">
	<div class="container">
		<div class="row">
			<?php echo wp_kses_post( get_field( 'terms_content_one' ) ); ?>
		</div>
	</div>
</section>
<?php get_template_part( 'modules/devide' ); ?>
<?php endif; ?>