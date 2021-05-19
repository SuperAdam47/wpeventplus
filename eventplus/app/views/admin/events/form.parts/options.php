<div class="postbox">
    <div class="inside">
        <div class="padding">

            <h1 class="stephead"><?php _e('Step 4', 'evrplus_language'); ?></h1>
            <br>
            <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl('images/check-icon.png'); ?>"><?php _e('Choose Event Options', 'evrplus_language'); ?></span>
            <div class="form-table">

                <p>
                    <label class="tooltip">
                        <?php _e('Show Registration Button', 'evrplus_language'); ?></label>
                <p class="cs2" title="<?php _e('If you select yes, then it will show the button and user must click it to reveal the form. In case of No, registration form will be displayed.', 'evrplus_language'); ?>"></p><br/>
                <input type="radio" name="show_register_button" class="radio" id="show_register_button_yes" value="Y" <?php
                if ($show_register_button == "Y" || $show_register_button == '') {
                    echo "checked";
                };
                ?>/><label for="show_register_button_yes"><?php _e('Yes', 'evrplus_language'); ?></label>
                <input type="radio" name="show_register_button" class="radio" id="show_register_buttonno" value="N" <?php
                if ($show_register_button == "N") {
                    echo "checked";
                };
                ?> /><label for="show_register_buttonno"><?php _e('No ', 'evrplus_language'); ?></label>

                </p>
                 <br />
                <p>
                    <label class="tooltip">
                        <?php _e('Skip Registration Step2', 'evrplus_language'); ?></label>
                <p class="cs2" title="<?php _e('Whether to skip step2 if attendee quantity is 1.', 'evrplus_language'); ?>"></p><br/>
                <input type="radio" name="skip_step_2" class="radio" id="skip_step_2_yes" value="Y" <?php
                if ($skip_step_2 == "Y" || $skip_step_2 == '') {
                    echo "checked";
                };
                ?>/><label for="skip_step_2_yes"><?php _e('Yes', 'evrplus_language'); ?></label>
                <input type="radio" name="skip_step_2" class="radio" id="skip_step_2_no" value="N" <?php
                if ($skip_step_2 == "N") {
                    echo "checked";
                }
                ?> /><label for="skip_step_2_no"><?php _e('No ', 'evrplus_language'); ?></label>

                </p>

                <br />
                <p>
                    <label class="tooltip">
                        <?php _e('Will you accept checks/cash for this event? ', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('If you will accept checks or cash, usually when accepting payment at event/on-site.', 'evrplus_language'); ?>"></p><br/>
                <input id="y1" type="radio" name="allow_checks" class="radio" id="accept_checks_yes" value="Y" <?php
                if ($allow_checks == "Y") {
                    echo "checked";
                };
                ?>/><label for="y1"><?php _e('Yes', 'evrplus_language'); ?></label>
                <input id="y2" type="radio" name="allow_checks" class="radio" id="accept_checks_no" value="N" <?php
                if ($allow_checks == "N") {
                    echo "checked";
                };
                ?> /><label for="y2"><?php _e('No ', 'evrplus_language'); ?></label>

                </p>

                <div class="cl2">
                    <p>
                        <label class="tooltip" >
                            <?php _e('Are you using an external registration?', 'evrplus_language'); ?></label> <p class="cs2" title="<?php _e('You can point your register now button to an external registration site/page by selecting yes and entering the url!', 'evrplus_language'); ?>"></p><br/>
                    <input id="w1" type="radio" name="outside_reg" class="radio" id="outside_reg_yes" value="Y" <?php
                    if ($outside_reg == "Y") {
                        echo "checked";
                    };
                    ?>/> <label for="w1"><?php _e('Yes', 'evrplus_language'); ?> </label>
                    <input id="w2" type="radio" name="outside_reg" class="radio" id="outside_reg_no" value="N" <?php
                    if ($outside_reg == "N") {
                        echo "checked";
                    };
                    ?>  /><label for="w2"><?php _e('No', 'evrplus_language'); ?> 
                    </label></p>
                </div>

                <div class="cl2">  
                    <p>

                        <label class="tooltip">
                            <?php _e('External registration URL', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Enter the url hyperlink to another webpage or website external registration', 'evrplus_language'); ?>" ></p><br/>
                    <input class= "title" id="external_site" name="external_site" type="text" value="<?php echo $external_site; ?>"/>
                    </p>
                </div>

                <div class="cl2">
                    <p>
                        <label class="tooltip">
                            <?php _e('Do you wish to disable registration for this event?', 'evrplus_language'); ?>
                        </label> 
                        <br/>
                        <input type="radio" name="disable_event_reg" class="radio" id="disable_event_reg_yes" value="Y" <?php
                        if ($disable_event_reg == "Y") {
                            echo "checked";
                        }
                        ?>/> <label for="disable_event_reg_yes"><?php _e('Yes', 'evrplus_language'); ?> </label>
                        <input  type="radio" name="disable_event_reg" class="radio" id="disable_event_reg_no" value="N" <?php
                        if ($disable_event_reg == "N") {
                            echo "checked";
                        };
                        ?>  /><label for="disable_event_reg_no"><?php _e('No', 'evrplus_language'); ?> 
                        </label></p>
                </div>

                <br style="clear:both;" />
                <p>

                    <label class="tooltip" >
                        <?php _e('Default Registration Information', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Choose the fields you would like visitors to fill out when registering for your event. Note that name and email are mandatory.', 'evrplus_language'); ?>"></p><br/>
                </p>
                <div class="dr1">

                    <INPUT  id="ckb1" class="" type="checkbox" name="reg_form_defaults[]" value="Address" <?php
                    if (@$inc_address == "Y") {
                        echo "checked";
                    };
                    ?>  /><label for="ckb1" class="checkbox<?php
                            if (@$inc_address == 'Y') {
                                echo ' checked';
                            }
                            ?>" ></label><?php _e('Street Address', 'evrplus_language'); ?><br>
                    <INPUT id="ckb2" class="" type="checkbox" name="reg_form_defaults[]" value="City" <?php
                    if (@$inc_city == "Y") {
                        echo "checked";
                    };
                    ?>  /><label for="ckb2" class="checkbox<?php
                           if (@$inc_city == 'Y') {
                               echo ' checked';
                           }
                           ?>"></label><?php _e('City', 'evrplus_language'); ?><br>
                    <INPUT id="ckb3" class="" type="checkbox" name="reg_form_defaults[]" value="State" <?php
                    if (@$inc_state == "Y") {
                        echo "checked";
                    };
                    ?> /><label for="ckb3" class="checkbox<?php
                           if (@$inc_state == 'Y') {
                               echo ' checked';
                           }
                           ?>"></label><?php _e('State or Province', 'evrplus_language'); ?><br>
                    <INPUT id="ckb4" class="" type="checkbox" name="reg_form_defaults[]" value="Zip" <?php
                    if (@$inc_zip == "Y") {
                        echo "checked";
                    };
                    ?>  /><label for="ckb4" class="checkbox<?php
                           if (@$inc_zip == 'Y') {
                               echo ' checked';
                           }
                           ?>"></label><?php _e('Zip or Postal Code', 'evrplus_language'); ?><br>
                    <INPUT id="ckb5" class="" type="checkbox" name="reg_form_defaults[]" value="Phone" <?php
                    if (@$inc_phone == "Y") {
                        echo "checked";
                    };
                    ?>  /><label for="ckb5" class="checkbox<?php
                           if (@$inc_phone == 'Y') {
                               echo ' checked';
                           }
                           ?>"></label><?php _e('Phone Number', 'evrplus_language'); ?><br>
                    <INPUT id="ckb6" class="" type="checkbox" name="reg_form_defaults[]" value="Country" <?php
                    if (@$inc_country == "Y") {
                        echo "checked";
                    };
                    ?> /><label for="ckb6" class="checkbox<?php
                           if (@$inc_country == 'Y') {
                               echo ' checked';
                           }
                           ?>"></label><?php _e('Country', 'evrplus_language'); ?><br>

                </div>
                <div class="dr2">

                    <INPUT id="ckb11" class="" type="checkbox" name="reg_form_defaults[]" value="Company" <?php
                    if (@$inc_comp == "Y") {
                        echo "checked";
                    };
                    ?> /><label for="ckb11" class="checkbox<?php
                           if (@$inc_comp == 'Y') {
                               echo ' checked';
                           }
                           ?>" ></label><?php _e('Company', 'evrplus_language'); ?><br>
                    <INPUT id="ckb7" class="" type="checkbox" name="reg_form_defaults[]" value="CoAddress" <?php
                    if (@$inc_coadd == "Y") {
                        echo "checked";
                    };
                    ?>  /><label for="ckb7" class="checkbox<?php
                           if (@$inc_coadd == 'Y') {
                               echo ' checked';
                           }
                           ?>" ></label><?php _e('Co. Addr', 'evrplus_language'); ?><br>
                    <INPUT id="ckb8" class="" type="checkbox" name="reg_form_defaults[]" value="CoCity" <?php
                    if (@$inc_cocity == "Y") {
                        echo "checked";
                    };
                    ?>  /><label for="ckb8" class="checkbox<?php
                           if (@$inc_cocity == 'Y') {
                               echo ' checked';
                           }
                           ?>" ></label><?php _e('Co. City', 'evrplus_language'); ?><br>
                    <INPUT id="ckb9" class="" type="checkbox" name="reg_form_defaults[]" value="CoState" <?php
                    if (@$inc_costate == "Y") {
                        echo "checked";
                    };
                    ?>  /><label for="ckb9" class="checkbox<?php
                           if (@$inc_costate == 'Y') {
                               echo ' checked';
                           }
                           ?>" ></label><?php _e('Co. State/Prov', 'evrplus_language'); ?><br>
                    <INPUT id="ckb10" class="" type="checkbox" name="reg_form_defaults[]" value="CoPostal" <?php
                    if (@$inc_copostal == "Y") {
                        echo "checked";
                    };
                    ?>  /><label for="ckb10" class="checkbox<?php
                           if (@$inc_copostal == 'Y') {
                               echo ' checked';
                           }
                           ?>" ></label><?php _e('Co. Postal', 'evrplus_language'); ?><br>

                </div>
                <div class="cl2">
                    <p>
                        <label class="tooltip" >
                            <?php _e('Do you want to display terms and conditions?', 'evrplus_language'); ?></label> <br/>
                        <input type="radio" name="term_c" class="radio" id="term_c_y" value="Y" <?php
                        if ($term_c == "Y") {
                            echo "checked";
                        };
                        ?>/> <label for="term_c_y"><?php _e('Yes', 'evrplus_language'); ?> </label>
                        <input  type="radio" name="term_c" class="radio" id="term_c_n" value="N" <?php
                        if ($term_c == "N") {
                            echo "checked";
                        };
                        ?>  /><label for="term_c_n"><?php _e('No', 'evrplus_language'); ?> 
                        </label>
                    </p>
                    <br /><br />
                    <div id="term_div" style="<?php
                    if ($term_c == 'N' || $term_c == '') {
                        echo 'display:none';
                    }
                    ?>">

                        <?php
                        $term_c_force = '';
                        if (isset($meta_data)) {
                            if (isset($meta_data['term_c_force'])) {
                                $term_c_force = $meta_data['term_c_force'];
                            }
                        }
                        ?>
                        <p>
                            <label class="tooltip" >
                                <?php _e('Do you want to force terms and conditions?', 'evrplus_language'); ?></label> 
                        <p class="cs2" title="<?php _e('If yes, registration form will only be displayed once attendee agrees the terms and conditions', 'evrplus_language'); ?>"></p>
                        <br/>
                        <input type="radio" name="term_c_force" class="radio" id="term_c_force_y" value="Y" <?php
                                if ($term_c_force == "Y") {
                                    echo "checked";
                                };
                                ?>/> <label for="term_c_force_y"><?php _e('Yes', 'evrplus_language'); ?> </label>
                        <input  type="radio" name="term_c_force" class="radio" id="term_c_force_n" value="N" <?php
                        if ($term_c_force == "N") {
                            echo "checked";
                        };
                                ?>  /><label for="term_c_force_n"><?php _e('No', 'evrplus_language'); ?> 
                        </label>
                        <Br />
                        <?php
                        if (function_exists('the_editor')) {
                            echo "</p>";
                            the_editor(htmlspecialchars_decode($term_desc), "term_desc", '', false);
                        } else {
                            ?>

                            <a href="javascript:void(0)" onclick="tinyfy(1, 'term_desc')"><input type="button" value="WYSIWG"/></a>
                            </p>
                            <textarea name="term_desc" id="term_desc" style="width: 100%; height: 200px;"><?php echo $term_desc; ?></textarea>
                        <?php } ?>
                    </div></div>
                <div class="clear"></div>
                <h3 class="t2"></h3>
                <span class="steptitle ntp"><img class="stepimg" src="<?php echo $this->assetUrl('images/check-icon.png'); ?>"><?php _e('Event Listing Options', 'evrplus_language'); ?></span><p></p>
                <p><label class="tooltip">
                        <?php _e('More Info URL', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Enter the url hyperlink to another webpage or website with more event information', 'evrplus_language'); ?>"></p><br/>

                <input class= "title" id="event_location" name="more_info" type="text" value="<?php echo $more_info; ?>"/>

                </p>
                <div class="cl2" style="overflow: hidden;">
                    <p>
                        <label class="tooltip">
                            <?php _e('Would you like to display countdown timer? Yes  No ', 'evrplus_language'); ?></label><br/>
                        <input type="radio" name="counter_checks" class="radio" id="counter_checks_yes" value="Y" <?php
                            if ($counter_checks == "Y") {
                                echo "checked";
                            };
                            ?> /><label for="counter_checks_yes"><?php _e('Yes', 'evrplus_language'); ?></label>
                        <input type="radio" name="counter_checks" class="radio" id="counter_checks_no" value="N" <?php
                        if ($counter_checks == "N") {
                            echo "checked";
                        };
                            ?> /><label for="counter_checks_no"><?php _e('No ', 'evrplus_language'); ?></label>

                    </p>
                </div>
                <p>
                <div class="uploader" style="clear: both;">
                    <label class="tooltip">
                        <?php _e('Thumbnail Image URL', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Enter the url to an image you would like displayed next to the event in the event listings. Size should be 300 x300', 'evrplus_language'); ?>"></p><br/>
                    <input class= "title" id="_unique_name" name="image_link" type="text" value="<?php echo $image_link; ?>"/>
                    <input id="_unique_name_button" class="button" name="_unique_name_button"  type="submit" value="Upload" style=" display: inline; width: 75px;  height: 45px;"/> </p>
                    <p class="_unique_name">
                        <img src="<?php echo $image_link; ?>" style="
                             width: 150px;
                             height: auto;
                             ">
                    </p>
                </div>
                <script>
                    jQuery(document).ready(function ($) {
                        var _custom_media = true,
                                _orig_send_attachment = wp.media.editor.send.attachment;

                        $('.uploader .button').on('click',function (e) {
                            var send_attachment_bkp = wp.media.editor.send.attachment;
                            var button = $(this);
                            var id = button.attr('id').replace('_button', '');
                            _custom_media = true;
                            wp.media.editor.send.attachment = function (props, attachment) {
                                if (_custom_media) {
                                    $("#" + id).val(attachment.url);
                                    $('.' + id + ' img').attr('src', attachment.url);
                                } else {
                                    return _orig_send_attachment.apply(this, [props, attachment]);
                                }
                            }

                            wp.media.editor.open(button);
                            return false;
                        });

                        $('.add_media').on('click', function () {
                            _custom_media = false;
                        });
                    });
                </script>


                <div class="uploader" style="clear: both;">
                    <p>
                        <label class="tooltip" >
                            <?php _e('Header Image URL', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Enter the url of an image you wish displayed above the registration form.  The image should be at least 700px wide hight does not metter.', 'evrplus_language'); ?>"></p><br/>
                    <input class= "title" id="_unique_name1" name="header_image" type="text" value="<?php echo $header_image; ?>"/>
                    <input id="_unique_name1_button" class="button" name="_unique_name_button" type="submit"  value="Upload" style=" display: inline; width: 75px;   height: 45px;"/> </p>
                    <p class="_unique_name1">
                        <img src="<?php echo $header_image; ?>" style="
                             width: 150px;
                             height: auto;
                             ">
                    </p>
                </div>
                <script>
                    jQuery(document).ready(function ($) {
                        var _custom_media = true,
                                _orig_send_attachment = wp.media.editor.send.attachment;

                        $('.uploader .button').on('click',function (e) {
                            var send_attachment_bkp = wp.media.editor.send.attachment;
                            var button = $(this);
                            var id = button.attr('id').replace('_button', '');
                            _custom_media = true;
                            wp.media.editor.send.attachment = function (props, attachment) {
                                if (_custom_media) {
                                    $("#" + id).val(attachment.url);
                                    $('.' + id + ' img').attr('src', attachment.url);
                                } else {
                                    return _orig_send_attachment.apply(this, [props, attachment]);
                                }
                                ;
                            }

                            wp.media.editor.open(button);
                            return false;
                        });

                        $('.add_media').on('click', function () {
                            _custom_media = false;
                        });
                    });
                </script>
                <br /><br />

                <input  type="submit" name="Submit" value="<?php echo $button_label; ?>" id="add_new_event" />
            </div></div></div></div>