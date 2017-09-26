<?php

class EventPlus_Models_Events_Discounts extends EventPlus_Abstract_Model {

    private function isValidArray($array) {

        if (is_array($array) == false) {
            return false;
        }

        $isValid = false;
        foreach ($array as $i => $v) {
            if (intval($v) >= 0 && $v <= 100) {
                $isValid = true;
                break;
            }
        }

        return $isValid;
    }

    function getSettings($event_id) {

        $discountSettings = array();

        $company_options = EventPlus_Models_Settings::getSettings();
        if ($company_options['qty_discount'] == 'N' || $company_options['qty_discount'] == '') {
            return $discountSettings;
        }

        $oEventMeta = new EventPlus_Models_Events_Meta();
        $meta_data = $oEventMeta->getAllOptions($event_id);

        if ($meta_data['qty_discount'] == 'N') {
            return $discountSettings;
        }

        if ($company_options['qty_discount'] == 'Y') {

            if (is_array($company_options['qty_discount_settings'])) {
                $company_options['qty_discount_settings'] = array_filter($company_options['qty_discount_settings'], 'strlen');

                if ($this->isValidArray($company_options['qty_discount_settings'])) {
                    $discountSettings = $company_options['qty_discount_settings'];
                }
            }
        }


        if ($meta_data['qty_discount'] == 'Y') {
            if ($this->isValidArray($meta_data['qty_discount_settings'])) {
                $discountSettings = $meta_data['qty_discount_settings'];
            }
        }

        return $discountSettings;
    }

}
