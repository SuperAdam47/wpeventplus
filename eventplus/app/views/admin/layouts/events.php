
<div class="events-plus_page_events">
    <div class="wrap">
        <h2><a href=""><img src="<?php echo $this->assetUrl('images/evrplus_icon.png'); ?>" alt="Event Registration for Wordpress" /></a></h2>
        <h2><?php _e('Event Management', 'evrplus_language'); ?></h2>

        <?php if (EventPlus::factory('Var')->get('method', $_GET) != 'add'): ?>

            <a href="<?php echo $this->adminUrl('admin_events/add'); ?>" class="evrplus_button"><?php _e('ADD EVENT', 'evrplus_language'); ?></a>


        <?php endif; ?>


        <?php echo $content; ?>

    </div>
</div>
<div style='text-align: center;'>
    <?php echo EventPlus_Helpers_Funx::promoBanner(); ?>
</div>
