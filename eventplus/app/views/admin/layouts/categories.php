<script>
    jQuery(function () {
        jQuery(document).tooltip({
            position: {
                my: 'left center',
                at: 'right+10 center',
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
    #wpbody-content #dashboard-widgets .postbox-container {
        width: 90% !important;
    }
    .ui-tooltip,
    .arrow:before {
        background: #5BA4A4;
        border: 1px #fff solid !important;
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
    .arrow {
        display: none !important;
    }
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
    #dashboard_right_now li a:before,
    #dashboard_right_now li span:before {
        content: normal !important;
        display: block;
        float: left;
        font: 400 20px/1 dashicons;
        margin: 0 5px 0 0;
        padding: 0;
        position: relative;
        text-align: center;
        text-decoration: none !important;
        text-indent: 0;
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
            theme_advanced_buttons3: ""
        }, {
            theme: "advanced",
            mode: "none",
            skin: "o2k7",
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

<div class="wrap">
    <h2><a href=""><img src="<?php echo $this->assetUrl('images/evrplus_icon.png'); ?>" alt="Event Registration for Wordpress" /></a></h2>
    <h2><?php _e('Category Management', 'evrplus_language'); ?></h2>
    
    <?php if(EventPlus::factory('Var')->get('method', $_GET) != 'add'): ?>
    
    <a href="<?php echo $this->adminUrl('admin_categories/add'); ?>" class="evrplus_button"><?php _e('ADD NEW CATEGORY', 'evrplus_language'); ?></a>

    <?php endif; ?>

    <div id="dashboard-widgets-wrap" class='events-plus_page_categories'>
        <div id="dashboard-widgets" class="metabox-holder">
            <div class='postbox-container' style='width:auto !important;'>
                <div id='normal-sortables' class='meta-box-sortables'>
                    <div id="dashboard_right_now1" class="postbox " >

                        <div class="inside">

                            <div class="padding">
                                <?php echo $content; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <div style='text-align: center;'>
    <?php echo EventPlus_Helpers_Funx::promoBanner(); ?>
</div>
