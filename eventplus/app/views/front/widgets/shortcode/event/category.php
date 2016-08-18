<?php

$category_id = $categoryRow['id'];

$category_name = stripslashes(htmlspecialchars_decode($categoryRow['category_name']));

$category_identifier = stripslashes(htmlspecialchars_decode($categoryRow['category_identifier']));

$category_desc = stripslashes(htmlspecialchars_decode($categoryRow['category_desc']));

$display_category_desc = $categoryRow['display_desc'];

$category_color = $categoryRow['category_color'];

$font_color = $categoryRow['font_color'];



//echo "<p><strong>" . $category_name . "</strong><br />" . $category_desc . "</p>";



$color_row = "1";

$month_no = $end_month_no = '01';

$start_date = $end_date = '';

?>

<div class="event-cont">

    <table summary="<?php _e('The list of upcoming events.', 'evrplus_language'); ?>">

        <?php

        foreach ($events as $row) {

            $event_id = $row['id'];

            $event_name = stripslashes(htmlspecialchars_decode($row ['event_name']));

            $event_identifier = stripslashes(htmlspecialchars_decode($row ['event_identifier']));

            $event_desc = stripslashes(htmlspecialchars_decode($row ['event_desc']));

            $start_date = $row['start_date'];

            $end_date = $row['end_date'];

            $start_month = $row ['start_month'];

            $start_day = $row ['start_day'];

            $start_year = $row ['start_year'];

            $end_month = $row ['end_month'];

            $end_day = $row ['end_day'];

            $end_year = $row ['end_year'];

            $start_time = $row ['start_time'];

            $end_time = $row ['end_time'];

            $outside_reg = $row['outside_reg'];  // Yor N

            $external_site = $row['external_site'];



            $cat_array = unserialize($row['category_id']);

            $cat_id = $cat_array[0];





            $style_event_catgry = '#999';

            if ($categoryRow['id'] > 0) {

                $category_identifier = $categoryRow['category_identifier'];

                if ($category_identifier != '') {

                    $style_event_catgry = ($categoryRow['category_color']);

                }

            }



            $sql2 = "SELECT SUM(quantity) FROM " . get_option('evr_attendee') . " WHERE  payment_status = 'success' AND event_id='" . (int) $event_id . "'";

            $attendeesRs = $this->wpDb()->get_results($sql2, ARRAY_N);



            $num = 0;

            foreach ($attendeesRs as $rowAttendee) {

                $num = $rowAttendee[0];

            }



            $available_spaces = 0;

            if ($row['reg_limit'] != "") {

                $available_spaces = $row['reg_limit'] - $num;

            }



            if (!isset($row['reg_limit']) || empty($row['reg_limit']) || $row['reg_limit'] == 999999) {

                $available_spaces = "UNLIMITED";

            }



            $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));

            $close_dt = $end_date . " " . $end_time;

            $today = strtotime($current_dt);

            $stp = date("Y-m-d H:i", strtotime($close_dt));

            $expiration_date = strtotime($stp);



            $td_class = '';

            if ($color_row == 1) {

                $td_class = "odd";

            } else if ($color_row == 2) {

                $td_class = "even";

            }

            ?>

            <tr>

                <td class="row <?php echo $td_class; ?>" style="border-right: 8px solid <?php echo $style_event_catgry ?>">

                    <div class="col-sm-7 eve-details">



                        <?php

                        if ($row['image_link'] == "") {

                            ?>

                            <div class="thumb" style="background-image: url(<?php echo $this->assetUrl('images/calendar-icon.png'); ?>); "></div>

                            <div class="eve-title">

                                <?php

                            } else {

                                ?>

                                <div class="thumb" style="background-image: url(<?php echo $row['image_link'] ?>);"></div>

                                <div class="eve-title">

                                    <?php

                                }



                                #Check to see if link only in company settings



                                $recurr = EventPlus_Helpers_Event::check_recurrence($event_id);

                                $parms = array('action' => 'evrplusegister', 'event_id' => $event->id);

                                if ($recurr) {

                                    $parms['recurr'] = $recurr;

                                }



                                if ($company_options['evrplus_list_format'] == "link") {



                                    if ($outside_reg == "Y") {

                                        ?>

                                        <h3><a href="<?php echo $row['external_site'] ?>"><?php echo $event_name; ?></a></h3></div>

                                    <?php

                                } else {

                                    ?>

                                    <h3><a href="<?php echo add_query_arg($parms, get_permalink(get_page_by_path('evrplus_registration'))); ?>"><?php echo $event_name; ?></a></h3></div>

                                <?php

                            }

                        } else {



                            if ($outside_reg == "Y") {

                                ?>

                                <h3><a href="<?php echo $row['external_site']; ?>"><?php echo $event_name; ?></a></h3></div>

                            <?php

                        } else {

                            ?>

                            <h3><a href="<?php echo evrplus_permalink($company_options['evrplus_page_id']); ?>action=evrplusegister&event_id=<?php echo $event_id . ( ($recurr) ? '&recurr=' . $recurr : '' ) ?>"><?php echo $event_name; ?></a></h3></div>

                            <?php

                        }

                    }



                    $date_format = "M j, Y";

                    $time_start = $row['start_time'];

                    $time_end = $row['end_time'];

                    if ($opt = EventPlus_Models_Settings::getSettings()) {

                        if (isset($opt['date_format']) && $opt['date_format'] == 'eur')

                            $date_format = "j M Y";

                        if (isset($opt['time_format']) && $opt['time_format'] == '24hrs') {

                            $time_start = date('H:i', strtotime($row['start_time']));

                            $time_end = date('H:i', strtotime($row['end_time']));

                        }

                    }

                    ?>

                    <div class="eve-desc">
                      
                        <p><?php echo EventPlus_Helpers_Funx::truncate(html_entity_decode(stripslashes($event_desc)), 60, ' ') ?></p>

                        <p><?php _e('Open Seats', 'evrplus_language') ?> 

                            <span class="seats" style="background-color: <?php echo $style_event_catgry ?>"><?php echo $available_spaces; ?></span>

                        </p>

                    </div> 



                    </div>



                    <div class="col-sm-4 timing">

                        <div class="time-cont">

                            <div class="eve-start">

                                <time datetime="2014-09-20" class="icon">

                                    <em><?php _e(date("l", ($recurr) ? $recurr : strtotime($row['start_date']))); ?></em>

                                    <strong><?php _e(date("F", ($recurr) ? $recurr : strtotime($row['start_date']))); ?></strong>

                                    <span><?php _e(date("j", ($recurr) ? $recurr : strtotime($row['start_date']))); ?></span>

                                </time>

                                <p style="position: relative;">

                                    <img src="<?php echo $this->assetUrl('images/popup-time-icon.png'); ?>" style="  float: left;margin-right: 3px;margin-top: 2px;">

                                    <?php echo $start_time . ( ($row['end_date'] == $row['start_date']) ? ' - ' . $end_time : '' ); ?></p>

                            </div><?php

                            #Check to see if the start date and end date are the same, if they are don''t display end date, only time



                            if ($row['end_date'] != $row['start_date']) {

                                ?>

                                <span class="eve-sap">-</span>

                                <div class="eve-end">

                                    <time datetime="2014-09-20" class="icon">

                                        <em><?php _e(date("l", ($recurr) ? $recurr : strtotime($row['end_date']))); ?></em>

                                        <strong><?php _e(date("F", ($recurr) ? $recurr : strtotime($row['end_date']))); ?></strong>

                                        <span><?php _e(date("j", ($recurr) ? $recurr : strtotime($row['end_date']))); ?></span>

                                    </time>

                                    <p><img src="<?php echo $this->assetUrl('images/popup-time-icon.png'); ?>" style="  float: left;margin-right: 3px;margin-top: 2px;"> <?php echo $end_time; ?></p>

                                </div> <?php } ?>



                        </div>

                    </div>

                </td>

            </tr>

        <?php }  ?>

    </table>

</div>



<?php /*@popup disabled*/ ?>
