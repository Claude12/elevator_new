<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<section id="social-feed-area" class="container-fluid py-md-5">
  <div class="container">
    <div class="row">
    <div class="top-bar">
    <h2>Keep up to date with us on our socials</h2>
    <a class="facebook-button" href="https://www.facebook.com/ElevatorEquipmentLtd/" title="Link to facebook" target="_blank"><i class="fab fa-facebook"></i></a>
    <a class="x-button" href="https://x.com/ElevatorEEL" title="Link to Twitter" target="_blank"><i class="fa-solid fa-x"></i></a>
    </div>
    
    <div class="col-12 col-lg-6">
      <?php echo do_shortcode('[instagram-feed feed=1]'); ?>
    </div>
    <div class="col-12 col-lg-6">
      <script src="https://static.elfsight.com/platform/platform.js" async></script>
      <div class="elfsight-app-35004c08-4c3e-4c4a-a9b1-c05bcb97c891" data-elfsight-app-lazy></div>
		<?php // echo do_shortcode('[linkedin_profile]');?>
    </div>
    </div>
  </div>
</section>
