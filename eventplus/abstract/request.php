<?php
/**
 * Abstract Request Class
 * 
 */
abstract class EventPlus_Abstract_Request
{
    private $_disallowed_params = array('plugin');
    /**
    * Has the action been dispatched?
    * @var boolean
    */
    protected $_dispatched = false;
    
     /**
     * Has the request contains exception?
     * @var exception Object
     */
    protected $_exception = null;
    
    /**
     * Controller
     * @var string
     */
    protected $_controller;

    /**
     * Action
     * @var string
     */
    protected $_action;
    
    
     /**
     * Slug
     * @var string
     */
    protected $_slug;
    
     /**
     * Code
     * @var string
     */
    protected $_code;
    
    /**
     * Request parameters
     * @var array
     */
    protected $_params = array();
    
    protected $_header = '';
    
    protected $httpResponse = null;

    /**
     * Retrieve the controller name
     *
     * @return string
     */
    public function getController()
    {
        if (null === $this->_controller) {
            $this->_controller = $this->getParam('core_controller');
        }

        return $this->_controller;
    }
    
     /**
     * Retrieve the Slug
     *
     * @return string
     */
    public function getSlug()
    {
        if (null === $this->_slug) {
            $this->_slug = $this->getParam('core_slug');
        }

        return $this->_slug;
    }
    
     /**
     * Set the Slug to use
     *
     * @param string $value
     * @return EventPlus_Abstract_Request
     */
    public function setSlug($value)
    {
        $this->_slug = $value;
        
        $this->setParam('core_slug', $value);
        
        return $this;
    }
    
      /**
     * Retrieve the Slug
     *
     * @return string
     */
    public function getCode()
    {
        if (null === $this->_code) {
            $this->_code = $this->getParam('core_code');
        }

        return $this->_code;
    }
    
     /**
     * Set the Slug to use
     *
     * @param string $value
     * @return EventPlus_Abstract_Request
     */
    public function setCode($value)
    {
        $this->_code = $value;
        
        $this->setParam('core_code', $value);
        
        return $this;
    }
    
    

    /**
     * Set the controller name to use
     *
     * @param string $value
     * @return EventPlus_Abstract_Request
     */
    public function setController($value)
    {
        $this->_controller = $value;
        
        $this->setParam('core_controller', $value);
        
        return $this;
    }

    /**
     * Retrieve the action name
     *
     * @return string
     */
    public function getAction()
    {
        if (null === $this->_action) {
            $this->_action = $this->getParam('core_action');
        }

        return $this->_action;
    }

    /**
     * Set the action name
     *
     * @param string $value
     * @return EventPlus_Abstract_Request
     */
    public function setAction($value)
    {
        $this->_action = $value;

        if (null === $value) {
            $this->setParam('core_action', $value);
        }
        return $this;
    }
    

    /**
     * Get an action parameter
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return mixed
     */
    public function getParam($key, $default = null)
    {
        $key = (string) $key;
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        return $default;
    }

    /**
     * Retrieve only user params (i.e, any param specific to the object and not the environment)
     *
     * @return array
     */
    public function getUserParams()
    {
        return $this->_params;
    }

    /**
     * Retrieve a single user param (i.e, a param specific to the object and not the environment)
     *
     * @param string $key
     * @param string $default Default value to use if key not found
     * @return mixed
     */
    public function getUserParam($key, $default = null)
    {
        if (isset($this->_params[$key])) {
            return $this->_params[$key];
        }

        return $default;
    }

    /**
     * Set an action parameter
     *
     * A $value of null will unset the $key if it exists
     *
     * @param string $key
     * @param mixed $value
     * @return EventPlus_Abstract_Request
     */
    public function setParam($key, $value)
    {
        $key = (string) $key;
        
        if(in_array($key, $this->_disallowed_params))
        {
            wp_die("You can't set '{$key}' param. It is reserveed for internal processing.");
        }
        
        if ((null === $value) && isset($this->_params[$key])) {
            unset($this->_params[$key]);
        } elseif (null !== $value) {
            $this->_params[$key] = $value;
        }

        return $this;
    }

    /**
     * Get all action parameters
     *
     * @return array
     */
     public function getParams()
     {
         return $this->_params;
     }

    /**
     * Set action parameters; does not overwrite
     *
     * Null values will unset the associated key.
     *
     * @param array $array
     * @return EventPlus_Abstract_Request
     */
    public function setParams(array $array)
    {
        $this->_params = $this->_params + (array) $array;

        foreach ($this->_params as $key => $value) {
            if (null === $value) {
                unset($this->_params[$key]);
            }
        }

        return $this;
    }

    /**
     * Unset all user parameters
     *
     * @return EventPlus_Abstract_Request
     */
    public function clearParams()
    {
        $this->_params = array();
        return $this;
    }
    
    /**
     * Set flag indicating whether or not request has exception
     *
     * @param boolean $flag
     * @return absRequest
     */
    public function setException(Exception $ex)
    {
        $this->_exception = $ex;
        
        return $this;
    }

    /**
     * Determine if the request has been dispatched
     *
     * @return boolean
     */
    public function isException()
    {
        return ($this->_exception instanceof Exception);
    }
    
    
    /**
     * Get request Exception
     *
     * @return Exception
     */
    public function getException()
    {
        return $this->_exception;
    }
    
    /**
     * Set flag indicating whether or not request has been dispatched
     *
     * @param boolean $flag
     * @return absRequest
     */
    public function setDispatched($flag = true)
    {
        $this->_dispatched = $flag ? true : false;
        return $this;
    }

    /**
     * Determine if the request has been dispatched
     *
     * @return boolean
     */
    public function isDispatched()
    {
        return $this->_dispatched;
    }
    
    protected $_response = null;
    
    public function setResponse($response,$code = null)
    {
        $this->_response  = $response;

        $this->httpResponse->body($response);

        if($code > 0)
        {
            $this->httpResponse->status($code);
        }
        
        return $this;
    }
    
    public function getResponse()
    {
        return $this->_response; 
    }
    
    public function isSecure()
    {
        $secure = false;
        if ( ! empty($_SERVER['HTTPS']) AND filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN))
        {
                // This request is secure
                $secure = TRUE;
        }
        
        return $secure;
    }
    
    public function toUrl()
    {
        $url = $this->getParam('core_url');
           
  
        $ignore_get_keys = array_merge(clsRegistry::getInstance()->get('route_indexes'),array('core_url','core_slug','core_controller','core_action'));

        $params = $this->getParams();
        foreach($params as $key => $v)
        {
            if(is_numeric($key) == true || in_array($key, $ignore_get_keys) == true || $v == '')
            {
                unset($params[$key]);
            }
        }

        if(count($params) > 0)
        {
            $url .= '?'.http_build_query($params);
        }
        

        return $url;
    }
    

    
    /**
    *
    * @param $httpResponse
    * @return $this 
    */
    public function setHttpResponse($httpResponse)
    {
        $this->httpResponse = $httpResponse;
        $this->httpResponse->setRequest($this);
        return $this;
    } 
    
    /**
     *
     * @return HTTP_Response
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    } 
    
    
}
