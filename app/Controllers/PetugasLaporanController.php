<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;

class PetugasLaporanController extends BaseController
{
    protected $peminjamanModel;

    public function __construct()
    {
        // Memanggil model peminjaman
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        // Mengambil seluruh data peminjaman
        $data['peminjaman'] = $this->peminjamanModel->findAll();

        // Menampilkan halaman laporan
        return view('petugas/laporan/index', $data);
    }

    public function cetak()
    {
        // Data yang sama, tapi tampilan khusus cetak
        $data['peminjaman'] = $this->peminjamanModel->findAll();

        return view('petugas/laporan/cetak', $data);
    }
}