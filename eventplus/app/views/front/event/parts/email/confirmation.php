<?php

$payment_link = evrplus_permalink($company_options['evrplus_page_id']) . "?action=confirmation&eventplus_token=" . $eventplus_token . "&event_id=" . $event_id;

//Send Confirmation Email   
//Select the default message
if ($company_options['send_confirm'] == "Y") {
    if ($send_mail == "Y" && $conf_mail != '') {
        $confirmation_email_body = $conf_mail;
    } else {
        $confirmation_email_body = $company_options['message'];
    }

    if (count($attendee_array) > "0") {
        $attendee_names = "";
        $i = 0;
        do {
            $attendee_names .= $attendee_array[$i]["first_name"] . " " . $attendee_array[$i]['last_name'] . ",";
            ++$i;
        } while ($i < count($attendee_array));
    }

    $row_count = count($ticket_array);
    $ticket_list = "";
    for ($row = 0; $row < $row_count; $row++) {
        if ($ticket_array[$row]['ItemQty'] >= "1") {
            $ticket_list .= $ticket_array[$row]['ItemQty'] . " " . $ticket_array[$row]['ItemCat'] . "-" . $ticket_array[$row]['ItemName'] . " " . $ticket_array[$row]['ItemCurrency'] . " " . $ticket_array[$row]['ItemCost'] . "<br \>";
        }
    }

    //search and replace tags
    $SearchValues = array("[id]", "[fname]", "[lname]", "[phone]",
        "[address]", "[city]", "[state]", "[zip]", "[email]",
        "[event]", "[description]", "[cost]", "[currency]",
        "[contact]", "[coordinator]", "[company]", "[co_add1]", "[co_add2]",
        "[co_city]", "[co_state]", "[co_zip]",
        "[payment_url]", "[start_date]", "[start_time]", "[end_date]", "[end_time]",
        "[num_people]", "[attendees]", "[tickets]");

    $ReplaceValues = array($reg_id, $reg_form['fname'], $reg_form['lname'], $reg_form['phone'],
        $reg_form['address'], $reg_form['city'], $reg_form['state'], $reg_form['zip'],
        $reg_form['email'],
        $event_name, $event_desc, $reg_form['payment'], $company_options['default_currency'],
        $company_options['company_email'], $coord_email, stripslashes($company_options['company']),
        $company_options['company_street1'], $company_options['company_street2'],
        $company_options['company_city'], $company_options['company_state'],
        $company_options['company_postal'],
        $payment_link, $start_date, $start_time, $end_date, $end_time,
        $reg_form['quantity'], $attendee_names, $ticket_list);

    $email_content = str_replace($SearchValues, $ReplaceValues, $confirmation_email_body);
    $message_top = "<html><body>";
    $message_bottom = "</html></body>";
    if ($company_options['wait_message'] != "") {
        $wait_message = $company_options['wait_message'];
    } else {
        $wait_message = '<font color="red"><p>' . __("Thank you for registering for", 'evrplus_language') . " " . $event_name . ". " . __("At this time, all seats for the event have been taken.  
        Your information has been placed on our waiting list.  
        The waiting list is on a first come, first serve basis.  
        You will be notified by email should a seat become available.", 'evrplus_language') . '</p><p>' . __("Thank You", 'evrplus_language') . '</p></font>';
    }

    $SearchValues = array("[id]", "[fname]", "[lname]", "[phone]",
        "[address]", "[city]", "[state]", "[zip]", "[email]",
        "[event]", "[description]", "[cost]", "[currency]",
        "[contact]", "[coordinator]", "[company]", "[co_add1]", "[co_add2]", "[co_city]", "[co_state]", "[co_zip]",
        "[payment_url]", "[start_date]", "[start_time]", "[end_date]", "[end_time]",
        "[num_people]", "[attendees]", "[tickets]");

    $ReplaceValues = array($reg_id, $reg_form['fname'], $reg_form['lname'], $reg_form['phone'],
        $reg_form['address'], $reg_form['city'], $reg_form['state'], $reg_form['zip'], $reg_form['email'],
        $event_name, $event_desc, $reg_form['payment'], $company_options['default_currency'],
        $company_options['company_email'], $coord_email, stripslashes($company_options['company']),
        $company_options['company_street1'], $company_options['company_street2'], $company_options['company_city'],
        $company_options['company_state'], $company_options['company_postal'],
        $payment_link, $start_date, $start_time, $end_date, $end_time,
        $reg_form['quantity'], $attendee_names, $ticket_list);

    $wait_message_replaced = str_replace($SearchValues, $ReplaceValues, $wait_message);

    if ($reg_form['reg_type'] == "WAIT") {
        $email_content = $wait_message_replaced;
    }
    
    $email_body = $email_content;

    $email_body = $message_top . $email_content . $message_bottom;

    $headers = array(
        'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n",
        "Content-Type: text/html"
    );

    $headers = implode("\r\n", $headers) . "\r\n";
    wp_mail($reg_form['email'], stripslashes($mail_subject), html_entity_decode(nl2br($email_body)), $headers);

    _e("A confirmation email has been sent to:", 'evrplus_language');
    echo " ";
    echo $reg_form['email'] . "<br/>";
}

//End Send Confirmation Email    
//Send Admin Email

if ($company_options['admin_noti'] == "Y") {

    $SearchValues = array("[event_name]", "[fname]", "[lname]", "[phone]",
        "[address]", "[city]", "[state]", "[zip]", "[email]",
        "[event]", "[description]", "[cost]", "[currency]",
        "[contact]", "[coordinator]", "[company]", "[co_add1]", "[co_add2]",
        "[co_city]", "[co_state]", "[co_zip]",
        "[payment_url]", "[start_date]", "[start_time]", "[end_date]", "[end_time]",
        "[num_people]", "[attendees]", "[tickets]");

    $ReplaceValues = array($mail_subject, $reg_form['fname'], $reg_form['lname'], $reg_form['phone'],
        $reg_form['address'], $reg_form['city'], $reg_form['state'], $reg_form['zip'],
        $reg_form['email'],
        $event_name, $event_desc, $reg_form['payment'], $company_options['default_currency'],
        $company_options['company_email'], $coord_email, stripslashes($company_options['company']),
        $company_options['company_street1'], $company_options['company_street2'],
        $company_options['company_city'], $company_options['company_state'],
        $company_options['company_postal'],
        $payment_link, $start_date, $start_time, $end_date, $end_time,
        $reg_form['quantity'], $attendee_names, $ticket_list);
    
    $admin_email_body = '<p>A new user register on [event_name]. Please check user details here:<br /></p>';
    $admin_email_body .='<a href="' . admin_url() . 'admin.php?page=attendee&action=aview_attendee&event_id=' . $event_id . '&attendee_id=' . $reg_id . '">Click Here</a>';
    $email_content = str_replace($SearchValues, $ReplaceValues, $admin_email_body);
    $message_top = "<html><body>";
    $message_bottom = "</html></body>";
    $email_body = $email_content;

    $email_body = $message_top . $email_content . $message_bottom;

    $headers = array(
        'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n",
        "Content-Type: text/html"
    );
    $headers = implode("\r\n", $headers) . "\r\n";

    $send_email = wp_mail(get_option('admin_email'), stripslashes($mail_subject), html_entity_decode($email_body), $headers);

    if (isset($company_options['secondary_email']) and ! empty($company_options['secondary_email'])) {
        $emails = explode(',', $company_options['secondary_email']);
        foreach ($emails as $em) {
            $em = trim($em);
            $headers = array(
                'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n",
                "Content-Type: text/html"
            );
            $headers = implode("\r\n", $headers) . "\r\n";
            wp_mail($em, stripslashes($mail_subject), html_entity_decode($email_body), $headers);
        }
    }
}

//Send Coordinator AlertEmail   
//Select the default message
if ($send_coord == "Y") {
    if (count($attendee_array) > "0") {
        $attendee_names = "";
        $i = 0;
        do {
            $attendee_names .= $attendee_array[$i]["first_name"] . " " . $attendee_array[$i]['last_name'] . ",";
            ++$i;
        } while ($i < count($attendee_array));
    }
    $row_count = count($ticket_array);
    $ticket_list = "";
    for ($row = 0; $row < $row_count; $row++) {
        if ($ticket_array[$row]['ItemQty'] >= "1") {
            $ticket_list.= $ticket_array[$row]['ItemQty'] . " " . $ticket_array[$row]['ItemCat'] . "-" . $ticket_array[$row]['ItemName'] . " " . $ticket_array[$row]['ItemCurrency'] . " " . $ticket_array[$row]['ItemCost'] . "<br \>";
        }
    }

    //get answers to custom questions
    $events_answer_tbl = get_option('evr_answer');
    $events_question_tbl = get_option('evr_question');
    $qry = "SELECT " . $events_question_tbl . ".id, " .
            $events_question_tbl . ".sequence, " .
            $events_question_tbl . ".question, " .
            $events_answer_tbl . ".answer " .
            " FROM " . $events_question_tbl . ", " . $events_answer_tbl .
            " WHERE " . $events_question_tbl . ".id = " . $events_answer_tbl . ".question_id " .
            " AND " . $events_answer_tbl . ".registration_id = " . $reg_id .
            " ORDER by sequence";

    $results2 = $wpdb->get_results($qry, ARRAY_A);
    $custom_responses = "";
    foreach ($results2 as $answer) {
        $custom_responses .= $answer["question"] . "   " . $answer["answer"] . "<br/>";
    }


    //search and replace tags
    $SearchValues = array("[id]", "[fname]", "[lname]", "[phone]",
        "[address]", "[city]", "[state]", "[zip]", "[email]",
        "[event]", "[description]", "[cost]", "[currency]",
        "[contact]", "[coordinator]", "[company]", "[co_add1]", "[co_add2]",
        "[co_city]", "[co_state]", "[co_zip]",
        "[payment_url]", "[start_date]", "[start_time]", "[end_date]", "[end_time]",
        "[num_people]", "[attendees]", "[tickets]", "[custom]");

    $ReplaceValues = array($reg_id, $reg_form['fname'], $reg_form['lname'], $reg_form['phone'],
        $reg_form['address'], $reg_form['city'], $reg_form['state'], $reg_form['zip'], $reg_form['email'],
        $event_name, $event_desc, $reg_form['payment'], $company_options['default_currency'],
        $company_options['company_email'], $coord_email, $company_options['company'],
        $company_options['company_street1'], $company_options['company_street2'],
        $company_options['company_city'],
        $company_options['company_state'], $company_options['company_postal'],
        $payment_link, $start_date, $start_time, $end_date, $end_time,
        $reg_form['quantity'], $attendee_names, $ticket_list, $custom_responses);

    $email_content = str_replace($SearchValues, $ReplaceValues, $coord_msg);
    $message_top = "<html><body>";
    $message_bottom = "</html></body>";

    $email_body = $message_top . $email_content . $message_bottom;

    $headers = array(
        'From: "' . $company_options['company'] . '" <' . $company_options['company_email'] . ">\r\n",
        "Content-Type: text/html"
    );

    $headers = implode("\r\n", $headers) . "\r\n";

    wp_mail($coord_email, stripslashes($mail_subject), html_entity_decode($email_body), $headers);
}