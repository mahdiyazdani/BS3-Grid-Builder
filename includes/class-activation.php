<?php
/**
 * Trigger defined events on plugin activation / deactivation
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.5
 */
// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Activation {

	private $file;
	private $dir;
	private $assets_url;

	public function __construct( $file, $plugin ) {
	 	$this->file = $plugin;
	 	$this->dir = dirname( $this->file );
	 	$this->admin_assets_url = esc_url( trailingslashit( plugins_url( 'admin/', $this->file ) ) );
	 	add_action( 'init', array( $this, 'textdomain' ), 10 );
		add_action( 'admin_notices', array( $this, 'activation' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ), 10 );
		add_action( 'admin_head', array( $this, 'TinyMCE_button_init' ), 10 );
		register_deactivation_hook( $file, array( $this, 'deactivation' ) );
		add_filter( 'mce_buttons_3', array( $this, 'fontsize_filter' ), 10, 1 );
		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ), array( $this, 'config_link' ), 10 );
	 }

	 // Load languages file and text domains 
	 public function textdomain() {
		$domain = 'bs3-grid-builder';
		$locale = apply_filters( 'bs3-grid-builder', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	// Run once plugin activated
	public function activation() {
		// Check if notice value stored in database or not?
		// If nothing found, register notice value into database and display plugin activation notice
		if( true != get_option( 'bs3_grid_builder_display_notice_once' ) ):
			add_option( 'bs3_grid_builder_display_notice_once', true );
			$html = '<div class="notice notice-info is-dismissible">';
				$html .= '<p>';
					$html .= sprintf(__( 'Welcome to BS3 Grid Builder! Start up and running with visiting our simple <a href="%s" target="_self">plugin option page</a>.', 'bs3-grid-builder'), esc_url( add_query_arg( 'page', 'bs3-grid-builder', admin_url( 'options-general.php' ) ) ) );
				$html .= '</p>';
				$html .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __( 'Dismiss this notice.', 'bs3-grid-builder' ) . '</span></button>';
			$html .= '</div><!-- .notice-info -->';
			echo $html;
		endif;
	}

	// Enqueue scripts and styles.
	// @link https://github.com/devinsays/better-blockquotes
	public function admin_enqueue() {
		wp_localize_script('editor', 'bs3_grid_builder_better_blockquotes', array( 
			'add_blockquote' => __('Add Blockquote', 'bs3-grid-builder') ,
			'blockquote' => __('Blockquote', 'bs3-grid-builder') ,
			'quote' => __('Quote', 'bs3-grid-builder') ,
			'citation' => __('Citation', 'bs3-grid-builder') ,
			'citation_link' => __('Citation Link', 'bs3-grid-builder')
		));
	}
	// TinyMCE Button Init
	public function TinyMCE_button_init() {
		// Exit if user can't edit posts
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')):
			return;
		endif;
		// Exit if rich editing is not enable
		if ('true' != get_user_option('rich_editing')):
			return;
		endif;
		add_filter('mce_buttons', array( $this, 'register_tinyMCE_button' ));
		add_filter('mce_external_plugins', array( $this, 'add_tinyMCE_plugin' ));
	}
	// Loads the javascript required for the custom TinyMCE button
	function add_tinyMCE_plugin($plugin_array) {
		$plugin_array['bs3_grid_builder_better_blockquote'] = $this->admin_assets_url . 'js/blockquotes.js';
		return $plugin_array;
	}
	// Add the button to TinyMCE
	function register_tinyMCE_button($buttons) {
		// Removes the default blockquote button
		if (false !== ($key = array_search('blockquote', $buttons))):
			unset($buttons[$key]);
		endif;
		// Add the custom blockquotes button
		array_push($buttons, 'bs3_grid_builder_better_blockquote');
		return $buttons;
	}
	// Run once plugin deactivated
	public function deactivation() {
		// Delete plugin activation notice value from database
		if( false == delete_option( 'bs3_grid_builder_display_notice_once' ) ):
			$html = '<div class="notice notice-error is-dismissible">';
				$html .= '<p>';
					$html .= __( 'There was a problem deactivating the BS3 Grid Builder Plugin. Please try again.', 'bs3-grid-builder' );
				$html .= '</p>';
				$html .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __( 'Dismiss this notice.', 'bs3-grid-builder' ) . '</span></button>';
			$html .= '</div><!-- .notice-error -->';
			echo $html;
		endif;
	}

	// Including the Font Size Filter to WP-Editor
	public function fontsize_filter( $buttons ) {
		array_shift( $buttons );
	    $buttons[] = 'fontselect';
		$buttons[] = 'fontsizeselect';
		$buttons[] = 'styleselect';
		$buttons[] = 'backcolor';
		$buttons[] = 'newdocument';
		$buttons[] = 'cut';
		$buttons[] = 'copy';
		return $buttons;
	}

	// Add plugin config link to Plugins page
	public function config_link( $links ) {
		$plugin_links = array(
			'<a href="https://support.mypreview.one" target="_blank">' . __('Support', 'bs3-grid-builder') . '</a>',
			'<a href="options-general.php?page=bs3-grid-builder" target="_self">' . __( 'Configuration', 'bs3-grid-builder' ) . '</a>'
		);
	  	return array_merge($plugin_links, $links);
	}
}

new BS3_Grid_Builder_Activation($file, $plugin);