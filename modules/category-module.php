<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( get_field('usp_repeater','option') ): ?>
<section id="category-module" class="container-fluid py-5 px-0">

    <div class="container">
        <div class="row">
            
        <div class="top-row">
            <h2 class="section-title"><?php echo esc_html (get_field('cat_heading', 'option')); ?></h2>
			<div class="d-flex module justify-content-between align-items-center">			
			
            <p class="title-intro"><?php echo esc_html (get_field('cat_support_text', 'option')); ?></p>

            <div class="woo-search">
            <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e( 'Search for:', 'woocommerce' ); ?></label>
            <input type="search" id="woocommerce-product-search-field" class="search-field" placeholder="<?php esc_attr_e( 'What are you looking for?', 'elevator' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
            <input type="hidden" name="post_type" value="product" />
          </form>
          </div>
            
			</div></div>

        <div class="top-cats">
            
            <?php if( have_rows('top_cat_links_repeater','option') ): ?>
                 <ul class="row cat-list">
                 <?php while( have_rows('top_cat_links_repeater','option') ): the_row(); ?>
                 <?php
                // vars
                $productImage = get_sub_field('top_cat_image','option');
                ?>

                 <li class="col-6 col-sm-6 col-lg-3 col-xl-3">
                    <a href="<?php echo esc_url( get_sub_field('top_cat_link','option') ); ?>" title="Link to <?php echo esc_html (get_sub_field('top_cat_title','option')); ?> category page">
                    <h2 class="cat-title"><?php echo esc_html (get_sub_field('top_cat_title','option')); ?></h2>
                    <?php if ( $productImage ) : ?>
                    <span class="image-wrap"><img src="<?php echo esc_url( $productImage['url'] ); ?>" alt="<?php echo esc_attr( $productImage['alt'] ); ?>"/></span>
                    <?php endif; ?>
                     <span class="primary-button-area">
                       <span class="primary-button">View Products</span>
                     </span>
                    </a>
                 </li>
                 <?php endwhile; ?>
                 </ul>
            <?php endif; ?>

        </div>
        

        <div class="whatsapp-area">
            <div class="whatsapp-logo">
                <i class="fab fa-whatsapp"></i>
            </div>
            <div class="whatsapp-text d-flex justify-content-center align-items-center">
                <span>
                    <p class="title"><?php echo esc_html (get_field('whatsapp_title','option')); ?></p>
                    <p class="text"><?php echo esc_html (get_field('whatsapp_text','option')); ?></p>
                </span>
                <span class="contacts">
                    <a class="whatsapp-phone" href="https://wa.me/447507940266" onclick="window.open(this.href, 'LiftPartsIDWhatsApp', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"><?php echo esc_html (get_field('whatsapp_number','options')); ?></a>
                    <a class="whatsapp-email" href="mailto:<?php echo esc_html (get_field('whatsapp_email','options')); ?>" title="Whatsapp spare email"><?php echo esc_html (get_field('whatsapp_email','options')); ?></a>
                </span>
                <span class="image-area">
                    <?php $parts_logo = get_field('parts_logo','option'); ?>
                    <?php if ( $parts_logo ) : ?>
                    <img src="<?php echo esc_url($parts_logo['url']); ?>" alt="<?php echo esc_attr($parts_logo['alt']); ?>" />
                    <?php endif; ?>
                </span>
            </div>

        </div>



        <div class="bottom-cats">
            
              <?php if( have_rows('bottom_cat_link_repeater','option') ): ?>
                 <ul class="row cat-list">
                 <?php while( have_rows('bottom_cat_link_repeater','option') ): the_row(); ?>
                 <?php
                // vars
                $product2Image = get_sub_field('bottom_cat_image','option');
                ?>

                 <li class="col-6 col-sm-6 col-lg-3 col-xl-3">
                    <a href="<?php echo esc_url( get_sub_field('bottom_cat_link','option') ); ?>" title="Link to <?php echo esc_html (get_sub_field('bottom_cat_title','option')); ?> category page">
                    <h2 class="cat-title"><?php echo esc_html (get_sub_field('bottom_cat_title','option')); ?></h2>
                    <?php if ( $product2Image ) : ?>
                    <span class="image-wrap"><img src="<?php echo esc_url( $product2Image['url'] ); ?>" alt="<?php echo esc_attr( $product2Image['alt'] ); ?>"/></span>
                    <?php endif; ?>
                     <span class="primary-button-area">
                       <span class="primary-button">View Products</span>
                     </span>
                    </a>
                 </li>
                 <?php endwhile; ?>
                 </ul>
            <?php endif; ?>

        </div>

        </div>
    </div>
   
  </section>
  <?php endif; ?>