<?php

class eplus_front_event_parts_confirmation_controller extends EventPlus_Abstract_Controller {

    function index() {

        $event_id = 0;
        $reg_id = 0;

        if (is_numeric($_REQUEST['event_id'])) {
            $event_id = (int) $_REQUEST['event_id'];
        }

        if (is_numeric($_REQUEST['reg_id'])) {
            $reg_id = (int) $_REQUEST['reg_id'];
        }

        $token = 0;
        if (isset($_COOKIE['evr_token'])) {
            $token = $_COOKIE['evr_token'];
        }

        $oEvent = new EventPlus_Models_Events();
        $eventRow = $oEvent->getRow($event_id);
        
        if(isset($eventRow['id']) == false){
            $this->setResponse(__("Invalid event request", 'evrplus_language'));
            return;
        }
        

        $oAttendee = new EventPlus_Models_Attendees();
        $attendeeData = $oAttendee->getDataByToken($reg_id, $token);
 
        if(isset($attendeeData[0]['id']) == false){
            $this->setResponse(__("Invalid request", 'evrplus_language'));
            return;
        }

        $output = $this->oView->View('front/event/parts/confirmation', array(
            'event_id' => $event_id,
            'reg_id' => $reg_id,
            'token' => $token,
            'row' => $eventRow,
            'reg_form' => $attendeeData,
        ));

        $this->setResponse($output);
    }

}
