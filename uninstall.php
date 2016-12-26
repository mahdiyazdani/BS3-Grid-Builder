<?php
/**
 * Unistall BS3 Grid Builder.
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
if (!defined( 'WP_UNINSTALL_PLUGIN')):
    exit();
endif;

$option_name = 'bs3_grid_builder';
delete_option( $option_name . '_general_settings_option_name' );
delete_option( $option_name . '_post_types_option_name' );