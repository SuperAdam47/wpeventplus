<?php

class eplus_front_shortcode_payment_controller extends EventPlus_Abstract_Controller {

    function index() {

        global $wpdb;

        $oEvent = new EventPlus_Models_Events();
        $oAttendee = new EventPlus_Models_Attendees();
        $oPayment = new EventPlus_Models_Payments();
        $attendee_id = "";
        $first = "";
        $passed_attendee_id = $_GET['id'];
        $passed_first = $_GET['fname'];

        if (is_numeric($passed_attendee_id)) {
            $attendee_id = $passed_attendee_id;
        } else {
            $attendee_id = "0";
            $this->setResponse('Failure - please retry!');
            return;
        }

        if (($attendee_id == "") || ($attendee_id == "0")) {
            $this->setResponse(_e('Please check your email for payment information. Click the link provided in the registration confirmation.', 'evrplus_language'));
            return;
        } else {

            $rowAttendee = $oAttendee->getData($attendee_id);
            
            $attendee_id = $rowAttendee['id'];
            $lname = $rowAttendee['lname'];
            $fname = $rowAttendee['fname'];
            $address = $rowAttendee['address'];
            $city = $rowAttendee['city'];
            $state = $rowAttendee['state'];
            $zip = $rowAttendee['zip'];
            $email = $rowAttendee['email'];
            $phone = $rowAttendee['phone'];
            $date = $rowAttendee['date'];
            $payment = $rowAttendee['payment'];
            $event_id = $rowAttendee['event_id'];
            $quantity = $rowAttendee ['quantity'];
            $reg_type = $rowAttendee['reg_type'];
            $ticket_order = unserialize($rowAttendee['tickets']);
            $coupon = $rowAttendee['coupon'];
            $attendee_name = $fname . " " . $lname;


            if ($passed_first != $rowAttendee['fname']) {
                $this->setResponse('Failure - please retry!');
                return;
            }

            //Query Database for event and get variable
            $rowEvent = $oEvent->getRow($event_id);
            $payments = $oPayment->getPayments($attendee_id);

            $viewParams = array();
            $viewParams['rowAttendee'] = $rowAttendee;
            $viewParams['rowEvent'] = $rowEvent;
            $viewParams['payments'] = $payments;
            $viewParams['company_options'] = EventPlus_Models_Settings::getSettings();


            $output = $this->oView->View('front/widgets/events', $viewParams);

            $this->setResponse($output);
        }
    }

}
