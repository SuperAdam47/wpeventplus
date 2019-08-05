
<div class="postbox " >
    <div class="inside">
        <div class="padding">
            <h1 class="stephead"><?php _e('Step 1', 'evrplus_language'); ?></h1>
            <br>
            <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl('images/check-icon.png'); ?>"> <?php _e('Event Description', 'evrplus_language'); ?></span>
            <div class="form-table">


                <p><label class="tooltip">
                        <?php _e('Event Name/Title ', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Use a concise but descriptive name', 'evrplus_language'); ?>"></p><br/>

                <input class="title" name="event_name" size="50" type="text" value="<?php echo $event_name; ?>"/>
                </p>
                <p>

                    <label class="tooltip">
                        <?php _e('Unique Event Identifier', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Provide a short Unique ID for this event. i.e. BOB001', 'evrplus_language'); ?>"></p><br/>

                <input name="event_identifier" type="text" value="<?php echo $event_identifier; ?>"/>
                </p>



                <p class="ed">

                    <label for="event_desc" class="tooltip">
                        <?php _e('Detailed Event Description', 'evrplus_language'); ?> </label><p class="cs2" title="<?php _e('Provide a detailed description of the event, include key details other than when and where. Do not use any html code. This is a text only display To create new display lines just press Enter', 'evrplus_language'); ?>"></p><br/>

                <?php
                $settings = array(
                    'media_buttons' => true,
                    'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                    'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                );
                if (function_exists('wp_editor')) {
                    //echo "</p>";
                    wp_editor(html_entity_decode(stripslashes($event_desc)), "event_desc", $settings);
                    //the_editor(htmlspecialchars_decode($event_desc), "event_desc", '', false);
                } else {
                    ?>

                    <a href="javascript:void(0)" onclick="tinyfy(1, 'event_desc')"><input type="button" value="WYSIWG"/></a>
                    </p>
                    <textarea name="event_desc" id="event_desc" style="width: 100%; height: 200px;"><?php echo stripslashes($event_desc); ?></textarea>
                <?php } ?>


                <p class="ed">

                    <label for="event_desc" class="tooltip">
                        <?php _e('Event Coordinator', 'evrplus_language'); ?> </label>
                    <br/>

                <?php
                $settings = array(
                    'media_buttons' => true,
                    'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                    'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                );
                if (function_exists('wp_editor')) {
                    wp_editor(html_entity_decode(stripslashes($event_coordinator)), "event_coordinator", $settings);
                } else {
                    ?>

                    <a href="javascript:void(0)" onclick="tinyfy(1, 'event_coordinator')"><input type="button" value="WYSIWG"/></a>
                    </p>
                    <textarea name="event_desc" id="event_desc" style="width: 100%; height: 200px;"><?php echo stripslashes($event_desc); ?></textarea>
                <?php } ?>


                <p>
                    <label class="tooltip">
                        <strong><?php _e('Event Categories', 'evrplus_language'); ?> </strong></label><p class="cs2" title="<?php _e('Select one or many categories for an event', 'evrplus_language'); ?>"></p><br/>

                </p>
                <p>
                    <?php
                    foreach ($categories as $row) {
                        $category_id = $row->id;
                        $category_name = $row->category_name;
                        $checked = in_array($category_id, (array)$event_category);
                        echo '<input  id="cktd' . $category_id . '" class="" value="' . $category_id . '" type="checkbox" name="event_category[]"' . ($checked ? ' checked="checked"' : "" ) . '/><label for="cktd' . $category_id . '" class="checkbox' . ($checked ? " checked" : "" ) . '"></label> ' . "&nbsp;" . $category_name . '&nbsp;&nbsp;&nbsp;<br>';
                    }
                    ?></p>
                <input  type="submit" name="Submit" value="<?php echo $button_label; ?>" id="add_new_event" />

            </div> </div> </div> </div> 