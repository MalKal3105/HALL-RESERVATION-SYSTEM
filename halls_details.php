<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve System - HALLS</title>
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

    <?php
    if (!isset($_GET['id'])) {
        redirect('halls.php');
    }

    $data = filteration($_GET);

    $halls_res = select("SELECT * FROM `halls` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

    if (mysqli_num_rows($halls_res) == 0) {
        redirect('halls.php');
    }

    $halls_data = mysqli_fetch_assoc($halls_res);
    ?>
    

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-4 px-4 ">
                <h2 class="fw-bold"><?php echo $halls_data['name'] ?></h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="halls.php" class="text-secondary text-decoration-none">HALLS</a>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 px-4">
                <div id="hallCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php

                            $halls_img = HALL_IMG_PATH . "thumbnail.jpg";
                            $img_q = mysqli_query($con, "SELECT * FROM `hall_images` 
                                WHERE `hall_id`='$halls_data[id]'");
 
                            if (mysqli_num_rows($img_q)>0) {
                                $active_class='active';

                                while($img_res = mysqli_fetch_assoc($img_q))
                                {
                                    echo"
                                        <div class='carousel-item $active_class'>
                                            <img src='".HALL_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
                                        </div>";
                                    $active_class='';
                                }
                            }
                            else {
                                echo"<div class='carousel-item active'>
                                    <img src='$halls_img' class='d-block w-100'>
                                </div>";
                            }
                       
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#hallCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#hallCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

        <div class="col-lg-5 col-md-12 px-4">
            <div class="card mb-4 border-0 shadow-sm rounded-3"> 
                <div class="card-body">
                    <?php
                  
                        echo<<<price
                          <h4>Start from RM$halls_data[price]</h4>
                        price;

                        echo<<<rating
                            <div class="mb-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                        rating;

                        $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f
                            INNER JOIN `hall_features` rfea ON f.id = rfea.features_id
                            WHERE rfea.hall_id = '$halls_data[id]'");

                        $features_data = "";
                        while($fea_row = mysqli_fetch_assoc($fea_q)){
                            $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                            $fea_row[name]
                            </span>";
                        }

                        echo<<<Person
                            <div class="mb-3">
                                <h6 class="mb-1">Hall Capacity</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                    $halls_data[quantity] person
                                </span>
                            </div>
                        Person;

                        echo<<<features
                            <div class="mb-3">
                                <h6 class="mb-1">Features</h6>
                                $features_data
                            </div>
                        features;

                        $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f
                            INNER JOIN `hall_facilities` rfac ON f.id = rfac.facilities_id
                            WHERE rfac.hall_id = '$halls_data[id]'");

                        $facilities_data = "";
                        while($fac_row = mysqli_fetch_assoc($fac_q)){
                            $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                            $fac_row[name]
                            </span>";
                        }

                        echo<<<facilities
                            <div class="mb-3">
                                <h6 class="mb-1">Facilities</h6>
                                $facilities_data
                            </div>
                        facilities;

                        echo<<<area
                            <div class="mb-3">
                                <h6 class="mb-1">Area</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                    $halls_data[area] sq. ft.    
                                </span>
                            </div>
                        area;

                        if(!$settings_r['shutdown']){
                            $login=0;
                            if(isset($_SESSION['login']) && $_SESSION['login']==true){
                                $login=1;
                            }
                            echo<<<book
                                <button onclick='checkLoginToBook($login,$halls_data[id])' class="btn w-100 text-white custom-bg shadow-none mb-1">Book Now</button>
                            book;
                        }
                    ?>
                </div>
            </div>
        </div>




            <div class="col-12 mt-4 px-4">
                    <div class="mb-4">
                      <h5>Description</h5>
                        <p>
                            <?php echo $halls_data['description'] ?>
                        </p>
                    </div>

                    <div>
                        <h5 class="mb-3">Reviews $ Ratings</h5>
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-person-fill"></i>
                                <h6 class="m-2 ms-2 align-items-center">Random User 1</h6>
                            </div>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto possimus aperiam accusantium laboriosam.
                                Excepturi, eius. Veniam laborum enim temporibus ipsa officiis quas et
                                culpa labore quasi, laudantium expedita adipisci exercitationem.
                            </p>
                            <div class="rating mt-5">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                            </div>
                        </div>
                    </div>
            </div>


        </div>
    </div>


    <?php require('inc/footer.php'); ?>

</body>

</html>