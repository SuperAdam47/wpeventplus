<?php

class eplus_front_event_parts_confirmation_controller extends EventPlus_Abstract_Controller {

    function index() {
		$event_id = 0;

        if (is_numeric($_REQUEST['event_id'])) {
            $event_id = (int) $_REQUEST['event_id'];
        }

        $eventplus_token = $this->_request->getParam('eventplus_token');
        $cookie_token = EventPlus_Helpers_Token::get($event_id);

        $oEvent = new EventPlus_Models_Events();
        $eventRow = $oEvent->getRow($event_id);

        if (isset($eventRow['id']) == false || EventPlus_Helpers_Token::isValidFormat($eventplus_token) == false) {
            $this->setResponse(__("Invalid event request", 'evrplus_language'));
            return;
        }


        $oAttendee = new EventPlus_Models_Attendees();
        $attendeeData = $oAttendee->getDataByPlainToken($eventplus_token);

        if (isset($attendeeData[0]['id']) == false) {
            $this->setResponse(__("Invalid request", 'evrplus_language'));
            return;
        }

        $reg_id = $attendeeData[0]['id'];

        $output = $this->oView->View('front/event/parts/confirmation', array(
            'event_id' => $event_id,
            'reg_id' => $reg_id,
            'eventplus_token' => $eventplus_token,
            'row' => $eventRow,
            'reg_form' => $attendeeData[0],
        ));

        $this->setResponse($output);
    }

}
