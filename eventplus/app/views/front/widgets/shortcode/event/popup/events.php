<?php /** * @author David Fleming * @copyright 2011 */ ?><!-- Main Page Event PopUp Start -->
<div style="display:none">
    <div id="event_content_<?php echo $event_id; ?>"><!--<div id="popup<?php echo $event_id; ?>" class="poplight"> -->
        <div id="evrplus_pop_top"><span style="float:center;">
                <?php if ($header_image != "") { ?> <img class="evrplus_pop_hdr_img" src="<?php echo $header_image; ?>" /><?php } ?></span>
        </div>
        <div id="evrplus_pop_title"><span style="float:left;"><h3><?php echo $event_name; ?></h3></span>     
            <span style="float:right;"><a href="<?php echo EVR_PLUGINFULLURL . "evrplus_ics.php"; ?>?event_id=<?php echo $event_id; ?>">                        <img src="<?php echo EVR_PLUGINFULLURL; ?>images/ical-logo.jpg" /></a></span>                        </div><div id="evrplus_pop_date_row" class="evrplus_pop_date"><?php
            echo "<br/>" . date($evrplus_date_format, strtotime($start_date)) . "  -  ";
            if ($end_date != $start_date) {
                echo date($evrplus_date_format, strtotime($end_date));
            } echo __('Time: ', 'evrplus_language') . " " . $start_time . " - " . $end_time;
            ?>                        </div><?php $url = urlencode(add_query_arg(array('action' => 'evrplusegister', 'event_id' => $event_id), get_permalink(get_page_by_path('evrplus_registration')))); ?>			 <span style="float:right;"><a target="_blank" href="https://twitter.com/home?status=<?php echo $url; ?>"><img style="margin-right:15px;" class="" src="<?php echo EVR_PLUGINFULLURL . 'images/twitter-share-btn.png'; ?>" /></a></span>			 			 <span style="float:right;"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>"><img style="margin-right:15px;" class="" src="<?php echo EVR_PLUGINFULLURL . 'images/facebook-share-btn.png'; ?>" /></a></span><div class="evrplus_spacer"></div> <div id="evrplus_pop_body" STYLE="text-align: justify;white-space:pre-wrap;"><?php echo html_entity_decode($event_desc); ?></div><div id="evrplus_pop_image"><?php if ($image_link != "") { ?><img class="evrplus_pop_img" src="<?php echo $image_link; ?>" alt="Thumbnail Image" /><?php } else { ?>                        <img class="evrplus_pop_img" src="<?php echo EVR_PLUGINFULLURL; ?>images/event_icon.png" />                        <?php } ?>                        </div>                                              <div class="evrplus_spacer"><hr /></div>  <div id="evrplus_pop_venue"><div id="evrplus_pop_address"><b><u>Location</u></b><br/><br/>                        <?php echo stripslashes($event_location); ?><br />                        <?php echo $event_address; ?><br />                        <?php echo $event_city . ", " . $event_state . " " . $event_postal; ?><br />                        </div><div id="evrplus_pop_map"><?php if ($google_map == "Y") { ?><!--                        <img border="0" src="http://maps.google.com/maps/api/staticmap?center=<?php echo $event_address . "," . $event_city . "," . $event_state; ?>&zoom=14&size=280x180&maptype=roadmap&markers=size:mid|color:0xFFFF00|label:*|<?php echo $event_address . "," . $event_city; ?>&sensor=false" />-->                        <?php
                            $event_address_map = str_replace(" ", "+", $event_address);
                            $event_city_map = str_replace(" ", "+", $event_city);
                            $event_state_map = str_replace(" ", "+", $event_state);
                            ?>						<iframe					width="282"					height="200"					frameborder="0" 					style="border:5px solid #fff;border-radius:15px;"					src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDblf6OIl46COqBYUo2DBaxo0-PRl9SZEM&q=<?php echo $event_address_map; ?>,<?php echo $event_city_map; ?>,<?php echo $event_state_map; ?>">				</iframe>												<?php } ?>                        </div></div>		                          <div id="evrplus_pop_price"><b><u><?php _e('Event Fees', 'evrplus_language'); ?>:</u></b><br /><br />                        <?php
                                                        $curdate = date("Y-m-d");
                                                        $sql2 = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id . " ORDER BY sequence ASC";
                                                        global $wpdb;
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
                                                            } if ($item_custom_cur == "USD") {
                                                                $item_custom_cur = "$";
                                                            } echo $item_title . '   ' . $item_custom_cur . ' ' . $item_price . '<br />';
                                                        }
                                                        ?>                                                
        </div>
        <div class="evrplus_spacer"></div>
        <div id="evrplus_pop_foot">
            <p align="center"><?php if ($more_info != "") { ?>
                    <input type="button" onClick="window.open('<?php echo $more_info; ?>');" value='MORE INFO'/> 
                <?php } ?>
                <?php if ($outside_reg == "Y") { ?>
                    <input type="button" onClick="window.open('<?php echo $external_site; ?>');" value='External Registration'/> 
                <?php } else { ?>                        
                    <input class="register_now_button" type="button" onClick="location.href = '<?php echo evrplus_permalink($company_options['evrplus_page_id']); ?>action=evrplusegister&event_id=<?php echo $event_id; ?>'" value='REGISTER'/>  
                <?php } ?>                                                                                             
            </p></div>               		                        
    </div></div><!-- EventPopUpEnd -->	