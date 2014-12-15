<?php


class Roomtype {
    private $_db,
            $_data;
    
    public function __construct($roomTypeId = NULL) {
        $this->_db = DB::getInstance();
       
    }
    
    public function create($fields = array()) {
        if (!$this->_db->insert('roomtype', $fields)) {
            throw new Exception("Unable to create room.");
        }
    }
    
    public function find($roomTypeId = null) {
        if ($roomTypeId) {
            $data = $this->_db->get('roomtype', array('id', '=', $roomTypeId));
            if ($data->count()) {
                $this->_data = $data->firstResult();
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }
    
        public function getRoomTypeData() {
        return $this->_data;
    }
    
    public function update($fields = array()) {
        if (count($fields) && $this->_data != null) {
            $id = $this->_data->id;

            $this->_db->update('roomtype', $id, $fields);
        }
    }
    
    public function delet($id = null) {
        if ($id) {
            $this->_db->delet('roomtype', array('id', '=', $id));
        }
        return FALSE;
    }
}
