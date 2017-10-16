<div id="tab8_tax" class="tab_content">
    <div class="postbox " >
        <div class="inside">
            <div class="padding">
                <h1 class="stephead">
                    <?php _e('Step 8', 'evrplus_language'); ?>
                </h1>
                <span class="steptitle"> <img class="stepimg" src="<?php echo $this->assetUrl(); ?>images/dollar-icon.png">
                    <?php _e('Tax Configuration', 'evrplus_language'); ?>
                </span>
                <p>
                    <?php _e('Do you want to charge sales tax', 'evrplus_language'); ?>
                    ?
                <div class="con">
                    <input id="t1" type="radio" name="use_sales_tax" class="regular-radio" value="Y"  <?php
                    if ($company_options['use_sales_tax'] == "Y") {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="t1">
                        <?php _e('Yes', 'evrplus_language'); ?>
                    </label>
                    <input id="t2" type="radio" name="use_sales_tax" class="regular-radio" value="N"  <?php
                    if (($company_options['use_sales_tax'] == "N") || ($company_options['use_sales_tax'] != "Y")) {
                        echo "checked";
                    }
                    ?> />
                    <label class="labels" for="t2">
                        <?php _e('No', 'evrplus_language'); ?>
                    </label>
                    <br />
                </div>
                <font size="-5">
                <?php _e('(This option must be enable to charge sales tax)', 'evrplus_language'); ?>
                </font>
                </p>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="sales_tax_rate">
                                <?php _e('Sales Tax Rate:', 'evrplus_language'); ?>
                                <br />
                                <?php _e('(must be decimal, i.e. .085 )', 'evrplus_language'); ?>
                            </label></th>
                        <td><input name="sales_tax_rate" type="text"  value="<?php echo $company_options['sales_tax_rate']; ?>" class="regular-text" /></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>