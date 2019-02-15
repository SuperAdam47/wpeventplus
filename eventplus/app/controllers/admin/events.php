<?php

class eplus_admin_events_controller extends EventPlus_Abstract_Controller {

    function before() {
        $this->_model = new EventPlus_Models_Events();
    }

    function index() {

        $record_limit = 50;

        $p = new EventPlus_Pagination();
        $totalEvents = $this->_model->getTotalEvents();
        $p->items($totalEvents);
        $p->limit($record_limit); // Limit entries per page
        $p->target($this->adminUrl('admin_events'));
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
        
        $company_options = EventPlus_Models_Settings::getSettings();
        $params['limit_str'] = $limit_str;

        $rows = $this->_model->getEvents($params);
        $category_id_collection = array();

        foreach($rows as $k => $r){
            $category_id = @unserialize($r->category_id);
            
            if(is_array($category_id)){
                $r->category_id = $category_id;
                $category_id_collection = array_merge($category_id_collection,$category_id);
            }
            
            $rows[$k] = $r;
        }
  
        $oCategory = new EventPlus_Models_Categories();
        $event_category_dataset = $oCategory->getCategoriesKeys(array('id_collection' => $category_id_collection));
    
        $response = $this->oView->loadLayout('admin/layouts/events', 'admin/events/manage', array(
            'event_category_dataset' => $event_category_dataset,
            'company_options' => $company_options,
            'rows' => $rows,
            'p' => $p,
        ));

        $this->setResponse($response);
    }

    private function fillFormData($formData) {

        $oCategory = new EventPlus_Models_Categories();
        $formData['categories'] = $oCategory->getCategories();

        return $formData;
    }

    function action_add_redirect() {
        $this->redirect($this->adminUrl('admin_events/add'));
    }
    
    function action_add() {

        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            unset($params['id']);
            $response = $this->_model->addEvent($params);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_events'));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_events', array('method' => 'add')));
            }


            return;
        }

        $formData = $this->fillFormData(array(
            'form_heading' => __("Add Event", 'evrplus_language'),
            'button_label' => __("Add Event", 'evrplus_language'),
        ));

        $response = $this->oView->loadLayout('admin/layouts/events', 'admin/events/form', $formData);
        $this->setResponse($response);
    }

    function action_edit() {

        $id = intVal($this->_request->getParam('id'));
        $row = $this->_model->getData($id);

        
        if ($row === false) {
            $this->setErrorMessage(__("Event doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_events'));
            return false;
        }

        if ($this->_request->isPost()) {
            $response = $this->_model->updateEvent($this->_request->getParams(), $row);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
            } else {
                $this->setErrorMessage($this->_model->getMessage());
            }

            $this->redirect($this->adminUrl('admin_events', array(
                        'method' => 'edit',
                        'id' => $id,
            )));
            return;
        }
        
        
        $oEventMeta = new EventPlus_Models_Events_Meta();
        $row->meta_data = $oEventMeta->getAllOptions($id);

        $formData = $this->fillFormData(array(
            'form_heading' => __("Edit Event", 'evrplus_language'),
            'button_label' => __("Update Event", 'evrplus_language'),
            'row' => $row,
            'meta_data' => $row->meta_data,
        ));
        

        $response = $this->oView->loadLayout('admin/layouts/events', 'admin/events/form', $formData);

        $this->setResponse($response);
    }

    function action_delete() {

        $id = intVal($this->_request->getParam('id'));

        $row = $this->_model->getData($id);

        if ($row === false) {
            $this->setErrorMessage(__("Event doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_events'));
            return false;
        }

        $total_attendees = $this->_model->atendeesCount($id);
        if ($total_attendees > 0) {
            $this->setErrorMessage(__('There are currently ', 'evrplus_language') . $total_attendees . ' ' . __(' attendees registered for this event.  The event cannot be deleted.', 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_events'));
            return false;
        }

        $response = $this->_model->deleteEvent($id);

        if ($response) {
            $this->setSuccessMessage($this->_model->getMessage());
        } else {
            $this->setErrorMessage($this->_model->getMessage());
        }

        $this->redirect($this->adminUrl('admin_events'));
    }

    function action_copy() {

        $id = intVal($this->_request->getParam('id'));

        $row = $this->_model->getData($id);

        if ($row === false) {
            $this->setErrorMessage(__("Event doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_events'));
            return false;
        }

        $response = $this->_model->copyEvent($id);

        if ($response) {
            $this->setSuccessMessage($this->_model->getMessage());
        } else {
            $this->setErrorMessage($this->_model->getMessage());
        }

        $this->redirect($this->adminUrl('admin_events'));
    }
}
