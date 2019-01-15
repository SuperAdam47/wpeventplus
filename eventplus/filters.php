<?php

class EventPlus_Filters {

    private $autopActive = true;

    function remove_wpautop($content) {

        if (!has_filter('the_content', 'wpautop')) {
            $this->autopActive = false;
        }

        if( $this->removeAutoPCheck($content) ) {
            remove_filter('the_content', 'wpautop');
        }

        return $content;
    }

    function do_wpautop($content) {
         if ($this->autopActive) {
            add_filter('the_content', 'wpautop');
        }
        return $content;
    }

    private function removeAutoPCheck($content) {
        if (has_shortcode($content, 'eventsplus_grid')) {
            return true;
        }

        if (preg_match('{EVR_UPCOMING}', $content)) {
            return true;
        }

        if (preg_match('{EVRREGIS}', $content)) {
            return true;
        }

        if (preg_match('[PLUS_CALENDAR:((.*))\w+]', $content, $matches)) {
            return true;
        } elseif (preg_match('[PLUS_CALENDAR]', $content)) {
            return true;
        }elseif (preg_match('[eventsplus_calendar]', $content)) {
            return true;
        }else if( has_shortcode( $content, 'eventsplus_calendar' ) ) {
             return true;
        }

        return false;
    }

    function grid_the_content_filter($content) {

        $file = EventPlus::getPlugin()->getFile();

        if (has_shortcode($content, 'eventsplus_grid')) {
            wp_register_style('mediaBoxes', plugins_url('/assets/scripts/gridview/css/mediaBoxes.css', $file), array(), '1.0.0', 'all');
            wp_enqueue_style('mediaBoxes');
            wp_register_style('magnific-popup', plugins_url('/assets/scripts/gridview/css/magnific-popup.css', $file), array(), '1.0.0', 'all');
            wp_enqueue_style('magnific-popup');
        }

        return $content;
    }

    function upcoming_event_list($content) {

        $display = "true";
        if (preg_match('{EVR_UPCOMING}', $content)) {
            if ($display == 'true') {
                $cal_output = '<span class="page-upcoming-events"><strong>Upcoming Events:</strong><br />' . EventPlus_Helpers_Event::upcoming_events() . '</span>';
                $content = str_replace('{EVR_UPCOMING}', $cal_output, $content);
            } else {

                $content = str_replace('{EVR_UPCOMING}', '', $content);
            }
        }
        return $content;
    }

    function evrplus_content_replace($content) {

        if (preg_match('{EVRREGIS}', $content)) {
            $buffer = EventPlus::dispatch('front_event_registration/index', array());
            $content = str_replace('{EVRREGIS}', $buffer, $content);
        }

        return $content;
    }

    function evrplus_calendar_replace($content) {

        $pieces = explode("]", $content);
        foreach ($pieces as $val) {
           
            if (preg_match('[PLUS_CALENDAR:((.*))\w+]', $content, $matches)) {

                $evr = $matches[0];
                $pos = strpos($evr, ':');
                $cat = trim(substr($evr, $pos + 1));
                ob_start();

                evrplus_display_calendar($cat); //function with main content
                $buffer = ob_get_contents();
                ob_end_clean();
                $content = str_replace('[PLUS_CALENDAR:' . $cat . ']', $buffer, $content);
            } elseif (preg_match('[PLUS_CALENDAR]', $content)) {
                ob_start();
                evrplus_display_calendar(); //function with main content
                $buffer = ob_get_contents();
                ob_end_clean();
                $content = str_replace('[PLUS_CALENDAR]', $buffer, $content);
            }
        }
        return $content;
    }

    function wpa3396_page_template($page_template) {
        if (is_page('thank-you-page')) {
            $page_template = EVENT_PLUS_PLUGIN_FRAMEWORK_PATH . 'page_templates/thankyou.php';
        }
        return $page_template;
    }

}
