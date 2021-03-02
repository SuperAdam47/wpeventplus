<?php

class eplus_admin_questions_controller extends EventPlus_Abstract_Controller {

    /**
     * @var EventPlus_Models_Events
     */
    private $_modelEvents = null;
    private $oEvent = null;

    function before() {
        $this->_model = new EventPlus_Models_Questions();
        $this->_modelEvents = new EventPlus_Models_Events();

        $event_id = (int) $this->_request->getParam('event_id');

        if ($event_id > 0) {
            $this->oEvent = $this->_modelEvents->getData($event_id);
            $this->oView->oEvent = $this->oEvent;

            if ($this->oEvent->id <= 0) {
                $this->setErrorMessage(__('Invalid event id.', 'evrplus_language'));
                $this->redirect($this->adminUrl('admin_questions'));
            }
        }
    }

    function index() {

        if (!empty($this->oEvent) && $this->oEvent->id > 0) {
            $record_limit = 100;

            $p = new EventPlus_Pagination();
            $totalRecords = $this->_model->getTotalQuestions($this->oEvent->id);
            $p->items($totalRecords);
            $p->limit($record_limit); // Limit entries per page
            $p->target($this->adminUrl('admin_questions', array('event_id' => $this->oEvent->id)));

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

            $response = $this->oView->loadLayout('admin/layouts/questions', 'admin/questions/manage', array(
                'rows' => $rows,
                'p' => $p,
            ));

            $this->setResponse($response);
        } else {
            $response = $this->oView->loadLayout('admin/layouts/questions', 'admin/questions/landing');

            $this->setResponse($response);
        }
    }

    function action_add() {


        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            unset($params['id']);
            $response = $this->_model->addQuestion($params, $this->oEvent);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_questions', array('event_id' => $this->oEvent->id)));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_questions', array('method' => 'add', 'event_id' => $this->oEvent->id)));
            }
            return;
        }

        $response = $this->oView->loadLayout('admin/layouts/questions', 'admin/questions/form', array(
            'form_heading' => __('ADD QUESTION', 'evrplus_language'),
            'button_label' => __('ADD QUESTION', 'evrplus_language')
        ));

        $this->setResponse($response);
    }

    function action_edit() {

        $question_id = intVal($this->_request->getParam('question_id'));
        $row = $this->_model->getData($question_id);

        if ($row === false) {
            $this->setErrorMessage(__("Question doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_questions'));
            return false;
        }

        if ($this->_request->isPost()) {
            $response = $this->_model->updateQuestion($this->_request->getParams(), $row, $this->oEvent);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_questions', array(
                            'event_id' => $this->oEvent->id
                )));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_questions', array(
                            'method' => 'edit',
                            'question_id' => $question_id,
                            'event_id' => $this->oEvent->id
                )));
            }


            return;
        }

        $response = $this->oView->loadLayout('admin/layouts/questions', 'admin/questions/form', array(
            'row' => $row,
            'question_id' => $question_id,
            'form_heading' => __("Edit Question", 'evrplus_language'),
            'button_label' => __("Update Question", 'evrplus_language')
        ));


        $this->setResponse($response);
    }

    function action_delete() {

        $question_id = intVal($this->_request->getParam('question_id'));
        $row = $this->_model->getData($question_id);

        if ($row === false) {
            $this->setErrorMessage(__("Question doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_questions'));
            return false;
        }


        $response = $this->_model->deleteQuestion($question_id);

        if ($response) {
            $this->setSuccessMessage($this->_model->getMessage());
        } else {
            $this->setErrorMessage($this->_model->getMessage());
        }

        $this->redirect($this->adminUrl('admin_questions', array('event_id' => $row['event_id'])));
    }

    function action_sort() {

        if ($this->_request->isPost()) {
            $this->_model->sortQuestions($this->_request->getParams(), $this->oEvent);
            exit;
        }

        $params = $this->_request->getParams();
        $params['event_id'] = $this->oEvent->id;

        $questions = $this->_model->getRecords($params);

        $response = $this->oView->loadLayout('admin/layouts/questions', 'admin/questions/sort', array(
            'questions' => $questions,
        ));

        $this->setResponse($response);
    }

}
