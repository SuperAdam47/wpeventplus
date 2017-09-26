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

$responseCode = $_REQUEST['x_response_code'];
$invoiceNum = $_REQUEST['x_invoice_num'];
$invoiceNumParts = explode('-', $invoiceNum);
$txn_id = $_REQUEST['x_trans_id'];
$amount = $_REQUEST['x_amount'];
$x_MD5_Hash = $_REQUEST['x_MD5_Hash'];
$payer_email = $_REQUEST['x_email'];
$method = $_REQUEST['x_method']; //CC or ECHECK
$txn_description = $_REQUEST['x_description'];
$amountPaid = 0;

$attendee_id = (int) $invoiceNumParts[0];

$company_options = EventPlus_Models_Settings::getSettings();

$sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE id = '" . $attendee_id . "' LIMIT 1";
$attendeeRow = $wpdb->get_row($sql, ARRAY_A);

$isPending = EventPlus_Helpers_Token::isPending($attendeeRow['token']);
if ($isPending === false) {
    wp_die(__("Couldn't proceed! registration already processed.", 'evrplus_language'));
    return;
}

$sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int) $attendeeRow['event_id'] . " LIMIT 1";
$eventRow = $wpdb->get_row($sql, ARRAY_A);
if ($eventRow['id'] <= 0 || $attendeeRow['id'] <= 0) {
    wp_die(__("Invalid request - Payment couldn't be processed.", 'evrplus_language'));
}

$event_id = $eventRow['id'];

$payment_status = EventPlus_Models_Payments::PAYMENT_FAILED;
$mc_gross = 0;
if (intVal($responseCode) === 1) {
    $payment_status = EventPlus_Models_Payments::PAYMENT_SUCCESS;
    $amountPaid = $amount;
    $mc_gross = $amount;
}

$payment_date = date('Y-m-d G:i:s', time());

$wpdb->query($wpdb->prepare("UPDATE " . get_option('evr_attendee') . " SET payment_status = '" . esc_sql($payment_status) . "', amount_pd = '" . esc_sql($amountPaid) . "', payment_date = '" . esc_sql($payment_date) . "' WHERE id = %d", $attendeeRow['id']));

$sqlParams = array(
    'payer_id' => (int) $attendee_id,
    'event_id' => (int) $event_id,
    'payment_date' => $payment_date,
    'payer_email' => $payer_email,
    'txn_id' => $txn_id,
    'mc_gross' => $mc_gross,
    'memo' => $x_MD5_Hash,
    'payment_type' => 'full',
    'payment_status' => $payment_status,
    'txn_type' => EventPlus_Models_Payments::AUTHORIZE
);

$sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
$wpdb->insert(get_option('evr_payment'), $sqlParams, $sql_data);

EventPlus_Helpers_Token::delete($event_id);

$emailData = array(
    'payer_id' => $attendee_id,
    'attendee_id' => $attendee_id,
    'event_id' => $event_id,
    'payment_date' => $payment_date,
    'payment_status' => $payment_status,
    'txn_data' => array(
        "payer_email" => $payer_email,
        "amount" => $mc_gross,
        "txn_id" => $txn_id,
        'payment_status' => $payment_status,
        'payment_date' => $payment_date,
        'txn_type' => EventPlus_Models_Payments::AUTHORIZE
    )
);

$oEmailPayment = new EventPlus_Helpers_Mail_Payment($emailData);
$oEmailPayment->send();

$urlToGo = evrplus_permalink($company_options['evrplus_page_id']) . '?event_id=' . $event_id . '&action=confirmation&eventplus_token=' . $attendeeRow['token'];
echo'<script>window.location.href="' . $urlToGo . '";</script>';
exit;

