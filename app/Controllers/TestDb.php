<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestDb extends BaseController
{
    public function index()
    {
        try {
            $db = \Config\Database::connect();
            $db->initialize();

            if ($db->connID === false)
                {
                    echo "Gagal konek <br>";
                    print_r($db->error());
                } else {
                    echo "koneksi database berhasil";
                }
        } catch (\Throwable $e) {
            echo "Exception:<br>";
            echo $e->getMessage();
        }
    }
}
