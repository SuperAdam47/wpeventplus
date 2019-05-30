<?php
$payment_vendors = (array) $company_options['payment_vendor'];
$paymentMethods = EventPlus_Models_Payments::getPaymentMethods();
?>
<div id="tab2" class="tab_content">
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
                            <option value="USD" <?php if ($company_options['default_currency'] == 'USD') echo ' selected'; ?>>USD</option>
                            <option value="JOD" <?php if ($company_options['default_currency'] == 'JOD') echo ' selected'; ?>>JOD</option>
                            <option value="TWD" <?php if ($company_options['default_currency'] == 'TWD') echo ' selected'; ?>>TWD</option>
                            <option value="TRY" <?php if ($company_options['default_currency'] == 'TRY') echo ' selected'; ?>>TRY</option>
                            <option value="TRY" <?php if ($company_options['default_currency'] == 'AED') echo ' selected'; ?>>AED</option>
                            <option value="THB" <?php if ($company_options['default_currency'] == 'THB') echo ' selected'; ?>>THB</option>
                            <option value="RUB" <?php if ($company_options['default_currency'] == 'RUB') echo ' selected'; ?>>RUB</option>
                            <option value="NOK" <?php if ($company_options['default_currency'] == 'NOK') echo ' selected'; ?>>NOK</option>
                            <option value="MYR" <?php if ($company_options['default_currency'] == 'MYR') echo ' selected'; ?>>MYR</option>
                            <option value="BRL" <?php if ($company_options['default_currency'] == 'BRL') echo ' selected'; ?>>BRL</option>
                            <option value="AUD" <?php if ($company_options['default_currency'] == 'AUD') echo ' selected'; ?>>AUD</option>
                            <option value="GBP" <?php if ($company_options['default_currency'] == 'GBP') echo ' selected'; ?>>GBP</option>
                            <option value="CAD" <?php if ($company_options['default_currency'] == 'CAD') echo ' selected'; ?>>CAD</option>
                            <option value="CZK" <?php if ($company_options['default_currency'] == 'CZK') echo ' selected'; ?>>CZK</option>
                            <option value="DKK" <?php if ($company_options['default_currency'] == 'DKK') echo ' selected'; ?>>DKK</option>
                            <option value="EUR" <?php if ($company_options['default_currency'] == 'EUR') echo ' selected'; ?>>EUR</option>
                            <option value="HKD" <?php if ($company_options['default_currency'] == 'HKD') echo ' selected'; ?>>HKD</option>
                            <option value="HUF" <?php if ($company_options['default_currency'] == 'HUF') echo ' selected'; ?>>HUF</option>
                            <option value="ILS" <?php if ($company_options['default_currency'] == 'ILS') echo ' selected'; ?>>ILS</option>
                            <option value="JPY" <?php if ($company_options['default_currency'] == 'JPY') echo ' selected'; ?>>JPY</option>
                            <option value="MXN" <?php if ($company_options['default_currency'] == 'MXN') echo ' selected'; ?>>MXN</option>
                            <option value="NZD" <?php if ($company_options['default_currency'] == 'NZD') echo ' selected'; ?>>NZD</option>
                            <option value="NOK" <?php if ($company_options['default_currency'] == 'NOK') echo ' selected'; ?>>NOK</option>
                            <option value="PLN" <?php if ($company_options['default_currency'] == 'PLN') echo ' selected'; ?>>PLN</option>
                            <option value="SGD" <?php if ($company_options['default_currency'] == 'SGD') echo ' selected'; ?>>SGD</option>
                            <option value="SEK" <?php if ($company_options['default_currency'] == 'SEK') echo ' selected'; ?>>SEK</option>
                            <option value="CHF" <?php if ($company_options['default_currency'] == 'CHF') echo ' selected'; ?>>CHF</option>
                            <option value="BOB" <?php if ($company_options['default_currency'] == 'BOB') echo ' selected'; ?>>BOB</option>
                            <option value="MUR" <?php if ($company_options['default_currency'] == 'MUR') echo ' selected'; ?>>MUR</option>
                            <option value="RON" <?php if ($company_options['default_currency'] == 'RON') echo ' selected'; ?>>RON</option>
                            <option value="LPS" <?php if ($company_options['default_currency'] == 'LPS') echo ' selected'; ?>>LPS</option>
                            <option value="RON" <?php if ($company_options['default_currency'] == 'KWR') echo ' selected'; ?>>KWR</option>
                            <option value="ZAR" <?php if ($company_options['default_currency'] == 'ZAR') echo ' selected'; ?>>ZAR</option>
                            <option value="SAR" <?php if ($company_options['default_currency'] == 'SAR') echo ' selected'; ?>>SAR</option>
                            <option value="PHP" <?php if ($company_options['default_currency'] == 'PHP') echo ' selected'; ?>>PHP</option>
                            <option value="INR" <?php if ($company_options['default_currency'] == 'INR') echo ' selected'; ?>>INR</option>
                            <option value="UGX" <?php if ($company_options['default_currency'] == 'UGX') echo ' selected'; ?>>UGX</option>
                            <option value="AOA" <?php if ($company_options['default_currency'] == 'AOA') echo ' selected'; ?>>AOA</option>
                            <option value="IDR" <?php if ($company_options['default_currency'] == 'IDR') echo ' selected'; ?>>IDR</option>
                            <option value="XOF" <?php if ($company_options['default_currency'] == 'XOF') echo ' selected'; ?>>IDR</option>
                            <option value="NGN" <?php if ($company_options['default_currency'] == 'NGN') echo ' selected'; ?>>IDR</option>
                            <option value="COP" <?php if ($company_options['default_currency'] == 'COP') echo ' selected'; ?>>COP</option>
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