<?php
$event_name = $oEvent->event_name;
$event_id = $oEvent->id;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->assetUrl('js/jquery.ui.all.css'); ?>"/>        
<style type="text/css"> 
    body{overflow-x: hidden;}
    #er_ticket_sortable { 
        list-style-type: none; 
        margin: 0; 
        padding: 0; 
        width: 90%; 
    } 
    #er_ticket_sortable li { 
        margin: 0 3px 3px 3px; 
        padding: 0.4em; 
        padding-left: 1.5em; 
        font-size: .8em; 
        height: 30px; 
    } 
    #er_ticket_sortable li span { 
        position: absolute; 
        margin-left: -1.3em; 
    } 
</style> 
<br /> <br />
<div class="wrap">
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class='postbox-container' style='width:65%;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox " >
                        <h3><span><?php _e('ReArrange Items for display order: ', 'evrplus_language'); ?><?php echo stripslashes($event_name); ?></span></h3>
                        <div class="inside">
                            <div class="padding">        
                                <ul id="er_ticket_sortable">	
                                    <?php
                                    if ($items) {
                                        foreach ($items as $row) {
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

                                            <li id='<?php echo "item_" . $item_id; ?>' class='ui-state-default'>
                                                <?php _e('Drag Line Up or Down to ReArrange.', 'evrplus_language'); ?> 

                                                 <font class="as"><?php echo $item_cat; ?> | <?php echo $item_title; ?></font>  <span class="as2"><?php echo $item_custom_cur . " " . $item_price; ?></span><br />


                                            </li>                      
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div>
</div> 
<script type="text/javascript">
    jQuery(function ($)
    {
        $("#er_ticket_sortable").sortable({
            placeholder: 'ui-state-highlight',
            stop: function (i) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->adminUrl('admin_events_items/sort', array('event_id' => $event_id)); ?>",
                    data: $("#er_ticket_sortable").sortable("serialize")});
            }
        });

        $("#er_ticket_sortable").disableSelection();
    });

</script>