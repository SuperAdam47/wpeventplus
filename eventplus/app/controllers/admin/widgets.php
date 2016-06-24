<?php

class eplus_admin_widgets_controller extends EventPlus_Abstract_Controller {

    function index() {
        
    }

    function action_flash_messages() {
        $response = $this->oView->View('admin/widgets/flash_messages', array(
            'flash_messages' => $this->_invokeArgs['flash_messages']
        ));
        $this->setResponse($response);
    }

    function action_messages() {
        $response = $this->oView->View('admin/widgets/messages', array(
            'messages' => (array) $this->_invokeArgs['messages']
        ));
        $this->setResponse($response);
    }

    function action_quick_links() {

        $links = $this->_invokeArgs['links'];
 
        $settings_link = "<a href='".$this->oView->adminUrl('admin_settings')."'>".__('Settings', 'evrplus_language')."</a>";
        $events_link = "<a href='".$this->oView->adminUrl('admin_events')."'>".__('Events', 'evrplus_language')."</a>";

        array_unshift($links, $settings_link, $events_link); // before other links

        $this->setResponse($links);
    }

}
