<?php
class EventPlus_Dispatcher extends EventPlus_Abstract_Dispatcher 
{
    /**
    * Dispatch request
    */
    protected function _dispatch()
    {                 
        $controllerClass = $this->formatController($this->_controller);
        /**
         * Instantiate controller with request, response, and invocation
         * arguments; throw exception if it's not an action controller
         */
        $this->oController = new $controllerClass($this,$this->getParams());

        
        if (is_object($this->oController) == false) {
            throw new Exception(
                'Controller "' . $controllerClass . '" - ivalid instance ', 404
            );
        }
        
        $request = $this->getRequest();
        $request->setDispatched(true);
        
        $this->oController->setRequest($request);
        
        $this->oController->dispatch($this->getActionMethod());
        
    }

}
