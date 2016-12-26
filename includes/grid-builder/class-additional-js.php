<?php
/**
 * Additional JS
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
namespace bs3_grid_builder\builder;
use bs3_grid_builder\BS3_Grid_Builder_Functions as Bs3;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Additional_JS{

	// Returns the instance
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ):
			$instance = new self;
		endif;
		return $instance;
	}

	public function __construct() {
		// Add JS Button
		add_action( 'bs3_grid_builder_switcher_nav', array( $this, 'add_js_control' ) );
		// Save JS
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
		// Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		// Add JS
		add_action( 'wp_head', array( $this, 'print_js' ), 99 );
	}

	// JS Control
	public function add_js_control( $post ){
		$post_id = $post->ID;
		?>
		<a href="#!" id="bs3-grid-builder-nav-js" class="bs3-grid-builder-nav-js"><span><?php _e( 'JS', 'bs3-grid-builder' ); ?></span></a>
		<?php 
			wp_nonce_field( __FILE__ , 'bs3_grid_builder_custom_js_nonce' );
			BS3_Grid_Builder_Functions::render_settings( array(
				'id'        => 'bs3-grid-builder-additional-js', // data-target
				'title'     => __( 'Additional JS', 'bs3-grid-builder' ),
				'width'     => '800px',
				'height'    => '420px',
				'callback'  => function() use( $post_id ){
				?>
				<textarea class="bs3-grid-builder-additional-js-textarea" name="_bs3_grid_builder_custom_js" autocomplete="off" placeholder="<?php esc_attr_e( 'You can add your own JS here. Feel free to use "$" instead of jQuery. ', 'bs3-grid-builder' ); ?>"><?php echo esc_textarea( BS3_Grid_Builder_Sanitize::js( get_post_meta( $post_id, '_bs3_grid_builder_custom_js', true ) ) ); ?></textarea>
				<p><label><input autocomplete="off" type="checkbox" name="_bs3_grid_builder_custom_js_disable" value="1" <?php checked( '1', get_post_meta( $post_id, '_bs3_grid_builder_custom_js_disable', true ) ); ?>><?php _e( 'Disable Additional JS', 'bs3-grid-builder' ); ?></label></p>
			<?php
			},
		));?>
		<?php
	}

	// Store additional JS
	public function save( $post_id, $post ){
		$request = stripslashes_deep( $_POST );
		if ( ! isset( $request['bs3_grid_builder_custom_js_nonce'] ) || ! wp_verify_nonce( $request['bs3_grid_builder_custom_js_nonce'], __FILE__ ) ):
			return false;
		endif;
		if( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ):
			return false;
		endif;
		$post_type = get_post_type_object( $post->post_type );
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ):
			return false;
		endif;

		// Save
		if( isset( $request['_bs3_grid_builder_custom_js'] ) ):
			if( $request['_bs3_grid_builder_custom_js'] ):
				update_post_meta( $post_id, '_bs3_grid_builder_custom_js', BS3_Grid_Builder_Sanitize::js( $request['_bs3_grid_builder_custom_js'] ) );
			else:
				delete_post_meta( $post_id, '_bs3_grid_builder_custom_js' );
			endif;
		endif;
		if( isset( $request['_bs3_grid_builder_custom_js_disable'] ) && ( '1' == $request['_bs3_grid_builder_custom_js_disable'] ) ):
			update_post_meta( $post_id, '_bs3_grid_builder_custom_js_disable', 1 );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_custom_js_disable' );
		endif;
	}

	// Admin Scripts
	public function admin_scripts( $hook_suffix ){
		global $post_type;
		if( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ):
			return false;
		endif;
		if( post_type_supports( $post_type, 'editor' ) && post_type_supports( $post_type, 'bs3_grid_builder' ) ):
			wp_enqueue_style( 'bs3-grid-builder-custom_js', URI . 'assets/css/additional-js.css', array( 'bs3-grid-builder' ), VERSION );
			wp_enqueue_script( 'bs3-grid-builder-custom_js', URI . 'assets/js/additional-js.js', array( 'jquery' ), VERSION, true );
		endif;
	}

	// Append inline additional JS into front-end
	public function print_js(){
		if( !is_singular() ):
			return;
		endif;
		$post_id = get_queried_object_id();
		$post_type = get_post_type( $post_id );
		if( ! post_type_supports( $post_type, 'bs3_grid_builder' ) ):
			return;
		endif;
		$active = get_post_meta( $post_id, '_bs3_grid_builder_active', true );
		if( ! $active ):
			return;
		endif;
		$js = get_post_meta( $post_id, '_bs3_grid_builder_custom_js', true );
		$disable = get_post_meta( $post_id, '_bs3_grid_builder_custom_js_disable', true );
		if( $js && !$disable ):
		?>
			<script id="bs3-grid-builder-additional-js" type="text/javascript">
				(function($) {
    				$(function() {
						<?php echo wp_strip_all_tags( $js ) . PHP_EOL;?>
				    }); // end of document ready
				})(jQuery); // end of jQuery name space
			</script>
		<?php
		endif;
	}

}

BS3_Grid_Builder_Additional_JS::get_instance();