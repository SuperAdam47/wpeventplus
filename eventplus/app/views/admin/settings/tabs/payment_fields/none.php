<p>
    <label for="checka">
        <?php _e('Will you accept checks/cash?', 'evrplus_language'); ?>
    </label>
    <br />
<div class="styled">
    <select name = 'checks' class="regular-select">
        <!--<option value="<?php echo $company_options['checks']; ?>"><?php echo $company_options['checks']; ?> </option>-->
        <option value="Yes" <?php if ($company_options['checks'] == 'Yes') echo ' selected'; ?>>
            <?php _e('Yes', 'evrplus_language'); ?>
        </option>
        <option value="No" <?php if ($company_options['checks'] == 'No') echo ' selected'; ?>>
            <?php _e('No', 'evrplus_language'); ?>
        </option>
    </select>
</div>
</p>