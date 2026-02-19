<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use App\Models\PeminjamanModel; // Model peminjaman
use App\Models\AlatModel; // Model alat
use CodeIgniter\HTTP\ResponseInterface; // Interface HTTP response

/**
 * Controller untuk mengelola peminjaman oleh Petugas
 * Menangani verifikasi, persetujuan, dan update peminjaman
 */
class PetugasPeminjamanController extends BaseController
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
        $this->peminjamanModel = new PeminjamanModel();
        
        // Buat instance AlatModel
        $this->alatModel       = new AlatModel();
    }

    /**
     * Method untuk menampilkan daftar peminjaman yang perlu diverifikasi
     * Hanya bisa diakses oleh Petugas
     */
    public function index()
    {
        // Validasi: Cek apakah user adalah Petugas
        if (session()->get('role') !== 'Petugas') {
            // Jika bukan petugas, redirect kembali
            return redirect()->back();
        }

        // Ambil data peminjaman yang perlu diverifikasi menggunakan method dari model
        $data['peminjaman'] = 
        $this->peminjamanModel->getVerifikasiPetugas();

        // Render view daftar peminjaman untuk petugas
        return view('petugas/peminjaman/index', $data);
    }

    /**
     * Method untuk menampilkan detail peminjaman
     * Menampilkan data lengkap user, HP, dan peminjaman
     * 
     * @param int $id ID peminjaman yang akan ditampilkan
     */
    public function detail($id)
    {
        // Validasi: Cek apakah user adalah Petugas
        if (session()->get('role') !== 'Petugas') {
            // Jika bukan petugas, redirect kembali
            return redirect()->back();
        }

        try {
            // Query lengkap dengan JOIN ke tabel user, alat, dan category
            $db = \Config\Database::connect(); // Buat koneksi database
            
            // Query dengan multiple JOIN untuk mendapatkan semua data yang dibutuhkan
            $peminjaman = $db->table('peminjaman')
                ->select('peminjaman.*, 
                          user.nama_user, user.username, user.email, user.no_hp, user.alamat, user.role,
                          alat.merk, alat.tipe, alat.harga, alat.kondisi, alat.status as status_hp,
                          category.nama_category') // Pilih kolom dari semua tabel
                ->join('user', 'user.id_user = peminjaman.id_user', 'left') // LEFT JOIN dengan tabel user
                ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left') // LEFT JOIN dengan tabel alat
                ->join('category', 'category.id_category = alat.id_category', 'left') // LEFT JOIN dengan tabel category
                ->where('peminjaman.id_peminjaman', $id) // Filter berdasarkan ID peminjaman
                ->get() // Eksekusi query
                ->getRowArray(); // Ambil 1 baris sebagai array

            // Validasi: Cek apakah data peminjaman ditemukan
            if (!$peminjaman) {
                // Jika tidak ditemukan, redirect dengan pesan error
                return redirect()->to('/petugas/peminjaman')->with('error', 'Data peminjaman tidak ditemukan');
            }

            // Render view detail peminjaman dengan data yang sudah diambil
            return view('petugas/peminjaman/detail', [
                'peminjaman' => $peminjaman
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan pesan error untuk debugging
            die('Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine());
        }
    }

    /**
     * Method untuk menyetujui peminjaman
     * Mengubah status peminjaman dan HP menjadi "Dipinjam"
     * 
     * @param int $id ID peminjaman yang akan disetujui
     */
    public function setujui($id)
    {
        // Buat instance model yang dibutuhkan
        $peminjamanModel = new PeminjamanModel();
        $alatModel = new AlatModel();

        // Ambil data peminjaman berdasarkan ID
        $peminjaman = $peminjamanModel->find($id);

        // Update status peminjaman menjadi "Dipinjam"
        $peminjamanModel->update($id, [
            'status' => 'Dipinjam' // Ubah status peminjaman
        ]);

        // Update status HP menjadi "Dipinjam" agar tidak bisa dipinjam lagi
        $alatModel->update($peminjaman['id_hp'], [
            'status' => 'Dipinjam' // Ubah status HP
        ]);

        // Catat aktivitas ke log sistem
        logAtivitas('Petugas menyetujui peminjaman ID:
        ' . $id);

        // Redirect ke halaman daftar peminjaman dengan pesan sukses
        return redirect()->to('/petugas/peminjaman')
            ->with('success', 'Peminjaman berhasil disetujui');
    }

    /**
     * Method untuk memproses update data peminjaman
     * Hanya bisa diakses oleh Petugas
     * 
     * @param int $id ID peminjaman yang akan diupdate
     */
    public function update($id)
    {
        // Validasi: Cek apakah user adalah Petugas
        if (session()->get('role') !== 'Petugas') {
            // Jika bukan petugas, redirect kembali
            return redirect()->back();
        }

        // Ambil data dari form POST
        $status = $this->request->getPost('status'); // Status peminjaman
        $kondisi_hp = $this->request->getPost('kondisi_hp'); // Kondisi HP
        $denda = $this->request->getPost('denda'); // Jumlah denda
        $waktu = $this->request->getPost('waktu'); // Waktu peminjaman
        $catatan = $this->request->getPost('catatan'); // Catatan tambahan

        // Update data peminjaman di database
        $this->peminjamanModel->update($id, [
            'status' => $status, // Update status
            'kondisi_hp' => $kondisi_hp, // Update kondisi HP
            'denda' => $denda, // Update denda
            'waktu' => $waktu, // Update waktu
            'catatan' => $catatan // Update catatan
        ]);

        // Catat aktivitas ke log sistem
        logAktivitas('Petugas mengupdate peminjaman HP');

        // Redirect ke halaman daftar peminjaman dengan pesan sukses
        return redirect()->to('petugas/peminjaman')
            ->with('success', 'Data peminjaman berhasil diupdate');
    }

    /**
     * Method untuk menampilkan form edit peminjaman
     * Hanya bisa diakses oleh Petugas
     * 
     * @param int $id ID peminjaman yang akan diedit
     */
    public function edit($id)
    {
        // Validasi: Cek apakah user adalah Petugas
        if (session()->get('role') !== 'Petugas') {
            // Jika bukan petugas, redirect kembali
            return redirect()->back();
        }

        // Ambil data peminjaman dengan JOIN ke tabel alat untuk mendapatkan merk dan tipe HP
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe') // Pilih kolom yang dibutuhkan
            ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
            ->find($id); // Cari berdasarkan ID

        // Validasi: Cek apakah data peminjaman ditemukan
        if (!$peminjaman) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->to('petugas/peminjaman')->with('error', 'Data tidak ditemukan');
        }

        // Render view form edit dengan data peminjaman
        return view('petugas/peminjaman/edit', [
            'peminjaman' => $peminjaman
        ]);
    }
}

