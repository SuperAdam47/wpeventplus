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
            jQuery(this).toggleClass('checked')
            jQuery(this).prop("checked", true);
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
    .evrplus_tab_container .tab_content input[type="checkbox"] {
        display: none;
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
    /*
    .checkbox{
        width: 23px;
        height: 21px;
            padding:3px 0px;
         background: transparent url(http://i.stack.imgur.com/S4p2R.png ) no-repeat 0 50%;
    }
    .checked{
        background: transparent url(http://i.stack.imgur.com/S4p2R.png ) no-repeat 80% 50%;
    } */
    .evrplus_tab_container .tab_content input[type=checkbox]:checked + label { 
        background: transparent url(http://i.stack.imgur.com/S4p2R.png ) no-repeat 80% 50%;
        width: 23px;
        height: 21px;
        margin: 0 8px 0 0 !important;
        padding: 0;

    }
    .evrplus_tab_container .tab_content input[type=checkbox] + label{
        background: transparent url(http://i.stack.imgur.com/S4p2R.png ) no-repeat 0 50%;
        width: 23px;
        height: 21px;
        margin: 0 8px 0 0 !important;
        padding: 0;

    }
    .events-plus_page_events .checkbox input {
        border: 0 none !important;
        visibility: hidden !important;
        width: 22px !important;
    }
</style>