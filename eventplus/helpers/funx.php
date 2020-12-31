<?php

class EventPlus_Helpers_Funx {

    protected static $evrplus_date_format = "l, M j, Y";

    static function getVersion() {
        return EventPlus::getPlugin()->getVersion();
    }

    static function getBuildVersion() {
        return EventPlus::getPlugin()->getBuildVersion();
    }

    static function getOldBuildVersion() {
        $oldBuildVersion = get_option('eventplus_build_version');
        if ($oldBuildVersion === false) {
            $oldBuildVersion = get_option('evr_event_version');
        }

        return $oldBuildVersion;
    }

    static function updateBuildVersion($version) {
        update_option('eventplus_build_version', $version);
    }

    static function assetUrl($uri_path) {
        if ($uri_path == '') {
            return EventPlus::getRegistry()->url->getAssetsUrl();
        } else {
            return EventPlus::getRegistry()->url->getAssetsUrl() . $uri_path;
        }
    }

    static function getDateFormat() {
        $evrplus_date_format = self::$evrplus_date_format;

        if ($opt = EventPlus_Models_Settings::getSettings()) {
            if (isset($opt['date_format']) and $opt['date_format'] == 'eur')
                $evrplus_date_format = "l, j M Y";
        }

        return $evrplus_date_format;
    }

    static function dateSelector($inName, $useDate = 0) {
        $str = '';
        /* create array so we can name months */
        $monthName = array(1 => "January", "February", "March",
            "April", "May", "June", "July", "August",
            "September", "October", "November", "December");

        /* if date invalid or not supplied, use current time */

        if ($useDate == 0) {
            $useDate = time();
        }

        /* make month selector */

        $str .= "<SELECT NAME=" . $inName . "_month\">\n";

        for ($currentMonth = 1; $currentMonth <= 12; $currentMonth++) {

            $str .= '<OPTION VALUE="';
            $str .= intval($currentMonth) . '"';
            if (intval(date("m", $useDate)) == $currentMonth) {
                $str .= ' SELECTED ';
            }
            $str .= '>' . __($monthName[$currentMonth], 'evrplus_language') . '</option>';
        }

        $str .= "</SELECT>";

        /* make day selector */
        $str .= "<SELECT NAME=" . $inName . "_day\">\n";
        for ($currentDay = 1; $currentDay <= 31; $currentDay++) {

            $str .= "<OPTION VALUE=\"$currentDay\"";

            if (intval(date("d", $useDate)) == $currentDay) {

                $str .= " SELECTED";
            }

            $str .= ">$currentDay\n";
        }

        $str .= "</SELECT>";

        /* make year selector */

        $str .= "<SELECT NAME=" . $inName . "_year\">\n";

        $startYear = date("Y", $useDate);

        for ($currentYear = $startYear - 50; $currentYear <= $startYear + 10; $currentYear++) {

            $str .= "<OPTION VALUE=\"$currentYear\"";

            if (date("Y", $useDate) == $currentYear) {

                $str .= " SELECTED";
            }

            $str .= ">$currentYear\n";
        }

        $str .= "</SELECT>";
        return $str;
    }

    static function truncate($string, $limit, $break = ".", $pad = "...") {
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

    static function truncateWords($input, $numwords, $padding = "...") {
        $output = strtok($input, " \n");
        while (--$numwords > 0) {
            $output .= " " . strtok(" \n");
        }
        if ($output != $input)
            $output .= $padding;
        return $output;
    }

    static function moneyFormat($number, $currencySymbol = '', $decPoint = '.', $thousandsSep = ',', $decimals = 2) {
        return $currencySymbol . number_format($number, $decimals, $decPoint, $thousandsSep);
    }

    function truncateGrid($string, $limit, $break = ".", $pad = "...") {
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

    static function getCategoryList($event_category_ids, $limit = 0) {
        $oCategory = new EventPlus_Models_Categories();
        $event_category_dataset = $oCategory->getCategoriesKeys(array('id_collection' => $event_category_ids, 'limit' => $limit));

        $category_list_str = '';
        if (count($event_category_dataset)) {
            $cNames = array();
            foreach ($event_category_dataset as $k => $cRow) {
                $cNames[] = $cRow['category_name'];
            }

            $category_list_str = implode(', ', $cNames);
        }

        return $category_list_str;
    }

    static function getAttachmentId($image_url) {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s' LIMIT 1", $image_url));
        
        return isset( $attachment[0] ) ? $attachment[0] : '';
    }

    static function getTimestamp($datetime_string) {

		$tz_string = get_option('timezone_string');
		$tz_offset = get_option('gmt_offset', 0);

		if( !empty($tz_string) ) {
			// If site timezone option string exists, use it
			$timezone = $tz_string;
		} elseif( $tz_offset == 0 ) {
			// get UTC offset, if it isn’t set then return UTC
			$timezone = 'UTC';
		} else {
			$timezone = $tz_offset;
			if(substr($tz_offset, 0, 1) != "-" && substr($tz_offset, 0, 1) != "+" && substr($tz_offset, 0, 1) != "U") {
				$timezone = "+" . $tz_offset;
			}
		}
		$datetime = new DateTime($datetime_string, new DateTimeZone($timezone));
		
		return $datetime->format('U');

        //return strtotime($datetime_string);
    }

    static function getRegistrationPages() {

        $args = array('sort_order' => 'ASC', 'sort_column' => 'post_title', 'hierarchical' => 1, 'exclude' => '', 'include' => '', 'meta_key' => '', 'meta_value' => '', 'authors' => '', 'child_of' => 0, 'parent' => -1, 'exclude_tree' => '', 'number' => '', 'offset' => 0, 'post_type' => 'page', 'post_status' => 'trash');
        $trash_pages = get_pages($args);
        $list_trash = '';
        $exclude = array();
        foreach ($trash_pages as $p) {
            $exclude[] = $p->ID;
        }

        $defaults = array(
            'child_of' => 0,
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
            'hierarchical' => 1,
            'exclude' => $exclude,
            'include' => array(),
        );
        $pages = get_pages($defaults);

        $pObjets = array();

        foreach ($pages as $page) {
            if (preg_match('{EVRREGIS}', $page->post_content)) {
                $pObjets[] = $page;
            }
            elseif (preg_match('[eventsplus_registration]', $page->post_content)) {
                $pObjets[] = $page;
            }
        }

        return $pObjets;
    }

    static function getRegistrationPage() {

        $evrplus_page_id = EventPlus_Models_Settings::getSettings('evrplus_page_id');

        return get_post($evrplus_page_id);
    }

    static function isValidRegistrationPage() {

        $page = self::getRegistrationPage();

        $is_valid = false;
        if (is_object($page)) {
            if (strstr($page->post_content,'{EVRREGIS}')) {
                $is_valid = true;
            }
        }
        
        return $is_valid;
    }

    static function promoBanner($width=''){
        
        $hide_ads = intVal(get_option('eventplus_hide_ads', false));
        if($hide_ads === 1){
            return '';
        }
                
        if($width == ''){
            $width = '460';
        }
        return '<div style="text-align:center;"><a href="http://bit.ly/eventastic-banner" target="_blank"><img width="'.$width.'" src="'.EVENT_PLUS_PLUGIN_URL.'assets/images/promo/eventsuite-banner.gif" /></a> <br /><small>[<a href="'. EventPlus::get('registry')->get('url')->admin('admin_settings', array('hide_ad' => 1)).'">Hide Ad</a>]</small></div>';
    }
}
