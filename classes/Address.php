<?php


class Address {
        private $_db;
        
        public function __construct($addressId = null) {
        $this->_db = DB::getInstance();
    }
    
        public function create($fields = array()) {
        if (!$this->_db->insert('address', $fields)) {
            throw new Exception("Unable to create Customer.");
        } else{
        return $this->_db->lastinsert();
        }
        
    }
}
