jQuery( document ).ready(function() {
    jQuery('.alert').each(function () {
        if (!jQuery(this).is(':empty')) {
            jQuery(this).show();
        }
    });
});