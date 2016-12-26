<?php
/**
 * Template Name: BS3 Grid Builder - Empty Template
 *
 * Template for displaying a page just with the header and footer area and a "naked" content area in between.
 * Good for landingpages and other types of pages where you want to add a lot of custom markup.
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */

get_header();

while ( have_posts() ) : the_post();
	the_content();
endwhile;

get_footer();