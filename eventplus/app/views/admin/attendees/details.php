<?php
$event_name = $oEvent->event_name;
$event_id = $oEvent->id;
$lname = $row['lname'];
$fname = $row['fname'];
$address = $row['address'];
$company = $row['company'];
$city = $row['city'];
$state = $row['state'];
$zip = $row['zip'];
$email = $row['email'];
$phone = $row['phone'];
$quantity = $row['quantity'];
$date = $row['date'];
$reg_type = $row['reg_type'];
$ticket_order = unserialize($row['tickets']);
$payment = $row['payment'];
$order_total = $row['order_total'];
$discount_percentage = $row['discount_percentage'];
$discount_amount = $row['discount_amount'];
$event_id = $row['event_id'];
$coupon = $row['coupon'];
$attendees = unserialize($row['attendees']);
if(empty($ER_org_data)){
	$ER_org_data['captcha'] = "";
}
$reg_form_defaults = unserialize($oEvent->reg_form_defaults);
$inc_phone = "";
$inc_zip = "";
$inc_state = "";
$inc_city = "";
$inc_address = "";
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
if(!empty($oEvent->event_category)){
	$event_category = unserialize($oEvent->event_category);
}else{
	$event_category = "";
}
$reg_limit = $oEvent->reg_limit;
$event_name = stripslashes($oEvent->event_name);
$use_coupon = $oEvent->use_coupon;

$questions = $this->wpDb()->get_results("SELECT * from " . get_option('evr_question') . " where event_id = '" . (int) $event_id . "' order by sequence");
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


                                <div class="padding">        

                                    <ul class="ssa">
                                        <li><div class="pass1"><label for="fname"><b><?php _e('First Name', 'evrplus_language'); ?>: </b></label> <?php echo $fname; ?></div></li>
                                        <li><div class="pass1"><label for="lname"><b><?php _e('Last Name', 'evrplus_language'); ?>: </b></label> <?php echo $lname; ?></div></li>
                                        <li><div class="pass1"><label for="email" ><b><?php _e('Email Address', 'evrplus_language'); ?>: </b></label> <?php echo $email; ?></div></li>
                                        <?php if ($company != "") { ?>
                                            <li><div class="pass1"><label for="phone" ><b><?php _e('Company', 'evrplus_language'); ?>: </b></label> 
                                                <?php echo $company; ?></div></li>
                                        <?php } ?>
                                        <?php if ($inc_phone == "Y") { ?>
                                            <li><div class="pass1"><label for="phone" ><b><?php _e('Phone Number', 'evrplus_language'); ?>: </b></label> <?php echo $phone; ?></div></li>
                                        <?php } ?>
                                        <?php if ($inc_address == "Y") { ?> 
                                            <li><div class="pass1"><label for="address"><b><?php _e('Address', 'evrplus_language'); ?>: </b></label> <?php echo $address; ?></div></li>
                                        <?php } ?>
                                        <?php if ($inc_city == "Y") { ?> 
                                            <li><div class="pass1"><label for="city"><b><?php _e('City', 'evrplus_language'); ?>: </b></label> <?php echo $city; ?></div></li>
                                        <?php } ?>
                                        <?php if ($inc_state == "Y") { ?> 
                                            <li><div class="pass1"><label for="state"><b><?php _e('State/Province', 'evrplus_language'); ?>: </b></label> <?php echo $state; ?></div></li>
                                        <?php } ?> 
                                        <?php if ($inc_zip == "Y") { ?> 
                                            <li><div class="pass1"><label for="zip"><b><?php _e('Zip/Postal Code', 'evrplus_language'); ?>: </b></label> <?php echo $zip; ?></div></li>
                                        <?php } ?>       
                                        <?php if ($use_coupon == "Y") { ?>
                                            <li><div class="pass1"><label for="coupon"><b><?php _e('Coupon code', 'evrplus_language'); ?>: </b></label> <?php echo (trim($coupon) != '') ? $coupon : 'N/A'; ?></div></li>
                                        <?php } ?>
                                        <?php
                                        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id = $event_id";
                                        $rows = $this->wpDb()->get_results($sql, ARRAY_A);
                                        ?>
                                        <li><div class="pass1"><label for="startdate"><b><?php _e('Start Date', 'evrplus_language'); ?>: </b></label> <?php echo date_i18n(get_option('date_format'), strtotime($rows[0]['start_date'])); ?></div></li>
                                        <li><div class="pass1"><label for="enddate"><b><?php _e('End Date', 'evrplus_language'); ?>: </b></label> <?php echo date_i18n(get_option('date_format'), strtotime($rows[0]['end_date'])); ?></div></li>
                                        <li><div class="pass1"><label for="regdate"><b><?php _e('Registration Date', 'evrplus_language'); ?>: </b></label> <?php echo date_i18n(get_option('date_format'), strtotime($date)); ?></div></li></ul>
                                    <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl('images/check-icon.png'); ?>"><?php _e('Cutom Fields', 'evrplus_language'); ?></span><br /><ul class="ssa"><br/>
                                        <?php
                                        if ($questions) {
                                            for ($i = 0; $i < count($questions); $i ++) {
                                                echo '<li><div class="pass1"><label><b>' . $questions [$i]->question . ": </b></label>";
                                                $question_id = $questions [$i]->id;
                                                $query = "SELECT * FROM " . get_option('evr_answer') . " WHERE registration_id =$attendee_id AND question_id =$question_id";
                                                $result_a = $this->wpDb()->get_results($query, ARRAY_A);
                                                foreach ($result_a as $row) {
                                                    echo $answers = $row ['answer'];
                                                }
                                                echo "</li>";
                                            }
                                        } else {
                                            _e('No records found', 'evrplus_language');
                                        }
                                        ?>
                                    </ul>

                                    <?php if ($quantity > 0 && count($attendees) > 0 && is_array($attendees)): ?>
                                        <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl('images/check-icon.png'); ?>"><?php _e('Attendees', 'evrplus_language'); ?></span><br /><ul class="ssa"><br/>
                                            <?php
                                            $i = 0;
                                            do {
                                                $person = $i + 1;
                                                echo '<li><div class="pass1"><b>' . __('Attendee', 'evrplus_language') . ' #' . $person . ' ' . __('First Name', 'evrplus_language') . ': </b>' . $attendees[$i]["first_name"] . '</div></li>';
                                                echo '<li><div class="pass1"><b>' . __('Last Name', 'evrplus_language') . ': </b>' . $attendees[$i]["last_name"] . '</div></li>';

                                                ++$i;
                                            } while ($i < $quantity);
                                            ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php
                                    $num = 0;
                                    $sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE payment_status = 'success' AND event_id='$event_id'";

                                    $attendee_count = $this->wpDb()->get_var($sql2);

                                    If ($attendee_count >= 1) {
                                        $num = $attendee_count;
                                    }
                                    $available = $reg_limit - $num;
                                    ?>                

                                    <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl('images/dollar-icon.png'); ?>"><?php _e('Registration Fees', 'evrplus_language'); ?></span><br /><ul class="ssa"><br />
                                        <li class="aw"><div class="pass1"><label for="reg_type"><b><?php _e('What type of Registration?', 'evrplus_language'); ?>: </b></label> <?php echo $reg_type; ?></div></li>

                                        <?php
                                        $open_seats = $available;
                                        $curdate = date("Y-m-d");

                                        $row_count = count($ticket_order);
                                        if ($ticket_order != "") {
                                            for ($row = 0; $row < $row_count; $row++) {
                                                ?>

                                                <li><div class="pass1"><b><?php echo $ticket_order[$row]['ItemName'] . "    " . $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']; ?>: </b><?php echo $ticket_order[$row]['ItemQty']; ?></div></li>
                                                <?php
                                            }
                                        }
                                        ?>

                                        <?php if ($discount_amount > 0) : ?>
                                            <li><div class="pass1"><b><?php _e('Total', 'evrplus_language'); ?>: </b><?php
                                                    echo $ticket_order[0]['ItemCurrency'] . ' ' . $order_total;
                                                    ?></div><li>
                                            <li><div class="pass1"><b><?php _e('Discount', 'evrplus_language'); ?> (<?php echo intval($discount_percentage) ?>%) : </b><?php
                                                    echo $ticket_order[0]['ItemCurrency'] . ' ' . $discount_amount;
                                                    ?></div><li>
                                            <?php endif; ?>

                                        <li><div class="pass1"><b><?php _e('Registration TOTAL', 'evrplus_language'); ?> : </b><?php
                                                if ($payment > 0) {
                                                    echo $ticket_order[0]['ItemCurrency'] . ' ' . number_format($payment, 2);
                                                } else {
                                                    echo '0.0';
                                                }
                                                ?></div><li>
                                    </ul>
                                    <br />
                                    <?php
                                    if ($open_seats <= "1") {
                                        echo '<hr><br><b><font color="red">';
                                        _e('This event has reached registration capacity.', 'evrplus_language');
                                        echo "<br />";
                                    }
                                    ?>

                                    <script language="JavaScript" type="text/javascript">


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


                                    </script>

                                    <hr />

                                    <?php if ($ER_org_data['captcha'] == 'Y') { ?>
                                        <p>Enter the security code as it is shown (required):<script type="text/javascript">sjcap("altTextField");</script>
                                        <noscript><p>[This resource requires a Javascript enabled browser.]</p></noscript>
                                    <?php } ?>



                                </div>

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