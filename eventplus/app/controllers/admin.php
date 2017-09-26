<?php
class eplus_admin_controller extends EventPlus_Abstract_Controller {
    
    function index() {
       
        $icons = EventPlus::dispatch('admin_dashboard_widgets/icons');
        $portlets = EventPlus::dispatch('admin_dashboard_widgets/portlets');
  
        $output = $this->oView->View('admin/dashboard/index',array(
            'icons' => $icons,
            'portlets' => $portlets,
        ));
        
        $this->setResponse($output);
    }
    

}
