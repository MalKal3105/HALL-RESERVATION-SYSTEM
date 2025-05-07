<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if(isset($_POST['get_bookings'])){

    $frm_data = filteration($_POST);

    $query = "SELECT bo.*, bd.* FROM `booking_order` bo
    INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
    WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
    AND (bo.booking_status=?) ORDER BY bo.booking_id ASC";

    $res = select($query,
        ["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","booked"],'ssss');

    $i=1;
    $table_data="";

    if(mysqli_num_rows($res)==0){
        echo"<b>No Data Found!</b>";
        exit;
    }

    while($data = mysqli_fetch_assoc($res)){

        $date = date("d-m-Y",strtotime($data['datentime']));
        $dates = date("d-m-Y",strtotime($data['date']));
        $id = $data['booking_id'];

        $table_data .="
        <tr>
            <td>$i</td>
            <td>
                <span class='badge bg-primary'>
                    Order ID: $data[order_id]
                </span>
                <br>
                <b>Name :</b> $data[user_name]
                <br>
                <b>Phone No:</b> $data[phonenum]
            </td>
            <td>
                <b>Hall:</b> $data[hall_name]
                <br>
                <b>Price:</b> RM$data[price]
            </td>
            <td>
                <b>Date Book:</b> $dates
                <br>
                <b>Days:</b> $data[days]
                <br>
                <b>Duration:</b> $data[duration] hours
                <br>
                <b>Paid:</b> RM$data[total_pay]
                <br>
                <b>Date Pay:</b> $date
            </td>
            <td>
                <button type='button' onclick='cancel_booking($data[booking_id])' class='mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none'>
                    <i class='bi bi-trash'></i> Cancel Booking
                </button> 
            </td>
        </tr>
        ";

        $i++;
    }

    echo  $table_data;
}


if(isset($_POST['cancel_booking'])){
    $frm_data = filteration($_POST);

    $query = "UPDATE `booking_order` SET `booking_status`=?, `refund`=? WHERE `booking_id`=?";
    $values = ['cancelled',0,$frm_data['booking_id']];
    $res = update($query,$values,'sii');

    echo $res;
}



?>