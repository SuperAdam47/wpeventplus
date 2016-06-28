<?php

class eplus_admin_settings_controller extends EventPlus_Abstract_Controller {

    function before() {
        $this->_model = new EventPlus_Models_Settings();
    }

    function index() {

        if ($this->_request->isPost()) {

            $response = $this->_model->saveSettings($this->_request->getParams());

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
            } else {
                $this->setErrorMessage($this->_model->getMessage());
            }

            $this->redirect($this->adminUrl('admin_settings'));
            return;
        }


        $tabs = array(
            'tab1' => __('Contact', 'evrplus_language'),
            'tab2' => __('Payment', 'evrplus_language'),
            'tab3' => __('Captcha', 'evrplus_language'),
            'tab4' => __('Page Config', 'evrplus_language'),
            'tab5' => __('Confirmation', 'evrplus_language'),
            'tab6' => __('Waitlist', 'evrplus_language'),
            'tab7' => __('Calendar', 'evrplus_language'),
            'tab8' => __('Tax', 'evrplus_language'),
            'tab9' => __('Done', 'evrplus_language'),
        );

        $response = $this->oView->View('admin/settings', array(
            'company_options' => EventPlus_Models_Settings::getSettings(),
            'tabs' => $tabs,
        ));

        $this->setResponse($response);
    }

}
