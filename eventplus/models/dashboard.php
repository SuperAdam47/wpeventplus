<?php

class EventPlus_Models_Dashboard extends EventPlus_Abstract_Model {

    function getEvents($limit) {

        $company_options = EventPlus_Models_Settings::getSettings();
        $orderby = $company_options['order_event_list'];
        $sql = "SELECT * FROM " . get_option('evr_event') . " where str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')" . $orderby . " LIMIT " . (int) $limit;
        $events = $this->getResults($sql);

        if (count($events) > 0) {
            foreach ($events as $i => $event) {

                $sql = "SELECT SUM(quantity) as totQty FROM " . get_option('evr_attendee') . " WHERE payment_status = '".EventPlus_Models_Payments::PAYMENT_SUCCESS."' AND event_id = '" . (int) $event->id . "'";
                $rowSum = $this->QuickArray($sql);

                $event->number_attendees = $rowSum['totQty'];
                $events[$i] = $event;
            }
        }

        return $events;
    }

    function getPayments($limit) {

        $sql = "SELECT p.*, a.fname, a.lname FROM " . get_option('evr_payment') . " p "
                . " JOIN " . get_option('evr_attendee') . " a on a.id = p.payer_id"
                . " ORDER BY p.id DESC LIMIT " . (int) $limit;
        return $this->getResults($sql);
    }

    function getAttendees($limit) {

        return $this->getResults("SELECT a.*, e.event_name FROM " . get_option('evr_attendee') . "  a "
                        . " JOIN " . get_option('evr_event') . " e on e.id = a.event_id ORDER BY a.id DESC LIMIT " . (int) $limit);
    }

    function getCategories($limit) {

        $sql = "SELECT * FROM " . get_option('evr_category') . " ORDER BY id DESC LIMIT " . (int) $limit;
        return $this->getResults($sql);
    }

}
