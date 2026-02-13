<?php if (!defined('ABSPATH'))
  exit; // Exit if accessed directly ?>



<?php get_header(); ?>

<!-- Banner start -->
<section id="internal-banner-v2" class="carousel slide" data-bs-ride="carousel">
  <div class="container">

    <div class="banner-image">
      <img src="/wp-content/uploads/2025/02/AI-elevator-2.png" alt="Elevator image created through AI" />
    </div>

    <div class="banner-row">
      <div class="banner-logo">
        <?php $logoMain = get_field('logo', 'options'); ?>
        <img id="logo-banner" src="<?php echo esc_url($logoMain['url']); ?>"
          alt="<?php echo esc_attr($logoMain['alt']); ?>">
      </div>

      <div class="banner-row-title">

        <div class="icon">
          <div class="icon"><i class="fas fa-newspaper"></i></div>
        </div>

        <div class="banner-text">
          <div class="banner-text">
            <h1><span>Latest news</span><span>Information and case studies</span></h1>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
<section class="breadcrumbs py-3">
  <div class="container">
    <?php if (function_exists('rank_math_the_breadcrumbs'))
      rank_math_the_breadcrumbs(); ?>
  </div>
</section>

<div class="container main-new-wrap">
  <section id="main-news" class="container-fluid position-relative py-5">

    <div class="container ">
      <div class=" archive">
        <?php if (have_posts()): ?>
          <?php while (have_posts()):
            the_post(); ?>
            <?php
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $image_id = get_post_thumbnail_id(get_the_ID());

            if (!has_post_thumbnail()) {
              $postbackupImage = get_field('news_backup_image', 'option');
              $featured_img_url = $postbackupImage['url'];
            }
            ?>
            <div class="single-archive-element">
              <a href="<?php echo esc_url(get_permalink()); ?>">
                <span class="archive-thumbnail">
                  <?php if (has_post_thumbnail()) {
                    the_post_thumbnail();
                  } ?>
                </span>
                <span class="pos-title">
                  <h3 class="card-title"><?php the_title(); ?></h3>
                  <span class="primary-button">Read More</span>
                </span>
              </a>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
      <div class="pagination">
        <?php
        global $wp_query;
        $big = 999999999; // A large number to replace with actual paged value
        
        echo paginate_links(array(
          'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
          'format' => '?paged=%#%',
          'current' => max(1, get_query_var('paged')),
          'total' => $wp_query->max_num_pages,
          'prev_text' => '',
          'next_text' => '',
        ));
        ?>
      </div>

    </div>

  </section>


  <section id="news-sidebar">
    <div class="container">
      <?php get_sidebar('news'); ?>
    </div>
  </section>
</div>

<?php get_template_part('modules/devide'); ?>


<?php get_footer();

