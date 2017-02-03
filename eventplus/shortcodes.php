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

        extract(shortcode_atts(array(
            'columns' => '4',
            'ordered' => 'yes',
            'init_events' => '8',
            'load_new_events' => '5'
                        ), $atts));



        $col = ($columns == 2) ? 2 : 4 - ($columns - 1);
        
        return EventPlus::dispatch('front_shortcode_event_grid/index', array(
                    'col' => $col,
                    'columns' => $columns,
                    'ordered' => $ordered,
                    'init_events' => $init_events,
                    'load_new_events' => $load_new_events,
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

        extract(shortcode_atts(array('event_category_id' => 'No Category ID Supplied'), $atts));
        $event_category_id = "{$event_category_id}";

        return EventPlus::dispatch('front_shortcode_event_category/index', array(
                    'event_category_id' => $event_category_id,
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
            'limit' => 0
                        ), $atts));
        
        return EventPlus::dispatch('front_shortcode_event_list/index', array(
            'shortcode_attributes' => $attributes
        ));
    }

}
