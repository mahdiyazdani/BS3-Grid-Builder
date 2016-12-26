/**
 * Switcher between wp-editor and BS3 Grid Builder
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
jQuery(document).ready(function($) {

    // Click Tab
    $(document.body).on('click', '#bs3-grid-builder-switcher a.nav-tab', function(e) {
        e.preventDefault();

        if ($(this).hasClass('nav-tab-active')) {
            return false;
        }

        // Add Confirmed Class
        $(this).addClass('switch-confirmed');

        // Force switch to visual editor
        $.fn.bs3_grid_builder_switchEditor('bs3_grid_builder_editor');

        var this_data = $(this).data('bs3-grid-builder-switcher');

        // Trigger event when user clicks on wp-editor
        if ('editor' == this_data) {
            $('input[name="_bs3_grid_builder_active"]').val('');
            $(this).addClass('nav-tab-active');
            $(this).siblings('.nav-tab').removeClass('nav-tab-active');
            $('html').removeClass('bs3_grid_builder_active');
        } else if ('builder' == this_data) {
            $('html').addClass('bs3_grid_builder_active');
            $('input[name="_bs3_grid_builder_active"]').val('1');
            $(this).addClass('nav-tab-active');
            $(this).siblings('.nav-tab').removeClass('nav-tab-active');
        }
    });
});