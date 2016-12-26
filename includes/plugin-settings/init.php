<?php
/**
 * Initialization BS3 Grid Builder - Plugin option page
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
namespace bs3_grid_builder\options_page;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

define( __NAMESPACE__ . '\URI', $uri );
define( __NAMESPACE__ . '\PATH', $path );
define( __NAMESPACE__ . '\VERSION', $version );

// Plugin option page settings.
require_once dirname( __FILE__ ) . '/class-options-page.php';