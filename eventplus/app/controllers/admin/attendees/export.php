<?php

class eplus_admin_attendees_export_controller extends EventPlus_Abstract_Controller {

    /**
     * @var EventPlus_Models_Events
     */
    private $oModelEvents = null;

    /**
     * @var EventPlus_Models_Questions
     */
    private $oModelQuestions = null;

    /**
     * @var EventPlus_Models_Attendees
     */
    private $oModelAttendees = null;
    private $event_id = 0;
    private $tables = array();

    function before() {
        $this->oModelEvents = new EventPlus_Models_Events();
        $this->oModelQuestions = new EventPlus_Models_Questions();
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


        if ($type == 'csv') {
            $this->doCsv();
        }

        if ($type == 'xls') {
            $this->doXLS();
        }
    }

    private function doXLS() {


        $today = date_i18n("Y-m-d_Hi", time());

        $event_data = $this->oModelEvents->getData($this->event_id, ARRAY_A);
        @list($event_id, $event_name, $event_description, $event_identifier, $event_cost, $allow_checks, $is_active) = $event_data;

        $st = "";
        $et = "\t";
        $s = $et . $st;
        $basic_header = array('Reg ID', 'Reg Date', 'Payment Status', 'Type', 'Last Name', 'First Name', 'Attendees', 'Email', 'Address', 'City', 'State', 'Zip', 'Phone', 'Co Name', 'Co Address', 'Co City', 'Co State/Prov', 'Co Postal', 'Num People', 'Payment', 'Tickets');

        $question_sequence = array();
        $questions = $this->oModelQuestions->getByEventId($this->event_id);
        foreach ($questions as $question) {
            array_push($basic_header, $question['question']);
            array_push($question_sequence, $question['sequence']);
        }

        $filename = urlencode($event_data['id']) . "-Attendees_" . $today . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");

        $csv_output = implode($s, $basic_header) . $et . "\r\n";
        $participants = $this->oModelAttendees->getByEventId($this->event_id);

        foreach ($participants as $participant) {

            $paymentStatus = $participant["payment_status"];
            if ($paymentStatus == '' || $paymentStatus == null) {
                $paymentStatus = 'Pending';
            }

            $csv_output .= $participant["id"]
                    . $s . $participant["date"]
                    . $s . $paymentStatus
                    . $s . $participant["reg_type"]
                    . $s . $participant["lname"]
                    . $s . $participant["fname"];
            $attendee_array = unserialize($participant["attendees"]);
            if (count($attendee_array) > "0") {
                $attendee_names = '"';
                $i = 0;
                do {
                    $attendee_names .= $attendee_array[$i]["first_name"] . " " . $attendee_array[$i]['last_name'] . ", ";
                    ++$i;
                } while ($i < count($attendee_array));
                $attendee_names .='"';
            }
            $csv_output .= $s . $attendee_names
                    . $s . $participant["email"]
                    . $s . $participant["address"]
                    . $s . $participant["city"]
                    . $s . $participant["state"]
                    . $s . "'" . $participant["zip"]
                    . $s . $participant["phone"]
                    . $s . $participant["company"]
                    . $s . $participant["co_address"]
                    . $s . $participant["co_city"]
                    . $s . $participant["co_state"]
                    . $s . $participant["co_zip"]
                    . $s . $participant["quantity"]
                    . $s . $participant["payment"]
                    . $s;

            $ticket_order = unserialize($participant["tickets"]);
            $row_count = count($ticket_order);
            $csv_output .= "||";
            for ($row = 0; $row < $row_count; $row++) {
                $csv_output .= $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " . $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "||";
            }

            $qry = "SELECT " . $this->tables['evrplus_question'] . ".id, " .
                    $this->tables['evrplus_question'] . ".sequence, " .
                    $this->tables['evrplus_question'] . ".question, " .
                    $this->tables['evrplus_answer'] . ".answer " . " FROM " . $this->tables['evrplus_question'] . ", " . $this->tables['evrplus_answer'] . " WHERE " . $this->tables['evrplus_question'] . ".id = " . $this->tables['evrplus_answer'] . ".question_id " . " AND " . $this->tables['evrplus_answer'] . ".registration_id = " . $participant["id"] . " ORDER by sequence";
            $answers = $this->oModelAttendees->getWpDb()->get_results($qry, ARRAY_A);
            foreach ($answers as $answer) {
                $csv_output .= $s . $answer["answer"];
            }
            $csv_output .= $et . "\r\n";
        }

        $temp = iconv("UTF-8", "utf-8//TRANSLIT", $csv_output); 
        print $temp;
        exit;
    }

    private function doCsv() {


        $today = date_i18n("Y-m-d_Hi", time());
		$csv_output = "";
        $event_data = $this->oModelEvents->getData($this->event_id, ARRAY_A);
       @list($event_id, $event_name, $event_description, $event_identifier, $event_cost, $allow_checks, $is_active) = $event_data;

        $st = "";
        $et = ",";
        $s = $et . $st;
        $basic_header = array('Reg ID', 'Reg Date', 'Payment Status', 'Type', 'Last Name', 'First Name', 'Attendees', 'Email', 'Address', 'City', 'State', 'Zip', 'Phone', 'Co Name', 'Co Address', 'Co City', 'Co State/Prov', 'Co Postal', 'Num People', 'Payment', 'Tickets');

        $question_sequence = array();
        $questions = $this->oModelQuestions->getByEventId($this->event_id);
        foreach ($questions as $question) {
            array_push($basic_header, $question['question']);
            array_push($question_sequence, $question['sequence']);
        }

        $filename = urlencode($event_data['id']) . "-Attendees_" . $today . ".csv";
        header("Content-type: application/x-msdownload");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");
        $csv_output .= implode($s, $basic_header) . "\r\n";
        $participants = $this->oModelAttendees->getByEventId($this->event_id);

        foreach ($participants as $participant) {

            $paymentStatus = $participant["payment_status"];
            if ($paymentStatus == '' || $paymentStatus == null) {
                $paymentStatus = 'Pending';
            }

            $csv_output .= $participant["id"]
                    . $s . $participant["date"]
                    . $s . $paymentStatus
                    . $s . $participant["reg_type"]
                    . $s . $participant["lname"]
                    . $s . $participant["fname"];
            $attendee_array = unserialize($participant["attendees"]);
            if (count($attendee_array) > "0") {
                $attendee_names = '"';
                $i = 0;
                do {
                    $attendee_names .= $attendee_array[$i]["first_name"] . " " . $attendee_array[$i]['last_name'] . ", ";
                    ++$i;
                } while ($i < count($attendee_array));
                $attendee_names .='"';
            }



            $csv_output .= $s . $attendee_names
                    . $s . $participant["email"]
                    . $s . $participant["address"]
                    . $s . $participant["city"]
                    . $s . $participant["state"]
                    . $s . $participant["zip"]
                    . $s . $participant["phone"]
                    . $s . $participant["company"]
                    . $s . $participant["co_address"]
                    . $s . $participant["co_city"]
                    . $s . $participant["co_state"]
                    . $s . $participant["co_zip"]
                    . $s . $participant["quantity"]
                    . $s . $participant["payment"]
                    . $s;

            $ticket_order = unserialize($participant["tickets"]);
            $row_count = count($ticket_order);
            $csv_output .= "||";
            for ($row = 0; $row < $row_count; $row++) {
                $csv_output .= $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " . $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "||";
            }

            $qry = "SELECT " . $this->tables['evrplus_question'] . ".id, " .
                    $this->tables['evrplus_question'] . ".sequence, " .
                    $this->tables['evrplus_question'] . ".question, " .
                    $this->tables['evrplus_answer'] . ".answer " . " FROM " . $this->tables['evrplus_question'] . ", " . $this->tables['evrplus_answer'] . " WHERE " . $this->tables['evrplus_question'] . ".id = " . $this->tables['evrplus_answer'] . ".question_id " . " AND " . $this->tables['evrplus_answer'] . ".registration_id = " . $participant["id"] . " ORDER by sequence";
            $answers = $this->oModelAttendees->getWpDb()->get_results($qry, ARRAY_A);
            foreach ($answers as $answer) {
                $csv_output .= $s . $answer["answer"];
            }
            $csv_output .= $et . "\r\n";
        }

        print $csv_output;
        exit;
    }

}