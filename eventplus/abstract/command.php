<?php

abstract class EventPlus_Abstract_Command {
 
    protected $_data = null;
    protected $_message = null;
    protected $_method = '';

    /**
     *
     * @var bool
     */
    protected $_isExecuted = false;

    /**
     *
     * @var mixed
     */
    protected $_response = null;

    public function getMessage() {
        return $this->_message;
    }

    public function setMessage($message) {
        $this->_message = $message;
        return $this;
    }

    public function setData(array $data) {
        $this->_data = $data;
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getResponse() {
        return $this->_response;
    }

    /**
     *
     * @return bool
     */
    public function isExecuted() {
        return $this->_isExecuted;
    }

    /**
     *
     * @param mixed $key
     * @return array | mixed
     */
    public function getData($key = '') {
        if ($key === '')
            return $this->_data;
        else
            return $this->_data[$key];
    }

    /**
     * This method will be invoked before Actual Command's execution
     */
    public function preExecution() {
        
    }

    /**
     * Abstract Method
     * Execute a Command
     *  @return mixed | $response
     */
    abstract protected function _Execute();

    /**
     * This method will be invoked after Actual Command's execution
     */
    public function postExecution() {
        
    }

    /**
     * Execute Command 
     * @uses: _Execute(true)
     * 
     * @param void
     * @return mixed | $response
     */
    public function Execute() {
        $this->preExecution();

        $this->_response = $this->_Execute();

        if ($this->_response !== false) {
            $this->_isExecuted = true;
        }

        $this->postExecution();

        return $this;
    }
}
