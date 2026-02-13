
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( get_field('whatsapp_title','option') ): ?>
<section id="whatsapp-module" class="container-fluid py-5 px-0">

    <div class="container">
        <div class="row">






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
                    <img src="<?php echo esc_url($parts_logo['url']); ?>" alt="<?php echo esc_attr($parts_logo['alt']); ?>" />
                </span>
            </div>



        </div>




 </div>
</div>
</section>
<?php endif; ?>