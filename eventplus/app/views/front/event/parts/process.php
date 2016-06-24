
    <img src="<?php echo $this->assetUrl('scripts/colorbox/images/loading.gif'); ?>" />
    <?php
    $num_people = 0;
    #For security purposes we serialized all form data on the confirmation page
    #this helps eliminate spam regisrations
    #We need to now convert it back to strings for posting to the database.
    $reg_form = unserialize(urldecode($_POST["reg_form"]));
    $qanda = unserialize(urldecode($_POST["questions"]));
    $attendee_array = $_POST['attendee'];
    #We added a session toaken to the confirmation page to eliminate double postings
    $submitted_token = isset($_POST['token']) ? $_POST['token'] : '0';
    #Make sure we are registering for a valid event
    $passed_event_id = $reg_form["event_id"];
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
    $attendee_sql = 'SELECT * FROM ' . get_option('evr_attendee') . " WHERE token='{$submitted_token}'";
    $attendee_result = $wpdb->get_results($attendee_sql, ARRAY_A);
    
    # Ideally there should be no records with the token, as it should be unique.  
    # If there are no records then we can add this record.
    $a = $wpdb->num_rows;

    //if (mysql_num_rows($attendee_result) == 0)
    if ($a == 0) {
        # Put all attendee data in an array for submission to the attendee database
        $sql = array('lname' => $reg_form['lname'], 'fname' => $reg_form['fname'], 'address' => $reg_form['address'], 'city' => $reg_form['city'],
            'state' => $reg_form['state'], 'zip' => $reg_form['zip'], 'reg_type' => $reg_form['reg_type'], 'email' => $reg_form['email'],
            'phone' => $reg_form['phone'], 'coupon' => $reg_form['coupon'], 'event_id' => $reg_form['event_id'], 'quantity' => $reg_form['num_people'],
            'tickets' => $reg_form['tickets'], 'payment' => $reg_form['payment'], 'tax' => $reg_form['tax'], 'attendees' => $attendee_list,
            'company' => $reg_form['company'], 'co_address' => $reg_form['co_add'], 'co_city' => $reg_form['co_city'], 'co_state' => $reg_form['co_state'],
            'co_zip' => $reg_form['co_zip'], 'token' => $submitted_token);
        # Define datatypes for submission to database, should be one for each field to post
        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
        #Post new attendee info to the Attendee Database
        $attendee_insert_sql_result = $wpdb->insert(get_option('evr_attendee'), $sql, $sql_data);
        # If attendee record posted to the database, then add the custom questions as well.
        if ($attendee_insert_sql_result) {
            # In order to post the custom, we need the id of the attendee we are posting for.
            $reg_id = $wpdb->insert_id;
            if (isset($reg_id)) {
                session_start();
                $_SESSION['send_email'] = 'no';
            }
            #Check our array of unserialized responses, if there are any begin posting to the answer database
            if (count($qanda) > "0") {
                $i = 0;
                do {
                    $question_id = $qanda[$i]['question'];
                    $response = $qanda[$i]["response"];
                    $wpdb->query("INSERT into " . get_option('evr_answer') . " (registration_id, question_id, answer)
                        	values ('$reg_id', '$question_id', '$response')");
                    ++$i;
                } while ($i < (count($qanda) + 1));
            }
        }
    } else {
        # If attendee record already existed in the database, get the id of the attendee for completing the registration process
        $attendee_row = $wpdb->get_results($attendee_sql, ARRAY_A);
        $reg_id = $attendee_row[0]['id'];
    }
    
    #Now that the attendee record has been posted and we have id, redirect to confirmation page.
    $url_to_goto = evrplus_permalink($company_options['evrplus_page_id']) . 'action=show_confirm_mess&event_id=' . $passed_event_id . '&amp;reg_id=' . $reg_id;
    echo '<meta http-equiv="refresh" content="0;url=' . $url_to_goto . '" />';