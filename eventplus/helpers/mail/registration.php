<?php

class EventPlus_Helpers_Mail_Registration extends EventPlus_Helpers_Mail {

    protected $boolConfirmation = false;

    protected function toAttendee() {
        if ($this->data['event_id'] > 0 && $this->data['attendee_id'] > 0) {


            if ($this->attendeeRow['id'] <= 0) {
                return;
            }

            if ($this->eventRow['id'] <= 0) {
                return;
            }


            $emailBodyStr = '';

            if (strtoupper($this->eventRow['send_mail']) == "Y") {
                $emailBodyStr = stripslashes($this->eventRow['conf_mail']);
            }

            if (trim($emailBodyStr) == '') {
                $emailBodyStr = $this->company_options['message'];
            }

            if ($emailBodyStr != '') {
                
                $bindParams = array(
                    "[company]" => stripslashes($this->attendeeRow['company']),
                );

                foreach ($bindParams as $searchValue => $replaceValue) {
                    $emailBodyStr = str_replace($searchValue, $replaceValue, $emailBodyStr);
                }

                $emailBodyStr = $this->bindParams($emailBodyStr);

                $email_content = $emailBodyStr;

                $message_top = "<html><body>";
                $message_bottom = "</html></body>";

                $wait_message = '<font color="red"><p>' . __("Thank you for registering for", 'evrplus_language') . " [event_name]. "
                        . __("At this time, all seats for the event have been taken. Your information has been placed on our waiting list.  
        The waiting list is on a first come, first serve basis.  
        You will be notified by email should a seat become available.", 'evrplus_language') . '</p>'
                        . '<p>' . __("Thank You", 'evrplus_language') . '</p></font>';

                if (trim($this->company_options['wait_message']) != "") {
                    $wait_message = $this->company_options['wait_message'];
                }

                $wait_message = $this->bindParams($wait_message);

                if (strtoupper($this->attendeeRow['reg_type']) == "WAIT") {
                    $email_content = $wait_message;
                }

                $email_body = $message_top . $email_content . $message_bottom;

                 $headers = array(
                        'From: "' . $this->company_options['company'] . '" <' . $this->company_options['company_email'] . ">",
                        "Content-Type: text/html; charset=UTF-8"
                    );


                $event_name = htmlspecialchars_decode(html_entity_decode(stripslashes($this->eventRow['event_name'])));
                $mail_subject = $event_name;

               $this->boolConfirmation = $this->send_wp_mail($this->attendeeRow['email'], stripslashes($mail_subject), html_entity_decode( str_replace( "??", "?", $email_body ) ), $headers);
            }
        }
    }

    protected function toAdmin() {
        $adminLink = $this->adminUrl('admin_attendees/details', array('event_id' => $this->eventRow['id'], 'attendee_id' => $this->attendeeRow['id']));
        $admin_email_body = '<p>A new user register on [event]. Please check user details here:<br /></p>';
        $admin_email_body .='<a href="' . $adminLink . '">Click Here</a>';

        $message_top = "<html><body>";
        $message_bottom = "</html></body>";

        if (trim($this->company_options['c_message']) != "") {
            $admin_email_body = $this->company_options['c_message'];
        }

        $admin_body = $this->bindParams($admin_email_body);

        $admin_email_body = $message_top . $admin_body . $message_bottom;

        $toAdminEmails = array();

        if( $is_admin_email = apply_filters( 'eventsplus_send_attendee_reg_mail_to_admin_email', true ) ) {
            $toAdminEmails = array(get_option('admin_email'));
        }

        if (isset($this->company_options['email']) && !empty($this->company_options['email'])) {
            $toAdminEmails[] = trim($this->company_options['email']);
        }
        
        if (isset($this->company_options['company_email']) && !empty($this->company_options['company_email'])) {
            $toAdminEmails[] = trim($this->company_options['company_email']);
        }

        if (isset($this->company_options['secondary_email']) && !empty($this->company_options['secondary_email'])) {
            $emails = explode(',', $this->company_options['secondary_email']);
            foreach ($emails as $email) {
                $toAdminEmails[] = trim($email);
            }
        }

        $toAdminEmails = array_unique($toAdminEmails);
        
        if (count($toAdminEmails) > 0) {

            $headers = array(
                'From: "' . $this->company_options['company'] . '" <' . $this->company_options['company_email'] . ">",
                "Content-Type: text/html; charset=UTF-8"
            );

            $event_name = htmlspecialchars_decode(html_entity_decode(stripslashes($this->eventRow['event_name'])));
            $mail_subject = $event_name;
                
            $r = $this->send_wp_mail($toAdminEmails, 'New Registration - ' . stripslashes($mail_subject), html_entity_decode($admin_email_body), $headers);
        }
    }

    function send() {

        if (strtoupper($this->company_options['send_confirm']) == 'Y') {
            $this->toAttendee();
        }


        if (strtoupper($this->company_options['admin_noti']) == "Y") {
            $this->toAdmin();
        }

        return $this->boolConfirmation;
    }

}