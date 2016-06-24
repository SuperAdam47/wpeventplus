<?php

class eplus_front_event_parts_regform_controller extends EventPlus_Abstract_Controller {

    function index() {
        
        $output = $this->oView->View('front/event/parts/regform',array(
            'event_id' => $this->_invokeArgs['event_id'],
            'recurr' => $this->_invokeArgs['recurr'],
        ));
        
        $this->setResponse($output);
    }

}
