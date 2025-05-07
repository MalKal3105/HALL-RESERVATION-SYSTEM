<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if(isset($_POST['check_availability'])){

    $frm_data = filteration($_POST);
    $status = "";
    $result = ""; 

    session_start();
    $_SESSION['halls'];

    $days = $_SESSION['halls']['price'];
    $countdays = $frm_data['days'];
    $duration = $frm_data['duration'];
    $payment = ($days * $countdays) + ($duration * ($days / 24));
    $paymentFormatted = number_format($payment, 2); // Format to 2 decimal places

    $_SESSION['halls']['payment'] = $paymentFormatted;
    $_SESSION['halls']['available'] = true;

    $result = json_encode(["status"=>'available', "days"=>$countdays, "duration"=>$duration, "payment"=>$payment]);
    echo $result;

}

?>