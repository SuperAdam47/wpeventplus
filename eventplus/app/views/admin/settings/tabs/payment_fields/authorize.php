<?php /* * *******************Authorized.Net Key******************** */ ?>


<p>
    <label for="use_authorize_sandbox">
        <?php _e('Sanbox Mode?', 'evrplus_language'); ?>
        <font size="-6">
        <?php _e('(used for testing/debug)', 'evrplus_language'); ?>
        </font>
    </label>
    <br />
<div class="styled">
    <select name = 'use_authorize_sandbox' class="regular-select">

        <option value="Y" <?php if ($company_options['use_authorize_sandbox'] == 'Y') echo ' selected'; ?>>
            <?php _e('Yes', 'evrplus_language'); ?>
        </option>
        <option value="N" <?php if ($company_options['use_authorize_sandbox'] == 'N') echo ' selected'; ?>>
            <?php _e('No', 'evrplus_language'); ?>
        </option>
    </select>
</div>
</p>

<p>
    <label for="authorize_id">
        <?php _e('Authorized.Net API Login ID', 'evrplus_language'); ?>
    </label>
    <br />
    <input name="authorize_id" id="authorize_id" value="<?php echo $company_options['authorize_id']; ?>" class="regular-text" type="text"/>
</p>
<p>
    <label for="authorize_key">
        <?php _e('Authorized.Net Txn Key', 'evrplus_language'); ?>
    </label>
    <br />
    <input id="authorize_key" type="text" name="authorize_key" value="<?php echo $company_options['authorize_key']; ?>" class="regular-text" />
</p>
<p>
    <label for="authorize_default_currency">
        <?php _e('Currency Format:', 'evrplus_language'); ?>
    </label>
    <br />
<div class="styled">
    <select name = "authorize_default_currency" class="regular-select">
        <option value="<?php echo $company_options['authorize_default_currency']; ?>">
            <?php _e('Select Currency', 'evrplus_language'); ?>
        </option>
        <!--<option value="<?php echo $company_options['authorize_default_currency']; ?>" ><?php echo $company_options['authorize_default_currency']; ?> </option>-->
        <option value="USD" <?php if ($company_options['authorize_default_currency'] == 'USD') echo ' selected'; ?>>USD</option>
        <option value="TWD" <?php if ($company_options['authorize_default_currency'] == 'TWD') echo ' selected'; ?>>TWD</option>
        <option value="TRY" <?php if ($company_options['authorize_default_currency'] == 'TRY') echo ' selected'; ?>>TRY</option>
        <option value="THB" <?php if ($company_options['authorize_default_currency'] == 'THB') echo ' selected'; ?>>THB</option>
        <option value="RUB" <?php if ($company_options['authorize_default_currency'] == 'RUB') echo ' selected'; ?>>RUB</option>
        <option value="NOK" <?php if ($company_options['authorize_default_currency'] == 'NOK') echo ' selected'; ?>>NOK</option>
        <option value="MYR" <?php if ($company_options['authorize_default_currency'] == 'MYR') echo ' selected'; ?>>MYR</option>
        <option value="BRL" <?php if ($company_options['authorize_default_currency'] == 'BRL') echo ' selected'; ?>>BRL</option>
        <option value="AUD" <?php if ($company_options['authorize_default_currency'] == 'AUD') echo ' selected'; ?>>AUD</option>
        <option value="GBP" <?php if ($company_options['authorize_default_currency'] == 'GBP') echo ' selected'; ?>>GBP</option>
        <option value="CAD" <?php if ($company_options['authorize_default_currency'] == 'CAD') echo ' selected'; ?>>CAD</option>
        <option value="CZK" <?php if ($company_options['authorize_default_currency'] == 'CZK') echo ' selected'; ?>>CZK</option>
        <option value="DKK" <?php if ($company_options['authorize_default_currency'] == 'DKK') echo ' selected'; ?>>DKK</option>
        <option value="EUR" <?php if ($company_options['authorize_default_currency'] == 'EUR') echo ' selected'; ?>>EUR</option>
        <option value="HKD" <?php if ($company_options['authorize_default_currency'] == 'HKD') echo ' selected'; ?>>HKD</option>
        <option value="HUF" <?php if ($company_options['authorize_default_currency'] == 'HUF') echo ' selected'; ?>>HUF</option>
        <option value="ILS" <?php if ($company_options['authorize_default_currency'] == 'ILS') echo ' selected'; ?>>ILS</option>
        <option value="JPY" <?php if ($company_options['authorize_default_currency'] == 'JPY') echo ' selected'; ?>>JPY</option>
        <option value="MXN" <?php if ($company_options['authorize_default_currency'] == 'MXN') echo ' selected'; ?>>MXN</option>
        <option value="NZD" <?php if ($company_options['authorize_default_currency'] == 'NZD') echo ' selected'; ?>>NZD</option>
        <option value="NOK" <?php if ($company_options['authorize_default_currency'] == 'NOK') echo ' selected'; ?>>NOK</option>
        <option value="PLN" <?php if ($company_options['authorize_default_currency'] == 'PLN') echo ' selected'; ?>>PLN</option>
        <option value="SGD" <?php if ($company_options['authorize_default_currency'] == 'SGD') echo ' selected'; ?>>SGD</option>
        <option value="SEK" <?php if ($company_options['authorize_default_currency'] == 'SEK') echo ' selected'; ?>>SEK</option>
        <option value="CHF" <?php if ($company_options['authorize_default_currency'] == 'CHF') echo ' selected'; ?>>CHF</option>
    </select>
</div>
</p>