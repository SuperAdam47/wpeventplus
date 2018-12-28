<?php

class EventPlus_Models_Events_Expired extends EventPlus_Models_Events {

    
    function getExpiredEventsBySettings($params = array()) {
        $company_options = EventPlus_Models_Settings::getSettings();

        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE str_to_date(end_date, '%Y-%m-%e') < curdate()";
        
        if($params['event_category_id'] > 0){
            $sql .= " AND category_id LIKE '%\"" . esc_sql($params['event_category_id']) . "\"%' ";
        }
       
        
        # Get events that end date is later than today and order by start date
        if ($company_options['order_event_list'] == 'DESC') {
            $sql .= " ORDER BY str_to_date(start_date, '%Y-%m-%e') DESC, start_time DESC";
        } else {
            $sql .= " ORDER BY str_to_date(start_date, '%Y-%m-%e') ASC, start_time ASC";
        }
        
       
        if(isset($params['limit'])){
            if($params['limit'] > 0){
                $sql .= " LIMIT " . (int)$params['limit'];
            }
        }
        
        return $this->getWpDb()->get_results($sql);
    }

}
