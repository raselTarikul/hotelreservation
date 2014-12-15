<?php

require_once './core/init.php';
if (Input::exists('get')) {
    if (Input::get('approved') === 'true' && Input::get('paymentId') === Session::get(Config::get('session/paypal_token'))) {
        $payerId = Input::get('PayerID');
        $paymentId = Input::get('paymentId');
        $payment = new Pay();
        $payment->execute($payerId, $paymentId);
        // update the order status.
        $order = new Order();
        $order->conferm(Session::get('order_id'));
        // reomve the oder id info sesson
        Session::delete('order_id');
        Session::delete(Config::get('session/paypal_token'));
        echo 'You order has been processed successfully';
    } elseif (nput::get('approved') === 'false') {
        // delet the order
        //  $order = new Order();
        $order->remove(Session::get('order_id'));
        Session::delete('order_id');
        Session::delete(Config::get('session/paypal_token'));

        echo 'Your payment has been cancled';
    }
}
header('Location : index.php');
