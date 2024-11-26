<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

function db($tabel, $db = null)
{
    if ($db == null || $db == 'pembelajaran') {
        $db = \Config\Database::connect();
    } else {
        $db = \Config\Database::connect(strtolower(str_replace(" ", "_", $db)));
    }
    $db = $db->table($tabel);

    return $db;
}



function encode_jwt($data)
{

    $jwt = JWT::encode($data, getenv('KEY_JWT'), 'HS256');

    return $jwt;
}

function decode_jwt($encode_jwt)
{
    try {

        $decoded = JWT::decode($encode_jwt, new Key(getenv('KEY_JWT'), 'HS256'));
        $arr = (array)$decoded;

        return $arr;
    } catch (\Exception $e) { // Also tried JwtException
        $data = [
            'status' => '400',
            'message' => $e->getMessage()
        ];

        echo json_encode($data);
        die;
    }
}


function clear($text)
{
    $text = trim($text);
    $text = htmlspecialchars($text);
    return $text;
}



function upper_first($text)
{
    // $text = clear($text);
    $exp = explode(" ", $text);

    $val = [];
    foreach ($exp as $i) {
        $lower = strtolower($i);
        $val[] = ucfirst($lower);
    }

    return implode(" ", $val);
}

function sukses($url, $pesan)
{
    session()->setFlashdata('sukses', $pesan);
    header("Location: " . $url);
    die;
}

function gagal($url, $pesan, $order = null)
{
    session()->setFlashdata(($order == null ? 'gagal_with_button' : 'gagal'), "Gagal!. " . $pesan);
    header("Location: " . $url);
    die;
}

function sukses_js($pesan, $data = null, $data2 = null, $data3 = null, $data4 = null)
{
    $data = [
        'status' => '200',
        'message' => $pesan,
        'data' => $data,
        'data2' => $data2,
        'data3' => $data3,
        'data4' => $data4
    ];

    echo json_encode($data);
    die;
}

function gagal_js($pesan)
{
    $data = [
        'status' => '400',
        'message' => "Gagal!. " . $pesan
    ];

    echo json_encode($data);
    die;
}

function menus()
{

    $q1[] = ['id' => 0, 'urutan' => 0, 'role' => session('role'), 'menu' => 'Home', 'tabel' => 'users', 'controller' => 'home', 'icon' => "fa-solid fa-earth-asia", 'url' => 'home', 'logo' => 'file_not_found.jpg', 'grup' => ''];
    $db = db('menu');
    $q2 = $db->where('role', session('role'))->orderBy('urutan', 'ASC')->get()->getResultArray();
    $menus = array_merge($q1, $q2);
    return $menus;
}

function menu($req = null)
{
    if ($req == null) {
        foreach (menus() as $i) {
            if ($i['url'] == url()) {
                return $i;
            }
        }
    } else {
        foreach (menus() as $i) {
            if ($i['url'] == $req) {
                return $i;
            }
        }
    }
}

function check_role($order = null)
{

    if (!session('id')) {
        gagal(base_url('login'), 'You are not login. Login first!.');
    }
    if ($order == null) {
        if (!menu()) {
            gagal(base_url('home'), 'You are not allowed!.');
        }
    }
}

function url($req = 3)
{
    $url = service('uri');
    $res = $url->getPath();

    $req = $req - 1;
    $exp = explode("/", $res);
    if (array_key_exists($req, $exp)) {
        return $exp[$req];
    }

    return '';
}


function options($req = 'Role', $asc = 'Value=Asc')
{

    $db = db('options');

    $db->where('kategori', $req);
    $exp = explode("=", $asc);
    $db->orderBy($exp[0], $exp[1]);
    $q = $db->get()->getResultArray();
    return $q;
}


function bulan($req = null)
{
    $bulan = [
        ['romawi' => 'I', 'bulan' => 'Januari', 'angka' => '01', 'satuan' => 1],
        ['romawi' => 'II', 'bulan' => 'Februari', 'angka' => '02', 'satuan' => 2],
        ['romawi' => 'III', 'bulan' => 'Maret', 'angka' => '03', 'satuan' => 3],
        ['romawi' => 'IV', 'bulan' => 'April', 'angka' => '04', 'satuan' => 4],
        ['romawi' => 'V', 'bulan' => 'Mei', 'angka' => '05', 'satuan' => 5],
        ['romawi' => 'VI', 'bulan' => 'Juni', 'angka' => '06', 'satuan' => 6],
        ['romawi' => 'VII', 'bulan' => 'Juli', 'angka' => '07', 'satuan' => 7],
        ['romawi' => 'VIII', 'bulan' => 'Agustus', 'angka' => '08', 'satuan' => 8],
        ['romawi' => 'IX', 'bulan' => 'September', 'angka' => '09', 'satuan' => 9],
        ['romawi' => 'X', 'bulan' => 'Oktober', 'angka' => '10', 'satuan' => 10],
        ['romawi' => 'XI', 'bulan' => 'November', 'angka' => '11', 'satuan' => 11],
        ['romawi' => 'XII', 'bulan' => 'Desember', 'angka' => '12', 'satuan' => 12]
    ];

    $res = $bulan;
    foreach ($bulan as $i) {
        if ($i['bulan'] == $req) {
            $res = $i;
        } elseif ($i['angka'] == $req) {
            $res = $i;
        } elseif ($i['satuan'] == $req) {
            $res = $i;
        } elseif ($i['romawi'] == $req) {
            $res = $i;
        }
    }
    return $res;
}


function nama_gelar($req)
{
    $nama = $req['nama'];

    if ($req['gelar_s3'] !== '') {
        $nama = $req['gelar_s3'] . '. ' . $req['nama'];
        if ($req['gelar_s2'] !== '' && $req['gelar_s1'] !== '') {
            $nama .= ", " . $req['gelar_s1'] . ', ' . $req['gelar_s2'] . '.';
        } elseif ($req['gelar_s2'] == '' && $req['gelar_s1'] !== '') {
            $nama = $req['nama'] . ", " . $req['gelar_s1'] . '.';
        } elseif ($req['gelar_s2'] !== '' && $req['gelar_s1'] == '') {
            $nama = $req['nama'] . ", " . $req['gelar_s2'] . '.';
        }
    } elseif ($req['gelar_s2'] !== '') {
        $nama = $req['nama'] . ', ' . $req['gelar_s2'] . '.';
        if ($req['gelar_s1'] !== '') {
            $nama = $req['nama'] . ", " . $req['gelar_s1'] . ', ' . $req['gelar_s2'] . '.';
        }
    } elseif ($req['gelar_s1'] !== '') {
        $nama = $req['nama'] . ', ' . $req['gelar_s1'] . '.';
    }

    return $nama;
}

function alamat_lengkap($req)
{
    $alamat = '';
    if ($req['alamat'] !== '') {
        $alamat .= $req['alamat'];
    }
    if ($req['kelurahan'] !== '') {
        $alamat .= ' Kel. ' . $req['kelurahan'];
    }
    if ($req['kecamatan'] !== '') {
        $alamat .= ' Kec. ' . $req['kecamatan'];
    }
    if ($req['kabupaten'] !== '') {
        $alamat .= ' Kab. ' . $req['kabupaten'];
    }
    if ($req['provinsi'] !== '') {
        $alamat .= ' Prov. ' . $req['provinsi'];
    }
    if (array_key_exists('kode_pos', $req)) {
        if ($req['kode_pos'] !== '' && $req['kode_pos'] !== '0') {
            $alamat .= ' ' . $req['kode_pos'];
        }
    }
    return $alamat;
}


function rp_to_int($uang)
{
    $uang = str_replace("Rp. ", "", $uang);
    $uang = str_replace(".", "", $uang);
    return $uang;
}


function hari($req = null)
{
    $hari = [
        ['inggris' => 'Monday', 'indo' => 'Senin'],
        ['inggris' => 'Tuesday', 'indo' => 'Selasa'],
        ['inggris' => 'Wednesday', 'indo' => 'Rabu'],
        ['inggris' => 'Thursday', 'indo' => 'Kamis'],
        ['inggris' => 'Friday', 'indo' => 'Jumat'],
        ['inggris' => 'Saturday', 'indo' => 'Sabtu'],
        ['inggris' => 'Sunday', 'indo' => 'Ahad']
    ];

    $res = [];
    foreach ($hari as $i) {
        if ($i['inggris'] == $req) {
            $res = $i;
        } elseif ($i['indo'] == $req) {
            $res = $i;
        }
    }

    if ($req == null) {
        return $hari;
    }
    return $res;
}

function day_in_month()
{
    return cal_days_in_month(CAL_GREGORIAN, date('n'), date('Y'));
}

function angka($uang)
{
    return number_format($uang, 0, ",", ".");
}

function is_menu_active($grup)
{
    $db = db('menu');
    $res = null;
    $q = $db->where('controller', menu()['controller'])->where('grup', $grup)->get()->getRowArray();
    if ($q) {
        $res = 1;
    }
    return $res;
}


function get_materi()
{
    $db = db('materi');
}
