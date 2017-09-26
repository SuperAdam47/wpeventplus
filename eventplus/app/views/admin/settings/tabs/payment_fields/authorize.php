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