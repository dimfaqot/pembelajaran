<?php

namespace App\Controllers;

class Siswa extends BaseController
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
        $nama = upper_first(clear($this->request->getVar('nama')));
        $kelas = strtoupper(clear($this->request->getVar('kelas')));

        $db = db(menu()['tabel']);
        $is_exist = $db->where('nama', $nama)->where('kelas', $kelas)->get()->getRowArray();
        if ($is_exist) {
            gagal(base_url('siswa/') . menu()['menu'], 'Siswa already exist!.');
        }



        $data = [
            'nama' => $nama,
            'kelas' => $kelas
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
        $nama = upper_first(clear($this->request->getVar('nama')));
        $kelas = strtoupper(clear($this->request->getVar('kelas')));

        $db = db(menu()['tabel']);
        $q = $db->where('id', $id)->get()->getRowArray();
        if (!$q) {
            gagal(base_url(menu()['controller']), 'Id not found!.');
        }

        $is_exist = $db->whereNotIn('id', [$id])->where('nama', $nama)->where('kelas', $kelas)->get()->getRowArray();
        if ($is_exist) {
            gagal(base_url('siswa/') . menu()['menu'], 'Siswa already exist!.');
        }





        $q['nama'] = $nama;
        $q['kelas'] = $kelas;

        $db->where('id', $id);
        if ($db->update($q)) {
            sukses(base_url(menu()['controller']), 'Update data success.');
        } else {
            gagal(base_url(menu()['controller']), 'Update data failed!.');
        }
    }
}
