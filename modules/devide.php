<?php
/**
 * Divider module.
 *
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$devide_image = function_exists( 'get_field' ) ? get_field( 'devide_image', 'option' ) : false;

if ( $devide_image && is_array( $devide_image ) ) : ?>
<section id="devider" class="container-fluid divider">
	<div class="container">
		<img class="divider-image"
			src="<?php echo esc_url( $devide_image['url'] ); ?>"
			alt="<?php echo esc_attr( $devide_image['alt'] ); ?>" />
	</div>
</section>
<?php endif; ?>