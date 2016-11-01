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
                
                $wpdb->query('ALTER TABLE '. get_option('evr_payment').' CHANGE `txn_id` `txn_id` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;');
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
                if (strstr($_GET['page'], $slug)) {
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

}
