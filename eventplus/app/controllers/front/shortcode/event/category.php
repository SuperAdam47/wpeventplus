<?php

class eplus_front_shortcode_event_category_controller extends EventPlus_Abstract_Controller {

    function index() {

        $event_category_id = $this->_invokeArgs['event_category_id'];
        if ($event_category_id == "") {
            $this->setResponse(__("Category not supplied", "evrplus_language"));
            return;
        }

        $limit = $this->_invokeArgs['limit'];
        $order_by = $this->_invokeArgs['order_by'];
         
        $curdate = date_i18n("Y-m-j");

        $oEvent = new EventPlus_Models_Events();

        $oEventCategories = new EventPlus_Models_Categories();
        $categoryRow = $oEventCategories->getDataByIdentifier($event_category_id);
        if ($categoryRow['id'] <= 0) {
            $this->setResponse(__("Invalid Category supplied", "evrplus_language"));
            return;
        }

        $rows = $oEvent->getEventsByCategory($categoryRow['id'], $order_by, $limit);

        $viewParams = array();
        $viewParams['invoke_params'] = $this->_invokeArgs;
        $viewParams['curdate'] = $curdate;
        $viewParams['rows'] = $rows;
        $viewParams['categoryRow'] = $categoryRow;
        $viewParams['company_options'] = EventPlus_Models_Settings::getSettings();

        $output = $this->oView->View('front/widgets/shortcode/event/category', $viewParams);

        $this->setResponse($output);
    }

}
