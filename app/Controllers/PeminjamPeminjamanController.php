<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\PeminjamanModel;

class PeminjamPeminjamanController extends BaseController
{
    public function index()
    {
        $id_user = session()->get('id_user'); // ✅ AMBIL DARI SESSION

        $data['pengembalian'] = $this->pengembalianModel
            ->getRiwayatByUser($id_user);

        return view('peminjam/pengembalian/index', $data);
    }

    // menampilkan form pengajuan
    public function create()
    {
        $alatModel = new AlatModel();

        // hanya alat yang tersedia
        $data['alat'] = $alatModel->where('status', 'Tersedia')->findAll();

        return view('peminjam/peminjaman/create', $data);
    }

    // simpan pengajuan
    public function store()
    {
        $peminjamanModel = new PeminjamanModel();

        $data = [
            'id_user'   => session()->get('id_user'),
            'id_hp'     => $this->request->getPost('id_hp'),
            'nama_user' => session()->get('nama_user'),
            'waktu'     => $this->request->getPost('waktu'),
            'status'    => 'diajukan'
        ];

        $peminjamanModel->insert($data);

        return redirect()->to('/user')->with('success', 'Peminjaman berhasil diajukan');
    }
}