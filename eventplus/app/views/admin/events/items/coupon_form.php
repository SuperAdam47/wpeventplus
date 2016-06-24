<?php
$form_url = $this->adminUrl('admin_events_items/coupon_form', array('event_id' => (int) $_GET['event_id']));

$use_coupon = $oEvent->use_coupon;
$coupon_code = $oEvent->coupon_code;
$coupon_code_price = $oEvent->coupon_code_price;
?>
<div class="postbox">
    <div class="inside">
        <div class="padding" style="padding:0px !important;">
            <h2 class="cup"><span><img src="<?php echo $this->assetUrl('images/discount_icon.png'); ?>" alt="tickets" style="vertical-align:middle" /> <?php _e('Coupon Code', 'evrplus_language'); ?></span></h2>
            <form name="discount" method="post" action="<?php echo $form_url; ?>">
                <ul><li class="discountcoupon">
                        <div class="pass1"><label class="tooltip">
                                <?php _e('Do you want to use a coupon code for this event?', 'evrplus_language'); ?></label></div><div class="pass1"><p class="cs2" title="<?php _e('A coupon code is a promotional code you can tie to your event. The code is valid for a discount off the total registration cost', 'evrplus_language'); ?>"></p></div>
                        <div class="pass2"><input id="q1" type="radio" class="radio" name="use_coupon" value="Y" <?php
                            if ($use_coupon == "Y") {
                                echo "checked";
                            }
                            ?>/> <label for="q1"><?php _e('Yes', 'evrplus_language'); ?></label>
                            <input id="q2" type="radio" class="radio" name="use_coupon" value="N" <?php
                            if ($use_coupon == "N") {
                                echo "checked";
                            }
                            ?>/><label for="q2"> <?php _e('No', 'evrplus_language'); ?> </label><div></div>
                    </li>
                    <li class="discountcoupon">
                        <div class="pass1"> <label class="tooltip">
                                <br>			<?php _e('Enter the Code', 'evrplus_language'); ?><label></div><div class="pass1"><p class="cs2" title="<?php _e('This should be a one word code with no spaces or extra characters. Recommend ALL CAPS', 'evrplus_language'); ?>"></p></div>
                                    <input id="coupon_code" name="coupon_code" type="text" value="<?php echo $coupon_code; ?>"/></li>
                                    <li class="discountcoupon">
                                        <div class="pass1"><label class="tooltip">
                                                <?php _e('Discount amount for Coupon Code', 'evrplus_language'); ?></label></div><div class="pass1"><p class="cs2" title="<?php _e('Enter the amount with two decimal places.&nbsp;You MUST put a - sign before the value, otherwise this will add to the total during calculations. i.e. -10.00', 'evrplus_language'); ?> "></p></div>
                                        <input id="coupon_code_price" name="coupon_code_price" type="text" value="<?php echo $coupon_code_price; ?>"/>
                                    </li>
                                    </ul>
                                    <br /><br /><br />
                                    <input type="hidden" name="page" value="events"/>
                                    <input type="hidden" name="action" value="update_coupon"/>
                                    <input type="hidden" name="id" value="<?php echo $event_id; ?>"/>
                                    <input type="hidden" name="end" value="<?php echo $end_date; ?>"?>
                                    <button id="uyt" type="submit" style="font-size:110%; border-color:RED; background-color: #BBBBBB; color: #GGG; font-weight: bolder;"><?php _e('UPDATE COUPON CODE', 'evrplus_language'); ?></button><br /></form>
                                    </div>
                                    </div>
                                    </div>
                                    <script>
                                        jQuery(function () {
                                            jQuery(document).tooltip({
                                                position: {
                                                    my: 'left center', at: 'right+10 center',
                                                    using: function (position, feedback) {
                                                        jQuery(this).css(position);
                                                        jQuery("<div>")
                                                                .addClass("arrow")
                                                                .addClass(feedback.vertical)
                                                                .addClass(feedback.horizontal)
                                                                .appendTo(this);
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                                    <style type="text/css">
                                        .ui-tooltip.ui-widget{
                                            z-index:999999 !important;	
                                        }
                                        .ui-tooltip, .arrow:before {
                                            background: #5BA4A4;
                                            border:1px #fff solid !important;2

                                        }
                                        .ui-tooltip {
                                            padding: 10px 10px;
                                            color: white;

                                            font: bold 13px "Helvetica Neue", Sans-Serif;
                                        }
                                        .arrow {
                                            width: 70px;
                                            height: 25px;
                                            overflow: hidden;
                                            position: absolute;


                                            bottom: 5px;
                                            left: -26px;
                                            z-index: -1;
                                        }
                                        .arrow{display:none !important;}
                                        .arrow:before {
                                            content: "";
                                            position: absolute;
                                            left: 20px;
                                            top: 0px;
                                            width: 25px;
                                            height: 25px;

                                            -webkit-transform: rotate(45deg);
                                            -moz-transform: rotate(45deg);
                                            -ms-transform: rotate(45deg);
                                            -o-transform: rotate(45deg);
                                            tranform: rotate(45deg);
                                        }

                                    </style>