<?php

require('inc/essentials.php');
require('inc/db_config.php');
require('inc/mpdf/vendor/autoload.php');

adminLogin();

if (isset($_GET['gen_pdf']) && isset($_GET['id'])) {
    $frm_data = filteration($_GET);

    $query = "SELECT bo.*, bd.* , uc.email FROM `booking_order` bo
    INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
    INNER JOIN user_cred uc ON bo.user_id = uc.id
    WHERE ((bo.booking_status='booked')
    OR (bo.booking_status='cancelled' AND bo.refund=0)
    OR (bo.booking_status='payment failed'))
    AND bo.booking_id = '$frm_data[id]'";

    $res = mysqli_query($con, $query);

    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        header('location: dashboard.php');
        exit;
    }

    $data = mysqli_fetch_assoc($res);
    $date = date("h:ia | d-m-Y", strtotime($data['datentime']));
    $dates = date("d-m-Y", strtotime($data['date']));
    $id = $data['booking_id'];

    $table_data = "
        <h2>BOOKING RECEIPT</h2>
        <table border='1'>
         <tr>
            <td>Order ID: $data[order_id]</td>
            <td>Booking Date: $date</td>
         </tr>
         <tr>
            <td colspan='2'>Status: $data[booking_status]</td>
         </tr>
         <tr>
            <td>Name : $data[user_name]</td>
            <td>Email: $data[email]</td>
         </tr>
         <tr>
            <td>Phone Number : $data[phonenum]</td>
            <td>Address: $data[address]</td>
         </tr>
         <tr>
            <td>Hall Name : $data[hall_name]</td>
            <td>Cost: $data[price]</td>
         </tr>
         <tr>
            <td colspan='2'>Date Reserve : $dates</td>
         </tr>
    ";

    if ($data['booking_status'] == 'cancelled') {
        $refund = ($data['refund']) ? "Amount Refunded" : "Not Yet Refunded";

        $table_data .= "<tr>
            <td colspan='2'>Amount Paid: $data[total_pay]</td>
            <td colspan='2'>Refund: $refund</td>
        </tr>";
    } else {
        $table_data .= "<tr>
            <td colspan='2'>Amount Paid: $data[total_pay]</td>
        </tr>";
    }

    $table_data.= "</table>";

    $mpdf = new \Mpdf\Mpdf();

    $mpdf->WriteHTML($table_data);

    $mpdf->Output($data['order_id'].'.pdf','D');

} else {
    header('location: dashboard.php');
}

?>