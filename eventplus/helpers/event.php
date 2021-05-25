<?php

class EventPlus_Helpers_Event {

    public static $future_days = "60";

    static function permalink($page_id) {

        if( $page_id == get_option('show_on_front') ) {
            $p_link = get_bloginfo('url');

            if( $p_link[strlen($p_link) - 1] != '/' ) {
                $p_link = $p_link . '/';
            }
        } else {
            $p_link = get_permalink($page_id);
        }

        if (!(strstr($p_link, '?'))) {
            $link_part = $p_link . '?';
        } else {
            $link_part = $p_link . '&';
        }

        return $link_part;
    }

    static function check_recurrence($id) {
        global $wpdb;
        $isRecurr = $wpdb->get_var("SELECT recurrence_choice FROM " . get_option('evr_event') . " WHERE id=" . (int) $id);
        $curr = null;
        if ($isRecurr == "yes") {
            $row = $wpdb->get_var("SELECT start_date FROM " . get_option('evr_event') . " WHERE id=" . (int) $id);
            if ($row) {
                $time = strtotime($row);
                $current = time();
                if ($time > $current)
                    $curr = $time;
                else {
                    $period = $wpdb->get_var("SELECT recurrence_period FROM " . get_option('evr_event') . " WHERE id=" . (int) $id);
                    switch ($period) {
                        case 'weekly':
                            while (true) {
                                $time += 60 * 60 * 24 * 7;
                                if ($time > $current) {
                                    $curr = $time;
                                    break;
                                }
                            }
                            break;
                        case 'monthly':
                            while (true) {
                                $time += 60 * 60 * 24 * 30;
                                if ($time > $current) {
                                    $curr = $time;
                                    break;
                                }
                            }
                            break;
                        case 'yearly':
                            while (true) {
                                $time += 60 * 60 * 24 * 365;
                                if ($time > $current) {
                                    $curr = $time;
                                    break;
                                }
                            }
                            break;
                        default: break;
                    }
                }
            }
        }
        return $curr;
    }

    static function get_category_identifier_by_id($id) {
        global $wpdb;
        $sql2 = "SELECT category_identifier FROM " . get_option('evr_category') . " WHERE id=" . (int) $id;
        $cat = $wpdb->get_var($sql2);
        return $cat;
    }

    static function upcoming_events() {

        $day_count = 1;
        while ($day_count < self::$future_days + 1) {

            $timeOffset = evrplus_time_offset();
            list($y, $m, $d) = split("-", date("Y-m-d", mktime($day_count * 24, 0, 0, date("m", $timeOffset), date("d", $timeOffset), date("Y", $timeOffset))));

            $events = self::fetch_events($y, $m, $d);
            usort($events, "evrplus_evrplus_time_cmp");

            if (count($events) > 0) {
                $output .= '<li>' . date_i18n(get_option('date_format'), mktime($day_count * 24, 0, 0, date("m", $timeOffset), date("d", $timeOffset), date("Y", $timeOffset)));
                foreach ($events as $event) {
                    if ($event->event_time == '00:00:00') {
                        $time_string = ' ' . __('all day', 'evrplus_language');
                    } else {
                        $time_string = ' ' . __('Between', 'evrplus_language') . ' ' . date(get_option('time_format'), strtotime(stripslashes($event->start_time))) . ' - ' . date(get_option('time_format'), strtotime(stripslashes($event->end_time)));
                    }
                    $output .= '<ul>'
                            . '<li>' . strip_tags($event->event_name) . ' (' . $time_string . ')<br />' . strip_tags($event->event_desc) . '</li>';
                    $output .= '</ul>';
                }
                $output .= '</li>';
            }
            $day_count = $day_count + 1;
        }

        if ($output == '') {
            $output .= '<li>' . __('No event till now!', 'evrplus_language') . '</li>';
        }

        $visual = '<ul>';
        $visual .= $output;
        $visual .= '</ul>';

        return $visual;
    }

    function fetch_events($y, $m, $d, $cat = null) {

        $date = $y . '-' . $m . '-' . $d;

        $oEvent = new EventPlus_Models_Events();
        $events = $oEvent->fetchEventsByDate($date);

        foreach ($events as $event) {
            if ($event->recurrence_choice == "yes") {
                $event->end_date = $event->start_date;
            }
        }

        $arr_events = array();

        if (!empty($events)) {
            foreach ($events as $event) {

                if ($event->recurrence_choice == 'no') {
                    array_push($arr_events, $event);
                } else {
                    if (evrplus_calculate_recurring_dates($event, $date))
                        array_push($arr_events, $event);
                }
            }
        }

        return $arr_events;
    }

    static function eventplus_getDiscountPercentage($quantity, $qty_discount_settings) {
        $discountPercentage = 0;
        if ($quantity > 0 && is_array($qty_discount_settings) && count($qty_discount_settings) > 0) {

            krsort($qty_discount_settings);

            foreach ($qty_discount_settings as $qtyDiscount => $percentage) {
                if ($quantity > $qtyDiscount && $percentage > 0 && $percentage <= 100) {
                    $discountPercentage = $percentage;
                    break;
                }
            }
        }

        return $discountPercentage;
    }

    static function getPercentageDataset($qty_discount_settings) {
        $discountDataset = array();
        if (is_array($qty_discount_settings) && count($qty_discount_settings) > 0) {

            asort($qty_discount_settings);

            foreach ($qty_discount_settings as $qtyDiscount => $percentage) {
                $discountDataset[$qtyDiscount] = $percentage;
            }
        }

        return $discountDataset;
    }

    static function comboDataset($param = array()) {
        $oEvents = new EventPlus_Models_Events();
        return $oEvents->getComboDataset();
    }

    static function getThumbnailAttachment($raw_source_url) {

        $attachment_id = EventPlus_Helpers_Funx::getAttachmentId($raw_source_url);

        if ($attachment_id > 0) {
            $medium_array = image_downsize($attachment_id, 'thumbnail');
            $medium_path = $medium_array[0];
            return $medium_path;
        } else {
            return $raw_source_url;
        }
    }

}
