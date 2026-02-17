<?php
/**
 * WooCommerce Compatibility File.
 *
 * @link https://woocommerce.com/
 * @package elevator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare WooCommerce support.
 */
function elevator_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 150,
			'single_image_width'    => 300,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'elevator_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 */
function elevator_woocommerce_scripts() {
	$woo_css_path = get_template_directory() . '/woocommerce.css';

	// Only enqueue if the file actually exists.
	if ( file_exists( $woo_css_path ) ) {
		wp_enqueue_style(
			'elevator-woocommerce-style',
			get_template_directory_uri() . '/woocommerce.css',
			array(),
			filemtime( $woo_css_path )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'elevator_woocommerce_scripts' );

/**
 * NOTE: We intentionally keep WooCommerce default stylesheets enabled.
 * They handle the product grid, gallery, and form layout.
 * Custom overrides go in woocommerce.css (when created).
 */

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param array $classes CSS classes applied to the body tag.
 * @return array
 */
function elevator_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';
	return $classes;
}
add_filter( 'body_class', 'elevator_woocommerce_active_body_class' );

/**
 * Related Products Args.
 *
 * @param array $args Related products args.
 * @return array
 */
function elevator_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);
	return wp_parse_args( $defaults, $args );
}
add_filter( 'woocommerce_output_related_products_args', 'elevator_woocommerce_related_products_args' );

/**
 * Remove default WooCommerce content wrappers.
 *
 * Our archive-product.php and single-product.php templates provide
 * their own container markup, so the default <main> wrapper is not needed.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Remove default WooCommerce breadcrumbs.
 *
 * We use Rank Math breadcrumbs instead.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

if ( ! function_exists( 'elevator_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments — update cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array
	 */
	function elevator_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		elevator_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'elevator_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'elevator_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link — displayed in header.
	 */
	function elevator_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'elevator' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'elevator' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			<span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'elevator_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 */
	function elevator_woocommerce_header_cart() {
		$class = is_cart() ? 'current-menu-item' : '';
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php elevator_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php the_widget( 'WC_Widget_Cart', array( 'title' => '' ) ); ?>
			</li>
		</ul>
		<?php
	}
}

/**
 * Shop/Category Page Customizations
 */

/**
 * Override theme default specification for product # per row.
 *
 * @return int Number of products per row.
 */
function elevator_loop_columns() {
	return 3; // 3 products per row.
}
add_filter( 'loop_shop_columns', 'elevator_loop_columns', 999 );

// Remove the default category description position.
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );

// Add the category description below the product list.
add_action( 'woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 10 );

/**
 * Remove category page title.
 *
 * @param bool $show_title Whether to show the title.
 * @return bool Modified setting.
 */
function elevator_remove_category_page_title( $show_title ) {
	if ( is_product_category() ) {
		return false; // Remove title on category pages.
	}
	return $show_title; // Keep title on other pages.
}
add_filter( 'woocommerce_show_page_title', 'elevator_remove_category_page_title' );

/**
 * Remove Add to Cart button on product category pages.
 */
function elevator_remove_add_to_cart_button_on_category_page() {
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}
}
add_action( 'wp', 'elevator_remove_add_to_cart_button_on_category_page' );