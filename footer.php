<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<footer id="footer" class="container-fluid py-5">
  <div class="container">
    <div class="row p-4 p-md-0 justify-content-between">
      <div class="col-12 col-md-6">
        <div class="add-info-wrapper d-flex align-items-top flex-wrap">
          <?php the_field('footer_info', 'options'); ?>
        </div>
      </div>

      <div class="col-12 col-md-5 foot-contact">
        <div class="quick-links">
            <h3 class="footer-widget-header">Sitemap</h3>
            <!--Quick Links 1-->
            <?php  wp_nav_menu( array(
              'theme_location'    => 'footer-menu-1',
              'depth'             => 1,
              'container'         => 'div',
              'container_class'   => 'footer-submenu',
              'container_id'      => 'footer-menu-1',
              'before'            => '',
            ) );
           ?>
          </div>
    </div>
    </div>
  </div>
</footer>

<div id="copyright">
  <div class="container d-flex justify-content-between align-items-center pt-4 pb-4 flex-wrap">
    <div class="copyright-item">		
      <p><?php
				$copyright_text = get_field('copyright', 'option'); 
				$dynamic_copyright = str_replace('{year}', date('Y'), $copyright_text);
				echo esc_html($dynamic_copyright);
				?>
		</p>
    </div>


    <?php if( have_rows('social_media_repeater', 'options') ): ?>
      <div class="social-media" class="container-fluid py-3">
        <div class="container d-flex justify-content-center align-items-center">
          <?php while ( have_rows('social_media_repeater', 'options') ) : the_row(); ?>
              <a class="mx-3 mx-lg-5" target="_blank" rel="nofollow noopener" href="<?php echo esc_url (get_sub_field('link')); ?>"><i class="fab <?php the_sub_field('icon'); ?>"></i></a>
            <?php endwhile; ?>
        </div>
      </div>
    <?php endif; ?>


    
    <div class="copyright-item">
      <p>Web Design and Marketing by <b><a class="copyright-link" target="_blank" href="https://studioaltitude.co.uk/">Studio Altitude</a></b></p>
    </div>
  </div>
</div>

<?php wp_footer(); ?>

</div>
</body>
</html>
