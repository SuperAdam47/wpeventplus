
<style type="text/css">body{overflow-x:hidden;} .ui-tooltip, .arrow:before {background: #5BA4A4 !important;border:1px #fff solid !important;}.ui-tooltip {padding: 10px 10px;color: white !important;font: bold 13px "Helvetica Neue", Sans-Serif;}.arrow {width: 70px;height: 25px;overflow: hidden;position: absolute;bottom: 5px;left: -26px;z-index: -1;}.arrow{display:none !important;}.arrow:before {content: "";position: absolute;left: 20px;top: 0px;width: 25px;height: 25px;-webkit-transform: rotate(45deg);-moz-transform: rotate(45deg);-ms-transform: rotate(45deg);-o-transform: rotate(45deg);tranform: rotate(45deg);}</style>
<?php
$event_name = $oEvent->event_name;
$event_id = $oEvent->id;
$end_date = $oEvent->end_date;

$form_url = $this->adminUrl('admin_events_items/add', array('event_id' => $event_id));

if (!empty($item_id) &&  $item_id > 0) {
    $form_url = $this->adminUrl('admin_events_items/edit', array('event_id' => $event_id, 'item_id' => $item_id));
}

$itemOptions = array(
    'REG' => __('Registration Attendee', 'evrplus_language'),
    'MDS' => __('Merchandise', 'evrplus_language'),
    'DSC' => __('Discount', 'evrplus_language'),
    'WRK' => __('Workshop', 'evrplus_language'),
    'MLS' => __('Meal or Food', 'evrplus_language'),
);
if(!empty($row)){
	$item_id = $row['id'];
	$item_sequence = $row['sequence'];
	$item_title = $row['item_title'];
	$item_description = $row['item_description'];
	$item_cat = $row['item_cat'];
	$item_limit = $row['item_limit'];
	$item_price = $row['item_price'];
	$free_item = $row['free_item'];
	$item_start_date = $row['item_available_start_date'];
	$item_end_date = $row['item_available_end_date'];
	$item_custom_cur = $row['item_custom_cur'];

	if($item_custom_cur == '' && $item_id == 0){
		$item_custom_cur = EventPlus_Helpers_Currency::getDefaultCurrency();
	}
}else{
	$item_id = 0;
	$item_sequence = "";
	$item_title = "";
	$item_description = "";
	$item_cat = "";
	$item_limit = "";
	$item_price = "";
	$free_item = "";
	$item_start_date = "";
	$item_end_date = "";
	$item_custom_cur = EventPlus_Helpers_Currency::getDefaultCurrency();
	$row['item_cat'] = 0;
}
?>

<br /><br />
<div class="wrap">
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class='postbox-container' style='width:75%;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now" class="postbox ">
                        <div class="inside">                    
                            <div class="padding">                           
                                <form name="<?php echo md5($form_url); ?>" method="post" action="<?php echo $form_url; ?>">                           
                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>"/>
                                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>"/>
                                    <input type="hidden" name="event_end" value="<?php echo $end_date; ?>"/>
                                    <h3><?php
                                        echo $form_heading . ' - ';
                                        echo stripslashes($event_name);
                                        ?></h3>					
                                    <hr />
                                    <br />
                                    <ul>
                                        <li>
                                            <div class="pass1"> 
                                                <label class="er_ticket_info" >
                                                    <?php _e('What type of Item/Cost is this?', 'evrplus_language'); ?>
                                                </label>
                                            </div>

                                            <div class="pass1">
                                                <p class="cs2" title="Select a Item/Cost category.&nbsp; Note that category REG is used for attendance count, all others are not included in attendance count">                                         
                                                </p>
                                            </div>
                                            <br/>

                                           <div class="pass2"> 
                                                <select class="title" name="item_cat">
                                                    <?php foreach ($itemOptions as $optionKey => $itemOption): ?>
                                                        <option value="<?php echo $optionKey; ?>"<?php echo ($row['item_cat'] == $optionKey) ? " selected='selected'" : ""; ?>><?php echo $optionKey; ?> - <?php echo $itemOption; ?></option>                            				
                                                    <?php endforeach; ?>           
                                                </select>
                                            </div>
                                            
                                        </li> 
                                        <li>
                                            <div class="pass1"><label class="er_ticket_info" ><?php _e('Name of Cost/Item', 'evrplus_language'); ?></label></div>
                                            <div class="pass1"><p title="Use a concise but descriptive name. Limit is 69 Characters."></p></div>
                                            <div class="pass2"><input  type="text" class="title" name="item_name" value="<?php echo $item_title; ?>" maxlength="69" size="70" /></div>
                                        </li>
                                        <li>
                                            <div class="pass1">
                                                <label for="cost_desc" class="er_ticket_info"><?php _e('Description of Cost', 'evrplus_language'); ?></label>
                                            </div>
                                            <div class="pass1"><p class="cs2" title="Provide a description of the cost/ticket"></p></div>
                                            <div class="pass2">
                                                <input type="text" class="desc"  name="item_desc" id="cost_desc" value="<?php echo $item_description; ?>" maxlength="69" size="70" /> 
                                            </div>
                                        </li>
                                        <li>
                                            <div class="pass1">
                                                <label class="er_ticket_info"><?php _e('Available items/cost per registration/order?', 'evrplus_language'); ?> </label></div>
                                            <div class="pass1">
                                                <p class="cs2" title="Provide the number of available item/cost types per registration form.&nbsp; If it is a REG item, available seats will impact overall amount available.&nbsp; Leave blank if no limit (system will default to 25)."></p>
                                            </div>

                                            <div class="pass2">
                                                <input type="text" class="title" name="item_limit" value="<?php echo $item_limit; ?>"/>
                                            </div>
                                        </li>

                                        <br/>
                                        <hr />
                                        <h3><?php _e('Value/Cost', 'evrplus_language'); ?></h3><br/><br/>
                                        <li>
                                            <div class="pass1">
                                                <label  class="er_ticket_info">
                                                    <?php _e('Will this be a free item?', 'evrplus_language'); ?></label>
                                            </div>
                                            <div class="pass1"><p class="cs2" title="Please select no for event pricing setup, select yes for free event"></p></div><br/>
                                            <div class="pss2">
                                                <input id="dd<?php echo $item_id; ?>" type="radio" name="item_free" class="radio" id="free_yes" value="Y"  
                                                <?php
                                                if ($free_item == "Y") {
                                                    echo "checked";
                                                }
                                                ?> /><label for="dd<?php echo $item_id; ?>"> <?php _e('Yes', 'evrplus_language'); ?></label> 
                                                <input id="ddd<?php echo $item_id; ?>" type="radio" name="item_free" class="radio" id="free_no" value="N" <?php
                                                if ($free_item == "N") {
                                                    echo "checked";
                                                }
                                                ?> /><label for="ddd<?php echo $item_id; ?>"> <?php _e('No', 'evrplus_language'); ?> </label></div>
                                        </li> 

                                        <li>
                                            <div class="pass1">
                                                <label  class="er_ticket_info"><?php _e('Custom Currency', 'evrplus_language'); ?></label></div>
                                            <div class="pass1"><p class="cs2" title="Please select the country in which the currency format will be used"></p></div><br/>
                                            <div class="pass2">
                                                <select class="select" name="custom_cur">
                                                 
                                                      <?php
                                                    $currency_codes = EventPlus_Helpers_Currency::get_currency_list();
                                                    if (is_array($currency_codes)):
                                                        foreach ($currency_codes as $k => $currency_code):
                                                            ?>
                                                            <option value="<?php echo $currency_code; ?>" <?php if ($item_custom_cur == $currency_code) echo ' selected'; ?>><?php echo $currency_code; ?></option>
                                                        <?php endforeach; ?>`
                                                    <?php endif; ?>`
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="pass1">  
                                                <label  class="er_ticket_info">
                                                    <?php _e('Item/Cost Value', 'evrplus_language'); ?> </label>
                                            </div>
                                            <div class="pass1">
                                                <p class="cs2" title="Please enter the amount using 2 decimal point (i.e. 10.00) for the registration cost.&nbsp; Use minus symbol before for discount amounts (i.e. -5.00)"></p></div>
                                            <div class="pass2">
                                                <input class="price" id="item_price" name="item_price" type="text" maxlength="14" value="<?php echo $item_price; ?>" />
                                            </div>
                                        </li>
                                        <br/>
                                        <hr />

                                        <h3><?php _e('AVAILABILITY TIMES OF COST/ITEM', 'evrplus_language'); ?></h3><br/><br/>
                                        <li>    
                                            <label  for="item_start_date"><b>Start Date</b></label>
                                            <div class="pass1">
                                                <?php echo EventPlus_Helpers_Funx::dateSelector("\"item_start", strtotime($item_start_date)); ?>
                                            </div>
                                        </li>
                                        <li>
                                            <label for="item_end_date"><b>End Date</b></label>
                                            <div class="pass1">
                                                <?php echo EventPlus_Helpers_Funx::dateSelector("\"item_end", strtotime($item_end_date)); ?>
                                            </div>
                                        </li>
                                    </ul>
                                    <hr />
                                    <br />
                                    <input type="hidden" name="end" value="<?php echo $end_date; ?>"/>
                                    <p id="uyt" class="att"><input type="submit" value="<?php echo $button_label; ?>" name="Submit" class="satt"></p>
 
                                </form>	 

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(function ($) {
        if ($('input#item_cat_2  :selected').val() == 'other')
            $('input#item_cat_2').show();
        else
            $('input#item_cat_2').hide();

        $('select#item_cat').change(function () {
            var id = $(this).attr('id');
            if ($('#' + id + ' :selected').val() == 'other')
                $('input#item_cat_2').show();
            else
                $('input#item_cat_2').hide();
        });
    });
</script>
