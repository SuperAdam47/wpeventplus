<?php
$currentDir = __DIR__;
$dirParts = explode('wp-content', $currentDir);
$wpDir = $dirParts[0] . DIRECTORY_SEPARATOR;

if (file_exists($wpDir.'wp-config.php') == false) {
    echo('Bad Request');
    exit;
}

include_once($wpDir.'wp-load.php');
include_once($wpDir.'wp-config.php');
include_once($wpDir.'wp-includes/wp-db.php');

global $wpdb;

if (isset($_REQUEST['eventplus_token']) == false) {
    echo("Invalid paypal request.");
    exit;
}

$eventplus_token = $_REQUEST['eventplus_token'];

$company_options = EventPlus_Models_Settings::getSettings();

$sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE token = '" . esc_sql($eventplus_token) . "' LIMIT 1";
$attendeeRow = $wpdb->get_row($sql, ARRAY_A);

$sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int) $attendeeRow['event_id'] . " LIMIT 1";
$eventRow = $wpdb->get_row($sql, ARRAY_A);
if ($eventRow['id'] <= 0) {
    echo(__("Invalid request", 'evrplus_language'));
    exit;
}

if($attendeeRow['payment_status'] == EventPlus_Models_Payments::PAYMENT_SUCCESS){
    echo(__("Already processed", 'evrplus_language'));
    exit;
}

$event_id = $eventRow['id'];

$output = EventPlus::dispatch('front_event_parts_paypal/ipn');