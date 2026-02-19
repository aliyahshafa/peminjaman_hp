<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use CodeIgniter\HTTP\ResponseInterface; // Interface HTTP response

/**
 * Controller untuk dashboard Petugas
 * Menampilkan statistik dan peminjaman yang menunggu persetujuan
 */
class PetugasController extends BaseController
{
    /**
     * Method untuk menampilkan dashboard petugas
     * Menampilkan statistik alat, peminjaman, dan daftar peminjaman menunggu
     */
    public function index()
    {
        // Validasi: Cek apakah user yang login adalah Petugas
        if (session()->get('role') != 'Petugas') {
            // Jika bukan petugas, redirect ke halaman login
            return redirect()->to('/login');
        }

        // Buat instance model yang dibutuhkan
        $alatModel = new \App\Models\AlatModel(); // Model untuk data alat
        $peminjamanModel = new \App\Models\PeminjamanModel(); // Model untuk data peminjaman

        // Hitung total alat/HP di database
        $data['totalAlat'] = $alatModel->countAll();
        
        // Hitung peminjaman dengan status "Diajukan" (menunggu persetujuan)
        // countAllResults(false) = hitung tanpa reset query builder
        $data['peminjamanMenunggu'] = $peminjamanModel->where('status', 'Diajukan')->countAllResults(false);
        
        // Hitung peminjaman dengan status "Disetujui"
        $data['peminjamanDisetujui'] = $peminjamanModel->where('status', 'Disetujui')->countAllResults(false);
        
        // Hitung total pengembalian (status "Dikembalikan")
        $data['totalPengembalian'] = $peminjamanModel->where('status', 'Dikembalikan')->countAllResults();

        // Ambil 5 peminjaman terbaru yang statusnya "Diajukan"
        $peminjamanMenunggu = $peminjamanModel
            ->where('peminjaman.status', 'Diajukan') // Filter status Diajukan
            ->orderBy('peminjaman.id_peminjaman', 'DESC') // Urutkan dari terbaru
            ->limit(5) // Batasi 5 data
            ->findAll(); // Ambil semua data
        
        // Tambahkan data alat dan user secara manual untuk setiap peminjaman
        $userModel = new \App\Models\UserModel(); // Buat instance UserModel
        
        // Loop setiap peminjaman (& untuk reference agar bisa diubah)
        foreach ($peminjamanMenunggu as &$p) {
            // Ambil data alat dengan query langsung ke database
            // Menggunakan database builder untuk memastikan harga muncul
            $alatData = $alatModel->db->table('alat')
                ->select('merk, tipe, harga') // Pilih kolom merk, tipe, dan harga
                ->where('id_hp', $p['id_hp']) // Filter berdasarkan id_hp dari peminjaman
                ->get() // Eksekusi query
                ->getRowArray(); // Ambil 1 baris sebagai array
            
            // Jika data alat ditemukan, tambahkan ke array peminjaman
            if ($alatData) {
                $p['merk'] = $alatData['merk']; // Tambahkan merk HP
                $p['tipe'] = $alatData['tipe']; // Tambahkan tipe HP
                $p['harga'] = $alatData['harga']; // Tambahkan harga HP
            }
            
            // Ambil data user berdasarkan id_user dari peminjaman
            $user = $userModel->find($p['id_user']);
            
            // Jika user ditemukan, tambahkan nama user ke array peminjaman
            if ($user) {
                $p['nama_user'] = $user['nama_user'];
            }
        }
        
        // Simpan data peminjaman yang sudah dilengkapi dengan data alat dan user
        $data['peminjamanMenungguList'] = $peminjamanMenunggu;

        // Render view dashboard petugas dengan data yang sudah disiapkan
        return view('dashboard/petugas', $data);
    }
}

