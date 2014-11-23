<?php

class Guest {

    private $_db,
            $_data;

    public function __construct($guestId = null) {
        $this->_db = DB::getInstance();
    }

    public function create($fields = array()) {
        if (!$this->_db->insert('customer', $fields)) {
            throw new Exception("Unable to create Customer.");
        }else{
        return $this->_db->lastinsert();
        }
    }

    public function find($guestId = null) {
        if ($guestId) {
            $data = $this->_db->get('customer', array('id', '=', $guestId));
            if ($data->count()) {
                $this->_data = $data->firstResult();
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    public function getCustomerData() {
        return $this->_data;
    }

    public function update($fields = array()) {
        if (count($fields) && $this->_data != null) {
            $id = $this->_data->id;

            $this->_db->update('customer', $id, $fields);
        }
    }

    public function delet($id = null) {
        if ($id) {
            $this->_db->delet('customer', array('id', '=', $id));
        }
        return FALSE;
    }

    public function findRoom($checkIn = null, $checkOut = null) {
        
        $sql = "SELECT capacity.name, capacity.capacity, room.id, room.number, room.discount, room.defaltprice FROM `capacity` INNER JOIN `room` ON capacity.id = room.capacity_id WHERE room.id NOT IN ( SELECT `room_id` FROM booking WHERE `checkin` <= $checkOut AND `checkout` >= $checkIn )";
        $room = $this->_db->query($sql);
       if($room->count()){
           $rooms = $room->results();
           return $rooms;
       }
       return FALSE;
        
    }

}
