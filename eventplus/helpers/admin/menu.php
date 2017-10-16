<?php

class EventPlus_Helpers_Admin_Menu {

    protected $oUrl = '';

    function __construct() {
        $this->oUrl = EventPlus::getRegistry()->url;
    }

    function register() {

         $event_id = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : 0;
                       
         
        $oPlugin = EventPlus::getPlugin();

        $title = $oPlugin->getTitle();
        $parent_uri = $this->oUrl->prepareUri('admin');
        $settings_uri = $this->oUrl->prepareUri('admin_settings');
        $role = 'manage_options';


        add_menu_page($title, $title, $role, $parent_uri, array($this, 'handle'));
        add_submenu_page($parent_uri, 'Configure Plugin', __('General Settings', 'evrplus_language'), $role, $settings_uri, array($this, 'handle'));
        
        if(isset($_GET['page'])){
            if($_GET['page'] == 'eventplus_admin_settings'){
                $setting_tabs = EventPlus_Helpers_Admin_Menu::getSettingTabs();
                unset($setting_tabs['tab9']);
                foreach($setting_tabs as $tabKey => $tabValue){
                    add_submenu_page($parent_uri, $tabValue, ' &nbsp;&nbsp;' .$tabValue, $role, $this->oUrl->prepareUri('admin_settings&ct='.$tabKey), array($this, 'handle'));

                }
            }
        }
         
        add_submenu_page($parent_uri, 'Event Categories', __('Event Categories', 'evrplus_language'), $role, $this->oUrl->prepareUri('admin_categories'), array($this, 'handle'));
        add_submenu_page($parent_uri, 'Add Event', __('Add Event', 'evrplus_language'), $role, $this->oUrl->prepareUri('admin_events/add'), array($this, 'handle'));
        add_submenu_page($parent_uri, 'Manage Events', __('Manage Events', 'evrplus_language'), $role, $this->oUrl->prepareUri('admin_events'), array($this, 'handle'));
        
     
        add_submenu_page($parent_uri, 'Event Fees/Tickets', __('Event Fees/Tickets', 'evrplus_language'), $role, $this->oUrl->prepareUri('admin_events_items'), array($this, 'handle'));
        add_submenu_page($parent_uri, 'Event Questions', __('Event Questions', 'evrplus_language'), $role, $this->oUrl->prepareUri('admin_questions'), array($this, 'handle'));
        add_submenu_page($parent_uri, 'Event Attendees', __('Event Attendees', 'evrplus_language'), $role, $this->oUrl->prepareUri('admin_attendees'), array($this, 'handle'));
        add_submenu_page($parent_uri, 'Manage Payments', __('Payments', 'evrplus_language'), $role, $this->oUrl->prepareUri('admin_payments'), array($this, 'handle'));
  }

    function handle() {

        $oRegistry = EventPlus::getRegistry();
        $core_uri = $oRegistry->url->assembleAdminMenuUri();
        $formatted_uri = $this->oUrl->formatUri($core_uri);

        try {
            echo EventPlus::dispatch($formatted_uri);
        } catch (Exception $ex) {
            echo wp_die($ex->getMessage());
            exit;
        }
    }
    
    static function getSettingTabs(){
        return array(
            'tab1_contact' => __('Contact', 'evrplus_language'),
            'tab2_payment' => __('Payment', 'evrplus_language'),
            'tab3_captcha' => __('Captcha', 'evrplus_language'),
            'tab4_page_config' => __('Page Config', 'evrplus_language'),
            'tab5_confirmation' => __('Confirmation', 'evrplus_language'),
            'tab6_waitlist' => __('Waitlist', 'evrplus_language'),
            'tab7_calendar' => __('Calendar', 'evrplus_language'),
            'tab8_tax' => __('Tax', 'evrplus_language'),
            'tabdiscount' => __('Bulk Discounts', 'evrplus_language'),
            'tab9_done' => __('Done', 'evrplus_language'),
        );
    }

}
