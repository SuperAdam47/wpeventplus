
<style type="text/css">
    .ui-tooltip, .arrow:before {
        background: #5BA4A4;
        border:1px #fff solid !important;

    }
    .ui-tooltip {
        padding: 10px 10px;
        color: white;

        font: bold 13px "Helvetica Neue", Sans-Serif;
    }
    .arrow {
        width: 70px;
        height: 25px;
        overflow: hidden;
        position: absolute;


        bottom: 5px;
        left: -26px;
        z-index: -1;
    }
    .arrow{display:none !important;}
    .arrow:before {
        content: "";
        position: absolute;
        left: 20px;
        top: 0px;
        width: 25px;
        height: 25px;

        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        tranform: rotate(45deg);
    }

    .showHideDivsecond {float:left; width:100%; display:none;}
    .showHideDivsecond input {position:static !important; display:inline-block !important; }
    .authorizeShowhide {float:left; width:100%; display:none;}
    .showHideDivfirst {float:left; width:100%; display:none;}

</style>

<?php
$currentTab = 'tab1_contact';
if (isset($_GET['ct'])) {
    $currentTab = $_GET['ct'];
}
?>

<div class="events-plus_page_configure">
    <div class="evrplus_container">

        <div class="wrap">
            <h2><img src="<?php echo $this->assetUrl('images/evrplus_icon.png'); ?>" alt="Event Registration for Wordpress" /></h2> 

            <h2 class="disp">
                <?php _e('Lets get your plugin setup!', 'evrplus_language'); ?>
            </h2>
            <ul class="tabs">

                <?php foreach ($tabs as $tabKey => $tabTitle): ?>
                    <li class="settingsTab" data-tab="<?php echo $tabKey; ?>" id="li_st_<?php echo $tabKey; ?>">
                        <a id="eplus_settings_tab_<?php echo $tabKey; ?>" href="#<?php echo $tabKey; ?>">
                            <?php echo $tabTitle; ?>
                        </a></li>
                <?php endforeach; ?>
            </ul>
            <div class="evrplus_tab_container">
                <form method="post" action="<?php echo $this->adminUrl('admin_settings'); ?>">

                    <?php foreach ($tabs as $tabKey => $tabTitle): ?>
                        <?php include 'settings/tabs/' . $tabKey . '.php'; ?>
                    <?php endforeach; ?>


                    <div style="display:none;">
                        <div id="custom_email_settings" style="width:650px;height:350px;overflow:auto;">
                            <h2>
                                <?php _e('Email Settings', 'evrplus_language'); ?>
                            </h2>
                            <p><strong>
                                    <?php _e('Email Confirmations', 'evrplus_language'); ?>
                                    :</strong><br>
                                <?php _e('For customized confirmation emails, the following tags can be placed in the email form and they will pull data from the database to include in the email', 'evrplus_language'); ?>
                                .</p>
                            <p>[id], [fname], [lname], [phone], [event],[description], [cost],[contact], [payment_url], [start_date], [start_time], [end_date], [end_time], [category_list]</p>
                            <p>Your Company: [company], [co_add1], [co_add2], [co_city],[co_state], [co_zip]</p>
                            <p>Attendee: [attendee_company],  [attendee_company_address],  [attendee_company_state],  [attendee_company_city],  [attendee_zip],  [attendee_state],  [attendee_address],  [attendee_coupon],  [attendee_email],  [attendee_phone],  [attendee_quantity]</p>
                        </div>
                    </div>
                    <div style="display:none;">
                        <div id="custom_email_example" style="width:650px;height:350px;overflow:auto;">
                            <h2>
                                <?php _e('Sample Mail Send', 'evrplus_language'); ?>
                                :</h2>
                            <p>
                                <?php _e('***This is an automated response - Do Not Reply***', 'evrplus_language'); ?>
                            </p>
                            <p>
                                <?php _e('Thank you [fname] [lname] for registering for [event]. We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact]', 'evrplus_language'); ?>
                                .</p>
                            <p>
                                <?php _e('If you have not done so already, please submit your payment in the amount of [cost]', 'evrplus_language'); ?>
                                .</p>
                            <p>
                                <?php _e('Your unique registration ID is: [id]', 'evrplus_language'); ?>
                                .</p>
                            <p>
                                <?php _e('Click here to review your payment information [payment_url]', 'evrplus_language'); ?>
                                .</p>
                            <p>
                                <?php _e('Thank You', 'evrplus_language'); ?>
                                .</p>
                        </div>
                    </div>
                    <div style="display:none;">
                        <div id="custom_wait_settings" style="width:650px;height:350px;overflow:auto;">
                            <h2>
                                <?php _e('Email Settings', 'evrplus_language'); ?>
                            </h2>
                            <p><strong>
                                    <?php _e('Waitlist', 'evrplus_language'); ?>
                                    :</strong><br>
                                <?php _e('For customized wait list emails, the following tags can be placed in the email form and they will pull data from the database to include in the email', 'evrplus_language'); ?>
                                For customized wait list emails, the following tags can be placed in the email form and they will pull data from the database to include in the email.</p>
                            <p>
                                <?php _e('[fname], [lname], [event]', 'evrplus_language'); ?>
                            </p>
                        </div>
                    </div>
                    <div style="display:none;">
                        <div id="custom_wait_example" style="width:650px;height:350px;overflow:auto;">
                            <p>
                                <?php _e('Thank you [fname] [lname] for your interest in registering for [event]', 'evrplus_language'); ?>
                                .</p>
                            <p>
                                <?php _e('At this time, all seats for the event have been taken.  
    Your information has been placed on our waiting list.  
    The waiting list is on a first come, first serve basis.', 'evrplus_language'); ?>
                            </p>
                            <p>
                                <?php _e('You will be notified by email with directions for completing registration and payment should a seat become available', 'evrplus_language'); ?>
                                .</p>
                            <p>
                                <?php _e('Thank You', 'evrplus_language'); ?>
                            </p>
                        </div>
                    </div>
                    <div style="display:none;">
                        <div id="custom_payment_email_settings" style="width:650px;height:350px;overflow:auto;">
                            <h2>
                                <?php _e('Payment Confirmation Email Settings', 'evrplus_language'); ?>
                            </h2>
                            <p><strong>
                                    <?php _e('Payment Confirmations', 'evrplus_language'); ?>
                                    :</strong><br>
                                <?php _e('For customized payment confirmation emails, the following tags can be placed in the email form and they will pull data from the database to include in the email', 'evrplus_language'); ?>
                                .</p>
                            <p>[id],[fname], [lname], [payer_email], [event_name],[amnt_pd], [txn_id],[address_street],[address_city],[address_state],[address_zip],[address_country],[start_date],[start_time],[end_date],[end_time] 
                        </div>
                    </div>
                    <div style="display:none;">
                        <div id="custom_payment_email_example" style="width:650px;height:350px;overflow:auto;">
                            <h2>
                                <?php _e('Sample Payment Mail Send', 'evrplus_language'); ?>
                                :</h2>
                            <p>
                                <?php _e('***This is an automated response - Do Not Reply***', 'evrplus_language'); ?>
                            </p>
                            <p>
                                <?php _e('Thank you [fname] [lname] for your recent payment of [amnt_pd] ([txn_id]) for [event_name]. We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].', 'evrplus_language'); ?>
                            </p>
                            <p>
                                <?php _e('Your unique registration ID is: [id]', 'evrplus_language'); ?>
                                .</p>
                            <p>
                                <?php _e('Click here to review your payment information [payment_url]', 'evrplus_language'); ?>
                                .</p>
                            <p>
                                <?php _e('Thank You', 'evrplus_language'); ?>
                                .</p>
                        </div>
                    </div>
                    <div style="display:none;">
                        <div id="css_override_help" style="width:650px;height:350px;overflow:auto;">
                            <p>enter css to override theme css on form</p>
                            <p>D0 NOT use style  tags (< style > </ style >)</p>
                        </div>
                    </div>
            </div>
            <p align="center">
                <input type="hidden" name="update_company" value="update">

                <input type="hidden" id="eventplus_settings_current_tab" name="eplus_current_tab" value="<?php echo $currentTab; ?>">
                <input  type="submit" name="update_button" value="<?php _e('Update Configuration Settings', 'evrplus_language'); ?>" id="update_button" />
                </form>
            </p>
        </div>
    </div>
</div>

<script>
    jQuery(function () {
        jQuery(document).tooltip({
            position: {
                my: 'left center', at: 'right+10 center',
                using: function (position, feedback) {
                    jQuery(this).css(position);
                    jQuery("<div>")
                            .addClass("arrow")
                            .addClass(feedback.vertical)
                            .addClass(feedback.horizontal)
                            .appendTo(this);
                }
            }
        });
    });
</script>
<script>
    var tinymceConfigs = [{
            theme: "advanced",
            mode: "none",
            language: "en",
            height: "200",
            width: "100%",
            theme_advanced_layout_manager: "SimpleLayout",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left", theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull", theme_advanced_buttons2: "", theme_advanced_buttons3: ""},
        {
            theme: "advanced",
            mode: "none",
            skin: "o2k7",
            language: "en",
            height: "200",
            width: "100%",
            theme_advanced_layout_manager: "SimpleLayout",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left"
        }];
    function tinyfy(settingid, el_id) {
        tinyMCE.settings = tinymceConfigs[settingid];
        tinyMCE.execCommand('mceAddControl', true, el_id);
    }
</script> 
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.tabs li a').on('click',function () {

            if (jQuery(this).attr('href') == '#tab9_done') {
                jQuery('.disp').html("<?php echo _e("Let's get your events plugin configured!", 'evrplus_language'); ?>");

            } else {
                jQuery('.disp').html("<?php echo _e('Event Registration Configuration Settings', 'evrplus_language'); ?>");

            }
        });

        jQuery('li.settingsTab').on('click',function () {
            jQuery('#eventplus_settings_current_tab').val(jQuery(this).attr('data-tab'));
        });

        //eplus_settings_tab_
<?php if ($currentTab != ''): ?>
            setTimeout(function () {
                jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class

                var oLi = jQuery('#li_st_<?php echo $currentTab; ?>');
                oLi.addClass("active"); //Add "active" class to selected tab

                jQuery(".tab_content").hide(); //Hide all tab content

                var activeTab = oLi.find("a").attr("href"); //Find the rel attribute value to identify the active tab + content

                jQuery(activeTab).fadeIn(); //Fade in the active content
            }, 50);
<?php endif; ?>

    });

    function showDiv(elem) {
        if (elem.value == 'STRIPEACTIVE') {
            document.getElementById('Divsecond').style.display = "block";
            document.getElementById('authorizeShowhide').style.display = "none";
            document.getElementById('Divfirst').style.display = "none";

        } else if (elem.value == 'PAYPAL') {
            document.getElementById('Divsecond').style.display = "none";
            document.getElementById('Divfirst').style.display = "block";
            document.getElementById('authorizeShowhide').style.display = "none";
        } else if (elem.value == 'AUTHORIZE') {
            document.getElementById('Divfirst').style.display = "none";
            document.getElementById('Divsecond').style.display = "none";
            document.getElementById('authorizeShowhide').style.display = "block";
        } else if (elem.value == 'NONE') {
            document.getElementById('Divsecond').style.display = "none";
            document.getElementById('authorizeShowhide').style.display = "none";
            document.getElementById('Divfirst').style.display = "none";
        }
    }

</script>

<div style='text-align: center;'>
    <?php echo EventPlus_Helpers_Funx::promoBanner(); ?>
</div>
