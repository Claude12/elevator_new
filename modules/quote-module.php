<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( get_field('usp_repeater','option') ): ?>
<section id="quote-module" class="container-fluid py-5 px-5">

<div class="container">
    <div class="row gy-5">
    <div class="col-12 col-md-4 trade-image">
        <?php $quote_image = get_field('quote_image', 'option'); ?>
            <img src="<?php echo esc_url($quote_image['url']); ?>" alt="<?php echo esc_attr($quote_image['alt']); ?>" />
    </div>
    <div class="col-12 col-md-8 quote-text">
        <h2><?php echo esc_html (get_field('quote_title','option')); ?></h2>
        <p><?php echo esc_html (get_field('quote_intro','option')); ?></p>
        <h3><?php echo esc_html (get_field('quote_sub_title','option')); ?></h3>
        <div class="list">
            <?php echo get_field('quote_left','option'); ?>
        </div>
        <div class="list">
            <?php echo get_field('quote_right','option'); ?>
        </div>

        <a class="primary-button" href="" title="">Apply For An Account <i class="fas fa-arrow-right"></i></a>
    </div>
    </div>
</div>


</section>
<?php endif; ?>