<div class="col-md-6 col-sm-12">
    <!-- BEGIN PORTLET-->
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption caption-md">
                <i class="icon-bar-chart theme-font-color hide"></i>
                <span class="caption-subject theme-font-color bold uppercase"><?php _e('Latest Events', 'evrplus_language'); ?></span>
            </div>

        </div>
        <div class="portlet-body">
            <div class="table-scrollable table-scrollable-borderless">
                <table class="table table-hover table-light">
                    <thead>
                        <tr class="uppercase">
                            <th>
                                <?php _e('Event Name', 'evrplus_language'); ?> 
                            </th>

                            <th>
                                <?php _e('Seats', 'evrplus_language'); ?>  									
                            </th>

                            <th>
                                <?php _e('Available Seats', 'evrplus_language'); ?>  									
                            </th>

                            <th>
                                <?php _e('Status', 'evrplus_language'); ?> 									
                            </th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php if (count($events) > 0): ?>
                            <tr>


                                <?php
                                foreach ($events as $event) {
                                    $reg_limit = $event->reg_limit;
                                    $number_attendees = $event->number_attendees;
                                    if ($number_attendees == '' || $number_attendees == 0) {

                                        $number_attendees = '0';
                                    }

                                    if ($reg_limit == "" || $reg_limit == " " || $reg_limit == 999999) {

                                        $reg_limit = "Unlimited";
                                    }

                                    $event_close = $event->close;
                                    $end_date = $event->end_date;
                                    $end_time = $event->end_time;
                                    $start_date = $event->start_date;
                                    $start_time = $event->start_time;

                                    $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));

                                    if ($event_close == "start") {
                                        $close_dt = $start_date . " " . $start_time;
                                    } else if ($event_close == "end") {
                                        $close_dt = $end_date . " " . $end_time;
                                    } else {
                                        $close_dt = $end_date . " " . $end_time;
                                    }

                                    if ($event->recurrence_choice == 'yes') {
                                        $dateTime = new DateTime('2030-7-15 8:30pm');
                                        $expiration_date = $dateTime->format("U");
                                    } else {
                                        $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
                                        $expiration_date = strtotime($stp);
                                    }


                                    $today = strtotime($current_dt);

                                    if ($expiration_date < $today) {

                                        $active_event = '<span class="event_ex">' . __('EXPIRED', 'evrplus_language') . '</span>';
                                    } else {

                                        $active_event = '<span class="event_ac">' . __('ACTIVE', 'evrplus_language') . '</span>';
                                    }


                                    $available_spaces = wpeventplus_get_open_seats($event->id, $reg_limit);
                                    ?>
                                <tr>
                                    <td><?php echo stripslashes($event->event_name) ?></td>
                                    <td><?php echo $reg_limit ?></td>
                                    <td><?php echo $available_spaces ?></td>
                                    <td><?php echo $active_event ?></td>
                                </tr>
                            <?php } ?>
                        <p  style="text-align: right"><a href="<?php echo $this->adminUrl('admin_events'); ?>"><?php _e('View All', 'evrplus_language'); ?></a></p>

                    <?php else: ?>
                        <tr><td colspan="3"> <h4 style="text-align: center"><?php _e('No Events Found!', 'evrplus_language'); ?></h4></td></tr>

                    <?php endif; ?>
                    </tbody></table>
            </div>


        </div>
    </div>
</div>