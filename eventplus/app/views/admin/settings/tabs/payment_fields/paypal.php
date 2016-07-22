<p>
    <label for="use_sandbox">
         <?php _e('Use PayPal Sandbox', 'evrplus_language'); ?>
        <font size="-6">
        <?php _e('(used for testing/debug)', 'evrplus_language'); ?>
        </font>
    </label>
    <br />
<div class="styled">
    <select name = 'use_sandbox' class="regular-select">

        <option value="Y" <?php if ($company_options['use_sandbox'] == 'Y') echo ' selected'; ?>>
            <?php _e('Yes', 'evrplus_language'); ?>
        </option>
        <option value="N" <?php if ($company_options['use_sandbox'] == 'N') echo ' selected'; ?>>
            <?php _e('No', 'evrplus_language'); ?>
        </option>
    </select>
</div>
</p>

<p>
    <label for="payment_vendor_id">
        <?php _e('Paypal Email', 'evrplus_language'); ?>
    </label>
    <br />
    <input type="text" name="payment_vendor_id" value="<?php echo $company_options['payment_vendor_id']; ?>" class="regular-text" maxlength="93"  size="10" />
</p>
<p>
    <label for="pay_now">
        <?php _e('Payment Button Text', 'evrplus_language'); ?>
    </label>
    <br />
    <input type="text" name="pay_now" value="<?php
    if ($company_options['pay_now'] != "") {
        echo $company_options['pay_now'];
    } else {
        _e('PAY NOW');
    }
    ?>" class="regular-text" />
</p>

<div class="form-table">
    <h2 class="stephead"><?php echo _e('Advanced', 'evrplus_language'); ?></h2>
    <p>
        <label for="image_url">
            <?php _e('Image URL', 'evrplus_language'); ?>
            <br />
            <font size="-6">
            <?php _e('(For your logo on PayPal page)', 'evrplus_language'); ?>
            </font></label>
        <br />
        <input type="text" name="image_url" value="<?php echo $company_options['image_url']; ?>" class="regular-text" />
    </p>
    <?php
    /* //comment out this and uncomment the other for IPN support! ?>
      <input type="hidden" value="" name="cancel_return">
      <input type="hidden" value="" name="notify_url">
      <input type="hidden" value="" name="return_method">
      <input type="hidden" value="" name="use_sandbox">
      <?php */
    //Uncomment this code if you use Paypal IPN Support  
    ?>
    <p>
        <label for="cancel_return">
            <?php _e('Cancel Return URL', 'evrplus_language'); ?>
            <br />
            <font size="-6">
            <?php _e('(page you setup for cancelled payment)', 'evrplus_language'); ?>
            </font></label>
        <br />
        <input type="text" name="cancel_return" value="<?php echo $company_options['cancel_return']; ?>" class="regular-text" />
    </p>
    <p>
        <label for="notify_url">
            <?php _e('Notify URL', 'evrplus_language'); ?>
            <br />
            <font size="-6">
            <?php _e('(used to process payments)', 'evrplus_language'); ?>
            </font></label>
        <br />
        <input type="text" name="notify_url" value="<?php echo $company_options['notify_url']; ?>" class="regular-text" />
    </p>
    <div class="abc">
        <label for="return_method">
            <?php _e('Return Method:', 'evrplus_language'); ?>
        </label>
        <br />
        <div class="styled">
            <select name = "return_method" class="regular-select">
                <option value="1" <?php if ($company_options['return_method'] == "1") echo ' selected'; ?>>
                    <?php _e('GET'); ?>
                </option>
                <option value="2" <?php if ($company_options['return_method'] == "2") echo ' selected'; ?>>
                    <?php _e('POST'); ?>
                </option>
            </select>
        </div>
    </div>
    
</div>
<br style="clear:both;" />
