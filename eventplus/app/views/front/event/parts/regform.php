<?php
global $noImage;

$curdate = date("Y-m-j");
$sql = "SELECT * FROM " . get_option('evr_event') . " WHERE id = '" . (int) $event_id . "'";
$rows = $wpdb->get_results($sql);

if ($rows) {
    foreach ($rows as $event) {
        include "_event_array2string.php";
    }

    $cap_url = $this->assetUrl('cimg/');
    $md5_url = $this->assetUrl("js/md5.js");

    #Begin Page Content    
    #Set default to show or hide event details
    if ($display_desc == "Y") {
        $dsply = "block";
    } else {
        $dsply = "none";
    }

    #Begin Expand/Hide for event details
    ?>
    <div>
        <div id="details" style="border-style:solid;border-width:2px;border-color:#FF0000;padding: 0 0 15px;width:730px;">
            <div class="evrplus_Image_single">
                <?php
                $noImage = false;

                if ($header_image != "header_image" && $header_image != "") {
                    $noImage = true;
                    ?>
                    <img style="width:100%" src="<?php echo $header_image; ?>" />
                <?php } ?>
                <div class="evrplus_social_container" style="float: right; text-align: center;">
                    <a class="evrplus_addToCalendar" href="<?php echo EVENT_PLUS_PUBLIC_URL; ?>ics.php?event_id=<?php echo $event_id; ?>">
                        <div class="evrplus_addcal_icon evrplus_addcal_icon_add_calendar"></div>
                        <div class="evrplus_addcal"><?php echo __('Add to your calendar', 'evrplus_language'); ?></div>
                    </a>
                    <?php
                    $url = urlencode(add_query_arg(array('action' => 'evrplusegister', 'event_id' => $event->id), get_permalink(get_page_by_path('evrplus_registration'))));
                    if (isset($company_options['show_social_icons']) and ! empty($company_options['show_social_icons']) and $company_options['show_social_icons'] != '2'):
                        echo '<div class="evrplus_social_content">';
                        $classForNoImage = "float: left; margin-right: 8px;";
                        $classForNoImageTitle = "padding-top:60px;";
                        if ($header_image != "header_image" && $header_image != "") {

                            echo '<span class="evrplus_social_Links "style="text-align: center; ">
							 <a class="evrplus_socialfacebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '">
							 	<div class="evrplus_fb_icon"></div>
							 </a>
							</span>';
                        } else {
                            echo '<span class="evrplus_social_Links "style="text-align: center; float: left; margin-right: 8px;">
							 <a class="evrplus_socialfacebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '">
							 	<div class="evrplus_fb_icon"></div>
							 </a>
							</span>';
                        }

                        echo '<span class="evrplus_social_Links" style="text-align: center; float:left;">
					 		<a class="evrplus_socialtwitter" target="_blank" href="https://twitter.com/home?status=' . $event_name . ' - ' . $url . '">
						 		<div class="evrplus_tw_icon"></div>
					 		</a>
					 	</span>';
                        echo '</div>';
                    endif;
                    ?>
                </div>
            </div>
            <?php
            if ($header_image != "header_image" && $header_image != "") {
                echo '<h2>' . $event_name . '</h2>';
            } else {
                echo '<h2 style="padding-top:60px;">' . $event_name . '</h2>';
            }
            ?>
            <div class="event_dateTime_container"><?php
                $d_format = date_i18n($evrplus_date_format, strtotime($event->start_date));


                if (isset($_GET['recurr'])) {
                    $d_format = date_i18n($evrplus_date_format, $_GET['recurr']);
                } elseif ($recurr)
                    $d_format = date_i18n($evrplus_date_format, $recurr);
                echo '<div class="col-sm-6 event_date_border"><div class="event_date_container"><p class="event_date" style="float:left;"><span class="dashicons dashicons-calendar-alt"></span> </p><div class="dashiconsText">' . $d_format . '<br />';
                if ($end_date != $start_date and $end_year != '2050') {
                    echo "  -  " . date_i18n($evrplus_date_format, strtotime($event->end_date)) . '</p></p></div></div></div>';
                } else {
                    echo '</div></div></div>';
                }
                if (isset($company_options['time_format']) and $company_options['time_format'] == '24hrs') {
                    $start_time = date('H:i', strtotime($start_time));
                    $end_time = date('H:i', strtotime($end_time));
                }
                echo '<div class="col-sm-6"><div class="event_time_container"><p class="event_time"><span class="dashicons dashicons-clock"></span> ' . __('Time', 'evrplus_language') . ": " . $start_time . " - " . $end_time . '</p></div></div>';
                ?></div>
            <div class="evrplus_thumbnail_container">
                <?php if ($image_link != "") { ?>
                    <img class="evrplus_pop_img evrplus_thumbnail_single" src="<?php echo $image_link; ?>" alt="Thumbnail Image" />
                <?php } else { ?>
                    <?php /* <!--<img class="evrplus_pop_img evrplus_thumbnail_single" src="<?php echo EVR_PLUGINFULLURL; ?>images/event_icon.png" />--> */ ?>
                <?php } ?>
                <p><?php echo html_entity_decode(nl2br($event_desc)); ?></p>
            </div>
            <div style="width:100%;white-space:normal;">
                <div style="white-space:normal;" class="event_map_border">
                    <?php
                    if ($google_map == "Y") {
                        ?>
                        <div id="evrplus_pop_map">
                            <?php
                            //echo '<img border="0" src="http://maps.google.com/maps/api/staticmap?center=';
                            //echo $event_address.','.$event_city.','.$event_state;
                            //echo '&zoom=14&size=280x180&maptype=roadmap&markers=size:mid|color:0xFFFF00|label:*|';
                            //echo $event_address.','.$event_city.'&sensor=false" />';
                            $event_address_map = str_replace(" ", "+", $event_address);
                            $event_city_map = str_replace(" ", "+", $event_city);
                            $event_state_map = str_replace(" ", "+", $event_state);
                            $event_country_map = str_replace(" ", "+", $event_country);
                            if (isset($company_options['googleMap_api_key']) and ! empty($company_options['googleMap_api_key']))
                                echo '<iframe width="100%" height="220" frameborder="0" src="https://www.google.com/maps/embed/v1/place?key=' . $company_options['googleMap_api_key'] . '&q=' . $event_address_map . ',' . $event_city_map . ',' . ( (!$event_state_map) ? $event_postal : $event_state_map) . ',' . $event_country_map . '"></iframe>';
                            else
                                echo '<iframe width="100%" height="220" frameborder="0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDblf6OIl46COqBYUo2DBaxo0-PRl9SZEM&q=' . $event_address_map . ',' . $event_city_map . ',' . ( (!$event_state_map) ? $event_postal : $event_state_map) . ',' . $event_country_map . '"></iframe>';
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="LocationDetailsContainer">
                    <div class="col-sm-6 locationAddressBorder">
                        <div id="evrplus_pop_address">
                            <h3>
                                <u>
                                    <div class="dashicons dashicons-location"></div>
                                    <p class="locationTitle"><?php _e('Event Location', 'evrplus_language'); ?></p>
                                </u>
                            </h3>
                            <p>
                                <?php echo stripslashes($event_location) . '<br />' . $event_address . '<br />' . $event_city . ', ' . $event_state . ' ' . $event_postal; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="padding">
                            <div id="evrplus_pop_price">
                                <h3>
                                    <u>
                                        <div style="  margin-top: 5px; margin-right: 5px;" class="dashicons dashicons-cart"></div>
                                        <p class="event_fee"><?php _e('Event Fees', 'evrplus_language'); ?>:</p>
                                    </u>
                                </h3>
                                <?php
                                $curdate = date("Y-m-d");
                                $sql2 = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id . " ORDER BY sequence ASC";
                                $result2 = $wpdb->get_results($sql2, ARRAY_A);

                                foreach ($result2 as $row2) {
                                    $item_id = $row2['id'];
                                    $item_sequence = $row2['sequence'];
                                    $event_id = $row2['event_id'];
                                    $item_title = $row2['item_title'];
                                    $item_description = $row2['item_description'];
                                    $item_cat = $row2['item_cat'];
                                    $item_limit = $row2['item_limit'];
                                    $item_price = $row2['item_price'];
                                    $free_item = $row2['free_item'];
                                    $item_start_date = $row2['item_available_start_date'];
                                    $item_end_date = $row2['item_available_end_date'];
                                    $item_custom_cur = $row2['item_custom_cur'];

                                    if ($item_custom_cur == "GBP") {
                                        $item_custom_cur = "&pound;";
                                    }

                                    if ($item_custom_cur == "USD") {
                                        $item_custom_cur = "$";
                                    }

                                    if ($fee->item_custom_cur == "BRL") {
                                        $item_custom_cur = "R$";
                                    }

                                    if ((float) $item_price == 0.0) {
                                        $item_custom_cur = "";
                                        $item_price = __('FREE', 'evrplus_language');
                                    }

                                    echo '<p>' . $item_title . '   ' . $item_custom_cur . ' ' . $item_price . '<br /></p>';
                                }

                                if (!$result2) {
                                    echo '<p>' . _e('FREE', 'evrplus_language') . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ($counter_checks == 'Y') {
                $sql_status = "SELECT * FROM " . get_option('evr_event') . " WHERE id = " . (int) $event_id . "";
                $recurring_status_ex = $wpdb->get_results($sql_status);
                $recurring_status = $recurring_status_ex[0]->recurrence_choice;
                if ($recurring_status == 'no') {
                    ?>
                    <div class="evrplus_counter">
                        <div id="evrplus_counter" class="redCountdownDemo">

                        </div>

                        <div class="timer">
                            <div class="days">  <?php _e('Days', 'evrplus_language'); ?></div>
                            <div class="hours"> <?php _e('Hours', 'evrplus_language'); ?></div>
                            <div class="min">   <?php _e('Minutes', 'evrplus_language'); ?></div>
                            <div class="sec">   <?php _e('Seconds', 'evrplus_language'); ?></div>
                        </div>

                    </div>
                    <?php
                }

                $sqlEndDate = "SELECT end_date FROM " . get_option('evr_event') . " WHERE id = " . (int) $event_id . "";
                $resultEndDate = $wpdb->get_var($sqlEndDate);

                if (isset($_GET['recurr']))
                    $resultEndDate = date_i18n('d-m-Y', $_GET['recurr']);
                elseif ($recurr)
                    $resultEndDate = date_i18n('d-m-Y', $recurr);
                $close_dt = $end_date . " " . $end_time;
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        var endDate = new Date(<?php echo strtotime($close_dt) ?>);
                        $('#evrplus_counter').redCountdown({
                            end: $.now() + (((endDate.getTime() * 1000) - $.now()) / 1000),
                            labels: true,
                            style: {
                                element: "",
                                textResponsive: .5,
                                daysElement: {
                                    gauge: {
                                        thickness: .2,
                                        bgColor: "#cccccc",
                                        fgColor: "#1ABC9C"
                                    },
                                    textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                                },
                                hoursElement: {
                                    gauge: {
                                        thickness: .2,
                                        bgColor: "#cccccc",
                                        fgColor: "#2980B9"
                                    },
                                    textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                                },
                                minutesElement: {
                                    gauge: {
                                        thickness: .2,
                                        bgColor: "#cccccc",
                                        fgColor: "#8E44AD"
                                    },
                                    textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                                },
                                secondsElement: {
                                    gauge: {
                                        thickness: .2,
                                        bgColor: "#cccccc",
                                        fgColor: "#F39C12"
                                    },
                                    textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                                }
                            },
                            onEndCallback: function () {
                                console.log("Time out!");
                            }});
                    });
                </script>
            <?php } ?>
            <?php
            #End Expand Hide for Event Details
            /**  In lieu of using the expand/hide feature you can just show the description only by commenting 
             * out the above block and uncommenting the below line.
             * 
             */
//if ($display_desc =="Y"){ echo "<blockquote>".html_entity_decode($event_desc)."</blockquote>"; }
#Registration form content begins here
            ?>
            <!--End Show/Hide Event Details -->
            <!--Begin registration form scripts -->
            <script type="text/javascript" src="<?php echo $md5_url; ?>"></script>
            <?php
            if ($company_options['captcha'] == 'Y') {
                $captcha = "Y";
            } else {
                $captcha = "N";
            }
            ?>
            <script type="text/javascript" src="<?php echo $this->assetUrl('scripts/public/validate.js.php?captcha=' . $captcha . ''); ?>"></script> 
            <?php
            $tax_rate = .0;
            if ($company_options['use_sales_tax'] == "Y") {
                $tax_rate = .0875;
                if ($company_options['sales_tax_rate'] != "") {
                    $tax_rate = $company_options['sales_tax_rate'];
                    echo '<script type="text/javascript" src="' . $this->assetUrl('scripts/public/calculator.js.php?tax=' . $tax_rate . '') . '"></script>';
                }
            } else {
                echo '<script type="text/javascript" src="' . $this->assetUrl('scripts/public/calculator.js.php?tax=' . $tax_rate . '') . '"></script>';
            }
            ?>
            <div id="evrplus_pop_foot">
                <p align="center" >
                    <?php
                    if ($more_info != "") {
                        echo ' <input class="more_info_button" type="button" onClick="window.open(\'' . $more_info . '\');" value="' . __('MORE INFO', 'evrplus_language') . '" />';
                    }
                    ?>
                </p>
            </div>
            <?php
            if ($disable_event_reg != 'Y'):
                $eventplus_token = EventPlus_Helpers_Token::doToken($event_id);
                $pendingTokenRow = EventPlus_Helpers_Token::getPendingRow();
                ?>
                <div class="registerForm">
                    <?php
                    if ($outside_reg == "Y")
                        echo '<a class="extenal_link_reg" href="' . $external_site . '" >' . __('REGISTER', 'evrplus_language') . '</a>';
                    else
                        echo '<input id="eventplus_register_btn" class="register_now_button" type="button" value="' . __('REGISTER', 'evrplus_language') . '"/>'
                        ?>
                    <!--Custom styles from company settings for form--> 

                    <?php if ($company_options['form_css'] != ''): ?>
                        <style>
                        *<?php echo $company_options['form_css']; ?>
                        </style>
                    <?php endif; ?>
                    <div id="evrplusRegForm">
                        <?php
                        //$current_dt= date('Y-m-d H:i a',current_time('timestamp',0));
                        $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));
                        if ($event_close == "start") {
                            $close_dt = $start_date . " " . $start_time;
                        } else if ($event_close == "end") {
                            $close_dt = $end_date . " " . $end_time;
                        } else if ($event_close == "") {
                            $close_dt = $start_date . " " . $start_time;
                        }
                        $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
                        $expiration_date = strtotime($stp);
                        if (isset($_GET['recurr']) and $_GET['recurr'])
                            $expiration_date = $_GET['recurr'];
                        elseif ($recurr)
                            $expiration_date = $recurr;
                        $today = strtotime($current_dt);
                        //echo "The current date and time is: ".$current_dt."<br/>";
                        //echo "Registration closes at: ". $stp."<br/>";                              
                        if ($expiration_date <= $today) {
                            echo '<br/><p class="reg_fees_select">';
                            _e('Registration is closed for this event.', 'evrplus_language');
                            echo '</p><p class="reg_fees_select">';
                            _e('For more information or questions, please email: ', 'evrplus_language');
                            echo '</p><a href="mailto:' . $company_options['company_email'] . '">' . $company_options['company_email'] . '</a></div>';
                        } else {
                            ?> 
                            <form  name="regform"  class="evrplus_regform" method="post" action="<?php echo evrplus_permalink($company_options['evrplus_page_id']); ?>" onSubmit="mySubmit.disabled = true;
                            return validateForm(this)">
                                <ul>
                                    <?php
                                    evrplus_generate_frm_defaults('fname', __('First Name', 'evrplus_language'), $pendingTokenRow['fname']);
                                    evrplus_generate_frm_defaults('lname', __('Last Name', 'evrplus_language'), $pendingTokenRow['lname']);
                                    evrplus_generate_frm_defaults('email', __('Email Address', 'evrplus_language'), $pendingTokenRow['email']);
                                    if ($inc_phone == "Y") {
                                        evrplus_generate_frm_defaults('phone', __('Phone Number', 'evrplus_language'), $pendingTokenRow['phone']);
                                    }
                                    if ($inc_address == "Y") {
                                        evrplus_generate_frm_defaults('address', __('Street/PO Address', 'evrplus_language'), $pendingTokenRow['address']);
                                    }
                                    if ($inc_city == "Y") {
                                        evrplus_generate_frm_defaults('city', __('City', 'evrplus_language', $pendingTokenRow['city']));
                                    }
                                    if ($inc_country == "Y") {
                                        evrplus_generate_frm_defaults('country', __('Country', 'evrplus_language'), $pendingTokenRow['country']);
                                    }
                                    if ($inc_state == "Y") {
                                        evrplus_generate_frm_defaults('state', __('State', 'evrplus_language'), $pendingTokenRow['state']);
                                    }
                                    if ($inc_zip == "Y") {
                                        evrplus_generate_frm_defaults('zip', __('Postal/Zip Code', 'evrplus_language'), $pendingTokenRow['zip']);
                                    }
                                    if ($inc_comp == "Y") {
                                        evrplus_generate_frm_defaults('company', __('Company Name', 'evrplus_language'), $pendingTokenRow['company']);
                                    }
                                    if ($inc_coadd == "Y") {
                                        evrplus_generate_frm_defaults('co_address', __('Company Address', 'evrplus_language'), $pendingTokenRow['co_address']);
                                    }
                                    if ($inc_cocity == "Y") {
                                        evrplus_generate_frm_defaults('co_city', __('Company City', 'evrplus_language'), $pendingTokenRow['co_city']);
                                    }
                                    if ($inc_costate == "Y") {
                                        evrplus_generate_frm_defaults('co_state', __('Company State/Province', 'evrplus_language'), $pendingTokenRow['co_state']);
                                    }
                                    if ($inc_copostal == "Y") {
                                        evrplus_generate_frm_defaults('co_zip', __('Company Postal Code', 'evrplus_language'), $pendingTokenRow['co_zip']);
                                    }
                                    if ($inc_cophone == "Y") {
                                        evrplus_generate_frm_defaults('co_phone', __('Company Phone', 'evrplus_language'), $pendingTokenRow['co_phone']);
                                    }
                                    ?>
                                    <!--End Default Questions -->
                                    <!--Begin Custom Questions -->
                                    <?php
                                    //Additional Questions
                                    $questions = $wpdb->get_results("SELECT * from " . get_option('evr_question') . " where event_id = '" . (int) $event_id . "' order by sequence");
                                    if ($questions) {
                                        foreach ($questions as $question) {
                                            $title = '';
                                            if ($question->remark) {
                                                $title = $question->remark;
                                            }
                                            ?>
                                            <li title="<?php echo $title; ?>">
                                                <label for="question-<?php echo $question->id; ?>" ><?php echo $question->question; ?></label>
                                                <?php echo evrplus_form_build($question); ?>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <!--End Custom Questions -->
                                    <?php
                                    if ($use_coupon == "Y") {
                                        evrplus_generate_frm_defaults('coupon', __('Enter coupon code for discount', 'evrplus_language'));
                                    }
                                    ?>
                                </ul>
                                <br />   
                                <?php
                                #See how many seats are left available
                                $available = evrplus_get_open_seats($event->id, $event->reg_limit);
                                #If there is at least one seat available then begin display of event pricing and allow registration, else no fees notice.                               
                                if ($available >= 1) {
                                    $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id . " ORDER BY sequence ASC";
                                    $rows = $wpdb->get_results($sql);
                                    if ($rows) {
                                        $open_seats = $available;
                                        $curdate = date("Y-m-d");
                                        $fee_count = 0;
                                        $isfees = "N";
                                        #Display Section Header
                                        ?>
                                        <h2 class="reg_img">
                                            <div style="  margin-top: 5px; margin-right: 5px;" class="dashicons dashicons-cart"></div>
                                            <?php _e('Registration Fees', 'evrplus_language'); ?>
                                        </h2>
                                        <br />
                                        <p class="reg_fees_select"><?php _e('You must select at least one item!', 'evrplus_language'); ?></p>
                                        <?php
                                        foreach ($rows as $fee) {
                                            #check fee dates and if date range is valid, display fee
                                            if ((evrplus_greaterDate($curdate, $fee->item_available_start_date)) && (evrplus_greaterDate($fee->item_available_end_date, $curdate))) {
                                                $req = '';
                                                $isfees = "Y";
                                                #Set hidden value for registration type to RGLR vs. WAIT
                                                ?>
                                                <input type="hidden" name="reg_type" value="RGLR"/>
                                                <div align="left">
                                                    <label for="cost" title ="<?php echo $fee->item_description; ?>" >
                                                        <select style="width: 60px" name = "PROD_<?php echo $fee->event_id; ?>-<?php echo $fee->id; ?>_<?php echo $fee->item_price; ?>" id = "PROD_<?php echo $fee->event_id; ?>-<?php echo $fee->id; ?>_<?php echo $fee->item_price; ?>" 
                                                                onChange="<?php
                                                                if ($company_options['use_sales_tax'] == "Y") {
                                                                    echo 'CalculateTotalTax(this.form)';
                                                                } else {
                                                                    echo 'CalculateTotal(this.form)';
                                                                }
                                                                ?>">
                                                            <option value="0">0</option>
                                                            <?php
                                                            #Begin generation of DropDown Box - Options
                                                            #Check to see if the item is a REG type.  If REG, set options count based on seating availability/ ticke limits
                                                            if ($fee->item_cat == "REG") {
                                                                if ($fee->item_limit != "") {
                                                                    if ($available >= $fee->item_limit) {
                                                                        $units_available = $fee->item_limit;
                                                                    } else {
                                                                        $units_available = $available;
                                                                    }
                                                                }
                                                                for ($i = 1; $i <= $units_available; $i++) {
                                                                    ?>
                                                                    <option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            #If item is not REG type, and no limit was set, limit options to 10
                                                            if ($fee->item_cat != "REG") {
                                                                $num_select = "10";
                                                                if ($fee->item_limit != "") {
                                                                    $num_select = $fee->item_limit;
                                                                }
                                                                for ($i = 1; $i < $num_select + 1; $i++) {
                                                                    ?> 
                                                                    <option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?></select>   
                                                        <?php
                                                        #Display Fee description and cost.
                                                        if ($fee->item_custom_cur == "GBP") {
                                                            $item_custom_cur = "&pound;";
                                                        }
                                                        if ($fee->item_custom_cur == "USD") {
                                                            $item_custom_cur = "$";
                                                        }
                                                        if ($fee->item_custom_cur == "BRL") {
                                                            $item_custom_cur = "R$";
                                                        }
                                                        echo $fee->item_title . "    " . $item_custom_cur . " " . $fee->item_price;
                                                        ?></label>
                                                </div>
                                                <?php
                                            }
                                        }
                                        #No fees are within todays date range.
                                        if ($isfees == "N") {
                                            ?>
                                            <p class="reg_fees_update">  <?php _e('No Fees/Items available for todays date!', 'evrplus_language'); ?>
                                                <?php _e('Please update fee dates!', 'evrplus_language'); ?></p>
                                            <?php #if no fees set hidden reg type to WAIT  ?>
                                            <input type="hidden" name="reg_type" value="WAIT" />
                                        <?php } ?>
                                        <br />
                                        <?php
                                        #Display the Total Boxes with Tax
                                        if ($company_options['use_sales_tax'] == "Y") {
                                            ?>
                                            <table>
                                                <tr><td><b><?php _e('Registration Fees', 'evrplus_language'); ?></b></td><td><input style="width: 100px" type="text" name="fees" id="fees" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/></td></tr>
                                                <tr><td><b><?php _e('Sales Tax', 'evrplus_language'); ?></b></td><td><input style="width: 100px" type="text" name="tax" id="tax" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/></td></tr>
                                                <?php if ($fee->item_price > 0): ?>
                                                    <tr>
                                                        <td><b><?php _e('Total', 'evrplus_language'); ?></b></td>
                                                        <td>
                                                            <input style="width: 100px" type="text" name="total" id="total" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/>
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="2">
                                                            <input style="width: 100px" type="hidden" name="total" id="total" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </table>
                                        <?php } else {  #Display Total Boxes without Tax 
                                            ?>

                                            <b>
                                                <?php if ($fee->item_price > 0): ?>
                                                    <?php _e('Total   ', 'evrplus_language'); ?>
                                                    <input style="width: 100px" type="text" name="total" id="total" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/>
                                                <?php else: ?>
                                                    <input style="width: 100px" type="hidden" name="total" id="total" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/>

                                                <?php endif; ?>
                                            </b>
                                        <?php } ?>
                                        <br />
                                        <br />
                                    <?php } else {
                                        ?>
                                        <p class="reg_fees_update">
                                            <?php _e('No Fees Have Been Setup For This Event!', 'evrplus_language'); ?>
                                            <?php _e('Registration for this event can not be taken at this time.', 'evrplus_language'); ?>
                                        </p>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <p class="reg_fees_update"><?php _e('This event has reached registration capacity.', 'evrplus_language'); ?>
                                        <?php _e('Please provide your information to be placed on the waiting list.', 'evrplus_language'); ?>
                                    </p>
                                    <br />
                                    <input type="checkbox" onclick="mySubmit.disabled = false" name="request" value="Waitlist" /> 
                                    <?php _e('Put me on the waitlist.', 'evrplus_language'); ?>
                                    <input type="hidden" name="reg_type" value="WAIT" />
                                <?php } ?>
                                <br />
                                <?php if ($company_options['captcha'] == 'Y' && trim($company_options['captcha_key']) != "") { ?>
                                    <script src="https://www.google.com/recaptcha/api.js" type="text/javascript" async defer></script>
                                    <script type="text/javascript">
                                        jQuery(document).ready(function () {
                                            jQuery("#mySubmit").click(function () {
                                                if (grecaptcha.getResponse() == "") {
                                                    alert("Please fill the captcha !");
                                                    return false;
                                                }
                                            });
                                        });
                                    </script>
                                    <div class="g-recaptcha" id ="g-recaptcha" data-sitekey="<?php echo $company_options['captcha_key']; ?>"></div>
                                    <?php
                                }
                                ?>
                                <?php
                                if ($term_c == 'Y') {
                                    echo '<p><input type="checkbox" name="accept_term" required/>' . __('I accept the terms and conditions', 'evrplus_language') . '</p>';
                                    echo '<p><div style="width:100%;height:90px;overflow-y:scroll;">' . html_entity_decode($term_desc) . '</div></p>';
                                }
                                ?>
                                <input type="hidden" name="action" value="confirm"/>
                                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>" />
                                <input type="hidden" name="eventplus_token" value="<?php echo $eventplus_token; ?>" />
                                <div  class="regform_buttons">
                                    <input type="submit" name="mySubmit" id="mySubmit" disabled="true" value="<?php _e('Submit', 'evrplus_language'); ?>" />
                                    <input type="reset" value="<?php _e('Reset', 'evrplus_language'); ?>" />
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>        
    <?php
}