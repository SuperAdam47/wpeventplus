<?php

define('EVENT_PLUS_URI_KEY', 'eplus_uri');
define('EVENT_PLUS_SITE_URL', get_bloginfo('url') . '/');
define('EVENT_PLUS_URL_DOMAIN', get_option('siteurl') . '/');
define('EVENT_PLUS_ADMIN_URL', EVENT_PLUS_SITE_URL . 'wp-admin/admin.php');
define('EVENT_PLUS_WP_CONTENT_PATH', ABSPATH . 'wp-content' . DIRECTORY_SEPARATOR);
define('EVENT_PLUS_UPLOAD_PATH', EVENT_PLUS_WP_CONTENT_PATH . 'uploads' . DIRECTORY_SEPARATOR);
define('EVENT_PLUS_WP_UPLOAD_URL', EVENT_PLUS_SITE_URL . 'wp-content/uploads/');
define('EVENT_PLUS_PUBLIC_URL', EVENT_PLUS_PLUGIN_URL . "public/");
define('EVENT_PLUS_PUBLIC_PATH', EVENT_PLUS_PLUGIN_FRAMEWORK_PATH . "public" . DIRECTORY_SEPARATOR);
define('EVENT_PLUS_PLUGIN_APP_PATH', EVENT_PLUS_PLUGIN_FRAMEWORK_PATH . 'app' . DIRECTORY_SEPARATOR);
define('EVENT_PLUS_PLUGIN_APP_CONTROLLERS_PATH', EVENT_PLUS_PLUGIN_APP_PATH . 'controllers' . DIRECTORY_SEPARATOR);
define('EVENT_PLUS_PLUGIN_APP_VIEWS_PATH', EVENT_PLUS_PLUGIN_APP_PATH . 'views' . DIRECTORY_SEPARATOR);
define("EVR_PLUGINFULLURL", WP_PLUGIN_URL . EVR_PLUGINPATH);

class EventPlus {

    private static $blockedVars = array('plugin');
    private static $vars = array();
    protected static $objectCache = array();

    static function factory($class_name, $params = array()) {
        $class_name = 'EventPlus_' . ucwords($class_name);

        return new $class_name($params);
    }

    static function setPlugin($oPlugin) {
        if (is_object($oPlugin) == false) {
            throw new Exception("Invalid Plugin instance", 500);
        }

        self::$vars['plugin'] = $oPlugin;
    }

    /**
     * @return EventPlus_Abstract_Plugin
     */
    static function getPlugin() {
        if (is_object(self::$vars['plugin'])) {
            return self::$vars['plugin'];
        } else {
            return false;
        }
    }

    static function set($key, $value) {

        if (in_array($key, self::$blockedVars)) {
            throw new Exception("Plugin param not allowed as its internal parameter", 500);
        }
        self::$vars[$key] = $value;
    }

    static function get($key) {
        return self::$vars[$key];
    }

    static function getRegistry() {
        return self::$vars['registry'];
    }

    static function init() {
        spl_autoload_register(array('EventPlus', 'AutoLoad'));
        require_once EVENT_PLUS_PLUGIN_FRAMEWORK_PATH . 'functions.php';
    }

    /**
     * autoload classes ( Library ) :)
     * includes desired file
     */
    static function AutoLoad($class) {

        $class_name = str_replace('_', DIRECTORY_SEPARATOR, strtolower($class));
        $filename = strtolower($class_name) . '.php';

        $file = EVENT_PLUS_PLUGIN_PATH . $filename;

        if (file_exists($file) == false) {
            return false;
        }

        require_once $file;
    }

    /**
     * Loads a file within a totally empty scope and returns the output:

     * @param   string
     * @return  mixed
     */
    public static function loadFile($file) {
        return include $file;
    }

    public static function dispatch($uri, array $invokeParams = array()) {

        $oDispatcher = EventPlus::factory('Dispatcher')
                ->setControllerDirectory(EVENT_PLUS_PLUGIN_APP_CONTROLLERS_PATH)
                ->setViewDirectory(EVENT_PLUS_PLUGIN_APP_VIEWS_PATH);

        $oRequest = EventPlus::factory('Request');
        $oRouter = EventPlus::factory('Router')
                ->setRequest($oRequest);

        $oResponse = EventPlus::factory('Http_Response');
        $oRequest->setHttpResponse($oResponse);

        $oFront = EventPlus::factory('Front_Controller')
                ->setUriKey(EVENT_PLUS_URI_KEY)
                ->setRequest($oRequest)
                ->setRouter($oRouter)
                ->setDispatcher($oDispatcher)
                ->execute($uri, $invokeParams);

        return $oFront->getResponse();
    }

    static function dump($var, $exit = true) {
        echo '<pre>';
        print_r($var);
        echo'</pre>';

        if ($exit) {
            exit;
        }
    }

}
