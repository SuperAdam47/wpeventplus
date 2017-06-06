<?php

class EventPlus_Models_Payments extends EventPlus_Abstract_Model {

    const AUTHORIZE = 'AUTHORIZE';
    const PAYPAL = 'PAYPAL';
    const STRIPE = 'STRIPEACTIVE';
    const OFFLINE = 'NONE';
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_SUCCESS = 'success';

    private $methodMeta = array();

    function __construct() {
        parent::__construct();

        $this->_table = get_option('evr_payment');

        $this->methodMeta[self::AUTHORIZE] = array('title' => __('Authorize.net', 'evrplus_language'), 'logo' => 'authnet.png');
        $this->methodMeta[self::PAYPAL] = array('title' => __('PayPal', 'evrplus_language'), 'logo' => 'paypal.png');
        $this->methodMeta[self::STRIPE] = array('title' => __('Stripe', 'evrplus_language'), 'logo' => 'stripe.png');
        $this->methodMeta[self::OFFLINE] = array('title' => __('Payment Offline', 'evrplus_language'), 'logo' => '');
    }

    static function getPaymentStatusCodes() {
        return array(self::PAYMENT_FAILED, self::PAYMENT_PENDING, self::PAYMENT_SUCCESS);
    }

    function getMethodMeta($method) {
        return $this->methodMeta[$method];
    }

    function getTotalRecords($event_id) {
        $sql = "SELECT count(1) as totRecords FROM " . $this->_table . " WHERE event_id = '" . (int) $event_id . "' LIMIT 1";

        $row = $this->QuickArray($sql);

        return $row['totRecords'];
    }

    function getPayments($payer_id) {
        $sql3 = "SELECT * FROM " . $this->_table . " WHERE payer_id='" . (int) $payer_id . "' ";
        return $this->getWpDb()->get_results($sql3, ARRAY_A);
    }

    function getData($id) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE id = '" . (int) $id . "' LIMIT 1";
        $row = $this->QuickArray($sql);

        if (is_array($row)) {
            return $row;
        } else {
            return false;
        }
    }

    function updateAttendeeStatus($payer_id) {

        $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE id = '" . (int) $payer_id . "' LIMIT 1";
        $attendeeRow = $this->getWpDb()->get_row($sql, ARRAY_A);

        $sql = "SELECT SUM(mc_gross) as totPaid FROM " . get_option('evr_payment') . " WHERE payment_status = 'success' AND payer_id='" . (int) $payer_id . "' LIMIT 1";
        $totPaymentRow = $this->getWpDb()->get_row($sql, ARRAY_A);

        $total_paid = $totPaymentRow['totPaid'];
        $orderTotal = $attendeeRow['payment'];

        $balance = ($orderTotal - $total_paid);

        if ($balance <= 0) {
            $payment_date = date('Y-m-d G:i:s', time());
            $this->getWpDb()->query($this->getWpDb()->prepare("UPDATE " . get_option('evr_attendee') . " SET payment_status = 'success', amount_pd = '" . esc_sql($total_paid) . "', payment_date = '" . esc_sql($payment_date) . "' WHERE id = %d", $attendeeRow['id']));
        }
    }

    function addPayment($params, $oEvent) {
        $wpdb = $this->getWpDb();

        $payer_id = $params['attendee_id'];
        $event_id = $params['event_id'];
        $first_name = $params['first_name'];
        $last_name = $params['last_name'];
        $payer_email = $params['payer_email'];
        $txn_id = $params['txn_id'];
        $payment_type = $params['payment_type'];
        $item_name = $params['item_name'];
        $item_number = $params['item_number'];
        $quantity = $params['quantity'];
        $payer_status = $params['payer_status'];
        $payment_status = $params['payment_status'];
        $txn_type = $params['txn_type'];
        $mc_currency = $params['mc_currency'];
        $memo = $params['memo'];
        $payment_date = $params['payment_date'];
        if (isset($params['mc_gross'])) {
            $amount_pd = $params['mc_gross'];
        } else {
            $amount_pd = $params['payment_gross'];
        }
        $mc_gross = $amount_pd;
        $address_name = $params['address_name'];
        $address_street = $params['address_street'];
        $address_city = $params['address_city'];
        $address_state = $params['address_state'];
        $address_zip = $params['address_zip'];
        $address_country = $params['address_country'];
        $address_status = $params['address_status'];
        $payer_business_name = $params['payer_business_name'];
        $pending_reason = $params['pending_reason'];
        $reason_code = $params['reason_code'];

        if ($amount_pd > 0) {
            $payment_status = 'success';
        } else {
            $payment_status = 'pending';
        }

        $send_payment_rec = $params['send_payment_rec'];

        $sql = array('payer_id' => $payer_id, 'event_id' => $event_id, 'payment_date' => $payment_date, 'txn_id' => $txn_id,
            'first_name' => $first_name, 'last_name' => $last_name, 'payer_email' => $payer_email, 'payer_status' => "$payer_status",
            'payment_type' => $payment_type, 'memo' => "$memo", 'item_name' => $item_name, 'item_number' => $item_number,
            'quantity' => $quantity, 'mc_gross' => $mc_gross, 'mc_currency' => "$mc_currency", 'address_name' => "$address_name",
            'address_street' => $address_street, 'address_city' => $address_city, 'address_state' => $address_state, 'address_zip' => $address_zip,
            'address_country' => $address_country, 'address_status' => $address_status, 'payer_business_name' => $payer_business_name,
            'payment_status' => "$payment_status",
            'pending_reason' => $pending_reason, 'reason_code' => $reason_code, 'txn_type' => $txn_type);

        $payment_dtl = array('payer_id' => $payer_id, 'event_id' => $event_id, 'payment_date' => $payment_date, 'txn_id' => $txn_id,
            'first_name' => $first_name, 'last_name' => $last_name, 'payer_email' => $payer_email, 'payer_status' => "$payer_status",
            'payment_type' => $payment_type, 'memo' => $memo, 'item_name' => $item_name, 'item_number' => $item_number,
            'quantity' => $quantity, 'mc_gross' => $mc_gross, 'mc_currency' => $mc_currency, 'address_name' => $address_name,
            'address_street' => $address_street, 'address_city' => $address_city, 'address_state' => $address_state, 'address_zip' => $address_zip,
            'address_country' => $address_country, 'address_status' => $address_status, 'payer_business_name' => $payer_business_name, 'payment_status' => $payment_status,
            'pending_reason' => $pending_reason, 'reason_code' => $reason_code, 'txn_type' => $txn_type);


        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');



        if ($wpdb->insert(get_option('evr_payment'), $sql, $sql_data)) {

            $this->updateAttendeeStatus($payer_id);

            if ($send_payment_rec == "Y") {

                $company_options = EventPlus_Models_Settings::getSettings();

                $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE id ='$payer_id'";
                //$result = mysql_query ( $sql );
                //$attendee_dtl = mysql_fetch_assoc ( $result );
                $attendee_dtl = $wpdb->get_results($sql, ARRAY_A);
                //$attendee_dtl = mysql_fetch_assoc ( $result );
                $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . $event_id;
                // $result = mysql_query ( $sql );
                $event_dtl = $wpdb->get_results($sql, ARRAY_A);

                //get return URL

                $payment_link = EventPlus_Helpers_Event::permalink($company_options['return_url']) . "id=" . $payment_dtl['payer_id'] . "&fname=" . $payment_dtl['first_name'];


                $payment_cue = __("To make payment or view your payment information go to", 'evrplus_language');
                $payment_text = $payment_cue . ": " . $payment_link;
                $subject = $company_options['payment_subj'];
                $distro = $email;

                $ticket_order = unserialize($attendee_dtl[0]['tickets']);

                /*  $row_count = count($ticket_order);
                  for ($row = 0; $row < $row_count; $row++) {
                  echo $ticket_order[$row]['ItemQty']." ".$ticket_order[$row]['ItemCat']."-".$ticket_order[$row]['ItemName']." ".$ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost']."<br \>";
                  }
                 */
                $message = html_entity_decode(nl2br($company_options['payment_message']));


                //search and replace tags
                /*
                  $SearchValues = array("[id]","[fname]", "[lname]", "[phone]", "[event]",
                  "[description]", "[cost]", "[currency]","[payment]",
                  "[company]", "[co_add1]", "[co_add2]",
                  "[co_city]", "[co_state]", "[co_zip]", "[payment_url]",
                  "[start_date]", "[start_time]", "[end_date]",
                  "[end_time]", "[num_people]");

                  $ReplaceValues = array($attendee_dtl['id'], $attendee_dtl['fname'], $attendee_dtl['lname'], $attendee_dtl['phone'], stripslashes($event_dtl['event_name']),
                  stripslashes($event_dtl['event_desc']), evrplus_moneyFormat($attendee_dtl['payment']), $currency_format, evrplus_moneyFormat($amount_pd),
                  $company_options['company_email'], $company_options['company'], $company_options['company_street1'], $company_options['company_street2'],
                  $company_options['city'], $company_options['state'], $company_options['postal'],$payment_link ,
                  ,
                  $attendee_dtl['quantity']);
                 */
                $SearchValues = array("[id]", "[fname]", "[lname]", "[payer_email]", "[event_name]", "[contact]",
                    "[payment_url]", "[amnt_pd]", "[txn_id]", "[txn_type]", "[address_street]", "[address_city]",
                    "[address_state]", "[address_zip]", "[address_country]", "
                        [start_date]", "[start_time]", "[end_date]", "[end_time]");


                $ReplaceValues = array($payment_dtl['payer_id'], $payment_dtl['first_name'], $payment_dtl['last_name'], $payment_dtl['payer_email'], stripslashes($event_dtl[0]['event_name']), $company_options['company_email'],
                    $payment_link, $payment_dtl['mc_gross'], $payment_dtl['txn_id'], $payment_dtl['txn_type'], $payment_dtl['address_street'], $payment_dtl['address_city'],
                    $payment_dtl['address_state'], $payment_dtl['address_zip'], $payment_dtl['address_country'],
                    $event_dtl[0]['start_date'], $event_dtl[0]['start_time'], $event_dtl[0]['end_date'], $event_dtl[0]['end_time'],);


                $email_content = str_replace($SearchValues, $ReplaceValues, $message);
                //$email_content .= "</br>".$payment_text;
                $message_top = "<html><body>";
                $message_bottom = "</html></body>";


                $email_body = $message_top . $email_content . $message_bottom;

                /* $headers = "MIME-Version: 1.0\r\n";
                  $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                  $headers .= 'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n"; */
                $headers = array(
                    'From: "' . ($company_options['company']) . '" <' . $company_options['company_email'] . ">\r\n",
                    "Content-Type: text/html"
                );
                $headers = implode("\r\n", $headers) . "\r\n";

                $this->wp_mail($attendee_dtl[0]['email'], $subject, html_entity_decode($email_body), $headers);

                $this->setMessage(__('The payment has now been added and Payment notification has been sent. ', 'evrplus_language'));
            } else {
                $this->setMessage(__('The payment has now been added.', 'evrplus_language'));
            }
            return true;
        } else {
            $this->setMessage(__('There was an error in your submission, please try again. The payment was not saved!', 'evrplus_language'));
            return false;
        }
    }

    function updatePayment($params, $dbRow, $oEvent) {
        $wpdb = $this->getWpDb();

        $event_id = (int) $params['event_id'];
        $payment_id = $params['payment_id'];
        $payer_id = $params['attendee_id'];
        $first_name = $params['first_name'];
        $last_name = $params['last_name'];
        $payer_email = $params['payer_email'];
        $txn_id = $params['txn_id'];
        $payment_type = $params['payment_type'];
        $item_name = $params['item_name'];
        $item_number = $params['item_number'];
        $quantity = $params['quantity'];
        $payer_status = $params['payer_status'];
        $payment_status = $params['payment_status'];
        $txn_type = $params['txn_type'];
        $mc_currency = $params['mc_currency'];
        $memo = $params['memo'];
        $payment_date = $params['payment_date'];
        if (isset($params['mc_gross'])) {
            $amount_pd = $params['mc_gross'];
        } else {
            $amount_pd = $params['payment_gross'];
        }

        if ($amount_pd > 0) {
            $payment_status = 'success';
        } else {
            $payment_status = 'pending';
        }

        $mc_gross = $amount_pd;
        $address_name = $params['address_name'];
        $address_street = $params['address_street'];
        $address_city = $params['address_city'];
        $address_state = $params['address_state'];
        $address_zip = $params['address_zip'];
        $address_country = $params['address_country'];
        $address_status = $params['address_status'];
        $payer_business_name = $params['payer_business_name'];
        $pending_reason = $params['pending_reason'];
        $reason_code = $params['reason_code'];

        $send_payment_rec = $params['send_payment_rec'];

        $sql = array('payer_id' => $payer_id, 'event_id' => $event_id, 'payment_date' => $payment_date, 'txn_id' => $txn_id,
            'first_name' => $first_name, 'last_name' => $last_name, 'payer_email' => $payer_email, 'payer_status' => $payer_status,
            'payment_type' => $payment_type, 'memo' => $memo, 'item_name' => $item_name, 'item_number' => $item_number,
            'quantity' => $quantity, 'mc_gross' => $mc_gross, 'mc_currency' => $mc_currency, 'address_name' => $address_name,
            'address_street' => $address_street, 'address_city' => $address_city, 'address_state' => $address_state, 'address_zip' => $address_zip,
            'address_country' => $address_country, 'address_status' => $address_status, 'payer_business_name' => $payer_business_name, 'payment_status' => $payment_status,
            'pending_reason' => $pending_reason, 'reason_code' => $reason_code, 'txn_type' => $txn_type);

        $payment_dtl = array('payer_id' => $payer_id, 'event_id' => $event_id, 'payment_date' => $payment_date, 'txn_id' => $txn_id,
            'first_name' => $first_name, 'last_name' => $last_name, 'payer_email' => $payer_email, 'payer_status' => $payer_status,
            'payment_type' => $payment_type, 'memo' => $memo, 'item_name' => $item_name, 'item_number' => $item_number,
            'quantity' => $quantity, 'mc_gross' => $mc_gross, 'mc_currency' => $mc_currency, 'address_name' => $address_name,
            'address_street' => $address_street, 'address_city' => $address_city, 'address_state' => $address_state, 'address_zip' => $address_zip,
            'address_country' => $address_country, 'address_status' => $address_status, 'payer_business_name' => $payer_business_name, 'payment_status' => $payment_status,
            'pending_reason' => $pending_reason, 'reason_code' => $reason_code, 'txn_type' => $txn_type);



        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

        $update_id = array('id' => $payment_id);



        if ($wpdb->update(get_option('evr_payment'), $sql, $update_id, $sql_data, array('%d')) === false) {

            $this->setMessage(__('There was an error in your submission, please try again. The payment was not updated!', 'evrplus_language'));

            return false;
        } else {

            $this->updateAttendeeStatus($payer_id);

            $this->setMessage(__('The payment has been updated.', 'evrplus_language'));

            if ($send_payment_rec == "Y") {

                $company_options = EventPlus_Models_Settings::getSettings();

                $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE id ='$payer_id'";
                //$result = mysql_query ( $sql );
                //$attendee_dtl = mysql_fetch_assoc ( $result );
                $attendee_dtl = $wpdb->get_results($sql, ARRAY_A);
                $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . $event_id;
                //$result = mysql_query ( $sql );
                //$event_dtl = mysql_fetch_assoc ($result);  
                $event_dtl = $wpdb->get_results($sql, ARRAY_A);
                //get return URL

                $oEeventHelper = new EventPlus_Helpers_Event();

                $payment_link = $oEeventHelper->permalink($company_options['return_url']) . "id=" . $payment_dtl['payer_id'] . "&fname=" . $payment_dtl['first_name'];

                $subject = "Updated " . $company_options['payment_subj'];
                $distro = $email;
                $message = html_entity_decode(nl2br($company_options['payment_message']));
                //search and replace tags
                $SearchValues = array("[id]", "[fname]", "[lname]", "[payer_email]", "[event_name]", "[contact]",
                    "[payment_url]", "[amnt_pd]", "[txn_id]", "[txn_type]", "[address_street]", "[address_city]",
                    "[address_state]", "[address_zip]", "[address_country]", "
                        [start_date]", "[start_time]", "[end_date]", "[end_time]");
                $ReplaceValues = array($payment_dtl['payer_id'], $payment_dtl['first_name'], $payment_dtl['last_name'], $payment_dtl['payer_email'], stripslashes($event_dtl['event_name']), $company_options['company_email'],
                    $payment_link, $payment_dtl['mc_gross'], $payment_dtl['txn_id'], $payment_dtl['txn_type'], $payment_dtl['address_street'], $payment_dtl['address_city'],
                    $payment_dtl['address_state'], $payment_dtl['address_zip'], $payment_dtl['address_country'],
                    $event_dtl['start_date'], $event_dtl['start_time'], $event_dtl['end_date'], $event_dtl['end_time'],);

                $email_content = str_replace($SearchValues, $ReplaceValues, $message);
                $message_top = "<html><body>";
                $message_bottom = "</html></body>";
                $email_body = $message_top . $email_content . $message_bottom;

                /* $headers = "MIME-Version: 1.0\r\n";
                  $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                  $headers .= 'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n"; */
                $headers = array(
                    'From: "' . ($company_options['company']) . '" <' . $company_options['company_email'] . ">\r\n",
                    "Content-Type: text/html"
                );
                $headers = implode("\r\n", $headers) . "\r\n";

                $this->wp_mail($attendee_dtl['email'], $subject, html_entity_decode($email_body), $headers);
            }
            return true;
        }
    }

    function getByPayerId($payer_id) {

        $sql = "SELECT p.*"
                . " FROM " . $this->_table . " p ";

        $sql .= " WHERE 1=1 ";

        $sql .= " AND p.payer_id = '" . (int) $payer_id . "'";


        return $this->getResults($sql);
    }

    function deletePayment($id) {
        return $this->deleteRow('id', $id, __('The payment has been successfully deleted from the attendee.', 'evrplus_language'), __("Payment entry couldn't deleted.", 'evrplus_language'));
    }

    function sendEmailReminder($event_id) {

        $messages = array();

        if ($event_id > 0) {
            $curdate = date("Y-m-d");
            $wpdb = $this->getWpDb();

            $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE payment > 0 AND (payment_status is NULL OR payment_status = '' OR payment_status = 'pending') AND event_id='" . (int) $event_id . "'";
            $attendees = $wpdb->get_results($sql);

            foreach ($attendees as $attendee) {
                if ($attendee->payment >= '.01') {
                    $payment_recieved = $wpdb->get_var($wpdb->prepare("SELECT SUM(mc_gross) FROM " . get_option('evr_payment') . " WHERE payment_sttus = 'success' AND payer_id= %d LIMIT 1", $attendee->id));
                    $payment_dtl = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . get_option('evr_payment') . " WHERE payment_sttus = 'success' AND payer_id= %d", $attendee->id), ARRAY_A);
                    $balance_due = $attendee->payment - $payment_recieved;
                    if ($balance_due > 0) {
                        
                        $company_options = EventPlus_Models_Settings::getSettings();

                        $payment_link = evrplus_permalink($company_options['evrplus_page_id']) . 'action=confirmation&event_id=' . $attendee->event_id . '&eventplus_token=' . $attendee->token;

                        $payment_cue = __("A balance is outstanding on your event registration fees.  Please pay to complete your registration process.", 'evrplus_language');
                        $payment_text = $payment_cue . ": " . $payment_link;
                        $subject = __('Payment Reminder', 'evrplus_language');
                        $distro = $attendee->email;

                        $SearchValues = array("[id]", "[fname]", "[lname]", "[payer_email]", "[event_name]", "[contact]",
                            "[payment_url]", "[amnt_pd]", "[txn_id]", "[txn_type]", "[address_street]", "[address_city]",
                            "[address_state]", "[address_zip]", "[address_country]", "
                        [start_date]", "[start_time]", "[end_date]", "[end_time]");


                        $ReplaceValues = array($payment_dtl['payer_id'], $payment_dtl['first_name'], $payment_dtl['last_name'], $payment_dtl['payer_email'], stripslashes($event_dtl['event_name']), $company_options['company_email'],
                            $payment_link, $payment_dtl['mc_gross'], $payment_dtl['txn_id'], $payment_dtl['txn_type'], $payment_dtl['address_street'], $payment_dtl['address_city'],
                            $payment_dtl['address_state'], $payment_dtl['address_zip'], $payment_dtl['address_country'],
                            $event_dtl['start_date'], $event_dtl['start_time'], $event_dtl['end_date'], $event_dtl['end_time'],);

                        //$email_content = str_replace($SearchValues, $ReplaceValues, $message);
                        $email_content = $payment_text;
                        $message_top = "<html><body>";
                        $message_bottom = "</html></body>";

                        $email_body = $message_top . $email_content . $message_bottom;

                        /* $headers = "MIME-Version: 1.0\r\n";
                          $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                          $headers .= 'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n"; */
                        $headers = array(
                            'From: "' . ($company_options['company']) . '" <' . $company_options['company_email'] . ">\r\n",
                            "Content-Type: text/html"
                        );
                        $headers = implode("\r\n", $headers) . "\r\n";

                        $this->wp_mail($distro, $subject, html_entity_decode($email_body), $headers);

                        $messages[] = __('Payment Reminder Notification sent to', 'evrplus_language') . " " .
                                $attendee->fname . " " . $attendee->lname . " | " . $attendee->email . " | " .
                                " Amount due: " . $balance_due;
                    }
                }
            }
        }

        $sent_count = intVal(count($messages));
        $response = ($sent_count > 0);

        if ($sent_count == 0) {
            $messages[] = __('No pending payments reminders to send', 'evrplus_language');
        }

        $this->setFormattedMessage($messages);


        return $response;
    }

    function wp_mail($email, $subject, $email_body, $headers) {
        return wp_mail($email, $subject, html_entity_decode($email_body), $headers);
    }

    static function getPaymentMethods() {
        $paymentMethods = array();
        $paymentMethods[self::AUTHORIZE] = 'Authorize.net';
        $paymentMethods[self::PAYPAL] = 'PayPal';
        $paymentMethods[self::STRIPE] = 'Stripe';
        $paymentMethods[self::OFFLINE] = 'Offline Payment';

        return $paymentMethods;
    }

    static function isValidMethod($key) {
        $paymentMethods = self::getPaymentMethods();

        return (isset($paymentMethods[$key]));
    }

    static function isActive($method) {
        $payment_methods = EventPlus_Models_Settings::getPaymentMethods();

        if (in_array($method, (array) $payment_methods)) {
            return true;
        } else {
            return false;
        }
    }

}
