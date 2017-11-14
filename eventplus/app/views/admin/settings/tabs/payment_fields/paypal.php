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
    <label for="paypal_pdt_token">
        <?php _e('PDT Auth Token:', 'evrplus_language'); ?>
        <font size="-6">(Please fill in and system will then be able to process payment status (Pending/Failed/Completed etc))</font>
        <br />
        <a target="_blank" href="https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/paymentdatatransfer/">How to activate</a>
    </label>
    <br />
    <input type="text" name="paypal_pdt_token" value="<?php echo $company_options['paypal_pdt_token']; ?>" class="regular-text" maxlength="120"  size="10" />

    <br />
<p>Follow these steps to configure your account for PDT:</p>
<ol>
    <li>Log in to your PayPal account.</li>
    <li>Click the <strong>Profile</strong> subtab.</li>
    <li>Click <strong>Website Payment Preferences</strong> in the Seller Preferences column.</li>
    <li>Under Auto Return for Website Payments, click the <strong>On</strong> radio button.</li>
    <li>For the Return URL, keep it blank.</li>
    <li>Under Payment Data Transfer, click the <strong>On</strong> radio button.</li>
    <li>Click <strong>Save</strong>.</li>
    <li>Click <strong>Website Payment Preferences</strong> in the Seller Preferences column.</li>
    <li>Scroll down to the Payment Data Transfer section of the page to view your PDT identity token.</li>
</ol>
</p>

<br style="clear:both;" />
