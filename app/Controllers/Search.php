<?php

namespace App\Controllers;

class Search extends BaseController
{
    public function search_db()
    {
        $tabel = clear($this->request->getVar('tabel'));
        $col_search = clear($this->request->getVar('col_search'));
        $value = clear($this->request->getVar('value'));
        $where = clear($this->request->getVar('where'));
        $order_by = clear($this->request->getVar('order_by'));
        $limit = clear($this->request->getVar('limit'));
        $db = clear($this->request->getVar('db'));

        $db = db($tabel, $db);
        $db->like($col_search, $value);
        if ($where !== "") {
            $exp = explode("=", $where);
            $db->where($exp[0], $exp[1]);
        }
        if ($order_by !== "") {
            $exp = explode("=", $order_by);
            $db->orderBy($exp[0], $exp[1]);
        }
        if ($limit !== "") {
            $db->limit($limit);
        }

        $q = $db->get()->getResultArray();

        sukses_js('Ok', $q);
    }
}
