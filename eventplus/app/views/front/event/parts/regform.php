<?php if ($company_options['form_css'] != ''): ?>
    <style>
    <?php echo $company_options['form_css']; ?>
    </style>
<?php endif; ?>

<script>
    var validationErrors = {
        invalid: "<?php echo __('Invalid', 'evrplus_language'); ?>",
        required: "<?php echo __('Required', 'evrplus_language'); ?>",
        fname: "<?php echo __('Please enter your first name', 'evrplus_language'); ?>",
        lname: "<?php echo __('Please enter your last name', 'evrplus_language'); ?>",
        email: "<?php echo __('Email format not correct!', 'evrplus_language'); ?>",
        phone: "<?php echo __('Please enter your phone number', 'evrplus_language'); ?>",
        phone_invalid: "<?php echo __('Please use correct format for your phone number', 'evrplus_language'); ?>",
        address: "<?php echo __('Please enter your address', 'evrplus_language'); ?>",
        city: "<?php echo __('Please enter your city', 'evrplus_language'); ?>",
        state: "<?php echo __('Please enter your state', 'evrplus_language'); ?>",
        zip: "<?php echo __('Please enter your zip/postal code', 'evrplus_language'); ?>",
        accept_terms: "<?php echo __("You didn't accept terms and conditions!", 'evrplus_language'); ?>!"
    };
</script>
<?php
global $noImage;

$curdate = date("Y-m-j");

foreach ($rows as $event) {
    include "_event_array2string.php";
}

$cap_url = $this->assetUrl('cimg/');
$md5_url = $this->assetUrl("js/md5.js");

if ($display_desc == "Y") {
    $dsply = "block";
} else {
    $dsply = "none";
}

$url = urlencode(add_query_arg(array('action' => 'evrplusegister', 'event_id' => $event->id), get_permalink(get_page_by_path('evrplus_registration'))));

$d_format = date_i18n($evrplus_date_format, strtotime($event->start_date));
if (isset($_GET['recurr'])) {
    $d_format = date_i18n($evrplus_date_format, $_GET['recurr']);
} elseif ($recurr) {
    $d_format = date_i18n($evrplus_date_format, $recurr);
}

if (isset($company_options['time_format']) and $company_options['time_format'] == '24hrs') {
    $start_time = date('H:i', strtotime($start_time));
    $end_time = date('H:i', strtotime($end_time));
}

$captcha = "N";
if ($company_options['captcha'] == 'Y') {
    $captcha = "Y";
}

$tax_rate = .0;
if ($company_options['use_sales_tax'] == "Y") {
    $tax_rate = .0875;
    if ($company_options['sales_tax_rate'] != "") {
        $tax_rate = $company_options['sales_tax_rate'];
    }
}

$current_dt = date('Y-m-d H:i', current_time('timestamp', 0));
if ($event_close == "start") {
    $close_dt = $start_date . " " . $start_time;
} else if ($event_close == "end") {
    $close_dt = $end_date . " " . $end_time;
} else if ($event_close == "") {
    $close_dt = $start_date . " " . $start_time;
}

$stp = DATE("Y-m-d H:i", STRTOTIME($close_dt));
$expiration_date = strtotime($stp);
if (isset($_GET['recurr']) and $_GET['recurr'])
    $expiration_date = $_GET['recurr'];
elseif ($recurr)
    $expiration_date = $recurr;
$today = strtotime($current_dt);

$sqlEndDate = "SELECT start_date FROM " . get_option('evr_event') . " WHERE id = " . (int) $event_id . "";
$resultEndDate = $wpdb->get_var($sqlEndDate);

if (isset($_GET['recurr']))
    $resultEndDate = date_i18n('d-m-Y', $_GET['recurr']);
elseif ($recurr)
    $resultEndDate = date_i18n('d-m-Y', $recurr);
$close_dt = $end_date . " " . $end_time;

#See how many seats are left available
$available = evrplus_get_open_seats($event->id, $event->reg_limit);

$oMeta = new EventPlus_Models_Events_Meta();
$show_register_button = $oMeta->getOption($event_id, 'show_register_button');

$show_form_bool = 0;
if ($show_register_button == '' || !in_array(strtolower($show_register_button), array('y', 'n')) || strtolower($show_register_button) == 'n') {
    $show_form_bool = 1;
}

$term_c_force = '';
if (isset($event_meta_data)) {
    if (isset($event_meta_data['term_c_force'])) {
        $term_c_force = $event_meta_data['term_c_force'];
    }
}
?>

<div class="events-plus-2">
    <div class="event-single" id="event-slug">
        <div class="row">
            <div class="col-xs-12">
                <div class="bann3r">
                    <?php if (isset($company_options['show_social_icons']) && !empty($company_options['show_social_icons']) && $company_options['show_social_icons'] != '2'): ?>
                        <div class="s0cial">
                            <a href="<?php echo 'https://twitter.com/home?status=' . $event_name . ' - ' . $url . ''; ?>" class="twitter evrplus_socialtwitter"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" class="facebook evrplus_socialfacebook"><i class="fa fa-facebook-f"></i></a>
                        </div>
                    <?php endif; ?>

                    <?php
                    $showButtonCalendar = true;
                    if (isset($company_options['evrplus_flag_add_to_cal_button'])) {
                        if ($company_options['evrplus_flag_add_to_cal_button'] == 'N') {
                            $showButtonCalendar = false;
                        }
                    }

                    if ($showButtonCalendar):
                        ?>

                        <div class="acti0n">
                            <a href="<?php echo EVENT_PLUS_PUBLIC_URL; ?>add_to_calednar.php?event_id=<?php echo $event_id; ?>" class="evrplus_addToCalendar btn btn-larg3 btn-ic0n cal3ndar"><?php echo __('Add to your calendar', 'evrplus_language'); ?></a>
                        </div>

                    <?php endif; ?>
                    <?php
                    $noImage = false;

                    if ($header_image != "header_image" && $header_image != "") {
                        $noImage = true;
                        ?>
                        <img src="<?php echo $header_image; ?>" alt="<?php echo $event_name; ?>" />
                    <?php } else { ?>
                        <div style="height:100px;">&nbsp;</div>
                    <?php } ?>
                </div>
                <h2 class="ti8le"><?php echo $event_name; ?></h2>
            </div>
            <div class="col-xs-12">
                <div class="row-eq-height me8a">
                    <div class="col-xs-6 it3m">
                        <i class="fa fa-2x fa-calendar"></i>
                        <div class="d3sc">
                            <h4>
                                <?php echo $d_format; ?> 
                                <?php if ($end_date != $start_date and $end_year != '2050'): ?> - <?php echo date_i18n($evrplus_date_format, strtotime($event->end_date)); ?><?php endif; ?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-xs-6 it3m">
                        <i class="fa fa-2x fa-clock-o"></i>
                        <div class="d3sc">
                            <h4>
                                <?php echo $start_time . " - " . $end_time; ?>
                            </h4>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="d3sc">
                    <p><?php echo html_entity_decode(nl2br($event_desc)); ?></p>
                </div>

                <?php if ($google_map == "Y"): ?>
                    <?php
                    $event_address_map = str_replace(" ", "+", $event_address);
                    $event_city_map = str_replace(" ", "+", $event_city);
                    $event_state_map = str_replace(" ", "+", $event_state);
                    $event_country_map = str_replace(" ", "+", $event_country);
                    if (isset($company_options['googleMap_api_key']) and ! empty($company_options['googleMap_api_key']))
                        echo '<iframe class="ma9" width="100%" height="220" frameborder="0" src="https://www.google.com/maps/embed/v1/place?key=' . $company_options['googleMap_api_key'] . '&q=' . $event_address_map . ',' . $event_city_map . ',' . ( (!$event_state_map) ? $event_postal : $event_state_map) . ',' . $event_country_map . '"></iframe>';
                    else
                        echo '<iframe class="ma9" width="100%" height="220" frameborder="0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDblf6OIl46COqBYUo2DBaxo0-PRl9SZEM&q=' . $event_address_map . ',' . $event_city_map . ',' . ( (!$event_state_map) ? $event_postal : $event_state_map) . ',' . $event_country_map . '"></iframe>';
                    ?>
                <?php endif; ?>

                <div class="row-eq-height me8a al8">
                    <div class="col-xs-6 it3m">
                        <i class="fa fa-2x fa-map-marker"></i>
                        <div class="d3sc">
                            <h4><?php _e('Event Location', 'evrplus_language'); ?></h4>

                            <?php
                            $eventLocationStr = '';
                            if ($event_location != '') {
                                $eventLocationStr .= stripslashes($event_location);
                            }

                            if ($event_city != '') {
                                $eventLocationStr .= '<br />' . $event_city;
                                if ($event_state != '') {
                                    $eventLocationStr .= ', ' . $event_state;
                                }
                                if ($event_postal != '') {
                                    $eventLocationStr .= ', ' . $event_postal;
                                }
                            }
                            ?>
                            <?php echo $eventLocationStr; ?>
                        </div>
                    </div>
                    <div class="col-xs-6 it3m">
                        <i class="fa fa-2x fa-money"></i>
                        <div class="d3sc">
                            <h4><?php _e('Event Fees', 'evrplus_language'); ?></h4>
                            <?php
                            $curdate = date("Y-m-d");
                            $sql2 = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . $event_id . " ORDER BY sequence ASC";
                            $result2 = $wpdb->get_results($sql2, ARRAY_A);

                            foreach ($result2 as $row2) {
                                $item_id = $row2['id'];
                                $item_sequence = $row2['sequence'];
                                $event_id = $row2['event_id'];
                                $item_title = $row2['item_title'];
                                $item_description = $row2['item_description'];
                                $item_cat = $row2['item_cat'];
                                $item_limit = $row2['item_limit'];
                                $item_price = $row2['item_price'];
                                $free_item = $row2['free_item'];
                                $item_start_date = $row2['item_available_start_date'];
                                $item_end_date = $row2['item_available_end_date'];
                                $item_custom_cur = $row2['item_custom_cur'];

                                if ($item_custom_cur == "GBP") {
                                    $item_custom_cur = "&pound;";
                                }

                                if ($item_custom_cur == "USD") {
                                    $item_custom_cur = "$";
                                }

                                if ($fee->item_custom_cur == "BRL") {
                                    $item_custom_cur = "R$";
                                }

                                if ((float) $item_price == 0.0) {
                                    $item_custom_cur = "";
                                    $item_price = __('FREE', 'evrplus_language');
                                }

                                echo '<div class="row">'
                                . ' <div class="col-xs-6">' . $item_title . '</div>'
                                . ' <div class="col-xs-6">' . $item_custom_cur . ' ' . $item_price . '</div>' .
                                '</div>';
                            }

                            if (!$result2) {
                                echo '<div class="row">'
                                . ' <div class="col-xs-6">' . _e('FREE', 'evrplus_language') . '</div>' .
                                '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php
                if ($counter_checks == 'Y'):
                    $sql_status = "SELECT * FROM " . get_option('evr_event') . " WHERE id = " . (int) $event_id . "";
                    $recurring_status_ex = $wpdb->get_results($sql_status);
                    $recurring_status = $recurring_status_ex[0]->recurrence_choice;
                    if ($recurring_status == 'no'):
                        ?>
                        <div class="coun8" id="details">
                            <div class="evrplus_counter">
                                <div id="evrplus_counter" class="redCountdownDemo"></div>
                                <div class="timer">
                                    <div class="days"><?php _e('Days', 'evrplus_language'); ?></div>
                                    <div class="hours"><?php _e('Hours', 'evrplus_language'); ?></div>
                                    <div class="min"><?php _e('Minutes', 'evrplus_language'); ?></div>
                                    <div class="sec"><?php _e('Seconds', 'evrplus_language'); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {

                            var endDate = new Date(<?php echo EventPlus_Helpers_Funx::getTimestamp($resultEndDate) ?>);
                            $('#evrplus_counter').redCountdown({
                                end: $.now() + (((endDate.getTime() * 1000) - $.now()) / 1000),
                                labels: true,
                                style: {
                                    element: "",
                                    textResponsive: .5,
                                    daysElement: {
                                        gauge: {
                                            thickness: .2,
                                            bgColor: "#cccccc",
                                            fgColor: "#1ABC9C"
                                        },
                                        textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                                    },
                                    hoursElement: {
                                        gauge: {
                                            thickness: .2,
                                            bgColor: "#cccccc",
                                            fgColor: "#2980B9"
                                        },
                                        textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                                    },
                                    minutesElement: {
                                        gauge: {
                                            thickness: .2,
                                            bgColor: "#cccccc",
                                            fgColor: "#8E44AD"
                                        },
                                        textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                                    },
                                    secondsElement: {
                                        gauge: {
                                            thickness: .2,
                                            bgColor: "#cccccc",
                                            fgColor: "#F39C12"
                                        },
                                        textCSS: 'font-family:Arial; font-size:25px; font-weight:300; color:#262626;'
                                    }
                                },
                                onEndCallback: function () {

                                }});
                        });
                    </script>
                    <?php
                endif;
                ?>
                <?php if (($disable_event_reg != '' && $disable_event_reg != 'Y') || $more_info != ""): ?>
                    <div class="ac8ion" id="eventplus_actions_registration_btns">
                        <?php if ($disable_event_reg != 'Y'): ?>
                            <?php if ($outside_reg == "Y"): ?>
                                <a href="<?php echo $external_site; ?>" class="btn btn-ic0n regis8er eventplus-registration-actions" id="regist3r-action"><?php echo __('REGISTER', 'evrplus_language'); ?></a>
                            <?php else: ?>
                                <a id="eventplus_register_btn" href="#" class="btn btn-ic0n regis8er eventplus-registration-actions" data-show-form-default="<?php echo $show_form_bool; ?>"><?php echo __('REGISTER', 'evrplus_language'); ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($more_info != ""): ?>
                            <a href="#" class="btn btn-ic0n m0re-info eventplus-registration-actions" onClick="window.open('<?php echo $more_info; ?>');
                                    return false;"><?php echo __('MORE INFO', 'evrplus_language'); ?></a>
                           <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($disable_event_reg != "Y"): ?>
                    <script type="text/javascript" src="<?php echo $md5_url; ?>"></script>
                    <script>
                            var discountSettings = new Array();
                    </script>
                    <?php
                    $oEventDiscounts = new EventPlus_Models_Events_Discounts();
                    $discountSettings = array(); //$oEventDiscounts->getSettings($event_id);

                    $discountPercentage = 0;
                    if (count($discountSettings) > 0 && is_array($discountSettings)) {
                        $discountDataset = EventPlus_Helpers_Event::getPercentageDataset($discountSettings);

                        if (count($discountDataset) > 0) {
                            ?>
                            <script>
            <?php foreach ($discountDataset as $qty => $percentage): ?>
                                    discountSettings['<?php echo $qty; ?>'] = "<?php echo $qty; ?>:<?php echo $percentage; ?>";
            <?php endforeach; ?>
                            </script>
                            <?php
                        }
                    }
                    ?>
                    <script type="text/javascript" src="<?php echo $this->assetUrl('front/funx.js?v=' . time()); ?>"></script> 

                    <?php
                    if ($disable_event_reg != 'Y'):
                        $eventplus_token = EventPlus_Helpers_Token::doToken($event_id);
                        $pendingTokenRow = EventPlus_Helpers_Token::getPendingRow();
                        ?>
                        <div class="col-md-10 col-md-offset-1 col-xs-12 regis8er-form" id="evrplusRegForm" style="display: none;">
                            <?php if ($expiration_date <= $today): ?>
                                <?php
                                echo '<div class="info-m3ssages">';
                                _e('Registration is closed for this event.', 'evrplus_language');
                                _e('For more information or questions, please email: ', 'evrplus_language');
                                echo '<a href="mailto:' . $company_options['company_email'] . '">' . $company_options['company_email'] . '</a>';
                                echo'</div>';
                                ?>
                            <?php else: ?>

                                <form  name="regform"  class="evrplus_regform" method="post" 
                                       action="<?php echo evrplus_permalink($company_options['evrplus_page_id']); ?>"  
                                       onSubmit="mySubmit.disabled = true;
                                        return validateForm(this)">

                                    <?php
                                    $formFieldStyle = ' ';
                                    if ($term_c_force == 'Y' && $term_c == 'Y'):
                                        $formFieldStyle = ' style="display:none;"';
                                        ?>
                                        <div class="row">
                                            <div class="col-xs-12 fi3ld">
                                                <label class="checkb0x"><input checked="false" type="checkbox" id="eventplus_terms_cbox" name="accept_term" value="1" /> <?php echo __('I accept the terms and conditions', 'evrplus_language'); ?></label>
                                                <div class="t3rms"><?php echo html_entity_decode($term_desc); ?></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>


                                    <div id="eventplus_form_fields"<?php echo $formFieldStyle; ?>>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12 fi3ld fi3ld-with-icon us3r">
                                                <input class="eplus-required" type="text" name="fname" id="fname" value="<?php echo $pendingTokenRow['fname']; ?>" placeholder="<?php echo __('First Name', 'evrplus_language'); ?>">
                                            </div>
                                            <div class="col-sm-6 col-xs-12 fi3ld fi3ld-with-icon us3r">
                                                <input class="eplus-required" type="text" name="lname" id="lname" value="<?php echo $pendingTokenRow['lname']; ?>" placeholder="<?php echo __('Last Name', 'evrplus_language'); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12 fi3ld fi3ld-with-icon emai7">
                                                <input class="eplus-required" type="email" name="email" id="email" value="<?php echo $pendingTokenRow['email']; ?>" placeholder="<?php echo __('Email Address', 'evrplus_language'); ?>">
                                            </div>
                                            <?php if ($inc_phone == "Y"): ?>
                                                <div class="col-sm-6 col-xs-12 fi3ld fi3ld-with-icon te7">
                                                    <input class="eplus-required eplus-phone" type="text" name="phone" id="phone" value="<?php echo $pendingTokenRow['phone']; ?>" placeholder="<?php echo __('Phone Number', 'evrplus_language'); ?>">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row">
                                            <?php if ($inc_address == "Y"): ?>

                                                <div class="<?php if ($inc_country == 'Y'): ?>col-xs-8<?php else: ?>col-xs-12<?php endif; ?> fi3ld fi3ld-with-icon addr3ss">
                                                    <input class="eplus-required"  type="text" name="address" id="address" value="<?php echo $pendingTokenRow['address']; ?>" placeholder="<?php echo __('Street/PO Address', 'evrplus_language'); ?>">
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($inc_country == "Y"): ?>
                                                <div class="<?php if ($inc_address == 'Y'): ?>col-xs-4<?php else: ?>col-xs-12<?php endif; ?> fi3ld">
                                                    <input class="eplus-required"  type="text" name="country" id="country" value="<?php echo $pendingTokenRow['country']; ?>" placeholder="<?php echo __('Country', 'evrplus_language'); ?>">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row">

                                            <?php if ($inc_city == "Y"): ?>
                                                <div class="col-sm-4 col-xs-12 fi3ld">
                                                    <input class="eplus-required" type="text" name="city" id="city" value="<?php echo $pendingTokenRow['city']; ?>" placeholder="<?php echo __('City', 'evrplus_language'); ?>">
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($inc_state == "Y"): ?>
                                                <div class="col-sm-4 col-xs-12 fi3ld">
                                                    <input class="eplus-required" type="text" name="state" id="state" value="<?php echo $pendingTokenRow['state']; ?>" placeholder="<?php echo __('State', 'evrplus_language'); ?>">
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($inc_zip == "Y"): ?>
                                                <div class="col-sm-4 col-xs-12 fi3ld">
                                                    <input class="eplus-required"  type="text" name="zip" id="zip" value="<?php echo $pendingTokenRow['zip']; ?>" placeholder="<?php echo __('Postal/Zip Code', 'evrplus_language'); ?>" />
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row">
                                            <?php
                                            $company_form_fields = array(
                                                'company' => array('title' => __('Company Name', 'evrplus_language'), 'flag' => $inc_comp),
                                                'co_address' => array('title' => __('Company Address', 'evrplus_language'), 'flag' => $inc_coadd),
                                                'co_city' => array('title' => __('Company City', 'evrplus_language'), 'flag' => $inc_cocity),
                                                'co_state' => array('title' => __('Company State/Province', 'evrplus_language'), 'flag' => $inc_costate),
                                                'co_zip' => array('title' => __('Company Postal Code', 'evrplus_language'), 'flag' => $inc_copostal),
                                                'co_phone' => array('title' => __('Company Phone', 'evrplus_language'), 'flag' => $inc_cophone),
                                            );
                                            ?>

                                            <?php foreach ($company_form_fields as $field => $fieldSet): ?>
                                                <?php if ($fieldSet['flag']): ?>
                                                    <div class="col-sm-6 col-xs-12 fi3ld">
                                                        <input type="text" name="<?php echo $field; ?>" id="country" value="<?php echo $pendingTokenRow[$field]; ?>" placeholder="<?php echo $fieldSet['title']; ?>">
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>


                                            <?php
                                            $questions = $wpdb->get_results("SELECT * from " . get_option('evr_question') . " where event_id = '" . (int) $event_id . "' order by sequence");
                                            if ($questions) :
                                                foreach ($questions as $question):
                                                    $title = '';
                                                    if ($question->remark) {
                                                        $title = $question->remark;
                                                    }
                                                    ?>
                                                    <div class="col-xs-12 fi3ld"  title="<?php echo $title; ?>">
                                                        <?php echo $this->View('front/event/parts/inc/form_fields', array('question' => $question)); ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <?php if ($use_coupon == "Y"): ?>
                                                <div class="col-xs-6 fi3ld"  title="<?php echo $title; ?>">
                                                    <p><?php echo __('Enter coupon code for discount', 'evrplus_language'); ?></p>
                                                    <input type="text" name="coupon" id="coupon" value="" />
                                                </div>
                                            <?php endif;
                                            ?>

                                            <?php
                                            #If there is at least one seat available then begin display of event pricing and allow registration, else no fees notice.                               
                                            if ($available >= 1):
                                                $sql = "SELECT * FROM " . get_option('evr_cost') . " WHERE event_id = " . (int) $event_id . " ORDER BY sequence ASC";
                                                $rows = $wpdb->get_results($sql);
                                                if ($rows):
                                                    $open_seats = $available;
                                                    $curdate = date("Y-m-d");
                                                    $fee_count = 0;
                                                    $isfees = "N";
                                                    ?>
                                                    <div class="col-xs-12 fi3ld">
                                                        <h3 class="section-ti8le"><i class="fa fa-calculator"></i> <?php _e('Registration Fees', 'evrplus_language'); ?></h3>
                                                    </div>

                                                    <div class="col-xs-12" id="event_fee_item_message">
                                                        <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i> <?php _e('You must select at least one item!', 'evrplus_language'); ?></div>
                                                    </div>


                                                    <?php
                                                    foreach ($rows as $fee):
                                                        #check fee dates and if date range is valid, display fee
                                                        if ((evrplus_greaterDate($curdate, $fee->item_available_start_date)) && (evrplus_greaterDate($fee->item_available_end_date, $curdate))):
                                                            $req = '';
                                                            $isfees = "Y";
                                                            ?>
                                                            <input type="hidden" name="reg_type" value="RGLR"/>  

                                                            <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 fi3ld" title="<?php echo $fee->item_description; ?>">
                                                                <p>  <?php
                                                                    #Display Fee description and cost.
                                                                    if ($fee->item_custom_cur == "GBP") {
                                                                        $item_custom_cur = "&pound;";
                                                                    }
                                                                    if ($fee->item_custom_cur == "USD") {
                                                                        $item_custom_cur = "$";
                                                                    }
                                                                    if ($fee->item_custom_cur == "BRL") {
                                                                        $item_custom_cur = "R$";
                                                                    }
                                                                    echo $fee->item_title . "    " . $item_custom_cur . " " . $fee->item_price;
                                                                    ?></p>
                                                                <select name = "PROD_<?php echo $fee->event_id; ?>-<?php echo $fee->id; ?>_<?php echo $fee->item_price; ?>"
                                                                        id = "PROD_<?php echo $fee->event_id; ?>-<?php echo $fee->id; ?>_<?php echo $fee->item_price; ?>"
                                                                        class="eventplus-ddl-items" 
                                                                        onChange="<?php
                                                                        if ($company_options['use_sales_tax'] == "Y") {
                                                                            echo 'CalculateTotalTax(this.form)';
                                                                        } else {
                                                                            echo 'CalculateTotal(this.form)';
                                                                        }
                                                                        ?>"
                                                                        >
                                                                    <option value="0">0</option>
                                                                    <?php
                                                                    #Begin generation of DropDown Box - Options
                                                                    #Check to see if the item is a REG type.  If REG, set options count based on seating availability/ ticke limits
                                                                    if ($fee->item_cat == "REG") {
                                                                        if ($fee->item_limit != "") {
                                                                            if ($available >= $fee->item_limit) {
                                                                                $units_available = $fee->item_limit;
                                                                            } else {
                                                                                $units_available = $available;
                                                                            }
                                                                        }
                                                                        for ($i = 1; $i <= $units_available; $i++) {
                                                                            ?>
                                                                            <option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    #If item is not REG type, and no limit was set, limit options to 10
                                                                    if ($fee->item_cat != "REG") {
                                                                        $num_select = "10";
                                                                        if ($fee->item_limit != "") {
                                                                            $num_select = $fee->item_limit;
                                                                        }
                                                                        for ($i = 1; $i < $num_select + 1; $i++) {
                                                                            ?> 
                                                                            <option value="<?php echo ($i); ?>"><?php echo ($i); ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>


                                                            </div>
                                                            <div class="clearfix"></div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>



                                                    <?php if ($isfees == "N"): ?>
                                                        <div class="col-xs-12">
                                                            <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i> <?php _e('No Fees/Items available for todays date!', 'evrplus_language'); ?></div>
                                                        </div>
                                                        <input type="hidden" name="reg_type" value="WAIT" />
                                                    <?php else: ?>

                                                        <div class="clearfix"></div>
                                                        <div class="col-md-8 col-sm-8 col-xs-12" id="eplus-data-summary-container">
                                                            <table width="100%" cellpadding="0" cellspacing="0" class="data-summary">
                                                                <tbody>
                                                                    <tr>
                                                                        <td width="60%"><?php _e('Registration Fees', 'evrplus_language'); ?></td>
                                                                        <td width="40%" align="right"><input style="width: 100px" type="text" name="fees" id="fees" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/></td>
                                                                    </tr>
                                                                    <?php if ($company_options['use_sales_tax'] == "Y"): ?>
                                                                        <tr>
                                                                            <td><?php _e('Sales Tax', 'evrplus_language'); ?></td>
                                                                            <td align="right"><input style="width: 100px" type="text" name="tax" id="tax" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/></td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                    <?php if (count($discountSettings) > 0 && is_array($discountSettings)): ?>
                                                                        <tr>
                                                                            <td width="60%"><?php _e('Discount', 'evrplus_language'); ?></td>
                                                                            <td width="40%" align="right"><input style="width: 100px" type="text" id="discount" name="discount" size="10" value="0.00" readonly="readyonly" onFocus="this.form.elements[0].focus()"/></td>
                                                                        </tr>
                                                                    <?php else: ?>
                                                                        <tr style="display:none;"><td>
                                                                                <input type="hidden" id="discount" name="discount" size="10" value="0.00" readonly="readyonly" onFocus="this.form.elements[0].focus()"/>
                                                                            </td>
                                                                        </tr>

                                                                    <?php endif; ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <?php if ($fee->item_price > 0): ?>

                                                                        <tr>
                                                                            <td><?php _e('Total', 'evrplus_language'); ?></td>
                                                                            <td align="right"><input style="width: 100px" type="text" name="displaytotal" id="displaytotal" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/>
                                                                                <input type="hidden" name="total" id="total" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/></td>
                                                                        </tr>

                                                                    <?php else: ?>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <input style="width: 100px" type="hidden" name="total" id="total" size="10" value="0.00" onFocus="this.form.elements[0].focus()"/>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                </tfoot>
                                                            </table>
                                                        </div>

                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div class="col-xs-12">
                                                        <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i> 
                                                            <?php _e('No Fees Have Been Setup For This Event!', 'evrplus_language'); ?>
                                                            <?php _e('Registration for this event can not be taken at this time.', 'evrplus_language'); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>


                                            <?php else: ?>

                                                <div class="col-xs-12">
                                                    <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i> 
                                                         <?php _e('This event has reached registration capacity.', 'evrplus_language'); ?>
                                                        <?php _e('Please provide your information to be placed on the waiting list.', 'evrplus_language'); ?>
                                                    </div>
                                                </div>  

                                                <input type="hidden" name="request" value="Waitlist" /> 
                                                <input type="hidden" name="reg_type" value="WAIT" />


                                            <?php endif; ?>


                                            <div class="clearfix"></div>
                                            <?php if ($company_options['captcha'] == 'Y' && trim($company_options['captcha_key']) != ""): ?>
                                                <div class="col-xs-12 fi3ld">
                                                    <div class="g-recaptcha" id ="g-recaptcha" data-sitekey="<?php echo $company_options['captcha_key']; ?>"></div>
                                                </div>
                                            <?php endif; ?>


                                            <?php if ($term_c == 'Y' && $term_c_force != 'Y'): ?>
                                                <div class="col-xs-12 fi3ld">
                                                    <label class="checkb0x"><input type="checkbox" id="accept_term" name="accept_term" value="1" /> <?php echo __('I accept the terms and conditions', 'evrplus_language'); ?></label>
                                                    <div class="t3rms"><?php echo html_entity_decode($term_desc); ?></div>
                                                </div>
                                            <?php endif; ?>


                                            <div class="col-xs-12" id="action_message_eplus_container" style="display:none;">
                                                <div class="info-m3ssages"><i class="fa fa-exclamation-triangle"></i>
                                                    <span id="form_action_message_eplus"></span>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 fi3ld-buttons">
                                                <input type="hidden" name="action" value="confirm"/>
                                                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>" />
                                                <input type="hidden" name="eventplus_token" value="<?php echo $eventplus_token; ?>" />
                                                <input type="hidden" id="tax_rate" value="<?php echo $tax_rate; ?>" />

                                                <input type="submit" name="mySubmit" id="mySubmit" value="<?php _e('Submit', 'evrplus_language'); ?>" />
                                                <input type="reset" value="<?php _e('Reset', 'evrplus_language'); ?>" />

                                            </div>

                                        </div>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php if ($company_options['captcha'] == 'Y' && trim($company_options['captcha_key']) != ""): ?>
    <script src="https://www.google.com/recaptcha/api.js" type="text/javascript" async defer></script>
    <script type="text/javascript">
                                        jQuery(document).ready(function () {
                                            jQuery("#mySubmit").click(function () {
                                                if (grecaptcha.getResponse() == "") {
                                                    alert("Please fill the captcha !");
                                                    return false;
                                                }
                                            });
                                        });
    </script>
    <?php









 endif;
