<?php
if( function_exists('evrplus_issetor') == false ) {
    function evrplus_issetor($variable, $or = NULL) {
        return $variable === NULL ? $or : $variable;
    }

}

if (function_exists('evrplus_evrplus_time_cmp') == false) {

    function evrplus_evrplus_time_cmp($a, $b) {
        if ($a->start_time == $b->start_time) {
            return 0;
        }
        return ($a->event_time < $b->event_time) ? -1 : 1;
    }

}

if (function_exists('evrplus_clean_inside_tags') == false) {

    function evrplus_clean_inside_tags($txt, $tags) {
        preg_match_all("/<([^>]+)>/i", $tags, $allTags, PREG_PATTERN_ORDER);
        foreach ($allTags[1] as $tag) {
            $txt = preg_replace("/<" . $tag . "[^>]*>/i", "<" . $tag . ">", $txt);
        }
        return $txt;
    }

}

if (function_exists('evrplus_calculate_recurring_dates') == false) {

    function evrplus_calculate_recurring_dates($get_event, $get_date) {
        $date = date('Y-m-d', strtotime($get_date));

        if ($get_event->recurrence_choice == 'yes') {
            $recurrence_period = $get_event->recurrence_period;

            $recurrence_frequency = $get_event->recurrence_frequency;
            $recurrence_repeat_period = $get_event->recurrence_repeat_period;
            $event_start_date = date('Y-m-d', strtotime($get_event->start_date));
            $event_end_date = date('Y-m-d', strtotime($get_event->end_date));
            $event_next_start_date = $event_start_date;
            $event_next_end_date = $event_end_date;

            //calculating next dates..

            if ($recurrence_period == 'daily') {
                $days_total_to_add = "+" . 1 * ($recurrence_repeat_period + 1) . " day";
                for ($i = 1; $i <= $recurrence_frequency; $i++) {
                    if ($date >= $event_next_start_date && $date <= $event_next_end_date) {
                        return true;
                    }
                    $event_next_start_date = date('Y-m-d', strtotime($days_total_to_add, strtotime($event_next_start_date)));
                    $event_next_end_date = date('Y-m-d', strtotime($days_total_to_add, strtotime($event_next_end_date)));
                }
            } elseif ($recurrence_period == 'weekly') {
                $days_total_to_add = "+" . 7 * ($recurrence_repeat_period + 1) . " day";
                //calculating subsequent starting and ending dates..
                for ($i = 1; $i <= $recurrence_frequency; $i++) {
                    if ($date >= $event_next_start_date && $date <= $event_next_end_date) {
                        return true;
                    }
                    $event_next_start_date = date('Y-m-d', strtotime($days_total_to_add, strtotime($event_next_start_date)));
                    $event_next_end_date = date('Y-m-d', strtotime($days_total_to_add, strtotime($event_next_end_date)));
                }
            } else if ($recurrence_period == 'monthly') {
                $days_total_to_add = "+" . 1 * ($recurrence_repeat_period + 1) . " month";
                //calculating subsequent starting and ending dates..
                for ($i = 1; $i <= $recurrence_frequency; $i++) {
                    if ($date >= $event_next_start_date && $date <= $event_next_end_date) {
                        return true;
                    }
                    $event_next_start_date = date('Y-m-d', strtotime($days_total_to_add, strtotime($event_next_start_date)));
                    $event_next_end_date = date('Y-m-d', strtotime($days_total_to_add, strtotime($event_next_end_date)));
                }
            } else if ($recurrence_period == 'yearly') {
                $days_to_add = 1;
                $days_total_to_add = "+" . 1 * ($recurrence_repeat_period + 1) . " year";
                //calculating subsequent starting and ending dates..
                for ($i = 1; $i <= $recurrence_frequency; $i++) {
                    if ($date >= $event_next_start_date && $date <= $event_next_end_date) {
                        return true;
                    }
                    $event_next_start_date = date('Y-m-d', strtotime($days_total_to_add, strtotime($event_next_start_date)));
                    $event_next_end_date = date('Y-m-d', strtotime($days_total_to_add, strtotime($event_next_end_date)));
                }
            }
            return false;
        }
    }

}

if (function_exists('evrplus_time_offset') == false) {

    function evrplus_time_offset() {
        return (time() + (3600 * (get_option('gmt_offset'))));
    }

}

if (function_exists('evrplus_month_compare') == false) {

    function evrplus_month_compare($month) {
        $current_month = strtolower(date("M", evrplus_time_offset()));
        if (isset($_GET['yr']) && isset($_GET['month'])) {
            if ($month == $_GET['month']) {
                return ' selected="selected"';
            }
        } elseif ($month == $current_month) {
            return ' selected="selected"';
        }
    }

}

if (function_exists('evrplus_year_compare') == false) {

    function evrplus_year_compare($year) {
        $current_year = strtolower(date("Y", evrplus_time_offset()));
        if (isset($_GET['yr']) && isset($_GET['month'])) {
            if ($year == $_GET['yr']) {
                return ' selected="selected"';
            }
        } else if ($year == $current_year) {
            return ' selected="selected"';
        }
    }

}

if (function_exists('evrplus_np_of_day') == false) {

    // Function to indicate the number of the day passed, eg. 1st or 2nd Sunday
    function evrplus_np_of_day($date) {

        $instance = 0;

        $dom = date('j', strtotime($date));
        if (($dom - 7) <= 0) {
            $instance = 1;
        } else if (($dom - 7) > 0 && ($dom - 7) <= 7) {
            $instance = 2;
        } else if (($dom - 7) > 7 && ($dom - 7) <= 14) {
            $instance = 3;
        } else if (($dom - 7) > 14 && ($dom - 7) <= 21) {
            $instance = 4;
        } else if (($dom - 7) > 21 && ($dom - 7) < 28) {
            $instance = 5;
        }

        return $instance;
    }

}

if (!function_exists('evrplus_permalink_prefix')) {

    function evrplus_permalink_prefix() {
        if (is_home()) {
            $p_link = get_bloginfo('url');
            if ($p_link[strlen($p_link) - 1] != '/') {
                $p_link = $p_link . '/';
            }
        } else {
            $p_link = get_permalink();
        }
        if (!(strstr($p_link, '?'))) {
            $link_part = $p_link . '?';
        } else {
            $link_part = $p_link . '&';
        }
        return $link_part;
    }

}

if (!function_exists('evrplus_permalink')) {

    function evrplus_permalink($page_id) {
        if (is_home()) {
            $p_link = get_bloginfo('url');
            if ($p_link[strlen($p_link) - 1] != '/') {
                $p_link = $p_link . '/';
            }
        } else {
            $p_link = get_permalink($page_id);
        }
        if (!(strstr($p_link, '?'))) {
            $link_part = $p_link . '?';
        } else {
            $link_part = $p_link . '&';
        }
        return $link_part;
    }

}

if (!function_exists('evrplus_get_month_shortname')) {
     function evrplus_get_month_shortname($month_code) {
         $months = array(
             'jan' => __('Jan', 'evrplus_language'),
             'feb' => __('Feb', 'evrplus_language'), 
             'mar' => __('Mar', 'evrplus_language'), 
             'apr' => __('Apr', 'evrplus_language'), 
             'may' => __('May', 'evrplus_language'), 
             'jun' => __('Jun', 'evrplus_language'), 
             'jul' => __('Jul', 'evrplus_language'), 
             'aug' => __('Aug', 'evrplus_language'), 
             'sept' => __('Sep', 'evrplus_language'), 
             'oct' => __('Oct', 'evrplus_language'), 
             'nov' => __('Nov', 'evrplus_language'), 
             'dec' => __('Dec', 'evrplus_language')
            );
         
         return $months[strtolower($month_code)];
     }
}

if (!function_exists('evrplus_next_link')) {
    /*     * ****** Configure the "Next" link in the calendar  ************ */

    function evrplus_next_link($cur_year, $cur_month) {

        $mod_rewrite_months = array(1 => 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec');
        $next_year = $cur_year + 1;

        $fragment = apply_filters( 'evrplus_link_fragment', '', array(
                                        'link' => 'next',
                                        'cur_year' => $cur_year,
                                        'cur_month' => $cur_month
                                    ) );

        if ($cur_month == 12) {

            $next_links = '<a href="' . evrplus_permalink_prefix() . 'month=jan&amp;yr=' . $next_year .$fragment. '">' . strtoupper(__('Jan', 'evrplus_language')) . ' &raquo;</a>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="' . evrplus_permalink_prefix() . 'month=feb&amp;yr=' . $next_year .$fragment. '">' . strtoupper(__('Feb', 'evrplus_language')) . ' &raquo;</a>';
        } else if ($cur_month == 11) {
            $next_links = '<a href="' . evrplus_permalink_prefix() . 'month=dec&amp;yr=' . $cur_year.$fragment. '">' . strtoupper(__('Dec', 'evrplus_language')) . ' &raquo;</a>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="' . evrplus_permalink_prefix() . 'month=jan&amp;yr=' . $next_year.$fragment. '">' . strtoupper(__('Jan', 'evrplus_language')) . ' &raquo;</a>';
        } else {
            $next_month = $cur_month + 1;
            $next_next_month = $cur_month + 2;
            $month = $mod_rewrite_months[$next_month];
            $month_after = $mod_rewrite_months[$next_next_month];
            $t_month = strtoupper(evrplus_get_month_shortname($month));

            $t_month_after = strtoupper(evrplus_get_month_shortname($month_after));
            $next_links = '<a href="' . evrplus_permalink_prefix() . 'month=' . esc_attr( $month ) . '&amp;yr=' . esc_attr( $cur_year .$fragment). '">' . $t_month . ' &raquo;</a>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="' . evrplus_permalink_prefix() . 'month=' . esc_attr($month_after) . '&amp;yr=' . $cur_year .$fragment. '">' . $t_month_after. ' &raquo;</a>';
        }
        return $next_links;
    }

}

if (!function_exists('evrplus_prev_link')) {

    /*     * *******  Configure the "Previous" link in the calendar  ************* */

    function evrplus_prev_link($cur_year, $cur_month) {
        $mod_rewrite_months = array(1 => 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sept', 'oct', 'nov', 'dec');
        $last_year = $cur_year - 1;

        $fragment = apply_filters( 'evrplus_link_fragment', '', array(
                                        'link' => 'prev',
                                        'cur_year' => $cur_year,
                                        'cur_month' => $cur_month
                                    ) );

        if ($cur_month == 1) {
            $prev_links = '<a href="' . evrplus_permalink_prefix() . 'month=nov&amp;yr=' . $last_year .$fragment. '">&laquo; ' . strtoupper(__('Nov', 'evrplus_language')) . '</a>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="' . evrplus_permalink_prefix() . 'month=dec&amp;yr=' . $last_year . $fragment .'">&laquo; ' . strtoupper(__('Dec', 'evrplus_language')) . '</a>';
        } else if ($cur_month == 2) {
            $prev_links = '<a href="' . evrplus_permalink_prefix() . 'month=dec&amp;yr=' . $last_year .$fragment. '">&laquo; ' . strtoupper(__('Dec', 'evrplus_language')) . '</a>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="' . evrplus_permalink_prefix() . 'month=jan&amp;yr=' . $cur_year.$fragment . '">' . strtoupper(__('Jan', 'evrplus_language')) . ' &raquo;</a>';
        } else {
            $prev_month = $cur_month - 1;
            $prev_prev_month = $cur_month - 2;
            $month = $mod_rewrite_months[$prev_month];
            $prev_month = $mod_rewrite_months[$prev_prev_month];
            $prev_links = '<a href="' . evrplus_permalink_prefix() . 'month=' . $prev_month . '&amp;yr=' . $cur_year .$fragment. '">&laquo; ' . strtoupper(evrplus_get_month_shortname($prev_month)) . '</a>' . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . '<a href="' . evrplus_permalink_prefix() . 'month=' . $month . '&amp;yr=' . $cur_year . $fragment.'">&laquo; ' . strtoupper(evrplus_get_month_shortname($month)) . '</a>';
        }
        return $prev_links;
    }

}

if (!function_exists('evrplus_generate_frm_defaults')) {

    function evrplus_generate_frm_defaults($field, $tag, $value = '') {
        ?>
        <li>
            <label for="<?php echo $field; ?>"><?php echo esc_html( $tag ); ?></label>
            <span class="fieldbox"><input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo esc_html($value); ?>" /></span>
        </li>
        <?php
    }

}

if (!function_exists('evrplus_Truncate')) {

    function evrplus_Truncate($string, $limit, $break = ".", $pad = "...") {
        // return with no change if string is shorter than $limit
        if (strlen($string) <= $limit)
            return $string;
        // is $break present between $limit and the end of the string?
        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }
        return $string;
    }

}


if (!function_exists('evrplus_truncateWords')) {

    function evrplus_truncateWords($input, $numwords, $padding = "...") {
        $output = strtok($input, " \n");
        while (--$numwords > 0)
            $output .= " " . strtok(" \n");
        if ($output != $input)
            $output .= $padding;
        return $output;
    }

}

if (!function_exists('evrplus_get_open_seats')) {

    function evrplus_get_open_seats($event_id, $reg_limit) {
        global $wpdb;

        $num = 0;
        $sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE payment_status = '" . EventPlus_Models_Payments::PAYMENT_SUCCESS . "' AND event_id='$event_id'";
        $attendee_count = $wpdb->get_var($sql2);
        If ($attendee_count >= 1) {
            $num = $attendee_count;
        }
        $open_seats = $reg_limit - $num;
        return $open_seats;
    }

}

if (!function_exists('evrplus_greaterDate')) {

    function evrplus_greaterDate($start_date, $end_date) {
        $start = strtotime($start_date);
        $end = strtotime($end_date);
        if ($start - $end >= 0)
            return 1;
        else
            return 0;
    }

}

if (!function_exists('evrplus_htmlchanger')) {

    function evrplus_htmlchanger($string) {
        $string = str_replace(array("&lt;", "&gt;", '&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array("<", ">", '&', '\'', '"', '<', '>'), htmlspecialchars_decode($string, ENT_NOQUOTES));
        return $string;
    }

}

if (!function_exists('evrplus_form_build')) {

    function evrplus_form_build($question, $answer = "") {
        $required = '';
        if ($question->required == "Y") {
            $required = ' class="r"';
        }
        if ($question->remark) {
            $title = $question->remark;
        }
        switch ($question->question_type) {
            case "TEXT" :
                echo "<span class=\"fieldbox\"><input type=\"text\" $required id=\"TEXT_$question->id\"  name=\"TEXT_$question->id\" size=\"40\" title=\"$question->question\" value=\"$answer\" /></span>\n";
                break;
            case "TEXTAREA" :
                echo "<span class=\"msgbox\"><textarea id=\"TEXTAREA_$question->id\" $required name=\"TEXTAREA_$question->id\" title=\"$question->question\" cols=\"30\" rows=\"5\">$answer</textarea></span>\n";
                break;
            case "SINGLE" :
                $values = explode(",", $question->response);
                $answers = explode(",", $answer);
                foreach ($values as $key => $value) {
                    $checked = in_array($value, $answers) ? " checked=\"checked\"" : "";
                    echo '<span class="radio"><input id="SINGLE_' . $question->id . '_' . $key . '" ' . $required . ' name="SINGLE_' . $question->id . '" title="' . substr($question->question, 0, 4) . '" type="radio" value="' . $value . '" ' . $checked . ' /> ' . $value . '</span>';
                }
                break;
            case "MULTIPLE" :
                $values = explode(",", $question->response);
                $answers = explode(",", $answer);
                foreach ($values as $key => $value) {
                    $checked = in_array($value, $answers) ? " checked=\"checked\"" : "";
                    echo "<span class=\"radio\"><input id=\"$value\" $required name=\"MULTIPLE_$question->id[]\" title=\"$question->question\" type=\"checkbox\" value=\"$value\" $checked /> $value</span>\n";
                }
                break;
            case "DROPDOWN" :
                $values = explode(",", $question->response);
                $answers = explode(",", $answer);
                echo "<select name=\"DROPDOWN_$question->id\" $required id=\"DROPDOWN_$question->id\" title=\"$question->question\" />";
                echo "<option value=''>Select One </option><br/>";
                foreach ($values as $key => $value) {
                    $checked = in_array($value, $answers) ? " selected =\" selected\"" : "";
                    echo "<option value=\"$value\" /> $value</option><br/>\n";
                }
                echo "</select>";
                break;
            default :
                break;
        }
    }

}

if (!function_exists('evrplus_form_build_edit')) {

    function evrplus_form_build_edit($question, $edits) {
        $required = '';
        if ($question->required == "Y") {
            $required = ' class="r"';
        }
        switch ($question->question_type) {
            case "TEXT" :
                echo "<span class=\"fieldbox\"><input type=\"text\"$required id=\"TEXT_$question->id\"  name=\"TEXT_$question->id\" size=\"40\" title=\"$question->question\" value=\"$edits\" /></span>";
                break;
            case "TEXTAREA" :
                echo "<span class=\"msgbox\"><textarea id=\"TEXTAREA_$question->id\"$required name=\"TEXTAREA_$question->id\" title=\"$question->question\" cols=\"30\" rows=\"5\">" . $edits . "</textarea></span>";
                break;
            case "SINGLE" :
                $values = explode(",", $question->response);
                $answers = explode(",", $edits);
                foreach ($values as $key => $value) {
                    $checked = in_array($value, $answers) ? " checked=\"checked\"" : "";
                    echo "<p class=\"hanging-indent radio_rows\"><input id=\"SINGLE_$question->id_$key\"$required name=\"SINGLE_$question->id\" title=\"$question->question\" type=\"radio\" value=\"$value\"$checked /> $value  </p>";
                }
                break;
            case "MULTIPLE" :
                $values = explode(",", $question->response);
                $answers = explode(",", $edits);
                foreach ($values as $key => $value) {
                    $checked = in_array($value, $answers) ? " checked=\"checked\"" : "";
                    /* 	echo "<label><input type=\"checkbox\"$required id=\"MULTIPLE_$question->id_$key\" name=\"MULTIPLE_$question->id_$key\" title=\"$question->question\" value=\"$value\"$checked /> $value</label><br/>\n"; */
                    echo " <p class=\"hanging-indent radio_rows\"><input id=\"$value\"$required name=\"MULTIPLE_$question->id[]\" title=\"$question->question\" type=\"checkbox\" value=\"$value\"$checked /> $value  </p>";
                }
                break;
            case "DROPDOWN" :
                $values = explode(",", $question->response);
                //$answers = explode ( ",", $edits );
                echo "<select name=\"DROPDOWN_$question->id\"$required id=\"DROPDOWN_$question->id\" title=\"$question->question\" />" . BR;
                echo "<option value=\"$edits\">$edits</option><br/>";
                foreach ($values as $key => $value) {
                    //$checked = in_array ( $value, $answers ) ? " selected =\" selected\"" : "";
                    echo "<option value=\"$value\" /> $value</option><br/>\n";
                }
                echo "</select>";
                break;
            default :
                break;
        }
    }

}

if (!function_exists('evrplus_Truncate_grid')) {

    function evrplus_Truncate_grid($string, $limit, $break = ".", $pad = "...") {
        // return with no change if string is shorter than $limit
        if (strlen($string) <= $limit)
            return $string;
        // is $break present between $limit and the end of the string?
        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }
        return $string;
    }

}


if (!function_exists('wpeventplus_get_open_seats')) {

    function wpeventplus_get_open_seats($event_id, $reg_limit) {

        if (trim($reg_limit) == "" || $reg_limit >= 999999) {
            return "Unlimited";
        }

        global $wpdb;

        $num = 0;
        $sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE payment_status = '" . EventPlus_Models_Payments::PAYMENT_SUCCESS . "' AND event_id='$event_id'";
        $attendee_count = $wpdb->get_var($sql2);
        If ($attendee_count >= 1) {
            $num = $attendee_count;
        }
        $open_seats = $reg_limit - $num;
        return $open_seats;
    }

}

require_once EVENT_PLUS_PLUGIN_FRAMEWORK_PATH . "funx/evrplus_calendar.php";
require_once EVENT_PLUS_PLUGIN_FRAMEWORK_PATH . "funx/evrplus_three_cal.php";
