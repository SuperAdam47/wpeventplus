<div class="postbox">
    <div class="inside">
        <div class="padding">
            <h1 class="stephead"><?php _e('Step 3', 'evrplus_language'); ?></h1>
            <br>
            <span class="steptitle">
                <img class="stepimg" src="<?php echo $this->assetUrl('images/check-icon.png'); ?>"><?php _e('Choose Event\'s Time', 'evrplus_language'); ?>
             </span>

            <div class="form-table">
                <p class="p1"><label><?php _e('Start Date', 'evrplus_language'); ?></label>
                    <?php
                    $start = strtotime('12:00am');
                    $end = strtotime('11:45pm');
                    ?>
                    <label for="start_date"><?php echo EventPlus_Helpers_Funx::dateSelector("\"start", strtotime($start_date)); ?></label>
                    <label><?php _e('Start Time', 'evrplus_language'); ?></label><label for="start_time">
                        <?php
                        echo '<select name="start_time">';
                        for ($i = $start; $i <= $end; $i += 900) {
                            $start_selected = '';
                            echo date('g:ia', $i);
                            if (date('g:ia', $i) == $start_time) {
                                $start_selected = 'selected';
                            }
                            $date_to_start = date('g:i a', $i);
                            if ($time_format == '24hrs')
                                $date_to_start = date('H:i', $i);
                            echo '<option value=' . date('g:ia', $i) . ' ' . $start_selected . '>' . $date_to_start . '</option>';
                        }
                        echo '</select>';  ?>
                    </label>
                </p>
                <p class="p2"><label><?php _e('End Date', 'evrplus_language'); ?></label>
                    <label for="end_date"><?php echo EventPlus_Helpers_Funx::dateSelector("\"end", strtotime($end_date)); ?></label>
                    <label><?php _e('End Time', 'evrplus_language'); ?></label><label for="end_time">
                        <?php
                        echo '<select name="end_time">';
                        for ($i = $start; $i <= $end; $i += 900) {
                            $end_selected = '';
                            if (date('g:ia', $i) == $end_time) {
                                $end_selected = 'selected';
                            }
                            $date_to_end = date('g:i a', $i);
                            if ($time_format == '24hrs')
                                $date_to_end = date('H:i', $i);
                            echo '<option value=' . date('g:ia', $i) . ' ' . $end_selected . '>' . $date_to_end . '</option>';
                        }
                        echo '</select>';?>
                    </label>
                </p>

                <p class="p3">
                    <label><?php _e('Do you want the event to be recurring?', 'evrplus_language'); ?></label><select
                            id="recurring_choice" name="recurring_choice"><?php
                        if ($recurrence_choice == "yes") {
                            echo '<option value="yes">' . __('Yes', 'evrplus_language') . '</option><option value="no">' . __('No', 'evrplus_language') . '</option>';
                        } //else if ($recurrence_choice == "no"){echo '<option value="no">No</option><option value="yes">Yes</option>';}
                        else {
                            echo '<option value="no">' . __('No', 'evrplus_language') . '</option><option value="yes">' . __('Yes', 'evrplus_language') . '</option>';
                        }
                        //else {echo '<option value="no">No</option>';}                       
                        ?>
                    </select>
                </p>
                <label for="infinate_event_1">
                    <input style="display:inline-block !important;" type="radio"
                         id="infinate_event_1" <?php if ($infinate_event == 'yes') echo 'checked'; ?>
                         class="infinate_event" value="yes" name="infinate_event"
                         disabled/> <?php _e('Yes') ?>
                </label>
                <p style="clear: both; display: block; margin-top: 10px;"><span
                            style="display: block;"><?php _e('Will this event run indefinitely?') ?></span>
                    <label for="infinate_event_2">
                        <input style="display:inline-block !important;" type="radio"
                             id="infinate_event_2" <?php if ($infinate_event == 'no') echo 'checked'; ?>
                             class="infinate_event" value="no" name="infinate_event"
                             disabled/> <?php _e('No') ?>
                    </label>
                </p>
                <div class="recurrence_options">
                    <p class="p3"><label><?php _e('Recurrence Period', 'evrplus_language'); ?>:</label>
                        <select name="recurring_period">
                            <option value=""<?php
                            if ($recurrence_period == "") {
                                echo ' selected';
                            } ?>><?php echo __('Select Period', 'evrplus_language'); ?></option>
                            <!--<option value="daily"<?php //if ($recurrence_period == "daily"){//echo ' selected';}        ?>>Daily</option>-->
                            
                            <option value="weekly"<?php
                            if ($recurrence_period == "weekly") {
                                echo ' selected';
                            } ?>><?php echo __('Weekly', 'evrplus_language'); ?></option>
                            
                            <option value="monthly"<?php
                            if ($recurrence_period == "monthly") {
                                echo ' selected';
                            } ?>><?php echo __('Monthly', 'evrplus_language'); ?></option>
                            
                            <option value="yearly"<?php
                            if ($recurrence_period == "yearly") {
                                echo ' selected';
                            } ?>><?php echo __('Yearly', 'evrplus_language'); ?></option>
                        </select>
                    </p>

                    <p><label class="tooltip" for="recurrence_frequency"><?php _e('Repeat number of times', 'evrplus_language'); ?></label></p>
                    <p class="cs2" title="<?php _e('Enter the number of times you want the event to occur.', 'evrplus_language'); ?>"></p>
                    <br/>
                    <p><input class="recurrence_frequency" name="recurrence_frequency" value="<?php echo $recurrence_frequency; ?>" type="number" min="0" max="100"></p>

                    <p><label class="tooltip" for="recurrence_repeat"> <?php _e('Gaps per repeat', 'evrplus_language'); ?>
                    <p class="cs2" title="<?php _e('Enter the repetition period', 'evrplus_language'); ?>">
                        <br/>
                        <input class="recurrence_repeat_period" name="recurrence_repeat_period" value="<?php echo $recurrence_repeat_period; ?>" type="number" min="0" max="100">
                    </p>
                </div>
                <p class="p3"><label><?php _e('Close Registration on', 'evrplus_language'); ?> </label>
                    <select name="close" onchange="if(this.value == 'selected_day'){$('#close_on_custom_day').show();}else{$('#close_on_custom_day').hide();}"><?php
                        if ($close == "start") {
                            echo '<option value="start" selected>' . __('Start of Event', 'evrplus_language') . '</option>';
                            echo '<option value="end">' . __('End of Event', 'evrplus_language') . '</option>';
                            echo '<option value="selected_day">' . __('Closed on Date Time', 'evrplus_language') . '</option>';
                        } else if ($close == "end") {
                            echo '<option value="end" selected>' . __('End of Event', 'evrplus_language') . '</option>';
                            echo '<option value="start">' . __('Start of Event', 'evrplus_language') . '</option>';
                            echo '<option value="selected_day">' . __('Closed on Date Time', 'evrplus_language') . '</option>';
                        }
                        else if ($close == "selected_day") {
                            echo '<option value="selected_day" selected>' . __('Closed on Date Time', 'evrplus_language') . '</option>';
                            echo '<option value="start">' . __('Start of Event', 'evrplus_language') . '</option>';
                            echo '<option value="end">' . __('End of Event', 'evrplus_language') . '</option>';
                        }
                        else {
                            echo '<option value="selected_day" selected>' . __('Closed on Date Time', 'evrplus_language') . '</option>';
                            echo '<option value="start">' . __('Start of Event', 'evrplus_language') . '</option>';
                            echo '<option value="end">' . __('End of Event', 'evrplus_language') . '</option>';
                        } ?>
                    </select>

                    <p class="p1" id="close_on_custom_day"<?php if($close != 'selected_day'):?> style="display: none;"<?php endif; ?>>
                        <?php
                        $start = strtotime('12:00am');
                        $end = strtotime('11:45pm');
                        ?>
                        <label for="closure_day_date"><?php echo EventPlus_Helpers_Funx::dateSelector("\"closure_day_date", strtotime($closure_day_date)); ?></label>
                        <label><?php _e('Time', 'evrplus_language'); ?></label>
                        <label for="closure_day_time"><?php
                            echo '<select name="closure_day_time">';
                            for ($i = $start; $i <= $end; $i += 900) {
                                $start_selected = '';
                                echo date('g:ia', $i);
                                if (date('g:ia', $i) == $closure_day_time) {
                                    $start_selected = 'selected';
                                }
                                $date_to_start = date('g:i a', $i);
                                if ($time_format == '24hrs')
                                    $date_to_start = date('H:i', $i);
                                echo '<option value=' . date('g:ia', $i) . ' ' . $start_selected . '>' . $date_to_start . '</option>';
                            }
                            echo '</select>';
                            ?></label>
                    <br />
                    </p>
                    <div style="clear: both;"></div>
                    <input type="submit" name="Submit" value="<?php echo $button_label; ?>" id="add_new_event"/>
            </div>
        </div>
    </div>
</div>