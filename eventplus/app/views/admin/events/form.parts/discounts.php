
<div class="postbox">
    <div class="inside">
        <div class="padding">

            <h1 class="stephead"><?php _e('Optional', 'evrplus_language'); ?></h1>
            <br>
            <div class="form-table">

                <p>

                    <label for="qty_discount_yes">
                        <?php _e('Would you like to offer quantity based discounts? ', 'evrplus_language'); ?></label><br />
                    <select name = 'qty_discount' class="regular-select ev--qty-discount">
                        <option value="" <?php if ($meta_data['qty_discount'] == '') echo ' selected'; ?>>
                            <?php _e('Use Global Settings', 'evrplus_language'); ?>
                        </option>

                        <option value="N" <?php if ($meta_data['qty_discount'] == 'N') echo ' selected'; ?>>
                            <?php _e('No', 'evrplus_language'); ?>
                        </option>
                        <option value="Y" <?php if ($meta_data['qty_discount'] == 'Y') echo ' selected'; ?>>
                            <?php _e('Yes', 'evrplus_language'); ?>
                        </option>
                    </select>

                    <br />
                </p>

                <?php
                $qtyMinPlusRange = range(1, 20);
                $company_options = EventPlus_Models_Settings::getSettings();
                ?>

                <div class="cl2" id="discount_range_holder" <?php if ($meta_data['qty_discount'] != 'Y'): ?>style="display: none;"<?php endif; ?>>
                    <table class="widefat" cellspacing="5" cellpadding="5">
                        <tr>
                            <td>Quantity</td>
                            <td>Percentage</td>
                        </tr>
                        <?php
                        foreach ($qtyMinPlusRange as $ri => $rVal):
                            $perVal = intval($meta_data['qty_discount_settings'][$rVal]);

                            if ($perVal < 0 && is_array($company_options['qty_discount_settings'])) {

                                if (isset($company_options['qty_discount_settings'][$rVal])) {
                                    $perVal = $company_options['qty_discount_settings'][$rVal];
                                }
                            }
                            ?>
                            <tr>
                                <td>
    <?php echo $rVal; ?>+
                                </td>
                                <td> <input type="text" style="width: 20%;" maxlength="6" name="qty_discount_settings[<?php echo $rVal; ?>]" value="<?php echo (int) $perVal; ?>" /></td>
                            </tr>
<?php endforeach; ?>

                    </table>
                </div>


                <br />
                <input  type="submit" name="Submit" value="<?php echo $button_label; ?>" id="add_new_event" />
            </div></div></div></div>


<script>
    jQuery(document).ready(function () {
        jQuery('.ev--qty-discount').on('change', function (e) {
            var oSelf = jQuery(this);

            if (oSelf.val() == 'Y') {
                jQuery('#discount_range_holder').show();
            } else {
                jQuery('#discount_range_holder').hide();
            }
        });
    });
</script>