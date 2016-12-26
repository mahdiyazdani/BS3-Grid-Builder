<?php
/**
 * Switch between wp-editor and grid builder
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
namespace bs3_grid_builder\builder;
use bs3_grid_builder\BS3_Grid_Builder_Functions as Bs3;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Switcher{

	// Returns the instance.
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ):
			$instance = new self;
		endif;
		return $instance;
	}

	public function __construct() {

		// Add HTML Class
		add_action( 'admin_head', array( $this, 'html_class_script' ) );
		// Add Editor/BS3 Grid Builder Tab
		add_action( 'edit_form_after_title', array( $this, 'editor_toggle' ) );
		// Save BS3_Grid_Builder_Switcher Preference
		add_action( 'save_post', array( $this, 'save' ), 9, 2 );
		// Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 99 );
	}

	// Add BS3_Grid_Builder_GBF Active HTML Class
	function html_class_script(){
		global $pagenow, $post_type;
		if( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ):
			return false;
		endif;
		if( post_type_supports( $post_type, 'editor' ) && post_type_supports( $post_type, 'bs3_grid_builder' ) ):
			// If builder selected
			if( $active = get_post_meta( get_the_ID(), '_bs3_grid_builder_active', true ) ):
				?>
				<script type="text/javascript">
					/* <![CDATA[ */
					document.documentElement.classList.add( 'bs3_grid_builder_active' );
					/* ]]> */
				</script>
				<?php
			endif;
		endif;
	}
	
	// Toggle builder
	public function editor_toggle( $post ){
		$post_id = $post->ID;
		if( post_type_supports( $post->post_type, 'editor' ) && post_type_supports( $post->post_type, 'bs3_grid_builder' ) ):
			$active        = get_post_meta( $post_id, '_bs3_grid_builder_active', true );
			$active        = $active ? 1 : 0;
			$editor_class  = $active ? 'nav-tab' : 'nav-tab nav-tab-active';
			$builder_class = $active ? 'nav-tab nav-tab-active' : 'nav-tab';
			?>
			<h1 id="bs3-grid-builder-switcher" class="nav-tab-wrapper wp-clearfix">
				<a data-bs3-grid-builder-switcher="editor" class="<?php echo esc_attr( $editor_class ); ?>" href="#!" id="wp-editor-btn"><span class="dashicons dashicons-wordpress-alt"></span><?php _e( 'WP Editor', 'bs3-grid-builder' ); ?></a>
				<a data-bs3-grid-builder-switcher="builder" class="<?php echo esc_attr( $builder_class ); ?>" href="#!" id="bs3-grid-builder-btn"><span><?php _e( 'B', 'bs3-grid-builder' ); ?></span><?php _e( 'BS3 Grid Builder', 'bs3-grid-builder' ); ?></a>
				<input type="hidden" name="_bs3_grid_builder_active" value="<?php echo esc_attr( $active ); ?>">
				<?php wp_nonce_field( __FILE__ , 'bs3_grid_builder_switcher_nonce' ); ?>
				<?php do_action( 'bs3_grid_builder_switcher_nav', $post ); ?>
			</h1>
			<?php
		endif;
	}

	// Save grid builder switcher prefrences
	public function save( $post_id, $post ){
		$request = stripslashes_deep( $_POST );
		if ( ! isset( $request['bs3_grid_builder_switcher_nonce'] ) || ! wp_verify_nonce( $request['bs3_grid_builder_switcher_nonce'], __FILE__ ) ):
			return false;
		endif;
		if( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ):
			return false;
		endif;
		$post_type = get_post_type_object( $post->post_type );
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ):
			return false;
		endif;

		// Save BS3_Grid_Builder_GBF Tab
		if( isset( $request['_bs3_grid_builder_active'] ) ):
			$new_data = $request['_bs3_grid_builder_active'] ? 1 : 0;
			if( $new_data ):
				update_post_meta( $post_id, '_bs3_grid_builder_active', 1 );
			else:
				delete_post_meta( $post_id, '_bs3_grid_builder_active' );
			endif;
		else:
			delete_post_meta( $post_id, '_bs3_grid_builder_active' );
		endif;
	}

	// Extra scripts
	public function scripts( $hook_suffix ){
		global $post_type;
		if( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ):
			return false;
		endif;
		if( post_type_supports( $post_type, 'editor' ) && post_type_supports( $post_type, 'bs3_grid_builder' ) ):
			wp_enqueue_style( 'bs3-grid-builder-switcher', URI . 'assets/css/switcher.css', array(), VERSION );
			wp_enqueue_script( 'bs3-grid-builder-switcher', URI . 'assets/js/switcher.js', array( 'jquery', 'bs3-grid-builder-item' ), VERSION, true );
		endif;
	}
}

BS3_Grid_Builder_Switcher::get_instance();