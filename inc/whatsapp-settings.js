/**
 * WhatsApp Settings JavaScript
 */

jQuery(document).ready(function($) {
    // Toggle auto-open delay field
    $('#whatsapp_auto_open').on('change', function() {
        if ($(this).is(':checked')) {
            $('#auto-open-delay-row').show();
        } else {
            $('#auto-open-delay-row').hide();
        }
    });
});

