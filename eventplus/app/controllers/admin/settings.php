<?php

class eplus_admin_settings_controller extends EventPlus_Abstract_Controller {

    function before() {
        $this->_model = new EventPlus_Models_Settings();
    }

    function index() {

        if (isset($_GET['hide_ad'])) {
            if ($_GET['hide_ad'] == 1) {
                update_option('eventplus_hide_ads', 1);
                $this->setSuccessMessage('Ad has been disabled.');
                $this->redirect($this->adminUrl('admin_settings'));
            }
        }

        if ($this->_request->isPost()) {

            $params = $this->_request->getParams();
            $response = $this->_model->saveSettings($params);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
            } else {
                $this->setErrorMessage($this->_model->getMessage());
            }

            //$params
            $currentTab = 'tab1_contact';
            if (isset($params['eplus_current_tab'])) {
                $currentTab = $params['eplus_current_tab'];
            }
            $this->redirect($this->adminUrl('admin_settings', array('ct' => $currentTab)));
            return;
        }


        $tabs = EventPlus_Helpers_Admin_Menu::getSettingTabs();

        $response = $this->oView->View('admin/settings', array(
            'company_options' => EventPlus_Models_Settings::getSettings(),
            'tabs' => $tabs,
        ));

        $this->setResponse($response);
    }

}
