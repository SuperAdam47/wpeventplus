<?php

class EventPlus_Payments_Offline extends EventPlus_Payments {

    function __construct() {
        parent::__construct();
        
        $this->method = EventPlus_Models_Payments::OFFLINE;
    }
    
    protected function valid(){
        
        $valid = false;
  
        if(strtolower($this->companyOptions['checks']) == 'yes'){
            $valid = true;
        }
        
        return $valid;
    }
    
}
