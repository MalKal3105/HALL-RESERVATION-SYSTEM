<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

if (isset($_POST['pay_now'])) {

    $ORDER_ID = 'ORD_' . $_SESSION['uId'] . random_int(11111, 9999999);
    $CUST_ID = $_SESSION['uId'];

    // Inser payment data into database

    $frm_data = filteration($_POST);
    $days = $_SESSION['halls']['price'];
    $countdays = $frm_data['days'];
    $duration = $frm_data['duration'];
    $TXN_AMOUNT = ($days * $countdays) + ($duration * ($days / 24));

    $query1 = "INSERT INTO booking_order(user_id, hall_id,days, duration, date, order_id, transfer_amt) VALUES (?,?,?,?,?,?,?)";

    insert($query1, [$CUST_ID, $_SESSION['halls']['id'],$frm_data['days'], $frm_data['duration'], $frm_data['date'], $ORDER_ID, $TXN_AMOUNT], 'isssssi');

    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO booking_details(booking_id, hall_name, price, total_pay,
     user_name, phonenum, address) VALUES (?,?,?,?,?,?,?)";

    insert($query2, [$booking_id, $_SESSION['halls']['name'], $TXN_AMOUNT, $TXN_AMOUNT, $frm_data['name'], $frm_data['phonenum'], $frm_data['address']], 'issssss');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            outline: none;
            border: none;
            text-transform: capitalize;
            transition: all .2s linear;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            min-height: 100vh;
            background: linear-gradient(90deg, #2ecc71 60%, #27ae60 40.1%);
        }

        .container form {
            padding: 20px;
            width: 700px;
            background: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
        }

        .container form .row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .container form .row .col {
            flex: 1 1 250px;
        }

        .container form .row .col .title {
            font-size: 20px;
            color: #333;
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        .container form .row .col .inputBox {
            margin: 15px 0;
        }

        .container form .row .col .inputBox span {
            margin-bottom: 10px;
            display: block;
        }

        .container form .row .col .inputBox input {
            width: 100%;
            border: 1px solid #ccc;
            padding: 10px 15px;
            font-size: 15px;
            text-transform: none;
        }

        .container form .row .col .inputBox input:focus {
            border: 1px solid #000;
        }

        .container form .row .col .flex {
            display: flex;
            gap: 15px;
        }

        .container form .row .col .flex .inputBox {
            margin-top: 5px;
        }

        .container form .row .col .inputBox img {
            height: 34px;
            margin-top: 5px;
            filter: drop-shadow(0 0 1px #000);
        }

        .container form .submit-btn {
            width: 100%;
            padding: 12px;
            font-size: 17px;
            background: #27ae60;
            color: #fff;
            margin-top: 5px;
            cursor: pointer;
        }

        .container form .submit-btn:hover {
            background: #2ecc71;
        }
    </style>
</head>

<body>
    <div class="container">

        <form action="pay_status.php?order=<?php echo urlencode($ORDER_ID); ?>">

            <div class="row">

                <div class="col">

                    <h3 class="title">billing address</h3>

                    <div class="inputBox">
                        <span>full name :</span>
                        <input type="text" placeholder="john deo" required>
                    </div>
                    <div class="inputBox">
                        <span>email :</span>
                        <input type="email" placeholder="example@example.com" required>
                    </div>
                    <div class="inputBox">
                        <span>address :</span>
                        <input type="text" placeholder="room - street - locality" required>
                    </div>
                    <div class="inputBox">
                        <span>city :</span>
                        <input type="text" placeholder="Johor" required>
                    </div>

                    <div class="flex">
                        <div class="inputBox">
                            <span>state :</span>
                            <input type="text" placeholder="Malaysia" required>
                        </div>
                        <div class="inputBox">
                            <span>zip code :</span>
                            <input type="text" placeholder="123 456" required>
                        </div>
                    </div>

                </div>

                <div class="col">

                    <h3 class="title">payment</h3>

                    <div class="inputBox">
                        <span>name on card :</span>
                        <input type="text" placeholder="mr. john deo" required>
                    </div>
                    <div class="inputBox">
                        <span>credit card number :</span>
                        <input type="number" placeholder="1111-2222-3333-4444" required>
                    </div>
                    <div class="inputBox">
                        <span>exp month :</span>
                        <input type="text" placeholder="january" required>
                    </div>

                    <div class="flex">
                        <div class="inputBox">
                            <span>exp year :</span>
                            <input type="number" placeholder="2022" required>
                        </div>
                        <div class="inputBox">
                            <span>CVV :</span>
                            <input type="text" placeholder="1234" required>
                        </div>
                    </div>

                </div>

            </div>
            <input type="hidden" name="order" value="<?php echo $ORDER_ID; ?>">

            <button onclick="urlweb($ORDER_ID)" type="submit" class="submit-btn">Submit</button>
        </form>

    </div>

    <script>
        if (isset($_GET['order'])) {
            $orderIDFromURL = $_GET['order'];
            // You can use $orderIDFromURL in your pay_status.php script
        } else {
            // Handle the case where ORDERID is not provided in the URL
        }

        function urlweb(orderid) {
            if (orderid) {
                window.location.href = 'pay_status.php?order=' + orderid;
            } else {
                alert('error', 'Please login to book hall!');
            }
        }

        setActive();
    </script>
</body>

</html>