<?php

class eplus_admin_payments_export_controller extends EventPlus_Abstract_Controller {

    /**
     * @var EventPlus_Models_Events
     */
    private $oModelEvents = null;

    /**
     * @var EventPlus_Models_Payments
     */
    private $oModelPayments = null;

    /**
     * @var EventPlus_Models_Attendees
     */
    private $oModelAttendees = null;
    private $event_id = 0;
    private $tables = array();

    function before() {
        $this->oModelEvents = new EventPlus_Models_Events();
        $this->oModelPayments = new EventPlus_Models_Payments();
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

        $type = $this->_invokeArgs['type'];
        if(!empty($this->_invokeArgs['oEvent'])){
			$oEvent = $this->_invokeArgs['oEvent'];
		}else{
			$oEvent = "";
		}
        $this->event_id = $this->_invokeArgs['event_id'];

        $today = date_i18n("Y-m-d_Hi", time());

        $event_data = $this->oModelEvents->getData($this->event_id, ARRAY_A);


        @list($event_id, $event_name, $event_description, $this->event_identifier, $event_cost, $allow_checks, $is_active) = $event_data;

        $wpdb = $this->oModelPayments->getWpDb();

        $st = "";
        $et = "\t";
        $s = $et . $st;
        
        $filename = urlencode($event_data['id']) . "-Payments_" . $today . ".xls";
        $basic_header = array('Participant ID', 'Name (Last, First)', 'Email', 'Registration Type', '# Attendees', 'Order Total', 'Balance Due', 'Order Details', 'Payment Details');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = implode($s, $basic_header) . $et . "\r\n";

        $results = $wpdb->get_results("SELECT * from " . $this->tables['evrplus_attendee'] . " where event_id = '$this->event_id' ORDER BY lname DESC", ARRAY_A);
     
        
        foreach ($results as $participant) {
            $output .= $participant ["id"]
            . $s . $participant ["lname"] . ", " . $participant ["fname"]
            . $s . $participant["email"]
            . $s . $participant ["reg_type"]
            . $s . $participant["quantity"]
            . $s . $participant["payment"];
            $sql2 = "SELECT SUM(mc_gross) FROM " . $this->tables['evrplus_payment'] . " WHERE payer_id='" . $participant ["id"] . "'";
            $result2 = $wpdb->get_results($sql2, ARRAY_A);
            foreach ($result2 as $row) {

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
                    echo $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " . $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "||";
                }
                $output .= $s;
                $output .= "||";
                $sql = "SELECT * from " . $this->tables['evrplus_payment'] . " WHERE payer_id ='" . $participant["id"] . "'";
                $result = $wpdb->get_results($sql, ARRAY_A);
                foreach ($result as $payment) {
                    $output .= $payment["mc_currency"] . " " . $payment["mc_gross"] . " " . $payment["txn_type"] . " " . $payment["txn_id"] . " (" . $payment["payment_date"] . ")" . "||";
                }
            }
            $output .= $et . "\r\n";
            print $output;
            exit;
        }
    }

}
