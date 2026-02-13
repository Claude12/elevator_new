
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( get_field('map_module','option') ): ?>
<section id="map-module" class="container-fluid py-5 px-0">

    <div class="container">
        <div class="row">
            <?php echo get_field('map_module','option'); ?>
        </div>
    </div>
</section>
<?php endif; ?>