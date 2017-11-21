<style>body{overflow-x: hidden;}</style>

<div class="wrap">
    <h2><a href="#"><img src="<?php echo $this->assetUrl('images/evrplus_icon.png'); ?>" alt="Event Registration for Wordpress" /></a></h2>
    <h2><?php _e('Event Payments Management', 'evrplus_language'); ?></h2>

    <?php if (is_object($oEvent)): ?>
       
        <a href="<?php echo $this->adminUrl('admin_events/edit', array('id' => $oEvent->id)); ?>" class="evrplus_button"><?php _e('Edit Event', 'evrplus_language'); ?></a>

        <?php if (isset($_GET['method'])): ?>

            <a href="<?php echo $this->adminUrl('admin_payments', array('event_id' => $oEvent->id)); ?>" class="evrplus_button"><?php _e('Back to Payments', 'evrplus_language'); ?></a>
        <?php endif; ?>

    <?php endif; ?>

    <a href="<?php echo $this->adminUrl('admin_events'); ?>" class="evrplus_button"><?php _e('View All Events', 'evrplus_language'); ?></a>

    <div class="events-plus_page_payments">
        <?php echo $content; ?>
    </div>

</div>

<div style='text-align: center;'>
    <?php echo EventPlus_Helpers_Funx::promoBanner(); ?>
</div>
