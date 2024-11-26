<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>

<?php if (session('role') == 'Root' || session('role') == 'Admin'): ?>
    <?php
    $db = db('materi');
    $val = $db->get()->getResultArray();

    $data = [];
    foreach ($val as $i) {
        if (date('d/m/Y') == date('d/m/Y', $i['jadwal'])) {
            $data[] = $i;
        }
    }

    ?>
    <div>Materi <b class="text_danger_light"><?= hari(date('l'))['indo']; ?>, <?= date('d/m/Y'); ?></b></div>
    <table class="table table-dark table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Sub</th>
                <th>Angkatan</th>
                <th>Mapel</th>
                <th>Act</th>
            </tr>
        </thead>
        <tbody class="body_cari">
            <?php foreach ($data as $k => $i): ?>

                <tr>
                    <td><?= ($k + 1); ?></td>
                    <td><?= $i['sub']; ?></td>
                    <td><?= $i['angkatan']; ?></td>
                    <td><?= $i['mapel']; ?></td>
                    <td><a class="link_3 rounded show_materi" data-url="<?= base_url('materi/') . $i['materi']; ?>" href=""><i class="fa-solid fa-up-right-from-square"></i> Show</a></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>


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
            let html = '<object class="pdf" data="' + url + '" width="100%" height="700"></object>';
            $('.modal_body_show_materi').html(html);

            let myModal = document.getElementById('show_materi');
            let modal = bootstrap.Modal.getOrCreateInstance(myModal)
            modal.show();

        });
    </script>
<?php else: ?>

    <?php
    $db = db('materi');
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
<?php endif; ?>


<?= $this->endSection() ?>