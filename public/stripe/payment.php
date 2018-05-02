<?php

$currentDir = __DIR__;
$dirParts = explode('wp-content', $currentDir);
$wpDir = $dirParts[0] . DIRECTORY_SEPARATOR;

if (file_exists($wpDir . 'wp-config.php') == false) {
    die('Bad Request');
}

include_once($wpDir . 'wp-load.php');
include_once($wpDir . 'wp-config.php');
include_once($wpDir . 'wp-includes/wp-db.php');

global $wpdb;

if( isset($_POST['stripeToken']) == false ) {
    die("Invalid request.");
}

$stripeToken = $_POST['stripeToken'];
$amount = $_POST['amount'];
$eventplus_token = $_POST['token'];
$stripeEmail = $_POST['stripeEmail'];
$stripeTokenType = $_POST['stripeTokenType'];

$price = $amount * 100;
$company_options = EventPlus_Models_Settings::getSettings();

$isPending = EventPlus_Helpers_Token::isPending($eventplus_token);
if ($isPending === false) {
    wp_die(__("Couldn't proceed! registration already processed.", 'evrplus_language'));
    return;
}

$sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE token = '" . esc_sql($eventplus_token) . "' LIMIT 1";
$attendeeRow = $wpdb->get_row($sql, ARRAY_A);

$sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int) $attendeeRow['event_id'] . " LIMIT 1";
$eventRow = $wpdb->get_row($sql, ARRAY_A);
if ($eventRow['id'] <= 0 || trim($company_options['secret_key']) == '') {
    wp_die(__("Invalid request - Payment couldn't be processed.", 'evrplus_language'));
}

$event_id = $eventRow['id'];

$payment_status = EventPlus_Models_Payments::PAYMENT_FAILED;
$amountPaid = 0;
$txn_id = '';
$payment_date = date('Y-m-d G:i:s', time());

try {

    require_once('Stripe/lib/Stripe.php');
    Stripe::setApiKey($company_options['secret_key']);

    $currency = $company_options['default_currency'];
    $currency = trim($currency);
    if (strlen($currency) < 3 || $currency == '') {
        $currency = 'USD';
    }

    $oCharge = Stripe_Charge::create(array(
                "amount" => $price,
                "currency" => $currency,
                "card" => $stripeToken,
                "receipt_email" => $attendeeRow['email'],
                "description" => '[' . $eventRow['id'] . '] ' . $eventRow['event_name'] . '  - Payment',
                "metadata" => array("registration_id" => $attendeeRow['id'])
    ));

    $stripeToken = $_POST['stripeToken'];
    $stripeTokenType = $_POST['stripeTokenType'];
    $stripeEmail = $_POST['stripeEmail'];

    $txn_id = $oCharge->id;

    if ($oCharge->paid == true) {
        $payment_status = EventPlus_Models_Payments::PAYMENT_SUCCESS;
        $amountPaid = $amount;
    }
} catch (Exception $e) {

    $payment_status = EventPlus_Models_Payments::PAYMENT_FAILED;
    $amountPaid = 0;
}

$wpdb->query($wpdb->prepare("UPDATE " . get_option('evr_attendee') . " SET payment_status = '" . esc_sql($payment_status) . "', amount_pd = '" . esc_sql($amountPaid) . "', payment_date = '" . esc_sql($payment_date) . "' WHERE id = %d", $attendeeRow['id']));

$sqlParams = array(
    'payer_id' => $attendeeRow['id'],
    'event_id' => $event_id,
    'payment_date' => $payment_date,
    'payer_email' => $stripeEmail,
    'txn_id' => $txn_id,
    'mc_gross' => $amountPaid,
    'payment_type' => 'full',
    'payment_status' => $payment_status,
    'txn_type' => EventPlus_Models_Payments::STRIPE
);


$sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
$wpdb->insert(get_option('evr_payment'), $sqlParams, $sql_data);

EventPlus_Helpers_Token::delete($event_id);

$emailData = array(
    'payer_id' => $attendeeRow['id'],
    'attendee_id' => $attendeeRow['id'],
    'event_id' => $event_id,
    'payment_date' => $payment_date,
    'payment_status' => $payment_status,
    'txn_data' => array(
        "payer_email" => $stripeEmail,
        "amount" => $amountPaid,
        "txn_id" => $txn_id,
        'payment_status' => $payment_status,
        'payment_date' => $payment_date,
        'txn_type' => EventPlus_Models_Payments::STRIPE
    )
);

$oEmailPayment = new EventPlus_Helpers_Mail_Payment($emailData);
$oEmailPayment->send();


$urlToGo = evrplus_permalink($company_options['evrplus_page_id']) . '?event_id=' . $event_id . '&action=confirmation&eventplus_token=' . $attendeeRow['token'];
echo'<script>window.location.href="' . $urlToGo . '";</script>';
exit;

