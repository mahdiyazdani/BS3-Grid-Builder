<?php
/**
 * Initialization BS3 Grid Builder - Grid Builder
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
namespace bs3_grid_builder\builder;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

define( __NAMESPACE__ . '\URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( __NAMESPACE__ . '\PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( __NAMESPACE__ . '\VERSION', $version );

// BS3_Grid_Builder_Sanitize
require_once( PATH . 'class-sanitize.php' );
// BS3_Grid_Builder_Functions
require_once( PATH . 'class-functions.php' );
// BS3_Grid_Builder_Switcher
require_once( PATH . 'class-switcher.php' );
// BS3_Grid_Builder_Additional_CSS
require_once( PATH . 'class-additional-css.php' );
// BS3_Grid_Builder_Additional_JS
require_once( PATH . 'class-additional-js.php' );
// BS3_Grid_Builder_Portability
require_once( PATH . 'class-portability.php' );
// BS3_Grid_Builder_GBF
require_once( PATH . 'class-gbf.php' );
// BS3_Grid_Builder_Revisions
require_once( PATH . 'class-revisions.php' );
// BS3_Grid_Builder_Front_End
require_once( PATH . 'class-front-end.php' );