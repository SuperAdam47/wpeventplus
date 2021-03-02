<?php

class EventPlus_Helpers_Token {

    private static $pendingTokenRow = array();
    
    static function generate($event_id) {
        $request = EventPlus::factory('Request');
 
        $t = md5(time() . rand(0, 999999) . $event_id . $request->getUserAgent() . $request->getClientIp() . wp_generate_password( 20, true, true ));
     
        return $t;
    }

    static function get($event_id) {
        return EventPlus_Cookie::get('eplus_reg_token_' . $event_id);
    }

    static function set($event_id, $token) {
        return EventPlus_Cookie::set('eplus_reg_token_' . $event_id, $token);
    }
    
    static function delete($event_id) {
        return EventPlus_Cookie::delete('eplus_reg_token_' . $event_id);
    }

    static function isValid($event_id) {
        return (self::get($event_id) != '');
    }
    
    static function isValidFormat($token) {
        return (strlen($token) == 32);
    }

    static function getPendingRow() {
        return self::$pendingTokenRow;
    }
    
    static function setPendingRow($row) {
        self::$pendingTokenRow = $row;
    }

    static function doToken($event_id) {
        
        $isValid = self::isValid($event_id);

        if ($isValid === false) {
            $token = self::generate($event_id);
            self::set($event_id, $token);
        } else {
            $token = self::get($event_id);
            
            if(self::isPending($token) === false){
                $token = self::generate($event_id);
                self::set($event_id, $token);
            }else{
                self::setPendingRow(self::getDataByToken($token));
            }
        }

        return $token;
    }

    static function isPending($token) {
        
        $tokenRow = self::getDataByToken($token, true);
		if(!empty($tokenRow)){
			if ($tokenRow['payment_status'] == '' || $tokenRow['payment_status'] == null || $tokenRow['payment_status'] == 'pending') {
				return true;
			} else {
				return false;
			}
		}else{
			return true;
		}
    }

    protected static $dataCache = array();
    static function getDataByToken($token, $cache = true) {
        global $wpdb;
        
        $sql = "SELECT * FROM " . get_option('evr_attendee') . " WHERE token  = '" . esc_sql($token) . "' LIMIT 1";
        
        if($cache == false && isset(self::$dataCache[$token])){
           return $wpdb->get_row($sql, ARRAY_A);
        }
        
        if(isset(self::$dataCache[$token]) == false){
            self::$dataCache[$token] = $wpdb->get_row("SELECT * FROM " . get_option('evr_attendee') . " WHERE token  = '" . esc_sql($token) . "' LIMIT 1", ARRAY_A);
        }
        
        return self::$dataCache[$token];
    }

}
