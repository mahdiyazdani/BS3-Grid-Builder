<?php
/**
 * Grid builder functionality
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.5
 */
namespace bs3_grid_builder\builder;
use bs3_grid_builder\BS3_Grid_Builder_Functions as Bs3;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_GBF{

	// Returns the instance
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ):
			$instance = new self;
		endif;
		return $instance;
	}

	public function __construct() {
		// Add it after editor in edit screen
		add_action( 'edit_form_after_editor', array( $this, 'form' ) );
		// Save BS3_Grid_Builder_GBF Data
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
		// Admin Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 99 );
	}

	// Form
	public function form( $post ){
		if( ! post_type_supports( $post->post_type, 'bs3_grid_builder' ) ):
			return;
		endif;
		$post_id = $post->ID;
		?>
		<div id="bs3-grid-builder-wrapper">
			<div class="bs3-grid-builder-modal-overlay" style="display:none;"></div>
			<?php BS3_Grid_Builder_Functions::add_row_field( 'prepend' ); ?>
			<div id="bs3-grid-builder">
			</div><!-- #bs3-grid-builder -->
			<?php BS3_Grid_Builder_Functions::add_row_field( 'append' ); ?>
			<input type="hidden" name="_bs3_grid_builder_row_ids" value="<?php echo esc_attr( get_post_meta( $post_id, '_bs3_grid_builder_row_ids', true ) ); ?>" autocomplete="off"/>
			<input type="hidden" name="_bs3_grid_builder_db_version" value="<?php echo esc_attr( VERSION ); ?>" autocomplete="off"/>
			<?php
				wp_nonce_field( __FILE__ , 'bs3_grid_builder_nonce' );
				// Load Editor
				BS3_Grid_Builder_Functions::render_settings( array(
					'id'        => 'bs3-grid-builder-editor',
					'title'     => __( 'Edit Content', 'bs3-grid-builder' ),
					'width'     => '800px',
					'callback'  => function(){
						wp_editor( '', 'bs3_grid_builder_editor', array(
							'tinymce'       => array(
								'wp_autoresize_on' => false,
								'resize'           => false
							),
							'editor_height' => 330
						) );
					},
				));
			?>
			<div id="bs3-grid-builder-templates">
				<?php require_once( PATH . 'underscore-js/template-row.php' ); ?>
				<?php require_once( PATH . 'underscore-js/template-item.php' ); ?>
			</div>
			<div id="bs3-grid-builder-template-loader">
				<?php $this->load_templates( $post_id ); ?>
			</div>
		</div><!-- #bs3-grid-builder-wrapper -->
		<?php
	}

	// Load Template
	public function load_templates( $post_id ){

		// Rows data
		$rows_data   = BS3_Grid_Builder_Sanitize::rows_data( get_post_meta( $post_id, '_bs3_grid_builder_rows', true ) );
		$row_ids     = BS3_Grid_Builder_Sanitize::ids( get_post_meta( $post_id, '_bs3_grid_builder_row_ids', true ) );
		if( ! $rows_data && $row_ids && is_array( $rows_data ) && is_array( $row_ids ) ):
			return false;
		endif;
		$rows = explode( ',', $row_ids );

		// Items data
		$items_data  = BS3_Grid_Builder_Sanitize::items_data( get_post_meta( $post_id, '_bs3_grid_builder_items', true ) );
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				var row_template = wp.template( 'bs3-grid-builder-row' );
				<?php foreach( $rows as $row_id ):
						if( isset( $rows_data[$row_id] ) ): 
				?>
							$( '#bs3-grid-builder' ).append( row_template( <?php echo wp_json_encode( $rows_data[$row_id] ); ?> ) );
				<?php
						endif;
					endforeach;
				?>
				<?php if( $items_data && is_array( $items_data ) ): ?>
					var item_template = wp.template( 'bs3-grid-builder-item' );
					<?php 
						foreach( $items_data as $item_id => $item ):
							if( isset( $rows_data[$item['row_id']] ) ): 
						?>
							$( '.bs3-grid-builder-row[data-id="<?php echo $item['row_id']; ?>"] .bs3-grid-builder-col[data-col_index="<?php echo $item['col_index']; ?>"] .bs3-grid-builder-col-content' ).append( item_template( <?php echo wp_json_encode( $item ); ?> ) );
				<?php
							endif;
						endforeach;
					endif;

				?>
			} );
		</script>
		<?php
	}

	// Save BS3 Grid Builder Data
	public function save( $post_id, $post ){

		$request = stripslashes_deep( $_POST );
		if ( ! isset( $request['bs3_grid_builder_nonce'] ) || ! wp_verify_nonce( $request['bs3_grid_builder_nonce'], __FILE__ ) ):
			return $post_id;
		endif;
		if( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ):
			return $post_id;
		endif;
		$post_type = get_post_type_object( $post->post_type );
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ):
			return $post_id;
		endif;
		$wp_preview = isset( $request['wp-preview'] ) ? esc_attr( $request['wp-preview'] ) : false;
		if( $wp_preview ):
			return $post_id;
		endif;

		// Query switcher
		$active = isset( $request['_bs3_grid_builder_active'] ) ? $request['_bs3_grid_builder_active'] : false;

		if( $active ):
			update_post_meta( $post_id, '_bs3_grid_builder_active', 1 );
		// BS3 Grid Builder isn't active, delete all data.
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_active' );
			delete_post_meta( $post_id, '_bs3_grid_builder_db_version' );
			delete_post_meta( $post_id, '_bs3_grid_builder_row_ids' );
			delete_post_meta( $post_id, '_bs3_grid_builder_rows' );
			delete_post_meta( $post_id, '_bs3_grid_builder_items' );
			return false;
		endif;

		// Database version
		if( isset( $request['_bs3_grid_builder_db_version'] ) ):
			$db_version = BS3_Grid_Builder_Sanitize::version( $request['_bs3_grid_builder_db_version'] );
			if( $db_version ):
				update_post_meta( $post_id, '_bs3_grid_builder_db_version', $db_version );
			else:
				delete_post_meta( $post_id, '_bs3_grid_builder_db_version' );
			endif;
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_db_version' );
		endif;

		// Row ID
		if( isset( $request['_bs3_grid_builder_row_ids'] ) ):
			$row_ids = BS3_Grid_Builder_Sanitize::ids( $request['_bs3_grid_builder_row_ids'] );
			if( $row_ids ):
				update_post_meta( $post_id, '_bs3_grid_builder_row_ids', $row_ids );
			else:
				delete_post_meta( $post_id, '_bs3_grid_builder_row_ids' );
			endif;
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_row_ids' );
		endif;

		// Row data
		if( isset( $request['_bs3_grid_builder_rows'] ) ):
			$rows = BS3_Grid_Builder_Sanitize::rows_data( $request['_bs3_grid_builder_rows'] );
			if( $rows ):
				update_post_meta( $post_id, '_bs3_grid_builder_rows', $rows );
			else:
				delete_post_meta( $post_id, '_bs3_grid_builder_rows' );
			endif;
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_rows' );
		endif;

		// Items data
		if( isset( $request['_bs3_grid_builder_items'] ) ):
			$items = BS3_Grid_Builder_Sanitize::items_data( $request['_bs3_grid_builder_items'] );
			if( $items ):
				update_post_meta( $post_id, '_bs3_grid_builder_items', $items );
			else:
				delete_post_meta( $post_id, '_bs3_grid_builder_items' );
			endif;
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_items' );
		endif;

		// Content
		$pb_content = BS3_Grid_Builder_Functions::content_raw( $post_id );
		$this_post = array(
			'ID'           => $post_id,
			'post_content' => sanitize_post_field( 'post_content', $pb_content, $post_id, 'db' ),
		);
		// Prevent infinite loop
		remove_action( 'save_post', array( $this, __FUNCTION__ ) );
		wp_update_post( $this_post );
		add_action( 'save_post', array( $this, __FUNCTION__ ), 10, 2 );
	}

	// Admin scripts
	public function scripts( $hook_suffix ){
		global $post_type;
		if( ! post_type_supports( $post_type, 'bs3_grid_builder' ) ):
			return;
		endif;
		
		if( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ):
			wp_enqueue_style( 'bs3-grid-builder', URI . 'assets/css/bs3-grid-builder-gbf.css', array(), VERSION );
			wp_enqueue_script( 'bs3-grid-builder-row', URI . 'assets/js/bs3-grid-builder-row.js', array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), VERSION, true );
			$data = array(
				'unload' => __( 'The changes you made will be lost if you navigate away from this page','bs3-grid-builder' ),
			);
			wp_localize_script( 'bs3-grid-builder-row', 'bs3_grid_builder_i18n', $data );
			wp_enqueue_script( 'bs3-grid-builder-item', URI . 'assets/js/bs3-grid-builder-item.js', array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), VERSION, true );
			$ajax_data = array(
				'ajax_url'     => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'   => wp_create_nonce( 'bs3_grid_builder_ajax_nonce' ),
			);
			wp_localize_script( 'bs3-grid-builder-item', 'bs3_grid_builder_ajax', $ajax_data );
		endif;
	}

}

BS3_Grid_Builder_GBF::get_instance();