<?php

namespace App\Controllers;

class Landing extends BaseController
{
    public function index(): string
    {
        return view('landing/landing', ['judul' => 'Landing - Pembelajaran Daring']);
    }
    public function login(): string
    {
        return view('landing/login', ['judul' => 'Login - Pembelajaran Daring']);
    }


    public function auth()
    {
        $username = clear($this->request->getVar('username'));
        $password = clear($this->request->getVar('password'));

        $db = db('user');
        $data = [
            'username' => $username,
            'password' => $password
        ];

        $q = $db->where('username', $username)->get()->getRowArray();

        if (!$q) {
            gagal(base_url('login'), 'Username not found!.');
        }

        if (!password_verify($password, $q['password'])) {
            gagal(base_url('login'), 'Password wrong!.');
        }

        $data = [
            'id' => $q['id'],
            'role' => $q['role'],
            'nama' => $q['nama'],
            'angkatan' => '',
            'kelas' => '',
        ];

        session()->set($data);

        sukses(base_url('home'), 'Ok');
    }

    public function auth_jwt($jwt)
    {
        $decode = decode_jwt($jwt);
        $db = db('user');

        $q = $db->where('id', $decode['id'])->get()->getRowArray();

        if ($decode['role'] !== 'Member' && !$q) {
            gagal(base_url('login'), 'Username not found!.');
        }

        $data = [
            'id' => $decode['id'],
            'role' => $decode['role'],
            'nama' => $decode['nama'],
            'angkatan' => $decode['angkatan'],
            'kelas' => $decode['kelas']
        ];

        session()->set($data);

        sukses(base_url('home'), 'Ok');
    }

    public function logout()
    {
        session()->remove('id');
        session()->remove('role');
        session()->remove('nama');
        session()->remove('angkatan');
        session()->remove('kelas');

        sukses(base_url('login'), 'Logout sukses!.');
    }
}
