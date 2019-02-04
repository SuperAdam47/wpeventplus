<?php
$wpdb = $this->wpDb();
?>
<a href="<?php echo $this->adminUrl('admin_events/add'); ?>" class="page-title-action"><?php _e('Add New Event', 'evrplus_language'); ?></a>
<a href="<?php echo $this->adminUrl('admin_settings'); ?>" class="page-title-action"><?php _e('General Settings', 'evrplus_language'); ?></a>
<a href="<?php echo $this->adminUrl('admin_categories'); ?>" class="page-title-action"><?php _e('Event Categories', 'evrplus_language'); ?></a>
<a href="<?php echo $this->adminUrl('admin_events'); ?>" class="page-title-action"><?php _e('View Events', 'evrplus_language'); ?></a>
<hr />
<?php echo EventPlus_Helpers_Funx::promoBanner(300); ?>
<div class="inside">
    <div id="activity-widget">
        <div class="activity-block" id="published-posts">
            <h3><?php _e('Next 5 Upcoming Events', 'evrplus_language'); ?></h3>
            <?php
            $sql = "SELECT * FROM " . get_option('evr_event') . " WHERE str_to_date(end_date, '%Y-%m-%e') >= curdate() ORDER BY str_to_date(start_date, '%Y-%m-%e') LIMIT 5";
            $rows = $wpdb->get_results( $sql );
            if( $rows ): ?>
                <ul>
                    <?php
                    foreach( $rows as $event ):
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
                        $custom_mail = isset( $event->custom_mail ) ? $event->custom_mail : '';
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
                        if( $expiration_date <= $today ) {
                            $active_event = '<span style="color: #F00; font-weight:bold;">' . __('EXPIRED', 'evrplus_language') . '</span>';
                        } else {
                            $active_event = '<span style="color: #090; font-weight:bold;">' . __('ACTIVE', 'evrplus_language') . '</span>';
                        } ?>

                        <li>
                            <span><?php echo $start_date; ?> @ <?php echo $start_time ?> </span> 
                            <a aria-label="View event" href="<?php echo $this->adminUrl('admin_events/edit', array('event_id' => $event_id)); ?>">
                                <?php echo $event_name ?> (<?php echo $number_attendees ?> / <?php echo $reg_limit ?>)
                            </a>

                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>