<?php defined( 'ABSPATH' ) or die( 'Scripts are not allowed!' );

/* Template Name: About */

get_header();
get_template_part('modules/internal', 'banner');

get_template_part('modules/two-col', 'repeater');
get_template_part('modules/devide');
get_template_part('modules/usp', 'module');
get_template_part('modules/devide');
get_template_part('modules/meet-the', 'team');
//get_template_part('modules/devide');
//get_template_part('modules/quote', 'module');
get_footer();
