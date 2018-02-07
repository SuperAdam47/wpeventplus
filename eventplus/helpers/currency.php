<?php

class EventPlus_Helpers_Currency {

    private static $currency_codes = array(
        'USD', 'JOD', 'TWD', 'TRY', 'AED', 'THB', 'RUB', 'NOK', 'MYR', 'BRL', 'AUD', 'GBP', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'ARS',
        'JPY', 'MXN', 'NZD', 'NOK', 'PLN', 'SGD', 'SEK', 'CHF', 'BOB', 'MUR', 'RON', 'LPS', 'KWR', 'ZAR', 'SAR', 'PHP', 'INR'
    );
    
    static function get_currency_list(){
        $currency_codes = self::$currency_codes;
        return apply_filters('eventplus_currency_codes', $currency_codes);
    }

}
