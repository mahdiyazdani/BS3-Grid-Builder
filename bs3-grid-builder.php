<?php
/*
Plugin Name: 	BS3 Grid Builder
Plugin URI:  	https://www.mypreview.one
Description: 	Bootstrap Grid Builder is a powerful WordPress plugin for designing and prototyping modern websites.
Version:     	1.0.5
Author:      	Mahdi Yazdani
Author URI:  	https://www.mypreview.one
Text Domain: 	bs3-grid-builder
Domain Path: 	/languages
License:     	GPL2
License URI: 	https://www.gnu.org/licenses/gpl-2.0.html
 
BS3 Grid Builder is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
BS3 Grid Builder is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with BS3 Grid Builder. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
// Prevent direct file access
defined( 'ABSPATH' ) or exit;

if (!function_exists('bs3_grid_builder_initialization')):
	function bs3_grid_builder_initialization(){
		$uri      = trailingslashit( plugin_dir_url( __FILE__ ) );
		$path     = trailingslashit( plugin_dir_path( __FILE__ ) );
		$file     = __FILE__;
		$plugin   = plugin_basename( __FILE__ );
		$version  = '1.0.5';

		// Run if admin is already logged in
		if ( is_admin() ) :
			// Prevent process if plugin didn't meet system requirments.
			require_once dirname( __FILE__ ) . '/includes/class-back-compat.php';
			$back_compat = new BS3_Grid_Builder_Back_Compat();
			if( $back_compat->results() == false ):
				return;
			endif;
		endif;

		// Plugin Activation / Deactivation
		require_once dirname( __FILE__ ) . '/includes/class-activation.php';
		// Initialization BS3 Grid Builder
		require_once dirname( __FILE__ ) . '/includes/init.php';
	}
endif;
add_action( 'plugins_loaded', 'bs3_grid_builder_initialization' );