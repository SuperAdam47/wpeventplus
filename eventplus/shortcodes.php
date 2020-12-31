<?php

class EventPlus_ShortCodes {

    function attendeeDetails($atts) {
        extract(shortcode_atts(array('event_id' => 'No ID Supplied', 'custom' => '1', 'template' => ''), $atts));

        $id = "{$event_id}";
        $custom = "{$custom}";
        $template = "{$template}";

        return EventPlus::dispatch('front_shortcode_attendees_list/index', array(
            'atts' => $atts,
            'event_id' => $id,
            'custom' => $custom,
            'template' => $template,
        ));
    }

    function eventGrid($atts) {

        $file = EventPlus::getPlugin()->getFile();
        wp_register_style('mediaBoxes', plugins_url('/assets/scripts/gridview/css/mediaBoxes.css', $file), array(), '1.0.0', 'all');
        wp_enqueue_style('mediaBoxes');
        wp_register_style('magnific-popup', plugins_url('/assets/scripts/gridview/css/magnific-popup.css', $file), array(), '1.0.0', 'all');
        wp_enqueue_style('magnific-popup');

        extract(shortcode_atts(array(
            'show_excerpt' => 'yes',
            'character_limit' => '110',
            'columns' => '4',
            'ordered' => 'yes',
            'init_events' => '8',
            'load_new_events' => '5',
            'category_id' => ''
        ), $atts));

        $col = ($columns == 2) ? 2 : 4 - ($columns - 1);

        if( $show_excerpt == '1' ) {
            $show_excerpt = 'yes';
        }

        $show_excerpt = strtolower($show_excerpt);

        return EventPlus::dispatch('front_shortcode_event_grid/index', array(
            'col' => $col,
            'columns' => $columns,
            'ordered' => $ordered,
            'init_events' => $init_events,
            'load_new_events' => $load_new_events,
            'show_excerpt' => $show_excerpt,
            'character_limit' => $character_limit,
            'category_id' => $category_id,
        ));
    }

    function paymentPage($atts) {

        echo EventPlus::dispatch('front_shortcode_payment/index', array(
            '$atts' => $atts
        ));
    }

    function attendeeShort($atts) {

        extract(shortcode_atts(array('event_id' => 0), $atts));

        return EventPlus::dispatch('front_shortcode_attendees_short/index', array(
                    'event_id' => $event_id,
        ));
    }

    function byCategory($atts, $content = null) {

        extract(shortcode_atts(array('event_category_id' => 'No Category ID Supplied', 'limit' => 0, 'order_by' => 'asc'), $atts));
        $event_category_id = "{$event_category_id}";
        $limit = "{$limit}";

        return EventPlus::dispatch('front_shortcode_event_category/index', array(
                    'event_category_id' => $event_category_id,
                    'limit' => $limit,
                    'order_by' => $order_by,
        ));
    }

    function singleEvent($atts) {
        extract(shortcode_atts(array('event_id' => 'No ID Supplied'), $atts));
        $id = "{$event_id}";
        $curr = EventPlus_Helpers_Event::check_recurrence($id);
        $buffer = EventPlus::dispatch('front_event_parts_regform/index', array(
                    'event_id' => $id,
                    'recurr' => $curr,
        ));
        return $buffer;
    }

    function eventList($atts) {

        $attributes = (shortcode_atts(array(
                    'limit' => 0,
                    'event_category_id' => 0,
                    'show_expire' => 'no',
                        ), $atts));

        return EventPlus::dispatch('front_shortcode_event_list/index', array(
                    'shortcode_attributes' => $attributes
        ));
    }

    function eventExpiredList($atts) {

        $attributes = (shortcode_atts(array(
            'limit' => 0,
            'event_category_id' => 0,
        ), $atts));

        return EventPlus::dispatch('front_shortcode_event_list_expired/index', array(
            'shortcode_attributes' => $attributes
        ));
    }

    function eventRegistration($atts) {
        return EventPlus::dispatch('front_event_registration/index', array());
    }

    function eventCalendar($atts) {
        $shortcode_params = (shortcode_atts(array(
                    'category' => '',
                        ), $atts));

        $category = '';

        $event_category_id = 0;
        if (isset($shortcode_params['category'])) {
            if (trim($shortcode_params['category']) != '') {
                $category = trim($shortcode_params['category']);
            }
        }

        ob_start();
        evrplus_display_calendar($category); //function with main content
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }
}