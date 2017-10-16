<div id="tab6_waitlist" class="tab_content">
    <div class="postbox " >
        <div class="inside">
            <div class="padding">
                <h1 class="stephead">
                    <?php _e('Step 6', 'evrplus_language'); ?>
                </h1>
                <span class="steptitle"> <img class="stepimg t5" src="<?php echo $this->assetUrl(); ?>images/email-icon.png">
                    <?php _e('Wait list Message', 'evrplus_language'); ?>
                </span>
                <p class="btn5"><a class="ev_reg-fancylink" href="#custom_wait_settings">
                        <?php _e('Settings', 'evrplus_language'); ?>
                    </a> <a class="ev_reg-fancylink" href="#custom_wait_example">
                        <?php _e('Example', 'evrplus_language'); ?>
                    </a></p>
                <p>
                    <?php _e('Waitlist Email Body', 'evrplus_language'); ?>
                    :
                    <?php
                    $settings = array(
                        'media_buttons' => false,
                        'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                        'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                    );
                    if (function_exists('wp_editor')) {
                        wp_editor(html_entity_decode(stripslashes($company_options['wait_message'])), 'wait_message', $settings);
                    } else {
                        ?>
                        <a href="javascript:void(0)" onclick="tinyfy(1, 'wait_message')">
                            <input type="button" value="WYSIWG"/>
                        </a> <br />
                        <textarea name="wait_message" id="wait_message" style="width: 100%; height: 200px;">
                            <?php echo stripslashes($company_options['wait_message']); ?></textarea>
                        <?php }
                        ?>
                </p>
            </div>
        </div>
    </div>
</div>