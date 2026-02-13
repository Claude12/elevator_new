<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

get_template_part('modules/hero', 'banner');
get_template_part('modules/usp', 'module');
get_template_part('modules/devide');
get_template_part('modules/category', 'module');
get_template_part('modules/devide');
//get_template_part('modules/trade', 'account');
//get_template_part('modules/devide');
get_template_part('modules/two-col', 'repeater');
get_template_part('modules/product-spotlight', 'feed');
get_template_part('modules/new-products', 'feed');
get_template_part('modules/social-feed', 'area');
get_template_part('modules/devide');
get_template_part('modules/suppliers', 'logo');


get_footer();
