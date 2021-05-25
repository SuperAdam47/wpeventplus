(function ($) {
'use strict'; 
$(document).ready(function () {
    $('.paypal--sandbox-toggle').on('click', function (e) {
        e.preventDefault();
        $('#evplus--sandbox').toggle();
    });

    $('.offline--details-toggle').on('click', function (e) {
        e.preventDefault();
        $('#evplus--offline-details').toggle();
    });

    $('#eventplus_terms_cbox').on('click', function (e) {
        var oCbox = $(this);

  
        if (oCbox.is(':checked') || oCbox.prop('checked')) {
            $('#eventplus_form_fields').fadeIn();
            $('html, body').animate({
                scrollTop: $("#eventplus_form_fields").offset().top - 150
            }, 500);
        } else {
            $('#eventplus_form_fields').fadeOut();
        }

    });

    if ($('#eventplus_terms_cbox').length) {
        $('#eventplus_terms_cbox').trigger('click');
    }



    if ($('#eventplus_register_btn').length) {
        var oRegisterBtn = $('#eventplus_register_btn');
        oRegisterBtn.on('click touchend', function (e) {
            e.preventDefault();
            $(this).hide();
            $('#evrplusRegForm').slideDown();
        });

        if (oRegisterBtn.attr('data-show-form-default') == '1') {
            oRegisterBtn.trigger('click');
        }
    }


    $('a.poplight').on('click',function () {
        var popID = $(this).attr('rel');
        var popURL = $(this).attr('href');

        var query = popURL.split('?');
        var dim = query[1].split('&');
        var popWidth = dim[0].split('=')[1];

        $('#' + popID).fadeIn().css({'width': Number(popWidth)}).prepend('<a href="#" class="close"><img src="/wp-content/plugins/eventsplus/images/btn-close.png" class="btn_close" title="Close Window" alt="Close" /></a>');


        var popMargTop = ($('#' + popID).height() + 80) / 2;
        var popMargLeft = ($('#' + popID).width() + 80) / 2;


        $('#' + popID).css({
            'margin-top': -popMargTop,
            'margin-left': -popMargLeft
        });

        $('body').append('<div id="fade"></div>');
        $('#fade').css({'filter': 'alpha(opacity=80)'}).fadeIn();
        return false;
    });

});
}(jQuery));