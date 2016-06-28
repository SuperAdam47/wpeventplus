
<div id="tab2" class="tab_content">
    <div class="postbox " >
        <div class="inside">
            <div class="padding">
                <h1 class="stephead">
                    <?php _e('Step 2', 'evrplus_language'); ?>
                </h1>
                <span class="steptitle"><img class="stepimg" src="<?php echo $this->assetUrl(); ?>images/dollar-icon.png"> <?php echo _e('Payment Information', 'evrplus_language'); ?></span>
                <div class="form-table">
                    <p>
                        <label for="payment_vendor">
                            <?php _e('Online Payment Vendor:', 'evrplus_language'); ?>
                        </label>
                        <br />
                    <div class="styled">
                    
                        <select name="payment_vendor" id="payment_vendor" class="regular-select" onchange="showDiv(this)">
                            <!--<option value="<?php echo $company_options['payment_vendor']; ?>"><?php echo $company_options['payment_vendor']; ?></option>-->
                            <option value="AUTHORIZE" <?php if ($company_options['payment_vendor'] == 'AUTHORIZE') echo ' selected'; ?>>AUTHORIZE.NET</option>
                            <option value="PAYPAL" <?php if ($company_options['payment_vendor'] == 'PAYPAL') echo ' selected'; ?>>PAYPAL</option>
                            <option value="NONE" <?php if ($company_options['payment_vendor'] == 'NONE') echo ' selected'; ?>>Offline Payment</option>
                            <option value="STRIPEACTIVE" <?php if ($company_options['payment_vendor'] == 'STRIPEACTIVE') echo ' selected'; ?>>STRIPE PAYMENT</option>

                            <!--<option value="GOOGLE">GOOGLE</option>
                    <option value="MONSTER">MONSTER PAY</option>
                    <option value="CUSTOM">CUSTOM</option>
                            -->
                        </select>
                    </div>
                    </p>

                </div>
                <div class="showHideDivfirst" id="Divfirst" <?php
                if ($company_options['payment_vendor'] == 'PAYPAL') {
                    echo "style='display:block'";
                }else{
                      echo "style='display:none;'";
                }
                ?>>

                    <p>
                        <label for="pay_msg">
                            <?php _e('Payment Message on Confirmation Screen', 'evrplus_language'); ?>
                        </label>
                        <br />
                        <input class="regular-text"   name="pay_msg" value="<?php
                        if ($company_options['pay_msg'] != "") {
                            echo stripslashes($company_options['pay_msg']);
                        } else {
                            _e("", 'evrplus_language');
                        }
                        ?>"  maxlength="93" size="70" type="text"/>
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
                            <!--<option value="<?php echo $company_options['default_currency']; ?>" ><?php echo $company_options['default_currency']; ?> </option>-->
                            <option value="USD" <?php if ($company_options['default_currency'] == 'USD') echo ' selected'; ?>>USD</option>
                            <option value="TWD" <?php if ($company_options['default_currency'] == 'TWD') echo ' selected'; ?>>TWD</option>
                            <option value="TRY" <?php if ($company_options['default_currency'] == 'TRY') echo ' selected'; ?>>TRY</option>
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
                            <option value="PHP" <?php if ($company_options['default_currency'] == 'PHP') echo ' selected'; ?>>PHP</option>
                        </select>
                    </div>
                    </p>
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
                    <p>
                        <label for="accept_donations">
                            <?php _e('Will you accept donations?', 'evrplus_language'); ?>
                        </label>
                        <br />
                    <div class="styled">
                        <select name = 'donations' class="regular-select">
                            <!--<option value="<?php echo $company_options['donations']; ?>"><?php echo $company_options['donations']; ?> </option>-->
                            <option value="Yes" <?php if ($company_options['donations'] == 'Yes') echo ' selected'; ?>>
                                <?php _e('Yes', 'evrplus_language'); ?>
                            </option>
                            <option value="No" <?php if ($company_options['donations'] == 'No') echo ' selected'; ?>>No</option>
                        </select>
                    </div>
                    </p>

                    <div class="form-table">
                        <h1 class="stephead"><?php echo _e('Advanced', 'evrplus_language'); ?></h1>
                        <span class="steptitle"> <img class="stepimg" src="<?php echo $this->assetUrl(); ?>images/paypal-icon.png"><?php echo _e('For Paypal Users Only', 'evrplus_language'); ?> </span>
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
                                    <?php
                                    //if ($company_options['return_method']=="1"){echo "<option value='1'>".__('GET','evrplus_language')."</option>";}
                                    //if ($company_options['return_method']=="2"){echo "<option value='2'>".__('POST','evrplus_language')."</option>";}
                                    ?>
                                    <option value="1" <?php if ($company_options['return_method'] == "1") echo ' selected'; ?>>
                                        <?php _e('GET'); ?>
                                    </option>
                                    <option value="2" <?php if ($company_options['return_method'] == "2") echo ' selected'; ?>>
                                        <?php _e('POST'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <p>
                            <label for="use_sandbox">
                                <?php _e('Use PayPal Sandbox', 'evrplus_language'); ?>
                                <br />
                                <font size="-6">
                                <?php _e('(used for testing/debug)', 'evrplus_language'); ?>
                                </font></label>
                            <br />
                            <input id="male" type="radio" name="use_sandbox" value="Y" <?php
                            if ($company_options['use_sandbox'] == "Y") {
                                echo "checked";
                            }
                            ?>/>
                            <label class="labels" for="male">
                                <?php _e('Yes', 'evrplus_language'); ?>
                            </label>
                            <input id="female" type="radio" name="use_sandbox" value="N" <?php
                            if ($company_options['use_sandbox'] == "N") {
                                echo "checked";
                            }
                            ?>/>
                            <label class="labels" for="female">
                                <?php _e('No', 'evrplus_language'); ?>
                            </label>
                        </p>
                    </div>
                </div>
                <?php /*                 * *******************STRIPE API KEY******************** */ ?>
                <div class="showHideDivsecond" id="Divsecond" <?php
                if ($company_options['payment_vendor'] == 'STRIPEACTIVE') {
                    echo "style='display:block'";
                }else{
                       echo "style='display:none;'";
                }
                ?>>
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
                    <?php /*                     * ****************stripe return url ****** */ ?>
                    <p>
                        <label for="payment_vendor_id">
                            <?php _e('Stripe Return Url', 'evrplus_language'); ?>
                        </label>
                        <br />
                        <input class="regular-text" type="text" value="<?php echo $company_options['stripereturn_url']; ?>" size="60" name="stripereturn_url">
                    </p>

                    <?php /*                     * ****************stripe return url ****** */ ?>
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
                            <!--<option value="<?php echo $company_options['default_currency']; ?>" ><?php echo $company_options['default_currency']; ?> </option>-->
                            <option value="USD" <?php if ($company_options['default_currency'] == 'USD') echo ' selected'; ?>>USD</option>
                            <option value="TWD" <?php if ($company_options['default_currency'] == 'TWD') echo ' selected'; ?>>TWD</option>
                            <option value="TRY" <?php if ($company_options['default_currency'] == 'TRY') echo ' selected'; ?>>TRY</option>
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
                        </select>
                    </div>
                    </p>
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
                    <p>
                        <label for="accept_donations">
                            <?php _e('Will you accept donations?', 'evrplus_language'); ?>
                        </label>
                        <br />
                    <div class="styled">
                        <select name = 'donations' class="regular-select">
                            <!--<option value="<?php echo $company_options['donations']; ?>"><?php echo $company_options['donations']; ?> </option>-->
                            <option value="Yes" <?php if ($company_options['donations'] == 'Yes') echo ' selected'; ?>>
                                <?php _e('Yes', 'evrplus_language'); ?>
                            </option>
                            <option value="No" <?php if ($company_options['donations'] == 'No') echo ' selected'; ?>>No</option>
                        </select>
                    </div>
                    </p>
                </div>
                <?php /*                 * *******************STRIPE API KEY******************** */ ?>
                <?php /*                 * *******************Authorized.Net Key******************** */ ?>
                <div id="authorizeShowhide" class="authorizeShowhide" <?php
                if ($company_options['payment_vendor'] == 'AUTHORIZE') {
                    echo "style='display:block'";
                }else{
                    echo "style='display:nonde'";
                }
                ?>>

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
                        <label for="currency_format">
                            <?php _e('Currency Format:', 'evrplus_language'); ?>
                        </label>
                        <br />
                    <div class="styled">
                        <select name = "default_currency" class="regular-select">
                            <option value="<?php echo $company_options['default_currency']; ?>">
                                <?php _e('Select Currency', 'evrplus_language'); ?>
                            </option>
                            <!--<option value="<?php echo $company_options['default_currency']; ?>" ><?php echo $company_options['default_currency']; ?> </option>-->
                            <option value="USD" <?php if ($company_options['default_currency'] == 'USD') echo ' selected'; ?>>USD</option>
                            <option value="TWD" <?php if ($company_options['default_currency'] == 'TWD') echo ' selected'; ?>>TWD</option>
                            <option value="TRY" <?php if ($company_options['default_currency'] == 'TRY') echo ' selected'; ?>>TRY</option>
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
                        </select>
                    </div>
                    </p>
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
                    <p>
                        <label for="accept_donations">
                            <?php _e('Will you accept donations?', 'evrplus_language'); ?>
                        </label>
                        <br />
                    <div class="styled">
                        <select name = 'donations' class="regular-select">
                            <!--<option value="<?php echo $company_options['donations']; ?>"><?php echo $company_options['donations']; ?> </option>-->
                            <option value="Yes" <?php if ($company_options['donations'] == 'Yes') echo ' selected'; ?>>
                                <?php _e('Yes', 'evrplus_language'); ?>
                            </option>
                            <option value="No" <?php if ($company_options['donations'] == 'No') echo ' selected'; ?>>No</option>
                        </select>
                    </div>
                    </p>
                </div>
                <?php /*                 * *******************Authorized.Net Key******************** */ ?>
            </div>
        </div>
    </div>
</div>