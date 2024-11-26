<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>
<?php
$dbk = db('kelas');
$value = $dbk->where('sub', url(4))->orderBy('urutan', 'ASC')->get()->getResultArray();
$kelas = [];
foreach ($value as $i) {
    $kelas[] = $i['kelas'];
}
$val = [];
if (count($kelas) > 0) {
    $db = db('siswa');
    $val = $db->whereIn('kelas', $kelas)->get()->getResultArray();
}

$data = [];

foreach ($val as $i) {
    $q = $dbk->where('kelas', $i['kelas'])->get()->getRowArray();
    $i['angkatan'] = ($q ? $q['angkatan'] : '');
    $i['wali_kelas'] = ($q ? $q['wali_kelas'] : '');

    $jwt = [
        'id' => 99999999999,
        'nama' => $i['nama'],
        'role' => 'Member',
        'username' => 'temp',
        'angkatan' => $i['angkatan'],
        'kelas' => $i['kelas']
    ];

    $i['jwt'] = encode_jwt($jwt);
    $data[] = $i;
}
$not_input = [
    ['col' => 'kelas', 'col_show' => 'kelas', 'db' => 'pembelajaran', 'col_search' => 'kelas', 'col_insert' => 'kelas', 'tabel' => 'kelas', 'where' => 'sub=' . url(4), 'form' => 'select', 'limit' => '', 'order_by' => 'urutan=ASC'],
    ['col' => 'nama', 'col_show' => 'nama', 'db' => 'santri', 'col_search' => 'nama', 'col_insert' => 'nama', 'tabel' => 'santri', 'where' => 'status=Aktif', 'form' => 'select', 'limit' => 10, 'order_by' => 'nama=ASC'],
];
$delete = ['where' => 'id', 'tabel' => menu()['tabel'], 'alert' => 'Yakin hapus data ini?', 'url' => 'general/delete'];
?>


<h1>Siswa <?= menu()['menu']; ?></h1>
<button class="btn_3 mb-2 rounded data_modal"><i class="fa-solid fa-circle-plus"></i> Add Siswa Sub <?= url(4); ?></button>
<button class="btn_5 mb-2 rounded" data-bs-toggle="modal" data-bs-target="#link_jwt"><i class="fa-solid fa-link"></i> Link Login Siswa <?= menu()['menu']; ?></button>
<?= view('template/read_tabel_db', ['col' => 'nama', 'sort' => 'ASC', 'cols' => ['nama', 'kelas', 'wali_kelas'], 'not_input' => $not_input, 'delete' => $delete, 'data' => $data]); ?>


<!-- modal link jwt-->
<div class="modal fade" id="link_jwt" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body bg_3 rounded-top" style="padding: 1px;">
                <div class="bg_1 rounded p-0">
                    <div class="d-flex justify-content-between p-3">
                        <div><i class="fa-solid fa-copy"></i> Copy Link Login</div>
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
                            <th scope="col">Kelas</th>
                            <th scope="col">Angkatan</th>
                            <th scope="col">Act</th>
                        </tr>
                    </thead>
                    <tbody class="body_cari">
                        <?php foreach ($data as $k => $i): ?>
                            <tr>
                                <td><?= ($k + 1); ?></td>
                                <td><?= $i['nama']; ?></td>
                                <td>Member</td>
                                <td><?= $i['kelas']; ?></td>
                                <td><?= $i['angkatan']; ?></td>
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