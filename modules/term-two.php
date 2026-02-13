<?php if( get_field('terms_content_two') ): ?>
<section id="term-two" class="container-fluid py-5 px-0">

    <div class="container">
        <div class="row">
            <?php echo get_field('terms_content_two'); ?>
        </span>
        </div>
    </div>
</section>
<?php get_template_part('modules/devide'); ?>
<?php endif; ?>