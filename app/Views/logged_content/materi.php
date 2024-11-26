<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>
<?php

$db = db('kelas');
$value = $db->where('sub', url(4))->groupBy('angkatan')->orderBy('urutan', 'ASC')->get()->getResultArray();
$angkatan = [];
foreach ($value as $i) {
    $angkatan[] = $i['angkatan'];
}

$data = [];

if (count($angkatan) > 0) {
    $db = db('materi');
    $data = $db->whereIn('angkatan', $angkatan)->get()->getResultArray();
}

?>
<h1>Materi <?= menu()['menu']; ?></h1>
<button class="btn_3 mb-2 rounded" data-bs-toggle="modal" data-bs-target="#modal_add"><i class="fa-solid fa-circle-plus"></i> Add Materi Sub <?= url(4); ?></button>
<div class="input_light mb-3">
    <input class="cari" placeholder="Cari: Ketik sesuatu..." style="width: 100%;" type="text">
</div>

<table class="table table-dark table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Angkatan</th>
            <th>Mapel</th>
            <th>Jadwal</th>
            <th>Materi</th>
            <th>Act</th>
        </tr>
    </thead>
    <tbody class="body_cari">
        <?php foreach ($data as $k => $i): ?>
            <tr>
                <td><?= ($k + 1); ?></td>
                <td><?= $i['angkatan']; ?></td>
                <td><?= $i['mapel']; ?></td>
                <td><?= hari(date('l', $i['jadwal']))['indo'] . ', ' . date('d/m/Y', $i['jadwal']); ?></td>
                <td><a class="link_3 rounded show_materi" data-url="<?= base_url('materi/') . $i['materi']; ?>" href=""><?= $i['materi']; ?></a></td>
                <td><a class="link_3 rounded detail" data-id="<?= $i['id']; ?>" href=""><i class="fa-regular fa-pen-to-square"></i> Edit</a> <a href="" class="link_danger rounded btn_confirm" data-where="" data-value="" data-alert="" data-url="" data-tabel=""><i class="fa-solid fa-circle-xmark"></i> Delete</a> <a class="link_warning rounded reset_password" data-id="' . $i['id'] . '" href=""><i class="fa-solid fa-repeat"></i> Reset</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- modal add-->
<div class="modal fade" id="modal_add" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal_body">
            <div class="modal-body bg_3 rounded" style="padding: 1px;">
                <div class="bg_2 rounded p-0">
                    <div class="d-flex justify-content-between p-3 border-bottom">
                        <div>Edit Materi</div>
                        <div><a data-bs-dismiss="modal" href=""><i class="fa-solid fa-circle-xmark"></i></a></div>
                    </div>

                    <div class="p-3 bg_1 rounded">
                        <form action="<?= base_url('materi/add'); ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="sub" value="<?= url(4); ?>">
                            <div class="input_light mb-3">
                                <div>Angkatan</div>
                                <input class="search_db search_db_angkatan" data-col="angkatan" data-db="pembelajaran" data-col_search="value" data-col_show="value" data-tabel="options" data-where="kategori=Angkatan" data-order_by="id=ASC" data-limit="" data-col_insert="value" name="angkatan" placeholder="Angkatan" value="" style="width: 100%;" type="text" readonly>
                            </div>
                            <div class="input_light mb-3">
                                <div>Mapel</div>
                                <input class="search_db search_db_mapel" name="mapel" data-col="mapel" data-db="pembelajaran" data-col_search="value" data-col_show="value" data-tabel="options" data-where="kategori=Mapel" data-order_by="value=ASC" data-limit="" data-col_insert="value" placeholder="Mapel" value="" style="width: 100%;" type="text" readonly>
                            </div>
                            <div class="input_light mb-3">
                                <div>Jadwal</div>
                                <input placeholder="Tanggal pelaksanaan" name="jadwal" value="<?= date('Y-m-d'); ?>" style="width: 100%;" type="date">
                            </div>
                            <div class="input_light mb-3">
                                <div>Materi (*Harus PDF dan max 5MB)</div>
                                <input name="file" value="" style="width: 100%;" type="file">
                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" class="btn_5 rounded px-2 py-1"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal detail-->
<div class="modal fade" id="modal_detail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal_body_detail">

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
<!-- modal show_materi-->
<div class="modal fade" id="show_materi" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal_body_show_materi">

        </div>
    </div>
</div>

<script>
    $(document).on('click', '.show_materi', function(e) {
        e.preventDefault();

        let url = $(this).data('url');
        let html = '';
        html += '<div style="text-align:center">';
        html += '<iframe src="https://docs.google.com/viewer?url=' + url + '&embedded=true" frameborder="0" height="700px" width="100%"></iframe>';
        html += '</div>';
        $('.modal_body_show_materi').html(html);

        let myModal = document.getElementById('show_materi');
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
    $(document).on('keyup', '.cari', function(e) {
        e.preventDefault();
        let value = $(this).val().toLowerCase();
        $('.body_cari tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });

    });
    $(document).on('click', '.detail', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let datas = <?= json_encode($data); ?>;

        let data;
        datas.forEach(e => {
            if (e.id == id) {
                data = e;
            }
        });

        let html = '';
        html += '<div class="modal-body bg_3 rounded" style="padding: 1px;">';
        html += '<div class="bg_2 rounded p-0">';
        html += '<div class="d-flex justify-content-between p-3 border-bottom">';
        html += '<div>Edit Materi</div>';
        html += '<div><a data-bs-dismiss="modal" href=""><i class="fa-solid fa-circle-xmark"></i></a></div>';
        html += '</div>';

        html += '<div class="p-3 bg_1 rounded">';
        html += '<form action="<?= base_url('materi/update'); ?>" method="post" enctype="multipart/form-data">';
        html += '<input type="hidden" name="id" value="' + data.id + '">';
        html += '<input type="hidden" name="sub" value="' + data.sub + '">';
        html += '<div class="input_light mb-3">';
        html += '<div>Angkatan</div>';
        html += '<input class="search_db search_db_angkatan" data-col="angkatan" data-db="pembelajaran" data-col_search="value" data-col_show="value" data-tabel="options" data-where="kategori=Angkatan" data-order_by="id=ASC" data-limit="" data-col_insert="value" name="angkatan" placeholder="Angkatan" value="' + data.angkatan + '" style="width: 100%;" type="text" readonly>';
        html += '</div>';
        html += '<div class="input_light mb-3">';
        html += '<div>Mapel</div>';
        html += '<input class="search_db search_db_mapel" name="mapel" data-col="mapel" data-db="pembelajaran" data-col_search="value" data-col_show="value" data-tabel="options" data-where="kategori=Mapel" data-order_by="value=ASC" data-limit="" data-col_insert="value" placeholder="Mapel" value="' + data.mapel + '" style="width: 100%;" type="text" readonly>';
        html += '</div>';
        html += '<div class="input_light mb-3">';
        html += '<div>Jadwal</div>';
        html += '<input placeholder="Tanggal pelaksanaan" name="jadwal" value="' + time_php_to_js(data.jadwal) + '" style="width: 100%;" type="date">';
        html += '</div>';
        html += '<div class="input_light mb-3">';
        html += '<div>Materi (*Harus PDF dan max 5MB)</div>';
        html += '<input name="file" value="" style="width: 100%;" type="file">';
        html += '</div>';

        html += '<div class="d-grid mt-5">';
        html += '<button type="submit" class="btn_5 rounded px-2 py-1"><i class="fa-solid fa-floppy-disk"></i> Save</button>';
        html += '</div>';

        html += '</form>';
        html += '</div>';
        html += '</div>';
        html += '</div>';

        $('.modal_body_detail').html(html);
        let myModal = document.getElementById('modal_detail');
        let modal = bootstrap.Modal.getOrCreateInstance(myModal)
        modal.show();

    });
</script>
<?= $this->endSection() ?>