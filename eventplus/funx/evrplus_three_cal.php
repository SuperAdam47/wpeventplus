<?php
/**
 * @author wpeventsplus.com
 * @copyright 2014
 */
##Set the number of future days for upcoming events listing##
$future_days = "90";
$evrplus_date_format = EventPlus_Helpers_Funx::getDateFormat();

/* * ***************************** Display the Three Calendar in a page ************************* */
function evrplus_mini_cal_calendar_replace($content) {
    if (preg_match('{EVR_MINI_CALENDARS}', $content)) {
        ob_start();
        echo '<div style = "width:32%; float:left;">';
        evrplus_mini_cal_display_calendar(date("Y", evrplus_time_offset()), strtolower(date("m", evrplus_time_offset()))); //function with main content
        $month = strtolower(date('M', strtotime('+1 month', time())));
        $yr = date('Y', strtotime('+1 month', time()));
        echo '</div><div style = "width:2%; float:left;"></div><div style = "width:32%; float:left;">';
        evrplus_mini_cal_display_calendar($month, $yr);
        $month = strtolower(date('M', strtotime('+2 month', time())));
        $yr = date('Y', strtotime('+2 month', time()));
        echo '</div><div style = "width:2%; float:left;"></div><div style = "width:32%; float:left;">';
        evrplus_mini_cal_display_calendar($month, $yr);
        echo '</div>';
        $buffer = ob_get_contents();
        ob_end_clean();
        $content = str_replace('{EVR_MINI_CALENDARS}', $buffer, $content);
    }
    return $content;
}

function evrplus_mini_cal_display_calendar($c_month, $c_year) {
    global $wpdb, $week_no;
    unset($week_no);
    $_GET['month'] = $c_month;
    $_GET['yr'] = $c_year;
    if (get_option('evr_start_of_week') == 0) {
        $name_days = array(1 => __('S', 'evrplus_language'), __('M', 'evrplus_language'), __('T', 'evrplus_language'), __('W', 'evrplus_language'), __('T', 'evrplus_language'), __('F', 'evrplus_language'), __('S', 'evrplus_language'));
    } else {
        $name_days = array(1 => __('M', 'evrplus_language'), __('T', 'evrplus_language'), __('W', 'evrplus_language'), __('T', 'evrplus_language'), __('F', 'evrplus_language'), __('S', 'evrplus_language'), __('S', 'evrplus_language'));
    }
    $name_months = array(1 => __('January', 'evrplus_language'), __('February', 'evrplus_language'), __('March', 'evrplus_language'), __('April', 'evrplus_language'), __('May', 'evrplus_language'), __('June', 'evrplus_language'), __('July', 'evrplus_language'), __('August', 'evrplus_language'), __('September', 'evrplus_language'), __('October', 'evrplus_language'), __('November', 'evrplus_language'), __('December', 'evrplus_language'));
    if (empty($_GET['month']) || empty($_GET['yr'])) {
        $c_year = date("Y", evrplus_time_offset());
        $c_month = date("m", evrplus_time_offset());
        $c_day = date("d", evrplus_time_offset());
    }
    if ($_GET['yr'] <= 3000 && $_GET['yr'] >= 0 && (int) $_GET['yr'] != 0) {
        if ($_GET['month'] == 'jan' || $_GET['month'] == 'feb' || $_GET['month'] == 'mar' || $_GET['month'] == 'apr' || $_GET['month'] == 'may' || $_GET['month'] == 'jun' || $_GET['month'] == 'jul' || $_GET['month'] == 'aug' || $_GET['month'] == 'sept' || $_GET['month'] == 'oct' || $_GET['month'] == 'nov' || $_GET['month'] == 'dec') {
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
    $calendar_body .= '<table class="evrplus_mini_cal_calendar-table mainTable"  >';
    $date_switcher = "false";
    if ($date_switcher == 'true') {
        $calendar_body .= '<tr><td colspan="7" class="calendar-date-switcher"><form method="get" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">';
        $qsa = array();
        foreach ($qsa as $name => $argument) {
            if ($name != 'month' && $name != 'yr') {
                $calendar_body .= '<input type="hidden" name="' . strip_tags($name) . '" value="' . strip_tags($argument) . '" />';
            }
        }
        $calendar_body .= '' . __('Month', 'evrplus_language') . ': <select name="month" style="width:100px;">
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
            </select>
            ' . __('Year', 'evrplus_language') . ': <select name="yr" style="width:70px;">';
        $past = 30;
        $future = 30;
        $fut = 1;
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
    //added to make calendar match large calendar
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
    ?>
    <style>
        .s2 {background-color:white;}</style>
    <?php if ($cal_head_clr != "") { ?>
        <style type="text/css">
            .monthYearRow {background-color:<?php echo $cal_head_clr; ?>;color: <?php echo $cal_head_txt_clr; ?>;}
        </style>
    <?php }
    if ($cal_day_clr != "") {
        ?>
        <style type="text/css">
            .today { background-color:<?php echo $cal_day_clr; ?>;color: <?php echo $cal_day_txt_clr; ?>;}
        </style>
    <?php }
    if ($cal_day_hdr_clr != "") {
        ?>
        <style type="text/css">
            .dayNamesRow { background-color:<?php echo $cal_day_hdr_clr; ?>;color: <?php echo $cal_day_hdr_txt_clr; ?>;}
        </style>
    <?php
    }
    $calendar_body .= '
                    <tr>
                    <td  class="monthYearText monthYearRow" colspan="7">' . $name_months[(int) $c_month] . ' ' . $c_year . '</td>
                    </tr>';
    $calendar_body .= '<tr class="dayNamesText">';
    for ($i = 1; $i <= 7; $i++) {
        if (get_option('evr_start_of_week') == 0) {
            $calendar_body .= '<td class="dayNamesRow" style="width:14%;">' . $name_days[$i] . '</td>';
        } else {
            $calendar_body .= '<td class="dayNamesRow" style="width:14%;">' . $name_days[$i] . '</td>';
        }
    }
    $calendar_body .= '</tr>';
    for ($i = 1; $i <= $days_in_month;) {
        $calendar_body .= '<tr class="rows">';
        for ($ii = 1; $ii <= 7; $ii++) {
            $go = true;
            if ($ii == $first_weekday && $i == 1) {
                $go = TRUE;
            } elseif ($i > $days_in_month) {
                $go = FALSE;
            }
            if ($go) {
                if (get_option('evr_start_of_week') == 0) {
                    $grabbed_events = evrplus_fetch_events($c_year, $c_month, $i);
                    $no_events_class = '';
                    if (!count($grabbed_events)) {
                        $no_events_class = ' s2';
                    } else {
                        $no_events_class = ' s22';
                    }
                    $calendar_body .= '<td class="' . (date("Ymd", mktime(0, 0, 0, $c_month, $i, $c_year)) == date("Ymd", evrplus_time_offset()) ? ' today' : 'day-with-datedrt') . $no_events_class . '">' . $i++ . '<span class="evrplus_mini_cal_event">' . evrplus_mini_cal_show_events($grabbed_events) . '</span></td>';
                } else {
                    $grabbed_events = evrplus_fetch_events($c_year, $c_month, $i);
                    $no_events_class = '';
                    if (!count($grabbed_events)) {
                        $no_events_class = ' s2';
                    } else {
                        $no_events_class = ' s21';
                    }
                    $calendar_body .= '<td class="' . (date("Ymd", mktime(0, 0, 0, $c_month, $i, $c_year)) == date("Ymd", evrplus_time_offset()) ? ' today' : 'day-with-datedrt') . $no_events_class . '">' . $i++ . '<span class="evrplus_mini_cal_event" >' . evrplus_mini_cal_show_events($grabbed_events) . '</span></td>';
                }
            } else {
                $calendar_body .= ' <td class="sOther">&nbsp;</td>';
            }
        }
        $calendar_body .= '</tr>';
    }
    $show_cat = false;
    if ($show_cat == 'true') {
        //Future Add
    }
    $calendar_body .= '</table>';
    echo $calendar_body;
    return $calendar_body;
}

/* * **********************    Display the events  ******************************** */
function evrplus_mini_cal_show_events($events) {
    //If you want to pupup event info.  
}

function evrplus_mini_cal_show_event($event) {
    global $wpdb;
    $company_options = EventPlus_Models_Settings::getSettings();
    $show_cat = "true";
    if ($show_cat == 'true') {
        $cat_array = unserialize($event->category_id);
        $cat_id = $cat_array[0];
        $cat_details = "";
        if ($cat_id != "") {
            $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id=" . (int)$cat_id;
            $cat_details = $wpdb->get_row($sql);
        }
        if ($cat_details != "") {
            $style = "background-color:" . stripslashes($cat_details->category_color) . " ; color:" . stripslashes($cat_details->font_color) . " ;";
        } else {
            $style = "background-color:#F6F79B;color:" . "#000000" . " ;";
        }
    } else {
        $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id=1";
        $cat_details = $wpdb->get_row($sql);
        $style = "background-color:#F6F79B;color:" . "#000000" . " ;";
    }
    $header_details .= '<span class="event-title" style="color:' . "#000000" . '">' . stripslashes(html_entity_decode($event->event_name));
    $header_details .= '</span><br/>';
    $event_id = $event->id;
    $reg_limit = $event->reg_limit;
    $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE  payment_status = 'success' AND event_id=%d", $event_id));
    if ($number_attendees == '' || $number_attendees == 0) {
        $number_attendees = '0';
    }
    if ($reg_limit == "" || $reg_limit == " ") {
        $reg_limit = "Unlimited";
    }
    $available_spaces = $reg_limit;
    //$number_attendees  
    if ($reg_limit == "Unlimited")
        $evrplus_mini_cal_details = '<div class="evrplus_mini_cal_add_extra_nfo unlimited_seats" style="display:block;"><i>Unilimited</i></div>';
    elseif ($number_attendees == $reg_limit)
        $evrplus_mini_cal_details = '<div class="evrplus_mini_cal_add_extra_nfo evrplus_mini_cal_waiting_list"><i>Waiting List</i></div>';
    else
        $evrplus_mini_cal_details = '<div class="evrplus_mini_cal_add_extra_nfo evrplus_mini_cal_nr_of_seats"><i>' . ((int) $reg_limit - (int) $number_attendees ) . ' Seats</i></div>';
    /* end evrplus_mini_cal */
    $details = '<span class="calnk_evrplus_mini_cal">' . '<span style="' . $style . '">' . $header_details . '</span><div class="evrplus_mini_cal-custom-cat evrplus_mini_cal_custom_cat_' . $cat_id . '"></div>' . $evrplus_mini_cal_details . '</span>';
    return $details;
}