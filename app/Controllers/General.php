<?php

namespace App\Controllers;

class General extends BaseController
{
    function __construct()
    {
        helper('functions');
        check_role('id');
    }
    public function delete()
    {

        $data = json_decode(json_encode($this->request->getVar('data')), true);

        $db = db($data['tabel']);

        $q = $db->where($data['where'], $data['value'])->get()->getRowArray();

        if (!$q) {
            gagal_js('Id not found!.');
        }
        $db->where($data['where'], $data['value']);
        if ($db->delete()) {
            sukses_js('Data sukses dihapus.');
        } else {
            sukses_js('Data gagal dihapus!.');
        }
    }
}
