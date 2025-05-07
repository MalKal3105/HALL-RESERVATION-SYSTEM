<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve System - TESTIMONIALS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="css/common.css">

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
    </style>
</head>

<body style="background-color: rgb(72, 106, 106);">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">ABOUT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe quae natus, sapiente qui, corporis <br>
            fugiat magni quaerat tempora suscipit sed temporibus, laudantium omnis laborum cumque minus molestiae consectetur repellat vel.
        </p>
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">Lorem, ipsum dolor sit</h3>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit sit doloribus
                    fugiat in, iusto incidunt? Deleniti vero provident, est impedit modi distinctio quo sed assumenda
                    doloremque numquam optio quidem molestias.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="images/about/loque.jpg" class="w-100">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/Reservesystem.jpeg" width="70px">
                    <h4 class="mt-3">100+ HALLS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/user.jpeg" width="70px">
                    <h4 class="mt-3">500+ USERS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/rating.png" width="70px">
                    <h4 class="mt-3">150+ REVIEWS</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/Reservesystem.jpeg" width="70px">
                    <h4 class="mt-3">100+ HALLS</h4>
                </div>
            </div>
        </div>
    </div>

    <h3 class="my-5 fw-bold text-center">MANAGEMENT TEAM</h3>

    <div class="container px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                <?php
                $about_r = selectAll('team_details');
                $path = ABOUT_IMG_PATH;
                while($row = mysqli_fetch_assoc($about_r)){
                    echo<<<data
                    <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                        <img src="$path$row[picture]" class="w-100">
                        <h5 class="mt-2">$row[name]</h5>
                    </div>
                    data;
                }

                ?> 
                <br>
            </div>
            <br>
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <?php require('inc/footer.php'); ?>


    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoints: {
                320: {
                    slidesPerView:1,
                },
                640: {
                    slidesPerView:1,
                },
                768: {
                    slidesPerView:3,
                },
                1024: {
                    slidesPerView:3,
                },
            }
        });
    </script>

</body>

</html>