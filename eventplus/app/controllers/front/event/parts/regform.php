<?php

class eplus_front_event_parts_regform_controller extends EventPlus_Abstract_Controller {

    function index() {

        $oEvents = new EventPlus_Models_Events();
        $eventRow = $oEvents->getEventObject($this->_invokeArgs['event_id']);
        
        $oEventMeta = new EventPlus_Models_Events_Meta();
        $event_meta_data = $oEventMeta->getAllOptions($this->_invokeArgs['event_id']);
        
        $output = $this->oView->View('front/event/parts/regform',array(
            'event_id' => $this->_invokeArgs['event_id'],
            'event_meta_data' => $event_meta_data,
            'recurr' => isset( $this->_invokeArgs['recurr'] ) ? $this->_invokeArgs['recurr'] : array(),
            'rows' => $eventRow,
        ));

        if ($eventRow) {
            $this->setResponse($output);
        }
    }

}
