<?php
extract($invoke_params);

if (count($rows)):
    ?>



    <div class="grid-section">
        <div class="content grid-container">
            <?php if ($cats): ?>
                <div class="filters-container">
                    <input type="text" id="evr-search" class="media-boxes-search" placeholder="<?php _e('Search By Title', 'evrplus_language'); ?>">
                    <ul class="media-boxes-filter" id="evr-filter">
                        <li><a class="selected" href="#" data-filter="*"><?php _e('All', 'evrplus_language'); ?></a></li>
                        <?php foreach ($categories as $cat) { ?>
                            <li><a href="#" data-filter=".<?php echo $cat->category_identifier; ?>"><?php echo $cat->category_name; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div id="evr-grid" data-boxesToLoadStart="<?php echo $init_events; ?>" data-boxesToLoad="<?php echo $init_events; ?>">

                <?php
                foreach ($rows as $event) :
                    if ($ordered != 'yes')
                        $height = rand(150, 300);
                    $this_cats = maybe_unserialize($event->category_id);
                    $temp = '';
                    $curr = EventPlus_Helpers_Event::check_recurrence($event->id);
                    $parms = array('action' => 'evrplusegister', 'event_id' => $event->id);
                    if ($curr) {
                        $parms['recurr'] = $curr;
                    }

                    $link = add_query_arg($parms, get_permalink(get_page_by_path('evrplus_registration')));
                    $d_format = date_i18n($evrplus_date_format, strtotime($event->start_date));
                    if ($curr) {
                        $d_format = date_i18n($evrplus_date_format, $curr);
                    }

                    $start_time = $event->start_time;
                    if (isset($company_options['time_format']) && $company_options['time_format'] == '24hrs') {
                        $start_time = date('H:i', strtotime($start_time));
                    }

                    $catStr = '';
                    if (is_array($this_cats)) {
                        foreach ($this_cats as $cat) {
                            $catStr .= EventPlus_Helpers_Event::get_category_identifier_by_id($cat) . ' ';
                        }
                    }
                    ?>
                    <div class="media-box <?php echo $catStr; ?>" data-columns="<?php echo $col; ?>">

                        <?php
                        $defaultImage = $this->assetUrl('images/calendar-icon.png');
                        ?>
                        <div class="media-box-image">
                            <div data-thumbnail="<?php echo ($event->image_link) ? $event->image_link : $defaultImage; ?>">
                            </div>
                            <div class="thumbnail-overlay">
                                <a href="<?php echo EventPlus_Helpers_Event::permalink($company_options['evrplus_page_id']); ?>action=evrplusegister&event_id=<?php echo $event->id . ( ($recurr) ? '&recurr=' . $recurr : '' ) ?>">
                                    <i class="fa fa-link"></i>
                                </a>
                            </div>
                        </div>

                        <div class="media-box-content" style="background-color: #f5f5f5;">
                            <div class="media-box-title"><?php echo $event->event_name; ?></div>
                            <div class="media-box-date"><span style="font-size:15px;color: #666;" class="dashicons dashicons-calendar-alt"></span><?php echo $d_format; ?></div>
                            <div class="media-box-date"><span style="font-size:15px;color: #666;" class="dashicons dashicons-clock"></span><?php echo $start_time; ?></div>
                            <div class="media-box-text">
                                <?php
                                $content = html_entity_decode($event->event_desc);

                                echo $con = substr(strip_tags(stripslashes($content)), 0, 110) . "...";
                                //echo EventPlus_Helpers_Funx::truncateGrid(html_entity_decode(stripslashes($event->event_desc)), 60, ' '); 
                                ?>
                            </div>
                            <div class="media-box-more"><a href="<?php echo EventPlus_Helpers_Event::permalink($company_options['evrplus_page_id']); ?>action=evrplusegister&event_id=<?php echo $event->id . ( ($recurr) ? '&recurr=' . $recurr : '' ) ?>">Read more</a> </div>
                        </div>
                    </div>
    <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
 endif; 
