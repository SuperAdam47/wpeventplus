<?php

class eplus_admin_attendees_controller extends EventPlus_Abstract_Controller {

    /**
     * @var EventPlus_Models_Events
     */
    private $_modelEvents = null;
    private $oEvent = null;

    function before() {
        $this->_model = new EventPlus_Models_Attendees();
        $this->_modelEvents = new EventPlus_Models_Events();

        $event_id = (int) $this->_request->getParam('event_id');

        if ($event_id > 0) {
            $this->oEvent = $this->_modelEvents->getData($event_id);
            $this->oView->oEvent = $this->oEvent;

            if ($this->oEvent->id <= 0) {
                $this->setErrorMessage(__('Invalid event id.', 'evrplus_language'));
                $this->redirect($this->adminUrl('admin_attendees'));
            }
        }
    }

    function index() {


        $record_limit = 15;

        $event_id = 0;
        if (is_object($this->oEvent)) {
            $event_id = $this->oEvent->id;
        }

        $p = new EventPlus_Pagination();
        $totalRecords = $this->_model->getTotalAttendees($event_id);
        $p->items($totalRecords);
        $p->limit($record_limit);
        $p->target($this->adminUrl('admin_attendees', array('event_id' => $event_id)));

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
		if(!empty($this->oEvent)){
			$params['event_id'] = $this->oEvent->id;
        }else{
			$params['event_id'] = 0;
		}
		$params['limit_str'] = $limit_str;

        $rows = $this->_model->getRecords($params);

        $response = $this->oView->loadLayout('admin/layouts/attendees', 'admin/attendees/manage', array(
            'rows' => $rows,
            'oEvent' => $this->oEvent,
            'p' => $p,
        ));

        $this->setResponse($response);
    }

    function action_add() {


        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            unset($params['id']);
            $response = $this->_model->addAttendee($params, $this->oEvent);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_attendees', array('event_id' => $this->oEvent->id)));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_attendees', array('method' => 'add', 'event_id' => $this->oEvent->id)));
            }
            return;
        }

        $response = $this->oView->loadLayout('admin/layouts/attendees', 'admin/attendees/add_form', array(
            'form_heading' => __('Add Attendee', 'evrplus_language'),
            'button_label' => __('Add Attendee', 'evrplus_language')
        ));

        $this->setResponse($response);
    }

    function action_edit() {

        $attendee_id = intVal($this->_request->getParam('attendee_id'));
        $row = $this->_model->getData($attendee_id);

        if ($row === false) {
            $this->setErrorMessage(__("Attendee doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_attendees'));
            return false;
        }
        
        $event_id = (int) $this->_request->getParam('event_id');
        if($event_id <= 0){
            $event_id = $row['event_id'];
        }
         
        $this->oEvent = $this->_modelEvents->getData($event_id);
        $this->oView->oEvent = $this->oEvent;

        if ($this->oEvent->id <= 0) {
            $this->setErrorMessage(__('Invalid event id.', 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_attendees'));
        }

        if ($this->_request->isPost()) {
            $response = $this->_model->updateAttendee($this->_request->getParams(), $row, $this->oEvent);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_attendees', array(
                            'event_id' => $this->oEvent->id
                )));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_attendees', array(
                            'method' => 'edit',
                            'attendee_id' => $attendee_id,
                            'event_id' => $this->oEvent->id
                )));
            }


            return;
        }

        $response = $this->oView->loadLayout('admin/layouts/attendees', 'admin/attendees/edit_form', array(
            'row' => $row,
            'attendee_id' => $attendee_id,
            'form_heading' => __("Edit Attendee", 'evrplus_language'),
            'button_label' => __("Update Attendee", 'evrplus_language')
        ));


        $this->setResponse($response);
    }

    function action_details() {

        $attendee_id = intVal($this->_request->getParam('attendee_id'));
        $row = $this->_model->getData($attendee_id);

        if ($row === false) {
            $this->setErrorMessage(__("Attendee doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_attendees'));
            return false;
        }

        $response = $this->oView->loadLayout('admin/layouts/attendees', 'admin/attendees/details', array(
            'row' => $row,
            'attendee_id' => $attendee_id,
            'form_heading' => __("Details Attendee", 'evrplus_language'),
        ));


        $this->setResponse($response);
    }

    function action_delete() {

        $attendee_id = intVal($this->_request->getParam('attendee_id'));
        $row = $this->_model->getData($attendee_id);

        if ($row === false) {
            $this->setErrorMessage(__("Attendee doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_attendees'));
            return false;
        }

		$response = $this->_model->deleteRecord( $attendee_id );

		if( $response ) {
			// Delete attendee answers also
			$this->_model->query("DELETE FROM " . get_option('evr_answer') . " WHERE registration_id = '" . (int) $attendee_id . "'");

			$this->setSuccessMessage($this->_model->getMessage());
		} else {
			$this->setErrorMessage($this->_model->getMessage());
		}

        $this->redirect($this->adminUrl('admin_attendees', array('event_id'=>$row['event_id'])));
    }

    function action_delete_all() {

    	$attendees = $this->_model->getByEventId( $this->oEvent->id );
        $response = $this->_model->deleteRecordsByEventId( $this->oEvent->id );

        if( $response ) {
        	if( !empty($attendees) ) {
        		foreach( $attendees as $attendee ) {
        			if( empty($attendee['id']) ) continue;

             		// Delete attendee answers also
					$this->_model->query("DELETE FROM " . get_option('evr_answer') . " WHERE registration_id = '" . (int) $attendee['id'] . "'");
        		}
        	}

            $this->setSuccessMessage($this->_model->getMessage());
        } else {
            $this->setErrorMessage($this->_model->getMessage());
        }

        $this->redirect($this->adminUrl('admin_attendees', array('event_id' => $this->oEvent->id)));
    }

}
