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
        jQuery(".checkbox").on('click',function () {

            if (jQuery(this).find('input:checkbox').is(":checked")) {

                jQuery(this).find('input:checkbox').attr("checked", false);
            }
            else {
                jQuery(this).find('input:checkbox').prop("checked", true);
            }

            var res = jQuery(this).find('input:checkbox').is(":checked");

            if (res == true)
            {
                jQuery(this).find('input:checkbox').attr("checked", "checked");
            }
            else
            {
                jQuery(this).find('input:checkbox').removeAttr("checked");
            }

            jQuery(this).toggleClass('checked')
        });
    });

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
        jQuery(".checkbox").on('click',function () {
            //jQuery(this).addClass('checked');
            jQuery(this).toggleClass('active');
        });
    });
    jQuery(document).ready(function ($) {

        $('#term_c_y').on('click',function () {
            $('#term_div').show();
        });
        $('#term_c_n').on('click',function () {
            $('#term_div').hide();
        });
    });
</script>
<style type="text/css">
    .ui-tooltip, .arrow:before {
        background: #5BA4A4;
        border:1px #fff solid !important;
    }
    .ui-tooltip {
        padding: 10px 10px;
        color: white;

        font: bold 13px "Helvetica Neue", Sans-Serif;
    }
    .evrplus_tab_container .tab_content input[type="checkbox"] {
        display: none;
    }
    .evrplus_tab_container .tab_content input[type="checkbox"] + label {
        background: url("http://i.stack.imgur.com/S4p2R.png") no-repeat scroll 0 50% transparent;
        height: 21px;
        margin: 0 8px 0 0 !important;
        padding: 0;
        width: 23px;
    }
    .evrplus_tab_container .tab_content input[type="checkbox"]:checked + label {
        background: url("http://i.stack.imgur.com/S4p2R.png") no-repeat scroll 80% 50% transparent;
        height: 21px;
        margin: 0 8px 0 0 !important;
        padding: 0;
        width: 23px;
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
    .checkbox{
        width: 23px;
        height: 21px;
        padding:3px 0px;
        background: transparent url(http://i.stack.imgur.com/S4p2R.png ) no-repeat 0 50%
    }
    .checked{
        background: transparent url(http://i.stack.imgur.com/S4p2R.png ) no-repeat 80% 50%
    }
    .events-plus_page_events .checkbox input {
        border: 0 none !important;
        visibility: hidden !important;
        width: 22px !important;
    }
</style>
<script>
    var tinymceConfigs = [{
            theme: "advanced",
            mode: "none",
            language: "en",
            height: "200",
            width: "100%",
            theme_advanced_layout_manager: "SimpleLayout",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",
            theme_advanced_buttons2: "",
            theme_advanced_buttons3: ""},
        {
            theme: "advanced",
            mode: "none",
            language: "en",
            height: "200",
            width: "100%",
            theme_advanced_layout_manager: "SimpleLayout",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left"
        }];
    function tinyfy(settingid, el_id) {
        tinyMCE.settings = tinymceConfigs[settingid];
        tinyMCE.execCommand('mceAddControl', true, el_id);
    }
</script>