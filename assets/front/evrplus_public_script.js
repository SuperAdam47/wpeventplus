
function confirmDelete() {
    if (confirm('Are you sure want to delete?')) {

    }
}

jQuery(document).ready(function ($) {
    jQuery('.paypal--sandbox-toggle').on('click', function (e) {
        e.preventDefault();
        jQuery('#evplus--sandbox').toggle();
    });

    jQuery('.offline--details-toggle').on('click', function (e) {
        e.preventDefault();
        jQuery('#evplus--offline-details').toggle();
    });

    jQuery('#eventplus_terms_cbox').on('click', function (e) {
        var oCbox = jQuery(this);

  
        if (oCbox.is(':checked') || oCbox.prop('checked')) {
            jQuery('#eventplus_form_fields').fadeIn();
            jQuery('html, body').animate({
                scrollTop: $("#eventplus_form_fields").offset().top - 40
            }, 500);
        } else {
            jQuery('#eventplus_form_fields').fadeOut();
        }

    });

    if (jQuery('#eventplus_terms_cbox').length) {
        jQuery('#eventplus_terms_cbox').trigger('click');
    }



    if (jQuery('#eventplus_register_btn').length) {
        var oRegisterBtn = jQuery('#eventplus_register_btn');
        oRegisterBtn.on('click touchend', function (e) {
            e.preventDefault();
            jQuery(this).hide();
            jQuery('#evrplusRegForm').slideDown();
        });

        if (oRegisterBtn.attr('data-show-form-default') == '1') {
            oRegisterBtn.trigger('click');
        }
    }


    jQuery('a.poplight').click(function () {
        var popID = jQuery(this).attr('rel');
        var popURL = jQuery(this).attr('href');

        var query = popURL.split('?');
        var dim = query[1].split('&');
        var popWidth = dim[0].split('=')[1];

        jQuery('#' + popID).fadeIn().css({'width': Number(popWidth)}).prepend('<a href="#" class="close"><img src="/wp-content/plugins/eventsplus/images/btn-close.png" class="btn_close" title="Close Window" alt="Close" /></a>');


        var popMargTop = (jQuery('#' + popID).height() + 80) / 2;
        var popMargLeft = (jQuery('#' + popID).width() + 80) / 2;


        jQuery('#' + popID).css({
            'margin-top': -popMargTop,
            'margin-left': -popMargLeft
        });

        jQuery('body').append('<div id="fade"></div>');
        jQuery('#fade').css({'filter': 'alpha(opacity=80)'}).fadeIn();
        return false;
    });

});
