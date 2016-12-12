<?php

class eplus_front_event_parts_list_controller extends EventPlus_Abstract_Controller {

    function index() {
        
        $output = $this->oView->View('front/event/parts/list');
        $this->setResponse($output);
    }

    function action_accordion(){
        $output = $this->oView->View('front/event/parts/accordion');
        $this->setResponse($output);
    }
}
