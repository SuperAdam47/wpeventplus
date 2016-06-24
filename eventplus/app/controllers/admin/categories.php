<?php

class eplus_admin_categories_controller extends EventPlus_Abstract_Controller {

    function before() {
        $this->_model = new EventPlus_Models_Categories();
    }

    function index() {

        $categories = $this->_model->getCategories();

        $response = $this->oView->loadLayout('admin/layouts/categories', 'admin/categories/manage', array(
            'categories' => $categories
        ));

        $this->setResponse($response);
    }

    function action_add() {

        $response = $this->oView->loadLayout('admin/layouts/categories', 'admin/categories/form', array(
            'form_heading' => __("Add Category", 'evrplus_language'),
            'button_label' => __("Add Category", 'evrplus_language'),
        ));

        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            unset($params['id']);
            $response = $this->_model->addCategory($params);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_categories'));
            } else {
                $this->setErrorMessage($this->_model->getMessage());
                $this->redirect($this->adminUrl('admin_categories', array('method' => 'add')));
            }


            return;
        }

        $this->setResponse($response);
    }

    function action_edit() {

        $id = intVal($this->_request->getParam('id'));
        $row = $this->_model->getData($id);

        if ($row === false) {
            $this->setErrorMessage(__("Category doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_categories'));
            return false;
        }

        $response = $this->oView->loadLayout('admin/layouts/categories', 'admin/categories/form', array(
            'row' => $row,
            'form_heading' => __("Edit Category", 'evrplus_language'),
            'button_label' => __("Update Category", 'evrplus_language')
        ));

        if ($this->_request->isPost()) {
            $response = $this->_model->editCategory($this->_request->getParams(), $row);

            if ($response) {
                $this->setSuccessMessage($this->_model->getMessage());
            } else {
                $this->setErrorMessage($this->_model->getMessage());
            }

            $this->redirect($this->adminUrl('admin_categories', array(
                        'method' => 'edit',
                        'id' => $id,
            )));
            return;
        }

        $this->setResponse($response);
    }

    function action_delete() {

        $id = intVal($this->_request->getParam('id'));
        $row = $this->_model->getData($id);

        if ($row === false) {
            $this->setErrorMessage(__("Category doesn't exist.", 'evrplus_language'));
            $this->redirect($this->adminUrl('admin_categories'));
            return false;
        }


        $response = $this->_model->deleteCategory($id);

        if ($response) {
            $this->setSuccessMessage($this->_model->getMessage());
        } else {
            $this->setErrorMessage($this->_model->getMessage());
        }

        $this->redirect($this->adminUrl('admin_categories'));
    }

}
