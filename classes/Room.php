<?php

class Room {

    private $_db,
            $_data;

    public function __construct($roomId = NULL) {
        $this->_db = DB::getInstance();
        if ($roomId) {
            $this->find($roomId);
        }
    }

    public function create($fields = array()) {
        if (!$this->_db->insert('room', $fields)) {
            throw new Exception("Unable to create room.");
        }
    }

    public function find($roomId = null) {
        if ($roomId) {
            $data = $this->_db->get('room', array('id', '=', $roomId));
            if ($data->count()) {
                $this->_data = $data->firstResult();
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    public function getRoomData() {
        return $this->_data;
    }

    public function update($fields = array()) {
        if (count($fields) && $this->_data != null) {
            $id = $this->_data->id;

            $this->_db->update('room', $id, $fields);
        }
    }

    public function delet($id = null) {
        if ($id) {
            $delet = $this->_db->delet('room', array('id', '=', $id));
        }
        return FALSE;
    }

}
