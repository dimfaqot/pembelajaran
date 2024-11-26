<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>
<?php

$db = db('user');
$q = $db->orderBy('nama', 'ASC')->get()->getResultArray();

$users = [];

foreach ($q as $i) {
    $val = [
        'id' => $i['id'],
        'nama' => $i['nama'],
        'role' => $i['role'],
        'username' => $i['username'],
        'angkatan' => '',
        'kelas' => ''
    ];

    $i['jwt'] = encode_jwt($val);

    $users[] = $i;
}
$not_input = [
    ['col' => 'role', 'col_show' => 'value', 'db' => 'pembelajaran', 'col_search' => 'value', 'col_insert' => 'value', 'tabel' => 'options', 'where' => 'kategori=Role', 'form' => 'select', 'limit' => '', 'order_by' => 'value=ASC']
];
$delete = ['where' => 'id', 'tabel' => menu()['tabel'], 'alert' => 'Yakin hapus data ini?', 'url' => 'general/delete'];
?>
<h1><?= menu()['menu']; ?></h1>
<button class="btn_3 mb-2 rounded data_modal"><i class="fa-solid fa-circle-plus"></i> Add <?= menu()['menu']; ?></button>
<button class="btn_5 mb-2 rounded" data-bs-toggle="modal" data-bs-target="#link_jwt"><i class="fa-solid fa-link"></i> Link <?= menu()['menu']; ?></button>
<?= view('template/read_tabel_db', ['col' => 'nama', 'sort' => 'ASC', 'cols' => ['nama', 'role', 'username'], 'not_input' => $not_input, 'delete' => $delete, 'data' => null]); ?>


<!-- modal link jwt-->
<div class="modal fade" id="link_jwt" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body bg_3 rounded-top" style="padding: 1px;">
                <div class="bg_1 rounded p-0">
                    <div class="d-flex justify-content-between p-3">
                        <div><i class="fa-solid fa-copy"></i> Copy Link <?= menu()['menu']; ?></div>
                        <div><a data-bs-dismiss="modal" href=""><i class="fa-solid fa-circle-xmark"></i></a></div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg_2">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Role</th>
                            <th scope="col">Act</th>
                        </tr>
                    </thead>
                    <tbody class="body_cari">
                        <?php foreach ($users as $k => $i): ?>
                            <tr>
                                <td><?= ($k + 1); ?></td>
                                <td><?= $i['nama']; ?></td>
                                <td><?= $i['role']; ?></td>
                                <td><a href="" class="copy_link link_4 rounded p-2" data-link="<?= base_url('login/auth/jwt/') . $i['jwt']; ?>"><i class="fa-solid fa-link"></i> Copy</a></td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.copy_link', function(e) {
        e.preventDefault();
        let link = $(this).data('link');
        navigator.clipboard.writeText(link);
        sukses(link);
    })
</script>
<?= $this->endSection() ?>