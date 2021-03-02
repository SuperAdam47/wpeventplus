<?php

class eplus_admin_payments_controller extends EventPlus_Abstract_Controller {

    /**
     * @var EventPlus_Models_Events
     */
    private $_modelEvents = null;
    private $oEvent = null;

    /**
     * @var EventPlus_Models_Attendees
     */
    private $_modelAttendee = null;

    function before() {
        $this->_model = new EventPlus_Models_Payments();
        $this->_modelAttendee = new EventPlus_Models_Attendees();
        $this->_modelEvents = new EventPlus_Models_Events();

        $event_id = (int) $this->_request->getParam('event_id');

        if ($event_id > 0) {
            $this->oEvent = $this->_modelEvents->getData($event_id);
            $this->oView->oEvent = $this->oEvent;

            if ($this->oEvent->id <= 0) {
                $this->setErrorMessage(__('Invalid event id.', 'evrplus_language'));
                $this->redirect($this->adminUrl('admin_payments'));
            }
        }
    }

    function index() {

        if (!empty($this->oEvent) && $this->oEvent->id > 0) {
            $record_limit = 15;

            $p = new EventPlus_Pagination();
            $totalRecords = $this->_modelAttendee->getTotalAttendees($this->oEvent->id);
            $p->items($totalRecords);
            $p->limit($record_limit); // Limit entries per page
            $p->target($this->adminUrl('admin_payments', array('event_id' => $this->oEvent->id)));

            if (!isset($_GET['paging']) || $_GET['paging'] == 0) {
                $p->page = 1;
            } else {

                $p->page = (int) $_GET['paging'];
            }

            $p->currentPage($p->page);
            $p->calculate(); // Calculates what to show

            $p->parameterName('paging');

            $p->adjacents(1); //No. of page away from the current page

            $limit_str = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;

            $params = $this->_request->getParams();

            $params['event_id'] = $this->oEvent->id;
            $params['limit_str'] = $limit_str;

            $rows = $this->_modelAttendee->getRecords($params, ARRAY_A);

            $response = $this->oView->loadLayout('admin/layouts/payments', 'admin/payments/manage', array(
                'rows' => $rows,
                'p' => $p,
            ));

            $this->setResponse($response);
        } else {
            $response = $this->oView->loadLayout('admin/layouts/attendees', 'admin/payments/landing');

            $this->setResponse($response);
        }
    }

    function action_add() {

        $attendee_id = intVal($this->_request->getParam('attendee_id'));
        $attendee_row = $this->_modelAttendee->getData($attendee_id);

        if ($attendee_row === false) {
            $this->setErrorMessage(__("Attendee doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_payments'));
            return false;
        }

        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $response = $this->_model->addPayment($params, $this->oEvent);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_payments', array('event_id' => $this->oEvent->id)));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_payments', array('method' => 'add', 'event_id' => $this->oEvent->id)));
            }
            return;
        }

        $response = $this->oView->loadLayout('admin/layouts/payments', 'admin/payments/add_form', array(
            'form_heading' => __('Add Payment', 'evrplus_language'),
            'row' => $attendee_row,
        ));

        $this->setResponse($response);
    }

    function action_edit() {

        $payment_id = intVal($this->_request->getParam('id'));
        $row = $this->_model->getData($payment_id);


        if ($row === false) {
            $this->setErrorMessage(__("Payment record doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_payments'));
            return false;
        }

        if ($this->_request->isPost()) {
            $response = $this->_model->updatePayment($this->_request->getParams(), $row, $this->oEvent);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_payments', array(
                            'event_id' => $this->oEvent->id
                )));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_payments', array(
                            'method' => 'edit',
                            'id' => $id,
                            'event_id' => $this->oEvent->id
                )));
            }


            return;
        }
        
        
        $response = $this->oView->loadLayout('admin/layouts/payments', 'admin/payments/edit_form', array(
            'row' => $row,
            'payment_id' => $payment_id,
            'form_heading' => __("Update Payment", 'evrplus_language'),
        ));


        $this->setResponse($response);
    }
    
    function action_view() {

        $payment_id = intVal($this->_request->getParam('id'));
        $row = $this->_model->getData($payment_id);


        if ($row === false) {
            $this->setErrorMessage(__("Payment record doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_payments'));
            return false;
        }

     
        $response = $this->oView->loadLayout('admin/layouts/payments', 'admin/payments/details', array(
            'row' => $row,
            'payment_id' => $payment_id,
            'form_heading' => __("Payment Details", 'evrplus_language'),
        ));


        $this->setResponse($response);
    }

    function action_delete() {

        $id = intVal($this->_request->getParam('id'));
        $row = $this->_model->getData($id);

        if ($row === false) {
            $this->setErrorMessage(__("Record doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_payments'));
            return false;
        }


        $response = $this->_model->deletePayment($id);

        if ($response) {
            $this->setSuccessMessage($this->_model->getMessage());
        } else {
            $this->setErrorMessage($this->_model->getMessage());
        }

        $this->redirect($this->adminUrl('admin_payments', array('event_id' => $row['event_id'])));
    }

    function action_email_reminder() {

        $response = $this->_model->sendEmailReminder($this->oEvent->id);

        if ($response) {
            $this->setSuccessMessage($this->_model->getMessage());
        } else {
            $this->setErrorMessage($this->_model->getMessage());
        }

        $this->redirect($this->adminUrl('admin_payments', array(
                    'event_id' => $this->oEvent->id
        )));
    }

}
