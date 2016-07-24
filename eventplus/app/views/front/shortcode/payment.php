<?php
$attendee_id = $rowAttendee['id'];
$lname = $rowAttendee['lname'];
$fname = $rowAttendee['fname'];
$address = $rowAttendee['address'];
$city = $rowAttendee['city'];
$state = $rowAttendee['state'];
$zip = $rowAttendee['zip'];
$email = $rowAttendee['email'];
$phone = $rowAttendee['phone'];
$date = $rowAttendee['date'];
$payment = $rowAttendee['payment'];
$event_id = $rowAttendee['event_id'];
$quantity = $rowAttendee ['quantity'];
$reg_type = $rowAttendee['reg_type'];
$ticket_order = unserialize($rowAttendee['tickets']);
$coupon = $rowAttendee['coupon'];
$attendee_name = $fname . " " . $lname;

//$event_id = $rowEvent['id'];
$event_name = stripslashes($rowEvent['event_name']);
$event_desc = stripslashes($rowEvent['event_desc']);
$event_description = stripslashes($rowEvent['event_desc']);
$event_identifier = $rowEvent['event_identifier'];
$made_payments = count($payments);

echo "<br><br><strong>" . __('Payment Page for', 'evrplus_language') . " " . $fname . " " . $lname . " " . __('for event', 'evrplus_language') . " " . $event_name . "</strong><br><br>";
?>
<div class="evrplus_paymentable">
    <p align="left"><strong><?php _e('Registration Detail Summary:', 'evrplus_language'); ?></strong></p>
    <table width="100%">
        <tr class="odd">
            <td><strong><?php _e('Event Name/Cost:', 'evrplus_language'); ?></strong></td>
            <td><?php echo $event_name; ?> - <?php echo $ticket_order[0]['ItemCurrency']; ?><?php echo $payment; ?></td>
        </tr>
        <tr class="even">
            <td><strong><?php _e('Attendee Name:', 'evrplus_language'); ?></strong></td>
            <td><?php echo $attendee_name ?></td>
        </tr>
        <tr class="odd">
            <td><strong><?php _e('Email Address:', 'evrplus_language'); ?></strong></td>
            <td><?php echo $email ?></td>
        </tr>
        <tr class="even">
            <td><strong><?php _e('Number of Attendees:', 'evrplus_language'); ?></strong></td>
            <td><?php echo $quantity ?></td>
        </tr>
        <tr class="odd">
            <td><strong><?php _e('Order Details:', 'evrplus_language'); ?></strong></td>
            <td><?php
                $row_count = count($ticket_order);
                for ($row = 0; $row < $row_count; $row++) {
                    if ($ticket_order[$row]['ItemQty'] >= "1") {
                        echo $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " . $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "<br \>";
                    }
                }
                ?></td>
        </tr>
    </table>
</div>
<br />
<?php
if ($made_payments > 0) {
    $payment_made = "0";
    echo '<div class="evrplus_paymentable"><p align="left"><strong>';
    _e('Payments Received:', 'evrplus_language');
    echo "</strong></p>";

    echo '<table width="100%">';
    $color_row = 1;
    foreach ($payments as $rowPayment) {
        if ($color_row == 1) {
            ?> <tr class="odd"> <?php } else if ($color_row == 2) { ?> <tr class="even"> <?php
        }
        echo '<td>' . __('Payment', 'evrplus_language') . " " . $rowPayment['mc_currency'] . " " . $rowPayment['mc_gross'] . " " . $rowPayment['txn_type'] . " " . $rowPayment['txn_id'] . " (" . $rowPayment['payment_date'] . ")" . "</td></tr>";
        $payment_made = $payment_made + $rowPayment['mc_gross'];
        if ($color_row == 1) {
            $color_row = "2";
        } else if ($color_row == 2) {
            $color_row = "1";
        }
    }
    echo '</table></div><font color="red">';
    echo "<br/>";
    _e('Total Outstanding Payment Due*:', 'evrplus_language');
    $total_due = $payment - $payment_made;
    echo $ticket_order[0]['ItemCurrency'] . " " . $total_due;
    echo '</font><br/><br/>';
} else {
    echo '<font color="red">';
    _e('No Payments Received!', 'evrplus_language');
    echo "<br/>";
    _e('Total Payment Due*:', 'evrplus_language');
    $total_due = $payment;
    echo $ticket_order[0]['ItemCurrency'] . " " . $total_due;
    echo '</font><br/><br/>';
}


_e('*Payments could take several days to post to this page. Please check back in several days if you made a payment and your payment is not showing at this time.', 'evrplus_language');
echo "<br><br>";
//Set payment value for return payments
$payment = $total_due;
//Get Payment Info
if ($company_options['pay_now'] != "") {
    $pay_now = $company_options['pay_now'];
} else {
    $pay_now = _e('Pay Now');
}

if ($company_options['payment_vendor'] == "PAYPAL") {
    $p = new EventPlus_Vendor_Paypal(); // initiate an instance of the class
    if ($company_options['use_sandbox'] == "Y") {
        $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // testing paypal url
        echo "<h3 style=\"color:#ff0000;\" title=\"Payments will not be processed\">Sandbox Mode Is Active</h3>";
    } else {
        $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr'; // paypal url
    }

    if ($payment != "0.00" || $payment != "" || $payment != " ") {
        $p->add_field('business', $company_options['payment_vendor_id']);
        $p->add_field('return', evrplus_permalink($company_options['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
        //$p->add_field('cancel_return', evrplus_permalink($company_options['cancel_return']));
        $p->add_field('cancel_return', evrplus_permalink($company_options['return_url']) . '&id=' . $attendee_id . '&fname=' . $fname);
        //$p->add_field('notify_url', evrplus_permalink($company_options['notify_url']).'id='.$attendee_id.'&event_id='.$event_id.'&action=paypal_txn');
        $p->add_field('notify_url', evrplus_permalink($company_options['evrplus_page_id']) . 'id=' . $attendee_id . '&event_id=' . $event_id . '&action=paypal_txn');
        //$p->add_field('return', evrplus_permalink($company_options['return_url']));
        //$p->add_field('cancel_return', evrplus_permalink($company_options['cancel_return']));
        //$p->add_field('notify_url', evrplus_permalink($company_options['notify_url']).'id='.$attendee_id.'&event_id='.$event_id.'&attendee_action=post_payment&form_action=payment');
        //$p->add_field('notify_url', evrplus_permalink($company_options['evrplus_page_id']).'id='.$attendee_id.'&event_id='.$event_id.'&action=paypal_txn');
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
        $p->submit_paypal_post($pay_now); // submit the fields to paypal
        if ($company_options['use_sandbox'] == "Y") {
            $p->dump_fields(); // for debugging, output a table of all the fields 
        }
    }
}
//End Paypal Section
//
//Authorize.Net Payment Section
if ($company_options['payment_vendor'] == "AUHTHORIZE") {
    //Authorize.Net Payment 
    // This sample code requires the mhash library for PHP versions older than
    // 5.1.2 - http://hmhash.sourceforge.net/
    // the parameters for the payment can be configured here
    // the API Login ID and Transaction Key must be replaced with valid values

    $loginID = $company_options['authorize_id'];

    $transactionKey = $company_options['authorize_key'];

    $amount = $payment;

    $description = $rowEvent['event_name'] . ' | Reg. ID: ' . $attendee_id . ' | Name: ' . $rowAttendee['fname'] . " " . $rowAttendee['lname'] . ' | Total Registrants: ' . $rowAttendee['quantity'];

    $label = $pay_now; // The is the label on the 'submit' button

    if ($company_options['use_authorize_testmode'] == "Y") {
        $testMode = "true";
    }

    if ($company_options['use_authorize_testmode'] == "N") {
        $testMode = "false";
    }

    // By default, this sample code is designed to post to our test server for
    // developer accounts: https://test.authorize.net/gateway/transact.dll
    // for real accounts (even in test mode), please make sure that you are
    // posting to: https://secure.authorize.net/gateway/transact.dll

    $url = "https://secure.authorize.net/gateway/transact.dll";

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

    echo "	<input type='submit' value='$pay_now' />";

    echo "</FORM>";
}
//End Authorize.Net Section 
//GooglePay Payment Section
if ($company_options['payment_vendor'] == "GOOGLE") {
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
//Begin Monster Pay Section
if ($company_options['payment_vendor'] == "MONSTER") {
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
            

