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

        // Ambil data pengembalian yang menunggu verifikasi dan yang sudah dikembalikan
        $data['pengembalian'] = $peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp')
            ->whereIn('peminjaman.status', ['Menunggu Pengembalian', 'Menunggu_Pengembalian', 'Dikembalikan'])
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();

        return view('admin/pengembalian/index', $data);
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