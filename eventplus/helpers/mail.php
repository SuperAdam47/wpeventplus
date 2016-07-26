<?php
final class EventPlus_Helpers_Mail {
    
    function send_wp_mail($to, $subject, $message, $headers = '', $attachments = array()) {
       return wp_mail( $to, $subject, $message, $headers, $attachments );
    }
    
    function adminUrl($uri, array $params = array()){
        return EventPlus::getRegistry()->url->admin($uri, $params);
    }

}
