
<p>
    <label for="payment_vendor_id">
        <?php _e('Secret key', 'evrplus_language'); ?>
    </label>
    <br />
    <input class="regular-text" type="text" value="<?php echo $company_options['secret_key']; ?>" size="60" name="secret_key">
</p>
<p>
    <label for="payment_vendor_id">
        <?php _e('Publishable key', 'evrplus_language'); ?>
    </label>
    <br />
    <input class="regular-text" type="text" value="<?php echo $company_options['publishable_key']; ?>" size="60" name="publishable_key">
</p>

<p style="display:none;">
    <label for="stripereturn_url">
        <?php _e('Stripe Return Url', 'evrplus_language'); ?>
    </label>
    <br />
    <input class="regular-text" type="text" value="<?php echo $company_options['stripereturn_url']; ?>" size="60" name="stripereturn_url">
</p>


<?php
$stripeRequirementNotices = array();
if (!function_exists('curl_init')) {
    $stripeRequirementNotices[] = 'curl_init: Stripe needs the CURL PHP extension.';
}
if (!function_exists('json_decode')) {
    $stripeRequirementNotices[] = 'json_decode: Stripe needs the JSON PHP extension.';
}
if (!function_exists('mb_detect_encoding')) {
    $stripeRequirementNotices[] = 'mb_detect_encoding: Stripe needs the Multibyte String PHP extension.';
}

if (count($stripeRequirementNotices)):
    ?>
    <p>
        <label for="stripereturn_warning" style="color:red;">
            <?php _e('Warning! Stripe will not work as intended. Please activate following PHP extensions:', 'evrplus_language'); ?>
        </label>
        <br />
    <ul>
        <?php foreach ($stripeRequirementNotices as $k => $stripeRequirementNotice): ?>
            <li><?php echo $stripeRequirementNotice; ?></li>
        <?php endforeach; ?>
    </ul>
    </p>
<?php endif; ?>
