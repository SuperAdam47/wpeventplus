<?php

class EventPlus_Models_Events_Items extends EventPlus_Abstract_Model {

    function __construct() {
        parent::__construct();
        $this->_table = get_option('evr_cost');
    }

    function getTotalItems($event_id) {
        $sql = "SELECT count(1) as totRecords FROM " . $this->_table . " WHERE event_id = '" . (int) $event_id . "' LIMIT 1";
        $row = $this->QuickArray($sql);
        return $row['totRecords'];
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

    function addItem($params, $oEvent) {
        $wpdb = $this->getWpDb();
        $event_id = (int) $params['event_id'];
        $sequence = $wpdb->get_var("SELECT max(sequence) FROM " . $this->_table  . " where event_id = '$event_id'") + 1;
        $item_title = $params['item_name'];
        $item_description = $params['item_desc'];
        $item_cat = $params['item_cat'];
        if ($item_cat == 'other' and isset($params['item_cat_2']))
            $item_cat = $params['item_cat_2'];
        if ($params['item_limit'] == "") {
            $item_limit = 25;
        } else {
            $item_limit = $params['item_limit'];
        }
        $item_price = $params['item_price'];
        $free_item = $params['item_free'];
        $item_start_month = $params['item_start_month'];
        $item_start_day = $params['item_start_day'];
        $item_start_year = $params['item_start_year'];
        $item_end_month = $params['item_end_month'];
        $item_end_day = $params['item_end_day'];
        $item_end_year = $params['item_end_year'];
        $item_start_date = $item_start_year . "-" . $item_start_month . "-" . $item_start_day;
        $item_end_date = $item_end_year . "-" . $item_end_month . "-" . $item_end_day;
        $item_custom_cur = $params['custom_cur'];

        $sql = array('sequence' => $sequence, 'event_id' => $event_id, 'item_title' => $item_title, 'item_description' => $item_description,
            'item_cat' => $item_cat, 'item_limit' => $item_limit, 'item_price' => $item_price, 'free_item' => $free_item, 'item_available_start_date' => $item_start_date, 'item_available_end_date' => $item_end_date, 'item_custom_cur' => $item_custom_cur);
        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s');
       if ($wpdb->insert( $this->_table, $sql, $sql_data )){ 
            $this->setMessage(__('The event cost/ticket item has been added.', 'evrplus_language'));
            return true;
        } else {
            $this->setMessage(__('There was an error in your submission, please try again. The event cost/ticket item was not saved!', 'evrplus_language'));
            return false;
        }
    }

    function updateItem($params, $dbRow, $oEvent) {

        $wpdb = $this->getWpDb();

        $event_id = (int) $params['event_id'];
        $item_id = $params['item_id'];
        $item_title = $params['item_name'];
        $item_description = $params['item_desc'];
        $item_cat = $params['item_cat'];

        if ($item_cat == 'other' && isset($params['item_cat_2']))
            $item_cat = $params['item_cat_2'];
        if ($params['item_limit'] == "") {
            $item_limit = 25;
        } else {
            $item_limit = $params['item_limit'];
        }

        $item_price = $params['item_price'];
        $free_item = $params['item_free'];
        $item_start_month = $params['item_start_month'];
        $item_start_day = $params['item_start_day'];
        $item_start_year = $params['item_start_year'];
        $item_end_month = $params['item_end_month'];
        $item_end_day = $params['item_end_day'];
        $item_end_year = $params['item_end_year'];
        $item_start_date = $item_start_year . "-" . $item_start_month . "-" . $item_start_day;
        $item_end_date = $item_end_year . "-" . $item_end_month . "-" . $item_end_day;
        $item_custom_cur = $params['custom_cur'];

        $sql = array('event_id' => $event_id, 'item_title' => $item_title, 'item_description' => $item_description,
            'item_cat' => $item_cat, 'item_limit' => $item_limit, 'item_price' => $item_price, 'free_item' => $free_item, 'item_available_start_date' => $item_start_date,
            'item_available_end_date' => $item_end_date, 'item_custom_cur' => $item_custom_cur);

        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s', '%s');
        $update_id = array('id' => $item_id);

        if ($wpdb->update($this->_table, $sql, $update_id, $sql_data, array('%d'))) {
            $this->setMessage(__('The event cost/ticket item has been updated.  You will now be taken back to the Event Pricing Page', 'evrplus_language'));
            return true;
        } else {
            $this->setMessage(__('There was an error in your submission, please try again. The event cost/ticket item was not updated!', 'evrplus_language'));
            return false;
        }
    }

    function getRecords($params) {

        $sql = "SELECT i.* FROM " . $this->_table . " i WHERE 1=1 ";

        if ($params['event_id']) {
            $sql .= " AND i.event_id = '" . (int) $params['event_id'] . "'";
        }

        $sql .= ' ORDER BY i.sequence ASC';

        if ($params['limit_str'] != '') {
            $sql .= ' ' . $params['limit_str'];
        }

        return $this->getResults($sql, ARRAY_A);
    }

    function deleteItem($id) {
        return $this->deleteRow('id', $id, __('Item has been deleted.', 'evrplus_language'), __("Item couldn't deleted.", 'evrplus_language'));
    }

    function sortItems($params) {

        if (is_array($params['item'])) {
            if (count($params['item'])) {
                foreach ($params['item'] as $key => $value) {
                    $this->getWpDb()->query("UPDATE " . $this->_table . " SET sequence = '" . (int) $key . "' WHERE id ='" . (int) $value . "';");
                }
            }
        }

        return true;
    }

}
