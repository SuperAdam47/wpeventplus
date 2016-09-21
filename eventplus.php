<?php

/** Plugin Name: WP EventsPlus
 * Description: Events Plus allows you to easily create and manage your events. Allow visitors to register and pay online for events, manage attendees, discount coupons, export attendees list, and much more.
 * Version: 2.0.7
 * Author: wpeventsplus.com
 * Author URI: http://wpeventsplus.com/
 * License: GPL2
 * Text Domain: evrplus_language
 */
if (!defined('ABSPATH')) {
    exit; //block direct access
}

define('EVENT_PLUS_FRAMEWORK_NAMESPACE', 'eplus');
define('EVENT_PLUS_FRAMEWORK_FOLDER', 'eventplus');
define("EVR_PLUGINPATH", "/" . plugin_basename(dirname(__file__)) . "/");
define('EVENT_PLUS_PLUGIN_PATH', rtrim(plugin_dir_path(__FILE__), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
define('EVENT_PLUS_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__file__)) . "/");
define('EVENT_PLUS_PLUGIN_FRAMEWORK_PATH', EVENT_PLUS_PLUGIN_PATH . EVENT_PLUS_FRAMEWORK_FOLDER . DIRECTORY_SEPARATOR);

require_once EVENT_PLUS_PLUGIN_FRAMEWORK_PATH . 'base.php';

EventPlus::init();

class EventPlus_Plugin extends EventPlus_Abstract_Plugin {

    protected $_plugin_title = 'Events+';
    protected $_build_version = '6.00.32';
    protected $_plugin_version = '2.0.6';
    protected $_plugin_slug = 'eventplus';
    protected $oApp = null;

    function _init() {

        EventPlus::setPlugin($this);
        EventPlus_Cookie::$expiration = time() + 10 * 365 * 24 * 60 * 60; /* 10 years */


        if (is_admin() == false) {
            ob_start();
        }

        $this->add_action('plugins_loaded', $this, 'i8ln');

        $oEventPlusCore = EventPlus::factory('Core', array(
                    'mode' => 'development'
        ));

        $oRegistry = EventPlus::factory('Registry');
        $oRegistry->set('core', $oEventPlusCore);
        $oRegistry->set('db', EventPlus::factory('WordPress_Database'));
        $oRegistry->set('url', EventPlus::factory('Url', array(
                    'site_url' => EVENT_PLUS_SITE_URL,
                    'admin_url' => EVENT_PLUS_SITE_URL . 'wp-admin/admin.php',
                    'assets_url' => $this->getUrl() . 'assets/',
                    'menu_slug' => $this->getSlug(),
        )));

        $oFlashMessage = EventPlus::factory('Flash_Message');
        $oFlashMessage->setKey('eventplus_admin_flash_messages');
        $oRegistry->set('flash', $oFlashMessage);

        if (is_admin()) {
            add_action('admin_notices', array($oFlashMessage, 'render'));
        }

        EventPlus::set('registry', $oRegistry);

        $this->oApp = EventPlus::factory('Helpers_App');

        $this->addCommonActions();
        $this->registerShortcodes();
        $this->addFilters();
    }

    function i8ln() {
        load_plugin_textdomain('evrplus_language', false, dirname(plugin_basename($this->getFile())) . '/lang/');
    }

    private function addCommonActions() {
        $this->add_action('init', $this->oApp, 'eventPlusInit');
        $this->add_action('widgets_init', $this, 'registerWidgets');
    }

    private function addFilters() {
        $oFilters = new EventPlus_Filters();
        $this->add_filter('the_content', $oFilters, 'grid_the_content_filter');
        $this->add_filter('the_content', $oFilters, 'upcoming_event_list');
        $this->add_filter('the_content', $oFilters, 'remove_wpautop', 8);
        $this->add_filter('the_content', $oFilters, 'do_wpautop', 12);
        $this->add_filter('the_content', $oFilters, 'evrplus_content_replace', 9);
        $this->add_filter('the_content', $oFilters, 'evrplus_calendar_replace');
        $this->add_filter('page_template', $oFilters, 'wpa3396_page_template');
        $this->add_filter('the_content', $oFilters, 'do_wpautop', 12);

        add_filter('the_content', 'evrplus_mini_cal_calendar_replace');
    }

    private function registerShortcodes() {

        $oShortCodes = new EventPlus_ShortCodes();
        add_shortcode('EVR_CUSTOM_ATTENDEE', array($oShortCodes, 'attendeeDetails'));
        add_shortcode('eventsplus_grid', array($oShortCodes, 'eventGrid'));
        add_shortcode('eventsplus_payment', array($oShortCodes, 'paymentPage'));
        add_shortcode('eventsplus_attendee', array($oShortCodes, 'attendeeShort'));
        add_shortcode('eventsplus_category', array($oShortCodes, 'byCategory'));
        add_shortcode('eventsplus_single', array($oShortCodes, 'singleEvent'));
    }

    function initAdmin() {
        $this->add_action('init', $this->oApp, 'adminInit');
        $this->add_action('admin_init', $this->oApp, 'dataExport');
        $this->add_action('admin_menu', $this->oApp, 'registerAdminMenu');
        $this->add_action('wp_dashboard_setup', $this->oApp, 'dashboardWidget');
        $this->add_filter('plugin_action_links', $this, 'actionLinks', 10, 2);
        $this->add_action('admin_footer', $this->oApp, 'insert_footer_wpse_51023');
    }

    function initFront() {
        $this->add_filter('pre_get_document_title', $this, 'filterMetaTitle');
        $this->add_action('wp_head', $this, 'pluginInfo');
        $this->add_action('init', $this->oApp, 'frontInit');
        $this->add_action('template_redirect', $this, 'eventplus_confirmation_registration');
    }

    function filterMetaTitle() {
        global $post;

        if (is_admin()) {
            return;
        }

        if (isset($_GET['event_id'])) {

            if (is_object($post) && is_singular() && is_singular() && $post->ID == EventPlus_Models_Settings::getSettings('evrplus_page_id')) {
                $oEvent = new EventPlus_Models_Events();
                $eventRow = $oEvent->getRow((int) $_GET['event_id']);
                return $eventRow['event_name'];
            }
        }
    }

    function eventplus_confirmation_registration() {
        if (is_page() == false && isset($_GET['eventplus_token']) && isset($_GET['action']) && isset($_GET['event_id'])) {

            if (strtolower($_GET['action']) == 'confirmation') {

                $company_options = EventPlus_Models_Settings::getSettings();

                if ($company_options['evrplus_page_id'] > 0 && intval($_GET['event_id']) > 0 && strlen($_GET['eventplus_token']) == 32) {

                    $perma_link = get_permalink($company_options['evrplus_page_id']);
                    $payment_link = $perma_link . "?action=confirmation&eventplus_token=" . strip_tags($_GET['eventplus_token']) . "&event_id=" . (int) $_GET['event_id'];
                    wp_redirect($payment_link);
                    exit();
                }
            }
        }
    }

    function registerWidgets() {
        register_widget('EventPlus_Widgets_Events');
    }

    function pluginInfo() {
        echo '<!--WPEventPlus ' . $this->_plugin_version . '-->';
    }

    function actionLinks($links, $file) {

        $this_plugin = plugin_basename($this->_plugin_file);

        if ($file == $this_plugin) {
            $links = EventPlus::dispatch('admin_widgets/quick_links', array(
                        'links' => $links
            ));
        }

        return $links;
    }

    function activate() {
        require_once (EVENT_PLUS_PLUGIN_FRAMEWORK_PATH . "install.php");
        evrplus_install();
    }

    function deactivate() {
        
    }

}

new EventPlus_Plugin(__FILE__);
