<?php

namespace App\Controllers;

class Home extends BaseController
{
    function __construct()
    {
        helper('functions');
        check_role();
    }
    public function index(): string
    {
        $db = db('user');
        $q = $db->orderBy('nama', 'ASC')->get()->getResultArray();

        $users = [];

        foreach ($q as $i) {
            $data = [
                'id' => $i['id'],
                'role' => $i['role'],
                'ip' => getenv('IP')
            ];

            $i['jwt'] = encode_jwt($data);
            $users[] = $i;
        }

        return view('logged_content/home', ['judul' => 'Home - Pembelajaran Daring', 'users' => $users]);
    }
}
