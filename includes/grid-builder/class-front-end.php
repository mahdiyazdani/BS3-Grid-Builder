<?php
/**
 * Front End
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
namespace bs3_grid_builder\builder;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Front_End{

	// Returns the instance
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ):
			$instance = new self;
		endif;
		return $instance;
	}

	public function __construct() {
		// Filter content with page builder content
		add_filter( 'the_content', array( $this, 'content_filter' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 999 );
		add_filter( 'post_class', array( $this, 'post_class' ), 10, 3 );
	}

	// Content Filter
	// This will format content with page builder data.
	public function content_filter( $content ){
		$post_id      = get_the_ID();
		$post_type    = get_post_type( $post_id );
		$active       = get_post_meta( $post_id, '_bs3_grid_builder_active', true );
		remove_filter( 'the_content', 'wpautop' );
		if( post_type_supports( $post_type, 'bs3_grid_builder' ) && $active ):
			$content = BS3_Grid_Builder_Functions::content( $post_id ); // autop added in this function.
		else:
			add_filter( 'the_content', 'wpautop' );
		endif;
		return $content;
	}

	// Front-end CSS
	public function scripts(){
		$grid_system_stylesheet = get_option( 'bs3_grid_builder_general_settings_option_name' );
		if( isset($grid_system_stylesheet['grid_system_stylesheet']) && $grid_system_stylesheet['grid_system_stylesheet'] == true ):
			//wp_enqueue_style( 'bs3-grid-builder', plugins_url( '/public/css/bs3-grid-builder.css', dirname(__FILE__, 2) ), array(), VERSION );
			wp_enqueue_style( 'bs3-grid-builder', plugins_url( '/public/css/bs3-grid-builder.min.css', dirname(__FILE__, 2) ), array(), VERSION );
		endif;
	}

	// Post BS3 Grid Builder active class
	public function post_class( $classes, $class, $post_id ){
		$post_type = get_post_type( $post_id );
		if( ! post_type_supports( $post_type, 'bs3_grid_builder' ) ):
			return $classes;
		endif;
		$active = get_post_meta( $post_id, '_bs3_grid_builder_active', true );
		if( $active ):
			$classes[] = 'bs3-grid-builder-entry';
		endif;
		return $classes;
	}

}

BS3_Grid_Builder_Front_End::get_instance();