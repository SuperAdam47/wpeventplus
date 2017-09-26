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
        <span><?php _e('Manage Questions', 'evrplus_language'); ?> - <strong><?php echo stripslashes($event_name); ?></strong></span>
        <?php if ($total_items): ?> <br /><br />
            <a class="btn btn-small btn-primary" href="<?php echo $this->adminUrl('admin_questions/sort', array('event_id' => $oEvent->id)); ?>"><?php _e('Sort Questions', 'evrplus_language'); ?></a>
        <?php endif; ?>
    </h3>     


    <table class="widefat">

        <thead>
            <tr>

                <th><?php _e('Question Id', 'evrplus_language'); ?></th>
                <th><?php _e('Question', 'evrplus_language'); ?></th>
                <th><?php _e('Actions', 'evrplus_language'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th><?php _e('Question Id', 'evrplus_language'); ?></th>
                <th><?php _e('Question', 'evrplus_language'); ?></th>
                <th><?php _e('Actions', 'evrplus_language'); ?></th>
            </tr>
        </tfoot>

        <tbody>

            <?php if ($total_items): ?>

                <?php
                foreach ($rows as $question) {

                    $question_id = (int) $question->id;
                    $event_id = (int) $question->event_id;
                    ?>

                    <tr>

                        <td style="white-space: nowrap;"><?php echo $question_id; ?></td>

                        <td>
                            <?php if ($question->required == 'Y'): ?>
                                <strong><?php echo $question->question; ?></strong>
                            <?php else: ?>
                                <?php echo $question->question; ?>
                            <?php endif; ?>
                        </td>

                        <td>

                            <div style="float:left; margin-right:10px;">

                                <div class="edit_button_icon">
                                    <a href="<?php echo $this->adminUrl('admin_questions', array('method' => 'edit', 'question_id' => $question_id, 'event_id' => $event_id)) ?>" class="edit_button"><?php _e('Edit', 'evrplus_language'); ?></a>
                                </div>


                            </div>



                            <div style="float:left;">


                                <div class="delete_btn_icon">
                                    <a href="<?php echo $this->adminUrl('admin_questions', array('method' => 'delete', 'question_id' => $question_id)) ?>" class="delete_btn" id="delete_event-<?php echo $event_id ?>" onclick="return confirm('<?php _e('Are you sure you want to delete', 'evrplus_language'); ?> <?php echo $question->question ?>?')"><?php _e('Delete', 'evrplus_language'); ?></a>
                                </div>


                            </div>

                        </td>

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

