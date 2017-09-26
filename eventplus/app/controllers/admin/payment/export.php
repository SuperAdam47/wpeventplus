<?php

class eplus_admin_payment_export_controller extends EventPlus_Abstract_Controller {

    /**
     * @var EventPlus_Models_Events
     */
    private $oModelEvents = null;

    /**
     * @var EventPlus_Models_Attendees
     */
    private $oModelAttendees = null;
    private $event_id = 0;
    private $tables = array();

    function before() {
        $this->oModelEvents = new EventPlus_Models_Events();
        $this->oModelAttendees = new EventPlus_Models_Attendees();

        $this->tables = array(
            "evrplus_answer" => get_option('evr_answer'),
            "evrplus_question" => get_option('evr_question'),
            "evrplus_event" => get_option('evr_event'),
            "evrplus_attendee" => get_option('evr_attendee'),
            "evrplus_payment" => get_option('evr_payment')
        );
    }

    function index() {

        $this->event_id = $this->_invokeArgs['event_id'];
        $event_data = $this->oModelEvents->getData($event_id, ARRAY_A);

        $today = date_i18n("Y-m-d_Hi", time());

        $st = "";
        $et = "\t";
        $s = $et . $st;
        $file = urlencode(stripslashes($event_data['event_name']));

        $filename = $file . "-Payments_" . $today . ".xls";
        $basic_header = array('Participant ID', 'Name (Last, First)', 'Email', 'Registration Type', '# Attendees', 'Order Total', 'Balance Due', 'Order Details', 'Payment Details');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = implode($s, $basic_header) . $et . "\r\n";

        $participants = $this->oModelAttendees->getByEventId($event_id, array('order_by_lname' => true));
        foreach ($participants as $participant) {
            $output .= $participant["id"]
                    . $s . $participant["lname"] . ", " . $participant["fname"]
                    . $s . $participant["email"]
                    . $s . $participant["reg_type"]
                    . $s . $participant["quantity"]
                    . $s . $participant["payment"];

            $sql2 = "SELECT SUM(mc_gross) FROM " . $this->tables['evrplus_payment'] . " WHERE payment_status = 'success' AND payer_id='" . (int) $participant["id"] . "'";
            $payments = $this->oModelAttendees->getWpDb()->get_results($sql2, ARRAY_A);
            foreach ($payments as $row) {

                $total_paid = $row['SUM(mc_gross)'];
                $balance = "0";
                if ($participant["payment"] > "0") {
                    $balance = ($participant["payment"] - $total_paid);
                }

                $output .= $s . $balance . $s;
                $ticket_order = unserialize($participant["tickets"]);
                $row_count = count($ticket_order);
                $output .= "||";
                for ($row = 0; $row < $row_count; $row++) {
                    $output .= $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " . $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "||";
                }
                $output .= $s;
                $output .= "||";
                $sql = "SELECT * from " . $this->tables['evrplus_payment'] . " WHERE payment_status = 'success' AND payer_id ='" . (int) $participant["id"] . "'";
                $result = $this->oModelAttendees->getWpDb()->get_results($sql, ARRAY_A);
                foreach ($result as $payment) {
                    $output .= $payment["mc_currency"] . " " . $payment["mc_gross"] . " " . $payment["txn_type"] . " " . $payment["txn_id"] . " (" . $payment["payment_date"] . ")" . "||";
                }
            }
            $output .= $et . "\r\n";
            exit;
        }
    }

}
