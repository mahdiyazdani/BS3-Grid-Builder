/**
 * Portability
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
;(function($) {

    // Display portability modal box
    $.fn.bs3_grid_builder_BS3_Grid_Builder_Portability_Open = function() {

        // Trigger modal and overlay
        $('.bs3-grid-builder-portability').fadeIn('fast');
        $('.bs3-grid-builder-modal-overlay').fadeIn('fast');

        // Fix height
        $('.bs3-grid-builder-bs3-grid-builder-portability .bs3-grid-builder-modal-content').css("height", $('.bs3-grid-builder-portability').height() - 35 + "px");
        $(window).resize(function() {
            $('.bs3-grid-builder-portability .bs3-grid-builder-modal-content').css("height", "auto").css("height", $('.bs3-grid-builder-portability').height() - 35 + "px");
        });
    };

    // Destroy modal box
    $.fn.bs3_grid_builder_BS3_Grid_Builder_Portability_Close = function() {

        // Hide modal and overlay
        $('.bs3-grid-builder-portability').hide();
        $('.bs3-grid-builder-modal-overlay').hide();

        // Reset Modal
        $('#bs3-grid-builder-export-tab').addClass('wp-tab-active');
        $('#bs3-grid-builder-import-tab').removeClass('wp-tab-active');
        $('#bs3-grid-builder-export-panel').show();
        $('#bs3-grid-builder-import-panel').hide();
        $('#bs3-grid-builder-portability-export-textarea').val('').hide();
        $('#bs3-grid-builder-portability-import-textarea').val('');
        $('#bs3-grid-builder-portability-import-action').addClass('disabled');
        $('#bs3-grid-builder-portability-import-spinner').removeClass('is-active');
    };

})(jQuery);


jQuery(document).ready(function($) {

    // Display portability modal box
    $(document.body).on('click', '#bs3-grid-builder-nav-tools', function(e) {
        e.preventDefault();
        $.fn.bs3_grid_builder_BS3_Grid_Builder_Portability_Open();
    });

    // Destroy modal box
    $(document.body).on('click', '.bs3-grid-builder-portability .bs3-grid-builder-modal-close', function(e) {
        e.preventDefault();
        $.fn.bs3_grid_builder_BS3_Grid_Builder_Portability_Close();
    });

    // Modal Navbar
    $(document.body).on('click', '.bs3-grid-builder-portability-nav-bar', function(e) {
        e.preventDefault();
        var tab = $(this).parent('.tabs');
        tab.addClass('wp-tab-active');
        tab.siblings('.tabs').removeClass('wp-tab-active');
        var target = $(this).attr('href');
        $(target).show();
        $(target).siblings('.wp-tab-panel').hide();
    });

    // Export
    $(document.body).on('click', '#bs3-grid-builder-portability-export-action', function(e) {
        e.preventDefault();
        // Enable spinner
        $('#bs3-grid-builder-portability-export-spinner').addClass('is-active');
        var pb_object = $('#post').serializeObject();
        var row_ids = pb_object._bs3_grid_builder_row_ids;
        var rows = {};
        var items = {};
        if (row_ids) {
            row_ids.split(',').forEach(function(row_id) {
                rows[row_id] = pb_object._bs3_grid_builder_rows[row_id];
                // Col1
                pb_object._bs3_grid_builder_rows[row_id].col_1.split(',').forEach(function(item_id) {
                    items[item_id] = pb_object._bs3_grid_builder_items[item_id];
                });
                // Col2
                pb_object._bs3_grid_builder_rows[row_id].col_2.split(',').forEach(function(item_id) {
                    items[item_id] = pb_object._bs3_grid_builder_items[item_id];
                });
                // Col3
                pb_object._bs3_grid_builder_rows[row_id].col_3.split(',').forEach(function(item_id) {
                    items[item_id] = pb_object._bs3_grid_builder_items[item_id];
                });
                // Col4
                pb_object._bs3_grid_builder_rows[row_id].col_4.split(',').forEach(function(item_id) {
                    items[item_id] = pb_object._bs3_grid_builder_items[item_id];
                });
                // Col5
                pb_object._bs3_grid_builder_rows[row_id].col_5.split(',').forEach(function(item_id) {
                    items[item_id] = pb_object._bs3_grid_builder_items[item_id];
                });
                // Col6
                pb_object._bs3_grid_builder_rows[row_id].col_6.split(',').forEach(function(item_id) {
                    items[item_id] = pb_object._bs3_grid_builder_items[item_id];
                });
            });
        }

        // Convert data into JSON
        $.ajax({
            type: "POST",
            url: bs3_grid_builder_tools.ajax_url,
            data: {
                action: 'bs3_grid_builder_export_to_json',
                nonce: bs3_grid_builder_tools.ajax_nonce,
                row_ids: row_ids,
                rows: rows,
                items: items,
            },
            //dataType: 'json',
            success: function(data) {
                // Append data into textarea value
                $('#bs3-grid-builder-portability-export-textarea').val(data).slideDown();
                // Auto select textarea content
                $("#bs3-grid-builder-portability-export-textarea").focus(function() {
                    var $this = $(this);
                    $this.select();
                });
                // Enable spinner
                $('#bs3-grid-builder-portability-export-spinner').removeClass('is-active');
                return;
            },
        });

    });

    // Import
    $(document.body).on('change', '#bs3-grid-builder-portability-import-textarea', function(e) {
        var pb_data = $(this).val();
        if (pb_data) {
            $('#bs3-grid-builder-portability-import-action').removeClass('disabled');
        } else {
            $('#bs3-grid-builder-portability-import-action').addClass('disabled');
        }
    });
    $(document.body).on('click', '#bs3-grid-builder-portability-import-action', function(e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) {
            return false;
        } else {
            // Check for valid JSON
            var json_data = $('#bs3-grid-builder-portability-import-textarea').val();
            try {
                $.parseJSON(json_data)
                var obj_data = $.parseJSON(json_data);
            } catch (e) {
                alert($(this).data('alert'));
                return false;
            }
            // Enable spinner
            $('#bs3-grid-builder-portability-import-spinner').addClass('is-active');
            // Import via Ajax
            $.ajax({
                type: "POST",
                url: bs3_grid_builder_tools.ajax_url,
                data: {
                    action: 'bs3_grid_builder_import_data',
                    nonce: bs3_grid_builder_tools.ajax_nonce,
                    data: json_data,
                },
                success: function(data) {
                    $('input[name="_bs3_grid_builder_row_ids"]').val(obj_data.row_ids);
                    $('#bs3-grid-builder').empty();
                    $('#bs3-grid-builder-template-loader').empty().html(data);
                    $.fn.bs3_grid_builder_sortItems();
                    $.fn.bs3_grid_builder_BS3_Grid_Builder_Portability_Close();
                    return;
                },
            });
        }
    });
});