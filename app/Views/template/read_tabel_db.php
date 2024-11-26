<?php
$db = db(menu()['tabel']);

$q = $db->orderBy($col, $sort)->get()->getResultArray();

if ($data !== null) {
    $q = $data;
}
?>


<div class="input_light mb-3">
    <input class="cari" placeholder="Cari: Ketik sesuatu..." style="width: 100%;" type="text">

</div>
<table class="table table-dark table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <?php foreach ($cols as $i): ?>
                <th scope="col"><?= upper_first(str_replace("_", " ", $i)); ?></th>
            <?php endforeach; ?>
            <th>Act</th>
        </tr>
    </thead>
    <tbody class="body_cari">
        <?php foreach ($q as $k => $i): ?>
            <tr>
                <td><?= ($k + 1); ?></td>
                <?php foreach ($cols as $c): ?>
                    <td><?= $i[$c]; ?></td>
                <?php endforeach; ?>
                <td><a class="link_3 rounded data_modal" data-id="<?= $i['id']; ?>" href=""><i class="fa-regular fa-pen-to-square"></i> Edit</a> <a href="" class="link_danger rounded btn_confirm" data-where="<?= $delete['where']; ?>" data-value="<?= $i[$delete['where']]; ?>" data-alert="<?= $delete['alert']; ?>" data-url="<?= $delete['url']; ?>" data-tabel="<?= $delete['tabel']; ?>"><i class="fa-solid fa-circle-xmark"></i> Delete</a> <?= (menu()['menu'] == 'User' ? '<a class="link_warning rounded reset_password" data-id="' . $i['id'] . '" href=""><i class="fa-solid fa-repeat"></i> Reset</a>' : ''); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- modal add-->
<div class="modal fade" id="modal_dark" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal_body">



        </div>
    </div>
</div>
<!-- modal search_db-->
<div class="modal fade" id="search_db" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal_body_search_db">

        </div>
    </div>
</div>
<script>
    let datas = <?= json_encode($q); ?>;
    let cols = <?= json_encode($cols); ?>;
    let not_input = <?= json_encode($not_input); ?>;
    const content_modal = (id = undefined) => {

        let html = '';
        html += '<div class="modal-body bg_3 rounded" style="padding: 1px;">';
        html += '<div class="bg_2 rounded p-0">';
        html += '<div class="d-flex justify-content-between p-3 border-bottom">';
        html += '<div>' + (id == undefined ? '<i class="fa-solid fa-circle-plus"></i> Add' : '<i class="fa-solid fa-pen-to-square"></i> Edit') + ' <?= menu()['menu']; ?></div>';
        html += '<div><a data-bs-dismiss="modal" href=""><i class="fa-solid fa-circle-xmark"></i></a></div>';
        html += '</div>';

        html += '<div class="p-3 bg_1 rounded">';
        html += '<form action="<?= base_url(menu()['url'] . '/'); ?>' + (id == undefined ? 'add' : 'update') + '" method="post">';
        if (id !== undefined) {
            html += '<input name="id" type="hidden" value="' + id + '">';
        }
        if (id == undefined) {
            cols.forEach(el => {
                // selain input
                let not_inp;
                not_input.forEach(n => {
                    if (n.col == el) {
                        not_inp = n;
                    }

                })

                if (not_inp == undefined) {
                    html += '<div class="input_light mb-3">';
                    html += '<div>' + upper_first(str_replace("_", " ", el)) + '</div>';
                    html += '<input name="' + el + '" placeholder="' + upper_first(str_replace("_", " ", el)) + '" value="" placeholder="' + upper_first(str_replace("_", " ", el)) + '" style="width: 100%;" type="text">';
                    html += '</div>';

                } else {
                    if (not_inp.form == 'select') {
                        html += '<div class="input_light mb-3">';
                        html += '<div>' + upper_first(str_replace("_", " ", el)) + '</div>';
                        html += '<input class="search_db search_db_' + not_inp.col + '" data-col="' + not_inp.col + '" data-db="' + not_inp.db + '" data-col_search="' + not_inp.col_search + '" data-col_show="' + not_inp.col_show + '" data-tabel="' + not_inp.tabel + '" data-where="' + not_inp.where + '" data-order_by="' + not_inp.order_by + '" data-limit="' + not_inp.limit + '" data-col_insert="' + not_inp.col_insert + '" name="' + el + '" placeholder="' + upper_first(str_replace("_", " ", el)) + '" value="" style="width: 100%;" type="text" readonly>';
                        html += '</div>';
                    }
                }


            })

        } else {
            datas.forEach(e => {
                if (e.id == id) {
                    cols.forEach(el => {
                        // selain input
                        let not_inp;
                        not_input.forEach(n => {
                            if (n.col == el) {
                                not_inp = n;
                            }

                        })
                        if (not_inp == undefined) {
                            html += '<div class="input_light mb-3">';
                            html += '<div>' + upper_first(str_replace("_", " ", el)) + '</div>';
                            html += '<input name="' + el + '" placeholder="' + upper_first(str_replace("_", " ", el)) + '" value="' + e[el] + '" style="width: 100%;" type="text">';
                            html += '</div>';

                        } else {
                            if (not_inp.form == 'select') {
                                html += '<div class="input_light mb-3">';
                                html += '<div>' + upper_first(str_replace("_", " ", el)) + '</div>';
                                html += '<input class="search_db search_db_' + not_inp.col + '_' + id + '" data-id="' + id + '" data-db="' + not_inp.db + '"  data-col="' + not_inp.col + '" data-col_search="' + not_inp.col_search + '" data-col_show="' + not_inp.col_show + '" data-tabel="' + not_inp.tabel + '" data-where="' + not_inp.where + '" data-order_by="' + not_inp.order_by + '" data-limit="' + not_inp.limit + '" data-col_insert="' + not_inp.col_insert + '" name="' + el + '" placeholder="' + upper_first(str_replace("_", " ", el)) + '" value="' + e[el] + '" style="width: 100%;" type="text" readonly>';
                                html += '</div>';
                            }
                        }
                    })
                }

            });
        }

        html += '<div class="d-grid mt-5">';
        html += '<button class="btn_5 rounded px-2 py-1"><i class="fa-solid fa-floppy-disk"></i> Save</button>';
        html += '</div>';
        html += '</form>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        return html;
    }
    $(document).on('click', '.data_modal', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let html = content_modal(id);

        $('.modal_body').html(html);

        let myModal = document.getElementById('modal_dark');
        let modal = bootstrap.Modal.getOrCreateInstance(myModal)
        modal.show();

    });


    $(document).on('click', '.search_db', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        let col = $(this).data('col');
        let col_insert = $(this).data('col_insert');
        let tabel = $(this).data('tabel');
        let limit = $(this).data('limit');
        let where = $(this).data('where');
        let order_by = $(this).data('order_by');
        let col_show = $(this).data('col_show');
        let col_search = $(this).data('col_search');
        let db = $(this).data('db');

        let html = '';
        html += '<div class="input_light">';
        html += '<input autofocus class="search_db_input" data-col="' + col + '" data-col_show="' + col_show + '" data-db="' + db + '" data-col_search="' + col_search + '" data-id="' + id + '" data-tabel="' + tabel + '" data-where="' + where + '" data-order_by="' + order_by + '" data-limit="' + limit + '" data-col_insert="' + col_insert + '" name="' + col + '" placeholder="Ketik sesuatu..." value="" style="width: 100%;" type="text">';
        html += '<section class="bg_3 sticky-top bg_3" style="z-index:10">';
        html += '<section style="position:absolute;width:100%" class="bg_3 px-2 body_list_search_db">';

        html += '</section>';
        html += '</section>';
        html += '</div>';
        $('.modal_body_search_db').html(html);
        let myModal = document.getElementById('search_db');
        let modal = bootstrap.Modal.getOrCreateInstance(myModal)
        modal.show();

        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });

    })
    $(document).on('keyup', '.search_db_input', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        let value = $(this).val();
        let col = $(this).data('col');
        let col_insert = $(this).data('col_insert');
        let tabel = $(this).data('tabel');
        let limit = $(this).data('limit');
        let where = $(this).data('where');
        let order_by = $(this).data('order_by');
        let col_show = $(this).data('col_show');
        let col_search = $(this).data('col_search');
        let db = $(this).data('db');

        post('search_db', {
            col,
            col_insert,
            value,
            tabel,
            limit,
            where,
            col_show,
            col_search,
            db,
            order_by
        }).then(res => {
            if (res.status == '200') {
                let html = '';
                res.data.forEach((e, i) => {
                    html += '<a data-col="' + col + '" data-id="' + id + '" data-value_insert="' + e[col_insert] + '" style="font-size:14px" href="" class="link_3 d-block rounded border-bottom insert_value">' + e[col_show] + '</a>';
                })

                $('.body_list_search_db').html(html);
            } else {
                gagal_with_button(res.message);
            }
        })

    })
    $(document).on('click', '.insert_value', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let col = $(this).data('col');
        let value = $(this).data('value_insert');

        if (id == 'undefined') {
            console.log('Ok');
            $('.search_db_' + col).val(value);
        } else {
            $('.search_db_' + col + '_' + id).val(value);
        }

        let myModal = document.getElementById('search_db');
        let modal = bootstrap.Modal.getOrCreateInstance(myModal)
        modal.hide();


    })
    <?php if (menu()['menu'] == 'User'): ?>

        $(document).on('click', '.reset_password', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            post('user/reset_password', {
                id
            }).then(res => {
                if (res.status == '200') {
                    sukses(res.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    gagal_with_button(res.message);
                }
            })


        })
    <?php endif; ?>

    $(document).on('keyup', '.cari', function(e) {
        e.preventDefault();
        let value = $(this).val().toLowerCase();
        $('.body_cari tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });

    });
</script>