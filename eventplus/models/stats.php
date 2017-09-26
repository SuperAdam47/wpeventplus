<?php

class EventPlus_Models_Stats extends EventPlus_Abstract_Model {

    function get_event_total_payment() {

        $sql = "SELECT sum(mc_gross) as totGross FROM " . get_option('evr_payment') . " ORDER BY id DESC LIMIT 5";
        $row = $this->QuickArray($sql);
        
        $sum = 0;
        if ($row['totGross'] != null) {
            $sum = (int)$row['totGross'];
        }

        return $sum;
    }

    function get_total_events() {

        $sql = "SELECT count(id) as totCount FROM " . get_option('evr_event') . " ORDER BY id DESC LIMIT 5";

        $row = $this->QuickArray($sql);
        
        $count = 0;
        if ($row['totCount'] > 0) {
            $count = $row['totCount'];
        }

        return $count;
    }

    function get_total_event_category() {

        $sql = "SELECT count(id) as totCount FROM " . get_option('evr_category') . " ORDER BY id DESC LIMIT 5";
        $row = $this->QuickArray($sql);

        $count = 0;
        if ($row['totCount'] > 0) {
            $count = $row['totCount'];
        }

        return $count;
    }

    function get_total_attendee() {

        $sql = "SELECT count(id) as totCount FROM " . get_option('evr_attendee') . " ORDER BY id DESC LIMIT 5";
        $row = $this->QuickArray($sql);
        
        $count = 0;
        if ($row['totCount'] > 0) {
            $count = $row['totCount'];
        }
        
        return $count;
    }

}
