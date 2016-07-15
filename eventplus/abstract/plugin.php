<?php

/**
 * Abstract Plugin Class
 */
abstract class EventPlus_Abstract_Plugin {

    protected $_plugin_title = '';
    protected $_plugin_version = '';
    protected $_build_version = '';
    protected $_plugin_slug = '';
    
    /**
     * Plugin File
     * @var (string) 
     */
    protected $_plugin_file = null;

    /**
     * Plugin Directory
     * @var (string) 
     */
    protected $_plugin_path = null;

    /**
     * Plugin URL
     * @var (string) 
     */
    protected $_plugin_url = null;
    

    /**
     * Collection of Shortcodes registered
     * @var (array) 
     */
    private $_short_codes = array();

    public function __construct($plugin_file) {
        $this->_plugin_file = $plugin_file;
        $this->_plugin_path = plugin_dir_path($plugin_file);
        $this->_plugin_url = plugin_dir_url($plugin_file);

        $this->init();
    }

    protected final function init() {

        $this->_init();

        register_activation_hook($this->_plugin_file, array($this, 'activate'));
        register_deactivation_hook($this->_plugin_file, array($this, 'deactivate'));

        if (is_admin()) {
            $this->initAdmin();
        } else {
            $this->initFront();
        }
    }

    /**
     * Wrapper method to add actions using WordPress. 
     * Returns 'absMVC_Plugin' for method chaining.
     * 
     * @param type $action
     * @param type $function
     * @param type $priority
     * @param type $accepted_args
     * @return absMVC_Plugin 
     */
    protected function add_action($action, $object, $function = '', $priority = 10, $accepted_args = 1) {
        add_action($action, array($object, $function), $priority, $accepted_args);

        return $this;
    }

    /**
     * Wrapper method to add actions using WordPress. 
     * Returns 'absMVC_Plugin' for method chaining.
     *
     * @param type $action
     * @param type $function
     * @param type $priority
     * @param type $accepted_args
     * @return absMVC_Plugin 
     */
    protected function add_ajax_action($action, $function = '', $priority = 10, $accepted_args = 1) {
        $this->add_action('wp_ajax_' . $action, $function, $priority, $accepted_args);
        $this->add_action('wp_ajax_nopriv_' . $action, $function, $priority, $accepted_args);
        return $this;
    }

    /**
     * Wrapper method to add filters using WordPress. 
     * Returns 'absMVC_Plugin' for method chaining.
     *
     * @param type $filter
     * @param type $function
     * @param type $priority
     * @param type $accepted_args
     * @return absMVC_Plugin 
     */
    protected function add_filter($filter, $object, $function, $priority = 10, $accepted_args = 1) {
        add_filter($filter, array($object, $function), $priority, $accepted_args);

        return $this;
    }

    /**
     * Wrapper method to add short code using WordPress. 
     * Instantiate absMVC_Shortcode class and registers short code
     * Returns 'absMVC_Plugin' for method chaining.
     *
     * @param type $shortcode
     * @param type $function
     * @return absMVC_Plugin 
     */
    protected function add_shortcode($shortcode, $shortCodeHandlerClass, $method) {
        $this->_short_codes[] = array('code' => $shortcode, 'class' => $shortCodeHandlerClass, 'func' => $method);

        $oShortCodeHandler = new $shortCodeHandlerClass($this);
        add_shortcode($shortcode, array($oShortCodeHandler, $method));


        return $this;
    }

    
    /**
     * Plugin Title
     * @return (string) 
     */
    function getTitle() {
        return $this->_plugin_title;
    }
    
    
    /**
     * Plugin Version
     * @return (string) 
     */
    function getVersion() {
        return $this->_plugin_version;
    }
    
    /**
     * Plugin Build Version
     * @return (string) 
     */
    function getBuildVersion() {
        return $this->_build_version;
    }
    
    /**
     * Plugin Directory
     * @return (string) 
     */
    function getPath() {
        return $this->_plugin_path;
    }

    /**
     * Plugin URL
     * @return (string) 
     */
    function getUrl() {
        return $this->_plugin_url;
    }
    
    /**
     * Plugin URL
     * @return (string) 
     */
    function getSlug() {
        return $this->_plugin_slug;
    }

    /**
     * Plugin File
     * @return (string) 
     */
    function getFile() {
        return $this->_plugin_file;
    }

    public function getShortCodes() {
        $this->_short_codes;
    }

    /**
     * Method to execute any code to initialize the plugin functionality
     */
    protected abstract function _init();

    public abstract function activate();

    public abstract function deactivate();

    protected abstract function initAdmin();

    protected abstract function initFront();
}
