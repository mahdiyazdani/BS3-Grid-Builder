<?php
/**
 * Item Underscore.js Template
 * 
 * Item datas saved as "bs3_grid_builder_items" meta key
 * 
 * Datas (all item data need to be prefixed with "item_*" ):
 *
 * Hidden field:
 * "item_id"    : unique item id (time stamp when row was created)
 * "item_state" : toggle state
 * "item_type"  : currently only one "text"
 * 
 * Content:
 * "content"    : hidden textarea (?)
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.3
**/
namespace bs3_grid_builder\builder;
?>
<script id="tmpl-bs3-grid-builder-item" type="text/html">

	<div class="bs3-grid-builder-item bs3-grid-builder-clear" data-item_id="{{data.item_id}}" data-item_state="{{data.item_state}}" data-item_type="{{data.item_type}}" data-item_index="{{data.item_index}}">

		<?php /* HIDDEN FIELD */ ?>
		<input type="hidden" data-item_field="item_id" name="_bs3_grid_builder_items[{{data.item_id}}][item_id]" value="{{data.item_id}}" autocomplete="off"/>

		<input type="hidden" data-item_field="item_index" name="_bs3_grid_builder_items[{{data.item_id}}][item_index]" value="{{data.item_index}}" autocomplete="off"/>

		<input type="hidden" data-item_field="item_state" name="_bs3_grid_builder_items[{{data.item_id}}][item_state]" value="{{data.item_state}}" autocomplete="off"/>

		<input type="hidden" data-item_field="item_type" name="_bs3_grid_builder_items[{{data.item_id}}][item_type]" value="text" autocomplete="off"/>

		<?php /* CONTEXT FIELD */ ?>
		<input type="hidden" data-item_field="row_id" name="_bs3_grid_builder_items[{{data.item_id}}][row_id]" value="{{data.row_id}}" autocomplete="off"/>

		<input type="hidden" data-item_field="col_index" name="_bs3_grid_builder_items[{{data.item_id}}][col_index]" value="{{data.col_index}}" autocomplete="off"/>

		<?php /* ITEM MENU */ ?>
		<div class="bs3-grid-builder-item-menu bs3-grid-builder-clear">
			<div class="bs3-grid-builder-left">
				<span class="bs3-grid-builder-icon bs3-grid-builder-grab bs3-grid-builder-item-handle dashicons dashicons-move"></span>
				<span class="bs3-grid-builder-icon bs3_grid_builder_item_index" data-item-index="{{data.item_index}}"></span>
			</div><!-- .bs3-grid-builder-left -->
			<div class="bs3-grid-builder-right">
				<span data-target=".bs3-grid-builder-item-settings" class="bs3-grid-builder-icon bs3-grid-builder-link bs3-grid-builder-settings dashicons dashicons-admin-settings"></span>
				<span data-confirm="<?php _e( 'Press Ok to delete section, Cancel to leave?', 'bs3-grid-builder' ); ?>" class="bs3-grid-builder-icon bs3-grid-builder-remove-item bs3-grid-builder-link dashicons dashicons-trash"></span>
				<span class="bs3-grid-builder-icon bs3-grid-builder-toggle-item bs3-grid-builder-link dashicons dashicons-arrow-up"></span>
				<?php /* SETTINGS */ ?>
				<?php BS3_Grid_Builder_Functions::render_settings( array(
					'id'        => 'bs3-grid-builder-item-settings', // data-target
					'title'     => __( 'Column Settings', 'bs3-grid-builder' ),
					'callback'  => __NAMESPACE__ . '\BS3_Grid_Builder_Functions::column_settings',
				));?>
			</div><!-- .bs3-grid-builder-right -->
		</div><!-- .bs3-grid-builder-item-menu -->
		<div class="bs3-grid-builder-item-content bs3-grid-builder-clear">
			<a href="#!" class="bs3-grid-builder-item-iframe-overlay"></a>
			<iframe class="bs3-grid-builder-item-iframe" height="100%" width="100%" scrolling="no"></iframe>
			<textarea class="bs3-grid-builder-item-textarea" data-item_field="item_content" name="_bs3_grid_builder_items[{{data.item_id}}][content]" >{{{data.content}}}</textarea>
		</div><!-- .bs3-grid-builder-item-content -->
	</div><!-- .bs3-grid-builder-item -->
</script>
