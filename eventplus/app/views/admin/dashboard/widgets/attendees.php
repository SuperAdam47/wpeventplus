<div class="col-md-6 col-sm-12">
    <!-- BEGIN PORTLET-->
    <div class="portlet light ">
        <div class="portlet-title">
            <div class="caption caption-md">
                <i class="icon-bar-chart theme-font-color hide"></i>
                <span class="caption-subject theme-font-color bold uppercase"> <?php _e('LATEST ATTENDEES', 'evrplus_language'); ?></span> 

            </div>

        </div>
        <div class="portlet-body">

            <div class="table-scrollable table-scrollable-borderless">
                <table class="table table-hover table-light">

                    <thead>
                        <tr class="uppercase">
                            <th>
                                <?php _e('Attendee Name', 'evrplus_language'); ?> 
                            </th>

                            <th>

                                <?php _e('Event', 'evrplus_language'); ?> 

                            </th>
                            <th>

                                <?php _e('Tickets', 'evrplus_language'); ?> 

                            </th>
                            <th>
                               									
                            </th>
                        </tr>

                    </thead>
                    <tbody><tr>


                            <?php
                            if (count($attendees) > 0):
                                ?>

                                <?php foreach ($attendees as $attendee) { ?>
                                <tr>
                                    <td><?php echo $attendee->fname . ' ' . $attendee->lname; ?></td>
                                    <td><?php echo $attendee->event_name ?></td>
                                    <td><?php echo $attendee->quantity; ?></td>
                                    <td><a href="<?php echo $this->adminUrl('admin_attendees/details', array('event_id' => $attendee->event_id, 'attendee_id' => $attendee->id)); ?>" class="btn btn-mini btn-info">View</a></td>
                                </tr>
                            <?php } ?>
                        <p  style="text-align: right"><a href="<?php echo $this->adminUrl('admin_attendees'); ?>"><?php _e('View All', 'evrplus_language'); ?></a></p>

                    <?php else: ?>
                        <tr><td colspan="3"> <h4 style="text-align: center"><?php _e('No Attendees Found!', 'evrplus_language'); ?></h4></td></tr>
                            <?php endif; ?>

                    </tbody></table>
            </div>
        </div>


    </div>
    <!-- END PORTLET-->
</div>