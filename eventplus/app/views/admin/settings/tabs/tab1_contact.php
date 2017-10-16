<div id="tab1_contact" class="tab_content">
    <div class="postbox " >
        <div class="inside">
            <div class="padding">
                <h1 class="stephead">
                    <?php _e('Step 1', 'evrplus_language'); ?>
                </h1>
                <br>
                <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl(); ?>images/check-icon.png"> <?php echo _e('Contact Information', 'evrplus_language'); ?></span>
                <div class="form-table">
                    <p>
                        <label for="company">
                            <?php _e('Your Company Name', 'evrplus_language'); ?>
                            :</label>
                        <br />
                        <input name="company_name" type="text" value="<?php echo stripslashes($company_options['company']); ?>" class="regular-text" size="60"/>
                    </p>
                    <p>
                        <label for="company_street1">
                            <?php _e('Street Address', 'evrplus_language'); ?>
                            :</label>
                        <br />
                        <input name="company_street1" type="text"  value="<?php echo stripslashes($company_options['company_street1']); ?>" class="regular-text" size="60"/>
                    </p>
                    <p>
                        <label for="company_street2">
                            <?php _e('Street Address 2', 'evrplus_language'); ?>
                            :</label>
                        <br />
                        <input name="company_street2" type="text" size="60" value="<?php echo stripslashes($company_options['company_street2']); ?>" class="regular-text" />
                    </p>
                    <p>
                        <label for="company_city">
                            <?php _e('City', 'evrplus_language'); ?>
                            :</label>
                        <br />
                        <input name="company_city" type="text" size="60" value="<?php echo stripslashes($company_options['company_city']); ?>" class="regular-text" />
                    </p>
                    <p>
                        <label for="company_state">
                            <?php _e('State', 'evrplus_language'); ?>
                            :</label>
                        <br />
                        <input name="company_state" type="text" size="60" value="<?php echo $company_options['company_state']; ?>" class="regular-text" />
                    </p>
                    <p>
                        <label for="company_zip">
                            <?php _e('Postal Code', 'evrplus_language'); ?>
                            :</label>
                        <br />
                        <input name="company_postal" type="text" value="<?php echo $company_options['company_postal']; ?>" class="regular-text" size="60" />
                    </p>
                    <p>
                        <label for="contact">
                            <?php _e('Primary Contact email:', 'evrplus_language'); ?>
                        </label>
                        <br />
                        <input name="email" type="text" size="60" value="<?php echo $company_options['company_email']; ?>" class="regular-text" />
                    </p>
                    <p>
                        <label for="secondary_contact">
                            <?php _e('Secondary Contact emails(Separated By Commas[,]):', 'evrplus_language'); ?>
                        </label>
                        <br />
                        <textarea name="secondary_email" rows="5"  class="regular-text" ><?php echo $company_options['secondary_email']; ?></textarea>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>