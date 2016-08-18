<?php
$wpdb = $this->wpDb();
?>
<style>
    #eventsnav {
        position:relative;
        float:left;
        width:100%;
        padding:0 0 1.75em 0em;
        margin-top:5px;
        list-style:none;
        line-height:1em;
    }
    #eventsnav li {
        float:left;
        margin:0;
        padding:0;
    }
    #eventsnav a {
        display:block;
        color:#444;
        text-decoration:none;
        font-weight:bold;
        background:#ddd;
        margin:0;
        padding:0.25em 1em;
        border-left:1px solid #fff;
        border-top:1px solid #fff;
        border-right:1px solid #aaa;
    }
    #eventsnav a:hover,
    #eventsnav a:active,
    #eventsnav a.here:link,
    #eventsnav a.here:visited {
        background:#bbb;
    }
    #eventsnav a.here:link,
    #eventsnav a.here:visited {
        position:relative;
        z-index:102;
    }
</style>

<form name="form" method="post" action="<?php echo $this->adminUrl('admin_events/add_redirect'); ?>">
    <input class="evrplus_button evrplus_add" type="submit" name="new" value="<?php _e('Add New Event', 'evrplus_language'); ?>" />
</form>    

<ul id="eventsnav">
    <li><a href="<?php echo $this->adminUrl('admin_settings'); ?>"><?php _e('General Settings', 'evrplus_language'); ?></a></li>
    <li><a href="<?php echo $this->adminUrl('admin_categories'); ?>"><?php _e('Event Categories', 'evrplus_language'); ?></a></li>
    <li><a href="<?php echo $this->adminUrl('admin_events'); ?>"><?php _e('View Events', 'evrplus_language'); ?></a></li>
</ul>

<table class="wp-list-table widefat fixed striped posts">
    <thead>
        <tr>
            <th>
    <h4><?php _e('Next 5 Upcoming Events', 'evrplus_language'); ?></h4>
</th>
<th></th>
</tr>
</thead> 
<tbody>
    <?php
    $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e') LIMIT 5";
    $rows = $wpdb->get_results($sql);
    if ($rows) {
        foreach ($rows as $event) {
            $event_id = $event->id;
            $event_name = stripslashes($event->event_name);
            $event_location = stripslashes($event->event_location);
            $event_address = $event->event_address;
            $event_city = $event->event_city;
            $event_postal = $event->event_postal;
            $reg_limit = $event->reg_limit;
            $start_time = $event->start_time;
            $end_time = $event->end_time;
            $conf_mail = $event->conf_mail;
            $custom_mail = $event->custom_mail;
            $start_date = $event->start_date;
            $end_date = $event->end_date;
            $number_attendees = $wpdb->get_var($wpdb->prepare("SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE payment_status = '" . EventPlus_Models_Payments::PAYMENT_SUCCESS . "' AND event_id=%d", $event_id));
            if ($number_attendees == '' || $number_attendees == 0) {
                $number_attendees = '0';
            }
            if ($reg_limit == "" || $reg_limit == " ") {
                $reg_limit = "Unlimited";
            }
            $available_spaces = $reg_limit;
            $exp_date = $end_date;
            $todays_date = date("Y-m-d");
            $today = strtotime($todays_date);
            $expiration_date = strtotime($exp_date);
            if ($expiration_date <= $today) {
                $active_event = '<span style="color: #F00; font-weight:bold;">' . __('EXPIRED', 'evrplus_language') . '</span>';
            } else {
                $active_event = '<span style="color: #090; font-weight:bold;">' . __('ACTIVE', 'evrplus_language') . '</span>';
            }
            ?>
            <tr>
                <td>
                    <a title="View event" href="<?php echo $this->adminUrl('admin_events/edit', array('event_id' => $event_id)); ?>"><?php echo $event_name ?></a>
                       <br />
                       &nbsp;&nbsp;&nbsp;  <?php echo $start_date; ?> @ <?php echo $start_time ?> </td><td> 
                       <a href="<?php echo $this->adminUrl('admin_attendees', array('event_id' => $event_id)); ?>">Attendees</a> 
                    <br />
                    &nbsp;&nbsp;<?php echo $number_attendees ?> / <?php echo $reg_limit ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</tbody>
</table>
<?php
