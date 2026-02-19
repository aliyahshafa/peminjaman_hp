<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;

class DashboardPeminjam extends BaseController
{
    protected $peminjamanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        $data = [];

        // Gunakan database builder langsung untuk menghindari masalah Model
        $db = \Config\Database::connect();
        $idUser = session()->get('id_user');
        
        // Query langsung dengan JOIN untuk mendapatkan semua data termasuk harga
        $riwayatRaw = $db->table('peminjaman')
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left')
            ->where('peminjaman.id_user', $idUser)
            ->whereIn('peminjaman.status', ['Diajukan', 'Disetujui', 'Menunggu Pengembalian', 'Dikembalikan'])
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->get()
            ->getResultArray();
        
        $data['riwayat'] = $riwayatRaw;
        return view('dashboard/peminjam', $data);
    }

    public function pengembalian()
    {
        // ambil data pengembalian milik user yang login
        $id_user = session()->get('id_user');

        // ambil data peminjaman MILIK USER
        // yang statusnya sudah dikembalikan
        $pengembalian = $this->peminjamanModel->getPengembalianByUser($id_user);

        return view('peminjam/pengembalian/index', [
            'pengembalian' => $pengembalian
        ]);
    }

    public function peminjaman()
    {
        $idUser = session()->get('id_user');

        // ambil peminjaman milik user ini
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp')
            ->where('peminjaman.id_user', $idUser)
            ->whereIn('peminjaman.status', [
                'Diajukan',
                'Disetujui', 
                'Dipinjam', 
                'Menunggu_Pengembalian',
                'Dikembalikan'])
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();

        // Ambil riwayat pembayaran terbaru (5 terakhir)
        $pembayaranModel = new \App\Models\PembayaranModel();
        $riwayatPembayaran = $pembayaranModel
            ->select('pembayaran.*, alat.merk, alat.tipe, peminjaman.waktu as waktu_pinjam, peminjaman.tanggal_kembali')
            ->join('peminjaman', 'peminjaman.id_peminjaman = pembayaran.id_peminjaman')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp')
            ->where('pembayaran.id_user', $idUser)
            ->orderBy('pembayaran.id_pembayaran', 'DESC')
            ->limit(5)
            ->findAll();

        return view('peminjam/peminjaman/index', [
            'peminjaman' => $peminjaman,
            'riwayatPembayaran' => $riwayatPembayaran
        ]);
    }

    public function kembalikan($id)
    {
        $kondisi = $this->request->getPost('kondisi_hp');
        $metodePembayaran = $this->request->getPost('metode_pembayaran');

        // Validasi metode pembayaran (wajib karena selalu ada biaya sewa)
        if (empty($metodePembayaran)) {
            return redirect()->back()->with('error', 'Metode pembayaran harus dipilih');
        }

        // Ambil data peminjaman
        $peminjaman = $this->peminjamanModel->find($id);
        if (!$peminjaman || $peminjaman['id_user'] != session()->get('id_user')) {
            return redirect()->to('/peminjam/peminjaman')->with('error', 'Data peminjaman tidak ditemukan');
        }

        // Ambil data alat untuk harga
        $alatModel = new \App\Models\AlatModel();
        $alatData = $alatModel->db->table('alat')
            ->select('harga')
            ->where('id_hp', $peminjaman['id_hp'])
            ->get()
            ->getRowArray();

        $hargaPerHari = $alatData['harga'] ?? 0;

        // Hitung durasi dari selisih tanggal
        $tanggalPinjam = new \DateTime($peminjaman['waktu']);
        $tanggalKembali = new \DateTime();
        $durasi = $tanggalPinjam->diff($tanggalKembali)->days;
        $durasi = max(1, $durasi); // Minimal 1 hari
        
        // Batasi maksimal 3 hari
        if ($durasi > 3) {
            $durasi = 3;
        }

        // Hitung biaya sewa
        $biayaSewa = $hargaPerHari * $durasi;

        // Hitung denda berdasarkan kondisi
        $denda = 0;
        if ($kondisi == 'rusak ringan') {
            $denda = 10000;
        } elseif ($kondisi == 'rusak berat') {
            $denda = 20000;
        }
        $denda = (int) $denda;

        // Total pembayaran = biaya sewa + denda
        $totalPembayaran = $biayaSewa + $denda;

        try {
            // Update peminjaman dengan status "Dikembalikan"
            $this->peminjamanModel->update($id, [
                'status' => 'Dikembalikan',
                'kondisi_hp' => $kondisi,
                'tanggal_kembali' => date('Y-m-d'),
                'denda' => $denda
            ]);

            // Simpan pembayaran menggunakan database builder langsung
            $db = \Config\Database::connect();
            $dataPembayaran = [
                'id_peminjaman' => $id,
                'id_user' => session()->get('id_user'),
                'waktu' => date('Y-m-d H:i:s'),
                'harga' => $hargaPerHari,
                'metode_pembayaran' => $metodePembayaran,
                'tanggal_bayar' => date('Y-m-d'),
                'status' => 'Lunas',
                'subtotal' => $totalPembayaran
            ];
            
            $db->table('pembayaran')->insert($dataPembayaran);

            // Update status alat menjadi Tersedia
            $alatModel->update($peminjaman['id_hp'], [
                'status' => 'Tersedia'
            ]);

            // Log aktivitas
            logAktivitas(
                'Pengembalian HP selesai dengan pembayaran: Biaya sewa Rp ' . number_format($biayaSewa, 0, ',', '.') . 
                ' (' . $durasi . ' hari)' .
                ($denda > 0 ? ' + Denda Rp ' . number_format($denda, 0, ',', '.') : '') .
                ' = Total Rp ' . number_format($totalPembayaran, 0, ',', '.') .
                ' via ' . $metodePembayaran
            );

            // Set session untuk detail pembayaran
            session()->setFlashdata('payment_details', [
                'biaya_sewa' => $biayaSewa,
                'durasi' => $durasi,
                'denda' => $denda,
                'total' => $totalPembayaran,
                'metode' => $metodePembayaran
            ]);

            return redirect()->to('/peminjam/peminjaman')->with('success', 'Pengembalian berhasil diselesaikan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function formKembalikan($id)
    {
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp')
            ->find($id);

        if (!$peminjaman || $peminjaman['id_user'] != session()->get('id_user')) {
            return redirect()->to('/peminjam/peminjaman')->with('error', 'Data tidak ditemukan');
        }

        // Cek status dengan case-insensitive dan berbagai kemungkinan
        $status = strtolower(trim($peminjaman['status']));
        $allowedStatus = ['disetujui', 'dipinjam'];
        
        if (!in_array($status, $allowedStatus)) {
            return redirect()->to('/peminjam/peminjaman')->with('error', 'HP ini tidak dapat dikembalikan. Status saat ini: ' . $peminjaman['status']);
        }

        // Pastikan durasi ada, jika tidak set default 1
        if (!isset($peminjaman['durasi']) || $peminjaman['durasi'] < 1) {
            $peminjaman['durasi'] = 1;
        }

        return view('peminjam/peminjaman/kembalikan', [
            'peminjaman' => $peminjaman
        ]);
    }

    public function ajukanPengembalian($idPeminjaman)
    {
        $peminjamanModel = new \App\Models\PeminjamanModel();

        // UBAH STATUS SAJA, DATA TETAP ADA
        $peminjamanModel->update($idPeminjaman, [
            'status' => 'dipinjam'
        ]);

        return redirect()->back()->with('success', 'Pengembalian diajukan');
    }
}