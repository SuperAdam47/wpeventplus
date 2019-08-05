<?php

/**
 * This file takes an array from an event row and turns it into strings
 */
$event_id = $event->id;
$reg_form_defaults = unserialize($event->reg_form_defaults);
$inc_address = '';
$inc_city = '';
$inc_phone = '';
$inc_state = '';
$inc_zip = '';
$inc_comp = '';
$inc_coadd = '';
$inc_cocity = '';
$inc_costate = '';
$inc_copostal = '';
$inc_cophone = '';

if ($reg_form_defaults != "") {
    if (in_array("Address", $reg_form_defaults)) {
        $inc_address = "Y";
    }
    if (in_array("City", $reg_form_defaults)) {
        $inc_city = "Y";
    }
    if (in_array("Country", $reg_form_defaults)) {
        $inc_country = "Y";
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

$use_coupon = $event->use_coupon;
$reg_limit = $event->reg_limit;
$event_name = stripslashes($event->event_name);
$event_identifier = stripslashes($event->event_identifier);
$display_desc = $event->display_desc; /* Y or N */

$event_desc = stripslashes($event->event_desc);
$event_category = unserialize($event->category_id);

$reg_limit = $event->reg_limit;

/*
  $event_location = stripslashes($event->event_location);
  $event_address = $event->event_address;
  $event_city = $event->event_city;
  $event_state = $event->event_state;
  $event_postal = $event->event_postcode;
 */

if ((get_option('evr_location_active') == "Y") && ( $event->location_list >= '1')) {
    $sql = "SELECT * FROM " . get_option('evr_location') . " WHERE id = '" . esc_sql($event->location_list) . "' ";
    $location = $wpdb->get_row($sql, OBJECT); /* default object */
    /* $object->field; */
    if (!empty($location)) {
        $event_location = stripslashes($location->location_name);
        $event_address = $location->street;
        $event_city = $location->city;
        $event_state = $location->state;
        $event_postal = $location->postal;
        $event_phone = $location->phone;
    }
} else {
    $event_location = stripslashes($event->event_location);
    $event_address = $event->event_address;
    $event_city = $event->event_city;
    $event_state = $event->event_state;
    $event_postal = $event->event_postal;
}

$google_map = $event->google_map; /* Y or N */
$start_month = $event->start_month;
$start_day = $event->start_day;
$start_year = $event->start_year;
$end_month = $event->end_month;
$end_day = $event->end_day;
$end_year = $event->end_year;
$start_time = $event->start_time;
$end_time = $event->end_time;
$allow_checks = $event->allow_checks;
$counter_checks = $event->counter_checks;
$outside_reg = $event->outside_reg; /* Y or N */
$disable_event_reg = $event->disable_event_reg; /* Y or N */
$external_site = $event->external_site;
$more_info = $event->more_info;
$image_link = $event->image_link;
$header_image = $event->header_image;

if (isset($event->event_cost)) {
    $event_cost = $event->event_cost;
}

$allow_checks = $event->allow_checks;
$is_active = $event->is_active;
$send_mail = $event->send_mail; /* Y or N */
$conf_mail = stripslashes($event->conf_mail);
$start_date = $event->start_date;
$end_date = $event->end_date;
$event_close = $event->close;
$event_country = $event->coord_msg;
$recurrence_choice = $event->recurrence_choice;
$recurrence_period = $event->recurrence_period;
$recurrence_frequency = $event->recurrence_frequency;
$recurrence_repeat_period = $event->recurrence_repeat_period;
$term_c = $event->term_c;
$term_desc = $event->term_desc;

/* In order to get the number of seats we need to count all attendees for this event
  #Retrieve the number of registered attendees for this event from attendee db */

$sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE  payment_status = 'success' AND event_id='" . (int) $event->id . "'";
$result2 = $wpdb->get_results($sql2, ARRAY_N);

$num = 0;
$available_spaces = 0;
foreach ($result2 as $row) {
    $num = $row[0];
}

$attendee_count = $num;

if ($event->reg_limit != "") {
    $available_spaces = $event->reg_limit - $num;
}

if ($event->reg_limit == "" || $event->reg_limit == " " || $event->reg_limit == "999") {
    $available_spaces = "UNLIMITED";
}

