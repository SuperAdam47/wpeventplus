<?php
class eplus_admin_dashboard_widgets_controller extends EventPlus_Abstract_Controller {
    
    function before() { 
        $this->_model = EventPlus::factory('Models_Dashboard');
    }
    
    function index() {}
    
    function action_icons(){
         
        $oStats = EventPlus::factory('Models_Stats');
         
        $icons = $this->oView->View('admin/dashboard/widgets/icons',array(
            'total_payments' => $oStats->get_event_total_payment(),
            'total_events' => $oStats->get_total_events(),
            'total_event_categories' => $oStats->get_total_event_category(),
            'total_attendees' => $oStats->get_total_attendee(),
        ));  
        
        $this->setResponse($icons);
    }
    
    function action_portlets(){
        
        $events = $this->_model->getEvents(5);
        $payments = $this->_model->getPayments(5);
        $attendees = $this->_model->getAttendees(5);
        $categories = $this->_model->getCategories(5);

        $porlets = array();
        
        $porlets['events'] = $this->oView->View('admin/dashboard/widgets/events',array(
            'events' => $events,
        ));
        
        $porlets['payments'] = $this->oView->View('admin/dashboard/widgets/payments',array(
            'payments' => $payments,
        ));
        
        $porlets['attendees'] = $this->oView->View('admin/dashboard/widgets/attendees',array(
            'attendees' => $attendees,
        ));
        
        $porlets['categories'] = $this->oView->View('admin/dashboard/widgets/categories',array(
            'categories' => $categories,
        ));
        
        $this->setResponse($porlets);
    }
    
     function action_events(){
      
        $icons = $this->oView->View('admin/dashboard/events');  
        
        $this->setResponse($icons);
    }
    

}
