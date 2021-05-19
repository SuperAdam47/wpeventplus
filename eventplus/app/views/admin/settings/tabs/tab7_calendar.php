<div id="tab7_calendar" class="tab_content">
    <div class="postbox " >
        <div class="inside">
            <div class="padding">
                <h1 class="stephead">
                    <?php _e('Step 7', 'evrplus_language'); ?>
                </h1>
                <span class="steptitle"> <img class="stepimg t7" src="<?php echo $this->assetUrl(); ?>images/calendar-color-icon.png">
                    <?php _e('Choose calendar colors', 'evrplus_language'); ?>
                </span>
                <h2 class="calh1">
                    <p>
                        <?php _e('Calendar Settings', 'evrplus_language'); ?>
                    </p>
                </h2>
                <br />
                <!--time format-->
                <p>
                    <label>
                        <?php _e('Choose the time format for your events:', 'evrplus_language'); ?>
                        <div class="styled">
                            <select name="time_format">
                                <option value="am_pm" <?php
                                if (!isset($company_options['time_format']))
                                    echo 'selected';
                                else
                                    selected('am_pm', $company_options['time_format']);
                                ?>>
                                            <?php _e('AM - PM', 'evrplus_language'); ?>
                                </option>
                                <option value="24hrs" <?php
                                if (!isset($company_options['time_format']))
                                    echo '';
                                else
                                    selected('24hrs', $company_options['time_format']);
                                ?>>
                                            <?php _e('24 Hours', 'evrplus_language'); ?>
                                </option>
                            </select>
                        </div>
                    </label>
                </p>
                <p>
                    <label>
                        <?php _e('Choose the date format for your events:', 'evrplus_language'); ?>
                        <div class="styled">
                            <select name="date_format">
                                <option value="us" <?php
                                if (!isset($company_options['date_format']))
                                    echo 'selected';
                                else
                                    selected('us', $company_options['date_format']);
                                ?>>
                                            <?php _e(date('M j, Y'), 'evrplus_language'); ?>
                                </option>
                                <option value="eur" <?php
                                if (!isset($company_options['date_format']))
                                    echo '';
                                else
                                    selected('eur', $company_options['date_format']);
                                ?>>
                                            <?php _e(date('j M Y'), 'evrplus_language'); ?>
                                </option>
                            </select>
                        </div>
                    </label>
                </p>
                <!--end time format--> 
                <!--Add num of seats-->
                <p>
                    <label>
                        <?php _e('Would you like to display amount of seats available?', 'evrplus_language'); ?>
                        <div class="styled">
                            <select name="show_num_seats">
                                <option value="yes" <?php
                                if (!isset($company_options['show_num_seats']))
                                    echo 'selected';
                                else
                                    selected('yes', $company_options['show_num_seats']);
                                ?>>Yes</option>
                                <option value="no" <?php
                                if (!isset($company_options['show_num_seats']))
                                    echo '';
                                else
                                    selected('no', $company_options['show_num_seats']);
                                ?>>No</option>
                            </select>
                        </div>
                    </label>
                </p>
                <!--end num seats-->
                <p>
                    <label>
                        <?php _e('Start Day of Week', 'evrplus_language'); ?>
                        <div class="styled">
                            <select name="start_of_week">
                                <?php if (get_option('evr_start_of_week') == 0) { ?>
                                <option value="0" selected><?php _e('Sunday', 'evrplus_language'); ?></option>
                                     <option value="1">
                                    <?php _e('Monday', 'evrplus_language'); ?>
                                </option>
                               
                                <?php } if (get_option('evr_start_of_week') == 1) { ?>
                                 <option value="0">
                                    <?php _e('Sunday', 'evrplus_language'); ?>
                                </option>
                                <option value="1" selected>
                                        <?php _e('Monday', 'evrplus_language'); ?>
                                    </option>
                               
                                <?php } ?>
                            </select>
                        </div>
                    </label>
                </p>
                <p>
                <div class="con">
                    <label for="tooltip_y">
                        <input type="radio"  id="tooltip_y" name="tooltip_show" value="yes" <?php echo (isset($company_options['evrplus_tooltip_show']) and $company_options['evrplus_cal_use_cat'] == "yes") ? 'checked' : ''; ?> />
                    </label>
                    <label for="tooltip_n">
                        <input type="radio" id="tooltip_n" name="tooltip_show" value="no"  <?php echo (isset($company_options['evrplus_tooltip_show']) and $company_options['evrplus_cal_use_cat'] == "no") ? 'checked' : ''; ?>/>
                    </label>
                </div>
                </label>
                </p>
                <p>
                    <?php _e('Would you like to display the category colors in the calendar?', 'evrplus_language'); ?>
                <div class="con">
                    <input id="uc1" type="radio" name="evrplus_cal_use_cat" class="regular-radio" value="Y"  <?php
                    if ($company_options['evrplus_cal_use_cat'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="uc1">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="uc2" type="radio" name="evrplus_cal_use_cat" class="regular-radio" value="N"  <?php
                    if ($company_options['evrplus_cal_use_cat'] == "N") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="uc2">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                </p>
                <p>
                    <?php _e('Do you want to show add to calendar button?', 'evrplus_language'); ?>
                <div class="con">
                    <input id="evrplus_flag_add_to_cal_button1" type="radio" name="evrplus_flag_add_to_cal_button" class="regular-radio" value="Y"  <?php
                    if ($company_options['evrplus_flag_add_to_cal_button'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="evrplus_flag_add_to_cal_button1">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="evrplus_flag_add_to_cal_button12" type="radio" name="evrplus_flag_add_to_cal_button" class="regular-radio" value="N"  <?php
                    if ($company_options['evrplus_flag_add_to_cal_button'] == "N") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="evrplus_flag_add_to_cal_button12">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                </p>
                <p class="tp1">
                    <?php _e('Select color for Calendar Display', 'evrplus_language'); ?>
                    :</p>
                <p class="cs2" title="<?php _e('Click on each field to display the color picker. Click again to close it', 'evrplus_language'); ?>"></p>
                <script type="text/javascript" charset="utf-8">
                    jQuery(document).ready(function () {
                        jQuery('#picker').hide();
                        /* jQuery('#picker').farbtastic("#cat_back"); */
                        jQuery.farbtastic('#picker').linkTo('#evrplus_cal_head');
                        jQuery("#evrplus_cal_head").on('click',function () {
                            jQuery('#picker').slideToggle()
                        });
                    });
                    jQuery(document).ready(function () {
                        jQuery('#daypicker').hide();
                        jQuery.farbtastic('#daypicker').linkTo('#evrplus_cal_cur_day');
                        jQuery("#evrplus_cal_cur_day").on('click',function () {
                            jQuery('#daypicker').slideToggle()
                        });
                    });
                    jQuery(document).ready(function () {
                        jQuery('#brdrpicker').hide();
                        /* jQuery('#picker').farbtastic("#cat_back"); */
                        jQuery.farbtastic('#brdrpicker').linkTo('#evrplus_cal_pop_border');
                        jQuery("#evrplus_cal_pop_border").on('click',function () {
                            jQuery('#brdrpicker').slideToggle()
                        });
                    });
                    jQuery(document).ready(function () {
                        jQuery('#hdrpicker').hide();
                        jQuery.farbtastic('#hdrpicker').linkTo('#evrplus_cal_day_head');
                        jQuery("#evrplus_cal_day_head").on('click',function () {
                            jQuery('#hdrpicker').slideToggle()
                        });
                    });
                </script>
                <hr />
                <h2 class="calh2">
                    <p>
                        <?php _e('Date Picker', 'evrplus_language'); ?>
                    </p>
                </h2>
                <p>
                    <?php _e('Do you want to use the Date selector?', 'evrplus_language'); ?>
                <div class="con">
                    <input id="ds1" type="radio" name="evrplus_date_select" class="regular-radio" value="Y"  <?php
                    if ($company_options['evrplus_date_select'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="ds1">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="ds2" type="radio" name="evrplus_date_select" class="regular-radio" value="N"  <?php
                    if ($company_options['evrplus_date_select'] == "N") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="ds2">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                </p>
                <p>
                    <?php _e('Do you want to Show Tool tip on the Calendar?', 'evrplus_language'); ?>
                <div class="con">
                    <input id="tp1" type="radio" name="evrplus_tooltip_select" class="regular-radio" value="Y"  <?php
                    if ($company_options['evrplus_tooltip_select'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="tp1">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="tp2" type="radio" name="evrplus_tooltip_select" class="regular-radio" value="N"  <?php
                    if ($company_options['evrplus_tooltip_select'] == "N") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="tp2">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                </p>
                <p class="cal">
                    <label for="color">
                        <?php _e('Calendar Date Selector Background Color', 'evrplus_language'); ?>
                        : </label>
                    <input type="text" id="evrplus_cal_head" name="evrplus_cal_head" style="background-color:<?php
                    if ($company_options['evrplus_cal_head'] != "") {
                        echo $company_options['evrplus_cal_head'];
                    } else {
                        echo '#583c32';
                    }
                    ?>; width: 195px; font-size:12px;" value="<?php
                           if ($company_options['evrplus_cal_head'] != "") {
                               echo $company_options['evrplus_cal_head'];
                           } else {
                               echo "#583c32";
                           }
                           ?> " />
                <div id="picker" style="margin-bottom: 1em;"></div>
                </p>
                <p>
                    <?php _e('Selector Text Color', 'evrplus_language'); ?>
                    :
                <div class="styled">
                    <select name='cal_head_txt_clr' >
                        <option value="#000000" <?php
                        if ($company_options['cal_head_txt_clr'] == "#000000") {
                            echo ' selected';
                        }
                        ?>>
                                    <?php _e('Black', 'evrplus_language'); ?>
                        </option>
                        <option value="#FFFFFF" <?php
                        if ($company_options['cal_head_txt_clr'] == "#FFFFFF") {
                            echo ' selected';
                        }
                        ?>>
                                    <?php _e('White', 'evrplus_language'); ?>
                        </option>
                    </select>
                </div>
                </p>
                <hr />
                <h2 class="calh3">
                    <p>
                        <?php _e('Calendar Header', 'evrplus_language'); ?>
                    </p>
                </h2>
                <p class="cal">
                    <label for="color">
                        <?php _e('Calendar Day Header Background Color', 'evrplus_language'); ?>
                        : </label>
                    <input type="text" id="evrplus_cal_day_head" name="evrplus_cal_day_head" value="<?php
                    if ($company_options['evrplus_cal_day_head'] != "") {
                        echo $company_options['evrplus_cal_day_head'];
                    } else {
                        echo "#b8ced6";
                    }
                    ?>"  style="width: 195px"/>
                <div id="hdrpicker" style="margin-bottom: 1em;"></div>
                </p>
                <p>
                    <?php _e('Selector Text Color', 'evrplus_language'); ?>
                    :
                <div class="styled">
                    <select  name='cal_day_head_txt_clr' >
                        <option value="#000000" <?php
                        if ($company_options['cal_day_head_txt_clr'] == "#000000") {
                            echo ' selected';
                        }
                        ?>>
                                    <?php _e('Black', 'evrplus_language'); ?>
                        </option>
                        <option value="#FFFFFF" <?php
                        if ($company_options['cal_day_head_txt_clr'] == "#FFFFFF") {
                            echo ' selected';
                        }
                        ?>>
                                    <?php _e('White', 'evrplus_language'); ?>
                        </option>
                    </select>
                </div>
                </p>
                <hr />
                <h2 class="calh4">
                    <p>
                        <?php _e('Current Day Color', 'evrplus_language'); ?>
                    </p>
                </h2>
                <p class="cal">
                    <label for="color">
                        <?php _e('Current Day Background Color', 'evrplus_language'); ?>
                        : </label>
                    <input type="text" id="evrplus_cal_cur_day" name="evrplus_cal_cur_day" value="<?php
                    if ($company_options['evrplus_cal_cur_day'] != "") {
                        echo $company_options['evrplus_cal_cur_day'];
                    } else {
                        echo "#b8ced6";
                    }
                    ?>"  style="width: 195px"/>
                <div id="daypicker" style="margin-bottom: 1em;"></div>
                </p>
                <p>
                    <?php _e('Current Day Text Color', 'evrplus_language'); ?>
                    :
                <div class="styled">
                    <select name='cal_day_txt_clr' >
                        <option value="#000000" <?php
                        if ($company_options['cal_day_txt_clr'] == "#000000") {
                            echo ' selected';
                        }
                        ?>>
                                    <?php _e('Black', 'evrplus_language'); ?>
                        </option>
                        <option value="#FFFFFF" <?php
                        if ($company_options['cal_day_txt_clr'] == "#FFFFFF") {
                            echo ' selected';
                        }
                        ?>>
                                    <?php _e('White', 'evrplus_language'); ?>
                        </option>
                    </select>
                </div>
                </p>
                <p class="cal hid">
                    <label for="color">
                        <?php _e('Description Pop Border Color', 'evrplus_language'); ?>
                        :</label>
                    <input type="text" id="evrplus_cal_pop_border" name="evrplus_cal_pop_border" value="<?php
                    if ($company_options['evrplus_cal_pop_border'] != "") {
                        echo $company_options['evrplus_cal_pop_border'];
                    } else {
                        echo "#b8ced6";
                    }
                    ?>"  style="width: 195px"/>
                <div id="brdrpicker" style="margin-bottom: 1em;"></div>
                </p>
            </div>
        </div>
    </div>
</div>