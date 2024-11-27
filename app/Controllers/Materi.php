<?php

namespace App\Controllers;

class Materi extends BaseController
{
    function __construct()
    {
        helper('functions');
        check_role();
    }

    public function index($sub = 'Smp'): string
    {
        return view('logged_content/materi', ['judul' => menu()['menu'] . ' ' . $sub]);
    }
    public function add()
    {
        $sub = clear($this->request->getVar('sub'));
        $angkatan = clear($this->request->getVar('angkatan'));
        $jadwal = strtotime($this->request->getVar('jadwal'));
        $mapel = clear($this->request->getVar('mapel'));


        $url = base_url('materi/') . $sub;
        $file = $_FILES['file'];

        if ($file['error'] == 4) {
            gagal($url, 'File belum dipilih.');
        }

        $randomname = 'materi_' . str_replace(" ", "_", str_replace("'", "", $mapel)) . '_' . $sub . '_' . time();

        if ($file['error'] == 0) {
            $size = $file['size'];

            if ($size > 5000000) {
                gagal($url, 'Ukuran file maksimal % MB.');
            }

            $exp = explode(".", $file['name']);
            $exe = strtolower(end($exp));

            if ($exe !== 'pdf') {
                gagal($url, 'Gagal!. Format file harus PDF.!');
            }

            $dir = 'materi/';

            $upload = $dir .  $randomname . '.' . $exe;

            if (!move_uploaded_file($file['tmp_name'], $upload)) {
                gagal($url, 'File gagal diupload.');
            } else {

                $db = db('materi');

                $data = [
                    'sub' => $sub,
                    'jadwal' => $jadwal,
                    'angkatan' => $angkatan,
                    'mapel' => $mapel,
                    'materi' => $randomname . '.' . $exe
                ];
                if ($db->insert($data)) {
                    sukses($url, 'Data berhasil disimpan.');
                } else {
                    gagal($url, 'Data berhasil disimpan!.');
                }
            }
        }
    }
    public function update()
    {
        $id = clear($this->request->getVar('id'));
        $sub = clear($this->request->getVar('sub'));
        $angkatan = clear($this->request->getVar('angkatan'));
        $jadwal = strtotime($this->request->getVar('jadwal'));
        $mapel = clear($this->request->getVar('mapel'));

        $url = base_url('materi/') . $sub;

        $db = db('materi');
        $q = $db->where('id', $id)->get()->getRowArray();
        if (!$q) {
            gagal($url, 'Id not found!.');
        }

        $file = $_FILES['file'];

        if ($file['error'] == 0) {
            $randomname = 'materi_' . str_replace(" ", "_", str_replace("'", "", $mapel)) . '_' . $sub . '_' . time();
            $size = $file['size'];

            if ($size > 5000000) {
                gagal($url, 'Ukuran file maksimal % MB.');
            }

            $exp = explode(".", $file['name']);
            $exe = strtolower(end($exp));

            if ($exe !== 'pdf') {
                gagal($url, 'Gagal!. Format file harus PDF.!');
            }

            $dir = 'materi/';

            $upload = $dir .  $randomname . '.' . $exe;

            if (!move_uploaded_file($file['tmp_name'], $upload)) {
                gagal($url, 'File gagal diupload.');
            } else {

                if (!unlink($dir . $q['materi'])) {
                    gagal($url, 'File lama gagal dihapus.');
                }

                $q['materi'] = $randomname . '.' . $exe;
            }
        }

        if ($file['error'] == 4) {
            $q['sub'] = $sub;
            $q['jadwal'] = $jadwal;
            $q['angkatan'] = $angkatan;
            $q['mapel'] = $mapel;
        }

        $db->where('id', $id);
        if ($db->update($q)) {
            sukses($url, 'Data berhasil disimpan.');
        } else {
            gagal($url, 'Data berhasil disimpan!.');
        }
    }
}
