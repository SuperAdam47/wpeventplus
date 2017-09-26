<?php

class EventPlus_Helpers_Assets {

    function init() {
        $file = EventPlus::getPlugin()->getFile();

        wp_register_script('evrplus_admin_script', plugins_url('/assets/scripts/evrplus.js', $file), array(), '1.0.0', 'all');

        wp_register_script('evrplus_fancy', plugins_url('/assets/scripts/fancybox/jquery.fancybox-1.3.4.pack.js', $file), array(), '1.0.0', 'all');

        wp_register_script('evrplus_tab_script', plugins_url('/assets/scripts/evrplus_tabs.js', $file), array(), '1.0.0', 'all');

        wp_register_script('evrplus_excanvas', plugins_url('/assets/js/excanvas.js', $file), array('jquery'), '1.0.0', 'all');

        wp_register_script('evrplus_knob', plugins_url('/assets/js/jquery.knob.min.js', $file), array('jquery'), '1.0.0', 'all');

        wp_register_script('evrplus_ba-throttle-debounce', plugins_url('/assets/js/jquery.ba-throttle-debounce.min.js', $file), array('jquery'), '1.0.0', 'all');

        wp_register_script('evrplus_redcountdown', plugins_url('/assets/js/jquery.redcountdown.min.js', $file), array('jquery'), '1.0.0', 'all');

        wp_register_script('evrplus_public_script', plugins_url('/assets/front/evrplus_public_script.js', $file), array(), time(), 'all');


        wp_register_script('evrplus_public_easing', plugins_url('/assets/scripts/fancybox/jquery.easing-1.3.pack.js', $file), array(), '1.0.0', 'all');

        wp_register_script('evrplus_public_mouswheel', plugins_url('/assets/scripts/fancybox/jquery.mousewheel-3.0.4.pack.js', $file), array(), '1.0.0', 'all');

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
    }
}
