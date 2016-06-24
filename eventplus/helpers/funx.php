<?php

class EventPlus_Helpers_Funx {

    protected static $evrplus_date_format = "l, M j, Y";

    static function getVersion() {
        return EventPlus::getPlugin()->getVersion();
    }
    
    static function assetUrl($uri_path){
         if($uri_path == ''){
            return EventPlus::getRegistry()->url->getAssetsUrl();
        }else{
             return EventPlus::getRegistry()->url->getAssetsUrl() . $uri_path;
        }
    }

    static function getDateFormat() {
        $evrplus_date_format = self::$evrplus_date_format;

        if ($opt = get_option('evr_company_settings')) {
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

}
