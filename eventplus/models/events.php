<?php

class EventPlus_Models_Events extends EventPlus_Abstract_Model {

    function __construct() {
        parent::__construct();

        $this->_table = get_option('evr_event');
    }

    function getData($event_id, $type = OBJECT) {

        $sql = "SELECT * FROM " . $this->_table . " WHERE id = '" . (int) $event_id . "' LIMIT 1";
        $row = $this->getWpDb()->get_row($sql, $type);

        return $row;
    }

    function getRow($event_id) {

        $sql = "SELECT * FROM " . $this->_table . " WHERE id = '" . (int) $event_id . "' LIMIT 1";
        return $this->QuickArray($sql);
    }

    function getEventsByCategoryId($category_id) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE category_id LIKE '%\"" . esc_sql($category_id) . "\"%' AND str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')";
        return $this->getWpDb()->get_results($sql, ARRAY_A);
    }
    
    function getEventsByCategory($category_id, $order_by = '' , $limit = 0) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE category_id LIKE '%\"" . esc_sql($category_id) . "\"%' AND str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')";
        
        if($order_by != ''){
            if(in_array(strtolower($order_by),array('asc','desc'))){
                $sql .= " " . $order_by;
            }
        }
        
        if($limit > 0){
            $sql .= " LIMIT " . (int) $limit;
        }
        
        return $this->getWpDb()->get_results($sql);
    }

    function addEvent($params) {

        $event_name = ($params['event_name']);
        $disable_event_reg = strtoupper($params['disable_event_reg']);
        $event_identifier = ($params['event_identifier']);
        $display_desc = 'Y';  // Y or N
        $event_desc = ($params['event_desc']);
        if (!empty($params['event_category'])) {
            $event_category = serialize($params['event_category']);
        } else {
            $event_category = "";
        }

        $reg_limit = $params['reg_limit'];
        $event_location = stripslashes( $params['event_location'] );
        $event_address = stripslashes( $params['event_street'] );
        $event_city = stripslashes( $params['event_city'] );
        $event_state = stripslashes( $params['event_state'] );
        $event_postal = $params['event_postcode'];
        if (!empty($params['location_list'])) {
            $location_list = $params['location_list'];
        } else {
            $location_list = "";
        }


        $google_map = $params['google_map'];  // Y or N
        $start_month = $params['start_month'];
        $start_day = $params['start_day'];
        $start_year = $params['start_year'];
        $end_month = $params['end_month'];
        $end_day = $params['end_day'];
        $infinate_event = isset($params['infinate_event']) ? $params['infinate_event'] : '';
         if (!empty($infinate_event) && $infinate_event = 'yes'){
            $end_year = 2050;
        } else {
            $end_year = $params['end_year'];
        }

        $start_time = $params['start_time'];
        $end_time = $params['end_time'];
        $recurrence_choice = $params['recurring_choice'];
        $recurrence_period = $params['recurring_period'];
        $recurrence_frequency = $params['recurrence_frequency'];
        $recurrence_repeat_period = $params['recurrence_repeat_period'];
        $close = $params['close'];
        $allow_checks = $params['allow_checks'];
        $counter_checks = $params['counter_checks'];
        $outside_reg = $params['outside_reg'];  // Yor N
        $external_site = $params['external_site'];


        if (!empty($params['reg_form_defaults'])) {
            $reg_form_defaults = serialize($params['reg_form_defaults']);
        } else {
            $reg_form_defaults = "";
        }

        $more_info = $params['more_info'];
        $image_link = $params['image_link'];
        $header_image = $params['header_image'];

        if (!empty($params['event_cost'])) {
            $event_cost = $params['event_cost'];
        } else {
            $event_cost = "";
        }


        if (!empty($params['is_active'])) {
            $is_active = $params['is_active'];
        } else {
            $is_active = "";
        }
        $send_mail = $params['send_mail'];  // Y or N

        $conf_mail = ($params['conf_mail']);
        //build start date
        $start_date = $start_year . "-" . $start_month . "-" . $start_day;
        //build end date
        $end_date = $end_year . "-" . $end_month . "-" . $end_day;
        //set reg limit if not set
        if ($reg_limit == '') {
            $reg_limit = 999;
        }
        //added ver 6.00.13 

        if (!empty($params['send_coord'])) {
            $send_coord = $params['send_coord'];
        } else {
            $send_coord = "";
        }
        // Y or N




        if (!empty($params['coord_email'])) {
            $coord_email = $params['coord_email'];
        } else {
            $coord_email = "";
        }

        $coord_msg = $params['event_country'];



        if (!empty($params['coord_pay_msg'])) {
            $coord_pay_msg = ($params['coord_pay_msg']);
        } else {
            $coord_pay_msg = "";
        }

        if (!empty($params['term_c'])) {
            $term_c = $params['term_c'];
        } else {
            $term_c = "";
        }

        if (!empty($params['custom_cur'])) {
            $custom_cur = $params['custom_cur'];
        } else {
            $custom_cur = "";
        }


        // Y or N
        $term_desc = esc_sql($params['term_desc']);

        $count = $this->getWpDb()->get_var("SELECT COUNT(*) as count
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE   TABLE_NAME ='" . $this->_table . "' AND
                            COLUMN_NAME = 'counter_checks'");

        if ($count == 0) {
            $this->getWpDb()->query("ALTER TABLE " . $this->getWpDb()->prefix . "evr_event ADD counter_checks varchar(45)");
        }

        $count2 = $this->getWpDb()->get_var("SELECT COUNT(*) as count
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE   TABLE_NAME ='" . $this->_table . "' AND
                            COLUMN_NAME = 'infinate_event'");

        if ($count2 == 0) {
            $this->getWpDb()->query("ALTER TABLE " . $this->getWpDb()->prefix . "evr_event ADD infinate_event varchar(45)");
        }

        $sqlData = array(
            'event_name' => "$event_name",
            'disable_event_reg' => "$disable_event_reg",
            'event_desc' => "$event_desc",
            'location_list' => "$location_list",
            'event_location' => "$event_location",
            'event_address' => "$event_address",
            'event_city' => "$event_city",
            'event_state' => "$event_state",
            'event_postal' => "$event_postal",
            'google_map' => "$google_map",
            'outside_reg' => "$outside_reg",
            'external_site' => "$external_site",
            'display_desc' => "$display_desc",
            'image_link' => "$image_link",
            'header_image' => "$header_image",
            'event_identifier' => "$event_identifier",
            'more_info' => "$more_info",
            'start_month' => "$start_month",
            'start_day' => "$start_day",
            'start_year' => "$start_year",
            'start_time' => "$start_time",
            'start_date' => "$start_date",
            'end_month' => "$end_month",
            'end_day' => "$end_day",
            'end_year' => "$end_year",
            'end_date' => "$end_date",
            'end_time' => "$end_time",
            'infinate_event' => "$infinate_event",
            'recurrence_choice' => "$recurrence_choice",
            'recurrence_period' => "$recurrence_period",
            'recurrence_frequency' => "$recurrence_frequency",
            'recurrence_repeat_period' => "$recurrence_repeat_period",
            'close' => "$close",
            'reg_limit' => "$reg_limit",
            'custom_cur' => "$custom_cur",
            'reg_form_defaults' => "$reg_form_defaults",
            'allow_checks' => "$allow_checks",
            'counter_checks' => "$counter_checks",
            'send_mail' => "$send_mail",
            'conf_mail' => "$conf_mail",
            'is_active' => "$is_active",
            'category_id' => "$event_category",
            'send_coord' => "$send_coord",
            'coord_email' => "$coord_email",
            'coord_msg' => "$coord_msg",
            'coord_pay_msg' => "$coord_pay_msg",
            'term_c' => "$term_c",
            'term_desc' => "$term_desc");

        $sqlFormat = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
            '%s', '%s', '%s', '%s');

        $message = '';
        $response = null;
        if ($this->getWpDb()->insert($this->_table, $sqlData, $sqlFormat)) {
            $response = true;
            $message = __('The event ', 'evrplus_language') . ' ' . stripslashes($params['event_name']) . ' ' . __(' has been added.', 'evrplus_language');

            $event_id = $this->db->getInsertID();

            $oMeta = new EventPlus_Models_Events_Meta();
            $oMeta->updateOption($event_id, 'qty_discount', $params['qty_discount']);
            $oMeta->updateOption($event_id, 'qty_discount_settings', (array) $params['qty_discount_settings']);
            $oMeta->updateOption($event_id, 'show_register_button', $params['show_register_button']);
            $oMeta->updateOption($event_id, 'term_c_force', $params['term_c_force']);
            $oMeta->updateOption($event_id, 'skip_step_2', $params['skip_step_2']);
            $oMeta->updateOption($event_id, 'event_coordinator',  addslashes($params['event_coordinator']));

            $closure_day_date = '';
            $closure_day_time = '';
            if($params['close'] == 'selected_day') {
                $closure_day_date_month = $params['closure_day_date_month'];
                $closure_day_date_day = $params['closure_day_date_day'];
                $closure_day_date_year = $params['closure_day_date_year'];
                $closure_day_date = $closure_day_date_year . "-" . $closure_day_date_month . "-" . $closure_day_date_day;
                $closure_day_time = $params['closure_day_time'];
            }

            $oMeta->updateOption($event_id, 'closure_day_date', $closure_day_date);
            $oMeta->updateOption($event_id, 'closure_day_time', $closure_day_time);

        } else {
            $response = false;
            $message = __('There was an error in your submission, please try again. The event was not saved!', 'evrplus_language');
        }


        $this->setMessage($message);

        return $response;
    }

    function updateEvent($params, $dbRow) {

        $event_id = (int) $params['id'];

        $event_name = ($params['event_name']);
        $disable_event_reg = strtoupper($params['disable_event_reg']);
        $event_identifier = ($params['event_identifier']);
        $display_desc = 'Y';  // Y or N
        $event_desc = $params['event_desc'];

        if (!empty($params['event_category'])) {
            $event_category = serialize($params['event_category']);
        } else {
            $event_category = "";
        }

        $reg_limit = $params['reg_limit'];

        if (!empty($params['location_list'])) {
            $location_list = $params['location_list'];
        } else {
            $location_list = "";
        }

        $event_location = esc_sql( stripslashes($params['event_location']) );
        $event_address = stripslashes( $params['event_street'] );
        $event_city = stripslashes( $params['event_city'] );
        $event_state = stripslashes( $params['event_state'] );
        $event_postal = $params['event_postcode'];

        $google_map = $params['google_map'];  // Y or N

        $start_month = $params['start_month'];
        $start_day = $params['start_day'];
        $start_year = $params['start_year'];
        $end_month = $params['end_month'];
        $end_day = $params['end_day'];
        $infinate_event = isset($params['infinate_event']) ? $params['infinate_event'] : '';

        if (!empty($infinate_event) && $infinate_event = 'yes')
            $end_year = 2050;
        else
            $end_year = $params['end_year'];

        $start_time = $params['start_time'];
        $end_time = $params['end_time'];
        $recurrence_choice = $params['recurring_choice'];
        $recurrence_period = $params['recurring_period'];
        $recurrence_frequency = $params['recurrence_frequency'];
        $recurrence_repeat_period = $params['recurrence_repeat_period'];

        $close = $params['close'];
        $allow_checks = $params['allow_checks'];
        $counter_checks = $params['counter_checks'];
        $outside_reg = $params['outside_reg'];  // Y or N
        $external_site = $params['external_site'];

        if (!empty($params['reg_form_defaults'])) {
            $reg_form_defaults = serialize($params['reg_form_defaults']);
        } else {
            $reg_form_defaults = "";
        }

        $more_info = $params['more_info'];
        $image_link = $params['image_link'];
        $header_image = $params['header_image'];

        if (!empty($params['is_active'])) {
            $is_active = $params['is_active'];
        } else {
            $is_active = "";
        }

        $send_mail = $params['send_mail'];  // Y or N
        $conf_mail = ($params['conf_mail']);
        //build start date
        $start_date = $start_year . "-" . $start_month . "-" . $start_day;
        //build end date
        $end_date = $end_year . "-" . $end_month . "-" . $end_day;
        //set reg limit if not set
        if ($reg_limit == '') {
            $reg_limit = 999999;
        }

        if (!empty($params['send_coord'])) {
            $send_coord = $params['send_coord'];
        } else {
            $send_coord = "";
        }
        if (!empty($params['coord_email'])) {
            $coord_email = $params['coord_email'];
        } else {
            $coord_email = "";
        }

        $coord_msg = $params['event_country'];
        if (!empty($params['coord_pay_msg'])) {
            $coord_pay_msg = $params['coord_pay_msg'];
        } else {
            $coord_pay_msg = "";
        }
        if (!empty($params['term_c'])) {
            $term_c = $params['term_c'];
        } else {
            $term_c = "";
        }

        if (!empty($params['custom_cur'])) {
            $custom_cur = $params['custom_cur'];
        } else {
            $custom_cur = "";
        }

        $term_desc = stripslashes($params['term_desc']);

        $sql = array(
            'event_name' => $event_name,
            'disable_event_reg' => "$disable_event_reg",
            'event_desc' => $event_desc,
            'location_list' => $location_list,
            'event_location' => $event_location,
            'event_address' => $event_address,
            'event_city' => $event_city,
            'event_state' => $event_state,
            'event_postal' => $event_postal,
            'google_map' => $google_map,
            'outside_reg' => $outside_reg,
            'external_site' => $external_site,
            'display_desc' => $display_desc,
            'image_link' => $image_link,
            'header_image' => $header_image,
            'event_identifier' => $event_identifier,
            'more_info' => $more_info,
            'start_month' => $start_month,
            'start_day' => $start_day,
            'start_year' => $start_year,
            'start_time' => $start_time,
            'start_date' => $start_date,
            'end_month' => $end_month,
            'end_day' => $end_day,
            'end_year' => $end_year,
            'end_date' => $end_date,
            'end_time' => $end_time,
            'recurrence_choice' => $recurrence_choice,
            'recurrence_period' => $recurrence_period,
            'recurrence_frequency' => $recurrence_frequency,
            'recurrence_repeat_period' => $recurrence_repeat_period,
            'close' => $close,
            'reg_limit' => $reg_limit,
            'custom_cur' => $custom_cur,
            'reg_form_defaults' => $reg_form_defaults,
            'allow_checks' => $allow_checks,
            'counter_checks' => $counter_checks,
            'send_mail' => $send_mail,
            'conf_mail' => $conf_mail,
            'is_active' => $is_active,
            'category_id' => $event_category,
            'send_coord' => $send_coord,
            'coord_email' => $coord_email,
            'coord_msg' => $coord_msg,
            'coord_pay_msg' => $coord_pay_msg,
            'term_c' => $term_c,
            'term_desc' => $term_desc);


        $response = null;
        $update_id = array('id' => $event_id);
        if ($this->getWpDb()->update($this->_table, $sql, $update_id) === false) {
            $response = false;
            $message = __('There was an error in your submission, please try again. The event was not saved!', 'evrplus_language');
        } else {
            $response = true;
            $message = __('The Event details saved for ', 'evrplus_language') . ' ' . stripslashes($params['event']) . ' ' . __(' has been updated!', 'evrplus_language');
        }


        $oMeta = new EventPlus_Models_Events_Meta();
        $oMeta->updateOption($event_id, 'qty_discount', $params['qty_discount']);
        $oMeta->updateOption($event_id, 'qty_discount_settings', (array) $params['qty_discount_settings']);
        $oMeta->updateOption($event_id, 'show_register_button', $params['show_register_button']);
        $oMeta->updateOption($event_id, 'term_c_force', $params['term_c_force']);
        $oMeta->updateOption($event_id, 'skip_step_2', $params['skip_step_2']);
        $oMeta->updateOption($event_id, 'event_coordinator', addslashes($params['event_coordinator']));

        $closure_day_date = '';
        $closure_day_time = '';
        if($params['close'] == 'selected_day') {
            $closure_day_date_month = $params['closure_day_date_month'];
            $closure_day_date_day = $params['closure_day_date_day'];
            $closure_day_date_year = $params['closure_day_date_year'];
            $closure_day_date = $closure_day_date_year . "-" . $closure_day_date_month . "-" . $closure_day_date_day;
            $closure_day_time = $params['closure_day_time'];
        }

        $oMeta->updateOption($event_id, 'closure_day_date', $closure_day_date);
        $oMeta->updateOption($event_id, 'closure_day_time', $closure_day_time);

        $this->setMessage($message);

        return $response;
    }

    function getTotalEvents(array $params = array()) {

        $sql = "SELECT count(1) as totEvents FROM " . $this->_table . " LIMIT 1";

        $row = $this->QuickArray($sql);

        return $row['totEvents'];
    }

    function getEvents($params) {

        $orderby = " ORDER BY str_to_date(start_time,'%h:%i%p') ";
		$orderby2 = "";
        
		if (!empty($params['sort'])) {
            $orderby = ' ORDER BY ' . $params['sort'];

            if ($params['sort'] == 'start_date') {
                $orderby = " ORDER BY DATE(start_date) ";
            }

            if (in_array(strtolower($params['sort_direction']), array('asc', 'desc'))) {
                $orderby .= ' ' . $params['sort_direction'];
            }
        }


        if (!empty($params['company_options']['order_event_list'])) {
            $option = $params['company_options']['order_event_list'];
            $orderby2 = " $option ";
        }

        //check database for number of records with date of today or in the future
        $sql = "SELECT * FROM " . $this->_table . $orderby . $orderby2;



        if ($params['limit_str'] != '') {
            $sql .= ' ' . $params['limit_str'];
        }



        return $this->getResults($sql);
    }

    function atendeesCount($event_id) {
        $sql = "SELECT count(1) as totCount FROM " . get_option('evr_attendee') . " WHERE event_id= '" . (int) $event_id . "' LIMIT 1";
        $row = $this->QuickArray($sql);

        return $row['totCount'];
    }

    function deleteEvent($event_id) {

        $q = $this->deleteRow('id', $event_id, __('The event has been deleted.', 'evrplus_language'), __("The event couldn't deleted.", 'evrplus_language'));

        if ($q) {
            $this->getWpDb()->query($this->getWpDb()->prepare(" DELETE FROM " . get_option('evr_eventplusmeta') . " WHERE event_id = %d", $event_id));
            $this->getWpDb()->query($this->getWpDb()->prepare(" DELETE FROM " . get_option('evr_question') . " WHERE event_id = %d", $event_id));
            $this->getWpDb()->query($this->getWpDb()->prepare(" DELETE FROM " . get_option('evr_cost') . " WHERE event_id = %d", $event_id));
        }

        return $q;
    }

    function copyEvent($event_id) {

        $sql = "SELECT * FROM " . $this->_table . " WHERE id =" . $event_id;

        $result = $this->getWpDb()->get_results($sql, ARRAY_A);
        foreach( $result as $row ) {
        	
            $event_name = "Copy of " . $row['event_name'];
            $event_identifier = "CPY-" . $row['event_identifier'];
            $event_desc = $row['event_desc'];
            $image_link = $row['image_link'];
            $header_image = $row['header_image'];
            $display_desc = $row['display_desc'];
            $event_location = $row['event_location'];
            $event_address = $row['event_address'];
            $event_city = $row['event_city'];
            $event_postal = $row['event_postal'];
            $event_state = $row['event_state'];
            $more_info = $row['more_info'];
            $reg_limit = $row['reg_limit'];
            $event_cost = $row['event_cost'];
            $allow_checks = $row['allow_checks'];
            $counter_checks = $row['counter_checks'];
            $is_active = $row['is_active'];
            $start_month = $row['start_month'];
            $start_day = $row['start_day'];
            $start_year = $row['start_year'];
            $end_month = $row['end_month'];
            $end_day = $row['end_day'];
            $end_year = $row['end_year'];
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];
            $conf_mail = $row['conf_mail'];
            $send_mail = $row['send_mail'];
            $event_category = $row['category_id'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $reg_form_defaults = $row['reg_form_defaults'];
            $use_coupon = $row['use_coupon'];
            $coupon_code = $row['coupon_code'];
            $coupon_code_price = $row['coupon_code_price'];
            $term_c = $row['term_c'];
            $term_desc = $row['term_desc'];
            $recurrence_choice = $row['recurrence_choice'];
            $google_map = $row['google_map'];

            $sql = array( 'event_name' => $event_name, 'event_desc' => $event_desc, 'event_location' => $event_location, 'event_address' => $event_address,
                'event_city' => $event_city, 'event_state' => $event_state, 'event_postal' => $event_postal, 'google_map' => $google_map, 'display_desc' => $display_desc,
                'image_link' => $image_link, 'header_image' => $header_image, 'event_identifier' => $event_identifier, 'more_info' => $more_info,
                'start_month' => $start_month, 'start_day' => $start_day, 'start_year' => $start_year, 'start_time' => $start_time, 'start_date' => $start_date,
                'end_month' => $end_month, 'end_day' => $end_day, 'end_year' => $end_year, 'end_date' => $end_date, 'end_time' => $end_time, 'reg_limit' => $reg_limit,
                'custom_cur' => $custom_cur, 'reg_form_defaults' => $reg_form_defaults, 'allow_checks' => $allow_checks,
                'send_mail' => $send_mail, 'conf_mail' => $conf_mail, 'is_active' => $is_active, 'category_id' => $event_category, 'use_coupon' => $use_coupon,
                'coupon_code' => $coupon_code, 'coupon_code_price' => $coupon_code_price, 
                'term_c' => $term_c, 'term_desc' => $term_desc, 'recurrence_choice' => $recurrence_choice );

            $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );

            $messages = array();
            if( $this->getWpDb()->insert(get_option('evr_event'), $sql, $sql_data) ) {

                $lastID = $this->getWpDb()->insert_id;

                // Get event meta
				$oMeta = new EventPlus_Models_Events_Meta();
				$show_register_button	= $oMeta->getOption($row['id'], 'show_register_button');
				$qty_discount			= $oMeta->getOption( $row['id'], 'qty_discount' );
				$qty_discount_settings	= $oMeta->getOption( $row['id'], 'qty_discount_settings' );
				$show_register_button	= $oMeta->getOption( $row['id'], 'show_register_button' );
				$term_c_force			= $oMeta->getOption( $row['id'], 'term_c_force' );
				$skip_step_2			= $oMeta->getOption( $row['id'], 'skip_step_2' );

				// Update event meta
				$oMeta->updateOption( $lastID, 'show_register_button', $show_register_button);
				$oMeta->updateOption( $lastID, 'qty_discount', $qty_discount );
				$oMeta->updateOption( $lastID, 'qty_discount_settings', (array) $qty_discount_settings );
				$oMeta->updateOption( $lastID, 'show_register_button', $show_register_button );
				$oMeta->updateOption( $lastID, 'term_c_force', $term_c_force );
				$oMeta->updateOption( $lastID, 'skip_step_2', $skip_step_2 );

                $messages[] = __('The copy of event ', 'evrplus_language') . ' ' . $row['event_name'] . ' ' . __('has been added.', 'evrplus_language');

                $events_question_tbl = get_option('evr_question');
                $questions = $this->getWpDb()->get_results("SELECT * from $events_question_tbl where event_id = $event_id order by sequence ASC");
                if ($questions) {
                    foreach ($questions as $question) {
                        $sql = array('event_id' => $lastID, 'sequence' => $question->sequence, 'question_type' => $question->question_type,
                            'question' => $question->question, 'response' => $question->response, 'required' => $question->required);
                        $sql_data = array('%s', '%s', '%s', '%s', '%s', '%s');
                        $this->getWpDb()->insert(get_option('evr_question'), $sql, $sql_data);
                    }

                    $messages[] = __('The questions have been added.', 'evrplus_language');
                }

                $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id;
                $result = $this->getWpDb()->get_results($sql, ARRAY_A);
                foreach ($result as $row) {
                    $sequence = $row['sequence'];
                    $event_id = $lastID;
                    $item_title = $row['item_title'];
                    $item_description = $row['item_description'];
                    $item_cat = $row['item_cat'];
                    $item_limit = $row['item_limit'];
                    $item_price = $row['item_price'];
                    $free_item = $row['free_item'];
                    $item_start_date = $row['item_available_start_date'];
                    $item_end_date = $row['item_available_end_date'];
                    $item_custom_cur = $row['item_custom_cur'];

                    $sql = array('sequence' => $sequence, 'event_id' => $event_id, 'item_title' => $item_title, 'item_description' => $item_description,
                        'item_cat' => $item_cat, 'item_limit' => $item_limit, 'item_price' => $item_price, 'free_item' => $free_item, 'item_available_start_date' => $item_start_date,
                        'item_available_end_date' => $item_end_date, 'item_custom_cur' => $item_custom_cur);
                    $sql_data = array('%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s');

                    if ($this->getWpDb()->insert(get_option('evr_cost'), $sql, $sql_data)) {
                        $messages[] = __('The cost ', 'evrplus_language') . ' ' . $item_title . ' ' . __('has been added.', 'evrplus_language');
                    }
                }
                $this->setFormattedMessage($messages);
                return true;
            } else {
                $messages = __('There was an error in your submission, please try again. The cost was not saved!', 'evrplus_language');
                $this->setMessage($messages);
                return false;
            }
        }
    }

    function updateCoupon($params) {

        $wpdb = $this->getWpDb();

        $id = $params['event_id'];
        $use_coupon = $params['use_coupon'];
        $coupon_code = $params['coupon_code'];
        $coupon_code_price = $params['coupon_code_price'];


        if ($coupon_code_price == '') {
            $coupon_code_price = 0.00;
        }

        $sql = array('use_coupon' => $use_coupon, 'coupon_code' => $coupon_code, 'coupon_code_price' => $coupon_code_price);

        $update_id = array('id' => $id);

        $sql_data = array('%s', '%s', '%s');

        if ($wpdb->update($this->_table, $sql, $update_id, $sql_data, array('%s')) === false) {
            $this->setMessage(__('There was an error in your submission, please try again. The coupon code changes were not saved!', 'evrplus_language'));
            return false;
        } else {
            $this->setMessage(__('The coupon code information has been updated.', 'evrplus_language'));
            return true;
        }
    }

    function getRecords(array $params) {

        $orderby = $params['orderby'];
        $category_id = $params['category_id'];

        $sql = "SELECT * FROM " . $this->_table . " "
                . " WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate()";

        if( !empty($category_id) ) {
            $sql .= " AND category_id LIKE '%:\"".$category_id."\";%'";
        }

        $sql .= " ORDER BY DATE(start_date) ".$orderby.", start_time " . $orderby;
        
        return $this->getWpDb()->get_results($sql);
    }

    function fetchEventsByDate($date) {
        $company_options = EventPlus_Models_Settings::getSettings();
        if ($company_options['order_event_list'] == 'DESC') {
            $events = $this->getWpDb()->get_results("SELECT * FROM " . $this->_table . " WHERE (str_to_date(start_date, '%Y-%m-%e') <= str_to_date('" . esc_sql($date) . "', '%Y-%m-%e') AND str_to_date(end_date, '%Y-%m-%e') >= str_to_date('" . esc_sql($date) . "', '%Y-%m-%e')) OR recurrence_choice='yes' ORDER BY str_to_date(start_time,'%h:%i%p') DESC");
        } else {
            $events = $this->getWpDb()->get_results("SELECT * FROM " . $this->_table . " WHERE (str_to_date(start_date, '%Y-%m-%e') <= str_to_date('" . esc_sql($date) . "', '%Y-%m-%e') AND str_to_date(end_date, '%Y-%m-%e') >= str_to_date('" . esc_sql($date) . "', '%Y-%m-%e')) OR recurrence_choice='yes' ORDER BY str_to_date(start_time,'%h:%i%p')  ASC");
        }

        return $events;
    }

    function getEventsBySettings($params = array()) {
        $company_options = EventPlus_Models_Settings::getSettings();

        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE 1 = 1";

        $show_expire = isset( $params['show_expire'] ) ? $params['show_expire'] : 'no';
        if( $show_expire != 'yes' ) {
            $sql .= " AND str_to_date(end_date, '%Y-%m-%e') >= curdate()";
        }
        
        if(!empty($params['event_category_id'])){
            $sql .= " AND category_id LIKE '%\"" . esc_sql($params['event_category_id']) . "\"%' ";
        }
       
        
        # Get events that end date is later than today and order by start date
        if ($company_options['order_event_list'] == 'DESC') {
            $sql .= " ORDER BY str_to_date(start_date, '%Y-%m-%e') DESC, start_time DESC";
        } else {
            $sql .= " ORDER BY str_to_date(start_date, '%Y-%m-%e') ASC, start_time ASC";
        }
        
       
        if(isset($params['limit'])){
            if($params['limit'] > 0){
                $sql .= " LIMIT " . (int)$params['limit'];
            }
        }
        
        return $this->getWpDb()->get_results($sql);
    }

    function getComboDataset($params = array()) {
        $sql = "SELECT id, event_name, event_identifier FROM " . $this->_table . "  ORDER BY str_to_date(start_date, '%Y-%m-%e') DESC";
        return $this->getWpDb()->get_results($sql, ARRAY_A);
    }

    function getEventObject($event_id) {
        $sql = "SELECT * FROM " . $this->_table . " WHERE id = '" . (int) $event_id . "' LIMIT 1";
        return $this->getWpDb()->get_results($sql);
    }

}
