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
                        <h3><span><?php _e('ReArrange Questions for display order: ', 'evrplus_language'); ?><?php echo stripslashes($event_name); ?></span></h3>
                        <div class="inside">
                            <div class="padding">        
                                <ul id="er_ticket_sortable">	
                                    <?php
                                    if ($questions) {
                                        foreach ($questions as $question) {
                                            $question_name = $question->question . "(" . $question->question_type . ")";
                                            ?>

                                            <li id='<?php echo "item_" . $question->id; ?>' class='ui-state-default'>
                                                <?php _e('Drag Line Up or Down to ReArrange.', 'evrplus_language'); ?> 

                                                <?php if ($question->required == "Y") {
                                                    ?>
                                                    ||  <strong><font class="yy2" color="red" size = "1"><?php _e('REQUIRED', 'evrplus_language'); ?></font></strong>  
                                                    <?php
                                                }
                                                ?>
                                                ||  <font class="yy1" color='#1BA79D' size = '1'> <?php _e('TYPE', 'evrplus_language'); ?>:</font> <?php echo $question->question_type; ?>
                                                ||  <font class="yy1" color='#1BA79D' size = '1'><?php _e('QUESTION', 'evrplus_language'); ?>:</font> <?php echo $question->question; ?>
                                                ||  <font class="yy1" color='#1BA79D' size = '1'><?php _e('RESPONSES', 'evrplus_language'); ?>:</font> <?php echo $question->response; ?>


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
                    url: "<?php echo $this->adminUrl('admin_questions/sort', array('event_id' => $event_id)); ?>",
                    data: $("#er_ticket_sortable").sortable("serialize")});
            }
        });

        $("#er_ticket_sortable").disableSelection();
    });

</script>