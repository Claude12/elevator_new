<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( get_field('trade_account_title','option') ): ?>
<section id="trade-account-module" class="container-fluid py-5 px-0">

<div class="container">
    <div class="row">
    <div class="col-12 col-md-4 trade-image" style="background: url(<?php echo esc_url (get_field('trades_image', 'option')); ?>);background-position:center;background-size:cover;">
    </div>
    <div class="col-12 col-md-8 trade-text">
        <h2><?php echo esc_html (get_field('trade_account_title','option')); ?></h2>
        <p><?php echo esc_html (get_field('trade_account_intro','option')); ?></p>
        <h3><?php echo esc_html (get_field('trade_account_sub_title','option')); ?></h3>
        <span>
            <?php echo get_field('trade_list', 'option'); ?>
        </span>
        <a class="primary-button" href="/my-account/" title="">Apply For An Account <i class="fas fa-arrow-right"></i></a>
    </div>
    </div>
</div>


</section>
<?php endif; ?>