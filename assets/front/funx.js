function checkInternationalPhone(strPhone) {
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
    if (strPhone.indexOf("(") != -1 && strPhone.indexOf("(") > bracket) {
        return false;
    }
    var brchr = strPhone.indexOf("(");
    if (strPhone.indexOf("(") != -1 && strPhone.charAt(brchr + 2) != ")") {
        return false;
    }
    if (strPhone.indexOf("(") == -1 && strPhone.indexOf(")") != -1) {
        return false;
    }
    s = stripCharsInBag(strPhone, validWorldPhoneChars);
    return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}
function echeck(str) {
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
function testIsValidObject(objToTest) {
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
function validateConfirmationForm(confForm) {
    var msg = "";
    var i = 0;
    var form = confForm['attendee[' + i + '][first_name]'];
    while (form != undefined)
    {
        if (confForm['attendee[' + i + '][first_name]'].value == "") {
            msg += "\n Attendee #" + (i + 1) + " Please enter attendee first name";
            confForm['attendee[' + i + '][first_name]'].focus( );
        }
        if (confForm['attendee[' + i + '][last_name]'].value == "") {
            msg += "\n Attendee #" + (i + 1) + " Please enter attendee last name";
            confForm['attendee[' + i + '][last_name]'].focus( );
        }
        i++;
        var form = confForm['attendee[' + i + '][first_name]'];
    }
    if (msg.length > 0) {
        msg = "The following fields need to be completed before you can submit.\n\n" + msg;
        alert(msg);
        if (document.getElementById("mySubmit").disabled == true) {
            document.getElementById("mySubmit").disabled = false;
        }
        document.getElementById("mySubmit").focus( );
        return false;
    }
    return true;
}
function validateForm(form) {
    var msg = "";
    if (form.fname.value == "") {
        msg += "\n " + "Please enter your first name";
        form.fname.focus( );
    }
    if (form.lname.value == "") {
        msg += "\n " + "Please enter your last name";
        form.lname.focus( );
    }
    if (echeck(form.email.value) == false) {
        msg += "\n " + "Email format not correct!";
    }
    if (form.phone) {
        if (form.phone.value == "" || form.phone.value == null) {
            msg += "\n " + "Please enter your phone number.";
            form.phone.focus( );
        }
        if (checkInternationalPhone(form.phone.value) == false) {
            msg += "\n " + "Please use correct format for your phone number.";
            form.value = "";
            form.phone.focus();
        }
    }
    if (form.address) {
        if (form.address.value == "") {
            msg += "\n " + "Please enter your address.";
            form.address.focus( );
        }
    }
    if (form.city) {
        if (form.city.value == "") {
            msg += "\n " + "Please enter your city.";
            form.city.focus( );
        }
    }
    if (form.state) {
        if (form.state.value == "") {
            msg += "\n " + "Please enter your state.";
            form.state.focus( );
        }
    }
    if (form.zip) {
        if (form.zip.value == "") {
            msg += "\n " + "Please enter your zip/postal code.";
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
        if (e.type == "text" && e.title && !value && e.className == "r") {
            msg += "\n " + e.title;
        }
        if ((e.type == "radio" || e.type == "checkbox") && e.className == "r") {
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
                msg += "\n " + e.title;
            }
        }
    }
    var inputs = form.getElementsByTagName("textarea");
    var e;
    for (var i = 0, e; e = inputs[i]; i++) {
        var value = e.value ? trim(e.value) : null;
        if (!value && e.className == "r")
        {
            msg += "\n " + e.title;
        }
    }
    var inputs = form.getElementsByTagName("select");
    var e;
    for (var i = 0, e; e = inputs[i]; i++) {
        var value = e.value ? trim(e.value) : null;
        if ((!value || value == '') && e.className == "r")
        {
            msg += "\n " + e.title;
        }
    }
    if (msg.length > 0) {
        msg = "The following fields need to be completed before you can submit.\n\n" + msg;
        alert(msg);
        if (document.getElementById("mySubmit").disabled == true) {
            document.getElementById("mySubmit").disabled = false;
        }
        document.getElementById("mySubmit").focus( );
        return false;
    }

    return true;
}

function CalculateTotalTax(frm) {
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
            }
            else if (item_one <= 0) {
                frm.mySubmit.disabled = true;
            }
            if (item_quantity >= 0) {
                order_total += item_quantity * item_price;

                if (order_total < 0) {
                    frm.mySubmit.disabled = true;
                }
            }
        }
    }

    frm.fees.value = round_decimals(order_total, 2);
    tax_total = order_total * tax_rate;
    frm.tax.value = round_decimals(tax_total, 2);

    var grand_total = order_total + tax_total;
    
    frm.total.value = round_decimals(grand_total, 2);
    
    if (item_one) {
        var discountPercentage = getDiscountPercentage(item_one);

        frm.discount.value = 0;
        if (discountPercentage > 0) {

            var discount = (grand_total * discountPercentage) / 100;

            if (isNaN(discount) == false) {
                grand_total = grand_total - discount;
                frm.discount.value = round_decimals(discount, 2);
            }
        }
    }

    frm.displaytotal.value = round_decimals(grand_total, 2);
}


function getDiscountPercentage(qty) {
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

function CalculateTotal(frm) {

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
            }
            else if (item_one <= 0) {
                frm.mySubmit.disabled = true;
            }
            if (item_quantity >= 0) {
                order_total += item_quantity * item_price;
                if (order_total < 0) {
                    frm.mySubmit.disabled = true;
                }
            }
        }
    }

    frm.total.value = round_decimals(order_total, 2);
    frm.fees.value = round_decimals(order_total, 2);
    
    if (item_one && order_total > 0) {
        var discountPercentage = getDiscountPercentage(item_one);

        frm.discount.value = 0;
        if (discountPercentage > 0) {

            var discount = (order_total * discountPercentage) / 100;

            if (isNaN(discount) == false) {
                order_total = order_total - discount;
                frm.discount.value = round_decimals(discount, 2);
            }
        }
    }
    
    frm.displaytotal.value = round_decimals(order_total, 2);

}

function round_decimals(original_number, decimals) {
    var result1 = original_number * Math.pow(10, decimals);
    var result2 = Math.round(result1);
    var result3 = result2 / Math.pow(10, decimals);
    return pad_with_zeros(result3, decimals);
}

function pad_with_zeros(rounded_value, decimal_places) {

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

function a_message()
{
    alert('I came from an external script! Ha, Ha, Ha!!!!');
} 