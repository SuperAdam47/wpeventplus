<?php

class EventPlus {

    private static $blockedVars = array('plugin');
    private static $vars = array();
    protected static $objectCache = array();

    static function factory($class_name, $params = array()) {
        $class_name = 'EventPlus_' . ucwords($class_name);
        $key = md5($class_name);

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
    }

    /**
     * autoload classes ( Library ) :)
     * includes desired file
     */
    static function AutoLoad($class) {

        $class_name = strtolower($class);
        $class_name = str_replace('_', DIRECTORY_SEPARATOR, $class_name);
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

}
