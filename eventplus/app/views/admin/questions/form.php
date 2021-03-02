<style type="text/css">body{overflow-x:hidden;} .ui-tooltip, .arrow:before {background: #5BA4A4 !important;border:1px #fff solid !important;}.ui-tooltip {padding: 10px 10px;color: white !important;font: bold 13px "Helvetica Neue", Sans-Serif;}.arrow {width: 70px;height: 25px;overflow: hidden;position: absolute;bottom: 5px;left: -26px;z-index: -1;}.arrow{display:none !important;}.arrow:before {content: "";position: absolute;left: 20px;top: 0px;width: 25px;height: 25px;-webkit-transform: rotate(45deg);-moz-transform: rotate(45deg);-ms-transform: rotate(45deg);-o-transform: rotate(45deg);tranform: rotate(45deg);}</style>
<?php
$event_name = $oEvent->event_name;
$event_id = $oEvent->id;

$form_url = $this->adminUrl('admin_questions/add', array('event_id' => $event_id));

if (!empty($question_id) && $question_id > 0) {
    $form_url = $this->adminUrl('admin_questions/edit', array('event_id' => $event_id, 'id' => $question_id));
}

$questionOptions = array(
    'TEXT' => __('Text', 'evrplus_language'),
    'TEXTAREA' => __('Text Area', 'evrplus_language'),
    'SINGLE' => __('Single', 'evrplus_language'),
    'MULTIPLE' => __('Multiple', 'evrplus_language'),
    'DROPDOWN' => __('Drop Down', 'evrplus_language'),
);
if(empty($row)){
	$row['question'] = "";
	$row['question_type'] = "";
	$row['required'] = "";
	$row['response'] = "";
	$row['remark'] = "";
}	
?>

<br /><br />
<div class="wrap">
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class='postbox-container' style='width:55%;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox ">
                        <h3 class='hndle lt'><span><?php echo $form_heading; ?> for <?php echo stripslashes($event_name); ?></span></h3>                 
                        <div class="inside">                    
                            <div class="padding">                           
                                <form name="<?php echo md5($form_url); ?>" method="post" action="<?php echo $form_url; ?>">                           
                                    <input type="hidden" name="question_id" value="<?php echo (int) $question_id; ?>" />                            	
                                    <input type="hidden" name="event_id" value="<?php echo (int) $event_id; ?>" />                            	
                                    <div class="pass1"><?php _e('Field Question', 'evrplus_language'); ?>:</div>
                                    <div class="pass1">
                                        <p class="cs2" title="<?php _e('Choose a title for your custom field do you have pets? How did you hear about us?etc', 'evrplus_language'); ?>"></p></div>                            	
                                    <br/><div class="pass2"><input name="question" type="text" id="question" size="100" value="<?php echo $row['question']; ?>" />
                                    </div>
                                    <div class="pass1"><?php _e('Field Type', 'evrplus_language'); ?>:</div>
                                    <div class="pass1">
                                        <p class="cs2" title="<?php _e('Choose the type of field you would like your visitor to fill out:text, textarea, single radio button, multiple radio buttons and dropdown', 'evrplus_language'); ?>"></p></div>                            	
                                    <br/>
                                    <div class="pass2">
                                        <select name="question_type" id="question_type">        

                                            <?php foreach ($questionOptions as $optionKey => $questionOption): ?>
                                                <option value="<?php echo $optionKey; ?>"<?php echo ($row['question_type'] == $optionKey) ? " selected='selected'" : ""; ?>><?php echo $questionOption; ?></option>                            				
                                            <?php endforeach; ?>                         	
                                        </select>
                                    </div>
                                    <div class="pass1"><?php _e('Field option(for multiple radio and dropdown)', 'evrplus_language') ?>:</div>
                                    <div class="pass1"><p class="cs2" title="<?php _e('Create the choices for the visitor separated by comma i.e google, yahoo, bing etc.', 'evrplus_language'); ?> "></p></div>                            	
                                    <br/><div class="pass2"><input name="values" type="text" id="values" size="50" value="<?php echo $row['response']; ?>" />
                                    </div>                            	                                                                
                                    <div class="pass1"><?php _e('Do You want this field to be mandatory?', 'evrplus_language'); ?>:</div>                            	
                                    <br/>
                                    <div class="pass2">
                                    
                                        <input id="sd1" name="required" type="radio"<?php echo ($row['required'] == "Y") ? " checked='checked'" : ""; ?> value="Y" />
                                        <label for="sd1"><?php _e('Yes', 'evrplus_language'); ?></label>
                                    
                                        <input id="sd2" name="required" type="radio"<?php echo ($row['required'] == "N") ? " checked='checked'" : ""; ?> value="N" />
                                        <label for="sd2"><?php _e('No', 'evrplus_language'); ?></label>
                                    
                                    </div>                            	
                                    <br/><div class="pass1"><?php _e('Comments', 'evrplus_language'); ?>:</div>                              
                                    <br/> 
                                    <div class="pass2"><input type="text" name="remark" id="remark" value="<?php echo $row['remark']; ?>" /></div>

                                    <p class="dd"><input id="uyt" type="submit" name="Submit" value="<?php echo $button_label; ?>" /></p>
                                </form>
                                <br />
                                <div class="bt">                             
                                    <p class="bt1"><?php _e('Definitions of the fields that you can create for your registration form', 'evrplus_language'); ?></p>
                                    <p class="bt2"><span class="subt"><?php _e('Text', 'evrplus_language'); ?>:</span>  <?php _e('This is generally what I would call short answer questions, typically consisting of a single sentence. Where the registering person would type in their response. You do not need to put anything in the Selections field for this question type.', 'evrplus_language'); ?></p>
                                    <p class="bt2"><span class="subt"><?php _e('Text Area', 'evrplus_language'); ?>:</span>  <?php _e('This is similar to the Text, except you are looking more for an paragraph/multiple sentence response. You do not need to put anything in the Selections field for this question type.', 'evrplus_language'); ?></p>
                                    <p class="bt2"><span class="subt"><?php _e('Single', 'evrplus_language'); ?>:</span>  <?php _e('This will provide radio button answers where the registering person will select one of several options. Yes/No, True/False are good examples of this type of question. Basically a multiple choice question with one possible choice. When entering this question you will need to provide the list of choices that will appear. Separate your choices by a comma: True, False etc.', 'evrplus_language'); ?>    
                                        <font color="red">  <?php _e('Do not provide answer choices that have a comma in the response', 'evrplus_language'); ?>!</font></p>
                                    <p class="bt2"><span class="subt"><?php _e('Multiple', 'evrplus_language'); ?>:</span> <?php _e('This is similar to Single Type questions but gives them the option of selecting several of the choices instead of just selecting one item. Basically a multiple choice question with several  possible choices. When entering this question you will need to provide the list of choices that will appear. Separate your choices by a comma: Newspaper, Web, A Friend, Billboard etc.', 'evrplus_language'); ?>  
                                        <font color="red"><?php _e('Do not provide answer choices that have a comma in the response', 'evrplus_language'); ?></font>!</p>
                                    <p class="bt2"><span class="subt"><?php _e('Dropdown', 'evrplus_language'); ?>:</span> <?php _e('This question type is similar in nature to the Single, exept instead of providing the choices in radio buttons, it provides them in a drop down box. This is handy if you have a lot of choices the person needs to choose from, it prevents them from taking up a lot space on your registration form.', 'evrplus_language'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>jQuery(function () {
        jQuery(document).tooltip({position: {my: 'left center', at: 'right+10 center', using: function (position, feedback) {
                    jQuery(this).css(position);
                    jQuery("<div>").addClass("arrow").addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
                }}});
    });</script>
