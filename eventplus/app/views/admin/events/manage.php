<?php
$total_items = count($rows);
if(empty($_REQUEST['sort'])){
	$_REQUEST['sort'] = "";
}
if(empty($_REQUEST['sort_direction'])){
	$_REQUEST['sort_direction'] = "";;
}
?>
<div class="padding">
    <div class="tablenav">
        <div class='tablenav-pages'>
            <?php
            if ($total_items > 0) {
                echo $p->show();
            }
            ?>
        </div>
    </div>
    <?php if ($total_items > 0) : ?>
        <p class="sort"> <?php _e('Sort By', 'evrplus_language'); ?> &nbsp; 
            <select name= "event_sort" class="event_sort event_sort_field">
                <option value=""> <?php _e('Select', 'evrplus_language'); ?> </option>
                <option value="id" <?php if ($_REQUEST['sort'] == 'id') echo 'selected="selected"' ?>> <?php _e('ID', 'evrplus_language'); ?></option>
                <option value="start_date" <?php if ($_REQUEST['sort'] == 'start_date') echo 'selected="selected"' ?>><?php _e('Date', 'evrplus_language'); ?></option>
            </select>
            <select name= "sort_direction" class="event_sort sort_direction">
                <option value="asc" <?php if ($_REQUEST['sort_direction'] == 'asc') echo 'selected="selected"' ?>> <?php _e('ASC', 'evrplus_language'); ?></option>
                <option value="desc" <?php if ($_REQUEST['sort_direction'] == 'desc') echo 'selected="selected"' ?>><?php _e('DESC', 'evrplus_language'); ?></option>
            </select>
        </p>
    <?php endif; ?>
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e('Event ID', 'evrplus_language'); ?></th>
                <th><?php _e('Start Date', 'evrplus_language'); ?></th>
                <th><?php _e('Name', 'evrplus_language'); ?></th>
                <th><?php _e('Category', 'evrplus_language'); ?></th>
                <th><?php _e('ShortCode', 'evrplus_language'); ?></th>
                <th><?php _e('Status', 'evrplus_language'); ?></th>
                <th><?php _e('Attendees', 'evrplus_language'); ?></th>
                <th><?php _e('Manage', 'evrplus_language'); ?></th>
                <th><?php _e('Action', 'evrplus_language'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($total_items) {
                foreach ($rows as $event) {
                    $event_id = (int) $event->id;
                    $event_name = stripslashes($event->event_name);
                    if ((get_option('evr_location_active') == "Y") && ( $event->location_list >= '1')) {
                        $sql = "SELECT * FROM " . get_option('evr_location') . " WHERE id =" . $event->location_list;
                        $location = $this->wpDb()->get_row($sql, OBJECT); //default object
                        if (!empty($location)) {
                            $event_location = stripslashes($location->location_name);
                            $event_address = $location->street;
                            $event_city = $location->city;
                            $event_state = $location->state;
                            $event_postal = $location->postal;
                            $event_phone = $location->phone;
                        }
                    } else {
                        $event_location = stripslashes($event->event_location);
                        $event_address = $event->event_address;
                        $event_city = $event->event_city;
                        $event_postal = $event->event_postal;
                    }
                    $reg_limit = $event->reg_limit;
                    $start_time = $event->start_time;
                    $end_time = $event->end_time;
                    $conf_mail = $event->conf_mail;
                    $start_date = $event->start_date;
                    $end_date = $event->end_date;
                    $event_close = $event->close;
                    $close_dt = $event->close;
                    $number_attendees = new EventPlus_Models_Attendees;
					$number_attendees = $number_attendees->numberOfSuccessfulAttendees($event_id);
                    if ($number_attendees == '' || $number_attendees == 0) {
                        $number_attendees = '0';
                    }
                    if ($reg_limit == "" || $reg_limit == " " || $reg_limit == 999999) {
                        $reg_limit = "Unlimited";
                    }
                    $available_spaces = $reg_limit;
                    $current_dt = date('Y-m-d H:i', current_time('timestamp', 0));

                    if ($event->recurrence_choice == 'yes') {
                        $dateTime = new DateTime('2030-7-15 8:30pm');
                        $expiration_date = $dateTime->format("U");
                    } else {
                        $stp = DATE("Y-m-d H:i", STRTOTIME($end_date));
                        $expiration_date = strtotime($stp);
                    }

                    $close_dt = $end_date . " " . $end_time;

                    $today = strtotime($current_dt);
                    if (strtotime($close_dt)) {
                        $dateTime = new DateTime($close_dt);
                        $expiration_date = $dateTime->format("U");
                    }
                    if ($expiration_date <= $today) {
                        $active_event = '<span class="event_ex">' . __('EXPIRED', 'evrplus_language') . '</span>';
                    } else {
                        $active_event = '<span class="event_ac">' . __('ACTIVE', 'evrplus_language') . '</span>';
                    }
                    ?>
                    <tr>
                        <td><?php echo $event_id; ?></td>
                        <td style="white-space: nowrap;"><?php echo $start_date; ?></td>
                        <td>
                            <a href="<?php echo EventPlus::factory('Helpers_Event')->permalink($company_options['evrplus_page_id']) . "action=evrplusegister&event_id=" . $event_id ?>" target="_blank"><?php echo EventPlus_Helpers_Funx::truncateWords($event_name, 8, "..."); ?></a>
                            <br />
                            <?php echo $event_location; ?><?php echo ", " . $event_city; ?>
                        </td>
                        <td  class="cname">
                            <?php if (is_array($event->category_id) && count($event->category_id)): ?>
                                <?php
                                foreach ($event->category_id as $d => $cid):

                                    $catRow = $event_category_dataset[$cid];
                                    $category_name = stripslashes(htmlspecialchars_decode($catRow['category_name']));
                                    $category_color = $catRow['category_color'];
                                    $font_color = $catRow['font_color'];
                                    $style = "padding:5px; text-wrap:none; margin-bottom:2px;  background-color:" . $category_color . " ; color:" . $font_color . " ;";
                                    ?>
                                    <div style="<?php echo $style; ?>"><?php echo $category_name; ?></div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td style="white-space: nowrap;">[eventsplus_single event_id="<?php echo $event_id; ?>"] </td>
                        <td><?php echo $active_event; ?></td>
                        <td><?php echo $number_attendees; ?> / <?php echo $reg_limit; ?></td>
                        <td>
                            <div class="btn-group grid-actions">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php _e('Manage', 'evrplus_language'); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="fees"><a href="<?php echo $this->adminUrl('admin_events_items', array('event_id' => $event_id)) ?>"><?php _e('Fees/Items', 'evrplus_language'); ?></a></li>
                                    <li class="questions"><a href="<?php echo $this->adminUrl('admin_questions', array('event_id' => $event_id)) ?>"><?php _e('Questions', 'evrplus_language'); ?></a></li>
                                    <li class="attendees"><a href="<?php echo $this->adminUrl('admin_attendees', array('event_id' => $event_id)) ?>"><?php _e('Attendees', 'evrplus_language'); ?></a></li>
                                    <li class="payments"><a href="<?php echo $this->adminUrl('admin_payments', array('event_id' => $event_id)) ?>"><?php _e('Payments', 'evrplus_language'); ?></a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group grid-actions">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php _e('Action', 'evrplus_language'); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                    <li class="edit"><a href="<?php echo $this->adminUrl('admin_events', array('method' => 'edit', 'id' => $event_id)) ?>"><?php _e('Edit', 'evrplus_language'); ?></a></li>
                                    <li class="copy"><a href="<?php echo $this->adminUrl('admin_events', array('method' => 'copy', 'id' => $event_id)) ?>" onclick="return confirm('<?php _e('Are you sure you want to copy', 'evrplus_language'); ?> <?php echo $event_name ?>?')"><?php _e('Copy', 'evrplus_language'); ?></a></li>
                                    <li class="delete"><a href="<?php echo $this->adminUrl('admin_events', array('method' => 'delete', 'id' => $event_id)) ?>" id="delete_event-<?php echo $event_id ?>" onclick="return confirm('<?php _e('Are you sure you want to delete', 'evrplus_language'); ?> <?php echo $event_name ?>?')"><?php _e('Delete', 'evrplus_language'); ?></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <?php
                }
            } else {
                ?>
                <tr>
                    <td>No events found!</td>
                <tr>
                <?php } ?>
        </tbody>
    </table>
    <div class="tablenav">
        <div class='tablenav-pages'>
            <?php
            if ($total_items > 0) {
                echo $p->show();
            }  // Echo out the list of paging.  
            ?>
        </div>
    </div>
</div>
