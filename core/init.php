<?php

session_start();
// configetation
$GLOBALS['config'] = array(
    'mysql' => array(
        'host'       => 'localhost',
        'dbname'     => 'HotelReservation',
        'dbuser'     => 'root',
        'dbpassword' => 'rrrr'
    ),
    'session' => array(
        'token_name' => 'token',
        'paypal_token' => 'paypal'
    ),
    'paypal' => array(
        'clientid' =>'AYp5tBCZVUIyv3h3i2lFMYe4PobbkQJX9SMAHGMILRTLJN-mWwWyCPjgAkM-',
        'secrectid' => 'EKO8ahAM_ccS3-gx7sJmLIjyisTfTzCVdJxyqdLcG3_vqgAYFEHDQmFihT3c',
        'redirecturl' => 'http://localhost/hotelreservation/payment.php?approved=true',
        'cancelurl' => 'http://localhost/hotelreservation/payment.php?approved=false'
    )
);

//Loding the class file
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

//payment cionfig
