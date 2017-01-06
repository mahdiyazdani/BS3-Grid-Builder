<?php
/**
 * Row Underscore.js Template
 * 
 * Row data saved as "bs3_grid_builder_rows" meta key
 * Row order saved as "bs3_grid_builder_row_ids" meta key
 *
 * Hidden Fields:
 * - "id"           : unique row id (time stamp when row was created)
 * - "index"        : order of the row (1st row = "1", 2nd row = "2", etc)
 * - "state"        : "open" vs "closed" (toggle state of the row)
 * - "col_num"      :  number of column in the layout (based on selected "col_order")
 *
 * Settings Fields:
 * - "layout"       : column layout (1 col, 1/2 - 1/2, etc)
 * - "col_order"    : collapse order, "default" (no val), "ltr" (left first/on top) vs "rtl" (right first)
 *
 * Item IDs Order (Hidden):
 * - "col_1"        : item IDs for 1st column in comma separated value
 * - "col_2"
 * - "col_3"
 * - "col_4"
 * - "col_5"
 * - "col_6"
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.3
**/
namespace bs3_grid_builder\builder;
global $bs3_grid_builder_admin_color;
?>
<script id="tmpl-bs3-grid-builder-row" type="text/html">
	<div class="bs3-grid-builder-row bs3-grid-builder-clear" data-id="{{data.id}}" data-index="{{data.index}}"  data-state="{{data.state}}" data-col_num="{{data.col_num}}" data-layout="{{data.layout}}" data-col_order="{{data.col_order}}">

		<?php /* HIDDEN FIELD */ ?>
		<input type="hidden" data-row_field="id" name="_bs3_grid_builder_rows[{{data.id}}][id]" value="{{data.id}}" autocomplete="off"/>
		<input type="hidden" data-row_field="index" name="_bs3_grid_builder_rows[{{data.id}}][index]" value="{{data.index}}" autocomplete="off"/>
		<input type="hidden" data-row_field="state" name="_bs3_grid_builder_rows[{{data.id}}][state]" value="{{data.state}}" autocomplete="off"/>
		<input type="hidden" data-row_field="col_num" name="_bs3_grid_builder_rows[{{data.id}}][col_num]" value="{{data.col_num}}" autocomplete="off"/>

		<?php /* ROW MENU */ ?>
		<div class="bs3-grid-builder-row-menu bs3-grid-builder-clear">
			<div class="bs3-grid-builder-left">
				<span class="bs3-grid-builder-icon bs3-grid-builder-grab bs3-grid-builder-row-handle dashicons dashicons-image-flip-vertical"></span>
				<span class="bs3-grid-builder-icon bs3_grid_builder_row_index" data-row-index="{{data.index}}"></span>
				<span class="bs3-grid-builder-icon bs3_grid_builder_row_title" data-row-title="{{data.row_title}}"></span>
			</div><!-- .bs3-grid-builder-left -->
			<div class="bs3-grid-builder-right">
				<span data-target=".bs3-grid-builder-row-settings" class="bs3-grid-builder-icon bs3-grid-builder-link bs3-grid-builder-settings dashicons dashicons-admin-settings"></span>
				<span data-confirm="<?php _e( 'Press Ok to delete section, Cancel to leave?', 'bs3-grid-builder' ); ?>" class="bs3-grid-builder-icon bs3-grid-builder-link bs3-grid-builder-remove-row dashicons dashicons-trash"></span>
				<span class="bs3-grid-builder-icon bs3-grid-builder-link bs3-grid-builder-toggle-row dashicons dashicons-arrow-up"></span>
				<?php /* SETTINGS */ ?>
				<?php BS3_Grid_Builder_Functions::render_settings( array(
					'id'        => 'bs3-grid-builder-row-settings', // data-target
					'title'     => __( 'Row Settings', 'bs3-grid-builder' ),
					'callback'  => __NAMESPACE__ . '\BS3_Grid_Builder_Functions::row_settings',
				));?>
			</div><!-- .bs3-grid-builder-right -->
		</div><!-- .bs3-grid-builder-row-menu -->
		<?php /* ROW CONTENT */ ?>
		<div class="bs3-grid-builder-row-content bs3-grid-builder-clear">
			<?php 
				BS3_Grid_Builder_Functions::render_column( array(
					'title'  => __( '1st Column', 'bs3-grid-builder' ),
					'index'  => 1,
				) );
				BS3_Grid_Builder_Functions::render_column( array(
					'title'  => __( '2nd Column', 'bs3-grid-builder' ),
					'index'  => 2,
				) );
				BS3_Grid_Builder_Functions::render_column( array(
					'title'  => __( '3rd Column', 'bs3-grid-builder' ),
					'index'  => 3,
				) );
				BS3_Grid_Builder_Functions::render_column( array(
					'title'  => __( '4th Column', 'bs3-grid-builder' ),
					'index'  => 4,
				) ); 
				BS3_Grid_Builder_Functions::render_column( array(
					'title'  => __( '5th Column', 'bs3-grid-builder' ),
					'index'  => 5,
				) );
				BS3_Grid_Builder_Functions::render_column( array(
					'title'  => __( '6th Column', 'bs3-grid-builder' ),
					'index'  => 6,
				) );
			?>
		</div><!-- .bs3-grid-builder-row-content -->
	</div><!-- .bs3-grid-builder-row -->
</script>
