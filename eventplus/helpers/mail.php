<?php

class EventPlus_Helpers_Mail {

    /**
     * @var EventPlus_Validate
     */
    protected $oValidate = null;
    protected $data = array();
    protected $company_options = array();
    protected $attendeeRow = array();
    protected $eventRow = array();

    function __construct($data) {
        $this->oValidate = new EventPlus_Validate($data);
        $this->data = $data;
        $this->company_options = EventPlus_Models_Settings::getSettings();


        if ($this->data['attendee_id'] > 0) {
            $oAttendee = new EventPlus_Models_Attendees();
            $this->attendeeRow = $oAttendee->getData($this->data['attendee_id']);
        }

        if (isset($this->data['attendeeRow'])) {
            $this->attendeeRow = $this->data['attendeeRow'];
        }

        if ($this->data['event_id'] > 0) {
            $oEvent = new EventPlus_Models_Events();
            $this->eventRow = $oEvent->getRow($this->data['event_id']);
        }

        if (isset($this->data['eventRow'])) {
            $this->eventRow = $this->data['eventRow'];
        }
    }

    function send_wp_mail($to, $subject, $message, $headers = '', $attachments = array()) {

        $message = nl2br($message);

        $totSent = 0;
        if (is_string($to)) {
            if ($this->oValidate->email($to)) {
                $q = wp_mail($to, $subject, $message, $headers, $attachments);
                if ($q) {
                    $totSent++;
                }
            }
        } else if (is_array($to)) {

            $to = array_unique($to);

            foreach ($to as $i => $toEmail) {
                if ($this->oValidate->email($toEmail)) {
                    $q = wp_mail($toEmail, $subject, $message, $headers, $attachments);
                    if ($q) {
                        $totSent++;
                    }
                }
            }
        }

        return $totSent;
    }

    function adminUrl($uri, array $params = array()) {
        return EventPlus::getRegistry()->url->admin($uri, $params);
    }

    public function bindParams($str) {
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
        $category_id = $this->eventRow['category_id'];


        $category_list_str = '';
        if (is_array($event_category) && count($event_category)) {
            $category_list_str = EventPlus_Helpers_Funx::getCategoryList($event_category);
        }


        $payment_link = evrplus_permalink($this->company_options['evrplus_page_id']) . "?action=confirmation&eventplus_token=" . $this->attendeeRow['token'] . "&event_id=" . $this->data['event_id'];

        $attendee_array = unserialize($this->attendeeRow['attendees']);
        $ticket_array = unserialize($this->attendeeRow['tickets']);

        $attendee_names = "";
        if (count($attendee_array) > 0) {
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
            "[category_list]" => $category_list_str,
            "[fname]" => $this->attendeeRow['fname'],
            "[lname]" => $this->attendeeRow['lname'],
            "[phone]" => $this->attendeeRow['phone'],
            "[address]" => $this->attendeeRow['address'],
            "[city]" => $this->attendeeRow['city'],
            "[state]" => $this->attendeeRow['state'],
            "[zip]" => $this->attendeeRow['zip'],
            "[email]" => $this->attendeeRow['email'],
            "[event]" => $event_name,
            "[event_id]" => $this->eventRow['id'],
            "[location]" => $this->eventRow['event_location'],
            "[event_city]" => $this->eventRow['event_city'],
            "[event_name]" => $event_name,
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
            "[tickets]" => $ticket_list,
            "[ADMIN_ATTENDEE_LINK]" => $this->adminUrl('admin_attendees/details', array('event_id' => $this->eventRow['id'], 'attendee_id' => $this->attendeeRow['id']))
        );


        foreach ($bindParams as $searchValue => $replaceValue) {
            $str = str_replace($searchValue, $replaceValue, $str);
        }

        return $str;
    }

}
