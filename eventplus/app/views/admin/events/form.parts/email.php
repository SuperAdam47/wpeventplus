<?php
$editor_settings = array('wpautop' => false, 'media_buttons' => false, 'textarea_rows' => '4', 'tinymce' => false);
$body = "***This is an automated response - Do Not Reply***<br />";
$body .= "Thank you [fname] [lname] for registering for [event].<br />";
$body .= "We hope that you will find this event both informative and enjoyable.";
$body .= "Should have any questions, please contact [contact].";
$body .= "If you have not done so already, please submit your payment in the amount of [cost].";
$body .= "Click here to review your payment information [payment_url].<br />";
$body .= "Thank You.";
if (isset($conf_mail) == false || $conf_mail == '') {
    $conf_mail = $body;
}
?>
<div class="postbox">
    <div class="inside">
        <div class="padding">
            <h1 class="stephead"><?php _e('Step 5', 'evrplus_language'); ?></h1>
            <br>
            <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl('images/email-icon.png'); ?>"><?php _e('Custom Confirmation Email', 'evrplus_language'); ?></span>
            <div class="form-table">
                <p><label  class="tooltip">
                        <?php _e('Do you want to use a custom email for this event?', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('If you have send mail option enabled in the company settings, you can override the default mail by creating a custom mail for this event.', 'evrplus_language'); ?>"></p><br/>
                <input id="ver1" type="radio" name="send_mail" class="radio"  value="Y" <?php
                if ($send_mail == "Y" || $send_mail == '') {
                    echo "checked";
                }
                ?> /><label for="ver1" > <?php _e('Yes', 'evrplus_language'); ?></label>
                <input id="ver2" type="radio" name="send_mail" class="radio"  value="N" <?php
                if ($send_mail == "N") {
                    echo "checked";
                };
                ?> /><label for="ver2"><?php _e('No', 'evrplus_language'); ?> </label>
                </p>
                <p class="qa"><label class="tooltip"><?php _e('Custom Confirmation Email', 'evrplus_language'); ?></label><p class="cs2" title="<?php _e('Enter the text for the confirmation email. This email will be sent in text format. See User Manual for data tags', 'evrplus_language'); ?>"></p><br/>
                <?php
                global $wp_version;

                 if (!version_compare($wp_version, '3.3', '>=')) {
                    echo "</p>";
                    the_editor(htmlspecialchars_decode($conf_mail), 'conf_mail', $editor_settings);
                } else {
                    wp_editor(htmlspecialchars_decode($conf_mail), 'conf_mail', $editor_settings);
                }

               /* if (function_exists('the_editor')) {
                    echo "</p>";
                    the_editor(htmlspecialchars_decode($conf_mail), 'conf_mail', $editor_settings);
                } else {
                    ?>
                    <a href="javascript:void(0)" onclick="tinyfy(1, 'conf_mail')"><input type="button" value="WYSIWG"/></a>
                    </p>
                    <textarea name="conf_mail" id="conf_mail" style="width: 100%; height: 200px;"><?php echo $conf_mail; ?></textarea>

                <?php }*/ ?>

                <br />
                <br />         
                <input  type="submit" name="Submit" value="<?php echo $button_label; ?>" id="add_new_event" />
            </div>
        </div>
    </div>
</div>