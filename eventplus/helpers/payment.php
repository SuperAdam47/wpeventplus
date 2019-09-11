<?php

class EventPlus_Helpers_Payment {

    private $companyOptions = null;
    private $event_id = 0;
    private $attendee_id = 0;
    private $eventRow = array();
    private $attendeeRow = array();
    private $returnUrl = '';
    private $cancelReturnUrl = '';

    function __construct($event_id, $attendee_id) {

        global $wpdb;

        $this->companyOptions = EventPlus_Models_Settings::getSettings();


        $this->event_id = $event_id;
        $this->attendee_id = $attendee_id;

        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int) $event_id . " LIMIT 1";
        $this->eventRow = $wpdb->get_row($sql, ARRAY_A);

        $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE event_id = '" . $event_id . "' AND id=" . (int) $attendee_id . " LIMIT 1";
        $this->attendeeRow = $wpdb->get_row($sql, ARRAY_A);

        $this->returnUrl = evrplus_permalink($this->companyOptions['return_url']) . '&action=return&eventplus_token=' . $this->attendeeRow['token'];
        $this->cancelReturnUrl = evrplus_permalink($this->companyOptions['return_url']) . '&action=cancel&eventplus_token=' . $this->attendeeRow['token'];
    }

    /**
     * Get All active/applicable payment methods
     * @return array
     */
    private function getPaymentOptions() {

        $payment_methods = EventPlus_Models_Settings::getPaymentMethods();

        foreach ($payment_methods as $index => $payment_method) {
            if ($payment_method == EventPlus_Models_Payments::OFFLINE) {

                $isCheckApplicable = strtolower($this->companyOptions['checks']) == 'yes';

                $isDonationApplicable = (strtolower($this->companyOptions['donations']) == "yes") && intVal($this->attendeeRow['payment']) <= 0 && $this->attendeeRow['reg_type'] != "WAIT";

                if ($isCheckApplicable == false && $isDonationApplicable == false) {
                    unset($payment_methods[$index]);
                }
            }

            if ($payment_method == EventPlus_Models_Payments::PAYPAL) {
                if (EventPlus::factory('Validate')->email($this->companyOptions['payment_vendor_id']) == false) {
                    unset($payment_methods[$index]);
                }
            }

            if ($payment_method == EventPlus_Models_Payments::STRIPE) {
                if (trim($this->companyOptions['secret_key']) == '' || trim($this->companyOptions['publishable_key']) == '') {
                    unset($payment_methods[$index]);
                }
            }

            if ($payment_method == EventPlus_Models_Payments::AUTHORIZE) {
                if (trim($this->companyOptions['authorize_id']) == '' || trim($this->companyOptions['authorize_key']) == '') {
                    unset($payment_methods[$index]);
                }
            }
        }

        return $payment_methods;
    }

    function evrplus_registration_payment() {

        $event_id = $this->event_id;

        if ($this->eventRow['id'] <= 0) {
            _e('Invalid event - please retry!', 'evrplus_language');
            return;
        }

        $attendee_id = $this->attendee_id;

        if ($this->attendeeRow['id'] <= 0) {
            _e('Invalid registration - please retry!', 'evrplus_language');
            return;
        }

        $event_name = stripslashes($this->eventRow['event_name']);
        $event_location = $this->eventRow['event_location'];
        $event_address = $this->eventRow['event_address'];
        $event_city = $this->eventRow['event_city'];
        $event_postal = $this->eventRow['event_postal'];
        $reg_limit = $this->eventRow['reg_limit'];
        $start_time = $this->eventRow['start_time'];
        $end_time = $this->eventRow['end_time'];
        $start_date = $this->eventRow['start_date'];
        $end_date = $this->eventRow['end_date'];
        $use_coupon = $this->eventRow['use_coupon'];
        $coupon_code = $this->eventRow['coupon_code'];
        $coupon_code_price = $this->eventRow['coupon_code_price'];


        $lname = $this->attendeeRow ['lname'];
        $fname = $this->attendeeRow ['fname'];
        $address = $this->attendeeRow ['address'];
        $city = $this->attendeeRow ['city'];
        $state = $this->attendeeRow ['state'];
        $zip = $this->attendeeRow ['zip'];
        $email = $this->attendeeRow ['email'];
        $phone = $this->attendeeRow ['phone'];
        $quantity = $this->attendeeRow ['quantity'];
        $date = $this->attendeeRow ['date'];
        $reg_type = $this->attendeeRow['reg_type'];
        $ticket_order = unserialize($this->attendeeRow['tickets']);

        $tax = $this->attendeeRow['tax'];
        $payment = $this->attendeeRow['payment'];
        $coupon = $this->attendeeRow['coupon'];
        $token = $this->attendeeRow['token'];
        $order_total = $this->attendeeRow['order_total'];
        $discount_percentage = $this->attendeeRow['discount_percentage'];
        $discount_amount = $this->attendeeRow['discount_amount'];
        $attendee_name = $fname . " " . $lname;
        $row_count = count($ticket_order);

        // Print the Order Verification to the screen.

        echo '<table width="100%" cellpadding="0" cellspacing="0" class="data-summary">'
        . '<thead>
                <tr>
                    <th colspan="2"><i class="fa fa-pencil"></i> ' . __('Order details', 'evrplus_language') . '</th>
                </tr>
            </thead>';

        echo '<tbody>';

        echo '<tr>';
        echo '<td><i class="fa fa-calculator"></i> ' . __('Event Name:', 'evrplus_language') . '</td>
                <td>' . $event_name . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . __('Attendee Name:', 'evrplus_language') . '</td>
                <td>' . $attendee_name . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . __('Email Address:', 'evrplus_language') . '</td>
                <td>' . $email . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . __('Number of Attendees:', 'evrplus_language') . '</td>
                <td>' . $quantity . '</td>';
        echo '</tr>';

        if ($row_count > 0) {
            echo '<tr>'
            . '<td>';
            _e('Order Details:', 'evrplus_language');
            echo '</td>'
            . '<td>';

            for ($row = 0; $row < $row_count; $row++) {
                if ($ticket_order[$row]['ItemQty'] >= 1) {
                    echo $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " .
                    $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "<br/>";
                }
            }

            echo '</td></tr>';
        }

        echo '</tbody>';

        if ($this->companyOptions['use_sales_tax'] == "Y") {
            echo '<tr>'
            . '<td>' . __('Sales Tax  ', 'evrplus_language') . '</td>'
            . '<td>' . $tax . '</td>'
            . '</tr>';
        }

        echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
        echo '<td>' . $ticket_order[0]['ItemCurrency'] . ' <strong>' . number_format($payment, 2) . '</strong></td></tr></table>';


        if ($this->attendeeRow['payment_status'] == '' || $this->attendeeRow['payment_status'] == null) {

            $paymentOptions = $this->getPaymentOptions();

            if (count($paymentOptions) > 0) {
                echo'
            <div class="info-m3ssages">' . __("Please select one of the following methods and by clicking Pay Now button you will be taken to our payment vendor's site", 'evrplus_language') . '</div>
        ';
                echo '<table width="100%" cellspacing="0" cellpadding="0" border="0"  class="data-summary"><thead>
                    <tr>
                    <th colspan="2"><i class="fa fa-money"></i> ' . __('Payment Methods', 'evrplus_language') . '</th>
                </tr>
                </thead>
                <tbody>';

                $oPaymentMethods = new EventPlus_Models_Payments();
                foreach( $paymentOptions as $pi => $paymentOption ) {

                    $paymentMethodMeta = $oPaymentMethods->getMethodMeta($paymentOption);

                    $paymentTitleStr = $paymentMethodMeta['title'];
                    if ($paymentMethodMeta['logo'] != '') {
                        $paymentTitleStr = "<img src='" . EVENT_PLUS_PLUGIN_URL . 'assets/images/pm/' . $paymentMethodMeta['logo'] . "' alt='" . $paymentMethodMeta['title'] . "' />";
                    }

                    if( $paymentOption == EventPlus_Models_Payments::STRIPE ) {

                        if ($payment != "0.00" || $payment != "0" || $payment != "" || $payment != " ") {

                            $oStripe = new EventPlus_Payments_Stripe(); // initiate an instance of the class
                                
                            $stripe_process_url = add_query_arg(array(
                                'eventplus_token' => $this->attendeeRow['token'],
                                'eventplus_pm' => 'stripe',
                            ), site_url());
                            
                            $oStripe->add_field('event_id', $event_id);
                            $oStripe->add_field('stripe_process_url', $stripe_process_url);
                            $oStripe->add_field('amount', $payment);
                            $oStripe->add_field('token', $token);
                            $oStripe->add_field('currency_code', $ticket_order[0]['ItemCurrency']);
                            $oStripe->add_field('desc', '['.$event_id.'] '.$event_name.' - Payment');

                            echo'<tr>
                                <td><b>' . $paymentTitleStr . '</b></td>
                                <td>' . $oStripe->submit() . '</td>
                              </tr>';
                        }
                    }

                    if ($paymentOption == EventPlus_Models_Payments::OFFLINE) {
                        if (strtolower($this->companyOptions['checks']) == "yes") {
                            echo'<tr>
                            <td valign="top"><b>' . $paymentTitleStr . '</b></td>
                            <td>';

                            echo " <a href='#' class='offline--details-toggle' title='View Details'>(" . __("View Details", 'evrplus_language') . ")</a>";

                            echo'<div id="evplus--offline-details" style="display:none;">';
                            _e("Please mail your check to:", 'evrplus_language');
                            echo "<p class='evplus-offline-payment-address'>" .
                            stripslashes($this->companyOptions['company']) . "<br />" .
                            $this->companyOptions['company_street1'] . "<br />";
                            if ($this->companyOptions['company_street2'] != "") {
                                echo $this->companyOptions['company_street2'] . "<br />";
                            }
                            echo $this->companyOptions['company_city'] . " " . $this->companyOptions['company_state'] . " " . $this->companyOptions['company_postal'] . "</p>";

                            echo '</div></td>'
                            . '</tr>';
                        }
                    }

                    if ($paymentOption == EventPlus_Models_Payments::PAYPAL) {

                        if ($payment != "0.00" || $payment != "0" || $payment != "" || $payment != " ") {
                            $oPayPal = new EventPlus_Payments_Paypal();

                            //$returnUrl = EVENT_PLUS_PUBLIC_URL . 'paypal/re7urn.php?eventplus_token=' . $this->attendeeRow['token'];
                            //$cancelUrl = EVENT_PLUS_PUBLIC_URL . 'paypal/canc3l.php?eventplus_token=' . $this->attendeeRow['token'];

                            $returnUrl = add_query_arg(array(
                                'eventplus_token' => $this->attendeeRow['token'],
                                'eventplus_pm' => 'paypal',
                                'eventplus_pm_action' => 're7urn',
                            ), site_url());

                            $cancelUrl = add_query_arg(array(
                                'eventplus_token' => $this->attendeeRow['token'],
                                'eventplus_pm' => 'paypal',
                                'eventplus_pm_action' => 'canc3l',
                            ), site_url());

                            //$ipnUrl = EVENT_PLUS_PUBLIC_URL . 'paypal/1pn.php?eventplus_token=' . $this->attendeeRow['token'];

                            $oPayPal->add_field('business', $this->companyOptions['payment_vendor_id']);
                            $oPayPal->add_field('return', $returnUrl);
                            $oPayPal->add_field('cancel_return', $cancelUrl);
                            //$oPayPal->add_field('notify_url', $ipnUrl);
                            $oPayPal->add_field('item_name', $event_name . ' | Reg. ID: ' . $attendee_id . ' | Name: ' . $attendee_name . ' | Total Registrants: ' . $quantity);
                            $oPayPal->add_field('amount', $payment);
                            $oPayPal->add_field('currency_code', $ticket_order[0]['ItemCurrency']);

                            //Post variables
                            $oPayPal->add_field('first_name', $fname);
                            $oPayPal->add_field('last_name', $lname);
                            $oPayPal->add_field('email', $email);
                            $oPayPal->add_field('address1', $address);
                            $oPayPal->add_field('city', $city);
                            $oPayPal->add_field('state', $state);
                            $oPayPal->add_field('zip', $zip);

                            $sandboxStr = '';
                            if ($this->companyOptions['use_sandbox'] == "Y") {
                                $sandboxStr .= " " . __("Sandbox Mode", 'evrplus_language') . "  <a href='#' class='paypal--sandbox-toggle' title='View Sandbox Details'>(" . __("View Details", 'evrplus_language') . ")</a>";
                            }

                            echo'<tr>
                            <td><b>' . $paymentTitleStr . '</b></td>
                            <td>' . $oPayPal->submit() . $sandboxStr . ' </td>
                          </tr>';

                            if ($this->companyOptions['use_sandbox'] == "Y") {
                                echo'<tr id="evplus--sandbox" style="display:none;">
                                <td colspan=2>' . $oPayPal->dump_fields(false) . ' </td>
                              </tr>';
                            }
                        }
                    }

                    if ($paymentOption == EventPlus_Models_Payments::AUTHORIZE) {
                        $amount = $payment;
                        $description = $event_name . ' | Reg. ID: ' . $attendee_id . ' | Total Registrants: ' . $quantity;
                        $label = __('Pay Now', 'evrplus_language'); // The is the label on the 'submit' button

                        $loginID = $this->companyOptions['authorize_id'];
                        $transactionKey = $this->companyOptions['authorize_key'];
                        $url = "https://secure.authorize.net/gateway/transact.dll";
                        $testMode = "false";

                        if ($this->companyOptions['use_authorize_sandbox'] == "Y") {
                            $url = "https://test.authorize.net/gateway/transact.dll";
                            $testMode = "true";
                        }

                        // an invoice is generated using the date and time
                        $invoice = $attendee_id . '-' . date('YmdHis', time());
                        // a sequence number is randomly generated
                        $sequence = rand(1, 1000);
                        // a timestamp is generated
                        $timeStamp = time();
                        // The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
                        // newer have the necessary hmac function built in.  For older versions, it
                        // will try to use the mhash library.
                        if (phpversion() >= '5.1.2') {
                            $fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey);
                        } else {
                            $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey));
                        }

                        $ipn_url = EVENT_PLUS_PUBLIC_URL . 'authorize/payment.php';

                        echo'<tr>
                            <td><b>' . $paymentTitleStr . '</b></td>
                            <td>';

                        // Create the HTML form containing necessary SIM post values
                        echo "<FORM method='post' action='$url' >";
                        // Additional fields can be added here as outlined in the SIM integration guide
                        // at: http://developer.authorize.net
                        echo "	<INPUT type='hidden' name='x_login' value='$loginID' />";

                        echo "	<INPUT type='hidden' name='x_amount' value='$amount' />";


                        echo "	<INPUT type='hidden' name='x_description' value='$description' />";
                        echo "	<INPUT type='hidden' name='x_invoice_num' value='$invoice' />";
                        echo "	<INPUT type='hidden' name='x_fp_sequence' value='$sequence' />";
                        echo "	<INPUT type='hidden' name='x_fp_timestamp' value='$timeStamp' />";
                        echo "	<INPUT type='hidden' name='x_fp_hash' value='$fingerprint' />";
                        echo "	<INPUT type='hidden' name='x_test_request' value='$testMode' />";
                        echo "	<INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM' />";
                        echo "	<INPUT type='hidden' name='x_Relay_URL' value='$ipn_url' />";
                        echo "	<input type='submit' value='$label' class='btn btn-sma77 btn-gr3y btn-ic0n paymen8' />";
                        echo "</FORM>";
                        echo '</td>
                          </tr>';
                    }
                }

                echo'</tbody>';
                echo'</table>';
            }
        } else {


            echo '<table width="100%" cellpadding="0" cellspacing="0" class="data-summary">';
            echo '<tr>
                  <td colspan="2">' . __("Payment Details", 'evrplus_language') . '</td>
                </tr>';
            echo '<tr>'
            . '<td>' . __("Payment Status", 'evrplus_language') . '</td><td>' . ucfirst($this->attendeeRow['payment_status']) . '</td>'
            . '</tr>';

            $oModels_Payments = new EventPlus_Models_Payments();
            $payments = $oModels_Payments->getPayments($this->attendeeRow['id']);

            if (count($payments)) {
                foreach ($payments as $p => $paymentRow) {

                    $metaPayment = $oModels_Payments->getMethodMeta($paymentRow['txn_type']);
                    $paymentTitleStr = $paymentRow['txn_type'];

                    if (isset($metaPayment['title'])) {
                        $paymentTitleStr = $metaPayment['title'];
                    }

                    if ($metaPayment['logo'] != '') {
                        $paymentTitleStr = "<img src='" . EVENT_PLUS_PLUGIN_URL . 'assets/images/pm/' . $metaPayment['logo'] . "' alt='" . $metaPayment['title'] . "' />";
                    }

                    if ($paymentRow['txn_id'] != '' && $paymentRow['txn_id'] != null) {
                        echo '<tr>'
                        . '<td>' . __("Transaction Id", 'evrplus_language') . '</td><td>' . $paymentRow['txn_id'] . '</td>'
                        . '</tr>';
                    }

                    echo '<tr>'
                    . '<td>' . __("Payment Method", 'evrplus_language') . '</td><td>' . $paymentTitleStr . '</td>'
                    . '</tr>';
                }
            }
            echo '</table>';
        }
    }

    static function evrplus_registration_donation() {

        global $wpdb;

        $this->companyOptions = EventPlus_Models_Settings::getSettings();
        if (is_numeric($this->event_id)) {
            $event_id = $this->event_id;
        } else {
            $event_id = "0";
            echo "Failure - please retry!";
            exit;
        }
        if (is_numeric($this->attendee_id)) {
            $attendee_id = $this->attendee_id;
        } else {
            $attendee_id = "0";
            echo "Failure - please retry!";
            exit;
        }

        //Get Event Info
        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int) $event_id;
        $result = $wpdb->get_results($sql, ARRAY_A);

        foreach ($result as $row) {
            $event_id = $row['id'];

            $event_name = $row['event_name'];
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

        //Get Attendee Info
        $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE id=" . (int) $attendee_id . "' LIMIT 1";
        $row = $wpdb->get_row($sql, ARRAY_A);
        $attendee_id = $row['id'];
        $lname = $row ['lname'];
        $fname = $row ['fname'];
        $address = $row ['address'];
        $city = $row ['city'];
        $state = $row ['state'];
        $zip = $row ['zip'];
        $email = $row ['email'];
        $phone = $row ['phone'];
        $quantity = $row ['quantity'];
        $date = $row ['date'];
        $reg_type = $row['reg_type'];
        $ticket_order = unserialize($row['tickets']);
        $payment = $row['payment'];
        $event_id = $row['event_id'];
        $coupon = $row['coupon'];
        $attendee_name = $fname . " " . $lname;

//Get Donate Info
        if ($this->companyOptions['donations'] == "Yes") {
            $pay_now = "MAKE A DONATION";
        } elseif ($this->companyOptions['pay_now'] != "") {
            $pay_now = $this->companyOptions['pay_now'];
        } else {
            $pay_now = "";
        }
//Paypal 
        if ($this->companyOptions['payment_vendor'] == "PAYPAL") {

            $p = new EventPlus_Payments_Paypal(); // initiate an instance of the class
            if ($this->companyOptions['use_sandbox'] == "Y") {
                $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
                echo "<h3 style=\"color:#ff0000;\" title=\"Payments will not be processed\">Sandbox Mode Is Active</h3>";
            } else {
                $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
            }

            if (($payment == "0.00" || $payment == "0" || $payment == "" || $payment == " ") && ($this->companyOptions['donations'] == "Yes")) {
                $p->add_field('business', $this->companyOptions['payment_vendor_id']);
                $p->add_field('return', evrplus_permalink($this->companyOptions['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
                $p->add_field('cancel_return', evrplus_permalink($this->companyOptions['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
                $p->add_field('notify_url', evrplus_permalink($this->companyOptions['evrplus_page_id']) . 'id=' . $attendee_id . '&event_id=' . $event_id . '&action=paypal_txn');
                $p->add_field('cmd', '_donations');
                $p->add_field('item_name', 'Donation - ' . $event_name);
                $p->add_field('no_note', '0');
                $p->add_field('currency_code', $ticket_order[0]['ItemCurrency']);
                //Post variables
                $p->add_field('first_name', $fname);
                $p->add_field('last_name', $lname);
                $p->add_field('email', $email);
                $p->add_field('address1', $address);
                $p->add_field('city', $city);
                $p->add_field('state', $state);
                $p->add_field('zip', $zip);

                // Print the Order Verification to the screen.
                echo '<p align="left"><strong>' . __('Order details:', 'evrplus_language') . '</strong></p><table width="95%" border="0"><tr><td><strong>';
                _e('Event Name/Cost:', 'evrplus_language');
                echo '</strong></td><td>' . $event_name . ' - ' . $ticket_order[0]['ItemCurrency'] . ' ' . $payment . '</td></tr><tr><td><strong>';
                _e('Attendee Name:', 'evrplus_language');
                echo '</strong></td><td>' . $attendee_name . '</td></tr><tr><td><strong>';
                _e('Email Address:', 'evrplus_language');
                echo '</strong></td><td>' . $email . '</td></tr><tr><td><strong>';
                _e('Number of Attendees:', 'evrplus_language');
                echo '</strong></td><td>' . $quantity . '</td></tr><tr><td><strong>';
                _e('Order Details:', 'evrplus_language');
                echo '</strong></td><td>';
                $row_count = count($ticket_order);
                for ($row = 0; $row < $row_count; $row++) {
                    if ($ticket_order[$row]['ItemQty'] >= "1") {
                        echo $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " .
                        $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "<br \>";
                    }
                }

                echo '</td></tr>';
                if ($this->companyOptions['use_sales_tax'] == "Y") {
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ', 'evrplus_language');
                    echo ':  ' . $tax;
                    echo '</td></tr>';
                }

                echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
                echo '<td>' . $ticket_order[0]['ItemCurrency'] . '<strong>' . number_format($payment, 2) . '</strong></td></tr></table><br />';
                $p->submit(); // submit the fields to paypal

                if ($this->companyOptions['use_sandbox'] == "Y") {
                    $p->dump_fields(); // for debugging, output a table of all the fields
                }
            }
        }
        //End Paypal Donation Section
    }

    function get_details() {

        $event_id = $this->event_id;

        if ($this->eventRow['id'] <= 0) {
            _e('Invalid event - please retry!', 'evrplus_language');
            return;
        }

        $attendee_id = $this->attendee_id;

        if ($this->attendeeRow['id'] <= 0) {
            _e('Invalid registration - please retry!', 'evrplus_language');
            return;
        }


        $event_name = stripslashes($this->eventRow['event_name']);
        $event_location = $this->eventRow['event_location'];
        $event_address = $this->eventRow['event_address'];
        $event_city = $this->eventRow['event_city'];
        $event_postal = $this->eventRow['event_postal'];
        $reg_limit = $this->eventRow['reg_limit'];
        $start_time = $this->eventRow['start_time'];
        $end_time = $this->eventRow['end_time'];
        $start_date = $this->eventRow['start_date'];
        $end_date = $this->eventRow['end_date'];
        $use_coupon = $this->eventRow['use_coupon'];
        $coupon_code = $this->eventRow['coupon_code'];
        $coupon_code_price = $this->eventRow['coupon_code_price'];


        $lname = $this->attendeeRow ['lname'];
        $fname = $this->attendeeRow ['fname'];
        $address = $this->attendeeRow ['address'];
        $city = $this->attendeeRow ['city'];
        $state = $this->attendeeRow ['state'];
        $zip = $this->attendeeRow ['zip'];
        $email = $this->attendeeRow ['email'];
        $phone = $this->attendeeRow ['phone'];
        $quantity = $this->attendeeRow ['quantity'];
        $date = $this->attendeeRow ['date'];
        $reg_type = $this->attendeeRow['reg_type'];
        $ticket_order = unserialize($this->attendeeRow['tickets']);

        $tax = $this->attendeeRow['tax'];
        $payment = $this->attendeeRow['payment'];
        $coupon = $this->attendeeRow['coupon'];
        $token = $this->attendeeRow['token'];
        $attendee_name = $fname . " " . $lname;
        $row_count = count($ticket_order);

        // Print the Order Verification to the screen.

        if (isset($this->companyOptions['info_recieved']) && trim($this->companyOptions['info_recieved']) != '') {
            $oMail = new EventPlus_Helpers_Mail(array(
                'attendeeRow' => $this->attendeeRow,
                'eventRow' => $this->eventRow,
            ));

            $confirmation_message_str = html_entity_decode(stripslashes($this->companyOptions['info_recieved']));


            echo '<div class="col-xs-12">
            <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i> ' . $oMail->bindParams($confirmation_message_str) . '</div>
        </div>';
        }

        echo '<table width="100%" cellpadding="0" cellspacing="0" class="data-summary">'
        . '<thead>
                <tr>
                    <th colspan="2"><i class="fa fa-pencil"></i> ' . __('Order details', 'evrplus_language') . '</th>
                </tr>
            </thead>';

        echo '<tbody>';

        echo '<tr>';
        echo '<td><i class="fa fa-calculator"></i> ' . __('Event Name:', 'evrplus_language') . '</td>
                <td>' . $event_name . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . __('Attendee Name:', 'evrplus_language') . '</td>
                <td>' . $attendee_name . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . __('Email Address:', 'evrplus_language') . '</td>
                <td>' . $email . '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>' . __('Number of Attendees:', 'evrplus_language') . '</td>
                <td>' . $quantity . '</td>';
        echo '</tr>';

        if ($row_count > 0) {
            echo '<tr>'
            . '<td>';
            _e('Order Details:', 'evrplus_language');
            echo '</td>'
            . '<td>';

            for ($row = 0; $row < $row_count; $row++) {
                if ($ticket_order[$row]['ItemQty'] >= 1) {
                    echo $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " .
                    $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "<br/>";
                }
            }

            echo '</td></tr>';
        }

        echo '</tbody>';

        if ($this->companyOptions['use_sales_tax'] == "Y") {
            echo '<tr><td colspan="2"></td><td>';
            _e('Sales Tax  ', 'evrplus_language');
            echo ':  ' . $tax;
            echo '</td></tr>';
        }

        echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';

        if ($payment > 0) {
            echo '<td>' . $ticket_order[0]['ItemCurrency'] . ' <strong>' . number_format($payment, 2) . '</strong></td>';
        } else {
            echo '<td>' . $ticket_order[0]['ItemCurrency'] . ' <strong>' . $payment . '</strong></td>';
        }

        echo '</tr></table>';
    }

}
