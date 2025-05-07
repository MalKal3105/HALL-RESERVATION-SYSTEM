
        let add_hall_form = document.getElementById('add_hall_form');

        add_hall_form.addEventListener('submit', function(e) {
            e.preventDefault();
            add_hall();
        });

        function add_hall() {
            let data = new FormData();
            data.append('add_hall', '');
            data.append('name', add_hall_form.elements['name'].value);
            data.append('area', add_hall_form.elements['area'].value);
            data.append('price', add_hall_form.elements['price'].value);
            data.append('quantity', add_hall_form.elements['quantity'].value);
            data.append('person', add_hall_form.elements['person'].value);
            data.append('desc', add_hall_form.elements['desc'].value);

            let features = [];

            add_hall_form.elements['features'].forEach(el => {
                if (el.checked) {
                    features.push(el.value);
                }
            });

            let facilities = [];

            add_hall_form.elements['facilities'].forEach(el => {
                if (el.checked) {
                    facilities.push(el.value);
                }
            });

            data.append('features', JSON.stringify(features));
            data.append('facilities', JSON.stringify(facilities));

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);

            xhr.onload = function() {
                var myModal = document.getElementById('add-hall');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                if (this.responseText == 1) {
                    alert('success', 'New hall added!');
                    add_hall_form.reset();
                    get_all_hall();
                } else {
                    alert('error', 'Server Down!');
                }
            }

            xhr.send(data);
        }

        function get_all_hall() {

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                document.getElementById('hall-data').innerHTML = this.responseText;
            }

            xhr.send('get_all_hall');
        }

        let edit_hall_form = document.getElementById('edit_hall_form');

        function edit_details(id) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                let data = JSON.parse(this.responseText);

                edit_hall_form.elements['name'].value = data.halldata.name;
                edit_hall_form.elements['area'].value = data.halldata.area;
                edit_hall_form.elements['price'].value = data.halldata.price;
                edit_hall_form.elements['quantity'].value = data.halldata.quantity;
                edit_hall_form.elements['person'].value = data.halldata.person;
                edit_hall_form.elements['desc'].value = data.halldata.description;
                edit_hall_form.elements['hall_id'].value = data.halldata.id;

                edit_hall_form.elements['features'].forEach(el => {
                    if (data.features.includes(Number(el.value))) {
                        el.checked = true;
                    }
                });

                edit_hall_form.elements['facilities'].forEach(el => {
                    if (data.facilities.includes(Number(el.value))) {
                        el.checked = true;
                    }
                });

            }

            xhr.send('get_hall=' + id);
        }


        edit_hall_form.addEventListener('submit', function(e) {
            e.preventDefault();
            submit_edit_hall();
        });

        function submit_edit_hall() {
            let data = new FormData();
            data.append('edit_hall', '');
            data.append('hall_id', edit_hall_form.elements['hall_id'].value);
            data.append('name', edit_hall_form.elements['name'].value);
            data.append('area', edit_hall_form.elements['area'].value);
            data.append('price', edit_hall_form.elements['price'].value);
            data.append('quantity', edit_hall_form.elements['quantity'].value);
            data.append('person', edit_hall_form.elements['person'].value);
            data.append('desc', edit_hall_form.elements['desc'].value);

            let features = [];

            edit_hall_form.elements['features'].forEach(el => {
                if (el.checked) {
                    features.push(el.value);
                }
            });

            let facilities = [];

            edit_hall_form.elements['facilities'].forEach(el => {
                if (el.checked) {
                    facilities.push(el.value);
                }
            });

            data.append('features', JSON.stringify(features));
            data.append('facilities', JSON.stringify(facilities));

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);

            xhr.onload = function() {
                var myModal = document.getElementById('edit-hall');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                if (this.responseText == 1) {
                    alert('success', 'Hall data edited!');
                    edit_hall_form.reset();
                    get_all_hall();
                } else {
                    alert('error', 'Server Down!');
                }
            }

            xhr.send(data);
        }


        function toggle_status(id, val) {

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (this.responseText == 1) {
                    alert('success', 'Status toggled!');
                    get_all_hall();
                } else {
                    alert('success', 'Server Down!');
                }
            }

            xhr.send('toggle_status=' + id + '&value=' + val);
        }

        let add_image_form = document.getElementById('add_image_form');

        add_image_form.addEventListener('submit', function(e) {
            e.preventDefault();
            add_image();
        });

        function add_image() {

            let data = new FormData();
            data.append('image', add_image_form.elements['image'].files[0]);
            data.append('hall_id', add_image_form.elements['hall_id'].value);
            data.append('add_image', '');

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);

            xhr.onload = function() {
                if (this.responseText === 'inv_img') {
                    alert('error', 'Only JPG, WEBP or PNG images are allowed!', 'image-alert');
                } else if (this.responseText === 'inv_size') {
                    alert('error', 'Image should be less than 2MB!', 'image-alert');
                } else if (this.responseText === 'upd_failed') {
                    alert('error', 'Image upload failed. Server Down!', 'image-alert');
                } else {
                    alert('success', 'New image added!', 'image-alert');
                    hall_images(add_image_form.elements['hall_id'].value, document.querySelector("#hall-images .modal-title").innerText);
                    add_image_form.reset();
                }
            }
            xhr.send(data);
        }

        function hall_images(id, rname) {
            document.querySelector("#hall-images .modal-title").innerText = rname;
            add_image_form.elements['hall_id'].value = id;
            add_image_form.elements['image'].value = '';

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                document.getElementById('hall-image-data').innerHTML = this.responseText;
            }

            xhr.send('get_hall_images=' + id);
        }

        function rem_image(img_id, hall_id) {

            let data = new FormData();
            data.append('image_id', img_id);
            data.append('hall_id', hall_id);
            data.append('rem_image', '');

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);

            xhr.onload = function() {
                if (this.responseText == 1) {
                    alert('success', 'Image Removed!', 'image-alert');
                    hall_images(hall_id, document.querySelector("#hall-images .modal-title").innerText);
                } else {
                    alert('error', 'Image removal failed!', 'image-alert');
                }
            }
            xhr.send(data);
        }

        function thumb_image(img_id, hall_id) {

            let data = new FormData();
            data.append('image_id', img_id);
            data.append('hall_id', hall_id);
            data.append('thumb_image', '');

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/hall.php", true);

            xhr.onload = function() {
                if (this.responseText == 1) {
                    alert('success', 'Image Thumbnail Changed!', 'image-alert');
                    hall_images(hall_id, document.querySelector("#hall-images .modal-title").innerText);
                } else {
                    alert('error', 'Thumbnail updated failed!', 'image-alert');
                }
            }
            xhr.send(data);
        }

        function remove_hall(hall_id) {

            if (confirm("Are you sure, you want to delete this hall?")) {
                let data = new FormData();
                data.append('hall_id', hall_id);
                data.append('remove_hall', '');

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/hall.php", true);

                xhr.onload = function() {
                    if (this.responseText == 1) {
                        alert('success', 'Hall Removed');
                        get_all_hall();
                    } else {
                        alert('error', 'Hall removal failed!');
                    }
                }
                xhr.send(data);
            }
        }


        window.onload = function() {
            get_all_hall();
        }
    