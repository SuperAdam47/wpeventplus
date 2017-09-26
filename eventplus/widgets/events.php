<?php
/* * **********************************************************
 * Custom Widget Class for Events Plus *
 * ********************************************************** */

class EventPlus_Widgets_Events extends WP_Widget {

    function __construct() {
        $widget_opts = array(
            'classname' => 'evrplus-widget-list-events',
            'description' => 'Creates a list of most recent events from the Event Registration Plugin to display in the sidebar.  List can use default template or you can create custom display templates.'
        );
        parent::__construct('evrplus-widget-list-events', 'Event Registration Upcoming Events', $widget_opts);
    }

    // Widget output to the User
    function widget($args, $instance) {
        
         
        extract($args, EXTR_SKIP);
        $title = apply_filters('widget_title', $instance['title']);
        $record_limit = isset($instance['event_limit']) ? strip_tags($instance['event_limit']) : '5'; // Defaults to 5 
        $event_desc_count = isset($instance['event_desc_count']) ? strip_tags($instance['event_desc_count']) : '50'; // Defaults to 5
        $record_category = isset($instance['event_category_id']) ? strip_tags($instance['event_category_id']) : '0'; // Defaults to 0 (All)
        $event_template = isset($instance['event_template']) ? stripslashes($instance['event_template']) : '';
    
        echo EventPlus::dispatch('front_widgets_events/index',array(
            'args' => $args,
            'oWidget' => $instance,
            'before_widget' => $before_widget,
            'before_title' => $before_title,
            'after_title' => $after_title,
            'title' => $title,
            'record_limit' => $record_limit,
            'event_desc_count' => $event_desc_count,
            'record_category' => $record_category,
            'event_template' => $event_template,
            'after_widget' => $after_widget,
        ));
    }

    function update($new_instance, $old_instance) { // Save widget options
        $instance = $old_instance;
        $instance['event_template'] = addslashes($new_instance['event_template']);
        $instance['event_limit'] = strip_tags($new_instance['event_limit']);
        $instance['event_desc_count'] = strip_tags($new_instance['event_desc_count']);
        $instance['event_category_id'] = strip_tags($new_instance['event_category_id']);
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    // Forms
    function form($instance) { // Output admin widget options form
        
        $wpdb = EventPlus::getRegistry()->db->getDb();
         
        $instance = wp_parse_args((array) $instance, array('event_limit' => ''));
        $title = isset($instance['title']) ? strip_tags($instance['title']) : '';
        $event_limit = isset($instance['event_limit']) ? strip_tags($instance['event_limit']) : '';
        $event_desc_count = isset($instance['event_desc_count']) ? strip_tags($instance['event_desc_count']) : '50';
        $event_category_id = isset($instance['event_category_id']) ? strip_tags($instance['event_category_id']) : '';
        $event_template = isset($instance['event_template']) ? stripslashes($instance['event_template']) : '';
       
        if (intval($event_limit) > 20)
            $event_limit = '20';
        
        // Load Categories from DB
        $table_name = $wpdb->prefix . 'evr_category';
        $events = $wpdb->get_results("SELECT id, category_name FROM $table_name", ARRAY_A);
        
        echo '<p>';
            echo '<label for="' . $this->get_field_id('title') . '">Title: </label>';
            echo '<input type="text" value="' . $title . '" name="' . $this->get_field_name('title') . '" id="' . $this->get_field_id('title') . '" class="widefat">';
        echo '</p>';
        
        echo '<p>';
            echo '<label for="' . $this->get_field_id('event_limit') . '" title="Max 20">Number of events to show: </label>';
            echo '<input type="text" value="' . $event_limit . '" name="' . $this->get_field_name('event_limit') . '" id="' . $this->get_field_id('event_limit') . '" size="3">';
        echo '</p>';
        
        echo '<p>';
            echo '<label for="' . $this->get_field_id('event_desc_count') . '">Event description length: </label><br/>';
            echo '<input type="number" value="' . $event_desc_count . '" name="' . $this->get_field_name('event_desc_count') . '" id="' . $this->get_field_id('event_desc_count') . '" size="3">';
        echo '</p>';
        
        echo '<p>';
        echo '<label for="' . $this->get_field_id('event_category_id') . '">Select a Category to display: </label>';
            echo '<select name="' . $this->get_field_name('event_category_id') . '" id="' . $this->get_field_id('event_category_id') . '">';
                echo '<option value="0">All Events </option>';

                foreach ($events as $event) {
                    $selected = $event_category_id == $event['id'] ? 'selected="selected"' : "";
                    echo "<option value=" . $event['id'] . " $selected>" . $event['category_name'] . ' (' . $event['id'] . ')' . "</option>";
                }
            echo '</select>';
        echo '</p>';
        
        echo '<p>';
            echo '<label for="' . $this->get_field_id('event_template') . '">(Optional) Enter a custom template: </label>';
            echo '<p><a class="ev_widget-fancylink" href="#evrplus_widget_help">Directions</a> | <a class="ev_widget-fancylink" href="#evrplus_widget_tags">Tags</a></p>';
            echo '<textarea class="widefat" rows="20" cols="20" id="' . $this->get_field_id('event_template') . '" name="' . $this->get_field_name('event_template') . '">' . $event_template . '</textarea>';
        echo '</p>';
        ?>
        <div style="display:none;">
            <div id="evrplus_widget_help" style="width:500px;height:500px;overflow:auto;">
                <h2>Customize Sidebar Widget</h2><p><strong>Custom Display for widget</strong><br>
                    By default no information is required in the customize box, as the widget has a default format. However if you would like to customize what information is displayed in the sidebar in relation to events you can 
                    create the layout yourself.  The layout should be in html format, and simply use the below listed tags to call the specific data.  Note: Only do the layout for one event, as each event will repeat the 
                    format automatically.
                    <br />
                    Example:
                <pre>
&#60;div id="evrplus_eventitem"&#62;
&nbsp;&#60;div id="datebg"&#62;
&nbsp;&nbsp;&#60;div id="topdate">{EVENT_MONTH_START_NAME_3}&#60;/div&#62;
&nbsp;&nbsp;&#60;div id="bottomdate"&#62;{EVENT_DAY_START_NUMBER}&#60;/div&#62;
&nbsp;&#60;/div&#62;
&nbsp;&#60;div id="evrplus_eventitem_title"&#62;
&nbsp;&nbsp;&#60;a href="{EVENT_URL}"&#62;{EVENT_NAME}&#60;/a&#62;&#60;/div&#62;
&#60;/div&#62;
&#60;hr/&#62;
                </pre>
            </div>
        </div> 
        <div style="display:none;">
            <div id="evrplus_widget_tags" style="width:500px;height:500px;overflow:auto;">
                <p><strong>Tags for EVR widget</strong><br />
                    <br />{EVENT_URL} - Direct link to Event
                    <br />{EVENT_NAME} - Name of Event
                    <br />{EVENT_DESC} - Description of Event
                    <br />{EVENT_LOC} - location of event
                    <br />{EVENT_ADDRESS} - address of event
                    <br />{EVENT_CITY} - city of event
                    <br />{EVENT_STATE} - state of event
                    <br />{EVENT_POSTAL} - postal code of event
                    <br />{EVENT_MONTH_START_NUMBER} - Start month digit
                    <br />{EVENT_MONTH_START_NAME} - Start month full name
                    <br />{EVENT_MONTH_START_NAME_3} - Start month abbreviated name
                    <br />{EVENT_DAY_START_NUMBER} - start day digit
                    <br />{EVENT_DAY_START_NAME} -  start day full name
                    <br />{EVENT_DAY_START_NAME_3} - start day abbreviated name
                    <br />{EVENT_YEAR_START} - start year (4 digit)
                    <br />{EVENT_TIME_START} - start time
                    <br />{EVENT_DATE_START} - full start date of event
                    <br />{EVENT_MONTH_END_NUMBER} - End month number
                    <br />{EVENT_MONTH_START_NAME} - End month full name
                    <br />{EVENT_MONTH_END_NAME_3} - End month abbreviated name
                    <br />{EVENT_DAY_END_NUMBER} - End day number
                    <br />{EVENT_DAY_END_NAME} - End day full name
                    <br />{EVENT_DAY_END_NAME_3} - End day abbreviated name
                    <br />{EVENT_YEAR_END} - end year (4 digit)
                    <br />{EVENT_DATE_END} - full end date of event
                    <br />{EVENT_TIME_END} - event end time
            </div>
        </div> 
        <?php
    }
}