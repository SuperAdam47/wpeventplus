<?php

class EventPlus_Helpers_Payment {

    static function evrplus_registration_payment($passed_event_id, $passed_attendee_id) {
        global $wpdb;
        
        $company_options = EventPlus_Models_Settings::getSettings();
        
        if (is_numeric($passed_event_id)) {
            $event_id = $passed_event_id;
        } else {
            $event_id = "0";
            _e('Failure - please retry!', 'evrplus_language');
            return;
        }
        
        if (is_numeric($passed_attendee_id)) {
            $attendee_id = $passed_attendee_id;
        } else {
            $attendee_id = "0";
            _e('Failure - please retry!', 'evrplus_language');
            return;
        }
        
        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int)$event_id;
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
        
        $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE id=" . (int)$attendee_id;
        $result = $wpdb->get_results($sql, ARRAY_A);
        
        foreach ($result as $row) {
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
            $tax = $row['tax'];
            $payment = $row['payment'];
            $event_id = $row['event_id'];
            $coupon = $row['coupon'];
            $attendee_name = $fname . " " . $lname;
        }
        
        //Get Payment Info
        if ($company_options['pay_now'] != "") {
            $pay_now = $company_options['pay_now'];
        } else {
            $pay_now = "PAY NOW";
        }
        
        if ($company_options['payment_vendor'] == "" || $company_options['payment_vendor'] == "NONE") {
            // Print the Order Verification to the screen.
            if ($company_options['pay_msg'] != "") {
                //echo stripslashes($company_options['pay_msg']);
            } else {
                _e("To pay online, please select the Payment button to be taken to our payment vendor's site.", 'evrplus_language');
            }
            
            echo '<br/>';
            echo "Reference " . $event_name . " ID: " . $event_id . "<br/>";
            echo '<br/>';
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
            if ($company_options['use_sales_tax'] == "Y") {
                echo '<tr><td></td><td>';
                _e('Sales Tax  ', 'evrplus_language');
                echo ':  ' . $tax;
                echo '</td></tr>';
            }
            echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
            echo '<td>' . $ticket_order[0]['ItemCurrency'] . " " . '<strong>' . number_format($payment, 2) . '</strong></td></tr></table><br />';
        }
//Paypal 
        if ($company_options['payment_vendor'] == "PAYPAL") {
            $p = new EventPlus_Vendor_Paypal(); // initiate an instance of the class
            if ($company_options['use_sandbox'] == "Y") {
                $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
                echo '<h3 style="color:#ff0000;" title="' . __('Payments will not be processed', 'evrplus_language') . '">' . __('Sandbox Mode Is Active', 'evrplus_language') . '</h3>';
            } else {
                $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
            }
            
            if ($payment != "0.00" || $payment != "0" || $payment != "" || $payment != " ") {
                $p->add_field('business', $company_options['payment_vendor_id']);
                $p->add_field('return', evrplus_permalink($company_options['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
                $p->add_field('cancel_return', evrplus_permalink($company_options['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
                $p->add_field('notify_url', evrplus_permalink($company_options['evrplus_page_id']) . 'id=' . $attendee_id . '&event_id=' . $event_id . '&action=paypal_txn');
                $p->add_field('item_name', $event_name . ' | Reg. ID: ' . $attendee_id . ' | Name: ' . $attendee_name . ' | Total Registrants: ' . $quantity);
                $p->add_field('amount', $payment);
                $p->add_field('currency_code', $ticket_order[0]['ItemCurrency']);
                
                //Post variables
                $p->add_field('first_name', $fname);
                $p->add_field('last_name', $lname);
                $p->add_field('email', $email);
                $p->add_field('address1', $address);
                $p->add_field('city', $city);
                $p->add_field('state', $state);
                $p->add_field('zip', $zip);
                
                if ($company_options['pay_msg'] != "") {
                    //echo stripslashes($company_options['pay_msg']);
                } else {
                    _e("To pay online, please select the Payment button to be taken to our payment vendor's site.", 'evrplus_language');
                }
                
                echo '<br/>';
                
                $p->submit_paypal_post($pay_now); // submit the fields to paypal
                
                echo '<p align="left"><strong>' . __('Order details:', 'evrplus_language') . '</strong></p>'
                        . '<table width="95%" border="0"><tr><td><strong>';
                _e('Event Name/Cost:', 'evrplus_language');
                echo '</strong></td><td>' . $event_name . ' - ' . $ticket_order[0]['ItemCurrency'] . ' ' . $payment . '</td></tr>'
                        . '<tr><td><strong>';
                _e('Attendee Name:', 'evrplus_language');
                echo '</strong></td><td>' . $attendee_name . '</td></tr>'
                        . '<tr><td><strong>';
                _e('Email Address:', 'evrplus_language');
                echo '</strong></td><td>' . $email . '</td></tr>'
                        . '<tr><td><strong>';
                _e('Number of Attendees:', 'evrplus_language');
                echo '</strong></td><td>' . $quantity . '</td></tr>'
                        . '<tr><td><strong>';
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
                
                if ($company_options['use_sales_tax'] == "Y") {
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ', 'evrplus_language');
                    echo ':  ' . $tax;
                    echo '</td></tr>';
                }
                
                echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
                echo '<td>' . $ticket_order[0]['ItemCurrency'] . ' <strong>' . number_format($payment, 2) . '</strong></td></tr></table><br />';
                if ($company_options['use_sandbox'] == "Y") {
                    $p->dump_fields(); // for debugging, output a table of all the fields
                }
            }
        }
        
        //End Paypal Section
        if ($company_options['payment_vendor'] == "STRIPEACTIVE") {//get_option('evr_stripe') == 'STRIPEACTIVE' && $company_options['payment_vendor']!='PAYPAL'){
            $p = new EventPlus_Vendor_Paypal(); // initiate an instance of the class
            

            if ($payment != "0.00" || $payment != "0" || $payment != "" || $payment != " ") {
                $p->add_field('business', $company_options['payment_vendor_id']);
                $p->add_field('return', evrplus_permalink($company_options['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
                $p->add_field('cancel_return', evrplus_permalink($company_options['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
                //$p->add_field('notify_url', evrplus_permalink($company_options['evrplus_page_id']) . 'id=' . $attendee_id . '&event_id=' . $event_id . '&action=paypal_txn');
                $p->add_field('item_name', $event_name . ' | Reg. ID: ' . $attendee_id . ' | Name: ' . $attendee_name . ' | Total Registrants: ' . $quantity);
                $p->add_field('amount', $payment);
                $p->add_field('currency_code', $ticket_order[0]['ItemCurrency']);
                //Post variables
                $p->add_field('first_name', $fname);
                $p->add_field('last_name', $lname);
                $p->add_field('email', $email);
                $p->add_field('address1', $address);
                $p->add_field('city', $city);
                $p->add_field('state', $state);
                $p->add_field('zip', $zip);
                
                _e("To pay online, please select the Payment button to be taken to our payment vendor's site.", 'evrplus_language');
                
                echo '<br/>';
                
                $p->submit_paypal_post1($pay_now); // submit the fields to paypal
                
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
                if ($company_options['use_sales_tax'] == "Y") {
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ', 'evrplus_language');
                    echo ':  ' . $tax;
                    echo '</td></tr>';
                }
                
                echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
                echo '<td>' . $ticket_order[0]['ItemCurrency'] . ' <strong>' . number_format($payment, 2) . '</strong></td></tr></table><br />';
              
            }

//echo get_option('evr_stripe'); die;
        }
//END STRIPE
//Authorize.Net Payment Section
        
        if ($company_options['payment_vendor'] == "AUTHORIZE") {
//Authorize.Net Payment 
            // This sample code requires the mhash library for PHP versions older than
            // 5.1.2 - http://hmhash.sourceforge.net/
            // the parameters for the payment can be configured here
            // the API Login ID and Transaction Key must be replaced with valid values
            //$loginID		= $company_options['authorize_id'];
            //$transactionKey = $company_options['authorize_key'];
            $amount = $payment;
            $description = $event_name . ' | Reg. ID: ' . $attendee_id . ' | Name: ' . $attendee_name . ' | Total Registrants: ' . $quantity;
            $label = $pay_now; // The is the label on the 'submit' button
            // By default, this sample code is designed to post to our test server for
            // developer accounts: https://test.authorize.net/gateway/transact.dll
            // for real accounts (even in test mode), please make sure that you are
            // posting to: https://secure.authorize.net/gateway/transact.dll
            
            $loginID = $company_options['authorize_id'];
            $transactionKey = $company_options['authorize_key'];
             $url = "https://secure.authorize.net/gateway/transact.dll";
            $testMode = "false";
             
            if ($company_options['use_authorize_sandbox'] == "Y") {
            $url = "https://test.authorize.net/gateway/transact.dll";
                $testMode = "true";
            }
            
            // If an amount or description were posted to this page, the defaults are overidden
            if ($_REQUEST["amount"]) {
                $amount = $_REQUEST["amount"];
            }
            if ($_REQUEST["description"]) {
                $description = $_REQUEST["description"];
            }
            // an invoice is generated using the date and time
            $invoice = date(YmdHis);
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
            
            if ($company_options['pay_msg'] != "") {
                //echo stripslashes($company_options['pay_msg']);
            } else {
                _e("To pay online, please select the Payment button to be taken to our payment vendor's site.", 'evrplus_language');
            }
            
            echo '<br/>';
            
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
            
            if ($company_options['use_sales_tax'] == "Y") {
                echo '<tr><td></td><td>';
                _e('Sales Tax  ', 'evrplus_language');
                echo ':  ' . $tax;
                echo '</td></tr>';
            }
            
            echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
            echo '<td>' . $ticket_order[0]['ItemCurrency'] . '<strong>' . number_format($payment, 2) . '</strong></td></tr></table><br />';
            $ipn_url = evrplus_permalink($company_options['evrplus_page_id']) . 'id=' . $attendee_id . '&event_id=' . $event_id . '&action=authorize_txn';
            
            // Create the HTML form containing necessary SIM post values
            echo "<FORM method='post' action='$url' >";
            // Additional fields can be added here as outlined in the SIM integration guide
            // at: http://developer.authorize.net
            echo "	<INPUT type='hidden' name='x_login' value='$loginID' />";
            if ($price == "0") {
                echo "Enter Amount $<INPUT type='text' name='x_amount' value='10.00' />";
            } else {
                echo "	<INPUT type='hidden' name='x_amount' value='$amount' />";
            }
            
            echo "	<INPUT type='hidden' name='x_description' value='$description' />";
            echo "	<INPUT type='hidden' name='x_invoice_num' value='$invoice' />";
            echo "	<INPUT type='hidden' name='x_fp_sequence' value='$sequence' />";
            echo "	<INPUT type='hidden' name='x_fp_timestamp' value='$timeStamp' />";
            echo "	<INPUT type='hidden' name='x_fp_hash' value='$fingerprint' />";
            echo "	<INPUT type='hidden' name='x_test_request' value='$testMode' />";
            echo "	<INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM' />";
            echo "	<INPUT type='hidden' name='x_Relay_URL' value='$ipn_url' />";
            echo "	<input type='submit' value='$label' />";
            echo "</FORM>";
// This is the end of the code generating the "submit payment" button.    -->
        }
//End Authorize.Net Section 
//
//GooglePay Payment Section
        if ($company_options['payment_vendor'] == "GOOGLE") {
            // Print the Order Verification to the screen.
            if ($company_options['pay_msg'] != "") {
                //echo $company_options['pay_msg'];
            } else {
                _e("To pay online, please select the Payment button to be taken to our payment vendor's site.", 'evrplus_language');
            }
            echo '<br/>';
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
            
            if ($company_options['use_sales_tax'] == "Y") {
                echo '<tr><td></td><td>';
                _e('Sales Tax  ', 'evrplus_language');
                echo ':  ' . $tax;
                echo '</td></tr>';
            }
            echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
            echo '<td>' . $ticket_order[0]['ItemCurrency'] . '<strong>' . number_format($payment, 2) . '</strong></td></tr></table><br />';
            // Create the HTML Payment Button
            //Google Payment Button
            ?>
            <form action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/<?php echo $company_options['payment_vendor_id']; ?>" id="BB_BuyButtonForm" method="post" name="BB_BuyButtonForm" target="_top">
                <input name="item_name_1" type="hidden" value="<?php echo $event_name . "-" . $attendee_name; ?>"/>
                <input name="item_description_1" type="hidden" value="<?php echo $event_name . ' | Reg. ID: ' . $attendee_id . ' | Name: ' . $attendee_name . ' | Total Registrants: ' . $quantity; ?>"/>
                <input name="item_quantity_1" type="hidden" value="1"/>
                <input name="item_price_1" type="hidden" value="<?php echo $payment; ?>"/>
                <input name="item_currency_1" type="hidden" value="<?php echo $ticket_order[0]['ItemCurrency']; ?>"/>
                <input name="_charset_" type="hidden" value="utf-8"/>
                <input alt="" src="https://checkout.google.com/buttons/buy.gif?merchant_id=<?php echo $company_options['payment_vendor_id']; ?>&amp;w=117&amp;h=48&amp;style=trans&amp;variant=text&amp;loc=en_US" type="image"/>
            </form>
            <?php
        }
//End Google Pay Section
//
//Begin Monster Pay Section
        if ($company_options['payment_vendor'] == "MONSTER") {
            // Print the Order Verification to the screen.
            if ($company_options['pay_msg'] != "") {
                //echo $company_options['pay_msg'];
            } else {
                _e("To pay online, please select the Payment button to be taken to our payment vendor's site.", 'evrplus_language');
            }
            echo '<br/>';
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
            if ($company_options['use_sales_tax'] == "Y") {
                echo '<tr><td></td><td>';
                _e('Sales Tax  ', 'evrplus_language');
                echo ':  ' . $tax;
                echo '</td></tr>';
            }
            echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
            echo '<td>' . $ticket_order[0]['ItemCurrency'] . '<strong>' . number_format($payment, 2) . '</strong></td></tr></table><br />';
//End Verification
//Display Payment Button
            ?>    
            <form action="https://www.monsterpay.com/secure/index.cfm" method="POST" enctype="APPLICATION/X-WWW-FORM-URLENCODED" target="_BLANK">
                <input type="hidden" name="ButtonAction" value="buynow">
                <input type="hidden" name="MerchantIdentifier" value="<?php echo $company_options['payment_vendor_id']; ?>">
                <input type="hidden" name="LIDDesc" value="<?php echo $event_name . ' | Reg. ID: ' . $attendee_id . ' | Name: ' . $attendee_name . ' | Total Registrants: ' . $quantity; ?>">
                <input type="hidden" name="LIDSKU" value="<?php echo $event_name . "-" . $attendee_name; ?>">
                <input type="hidden" name="LIDPrice" value="<?php echo $payment; ?>">
                <input type="hidden" name="LIDQty" value="1">
                <input type="hidden" name="CurrencyAlphaCode" value="<?php echo $ticket_order[0]['ItemCurrency']; ?>">
                <input type="hidden" name="ShippingRequired" value="0">
                <input type="hidden" name="MerchRef" value="">
                <input type="submit" value="<?php echo $pay_now; ?>" style="background-color: #DCDCDC; font-family: Arial; font-size: 11px; color: #000000; font-weight: bold; border: 1px groove #000000;">
            </form> 
            <?php
        }
//End Monster Pay Section
    }

    static function evrplus_registration_donation($passed_event_id, $passed_attendee_id) {

        global $wpdb;
        $company_options = EventPlus_Models_Settings::getSettings();
        if (is_numeric($passed_event_id)) {
            $event_id = $passed_event_id;
        } else {
            $event_id = "0";
            echo "Failure - please retry!";
            exit;
        }
        if (is_numeric($passed_attendee_id)) {
            $attendee_id = $passed_attendee_id;
        } else {
            $attendee_id = "0";
            echo "Failure - please retry!";
            exit;
        }
        
        //Get Event Info
        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int)$event_id;
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
        $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE id=" .(int) $attendee_id;
        $result = $wpdb->get_results($sql, ARRAY_A);
        foreach ($result as $row) {
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
        }
//Get Donate Info
        if ($company_options['donations'] == "Yes") {
            $pay_now = "MAKE A DONATION";
        } elseif ($company_options['pay_now'] != "") {
            $pay_now = $company_options['pay_now'];
        } else {
            $pay_now = "PAY NOW";
        }
//Paypal 
        if ($company_options['payment_vendor'] == "PAYPAL") {
            $p = new EventPlus_Vendor_Paypal(); // initiate an instance of the class
            if ($company_options['use_sandbox'] == "Y") {
                $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
                echo "<h3 style=\"color:#ff0000;\" title=\"Payments will not be processed\">Sandbox Mode Is Active</h3>";
            } else {
                $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
            }
            if (($payment == "0.00" || $payment == "0" || $payment == "" || $payment == " ") && ($company_options['donations'] == "Yes")) {
                $p->add_field('business', $company_options['payment_vendor_id']);
                $p->add_field('return', evrplus_permalink($company_options['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
                $p->add_field('cancel_return', evrplus_permalink($company_options['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
                $p->add_field('notify_url', evrplus_permalink($company_options['evrplus_page_id']) . 'id=' . $attendee_id . '&event_id=' . $event_id . '&action=paypal_txn');
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
                if ($company_options['use_sales_tax'] == "Y") {
                    echo '<tr><td></td><td>';
                    _e('Sales Tax  ', 'evrplus_language');
                    echo ':  ' . $tax;
                    echo '</td></tr>';
                }
                
                echo '<tr><td><strong>' . __('Total Cost:', 'evrplus_language') . '</strong></td>';
                echo '<td>' . $ticket_order[0]['ItemCurrency'] . '<strong>' . number_format($payment, 2) . '</strong></td></tr></table><br />';
                $p->submit_paypal_post($pay_now); // submit the fields to paypal
                
                if ($company_options['use_sandbox'] == "Y") {
                    $p->dump_fields(); // for debugging, output a table of all the fields
                }
            }
        }
        //End Paypal Donation Section
    }

}
