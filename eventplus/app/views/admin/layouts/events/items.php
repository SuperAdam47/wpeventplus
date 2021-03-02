<div class="events-plus_page_events">
    <div class="wrap">
        <h2><a href="#"><img src="<?php echo $this->assetUrl('images/evrplus_icon.png'); ?>" alt="Event Registration for Wordpress" /></a></h2>
        <h2><img src="<?php echo $this->assetUrl('images/icon_ticket.png'); ?>" alt="Event Registration for Wordpress" width="40" />  <?php _e('Event Items/Cost Management', 'evrplus_language'); ?></h2>
          <p> <?php _e('Add tickets to set pricing for your event (Adult, Child, VIP, etc.)', 'evrplus_language'); ?> </p>
                                      
        <?php if (!empty($oEvent) && is_object($oEvent)): ?>
            <?php if (isset($_GET['method']) == false): ?>
                <a href="<?php echo $this->adminUrl('admin_events_items/add', array('event_id' => $oEvent->id)); ?>" class="evrplus_button"><?php _e('Add Cost/Item', 'evrplus_language'); ?></a>

            <?php endif; ?>
            <a href="<?php echo $this->adminUrl('admin_events/edit', array('id' => $oEvent->id)); ?>" class="evrplus_button"><?php _e('Edit Event', 'evrplus_language'); ?></a>

            <?php if (isset($_GET['method'])): ?>

                <a href="<?php echo $this->adminUrl('admin_events_items', array('event_id' => $oEvent->id)); ?>" class="evrplus_button"><?php _e('Back to Items', 'evrplus_language'); ?></a>
            <?php endif; ?>

        <?php endif; ?>

        <a href="<?php echo $this->adminUrl('admin_events'); ?>" class="evrplus_button"><?php _e('View All Events', 'evrplus_language'); ?></a>


        <?php echo $content; ?>

    </div>
</div>


<script>jQuery(function () {
        jQuery(document).tooltip({position: {my: 'left center', at: 'right+10 center', using: function (position, feedback) {
                    jQuery(this).css(position);
                    jQuery("<div>").addClass("arrow").addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
                }}});
    });</script>

