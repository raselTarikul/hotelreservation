<?php

class Order {

    private $_db,
            $_data;
    
        public function __construct($guestId = null) {
        $this->_db = DB::getInstance();
    }

    public static function getPrice($price, $checkin, $checkout) {
        $checkindate = new DateTime($checkin);
        $checkoutdate = new DateTime($checkout);
        $diff = $checkoutdate->diff($checkindate);
        return $price * $diff->format('%d');
    }

    public function createOrder($fields = array()) {
        if (!$this->_db->insert('booking', $fields)) {
            throw new Exception("Unable to create Order at the moment.");
        } else {
            return $this->_db->lastinsert();
        }
    }

}
