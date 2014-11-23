<?php

class Validator {

    private $_db,
            $_pass = false,
            $_errors = array();

    public function __construct() {
        $this->_db = DB::getInstance();
    }
    
    
    public function check($source, $items = array()){
        foreach ($items as $item => $ruls){
            foreach ($ruls as $rule => $rule_value){
                $value = $source[$item];
                if($rule === 'required' && empty($value)){
                    $this->addError("{$item} is requires.");
                }
                
               if($rule === 'date'){
                   $format = $rule_value;
                   if(!$this->validateDate($value, $format)){
                       $this->addError("{$item} is should be {$format} fromat."); 
                   }
                   
               }
            }
        }
        
        if(empty($this->_errors)){
            $this->_pass = true;
        }
    }
    
    public static function checkDateIsGrater($firstdate, $seconddate){
        if(strtotime($firstdate) > strtotime($seconddate)){
            return true;
        } else {
            return false;
        }
    }

    private function validateDate($date, $format) {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    public function addError($error){
        $this->_errors[] = $error;
    }
    
    public function errors(){
        return $this->_errors;
    }
    
    public function passed(){
        return $this->_pass;
    }

}
