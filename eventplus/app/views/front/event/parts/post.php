<img src="<?php echo esc_url( $this->assetUrl('scripts/colorbox/images/loading.gif') ); ?>" />

<?php
$num_people = 0;

#For security purposes we serialized all form data on the confirmation page
#this helps eliminate spam regisrations
#We need to now convert it back to strings for posting to the database.
$reg_form = unserialize(urldecode($_POST["reg_form"]));
$questionsResponse = unserialize(urldecode($_POST["questions"]));
$attendee_array = $_POST['attendee'];

#We added a session toaken to the confirmation page to eliminate double postings
$eventplus_token = isset($_POST['eventplus_token']) ? $_POST['eventplus_token'] : '';

$isPending = EventPlus_Helpers_Token::isPending($eventplus_token);
if ($isPending === false || $eventplus_token === '') {
    _e("Couldn't proceed! registration already processed.", 'evrplus_language');
    return;
}

#Make sure we are registering for a valid event
$passed_event_id = $reg_form["event_id"];
$event_id = 0;
if (is_numeric($passed_event_id) && $passed_event_id > 0) {
    $event_id = $passed_event_id;
} else {
    echo "Failure - please retry!";
    return;
}

#Grab field data needed later    
$ticket_array = unserialize($reg_form['tickets']);
$attendee_list = serialize($attendee_array);
$business = serialize($company_options);

# Start check to see if guest was already inserted earlier
$attendee_sql = 'SELECT * FROM ' . get_option('evr_attendee') . " WHERE token='" . esc_sql($eventplus_token) . "' AND event_id = '" . (int) $event_id . "' LIMIT 1";
$attendee_result = $wpdb->get_results($attendee_sql, ARRAY_A);
$attendee_row = array();
if(!empty($attendee_result)){
	$attendee_row = $attendee_result[0];
}

# Ideally there should be no records with the token, as it should be unique.  
# If there are no records then we can add this record. 
$count = $wpdb->num_rows;

$update_id = 0;
if ($count > 0 && $attendee_row['id'] > 0) {
    $update_id = array('id' => $attendee_row['id']);
    //$wpdb->query("DELETE FROM " . get_option('evr_attendee') . " WHERE token= '" . esc_sql($eventplus_token) . "' AND event_id = '" . (int) $event_id . "'");
    $wpdb->query("DELETE FROM " . get_option('evr_answer') . " WHERE registration_id = '" . (int) $attendee_row['id'] . "'");
}


$payment_status = '';
if ($reg_form['payment'] <= 0 && $reg_form['reg_type'] == 'RGLR') {
    $payment_status = EventPlus_Models_Payments::PAYMENT_SUCCESS;
}

if ($reg_form['discount'] <= 0) {
    $reg_form['discount'] = 0;
}

if ($reg_form['discount_percentage'] <= 0) {
    $reg_form['discount_percentage'] = 0;
}

# Put all attendee data in an array for submission to the attendee database
$sql = array('lname' => $reg_form['lname'], 'fname' => $reg_form['fname'], 'address' => $reg_form['address'], 'city' => $reg_form['city'],
    'state' => $reg_form['state'], 'zip' => $reg_form['zip'], 'reg_type' => $reg_form['reg_type'], 'email' => $reg_form['email'],
    'phone' => $reg_form['phone'], 'coupon' => $reg_form['coupon'], 'event_id' => $reg_form['event_id'], 'quantity' => $reg_form['num_people'],
    'tickets' => $reg_form['tickets'], 'payment' => $reg_form['payment'], 'tax' => $reg_form['tax'], 'attendees' => $attendee_list,
    'company' => $reg_form['company'], 'co_address' => $reg_form['co_add'], 'co_city' => $reg_form['co_city'], 'co_state' => $reg_form['co_state'],
    'co_zip' => $reg_form['co_zip'], 'token' => $eventplus_token, 'payment_status' => $payment_status,
    'order_total' => $reg_form['order_total'],
    'discount_percentage' => intVal($reg_form['discount_percentage']),
    'discount_amount' => $reg_form['discount'],
);

# Define datatypes for submission to database, should be one for each field to post
$sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

$attendee_insert_result = false;
if (is_array($update_id)) {
    if ($wpdb->update(get_option('evr_attendee'), $sql, $update_id) === false) {
        $attendee_insert_result = false;
    }else{
        $attendee_insert_result = true;
    }
} else {
    #Post new attendee info to the Attendee Database
    $attendee_insert_result = $wpdb->insert(get_option('evr_attendee'), $sql, $sql_data);
}

$is_email_sent = 0;
# If attendee record posted to the database, then add the custom questions as well.
if ($attendee_insert_result) {

    # In order to post the custom, we need the id of the attendee we are posting for.
    $reg_id = $wpdb->insert_id;
    if(!empty($attendee_row) && $attendee_row['id'] > 0){
        $reg_id = $attendee_row['id'];
    }
    


    #Check our array of unserialized responses, if there are any begin posting to the answer database
    if (count($questionsResponse) > 0) {
        $i = 0;
        do {
            $question_id = $questionsResponse[$i]['question'];
            $response = $questionsResponse[$i]["response"];
            $wpdb->query("INSERT into " . get_option('evr_answer') . " (registration_id, question_id, answer)
                        	values ('" . (int) $reg_id . "', '" . (int) $question_id . "', '" . esc_sql($response) . "')");
            ++$i;
        } while ($i < (count($questionsResponse) + 1));
    }

    $oEmailReigstration = new EventPlus_Helpers_Mail_Registration(array(
        'event_id' => $event_id,
        'attendee_id' => $reg_id,
    ));

    $emailSent = $oEmailReigstration->send();

    if ($emailSent) {
        $is_email_sent = 1;
    }
}

EventPlus_Helpers_Token::delete($event_id);

#Now that the attendee record has been posted and we have id, redirect to confirmation page.
$url_to_goto = evrplus_permalink($company_options['evrplus_page_id']) . 'action=confirmation&event_emr=' . md5($is_email_sent) . '&event_id=' . $passed_event_id . '&eventplus_token=' . $eventplus_token;
echo '<meta http-equiv="refresh" content="0;url=' . $url_to_goto . '" />';
exit;
