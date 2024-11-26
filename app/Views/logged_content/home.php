<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>
<?php
$db = db('materi');
$hari = hari(date('l'))['indo'];
$val = $db->where('angkatan', session('angkatan'))->get()->getResultArray();

$q = [];
foreach ($val as $i) {
    if (date('d/m/Y') == date('d/m/Y', $i['jadwal'])) {
        $q = $i;
    }
}
?>
<?php if (!$q): ?>
    <div class="text_danger_light">HARI INI TIDAK ADA MATERI!.</div>

<?php else: ?>
    <div><b class="text_danger_light"><?= hari(date('l', $q['jadwal']))['indo']; ?>, <?= date('d/m/Y', $q['jadwal']); ?></b> <?= $q['mapel']; ?> Kelas <?= $q['angkatan']; ?></div>
    <object data="<?= base_url('materi/'); ?><?= $q['materi']; ?>" width="100%" height="700"></object>
<?php endif; ?>
<?= $this->endSection() ?>