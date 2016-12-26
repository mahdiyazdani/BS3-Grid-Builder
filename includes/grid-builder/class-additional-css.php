<?php
/**
 * Additional CSS
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
namespace bs3_grid_builder\builder;
use bs3_grid_builder\BS3_Grid_Builder_Functions as Bs3;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Additional_CSS{

	// Returns the instance
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ):
			$instance = new self;
		endif;
		return $instance;
	}

	public function __construct() {
		// Add CSS Button
		add_action( 'bs3_grid_builder_switcher_nav', array( $this, 'add_css_control' ) );
		// Save CSS
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
		// Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		// Add CSS
		add_action( 'wp_head', array( $this, 'print_css' ), 99 );
	}

	// CSS Control
	public function add_css_control( $post ){
		$post_id = $post->ID;
		?>
		<a href="#!" id="bs3-grid-builder-nav-css" class="bs3-grid-builder-nav-css"><span><?php _e( 'CSS', 'bs3-grid-builder' ); ?></span></a>
		<?php 
			wp_nonce_field( __FILE__ , 'bs3_grid_builder_custom_css_nonce' );
			BS3_Grid_Builder_Functions::render_settings( array(
				'id'        => 'bs3-grid-builder-additional-css', // data-target
				'title'     => __( 'Additional CSS', 'bs3-grid-builder' ),
				'width'     => '800px',
				'height'    => '420px',
				'callback'  => function() use( $post_id ){
				?>
				<textarea class="bs3-grid-builder-additional-css-textarea" name="_bs3_grid_builder_custom_css" autocomplete="off" placeholder="<?php esc_attr_e( 'You can add your own CSS here. CSS allows you to customize the appearance and layout of your site with code. ', 'bs3-grid-builder' ); ?>"><?php echo esc_textarea( BS3_Grid_Builder_Sanitize::css( get_post_meta( $post_id, '_bs3_grid_builder_custom_css', true ) ) ); ?></textarea>
				<p><label><input autocomplete="off" type="checkbox" name="_bs3_grid_builder_custom_css_disable" value="1" <?php checked( '1', get_post_meta( $post_id, '_bs3_grid_builder_custom_css_disable', true ) ); ?>><?php _e( 'Disable Additional CSS', 'bs3-grid-builder' ); ?></label></p>
			<?php
			},
		));?>
		<?php
	}

	// Store additional CSS
	public function save( $post_id, $post ){
		$request = stripslashes_deep( $_POST );
		if ( ! isset( $request['bs3_grid_builder_custom_css_nonce'] ) || ! wp_verify_nonce( $request['bs3_grid_builder_custom_css_nonce'], __FILE__ ) ):
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
		if( isset( $request['_bs3_grid_builder_custom_css'] ) ):
			if( $request['_bs3_grid_builder_custom_css'] ):
				update_post_meta( $post_id, '_bs3_grid_builder_custom_css', BS3_Grid_Builder_Sanitize::css( $request['_bs3_grid_builder_custom_css'] ) );
			else:
				delete_post_meta( $post_id, '_bs3_grid_builder_custom_css' );
			endif;
		endif;
		if( isset( $request['_bs3_grid_builder_custom_css_disable'] ) && ( '1' == $request['_bs3_grid_builder_custom_css_disable'] ) ):
			update_post_meta( $post_id, '_bs3_grid_builder_custom_css_disable', 1 );
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_custom_css_disable' );
		endif;
	}

	// Admin Scripts
	public function admin_scripts( $hook_suffix ){
		global $post_type;
		if( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ):
			return false;
		endif;
		if( post_type_supports( $post_type, 'editor' ) && post_type_supports( $post_type, 'bs3_grid_builder' ) ):
			wp_enqueue_style( 'bs3-grid-builder-custom_css', URI . 'assets/css/additional-css.css', array( 'bs3-grid-builder' ), VERSION );
			wp_enqueue_script( 'bs3-grid-builder-custom_css', URI . 'assets/js/additional-css.js', array( 'jquery' ), VERSION, true );
		endif;
	}

	// Append inline additional CSS into front-end
	public function print_css(){
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
		$css = get_post_meta( $post_id, '_bs3_grid_builder_custom_css', true );
		$disable = get_post_meta( $post_id, '_bs3_grid_builder_custom_css_disable', true );
		if( $css && !$disable ):
		?>
			<style id="bs3-grid-builder-additional-css" type="text/css">
				<?php echo wp_strip_all_tags( $css ) . PHP_EOL;?>
			</style>
		<?php
		endif;
	}

}

BS3_Grid_Builder_Additional_CSS::get_instance();