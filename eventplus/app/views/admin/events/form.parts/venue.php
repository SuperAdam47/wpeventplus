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
                        <?php
                        $sql = "SELECT * FROM " . get_option('evr_location') . " ORDER BY location_name";
                        $locations_array = $this->wpDb()->get_results($sql);
                        if ((!empty($locations_array)) && (get_option('evr_location_active') == "Y")) :
                            ?>
                            <script type="text/javascript">


                                jQuery(document).ready(function ($) {
                                    $("#location_list").change(function () {

                                        if ($(this).val() == "0") {
                                            $("#hide1").slideDown("fast");
                                            $('#hide1 :input').attr('disabled', false);



                                        } else {

                                            $("#hide1").slideUp("fast");
                                            $('#hide1 :input').attr('disabled', true);
                                        }
                                    });

                                });



                            </script>
                            <?php
                            if ($location_list >= '1') {
                                echo '<style type="text/css">.custom_addrs{display:none;}</style>';
                            }
                            ?>
                            <div class="input select">
                                <p><label for="select_location">Event Location: </label><br/>
                                    <select name="location_list" id="location_list" onchange="showUser(this.value)">
                                        <option value="<?php echo $location_list; ?>">(choose one)</option>
                                        <option value="0">Custom</option>
                                        <?php
                                        foreach ($locations_array as $location) :
                                            ?>
                                            <option value="<?php echo $location->id; ?>"><?php echo stripslashes($location->location_name); ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </p>
                            </div>

                            <div class="custom_addrs" id="hide1"><!-- this select box will be hidden at first -->
                                <p>
                                    <label class="tooltip" for="event_location">
                                        <?php _e('Event Location/Venue', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Enter the name of the business or facility where the event is being held', 'evrplus_language'); ?>"></p><br/>

                                <input class= "title" id="event_location" name="event_location" type="text" size="50" value="<?php echo $event_location; ?>"/></p>

                                <p>
                                    <label class="first" for="event_street"><?php _e('Street', 'evrplus_language'); ?></label><br/>

                                    <input  class= "title" id="event_street" name="event_street" type="text" value="<?php echo $event_address; ?>" />
                                </p>


                                <p><label for="event_city">
                                        <?php _e('City', 'evrplus_language'); ?></label><br/><input id="event_city" name="event_city" type="text" value="<?php echo $event_city; ?>" /></p>

                                <p><label for="event_state">
                                        <?php _e('State', 'evrplus_language'); ?></label><br /><input id="event_state" name="event_state" type="text"  value="<?php echo $event_state; ?>"/></p>

                                <p>
                                    <label for="event_postcode">
                                        <?php _e('Postcode', 'evrplus_language'); ?></label><br/>

                                    <input id="event_postcode" name="event_postcode" type="text" value="<?php echo $event_postal; ?>"  />
                                </p>

                            </div>

                        <?php else :
                            ?>
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


                <?php
                endif;
                ?>  


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