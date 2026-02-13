<?php if( get_field('terms_content_one') ): ?>
<section id="term-one" class="container-fluid py-5 px-0">

    <div class="container">
        <div class="row">
            <?php echo get_field('terms_content_one'); ?>
        </span>
        </div>
    </div>
</section>
<?php get_template_part('modules/devide'); ?>
<?php endif; ?>