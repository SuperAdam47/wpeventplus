<script>
    var validationErrors = {
        invalid: "<?php echo __('Invalid', 'evrplus_language'); ?>",
        required: "<?php echo __('Required', 'evrplus_language'); ?>"
    };
</script>
<?php
$num_people = 0;
$item_order = array();

$passed_event_id = (int) $_POST['event_id'];
$event_id = 0;
if (is_numeric($passed_event_id) && $passed_event_id > 0 && (isset($_POST['eventplus_token']) && strlen($_POST['eventplus_token']) == 32)) {
    $event_id = $passed_event_id;
} else {
    _e('Failure - please retry!', 'evrplus_language');
    return;
}

$eventplus_token = $_POST['eventplus_token'];

$isPending = EventPlus_Helpers_Token::isPending($eventplus_token);

if( $isPending === false ) {
    _e("Couldn't proceed! registration already processed.", 'evrplus_language');
    return;
}

//Begin gather registrtion data for database input
if (isset($_POST['fname'])) {
    $fname = $_POST['fname'];
}
if (isset($_POST['lname'])) {
    $lname = $_POST['lname'];
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
}

if (isset($_POST['address'])) {
    $address = $_POST['address'];
} else {
    $address = '';
}

if (isset($_POST['city'])) {
    $city = $_POST['city'];
} else {
    $city = '';
}

if (isset($_POST['state'])) {
    $state = $_POST['state'];
} else {
    $state = '';
}
if (isset($_POST['zip'])) {
    $zip = $_POST['zip'];
} else {
    $zip = '';
}
if (isset($_POST['phone'])) {
    $phone = $_POST['phone'];
} else {
    $phone = '';
}

if (isset($_POST['fees'])) {
    $fees = $_POST['fees'];
} else {
    $fees = '';
}
if (isset($_POST['tax'])) {
    $tax = $_POST['tax'];
} else {
    $tax = '';
}
if (isset($_POST['total'])) {
    $payment = $_POST['total'];
} else {
    $payment = '';
}
if (isset($_POST['coupon'])) {
    $coupon = $_POST['coupon'];
} else {
    $coupon = '';
}
if (isset($_POST['reg_type'])) {
    $reg_type = $_POST['reg_type'];
} else {
    $reg_type = '';
}
if (isset($_POST['company'])) {
    $company = $_POST['company'];
} else {
    $company = '';
}
if (isset($_POST['co_address'])) {
    $coadd = $_POST['co_address'];
} else {
    $coadd = '';
}
if (isset($_POST['co_city'])) {
    $cocity = $_POST['co_city'];
} else {
    $cocity = '';
}
if (isset($_POST['co_state'])) {
    $costate = $_POST['co_state'];
} else {
    $costate = '';
}
if (isset($_POST['co_zip'])) {
    $cozip = $_POST['co_zip'];
} else {
    $cozip = '';
}
if (isset($_POST['co_phone'])) {
    $cophone = $_POST['co_phone'];
} else {
    $cophone = '';
}

$attendee_name = $fname . " " . $lname;

$sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id . " ORDER BY sequence ASC";
$result = $wpdb->get_results($sql, ARRAY_A);
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

    $item_post = str_replace(".", "_", $row['item_price']);
    $item_qty = $_REQUEST['PROD_' . $event_id . '-' . $item_id . '_' . $item_post];
    if ($item_cat == "REG") {
        $num_people = $num_people + $item_qty;
    }

    $item_info = array('ItemID' => $item_id, 'ItemEventID' => $event_id, 'ItemCat' => $item_cat,
        'ItemName' => $item_title, 'ItemCost' => $item_price, 'ItemCurrency' =>
        $item_custom_cur, 'ItemFree' => $free_item, 'ItemStart' => $item_start_date,
        'ItemEnd' => $item_end_date, 'ItemQty' => $item_qty);
    array_push($item_order, $item_info);
}

if ($reg_type == "WAIT") {
    $quantity = 1;
} else {
    $quantity = $num_people;
}

$ticket_data = serialize($item_order);

$qanda = array();
$questions = $wpdb->get_results("SELECT * from " . get_option('evr_question') . " where event_id = '" . (int) $event_id . "'");
if ($questions) {
    foreach ($questions as $question) {
        switch ($question->question_type) {
            case "TEXT":
            case "TEXTAREA":
            case "DROPDOWN":
                $post_val = $_POST[$question->question_type . '_' . $question->id];

                $custom_response = array('email' => $email, 'question' => $question->id, 'response' => $post_val);
                array_push($qanda, $custom_response);
                break;
            case "SINGLE":
                $post_val = $_POST[$question->question_type . '_' . $question->id];
                $custom_response = array('email' => $email, 'question' => $question->id, 'response' => $post_val);
                array_push($qanda, $custom_response);
                break;
            case "MULTIPLE":
                $value_string = '';
                for ($i = 0; $i < count($_POST[$question->question_type . '_' . $question->id]); $i++) {
                    $value_string .= $_POST[$question->question_type . '_' . $question->id][$i] . ",";
                }
                $custom_response = array('email' => $email, 'question' => $question->id, 'response' => $value_string);
                array_push($qanda, $custom_response);
                break;
        }
    }
}

$sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int) $event_id;
$result = $wpdb->get_results($sql, ARRAY_A);

foreach ($result as $row) {
    $event_id = $row['id'];
    $event_name = stripslashes($row['event_name']);
    $event_location = $row['event_location'];
    $event_address = $row['event_address'];
    $event_city = $row['event_city'];
    $event_postal = $row['event_postal'];
    $reg_limit = $row['reg_limit'];
    $start_time = $row['start_time'];
    $end_time = $row['end_time'];
    $start_date = $row['start_date'];
    $end_date = $row['end_date'];
    $use_coupon = $row['use_coupon'];
    $coupon_code = $row['coupon_code'];
    $coupon_code_price = $row['coupon_code_price'];
}

// GT Validate coupon code and deduct discount	
if ($use_coupon == "Y") {
    if ($coupon == $coupon_code) {
        $payment = ($payment + $coupon_code_price);
    }
}

$posted_data = array('lname' => $lname, 'fname' => $fname, 'address' => $address, 'city' => $city,
    'state' => $state, 'zip' => $zip, 'reg_type' => $reg_type, 'email' => $email,
    'phone' => $phone, 'email' => $email, 'coupon' => $coupon, 'event_id' => $event_id,
    'company' => $company, 'co_add' => $coadd, 'co_city' => $cocity, 'co_state' => $costate, 'co_zip' => $cozip,
    'num_people' => $quantity, 'tickets' => $ticket_data,
    'payment' => $payment,
    'order_total' => $payment,
    'fees' => $fees, 'tax' => $tax);

#Begin display of confirmation form
echo '<script type="text/javascript" src="' . $this->assetUrl('front/funx.js?v=' . time()) . '"></script>';


$eventNameCostStr = $event_name . ' - ' . $item_order[0]['ItemCurrency'] . '&nbsp;' . $payment;
if (intval($payment) == 0) {
    $eventNameCostStr = $event_name . ' - Free';
}


#Registration Type
$row_count = 0;
if ($reg_type != "WAIT") {
    $row_count = count($item_order);
}

$total = $payment;
if (intval($total) > 0) {
    $oEventDiscounts = new EventPlus_Models_Events_Discounts();
    $discountSettings = $oEventDiscounts->getSettings($event_id);

    $discountPercentage = 0;
    $posted_data['discount_percentage'] = $discountPercentage;
	$posted_data['discount'] = round(($total * $discountPercentage) / 100, 2);
	$total = $total - $posted_data['discount'];
	$posted_data['payment'] = $total;
	if (count($discountSettings) > 0 && is_array($discountSettings)) {
        $discountPercentage = EventPlus_Helpers_Event::eventplus_getDiscountPercentage($quantity, $discountSettings);

        if ($discountPercentage > 0) {
            $posted_data['discount_percentage'] = $discountPercentage;
            $posted_data['discount'] = round(($total * $discountPercentage) / 100, 2);
            $total = $total - $posted_data['discount'];
            $posted_data['payment'] = $total;
        }
    }
}

if ($reg_type == "WAIT") {
    $type = __('You are on the waiting list.', 'evrplus_language');
}
if ($reg_type == "RGLR") {
    $type = sprintf( esc_html__('You are registering for %s person(s) Please provide the first and last name of each person:', 'evrplus_language'), $quantity );
}

if(has_filter('eventplus_registration_type_message')) {
    $type = apply_filters('eventplus_registration_type_message', $reg_type);
}

$form_post = urlencode(serialize($posted_data));
$question_post = urlencode(serialize($qanda));
?>
<div class="events-plus-2">
    <table width="100%" cellpadding="0" cellspacing="0" class="data-summary">
        <thead>
            <tr>
                <th colspan="3"><i class="fa fa-pencil"></i> <?php echo __('Please verify your registration details:', 'evrplus_language'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="35%"><i class="fa fa-calculator"></i> <?php echo _e('Event Name/Cost:', 'evrplus_language'); ?></td>
                <td width="50%"><?php echo $eventNameCostStr; ?></td>
                <td width="15%" align="right"></td>
            </tr>
            <tr>
                <td><i class="fa fa-user"></i> <?php echo _e('Registering Name:', 'evrplus_language'); ?></td>
                <td><?php echo $attendee_name; ?></td>
                <td align="right"></td>
            </tr>
            <tr>
                <td><i class="fa fa-envelope"></i> <?php echo __('Email Address:', 'evrplus_language'); ?></td>
                <td><?php echo $email; ?></td>
                <td align="right"></td>
            </tr>
            <tr>
                <td><i class="fa fa-users"></i> <?php echo __('Number of Attendees:', 'evrplus_language'); ?></td>
                <td><?php echo $quantity; ?></td>
                <td align="right"></td>
            </tr>
            <tr>
                <td><i class="fa fa-pencil"></i> <?php echo __('Order Details:', 'evrplus_language'); ?></td>
                <td><?php if ($reg_type == "WAIT"): ?><?php echo __('Waiting List', 'evrplus_language'); ?><?php endif; ?></td>
                <td align="right"></td>
            </tr>

            <?php
            if ($row_count):
                for ($row = 0; $row < $row_count; $row++):
                    if ($item_order[$row]['ItemQty'] >= 1) {
                        $strItemD = $item_order[$row]['ItemQty'] . " " . $item_order[$row]['ItemCat'] . "-" . $item_order[$row]['ItemName'] . " " . $item_order[$row]['ItemCurrency'] . '  ' . $item_order[$row]['ItemCost'] . "<br \>";
                        if ($item_order[$row]['ItemCost'] <= 0) {
                            $strItemD = $item_order[$row]['ItemQty'] . " " . $item_order[$row]['ItemCat'] . "-" . $item_order[$row]['ItemName'] . " - Free " . "<br \>";
                        }
                    }
                    ?>
                    <?php if ($item_order[$row]['ItemQty'] >= 1): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td><?php echo $item_order[$row]['ItemQty'] . " " . $item_order[$row]['ItemCat'] . "-" . $item_order[$row]['ItemName']; ?></td>
                            <td align="right">
                                <?php
                                if ($item_order[$row]['ItemCost'] > 0) {
                                    echo $item_order[$row]['ItemCurrency'] . '  ' . $item_order[$row]['ItemCost'];
                                } else {
                                    echo __('Free', 'evrplus_language');
                                }
                                ?>

                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <?php if ($use_coupon == "Y"): ?>
                <tr>
                    <?php if ($coupon == $coupon_code): ?>
                        <td><?php echo __('Coupon:', 'evrplus_language'); ?></td>
                        <td><?php echo $coupon_code; ?></td>
                        <td align="right"><?php echo $coupon_code_price; ?></td>
                    <?php elseif ($coupon != $coupon_code && $coupon != ''): ?>
                        <td><?php echo __('Coupon:', 'evrplus_language'); ?></td>
                        <td><?php echo __('Invalid Code!', 'evrplus_language'); ?></td>
                        <td align="right">&nbsp;</td>
                    <?php endif; ?>
                </tr>
            <?php endif;
            ?>
            <?php if ($company_options['use_sales_tax'] == "Y"): ?>
                <tr>
                    <td></td>
                    <td><?php _e('Sales Tax:', 'evrplus_language'); ?></td>
                    <td align="right"><?php echo $tax; ?></td>
                </tr>
            <?php endif; ?>


            <?php if ($posted_data['discount'] > 0): ?>
                <tr>
                    <td></td>
                    <td><?php echo __('Order Total:', 'evrplus_language'); ?></td>
                    <td align="right"><?php echo $item_order[0]['ItemCurrency'] . '<strong>  (' . number_format(floatval($payment), 2) . ')</strong>'; ?></td>
                </tr> 
                <tr>
                    <td></td>
                    <td><?php echo __('Discount:', 'evrplus_language') . ' (' . intval($posted_data['discount_percentage']) . '%)'; ?></td>
                    <td align="right"><?php echo $item_order[0]['ItemCurrency'] . '<strong>  (' . number_format(floatval($posted_data['discount']), 2) . ')</strong>'; ?></td>
                </tr> 
            <?php endif; ?>
            <tr>
                <td></td>
                <td><?php echo __('Total Cost:', 'evrplus_language'); ?></td>
                <td align="right"><?php echo $item_order[0]['ItemCurrency'] . '<strong>  ' . number_format(floatval($total), 2) . '</strong>'; ?></td>
            </tr>
        </tfoot>
    </table>

    <?php
    if( $type != "" ): ?>
        <div class="row"><div class="col-xs-12">
            <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i> <?php echo $type; ?></div>
        </div></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xs-12 regis8er-form" id="regis8er-form">

            <form id="eventplus_attendee_form_confirm" method="post" action="<?php echo evrplus_permalink($company_options['evrplus_page_id']); ?>" onSubmit="myConfirmSubmit.disabled = true;
                    return eventplus_validateConfirmationForm(this)">
                <div class="row">
                    <?php if ($quantity > 0): ?>
                        <?php
                        for ($person = 0; $person < $quantity; $person++):
                            $first_fname = '';
                            $first_lname = '';
                            if ($person == 0) {
                                $first_fname = $fname;
                                $first_lname = $lname;
                            }
                            ?>
                            <div class="col-xs-12 fi3ld">
                                <h3 class="section-sub-ti8le"><i class="fa fa-user"></i> <?php echo __('Attendee', 'evrplus_language'); ?> # <?php echo $person + 1; ?></h3>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-9 fi3ld">
                                <input class="eplus-required" value="<?php echo $first_fname; ?>" type="text" name="attendee[<?php echo $person; ?>][first_name]" placeholder="<?php echo __('First Name', 'evrplus_language'); ?>">
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-4 col-sm-6 col-xs-9 fi3ld">
                                <input class="eplus-required" value="<?php echo $first_lname; ?>" type="text" name="attendee[<?php echo $person; ?>][last_name]" placeholder="<?php echo __('Last Name', 'evrplus_language'); ?>">
                            </div>
                            <div class="clearfix"></div>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <div class="clearfix"></div>
                    <div class="col-xs-12 fi3ld-buttons">

                        <div class="col-xs-12" id="action_message_eplus_container" style="display:none;">
                            <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i>
                                <span id="form_action_message_eplus"></span>
                            </div>
                        </div>

                        <input type="reset" name="back" id="back" value="<?php echo __('BACK', 'evrplus_language'); ?>" onclick="history.go(-1);
                                return false;">

                        <?php
                        $count = (int) $quantity;
                        if ($count <= 0) {
                            echo '<div class="col-xs-12">
                                    <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i> 
                                    ' . __('You must select at least one registration item.', 'evrplus_language') . '
                                    ' . __('Please go back and select an item!', 'evrplus_language') . '
                                    </div>
                            </div>';
                        } else {

                            $oMeta = new EventPlus_Models_Events_Meta();
                            $skip_step_2 = $oMeta->getOption($event_id, 'skip_step_2');
                            if ($skip_step_2 == 'Y' || $skip_step_2 == '') {
                                echo '<input type="hidden" id="qty_attendees" value="' . $quantity . '" />';
                            }

                            echo '<input type="hidden" name="reg_form" value="' . $form_post . '" />';
                            echo '<input type="hidden" name="questions" value="' . $question_post . '" />';
                            echo '<input type="hidden" name="action" value="post"/>';
                            echo '<input type="hidden" name="eventplus_token" value="' . $eventplus_token . '" />';
                            echo '<input type="hidden" name="event_id" value="' . $event_id . '" />';

                            echo '<input type="submit" name="myConfirmSubmit" id="myConfirmSubmit" value="' . __('Confirmed', 'evrplus_language') . '" />';
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
