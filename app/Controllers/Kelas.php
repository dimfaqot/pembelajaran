<?php

namespace App\Controllers;

class Kelas extends BaseController
{
    function __construct()
    {
        helper('functions');
        check_role();
    }

    public function index($sub = 'Smp'): string
    {
        return view('logged_content/' . menu()['url'], ['judul' => menu()['menu']]);
    }

    public function add()
    {
        $sub = upper_first(clear($this->request->getVar('sub')));
        $angkatan = upper_first(clear($this->request->getVar('angkatan')));
        $wali_kelas = upper_first(clear($this->request->getVar('wali_kelas')));
        $urutan = clear($this->request->getVar('urutan'));
        $kelas = strtoupper(clear($this->request->getVar('kelas')));

        $db = db(menu()['tabel']);
        $is_exist = $db->where('kelas', $kelas)->get()->getRowArray();
        if ($is_exist) {
            gagal(base_url('kelas/') . $sub, 'Data already exist!.');
        }



        $data = [
            'sub' => $sub,
            'angkatan' => $angkatan,
            'wali_kelas' => $wali_kelas,
            'kelas' => $kelas,
            'urutan' => $urutan
        ];


        if ($db->insert($data)) {
            sukses(base_url(menu()['controller']), 'Save data success.');
        } else {
            gagal(base_url(menu()['controller']), 'Save data failed!.');
        }
    }
    public function update()
    {
        $id = clear($this->request->getVar('id'));
        $sub = upper_first(clear($this->request->getVar('sub')));
        $angkatan = upper_first(clear($this->request->getVar('angkatan')));
        $wali_kelas = upper_first(clear($this->request->getVar('wali_kelas')));
        $urutan = clear($this->request->getVar('urutan'));
        $kelas = strtoupper(clear($this->request->getVar('kelas')));

        $db = db(menu()['tabel']);
        $q = $db->where('id', $id)->get()->getRowArray();
        if (!$q) {
            gagal(base_url(menu()['controller']), 'Id not found!.');
        }

        $is_exist = $db->whereNotIn('id', [$id])->where('kelas', $kelas)->get()->getRowArray();
        if ($is_exist) {
            gagal(base_url('kelas/') . $sub, 'Data already exist!.');
        }





        $q['sub'] = $sub;
        $q['angkatan'] = $angkatan;
        $q['wali_kelas'] = $wali_kelas;
        $q['urutan'] = $urutan;
        $q['kelas'] = $kelas;

        $db->where('id', $id);
        if ($db->update($q)) {
            sukses(base_url(menu()['controller']), 'Update data success.');
        } else {
            gagal(base_url(menu()['controller']), 'Update data failed!.');
        }
    }
}
