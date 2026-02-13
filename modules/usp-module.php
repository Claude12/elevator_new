<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( get_field('usp_repeater','option') ): ?>
<section id="usp-module" class="container-fluid py-5 px-0 custom-usp-class">


    <div class="usp-area-wrap">
  		<div class="container">

        <h2 class="section-title"><?php echo esc_html (get_field('usp_heading', 'option')); ?></h2>
        <p class="title-intro"><?php echo esc_html (get_field('usp_support_text', 'option')); ?></p>

  				<?php if( have_rows('usp_repeater','option') ): ?>
  		         <ul class="row usp-list">
  		         <?php while( have_rows('usp_repeater','option') ): the_row(); ?>
                 

  		         <li class="col-12 col-md-6 col-lg-3">
                 <div class="usp-wrap d-flex justify-content-center">
                 <i class="fas <?php the_sub_field('icon'); ?>"></i>       
  		         <?php echo esc_html (get_sub_field('usp_title','option')); ?>
                 </div>
  		         </li>
  		         <?php endwhile; ?>
  		         </ul>
  		      <?php endif; ?>
  	</div>
  </div>



  </section>
  <?php endif; ?>