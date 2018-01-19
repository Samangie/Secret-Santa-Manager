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

    jQuery('.campaign_id').each(function () {
        var campaignID = jQuery(this).text();
        jQuery('.campaign-' + campaignID).find('.signin-btn a').hide();
    });

    if (document.cookie.indexOf("user_is_assigned=") >= 0) {
        jQuery('.popup.userAssigned').show('slide', { direction: 'right' }, 500);
        document.cookie = 'user_is_assigned=; Path=/Campaign; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        setTimeout(function() {
            jQuery('.popup.userAssigned').hide('slide', { direction: 'right' }, 500);;
        }, 3000);
    } else if (document.cookie.indexOf("user_is_already_assigned=") >= 0) {
        jQuery('.popup.userAlreadyAssigned').show('slide', { direction: 'right' }, 500);
        document.cookie = 'user_is_already_assigned=; Path=/Campaign; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        setTimeout(function() {
            jQuery('.popup.userAlreadyAssigned').hide('slide', { direction: 'right' }, 500);
        }, 3000);
    }
});
