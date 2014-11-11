<?php

session_start();
// configetation
$GLOBALS['config'] = array(
    'mysql' => array(
        'host'       => 'localhost',
        'dbname'     => 'HotelReservation',
        'dbuser'     => 'root',
        'dbpassword' => 'rrrr'
    )
);

//Loding the class file
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});