<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="css/common.css">
    <title><?php echo $settings_r['site_title'] ?> - BOOKINGS</title>

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

    <?php require('inc/header.php');

    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }
    ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5  px-4">
                <h2 class="fw-bold">BOOKINGS</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-dark text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-dark text-decoration-none">BOOKINGS</a>
                </div>
            </div>

            <?php
            $query = "SELECT bo.*, bd.* FROM `booking_order` bo
                    INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
                    WHERE ((bo.booking_status='booked')
                    OR (bo.booking_status='cancelled')
                    OR (bo.booking_status='payment failed'))
                    AND (bo.user_id=?)
                    ORDER BY bo.booking_id DESC";

            $result = select($query, [$_SESSION['uId']], 'i');

            while ($data = mysqli_fetch_assoc($result)) {

                $date = date("d-m-Y", strtotime($data['datentime']));
                $dates = date("d-m-Y", strtotime($data['date']));
                $id = $data['booking_id'];

                $status_bg = "";
                $btn = "";

                if ($data['booking_status'] == 'booked') {
                    $status_bg = 'bg-success';

                    $btn = "<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>
                            <button type='button' onclick='cancel_booking($data[booking_id])' class='btn btn-danger btn-sm shadow-none'>Cancel</button>";

                    if ($data['rate_review'] == 0) {
                        $btn .= "<button type='button' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-dark btn-sm shadow-none ms-2'>Rate & Review</button>";
                    }
                } else if ($data['booking_status'] == 'cancelled') {
                    $status_bg = "bg-danger";

                    if ($data['refund'] == 0) {
                        $btn = "<span class='badge bg-primary'>Refund in process!</span> ";
                    } else {
                        $btn = "<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
                    }
                } else {
                    $status_bg = "bg-warning";
                    $btn = "<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
                }

                echo <<<bookings
                            <div class='col-md-4 px-4 mb-4'>
                                <div class='bg-white p-3 rounded shadow-sm'>
                                    <h5 class='fw-bold'>$data[hall_name]</h5>
                                    <p>RM$data[price] per day</p>
                                    <p>
                                        <b>Date: </b> $dates <br>
                                        <b>Hours: </b> $data[duration]
                                    </p>
                                    <p>
                                        <b>Amount: </b> RM$data[price] <br>
                                        <b>Order ID: </b> $data[order_id] <br>
                                        <b>Date Pay: </b> $date
                                    </p>
                                    <p>
                                        <span class='badge $status_bg'>$data[booking_status]</span>
                                    </p>
                                    $btn
                                </div>
                            </div>
                        bookings;
            }
            ?>

        </div>
    </div>

    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i> Rate & Review
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Exellent</option>
                                <option value="4">Good</option>
                                <option value="3">Ok</option>
                                <option value="2">Poor</option>
                                <option value="1">Bad</option>
                            </select>
                        </div>
                        <div class="mb-4 ">
                            <label class="form-label">Review</label>
                            <textarea name="review" type="password" id="" rows="3" class="form-control shadow-none"></textarea>
                        </div>
                        <input type="hidden" name="booking_id">
                        <div class="d-flex align-items-center justify-content-between">
                            <button type="submit" class="btn btn-dark shadow-none">LOGIN</button>
                            <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">
                                Forgot Password?
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['cancel_status'])) {
        alert('success', 'Booking Cancelled!');
    }
    ?>


    <?php require('inc/footer.php'); ?>

    <script>
        function cancel_booking(id) {
            if (confirm('Are you sure to cancel booking?')) {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/cancel_booking.php", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (this.responseText == 1) {
                        window.location.href = "bookings.php?cancel_status=true";
                    } else {
                        alert('error', 'Cancellation Failed!');
                    }
                }

                xhr.send('cancel_booking&id=' + id);
            }
        }
    </script>

</body>

</html>