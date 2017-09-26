<?php

/**
 * Front Controller 
 * 
 * Executes the request by calling the Router class that maps the router parameters (controller, action etc) into the request object. 
 */
class EventPlus_Front_Controller {

    protected $request = null;
    protected $_router = null;
    private $_dispatcher = null;

    /**
     * Array of invocation parameters to use when instantiating action
     * controllers
     * @var array
     */
    protected $_invokeParams = array();
    
    protected $uriKey = null;

    /**
     * Instance of Exception
     * @var last_exception
     */
    protected $_last_exception = null;

    public function setLastException(Exception $ex) {
        $this->_last_exception = $ex;

        return $this;
    }

    public function getLastException() {
        return $this->_last_exception;
    }
    
    public function setUriKey($key) {
        $this->uriKey = $key;

        return $this;
    }

    public function getUriKey() {
        return $this->uriKey;
    }

    /**
     * Add or modify a parameter to use when instantiating an action controller
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setParam($name, $value) {
        $this->_invokeParams[(string) $name] = $value;
        return $this;
    }

    /**
     * Set parameters to pass to action controller constructors
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params) {
        $this->_invokeParams = array_merge($this->_invokeParams, $params);
        return $this;
    }

    /**
     * Retrieve a single parameter from the controller parameter stack
     *
     * @param string $name
     * @return mixed
     */
    public function getParam($name) {
        if (isset($this->_invokeParams[$name])) {
            return $this->_invokeParams[$name];
        }

        return null;
    }

    /**
     * Retrieve action controller instantiation parameters
     *
     * @return array
     */
    public function getParams() {
        return $this->_invokeParams;
    }

    /**
     * Clear the controller parameter stack
     *
     * By default, clears all parameters. If a parameter name is given, clears
     * only that parameter; if an array of parameter names is provided, clears
     * each.
     *
     * @param null|string|array single key or array of keys for params to clear
     * @return $this
     */
    public function clearParams($name = null) {
        if (null === $name) {
            $this->_invokeParams = array();
        } elseif (is_string($name) && isset($this->_invokeParams[$name])) {
            unset($this->_invokeParams[$name]);
        } elseif (is_array($name)) {
            foreach ($name as $key) {
                if (is_string($key) && isset($this->_invokeParams[$key])) {
                    unset($this->_invokeParams[$key]);
                }
            }
        }

        return $this;
    }

    public function setDispatcher($dispatcher) {
        $this->_dispatcher = $dispatcher;
        return $this;
    }

    public function getDispatcher() {
        return $this->_dispatcher;
    }

    /**
     * Set request class/object
     *
     * Set the request object.  The request holds the request environment.
     * @return $this
     */
    public function setRequest($request) {
        $this->request = $request;
        return $this;
    }

    /**
     * Return the request object.
     *
     * @return null
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Set router class/object
     *
     * Set the router object.  The router is responsible for mapping
     * the request to a controller and action.
     *
     * @param string|$router
     * @throws expCore if invalid router class
     * @return $this
     */
    public function setRouter($router) {

        $this->_router = $router;

        return $this;
    }

    /**
     * Return the router object.
     *
     * @return router
     */
    public function getRouter() {

        return $this->_router;
    }

    /**
     * @return  
     */
    public function HttpResponse() {
        return $this->request->getHttpResponse();
    }

    public function execute($uri, $params = array()) {

        $this->getRequest()
                ->setParam($this->getUriKey(),$uri);
        
        //Map the routes
        $this->getRouter()->run();
        
        $this->setParams($params);

        //Dispatch Request
        $this->getDispatcher()
                ->setParams($this->getParams())
                ->setFrontController($this)
                ->dispatch($this->getRequest());

        return $this;
    }

    function getCurrentController() {
        return $this->getRequest()->getController();
    }

    function getCurrentAction() {
        return $this->getRequest()->getAction();
    }

    function getResponse() {
        return $this->getRequest()->getHttpResponse()->body(NULL);
    }
}
