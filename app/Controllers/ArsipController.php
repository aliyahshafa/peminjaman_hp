<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PeminjamanModel;

class ArsipController extends BaseController
{
    protected $peminjamanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
    }

    // tampilkan data yang sudah dihapus
    public function peminjaman()
    {
        $data['peminjaman'] = $this->peminjamanModel
            ->onlyDeleted() // KHUSUS DATA TERHAPUS
            ->findAll();

        return view('admin/arsip/peminjaman', $data);
    }
}
