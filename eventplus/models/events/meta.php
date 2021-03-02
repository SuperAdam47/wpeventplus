<?php

class EventPlus_Models_Events_Meta extends EventPlus_Abstract_Model {

    function __construct() {
        parent::__construct();
        $this->_table = get_option('evr_eventplusmeta');
    }

    function updateOption($event_id, $meta_key, $meta_value) {

        if (!$meta_key || !is_numeric($event_id)) {
            return false;
        }

        $event_id = absint($event_id);
        if (!$event_id) {
            return false;
        }

        $table = $this->_table;

        if (!$table) {
            return false;
        }

        // expected_slashed ($meta_key)
        $raw_meta_key = $meta_key;
        $meta_key = wp_unslash($meta_key);
        $meta_value = maybe_serialize(wp_unslash($meta_value));

        $wpdb = $this->wpDb();

        $sql = $wpdb->prepare("SELECT * FROM $table WHERE meta_key = %s AND event_id = %d", $meta_key, $event_id);
        $row = $this->QuickArray($sql);

        $sql = "INSERT INTO " . $table . " (`event_id`, `meta_key`, `meta_value`)" . " values('$event_id', '$meta_key', '" . $meta_value . "')";
        if ($row['meta_id'] > 0) {
            $sql = "UPDATE " . $table . " SET `meta_value` = '$meta_value' WHERE meta_key = '" . $meta_key . "' AND event_id = '" . $event_id . "'";
        }

        $q = $wpdb->query($sql);

        if ($q === false) {
            return false;
        } else {
            return true;
        }
    }

    function getOption($event_id, $meta_key) {

        $event_id = absint($event_id);
        if (!$event_id) {
            return false;
        }

        if (!$meta_key) {
            return false;
        }

        $sql = $this->wpDb()->prepare("SELECT * FROM $this->_table WHERE meta_key = %s AND event_id = %d", $meta_key, $event_id);
        $row = $this->QuickArray($sql);
		$meta_value = "";		
		if(!empty($row)){
			$meta_value = maybe_unserialize($row['meta_value']);
		}
		return $meta_value;
    }

    function getAllOptions($event_id) {

        $event_id = absint($event_id);
        if (!$event_id) {
            return false;
        }

        $sql = $this->wpDb()->prepare("SELECT * FROM $this->_table WHERE event_id = %d", $event_id);
        $ds = $this->Dataset($sql);

        $rs = array();

        if (count($ds) && is_array($ds)) {
            foreach ($ds as $k => $r) {
                $meta_value = maybe_unserialize($r['meta_value']);
                $rs[$r['meta_key']] = $meta_value;
            }
        }

        return $rs;
    }

}
