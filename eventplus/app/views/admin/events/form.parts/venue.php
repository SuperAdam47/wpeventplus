<div class="postbox">
    <div class="inside">
        <div class="padding">
            <h1 class="stephead"><?php _e('Step 2', 'evrplus_language'); ?></h1>
            <br>
            <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl('images/check-icon.png'); ?>"><?php _e('Choose your event venue', 'evrplus_language'); ?></span>
            <div class="form-table">
                <p><label  class="tooltip" for="reg_limit">
                        <?php _e('Event Seating Capacity', 'evrplus_language'); ?><p class="cs2" title="<?php _e('Enter the number of available seats at your event venue. Leave blank if their is no limit on registrations', 'evrplus_language'); ?>"></p><br/>
                        <input  class="count" name="reg_limit" type="text" value="<?php echo $reg_limit; ?>"></p>
                        
                            <p>

                                <label class="tooltip"  for="event_location">
                                    <?php _e('Event Location/Venue', 'evrplus_language'); ?></label><p class="cs2" title="Enter the name of the business or facility where the event is being held"></p><br/>

                            <input class= "title" id="event_location" name="event_location" type="text" size="50" value="<?php echo $event_location; ?>" />
                    </p>

                    <p>
                        <label class="first" for="event_street"><?php _e('Street', 'evrplus_language'); ?></label><br/>

                        <input  class= "title" id="event_street" name="event_street" type="text" value="<?php echo $event_address; ?>" />
                    </p>

                    <p><label for="event_city">
                            <?php _e('City', 'evrplus_language'); ?></label><br/><input id="event_city" name="event_city" type="text" value="<?php echo $event_city; ?>"/></p>
                    <p><label for="event_state">
                            <?php _e('State', 'evrplus_language'); ?></label><br/><input id="event_state" name="event_state" type="text" value="<?php echo $event_state; ?>" /></p>
                    <p>
                        <label for="event_postcode">
                            <?php _e('Postcode', 'evrplus_language'); ?></label><br/>

                        <input id="event_postcode" name="event_postcode" type="text" value="<?php echo $event_postal; ?>" />
                    </p>
                    <p><label for="event_country">
                            <?php _e('Country', 'evrplus_language'); ?></label><br/><input id="event_country" name="event_country" type="text" value="<?php echo $event_country; ?>"/></p>


             

                <p><label class="tooltip">
                        <?php _e('Use Google Maps On Registration Page', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('All location information must be complete for Google Map feature to work', 'evrplus_language'); ?>"></p><br/>

                <input id="gp1" type="radio" class="radio" name="google_map" value="Y" <?php
                if ($google_map == "Y") {
                    echo "checked";
                }
                ?>><label for="gp1"><?php _e('Yes', 'evrplus_language'); ?></label>
                <input id="gp2" type="radio" class="radio" name="google_map" value="N" <?php
                if ($google_map == "N") {
                    echo "checked";
                }
                ?>><label for="gp2"><?php _e('No', 'evrplus_language'); ?>
                </label>
                </p>
                <br style="clear:both;" /><br />
                <input  type="submit" name="Submit" value="<?php echo $button_label; ?>" id="add_new_event" />
            </div>
        </div>
    </div>
</div>