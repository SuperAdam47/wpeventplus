<?php /* * *******************STRIPE API KEY******************** */ ?>

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
<?php /* * ****************stripe return url ****** */ ?>
<p style="display:none;">
    <label for="stripereturn_url">
        <?php _e('Stripe Return Url', 'evrplus_language'); ?>
    </label>
    <br />
    <input class="regular-text" type="text" value="<?php echo $company_options['stripereturn_url']; ?>" size="60" name="stripereturn_url">
</p>

<?php /* * ****************stripe return url ****** */ ?>