<?php

class eplus_front_event_parts_pay_controller extends EventPlus_Abstract_Controller {

    function index() {
        
        $output = $this->oView->View('front/event/parts/pay');
        
        $this->setResponse($output);
    }

}
