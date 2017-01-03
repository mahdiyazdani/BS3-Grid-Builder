<?php
/**
 * Portability grid builder data
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.1
 */
namespace bs3_grid_builder\builder;
use bs3_grid_builder\BS3_Grid_Builder_Functions as Bs3;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Portability{

	// Returns the instance
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ):
			$instance = new self;
		endif;
		return $instance;
	}

	public function __construct() {
		// Add Export and Import Button
		add_action( 'bs3_grid_builder_switcher_nav', array( $this, 'add_tools_control' ), 11 );
		// Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		// To JSON
		add_action( 'wp_ajax_bs3_grid_builder_export_to_json', array( $this, 'ajax_export_to_json' ) );
		add_action( 'wp_ajax_bs3_grid_builder_import_data', array( $this, 'ajax_import_data' ) );
	}

	// Export and Import Control
	public function add_tools_control( $post ){
		$post_id = $post->ID;
		?>
		<a href="#!" id="bs3-grid-builder-nav-tools" class="bs3-grid-builder-nav-tools"><span><?php _e( 'Portability', 'bs3-grid-builder' ); ?></span></a>
		<?php BS3_Grid_Builder_Functions::render_settings( array(
			'id'        => 'bs3-grid-builder-portability', // data-target
			'title'     => __( 'Portability', 'bs3-grid-builder' ),
			'width'     => '400px',
			'height'    => 'auto',
			'callback'  => function() use( $post_id ){
				?>
				<ul class="wp-tab-bar">
					<li id="bs3-grid-builder-export-tab" class="tabs wp-tab-active">
						<a class="bs3-grid-builder-portability-nav-bar" href="#bs3-grid-builder-export-panel"><?php _e( 'Export', 'bs3-grid-builder' ); ?></a>
					</li><!-- .tabs -->
					<li id="bs3-grid-builder-import-tab" class="tabs">
						<a class="bs3-grid-builder-portability-nav-bar" href="#bs3-grid-builder-import-panel"><?php _e( 'Import', 'bs3-grid-builder' ); ?></a>
					</li><!-- .tabs -->
				</ul><!-- .wp-tab-bar -->
				<div id="bs3-grid-builder-export-panel" class="bs3-grid-builder-portability-panel wp-tab-panel" style="display:block;">
					<p><?php _e('Exporting your BS3 Grid Builder Layout will create a JSON code that can be imported into a different website.', 'bs3-grid-builder'); ?></p>
					<textarea autocomplete="off" id="bs3-grid-builder-portability-export-textarea" readonly="readonly" style="display:none;" placeholder="<?php esc_attr_e( 'No Data', 'bs3-grid-builder' ); ?>"></textarea>
					<p><a id="bs3-grid-builder-portability-export-action" href="#!" class="button button-primary"><?php _e( 'Export', 'bs3-grid-builder' ); ?></a>
					<span id="bs3-grid-builder-portability-export-spinner" class="spinner" style=""></span>
					</p>
				</div><!-- .wp-tab-panel -->
				<div id="bs3-grid-builder-import-panel" class="bs3-grid-builder-portability-panel wp-tab-panel" style="display:none;">
					<p style="color:red;font-style:italic;font-weight:bold;"><?php _e('Importing a previously-generated BS3 Grid Builder Layout file will overwrite all content currently on this page.', 'bs3-grid-builder'); ?></p>
					<textarea autocomplete="off" id="bs3-grid-builder-portability-import-textarea" placeholder="<?php esc_attr_e( 'Paste previously-generated JSON code here.', 'bs3-grid-builder' ); ?>"></textarea>
					<p><a id="bs3-grid-builder-portability-import-action" href="#!" data-alert="<?php esc_attr_e( 'Invalid File format. You should be inserting a JSON file.', 'bs3-grid-builder' ); ?>" class="button button-primary disabled"><?php _e( 'Import', 'bs3-grid-builder' ); ?></a>
					<span id="bs3-grid-builder-portability-import-spinner" class="spinner" style=""></span>
					</p>
				</div><!-- .wp-tab-panel -->
				<?php
			},
		));?>
		<?php
	}

	// Admin Scripts
	public function admin_scripts( $hook_suffix ){
		global $post_type;
		if( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ):
			return false;
		endif;
		if( post_type_supports( $post_type, 'editor' ) && post_type_supports( $post_type, 'bs3_grid_builder' ) ):
			wp_enqueue_style( 'bs3-grid-builder-portability', URI . 'assets/css/portability.css', array( 'bs3-grid-builder' ), VERSION );
			wp_register_script( 'serialize-object', URI . 'assets/js/jquery.serialize-object.min.js', array( 'jquery' ), VERSION, false );
			wp_enqueue_script( 'bs3-grid-builder-portability', URI . 'assets/js/portability.js', array( 'jquery', 'bs3-grid-builder-item', 'serialize-object' ), VERSION, true );
			$ajax_data = array(
				'ajax_url'         => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'       => wp_create_nonce( 'bs3_grid_builder_tools_nonce' )
			);
			wp_localize_script( 'bs3-grid-builder-portability', 'bs3_grid_builder_tools', $ajax_data );
		endif;
	}

	// Ajax export to JSON
	public function ajax_export_to_json(){
		// Strip slash
		$request = stripslashes_deep( $_POST );
		// Check AJAX
		check_ajax_referer( 'bs3_grid_builder_tools_nonce', 'nonce' );

		$data = array(
			'row_ids' => isset( $request['row_ids'] ) ? $request['row_ids'] : '',
			'rows'    => isset( $request['rows'] ) ? $request['rows'] : array(),
			'items'   => isset( $request['items'] ) ? $request['items'] : array()
		);
		echo json_encode( $data );
		wp_die();
	}

	// Ajax import
	public function ajax_import_data(){
		// Strip slash
		$request = stripslashes_deep( $_POST );
		// Check AJAX
		check_ajax_referer( 'bs3_grid_builder_tools_nonce', 'nonce' );

		$data = isset( $request['data'] ) ? $request['data'] : '';
		$data = json_decode( $data, true );
		$default = array(
			'row_ids' => '',
			'rows'    => array(),
			'items'   => array(),
		);
		$data = wp_parse_args( $data, $default );

		$rows_data = BS3_Grid_Builder_Sanitize::rows_data( $data['rows'] );
		$row_ids = BS3_Grid_Builder_Sanitize::ids( $data['row_ids'] );
		if( ! $rows_data && $row_ids && is_array( $rows_data ) && is_array( $row_ids ) ):
			return false;
		endif;
		$rows = explode( ',', $row_ids );
		$items_data = BS3_Grid_Builder_Sanitize::items_data( $data['items'] );
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				var row_template = wp.template( 'bs3-grid-builder-row' );
				<?php 
					foreach( $rows as $row_id ):
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
				var iframe_css = $.fn.bs3_grid_builder_getIframeCSS();
				$( '.bs3-grid-builder-item-iframe' ).each( function(i){
					$( this ).bs3_grid_builder_loadIfameContent( iframe_css );
				} );
			} );
		</script>
		<?php
		wp_die();
	}
}

BS3_Grid_Builder_Portability::get_instance();