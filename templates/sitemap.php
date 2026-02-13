<?php if ( ! defined( 'ABSPATH') ) exit; // Exit if accessed directly

/* Template name: Sitemap */

get_header(); ?>

<!-- Default Header -->
<?php get_template_part('modules/internal', 'banner'); ?>

<div id="sitemap" class="container-fluid pb-5 mb-5">
	<div class="container">
      <div class="pb-5 text-center">
        <?php the_content(); ?>
      </div>

			<h2 class="py-3">Pages:</h2>
				<ul class="sitemap-pages sitemap-list">
            <?php wp_list_pages(array('exclude' => '', 'title_li' => '')); // Exclude pages by ID ?>
        </ul>
        <?php  wp_reset_query(); ?>

        <br>

        <h2 class="py-3">Product Categories</h2>
        <?php
// Fetch WooCommerce product categories
$product_categories = get_terms([
    'taxonomy'   => 'product_cat', // WooCommerce product categories
    'hide_empty' => false, // Set to true to hide empty categories
]); ?>
<ul class="cat-posts sitemap-list">
<?php if (!empty($product_categories) && !is_wp_error($product_categories)) {
    foreach ($product_categories as $category) { ?>
        
            <li>
                <a class="sitemap-link" href="<?php echo get_term_link($category); ?>" title="<?php echo esc_attr($category->name); ?>">
                    <?php echo esc_html($category->name); ?>
                </a>
            </li>
    
    <?php }
}
?>
</ul>




			  <h2 class="py-3">Posts:</h2>

          <?php
            $categories = get_categories('exclude='); // Exclude categories by ID
            foreach ($categories as $cat) {
            ?>

            <ul class="cat-posts sitemap-list">
                <?php
                    query_posts('posts_per_page=-1&cat='.$cat->cat_ID); //-1 shows all posts per category. 1 to show most recent post.
                    while(have_posts()):

                      the_post();
                      $category = get_the_category();
                        if ($category[0]->cat_ID == $cat->cat_ID) { ?>
                          <li><a class="sitemap-link" href="<?php the_permalink() ?>"  title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
                  <?php }
                    endwhile; ?>
            </ul>
				<?php } ?>
				<?php  wp_reset_query(); ?>

	</div>
</div>

<?php get_footer();
