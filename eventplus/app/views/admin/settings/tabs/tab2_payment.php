<?php
$payment_vendors = (array) $company_options['payment_vendor'];
$paymentMethods = EventPlus_Models_Payments::getPaymentMethods();
?>
<div id="tab2_payment" class="tab_content">
    <div class="postbox">
        <div class="inside">
            <div class="padding">
                <h1 class="stephead">
                    <?php _e('Step 2', 'evrplus_language'); ?>
                </h1>
                <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl(); ?>images/dollar-icon.png"> <?php echo _e('Payment Information', 'evrplus_language'); ?></span>
                <div class="form-table">

                    <p>
                        <label for="currency_format">
                            <?php _e('Currency Format:', 'evrplus_language'); ?>
                        </label>
                        <br />
                    <div class="styled">
                        <select name = "default_currency" class="regular-select">
                            <option value="<?php echo $company_options['default_currency']; ?>">
                                <?php _e('Select Currency', 'evrplus_language'); ?>
                            </option>

                            <?php
                            $currency_codes = EventPlus_Helpers_Currency::get_currency_list();
                            if (is_array($currency_codes)):
                                foreach ($currency_codes as $k => $currency_code):
                                    ?>
                                    <option value="<?php echo $currency_code; ?>" <?php if ($company_options['default_currency'] == $currency_code) echo ' selected'; ?>><?php echo $currency_code; ?></option>
                                <?php endforeach; ?>`
                            <?php endif; ?>`

                        </select>
                    </div>
                    </p> <br />

                    <span id="payment_vendor_holders"></span>
                    <?php foreach ($paymentMethods as $methodKey => $paymentMethod): ?>
                        <table class="table-condensed">
                            <tr style="background-color: #c0c0c0;">
                                <td width="60%"><?php echo $paymentMethod; ?></td>
                                <td  width="10%" style="text-align: right;">
                                    <?php
                                    $aTxt = __('Off', 'evrplus_language');
                                    $tabStyleCss = 'display: none;';
                                    $aClass = 'btn-danger';
                                    if (in_array($methodKey, $payment_vendors)) {
                                        $aTxt = __('On', 'evrplus_language');
                                        $aClass = 'btn-primary';
                                        $tabStyleCss = 'display: block;';
                                    }
                                    ?>
                                    <a href="#" data-tab-id="tab_<?php echo strtolower($methodKey); ?>" data-key="<?php echo $methodKey; ?>" 
                                       class="paymentMethodTrigger btn btn-mini <?php echo $aClass; ?>"><?php echo $aTxt; ?></a>
                                </td>
                            </tr>

                        </table>

                        <div id="tab_<?php echo strtolower($methodKey) ?>" style="padding: 10px; <?php echo $tabStyleCss; ?>">
                            <td colspan="2">
                                <?php include('payment_fields/' . strtolower($methodKey) . '.php'); ?>
                            </td>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function () {
        jQuery('.paymentMethodTrigger').on('click', function (e) {
            e.preventDefault();
            var oSelf = jQuery(this);
            var paymentTabId = oSelf.attr('data-tab-id');
            var oPaymentTab = jQuery('#' + paymentTabId);
            var isActive = oSelf.hasClass('btn-primary');


            if (isActive) {
                oSelf.html('Off');
                oSelf.addClass('btn-danger');
                oSelf.removeClass('btn-primary');
                oPaymentTab.hide();
            } else {
                oSelf.html('On');
                oSelf.addClass('btn-primary');
                oSelf.removeClass('btn-danger');
                oPaymentTab.show();
                oPaymentTab.focus();
            }


            jQuery('body').trigger('update_payment_fields');

        });

        jQuery('body').on('update_payment_fields', function () {

            jQuery('#payment_vendor_holders').html(''); /*reset*/

            jQuery('.paymentMethodTrigger').each(function () {
                var _oSelf = jQuery(this);
                var _isActive = _oSelf.hasClass('btn-primary');

                if (_isActive) {
                    jQuery('#payment_vendor_holders').append("<input type='hidden' name='payment_vendor[]' value='" + _oSelf.attr('data-key') + "' />");
                }
            });
        });

        jQuery('body').trigger('update_payment_fields');
    });
</script>