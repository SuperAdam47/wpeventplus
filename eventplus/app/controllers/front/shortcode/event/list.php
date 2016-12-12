<?php

class eplus_front_shortcode_event_list_controller extends EventPlus_Abstract_Controller {

    function index() {
  
        wp_enqueue_style('eventplus-fonts-fa');
        
        $oEvents = new EventPlus_Models_Events();
        $rows = $oEvents->getEventsBySettings();
        

        $viewParams = array(
            'rows' => $rows 
        );
        
        $output = $this->oView->View('front/widgets/shortcode/event/list',$viewParams);  
         
        $this->setResponse($output);
    }

}
