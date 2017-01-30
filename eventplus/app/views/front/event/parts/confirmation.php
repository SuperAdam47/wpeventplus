<?php
$event_id = $row['id'];
$reg_form_defaults = unserialize($row['reg_form_defaults']);
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


$use_coupon = $row['use_coupon'];
$reg_limit = $row['reg_limit'];
$event_name = htmlspecialchars_decode(html_entity_decode(stripslashes($row['event_name'])));
$mail_subject = evrplus_htmlchanger($row['event_name']);
$invoice_event = $row['event_name'];
$event_identifier = stripslashes($row['event_identifier']);
$display_desc = $row['display_desc'];  // Y or N
$event_desc = html_entity_decode(stripslashes($row['event_desc']));
$event_category = unserialize($row['category_id']);
$event_location = $row['event_location'];
$event_address = $row['event_address'];
$event_city = $row['event_city'];
$event_state = $row['event_state'];
$event_postal = $row['event_postal'];
$google_map = $row['google_map'];  // Y or N
$start_month = $row['start_month'];
$start_day = $row['start_day'];
$start_year = $row['start_year'];
$end_month = $row['end_month'];
$end_day = $row['end_day'];
$end_year = $row['end_year'];
$start_time = $row['start_time'];
$end_time = $row['end_time'];
$allow_checks = $row['allow_checks'];
$counter_checks = $row['counter_checks'];
$outside_reg = $row['outside_reg'];  // Yor N
$external_site = $row['external_site'];
$more_info = $row['more_info'];
$image_link = $row['image_link'];
$header_image = $row['header_image'];
$is_active = $row['is_active'];
$send_mail = $row['send_mail'];  // Y or N
$conf_mail = stripslashes($row['conf_mail']);
$start_date = $row['start_date'];
$end_date = $row['end_date'];

//added 6.00.13
$send_coord = $row['send_coord'];
$coord_email = $row['coord_email'];
$coord_msg = stripcslashes($row['coord_msg']);
$coord_pay_msg = stripslashes($row['coord_pay_msg']);

if (trim($reg_limit) == "" || $reg_limit === 0) {
    $reg_limit = "Unlimited";
}

$available_spaces = $reg_limit;

$attendee_array = unserialize($reg_form['attendees']);
$ticket_array = unserialize($reg_form['tickets']);
$business = '';

$invoice_data = array('reg_id' => $reg_id, 'lname' => $reg_form['lname'], 'fname' => $reg_form['fname'], 'address' => $reg_form['address'],
    'city' => $reg_form['city'], 'state' => $reg_form['state'], 'zip' => $reg_form['zip'], 'reg_type' => $reg_form['reg_type'],
    'company' => $reg_form['company'], 'co_address' => $reg_form['co_address'], 'co_city' => $reg_form['co_city'], 'co_state' => $reg_form['co_state'],
    'co_zip' => $reg_form['co_zip'], 'email' => $reg_form['email'], 'phone' => $reg_form['phone'], 'coupon' => $reg_form['coupon'], 'event_id' => $reg_form['event_id'],
    'event_name' => $invoice_event, 'quantity' => $reg_form['quantity'], 'tickets' => $reg_form['tickets'],
    'payment' => $reg_form['payment'], 'tax' => $reg_form['tax'], 'attendees' => $attendee_array, 'business' => $business);

$invoice_post = urlencode(serialize($invoice_data));
?>

<div class="events-plus-2">
    <?php
    if (isset($_GET['event_emr'])) {
        if ($_GET['event_emr'] == md5(1)) {
            ?>
            <div class="info-m3ssages gr33n"><?php echo __("A confirmation email has been sent to:", 'evrplus_language') . ' ' . $reg_form['email']; ?></div>
            <?php
        }
    }
    ?>

    <?php if ($reg_form['reg_type'] == "WAIT") {
        ?>
        <div class="info-m3ssages"><?php _e("At this time, all seats for the event have been taken.  Your information has been placed on our waiting list.  The waiting list is on a first come, first serve basis.  You will be notified by email should a seat become available.", 'evrplus_language'); ?></div>
        <?php
    }
    ?>

    <?php if ($reg_form['payment'] > 0): ?>
        <div class="info-m3ssages">
            <?php
            if ($reg_form['payment_status'] == '' || $reg_form['payment_status'] == null) {
                if (isset($company_options['info_recieved']) && ($company_options['info_recieved'] != '')) {
                    echo $company_options['info_recieved'];
                } else {
                    _e("Your information has been received.", 'evrplus_language');
                }
            } elseif ($reg_form['payment_status'] == EventPlus_Models_Payments::PAYMENT_SUCCESS) {
                _e("Congratulations! Transaction has been completed and payment has been received. Thank you for your payment.", 'evrplus_language');
            } elseif ($reg_form['payment_status'] == EventPlus_Models_Payments::PAYMENT_FAILED) {
                _e("Transaction has been completed but payment failed. Please try registering again.", 'evrplus_language');
            }
            ?>
        </div>
        <?php
        $oHelperPayment = new EventPlus_Helpers_Payment($event_id, $reg_id);
        $oHelperPayment->evrplus_registration_payment($event_id, $reg_id);
        ?>
    <?php else: ?>
        <?php
        $oHelperPayment = new EventPlus_Helpers_Payment($event_id, $reg_id);
        $oHelperPayment->get_details();
        ?>
    <?php endif; ?>
</div>