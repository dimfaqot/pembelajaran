<?= $this->extend('template/logged') ?>

<?= $this->section('content') ?>
<?php
$db = db('menu');
$menus = $db->where('role', 'Root')->orderBy('urutan', 'ASC')->get()->getResultArray();

$not_input = [
    ['col' => 'role', 'db' => 'pembelajaran', 'col_show' => 'value', 'col_search' => 'value', 'col_insert' => 'value', 'tabel' => 'options', 'where' => 'kategori=Role', 'form' => 'select', 'limit' => '', 'order_by' => 'value=ASC']
];
$delete = ['where' => 'id', 'tabel' => menu()['tabel'], 'alert' => 'Yakin hapus data ini?', 'url' => 'general/delete'];
?>
<h1><?= menu()['menu']; ?></h1>
<button class="btn_3 mb-2 rounded data_modal"><i class="fa-solid fa-circle-plus"></i> Add <?= menu()['menu']; ?></button>
<button data-bs-toggle="modal" data-bs-target="#copy_menu" class="btn_5 mb-2 rounded"><i class="fa-solid fa-copy"></i> Copy <?= menu()['menu']; ?></button>
<?= view('template/read_tabel_db', ['col' => 'urutan', 'sort' => 'ASC', 'cols' => ['role', 'menu', 'tabel', 'controller', 'icon', 'urutan', 'grup'], 'not_input' => $not_input, 'delete' => $delete, 'data' => null]); ?>

<!-- modal copy_menu-->
<div class="modal fade" id="copy_menu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body bg_3 rounded-top" style="padding: 1px;">
                <div class="bg_1 rounded p-0">
                    <div class="d-flex justify-content-between p-3">
                        <div><i class="fa-solid fa-copy"></i> Copy <?= menu()['menu']; ?></div>
                        <div><a data-bs-dismiss="modal" href=""><i class="fa-solid fa-circle-xmark"></i></a></div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg_2">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Menu</th>
                            <th scope="col">Copy To</th>
                            <th scope="col">Act</th>
                        </tr>
                    </thead>
                    <tbody class="body_cari">
                        <?php foreach ($menus as $k => $i): ?>
                            <tr>
                                <td><?= ($k + 1); ?></td>
                                <td><?= $i['menu']; ?></td>
                                <td>
                                    <select class="form-select form-select-sm target_role" style="padding: 2px 4px;">
                                        <?php foreach (options() as $k => $o): ?>
                                            <?php if ($o['value'] !== 'Root'): ?>
                                                <option <?= ($k == 2 ? 'selected' : ''); ?> value="<?= $o['value']; ?>"><?= $o['value']; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><a href="" class="copy_menu link_4 rounded p-2" data-menu_id="<?= $i['id']; ?>"><i class="fa-solid fa-copy"></i> Copy</a></td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '.copy_menu', function(e) {
        e.preventDefault();
        let menu_id = $(this).data('menu_id');
        let target_role = $('.target_role').val();

        post('menu/copy_menu', {
            menu_id,
            target_role
        }).then(res => {
            if (res.status == '200') {
                sukses(res.message);
                setTimeout(() => {
                    location.reload();
                }, 1200);
            } else {
                gagal(res.message);
            }
        })
    })
</script>
<?= $this->endSection() ?>