<?php
/**
 * Functions of BS3 Grid Builder
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.4
 */
namespace bs3_grid_builder\builder;
use bs3_grid_builder\BS3_Grid_Builder_Functions as Bs3;

// Prevent direct file access
defined( 'ABSPATH' ) or exit;

class BS3_Grid_Builder_Functions{

	public $break_now;

	// Add a new row
	public static function add_row_field( $method = 'prepend' ){
		global $post;
		if( isset( $post ) && $post->ID != get_option('page_for_posts') ):
			?>
			<div class="bs3-grid-builder-add-row" data-add_row_method="<?php echo esc_attr( $method ); ?>">
				<div class="layout-thumb-wrap layout-thumb-label">
					<span><?php _e( 'Select row layout:', 'bs3-grid-builder' ); ?></span>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="1" data-row-col_num="1"><i class="bs3-grid-builder-font-icon bs3-grid-builder-1_1" title="1/1"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="12_12" data-row-col_num="2"><i class="bs3-grid-builder-font-icon bs3-grid-builder-12_12" title="1/2 - 1/2"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="23_13" data-row-col_num="2"><i class="bs3-grid-builder-font-icon bs3-grid-builder-23_13" title="2/3 - 1/3"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="13_23" data-row-col_num="2"><i class="bs3-grid-builder-font-icon bs3-grid-builder-13_23" title="1/3 - 2/3"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="13_13_13" data-row-col_num="3"><i class="bs3-grid-builder-font-icon bs3-grid-builder-13_13_13" title="1/3 - 1/3 - 1/3"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="14_34" data-row-col_num="2"><i class="bs3-grid-builder-font-icon bs3-grid-builder-14_34" title="1/4 - 3/4"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="34_14" data-row-col_num="2"><i class="bs3-grid-builder-font-icon bs3-grid-builder-34_14" title="3/4 - 1/4"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="14_12_14" data-row-col_num="3"><i class="bs3-grid-builder-font-icon bs3-grid-builder-14_12_14" title="1/4 - 1/2 - 1/4"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="14_14_14_14" data-row-col_num="4"><i class="bs3-grid-builder-font-icon bs3-grid-builder-14_14_14_14" title="1/4 - 1/4 - 1/4 - 1/4"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="14_14_12" data-row-col_num="3"><i class="bs3-grid-builder-font-icon bs3-grid-builder-14_14_12" title="1/4 - 1/4 - 1/2"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="12_14_14" data-row-col_num="3"><i class="bs3-grid-builder-font-icon bs3-grid-builder-12_14_14" title="1/2 - 1/4 - 1/4"></i></div>
				</div>
				<div class="layout-thumb-wrap">
					<div class="layout-thumb" data-row-layout="16_16_16_16_16_16" data-row-col_num="6"><i class="bs3-grid-builder-font-icon bs3-grid-builder-16_16_16_16_16_16" title="1/6 - 1/6 - 1/6 - 1/6 - 1/6 - 1/6"></i></div>
				</div>
			</div><!-- .bs3-grid-builder-add-row -->
		<?php
		endif;
	}

	// Render modal box settings HTML
	public static function render_settings( $args = array() ){
		$args_default = array(
			'id'        => '',
			'title'     => '',
			'callback'  => '__return_false',
			'width'     => '500px',
			'height'    => 'auto',
		);
		$args = wp_parse_args( $args, $args_default );
		?>
		<div class="<?php echo sanitize_title( $args['id'] ); ?> bs3-grid-builder-modal" style="display:none;width:<?php echo esc_attr( $args['width'] ); ?>;height:<?php echo esc_attr( $args['height'] );?>;">
			<div class="bs3-grid-builder-modal-container">
				<div class="bs3-grid-builder-modal-title"><?php echo $args['title']; ?><span class="bs3-grid-builder-modal-close"><span aria-hidden="true">×</span></span></div><!-- .bs3-grid-builder-modal-title -->
				<div class="bs3-grid-builder-modal-content">
					<?php if ( is_callable( $args['callback'] ) ){
						call_user_func( $args['callback'] );
					} ?>
				</div><!-- .bs3-grid-builder-modal-content -->
				<div class="bs3-grid-builder-modal-footer"><button type="button" class="btn button-primary bs3-grid-builder-modal-close"><?php _e( 'Apply Changes', 'bs3-grid-builder' ); ?></button></div><!-- .bs3-grid-builder-modal-footer -->
			</div><!-- .bs3-grid-builder-modal-container -->
		</div><!-- .bs3-grid-builder-modal -->
		<?php
	}

	// Render modal box row settings HTML
	public static function row_settings(){
		// Row title
		?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-text">
			<label for="bs3_grid_builder_rows[{{data.id}}][row_title]">
				<?php esc_html_e( 'Label', 'bs3-grid-builder' ); ?>
			</label>
			<input autocomplete="off" id="bs3_grid_builder_rows[{{data.id}}][row_title]" data-row_field="row_title" name="_bs3_grid_builder_rows[{{data.id}}][row_title]" type="text" value="{{data.row_title}}" />
		</div><!-- .bs3-grid-builder-modal-field -->
	
		<?php // Container Layout ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-select">
			<label for="bs3_grid_builder_rows[{{data.id}}][container_layout]">
				<?php esc_html_e( 'Container', 'bs3-grid-builder' ); ?>
			</label>
			<select id="bs3_grid_builder_rows[{{data.id}}][container_layout]" data-row_field="container_layout" name="_bs3_grid_builder_rows[{{data.id}}][container_layout]" autocomplete="off">
				<option value="" <# if( data.container_layout == '' ){ print('selected="selected"') } #>><?php _e( 'Default', 'bs3-grid-builder' ); ?></option>
				<option value="container" <# if( data.container_layout == 'container' ){ print('selected="selected"') } #>><?php _e( 'Container', 'bs3-grid-builder' ); ?></option>
				<option value="container-fluid" <# if( data.container_layout == 'container-fluid' ){ print('selected="selected"') } #>><?php _e( 'Container Fluid', 'bs3-grid-builder' ); ?></option>
			</select>
		</div><!-- .bs3-grid-builder-modal-field -->

		<?php // Container ID ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-text">
			<label for="bs3_grid_builder_rows[{{data.id}}][container_html_id]">
				<?php esc_html_e( 'Container ID', 'bs3-grid-builder' ); ?>
			</label>
			<input autocomplete="off" id="bs3_grid_builder_rows[{{data.id}}][container_html_id]" data-row_field="container_html_id" name="_bs3_grid_builder_rows[{{data.id}}][container_html_id]" type="text" value="{{data.container_html_id}}" />
		</div><!-- .bs3-grid-builder-modal-field -->

		<?php // Container classes ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-text">
			<label for="bs3_grid_builder_rows[{{data.id}}][container_html_class]">
				<?php esc_html_e( 'Container Class', 'bs3-grid-builder' ); ?>
			</label>
			<input autocomplete="off" id="bs3_grid_builder_rows[{{data.id}}][container_html_class]" data-row_field="container_html_class" name="_bs3_grid_builder_rows[{{data.id}}][container_html_class]" type="text" value="{{data.container_html_class}}" />
		</div><!-- .bs3-grid-builder-modal-field -->

		<?php // Row layout ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-select">
			<label for="bs3_grid_builder_rows[{{data.id}}][layout]">
				<?php esc_html_e( 'Layout', 'bs3-grid-builder' ); ?>
			</label>
			<select id="bs3_grid_builder_rows[{{data.id}}][layout]" data-row_field="layout" name="_bs3_grid_builder_rows[{{data.id}}][layout]" autocomplete="off">
				<option data-col_num="1" value="1" <# if( data.layout == '1' ){ print('selected="selected"') } #>><?php _e( '1/1', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="2" value="12_12" <# if( data.layout == '12_12' ){ print('selected="selected"') } #>><?php _e( '1/2 - 1/2', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="2" value="23_13" <# if( data.layout == '23_13' ){ print('selected="selected"') } #>><?php _e( '2/3 - 1/3', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="2" value="13_23" <# if( data.layout == '13_23' ){ print('selected="selected"') } #>><?php _e( '1/3 - 2/3', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="3" value="13_13_13" <# if( data.layout == '13_13_13' ){ print('selected="selected"') } #>><?php _e( '1/3 - 1/3 - 1/3', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="2" value="14_34" <# if( data.layout == '14_34' ){ print('selected="selected"') } #>><?php _e( '1/4 - 3/4', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="2" value="34_14" <# if( data.layout == '34_14' ){ print('selected="selected"') } #>><?php _e( '3/4 - 1/4', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="3" value="14_12_14" <# if( data.layout == '14_12_14' ){ print('selected="selected"') } #>><?php _e( '1/4 - 1/2 - 1/4', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="4" value="14_14_14_14" <# if( data.layout == '14_14_14_14' ){ print('selected="selected"') } #>><?php _e( '1/4 - 1/4 - 1/4 - 1/4', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="3" value="14_14_12" <# if( data.layout == '14_14_12' ){ print('selected="selected"') } #>><?php _e( '1/4 - 1/4 - 1/2', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="3" value="12_14_14" <# if( data.layout == '12_14_14' ){ print('selected="selected"') } #>><?php _e( '1/2 - 1/4 - 1/4', 'bs3-grid-builder' ); ?></option>
				<option data-col_num="6" value="16_16_16_16_16_16" <# if( data.layout == '16_16_16_16_16_16' ){ print('selected="selected"') } #>><?php _e( '1/6 - 1/6 - 1/6 - 1/6 - 1/6 - 1/6', 'bs3-grid-builder' ); ?></option>
			</select>
		</div><!-- .bs3-grid-builder-modal-field -->

		<?php // Stack ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-select">
			<label for="bs3_grid_builder_rows[{{data.id}}][col_order]">
				<?php esc_html_e( 'Collapse Order', 'bs3-grid-builder' ); ?>
			</label>
			<select id="bs3_grid_builder_rows[{{data.id}}][col_order]" data-row_field="col_order" name="_bs3_grid_builder_rows[{{data.id}}][col_order]" autocomplete="off">
				<option value="" <# if( data.col_order == '' ){ print('selected="selected"') } #>><?php _e( 'Default', 'bs3-grid-builder' ); ?></option>
				<option value="ltr" <# if( data.col_order == 'ltr' ){ print('selected="selected"') } #>><?php _e( 'Left to Right', 'bs3-grid-builder' ); ?></option>
				<option value="rtl" <# if( data.col_order == 'rtl' ){ print('selected="selected"') } #>><?php _e( 'Right to Left', 'bs3-grid-builder' ); ?></option>
			</select>
		</div><!-- .bs3-grid-builder-modal-field -->

		<?php // Row ID ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-text">
			<label for="bs3_grid_builder_rows[{{data.id}}][row_html_id]">
				<?php esc_html_e( 'Row ID', 'bs3-grid-builder' ); ?>
			</label>
			<input autocomplete="off" id="bs3_grid_builder_rows[{{data.id}}][row_html_id]" data-row_field="row_html_id" name="_bs3_grid_builder_rows[{{data.id}}][row_html_id]" type="text" value="{{data.row_html_id}}" />
		</div><!-- .bs3-grid-builder-modal-field -->

		<?php // Row classes ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-text">
			<label for="bs3_grid_builder_rows[{{data.id}}][row_html_class]">
				<?php esc_html_e( 'Row Class', 'bs3-grid-builder' ); ?>
			</label>
			<input autocomplete="off" id="bs3_grid_builder_rows[{{data.id}}][row_html_class]" data-row_field="row_html_class" name="_bs3_grid_builder_rows[{{data.id}}][row_html_class]" type="text" value="{{data.row_html_class}}" />
		</div><!-- .bs3-grid-builder-modal-field -->
		<?php // Disable on ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-checkbox">
			<label for="bs3_grid_builder_rows[{{data.id}}][row_disable_on]">
				<?php esc_html_e( 'Disable on', 'bs3-grid-builder' ); ?>
			</label>
			<div class="bs3-grid-builder-modal-field-checkbox-group bs3-visibility-options">
				<ul>
					<?php // Disable on Extra small devices ?>
					<li>
						<label for="bs3_grid_builder_rows[{{data.id}}][row_disable_on_extra_small]">
							<small><em><?php _e( 'Phones (<768px)', 'bs3-grid-builder' ); ?></em></small>
							<input id="bs3_grid_builder_rows[{{data.id}}][row_disable_on_extra_small]" data-row_field="row_disable_on_extra_small" name="_bs3_grid_builder_rows[{{data.id}}][row_disable_on_extra_small]" type="checkbox" value="1" <# if( data.row_disable_on_extra_small == '1' ){ print('checked="checked"') } #> />
						</label>
					</li>
					<?php // Disable on Small devices ?>
					<li>
						<label for="bs3_grid_builder_rows[{{data.id}}][row_disable_on_small]">
							<small><em><?php _e( 'Tablets (≥768px)', 'bs3-grid-builder' ); ?></em></small>
							<input id="bs3_grid_builder_rows[{{data.id}}][row_disable_on_small]" data-row_field="row_disable_on_small" name="_bs3_grid_builder_rows[{{data.id}}][row_disable_on_small]" type="checkbox" value="1" <# if( data.row_disable_on_small == '1' ){ print('checked="checked"') } #> />
						</label>
					</li>
					<br/>
					<?php // Disable on Medium devices ?>
					<li>
						<label for="bs3_grid_builder_rows[{{data.id}}][row_disable_on_desktop_medium]">
							<small><em><?php _e( 'Desktops (≥992px)', 'bs3-grid-builder' ); ?></em></small>
							<input id="bs3_grid_builder_rows[{{data.id}}][row_disable_on_desktop_medium]" data-row_field="row_disable_on_desktop_medium" name="_bs3_grid_builder_rows[{{data.id}}][row_disable_on_desktop_medium]" type="checkbox" value="1" <# if( data.row_disable_on_desktop_medium == '1' ){ print('checked="checked"') } #> />
						</label>
					</li>
					<?php // Disable on Large devices ?>
					<li>
						<label for="bs3_grid_builder_rows[{{data.id}}][row_disable_on_desktop_large]">
							<small><em><?php _e( 'Desktops (≥1200px)', 'bs3-grid-builder' ); ?></em></small>
							<input id="bs3_grid_builder_rows[{{data.id}}][row_disable_on_desktop_large]" data-row_field="row_disable_on_desktop_large" name="_bs3_grid_builder_rows[{{data.id}}][row_disable_on_desktop_large]" type="checkbox" value="1" <# if( data.row_disable_on_desktop_large == '1' ){ print('checked="checked"') } #> />
						</label>
					</li>
				</ul>
			</div><!-- .bs3-grid-builder-modal-field-checkbox-group -->
		</div><!-- .bs3-grid-builder-modal-field -->
		<?php
	}

	// Render modal box row settings HTML
	public static function column_settings(){
		?>
		<?php // Column ID ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-text">
			<label for="bs3_grid_builder_items[{{data.item_id}}][column_html_id]">
				<?php esc_html_e( 'Column ID', 'bs3-grid-builder' ); ?>
			</label>
			<input autocomplete="off" id="bs3_grid_builder_items[{{data.item_id}}][column_html_id]" data-row_field="column_html_id" name="_bs3_grid_builder_items[{{data.item_id}}][column_html_id]" type="text" value="{{data.column_html_id}}" />
		</div><!-- .bs3-grid-builder-modal-field -->

		<?php // Column classes ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-text">
			<label for="bs3_grid_builder_items[{{data.item_id}}][column_html_class]">
				<?php esc_html_e( 'Column Class', 'bs3-grid-builder' ); ?>
			</label>
			<input autocomplete="off" id="bs3_grid_builder_items[{{data.item_id}}][column_html_class]" data-row_field="column_html_class" name="_bs3_grid_builder_items[{{data.item_id}}][column_html_class]" type="text" value="{{data.column_html_class}}" />
		</div><!-- .bs3-grid-builder-modal-field -->
		<?php // Disable on ?>
		<div class="bs3-grid-builder-modal-field bs3-grid-builder-modal-field-checkbox">
			<label for="bs3_grid_builder_items[{{data.item_id}}][column_disable_on]">
				<?php esc_html_e( 'Disable on', 'bs3-grid-builder' ); ?>
			</label>
			<div class="bs3-grid-builder-modal-field-checkbox-group">
				<ul>
					<?php // Disable on Extra small devices ?>
					<li>
						<label for="bs3_grid_builder_items[{{data.item_id}}][column_disable_on_extra_small]">
							<small><em><?php _e( 'Phones (<768px)', 'bs3-grid-builder' ); ?></em></small>
							<input id="bs3_grid_builder_items[{{data.item_id}}][column_disable_on_extra_small]" data-row_field="column_disable_on_extra_small" name="_bs3_grid_builder_items[{{data.item_id}}][column_disable_on_extra_small]" type="checkbox" value="1" <# if( data.column_disable_on_extra_small == '1' ){ print('checked="checked"') } #> />
						</label>
					</li>
					<?php // Disable on Small devices ?>
					<li>
						<label for="bs3_grid_builder_items[{{data.item_id}}][column_disable_on_small]">
							<small><em><?php _e( 'Tablets (≥768px)', 'bs3-grid-builder' ); ?></em></small>
							<input id="bs3_grid_builder_items[{{data.item_id}}][column_disable_on_small]" data-row_field="column_disable_on_small" name="_bs3_grid_builder_items[{{data.item_id}}][column_disable_on_small]" type="checkbox" value="1" <# if( data.column_disable_on_small == '1' ){ print('checked="checked"') } #> />
						</label>
					</li>
					<br/>
					<?php // Disable on Medium devices ?>
					<li>
						<label for="bs3_grid_builder_items[{{data.item_id}}][column_disable_on_desktop_medium]">
							<small><em><?php _e( 'Desktops (≥992px)', 'bs3-grid-builder' ); ?></em></small>
							<input id="bs3_grid_builder_items[{{data.item_id}}][column_disable_on_desktop_medium]" data-row_field="column_disable_on_desktop_medium" name="_bs3_grid_builder_items[{{data.item_id}}][column_disable_on_desktop_medium]" type="checkbox" value="1" <# if( data.column_disable_on_desktop_medium == '1' ){ print('checked="checked"') } #> />
						</label>
					</li>
					<?php // Disable on Large devices ?>
					<li>
						<label for="bs3_grid_builder_items[{{data.item_id}}][column_disable_on_desktop_large]">
							<small><em><?php _e( 'Desktops (≥1200px)', 'bs3-grid-builder' ); ?></em></small>
							<input id="bs3_grid_builder_items[{{data.item_id}}][column_disable_on_desktop_large]" data-row_field="column_disable_on_desktop_large" name="_bs3_grid_builder_items[{{data.item_id}}][column_disable_on_desktop_large]" type="checkbox" value="1" <# if( data.column_disable_on_desktop_large == '1' ){ print('checked="checked"') } #> />
						</label>
					</li>
				</ul>
			</div><!-- .bs3-grid-builder-modal-field-checkbox-group -->
		</div><!-- .bs3-grid-builder-modal-field -->
		<?php
	}

	// Render (empty) Column
	public static function render_column( $args = array() ){
		$args_default = array(
			'title'     => '',
			'index'     => '',
		);
		$args = wp_parse_args( $args, $args_default );
		extract( $args );
		$field = "col_{$index}";
		?>
		<div class="bs3-grid-builder-col bs3-grid-builder-clear" data-col_index="<?php echo esc_attr( $field ); ?>">
			<?php // Hidden input ?>
			<input type="hidden" data-id="item_ids" data-row_field="<?php echo esc_attr( $field ); ?>" name="_bs3_grid_builder_rows[{{data.id}}][<?php echo esc_attr( $field ); ?>]" value="{{data.<?php echo esc_attr( $field ); ?>}}" autocomplete="off"/>
			<div class="bs3-grid-builder-col-content"></div><!-- .bs3-grid-builder-col-content -->
			<div class="bs3-grid-builder-add-item bs3-grid-builder-link">
				<span><?php _e( 'Add Item', 'bs3-grid-builder' );?></span>
			</div><!-- .bs3-grid-builder-add-item -->
		</div><!-- .bs3-grid-builder-col -->
		<?php
	}

	/**
	 * Format Post BS3_Grid_Builder_GBF Data To Single String
	 * This is the builder data without div wrapper
	 */
	public static function content_raw( $post_id ){
		$row_ids     = BS3_Grid_Builder_Sanitize::ids( get_post_meta( $post_id, '_bs3_grid_builder_row_ids', true ) );
		$rows_data   = BS3_Grid_Builder_Sanitize::rows_data( get_post_meta( $post_id, '_bs3_grid_builder_rows', true ) );
		$items_data  = BS3_Grid_Builder_Sanitize::items_data( get_post_meta( $post_id, '_bs3_grid_builder_items', true ) );
		if( !$row_ids || ! $rows_data ) :
			return false;
		endif;
		$rows = explode( ',', $row_ids );
		$content = '';
		foreach( $rows as $row_id ):
			if( isset( $rows_data[$row_id] ) ):
				$cols = range( 1, $rows_data[$row_id]['col_num'] );
				foreach( $cols as $col ):
					$items = $rows_data[$row_id]['col_' . $col];
					$items = explode(",", $items);
					foreach( $items as $item_id ):
						if( isset( $items_data[$item_id]['content'] ) && !empty( $items_data[$item_id]['content'] ) ):
							$content .= $items_data[$item_id]['content'] . "\r\n\r\n";
						endif;
					endforeach;
				endforeach;
			endif;
		endforeach;
		return apply_filters( 'bs3_grid_builder_content_raw', $content, $post_id, $row_ids, $rows_data, $items_data );
	}

	/**
	 * Format Post BS3_Grid_Builder_GBF Data To Single String
	 * This will format page builder data to content (single string)
	 */
	public static function content( $post_id ){
		$row_ids     = BS3_Grid_Builder_Sanitize::ids( get_post_meta( $post_id, '_bs3_grid_builder_row_ids', true ) );
		$rows_data   = BS3_Grid_Builder_Sanitize::rows_data( get_post_meta( $post_id, '_bs3_grid_builder_rows', true ) );
		$items_data  = BS3_Grid_Builder_Sanitize::items_data( get_post_meta( $post_id, '_bs3_grid_builder_items', true ) );
		$rows        = explode( ',', $row_ids );
		if( !$row_ids || ! $rows_data ):
			return false;
		endif;
		ob_start();
			$break_now = false;
			foreach( $rows as $row_id ):
				if( $break_now === true && get_post_type() == 'post' ):
					break;
				endif;
				if( isset( $rows_data[$row_id] ) ):
				// Container ID
				$container_html_id = $rows_data[$row_id]['container_html_id'] ? ' id="' . $rows_data[$row_id]['container_html_id'] . '"' : '';
				// Container Class
				$container_html_class = $rows_data[$row_id]['container_html_class'] ? "bs3-grid-builder-container {$rows_data[$row_id]['container_html_class']}" : 'bs3-grid-builder-container';
				$container_html_class = explode( " ", $container_html_class );
				$container_html_class[] = $rows_data[$row_id]['container_layout'];
				$container_html_class = array_map( 'sanitize_html_class', $container_html_class );
				$container_html_class = implode( ' ', $container_html_class );
				?>
				<div<?php echo $container_html_id; ?> class="<?php echo esc_attr( $container_html_class ); ?>">
				<?php
				// Row ID
				$row_html_id = $rows_data[$row_id]['row_html_id'] ? ' id="' . $rows_data[$row_id]['row_html_id'] . '"' : '';
				// Row Class
				$row_html_class = $rows_data[$row_id]['row_html_class'] ? "row bs3-grid-builder-row {$rows_data[$row_id]['row_html_class']}" : 'row bs3-grid-builder-row';
				$row_html_class = explode( " ", $row_html_class ); // array
				// Collapse Order
				$row_html_class[] = "bs3-grid-builder-row-order-{$rows_data[$row_id]['col_order']}";
				// Row responsive utilities
				$row_disable_on_extra_small = esc_attr( $rows_data[$row_id]['row_disable_on_extra_small'] );
				if(isset($row_disable_on_extra_small) && $row_disable_on_extra_small == 1):
					$row_html_class[] = 'hidden-xs';
				endif;
				$row_disable_on_small = esc_attr( $rows_data[$row_id]['row_disable_on_small'] );
				if(isset($row_disable_on_small) && $row_disable_on_small == 1):
					$row_html_class[] = 'hidden-sm';
				endif;
				$row_disable_on_desktop_medium = esc_attr( $rows_data[$row_id]['row_disable_on_desktop_medium'] );
				if(isset($row_disable_on_desktop_medium) && $row_disable_on_desktop_medium == 1):
					$row_html_class[] = 'hidden-md';
				endif;
				$row_disable_on_desktop_large = esc_attr( $rows_data[$row_id]['row_disable_on_desktop_large'] );
				if(isset($row_disable_on_desktop_large) && $row_disable_on_desktop_large == 1):
					$row_html_class[] = 'hidden-lg';
				endif;
				// Sanitize and implode classes from array
				$row_html_class = array_map( 'sanitize_html_class', $row_html_class );
				$row_html_class = implode( ' ', $row_html_class );
				?>
				<div<?php echo $row_html_id; ?> class="<?php echo esc_attr( $row_html_class ); ?>">
					<?php
					$cols = range( 1, $rows_data[$row_id]['col_num'] );
					// BS3 grid classes
					$grid_counter = 1;
					$grid_cols_cls = '';
					$grid_cols_cls_2 = '';
					$grid_cols_cls_3 = '';
					$grid_cols = esc_attr( $rows_data[$row_id]['layout'] );
					switch($grid_cols):
						case '14_14_14_14':
							$grid_cols_cls = 'col-md-3 col-sm-6';
						break;
						case '13_13_13':
							$grid_cols_cls = 'col-md-4 col-sm-6';
						break;
						case '23_13':
							$grid_cols_cls = 'col-md-8 col-sm-6';
							$grid_cols_cls_2 = 'col-md-4 col-sm-6';
						break;
						case '13_23':
							$grid_cols_cls = 'col-md-4 col-sm-6';
							$grid_cols_cls_2 = 'col-md-8 col-sm-6';
						break;
						case '12_12':
							$grid_cols_cls = 'col-sm-6';
						break;
						case '1':
							$grid_cols_cls = 'col-sm-12';
						break;
						case '14_34':
							$grid_cols_cls = 'col-md-3 col-sm-6';
							$grid_cols_cls_2 = 'col-md-9 col-sm-6';
						break;
						case '34_14':
							$grid_cols_cls = 'col-md-9 col-sm-6';
							$grid_cols_cls_2 = 'col-md-3 col-sm-6';
						break;
						case '14_12_14':
							$grid_cols_cls = 'col-md-3 col-sm-6';
							$grid_cols_cls_2 = 'col-md-6 col-sm-6';
							$grid_cols_cls_3 = 'col-md-3 col-sm-6';
						break;
						case '14_14_12':
							$grid_cols_cls = 'col-md-3 col-sm-6';
							$grid_cols_cls_2 = 'col-md-3 col-sm-6';
							$grid_cols_cls_3 = 'col-md-6 col-sm-6';
						break;
						case '12_14_14':
							$grid_cols_cls = 'col-md-6 col-sm-6';
							$grid_cols_cls_2 = 'col-md-3 col-sm-6';
							$grid_cols_cls_3 = 'col-md-3 col-sm-6';
						break;
						case '16_16_16_16_16_16':
							$grid_cols_cls = 'col-md-2 col-sm-6';
						break;
						default:
							$grid_cols_cls = 'col-sm-12';
						break;
					endswitch;
					$current_column = 0;
					$add_wrapper_class = false;
					foreach( $cols as $col ):
						$items = $rows_data[$row_id]['col_' . $col];
						$items = explode(",", $items);
							foreach( $items as $item_id ):
								if(! empty($item_id) ):
									if( preg_match( '/<!--more(.*?)?-->/', $items_data[$item_id]['content'] ) && ! is_single() && get_post_type() == 'post' && apply_filters('bs3_grid_builder_append_readmore_tag', true) === true ):
										$break_now = true;
									endif;
									if( isset( $items_data[$item_id] ) ):
										// Find 23_13, 13_23, 14_34 (2 Columns)
										if(isset($grid_cols_cls_2) && !empty($grid_cols_cls_2) && $col == 2):
											$grid_cols_cls = $grid_cols_cls_2;
										endif;
										// Find 14_12_14, 14_14_12, 12_14_14 (3 Columns)
										if(isset($grid_cols_cls_2, $grid_cols_cls_3) && !empty($grid_cols_cls_2) && !empty($grid_cols_cls_3) && $col == 3):
											$grid_cols_cls = $grid_cols_cls_3;
										endif;
										// Append column wrapper class
										if(strpos($grid_cols_cls, 'bs3-grid-col-wrapper') == false):
											$grid_cols_cls .= ' bs3-grid-col-wrapper';
										endif;
										// Append pull-right class to wrapper
										if(strpos($grid_cols_cls, 'pull-right') == false && esc_attr( $rows_data[$row_id]['col_order'] ) == 'rtl'):
											$grid_cols_cls .= (esc_attr( $rows_data[$row_id]['col_order'] ) == 'rtl') ? ' pull-right' : '';
										endif;
										// Column ID
										$column_html_id = $items_data[$item_id]['column_html_id'] ? ' id="' . $items_data[$item_id]['column_html_id'] . '"' : '';
										// Column Class
										$column_html_class = $items_data[$item_id]['column_html_class'] ? "bs3-grid-builder-child-item {$items_data[$item_id]['column_html_class']}" : "bs3-grid-builder-child-item";
										$column_html_class = explode( ' ', $column_html_class ); // array
										$column_html_class[] = 'bs3-grid-builder-col-' . intval( $col );
										// Column responsive utilities
										$column_disable_on_extra_small = esc_attr( $items_data[$item_id]['column_disable_on_extra_small'] );
										if(isset($column_disable_on_extra_small) && $column_disable_on_extra_small == 1):
											$column_html_class[] = 'hidden-xs';
										endif;
										$column_disable_on_small = esc_attr( $items_data[$item_id]['column_disable_on_small'] );
										if(isset($column_disable_on_small) && $column_disable_on_small == 1):
											$column_html_class[] = 'hidden-sm';
										endif;
										$column_disable_on_desktop_medium = esc_attr( $items_data[$item_id]['column_disable_on_desktop_medium'] );
										if(isset($column_disable_on_desktop_medium) && $column_disable_on_desktop_medium == 1):
											$column_html_class[] = 'hidden-md';
										endif;
										$column_disable_on_desktop_large = esc_attr( $items_data[$item_id]['column_disable_on_desktop_large'] );
										if(isset($column_disable_on_desktop_large) && $column_disable_on_desktop_large == 1):
											$column_html_class[] = 'hidden-lg';
										endif;
										// Sanitize and implode classes from array
										$column_html_class = array_map( 'sanitize_html_class', $column_html_class );
										$column_html_class = implode( ' ', $column_html_class );
										// Check if column has a child or not?
										if($current_column != intval( $col )):
											// Don't close <div> if we are printing first column
											if( intval( $col ) !== 1):
												echo '</div><!-- .bs3-grid-col-wrapper -->';
											endif;
											// Go to the next column
											$current_column = intval( $col );
											?>
											<div class="<?php echo $grid_cols_cls; ?>">
											<div<?php echo $column_html_id; ?> class="<?php echo esc_attr( $column_html_class ); ?>">
													<?php echo wpautop( $items_data[$item_id]['content'] ); ?>
											</div><!-- .bs3-grid-builder-child-item -->
										<?php 
										else:
										?>
											<div<?php echo $column_html_id; ?> class="<?php echo esc_attr( $column_html_class ); ?>">
												<?php echo wpautop( $items_data[$item_id]['content'] ); ?>
											</div><!-- .bs3-grid-builder-child-item -->
										<?php
										endif;
										endif;
										$grid_counter = $grid_counter + 1;
									endif;
								endforeach; 
						endforeach; 
					?>
					</div><!-- .bs3-grid-col-wrapper -->
				</div><!-- .bs3-grid-builder-row -->
			</div><!-- .bs3-grid-builder-container -->
		<?php 
				endif;
			endforeach; 
		return apply_filters( 'bs3_grid_builder_content', ob_get_clean(), $post_id, $row_ids, $rows_data, $items_data );
	}

	// Get Col Number from Layout
	public static function get_col_num( $layout ){
		if( '1' == $layout ):
			return 1;
		elseif( in_array( $layout, array( '12_12', '13_23', '23_13', '14_34', '34_14' ) ) ):
			return 2;
		elseif( in_array( $layout, array( '13_13_13', '14_12_14', '14_14_12', '12_14_14' ) ) ):
			return 3;
		elseif( '14_14_14_14' == $layout ):
			return 4;
		elseif( '16_16_16_16_16_16' == $layout ):
			return 6;
		endif;
		return 1; // fallback
	}

}