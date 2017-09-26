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
        <span><?php _e('Event Payments', 'evrplus_language'); ?> - <strong><?php echo stripslashes($event_name); ?></strong></span>
        <?php if ($total_items): ?> <br /><br />
            <a class="btn btn-small btn-primary" onclick="return confirm('Are you sure you wish to send email reminder?');" href="<?php echo $this->adminUrl('admin_payments/email_reminder', array('event_id' => $oEvent->id)); ?>"><?php _e('Email Payment Reminders', 'evrplus_language'); ?></a>
        <?php endif; ?>
    </h3>     


    <table class="widefat">

        <thead>
        <thead>
            <tr>
                <th><?php _e('# People', 'evrplus_language'); ?></th>
                <th><?php _e('Name', 'evrplus_language'); ?> </th>
                <th><?php _e('Total', 'evrplus_language'); ?></th>
                <th><?php _e('Order Detail', 'evrplus_language'); ?></th>
                <th><?php _e('Payments', 'evrplus_language'); ?></th>
                <th><?php _e('Action', 'evrplus_language'); ?></th>
            </tr>
        </thead>
        </thead>

        <tbody>

            <?php if ($total_items): ?>

                <?php
                foreach ($rows as $row) {
                    $attendee_id = $row['id'];
                    $lname = $row ['lname'];
                    $fname = $row ['fname'];
                    $address = $row ['address'];
                    $city = $row ['city'];
                    $state = $row ['state'];
                    $zip = $row ['zip'];
                    $email = $row ['email'];
                    $phone = $row ['phone'];
                    $quantity = $row ['quantity'];
                    $date = $row ['date'];
                    $reg_type = $row['reg_type'];
                    $ticket_order = unserialize($row['tickets']);
                    $payment = $row['payment'];
                    $event_id = $row['event_id'];
                    $coupon = $row['coupon'];

                    $sql3 = "SELECT * FROM " . get_option('evr_payment') . " WHERE payer_id='$attendee_id' GROUP BY txn_id";
                    
                    $payments = $this->wpDb()->get_results($sql3, ARRAY_A);
                    ?>
                    <tr>
                        <td>
                            <?php echo $quantity; ?>
                        </td>
                        <td align='left'>
                            <?php echo $lname . ", " . $fname; ?>
                        </td>
                        <td>
                            <?php echo $payment; ?>
                        </td>
                        <td>
                            <?php
                            $row_count = count($ticket_order);
                            for ($row = 0; $row < $row_count; $row++) {
                                echo $ticket_order[$row]['ItemQty'] . " " . $ticket_order[$row]['ItemCat'] . "-" . $ticket_order[$row]['ItemName'] . " " . $ticket_order[$row]['ItemCurrency'] . " " . $ticket_order[$row]['ItemCost'] . "<br \>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $totalPaid = 0;
                            if (count($payments) > 0):
                                ?>
                                <?php
                                foreach ($payments as $paymentRow):

                                    $deleteLink = $this->adminUrl('admin_payments/delete', array('event_id' => $event_id, 'id' => $paymentRow['id']));
                                    $editLink = $this->adminUrl('admin_payments/edit', array('event_id' => $event_id, 'id' => $paymentRow['id']));
                                    $viewLink = $this->adminUrl('admin_payments/view', array('event_id' => $event_id, 'id' => $paymentRow['id']));

                                    $totalPaid = $totalPaid + $paymentRow['mc_gross'];
                                    echo $paymentRow['payment_status'] . " - " . $paymentRow['mc_currency'] . " " . $paymentRow['mc_gross'] . " " . $paymentRow['txn_type'] . " " . $paymentRow['txn_id'] . " (" . $paymentRow['payment_date'] . ")" . "     ";
                                    ?>
                                    <?php if (EventPlus_Models_Payments::isValidMethod($paymentRow['txn_type']) == false): ?>
                                        <a href="<?php echo $deleteLink; ?>" onclick="return confirm('Are you sure you wish to delete?');"><?php _e('Delete', 'evrplus_language'); ?></a> | 
                                        <a href="<?php echo $editLink; ?>"><?php _e('Edit', 'evrplus_language'); ?></a> <br />
                                    <?php else: ?>
                                        <a href="<?php echo $viewLink; ?>"><?php _e('View Details', 'evrplus_language'); ?></a> 
                                    <?php endif; ?>
                       
                                    <br />

                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php
                                echo '<font color="red">' . _e('No Payments Received!', 'evrplus_language') . '</font>';
                                ?>
                            <?php endif; ?>
                        </td>
                        <td>

                            <?php if ($totalPaid != $payment): ?>
                                <a href="<?php echo $this->adminUrl('admin_payments/add', array('event_id' => $event_id, 'attendee_id' => $attendee_id)); ?>" class="btn btn-small btn-warning"><?php _e('Add Payment', 'evrplus_language'); ?></a>
                            <?php else: ?>
                                -
                            <?php endif; ?>

                        </td>
                    </tr>

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

            <div style="float:left;">
                <form method="POST" action="<?php echo $this->adminUrl('admin_payments/export', array('event_id' => $oEvent->id, 'type' => 'csv')); ?>">
                    <input class="xls_btn" type="submit" value="Export Details - CSV"/>
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

