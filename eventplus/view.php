<?php

class EventPlus_View extends EventPlus_Abstract_View {
    
    function url(){
        return EventPlus::getRegistry()->url;
    }
    
    function assetUrl($uri_path = ''){
        if($uri_path == ''){
            return $this->url()->getAssetsUrl();
        }else{
             return $this->url()->getAssetsUrl() . $uri_path;
        }
    }
    
    function adminUrl($uri, array $params = array()){
        return $this->url()->admin($uri, $params);
    }
    
    function loadLayout($layout, $view, array $view_params = array()){
        
        $content = $this->View($view,$view_params);
        
        return $this->View($layout, array(
            'content' => $content
        ));
    }
    
    function View($view_name, $additional_data = null) {
 
        $path = $this->getViewFile($view_name);
  
        $this->global_vars['evrplus_date_format'] = EventPlus_Helpers_Funx::getDateFormat();
        $this->global_vars['company_options'] = EventPlus_Models_Settings::getSettings();
        $this->global_vars['wpdb'] = $this->wpDb();

        // Import the view variables to local namespace
        extract($this->global_vars, EXTR_SKIP);

        if (is_array($additional_data) && count($additional_data) > 0) {
            extract($additional_data, EXTR_SKIP);
        }

        // Capture the view output
        ob_start();

        if (file_exists($path) == false) {
            throw new Exception("" . $view_name . " not found", 404);
        }

        try {
            //Load the view 
            include ($path);
        } catch (Exception $e) {
            // Delete the output buffer
            ob_end_clean();

            // Re-throw the exception
            throw $e;
        }

        // Get the captured output and close the buffer
        return ob_get_clean();
    }
    
    function wpDb(){
        return EventPlus::getRegistry()->db->getDb();
    }
}
