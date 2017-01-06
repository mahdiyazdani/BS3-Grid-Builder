/**
 * BS3 Grid Builder (Rows)
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0.3
 */
;(function($) {

    /**
     * Update rows index
     *
     * This function should be loaded on:
     * - Add new row
     * - Delete row
     * - Sort row
     *
     ************************************
     */
    $.fn.bs3_grid_builder_updateRowsIndex = function() {
        // Row IDs
        var row_ids = [];
        // Update attributes of each rows
        $('#bs3-grid-builder > .bs3-grid-builder-row').each(function(i) {
            var num = i + 1;
            var row_id = $(this).data('id');
            // Set data
            $(this).data('index', num); // set index
            var row_index = $(this).data('index'); // get index
            // Update row
            $(this).attr('data-index', row_index); // set data attr
            $(this).find('.bs3_grid_builder_row_index').attr('data-row-index', row_index); // display text
            $(this).find('input[data-row_field="index"]').val(row_index).trigger('change'); // change input
            // Get ID
            row_ids.push(row_id);
        });
        // Update Hidden Input
        $('input[name="_bs3_grid_builder_row_ids"]').val(row_ids.join()).trigger('change');
    };
})(jQuery);

jQuery(document).ready(function($) {

    // on-load notice
    $(document).on('change', '#bs3-grid-builder-wrapper input, #bs3-grid-builder-wrapper select, #bs3-grid-builder-wrapper textarea, #bs3-grid-builder-switcher input, #bs3-grid-builder-switcher select, #bs3-grid-builder-switcher textarea', function() {
        $(window).on('beforeunload', function() {
            return bs3_grid_builder_i18n.unload;
        });
    });
    $(document).on('submit', 'form', function() {
        $(window).unbind('beforeunload');
    });

    // Display add row collection below the editor
    if ($('#bs3-grid-builder .bs3-grid-builder-row').length) {
        $('.bs3-grid-builder-add-row').show();
    }
    var row_template = wp.template('bs3-grid-builder-row'); // load #tmpl-bs3-grid-builder-row

    // Add a new row, once user clicked on a row button
    $(document.body).on('click', '.bs3-grid-builder-add-row .layout-thumb', function(e) {
        e.preventDefault();
        var row_id = new Date().getTime(); // time stamp when crating row
        var row_config = {
                id: row_id,
                index: '1',
                state: 'open',
                layout: $(this).data('row-layout'),
                col_num: $(this).data('row-col_num'),
                col_order: '',
                col_1: '',
                col_2: '',
                col_3: '',
                col_4: '',
                col_5: '',
                col_6: '',
            }
        // Add template to container
        if ("prepend" == $(this).parents('.bs3-grid-builder-add-row').data('add_row_method')) {
            $('#bs3-grid-builder').prepend(row_template(row_config));
        } else {
            $('#bs3-grid-builder').append(row_template(row_config));
        }
        // Update Index
        $.fn.bs3_grid_builder_updateRowsIndex();
        // Make New Column Sortable
        $.fn.bs3_grid_builder_sortItems();
        // Always show both add row buttons
        $('.bs3-grid-builder-add-row').show();

    });

    // Delete row when click new row button, ask for with a confirmation message
    $(document.body).on('click', '.bs3-grid-builder-remove-row', function(e) {
        e.preventDefault();
        // Confirm delete
        var confirm_delete = confirm($(this).data('confirm'));
        if (true === confirm_delete) {
            // Remove Row
            $(this).parents('.bs3-grid-builder-row').remove();
            // Update Index
            $.fn.bs3_grid_builder_updateRowsIndex();
            // If there isn't any row, hide the bottom row collection bar
            if (!$('#bs3-grid-builder .bs3-grid-builder-row').length) {
                $('.bs3-grid-builder-add-row[data-add_row_method="append"]').hide();
            }
        }
    });

    // Open/Close row using toggle arrow icon.
    $(document.body).on('click', '.bs3-grid-builder-toggle-row', function(e) {
        e.preventDefault();
        var row = $(this).parents('.bs3-grid-builder-row');
        var row_state = row.data('state'); // old
        // Toggle state data
        if ('open' == row_state) {
            row.data('state', 'close'); // set data
        } else {
            row.data('state', 'open');
        }
        // Update state
        var row_state = row.data('state'); // get new state data
        row.attr('data-state', row_state); // change attr for styling
        row.find('input[data-row_field="state"]').val(row_state).trigger('change'); // change hidden input
    });


    // Open/Close row settings
    /* == Open settings == */
    $(document.body).on('click', '.bs3-grid-builder-settings', function(e) {
        e.preventDefault();
        // Show settings target
        $(this).siblings($(this).data('target')).fadeIn('fast');
        // Show overlay background
        $('.bs3-grid-builder-modal-overlay').fadeIn('fast');
        // Disable Enter to Submit Form
        $('.bs3-grid-builder-row-settings').bind('keypress', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
        // Fix Height
        $(window).resize(function() {
            $('.bs3-grid-builder-row-settings .bs3-grid-builder-modal-content').css('height', 'auto').css('height', $('.bs3-grid-builder-row-settings').height() - 35 + 'px');
        });
    });
    /* == Close settings == */
    $(document.body).on('click', '.bs3-grid-builder-row-settings .bs3-grid-builder-modal-close', function(e) {
        e.preventDefault();
        // Update title in row
        var this_title = $(this).parents('.bs3-grid-builder-modal').find('input[data-row_field="row_title"]').val();
        $(this).parents('.bs3-grid-builder-row-menu').find('.bs3_grid_builder_row_title').data('row-title', this_title).attr('data-row-title', this_title);
        // Hide settings modal
        $(this).parents('.bs3-grid-builder-modal').fadeOut('fast');
        // Hide overlay background
        $('.bs3-grid-builder-modal-overlay').fadeOut('fast');
    });

    // Change layout
    $(document.body).on('change', 'select[data-row_field="layout"]', function(e) {
        // Get selected value
        var new_layout = $(this).val();
        var new_col_num = $('option:selected', this).attr('data-col_num');
        // Get current row
        var row = $(this).parents('.bs3-grid-builder-row');
        // Update Row Data
        row.data('layout', new_layout); // set layout
        row.attr('data-layout', row.data('layout')); // update data attr
        row.data('col_num', new_col_num);
        row.attr('data-col_num', row.data('col_num'));
        // Update hidden Input
        row.find('input[data-row_field="col_num"]').val(row.data('col_num')).trigger('change');
    });

    // Change columns collapse order
    $(document.body).on('change', 'select[data-row_field="col_order"]', function(e) {
        // Get selected value
        var selected = $(this).val();
        // Get current row
        var row = $(this).parents('.bs3-grid-builder-row');
        // Update Row Data
        row.data('col_order', selected);
        row.attr('data-col_order', row.data('col_order'));
    });

    // Make row sortable
    $('#bs3-grid-builder').sortable({
        handle: '.bs3-grid-builder-row-handle',
        cursor: 'grabbing',
        axis: 'y',
        stop: function(e, ui) {
            $.fn.bs3_grid_builder_updateRowsIndex();
        },
    });
    $(document.body).on('mousedown mouseup', '.bs3-grid-builder-grab', function(event) {
        $(this).toggleClass('bs3-grid-builder-grabbing');
    });

});