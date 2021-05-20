<?php

class EventPlus_Database {

    private $lastQ = null;

    public function __construct() {
        global $wpdb;

        $this->wpdb = $wpdb;
    }

    function getDb() {
        return $this->wpdb;
    }

    public function escape($unescaped_string) {
        return $this->wpdb->_escape($unescaped_string);
    }

    function query($sql) {
        if (is_object($this->wpdb)) {
            $this->lastQ = $this->wpdb->query($sql);

            if (!$this->lastQ && $this->wpdb->last_error != '') {
                $this->last_error = $this->wpdb->last_error;
                throw new Exception($this->last_error . ' - ' . $sql);
            }
            return $this->lastQ;
        } else {
            throw new Exception('WPDB Object not instantiated - ' . $sql);
        }
    }

    /**
     * 
     * @returns the QuickArray    
     */
    function QuickArray($sql) {
        return $this->wpdb->get_row($sql, ARRAY_A);
    }

    function getFoundRows() {
        $array = $this->QuickArray(
                "SELECT FOUND_ROWS() as totRows"
        );
        return $array['totRows'];
    }

    /**
     * Get the number of rows count
     * @return the number of rows    
     */
    function NumRows() {
        return $this->wpdb->num_rows;
    }

    function getInsertID() {
        return $this->wpdb->insert_id;
    }

    function getAffectedRows() {
        return $this->wpdb->rows_affected;
    }

    public function close() {
        
    }

    function get_results($sql, $type = ARRAY_A) {
        return $this->wpdb->get_results($sql, $type);
    }

    function getVar($q) {
        return $this->wpdb->get_var($q);
    }

    function dataset($sql) {
        return $this->wpdb->get_results($sql, ARRAY_A);
    }

}
