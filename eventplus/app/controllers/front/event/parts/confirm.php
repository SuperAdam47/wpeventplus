<?php

class eplus_front_event_parts_confirm_controller extends EventPlus_Abstract_Controller {

    function index() {
        
        $output = $this->oView->View('front/event/parts/confirm');
        
        $this->setResponse($output);
    }

}
