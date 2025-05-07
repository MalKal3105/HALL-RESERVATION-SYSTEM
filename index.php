<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <link rel="stylesheet" href="css/common.css">

    <style>
        .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }

        @media screen and(max-width: 575px) {
            .availability-form {
                margin-top: 25px;
                padding: 0 35px;
            }
        }

        .sw-testimonials {
            width: 500px;
            height: 400px;
        }

        .swiper-slide {
            border-radius: 18px;
            font-size: 22px;
            font-weight: bold;
            color: #000000;
        }
    </style>
</head>

<body style="background-color: rgb(72, 106, 106);">

    <?php require('inc/header.php'); ?>

    <!-- Carousel -->

    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container sw">
            <div class="swiper-wrapper">
            <?php
            $res = selectAll('carousel');
            while($row = mysqli_fetch_assoc($res))
            {
                $path = CAROUSEL_IMG_PATH;
                echo <<<data
                <div class="swiper-slide">
                    <img src="$path$row[image]" class="w-100 d-block" />
                </div>
                data;
            }
            ?>
            </div>
        </div>
    </div>

    <!-- Check availability form -->

    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12 shadow p-4 rounded" style="background-color: rgb(184, 187, 195);">
                <h3 class="mb-4"><span class="text-white">Venue</span> Search</h3>
                <form action="halls.php">
                    <div class="row align-items-end">
                        <div class="col-9 mb-3"> 
                            <label class="form-label" style="font-weight: 500;">Smart Search</label>
                            <input class="form-control me-2" type="search" placeholder="Hall Name" aria-label="search" name="search" required>
                        </div>
                        <input type="hidden" name="check_availability">
                        <div class="col-3 mb-3">
                            <button type="submit" class="btn text-white shadow-none custom-bg">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Our Hall -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">OUR HALLS</h2>

    <div class="container">
        <div class="row">

        <?php
                $hall_res = select("SELECT * FROM `halls` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3",[1,0],'ii');

                while($hall_data = mysqli_fetch_assoc($hall_res))
                {
                    // get features of hall

                    $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f
                    INNER JOIN `hall_features` rfea ON f.id = rfea.features_id
                    WHERE rfea.hall_id = '$hall_data[id]'");

                    $features_data = "";
                    while($fea_row = mysqli_fetch_assoc($fea_q)){
                        $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                        $fea_row[name]
                        </span>";
                    }
                        // get facilities of hall

                        $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f
                        INNER JOIN `hall_facilities` rfac ON f.id = rfac.facilities_id
                        WHERE rfac.hall_id = '$hall_data[id]'");

                        $facilities_data = "";
                        while($fac_row = mysqli_fetch_assoc($fac_q)){
                            $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                            $fac_row[name]
                            </span>";
                        }
                    
                        // get thumbnail of image

                        $hall_thumb = HALL_IMG_PATH."thumbnail.jpg";
                        $thumb_q = mysqli_query($con, "SELECT * FROM `hall_images` 
                            WHERE `hall_id`='$hall_data[id]' 
                            AND `thumb`='1'");

                        if(mysqli_num_rows($thumb_q)>0){
                            $thumb_res = mysqli_fetch_assoc($thumb_q);
                            $hall_thumb = HALL_IMG_PATH.$thumb_res['image'];
                        }

                        $book_btn = "";

                        if(!$settings_r['shutdown']){
                            $login=0;
                            if(isset($_SESSION['login']) && $_SESSION['login']==true){
                                $login=1;
                            }
                            

                            $book_btn = "<button onclick='checkLoginToBook($login,$hall_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>Book Now</button>";
                        }

                        /// print hall card 

                        echo <<< data

                        <div class="col-lg-4 col-md-6 my-3">
                            <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                                <img src="$hall_thumb" class="card-img-top">
                                <div class="card-body" style="background-color: rgb(184, 187, 195);">
                                    <h5>$hall_data[name]</h5>
                                    <h6 class="mb-4">$hall_data[quantity] person</h6>
                                    <h6 class="mb-4">Starts from RM$hall_data[price]</h6>
                                    <div class="features mb-4">
                                        <h6 class="mb-1">Features</h6>
                                        $features_data
                                    </div>
                                    <div class="facilities mb-4">
                                        <h6 class="mb-1">Facilities</h6>
                                        $facilities_data
                                    </div>
                                    <div class="rating mb-4">
                                        <h6 class="mb-1">Rating</h6>
                                        <span class="badge rounded-pill bg-light">
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                            <i class="bi bi-star-fill text-warning"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-evenly mb-2">
                                    $book_btn
                                        <a href="halls_details.php?id=$hall_data[id]" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        data;
                    

                }
            ?>

            <div class="col-lg-12 text-center mt-5">
                <a href="halls.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Hall > > ></a>
            </div>
        </div>
    </div>

    <!-- Testimonials -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">TESTIMONIALS</h2>

    <div class="container mt-5">
        <div class="swiper sw-testimonials">
            <div class="swiper-wrapper mb-5 text-dark">
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
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
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
                        <i class="bi bi-person-fill"></i>
                        <h6 class="m-2 ms-2 align-items-center">Random User 2</h6>
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

                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
                        <i class="bi bi-person-fill"></i>
                        <h6 class="m-2 ms-2 align-items-center">Random User 3</h6>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto possimus aperiam accusantium laboriosam.
                        Excepturi, eius. Veniam laborum enim temporibus ipsa officiis quas et
                        culpa labore quasi, laudantium expedita adipisci exercitationem.
                    </p>
                    <div class="rating mt-5">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
                        <i class="bi bi-person-fill"></i>
                        <h6 class="m-2 ms-2 align-items-center">Random User 4</h6>
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
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
                        <i class="bi bi-person-fill"></i>
                        <h6 class="m-2 ms-2 align-items-center">Random User 5</h6>
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
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
                        <i class="bi bi-person-fill"></i>
                        <h6 class="m-2 ms-2 align-items-center">Random User 6</h6>
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
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
                        <i class="bi bi-person-fill"></i>
                        <h6 class="m-2 ms-2 align-items-center">Random User 7</h6>
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
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
                        <i class="bi bi-person-fill"></i>
                        <h6 class="m-2 ms-2 align-items-center">Random User 8</h6>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto possimus aperiam accusantium laboriosam.
                        Excepturi, eius. Veniam laborum enim temporibus ipsa officiis quas et
                        culpa labore quasi, laudantium expedita adipisci exercitationem.
                    </p>
                    <div class="rating mt-5">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex mb-3">
                        <i class="bi bi-person-fill"></i>
                        <h6 class="m-2 ms-2 align-items-center">Random User 9</h6>
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
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Perfect Event Space -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold"><i class="bi bi-buildings-fill"></i> Find the perfect event space rental in Malaysia </h2>


    <!-- Reach Us -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">REACH US</h2>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
            <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy"></iframe>
    
            </div>
            <div class="col-lg-4 col-md-4">
                <div style="background-color: rgb(92, 130, 130);" class="p-4 rounded mb-4">
                    <h5>Call us</h5>
                    <a href="tel: +0<?php echo $contact_r['pn1'] ?> " class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +0<?php echo $contact_r['pn1'] ?> 
                    </a>
                    <br>
                    <?php
                        if($contact_r['pn2']!=''){
                            echo<<<data
                                <a href="tel: +0$contact_r[pn2]" class="d-inline-block text-decoration-none text-dark">
                                    <i class="bi bi-telephone-fill"></i> +0$contact_r[pn2] 
                                </a>
                            data;
                        }
                    ?>
                </div>
                <div style="background-color: rgb(92, 130, 130);" class="p-4 rounded mb-4">
                    <h5>Follow us</h5>
                    <?php 
                        if($contact_r['tw']!=''){
                            echo <<<data
                            <a href="$contact_r[tw]" class="d-inline-block mb-3">
                            <span class="badge text-dark fs-6 p-2" style="background-color: rgb(72, 106, 106);">
                                <i class="bi bi-twitter me-1"></i> Twitter
                            </span>
                            </a>
                            <br>
                            data;
                        }
                    ?>
                
                    <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block">
                        <span class="badge text-dark fs-6 p-2" style="background-color: rgb(72, 106, 106);">
                            <i class="bi bi-instagram"></i> Instagram
                        </span>
                    </a>
                    <br><br>
                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block">
                        <span class="badge text-dark fs-6 p-2" style="background-color: rgb(72, 106, 106);">
                            <i class="bi bi-facebook"></i> Facebook
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Password reset modal and code -->

    <div class="modal fade" id="recoveryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="recovery-form">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-shield-lock fs-3 me-2"></i> Set up New Password
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">New Password</label>
                        <input type="password" name="pass" required class="form-control shadow-none">
                        <input type="hidden" name="email">
                        <input type="hidden" name="token">

                    </div>
                    <div class="mb-2 text-end">
                        <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    <?php require('inc/footer.php'); ?>

    <?php 
    
        if(isset($_GET['account_recovery']))
        {
            $data = filteration($_GET);

            $t_date = date("Y-m-d");

            $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
             [$data['email'],$data['token'],$t_date],'sss');

            if(mysqli_num_rows($query)==1)
            {
                echo<<<showModal
                <script>
                    var myModal = document.getElementById('recoveryModal');

                    myModal.querySelector("input[name='email']").value = '$data[email]';
                    myModal.querySelector("input[name='token']").value = '$data[token]';

                    var modal = bootstrap.Modal.getOrCreateInstance(myModal);
                    modal.show();
                </script>
                showModal;
            }
            else{
                alert("error","Invalid or Expired Link !");
            }
        }
    
    ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>


    <script>
        var swiper = new Swiper(".swiper-container", {
            spaceBetween: 30,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            }
        });

        var swiper = new Swiper(".sw-testimonials", {
            effect: "cards",
            grabCursor: true,
        });

        // recover account

        let recovery_form = document.getElementById('recovery-form');

        recovery_form.addEventListener('submit', (e)=>{
         e.preventDefault();

        let data = new FormData();

        data.append('email',recovery_form.elements['email'].value);
        data.append('token',recovery_form.elements['token'].value);
        data.append('pass',recovery_form.elements['pass'].value);
        data.append('recover_user','');

        var myModal = document.getElementById('recoveryModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function() {
            if(this.responseText.trim() == 'failed'){
                alert('error',"Account reset failed!");
            }
            else{
                alert('success',"Account Reset Successful!");
                recovery_form.reset();
            }

        }
        xhr.send(data);


        function searchHalls() {
            // Get the search query from the input field
            let searchTerm = search.value.trim().toLowerCase();
            
            // Get all the hall cards
            let hallCards = document.querySelectorAll('.card');
            
            // Loop through each hall card and check if the name contains the search term
            hallCards.forEach(card => {
                let hallName = card.querySelector('h5').textContent.toLowerCase();
                if (hallName.includes(searchTerm)) {
                    // If the hall name matches the search term, display the card
                    card.style.display = 'block';
                } else {
                    // If the hall name does not match the search term, hide the card
                    card.style.display = 'none';
                }
            });
        }


        // Add an event listener to the search input field
        search.addEventListener('input', searchHalls);


    });

    </script>
</body>

</html>