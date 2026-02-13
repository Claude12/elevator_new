<?php
defined( 'ABSPATH' ) or die( 'Scripts are not allowed!' );
/* Template name: Thank You */

get_header();
get_template_part('modules/internal', 'banner');

?>

<div class="container-fluid py-5">
  <div class="container py-5">
    <?php the_content(); ?>
    <div class="row py-5">
      <div class="col-12 col-lg-6 my-3 text-center">
        <a class="button-primary" href="/news/">Read Latest News</a>
      </div>
      <div class="col-12 col-lg-6 my-3 text-center">
        <a class="button-primary" href="/">Back to Home</a>
      </div>
    </div>
  </div>
</div>

<?php get_footer();
