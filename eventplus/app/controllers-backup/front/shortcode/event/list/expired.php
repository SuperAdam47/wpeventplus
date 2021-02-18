<?php

class eplus_front_shortcode_event_list_expired_controller extends EventPlus_Abstract_Controller {

    function index() {


        $shortcode_params = array('limit' => 0);
        if (isset($this->_invokeArgs['shortcode_attributes'])) {
            $shortcode_params = $this->_invokeArgs['shortcode_attributes'];
        }

        $event_category_id = 0;
        if ($shortcode_params['event_category_id'] > 0) {
            $event_category_id = $shortcode_params['event_category_id'];
        }

        $oExpiredEvents = new EventPlus_Models_Events_Expired();
        $rows = $oExpiredEvents->getExpiredEventsBySettings($shortcode_params);

        $viewParams = array(
            'rows' => $rows
        );

        $output = $this->oView->View('front/widgets/shortcode/event/list/expired', $viewParams);

        $this->setResponse($output);
    }

}
