<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>
<?php
$db = db('materi');
$hari = hari(date('l'))['indo'];
$q = $db->where('angkatan', session('angkatan'))->where('jadwal', $hari)->get()->getRowArray();
?>
<?php if (!$q): ?>
    <div class="text_danger_light">HARI INI TIDAK ADA MATERI!.</div>

<?php else: ?>
    <div><b class="text_danger_light"><?= $q['jadwal']; ?></b> <?= $q['mapel']; ?> Kelas <?= $q['angkatan']; ?></div>
    <object data="<?= base_url('materi/'); ?><?= $q['materi']; ?>" width="100%" height="700"></object>
<?php endif; ?>
<?= $this->endSection() ?>