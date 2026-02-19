<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PeminjamController extends BaseController
{
    public function index()
    {
        // Proteksi: hanya peminjam
        if (session()->get('role') != 'Peminjam') {
            return redirect()->to('/login');
        }
        // menampilkan dashboard peminjam
        return view('dashboard/peminjam');
    }

    public function riwayat()
    {
        $id_user = session()->get('id_user');

        $peminjaman = $this->peminjamanModel
            ->where('id_user', $id_user)
            ->where('status', 'dikembalikan')
            ->findAll();

        return view('peminjam/riwayat', $data);
    }
}
