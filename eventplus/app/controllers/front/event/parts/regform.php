<?php

class eplus_front_event_parts_regform_controller extends EventPlus_Abstract_Controller {

    function index() {

        $oEvents = new EventPlus_Models_Events();
        $eventRow = $oEvents->getEventObject($this->_invokeArgs['event_id']);


        $output = $this->oView->View('front/event/parts/regform', array(
            'event_id' => $this->_invokeArgs['event_id'],
            'recurr' => $this->_invokeArgs['recurr'],
            'rows' => $eventRow,
        ));

        if ($eventRow) {
            $this->setResponse($output);
        }
    }

}
