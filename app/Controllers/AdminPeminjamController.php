<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminPeminjamController extends BaseController
{

    public function index()
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->back();
        }

        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe, users.nama_user')
            ->join('alat', 'alat.id_hp = peminjaman.id_alat')
            ->join('users', 'users.id = peminjaman.id_user')
            ->findAll();

        return view('admin/peminjaman/index', [
            'peminjaman' => $peminjaman
        ]);
    }

    public function edit($id)
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->back();
        }

        $data = $this->peminjamanModel->find($id);

        return view('admin/peminjaman/edit', [
            'peminjaman' => $data
        ]);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'Admin') {
            return redirect()->back();
        }

        $this->peminjamanModel->update($id, [
            'waktu'   => $this->request->getPost('waktu'),
            'status'  => $this->request->getPost('status'),
            'catatan' => $this->request->getPost('catatan')
        ]);

        return redirect()->to('/admin/peminjaman')->with('success', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $this->peminjamanModel->delete($id);

        logAktivitas('Admin mengarsipkan data peminjaman');

        return redirect()->back()->with('success', 'Data dipindahkan ke arsip');
    }

    public function pengembalian()
    {
        $peminjamanModel = new \App\Models\PeminjamanModel();
        $alatModel = new \App\Models\AlatModel();

        // Ambil data pengembalian yang menunggu verifikasi saja
        $data['pengembalian'] = $peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp')
            ->whereIn('peminjaman.status', ['Menunggu Pengembalian', 'Menunggu_Pengembalian'])
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();

        // Ambil history pengembalian yang sudah selesai (Dikembalikan)
        $data['historyPengembalian'] = $peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp')
            ->where('peminjaman.status', 'Dikembalikan')
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();

        // Ambil peminjaman yang statusnya Disetujui untuk form tambah pengembalian
        $data['peminjamanAktif'] = $peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp')
            ->whereIn('peminjaman.status', ['Disetujui', 'Dipinjam'])
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();

        return view('admin/pengembalian/index', $data);
    }

    public function tambahPengembalian()
    {
        $peminjamanModel = new \App\Models\PeminjamanModel();
        $alatModel       = new \App\Models\AlatModel();

        $id_peminjaman = $this->request->getPost('id_peminjaman');
        $kondisi_hp    = $this->request->getPost('kondisi_hp');

        // Hitung denda berdasarkan kondisi
        if ($kondisi_hp == 'baik') {
            $denda = 0;
        } elseif ($kondisi_hp == 'rusak ringan') {
            $denda = 10000;
        } else {
            $denda = 20000;
        }

        $peminjaman = $peminjamanModel->find($id_peminjaman);

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan');
        }

        // Update status peminjaman menjadi Menunggu Pengembalian
        $peminjamanModel->update($id_peminjaman, [
            'status'         => 'Menunggu Pengembalian',
            'kondisi_hp'     => $kondisi_hp,
            'denda'          => $denda,
            'tanggal_kembali'=> date('Y-m-d'),
        ]);

        logAktivitas('Admin menambahkan pengembalian HP');

        return redirect()->back()->with('success', 'Pengembalian berhasil ditambahkan');
    }

    public function setujuiPengembalian($id)
    {
        $peminjamanModel = new \App\Models\PeminjamanModel();
        $alatModel = new \App\Models\AlatModel();

        $peminjaman = $peminjamanModel->find($id);

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan');
        }

        // update peminjaman menjadi dikembalikan
        $peminjamanModel->update($id, [
            'status' => 'Dikembalikan'
        ]);

        // kembalikan status HP menjadi tersedia
        $alatModel->update($peminjaman['id_hp'], [
            'status' => 'Tersedia'
        ]);

        logAktivitas('Admin menyetujui pengembalian HP');

        return redirect()->back()->with('success', 'Pengembalian berhasil disetujui, HP sudah tersedia kembali');
    }
}