<?php

class EventPlus_Helpers_Assets_Front {

    function enqueueStylesheets() {
        $file = EventPlus::getPlugin()->getFile();

        wp_register_style('evrplus_public', plugins_url('assets/front/evrplus_public_style_v2.css', $file), array(), '1.0.0', 'all');

        wp_register_style('evrplus_calendar', plugins_url('assets/front/evrplus_calendar_v19.css', $file), array(), '1.0.0', 'all');

        wp_register_style('custom-style', plugins_url('assets/front/custom-style.css', $file), array(), '1.0.0', 'all');

        wp_register_style('evrplus_pop_style', plugins_url('assets/front/evrplus_pop_style_v1.css', $file), array(), '1.0.0', 'all');

        wp_register_style('evrplus_fancy_style', plugins_url('assets/scripts/fancybox/jquery.fancybox-1.3.4.css', $file), array(), '1.0.0', 'all');

        wp_register_style('evrplus_colorbox_style', plugins_url('assets/scripts/colorbox/css/colorbox.css', $file), array(), '1.0.0', 'all');

        wp_enqueue_style('evrplus_public');

        wp_enqueue_style('evrplus_calendar');

        wp_enqueue_style('evrplus_pop_style');

        wp_enqueue_style('evrplus_fancy_style');

        wp_enqueue_style('evrplus_colorbox_style');

        wp_register_style('bootstrabCSS', plugins_url('assets/js/bootstrap.min.css', $file), array(), '1.0.0', 'all');
        wp_enqueue_style('bootstrabCSS');

        wp_enqueue_style('custom-style');
    }

    function enqueueScripts() {
        wp_enqueue_script('evrplus_tooltip_script');

        wp_enqueue_script('evrplus_excanvas');

        wp_enqueue_script('evrplus_knob');

        wp_enqueue_script('evrplus_ba-throttle-debounce');

        wp_enqueue_script('evrplus_redcountdown');

        wp_enqueue_script('evrplus_public_script');

        wp_enqueue_script('evrplus_public_colorbox');

        wp_enqueue_script('evrplus_pop_overlay');

        wp_enqueue_script('evrplus_public_fancy');

        wp_enqueue_script('evrplus_public_easing');

        wp_enqueue_script('evrplus_public_mouswheel');
    }

    function init() {
        add_action('wp_enqueue_scripts',array($this, 'enqueueStylesheets'), 10);
        add_action('wp_enqueue_scripts',array($this, 'enqueueScripts'), 10);
    }

}
