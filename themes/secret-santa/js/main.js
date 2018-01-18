jQuery( document ).ready(function() {
    jQuery('#date-input').datepicker({ minDate: 0 });

    jQuery('.errorMessage').each(function () {
        if (!jQuery(this).is(':empty')) {
            jQuery(this).show();
        }
    });

    jQuery('.campaign').each(function () {
       if(jQuery(this).find('.isAssigned:contains("1")').length > 0) {
           jQuery(this).find('.signin-btn a').remove();
       }
    });
    jQuery('.signin-btn a').show();

    //document.cookie = 'user_is_assigned=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    /*if (document.cookie.indexOf("user_is_assigned=") >= 0) {
        jQuery('.popup.userAssigned').show();

        setTimeout(function() {
            jQuery('.popup.userAssigned').hide();
        }, 3000);
    }*/
});
