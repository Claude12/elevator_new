<?php defined( 'ABSPATH' ) or die( 'Scripts are not allowed!' );

/* Template Name: Contact */

get_header();

get_template_part('modules/internal', 'banner');

get_template_part('modules/two-col', 'repeater');
get_template_part('modules/devide');
get_template_part('modules/whatsapp', 'module');
get_template_part('modules/map', 'module');
get_template_part('modules/devide');
get_template_part('modules/social-feed', 'area');

get_footer();
