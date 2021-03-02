<?php

class eplus_admin_events_items_controller extends EventPlus_Abstract_Controller {

    /**
     * @var EventPlus_Models_Events
     */
    private $_modelEvents = null;
    private $oEvent = null;

    function before() {
        $this->_model = new EventPlus_Models_Events_Items();
        $this->_modelEvents = new EventPlus_Models_Events();

        $event_id = (int) $this->_request->getParam('event_id');

        if ($event_id > 0) {
            $this->oEvent = $this->_modelEvents->getData($event_id);
            $this->oView->oEvent = $this->oEvent;

            if ($this->oEvent->id <= 0) {
                $this->setErrorMessage(__('Invalid event id.', 'evrplus_language'));
                $this->redirect($this->adminUrl('admin_events_items'));
            }
        }
    }

    function index() {

        if (!empty($this->oEvent) && $this->oEvent->id > 0) {
            $record_limit = 200;

            $p = new EventPlus_Pagination();
            $totalRecords = $this->_model->getTotalItems($this->oEvent->id);
            $p->items($totalRecords);
            $p->limit($record_limit); // Limit entries per page
            $p->target($this->adminUrl('admin_events_items', array('event_id' => $this->oEvent->id)));

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

            $rows = $this->_model->getRecords($params);



            $response = $this->oView->loadLayout('admin/layouts/events/items', 'admin/events/items/manage', array(
                'rows' => $rows,
                'p' => $p,
            ));

            $this->setResponse($response);
        } else {
            $response = $this->oView->loadLayout('admin/layouts/events/items', 'admin/events/items/landing');

            $this->setResponse($response);
        }
    }

    function action_add() {


        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            unset($params['id']);
            $response = $this->_model->addItem($params, $this->oEvent);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_events_items', array('event_id' => $this->oEvent->id)));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_events_items', array('method' => 'add', 'event_id' => $this->oEvent->id)));
            }
            return;
        }

        $response = $this->oView->loadLayout('admin/layouts/events/items', 'admin/events/items/form', array(
            'form_heading' => __('Add Item', 'evrplus_language'),
            'button_label' => __('Add Item', 'evrplus_language')
        ));

        $this->setResponse($response);
    }

    function action_edit() {

        $item_id = intVal($this->_request->getParam('item_id'));
        $row = $this->_model->getData($item_id);

        if ($row === false) {
            $this->setErrorMessage(__("Item doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_events_items'));
            return false;
        }

        if ($this->_request->isPost()) {
            $response = $this->_model->updateItem($this->_request->getParams(), $row, $this->oEvent);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_events_items', array(
                            'event_id' => $this->oEvent->id
                )));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_events_items', array(
                            'method' => 'edit',
                            'item_id' => $item_id,
                            'event_id' => $this->oEvent->id
                )));
            }


            return;
        }

        $response = $this->oView->loadLayout('admin/layouts/events/items', 'admin/events/items/form', array(
            'row' => $row,
            'item_id' => $item_id,
            'form_heading' => __("Edit Item", 'evrplus_language'),
            'button_label' => __("Update Item", 'evrplus_language')
        ));


        $this->setResponse($response);
    }

    function action_delete() {

        $item_id = intVal($this->_request->getParam('item_id'));
        $row = $this->_model->getData($item_id);

        if ($row === false) {
            $this->setErrorMessage(__("Item doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_events_items'));
            return false;
        }


        $response = $this->_model->deleteItem($item_id);

        if ($response) {
            $this->setSuccessMessage($this->_model->getMessage());
        } else {
            $this->setErrorMessage($this->_model->getMessage());
        }

        $this->redirect($this->adminUrl('admin_events_items', array('event_id' => $row['event_id'])));
    }

    function action_sort() {

        if ($this->_request->isPost()) {
            $this->_model->sortItems($this->_request->getParams(), $this->oEvent);
            exit;
        }

        $params = $this->_request->getParams();
        $params['event_id'] = $this->oEvent->id;

        $items = $this->_model->getRecords($params);

        $response = $this->oView->loadLayout('admin/layouts/events/items', 'admin/events/items/sort', array(
            'items' => $items,
        ));

        $this->setResponse($response);
    }

    function action_coupon_form() {

        if ($this->_request->isPost()) {
            $response = $this->_modelEvents->updateCoupon($this->_request->getParams());

            if ($response) {
                $this->setSuccessMessage($this->_modelEvents->getMessage());
            } else {
                $this->setErrorMessage($this->_modelEvents->getMessage());
            }

            $this->redirect($this->adminUrl('admin_events_items', array(
                        'event_id' => $this->oEvent->id
            )));

            return;
        }

        $form = $this->oView->View('admin/events/items/coupon_form', array(
            'oEvent' => $this->oEvent
        ));
        $this->setResponse($form);
    }

}
