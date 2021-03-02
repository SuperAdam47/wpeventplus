<?php

class EventPlus_Helpers_App {

    function eventPlusInit() {
        $this->doOputputBufer();

        EventPlus::factory('Helpers_Assets')->init();

        $this->doUpgrade();
    }

    protected function doUpgrade() {
        global $wpdb;

        $oldBuildVersion = EventPlus_Helpers_Funx::getOldBuildVersion();
        $currentBuildVersion = EventPlus::getPlugin()->getBuildVersion();

        if ($oldBuildVersion < $currentBuildVersion && $oldBuildVersion !== false) {

            if ($oldBuildVersion <= '6.00.31') {

                $checkCol = "SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '" . get_option('evr_event') . "' AND COLUMN_NAME = 'disable_event_reg' ";
                $colExists = (count($wpdb->get_results($checkCol, ARRAY_N)) > 0 );

                if ($colExists == 0) {

                    $sql = "ALTER TABLE `" . get_option('evr_event') . "` ADD `disable_event_reg` ENUM('Y','N') NOT NULL DEFAULT 'N' AFTER `event_name`;";
                    $q = $wpdb->query($sql);
                }

                $wpdb->query('ALTER TABLE ' . get_option('evr_payment') . ' CHANGE `txn_id` `txn_id` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;');
            }

            EventPlus_Helpers_Funx::updateBuildVersion($currentBuildVersion);
        }

        if ($oldBuildVersion < $currentBuildVersion && $oldBuildVersion !== false) {

            if ($oldBuildVersion <= '6.00.32') {

                $table_name = $wpdb->prefix . "eventplusmeta";

                $sql = "CREATE TABLE `" . $table_name . "` ( `meta_id` BIGINT(11) NOT NULL AUTO_INCREMENT , `event_id` BIGINT(11) NOT NULL , `meta_key` VARCHAR(255) NOT NULL , `meta_value` LONGTEXT NOT NULL , PRIMARY KEY (`meta_id`), INDEX (`event_id`), INDEX (`meta_key`(191))) ENGINE = InnoDB;";

                require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

                if (dbDelta($sql)) {
                    //create option for table name
                    $option_name = 'evr_eventplusmeta';
                    $newvalue = $table_name;
                    update_option($option_name, $newvalue);

                    //create option for table version
                    $option_name = 'evr_eventplusmeta_version';
                    update_option($option_name, $currentBuildVersion);

                    $attendee_table_name = get_option('evr_attendee');
                    $wpdb->query('ALTER TABLE `' . $attendee_table_name . '` ADD `discount_percentage` DECIMAL(5,2) NOT NULL AFTER `token`, ADD `discount_amount` DECIMAL(10,2) NOT NULL AFTER `discount_percentage`;');
                    $wpdb->query('ALTER TABLE `' . $attendee_table_name . '` ADD `order_total` DECIMAL(10,2) NOT NULL AFTER `token`;');

                    update_option('evr_attendee_version', $currentBuildVersion);

                    EventPlus_Helpers_Funx::updateBuildVersion($currentBuildVersion);
                }
            }
        }
		$company_options = get_option('evr_company_settings');
		if(empty($company_options)){
			$company_options['company'] = "";
			$company_options['company_street1'] = "";
			$company_options['company_street2'] = "";
			$company_options['company_city'] = "";
			$company_options['company_state'] = "";
			$company_options['company_postal'] = "";
			$company_options['company_email'] = "";
			$company_options['secondary_email'] = "";
			$company_options['evrplus_page_id'] = "";
			$company_options['splash'] = "";
			$company_options['send_confirm'] = "";
			$company_options['message'] = "";
			$company_options['wait_message'] = "";
			$company_options['thumbnail'] = "";
			$company_options['calendar_url'] = "";          //$params['calendar_url';
			$company_options['default_currency'] = "";
			$company_options['donations'] = "";
			$company_options['checks'] = "";
			$company_options['pay_now'] = "";
			$company_options['payment_vendor'] = "";
			$company_options['secret_key'] = "";
			$company_options['publishable_key'] ="";
			$company_options['stripereturn_url'] = "";
			$company_options['payment_vendor_id'] ="";
			$company_options['payment_vendor_key'] = "";
			$company_options['use_authorize_sandbox'] = "";
			$company_options['authorize_id'] = "";
			$company_options['authorize_key'] = "";
			$company_options['pay_msg'] = "";
			$company_options['return_url'] = "";
			$company_options['notify_url'] = "";
			$company_options['cancel_return'] = "";
			$company_options['return_method'] = "";
			$company_options['use_sandbox'] = "";
			$company_options['paypal_pdt_token'] = "";
			$company_options['image_url'] = "";
			$company_options['admin_message'] = "";
			$company_options['pay_confirm'] = "";
			$company_options['payment_subj'] = "";
			$company_options['payment_message'] = "";
			$company_options['c_message'] = "";
			$company_options['info_recieved'] = "";
			$company_options['captcha'] = "";
			$company_options['captcha_key'] ="";
			$company_options['event_pop'] = "";
			$company_options['form_css'] = "";
			$start_of_week = "";
			$company_options['use_sales_tax'] = "";
			$company_options['googleMap_api_key'] = "";
			$company_options['sales_tax_rate'] = "";
			$company_options['start_of_week'] = "";
			$company_options['evrplus_date_select'] = "";
			$company_options['evrplus_tooltip_select'] = "";
			$company_options['evrplus_cal_head'] = "";
			$company_options['time_format'] = "";
			$company_options['date_format'] = "";
			$company_options['show_num_seats'] = "";
			$company_options['cal_head_txt_clr'] = "";
			$company_options['evrplus_cal_cur_day'] = "";
			$company_options['evrplus_cal_use_cat'] = "";
			$company_options['evrplus_flag_add_to_cal_button'] = "";
			$company_options['evrplus_cal_pop_border'] = "";
			$company_options['cal_day_txt_clr'] = "";
			$company_options['show_social_icons'] = "";
			$company_options['evrplus_cal_day_head'] = "";
			$company_options['cal_day_head_txt_clr'] = "";
			$company_options['evrplus_list_format'] = "";
			$company_options['admin_noti'] = "";
			$company_options['order_event_list'] = "";
			$company_options['evrplus_tooltip_show'] = "";
			$company_options['qty_discount'] = "";
			$company_options['qty_discount_settings'] = "";
			$company_options['after_pay_confirm'] = "";
			$company_options['after_payment_subj'] = "";
			$company_options['after_payment_message'] = "";
			$company_options['qty_discount_settings'] = "";
			$company_options['qty_discount_settings'] = "";
			$company_options['evrplus_invoice'] = "";
			update_option('evr_company_settings', $company_options);
			update_option('evr_start_of_week', "");
			$dwolla_enabled = "";
			update_option('evr_dwolla', $dwolla_enabled);
		}
    }

    function adminInit() {
        EventPlus::factory('Helpers_Assets_Admin')->init();
    }

    function frontInit() {
        EventPlus::factory('Helpers_Assets_Front')->init();
    }

    function doOputputBufer() {

        if (is_admin()) {
            $oPlugin = EventPlus::getPlugin();

            if (is_object($oPlugin)) {
                $slug = $oPlugin->getSlug();
                if (isset( $_GET['page'] ) && strstr($_GET['page'], $slug)) {
                    ob_start();
                }
            }
        }
    }

    function registerAdminMenu() {
        EventPlus::factory('Helpers_Admin_Menu')->register();
    }

    function dashboardWidget() {
        $oAdminDashboard = new EventPlus_Helpers_Admin_Dashboard();
        wp_add_dashboard_widget('dashboard_custom_feed', __('Events Plus Dashboard'), array($oAdminDashboard, 'handleEvents'));
    }

    function dataExport() {

        if (isset($_REQUEST['page'])) {
            if ($_REQUEST['page'] == 'eventplus_admin_attendees') {
                if (isset($_REQUEST['method'])) {
                    if ($_REQUEST['method'] == 'export') {

                        $event_id = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : 0;
                        $export_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'csv';

                        if (in_array($export_type, array('csv', 'xls')) == false) {
                            $export_type = 'csv';
                        }

                        if (is_numeric($event_id) && $event_id > 0) {
                            EventPlus::dispatch('admin_attendees_export', array(
                                'type' => $export_type,
                                'event_id' => $event_id,
                            ));
                        }
                    }
                }
            }

            if ($_REQUEST['page'] == 'eventplus_admin_payments') {
                if (isset($_REQUEST['method'])) {
                    if ($_REQUEST['method'] == 'export') {

                        $event_id = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : 0;
                        $export_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'csv';

                        if (in_array($export_type, array('csv', 'xls')) == false) {
                            $export_type = 'csv';
                        }

                        if (is_numeric($event_id) && $event_id > 0) {

                            EventPlus::dispatch('admin_payments_export', array(
                                'type' => $export_type,
                                'event_id' => $event_id,
                            ));
                            exit;
                        }
                    }
                }
            }
        }
    }

    function insert_footer_wpse_51023() {
        ?>
        <script type="text/javascript">
            function showDiv(elem) {
                if (elem.value == 'STRIPEACTIVE') {
                    document.getElementById('Divsecond').style.display = "block";
                    document.getElementById('authorizeShowhide').style.display = "none";
                    document.getElementById('Divfirst').style.display = "none";

                } else if (elem.value == 'PAYPAL') {
                    document.getElementById('Divsecond').style.display = "none";
                    document.getElementById('Divfirst').style.display = "block";
                    document.getElementById('authorizeShowhide').style.display = "none";
                } else if (elem.value == 'AUTHORIZE') {
                    document.getElementById('Divfirst').style.display = "none";
                    document.getElementById('Divsecond').style.display = "none";
                    document.getElementById('authorizeShowhide').style.display = "block";
                } else if (elem.value == 'NONE') {
                    document.getElementById('Divsecond').style.display = "none";
                    document.getElementById('authorizeShowhide').style.display = "none";
                    document.getElementById('Divfirst').style.display = "block";
                }
            }
        </script>
        <?php
    }

    function eventsplus_registration_setup_notice() {
        if (EventPlus_Helpers_Funx::isValidRegistrationPage() == false) {
            /*?>
            <div class="notice notice-error">
                <p><?php _e('Warning: {EVRREGIS} shortcode is missing. Please configure page and paste the shortcode. If you fail to add this shortcode non of your event links will work as <a href="http://wpeventsplus.com/documentation/knowledge-base/registration-page-shortcode/">explained in this article</a>.', 'evrplus_language'); ?></p>
            </div>
            <?php*/
        }
    }

}
