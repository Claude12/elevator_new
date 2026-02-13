<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( get_field('team_repeater','option') ): ?>
<section id="meet-the-team" class="container-fluid py-5 px-5">

    <div class="container">
        <div class="row">
            
            <h2 class="section-title"><?php echo esc_html (get_field('team_title', 'option')); ?></h2>
            <p class="title-intro"><?php echo esc_html (get_field('team_support_text', 'option')); ?></p>

            <div class="team-area">
                <?php if( have_rows('team_repeater','option') ): ?>
                 <ul class="row team-list">
                 <?php while( have_rows('team_repeater','option') ): the_row(); ?>
                 <?php
                // vars
                $memberImage = get_sub_field('member_image','option');
                ?>

                 <li class="col-12 col-sm-6 col-md-4 col-xl-3">
                    <span class="member-wrap">
                    <span class="image-wrap"><img src="<?php echo esc_url( $memberImage['url'] ); ?>" alt="<?php echo esc_attr( $memberImage['alt'] ); ?>"/></span>
                    <span class="info-area">
                        <h2 class="team-title"><?php echo esc_html (get_sub_field('member_name','option')); ?></h2>
                        <p class="team-position"><?php echo esc_html (get_sub_field('member_job','option')); ?></p>
                        <?php if( get_sub_field('linkedin_link','option') ): ?>
                            <a href="<?php echo esc_html (get_sub_field('linkedin_link','option')); ?>" title="Link to LinkedIn profile"><i class="fab fa-linkedin"></i></a>
                        <?php endif; ?>
                    </span>
                    </span>
                    
                    
                     
                 </li>
                 <?php endwhile; ?>
                 </ul>
            <?php endif; ?>
            </div>



        </div>
    </div>
</section>
    <?php endif; ?>