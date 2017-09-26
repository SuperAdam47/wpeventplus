<?php

class eplus_front_event_registration_controller extends EventPlus_Abstract_Controller {

    private $company_options = null;
    private $action = 'default';
    private $event_id = '';
    private $actions = array(
        'evrplusegister' => 'register',
        'confirm' => 'confirm',
        'post' => 'post',
        'confirmation' => 'confirmation',
        'pay' => 'returnToPay',
        'key' => 'processKey',
        'default' => 'defaultAction',
    );

    function before() {
        
        $this->company_options = EventPlus_Models_Settings::getSettings();

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

    protected function post() {
        $output = EventPlus::dispatch('front_event_parts_post/index');
        $this->setResponse($output);
    }

    protected function confirmation() {
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

    protected function defaultAction() {

        if ($this->company_options['evrplus_list_format'] == "accordian") {
            $output = EventPlus::dispatch('front_event_parts_list/accordion', array());
            $this->setResponse($output);
        } else {
            $output = EventPlus::dispatch('front_shortcode_event_list/index', array());
            $this->setResponse($output);
        }
    }

}
