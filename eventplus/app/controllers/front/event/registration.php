<?php

class eplus_front_event_registration_controller extends EventPlus_Abstract_Controller {

    private $company_options = null;
    private $action = 'default';
    private $event_id = '';
    private $actions = array(
        'evrplusegister' => 'register',
        'confirm' => 'confirm',
        'post' => 'processConfirmation',
        'show_confirm_mess' => 'showConfirmation',
        'pay' => 'returnToPay',
        'paypal_txn' => 'paypalTxn',
        'key' => 'processKey',
        'default' => 'defaultAction',
    );

    function before() {
        
        $this->company_options = get_option('evr_company_settings');

        if (isset($_REQUEST['event_id']) && is_numeric($_REQUEST['event_id'])) {
            $this->event_id = (int) $_REQUEST['event_id'];
        }

        parent::before();
    }

    function index() {

        $action = $this->_request->getParam('action', '');

        if ($action && $action != '') {
            $action = strtolower($_REQUEST['action']);

            if (method_exists($this, $this->actions[$action]) && isset($this->actions[$action])) {
                $this->action = $action;
            }
        }
        
        $actionMethod = $this->actions[$this->action];
        $this->$actionMethod();
    }

    protected function register() {

        if (is_numeric($this->event_id)) {

            $output = EventPlus::dispatch('front_event_parts_regform/index', array(
                    'event_id' => $this->event_id
            ));

            $this->setResponse($output);
        } else {
            $this->defaultAction();
        }
    }

    protected function confirm() {

        $output = EventPlus::dispatch('front_event_parts_confirm/index');
        $this->setResponse($output);
    }

    protected function processConfirmation() {
        $output = EventPlus::dispatch('front_event_parts_process/index');
        $this->setResponse($output);
    }

    protected function showConfirmation() {
        $output = EventPlus::dispatch('front_event_parts_confirmation/index');
        $this->setResponse($output);
    }

    protected function returnToPay() {
        $output = EventPlus::dispatch('front_event_parts_pay/index');
        $this->setResponse($output);
    }

    protected function processKey() {
        $str = "<br />";
        $str .= get_option('siteurl') . " - " . get_option('plug-evrplus-activate');
        $str .= "<br />";
        $str .= get_option('siteurl') . " -coordmodule- " . get_option('plug-evrplus_coord-activate');

        $this->setResponse($str);
    }

    protected function paypalTxn() {
        if ($this->company_options['payment_vendor'] == "PAYPAL") {
            $output = EventPlus::dispatch('front_event_parts_paypal/ipn');
            $this->setResponse($output);
        } else {
            $this->setResponse(__('IPN is only available with PAYPAL with this version of Event Reigstration', 'evrplus_language'));
        }
    }

    protected function defaultAction() {

        if ($this->company_options['evrplus_list_format'] == "accordian") {
            $output = EventPlus::dispatch('front_event_parts_list/accordion', array());
            $this->setResponse($output);
        } else {

            $output = EventPlus::dispatch('front_event_parts_list/index', array());
            $this->setResponse($output);
        }
    }

}
