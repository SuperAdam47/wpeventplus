<?php

class EventPlus_Helpers_Assets_Admin {

    function loadTinyMce() {
        global $wp_version;

        if (!version_compare($wp_version, '3.2', '>=')) {
            if (function_exists('wp_tiny_mce_preload_dialogs')) {
                add_action('admin_print_footer_scripts', 'wp_tiny_mce_preload_dialogs');
            }

            wp_tiny_mce(false, array("editor_selector" => "edit_class",
                'height' => 200,
                'plugins' => 'inlinepopups,wpdialogs,wplink,media,wpeditimage,wpgallery,paste,tabfocus',
                'forced_root_block' => false,
                'force_br_newlines' => true,
                'force_p_newlines' => false,
                'convert_newlines_to_brs' => true));
        }
    }

    function initAssets() {
        $file = EventPlus::getPlugin()->getFile();

        wp_register_script('evrplus_admin_script', plugins_url('/assets/scripts/evrplus.js', $file), array(), '1.0.0', 'all');

        wp_register_script('evrplus_fancy', plugins_url('/assets/scripts/fancybox/jquery.fancybox-1.3.4.pack.js', $file), array(), '1.0.0', 'all');

        wp_register_script('evrplus_tab_script', plugins_url('/assets/scripts/evrplus_tabs.js', $file), array(), '1.0.0', 'all');

        // wp_register_script('evrplus_tooltip_script', plugins_url('/assets/js/jquery.tooltip.js', $file), array(), '1.0.0', 'all');
        //register public scripts

        wp_register_script('evrplus_excanvas', plugins_url('/assets/js/excanvas.js', $file), array('jquery'), '1.0.0', 'all');

        wp_register_script('evrplus_knob', plugins_url('/assets/js/jquery.knob.min.js', $file), array('jquery'), '1.0.0', 'all');

        wp_register_script('evrplus_ba-throttle-debounce', plugins_url('/assets/js/jquery.ba-throttle-debounce.min.js', $file), array('jquery'), '1.0.0', 'all');

        wp_register_script('evrplus_redcountdown', plugins_url('/assets/js/jquery.redcountdown.min.js', $file), array('jquery'), '1.0.0', 'all');

        //wp_register_script('evrplus_public_script', plugins_url('/assets/front/evrplus_public_script.js', $file), array(), time(), 'all');

        wp_register_script('evrplus_public_easing', plugins_url('/assets/scripts/fancybox/jquery.easing-1.3.pack.js', $file), array(), '1.0.0', 'all');

        wp_register_script('evrplus_public_mouswheel', plugins_url('/assets/scripts/fancybox/jquery.mousewheel-3.0.4.pack.js', $file), array(), '1.0.0', 'all');

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-selectable');
        wp_enqueue_script('jquery-ui-core');  
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script(array('tiny_mce', 'editor', 'editor-functions', 'media-upload'));
    }

    function adminHeader() {
        
        $file = EventPlus::getPlugin()->getFile();
        
        if(strstr(strtolower($_GET['page']),'eventplus') == false){
            return;
        }

        wp_register_style($handle = 'evrplus_admin_css', $src = plugins_url('/assets/admin/css/evrplus_admin_style.css', $file), $deps = array(), $ver = '1.0.0', $media = 'all');

        wp_register_style($handle = 'evrplus_fancy_css', $src = plugins_url('/assets/scripts/fancybox/jquery.fancybox-1.3.4.css', $file), $deps = array(), $ver = '1.0.0', $media = 'all');

        wp_enqueue_style('evrplus_fancy_css');
        wp_enqueue_style('evrplus_admin_css');

        wp_enqueue_style('farbtastic');

        wp_register_script($handle = 'evrplus_admin_script', $src = plugins_url('/assets/scripts/evrplus.js', $file), $deps = array(), $ver = '1.0.0', $media = 'all');

        wp_register_script($handle = 'evrplus_admin_fancy', $src = plugins_url('/assets/scripts/fancybox/jquery.fancybox-1.3.4.pack.js', $file), $deps = array(), $ver = '1.0.0', $media = 'all');

        wp_register_script($handle = 'evrplus_tab_script', $src = plugins_url('/assets/scripts/evrplus_tabs.js', $file), $deps = array(), $ver = '1.0.0', $media = 'all');

        // wp_register_script($handle = 'evrplus_tooltip_script', $src = plugins_url('/assets/js/jquery.tooltip.js', $file), $deps = array(), $ver = '1.0.0', $media = 'all');
		
        wp_register_script($handle = 'bootstrap', $src = plugins_url('/assets/scripts/bootstrap.min.js', $file), $deps = array(), $ver = '3.3.7', $media = 'all');

        wp_register_script($handle = 'jquery-ui', $src = "//code.jquery.com/ui/1.11.4/jquery-ui.js", $deps = array(), $ver = '1.10.4', $media = 'all');

        wp_enqueue_script('jquery');

        wp_enqueue_script('evrplus_admin_script'); 

        wp_enqueue_script('evrplus_admin_fancy');

        wp_enqueue_script('evrplus_tab_script');

        wp_enqueue_script('evrplus_tooltip_script');

        wp_enqueue_script('farbtastic');

        wp_enqueue_script('bootstrap');

        wp_enqueue_script('jquery-ui');
    }

    function loadScripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');

        echo '<script type="text/javascript">
		jQuery.noConflict();
		jQuery(document).ready(function($) {
                    jQuery("#tabs").tabs();
		});
	</script>';

        echo '
	<style type="text/css">
	.ui-tabs .ui-tabs-hide {
	     display: none;
	}
	</style>
	';
    }

    function init() {
        
        if(strstr(strtolower($_GET['page']),'eventplus') == false){
            return;
        }
        
        $this->initAssets();
      
        add_action('admin_head', array($this, 'loadTinyMce'));
        add_action('admin_head', array($this, 'adminHeader'));
        add_action('admin_head', array($this, 'loadScripts'));
    }

}
