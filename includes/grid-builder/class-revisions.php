<?php
/**
 * Grid builder revisions
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.3
 */
namespace bs3_grid_builder\builder;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Revisions{

	// Returns the instance
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ):
			$instance = new self;
		endif;
		return $instance;
	}

	public function __construct() {
		// Save BS3 grid builder revision
		add_action( 'save_post', array( $this, 'save_revision' ), 11, 2 );
		// Restore post revisions
		add_action( 'wp_restore_post_revision', array( $this, 'restore_revision' ), 10, 2 );
	}

	// Save Revision
	public function save_revision( $post_id, $post ){
		$parent_id = wp_is_post_revision( $post_id );
		if ( $parent_id ) :
			$parent     = get_post( $parent_id );
			$active     = get_post_meta( $parent->ID, '_bs3_grid_builder_active', true );
			$db_version = get_post_meta( $parent->ID, '_bs3_grid_builder_db_version', true );
			$row_ids    = get_post_meta( $parent->ID, '_bs3_grid_builder_row_ids', true );
			$rows       = get_post_meta( $parent->ID, '_bs3_grid_builder_rows', true );
			$items      = get_post_meta( $parent->ID, '_bs3_grid_builder_items', true );
			$js        	= get_post_meta( $parent->ID, '_bs3_grid_builder_custom_js', true );
			$css        = get_post_meta( $parent->ID, '_bs3_grid_builder_custom_css', true );

			if ( false !== $active ):
				add_metadata( 'post', $post_id, '_bs3_grid_builder_active', $active );
			endif;
			if ( false !== $db_version ):
				add_metadata( 'post', $post_id, '_bs3_grid_builder_db_version', $db_version );
			endif;
			if ( false !== $row_ids ):
				add_metadata( 'post', $post_id, '_bs3_grid_builder_row_ids', $row_ids );
			endif;
			if ( false !== $rows ):
				add_metadata( 'post', $post_id, '_bs3_grid_builder_rows', $rows );
			endif;
			if ( false !== $items ):
				add_metadata( 'post', $post_id, '_bs3_grid_builder_items', $items );
			endif;
			if ( false !== $js ):
				add_metadata( 'post', $post_id, '_bs3_grid_builder_custom_js', $js );
			endif;
			if ( false !== $css ):
				add_metadata( 'post', $post_id, '_bs3_grid_builder_custom_css', $css );
			endif;
		endif;
	}

	// Restore revisions
	public function restore_revision( $post_id, $revision_id ) {

		$revision   = get_post( $revision_id );

		$active 	= get_metadata( 'post', $revision->ID, '_bs3_grid_builder_active', true );
		$db_version = get_metadata( 'post', $revision->ID, '_bs3_grid_builder_db_version', true );
		$row_ids    = get_metadata( 'post', $revision->ID, '_bs3_grid_builder_row_ids', true );
		$rows       = get_metadata( 'post', $revision->ID, '_bs3_grid_builder_rows', true );
		$items      = get_metadata( 'post', $revision->ID, '_bs3_grid_builder_items', true );
		$js        	= get_metadata( 'post', $revision->ID, '_bs3_grid_builder_custom_js', true );
		$css        = get_metadata( 'post', $revision->ID, '_bs3_grid_builder_custom_css', true );

		if ( false !== $active ):
			update_post_meta( $post_id, '_bs3_grid_builder_active', $active );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_active' );
		endif;
		if ( false !== $db_version ):
			update_post_meta( $post_id, '_bs3_grid_builder_db_version', $db_version );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_db_version' );
		endif;
		if ( false !== $row_ids ):
			update_post_meta( $post_id, '_bs3_grid_builder_row_ids', $row_ids );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_row_ids' );
		endif;
		if ( false !== $rows ):
			update_post_meta( $post_id, '_bs3_grid_builder_rows', $rows );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_rows' );
		endif;
		if ( false !== $items ):
			update_post_meta( $post_id, '_bs3_grid_builder_items', $items );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_items' );
		endif;
		if ( false !== $js ):
			update_post_meta( $post_id, '_bs3_grid_builder_custom_js', $js );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_custom_js' );
		endif;
		if ( false !== $css ):
			update_post_meta( $post_id, '_bs3_grid_builder_custom_css', $css );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_custom_css' );
		endif;
	}
}

BS3_Grid_Builder_Revisions::get_instance();