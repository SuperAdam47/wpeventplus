<?php
/**
 * @author wpeventsplus.com
 * @copyright 2014
 */
##Set the number of future days for upcoming events listing##
$future_days = "60";
$evrplus_date_format = EventPlus_Helpers_Funx::getDateFormat();


/* * **Function to return a prefix which will allow the correct , placement of arguments into the query string. ** */

/* * ***************************** Display the Calendar in a page ************************* */

function evrplus_calendar_replace($content) {
    if (preg_match('[PLUS_CALENDAR:([A-Za-z])\w+]', $content, $matches)) {
        $evr = $matches[0];
        $pos = strpos($evr, ':');
        $cat = substr($evr, $pos + 1);
        ob_start();

        evrplus_display_calendar($cat); //function with main content
        $buffer = ob_get_contents();
        ob_end_clean();
        $content = str_replace('[PLUS_CALENDAR:' . $cat . ']', $buffer, $content);
    } elseif (preg_match('[PLUS_CALENDAR]', $content)) {
        ob_start();
        evrplus_display_calendar(); //function with main content
        $buffer = ob_get_contents();
        ob_end_clean();
        $content = str_replace('[PLUS_CALENDAR]', $buffer, $content);
    }
    return $content;
}

function evrplus_display_calendar($cat = null) {
    global $wpdb, $week_no;

    $company_options = EventPlus_Models_Settings::getSettings();

    $cal_head_clr = $company_options['evrplus_cal_head'];
    $cal_head_txt_clr = $company_options['cal_head_txt_clr'];
    $cal_use_cat = $company_options['evrplus_cal_use_cat'];
    $cal_pop_brdr_clr = $company_options['evrplus_cal_pop_border'];
    $cal_day_clr = $company_options['evrplus_cal_cur_day'];
    $cal_day_txt_clr = $company_options['cal_day_txt_clr'];
    $date_switcher = $company_options['evrplus_date_select'];
    $cal_day_hdr_clr = $company_options['evrplus_cal_day_head'];
    $cal_day_hdr_txt_clr = $company_options['cal_day_head_txt_clr'];

    if( $cal_head_clr != "" ) { ?>
        <style type="text/css">
             #calendar-table tr .calendar-date-switcher{background-color:<?php echo $cal_head_clr; ?>;color: <?php echo $cal_head_txt_clr; ?>;}
        </style>
        <?php
    }
    if ($cal_day_clr != "") {
        ?>
        <style type="text/css">
            #calendar-table tr .current-day{ background-color:<?php echo $cal_day_clr; ?>;color: <?php echo $cal_day_txt_clr; ?>;}
        </style>
        <?php
    }
    if ($cal_day_hdr_clr != "") {
        ?>
        <style type="text/css">
            #calendar-table tr .normal-day-heading{ background-color:<?php echo $cal_day_hdr_clr; ?>;color: <?php echo $cal_day_hdr_txt_clr; ?>;}
            #calendar-table tr .weekend-heading{ background-color:<?php echo $cal_day_hdr_clr; ?>;color: <?php echo $cal_day_hdr_txt_clr; ?>;}
        </style>
    <?php } ?>
    <?php
    unset($week_no);
    if (get_option('evr_start_of_week') == 0) {
        $name_days = array(1 => __('Sun', 'evrplus_language'), __('Mon', 'evrplus_language'), __('Tue', 'evrplus_language'), __('Wed', 'evrplus_language'), __('Thu', 'evrplus_language'), __('Fri', 'evrplus_language'), __('Sat', 'evrplus_language'));
    } else {
        $name_days = array(1 => __('Mon', 'evrplus_language'), __('Tue', 'evrplus_language'), __('Wed', 'evrplus_language'), __('Thu', 'evrplus_language'), __('Fri', 'evrplus_language'), __('Sat', 'evrplus_language'), __('Sun', 'evrplus_language'));
    }
    $name_months = array(1 => __('January', 'evrplus_language'), __('February', 'evrplus_language'), __('March', 'evrplus_language'), __('April', 'evrplus_language'), __('May', 'evrplus_language'), __('June', 'evrplus_language'), __('July', 'evrplus_language'), __('August', 'evrplus_language'), __('September', 'evrplus_language'), __('October', 'evrplus_language'), __('November', 'evrplus_language'), __('December', 'evrplus_language'));
    if (empty($_GET['month']) || empty($_GET['yr'])) {
        $c_year = date("Y", evrplus_time_offset());
        $c_month = date("m", evrplus_time_offset());
        $c_day = date("d", evrplus_time_offset());
    }
    if( isset($_GET['yr']) && ($_GET['yr'] <= 3000 && $_GET['yr'] >= 0 && (int) $_GET['yr'] != 0) ) {
        if( isset($_GET['month']) && ($_GET['month'] == 'jan' || $_GET['month'] == 'feb' || $_GET['month'] == 'mar' || $_GET['month'] == 'apr' || $_GET['month'] == 'may' || $_GET['month'] == 'jun' || $_GET['month'] == 'jul' || $_GET['month'] == 'aug' || $_GET['month'] == 'sept' || $_GET['month'] == 'oct' || $_GET['month'] == 'nov' || $_GET['month'] == 'dec') ) {
            $c_year = esc_sql($_GET['yr']);
            if ($_GET['month'] == 'jan') {
                $t_month = 1;
            } else if ($_GET['month'] == 'feb') {
                $t_month = 2;
            } else if ($_GET['month'] == 'mar') {
                $t_month = 3;
            } else if ($_GET['month'] == 'apr') {
                $t_month = 4;
            } else if ($_GET['month'] == 'may') {
                $t_month = 5;
            } else if ($_GET['month'] == 'jun') {
                $t_month = 6;
            } else if ($_GET['month'] == 'jul') {
                $t_month = 7;
            } else if ($_GET['month'] == 'aug') {
                $t_month = 8;
            } else if ($_GET['month'] == 'sept') {
                $t_month = 9;
            } else if ($_GET['month'] == 'oct') {
                $t_month = 10;
            } else if ($_GET['month'] == 'nov') {
                $t_month = 11;
            } else if ($_GET['month'] == 'dec') {
                $t_month = 12;
            }

            $c_month = $t_month;
            $c_day = date("d", evrplus_time_offset());
        } else {
            $c_year = date("Y", evrplus_time_offset());
            $c_month = date("m", evrplus_time_offset());
            $c_day = date("d", evrplus_time_offset());
        }
    } else {
        $c_year = date("Y", evrplus_time_offset());
        $c_month = date("m", evrplus_time_offset());
        $c_day = date("d", evrplus_time_offset());
    }
    if (get_option('evr_start_of_week') == 0) {
        $first_weekday = date("w", mktime(0, 0, 0, $c_month, 1, $c_year));
        $first_weekday = ($first_weekday == 0 ? 1 : $first_weekday + 1);
    } else {
        $first_weekday = date("w", mktime(0, 0, 0, $c_month, 1, $c_year));
        $first_weekday = ($first_weekday == 0 ? 7 : $first_weekday);
    }
    $days_in_month = date("t", mktime(0, 0, 0, $c_month, 1, $c_year));
    $calendar_body = '<table class="calendar-table" id="calendar-table" >';
    $calendar_body .= '';
    if ($date_switcher == 'Y') {
        $calendar_body .= '<tr><td colspan="7" class="calendar-date-switcher"><form method="get" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '"><input type="hidden" name="page_id" value="' . get_the_ID() . '" />';
        $qsa = array();

        foreach ($qsa as $name => $argument) {
            if ($name != 'month' && $name != 'yr') {
                $calendar_body .= '<input type="hidden" name="' . strip_tags($name) . '" value="' . strip_tags($argument) . '" />';
            }
        }
        $calendar_body .= '' . __('Month', 'evrplus_language') . ':<select name="month" style="width:100px;display:inline-block;">
            <option value="jan"' . evrplus_month_compare('jan') . '>' . __('January', 'evrplus_language') . '</option>
            <option value="feb"' . evrplus_month_compare('feb') . '>' . __('February', 'evrplus_language') . '</option>
            <option value="mar"' . evrplus_month_compare('mar') . '>' . __('March', 'evrplus_language') . '</option>
            <option value="apr"' . evrplus_month_compare('apr') . '>' . __('April', 'evrplus_language') . '</option>
            <option value="may"' . evrplus_month_compare('may') . '>' . __('May', 'evrplus_language') . '</option>
            <option value="jun"' . evrplus_month_compare('jun') . '>' . __('June', 'evrplus_language') . '</option>
            <option value="jul"' . evrplus_month_compare('jul') . '>' . __('July', 'evrplus_language') . '</option> 
            <option value="aug"' . evrplus_month_compare('aug') . '>' . __('August', 'evrplus_language') . '</option> 
            <option value="sept"' . evrplus_month_compare('sept') . '>' . __('September', 'evrplus_language') . '</option> 
            <option value="oct"' . evrplus_month_compare('oct') . '>' . __('October', 'evrplus_language') . '</option> 
            <option value="nov"' . evrplus_month_compare('nov') . '>' . __('November', 'evrplus_language') . '</option> 
            <option value="dec"' . evrplus_month_compare('dec') . '>' . __('December', 'evrplus_language') . '</option> 
            </select>' . __('Year', 'evrplus_language') . ': <select name="yr" style="width:90px;display:inline-block;">';

        $past = 1;
        $future = 5;
        $fut = 1;
        $p = '';
        $f = '';
        while ($past > 0) {
            $p .= '<option value="';

            $p .= date("Y", evrplus_time_offset()) - $past;
            $p .= '"' . evrplus_year_compare(date("Y", evrplus_time_offset()) - $past) . '>';
            $p .= date("Y", evrplus_time_offset()) - $past . '</option>';
            $past = $past - 1;
        }
        while ($fut < $future) {

            $f .= '<option value="';

            $f .= date("Y", evrplus_time_offset()) + $fut;
            $f .= '"' . evrplus_year_compare(date("Y", evrplus_time_offset()) + $fut) . '>';
            $f .= date("Y", evrplus_time_offset()) + $fut . '</option>';
            $fut = $fut + 1;
        }
        $calendar_body .= $p;
        $calendar_body .= '<option value="' . date("Y", evrplus_time_offset()) . '"' . evrplus_year_compare(date("Y", evrplus_time_offset())) . '>' . date("Y", evrplus_time_offset()) . '</option>';
        $calendar_body .= $f;
        $calendar_body .= '</select><input type="submit" value="' . __('Go', 'evrplus_language') . '" /></form></td></tr>';
    }

    $calendar_body .= '<tr><td colspan="2" class="calendar-prev">' . evrplus_prev_link($c_year, $c_month) . '</td>
		<td colspan="3" class="calendar-month">' . $name_months[(int) $c_month] . ' ' . $c_year . '</td>
        <td colspan="2" class="calendar-next">' . evrplus_next_link($c_year, $c_month) . '</td></tr>';
    $calendar_body .= '<tr>';
    for ($i = 1; $i <= 7; $i++) {
        if (get_option('evr_start_of_week') == 0) {
            $calendar_body .= '<td class="' . ($i < 7 && $i > 1 ? 'normal-day-heading' : 'weekend-heading') . '">' . $name_days[$i] . '</td>';
        } else {
            $calendar_body .= '<td class="' . ($i < 6 ? 'normal-day-heading' : 'weekend-heading') . '">' . $name_days[$i] . '</td>';
        }
    }

    $calendar_body .= '</tr>';
    $grabbed_events_popup = array();
    $grabbed_non_events = isset( $grabbed_non_events ) ? $grabbed_non_events : array();
    for ($i = 1; $i <= $days_in_month;) {
        $calendar_body .= '<tr>';
        for ($ii = 1; $ii <= 7; $ii++) {
            if( $ii == $first_weekday && $i == 1 ) {
                $go = TRUE;
            } elseif( $i > $days_in_month ) {
                $go = FALSE;
            }
            if( $go ) {
                if( get_option('evr_start_of_week') == 0 ) {
                    $grabbed_events = evrplus_fetch_events( $c_year, $c_month, $i );
                    foreach( $grabbed_events as $event ) {
                        array_push( $grabbed_events_popup, $event );
                    }
                    $no_events_class = '';
                    if( (!count($grabbed_events)) && (!count($grabbed_non_events)) ) {
                        $no_events_class = ' no-events';
                    } else {
                        $no_events_class = ' events';
                    }

                    $calendar_body .= '<td class="' . (date("Ymd", mktime(0, 0, 0, $c_month, $i, $c_year)) == date("Ymd", evrplus_time_offset()) ? 'current-day' : 'day-with-date') . $no_events_class . '">
								  <span ' . ($ii < 7 && $ii > 1 ? '' : 'class="weekend"') . '>' . $i++ . '</span><span class="event">
                                    <br /><div class="tooltip">' . evrplus_show_events( $grabbed_events, ($i - 1), $cat );
                    $calendar_body .= '</div></span></td>';
                } else {

                    $grabbed_events = evrplus_fetch_events($c_year, $c_month, $i);
                    foreach( $grabbed_events as $event ) {
                        array_push($grabbed_events_popup, $event);
                    }
                    $no_events_class = '';

                    if ((!count($grabbed_events)) && (!count($grabbed_non_events))) {
                        $no_events_class = ' no-events';
                    } else {
                        $no_events_class = ' events';
                    }
                    $calendar_body .= '<td class="' . (date("Ymd", mktime(0, 0, 0, $c_month, $i, $c_year)) == date("Ymd", evrplus_time_offset()) ? 'current-day' : 'day-with-date') .
                            $no_events_class . '"><span ' . ($ii < 6 ? '' : 'class="weekend"') . '>' . $i++ . '</span><br/><span class="event">' . evrplus_show_events($grabbed_events, ($i - 1), $cat);
                    $calendar_body .= '</span></td>';
                }
            } else {
                $calendar_body .= ' <td class="day-without-date">&nbsp;</td>';
            }
        }
        $calendar_body .= '</tr>';
    }

    $company_options = EventPlus_Models_Settings::getSettings();
    $cal_use_cat = $company_options['evrplus_cal_use_cat'];
    if ($cal_use_cat == 'Y') {
        $sql = "SELECT * FROM " . get_option('evr_category') . " ORDER BY id ASC";

        $result = $wpdb->get_results($sql, ARRAY_A);
        if( $wpdb->num_rows > 0 ) {
            $i = 0;
            foreach( $result as $row ) {
                $category_id = $row['id'];
                $category_name = $row['category_name'];
                $category_identifier = $row['category_identifier'];
                $category_desc = $row['category_desc'];
                $display_category_desc = $row['display_desc'];
                $category_color = $row['category_color'];
                $font_color = $row['font_color'];
                if( $i % 7 != 0 ) {
                    $calendar_body .= '<td colspan="1" style="background-color:' . $category_color . ';font-size:0.9em; color:' . $font_color . '; ">' . $category_name . '</td>';
                } else {
                    $calendar_body.='</tr><tr class="eventplus--calendar-legend"><td colspan="1" style="background-color:' . $category_color . ';font-size:0.9em; color:' . $font_color . '; ">' . $category_name . '</td>';
                }
                $i++;
            }
        }
    }

    $calendar_body .= '</table>';
    //$calendar_body .= evrplus_colorbox_cal_content($grabbed_events_popup);

    echo $calendar_body;
    return $calendar_body;
}

/* * **********************    Display the events  ******************************** */
function evrplus_show_events($events, $day = 0, $cat = null) {
    $output = '';
    global $wpdb;
    foreach( $events as $event ) {
        $cat_id = 0;
        if( $cat ) {
            $cat_id = $wpdb->get_var("SELECT id FROM " . get_option('evr_category') . " WHERE category_identifier='$cat'");
            if ($cat_id) {
                $cat_array = unserialize($event->category_id);
                if (!in_array($cat_id, (array)$cat_array))
                    continue;
            }
        }
        $output .= evrplus_show_event( $event, $day ) . '<br />';
    }
    return $output;
}

function evrplus_show_non_events($events) {
    usort($events, "evrplus_evrplus_time_cmp");
    $output = '';
    foreach ($events as $event) {
        $output .= evrplus_show_non_event($event) . '<br />';
    }
    return $output;
}

function evrplus_show_event($event, $day = 0) {
    global $wpdb;

    $company_options = EventPlus_Models_Settings::getSettings();
    $evrplus_date_format = EventPlus_Helpers_Funx::getDateFormat();

    $cal_head_clr = $company_options['evrplus_cal_head'];

    $cal_head_txt_clr = $company_options['cal_head_txt_clr'];
    $cal_use_cat = $company_options['evrplus_cal_use_cat'];
    $cal_pop_brdr_clr = $company_options['evrplus_cal_pop_border'];
    $cal_day_clr = $company_options['evrplus_cal_cur_day'];
    $cal_day_txt_clr = $company_options['cal_day_txt_clr'];
    $date_switcher = $company_options['evrplus_date_select'];
    $cal_day_hdr_clr = $company_options['evrplus_cal_day_head'];
    $cal_day_hdr_txt_clr = $company_options['cal_day_head_txt_clr'];

    $show_cat = $cal_use_cat;
    $category_identifier = '';
    $cat_array = @unserialize($event->category_id);
    
    $style = "background: white; border: 2px solid #2BB0D7;";
    $edge = '#b8ced6';
    if ($show_cat == 'Y') {
        $cat_id = $cat_array[0];
       
        $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id='" . $cat_id . "'";
        $cat_details = $wpdb->get_row($sql);
        
        if ($cat_details != "") {
            $style = "background: white; border: 2px solid " . stripslashes($cat_details->category_color) . "; ";
            $edge = $cat_details->category_color;
            $category_identifier = $cat_details->category_identifier;
        } else {
            $style = 'background: white; border: 2px solid ' . $cal_pop_brdr_clr . ';';
            $edge = $cal_pop_brdr_clr;
        }
    } else {
        if ($cal_pop_brdr_clr != "") {
            $style = 'background: white; border: 2px solid ' . $cal_pop_brdr_clr . ';';
            $edge = $cal_pop_brdr_clr;
        } else {
            $style = "background: white; border: 2px solid #2BB0D7;";
            $edge = '#b8ced6';
        }
    }
    
    if (isset($company_options['show_num_seats']) and $company_options['show_num_seats'] == 'yes') {
        $num = 0;
        $sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE event_id='$event->id' AND payment_status = '" . EventPlus_Models_Payments::PAYMENT_SUCCESS . "'";

        $attendee_count = $wpdb->get_var($sql2);
        If ($attendee_count >= 1) {
            $num = $attendee_count;
        }
        If ($num < $event->reg_limit) {
            $available = $event->reg_limit - $num;
        } else {
            $available = 0;
        }
        If ($available >= 1) {
            $seats = $available . " " . __("Seats", 'evrplus_language');
        }
        If ($available <= 0) {
            $seats = __("Event Full", 'evrplus_language');
        }
        if (!isset($event->reg_limit) or empty($event->reg_limit) or $event->reg_limit == 999999)
            $seats = __("Unlimited", 'evrplus_language');
    }else {
        $seats = '';
    }
    
    if ($event->more_info != '') {
        $linky = stripslashes($event->more_info);
    } else {
        $linky = evrplus_permalink($company_options['evrplus_page_id']) . "action=evrplusegister&event_id=" . $event->id;
    }
    
    $style_event_catgry = '';
    $event_id = $event->id;

    $curdate = date( "Y-m-j" );
    $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id = $event_id";
    $rows = $wpdb->get_results( $sql );

    $event_name = stripslashes( $rows['0']->event_name );
    $event_img = stripslashes( $rows['0']->image_link );
    $event_dis = stripslashes( $rows['0']->event_desc );
    $tooltip_status = $company_options['evrplus_tooltip_select'];

    $event_url = stripslashes( evrplus_permalink($company_options['evrplus_page_id']) . 'action=evrplusegister&event_id=' . $event_id );
    if (empty($event_img)) {
        //$event_img = plugins_url( 'images/calendar-icon.png', __FILE__ );
        $event_img = plugins_url( '../../assets/images/calendar-icon.png', __FILE__ );
    }

    $extraParam = '';
    $event_startdate = strtotime( $event->start_date );
    if( $event->recurrence_choice == "yes" ) {
        if( isset($_GET['month']) ) {
            $this_month = $_GET['month'];
        } else {
            $this_month = date('n');
        }

        if( isset($_GET['yr']) ) {
            $this_year = $_GET['yr'];
        } else {
            $this_year = date('Y');
        }

        $date = strtotime($day . '-' . $this_month . '-' . $this_year);
        $extraParam = '&recurr=' . $date;
        $event_startdate = $date;
    }
    
    if( $category_identifier != '' ) {
        
        $style_event_catgry = 'background:' . (stripslashes($cat_details->category_color)) . '!important; color:' . (stripslashes($cat_details->font_color)) . '!important;';
  
        $d_format = '<p class="dashiconsText">' . date_i18n($evrplus_date_format, $event_startdate) . '</P>';
        $d_format = date_i18n($evrplus_date_format, $event_startdate);
        $start_time = $event->start_time;
        $end_time = $event->end_time;
        if (isset($company_options['time_format']) and $company_options['time_format'] == '24hrs') {
            $start_time = date('H:i', strtotime($start_time));
            $end_time = date('H:i', strtotime($end_time));
        }
        if ($tooltip_status == 'Y') {

            $details = '<div class = "catgry">';
            $details = '<div class="dummy dummy-text"><span class="tooltip tooltip-effect-1">';
            $details .= '<a class="tooltip-item" href="' . evrplus_permalink($company_options['evrplus_page_id']) . 'action=evrplusegister&event_id=' . $event->id . $extraParam . '" style="' . $style_event_catgry . '">' . $event_name . '</a>';
            $details .='<span class="tooltip-content clearfix">
                            <span class="event_img" style="background:url(' . $event_img . ')"></span>
                            <span class="tooltip-text heading">
                                <span class="event_title">' . $event_name . '</span><br><br>'
                                .'<span style="font-size:15px;color: #666;" class="dashicons dashicons-calendar-alt"></span>
                                <span class="event_date">' . date_i18n($evrplus_date_format, $event_startdate) . '</span><br/>
                                <span style="font-size:15px;color: #666;" class="dashicons dashicons-clock"></span>
                                <span class="event_time">' . $start_time . ' - ' . $end_time . '</span>
                                <span class="tooltip-text">' . evrplus_Truncate_grid(html_entity_decode(stripslashes($event->event_desc)), 50, ' ') . '</span>
                                <span class="tooltip-text read-more">
                                    <a href=' . $event_url . '>'. __('read more', 'evrplus_language').'</a>
                                </span>
                            </span>
                        </span></div>';
        } else if (($tooltip_status = '') || ($tooltip_status = 'N')) {
            $details = '<div class = "catgry">';
            $details .= '<a href="' . evrplus_permalink($company_options['evrplus_page_id']) . 'action=evrplusegister&event_id=' . $event->id . $extraParam . '" style="' . $style_event_catgry . '">' . $event_name . '</a>';
        }
    } else {
        $d_format = '<p class="dashiconsText">' . date_i18n($evrplus_date_format, strtotime($event->start_date)) . '</P>';
        $d_format = date_i18n($evrplus_date_format, $event_startdate);

        if ($tooltip_status == 'Y') {
            $style_event_catgry = 'background:' . ($cat_details->category_color) . '!important;color:' . ($cat_details->font_color) . '!important;';
            $start_time = $event->start_time;
            $end_time = $event->end_time;
            if (isset($company_options['time_format']) and $company_options['time_format'] == '24hrs') {
                $start_time = date('H:i', strtotime($start_time));
                $end_time = date('H:i', strtotime($end_time));
            }

            $details = '<div class = "catgry">';
            $details = '<div class="dummy dummy-text"><span class="tooltip tooltip-effect-1">';
            $details .= '<a class="tooltip-item" href="' . evrplus_permalink($company_options['evrplus_page_id']) . 'action=evrplusegister&event_id=' . $event->id . $extraParam . '"style="' . $style_event_catgry . '">' . $event_name . '</a>';
            $details .='<span class="tooltip-content clearfix"><span class="event_img" style="background:url(' . $event_img . ')"></span>'
                    . '<span class="tooltip-text heading"><span class="event_title">' . $event_name . '</span><br><br>';

            if (count($cat_array) > 0) {
                $details .= '<span style="font-size:15px;color: #666;" class="dashicons dashicons-category"></span>'
                        . '<span class="event_date">' . EventPlus_Helpers_Funx::getCategoryList($cat_array) . '</span><br/>';
            }

            $details .= '<span style="font-size:15px;color: #666;" class="dashicons dashicons-calendar-alt"></span>'
                    . '<span class="event_date">' . date_i18n($evrplus_date_format, strtotime($event->start_date)) . '</span><br/>'
                    . '<span style="font-size:15px;color: #666;" class="dashicons dashicons-clock"></span>'
                    . '<span class="event_time">' . $start_time . ' - ' . $end_time . '</span>'
                    . '<span class="tooltip-text">' . evrplus_Truncate_grid(html_entity_decode(stripslashes($event->event_desc)), 50, ' ') . '</span><span class="tooltip-text read-more"><a href=' . $event_url . '>'.__('read more', 'evrplus_language').'</a></span> </span></span></div>';
        } else if (($tooltip_status = '') || ($tooltip_status = 'N')) {
            $details = '<div class = "catgry">';
            $details .= '<a href="' . evrplus_permalink($company_options['evrplus_page_id']) . 'action=evrplusegister&event_id=' . $event->id . $extraParam . '"style="' . $style_event_catgry . '">' . $event_name . '</a>';
        }
    }

    $details .= $seats;

    $details .= '<div style="display:none;">';
    $details .= '<div id="tip_' . $event->id . '" style="width: 510px;">';
    if ($event->image_link != '')
        $details .= '<div style="width: 100px;display: inline-block; "><div class="thumb" style="background-image: url(' . stripslashes($event->image_link) . ');"></div></div>';
    else
        $details .= '<div style="width: 100px;display: inline-block; "><div class="thumb" style="background-image: url(' . EVR_PLUGINFULLURL . 'images/calendar-icon.png);"></div></div>';
    
    $start_time = $event->start_time;
    $end_time = $event->end_time;
    if( isset($company_options['time_format']) and $company_options['time_format'] == '24hrs' ) {
        $start_time = date('H:i', strtotime($start_time));
        $end_time = date('H:i', strtotime($end_time));
    }
    $details .= '<div style="width: 300px;  margin-left: 80px;display: inline-block;position: relative;top: -20px;">'
            . '<h3 style="color:#666; margin-bottom: 0;">' . stripslashes($event->event_name) . '</h3>'
            . '<p style="color:#666;   line-height: 15px; margin-top: 0; font-size: 12px;">' . evrplus_Truncate(strip_tags(html_entity_decode(stripslashes($event->event_desc))), 15, ' ') . '</p>'
            . '<span style="color:#666;  font-size: 14px;">'
            . '<span class="dashicons dashicons-calendar-alt"></span>' . date_i18n($evrplus_date_format, strtotime($event->start_date)) . '</span>'
            . '<br/><span style="color:#666;  font-size: 14px;"><span class="dashicons dashicons-clock"></span> '
            . 'Time: ' . $start_time . ' - ' . $end_time . '</span>'
            . '</div>';
    $details .= '</div>';
    $details .= '</div>';
    $details.='</div>';
    
    return $details;
}

#Used for colorbox popup with event details.

function evrplus_colorbox_cal_content($events) {
    global $wpdb;

    $evrplus_date_format = EventPlus_Helpers_Funx::getDateFormat();

    #retrieve company and configuration settings
    $company_options = EventPlus_Models_Settings::getSettings();

    if ($events) {

        $listing = "";
        foreach ($events as $event) {
            $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));
            if ($event->close == "start") {
                $close_dt = $event->start_date . " " . $event->start_time;
            } else if ($event->close == "end") {
                $close_dt = $event->end_date . " " . $event->end_time;
            } else if ($event->close == "") {
                $close_dt = $event->start_date . " " . $event->start_time;
            }

            $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
            $expiration_date = strtotime($stp);
            $today = strtotime($current_dt);
            $listing .= '<div style="display:none;"><div id="event_content_' . $event->id . '" style="padding:10px; background:#e9e9e9;">';
            $listing .= '<div id="evrplus_pop_top"><span style="float:center;">';
            if ($event->header_image != "") {
                $listing .= '<img style="width: 100% !important;" class="evrplus_pop_hdr_img" src="' . $event->header_image . '" />';
            }
            $listing .='</span></div><div id="evrplus_pop_title"><div style="float:left;"><h3>' . stripslashes(html_entity_decode($event->event_name)) . '</h3></div>'
                    . '<div style="float: right; width: 200px; text-align: center; padding: 4px;"><a style="text-decoration:none;" href="' . EVENT_PLUS_PUBLIC_URL . 'ics.php?event_id=' . $event->id . '"><div class="evrplus_addcal_icon"></div><div class="evrplus_addcal">' . __('Add to your calendar', 'evrplus_language') . '</div></a></div></div>';
            $listing .='<div class="date_time" style="float:left;">';
            $listing .='<p class="event_date"><span class="dashicons dashicons-calendar-alt"></span> ' . date_i18n($evrplus_date_format, strtotime($event->start_date)) . '  -  ';

            if ($event->end_date != $event->start_date) {
                $listing .= date_i18n($evrplus_date_format, strtotime($event->end_date));
            }
            $start_time = $event->start_time;
            $end_time = $event->end_time;
            if (isset($company_options['time_format']) and $company_options['time_format'] == '24hrs') {
                $start_time = date('H:i', strtotime($start_time));
                $end_time = date('H:i', strtotime($end_time));
            }
            $listing .= '</p><p class="event_time"><span class="dashicons dashicons-clock"></span> ';
            $listing .= __('Time', 'evrplus_language') . ': ' . $start_time . " - " . $end_time;
            $listing .='</p></div>';
            $url = urlencode(add_query_arg(array('action' => 'evrplusegister', 'event_id' => $event->id), get_permalink(get_page_by_path('evrplus_registration'))));
            $listing .= '<div style="float: right; text-align: center; margin-right: 17px; width: 75px;"><a style="text-decoration:none;" target="_blank" href="https://twitter.com/home?status=' . $url . '"><div class="evrplus_tw_icon"></div><div class="evrplus_socialt">' . __('Tweet', 'evrplus_language') . '</div></a></div>';
            $listing .= '<div style="float: right; text-align: center; width: 85px; margin-right: 40px;"><a style="text-decoration:none;" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '"><div class="evrplus_fb_icon"></div><div class="evrplus_socialf">' . __('Share', 'evrplus_language') . '</div></a></div>';
            $listing .='<div class="evrplus_spacer"></div><div id="evrplus_pop_body" STYLE="text-align: justify;white-space:pre-wrap;">';
            $listing .=html_entity_decode(stripslashes($event->event_desc));
            $listing .='</div><div id="evrplus_pop_image">';
            if ($event->image_link != "") {
                $listing .='<img class="evrplus_pop_img" src="' . $event->image_link . '" alt="Thumbnail Image" />';
            } else {
                $listing .= '<img class="evrplus_pop_img" src="' . EventPlus_Helpers_Funx::assetUrl('images/event_icon.png') . '" />';
            }
            $listing .='</div><div class="evrplus_spacer"><hr /></div><div id="evrplus_pop_venue"><div id="evrplus_pop_address"><b><u>';
            $listing .= '<div class="dashicons dashicons-location"></div>' . __('Location', 'evrplus_language') . '</u></b><br/><br/>';
            $listing .= stripslashes($event->event_location) . '<br/>' . $event->event_address . '<br/>';
            $listing .= $event->event_city . ', ' . $event->event_state . ' ' . $event->event_postal . '<br/></div><div id="evrplus_pop_map">';
            if ($event->google_map == "Y") {

                $event_address_map = str_replace(" ", "+", $event->event_address);
                $event_city_map = str_replace(" ", "+", $event->event_city);
                $event_state_map = str_replace(" ", "+", $event->event_state);
                $listing .='<iframe width="282" height="200" frameborder="0" style="border:5px solid #fff;border-radius:15px;" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDblf6OIl46COqBYUo2DBaxo0-PRl9SZEM&q=' . $event_address_map . ',' . $event_city_map . ',' . $event_state_map . '"></iframe>';
            }

            $listing .='</div></div><div id="evrplus_pop_priceddd"><hr /><b><u>';
            $listing .='<div class="dashicons dashicons-cart"></div>' . __('Event Fees', 'evrplus_language') . ':</u></b><br /><br />';
            $curdate = date("Y-m-d");
            $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event->id . " ORDER BY sequence ASC";
            $rows = $wpdb->get_results($sql);
            if ($rows) {
                foreach ($rows as $fee) {
                    $item_custom_cur = $fee->item_custom_cur;
                    if ($fee->item_custom_cur == "GBP") {
                        $item_custom_cur = "&pound;";
                    }
                    if ($fee->item_custom_cur == "USD") {
                        $item_custom_cur = "$";
                    }
                    $listing .= $item_custom_cur . ' ' . $fee->item_price . '   ' . $fee->item_title . '<br />';
                }
            }
            $listing .='</div><div class="evrplus_spacer"></div><div id="evrplus_pop_foot"><p align="center">';
            if ($expiration_date <= $today) {
                $alert = '<br/><font color="red">';
                $alert .= __('Registration is closed for this event.', 'evrplus_language');
                $alert .= '<br/>';
                $alert .= __('For more information or questions, please email: ', 'evrplus_language');
                $alert .= '</font><a href="mailto:' . $company_options['company_email'] . '">' . $company_options['company_email'] . '</a>';

                $listing .= $alert;
            } else {

                if ($event->more_info != "") {
                    $listing .='<input type="button" onClick="window.open(\'' . $event->more_info . '\');" value="' . __('MORE INFO', 'evrplus_language') . '"/>';
                }
                if ($event->outside_reg == "Y") {
                    $listing .='<input type="button" onClick="window.open(\'' . $event->external_site . '\');" value="' .
                            __('External Registration', 'evrplus_language') . '"/>';
                } else {
                    $listing .= '<input class="register_now_button" type="button" onClick="location.href=\'' . add_query_arg(array('action' => 'evrplusegister', 'event_id' => $event->id), get_permalink(get_page_by_path('evrplus_registration'))) . '\'" value="' .
                            __('REGISTER', 'evrplus_language') . '"/>';
                }
            }
            $listing .= '</p></div>';
            $listing .= '</div></div>';
        }
    }
    return $listing;
}

function evrplus_show_non_event($event) {
    global $wpdb;
    $company_options = EventPlus_Models_Settings::getSettings();
    $cal_head_clr = $company_options['evrplus_cal_head'];
    $cal_head_txt_clr = $company_options['cal_head_txt_clr'];
    $cal_use_cat = $company_options['evrplus_cal_use_cat'];
    $cal_pop_brdr_clr = $company_options['evrplus_cal_pop_border'];
    $cal_day_clr = $company_options['evrplus_cal_cur_day'];
    $cal_day_txt_clr = $company_options['cal_day_txt_clr'];
    $date_switcher = $company_options['evrplus_date_select'];
    $cal_day_hdr_clr = $company_options['evrplus_cal_day_head'];
    $cal_day_hdr_txt_clr = $company_options['cal_day_head_txt_clr'];
    $show_cat = $cal_use_cat;
    if ($show_cat == 'Y') {
        $cat_array = unserialize($event->category_id);
        $cat_id = $cat_array[0];

        $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id='" . $cat_id . "'";
        $cat_details = $wpdb->get_row($sql);

        if ($cat_details != "") {
            $style = "background: white; border: 2px solid " . stripslashes($cat_details->category_color) . "; ";
            $edge = $cat_details->category_color;
        } else {
            $style = 'background: white; border: 2px solid ' . $cal_pop_brdr_clr . ';';
            $edge = $cal_pop_brdr_clr;
        }
    } else {
        if ($cal_pop_brdr_clr != "") {
            $style = 'background: white; border: 2px solid ' . $cal_pop_brdr_clr . ';';
            $edge = $cal_pop_brdr_clr;
        } else {
            $style = "background: white; border: 2px solid #2BB0D7;";
            $edge = '#b8ced6';
        }
    }
    if ($event->link != '') {

        $linky = stripslashes($event->link);
    }
    $allow = '<p><ul><li><b><strong><i>';
    $tool_desc = strip_tags(stripslashes(html_entity_decode($event->event_desc)), $allow);
    $details = '<div class = "catgry">';
    if ($event->use_link == 'Y') {
        $details .='<a class="tooltip" href="' . $linky . '" style="text-decoration:none"><h3>' . stripslashes(html_entity_decode($event->event_name)) . '</h3>';
    } else {
        $details .='<a class="tooltip" > <h3 >' . stripslashes(html_entity_decode($event->event_name)) . '</h3>';
    }
    $details .='<span class="help" style ="' . $style . '">';
    $details .= '<em>' . stripslashes(html_entity_decode($event->event_name)) . '</em>' . evrplus_clean_inside_tags($tool_desc, $allow) . '</span></a>' . '<p class="time">' . date(get_option('time_format'), strtotime(stripslashes($event->start_time))) . "-" . date(get_option('time_format'), strtotime(stripslashes($event->end_time))) . '</p>' .
            '</div>';
    return $details;
}

function evrplus_fetch_events($y, $m, $d, $cat = null) {
    global $wpdb, $tod_no, $cal_no;
    $arr_events = array();
    $date = $y . '-' . $m . '-' . $d;

    $company_options = EventPlus_Models_Settings::getSettings();
    if ($company_options['order_event_list'] == 'DESC') {
        $events = $wpdb->get_results("SELECT * FROM " . get_option('evr_event') . " WHERE (str_to_date(start_date, '%Y-%m-%e') <= str_to_date('$date', '%Y-%m-%e') AND str_to_date(end_date, '%Y-%m-%e') >= str_to_date('$date', '%Y-%m-%e')) OR recurrence_choice='yes' ORDER BY str_to_date(start_time,'%h:%i%p') DESC");
    } else {
        $events = $wpdb->get_results("SELECT * FROM " . get_option('evr_event') . " WHERE (str_to_date(start_date, '%Y-%m-%e') <= str_to_date('$date', '%Y-%m-%e') AND str_to_date(end_date, '%Y-%m-%e') >= str_to_date('$date', '%Y-%m-%e')) OR recurrence_choice='yes' ORDER BY str_to_date(start_time,'%h:%i%p')  ASC");
    }
    foreach ($events as $event) {
        if ($event->recurrence_choice == "yes") {
            $event->end_date = $event->start_date;
        }
    }
    if (!empty($events)) {
        foreach ($events as $event) {

            if ($event->recurrence_choice == 'no') {
                array_push($arr_events, $event);
            } else {
                if (evrplus_calculate_recurring_dates($event, $date))
                    array_push($arr_events, $event);
            }
        }
    }
    return $arr_events;
}

function evrplus_fetch_non_events($y, $m, $d) {
    global $wpdb, $tod_no, $cal_no;
    $arr_non_events = array();
    $date = $y . '-' . $m . '-' . $d;

    if (get_option('evrplus_cal_active') == "Y") {
        $cal_events = $wpdb->get_results("SELECT * FROM " . get_option('evr_cal_tbl') . " WHERE str_to_date(start_date, '%Y-%m-%e') <= str_to_date('$date', '%Y-%m-%e') AND str_to_date(end_date, '%Y-%m-%e') >= str_to_date('$date', '%Y-%m-%e') ORDER BY id");
        if (!empty($cal_events)) {
            foreach ($cal_events as $cal_event) {
                array_push($arr_non_events, $cal_event);
            }
        }
    }
    return $arr_non_events;
}

function evrplus_upcoming_events() {
    global $wpdb, $future_days;
    $day_count = 1;
    while ($day_count < $future_days + 1) {
        list($y, $m, $d) = split("-", date("Y-m-d", mktime($day_count * 24, 0, 0, date("m", evrplus_time_offset()), date("d", evrplus_time_offset()), date("Y", evrplus_time_offset()))));
        $events = evrplus_fetch_events($y, $m, $d);
        usort($events, "evrplus_evrplus_time_cmp");
        if (count($events) != 0) {
            $output .= '<li>' . date_i18n(get_option('date_format'), mktime($day_count * 24, 0, 0, date("m", evrplus_time_offset()), date("d", evrplus_time_offset()), date("Y", evrplus_time_offset())));
            foreach ($events as $event) {
                if ($event->event_time == '00:00:00') {
                    $time_string = ' ' . __('all day', 'evrplus_language');
                } else {
                    $time_string = ' ' . __('Between', 'evrplus_language') . ' ' . date(get_option('time_format'), strtotime(stripslashes($event->start_time))) . ' - ' . date(get_option('time_format'), strtotime(stripslashes($event->end_time)));
                }
                $output .= '<ul><li>' . strip_tags($event->event_name) . ' (' . $time_string . ')';
                $output .= '<br />' . strip_tags($event->event_desc) . '</li>';
                $output .= '</ul>';
            }
            $output .= '</li>';
        }
        $day_count = $day_count + 1;
    }
    if ($output == '') {
        $output .='' . __('No event till now!', 'evrplus_language') . '</ul>';
    }
    $visual = '<ul>';
    $visual .= $output;
    $visual .= '</ul>';
    return $visual;
}

function evrplus_upcoming_event_list($content) {
    global $wpdb;
    $display = "true";
    if (preg_match('{EVR_UPCOMING}', $content)) {
        if ($display == 'true') {
            $cal_output = '<span class="page-upcoming-events"><B>Upcoming Events:</B><br />' . evrplus_upcoming_events() . '</span>';
            $content = str_replace('{EVR_UPCOMING}', $cal_output, $content);
        } else {

            $content = str_replace('{EVR_UPCOMING}', '', $content);
        }
    }
    return $content;
}