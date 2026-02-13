<?php
defined( 'ABSPATH' ) or die( 'Scripts are not allowed!' );
/* Template name: Terms */

get_header();
get_template_part('modules/internal', 'banner'); ?>

<div class="container-fluid">
  <div class="standard-content container">
    <?php the_content(); ?>
  </div>
</div>
<?php get_template_part('modules/devide'); ?>
<?php get_template_part('modules/category', 'module'); ?>



<?php get_footer();
