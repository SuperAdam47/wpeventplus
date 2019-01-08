<?php if ($rows): ?>
    <div class="events-plus-2">
        <div class="events-list">
            <?php
            foreach ($rows as $event):
                #Determine when the event ends and compare that date and time to today's date and time
                $id = $event->id;
                $isRecurr = $wpdb->get_var("SELECT recurrence_choice FROM " . get_option('evr_event') . " WHERE id=" . (int) $id);
                $curr = EventPlus_Helpers_Event::check_recurrence($id);

                $cat_array = unserialize($event->category_id);
                $cat_id = $cat_array[0];

                $sql = "SELECT * FROM " . get_option('evr_category') . " WHERE id='" . (int) $cat_id . "' LIMIT 1";
                $cat_details = $wpdb->get_row($sql);

                $style_event_catgry = '#999999'; $event_catgry_fnt_clr = '#ffffff';
                if ($cat_details != "") {
                    $category_identifier = $cat_details->category_identifier;
                    if ($category_identifier != '') {
                        $style_event_catgry = ($cat_details->category_color);
                        $event_catgry_fnt_clr = ($cat_details->font_color);
                    }
                }

                $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));
                $close_dt = $event->end_date . " " . $event->end_time;
                $today = strtotime($current_dt);
                $stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
                $expiration_date = strtotime($stp);

                $imgSrc = $event->image_link;
                if ($event->image_link == "") {
                    $imgSrc = $this->assetUrl('images/calendar-icon.png');
                } else {
                    $imgSrc = EventPlus_Helpers_Event::getThumbnailAttachment($imgSrc);
                }

                $event_link = evrplus_permalink($company_options['evrplus_page_id']) . 'action=evrplusegister&event_id=' . $event->id . ( ($curr) ? '&recurr=' . $curr : '' );
                /*if( isset($event->outside_reg) && $event->outside_reg == "Y" ) {
                    $event_link = $event->external_site;
                }*/

                $date_format = "M j, Y";
                $time_start = $event->start_time;
                $time_end = $event->end_time;
                $opt = EventPlus_Models_Settings::getSettings();

                if (isset($opt['date_format']) && $opt['date_format'] == 'eur') {
                    $date_format = "j M Y";
                }

                if (isset($opt['time_format']) && $opt['time_format'] == '24hrs') {
                    $time_start = date_i18n('H:i', strtotime($event->start_time));
                    $time_end = date_i18n('H:i', strtotime($event->end_time));
                }

                $startDate = ($curr) ? date_i18n($date_format, $curr) : date_i18n($date_format, strtotime($event->start_date));
                $endDate = ($curr) ? date_i18n($date_format, $curr) : date_i18n($date_format, strtotime($event->end_date));
                $event_name = stripslashes($event->event_name);

                $icoDate = '';
                if ($event->end_date == $event->start_date) {
                    $icoDate = $startDate;
                } else {
                    $icoDate = $startDate . ' - ' . $endDate;
                }

                $icoTimeEnd = ($event->end_date == $event->start_date) ? ' - ' . $time_end : '';
                ?>

                <div class="i8em" style="border-right-color: <?php echo $style_event_catgry; ?>;">
                    <div class="col-lg-2 col-sm-3 col-xs-4 t7umb">
                        <a href="<?php echo $event_link; ?>"><img src="<?php echo $imgSrc; ?>" alt="<?php echo $event_name; ?>"></a>
                        <?php
                        if ($company_options['show_num_seats'] !== 'no'):

                            $sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE payment_status = 'success' AND event_id='$event->id'";

                            $num = 0;

                            $attendee_count = $wpdb->get_var($sql2);
                            If ($attendee_count >= 1) {
                                $num = $attendee_count;
                            }

                            $available_spaces = 0;
                            if ($event->reg_limit != "") {
                                $available_spaces = $event->reg_limit - $num;
                            }

                            if (!isset($event->reg_limit) or empty($event->reg_limit) or $event->reg_limit == 999999) {
                                $available_spaces = __("Unlimited", 'evrplus_language');
                            }
                            ?>
                            <div class="sea8s hidden">
                                <?php echo __('Open Seats', 'evrplus_language'); ?>
                                <label style="background-color: <?php echo $style_event_catgry; ?>; color: <?php echo $event_catgry_fnt_clr; ?>;"><?php echo $available_spaces; ?></label>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-10 col-sm-9 col-xs-8">
                        <h2 class="ti8le"><a href="<?php echo $event_link; ?>" title="<?php echo $event_name; ?>"><?php echo $event_name; ?></a></h2>
                        <div class="me8a">
                            <span> <i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $icoDate; ?></span>
                            <span> <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo $time_start; ?> <?php echo $icoTimeEnd; ?></span>
                            <?php /* <span>
                              <i class="fa fa-map-marker" aria-hidden="true"></i>
                              8276 Walnut Blvd. Jonesboro. GA. 30238
                              </span> */ ?>
                        </div>
                        <p class="d3sc"><?php echo evrplus_Truncate(strip_tags(html_entity_decode(stripslashes($event->event_desc))), 150, ' '); ?></p>

                        <?php
                        do_action( 'evrplus_after_event_list_description', $event, array(
                            'event_url' => $event_link,
                            'seats' => $available_spaces
                        ) ); ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
<?php
endif;