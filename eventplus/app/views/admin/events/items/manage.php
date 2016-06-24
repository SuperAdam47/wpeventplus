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

        <span><?php _e('Manage Items/Cost', 'evrplus_language'); ?> - <strong><?php echo stripslashes($event_name); ?></strong></span>
        <?php if ($total_items): ?> <br /><br />
            <a class="btn btn-small btn-primary" href="<?php echo $this->adminUrl('admin_events_items/sort', array('event_id' => $oEvent->id)); ?>"><?php _e('Sort Items', 'evrplus_language'); ?></a>
        <?php endif; ?>
    </h3>     


    <table class="wp-list-table widefat fixed posts">

        <thead>
            <tr>
                <th class="full first" width="50%" align="left"> <span class="cufon"> <?php _e('Name', 'evrplus_language'); ?> </span> </th>
                <th class="center" width="75" align="left"> <span class="cufon"> <?php _e('Price', 'evrplus_language'); ?> </span> </th>
                <th class="center" width="75" > <span class="cufon"> <?php _e('Start', 'evrplus_language'); ?> </span> </th>
                <th class="center" width="75" > <span class="cufon"> <?php _e('End', 'evrplus_language'); ?> </span> </th>
                <th class="center" colspan="2" width="75" ><?php _e('Actions', 'evrplus_language'); ?></th>
                <th></th>
            </tr>
        </thead>


        <tbody>

            <?php if ($total_items): ?>

                <?php
                foreach ($rows as $row) {

                    $item_id = $row['id'];
                    $item_sequence = $row['sequence'];
                    $event_id = $row['event_id'];
                    $item_title = $row['item_title'];
                    $item_description = $row['item_description'];
                    $item_cat = $row['item_cat'];
                    $item_limit = $row['item_limit'];
                    $item_price = $row['item_price'];
                    $free_item = $row['free_item'];
                    $item_start_date = $row['item_available_start_date'];
                    $item_end_date = $row['item_available_end_date'];
                    $item_custom_cur = $row['item_custom_cur'];
                    ?>

                    <tr>

                        <td class='er_ticket_info' style='WORD-BREAK:BREAK-ALL;'>
                            <?php
                            if ($free_item == "Y") {
                                ?><img src="<?php echo $this->assetUrl('images/free_icon.png'); ?>" alt="free" style="vertical-align:middle" />&nbsp;<?php
                            }
                            if ($free_item == "N") {
                                ?><img src="<?php echo $this->assetUrl('images/dollar_icon.png'); ?>" alt="free" style="vertical-align:middle" /> <?php
                            }
                            echo $item_cat . " | " . $item_title;
                            ?>

                            <?php if ($item_description != ''): ?>
                                <a title="<?php echo $item_description; ?>"><span>?</span></a>
                            <?php endif; ?>
                        </td>
                        <td align='left'><?php echo $item_custom_cur . " " . $item_price; ?></td>
                        <td align='center'><?php echo $item_start_date; ?> </td>
                        <td align='center'><?php echo $item_end_date; ?> </td>

                        <td width="15">
                            <div class="edit_button_icon">
                                <a href="<?php echo $this->adminUrl('admin_events_items', array('method' => 'edit', 'item_id' => $item_id, 'event_id' => $event_id)) ?>" class="edit_button"><?php _e('Edit', 'evrplus_language'); ?></a>
                            </div>
                        </td>
                        <td width="15" align="left">
                            <div class="delete_btn_icon">
                                <a href="<?php echo $this->adminUrl('admin_events_items', array('method' => 'delete', 'item_id' => $item_id, 'event_id' => $event_id)) ?>" class="delete_btn" id="delete_event-<?php echo $event_id ?>" onclick="return confirm('<?php _e('Are you sure you want to delete', 'evrplus_language'); ?> <?php echo $item_title ?>?')"><?php _e('Delete', 'evrplus_language'); ?></a>
                            </div>
                        </td>
                        <td></td>


                    </tr>



                    <?php
                }
            else:
                ?>

                <tr>

                    <td><?php _e('No records found.', 'evrplus_language'); ?></td>

                <tr>

                <?php endif; ?>

        </tbody>

    </table>

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




    <?php echo EventPlus::dispatch('admin_events_items/coupon_form'); ?>