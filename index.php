<?php
require_once './core/init.php';


?>
<table>
    <tr>
        <td>Room No</td><td>Room type</td><td>Price($)</td><td>&nbsp;</td>
    </tr>
    <tr>
<?php
//Order::getPrice(50,'2014-11-20', '2014-11-25');

if(Input::exists()){
    if(Token::check(Input::get('token'))){
 $validate = new Validator();
$validate->check($_POST, array(
    'checkin' => array(
        'required' => true,
        'date' => 'Y-m-d',
    ),
    'checkout' => array(
        'required' => true,
        'date' => 'Y-m-d',
    )
));

if($validate->passed()){
   if(Validator::checkDateIsGrater(Input::get('checkout'), Input::get('checkin'))){
       $rooms = new Guest();
$cheakin = Input::get('checkin');
$checkout = Input::get('checkout');
$rooms = $rooms->findRoom($cheakin, $checkout);
if($rooms){

foreach ($rooms as $room){
   // var_dump($room);
    echo "<td>{$room->number}</td><td>{$room->name}</td><td>{$room->defaltprice}</td><td><a href='booking.php?checkin=".urlencode($cheakin)."&checkout=".urlencode($checkout)."&roomid=".$room->id."&token=".Token::generate()."&price=".$room->defaltprice."'>book it</a></td>";
}
}

   } else {
       echo "Check check out date should be greater than check in";
   }
} else {
    foreach ($validate->errors() as $error){
        echo $error;
    }
    }
}

}




?>
            </tr>
</table>
<form action="" method="post">
    <p>Check In : <input type="text" name="checkin" id="checkin" placeholder="015-11-20"></p>
    <p>Check In : <input type="text" name="checkout" id="checkout" placeholder="015-11-20"></p>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"/>
    <input type="submit" name="submit" value="Serch For Rooms">
    
</form>
<style type="text/css">
    table{
        
    }
    td{
        padding: 10px;
    }
</style>

        



