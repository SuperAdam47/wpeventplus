<?php

class EventPlus_Flash_Message {

    private $key = 'eventplus_flash_messages';
    private $defaultClass = 'updated';
    
    function setKey($key){
        $this->key = $key;
        
        return $this;
    }

    public function add($message, $class = '') {
        if ($class == '') {
            $class = $this->defaultClass;
        }
        
        $flash_messages = maybe_unserialize(get_option($this->key, array()));
        $flash_messages[$class][] = $message;
        update_option($this->key, $flash_messages);
    }

    public function render() {
        
        $flash_messages = maybe_unserialize(get_option($this->key, ''));
        update_option($this->key, array());
         
        echo EventPlus::dispatch('admin_widgets/flash_messages',array(
            'flash_messages' => $flash_messages
        ));
    }

}
