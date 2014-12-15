<?php
require_once './core/init.php';
// Order processing code will go here

if (Input::exists('post')) {
    $name = Input::get('name');
    $email = Input::get('email');
    $phone = Input::get('phone');
    $street = Input::get('street');
    $city = Input::get('city');
    $state = Input::get('state');
    $zip = Input::get('zip');
    $country = Input::get('country');
    $guestno = Input::get('guestno');
    $price = Input::get('price');
    $checkin = Input::get('checkin');
    $checkout = Input::get('checkout');
    $amount = Order::getPrice($price, $checkin, $checkout);
    $orderdate = date("Y-m-d");
    $roomid = Input::get('roomid');


    //Creat address
    $address = new Address();
    try {
        $addressid = $address->create(array(
            'street' => $street,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'zip' => $zip
        ));
    } catch (Exception $e) {
        echo $e;
    }
    //Creat Guest
    $customer = new Guest();
    try {
        $cestomerid = $customer->create(array(
            'name' => $name,
            'address_id' => $addressid,
            'phoneno' => $phone,
            'email' => $email
        ));
    } catch (Exception $e) {
        echo $e;
    }
    //Creat Order
    //Get amount

    $orde = new Order();
    try {
        $orderid = $orde->createOrder(array(
            'customer_id' => $cestomerid,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'room_id' => $roomid,
            'quantity' => 1,
            'bookingdate' => $orderdate,
            'status' => 0,
            'guestno' => $guestno,
            'totalamount' => $amount,
            'discountamount' => 0,
            'paidamount' => $amount,
            'dueamount' => 0,
        ));
       Session::put('order_id', $orderid);
       $payment = new Pay();
       $payment->pay($amount, "Payment for reservation");
       
    } catch (Exception $e) {
        echo 'Unable to place order at this moment';
    }

}
if (Input::exists('get') /* && Token::check(Input::get('token')) */) {
    ?>
    <p>
        Check in date  : <?php echo Input::get('checkin'); ?><br>
        Check out date : <?php echo Input::get('checkout'); ?>
    </p>
    <p>
    <form action="" method="post">
        <table>
            <tr>
                <td>Name :</td>
                <td><input type="text" name="name"></td>
            </tr>
            <tr>
                <td>Email :</td>
                <td><input type="text" name="email"></td>
            </tr>
            <tr>
                <td>Phone No :</td>
                <td><input type="text" name="phone"></td>
            </tr>
            <tr>
                <td>Street :</td>
                <td><input type="text" name="street"></td>
            </tr>
            <tr>
                <td>City :</td>
                <td><input type="text" name="city"></td>
            </tr>
            <tr>
                <td>State :</td>
                <td><input type="text" name="state"></td>
            </tr>
            <tr>
                <td>Zip :</td>
                <td><input type="text" name="zip"></td>
            </tr>
            <tr>
                <td>Country :</td>
                <td><input type="text" name="country"></td>
            </tr>
            <tr>
                <td>Guest No :</td>
                <td><input type="text" name="guestno"></td>
            </tr>

            <input type="hidden" name="checkin" value="<?php echo Input::get('checkin'); ?>">
            <input type="hidden" name="checkout" value="<?php echo Input::get('checkout'); ?>">
            <input type="hidden" name="price" value="<?php echo Input::get('price'); ?>">
            <input type="hidden" name="roomid" value="<?php echo Input::get('roomid'); ?>">

            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" value="Book"></td>
            </tr>


        </table>
    </form>
    </p>
    <?php
}