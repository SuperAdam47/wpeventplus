<?php
$total_items = count($rows);
$event_name = $oEvent->event_name;
$event_id = $oEvent->id;
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
        <span><?php _e('Manage Attendees', 'evrplus_language'); ?> - <strong><?php echo stripslashes($event_name); ?></strong></span>
        <?php if ($total_items): ?> <br /><br />
            <a class="btn btn-small btn-primary" onclick="return confirm('Are you sure you wish to delete all attendees under <?php echo stripslashes($event_name); ?>?');" href="<?php echo $this->adminUrl('admin_attendees/delete_all', array('event_id' => $oEvent->id)); ?>"><?php _e('Delete All', 'evrplus_language'); ?></a>
        <?php endif; ?>
    </h3>     


    <table class="widefat">

        <thead>
            <tr>
                <th><?php _e('Type', 'evrplus_language'); ?></th>
                <th><?php _e('People', 'evrplus_language'); ?></th>
                <th><?php _e('Registered Name', 'evrplus_language'); ?> </th>
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
                    $event_id = (int) $question->event_id;

                    echo "<tr>"
                    . "<td>" . $attendee->reg_type . "</td>"
                    . "<td>" . $attendee->quantity . "</td><td align='left'>" . $attendee->lname . ", " . $attendee->fname . " ( ID: " . $attendee->id . ")</td><td>";

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
                    if($payment_status == 'Pending' && ($attendee->payment) === ($attendee->amount_pd)){
                        $payment_status = "Success";
                    }
                    ?>
                    <?php if (strtolower($payment_status) == 'success'): ?>
                        <span class='label  label-success'><?php echo ucfirst($payment_status); ?></span>
                    <?php else: ?>
                        <span class='label label-warning'><?php echo $payment_status; ?></span>
                    <?php endif; ?>
                </td>
                <td>    
                    <?php if ($payment_status != 'success'): ?>
                        <a href="<?php echo $this->adminUrl('admin_attendees/edit', array('event_id' => $oEvent->id, 'attendee_id' => $attendee->id)); ?>" id="update_button1"><?php _e('Edit', 'evrplus_language'); ?></a>
                    <?php endif; ?>
                    <a href="<?php echo $this->adminUrl('admin_attendees/details', array('event_id' => $oEvent->id, 'attendee_id' => $attendee->id)); ?>" id="update_button1"><?php _e('View', 'evrplus_language'); ?></a>
                    <br style="clear:both;" /><br />


                        <a id="delete_button" href="<?php echo $this->adminUrl('admin_attendees/delete', array('event_id' => $oEvent->id, 'attendee_id' => $attendee->id)); ?>" 
                           onclick="return confirm('Are you sure you want to delete attendee <?php echo $attendee->fname . " " . $attendee->lname; ?>?')"><?php _e('Delete', 'evrplus_language'); ?></a>
            
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
    <?php if ($total_items): ?>
        <br />
        <div style="float:left; margin-right:20px;">
            <form method="POST" action="<?php echo $this->adminUrl('admin_attendees/export', array('event_id' => $oEvent->id, 'type' => 'xls')); ?>">
                <input class="xls_btn" type="submit" value="Export Details - Excel"/>
            </form>
        </div>
        <div style="float:left;">
            <form method="POST" action="<?php echo $this->adminUrl('admin_attendees/export', array('event_id' => $oEvent->id, 'type' => 'csv')); ?>">
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

