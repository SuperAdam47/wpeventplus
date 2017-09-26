<?php

abstract class EventPlus_Abstract_Controller {

    /**
     * Auth needed for this Action Controller
     * @var bool 
     */
    protected $_auth_needed = false;

    /**
     * Master Page directory
     * @var string 
     */
    protected $_layout = 'default';

    /**
     * Front controller instance
     */
    protected $_frontController;

    /**
     * dispatcher instance
     */
    protected $oDispatcher = null;

    /**
     * Array of arguments provided to the constructor, minus the
     * {@link $_request Request object}.
     * @var array
     */
    protected $_invokeArgs = array();

    /**
     * absRequest  instance
     */
    protected $_request = null;

    /**
     * absModel instance
     * @var model 
     */
    protected $_model = null;

    /**
     * Whether to run $this->index() by default if requested action doesnt exist.
     * @var (bool) 
     */
    protected $_run_default_method = false;
    protected $oView = null;

    function __construct($oDispatcher, array $args = array()) {
        
        if(is_admin() == false){
             wp_enqueue_style('eventplus-fonts-fa');
        }
        
        $this->oDispatcher = $oDispatcher;
        $this->oView = EventPlus::factory('View')
                ->setDirectory($this->oDispatcher->getViewDirectory());

        $this->oView->oUrl = EventPlus::getRegistry()->url;

        $this->oView->assets_url = $this->oView->oUrl->getAssetsUrl();

        $this->_setInvokeArgs($args)
                ->init();

        $this->checkAuth();
    }

    /**
     * Set invocation arguments
     *
     * @param array $args
     * @return absController
     */
    protected function _setInvokeArgs(array $args = array()) {
        $this->_invokeArgs = $args;
        return $this;
    }

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
    public function init() {
        // Nothing by default
    }

    protected function checkAuth() {
        if ($this->_auth_needed == true) {
            $this->Authenticate();
        }
    }

    /**
     * Return the Request object
     *
     * @return
     */
    public function getRequest() {
        return $this->_request;
    }

    /**
     * Set the Request object
     *
     * @param $request
     * @return Action
     */
    public function setRequest($request) {
        $this->_request = $request;
        return $this;
    }

    public function isAuthNeeded() {
        return $this->_auth_needed;
    }

    /**
     * @return
     */
    public function Dispatcher() {
        return $this->oDispatcher;
    }

    /**
     * Set the front controller instance
     *
     * @param clsFrontController $front
     * @return absController
     */
    public function setFrontController($front) {
        $this->_frontController = $front;
        return $this;
    }

    /**
     * Gets a parameter from the {@link $_request Request object}.  If the
     * parameter does not exist, NULL will be returned.
     *
     * If the parameter does not exist and $default is set, then
     * $default will be returned instead of NULL.
     *
     * @param string $paramName
     * @param mixed $default
     * @return mixed
     */
    protected function _getParam($paramName, $default = null) {
        $value = $this->getRequest()->getParam($paramName);
        if ((null === $value || '' === $value) && (null !== $default)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Set a parameter in the {@link $_request Request object}.
     *
     * @param string $paramName
     * @param mixed $value
     * @return Action
     */
    protected function _setParam($paramName, $value) {
        $this->getRequest()->setParam($paramName, $value);

        return $this;
    }

    /**
     * Determine whether a given parameter exists in the
     * {@link $_request Request object}.
     *
     * @param string $paramName
     * @return boolean
     */
    protected function _hasParam($paramName) {
        return null !== $this->getRequest()->getParam($paramName);
    }

    /**
     * Return all parameters in the {@link $_request Request object}
     * as an associative array.
     *
     * @return array
     */
    protected function _getAllParams() {
        return $this->getRequest()->getParams();
    }

    protected function setResponse($response, $code = null) {
        $this->getRequest()->setResponse($response, $code);

        return $this;
    }

    protected function getResponse() {
        return $this->getRequest()->getResponse();
    }

    /**
     * Automatically executed if auth_needed = true. Can be used to do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function Authenticate() {
        // Nothing by default
    }

    /**
     * Automatically executed before the controller action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before() {
        // Nothing by default
    }

    /**
     * Automatically executed after the controller action. Can be used to apply
     * transformation to the request response, add extra output, and execute
     * other custom code.
     *
     * @return  void
     */
    public function after() {
        // Nothing by default
    }

    //default method
    abstract function index();

    public final function dispatch($action) {
        if ($this->getRequest()->isDispatched()) {
            $this->before();

            if (method_exists($this, $action) == false) {
                $action = '';
                //run the action
                if ($this->_run_default_method) {
                    $action = 'index';
                }
            }

            if ($action == '') {
                throw new Exception("Requested action <strong>'" . $this->getRequest()->getAction() . "'</strong> doesn't exist.", 404);
            }

            $this->$action();

            $this->after();
        }
    }

    function getRegistry() {
        return EventPlus::getRegistry();
    }

    protected function flash($message, $class) {

        if (is_object($this->getRegistry()->get('flash'))) {
            $this->getRegistry()->get('flash')->add($message, $class);
        }
    }

    function setSuccessMessage($message) {
        $this->flash($message, 'updated');
    }

    function setErrorMessage($message) {
        $this->flash($message, 'error');
    }
    
    function redirect($location, $status=302){
        wp_safe_redirect( $location, $status );
    }
    
    function adminUrl($uri,array $params = array()){
        return EventPlus::getRegistry()->url->admin($uri, $params);
    }

}
