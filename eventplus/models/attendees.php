<?php

class EventPlus_Models_Attendees extends EventPlus_Abstract_Model {

    function __construct() {
        parent::__construct();

        $this->_table = get_option('evr_attendee');
    }
    
    function numberOfSuccessfulAttendees($event_id) {
        global $wpdb;
        $return_data =  $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE payment_status = '".EventPlus_Models_Payments::PAYMENT_SUCCESS."' AND event_id= %d LIMIT 1", $event_id));
		if($return_data){
			return $return_data;
		}else{
			return 0;
		}	
    }
    
    function getTotalAttendees($event_id) {
        $sql = "SELECT count(1) as totRecords FROM " . $this->_table . " WHERE 1=1 ";
        
        if($event_id > 0){
            $sql .= " AND event_id = '" . (int) $event_id . "'";
        }
        
        $sql .= " LIMIT 1";
        
        $row = $this->QuickArray($sql);

        return $row['totRecords'];
    }
    
    
    function getAttendeesSum($event_id) {
        $sql = "SELECT SUM(quantity) as totQty FROM " . $this->_table . " WHERE payment_status = '".EventPlus_Models_Payments::PAYMENT_SUCCESS."' AND event_id  = '" . (int) $event_id . "'";
        $row = $this->QuickArray($sql);

        return $row['totQty'];
    }
    
    
    
    function getRow($id){
       $query = "SELECT * FROM " . $this->_table . " WHERE id = '" . (int) $id . "' LIMIT 1";
       return $this->getWpDb()->get_results($query, ARRAY_A);
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

    function addAttendee($params, $oEvent) {
        $wpdb = $this->getWpDb();

        $event_id = $params['event_id'];
        $fname = $params['fname'];
        $lname = $params['lname'];
        $address = $params['address'];
        $city = $params['city'];
        $state = $params['state'];
        $zip = $params['zip'];
        $phone = $params['phone'];
        $email = $params['email'];
        $payment = $params['total'];
        $coupon = $_REQEUST['coupon'];
        $reg_type = $params['reg_type'];

        $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . (int) $event_id . " ORDER BY sequence ASC";

        $result = $wpdb->get_results($sql, ARRAY_A);
        $item_order = array();
        foreach ($result as $row) {
            $item_id = $row['id'];
            $item_sequence = $row['sequence'];
            $event_id = $row['event_id'];
            $item_title = $row['item_title'];
            $item_description = $row['item_description'];
            $item_cat = $row['item_cat'];
            $item_limit = $row['item_limit'];
            $item_price = $row['item_price'];
            $free_item = $row['free_item'];
            $item_start_date = $row['item_available_start_date'];
            $item_end_date = $row['item_available_end_date'];
            $item_custom_cur = $row['item_custom_cur'];

            $item_post = str_replace(".", "_", $row['item_price']);
            $item_qty = $params['PROD_' . $event_id . '-' . $item_id . '_' . $item_post];

            if ($item_cat == "REG") {
                $num_people = $num_people + $item_qty;
            }

            $item_info = array('ItemID' => $item_id, 'ItemEventID' => $event_id, 'ItemCat' => $item_cat,
                'ItemName' => $item_title, 'ItemCost' => $item_price, 'ItemCurrency' =>
                $item_custom_cur, 'ItemFree' => $free_item, 'ItemStart' => $item_start_date,
                'ItemEnd' => $item_end_date, 'ItemQty' => $item_qty);
            array_push($item_order, $item_info);
        }

        $ticket_data = serialize($item_order);
        $sql = array('lname' => $lname, 'fname' => $fname, 'address' => $address, 'city' => $city,
            'state' => $state, 'zip' => $zip, 'reg_type' => $reg_type, 'email' => $email,
            'phone' => $phone, 'email' => $email, 'coupon' => $coupon, 'event_id' => $event_id,
            'quantity' => $num_people, 'tickets' => $ticket_data, 'payment' => $payment);


        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

        if ($wpdb->insert(get_option('evr_attendee'), $sql, $sql_data)) {
            $this->setMessage(__('The attendee has been added.', 'evrplus_language'));
            return true;
        } else {
            $this->setMessage(__('There was an error in your submission, please try again. The attendee was not saved!', 'evrplus_language'));
            return false;
        }
    }

    function updateAttendee($params, $dbRow, $oEvent) {
        $wpdb = $this->getWpDb();

        $event_id = (int) $params['event_id'];
        $attendee_id = $params['attendee_id'];
        $num_people = 0;

        $item_order = array();

        //Begin gather registrtion data for database input
        $fname = $params['fname'];
        $lname = $params['lname'];
        $address = $params['address'];
        $city = $params['city'];
        $state = $params['state'];
        $zip = $params['zip'];
        $phone = $params['phone'];
        $email = $params['email'];

        $payment = $params['total'];
        $coupon = $params['coupon'];
        $reg_type = $params['reg_type'];
        $payment_status = $params['payment_status'];
        $attendee_array = $params['attendee'];
        $attendee_list = serialize($attendee_array);


        $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id . " ORDER BY sequence ASC";

        $result = $wpdb->get_results($sql, ARRAY_A);
        foreach ($result as $row) {
            $item_id = $row['id'];
            $item_sequence = $row['sequence'];
            $event_id = $row['event_id'];
            $item_title = $row['item_title'];
            $item_description = $row['item_description'];
            $item_cat = $row['item_cat'];
            $item_limit = $row['item_limit'];
            $item_price = $row['item_price'];
            $free_item = $row['free_item'];
            $item_start_date = $row['item_available_start_date'];
            $item_end_date = $row['item_available_end_date'];
            $item_custom_cur = $row['item_custom_cur'];

            $item_post = str_replace(".", "_", $row['item_price']);
            $item_qty = $params['PROD_' . $event_id . '-' . $item_id . '_' . $item_post];

            if ($item_cat == "REG") {
                $num_people = $num_people + $item_qty;
            }

            $item_info = array('ItemID' => $item_id, 'ItemEventID' => $event_id, 'ItemCat' => $item_cat,
                'ItemName' => $item_title, 'ItemCost' => $item_price, 'ItemCurrency' =>
                $item_custom_cur, 'ItemFree' => $free_item, 'ItemStart' => $item_start_date,
                'ItemEnd' => $item_end_date, 'ItemQty' => $item_qty);
            array_push($item_order, $item_info);
        }

        $ticket_data = serialize($item_order);
        $sql = array(
            'lname' => $lname, 'fname' => $fname, 'address' => $address, 'city' => $city,
            'state' => $state, 'zip' => $zip, 'reg_type' => $reg_type, 'email' => $email, 'payment_status' => $payment_status,
            'phone' => $phone, 'email' => $email, 'coupon' => $coupon, 'event_id' => $event_id,
            'quantity' => $num_people, 'tickets' => $ticket_data, 'payment' => $payment, 'attendees' => $attendee_list);
        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s');

        $update_id = array('id' => $attendee_id);

        if ($wpdb->update($this->_table, $sql, $update_id, $sql_data, array('%d')) === false) {
            $this->setMessage(__("There was an error in your submission, please try again. The attendee was not saved!", 'evrplus_language'));
            return false;
            
        } else {
            $this->setMessage(__('Attendee has been updated.', 'evrplus_language'));
            return true;
        }
    }

    function getRecords($params, $type =  OBJECT) {

        $sql = "SELECT a.*, e.event_name FROM " . $this->_table . " a"
                . " JOIN ".get_option('evr_event')." e on e.id = a.event_id"
                . "  WHERE 1=1  ";

        if ($params['event_id']) {
            $sql .= " AND a.event_id = '" . (int) $params['event_id'] . "'";
        }

        if (!empty($params['payment_status']) && $params['payment_status']) {
            $sql .= " AND a.payment_status = '" . esc_sql($params['payment_status']) . "'";
        }

        $sql .= ' ORDER BY a.id DESC ';

        if ($params['limit_str'] != '') {
            $sql .= ' ' . $params['limit_str'];
        }

        return $this->getResults($sql, $type);
    }
    
   
    function deleteRecord($id) {
        return $this->deleteRow('id', $id, __('Attendee has been deleted.', 'evrplus_language'), __("Attendee couldn't deleted.", 'evrplus_language'));
    }


    function deleteRecordsByEventId($event_id) {
        return $this->deleteRow('event_id', $event_id, __('All the attendee information has been successfully deleted from the event.', 'evrplus_language'), __("Attendees couldn't deleted.", 'evrplus_language'));
    }
    
    function getByEventId($event_id, $params = array()) {
        $sql = "select * from " . $this->_table . " where event_id = '" . (int) $event_id . "'";
        
        if(isset($params['order_by_lname'])){
            $sql .= ' ORDER BY lname DESC';
        }
        
        return $this->getWpDb()->get_results($sql, ARRAY_A);
    }

    function getDataByToken($reg_id, $token){
        $attendee_sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE id=" . (int)$reg_id . " AND token='".  esc_sql($token)."'";

        $data = $this->getWpDb()->get_results($attendee_sql, ARRAY_A);
        if (!$data){
            $data = $this->getWpDb()->get_results("SELECT * FROM " . get_option('evr_attendee') . " WHERE id=" . (int) $reg_id, ARRAY_A);
        }
        
        return $data;
    }
    
    function getDataByPlainToken($token){
        $attendee_sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE token='".  esc_sql($token)."' LIMIT 1";
        $data = $this->getWpDb()->get_results($attendee_sql, ARRAY_A);

        return $data;
    }
}
