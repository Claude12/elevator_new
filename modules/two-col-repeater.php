<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div id="fifty-repeater-container" class="container-fluid py-md-5">
  <div class="container py-3">
    <?php

      if( have_rows('content_repeater') ):
        //logic for re-ordering columns on smaller screens
        $counter = 2;

        $order1 = "";
        $order_lg1 = "";

        $order2 = "";
        $order_lg2 = "";

        while( have_rows('content_repeater') ) : the_row(); ?>

        <div class="row fifty-repeater-row">
          <?php
            if ( $counter % 2 == 0 ) {

              $order1 = "1";
              $order_lg1 = "1";

              $order2 = "2";
              $order_lg2 = "2";

            } else {

              $order1 = "2";
              $order_lg1 = "1";

              $order2 = "1";
              $order_lg2 = "2";
            }

           ?>

          <div class="order-<?php echo $order1; ?> order-lg-<?php echo $order_lg1; ?> col-12 col-lg-6 d-flex flex-column ">
            <?php echo get_sub_field('content_left'); ?>
          </div>
          <div class="order-<?php echo $order2; ?> order-lg-<?php echo $order_lg2; ?> content-right-col col-12 col-lg-6 d-flex flex-column justify-content-center">
            <?php echo get_sub_field('content_right'); ?>
          </div>

       </div>

       <?php $counter++; ?>

<?php endwhile;
    endif; ?>

  </div>
</div>
