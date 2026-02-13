<?php if (!defined('ABSPATH'))
	exit; // Exit if accessed directly ?>


<!-- Banner start -->
<section id="internal-banner-v2" class="carousel slide" data-bs-ride="carousel">
	<div class="container">


		<div class="banner-image">
			<?php $banner_image = get_field('internal_banner_image'); ?>
			<img src="<?php echo esc_url($banner_image['url']); ?>"
				alt="<?php echo esc_attr($banner_image['alt']); ?>" />
		</div>

		<div class="banner-row">

			<div class="banner-logo">
				<?php $logoMain = get_field('logo', 'options'); ?>
				<img id="logo-banner" src="<?php echo esc_url($logoMain['url']); ?>"
					alt="<?php echo esc_attr($logoMain['alt']); ?>">
			</div>

			<div class="banner-row-title">
				<div class="icon">
					<?php if (get_field('banner_icon')): ?>
						<div class="icon"><i class="fas <?php the_field('banner_icon'); ?>"></i></div>
					<?php endif; ?>
				</div>
				<div class="banner-text">
					<div class="banner-text">
						<h2><span><?php echo esc_html(get_field('internal_banner_title_top')); ?></span><span><?php echo esc_html(get_field('internal_banner_title_bottom')); ?></span>
						</h2>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</section>
<section class="breadcrumbs py-3">
	<div class="container">
		<?php if (function_exists('rank_math_the_breadcrumbs'))
			rank_math_the_breadcrumbs(); ?>
	</div>
</section>