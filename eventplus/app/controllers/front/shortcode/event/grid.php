<?php

class eplus_front_shortcode_event_grid_controller extends EventPlus_Abstract_Controller {

    function index() {

        $file = EventPlus::getPlugin()->getFile();

        wp_register_script('jquery.easing', plugins_url('/assets/scripts/gridview/js/jquery.easing.js', $file), array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('jquery.easing');
        wp_register_script('jquery.imagesLoaded.min', plugins_url('/assets/scripts/gridview/js/jquery.imagesLoaded.min.js', $file), array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('jquery.imagesLoaded.min');
        wp_register_script('jquery.isotope.min', plugins_url('/assets/scripts/gridview/js/jquery.isotope.min.js', $file), array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('jquery.isotope.min');
        wp_register_script('jquery.magnific-popup.min', plugins_url('/assets/scripts/gridview/js/jquery.magnific-popup.min.js', $file), array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('jquery.magnific-popup.min');
        wp_register_script('jquery.mediaBoxes', plugins_url('/assets/scripts/gridview/js/jquery.mediaBoxes.js', $file), array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('jquery.mediaBoxes');

        wp_register_script('evrplus_grid', plugins_url('/assets/front/evrplus_grid.js', $file), array('jquery'), '1.0.0', true);

		wp_localize_script( 'evrplus_grid', 'EvrGrid', array(
			'LoadingWord'       => __( 'Loading...', 'evrplus_language' ),
			'loadMoreWord'      => __( 'Load More', 'evrplus_language' ),
			'noMoreEntriesWord' => __( 'No More Entries', 'evrplus_language' )
		) );

        wp_enqueue_script('evrplus_grid');

        wp_register_script('jquery.transit.min', plugins_url('/assets/scripts/gridview/js/jquery.transit.min.js', $file), array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('jquery.transit.min');
        wp_register_script('modernizr.custom.min', plugins_url('/assets/scripts/gridview/js/modernizr.custom.min.js', $file), array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('modernizr.custom.min');
        wp_register_script('waypoints.min', plugins_url('/assets/scripts/gridview/js/waypoints.min.js', $file), array('jquery'), '1.0.0', 'all');
        wp_enqueue_script('waypoints.min');

        wp_register_style('mediaBoxes', plugins_url('/assets/scripts/gridview/css/mediaBoxes.css', $file), array(), '1.0.0', 'all');
        wp_register_style('magnific-popup', plugins_url('/assets/scripts/gridview/css/magnific-popup.css', $file), array(), '1.0.0', 'all');

        wp_enqueue_style('mediaBoxes');
        wp_enqueue_style('magnific-popup');

        $col = $this->_invokeArgs['col'];
        $columns = $this->_invokeArgs['columns'];
        $ordered = $this->_invokeArgs['ordered'];
        $init_events = $this->_invokeArgs['init_events'];
        $load_new_events = $this->_invokeArgs['load_new_events'];
        $custom = $this->_invokeArgs['custom'];
        $show_excerpt = $this->_invokeArgs['show_excerpt'];
        $character_limit = $this->_invokeArgs['character_limit'];
        $category_id = $this->_invokeArgs['category_id'];

        $company_options = EventPlus_Models_Settings::getSettings();

        $orderby = $company_options['order_event_list'];

        $oEvent = new EventPlus_Models_Events();
        $rows = $oEvent->getRecords(array(
            'category_id' => $category_id,
            'orderby' => $orderby
        ));

        $categories = array();
        if( empty($category_id) ) {
            $oEventCategories = new EventPlus_Models_Categories();
            $categories = $oEventCategories->getCategories();
        }

        $viewParams = array();
        $viewParams['invoke_params'] = $this->_invokeArgs;
        $viewParams['company_options'] = $company_options;
        $viewParams['rows'] = $rows;
        $viewParams['categories'] = $categories;
        $viewParams['cats'] = $categories;
        $viewParams['show_excerpt'] = $show_excerpt;
        $viewParams['character_limit'] = $character_limit;
        $viewParams['category_id'] = $category_id;

        $output = $this->oView->View('front/widgets/shortcode/event/grid', $viewParams);

        $this->setResponse($output);
    }

}
