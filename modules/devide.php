<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<section id="devider" class="container-fluid divider">
  <div class="container">
   <?php
      // vars
      $devideImage = get_field('devide_image', 'option');
   ?>
   <img class="divider-image" src="<?php echo esc_url( $devideImage['url'] ); ?>" alt="<?php echo esc_attr( $devideImage['alt'] ); ?>"/>
  </div>
</section>