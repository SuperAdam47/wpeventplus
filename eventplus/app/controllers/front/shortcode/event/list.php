<?php

class eplus_front_shortcode_event_list_controller extends EventPlus_Abstract_Controller {

    function index() {
  
       
        
        $oEvents = new EventPlus_Models_Events();
        $rows = $oEvents->getEventsBySettings();
        

        $viewParams = array(
            'rows' => $rows 
        );
        
        $output = $this->oView->View('front/widgets/shortcode/event/list',$viewParams);  
         
        $this->setResponse($output);
    }

}
