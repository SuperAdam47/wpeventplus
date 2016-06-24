<?php

class EventPlus_Var {
    
    function get($key, $data){
       if(isset($data[$key])){
           return $data[$key];
       }
       return false;
    }
}
