function eventplus_checkInternationalPhone(strPhone) {
    var digits = "0123456789";
    var phoneNumberDelimiters = "()- ";
    var validWorldPhoneChars = phoneNumberDelimiters + "+";
    var minDigitsInIPhoneNumber = 8;
    function isInteger(s) {
        var i;
        for (i = 0; i < s.length; i++) {
            var c = s.charAt(i);
            if (((c < "0") || (c > "9"))) {
                return false;
            }
        }
        return true;
    }
    function trim(s) {
        var i;
        var returnString = "";
        for (i = 0; i < s.length; i++) {
            var c = s.charAt(i);
            if (c != " ")
                returnString += c;
        }
        return returnString;
    }
    function stripCharsInBag(s, bag) {
        var i;
        var returnString = "";
        for (i = 0; i < s.length; i++) {
            var c = s.charAt(i);
            if (bag.indexOf(c) == -1)
                returnString += c;
        }
        return returnString;
    }
    var bracket = 3;
    strPhone = trim(strPhone);
    if (strPhone.indexOf("+") > 1) {
        return false;
    }
    if (strPhone.indexOf("-") != -1) {
        bracket = bracket + 1;
    }
    if (strPhone.indexOf("(") > bracket) {
        return false;
    }


    s = stripCharsInBag(strPhone, validWorldPhoneChars);
    return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}

function eventplus_echeck(str) {
    var at = "@";
    var dot = ".";
    var em = "";
    var lat = str.indexOf(at);
    var lstr = str.length;
    var ldot = str.indexOf(dot);
    if (str.indexOf(at) == -1) {
        return false;
    }
    if (str.indexOf(at) == -1 || str.indexOf(at) == 0 || str.indexOf(at) == lstr) {
        return false;
    }
    if (str.indexOf(dot) == -1 || str.indexOf(dot) == 0 || str.indexOf(dot) == lstr) {
        return false;
    }
    if (str.indexOf(at, (lat + 1)) != -1) {
        return false;
    }
    if (str.substring(lat - 1, lat) == dot || str.substring(lat + 1, lat + 2) == dot) {
        return false;
    }
    if (str.indexOf(dot, (lat + 2)) == -1) {
        return false;
    }
    if (str.indexOf(" ") != -1) {
        return false;
    }
    return true;
}

function eventplus_testIsValidObject(objToTest) {
    if (objToTest == null || objToTest == undefined) {
        return false;
    }
    return true;
}

function jcap() {
    var uword = hex_md5(document.getElementById(jfldid).value);
    if (uword == cword[anum - 1]) {
        return true;
    }
    else {
        return false;
    }
}

function eventplus_validateConfirmationForm(confForm) {

    var oActionMsgContainer = jQuery('#action_message_eplus_container');
    var oActionMsg = jQuery('#form_action_message_eplus');

    oActionMsgContainer.fadeOut();
    oActionMsg.fadeOut();

    var msg = "";
    var i = 0;
    var form = confForm['attendee[' + i + '][first_name]'];
    while (form != undefined)
    {
        if (confForm['attendee[' + i + '][first_name]'].value == "") {
            msg += "<li>Attendee #" + (i + 1) + " Please enter attendee first name</li>";
            confForm['attendee[' + i + '][first_name]'].focus( );
        }
        if (confForm['attendee[' + i + '][last_name]'].value == "") {
            msg += "<li>Attendee #" + (i + 1) + " Please enter attendee last name</li>";
            confForm['attendee[' + i + '][last_name]'].focus( );
        }
        i++;
        var form = confForm['attendee[' + i + '][first_name]'];
    }

    if (msg.length > 0) {
        msg = "<ul>" + msg + "</ul>";
        oActionMsgContainer.fadeIn();
        oActionMsg.html(msg);
        oActionMsg.fadeIn();
        if (document.getElementById("myConfirmSubmit").disabled == true) {
            document.getElementById("myConfirmSubmit").disabled = false;
        }
        document.getElementById("myConfirmSubmit").focus();
        return false;
    }
    return true;
}

function eventplus_validateForm(form) {
    var msg = "";

    var oActionMsgContainer = jQuery('#action_message_eplus_container');
    var oActionMsg = jQuery('#form_action_message_eplus');

    oActionMsgContainer.fadeOut();
    oActionMsg.fadeOut();

    if (form.fname.value == "") {
        msg += "<li>" + validationErrors.fname + "</li>";
        form.fname.focus( );
    }
    if (form.lname.value == "") {
        msg += "<li>" + validationErrors.lname + "</li>";
        form.lname.focus( );
    }
    if (eventplus_echeck(form.email.value) == false) {
        msg += "<li>" + validationErrors.email + "</li>";
    }
    if (form.phone) {
        if (form.phone.value == "" || form.phone.value == null) {
            msg += "<li>" + validationErrors.phone + "</li>";
            form.phone.focus( );
        }
        if (eventplus_checkInternationalPhone(form.phone.value) == false) {
            msg += "<li>" + validationErrors.phone_invalid + "</li>";
            form.value = "";
            form.phone.focus();
        }
    }
    if (form.address) {
        if (form.address.value == "") {
            msg += "<li>" + validationErrors.address + "</li>";
            form.address.focus( );
        }
    }
    if (form.city) {
        if (form.city.value == "") {
            msg += "<li>" + validationErrors.city + "</li>";
            form.city.focus( );
        }
    }
    if (form.state) {
        if (form.state.value == "") {
            msg += "<li>" + validationErrors.state + "</li>";
            form.state.focus( );
        }
    }

    if (form.zip) {
        if (form.zip.value == "") {
            msg += "<li>" + validationErrors.zip + "</li>";
            form.zip.focus( );
        }
    }
    function trim(s) {
        if (s) {
            return s.replace(/^\s*|\s*$/g, "");
        }
        return null;
    }

    var inputs = form.getElementsByTagName("input");
    var e;
    for (var i = 0, e; e = inputs[i]; i++) {
        var value = e.value ? trim(e.value) : null;
        if (e.type == "text" && e.title && !value && trim(e.className) == "eplus_required_cq") {
            msg += "<li> " + e.title + "</li>";
        }
        if ((e.type == "radio" || e.type == "checkbox") && trim(e.className) == "eplus_required_cq") {
            var rd = ""
            var controls = form.elements;
            function getSelectedControl(group)
            {
                for (var i = 0, n = group.length; i < n; ++i)
                    if (group[i].checked)
                        return group[i];
                return null;
            }

            if (!getSelectedControl(controls[e.name])) {
                msg += "<li> " + e.title + "</li>";
                break;
            }
        }
    }

    var inputs = form.getElementsByTagName("textarea");
    var e;
    for (var i = 0, e; e = inputs[i]; i++) {
        var value = e.value ? trim(e.value) : null;
        if (!value && trim(e.className) == "eplus_required_cq")
        {
            msg += "<li> " + e.title + "</li>";
        }
    }
    var inputs = form.getElementsByTagName("select");
    var e;
    for (var i = 0, e; e = inputs[i]; i++) {
        var value = e.value ? trim(e.value) : null;
        if ((!value || value == '') && trim(e.className) == "eplus_required_cq")
        {
            msg += "<li> " + e.title + "</li>";
        }
    }


    if (form.accept_term) {
        if (form.accept_term.checked == false) {
            msg += "<li> " + validationErrors.accept_terms + "</li>";
        }
    }

    if (msg.length > 0) {
        msg = "<ul>" + msg + "</ul>";

        oActionMsgContainer.fadeIn();
        oActionMsg.html(msg);
        oActionMsg.fadeIn();

        if (document.getElementById("mySubmit").disabled == true) {
            document.getElementById("mySubmit").disabled = false;
        }
        document.getElementById("mySubmit").focus();
        return false;
    }

    return true;
}

function eventplus_CalculateTotalTax(frm) {
    var tax_rate = document.getElementById('tax_rate');

    if (tax_rate) {
        tax_rate = tax_rate.value;
    }

    var order_total = 0;
    var item_one = 0;

    for (var i = 0; i < frm.elements.length; ++i) {
        form_field = frm.elements[i];
        form_name = form_field.name;
        if (form_name.substring(0, 4) == "PROD") {
            item_price = parseFloat(form_name.substring(form_name.lastIndexOf("_") + 1));
            item_quantity = parseInt(form_field.value);

            item_one = item_one + item_quantity;
            if (item_one > 0) {
                frm.mySubmit.disabled = false;
                jQuery('#event_fee_item_message').fadeOut();
                jQuery('#eplus-data-summary-container').fadeIn();
            }
            else if (item_one <= 0) {
                frm.mySubmit.disabled = true;

                jQuery('#event_fee_item_message').fadeIn();
                jQuery('#eplus-data-summary-container').fadeOut();
            }
            if (item_quantity >= 0) {
                order_total += item_quantity * item_price;

                if (order_total < 0) {
                    frm.mySubmit.disabled = true;
                }
            }
        }
    }

    if (order_total <= 0) {
        return;
    }

    frm.fees.value = eventplus_round_decimals(order_total, 2);
    tax_total = order_total * tax_rate;
    frm.tax.value = eventplus_round_decimals(tax_total, 2);

    var grand_total = order_total + tax_total;

    frm.total.value = eventplus_round_decimals(grand_total, 2);

    if (item_one) {
        var discountPercentage = eventplus_getDiscountPercentage(item_one);

        frm.discount.value = 0;
        if (discountPercentage > 0) {

            var discount = (grand_total * discountPercentage) / 100;

            if (isNaN(discount) == false) {
                grand_total = grand_total - discount;
                frm.discount.value = eventplus_round_decimals(discount, 2);
            }
        }
    }

    frm.displaytotal.value = eventplus_round_decimals(grand_total, 2);
}


function eventplus_getDiscountPercentage(qty) {
    var percentage = 0;
    if (qty > 0) {

        if (discountSettings.length) {
            for (var i = discountSettings.length; i > 0; i--) {
                var rS = discountSettings[i];

                if (rS) {

                    var qtySet = rS.split(':');

                    if (qtySet) {
                        var qtyDiscount = qtySet[0];
                        var discountPercentage = qtySet[1];

                        if (qty > qtyDiscount && discountPercentage > 0 && discountPercentage <= 100) {
                            percentage = discountPercentage;
                            break;
                        }
                    }
                }

            }
        }
    }

    return percentage;

}

function eventplus_CalculateTotal(frm) {

    var order_total = 0;
    var item_one = 0;
    for (var i = 0; i < frm.elements.length; ++i) {
        form_field = frm.elements[i];
        form_name = form_field.name;
        if (form_name.substring(0, 4) == "PROD") {
            item_price = parseFloat(form_name.substring(form_name.lastIndexOf("_") + 1));
            item_quantity = parseInt(form_field.value);

            item_one = item_one + item_quantity;
            if (item_one > 0) {
                frm.mySubmit.disabled = false;
                jQuery('#event_fee_item_message').fadeOut();
                jQuery('#eplus-data-summary-container').fadeIn();
            }
            else if (item_one <= 0) {
                frm.mySubmit.disabled = true;
                jQuery('#event_fee_item_message').fadeIn();
                jQuery('#eplus-data-summary-container').fadeOut();
            }

            if (item_quantity >= 0) {
                order_total += item_quantity * item_price;
                if (order_total < 0) {
                    frm.mySubmit.disabled = true;
                }
            }
        }
    }


    if (order_total <= 0) {
        return;
    }

    frm.total.value = eventplus_round_decimals(order_total, 2);
    frm.fees.value = eventplus_round_decimals(order_total, 2);

    if (item_one && order_total > 0) {
        var discountPercentage = eventplus_getDiscountPercentage(item_one);

        frm.discount.value = 0;
        if (discountPercentage > 0) {

            var discount = (order_total * discountPercentage) / 100;

            if (isNaN(discount) == false) {
                order_total = order_total - discount;
                frm.discount.value = eventplus_round_decimals(discount, 2);
            }
        }
    }

    frm.displaytotal.value = eventplus_round_decimals(order_total, 2);

}

function eventplus_round_decimals(original_number, decimals) {
    var result1 = original_number * Math.pow(10, decimals);
    var result2 = Math.round(result1);
    var result3 = result2 / Math.pow(10, decimals);
    return eventplus_pad_with_zeros(result3, decimals);
}

function eventplus_pad_with_zeros(rounded_value, decimal_places) {

    var value_string = rounded_value.toString();
    var decimal_location = value_string.indexOf(".");
    if (decimal_location == -1) {
        decimal_part_length = 0
        value_string += decimal_places > 0 ? "." : "";
    }
    else {
        decimal_part_length = value_string.length - decimal_location - 1;
    }
    var pad_total = decimal_places - decimal_part_length;
    if (pad_total > 0) {
        for (var counter = 1; counter <= pad_total; counter++) {
            value_string += "0";
        }
    }
    return value_string;
}

(function ($) {
'use strict'; 
$(document).ready(function () {

    if ($('.eventplus-ddl-items').val() >= 0) {
        $('#mySubmit').removeAttr('disabled');
    }

    if ($('#qty_attendees').length && $('#eventplus_attendee_form_confirm').length) {
        if ($('#qty_attendees').val() == '1') {
            $('#eventplus_attendee_form_confirm').submit();
        }
    }

    $('.eventplus-ddl-items').trigger('change');

    $('.paypal--sandbox-toggle').on('click', function (e) {
        e.preventDefault();
        $('#evplus--sandbox').toggle();
    });

    $('.offline--details-toggle').on('click', function (e) {
        e.preventDefault();
        $('#evplus--offline-details').toggle();
    });

    if ($('#eventplus_register_btn').length) {
        var oRegisterBtn = $('#eventplus_register_btn');
        oRegisterBtn.on('click touchend', function (e) {
            e.preventDefault();
            $(this).hide();
            $('#evrplusRegForm').slideDown();

            if ($('.eventplus-registration-actions').length == 1) {
                $('#eventplus_actions_registration_btns').hide();
            }
        });

        if (oRegisterBtn.attr('data-show-form-default') == '1') {
            oRegisterBtn.trigger('click');
        }

    }


    $('.eplus-required').on('change', function () {
        var oSelf = $(this);
        var oParent = oSelf.parent('.fi3ld');
        var oValidationMsg = oParent.find('span.valida8ion-msg');
        if (!oValidationMsg.length) {
            oParent.append('<span class="valida8ion-msg r3d" style="display:none;">&nbsp;</span>');
            oValidationMsg = oParent.find('span.valida8ion-msg');
        }

        if (oSelf.val() == '') {
            oParent.addClass('r3d');
            oParent.removeClass('gr33n');

            if (validationErrors) {
                oValidationMsg.html(validationErrors.required);
                oValidationMsg.fadeIn();
            }
        } else {

            if (oSelf.attr('type') == 'email') {
                if (eventplus_echeck(oSelf.val()) == false) {
                    oParent.addClass('r3d');
                    oParent.removeClass('gr33n');
                    if (validationErrors) {
                        oValidationMsg.html(validationErrors.invalid);
                        oValidationMsg.fadeIn();
                    }
                } else {
                    oParent.addClass('gr33n');
                    oParent.removeClass('r3d');
                    oValidationMsg.fadeOut();
                }

                return;
            }

            if (oSelf.hasClass('eplus-phone')) {

                if (eventplus_checkInternationalPhone(oSelf.val()) == false) {
                    oParent.addClass('r3d');
                    oParent.removeClass('gr33n');
                    if (validationErrors) {
                        oValidationMsg.html(validationErrors.invalid);
                        oValidationMsg.fadeIn();
                    }
                } else {
                    oParent.addClass('gr33n');
                    oParent.removeClass('r3d');
                    oValidationMsg.fadeOut();
                }

                return;
            }

            oParent.addClass('gr33n');
            oParent.removeClass('r3d');
            oValidationMsg.fadeOut();
        }
    });

    try {
        var _endDate = parseInt($('#evrplus_counter').attr('data-end-date'));
        var _now = parseInt($('#evrplus_counter').attr('data-now'));
        if( _endDate ) {
            var endDate = new Date(_endDate);

            if( $('#evrplus_counter').hasClass('initialize') ) return;
            $('#evrplus_counter').addClass('initialize');

            $('#evrplus_counter').redCountdown({
                end: $.now() + (((endDate.getTime() * 1000) - $.now()) / 1000),
                labels: true,
                style: {
                    element: "",
                    textResponsive: 0.5,
                    daysElement: {
                        gauge: {
                            thickness: 0.2,
                            bgColor: "#cccccc",
                            fgColor: "#1ABC9C"
                        },
                        textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                    },
                    hoursElement: {
                        gauge: {
                            thickness: 0.2,
                            bgColor: "#cccccc",
                            fgColor: "#2980B9"
                        },
                        textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                    },
                    minutesElement: {
                        gauge: {
                            thickness: .2,
                            bgColor: "#cccccc",
                            fgColor: "#8E44AD"
                        },
                        textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                    },
                    secondsElement: {
                        gauge: {
                            thickness: .2,
                            bgColor: "#cccccc",
                            fgColor: "#F39C12"
                        },
                        textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                    }
                }});
        }
    } catch (ex) {
       
    }
});
}(jQuery));