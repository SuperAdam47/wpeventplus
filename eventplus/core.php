<?php  
/**
 * EventPlus - Core Class
 */
class EventPlus_Core
{
    const version = "1.1";
    
    const INFO = "information";
    const SUCCESS = "success";
    const ERROR = "error";
    const ATTENTION = "attention";
    
    //app modes
    const DEVELOPMENT = 'development';
    const STAGING     = 'staging';
    const TESTING     = 'testing';
    const PRODUCTION  = 'production';
    
    protected $php_errors = array(
        E_ERROR              => 'Fatal Error',
        E_USER_ERROR         => 'User Error',
        E_PARSE              => 'Parse Error',
        E_WARNING            => 'Warning',
        E_USER_WARNING       => 'User Warning',
        E_USER_NOTICE        => 'User Notice',
        E_STRICT             => 'Strict',
        E_NOTICE             => 'Notice',
        E_RECOVERABLE_ERROR  => 'Recoverable Error',
    );
   
    protected $mode = '';
    
    function __construct(array $params = array('mode' => 'development')) {
        $this->setMode($params['mode']);
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }
    
    function getMode()
    {
        return $this->mode;
    }
    
    function getErrorTitle($key)
    {
        return isset($this->php_errors[$key]) ? $this->php_errors[$key] : '';
    }
}