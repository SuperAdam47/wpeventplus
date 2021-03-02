<?php
$editor_settings = array('wpautop', 'media_buttons' => false, 'textarea_rows' => '4');
$event_id = 0;
$form_url = $this->adminUrl('admin_events', array('method' => 'add'));
if (!empty($row) && is_object($row)) {

    if ($row->id > 0) {

        $event_id = (int) $row->id;
        $form_url = $this->adminUrl('admin_events', array('method' => 'edit', 'id' => $event_id));
        $event_name = stripslashes($row->event_name);
        $event_identifier = stripslashes($row->event_identifier);
        $display_desc = $row->display_desc;  // Y or N
        $event_desc = stripslashes($row->event_desc);

        $reg_limit = $row->reg_limit;
        $term_c = $row->term_c;
        $term_desc = $row->term_desc;
        $meta_data = $row->meta_data;
		$location_list = $row->location_list;
        if ((get_option('evr_location_active') == "Y") && ( $row->location_list >= '1')) {
            $location_list = $row->location_list;
            $sql = "SELECT * FROM " . get_option('evrplus_location') . " WHERE id = $location_list";
            $location = $this->wpDb()->get_row($sql, OBJECT); //default object
            //$object->field;
            if (!empty($location)) {

                $location_tag = stripslashes($location->location_name);
                $event_location = stripslashes($location->location_name);
                $event_address = $location->street;
                $event_city = $location->city;
                $event_state = $location->state;
                $event_postal = $location->postal;
                $event_phone = $location->phone;
            }
        } else {
            $location_list = '0';
            $location_tag = 'Custom';
            $event_location = stripslashes($row->event_location);
            $event_address = $row->event_address;
            $event_city = $row->event_city;
            $event_state = $row->event_state;
            $event_postal = $row->event_postal;
        }
        $google_map = $row->google_map;  // Y or N
        $start_month = $row->start_month;
        $start_day = $row->start_day;
        $start_year = $row->start_year;
        $end_month = $row->end_month;
        $end_day = $row->end_day;
        $end_year = $row->end_year;
        $infinite_event = '';
        $start_time = $row->start_time;
        $end_time = $row->end_time;
        $allow_checks = $row->allow_checks;
        $counter_checks = $row->counter_checks;
        $outside_reg = $row->outside_reg;  // Yor N
        $disable_event_reg = $row->disable_event_reg;  // Y or N
        $external_site = $row->external_site;
        $reg_form_defaults = unserialize($row->reg_form_defaults);
        $more_info = $row->more_info;
        $image_link = $row->image_link;
        $header_image = $row->header_image;
        //$event_cost = $row->event_cost;


        $is_active = $row->is_active;
        $send_mail = $row->send_mail;  // Y or N
        $conf_mail = stripslashes($row->conf_mail);

        $start_date = $row->start_date;
        $end_date = $row->end_date;
        $recurrence_choice = $row->recurrence_choice;
        $recurrence_period = $row->recurrence_period;
        $recurrence_frequency = $row->recurrence_frequency;
        $recurrence_repeat_period = $row->recurrence_repeat_period;
        $close = $row->close;
        $infinate_event = $row->infinate_event;

        $event_category = unserialize($row->category_id);
        if ($event_category == "") {
            $event_category = array();
        }

        $coord_email = $row->coord_email;
        $send_coord = $row->send_coord;
        $event_country = $row->coord_msg;
        $coord_pay_msg = stripslashes($row->coord_pay_msg);
        $reg_form_defaults = unserialize($row->reg_form_defaults);

        $company_options = EventPlus_Models_Settings::getSettings();
        $time_format = $company_options['time_format'];
        $date_format = $company_options['date_format'];
        unset($company_options);
        if ($reg_form_defaults != "") {
            if (in_array("Address", $reg_form_defaults)) {
                $inc_address = "Y";
            }
            if (in_array("City", $reg_form_defaults)) {
                $inc_city = "Y";
            }
            if (in_array("State", $reg_form_defaults)) {
                $inc_state = "Y";
            }
            if (in_array("Zip", $reg_form_defaults)) {
                $inc_zip = "Y";
            }
            if (in_array("Phone", $reg_form_defaults)) {
                $inc_phone = "Y";
            }
            if (in_array("Country", $reg_form_defaults)) {
                $inc_country = "Y";
            }
            if (in_array("Company", $reg_form_defaults)) {
                $inc_comp = "Y";
            }
            if (in_array("CoAddress", $reg_form_defaults)) {
                $inc_coadd = "Y";
            }
            if (in_array("CoCity", $reg_form_defaults)) {
                $inc_cocity = "Y";
            }
            if (in_array("CoState", $reg_form_defaults)) {
                $inc_costate = "Y";
            }
            if (in_array("CoPostal", $reg_form_defaults)) {
                $inc_copostal = "Y";
            }
            if (in_array("CoPhone", $reg_form_defaults)) {
                $inc_cophone = "Y";
            }
        }

        //set reg limit if not set
        if ($reg_limit == '') {
            $reg_limit = 999999;
        }

        $sql2 = "SELECT COUNT(*) FROM " . get_option('evr_attendee') . " WHERE event_id='$event_id'";
        $result2 = $this->wpDb()->get_var($sql2);
        $num = $result2;
        $number_attendees = $num;

        if ($number_attendees == '' || $number_attendees == 0) {
            $number_attendees = '0';
        }

        if ($reg_limit == "" || $reg_limit == " ") {
            $reg_limit = "Unlimited";
        }
        $available_spaces = $reg_limit;


        $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));
        $close_dt = $start_date . " " . $start_time;
        $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
        $expiration_date = strtotime($stp);

        if ($row->recurrence_choice == 'yes') {
            $dateTime = new DateTime('2030-7-15 8:30pm');
            $expiration_date = $dateTime->format("U");
        } else {
            $stp = DATE("Y-m-d H:i", STRTOTIME($end_date));
            $expiration_date = strtotime($stp);
        }


        $today = strtotime($current_dt);


        if ($expiration_date <= $today) {
            $active_event = '<span style="color: #F00; font-weight:bold;">' . __('EXPIRED EVENT', 'evrplus_language') . '</span>';
        } else {
            $active_event = '<span style="color: #090; font-weight:bold;">' . __('ACTIVE EVENT', 'evrplus_language') . '</span>';
        }
        
        $oMeta = new EventPlus_Models_Events_Meta();
        $show_register_button = $oMeta->getOption($event_id, 'show_register_button');
        $skip_step_2 = $oMeta->getOption($event_id, 'skip_step_2');
        $event_coordinator = $oMeta->getOption($event_id, 'event_coordinator');
        $closure_day_date = $oMeta->getOption($event_id, 'closure_day_date');
        $closure_day_time = $oMeta->getOption($event_id, 'closure_day_time');

    }
}else{
		$event_id = 0;
        $form_url = "";
        $event_name ="";
        $event_identifier = "";
        $display_desc = "";  // Y or N
        $event_desc = "";
		$reg_limit = "";
        $term_c = "";
        $term_desc = "";
        $meta_data = "";
		$location_list = "";
		$location_tag = "";
		$event_location = "";
		$event_address = "";
		$event_city = "";
		$event_state ="";
		$event_postal = "";
		$event_phone = "";
		$google_map = "";  // Y or N
        $start_month = "";
        $start_day = "";
        $start_year = "";
        $end_month = "";
        $end_day = "";
        $end_year = "";
        $infinite_event = '';
        $start_time = "";
        $end_time = "";
        $allow_checks = "";
        $counter_checks = "";
        $outside_reg = "";  // Yor N
        $disable_event_reg = "";  // Y or N
        $external_site = "";
        $reg_form_defaults = "";
        $more_info = "";
        $image_link = "";
        $header_image = "";
        //$event_cost = $row->event_cost;
		$is_active = "";
        $send_mail = "";  // Y or N
        $conf_mail = "";

        $start_date = "";
        $end_date = "";
        $recurrence_choice = "";
        $recurrence_period = "";
        $recurrence_frequency = "";
        $recurrence_repeat_period = "";
        $close = "";
        $infinate_event = "";

        $event_category = "";
        if ($event_category == "") {
            $event_category = array();
        }

        $coord_email = "";
        $send_coord = "";
        $event_country = "";
        $coord_pay_msg = "";
        $reg_form_defaults = "";

        $company_options = EventPlus_Models_Settings::getSettings();
        $time_format = $company_options['time_format'];
        $date_format = $company_options['date_format'];
        unset($company_options);
        if ($reg_form_defaults != "") {
            if (in_array("Address", $reg_form_defaults)) {
                $inc_address = "Y";
            }
            if (in_array("City", $reg_form_defaults)) {
                $inc_city = "Y";
            }
            if (in_array("State", $reg_form_defaults)) {
                $inc_state = "Y";
            }
            if (in_array("Zip", $reg_form_defaults)) {
                $inc_zip = "Y";
            }
            if (in_array("Phone", $reg_form_defaults)) {
                $inc_phone = "Y";
            }
            if (in_array("Country", $reg_form_defaults)) {
                $inc_country = "Y";
            }
            if (in_array("Company", $reg_form_defaults)) {
                $inc_comp = "Y";
            }
            if (in_array("CoAddress", $reg_form_defaults)) {
                $inc_coadd = "Y";
            }
            if (in_array("CoCity", $reg_form_defaults)) {
                $inc_cocity = "Y";
            }
            if (in_array("CoState", $reg_form_defaults)) {
                $inc_costate = "Y";
            }
            if (in_array("CoPostal", $reg_form_defaults)) {
                $inc_copostal = "Y";
            }
            if (in_array("CoPhone", $reg_form_defaults)) {
                $inc_cophone = "Y";
            }
        }

        //set reg limit if not set
        if ($reg_limit == '') {
            $reg_limit = 999999;
        }

        $number_attendees = 0;

        if ($number_attendees == '' || $number_attendees == 0) {
            $number_attendees = '0';
        }

        if ($reg_limit == "" || $reg_limit == " ") {
            $reg_limit = "Unlimited";
        }
        $available_spaces = $reg_limit;


        $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));
        $close_dt = $start_date . " " . $start_time;
        $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
        $expiration_date = strtotime($stp);
		$today = strtotime($current_dt);

		$active_event ='';
        $oMeta = new EventPlus_Models_Events_Meta();
        $show_register_button = $oMeta->getOption($event_id, 'show_register_button');
        $skip_step_2 = $oMeta->getOption($event_id, 'skip_step_2');
        $event_coordinator = $oMeta->getOption($event_id, 'event_coordinator');
        $closure_day_date = $oMeta->getOption($event_id, 'closure_day_date');
        $closure_day_time = $oMeta->getOption($event_id, 'closure_day_time');
	
	
}
?>

<?php if ($event_id > 0): ?>
    <?php include 'form.parts/edit_event_scripts.php'; ?>
<?php else: ?>
    <?php include 'form.parts/add_event_scripts.php'; ?>
<?php endif; ?>

<form id="er_popup_Form" method="post" action="<?php echo $form_url; ?>">
    <input type="hidden" name="id" value="<?php echo $event_id; ?>">

    <div class="evrplus_container">

        <?php if ($event_id > 0): ?>
            <h2><?php _e('EDIT', 'evrplus_language'); ?> <?php echo $active_event . " - " . $event_name; ?></h2>
        <?php endif; ?>

        <?php
        $company_options = EventPlus_Models_Settings::getSettings();
        $tabs = array(
            'description' => __('Description', 'evrplus_language'),
            'venue' => __('Event Venue', 'evrplus_language'),
            'datetime' => __('Event Date/Time', 'evrplus_language'),
            'options' => __('Event Options', 'evrplus_language')
        );

        $tabs ['email'] = __('Confirmation Mail', 'evrplus_language');
        if ($company_options['qty_discount'] == 'Y') {
            $tabs ['discounts'] = __('Bulk Discounts', 'evrplus_language');
        }

        ?>

        <ul class="tabs">

            <?php foreach ($tabs as $tabIndex => $tab): ?>
                <li><a href="#tab_<?php echo $tabIndex; ?>"><?php echo $tab; ?></a></li>
            <?php endforeach; ?>
        </ul>

        <div class="evrplus_tab_container">

            <?php foreach ($tabs as $tabIndex => $tab): ?>
                <div id="tab_<?php echo $tabIndex; ?>" class="tab_content">
                    <?php include 'form.parts/' . $tabIndex . '.php'; ?>
                </div>
            <?php endforeach; ?>


        </div>
    </div>
</form>

<div style="clear: both; display: block; padding: 10px 0; text-align:center;"><font color="blue"><?php _e('Please make sure you complete each section before submitting!', 'evrplus_language'); ?></font></div>
