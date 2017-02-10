<?php
/**
 * BS3 Grid Builder back-end functionality
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.5
 */
namespace bs3_grid_builder\options_page;
use bs3_grid_builder\BS3_Grid_Builder_Functions as Bs3;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Options_Page{

	private $bs3_grid_builder_general_settings;

	// Returns instance.
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ):
			$instance = new self;
		endif;
		return $instance;
	}

	public function __construct() {
		$this->hook_suffix   = '';
		$this->settings_slug = 'bs3-grid-builder';

		// Create BS3_Grid_Builder_Options_Page
		add_action( 'admin_menu', array( $this, 'create_settings_page' ) );
		// Register BS3_Grid_Builder_Options_Page and Fields
		add_action( 'admin_init', array( $this, 'register_settings' ), 1 );
		// Add Post Type Support
		add_action( 'init', array( $this, 'add_bs3_grid_builder_support' ) );
	}

	public function create_settings_page(){

		// Hook to disable settings
		if( false === apply_filters( 'bs3_grid_builder_post_type_settings', true ) ):
			return false;
		endif;
		$this->hook_suffix = add_options_page(
			$page_title  = __( 'BS3 Grid Builder', 'bs3-grid-builder' ),
			$menu_title  = __( 'BS3 Grid Builder', 'bs3-grid-builder' ),
			$capability  = 'manage_options',
			$menu_slug   = $this->settings_slug,
			$function    = array( $this, 'settings_page' )
		);
	}

	public function settings_page(){
		$active_tab = 'general_settings';
		$tab_title = esc_attr__( 'General Settings', 'bs3-grid-builder' );
		if( isset( $_GET[ 'tab' ] ) ) :
    		$active_tab = $_GET[ 'tab' ];
    		switch($active_tab):
    			case 'general_settings':
    				$active_tab = 'general_settings';
    				$tab_title = esc_attr__( 'General Settings', 'bs3-grid-builder' );
    			break;
    			case 'post_types':
    				$active_tab = 'post_types';
    				$tab_title = esc_attr__( 'Activate BS3 Grid Builder', 'bs3-grid-builder' );
    			break;
    			default:
    				$active_tab = 'general_settings';
    				$tab_title = esc_attr__( 'General Settings', 'bs3-grid-builder' );
    		endswitch;
		endif;
		// Retrieve general settings options
		$this->bs3_grid_builder_general_settings = get_option('bs3_grid_builder_general_settings_option_name');
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h1><?php _e( 'BS3 Grid Builder', 'bs3-grid-builder' ); ?></h1>
			<h2 class="nav-tab-wrapper">
	            <a href="options-general.php?page=<?php echo $this->settings_slug; ?>&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>"><?php _e('General Settings', 'bs3-grid-builder'); ?></a>
	            <a href="options-general.php?page=<?php echo $this->settings_slug; ?>&tab=post_types" class="nav-tab <?php echo $active_tab == 'post_types' ? 'nav-tab-active' : ''; ?>"><?php _e('Post Types', 'bs3-grid-builder'); ?></a>
        	</h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<div class="handlediv" title="<?php __('Click to toggle', 'bs3-grid-builder'); ?>"><br></div>
								<h2 class="hndle"><span><?php echo $tab_title; ?></span></h2>
								<div class="inside">
									<form id="bs3-grid-builder-plugin-options-form" method="post" action="options.php">
										<?php 
											if($active_tab == 'general_settings'):
												settings_fields( 'bs3_grid_builder_general_settings_option_group' ); 
												do_settings_sections( 'bs3_grid_builder_general_settings_admin' ); 
											elseif($active_tab == 'post_types'):
												settings_fields( 'bs3_grid_builder_post_types_option_group' ); 
												do_settings_sections( 'bs3_grid_builder_post_type_settings_admin' ); 
											endif;
										?>
									</form>
								</div><!-- .inside -->
							</div><!-- .postbox -->
						</div><!-- .meta-box-sortables .ui-sortable -->
						<?php 
							$other_attributes = array( 'form' => 'bs3-grid-builder-plugin-options-form' );
							submit_button( 'Apply Changes', 'primary', 'wpdocs-save-settings', true, $other_attributes ); 
						?>
					</div><!-- post-body-content -->
					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<div class="postbox">
								<div class="handlediv" title="<?php _e('Click to toggle', 'bs3-grid-builder'); ?>"><br></div>
								<h2 class="hndle"><span><?php esc_attr_e( 'Looking for a stylish theme?', 'bs3-grid-builder' ); ?></span></h2>
								<div class="inside">
									<p>
										<a href="https://wp.me/p8930x-8q" target="_blank">
											<img src="https://i.imgsafe.org/6a52b7b71e.jpg" style="max-width:100%;height:auto;" />
										</a>
									</p>
									<p>
										<?php 
											printf( __('In case you want to start an e-commerce project, the %s is one of the first things you need. The whole design of Hypermarket is ultra-responsive and Retina ready, offering you a site that can be accessed from any device, no matter the size or technology of its screen.' , 'woo-store-vacation'), '<a href="https://wp.me/p8930x-8q" target="_blank">Hypermarket WordPress Theme</a>' ); 
										?>
									</p>
								</div><!-- .inside -->
							</div><!-- .postbox -->
						</div><!-- .meta-box-sortables -->
					</div><!-- #postbox-container-1 .postbox-container -->
				</div><!-- #post-body .metabox-holder .columns-2 -->	
				<br class="clear">
			</div><!-- #poststuff -->
		</div><!-- wrap -->
		<?php
	}

	public function register_settings(){

		// Hook to disable settings
		if( false === apply_filters( 'bs3_grid_builder_post_type_settings', true ) ):
			return false;
		endif;
		// General settings
		register_setting(
			$option_group      = 'bs3_grid_builder_general_settings_option_group',
			$option_name       = 'bs3_grid_builder_general_settings_option_name',
			$sanitize_callback = array( $this, 'bs3_grid_builder_general_settings_sanitize' )
		);
		// Post types
		register_setting(
			$option_group      = 'bs3_grid_builder_post_types_option_group',
			$option_name       = 'bs3_grid_builder_post_types_option_name',
			$sanitize_callback = function( $data ){
				return $this->check_post_types_exists( $data );
			}
		);
		// General settings
		add_settings_section(
			$section_id        = 'bs3_grid_builder_general_settings',
			$section_title     = '',
			$callback_function = '__return_false',
			$settings_slug     = 'bs3_grid_builder_general_settings_admin'
		);
		// Post types
		add_settings_section(
			$section_id        = 'bs3_grid_builder_post_type_settings',
			$section_title     = '',
			$callback_function = '__return_false',
			$settings_slug     = 'bs3_grid_builder_post_type_settings_admin'
		);
		// General Settings
		add_settings_field(
			$field_id          = 'bs3_grid_builder_grid_stylesheet',
			$field_title       = __( 'Enqueue Stylesheet', 'bs3-grid-builder' ),
			$callback_function = function(){
				?>
				<span style="margin-right: 20px;">
					<label>
						<input type="checkbox" value="true" name="bs3_grid_builder_general_settings_option_name[grid_system_stylesheet]" <?php echo ( isset( $this->bs3_grid_builder_general_settings['grid_system_stylesheet'] ) && $this->bs3_grid_builder_general_settings['grid_system_stylesheet'] == true ) ? 'checked' : ''; ?> /> <small><em><?php _e('Bootstrap\'s responsive grid and responsive utility classes only, without any extras.', 'bs3_grid_builder'); ?></em></small>
					</label>
				</span>
				<?php
			},
			$settings_slug     = 'bs3_grid_builder_general_settings_admin',
			$section_id        = 'bs3_grid_builder_general_settings'
		);
		// Post types
		add_settings_field(
			$field_id          = 'bs3_grid_builder_post_types_field',
			$field_title       = __( 'Post Types', 'bs3-grid-builder' ),
			$callback_function = function(){
				// Get all available Post Types
				$post_types = get_post_types( $args = array( 'public' => true ) , 'objects' );

				// Generating checkbox for each Post Types
				foreach( $post_types as $post_type ):
					// Only if post type has "wp-editor" the_content();
					if( post_type_supports( $post_type->name, 'editor' ) ):
						?>
						<span style="margin-right: 20px;">
							<label>
								<input type="checkbox" value="<?php echo esc_attr( $post_type->name );?>" name="bs3_grid_builder_post_types_option_name[]" <?php checked( post_type_supports( $post_type->name, 'bs3_grid_builder' ) ); ?>> <?php echo $post_type->label; ?>
							</label>
						</span>
						<?php
					endif;
				endforeach;
				echo '<br><br><small><em>' . __('Note: By default BS3 Grid Builder is available for pages only.', 'bs3_grid_builder') . '</em></small>';
			},
			$settings_slug     = 'bs3_grid_builder_post_type_settings_admin',
			$section_id        = 'bs3_grid_builder_post_type_settings'
		);

	}

	public function bs3_grid_builder_general_settings_sanitize( $input ) {
		$sanitary_values = array();
		if ( isset( $input['grid_system_stylesheet'] ) ) :
			$sanitary_values['grid_system_stylesheet'] = $input['grid_system_stylesheet'];
		endif;
		return $sanitary_values;
	}

	public function add_bs3_grid_builder_support(){
		
		// Hook to disable settings
		if( false === apply_filters( 'bs3_grid_builder_post_type_settings', true ) ):
			return false;
		endif;
		// If not set, default to page.
		$post_types = get_option( 'bs3_grid_builder_post_types_option_name' );
		if( ! $post_types && ! is_array( $post_types ) ):
			$post_types = array( 'page' );
		else :
			$post_types = $this->check_post_types_exists( $post_types );
		endif;
		foreach( $post_types as $pt ):
			add_post_type_support( $pt, 'bs3_grid_builder' );
		endforeach;
	}

	public function check_post_types_exists( $input ) {
		$input = is_array( $input ) ? $input : array();
		$post_types = array();
		foreach( $input as $post_type ):
			if( post_type_exists( $post_type ) ):
				$post_types[] = $post_type;
			endif;
		endforeach;
		return $post_types;
	}
}

BS3_Grid_Builder_Options_Page::get_instance();