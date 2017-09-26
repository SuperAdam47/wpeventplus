<?php

class eplus_front_event_parts_paypal_controller extends EventPlus_Abstract_Controller {

    function index() {
        
    }

    function action_ipn() {

        global $wpdb;
        
        $today = date("m-d-Y");
        $company_options = EventPlus_Models_Settings::getSettings();
        $events_attendee_tbl = get_option('evr_attendee');

        $id = $_REQUEST['id']; //This is the id of the registrant
        if ($id == "") {
            $this->setResponse("ID not supplied.");
        } else if ($company_options['use_sandbox'] == 'Y') {
            $this->sandboxIpn($id = $_REQUEST['id']);
        } else {
            $p = new EventPlus_Vendor_Paypal(); // initiate an instance of the class
            $p->validate_ipn();
            //store the results in reusable variables
            $payer_id = $p->ipn_data['payer_id'];
            $payment_date = $p->ipn_data['payment_date'];
            $txn_id = $p->ipn_data['txn_id'];
            $first_name = $p->ipn_data['first_name'];
            $last_name = $p->ipn_data['last_name'];
            $payer_email = $p->ipn_data['payer_email'];
            $payer_status = $p->ipn_data['payer_status'];
            $payment_type = $p->ipn_data['payment_type'];
            $memo = $p->ipn_data['memo'];
            $item_name = $p->ipn_data['item_name'];
            $item_number = $p->ipn_data['item_number'];
            $quantity = $p->ipn_data['quantity'];
            if (isset($_REQUEST['mc_gross'])) {
                $amount_pd = $_REQUEST['mc_gross'];
            } else {
                $amount_pd = $_REQUEST['payment_gross'];
            }
            
            $amount_pd = esc_sql($amount_pd);
            $mc_currency = $p->ipn_data['mc_currency'];
            $address_name = $p->ipn_data['address_name'];
            $address_street = nl2br($p->ipn_data['address_street']);
            $address_city = $p->ipn_data['address_city'];
            $address_state = $p->ipn_data['address_state'];
            $address_zip = $p->ipn_data['address_zip'];
            $address_country = $p->ipn_data['address_country'];
            $address_status = $p->ipn_data['address_status'];
            $payer_business_name = $p->ipn_data['payer_business_name'];
            $payment_status = $p->ipn_data['payment_status'];
            $pending_reason = $p->ipn_data['pending_reason'];
            $reason_code = $p->ipn_data['reason_code'];
            $txn_type = $p->ipn_data['txn_type'];

            $events_attendee_tbl = get_option('evr_attendee');

            $today = date("m-d-Y");
            //$sql="UPDATE ". $events_attendee_tbl . " SET payment_status = '$payment_status', amount_pd = '$amount_pd', payment_date ='$payment_date' WHERE id = '$id'";
            $wpdb->query($wpdb->prepare("UPDATE " . $events_attendee_tbl . " SET payment_status = '$payment_status', amount_pd = '$amount_pd', payment_date ='$payment_date' WHERE id = %d", $id));
            (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = "0";
            $events_detail_tbl = get_option('evr_event');
            $query = "SELECT * FROM " . $events_detail_tbl . " WHERE id='$event_id'";
            //$result = mysql_query($query) or die('Error : ' . mysql_error());
            //while ($row = mysql_fetch_assoc ($result)){
            $result = $wpdb->get_results($query, ARRAY_A) or die('Error : ' . mysqli_error());
            foreach ($result as $row) {
                $event_id = $row['id'];
                $event_name = stripslashes($row['event_name']);
                $event_location = stripslashes($row['event_location']);
                $event_address = $row['event_address'];
                $event_city = $row['event_city'];
                $event_postal = $row['event_postal'];
                $reg_limit = $row['reg_limit'];
                $start_time = $row['start_time'];
                $end_time = $row['end_time'];
                $start_date = $row['start_date'];
                $end_date = $row['end_date'];
                //added 6.00.13
                $send_coord = $row['send_coord'];
                $coord_email = $row['coord_email'];
                $coord_msg = stripcslashes($row['coord_msg']);
                $coord_pay_msg = stripslashes($row['coord_pay_msg']);
            }

            $events_paypal_transactions_tbl = get_option('evr_payment');

            //Store transaction details in the database
            $sql = array('payer_id' => $id, 'event_id' => $event_id, 'payment_date' => $payment_date, 'txn_id' => $txn_id, 'first_name' => $first_name,
                'last_name' => $last_name, 'payer_email' => $payer_email, 'payer_status' => $payer_status, 'payment_type' => $payment_type, 'memo' => $memo,
                'item_name' => $item_name, 'item_number' => $item_number, 'quantity' => $quantity,
                'mc_gross' => $amount_pd, 'mc_currency' => $mc_currency, 'address_name' => $address_name, 'address_street' => $address_street,
                'address_city' => $address_city, 'address_state' => $address_state,
                'address_zip' => $address_zip, 'address_country' => $address_country, 'address_status' => $address_status,
                'payer_business_name' => $payer_business_name, 'payment_status' => $payment_status,
                'pending_reason' => $pending_reason, 'reason_code' => $reason_code, 'txn_type' => $txn_type);
            $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

            $query = "SELECT * FROM " . $events_attendee_tbl . " WHERE id ='" . (int)$id . "'";
            $result = $wpdb->get_results($query, ARRAY_A) or die('Error : ' . mysqli_error());

            foreach ($result as $row) {
                $attendee_email = $row['email'];
                $f_name = $row['fname'];
                $l_name = $row['lname'];
            }

            if ($wpdb->insert(get_option('evr_payment'), $sql, $sql_data)) {
                $headers = "From: " . $company_options['company'] . " <" . $company_options['company_email'] . ">\r\n";
                $headers .= "Content-Type: text/html\r\n";
                $headers .= "Reply-To: " . $company_options['company'] . "  <" . $company_options['company_email'] . ">\r\n";
                $details = "";
                foreach ($p->ipn_data as $key => $value) {
                    $details .= "\n$key: $value";
                }
                $paydate = date('m/d/Y');
                $paytime = date('g:i A');
                $SearchValues = array("[payer_email]", "[fname]", "[lname]", "[attendee_email]", "[event_name]", "[event_id]", "[details]", "[pay_date]", "[pay_time]");
                $ReplaceValues = array($p->ipn_data['payer_email'], $f_name, $l_name, $attendee_email, $event_name, $event_id, $details, $paydate, $paytime);

                if ($send_coord == "Y") {
                    $subject = 'Instant Payment Notification - Success';
                    $email_content = str_replace($SearchValues, $ReplaceValues, $coord_pay_msg);
                    wp_mail($coord_email, html_entity_decode($subject), html_entity_decode($email_content), $headers);
                } else {

                    $subject = 'Instant Payment Notification - Success';
                    $body = "An instant payment notification was successfully posted\n";
                    $body .= "from " . $p->ipn_data['payer_email'] . " on behalf of " . $f_name . " " . $l_name;
                    $body .= " for event " . $event_name . "(" . $event_id . ")" . " on " . date('m/d/Y');
                    $body .= " at " . date('g:i A') . "\n\nDetails:\n";
                    foreach ($p->ipn_data as $key => $value) {
                        $body .= "\n$key: $value";
                    }
                    wp_mail($contact, $subject, $body, $headers);
                }
            } else {
                if ($send_coord == "Y") {
                    $subject = 'Instant Payment Notification - Failure';
                    $body = "An instant payment notification was received but not posted!\n";
                    $body .= "from " . $p->ipn_data['payer_email'] . " on behalf of " . $f_name . " " . $l_name;
                    $body .= " for event " . $event_name . "(" . $event_id . ")" . " on " . date('m/d/Y');
                    $body .= " at " . date('g:i A') . "\n\nDetails:\n";
                    foreach ($p->ipn_data as $key => $value) {
                        $body .= "\n$key: $value";
                    }
                    wp_mail($coord_email, $subject, $body, $headers);
                } else {
                    
                    if ($send_coord == "Y") {
                        $contact = $coord_email;
                    } else {
                        $contact = $company_options['company_email'];
                    }

                    $subject = 'Instant Payment Notification - Failure';
                    $body = "An instant payment notification was received but not posted!\n";
                    $body .= "from " . $p->ipn_data['payer_email'] . " on behalf of " . $f_name . " " . $l_name;
                    $body .= " for event " . $event_name . "(" . $event_id . ")" . " on " . date('m/d/Y');
                    $body .= " at " . date('g:i A') . "\n\nDetails:\n";
                    foreach ($p->ipn_data as $key => $value) {
                        $body .= "\n$key: $value";
                    }
                    wp_mail($contact, $subject, $body, $headers);
                }
            }

            //$attendee_email   = "consultant@avdude.com";
            $email_subject = $company_options['payment_subj'];
            $payment_msg = stripslashes($company_options['payment_message']);
            $pay_confirm = $company_options['pay_confirm'];
            $Organization = $company_options['company'];
            $contact = $company_options['company_email'];

            $headers .= "From: " . $Organization . " <" . $contact . ">\r\n";
            $headers .= "Reply-To: " . $Organization . "  <" . $contact . ">\r\n";

            if ($send_coord == "Y") {
                $contact = $coord_email;
            } else {
                $contact = $company_options['company_email'];
            }

            $payment_link = evrplus_permalink($company_options['return_url']) . "id=" . $id . "&fname=" . $f_name;

            $SearchValues = array("[id]", "[fname]", "[lname]", "[contact]", "[payer_email]", "[event_name]",
                "[event_id]", "[location]", "[event_city]", "[amnt_pd]", "[txn_id]",
                "[payment_url]", "[start_date]", "[start_time]", "[end_date]", "[end_time]",
                "[email]");

            $ReplaceValues = array($id, $f_name, $l_name, $contact, $payer_email, $event_name,
                $event_id, $event_location, $event_city, $amount_pd, $txn_id,
                $payment_link, $start_date, $start_time, $end_date, $end_time,
                $attendee_email);

            $email_content = str_replace($SearchValues, $ReplaceValues, $payment_msg);
            if ($pay_confirm == 'Y') {
                wp_mail($attendee_email, html_entity_decode($email_subject), html_entity_decode($email_content), $headers);
            }
        }
    }

    protected function sandboxIpn($id) {
        $company_options = EventPlus_Models_Settings::getSettings();

        $email_subject = $company_options['payment_subj'];
        $email_body = $company_options['payment_message'];
        $default_mail = $company_options['send_confirm'];
        $Organization = $company_options['company'];
        $contact = $company_options['company_email'];

        $events_detail_tbl = get_option('evr_event');
        $id = $id;
        $p = new EventPlus_Vendor_Paypal(); // initiate an instance of the class
        $p->validate_ipn();
        
        //store the results in reusable variables
        $payer_id = $p->ipn_data['payer_id'];
        $payment_date = $p->ipn_data['payment_date'];
        $txn_id = $p->ipn_data['txn_id'];
        $first_name = $p->ipn_data['first_name'];
        $last_name = $p->ipn_data['last_name'];
        $payer_email = $p->ipn_data['payer_email'];
        $payer_status = $p->ipn_data['payer_status'];
        $payment_type = $p->ipn_data['payment_type'];
        $memo = $p->ipn_data['memo'];
        $item_name = $p->ipn_data['item_name'];
        $item_number = $p->ipn_data['item_number'];
        $quantity = $p->ipn_data['quantity'];
        if (isset($_REQUEST['mc_gross'])) {
            $amount_pd = $_REQUEST['mc_gross'];
        } else {
            $amount_pd = $_REQUEST['payment_gross'];
        }
        $mc_currency = $p->ipn_data['mc_currency'];
        $address_name = $p->ipn_data['address_name'];
        $address_street = nl2br($p->ipn_data['address_street']);
        $address_city = $p->ipn_data['address_city'];
        $address_state = $p->ipn_data['address_state'];
        $address_zip = $p->ipn_data['address_zip'];
        $address_country = $p->ipn_data['address_country'];
        $address_status = $p->ipn_data['address_status'];
        $payer_business_name = $p->ipn_data['payer_business_name'];
        $payment_status = $p->ipn_data['payment_status'];
        $pending_reason = $p->ipn_data['pending_reason'];
        $reason_code = $p->ipn_data['reason_code'];
        $txn_type = $p->ipn_data['txn_type'];

        //Debugging option
        $email_paypal_dump = true;
        if ($email_paypal_dump == true) {
            // For this, we'll just email ourselves ALL the data as plain text output.
            $subject = 'Instant Payment Notification - PayPal Variable Dump';
            $body = "An instant payment notification was successfully received\n";
            $body .= "from " . $p->ipn_data['payer_email'] . " on " . date('m/d/Y');
            $body .= " at " . date('g:i A') . "\n\nDetails:\n";
            foreach ($p->ipn_data as $key => $value) {
                $body .= "\n$key: $value";
            }
            wp_mail($contact, $subject, $body);
        }

        $events_attendee_tbl = get_option('evr_attendee');

        $today = date("m-d-Y");
        $wpdb->query($wpdb->prepare("UPDATE " . $events_attendee_tbl . " SET payment_status = '$payment_status', amount_pd = '$amount_pd', payment_date ='$payment_date' WHERE id =%d", $id));


        $event_id = $_REQUEST['event_id'];
      
        $query = "SELECT * FROM " . $events_detail_tbl . " WHERE id='".(int)$event_id."'";
        $result = $wpdb->get_results($query, ARRAY_A) or die('Error : ' . mysqli_error());

        foreach ($result as $row) {
            $event_id = $row['id'];
            $event_name = stripslashes($row['event_name']);
            $event_location = stripslashes($row['event_location']);
            $event_address = $row['event_address'];
            $event_city = $row['event_city'];
            $event_postal = $row['event_postal'];
            $reg_limit = $row['reg_limit'];
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            //added 6.00.13
            $send_coord = $row['send_coord'];
            $coord_email = $row['coord_email'];
            $coord_msg = stripcslashes($row['coord_msg']);
            $coord_pay_msg = stripslashes($row['coord_pay_msg']);
        }

        $events_paypal_transactions_tbl = get_option('evr_payment');
        //Store transaction details in the database

        $sql = array('payer_id' => $id, 'event_id' => $event_id, 'payment_date' => $payment_date, 'txn_id' => $txn_id, 'first_name' => $first_name,
            'last_name' => $last_name, 'payer_email' => $payer_email, 'payer_status' => $payer_status, 'payment_type' => $payment_type, 'memo' => $memo,
            'item_name' => $item_name, 'item_number' => $item_number, 'quantity' => $quantity,
            'mc_gross' => $amount_pd, 'mc_currency' => $mc_currency, 'address_name' => $address_name, 'address_street' => $address_street,
            'address_city' => $address_city, 'address_state' => $address_state,
            'address_zip' => $address_zip, 'address_country' => $address_country, 'address_status' => $address_status,
            'payer_business_name' => $payer_business_name, 'payment_status' => $payment_status,
            'pending_reason' => $pending_reason, 'reason_code' => $reason_code, 'txn_type' => $txn_type);

        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

        $query = "SELECT * FROM " . $events_attendee_tbl . " WHERE id ='" . (int)$id . "'";

        $result = $wpdb->get_results($query, ARRAY_A) or die('Error : ' . mysqli_error());

        foreach ($result as $row) {
            $attendee_email = $row['email'];
            $f_name = $row['fname'];
            $l_name = $row['lname'];
        }

        if ($wpdb->insert(get_option('evr_payment'), $sql, $sql_data)) {
            $headers = "From: " . $company_options['company'] . " <" . $company_options['company_email'] . ">\r\n";
            $headers .= "Content-Type: text/html\r\n";
            $headers .= "Reply-To: " . $company_options['company'] . "  <" . $company_options['company_email'] . ">\r\n";
            $details = "";
            foreach ($p->ipn_data as $key => $value) {
                $details .= "\n$key: $value";
            }
            $paydate = date('m/d/Y');
            $paytime = date('g:i A');
            $SearchValues = array("[payer_email]", "[fname]", "[lname]", "[attendee_email]", "[event_name]", "[event_id]", "[details]", "[pay_date]", "[pay_time]");
            $ReplaceValues = array($p->ipn_data['payer_email'], $f_name, $l_name, $attendee_email, $event_name, $event_id, $details, $paydate, $paytime);

            if ($send_coord == "Y") {
                $subject = 'Sandbox Instant Payment Notification - Success';
                $email_content = str_replace($SearchValues, $ReplaceValues, $coord_pay_msg);
                wp_mail($coord_email, html_entity_decode($subject), html_entity_decode($email_content), $headers);
            } else {


                $subject = 'Sandbox Instant Payment Notification - Success';
                $body = "An instant payment notification was successfully posted\n";
                $body .= "from " . $p->ipn_data['payer_email'] . " on " . date('m/d/Y');
                $body .= " at " . date('g:i A') . "\n\nDetails:\n";
                foreach ($p->ipn_data as $key => $value) {
                    $body .= "\n$key: $value";
                }
                wp_mail($contact, $subject, $body);
            }
        } else {
            if ($send_coord == "Y") {
                $subject = 'Sandbox Instant Payment Notification - Failure';
                $body = "An instant payment notification was received but not posted!\n";
                $body .= "from " . $p->ipn_data['payer_email'] . " on " . date('m/d/Y');
                $body .= " at " . date('g:i A') . "\n\nDetails:\n";
                foreach ($p->ipn_data as $key => $value) {
                    $body .= "\n$key: $value";
                }
                wp_mail($coord_email, $subject, $body);
            } else {

                $subject = 'Sandbox Instant Payment Notification - Failure';
                $body = "An instant payment notification was received but not posted!\n";
                $body .= "from " . $p->ipn_data['payer_email'] . " on " . date('m/d/Y');
                $body .= " at " . date('g:i A') . "\n\nDetails:\n";
                foreach ($p->ipn_data as $key => $value) {
                    $body .= "\n$key: $value";
                }
                wp_mail($contact, $subject, $body);
            }
        }

        $email_subject = $company_options['payment_subj'];
        $payment_msg = stripslashes($company_options['payment_message']);
        $pay_confirm = $company_options['pay_confirm'];
        $Organization = $company_options['company'];
        $contact = $company_options['company_email'];

        $headers .= "From: " . $Organization . " <" . $contact . ">\r\n";
        $headers .= "Reply-To: " . $Organization . "  <" . $contact . ">\r\n";

        if ($send_coord == "Y") {
            $contact = $coord_email;
        } else {
            $contact = $company_options['company_email'];
        }
        
        $payment_link = evrplus_permalink($company_options['return_url']) . "id=" . $id . "&fname=" . $f_name;

        $SearchValues = array("[id]", "[fname]", "[lname]", "[contact]", "[payer_email]", "[event_name]",
            "[event_id]", "[location]", "[event_city]", "[amnt_pd]", "[txn_id]",
            "[payment_url]", "[start_date]", "[start_time]", "[end_date]", "[end_time]",
            "[email]");
        $ReplaceValues = array($id, $f_name, $l_name, $contact, $payer_email, $event_name,
            $event_id, $event_location, $event_city, $amount_pd, $txn_id,
            $payment_link, $start_date, $start_time, $end_date, $end_time,
            $attendee_email);


        $email_content = str_replace($SearchValues, $ReplaceValues, $payment_msg);

        if ($pay_confirm == 'Y') {
            wp_mail($attendee_email, html_entity_decode($email_subject), html_entity_decode($email_content), $headers);
        }
    }

}
