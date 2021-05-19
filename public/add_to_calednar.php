<?php
ini_set('display_errors','off');
error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);

if (file_exists('../../../../wp-config.php')) {
    
    require_once( '../../../../wp-config.php');
    global $wpdb;
    $curdate = date_i18n("Ymd");
    $curtime = date_i18n("His");
    (is_numeric($_REQUEST['event_id'])) ? $event_id = $_REQUEST['event_id'] : $event_id = 0;
    $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id=" . (int)$event_id;
    $result = $wpdb->get_results($sql, ARRAY_A);

    if( !empty($result) ){
        foreach ($result as $row) {
            $event_id = $row['id'];
            $event_name = html_entity_decode(stripslashes($row['event_name']), ENT_NOQUOTES, 'UTF-8');
            $event_identifier = stripslashes($row['event_identifier']);
            $event_location = html_entity_decode(stripslashes($row['event_location']), ENT_NOQUOTES, 'UTF-8');
            $event_desc = html_entity_decode(stripslashes($row['event_desc']), ENT_NOQUOTES, 'UTF-8');
            $event_address = esc_html( $row['event_address'] );
            $event_city = esc_html( $rw['event_city'] );
            $event_state = esc_html($row['event_state'] );
            $event_postal = esc_html( $row['event_postal'] );
            $reg_limit = esc_html( $row['reg_limit'] );
            $start_time = esc_html( $row['start_time'] );
            $end_time = esc_html( $row['end_time'] );
            $conf_mail = esc_html( $row['conf_mail'] );
            $start_date = esc_html( $row['start_date'] );
            $end_date = esc_html(  $row['end_date'] );
        }
    }
    
    header("Content-Type: text/Calendar");
    header("Content-Disposition: inline; filename=" . rawurlencode($event_name) . ".ics");

    echo "BEGIN:VCALENDAR\n";
    echo "TZID:". get_option('timezone_string')."\n";
    echo "BEGIN:VEVENT\n";
    echo "CLASS:PUBLIC\n";
    echo "CREATED:" . $curdate . "T" . $curtime . "\n";
    echo "DESCRIPTION:" . str_replace("\r\n", "\\n", $event_desc) . "\n";
    echo "DTEND:" . date("Ymd", strtotime($end_date)) . "T" . date("His", strtotime($end_time)) . "Z\n";
    echo "DTSTAMP:" . $curdate . "T" . $curtime . "\n";
    
    echo "DTSTART:" . date("Ymd", strtotime($start_date)) . "T" . date("His", strtotime($start_time)) . "\n";
    echo "LAST-MODIFIED:20091109T101015\n";
    echo "LOCATION:" . $event_location . ", " . $event_address . ", " . $event_city . ", " . $event_state . ", " . $event_postal . "\n";
    
    echo "SUMMARY;LANGUAGE=en-us:" . $event_name . "\n";
    echo "BEGIN:VALARM\n";
    echo "TRIGGER:-PT1440M\n";
    echo "ACTION:DISPLAY\n";
    echo "DESCRIPTION:Reminder\n";
    echo "END:VALARM\n";
    echo "END:VEVENT\n";
    echo "END:VCALENDAR\n";
} else {
    echo "Invalid request!";
}