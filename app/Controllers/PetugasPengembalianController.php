<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use App\Models\PeminjamanModel; // Model peminjaman
use App\Models\AlatModel; // Model alat

/**
 * Controller untuk mengelola pengembalian HP oleh Petugas
 * Menangani monitoring dan proses pengembalian HP
 */
class PetugasPengembalianController extends BaseController
{
    // Property untuk menyimpan instance model
    protected $peminjamanModel; // Instance PeminjamanModel
    protected $alatModel; // Instance AlatModel

    /**
     * Constructor - dipanggil saat class diinstansiasi
     * Menginisialisasi model yang dibutuhkan
     */
    public function __construct()
    {
        // Buat instance PeminjamanModel
        $this->peminjamanModel   = new PeminjamanModel();
        
        // Buat instance AlatModel
        $this->alatModel         = new AlatModel();
    }

    /**
     * Method untuk menampilkan data HP yang sedang dipinjam
     * Monitoring pengembalian untuk Petugas
     */
    public function index()
    {
        // Ambil data peminjaman yang perlu dimonitor pengembaliannya
        // Menggunakan method dari model
        $peminjaman = $this->peminjamanModel->getMonitoringPengembalian();

        // Render view monitoring pengembalian dengan data peminjaman
        return view('petugas/pengembalian/index', ['peminjaman'=> $peminjaman] );
    }

    /**
     * Method untuk memproses pengembalian HP
     * Menghitung denda berdasarkan kondisi HP dan update status
     */
    public function store()
    {
        // Ambil ID peminjaman dari form POST
        $idPeminjaman = $this->request->getPost('id_peminjaman');
        
        // Ambil ID HP dari form POST
        $idHp         = $this->request->getPost('id_hp');
        
        // Ambil kondisi HP dari form POST
        // Nilai: baik | rusak ringan | rusak berat
        $kondisi      = $this->request->getPost('kondisi'); 

        // Hitung denda berdasarkan kondisi HP
        // Logika denda ditentukan di aplikasi, bukan di database
        $denda = 0; // Default tidak ada denda

        // Jika kondisi rusak ringan, denda Rp 10.000
        if ($kondisi === 'rusak ringan') {
            $denda = 10000;
        } 
        // Jika kondisi rusak berat, denda Rp 20.000
        elseif ($kondisi === 'rusak berat') {
            $denda = 20000;
        }

        // Update data peminjaman dengan status "dikembalikan"
        $this->peminjamanModel->update($idPeminjaman, [
            'status'      => 'dikembalikan', // Ubah status menjadi dikembalikan (ENUM valid)
            'kondisi_hp'  => $kondisi,       // Simpan kondisi HP saat dikembalikan (ENUM valid)
            'denda'       => $denda          // Simpan jumlah denda
        ]);

        // Update status HP menjadi "tersedia" agar bisa dipinjam lagi
        $this->alatModel->update($idHp, [
            'status' => 'tersedia' // Ubah status HP menjadi tersedia
        ]);

        // Catat aktivitas ke log sistem
        logAktivitas('Petugas memproses pengembalian HP');

        // Redirect ke halaman monitoring pengembalian dengan pesan sukses
        return redirect()->to('/petugas/pengembalian')
                        ->with('success', 'Pengembalian berhasil diproses');
    }
}
