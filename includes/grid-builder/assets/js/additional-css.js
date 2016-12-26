/**
 * Additional CSS
 *
 * @author      Mahdi Yazdani
 * @package     BS3 Grid Builder
 * @since       1.0
 */
jQuery(document).ready(function($) {

    // Display additional css modal box
    $(document.body).on('click', '#bs3-grid-builder-nav-css', function(e) {
        e.preventDefault();

        // Trigger modal and overlay
        $('.bs3-grid-builder-additional-css').fadeIn('fast');
        $('.bs3-grid-builder-modal-overlay').fadeIn('fast');

        // Fix height
        $('.bs3-grid-builder-additional-css .bs3-grid-builder-modal-content').css('height', $('.bs3-grid-builder-additional-css').height() - 35 + 'px');
        $(window).resize(function() {
            $('.bs3-grid-builder-additional-css .bs3-grid-builder-modal-content').css('height', 'auto').css('height', $('.bs3-grid-builder-additional-css').height() - 35 + 'px');
        });
    });

    // Destroy modal box
    $(document.body).on('click', '.bs3-grid-builder-additional-css .bs3-grid-builder-modal-close', function(e) {
        e.preventDefault();

        // Hide modal and overlay
        $('.bs3-grid-builder-additional-css').fadeOut('fast');
        $('.bs3-grid-builder-modal-overlay').fadeOut('fast');
    });

    // Support 'Tab' in textarea
    $(document.body).delegate('.bs3-grid-builder-additional-css-textarea', 'keydown', function(e) {
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