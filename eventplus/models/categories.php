<?php

class EventPlus_Models_Categories extends EventPlus_Abstract_Model {

    function __construct() {
        parent::__construct();

        $this->_table = get_option('evr_category');
    }

    function getData($id) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE id = '" . (int) $id . "' LIMIT 1";
        $row = $this->QuickArray($sql);

        if (is_array($row)) {
            return $row;
        } else {
            return false;
        }
    }

    function getDataByIdentifier($category_identifier) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE category_identifier = '" . esc_sql($category_identifier) . "' LIMIT 1";
        $row = $this->QuickArray($sql);

        if (is_array($row)) {
            return $row;
        } else {
            return false;
        }
    }

    function getCategories(array $params = array()) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE 1=1 ";

        if (!empty($params) && is_array($params['id_collection']) && count($params['id_collection'])) {
            $sql .= " AND id IN (" . implode(',', $params['id_collection']) . ") ";
        }

        $sql .= " ORDER BY id ASC";

        return $this->getResults($sql);
    }

    function identifierExists($category_identifier) {
        $sql = "SELECT id FROM " . $this->_table . " WHERE category_identifier = '" . esc_sql($category_identifier) . "' LIMIT 1";
        $row = $this->QuickArray($sql);

        $id = 0;
        if ($row['id'] != null) {
            $id = (int) $row['id'];
        }

        return $id;
    }

    function addCategory($params) {

        $category_name = ($params['category_name']);
        $category_identifier = htmlentities2($params['category_identifier']);
        $category_desc = ($params['category_desc']);
        $display_category_desc = $params['display_desc'];
        $category_background = $params['cat_back'];
        $category_font = $params['cat_text'];

        $errors = array();
        if (trim($category_name) == '') {
            $errors[] = __('Please fill in category name.', 'evrplus_language');
        }

        if (trim($category_identifier) == '') {
            $errors[] = __('Please fill in category unique id.', 'evrplus_language');
        } else if ($this->identifierExists($category_identifier) > 0) {
            $errors[] = __('Category unique id already exists.', 'evrplus_language');
        }

        if (count($errors) > 0) {
            $this->setFormattedMessage($errors);
            return false;
        }

        $sql = array('category_name' => $category_name, 'category_identifier' => $category_identifier, 'category_desc' => $category_desc,
            'display_desc' => $display_category_desc, 'font_color' => $category_font, 'category_color' => $category_background);

        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s');

        if ($this->getWpDb()->insert($this->_table, $sql, $sql_data)) {
            $this->setMessage(__('The category has been added.', 'evrplus_language'));
            return true;
        } else {
            $this->setMessage(__("The category couldn't be added.", 'evrplus_language'));
            return false;
        }
    }

    function editCategory($params, array $dbRow) {

        $category_id = intVal($params['id']);
        $category_name = ($params['category_name']);
        $category_identifier = htmlentities2($params['category_identifier']);
        $category_desc = ($params['category_desc']);
        $display_category_desc = $params['display_desc'];
        $category_background = $params['cat_back'];
        $category_font = $params['cat_text'];

        $errors = array();
        if (trim($category_name) == '') {
            $errors[] = __('Please fill in category name.', 'evrplus_language');
        }

        if (trim($category_identifier) == '') {
            $errors[] = __('Please fill in category unique id.', 'evrplus_language');
        } else if ($params['category_identifier'] != $dbRow['category_identifier']) {
            if ($this->identifierExists($category_identifier) > 0) {
                $errors[] = __('Category unique id already exists.', 'evrplus_language');
            }
        }

        if (count($errors) > 0) {
            $this->setFormattedMessage($errors);
            return false;
        }

        $data = array('category_name' => $category_name, 'category_identifier' => $category_identifier, 'category_desc' => $category_desc,
            'display_desc' => $display_category_desc, 'font_color' => $category_font, 'category_color' => $category_background);

        $where = array('id' => $category_id);

        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s');

        $q = $this->getWpDb()->update($this->_table, $data, $where, $sql_data, array('%d'));

        if ($q === false) {
            $this->setMessage(__("The category couldn't updated.", 'evrplus_language'));
            return false;
        } else {
            $this->setMessage(__('The category has been updated.', 'evrplus_language'));
            return true;
        }
    }

    function deleteCategory($id) {
        return $this->deleteRow('id', $id, __('The category has been deleted.', 'evrplus_language'), __("The category couldn't deleted.", 'evrplus_language'));
    }

    function getCategoriesKeys(array $params = array()) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE 1=1 ";

        if (is_array($params['id_collection']) && count($params['id_collection'])) {
            $sql .= " AND id IN (" . implode(',', $params['id_collection']) . ") ";
        }

        $sql .= " ORDER BY id ASC";

        if (isset($params['limit']) && $params['limit'] > 0) {
            $sql .= " LIMIT " . (int)$params['limit'] . " ";
        }

        return $this->Dataset($sql, 'id');
    }

}
