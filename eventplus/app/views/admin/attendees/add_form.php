<?php
$event_id = $oEvent->id;

$form_url = $this->adminUrl('admin_attendees/add', array('event_id' => $event_id));

$reg_form_defaults = unserialize($oEvent->reg_form_defaults);
if ($reg_form_defaults != "") {
    if (in_array("Address", $reg_form_defaults)) {
        $inc_address = "Y";
    }
    if (in_array("City", $reg_form_defaults)) {
        $inc_city = "Y";
    }
    if (in_array("State", $reg_form_defaults)) {
        $inc_state = "Y";
    }
    if (in_array("Zip", $reg_form_defaults)) {
        $inc_zip = "Y";
    }
    if (in_array("Phone", $reg_form_defaults)) {
        $inc_phone = "Y";
    }
}
$event_category = unserialize($oEvent->event_category);
$reg_limit = $oEvent->reg_limit;
$event_name = stripslashes($oEvent->event_name);
$use_coupon = $oEvent->use_coupon;
?>
<style type="text/css">.ui-tooltip, .arrow:before {background: #5BA4A4;border:1px #fff solid !important;}.ui-tooltip {padding: 10px 10px;color: white;font: bold 13px "Helvetica Neue", Sans-Serif;}.arrow {width: 70px;height: 25px;overflow: hidden;position: absolute;bottom: 5px;left: -26px;z-index: -1;}.arrow{display:none !important;}.arrow:before {content: "";position: absolute;left: 20px;top: 0px;width: 25px;height: 25px;-webkit-transform: rotate(45deg);-moz-transform: rotate(45deg);-ms-transform: rotate(45deg);-o-transform: rotate(45deg);tranform: rotate(45deg);}</style>
<br /><br />
<div class="wrap">
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class='postbox-container' style='width:55%;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox ">
                        <div class="inside">                    
                            <div class="padding">                           
                                <h3><span><?php echo $form_heading; ?> for <?php echo stripslashes($event_name); ?></span></h3>                 

                                <form method="post" action="<?php echo $form_url; ?>" onSubmit="return eventplus_validateForm(this)">
                                    <ul class="ssa">
                                        <li><div class="pass1"><label for="fname"><?php _e('First Name', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="fname" name="fname" /></div></li>
                                        <li><div class="pass1"><label for="lname"><?php _e('Last Name', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="lname" name="lname" /></div></li>
                                        <li><div class="pass1"><label for="email" ><?php _e('Email Address', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="email" name="email"/></div></li>
                                        <?php if ($inc_phone == "Y") { ?>
                                            <li><div class="pass1"><label for="phone" ><?php _e('Phone Number', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="phone" name="phone" /></div></li>
                                        <?php } ?>
                                        <?php if ($inc_address == "Y") { ?> 
                                            <li><div class="pass1"><label for="address"><?php _e('Address', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="address" name="address"  /></div></li>
                                        <?php } ?>
                                        <?php if ($inc_city == "Y") { ?> 
                                            <li><div class="pass1"><label for="city"><?php _e('City', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="city" name="city" /></div></li>
                                        <?php } ?>
                                        <?php if ($inc_state == "Y") { ?> 
                                            <li><div class="pass1"><label for="state"><?php _e('State/Province', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="state" name="state" /></div></li>
                                        <?php } ?> 
                                        <?php if ($inc_zip == "Y") { ?> 
                                            <li><div class="pass1"><label for="zip"><?php _e('Zip/Postal Code', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="zip" name="zip"  /></div></li>
                                        <?php } ?>       
                                        <?php if ($use_coupon == "Y") { ?>
                                            <li><div class="pass1"><label for="coupon"><?php _e('Enter coupon code', 'evrplus_language'); ?></label></div><div class="pass2"><input type="text" id="coupon" name="coupon" /></div></li>
                                        <?php } ?>

                                    </ul>
                                    <?php
                                    $num = 0;
                                    $sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE  payment_status = 'success' AND event_id='$event_id'";

                                    $attendee_count = $this->wpDb()->get_var($sql2);

                                    if ($attendee_count >= 1) {
                                        $num = $attendee_count;
                                    }

                                    $available = $reg_limit - $num;

                                    if ($available >= 1) {
                                        ?>                
                                        <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl('images/dollar-icon.png'); ?>"><?php _e('Registration Fees', 'evrplus_language'); ?></span><br /><br/>
                                        <?php
                                        $open_seats = $available;
                                        $curdate = date("Y-m-d");
                                        $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . (int) $event_id . " ORDER BY sequence ASC";
                                        //$result = mysql_query ( $sql );
                                        //while ($row = mysql_fetch_assoc ($result)){
                                        $result = $this->wpDb()->get_results($sql, ARRAY_A);
                                        foreach ($result as $row) {
                                            $item_id = $row['id'];
                                            $item_sequence = $row['sequence'];
                                            $event_id = $row['event_id'];
                                            $item_title = $row['item_title'];
                                            $item_description = $row['item_description'];
                                            $item_cat = $row['item_cat'];
                                            $item_limit = $row['item_limit'];
                                            $item_price = $row['item_price'];
                                            $free_item = $row['free_item'];
                                            $item_start_date = $row['item_available_start_date'];
                                            $item_end_date = $row['item_available_end_date'];
                                            $item_custom_cur = $row['item_custom_cur'];
                                            ?>
                                            <input type="hidden" name="reg_type" value="RGLR"/>
                                            <div class="pass1"><?php echo $item_title . "    " . $item_custom_cur . " " . $item_price; ?></div><div class="pass2"><select name="PROD_<?php echo $event_id . "-" . $item_id . "_" . $item_price; ?>" id = "PROD_<?php
                                                echo
                                                $event_id . "-" . $item_id . "_" . $item_price;
                                                ?>" onChange="eventplus_CalculateTotal(this.form)"  >
                                                    <option value="0">0</option>
                                                    <?php
                                                    if ($item_cat == "REG") {
                                                        if ($ticket_limit != "") {
                                                            if ($available >= $item_limit) {
                                                                $available = $item_limit;
                                                            }
                                                        }
                                                        
                                                        if($available > 499){
                                                            $available = 500;
                                                        }
                                                        
                                                        for ($i = 1; $i < $available + 1; $i++) {
                                                            ?>
                                                            <option value="<?php echo($i); ?>"><?php echo($i); ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    if ($item_cat != "REG") {
                                                        $num_select = "10";
                                                        if ($ticket_limit != "") {
                                                            $num_select = $item_limit;
                                                        }

                                                        for ($i = 1; $i < $num_select + 1; $i++) {
                                                            ?>
                                                            <option value="<?php echo($i); ?>"><?php echo($i); ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            // }
                                        }
                                        ?>
                                        <li><div class="pass1"><b><?php _e('Registration TOTAL', 'evrplus_language'); ?></b></div><div class="pass2">  <input type="text" readonly name="total" id="total" size="10" value="0.00" /></div></li>

                                        </ul>
                                        <br />
                                        <?php
                                    } else {
                                        echo '<hr><br><b><font color="red">';
                                        _e('This event has reached registration capacity.', 'evrplus_language');
                                        echo "<br>";
                                        _e('Please provide your information to be placed on the waiting list.', 'evrplus_language');
                                        echo '</b></font>';
                                        ?>
                                        <input type="hidden" name="reg_type" value="WAIT" />
                                        <?php
                                    }
                                    ?>

                                    <script language="JavaScript" type="text/javascript">
                                    <!--
                                
                                        /* This script is Copyright (c) Paul McFedries and 
                                         Logophilia Limited (http://www.mcfedries.com/).
                                         Permission is granted to use this script as long as 
                                         this Copyright notice remains in place.*/

                                        function eventplus_CalculateTotal(frm) {
                                            var order_total = 0

                                            // Run through all the form fields
                                            for (var i = 0; i < frm.elements.length; ++i) {

                                                // Get the current field
                                                form_field = frm.elements[i]

                                                // Get the field's name
                                                form_name = form_field.name

                                                // Is it a "product" field?
                                                if (form_name.substring(0, 4) == "PROD") {

                                                    // If so, extract the price from the name
                                                    item_price = parseFloat(form_name.substring(form_name.lastIndexOf("_") + 1))

                                                    // Get the quantity
                                                    item_quantity = parseInt(form_field.value)

                                                    // Update the order total
                                                    if (item_quantity >= 0) {
                                                        order_total += item_quantity * item_price
                                                    }
                                                }
                                            }

                                            // Display the total rounded to two decimal places
                                            frm.total.value = eventplus_round_decimals(order_total, 2)
                                        }
                                        function eventplus_round_decimals(original_number, decimals) {
                                            var result1 = original_number * Math.pow(10, decimals)
                                            var result2 = Math.round(result1)
                                            var result3 = result2 / Math.pow(10, decimals)
                                            return eventplus_pad_with_zeros(result3, decimals)
                                        }

                                        function eventplus_pad_with_zeros(rounded_value, decimal_places) {

                                            // Convert the number to a string
                                            var value_string = rounded_value.toString()

                                            // Locate the decimal point
                                            var decimal_location = value_string.indexOf(".")

                                            // Is there a decimal point?
                                            if (decimal_location == -1) {

                                                // If no, then all decimal places will be padded with 0s
                                                decimal_part_length = 0

                                                // If decimal_places is greater than zero, tack on a decimal point
                                                value_string += decimal_places > 0 ? "." : ""
                                            }
                                            else {

                                                // If yes, then only the extra decimal places will be padded with 0s
                                                decimal_part_length = value_string.length - decimal_location - 1
                                            }

                                            // Calculate the number of decimal places that need to be padded with 0s
                                            var pad_total = decimal_places - decimal_part_length

                                            if (pad_total > 0) {

                                                // Pad the string with 0s
                                                for (var counter = 1; counter <= pad_total; counter++)
                                                    value_string += "0"
                                            }
                                            return value_string
                                        }

                                        //-->
                                    </script>

                                    <hr />

                                    <?php if ($ER_org_data['captcha'] == 'Y') { ?>
                                            <p>Enter the security code as it is shown (required):<script type="text/javascript">sjcap("altTextField");</script>
                                        <noscript><p>[This resource requires a Javascript enabled browser.]</p></noscript>
                                    <?php } ?>

                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>"/>
                                    <p align="center"  class="att" id="uyt">
                                        <input class="satt" type="submit" name="Submit" value="<?php echo $button_label; ?>"/> 
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>jQuery(function () {
        jQuery(document).tooltip({position: {my: 'left center', at: 'right+10 center', using: function (position, feedback) {
                    jQuery(this).css(position);
                    jQuery("<div>").addClass("arrow").addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
                }}});
    });</script> 