<?php

namespace App\Controllers;

class User extends BaseController
{
    function __construct()
    {
        helper('functions');
        check_role();
    }

    public function index(): string
    {

        return view('settings/user', ['judul' => menu()['menu']]);
    }

    public function add()
    {
        $nama = upper_first(clear($this->request->getVar('nama')));
        $username = strtolower(clear($this->request->getVar('username')));
        $role = upper_first(clear($this->request->getVar('role')));
        $password = password_hash(getenv('DEFAULT_PASSWORD'), PASSWORD_DEFAULT);

        $db = db(menu()['tabel']);
        $is_exist = $db->where('username', $username)->get()->getRowArray();
        if ($is_exist) {
            gagal(base_url(menu()['controller']), 'Username already taken!.');
        }

        $data = [
            'nama' => $nama,
            'username' => $username,
            'role' => $role,
            'password' => $password
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
        $username = strtolower(clear($this->request->getVar('username')));
        $role = upper_first(clear($this->request->getVar('role')));

        $db = db(menu()['tabel']);
        $is_exist = $db->where('username', $username)->whereNotIn('id', [$id])->get()->getRowArray();

        if ($is_exist) {
            gagal(base_url(menu()['controller']), 'Username already taken!.');
        }

        $q = $db->where('id', $id)->get()->getRowArray();
        if (!$q) {
            gagal(base_url(menu()['controller']), 'Id not found!.');
        }


        $q['nama'] = $nama;
        $q['username'] = $username;
        $q['role'] = $role;


        $db->where('id', $id);
        if ($db->update($q)) {
            sukses(base_url(menu()['controller']), 'Update data success.');
        } else {
            gagal(base_url(menu()['controller']), 'Update data failed!.');
        }
    }
    public function reset_password()
    {
        $id = clear($this->request->getVar('id'));

        $db = db(menu()['tabel']);

        $q = $db->where('id', $id)->get()->getRowArray();
        if (!$q) {
            gagal(base_url(menu()['controller']), 'Id not found!.');
        }


        $q['password'] = password_hash(getenv('DEFAULT_PASSWORD'), PASSWORD_DEFAULT);;


        $db->where('id', $id);
        if ($db->update($q)) {
            sukses_js('Reset password sukses.');
        } else {
            gagal_js('Reset password gagal!.');
        }
    }
}
