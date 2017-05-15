<?php
$curdate = date("Y-m-j");
#Set the count for the alternating color rows of the table
$color_row = "1";
#Set the the default month end number for events in case none is defined
$month_no = $end_month_no = '01';
#Clear start date and end date fields to ensure no carry over data 
$start_date = $end_date = '';

# Get events that end date is later than today and order by start date
if ($company_options['order_event_list'] == 'DESC') {
    $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY date(start_date) DESC";
} else {
    $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY date(start_date) ASC";
}
$rows = $wpdb->get_results($sql);
?>
<div class="event-cont">
    <table summary="<?php echo __('The list of upcoming events.', 'evrplus_language'); ?>">
        <?php
#Check and see if the sql querry returned rows, if they did then begin to return each row
        if ($rows) {
            $codeToReturn = '';
            foreach ($rows as $event) {
                #Determine when the event ends and compare that date and time to today's date and time
                $id = $event->id;
                $isRecurr = $wpdb->get_var("SELECT recurrence_choice FROM " . get_option('evr_event') . " WHERE id=" . (int) $id);
                $curr = EventPlus_Helpers_Event::check_recurrence($id);

                $cat_array = unserialize($event->category_id);
                $cat_id = $cat_array[0];

                $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id='" . (int) $cat_id . "' LIMIT 1";
                $cat_details = $wpdb->get_row($sql);

                $style_event_catgry = '#999';
                if ($cat_details != "") {
                    $category_identifier = $cat_details->category_identifier;
                    if ($category_identifier != '') {
                        $style_event_catgry = ($cat_details->category_color);
                    }
                }

                $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));
                $close_dt = $event->end_date . " " . $event->end_time;
                $today = strtotime($current_dt);
                $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
                $expiration_date = strtotime($stp);

                #check to see if there is a custom template for the table if not, use the default.
                #GT Populate variable
                $outside_reg = $event->outside_reg;
                if ($public_list_template == '') {
                    #Check to see if the end time of this event is later than now, if so then display then send the event details to a string
                    if ($stp >= $current_dt) {
                        #Set the row color for this row
                        if ($color_row == 1) {
                            $td_class = "odd";
                        } else if ($color_row == 2) {
                            $td_class = "even";
                        }
                        #Begin creation of string that will return event data in html format for table                 
                        $codeToReturn .= '<tr>'
                                . ' <td class="row ' . $td_class . '" style="border-right: 8px solid ' . $style_event_catgry . '">'
                                . '     <div class="col-sm-7 eve-details">';
                        if ($event->image_link == "") {
                            $codeToReturn .='<div class="thumb" style="background-image: url(' . $this->assetUrl('images/calendar-icon.png') . ');"></div>'
                                    . '<div class="eve-title">';
                        } else {
                            $codeToReturn .='<div class="thumb" style="background-image: url(' . $event->image_link . ');"></div>'
                                    . '<div class="eve-title">';
                        }

                        #Check to see if link only in company settings
                        if ($company_options['evrplus_list_format'] == "link") {
                            if ($outside_reg == "Y") {
                                echo $event->external_site;
                                $codeToReturn .= '<h3><a href="' . $event->external_site . '">{EVENT_NAME}</a></h3></div>';
                            } else {
                                echo $event->external_site;
                                $codeToReturn .= '<h3><a href="{EVENT_URL}">{EVENT_NAME}</a></h3></div>';
                            }
                        } else {
                            if ($outside_reg == "Y") {
                                $codeToReturn .= '<h3><a href="' . $event->external_site . '">{EVENT_NAME}</a></h3></div>';
                            } else {
                                // $codeToReturn .= '<a class="thickbox" href="#TB_inline?width=640&height=1005&inlineId=popup{EVENT_ID}&modal=false title='.stripslashes($event->event_name).'">{EVENT_NAME}</a>';
                                #changed to use colorbox popup
                                $codeToReturn .= '<h3><a href="' . evrplus_permalink($company_options['evrplus_page_id']) . 'action=evrplusegister&event_id=' . $event->id . ( ($curr) ? '&recurr=' . $curr : '' ) . '">{EVENT_NAME}</a></h3></div>';
                            }
                        }

                        if ($company_options['show_num_seats'] !== 'no') {
                            $open_seats = '<p>' . __('Open Seats', 'evrplus_language') . '
						<span class="seats" style="background-color: ' . $style_event_catgry . '">{EVENT_AVAIL_SPOTS}</span></p>';
                        } else {
                            $open_seats = '';
                        }
                        $codeToReturn .= '<div class="eve-desc"><p>{EVENT_SHORTDESC}</p>' . $open_seats . '</div> </div>
                            <div class="col-sm-4 timing">
                                <div class="time-cont">
                                    <div class="eve-start">
                                        <time datetime="2014-09-20" class="icon">
                                            <em>{EVENT_DAY_START_NAME}</em>
                                            <strong>{EVENT_MONTH_START_NAME}</strong>
                                            <span>{EVENT_DAY_START_NUMBER}</span>
                                        </time>
                                        <p style="position: relative;"><!--<span class="glyphicon-time"></span>-->
                                        <img src="' . $this->assetUrl('images/popup-time-icon.png') . '" style="  float: left;margin-right: 3px;margin-top: 2px;">
                                        {EVENT_TIME_START} ' . ( ($event->end_date == $event->start_date) ? ' - {EVENT_TIME_END}' : '' ) . '</p>
                                    </div>';
                        #Check to see if the start date and end date are the same, if they are don''t display end date, only time
                        if ($event->end_date != $event->start_date) {
                            $codeToReturn .='<span class="eve-sap">-</span>
                                    <div class="eve-end">
                                        <time datetime="2015-09-20" class="icon">
                                            <em>{EVENT_DAY_END_NAME}</em>
                                            <strong>{EVENT_MONTH_END_NAME}</strong>
                                            <span>{EVENT_DAY_END_NUMBER}</span>
                                        </time>
                                        <p><img src="' . $this->assetUrl('images/popup-time-icon.png') . '" style="  float: left;margin-right: 3px;margin-top: 2px;"> {EVENT_TIME_END}</p>
                                    </div>';
                        }
                        $codeToReturn .='</div></div></td></tr>';
                    }
                }
                #If a custom table template was defined use it instead of the default.
                else {
                    $codeToReturn .= $public_list_template;
                }
                #Now that we have created the row, change color for next row
                if ($color_row == 1) {
                    $color_row = "2";
                } else if ($color_row == 2) {
                    $color_row = "1";
                }
                #Now that we have created the string for this row, lets replace the tags with the real event data    
                $codeToReturn = str_replace("{EVENT_SHORTDESC}", evrplus_Truncate(html_entity_decode(stripslashes($event->event_desc)), 60, ' '), $codeToReturn);
                $event_name = stripslashes($event->event_name);
                $event_desc = stripslashes($event->event_desc);
                $codeToReturn = str_replace("\r\n", '', $codeToReturn);
                $parms = array('action' => 'evrplusegister', 'event_id' => $event->id);
                if ($curr) {
                    $parms['recurr'] = $curr;
                }

                echo "<pre>";
                print_r($event);
                echo "</pre>";

                if( isset($event->outside_reg) && $event->outside_reg == 'Y' ) {
                    $permaLink = !empty( $event->external_site ) ? $event->external_site : get_permalink( get_page_by_path('evrplus_registration') );
                } else {
                    $permaLink = get_permalink(get_page_by_path('evrplus_registration'));
                    if ($post_id > 0) {
                        $permaLink = get_permalink( get_page_by_path('evrplus_registration') );
                    }
                }

                $codeToReturn = str_replace("{EVENT_URL}", add_query_arg($parms, $permaLink), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_ID}", $event->id, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_NAME}", stripslashes($event->event_name), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_SHORTNAME}", evrplus_truncateWords(stripslashes($event->event_name), 8, "..."), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DESC}", stripslashes($event->event_desc), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_LOC}", stripslashes($event->event_location), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_ADDRESS}", stripslashes($event->event_address), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_CITY}", stripslashes($event->event_city), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_STATE}", stripslashes($event->event_state), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_POSTAL}", stripslashes($event->event_postal), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_START_NUMBER}", $event->start_month, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_START_NAME}", __(date("F", ($curr) ? $curr : strtotime($event->start_date)), 'evrplus_language'), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_START_NAME_3}", date("M", ($curr) ? $curr : strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_START_NUMBER}", date("j", ($curr) ? $curr : strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_START_NAME}", __(date("l", ($curr) ? $curr : strtotime($event->start_date)), 'evrplus_language'), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_START_NAME_3}", date("D", ($curr) ? $curr : strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_YEAR_START}", $event->start_year, $codeToReturn);

                $date_format = "M j, Y";
                $time_start = $event->start_time;
                $time_end = $event->end_time;
                $opt = EventPlus_Models_Settings::getSettings();

                if (isset($opt['date_format']) and $opt['date_format'] == 'eur')
                    $date_format = "j M Y";

                if (isset($opt['time_format']) and $opt['time_format'] == '24hrs') {
                    $time_start = date('H:i', strtotime($event->start_time));
                    $time_end = date('H:i', strtotime($event->end_time));
                }


                $codeToReturn = str_replace("{EVENT_TIME_START}", $time_start, $codeToReturn);
                $start = ($curr) ? date($date_format, $curr) : date($date_format, strtotime($event->start_date));
                $codeToReturn = str_replace("{EVENT_DATE_START}", $start, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_END_NUMBER}", $event->end_month, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_END_NAME}", __(date("F", ($curr) ? $curr : strtotime($event->end_date)), 'evrplus_language'), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_END_NAME_3}", date("M", ($curr) ? $curr : strtotime($event->end_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_END_NUMBER}", date("j", ($curr) ? $curr : strtotime($event->end_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_END_NAME}", __(date("l", ($curr) ? $curr : strtotime($event->end_date)), 'evrplus_language'), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_END_NAME_3}", date("D", ($curr) ? $curr : strtotime($event->end_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_YEAR_END}", $event->end_year, $codeToReturn);
                $end = ($curr) ? date($date_format, $curr) : date($date_format, strtotime($event->end_date));
                $codeToReturn = str_replace("{EVENT_DATE_END}", $end, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_TIME_END}", $time_end, $codeToReturn);

                #In order to get the number of seats we need to count all attendees for this event
                #Retrieve the number of registered attendees for this event from attendee db
                $sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE payment_status = 'success' AND event_id='$event->id'";

                $num = 0;

                $attendee_count = $wpdb->get_var($sql2);
                If ($attendee_count >= 1) {
                    $num = $attendee_count;
                }

                $available_spaces = 0;
                if ($event->reg_limit != "") {
                    $available_spaces = $event->reg_limit - $num;
                }

                if (!isset($event->reg_limit) or empty($event->reg_limit) or $event->reg_limit == 999999) {
                    $available_spaces = __("Unlimited", 'evrplus_language');
                }

                $codeToReturn = str_replace("{EVENT_AVAIL_SPOTS}", $available_spaces, $codeToReturn);
                #We have now finished this row, repeat the process for the remaining row(s)
            }

            #All rows should have been returned and put into string
            #Output html string to screen              
            echo $codeToReturn;
        }

        echo '</table>'
        . '</div>';

#Now that we have returned the table, we need to return the hidden html that provides the popups.
#Once again we will go through the retruned event data to generate the popup html
        if ($rows) {
            foreach ($rows as $event) {
                #use the included file to put all the event data for this event into strings
                //include "_event_array2string.php";
                #Generate the html popup code for this event
                //include "evrplus_event_colorbox_pop.php";
            }
        }