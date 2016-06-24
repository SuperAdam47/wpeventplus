<?php

class EventPlus_Registry {

    protected $vars = array();

    /**
     * @get variables
     * @param mixed $index
     * @return mixed
     */
    public function __get($index) {
        if ($this->is($index)) {
            return $this->vars[$index];
        }
    }

    function set($key, $value) {
        $this->vars[$key] = $value;
        return $this;
    }

    function is($key) {
        return isset($this->vars[$key]);
    }

    function get($key) {
        return $this->vars[$key];
    }

}
