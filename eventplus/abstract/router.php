<?php
abstract class EventPlus_Abstract_Router
{
    /**
     * Array of parameters to use when instantiating controller
     * @var array
     */
    protected $_request = array();

    
    /**
     * Array of parameters to use when instantiating controller
     * @var array
     */
    protected $_params = array();
   
     /**
     * Add or modify a parameter to use when instantiating an action controller
     *
     * @param string $name
     * @param mixed $value
     * @return EventPlus_Abstract_Router
     */
    public function setParam($name, $value)
    {
        $name = (string) $name;
        $this->_params[$name] = $value;
        return $this;
    }

    /**
     * Set parameters to pass to action controller constructors
     *
     * @param array $params
     * @return EventPlus_Abstract_Router
     */
    public function setParams(array $params)
    {
        $this->_params = array_merge($this->_params, $params);
        return $this;
    }

    /**
     * Retrieve a single parameter from the controller parameter stack
     *
     * @param string $name
     * @return mixed
     */
    public function getParam($name)
    {
        if(isset($this->_params[$name])) {
            return $this->_params[$name];
        }

        return null;
    }

    /**
     * Retrieve action controller instantiation parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Clear the controller parameter stack
     *
     * By default, clears all parameters. If a parameter name is given, clears
     * only that parameter; if an array of parameter names is provided, clears
     * each.
     *
     * @param null|string|array single key or array of keys for params to clear
     * @return EventPlus_Abstract_Router
     */
    public function clearParams($name = null)
    {
        if (null === $name) {
            $this->_params = array();
        } elseif (is_string($name) && isset($this->_params[$name])) {
            unset($this->_params[$name]);
        } elseif (is_array($name)) {
            foreach ($name as $key) {
                if (is_string($key) && isset($this->_params[$key])) {
                    unset($this->_params[$key]);
                }
            }
        }

        return $this;
    }
    

    public function getRequest(){
        return $this->_request;
    }

    public function setRequest($request){
        
        $this->_request = $request;
        
        return $this;
    }

    public abstract function run();

}
