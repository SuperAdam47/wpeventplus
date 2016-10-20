<?php

class eplus_front_widgets_events_controller extends EventPlus_Abstract_Controller {

    function index() {
        
        $record_limit = $this->_invokeArgs['record_limit']; // Defaults to 5 
        $event_desc_count = $this->_invokeArgs['event_desc_count']; // Defaults to 5
        $record_category = $this->_invokeArgs['record_category']; // Defaults to 0 (All)
        $event_template = $this->_invokeArgs['event_template'];

        $events_list = $this->makeEventsList($record_limit,$event_desc_count, $record_category, $event_template);
        
        $viewParams = $this->_invokeArgs;
        $viewParams['events_list'] = $events_list;
        
        $output = $this->oView->View('front/widgets/events',$viewParams);  
        
        $this->setResponse($output);
    }

    protected function makeEventsList($record_limit = '5',$event_desc_count, $record_category = '0', $record_template = '') {
        
        $wpdb = EventPlus::getRegistry()->db->getDb();
        
        $curdate = date("Y-m-d");
        $company_options = EventPlus_Models_Settings::getSettings();
        $category_query = '';
        
        if (intval($record_limit) > 20)
            $record_limit = 20;
        
        var_dump($record_category);
        
        if ($record_category != '0' && $record_category > 0)
            $category_query = " AND category_id LIKE '%:\"$record_category\"%' ";
        
        $orderby = $company_options['order_event_list'];
        
        $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() $category_query ORDER BY str_to_date(start_date, '%Y-%m-%e') " . $orderby . " LIMIT 0," . $record_limit;
        $rows = $wpdb->get_results($sql);
     
        if ($rows) {
            $count = 1;
            foreach ($rows as $event) {
                if ($record_template == '') {
                    $codeToReturn .= '
                        <li style="list-style: none;  border-top: 1px solid #cdcdcd;padding: 0 5px; margin-left: 0;padding-bottom: 23px; background-color: ' . ( ($count % 2 == 0) ? '#F5F5F5' : '#FFF' ) . ';">
                             <div style="clear:both; width: 100%;">
                                <div style="width: 30%; float: left; ">
                                    <div class="timing">
                                        <div class="time-cont">
                                            <div class="eve-start">
                                                <time datetime="2014-09-20" class="icon">
                                                    <em>{EVENT_DAY_START_NAME}</em>
                                                    <strong>{EVENT_MONTH_START_NAME_3}</strong>
                                                    <span>{EVENT_DAY_START_NUMBER}</span>
                                                </time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="width: 60%;display: inline-block;margin-top: 0;margin-left: 5%;">
                                    <h3><a style="color:#666; font-size: 15px; margin-bottom: 0; text-decoration: none;" href="{EVENT_URL}">{EVENT_NAME}</a></h3>
                                    <p style="color:#999; font-size: 12px;  line-height: 15px; margin-top: 0; font-size: 12px;">{EVENT_DESC}</p>
                                </div>
                            </div>
                        </li>';
                } else {
                    $codeToReturn .= $record_template;
                }
                
          
                $post_id = (int)$company_options['evrplus_page_id'];
                $permaLink = get_permalink(get_page_by_path('evrplus_registration'));
                if($post_id > 0){
                    $permaLink = get_permalink($post_id);
                }
                
                $event_url = add_query_arg(array('action' => 'evrplusegister', 'event_id' => $event->id), $permaLink);
           
                        
                $event_name = stripslashes($event->event_name);
                $event_desc = stripslashes($event->event_desc);
                $codeToReturn = str_replace("\r\n", '', $codeToReturn);
                $codeToReturn = str_replace("{EVENT_URL}", $event_url, $codeToReturn);
                
                $codeToReturn = str_replace("{EVENT_ID}", $event->id, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_NAME}", evrplus_truncateWords(stripslashes($event->event_name), 8, ""), $codeToReturn);
                $desc = stripslashes($event->event_desc);
                
                if (strlen($desc) > $event_desc_count) {
                    $desc = substr($desc, 0, $event_desc_count) . '...';
                }
                
                $codeToReturn = str_replace("{EVENT_DESC}", html_entity_decode($desc), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_LOC}", stripslashes($event->event_location), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_ADDRESS}", stripslashes($event->event_address), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_CITY}", stripslashes($event->event_city), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_STATE}", stripslashes($event->event_state), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_POSTAL}", stripslashes($event->event_postal), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_START_NUMBER}", $event->start_month, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_START_NAME}", date("F", strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_START_NAME_3}", date("M", strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_START_NUMBER}", $event->start_day, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_START_NAME}", date("l", strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_START_NAME_3}", date("D", strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_YEAR_START}", $event->start_year, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_TIME_START}", $event->start_time, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DATE_START}", $event->start_date, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_END_NUMBER}", $event->end_month, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_START_NAME}", date("F", strtotime($event->end_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_MONTH_END_NAME_3}", date("M", strtotime($event->end_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_END_NUMBER}", $event->end_day, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_END_NAME}", date("l", strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DAY_END_NAME_3}", date("D", strtotime($event->start_date)), $codeToReturn);
                $codeToReturn = str_replace("{EVENT_YEAR_END}", $event->end_year, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DATE_END}", $event->end_date, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_TIME_END}", $event->end_time, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_AVAIL_SPOTS}", $event->reg_limit, $codeToReturn);
                $codeToReturn = str_replace("{EVENT_DESC1}", substr(stripslashes($event->event_desc), 0, 24), $codeToReturn);
                $codeToReturn .= evrplus_colorbox_cal_content($rows);
                $count++;
            }
        }
        
        return $codeToReturn;
    }

}
