<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>
<?php
$db = db('kelas');
$data = $db->where('sub', url(4))->orderBy('urutan', 'ASC')->get()->getResultArray();

$not_input = [
    ['col' => 'sub', 'col_show' => 'value', 'db' => 'pembelajaran', 'col_search' => 'value', 'col_insert' => 'value', 'tabel' => 'options', 'where' => 'kategori=Sub', 'form' => 'select', 'limit' => '', 'order_by' => 'id=ASC'],
    ['col' => 'wali_kelas', 'db' => 'karyawan', 'col_show' => 'nama', 'col_search' => 'nama', 'col_insert' => 'nama', 'tabel' => 'karyawan', 'where' => 'status=Aktif', 'form' => 'select', 'limit' => 10, 'order_by' => 'nama=ASC'],
    ['col' => 'angkatan', 'col_show' => 'value', 'db' => 'pembelajaran', 'col_search' => 'value', 'col_insert' => 'value', 'tabel' => 'options', 'where' => 'kategori=Angkatan', 'form' => 'select', 'limit' => '', 'order_by' => 'id=ASC']
];
$delete = ['where' => 'id', 'tabel' => menu()['tabel'], 'alert' => 'Yakin hapus data ini?', 'url' => 'general/delete'];
?>
<h1>Kelas <?= menu()['menu']; ?></h1>
<button class="btn_3 mb-2 rounded data_modal"><i class="fa-solid fa-circle-plus"></i> Add Kelas Sub <?= url(4); ?></button>
<?= view('template/read_tabel_db', ['col' => 'urutan', 'sort' => 'ASC', 'cols' => ['sub', 'angkatan', 'kelas', 'wali_kelas', 'urutan'], 'not_input' => $not_input, 'delete' => $delete, 'data' => $data]); ?>

<?= $this->endSection() ?>