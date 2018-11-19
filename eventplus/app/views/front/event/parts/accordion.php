<?php

#Set the the default month end number for events in case none is defined
$month_no = $end_month_no = '01';
#Clear start date and end date fields to ensure no carry over data 
$start_date = $end_date = '';
#retrieve company and configuration settings
$company_options = EventPlus_Models_Settings::getSettings();

$curdate = date("Y-m-j");

# Get events that end date is later than today and order by start date
$sql = "SELECT * FROM " . get_option('evr_event') . " WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e')";
$rows = $wpdb->get_results($sql);

#include style sheet for accordian here to esnure style was not overwritten by other style elsewhere!
include "_accordian_style.php";

#start accordian html outpupt
echo '<div class="evrplus_accordion">';
echo '<section id="close"><h2><a href="#Close">Click on Event for Details - Click Here to Collapse All</a></h2><div></div></section>';
#Check and see if the sql querry returned rows, if they did then begin to return each row   
if ($rows) {
    foreach ($rows as $event) {
        #use the included file to put all the event data for this event into strings
        include "_event_array2string.php";
        #Generate the html accordian code for this event
        //include "evrplus_public_event_accordian.php";
        $codeToReturn .='<section id="' . $event_id . '"><h2><a href="#' . $event_id . '">'
                . strtoupper($event_name) . '<br/><br/>' . date($evrplus_date_format, strtotime($start_date)) . '  -  ';
        if ($end_date != $start_date) {
            $codeToReturn .= date($evrplus_date_format, strtotime($end_date));
        }
        $codeToReturn .= __('&nbsp;&nbsp;&nbsp;&nbsp;Time: ', 'evrplus_language') . ' ' . $start_time . ' - ' . $end_time . '</a></h2><div>';
        $codeToReturn .='<div class="evrplus_spacer"></div><div style="text-align: justify;white-space:pre-wrap;"><p>'
                . html_entity_decode($event_desc) . '</p></div><span style="float:right;">';
        $codeToReturn .='<a href="' . EVENT_PLUS_PUBLIC_URL . 'ics.php?event_id=' . (int) $event_id . '">
                                <img src="' . $this->assetUrl('images/ical-logo.jpg') . '" /></a></span>';
        $codeToReturn .='<div class="evrplus_spacer"><hr /></div><div style="float: left;width: 310px;">
                                <p><b><u>' . __('Location', 'evrplus_language') . '</u></b><br/><br/>' . stripslashes($event_location);
        $codeToReturn .='<br />' . $event_address . '<br />' . $event_city . ', ' . $event_state . ' ' . $event_postal . '<br /></p></div>';
        $codeToReturn .='<div style="float: right;width: 280px;"> <div id="evrplus_pop_map">';
        if ($google_map == "Y") {
            //$codeToReturn .='<img border="0" src="http://maps.google.com/maps/api/staticmap?center='.
            //         $event_address.','.$event_city.','.$event_state.
            //       '&zoom=14&size=280x180&maptype=roadmap&markers=size:mid|color:0xFFFF00|label:*|'.
            //     $event_address.','.$event_city.'&sensor=false" />';							   							   							   							   $event_address_map=str_replace(" ","+",$event_address);					$event_city_map=str_replace(" ","+",$event_city);					$event_state_map=str_replace(" ","+",$event_state);					$codeToReturn .= '<iframe					width="282"					height="200"					frameborder="0" 					style="border:5px solid #fff;border-radius:15px;"					src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDblf6OIl46COqBYUo2DBaxo0-PRl9SZEM&q='.$event_address_map.','.$event_city_map.','.$event_state_map.'">				</iframe>';
        }
        $codeToReturn .='</div></div><div id="evrplus_pop_price"><p><b><u>' . __('Event Fees', 'evrplus_language') . ':</u></b><br /><br />';

        #Get event fees from the cost database for this event
        $sql_fees = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " .  (int)$event_id . " ORDER BY sequence ASC";
        $fee_rows = $wpdb->get_results($sql_fees);
        if ($fee_rows) {
            foreach ($fee_rows as $fee) {
                $item_custom_cur = $fee->item_custom_cur;
                if( $item_custom_cur == "GBP" ) {
                    $item_custom_cur = "&pound;";
                } else if( $item_custom_cur == "USD" ) {
                    $item_custom_cur = "$";
                } else if( $item_custom_cur == "EUR" ) {
                    $item_custom_cur = "€";
                }

                $codeToReturn .=$fee->item_title . '   ' . $item_custom_cur . ' ' . $fee->item_price . '<br />';
                /*
                  while ($row2 = mysql_fetch_assoc ($result2)){
                  $item_id          = $row2['id'];
                  $item_sequence    = $row2['sequence'];
                  $event_id         = $row2['event_id'];
                  $item_title       = $row2['item_title'];
                  $item_description = $row2['item_description'];
                  $item_cat         = $row2['item_cat'];
                  $item_limit       = $row2['item_limit'];
                  $item_price       = $row2['item_price'];
                  $free_item        = $row2['free_item'];
                  $item_start_date  = $row2['item_available_start_date'];
                  $item_end_date    = $row2['item_available_end_date'];
                  echo $item_title.'   '.$item_custom_cur.' '.$item_price.'<br />';
                 */
            }
        }
        $codeToReturn .='</p></div><div class="evrplus_spacer"></div><div id="evrplus_pop_foot"><p align="center">';
        if ($more_info != "") {
            $codeToReturn .='<input type="button" onClick="window.open(\'' . $more_info . '\');" value=\'MORE INFO\'/>';
        }
        if ($outside_reg == "Y") {
            $codeToReturn .='<input type="button" onClick="window.open(\'' . $external_site . '\');" value=\'External Registration\'/>';
        } else {
            $codeToReturn .='<input type="button" onClick="location.href=\'' . evrplus_permalink($company_options['evrplus_page_id']) .
                    'action=evrplusegister&event_id=' . $event_id . '\'" value=\'REGISTER\'/>';
        }
        $codeToReturn .='</p></div></div></section>';
        #end of event
    }
    echo $codeToReturn;
}
echo '</div>';
