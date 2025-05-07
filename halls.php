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

    <?php 
    require('inc/header.php'); 
    
    $search_default="";

    if(isset($_GET['check_availability']))
    {
        $frm_data = filteration($_GET);

        if (isset($frm_data['search'])) {
        $search_default= $frm_data['search'];
        }
    }
    
    ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR HALLS</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light rounded-shadow" style="background-color: rgb(184, 187, 195);">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">FILTERS</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                            <!-- Check availability -->
                            <div class="border p-3 rounded mb-3 bg-light">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>CHECK AVAILABILITY</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <label class="form-label" style="font-weight: 500;">Smart Search</label>
                                <input class="form-control mb-3 shadow-none" type="text" value="<?php echo $search_default ?>" id="search" oninput="toggleResetButton()" placeholder="Hall Name" aria-label="Search">

                                <label class="form-label" style="font-weight: 500;">Person</label>
                                <input class="form-control mb-3 shadow-none" type="number" id="person" oninput="search_filter()" placeholder="No. of Person" aria-label="Search">
                            </div>
                            
                            <!-- Facilities -->
                            <div class="border p-3 rounded mb-3 bg-light">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>FACILITIES</span>
                                    <button id="facilities_btn" onclick="facilities_clear()" class="btn btn-sm text-secondary d-none">Reset</button>
                                </h5>

                                <?php

                                    $facilities_q = selectAll('facilities');
                                    while($row = mysqli_fetch_assoc($facilities_q))
                                    {
                                        echo<<<facilities

                                        <div class="mb-2">
                                            <input type="checkbox" onclick="fetch_halls()" name="facilities" value="$row[id]" class"form-check-input shadow-none me-1" "$row[id]">
                                            <label class="form-check-label" for="$row[id]">$row[name]</label>
                                        </div>  

                                        facilities;
                                    }

                                ?>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4" id="halls-data">
            </div>


        </div>
    </div>

    <script>

        let chk_avail_btn = document.getElementById('chk_avail_btn');
        let person = document.getElementById('person');
        let search = document.getElementById('search');


        let halls_data = document.getElementById('halls-data');

        let facilities_btn = document.getElementById('facilities_btn');

        function fetch_halls()
        {
            let chk_avail = JSON.stringify({
            search: search.value,
            person: person.value,
        });

        let facility_list = {"facilities":[]};

        let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
        if(get_facilities.length>0)
        {
            get_facilities.forEach((facility)=>{
                facility_list.facilities.push(facility.value);
            });
            facilities_btn.classList.remove('d-none');
        }
        else{
            facilities_btn.classList.add('d-none');
        }

        facility_list = JSON.stringify(facility_list);

            let xhr = new XMLHttpRequest();
            xhr.open("GET","ajax/halls.php?fetch_halls&chk_avail="+chk_avail+"&facility_list="+facility_list,true);


            xhr.onprogress = function(){
                halls_data.innerHTML = `<div class="spinner-border text-info mb-3 d-block mx-auto" id="loader" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>`;
            }

            xhr.onload = function(){
                halls_data.innerHTML = this.responseText;
            }

            xhr.send();
        }
        

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


        function toggleResetButton() {
        let searchInput = document.getElementById('search');
        let resetButton = document.getElementById('chk_avail_btn');

        if (searchInput.value.trim() !== '') {
            resetButton.classList.remove('d-none');
        } else {
            resetButton.classList.add('d-none');
        }
    }
    
        
        function search_filter() {
            if (person.value > 0  && search.value.trim() !== '') {
                fetch_halls();
                chk_avail_btn.classList.remove('d-none');
            }
        }


        function chk_avail_clear(){
            search.value='';
            person.value='';
            chk_avail_btn.classList.add('d-none');
            fetch_halls();

        }

        function facilities_clear(){
            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
            get_facilities.forEach((facility)=>{
                facility.checked=false;
            });
            facilities_btn.classList.add('d-none');
            fetch_halls();
        }

        fetch_halls();

    </script>

    <?php require('inc/footer.php'); ?>

</body>

</html>