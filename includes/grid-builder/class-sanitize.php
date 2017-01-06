<?php
/**
 * Data Sanitization
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.3
 */
namespace bs3_grid_builder\builder;
use bs3_grid_builder\BS3_Grid_Builder_Sanitize as Bs3;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Sanitize{

	public static function rows_data( $input ){
		if( ! is_array( $input ) || empty( $input ) ):
			return array();
		endif;
		$rows = array();
		foreach( $input as $row_id => $row_data ):
			$default = array(
				'id'              				=> $row_id,
				'index'           				=> '',
				'state'           				=> 'open',
				'col_num'         				=> '1',
				'layout'          				=> '1',
				'col_order'       				=> '',
				'col_1'          				=> '',
				'col_2'          				=> '',
				'col_3'           				=> '',
				'col_4'           				=> '',
				'col_5'           				=> '',
				'col_6'           				=> '',
				'row_title'       				=> '',
				'container_layout'  			=> '',
				'container_html_id'  			=> '',
				'container_html_class'  		=> '',
				'row_html_id'     				=> '',
				'row_html_class'  				=> '',
				'row_disable_on_extra_small' 	=> '0',
				'row_disable_on_small' 			=> '0',
				'row_disable_on_desktop_medium'	=> '0',
				'row_disable_on_desktop_large'	=> '0',
			);
			$rows[$row_id]                     				= wp_parse_args( $row_data, $default );
			$rows[$row_id]['id']               				= strip_tags( $rows[$row_id]['id'] );
			$rows[$row_id]['index']            				= strip_tags( $rows[$row_id]['index'] );
			$rows[$row_id]['state']            				= self::state( $rows[$row_id]['state'] );
			$rows[$row_id]['col_num']          				= BS3_Grid_Builder_Functions::get_col_num( $rows[$row_id]['layout'] );
			$rows[$row_id]['layout']           				= self::layout( $rows[$row_id]['layout'] );
			$rows[$row_id]['col_order']        				= self::col_order( $rows[$row_id]['col_order'] );
			$rows[$row_id]['col_1']            				= self::ids( $rows[$row_id]['col_1'] );
			$rows[$row_id]['col_2']            				= self::ids( $rows[$row_id]['col_2'] );
			$rows[$row_id]['col_3']            				= self::ids( $rows[$row_id]['col_3'] );
			$rows[$row_id]['col_4']            				= self::ids( $rows[$row_id]['col_4'] );
			$rows[$row_id]['col_5']            				= self::ids( $rows[$row_id]['col_5'] );
			$rows[$row_id]['col_6']            				= self::ids( $rows[$row_id]['col_6'] );
			$rows[$row_id]['row_title']        				= sanitize_text_field( $rows[$row_id]['row_title'] );
			$rows[$row_id]['container_layout'] 				= self::container_layout( $rows[$row_id]['container_layout'] );
			$rows[$row_id]['container_html_id'] 			= sanitize_html_class( $rows[$row_id]['container_html_id'] );
			$rows[$row_id]['container_html_class'] 			= self::html_classes( $rows[$row_id]['container_html_class'] );
			$rows[$row_id]['row_html_id']      				= sanitize_html_class( $rows[$row_id]['row_html_id'] );
			$rows[$row_id]['row_html_class']   				= self::html_classes( $rows[$row_id]['row_html_class'] );
			$rows[$row_id]['row_disable_on_extra_small']   	= self::checkbox_values( $rows[$row_id]['row_disable_on_extra_small'] );
			$rows[$row_id]['row_disable_on_small']   		= self::checkbox_values( $rows[$row_id]['row_disable_on_small'] );
			$rows[$row_id]['row_disable_on_desktop_medium'] = self::checkbox_values( $rows[$row_id]['row_disable_on_desktop_medium'] );
			$rows[$row_id]['row_disable_on_desktop_large']  = self::checkbox_values( $rows[$row_id]['row_disable_on_desktop_large'] );
		endforeach;
		return $rows;
	}

	public static function items_data( $input ){
		if( ! is_array( $input ) || empty( $input ) ):
			return array();
		endif;
		$items = array();
		foreach( $input as $item_id => $item_data ):
			$default = array(
				'item_id'      						=> $item_id,
				'item_index'   						=> '',
				'item_state'   						=> 'open',
				'item_type'    						=> 'text',
				'row_id'      	 					=> '',
				'col_index'    						=> 'col_1',
				'column_html_id'    				=> '',
				'column_html_class' 				=> '',
				'column_disable_on_extra_small' 	=> '0',
				'column_disable_on_small' 			=> '0',
				'column_disable_on_desktop_medium' 	=> '0',
				'column_disable_on_desktop_large' 	=> '0',
				'content'      						=> '',
			);
			$items[$item_id]                  						= wp_parse_args( $item_data, $default );
			$items[$item_id]['item_id']      						= strip_tags( $items[$item_id]['item_id'] );
			$items[$item_id]['item_index']    						= strip_tags( $items[$item_id]['item_index'] );
			$items[$item_id]['item_state']    						= self::state( $items[$item_id]['item_state'] );
			$items[$item_id]['item_type']     						= self::item_type( $items[$item_id]['item_type'] );
			$items[$item_id]['row_id']        						= strip_tags( $items[$item_id]['row_id'] );
			$items[$item_id]['col_index']     						= self::item_col_index( $items[$item_id]['col_index'] );
			$items[$item_id]['column_html_id']     					= sanitize_html_class( $items[$item_id]['column_html_id'] );
			$items[$item_id]['column_html_class']  					= self::html_classes( $items[$item_id]['column_html_class'] );
			$items[$item_id]['column_disable_on_extra_small']  		= self::checkbox_values( $items[$item_id]['column_disable_on_extra_small'] );
			$items[$item_id]['column_disable_on_small']  			= self::checkbox_values( $items[$item_id]['column_disable_on_small'] );
			$items[$item_id]['column_disable_on_desktop_medium']  	= self::checkbox_values( $items[$item_id]['column_disable_on_desktop_medium'] );
			$items[$item_id]['column_disable_on_desktop_large']  	= self::checkbox_values( $items[$item_id]['column_disable_on_desktop_large'] );
			$items[$item_id]['content']       	   					= wp_kses_post( $items[$item_id]['content'] );
		endforeach;
		return $items;
	}

	// Sanitize State
	public static function state( $input ){
		$default = 'open';
		$valid = array( 'open', 'close' );
		if( in_array( $input, $valid ) ):
			return $input;
		endif;
		return $default;
	}

	// Sanitize Container Layout
	public static function container_layout( $layout ){
		$default = 'container';
		$valid = array( 'container-fluid', 'container' );
		if( in_array( $layout, $valid ) ):
			return $layout;
		endif;
		return $default;
	}

	// Sanitize Collapse Order
	public static function col_order( $order ){
		$default = is_rtl() ? 'rtl' : 'ltr';
		$valid = array( 'rtl', 'ltr' );
		if( in_array( $order, $valid ) ):
			return $order;
		endif;
		return $default;
	}

	// Sanitize Layout
	public static function layout( $layout ){
		$default = '1';
		$valid = array( '1', '12_12', '13_23', '23_13', '13_13_13', '14_14_14_14', '14_34', '34_14', '14_12_14', '14_14_12', '12_14_14', '16_16_16_16_16_16' );
		if( in_array( $layout, $valid ) ):
			return $layout;
		endif;
		return $default;
	}

	// Sanitize IDs
	public static function ids( $input ){
		$output = explode( ",", $input );
		$output = array_map( "strip_tags", $output );
		$output = implode( ",", $output );
		return $output;
	}

	// Sanitize Col Number from Layout
	public static function item_type( $input ){
		$default = 'text';
		$valid = array( 'text' );
		if( in_array( $input, $valid ) ):
			return $input;
		endif;
		return $default;
	}

	// Sanitize Col Index
	public static function item_col_index( $input ){
		$default = 'col_1';
		$valid = array( 'col_1', 'col_2', 'col_3', 'col_4', 'col_5', 'col_6' );
		if( in_array( $input, $valid ) ):
			return $input;
		endif;
		return $default;
	}

	// Sanitize Checkbox values
	public static function checkbox_values( $input ){
		$input = filter_var( $input, FILTER_SANITIZE_NUMBER_INT );
		return $input;
	}

	// Sanitize HTML Classes
	public static function html_classes( $classes ){
		$classes = explode( " ", $classes );
		$classes = array_map( "sanitize_html_class", $classes );
		$classes = implode( " ", $classes );
		return $classes;
	}

	// Sanitize Version
	public static function version( $input ){
		$output = sanitize_text_field( $input );
		$output = str_replace( ' ', '', $output );
		return trim( esc_attr( $output ) );
	}

	// Sanitize Custom JS
	public static function js( $js ){
		$js = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $js );
		$js = wp_kses( $js, array() );
		$js = esc_html( $js );
		$js = str_replace( '&gt;', '>', $js );
		$js = str_replace( '&quot;', '"', $js );
		$js = str_replace( '&amp;', "&", $js );
		$js = str_replace( '&amp;#039;', "'", $js );
		$js = str_replace( '&#039;', "'", $js );
		return $js;
	}

	// Sanitize Custom CSS
	public static function css( $css ){
		$css = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $css );
		$css = wp_kses( $css, array() );
		$css = esc_html( $css );
		$css = str_replace( '&gt;', '>', $css );
		$css = str_replace( '&quot;', '"', $css );
		$css = str_replace( '&amp;', "&", $css );
		$css = str_replace( '&amp;#039;', "'", $css );
		$css = str_replace( '&#039;', "'", $css );
		return $css;
	}

}