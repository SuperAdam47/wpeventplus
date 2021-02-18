<div id="tab5_confirmation" class="tab_content">
    <div class="postbox " >
        <div class="inside">
            <div class="padding">
                <h1 class="stephead">
                    <?php _e('Step 5', 'evrplus_language'); ?>
                </h1>
                <span class="steptitle"> <img class="stepimg t5" src="<?php echo $this->assetUrl(); ?>images/email-icon.png">
                    <?php _e('Email Confirmation', 'evrplus_language'); ?>
                </span>
                <p>
                    <?php _e('Do you want to send Registration Confirmation emails?', 'evrplus_language'); ?>
                <div class="con">
                    <input id="cs1" type="radio" name="send_confirm" class="regular-radio" value="Y"  <?php
                    if ($company_options['send_confirm'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="cs1">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="cs2" type="radio" name="send_confirm" class="regular-radio" value="N"  <?php
                    if ($company_options['send_confirm'] == "N") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="cs2">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                <font class="fnt">
                <?php _e('(This option must be enable to send custom mails in events)', 'evrplus_language'); ?>
                </font>
                </p>
                <p class="btn5"><a class="ev_reg-fancylink" href="#custom_email_settings">
                        <?php _e('Settings', 'evrplus_language'); ?>
                    </a> <a class="ev_reg-fancylink" href="#custom_email_example">
                        <?php _e('Example', 'evrplus_language'); ?>
                    </a></p>
                <p>
                    <?php _e('Email Body', 'evrplus_language'); ?>
                    :
                    <?php
                    $settings = array(
                        'media_buttons' => false,
                        'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                        'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                    );

                    if (function_exists('wp_editor')) {
                        wp_editor(html_entity_decode(stripslashes($company_options['message'])), 'message', $settings);
                    } else {
                        ?>
                        <a href="javascript:void(0)" onclick="tinyfy(1, 'message')">
                            <input type="button" value="WYSIWG"/>
                        </a> <br />
                        <textarea name="message" id="message" style="width: 100%; height: 250px;">
                            <?php echo stripslashes($company_options['message']); ?></textarea>
                    <?php }
                    ?>
                </p>
            </div>
            <div class="padding net">
                <p class="net2">
                    <?php _e('Do you want to send Payment Confirmation emails?', 'evrplus_language'); ?>
                <div class="con">
                    <input id="pc11" type="radio" name="pay_confirm" class="regular-radio" value="Y"  <?php
                    if ($company_options['pay_confirm'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="pc11">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="pc21" type="radio" name="pay_confirm" class="regular-radio" value="N"  <?php
                    if ($company_options['pay_confirm'] == "N") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="pc21">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                <font class="fnt">
                <?php _e('(This option must be enable to send payment confrimation emails)', 'evrplus_language'); ?>
                </font>
                </p>
                <p class="btn5"><a class="ev_reg-fancylink" href="#custom_payment_email_settings">
                        <?php _e('Settings', 'evrplus_language'); ?>
                    </a> <a class="ev_reg-fancylink" href="#custom_payment_email_example">
                        <?php _e('Example', 'evrplus_language'); ?>
                    </a></p>
                <br />
                <p>
                    <label for="payment_subj" class="sub">
                        <?php _e('Subject', 'evrplus_language'); ?>
                    </label>
                    <input type="text" name="payment_subj" value="<?php echo $company_options['payment_subj']; ?>" class="regular-text" />
                </p>
                <p>
                    <?php _e('Email Body', 'evrplus_language'); ?>
                    :
                    <?php
                    if (function_exists('wp_editor')) {
                        wp_editor(html_entity_decode(stripslashes($company_options['payment_message'])), 'payment_message', $settings);
                    } else {
                        ?>
                        <a href="javascript:void(0)" onclick="tinyfy(1, 'payment_message')">
                            <input type="button" value="WYSIWG"/>
                        </a><br />
                        <textarea name="payment_message" id="payment_message" style="width: 100%; height: 200px;">
                            <?php echo stripslashes($company_options['payment_message']); ?></textarea>
                        <br />
                        <?php
                    }
                    ?>
                </p>
				
				<p class="net2">
                    <?php _e('Do you want to send After Payment emails?', 'evrplus_language'); ?>
                <div class="con">
                    <input id="pc1" type="radio" name="after_pay_confirm" class="regular-radio" value="Y"  <?php
                    if ($company_options['after_pay_confirm'] && $company_options['after_pay_confirm'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="pc1">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="pc2" type="radio" name="after_pay_confirm" class="regular-radio" value="N"  <?php
                    if ($company_options['after_pay_confirm']  && $company_options['after_pay_confirm'] == "N") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="pc2">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                <font class="fnt">
                <?php _e('(This option must be enable to send payment confrimation emails)', 'evrplus_language'); ?>
                </font>
                </p>
                <p class="btn5"><a class="ev_reg-fancylink" href="#custom_payment_email_settings">
                        <?php _e('Settings', 'evrplus_language'); ?>
                    </a> <a class="ev_reg-fancylink" href="#custom_payment_email_example">
                        <?php _e('Example', 'evrplus_language'); ?>
                    </a></p>
                <br />
                <p>
                    <label for="payment_subj" class="sub">
                        <?php _e('Subject', 'evrplus_language'); ?>
                    </label>
                    <input type="text" name="payment_subj" value="<?php echo $company_options['payment_subj']; ?>" class="regular-text" />
                </p>
                <p>
                    <?php _e('Email Body', 'evrplus_language'); ?>
                    :
                    <?php
                    if (function_exists('wp_editor')) {
                        wp_editor(html_entity_decode(stripslashes($company_options['after_payment_message'])), 'after_payment_message', $settings);
                    } else {
                        ?>
                        <a href="javascript:void(0)" onclick="tinyfy(1, 'after_after_payment_message')">
                            <input type="button" value="WYSIWG"/>
                        </a><br />
                        <textarea name="after_payment_message" id="after_payment_message" style="width: 100%; height: 200px;">
                            <?php echo stripslashes($company_options['after_payment_message']); ?></textarea>
                        <br />
                        <?php
                    }
                    ?>
                </p>
				
				
                <p class="net2">
                    <?php _e('Do you want to receive email notifications when someone registers?', 'evrplus_language'); ?>
                <div class="con">
                    <input id="adn1" type="radio" name="admin_noti" class="regular-radio" value="Y"  <?php
                    if ($company_options['admin_noti'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="adn1">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="adn2" type="radio" name="admin_noti" class="regular-radio" value="N"  <?php
                    if ($company_options['admin_noti'] == "N") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="adn2">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                <font class="fnt">
                <?php _e('(This option must be enable to send email to admin)', 'evrplus_language'); ?>
                </font>
                </p>
                <p>
                    <?php _e('Email Body', 'evrplus_language'); ?>
                    :
                    <?php
                    $settings = array(
                        'media_buttons' => false,
                        'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                        'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                    );

                    if (function_exists('wp_editor')) {
                        wp_editor(html_entity_decode(stripslashes($company_options['c_message'])), 'c_message', $settings);
                    } else {
                        ?>
                        <a href="javascript:void(0)" onclick="tinyfy(1, 'c_message')">
                            <input type="button" value="WYSIWG"/>
                        </a> <br />
                        <textarea name="c_message" id="message" style="width: 100%; height: 250px;">

                            <?php echo stripslashes($company_options['c_message']); ?></textarea>
                    <?php }
                    ?>
                </p>
                <br />
                <p>
                    <?php _e('Type your confirmation message below', 'evrplus_language'); ?>
                    :
                    <?php
                    $settings = array(
                        'media_buttons' => false,
                        'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                        'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                    );

                    if (function_exists('wp_editor')) {
                        wp_editor(html_entity_decode(stripslashes($company_options['info_recieved'])), 'info_recieved', $settings);
                    } else {
                        ?>
                        <a href="javascript:void(0)" onclick="tinyfy(1, 'info_recieved')">
                            <input type="button" value="WYSIWG"/>
                        </a> <br />
                        <textarea name="info_recieved" id="message" style="width: 100%; height: 250px;"><?php echo stripslashes($company_options['info_recieved']); ?></textarea>
                    <?php }
                    ?>
                </p>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
</div>