<?php
defined( 'ABSPATH' ) or die( 'Scripts are not allowed!' );
/* Template name: Full Width */

get_header();
get_template_part('modules/internal', 'banner'); ?>

<div class="container-fluid">
  <div class="standard-content container">
    <?php the_content(); ?>
  </div>
</div>
<div class="end-devider">
	<?php get_template_part('modules/devide'); ?>
</div>



<?php get_footer();
