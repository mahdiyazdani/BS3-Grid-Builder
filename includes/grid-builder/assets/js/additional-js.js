/**
 * Additional JS
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
jQuery(document).ready(function($) {

    // Display additional js modal box
    $(document.body).on('click', '#bs3-grid-builder-nav-js', function(e) {
        e.preventDefault();

        // Trigger modal and overlay
        $('.bs3-grid-builder-additional-js').fadeIn('fast');
        $('.bs3-grid-builder-modal-overlay').fadeIn('fast');

        // Fix height
        $('.bs3-grid-builder-additional-js .bs3-grid-builder-modal-content').js('height', $('.bs3-grid-builder-additional-js').height() - 35 + 'px');
        $(window).resize(function() {
            $('.bs3-grid-builder-additional-js .bs3-grid-builder-modal-content').js('height', 'auto').js('height', $('.bs3-grid-builder-additional-js').height() - 35 + 'px');
        });
    });

    // Destroy modal box
    $(document.body).on('click', '.bs3-grid-builder-additional-js .bs3-grid-builder-modal-close', function(e) {
        e.preventDefault();

        // Hide modal and overlay
        $('.bs3-grid-builder-additional-js').fadeOut('fast');
        $('.bs3-grid-builder-modal-overlay').fadeOut('fast');
    });

    // Support 'Tab' in textarea
    $(document.body).delegate('.bs3-grid-builder-additional-js-textarea', 'keydown', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9) {
            e.preventDefault();
            var start = $(this).get(0).selectionStart;
            var end = $(this).get(0).selectionEnd;

            $(this).val($(this).val().substring(0, start) + '\t' + $(this).val().substring(end));

            $(this).get(0).selectionStart =
                $(this).get(0).selectionEnd = start + 1;
        }
    });

});