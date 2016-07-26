<?php

class EventPlus_Helpers_Mail_Registration extends EventPlus_Helpers_Mail {

    private $data = array();
    private $company_options = array();
    private $attendeeRow = array();
    private $eventRow = array();

    function __construct($data) {
        $this->data = $data;
        $this->company_options = EventPlus_Models_Settings::getSettings();

        $oAttendee = new EventPlus_Models_Attendees();
        $this->attendeeRow = $oAttendee->getRow($this->data['attendee_id']);

        $oEvent = new EventPlus_Models_Events();
        $this->eventRow = $oEvent->getRow($this->data['event_id']);
    }

    function send() {

        $use_coupon = $this->eventRow['use_coupon'];
        $reg_limit = $this->eventRow['reg_limit'];
        $event_name = htmlspecialchars_decode(html_entity_decode(stripslashes($this->eventRow['event_name'])));
        $mail_subject = evrplus_htmlchanger($this->eventRow['event_name']);
        $invoice_event = $this->eventRow['event_name'];
        $event_identifier = stripslashes($this->eventRow['event_identifier']);
        $display_desc = $this->eventRow['display_desc'];  // Y or N
        $event_desc = html_entity_decode(stripslashes($this->eventRow['event_desc']));
        $event_category = unserialize($this->eventRow['category_id']);
        $event_location = $this->eventRow['event_location'];
        $event_address = $this->eventRow['event_address'];
        $event_city = $this->eventRow['event_city'];
        $event_state = $this->eventRow['event_state'];
        $event_postal = $this->eventRow['event_postal'];
        $google_map = $this->eventRow['google_map'];  // Y or N
        $start_month = $this->eventRow['start_month'];
        $start_day = $this->eventRow['start_day'];
        $start_year = $this->eventRow['start_year'];
        $end_month = $this->eventRow['end_month'];
        $end_day = $this->eventRow['end_day'];
        $end_year = $this->eventRow['end_year'];
        $start_time = $this->eventRow['start_time'];
        $end_time = $this->eventRow['end_time'];
        $allow_checks = $this->eventRow['allow_checks'];
        $counter_checks = $this->eventRow['counter_checks'];
        $outside_reg = $this->eventRow['outside_reg'];  // Yor N
        $external_site = $this->eventRow['external_site'];
        $more_info = $this->eventRow['more_info'];
        $image_link = $this->eventRow['image_link'];
        $header_image = $this->eventRow['header_image'];
        $is_active = $this->eventRow['is_active'];
        $send_mail = $this->eventRow['send_mail'];  // Y or N
        $start_date = $this->eventRow['start_date'];
        $end_date = $this->eventRow['end_date'];

        if (strtoupper($this->company_options['send_confirm']) == 'Y') {

            if ($this->data['event_id'] > 0 && $this->data['attendee_id'] > 0) {

                if ($this->attendeeRow['id'] <= 0) {
                    return;
                }

                if ($this->eventRow['id'] <= 0) {
                    return;
                }

                $payment_link = evrplus_permalink($this->company_options['evrplus_page_id']) . "?action=confirmation&eventplus_token=" . $this->attendeeRow['eventplus_token'] . "&event_id=" . $this->data['event_id'];

                if (strtoupper($send_mail) == "N") {
                    return;
                }

                $emailBodyStr = stripslashes($this->eventRow['conf_mail']);
                if (trim($event_signup_email) == '') {
                    $emailBodyStr = $this->company_options['message'];
                }

                if ($emailBodyStr != '') {
                    $attendee_array = unserialize($this->attendeeRow['attendees']);
                    $ticket_array = unserialize($this->attendeeRow['tickets']);

                    $attendee_names = "";
                    if (count($attendee_array) > "0") {
                        $i = 0;
                        do {
                            $attendee_names .= $attendee_array[$i]["first_name"] . " " . $attendee_array[$i]['last_name'] . ",";
                            ++$i;
                        } while ($i < count($attendee_array));
                    }

                    $ticketsCount = count($ticket_array);
                    $ticket_list = "";
                    if ($ticketsCount > 0) {
                        for ($row = 0; $row < $ticketsCount; $row++) {
                            if ($ticket_array[$row]['ItemQty'] >= "1") {
                                $ticket_list .= $ticket_array[$row]['ItemQty'] . " " . $ticket_array[$row]['ItemCat'] . "-" . $ticket_array[$row]['ItemName'] . " " . $ticket_array[$row]['ItemCurrency'] . " " . $ticket_array[$row]['ItemCost'] . "<br \>";
                            }
                        }
                    }

                    $bindParams = array(
                        "[id]" => $this->attendeeRow['id'],
                        "[fname]" => $this->attendeeRow['fname'],
                        "[lname]" => $this->attendeeRow['lname'],
                        "[phone]" => $this->attendeeRow['phone'],
                        "[address]" => $this->attendeeRow['address'],
                        "[city]" => $this->attendeeRow['city'],
                        "[state]" => $this->attendeeRow['state'],
                        "[zip]" => $this->attendeeRow['zip'],
                        "[email]" => $this->attendeeRow['email'],
                        "[event]" => $event_name,
                        "[description]" => $event_desc,
                        "[cost]" => $this->attendeeRow['payment'],
                        "[currency]" => $this->company_options['default_currency'],
                        "[contact]" => $this->company_options['company_email'],
                        "[coordinator]" => '',
                        "[company]" => stripslashes($this->company_options['company']),
                        "[co_add1]" => $this->company_options['company_street1'],
                        "[co_add2]" => $this->company_options['company_street2'],
                        "[co_city]" => $this->company_options['company_city'],
                        "[co_state]" => $this->company_options['company_state'],
                        "[co_zip]" => $this->company_options['company_postal'],
                        "[payment_url]" => $payment_link,
                        "[start_date]" => $start_date,
                        "[start_time]" => $start_time,
                        "[end_date]" => $end_date,
                        "[end_time]" => $end_time,
                        "[num_people]" => number_format($this->attendeeRow['quantity'], 0),
                        "[attendees]" => $attendee_names,
                        "[tickets]" => $ticket_list
                    );

                    foreach ($bindParams as $searchValues => $replaceValues) {
                        $emailBodyStr = str_replace($searchValues, $replaceValues, $emailBodyStr);
                    }

                    $email_content = $emailBodyStr;

                    $message_top = "<html><body>";
                    $message_bottom = "</html></body>";

                    $wait_message = '<font color="red"><p>' . __("Thank you for registering for", 'evrplus_language') . " " . $event_name . ". " . __("At this time, all seats for the event have been taken.  
        Your information has been placed on our waiting list.  
        The waiting list is on a first come, first serve basis.  
        You will be notified by email should a seat become available.", 'evrplus_language') . '</p><p>' . __("Thank You", 'evrplus_language') . '</p></font>';

                    if (trim($this->company_options['wait_message']) != "") {
                        $wait_message = $this->company_options['wait_message'];
                    }

                    foreach ($bindParams as $searchValues => $replaceValues) {
                        $wait_message = str_replace($searchValues, $replaceValues, $wait_message);
                    }

                    if (strtoupper($this->attendeeRow['reg_type']) == "WAIT") {
                        $email_content = $wait_message;
                    }

                    $email_body = $message_top . $email_content . $message_bottom;

                    $headers = array(
                        'From: "' . $this->company_options['company'] . '" <' . $this->company_options['company_email'] . ">\r\n",
                        "Content-Type: text/html"
                    );

                    $headers = implode("\r\n", $headers) . "\r\n";
                    $this->send_wp_mail($this->attendeeRow['email'], stripslashes($mail_subject), html_entity_decode(nl2br($email_body)), $headers);
                }
            }
        }

        if (strtoupper($this->company_options['admin_noti']) == "Y") {

            $adminLink = $this->adminUrl('admin_attendees/details', array('event_id' => $this->eventRow['id'], 'attendee_id' => $this->attendeeRow['id']));
            $admin_email_body = '<p>A new user register on ' . stripslashes($event_name) . '. Please check user details here:<br /></p>';
            $admin_email_body .='<a href="' . $adminLink . '">Click Here</a>';

            $message_top = "<html><body>";
            $message_bottom = "</html></body>";

            $admin_email_body = $message_top . $admin_email_body . $message_bottom;

            $toAdminEmails = array(get_option('admin_email'));
            if (isset($this->company_options['secondary_email']) && !empty($this->company_options['secondary_email'])) {
                $emails = explode(',', $this->company_options['secondary_email']);
                foreach ($emails as $email) {
                    $toAdminEmails[] = trim($email);
                }
            }

            $toAdminEmails = array_unique($toAdminEmails);

            if (count($toAdminEmails) > 0) {

                $headers = array(
                    'From: "' . $this->company_options['company'] . '" <' . $this->company_options['company_email'] . ">\r\n",
                    "Content-Type: text/html"
                );
                
                $headers = implode("\r\n", $headers) . "\r\n";

                foreach ($toAdminEmails as $email) {
                    $email = trim($email);
                    $this->send_wp_mail($email, 'New Registration - ' . stripslashes($mail_subject), html_entity_decode(nl2br($admin_email_body)), $headers);
                }
            }
        }
    }

}
