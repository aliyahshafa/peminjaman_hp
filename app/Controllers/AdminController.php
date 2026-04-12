<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use CodeIgniter\HTTP\ResponseInterface; // Interface HTTP response
use App\Models\PeminjamanModel; // Model peminjaman
use App\Models\AlatModel; // Model alat/HP
use App\Models\UserModel; // Model user

/**
 * Controller untuk dashboard Admin
 * Menampilkan statistik dan data terbaru untuk Admin
 */
class AdminController extends BaseController
{
    /**
     * Method untuk menampilkan dashboard admin
     * Menampilkan statistik dan data terbaru (user, alat, peminjaman)
     */
    public function index()
    {
        // Validasi: Cek apakah user yang login adalah Admin
        if (session()->get('role') != 'Admin') {
            // Jika bukan admin, redirect ke halaman login
            return redirect()->to('/login');
        }
        
        // Buat instance model yang dibutuhkan
        $userModel = new UserModel(); // Model untuk data user
        $alatModel = new AlatModel(); // Model untuk data alat
        $peminjamanModel = new PeminjamanModel(); // Model untuk data peminjaman
        
        // Hitung total user di database
        $data['totalUsers'] = $userModel->countAll();
        
        // Hitung total alat/HP di database
        $data['totalAlat'] = $alatModel->countAll();
        
        // Hitung total peminjaman di database
        $data['totalPeminjaman'] = $peminjamanModel->countAll();
        
        // Hitung peminjaman yang statusnya "Disetujui"
        $data['peminjamanAktif'] = $peminjamanModel->where('status', 'Disetujui')->countAllResults();
        
        // Ambil 5 user terbaru, diurutkan berdasarkan tanggal dibuat (DESC = terbaru dulu)
        $data['recentUsers'] = $userModel->orderBy('created_at', 'DESC')->limit(5)->findAll();
        
        // Ambil 5 alat terbaru menggunakan query langsung (untuk memastikan harga muncul)
        // Menggunakan database builder untuk menghindari masalah soft delete
        $recentAlatRaw = $alatModel->db->table('alat')
            ->select('*') // Pilih semua kolom
            ->where('deleted_at IS NULL') // Filter hanya data yang tidak dihapus (soft delete)
            ->orderBy('id_hp', 'DESC') // Urutkan dari ID terbesar (terbaru)
            ->limit(5) // Batasi 5 data
            ->get() // Eksekusi query
            ->getResultArray(); // Ambil hasil sebagai array
        
        // Tambahkan nama kategori secara manual untuk setiap alat
        $categoryModel = new \App\Models\CategoryModel(); // Buat instance CategoryModel
        foreach ($recentAlatRaw as &$alat) { // Loop setiap alat (& untuk reference agar bisa diubah)
            // Cek apakah alat punya id_category
            if (isset($alat['id_category'])) {
                // Cari data kategori berdasarkan id_category
                $category = $categoryModel->find($alat['id_category']);
                // Jika kategori ditemukan, ambil namanya, jika tidak set '-'
                $alat['nama_category'] = $category ? $category['nama_category'] : '-';
            } else {
                // Jika tidak ada id_category, set '-'
                $alat['nama_category'] = '-';
            }
        }
        
        // Simpan data alat yang sudah dilengkapi dengan nama kategori
        $data['recentAlat'] = $recentAlatRaw;
        
        // Ambil 5 peminjaman terbaru dengan JOIN ke tabel alat untuk mendapatkan merk dan tipe HP
        $data['recentPeminjaman'] = $peminjamanModel->select('peminjaman.*, alat.merk, alat.tipe') // Pilih kolom yang dibutuhkan
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left') // LEFT JOIN dengan tabel alat
            ->orderBy('peminjaman.created_at', 'DESC') // Urutkan dari terbaru
            ->limit(5) // Batasi 5 data
            ->findAll(); // Ambil semua data

        // Ambil history peminjaman yang sudah selesai (Dikembalikan) atau ditolak
        $data['historyPeminjaman'] = $peminjamanModel->db->table('peminjaman')
            ->select('peminjaman.*, alat.merk, alat.tipe')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left')
            ->whereIn('peminjaman.status', ['Dikembalikan', 'Ditolak'])
            ->where('peminjaman.deleted_at IS NULL')
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->get()
            ->getResultArray();
        
        // Catat aktivitas view dashboard ke log sistem
        logView('dashboard admin');
        
        // Render view dashboard admin dengan data yang sudah disiapkan
        return view('dashboard/admin', $data);
    }
    
}

