<script>
    const baseUrl = "<?= base_url(); ?>";
    let url = '';

    const loading = (req = true) => {
        if (req === true) {
            $('.waiting').show()
        } else {
            $('.waiting').fadeOut()
        }
    }

    const str_replace = (search, replace, subject) => {
        return subject.split(search).join(replace);
    }

    function angka(a, prefix) {
        let angka = a.toString();
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }


    const gagal = (alert) => {
        let html = '';
        html += '<div class="d-flex flex-column min-vh-100 min-vw-100">';
        html += '<div class="d-flex flex-grow-1 justify-content-center align-items-center">';
        html += '<div class="d-flex gap-3" style="border:2px solid #FF9FA1;border-radius:8px;padding:5px;background-color:#FFC9C9;color:#A90020">';
        html += '<div class="px-2"><i class="fa-solid fa-circle-xmark"></i> ' + alert + '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $('.box_warning').html(html)
        $('.box_warning').fadeIn();
        $('.box_confirm').fadeOut();

        setTimeout(() => {
            $('.box_warning').fadeOut();
        }, 1000);

    }

    const gagal_with_button = (alert) => {
        let html = '';
        html += '<div class="d-flex flex-column min-vh-100 min-vw-100">';
        html += '<div class="d-flex flex-grow-1 justify-content-center align-items-center">';
        html += '<div class="d-flex gap-3" style="border:2px solid #FF9FA1;border-radius:8px;padding:5px;background-color:#FFC9C9;color:#A90020">';
        html += '<div class="d-flex">';
        html += '<div class="px-2"><i class="fa-solid fa-triangle-exclamation" style="color: #cc0000;"></i> ' + alert + '</div>';
        html += '<a class="btn_close_warning" style="text-decoration: none;color:#A90020" href=""><i class="fa-solid fa-circle-xmark"></i></a>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $('.box_warning_with_button').html(html);

        $('.box_warning_with_button').show();

        $(document).on('click', '.btn_close_warning', function(e) {
            e.preventDefault();
            $('.box_warning_with_button').fadeOut();
        });

    }

    $(document).on('click', '.btn_close_warning', function(e) {
        e.preventDefault();
        $('.box_warning_with_button').fadeOut();
    });

    const object_to_array = (obj) => {

        let data = [];
        for (const [key, value] of Object.entries(obj)) {
            data.push({
                key,
                value
            });
        }

        return data;
    }

    const confirm = (obj) => {
        let args = object_to_array(obj);
        let args_values = '';
        let alert = '';
        args.forEach(elm => {
            args_values += 'data-' + elm.key + '="' + elm.value + '" ';
            if (elm.key == 'alert') {
                alert = elm.value;
            }
        });

        let html = '';
        html += '<div class="d-flex flex-column min-vh-100 min-vw-100">';
        html += '<div class="d-flex flex-grow-1 justify-content-center align-items-center">';
        html += '<div class="d-flex gap-3" style="border:2px solid #ff9933;border-radius:8px;padding:5px;background-color:#ffe6cc;color:#cc6600">';
        html += '<div class="d-flex gap-2">';
        html += '<div class="px-2" style="font-weight: 700;"><i class="fa-solid fa-triangle-exclamation" style="color: #ff9933;"></i> ' + alert + '</div>';
        html += '<a class="btn_close_confirm" style="text-decoration: none;color:#ff8000" href=""><i class="fa-solid fa-circle-xmark"></i></a>';
        html += '<a class="btn_execute_confirm" ' + args_values + ' style="text-decoration: none;color:green" href=""><i class="fa-solid fa-circle-check"></i></i></a>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $('.box_confirm').html(html);

        $('.box_confirm').show();

        $(document).on('click', '.btn_close_confirm', function(e) {
            e.preventDefault();
            $('.box_confirm').fadeOut();
        });


    }

    $(document).on('click', '.btn_execute_confirm', function(e) {
        e.preventDefault();
        let data = $(this).data();
        post(data.url, {
                data
            })
            .then(res => {
                if (res.status == '200') {
                    sukses(res.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    $('.box_confirm').fadeOut();
                    gagal_with_button(res.message);
                }

            })

    });


    $(document).on('click', '.btn_confirm', function(e) {
        e.preventDefault();
        confirm($(this).data());
    });




    const sukses = (alert) => {
        let html = '';
        html += '<div class="d-flex flex-column min-vh-100 min-vw-100">';
        html += '<div class="d-flex flex-grow-1 justify-content-center align-items-center">';
        html += '<div class="d-flex gap-3" style="border:2px solid #9fffc4;border-radius:8px;padding:5px;background-color:#c9ffde;color:#00a939">';
        html += '<div class="px-2"><i class="fa-solid fa-circle-check"></i> ' + alert + '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $('.box_warning').html(html)
        $('.box_warning').fadeIn();
        $('.box_confirm').fadeOut();

        setTimeout(() => {
            $('.box_warning').fadeOut();
        }, 1000);

    }

    async function post(url = '', data = {}) {
        loading(true);
        const response = await fetch("<?= base_url(); ?>" + url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        loading(false);
        return response.json(); // parses JSON response into native JavaScript objects
    }

    const body_warning = (warning_text, url, id, order, table) => {
        let html = '';

        html += '<div class="d-flex flex-column min-vh-100 min-vw-100">';
        html += '<div class="d-flex flex-grow-1 justify-content-center align-items-center">';
        html += '<div class="d-flex gap-3" style="border:1px solid red;border-radius:8px;padding:10px 20px;background-color:#eee">';
        html += '<div>' + warning_text + '</div>';
        html += '<div><a class="del_execute text_success" data-id="' + id + '" data-url="' + url + '" data-order="' + order + '" data-table="' + table + '" href=""><i class="fa-regular fa-circle-check" style="font-size:large"></i></a></div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    }

    $(document).on('click', '.warning', function(e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        let table = $(this).attr('data-table');
        let order = $(this).attr('data-order');
        $('.box_warning').html(body_warning('Are you sure to <b class="text_danger">' + order + '</b> this data!.', url, id, order, table));


    });
    $(document).on('click', '.del_execute', function(e) {
        e.preventDefault();
        let id = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        let table = $(this).attr('data-table');
        let order = $(this).attr('data-order');

        $('.box_warning').html('');
        post(url, {
                id,
                table
            })
            .then(res => {
                if (res.status == '200') {
                    location.reload();
                } else {
                    gagal(res.message);
                }

            })

    });

    $(document).on('click', '.zoom', function(e) {
        e.preventDefault();
        let url = $(this).data('url');

        let html = '';
        html += '<button type="button" class="btn_purple" data-bs-dismiss="modal">Close</button>';
        html += '<a href="' + url + '" type="button" class="btn_primary" download>Download</a>';
        $('.download_footer').html(html);

        let img = '';
        img += '<img src="' + url + '" class="img-fluid" alt="File">';
        $('.body_download').html(img);

        let myModal = document.getElementById('modal_zoom');
        let modal = bootstrap.Modal.getOrCreateInstance(myModal)
        modal.show();

    });



    const upper_first = (str, order) => {
        let splitStr = str.toLowerCase().split(' ');
        for (let i = 0; i < splitStr.length; i++) {
            // You do not need to check if i is larger than splitStr length, as your for does that for you
            // Assign it back to the array
            if (order == undefined) {
                if (i == 0) {
                    splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
                } else {
                    splitStr[i] = splitStr[i];
                }
            } else {
                splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
            }
        }
        // Directly return the joined string
        return splitStr.join(' ');
    }

    const get_key_value_obj = (obj, text = undefined) => {
        let args = [];
        Object.keys(obj).forEach(key => {
            if (text == undefined) {
                args.push('data-' + key + '="' + obj[key] + '"');
            } else {
                args.push('data-' + text + '_' + key + '="' + obj[key] + '"');
            }
        })

        return args;
    }

    $(document).on('click', '.btn_select', function(e) {
        e.preventDefault();
        let where = $(this).data('where');
        let target = $(this).data('target');
        let col = $(this).data('col');
        let tabel = $(this).data('tabel');
        let orderby = $(this).data('orderby');

        body_select(tabel, col, orderby, where, target);

    });


    $(document).on('keyup', '.uang', function(e) {
        e.preventDefault();
        let val = $(this).val();
        $(this).val(angka(val));
    });

    const time_php_to_js = (date) => {
        let d = new Date(date * 1000);
        let res = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();

        return res;
    }
</script>