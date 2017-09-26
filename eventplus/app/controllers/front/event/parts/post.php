<?php

class eplus_front_event_parts_post_controller extends EventPlus_Abstract_Controller {

    function index() {
        
        $output = $this->oView->View('front/event/parts/post');
        
        $this->setResponse($output);
    }

}
