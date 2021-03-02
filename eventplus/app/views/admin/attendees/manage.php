<?php
$total_items = count($rows);
$event_name = '';
$_event_id = 0;

if (is_object($oEvent)) {
    $event_name = $oEvent->event_name;
    $_event_id = $oEvent->id;
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

    <h3>
        <span><?php _e('Manage Attendees', 'evrplus_language'); ?> <?php if ($event_name != ''): ?>- <strong><?php echo stripslashes($event_name); ?></strong><?php endif; ?></span>
         <?php if ($total_items && $_event_id > 0): ?> <br /><br />
            <a class="btn btn-small btn-primary" onclick="return confirm('Are you sure you wish to delete all attendees under <?php echo stripslashes($event_name); ?>?');" href="<?php echo $this->adminUrl('admin_attendees/delete_all', array('event_id' => $oEvent->id)); ?>"><?php _e('Delete All', 'evrplus_language'); ?></a>
        <?php endif; ?>
    </h3>     

    <?php
    $events = EventPlus_Helpers_Event::comboDataset();
    if (count($events)):
        ?>
        <p class="sort">
            <select name="event_id" class="event_id_filter" data-current-uri="eventplus_admin_attendees">
                <option value=""> <?php _e('All', 'evrplus_language'); ?> </option>
                <?php foreach ($events as $ei => $eventRow):  ?>
                    <?php 
                    $identifier = '[#'.$eventRow['id'].']';
                    if(trim($eventRow['event_identifier']) != ''){
                        $identifier = '[' . $eventRow['event_identifier'] . ']'; 
                    }
                    
                    ?>
                <option value="<?php echo $eventRow['id']; ?>" <?php if ($_REQUEST['event_id'] == $eventRow['id']) echo 'selected="selected"' ?>> <?php echo $identifier.' - '. stripslashes($eventRow['event_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
    <?php endif; ?>

    <table class="widefat">

        <thead>
            <tr>
                <th><?php _e('Event Name/Title', 'evrplus_language'); ?></th>
                <th><?php _e('People', 'evrplus_language'); ?></th>
                <th><?php _e('Registered Name', 'evrplus_language'); ?></th>
                <th><?php _e('Attendees', 'evrplus_language'); ?></th>
                <th><?php _e('Email', 'evrplus_language'); ?></th>
                <th><?php _e('Phone', 'evrplus_language'); ?></th>
                <th><?php _e('Status', 'evrplus_language'); ?></th>
                <th><?php _e('Action', 'evrplus_language'); ?></th>
            </tr>
        </thead>

        <tbody>

            <?php if ($total_items): ?>

                <?php
                foreach ($rows as $attendee) {

                    $attendee_id = (int) $attendee->id;
                    $event_id = (int) $attendee->event_id;
					echo "<tr>"
                    . "<td><a href='" . $this->adminUrl('admin_events', array('method' => 'edit', 'id' => $attendee->event_id)) . "'>" . $attendee->event_name . "</a></td>"
                    . "<td>" . $attendee->quantity . "</td>"
                    . "<td align='left'>" . $attendee->lname . ", " . $attendee->fname . " ( ID: " . $attendee->id . ")</td><td>";

                    if ($attendee->attendees == "" || $attendee->attendees == "N") {
                        echo "<font color='red'>Please Update This Attendee</font>";
                    } else {
                        $attendee_array = unserialize($attendee->attendees);
                        foreach ($attendee_array as $ma)
                            echo $ma["first_name"] . " " . $ma["last_name"] . ', ';
                    }
                    echo "</td>"
                    . "<td>" . $attendee->email . "</td><td>" . $attendee->phone . "</td>";
                    ?>

                <td>
                    <?php
                    $payment_status = ($attendee->payment_status != null && $attendee->payment_status != '') ? $attendee->payment_status : 'Pending';
                    if ($payment_status == 'Pending' && ($attendee->payment) === ($attendee->amount_pd)) {
                        $payment_status = "Success";
                    }
                    ?>
                   <?php if (strtolower($payment_status) == 'success'): ?>
                        <span class='label  label-success' style="color:#FFF;"><?php echo ucfirst($payment_status); ?></span>
                    <?php else: ?>
                        <span class='label label-warning' style="color:#FFF;"><?php echo $payment_status; ?></span>
                    <?php endif; ?>
                </td>
                <td>    
                    <div class="btn-group grid-actions">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php _e('Action', 'evrplus_language'); ?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <?php if ($payment_status != 'success'): ?>
                                <li class="edit"><a href="<?php echo $this->adminUrl('admin_attendees/edit', array('event_id' => $attendee->event_id, 'attendee_id' => $attendee->id)); ?>"><?php _e('Edit', 'evrplus_language'); ?></a></li>
                            <?php endif; ?>
                            <li class="attendees"><a href="<?php echo $this->adminUrl('admin_attendees/details', array('event_id' => $attendee->event_id, 'attendee_id' => $attendee->id)); ?>"><?php _e('View', 'evrplus_language'); ?></a></li>

                            <li class="delete"><a href="<?php echo $this->adminUrl('admin_attendees/delete', array('event_id' => $attendee->event_id, 'attendee_id' => $attendee->id)); ?>" 
                                                  onclick="return confirm('Are you sure you want to delete attendee <?php echo $attendee->fname . " " . $attendee->lname; ?>?')" id="delete_event-<?php echo $event_id ?>" onclick="return confirm('<?php _e('Are you sure you want to delete', 'evrplus_language'); ?> <?php echo $event_name ?>?')"><?php _e('Delete', 'evrplus_language'); ?></a></li>
                        </ul>
                    </div>


                </td>

                <?php
            }
        else:
            ?>

            <tr>

                <td><?php _e('No records found.', 'evrplus_language'); ?></td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>
    <?php if ($total_items && $_event_id > 0): ?>
        <br />
        <div style="float:left; margin-right:20px;">
            <form method="POST" action="<?php echo $this->adminUrl('admin_attendees/export', array('event_id' => $_event_id, 'type' => 'xls')); ?>">
                <input class="xls_btn" type="submit" value="Export Details - Excel"/>
            </form>
        </div>
        <div style="float:left;">
            <form method="POST" action="<?php echo $this->adminUrl('admin_attendees/export', array('event_id' => $_event_id, 'type' => 'csv')); ?>">
                <input class="csv_btn" type="submit" value="Export Details - CSV"/>
            </form>
        </div>
    <?php endif; ?>
    <div class="tablenav">

        <div class='tablenav-pages'>

            <?php
            if ($total_items > 0) {
                echo $p->show();
            }
            ?>

        </div>

    </div>

</div>

