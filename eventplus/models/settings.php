<?php

class EventPlus_Models_Settings extends EventPlus_Abstract_Model {

    protected static $cache = null;

    static function getSettings($key = null) {

        if (self::$cache === null) {
            self::$cache = get_option('evr_company_settings');

            if (self::$cache['return_url'] <= 0) {
                self::$cache['return_url'] = self::$cache['evrplus_page_id']; /* FAllback page id */
            }
        }
		if ($key !== null) {
            return isset(self::$cache[$key]) ? self::$cache[$key] : null;
        } else {
            return self::$cache;
        }
    }

    static function getPaymentMethods() {
        $companyOptions = self::getSettings();
        return (array) $companyOptions['payment_vendor'];
    }

    function saveSettings($params) {
        if ($params['company_name'] != "") {
            $company_options = get_option('evrplus_company_settings');
            //$company_options = $params['company_settings'];
            $company_options['company'] = $params['company_name'];
            $company_options['company_street1'] = $params['company_street1'];
            $company_options['company_street2'] = $params['company_street2'];
            $company_options['company_city'] = $params['company_city'];
            $company_options['company_state'] = $params['company_state'];
            $company_options['company_postal'] = $params['company_postal'];
            $company_options['company_email'] = $params['email'];
            $company_options['secondary_email'] = $params['secondary_email'];
            $company_options['evrplus_page_id'] = $params['evrplus_page_id'];
            $company_options['splash'] = $params['splash'];
            $company_options['send_confirm'] = $params['send_confirm'];
            $company_options['message'] = ($params['message']);
            $company_options['wait_message'] = ($params['wait_message']);
            $company_options['thumbnail'] = $params['thumbnail'];
            $company_options['calendar_url'] = $params['evrplus_page_id'];            //$params['calendar_url';
            $company_options['default_currency'] = $params['default_currency'];
            $company_options['donations'] = $params['donations'];
            $company_options['checks'] = $params['checks'];
            $company_options['pay_now'] = $params['pay_now'];
            $company_options['payment_vendor'] = $params['payment_vendor'];
            $company_options['secret_key'] = $params['secret_key'];
            $company_options['publishable_key'] = $params['publishable_key'];
            $company_options['stripereturn_url'] = $params['stripereturn_url'];
            $company_options['payment_vendor_id'] = $params['payment_vendor_id'];
            $company_options['payment_vendor_key'] = $params['payment_vendor_key'];
            $company_options['use_authorize_sandbox'] = $params['use_authorize_sandbox'];
            $company_options['authorize_id'] = $params['authorize_id'];
            $company_options['authorize_key'] = $params['authorize_key'];
            $company_options['pay_msg'] = $params['pay_msg'];
            $company_options['return_url'] = $params['return_url'];
            $company_options['notify_url'] = $params['notify_url'];
            $company_options['cancel_return'] = $params['cancel_return'];
            $company_options['return_method'] = $params['return_method'];
            $company_options['use_sandbox'] = $params['use_sandbox'];
            $company_options['paypal_pdt_token'] = $params['paypal_pdt_token'];
            $company_options['image_url'] = $params['image_url'];
            $company_options['admin_message'] = ($params['admin_message']);
            $company_options['pay_confirm'] = $params['pay_confirm'];
            $company_options['payment_subj'] = stripslashes( $params['payment_subj'] );
            $company_options['payment_message'] = ($params['payment_message']);
            $company_options['c_message'] = ($params['c_message']);
            $company_options['info_recieved'] = ($params['info_recieved']);
            $company_options['captcha'] = $params['captcha'];
            $company_options['captcha_key'] = $params['captcha_key'];
            $company_options['event_pop'] = $params['event_pop'];
            $company_options['form_css'] = $params['form_css'];
            $start_of_week = $params['start_of_week'];
            $company_options['use_sales_tax'] = $params['use_sales_tax'];
            $company_options['googleMap_api_key'] = $params['googleMap_api_key'];
            $company_options['sales_tax_rate'] = $params['sales_tax_rate'];
            $company_options['start_of_week'] = $params['start_of_week'];
            $company_options['evrplus_date_select'] = $params['evrplus_date_select'];
            $company_options['evrplus_tooltip_select'] = $params['evrplus_tooltip_select'];
            $company_options['evrplus_cal_head'] = $params['evrplus_cal_head'];
            $company_options['time_format'] = $params['time_format'];
            $company_options['date_format'] = $params['date_format'];
            $company_options['show_num_seats'] = $params['show_num_seats'];
            $company_options['cal_head_txt_clr'] = $params['cal_head_txt_clr'];
            $company_options['evrplus_cal_cur_day'] = $params['evrplus_cal_cur_day'];
            $company_options['evrplus_cal_use_cat'] = $params['evrplus_cal_use_cat']; //true-false
            $company_options['evrplus_flag_add_to_cal_button'] = $params['evrplus_flag_add_to_cal_button']; //true-false
            $company_options['evrplus_cal_pop_border'] = $params['evrplus_cal_pop_border'];
            $company_options['cal_day_txt_clr'] = $params['cal_day_txt_clr'];
            $company_options['show_social_icons'] = $params['show_social_icons'];
            $company_options['evrplus_cal_day_head'] = $params['evrplus_cal_day_head'];
            $company_options['cal_day_head_txt_clr'] = $params['cal_day_head_txt_clr'];
            $company_options['evrplus_list_format'] = $params['evrplus_list_format'];
            $company_options['admin_noti'] = $params['admin_noti'];
            $company_options['order_event_list'] = $params['order_event_list'];
            $company_options['evrplus_tooltip_show'] = $params['tooltip_show'];
            $company_options['qty_discount'] = $params['qty_discount'];
            $company_options['qty_discount_settings'] = $params['qty_discount_settings'];
			
			$company_options['after_pay_confirm'] = $params['after_pay_confirm'];
			$company_options['after_payment_subj'] = $params['after_payment_subj'];
			$company_options['after_payment_message'] = $params['after_payment_message'];
			$company_options['qty_discount_settings'] = $params['qty_discount_settings'];
			$company_options['qty_discount_settings'] = $params['qty_discount_settings'];
			
            //$company_options['evrplus_invoice'] = $params['evrplus_invoice'];
            
			update_option('evr_company_settings', $company_options);
            update_option('evr_start_of_week', $start_of_week);
            $dwolla_enabled = $params['enable_dwolla'];
			update_option('evr_dwolla', $dwolla_enabled);
			$this->setMessage(__('Configuration settings saved', 'evrplus_language'));
            return true;
        } else {
            $this->setMessage(__('There was an error in your submission, please try again. The configuration data was not updated!', 'evrplus_language'));
            return false;
        }
    }

}
