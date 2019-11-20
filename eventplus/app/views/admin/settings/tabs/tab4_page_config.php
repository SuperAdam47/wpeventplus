<div id="tab4_page_config" class="tab_content">
    <div class="postbox " >
        <div class="inside">
            <div class="padding">
                <div class="padding">
                    <h1 class="stephead">
                        <?php _e('Step 4', 'evrplus_language'); ?>
                    </h1>
                    <span class="steptitle"> <img class="stepimg" src="<?php echo $this->assetUrl(); ?>images/choose-page-icon.png"> <?php echo _e('Choose Pages', 'evrplus_language'); ?> </span>
                    <?php
                    if( !isset($_POST['evrplus_page_id']) || evrplus_issetor($_POST['evrplus_page_id']) || $company_options['evrplus_page_id'] == '0' ) {
                        ?>
                        <p class="updated fade red_text" align="center"><strong><span>**
                                    <?php _e('Attention', 'evrplus_language'); ?>
                                    **</strong><br />
                            <?php _e('These settings must be configured for the plugin to function correctly.', 'evrplus_language'); ?>
                            </span>.</p>
                    <?php } ?>
                    <label class="main">
                        <?php _e('Main registration page', 'evrplus_language'); ?>
                    </label>
                    <div class="styled cs1"> 

                        <?php
                        $pages = EventPlus_Helpers_Funx::getRegistrationPages();
                        $class = !empty( $class ) ? $class : '';

                        $outputDDL = "<select name='evrplus_page_id' id='evrplus_page_id' " . $class . ">\n";

                        if (count($pages)) {
                            foreach ($pages as $p => $pageObj) {
                                $selectedStr = '';
                                if ($company_options['evrplus_page_id'] == $pageObj->ID) {
                                    $selectedStr = ' selected="selected"';
                                }
                                $outputDDL .= "\t<option value=\"" . esc_attr($pageObj->ID) . '"' . $selectedStr . '>[ID: ' . $pageObj->ID . '] ' . $pageObj->post_title . "</option>\n";
                            }
                        } else {
                            $outputDDL .= "\t<option>Configure page with {EVRREGIS} shortcode.</option>\n";
                        }
                        $outputDDL .= "</select>\n";

                        echo $outputDDL;
                        ?>
                    </div>
                    <p class="cs2" title="<?php _e('This page should contain the {EVRREGIS} filter.&nbsp;This page can be hidden from navigation, if desired', 'evrplus_language'); ?>"></p>
                    
                    <?php /* <p class="pay">
                        <?php _e('Return URL for Payments', 'evrplus_language'); ?>
                    <div class="styled cs1"> 
                        <!--  <select name="return_url">
                        <option value="0"><?php _e('Main page', 'evrplus_language'); ?></option>-->
                        <?php //parent_dropdown ($default=$company_options['return_url']);     ?>
                        </select>
                        <?php
                        $args2 = array('exclude' => $list_trash, 'selected' => $company_options['return_url'], 'name' => 'return_url');
                        wp_dropdown_pages($args2);       //parent_dropdown ($default=$company_options['evrplus_page_id']);  
                        ?>
                    </div>
                    <p class="cs2" title="<?php _e('This page should be hidden and will contain the [eventsplus_payment] payment shortcode.&nbsp; This page should be hidden from navigation', 'evrplus_language'); ?>"></p>
                    <!--   <a class="ev_reg-fancylink" href="#payment_page_info"><img src="<?php //echo EVR_PLUGINFULLURL              ?>/images/question-frame.png" width="16" height="16" /></a><br />
                        <font  size="-2">(This page should be hidden and will contain the EVR_PAYMENT payment shortcode. This page should be hidden from navigation.)</font>  -->
                    </p>*/?>
                    <div id="registration_page_info" style="display:none">
                        <h2>Main Events Page</h2>
                        <p>This is the page that displays your events.</p>
                        <p>Additionally, all registration process pages will use this page as well.</p>
                        <p>This page should contain the <strong>{EVRREGIS}</strong> shortcode.</p>
                    </div>
                </div>
                <div class="padding">
                    <label for="captcha">
                        <?php _e('Choose if you would like to display events in Ascending or Descending order', 'evrplus_language'); ?>
                    </label>
                    <input name="order_event_list" id="orderAsc" type="radio" value="ASC" class="regular-radio" <?php
                    if ($company_options['order_event_list'] == "ASC") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="orderAsc">
                        <?php _e('ASC', 'evrplus_language'); ?>
                    </label>
                    <input name="order_event_list" id="orderDesc" type="radio" value="DESC" class="regular-radio" <?php
                    if ($company_options['order_event_list'] == "DESC") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="orderDesc">
                        <?php _e('DESC', 'evrplus_language'); ?>
                    </label>
                </div>
                <div class="padding"> <span class="steptitle">
                        <?php _e('Social Icons', 'evrplus_language'); ?>
                    </span>
                    <p>
                        <?php _e('Show Social sharing icons in event page?', 'evrplus_language'); ?>
                        <select name = "show_social_icons" class="regular-select">
                            <option value="1" <?php if ($company_options['show_social_icons'] == "1") echo ' selected'; ?>>
                                <?php _e('Yes'); ?>
                            </option>
                            <option value="2" <?php if ($company_options['show_social_icons'] == "2") echo ' selected'; ?>>
                                <?php _e('No'); ?>
                            </option>
                        </select>
                    </p>
                </div>
                <div class="padding"> <span class="steptitle">
                        <?php _e('Google Map', 'evrplus_language'); ?>
                    </span>
                    <p>
                        <?php
                        _e('Add Google map Api key', 'evrplus_language');
                        echo '(<a href="https://developers.google.com/maps/documentation/embed/guide">Learn More</a>)';
                        ?>
                        <input type="text" name="googleMap_api_key" id="googleMap_api_key" value="<?php echo (isset($company_options['googleMap_api_key'])) ? $company_options['googleMap_api_key'] : ''; ?>" />

                    <p class="cs2" title="<?php _e('If your website gets huge high traffic we highly recommend to use API key.'); ?>"></p>
                    </p>
                </div>
                
                <h2><?php _e('Event Details Page', 'evrplus_language'); ?></h2>
                <?php
                $show_hide_options = array(
                    'display_event_category_details' => array('title' => __('Show/Hide Event Category', 'evrplus_language')),
                    'display_event_coordinator_details' => array('title' => __('Show/Hide Event Coordinator', 'evrplus_language')),
                    'display_event_total_seats' => array('title' => __('Show/Hide Total Seats', 'evrplus_language')),
                    'display_event_remaining_seats' => array('title' => __('Show/Hide Remaining Seats', 'evrplus_language')),
                );
                ?>
                
                <?php foreach( $show_hide_options as $show_hide_option_key => $show_hide_option ): 
                    if( $company_options[$show_hide_option_key] == '' ){
                        $company_options[$show_hide_option_key] = 'hide';
                    }
                    ?>
                <div class="padding">
                    <label for="captcha">
                        <?php echo $show_hide_option['title']; ?>
                    </label>
                    <input name="<?php echo $show_hide_option_key; ?>" id="<?php echo $show_hide_option_key; ?>Show" type="radio" value="show" class="regular-radio" <?php
                    if ($company_options[$show_hide_option_key] == "show") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="<?php echo $show_hide_option_key; ?>Show" style="margin-right: 20px;">
                        <?php _e('Show', 'evrplus_language'); ?>
                    </label>
                    <input name="<?php echo $show_hide_option_key; ?>" id="<?php echo $show_hide_option_key; ?>Hide" type="radio" value="hide" class="regular-radio" <?php
                    if ($company_options[$show_hide_option_key] == "hide") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="<?php echo $show_hide_option_key; ?>Hide">
                        <?php _e('Hide', 'evrplus_language'); ?>
                    </label>
                </div>
                <?php endforeach; ?>
                
                
            </div>
        </div>
    </div>
</div>