<?php

class EventPlus_Helpers_Admin_Dashboard {

    function handleEvents() {
        echo EventPlus::dispatch('admin_dashboard_widgets/events');
    }

}
