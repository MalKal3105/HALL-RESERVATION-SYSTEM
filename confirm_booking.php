<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="css/common.css">
    <title><?php echo $settings_r['site_title'] ?> - CONFIRM BOOKING</title>

    <style>
        @media screen and(max-width: 575px) {
            .availability-form {
                margin-top: 25px;
                padding: 0 35px;
            }
        }

        .box {
            border-top-color: var(--teal) !important;
        }

        .custom-alert {
            position: fixed;
            top: 80px;
            right: 25px;
            z-index: 1111;
        }
    </style>
</head>

<body style="background-color: rgb(72, 106, 106);">

    <?php require('inc/header.php'); ?>

    <?php

    if (!isset($_GET['id']) || $settings_r['shutdown'] == true) {
        redirect('halls.php');
    } else if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('halls.php');
    }

    //filter and get room and user data

    $data = filteration($_GET);

    $halls_res = select("SELECT * FROM halls WHERE id=? AND status=? AND removed=?", [$data['id'], 1, 0], 'iii');

    if (mysqli_num_rows($halls_res) == 0) {
        redirect('halls.php');
    }

    $halls_data = mysqli_fetch_assoc($halls_res);

    $_SESSION['halls'] = [
        "id" => $halls_data['id'],
        "name" => $halls_data['name'],
        "price" => $halls_data['price'],
        "payment" => null,
        "available" => false,
    ];

    $user_res = select("SELECT * FROM user_cred WHERE id = ? LIMIT 1", [$_SESSION['uId']], "i");
    $user_data = mysqli_fetch_assoc($user_res);

    ?>


    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">CONFRIM BOOKING</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-dark text-decoration-none">HOME</a>
                    <span class="text-dark"> > </span>
                    <a href="halls.php" class="text-dark text-decoration-none">HALLS</a>
                    <span class="text-dark"> > </span>
                    <a href="#" class="text-dark text-decoration-none">CONFRIM</a>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 px-4">

                <?php
                $halls_thumb = HALL_IMG_PATH . "thumbnail.jpg";
                $thumb_q = mysqli_query($con, "SELECT * FROM hall_images 
                                WHERE hall_id='$halls_data[id]' 
                                AND thumb='1'");

                if (mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $halls_thumb = HALL_IMG_PATH . $thumb_res['image'];
                }

                echo <<<data
                            <div class="card p-3 shadow-sm rounded">
                                <img src="$halls_thumb" class="img-fluid rounded mb-3">
                                <h5>$halls_data[name]</h5>
                                <h6>RM$halls_data[price] per day</h6>
                            </div>
                        data;
                ?>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="pay_now.php" method="POST" id="booking_form">
                            <h6 class="mb-3">BOOKING DETAILS</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input name="phonenum" type="number" value="<?php echo $user_data['phonenum'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $user_data['address'] ?></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Date</label>
                                    <input name="date" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Days (optional)</label>
                                    <input name="days" onchange="check_availability()" type="text" class="form-control shadow-none" placeholder="0" value="0" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Duration Hour</label>
                                    <select onchange="check_availability()" name="duration" class="form-select shadow-none">
                                        <option value="0">Duration</option>
                                        <option value="1">1 Hour</option>
                                        <option value="2">2 Hour</option>
                                        <option value="3">3 Hour</option>
                                        <option value="4">4 Hour</option>
                                        <option value="5">5 Hour</option>
                                        <option value="6">6 Hour</option>
                                        <option value="7">7 Hour</option>
                                        <option value="8">8 Hour</option>
                                        <option value="9">9 Hour</option>
                                        <option value="10">10 Hour</option>
                                        <option value="11">11 Hour</option>
                                        <option value="12">12 Hour</option>
                                        <option value="13">13 Hour</option>
                                        <option value="14">14 Hour</option>
                                        <option value="15">15 Hour</option>
                                        <option value="16">16 Hour</option>
                                        <option value="17">17 Hour</option>
                                        <option value="18">18 Hour</option>
                                        <option value="19">19 Hour</option>
                                        <option value="20">20 Hour</option>
                                        <option value="21">21 Hour</option>
                                        <option value="22">22 Hour</option>
                                        <option value="23">23 Hour</option>
                                        <option value="24">24 Hour</option>
                                    </select>
                                </div>
            
                                <div class="col-12">
                                    <h6 class="mb-3" id="pay_info"></h6>
                                    <button name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1"><i class="bi bi-send"></i> Pay</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



        </div>
    </div>


    <?php require('inc/footer.php'); ?>
    <script>

        let booking_form = document.getElementById('booking_form');

    function check_availability(){

        let duration_val = booking_form.elements['duration'].value;
        let date_val = booking_form.elements['date'].value;
        let days_val = booking_form.elements['days'].value;

        let data = new FormData();

        data.append('check_availability','');
        data.append('duration',duration_val);
        data.append('date',date_val);
        data.append('days',days_val);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/confirm_booking.php", true);

        xhr.onload = function() {
            let data = JSON.parse(this.responseText);
        
            pay_info.innerHTML = data.days+" Days and "+data.duration+" Hours<br>Total Amount To Pay: RM"+data.payment.toFixed(2);
        }
        xhr.send(data);
    }
    </script>

</body>

</html>