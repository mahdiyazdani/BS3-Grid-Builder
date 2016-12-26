<?php
/**
 * Check PHP and WordPress Version Class
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Back_Compat {

	public function __construct() {
		$args = array(
			'wp_requires'   => array(
				'version'       => '4.4',
				'notice'        => wpautop( sprintf( __( 'BS3 Grid Builder plugin requires at least WordPress 4.4+. You are running WordPress %s. Please upgrade and try again.', 'bs3-grid-builder' ), get_bloginfo( 'version' ) ) )
			),
			'php_requires'  => array(
				'version'       => '5.3',
				'notice'        => wpautop( sprintf( __( 'BS3 Grid Builder plugin requires at least PHP 5.3+. You are running PHP %s. Please upgrade and try again.', 'bs3-grid-builder' ), PHP_VERSION ) )
			)
		);

		$this->args = wp_parse_args( $args );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}
	public function admin_notices(){
		// WordPress Version Notice
		if( $this->args['wp_requires']['notice'] && version_compare( get_bloginfo( 'version' ), $this->args['wp_requires']['version'], '<' ) ):
			$wp_error_message = $this->args['wp_requires']['notice'];
			printf('<div class="error"><p>%s</p></div>', $wp_error_message);
		endif;

		// PHP Version Notice
		if( $this->args['php_requires']['notice'] && version_compare( PHP_VERSION, $this->args['php_requires']['version'], '<' ) ):
			$php_error_message = $this->args['php_requires']['notice'];
			printf('<div class="error"><p>%s</p></div>', $php_error_message);
		endif;
	}
	public function results(){
		// Check if installation meets with minimum requirements.
		if ( version_compare( get_bloginfo( 'version' ), $this->args['wp_requires']['version'], '>=' ) && version_compare( PHP_VERSION, $this->args['php_requires']['version'], '>=' ) ):
			return true;
		else: 
			/* if not return false */
			return false;
		endif;
	}
}