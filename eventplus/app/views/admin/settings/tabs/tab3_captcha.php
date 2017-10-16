<div id="tab3_captcha" class="tab_content">
    <div class="postbox " >
        <div class="inside">
            <div class="padding">
                <h1 class="stephead">
                    <?php _e('Step 3', 'evrplus_language'); ?>
                </h1>
                <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl(); ?>images/lock-icon.png"> <?php echo _e('Captcha Integration', 'evrplus_language'); ?></span>
                <label for="captcha">
                    <?php _e('Use Captcha on registration form?', 'evrplus_language'); ?>
                </label>
                <input name="captcha" id="yescap" type="radio" value="Y" class="regular-radio" <?php
                if ($company_options['captcha'] == "Y") {
                    echo "checked";
                }
                ?> />
                <label class="labels" for="yescap">
                    <?php _e('Yes', 'evrplus_language'); ?>
                </label>
                <input name="captcha" id="nocap" type="radio" value="N" class="regular-radio" <?php
                if ($company_options['captcha'] == "N") {
                    echo "checked";
                }
                ?> />
                <label class="labels" for="nocap">
                    <?php _e('No', 'evrplus_language'); ?>
                </label>
            </div>
			
			<div class="google_captcha">
				<label for="captcha">
                    <?php _e('Google Captcha site Key?', 'evrplus_language'); ?>
                </label>
				
                <input name="captcha_key" id="captcha_key" type="text" value="<?php
                if (!empty($company_options['captcha_key'])) { echo $company_options['captcha_key']; } ?>" />	
            				
			</div>
			
            <div class="padding">
                <label class="bhelp" for="form_css">
                    <?php _e('CSS Overrides for registration form?', 'evrplus_language'); ?>
                </label>
                <p class="hep"><a class="ev_reg-fancylink" href="#css_override_help">
                        <?php _e('Help', 'evrplus_language'); ?>
                    </a> </p>
                <textarea name="form_css" id="form_css" style="width: 100%; height: 300px;">
                    <?php echo $company_options['form_css']; ?></textarea>
                <br />
            </div>
        </div>
    </div>
</div>