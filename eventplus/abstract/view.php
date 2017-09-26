<?php

abstract class EventPlus_Abstract_View {

    /**
     * Path to View's folder
     * 
     * @var mixed
     */
    private $_path;

    /*
     * @Global Variables array
     * @access private
     */
    protected $global_vars = array();

    function setDirectory($path) {
        if (is_dir($path) == false) {
            throw new Exception("Invalid view path specified.", 500);
        }
        $this->_path = $path;
        return $this;
    }

    /**
     * @set undefined vars
     * @param string $index
     * @param mixed $value
     * @return void
     */
    public function __set($index, $value) {
        $this->global_vars[$index] = $value;
    }

    /**
     * @get 
     * @param string $index
     * @param mixed $value
     * @return void
     */
    public function __get($index) {
        $value = isset($this->global_vars[$index]) ? $this->global_vars[$index] : null;
        return $value;
    }

    public function getViewFile($view_name) {
        return $this->_path . $view_name . '.php';
    }

    public function getGlobalVars() {
        return $this->global_vars;
    }

    function View($view_name, $additional_data = null) {
 
        $path = $this->getViewFile($view_name);
  
        $this->global_vars['evrplus_date_format'] = EventPlus_Helpers_Funx::getDateFormat();
        $this->global_vars['company_options'] = EventPlus_Models_Settings::getSettings();
        $this->global_vars['wpdb'] = $this->getWp();

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

}
