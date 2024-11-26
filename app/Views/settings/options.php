<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>
<?php
$not_input = [
    ['col' => '', 'col_show' => 'value', 'db' => 'pembelajaran', 'col_search' => 'value', 'col_insert' => 'value', 'tabel' => 'options', 'where' => 'kategori=Role', 'form' => 'select', 'limit' => '', 'order_by' => 'value=ASC']
];
$delete = ['where' => 'id', 'tabel' => menu()['tabel'], 'alert' => 'Yakin hapus data ini?', 'url' => 'general/delete'];
?>
<h1><?= menu()['menu']; ?></h1>
<button class="btn_3 mb-2 rounded data_modal"><i class="fa-solid fa-circle-plus"></i> Add <?= menu()['menu']; ?></button>
<?= view('template/read_tabel_db', ['col' => 'kategori', 'sort' => 'ASC', 'cols' => ['kategori', 'value'], 'not_input' => $not_input, 'delete' => $delete, 'data' => null]); ?>
<?= $this->endSection() ?>