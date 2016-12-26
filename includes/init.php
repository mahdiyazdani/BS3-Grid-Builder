<?php
/**
 * Initialization BS3 Grid Builder
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
namespace bs3_grid_builder;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

define( __NAMESPACE__ . '\URI', $uri );
define( __NAMESPACE__ . '\PATH', $path );
define( __NAMESPACE__ . '\FILE', $file );
define( __NAMESPACE__ . '\PLUGIN', $plugin );
define( __NAMESPACE__ . '\VERSION', $version );

// Plugin option page
require_once dirname( __FILE__ ) . '/plugin-settings/init.php';
// BS3 Grid Builder
require_once dirname( __FILE__ ) . '/grid-builder/init.php';
// BS3 Grid Builder - Page Template(s)
require_once dirname( __FILE__ ) . '/page-templates/class-page-template.php';