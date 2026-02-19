<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use App\Models\PeminjamanModel; // Model peminjaman
use App\Models\PembayaranModel; // Model pembayaran
use App\Models\AlatModel; // Model alat

/**
 * Controller untuk mengelola pembayaran
 * Menangani pembayaran denda dan biaya sewa untuk Peminjam
 */
class PembayaranController extends BaseController
{
    // Property untuk menyimpan instance model
    protected $peminjamanModel; // Instance PeminjamanModel
    protected $pembayaranModel; // Instance PembayaranModel
    protected $alatModel; // Instance AlatModel

    /**
     * Constructor - dipanggil saat class diinstansiasi
     * Menginisialisasi model yang dibutuhkan
     */
    public function __construct()
    {
        // Buat instance PeminjamanModel
        $this->peminjamanModel = new PeminjamanModel();
        
        // Buat instance PembayaranModel
        $this->pembayaranModel = new PembayaranModel();
        
        // Buat instance AlatModel
        $this->alatModel = new AlatModel();
    }

    /**
     * Method untuk menampilkan halaman pembayaran untuk peminjam
     * Menampilkan peminjaman yang perlu dibayar dan riwayat pembayaran
     */
    public function index()
    {
        // Validasi: Cek apakah user adalah Peminjam
        if (session()->get('role') != 'Peminjam') {
            // Jika bukan peminjam, redirect ke login
            return redirect()->to('/login');
        }

        // Ambil ID user yang sedang login dari session
        $idUser = session()->get('id_user');

        // Ambil peminjaman yang perlu dibayar
        // Kriteria: status "Menunggu Pengembalian" dan denda > 0
        $peminjamanPerluBayar = $this->peminjamanModel
            ->where('id_user', $idUser) // Filter peminjaman milik user
            ->where('status', 'Menunggu Pengembalian') // Filter status
            ->where('denda >', 0) // Filter yang ada dendanya
            ->findAll(); // Ambil semua data

        // Tambahkan data alat untuk setiap peminjaman
        foreach ($peminjamanPerluBayar as &$p) { // & untuk reference agar bisa diubah
            // Ambil data alat dengan query langsung
            $alatData = $this->alatModel->db->table('alat')
                ->select('merk, tipe, harga') // Pilih kolom yang dibutuhkan
                ->where('id_hp', $p['id_hp']) // Filter berdasarkan id_hp
                ->get() // Eksekusi query
                ->getRowArray(); // Ambil 1 baris sebagai array
            
            // Jika data alat ditemukan, tambahkan ke array peminjaman
            if ($alatData) {
                $p['merk'] = $alatData['merk']; // Tambahkan merk HP
                $p['tipe'] = $alatData['tipe']; // Tambahkan tipe HP
                $p['harga'] = $alatData['harga']; // Tambahkan harga HP
            }

            // Cek apakah sudah ada pembayaran untuk peminjaman ini
            $pembayaran = $this->pembayaranModel->getByPeminjaman($p['id_peminjaman']);
            $p['sudah_bayar'] = $pembayaran ? true : false; // Set flag sudah bayar
            $p['status_bayar'] = $pembayaran['status'] ?? null; // Set status pembayaran
        }

        // Ambil riwayat pembayaran user
        $riwayatPembayaran = $this->pembayaranModel->getByUser($idUser);

        // Render view dengan data yang sudah disiapkan
        return view('peminjam/pembayaran/index', [
            'peminjamanPerluBayar' => $peminjamanPerluBayar, // Peminjaman yang perlu dibayar
            'riwayatPembayaran' => $riwayatPembayaran // Riwayat pembayaran
        ]);
    }

    /**
     * Method untuk menampilkan form pembayaran untuk peminjaman tertentu
     * 
     * @param int $idPeminjaman ID peminjaman yang akan dibayar
     */
    public function bayar($idPeminjaman)
    {
        // Validasi: Cek apakah user adalah Peminjam
        if (session()->get('role') != 'Peminjam') {
            // Jika bukan peminjam, redirect ke login
            return redirect()->to('/login');
        }

        // Ambil ID user yang sedang login dari session
        $idUser = session()->get('id_user');

        // Ambil data peminjaman berdasarkan ID
        $peminjaman = $this->peminjamanModel->find($idPeminjaman);
        
        // Validasi: Cek apakah peminjaman ditemukan dan milik user yang login
        if (!$peminjaman || $peminjaman['id_user'] != $idUser) {
            // Jika tidak valid, redirect dengan pesan error
            return redirect()->to('/peminjam/pembayaran')->with('error', 'Data peminjaman tidak ditemukan');
        }

        // Validasi: Cek apakah peminjaman perlu dibayar
        if ($peminjaman['status'] != 'Menunggu Pengembalian' || $peminjaman['denda'] <= 0) {
            // Jika tidak perlu dibayar, redirect dengan pesan error
            return redirect()->to('/peminjam/pembayaran')->with('error', 'Peminjaman ini tidak memerlukan pembayaran denda');
        }

        // Cek apakah sudah ada pembayaran untuk peminjaman ini
        $pembayaran = $this->pembayaranModel->getByPeminjaman($idPeminjaman);
        if ($pembayaran) {
            // Jika sudah ada pembayaran, redirect dengan pesan error
            return redirect()->to('/peminjam/pembayaran')->with('error', 'Pembayaran untuk peminjaman ini sudah dilakukan');
        }

        // Ambil data alat untuk ditampilkan di form
        $alatData = $this->alatModel->db->table('alat')
            ->select('merk, tipe, harga') // Pilih kolom yang dibutuhkan
            ->where('id_hp', $peminjaman['id_hp']) // Filter berdasarkan id_hp
            ->get() // Eksekusi query
            ->getRowArray(); // Ambil 1 baris sebagai array
        
        // Jika data alat ditemukan, tambahkan ke array peminjaman
        if ($alatData) {
            $peminjaman['merk'] = $alatData['merk']; // Tambahkan merk HP
            $peminjaman['tipe'] = $alatData['tipe']; // Tambahkan tipe HP
            $peminjaman['harga'] = $alatData['harga']; // Tambahkan harga HP
        }

        // Render view form pembayaran dengan data peminjaman
        return view('peminjam/pembayaran/bayar', [
            'peminjaman' => $peminjaman
        ]);
    }

    /**
     * Method untuk memproses pembayaran
     * Menyimpan data pembayaran dan mengupdate status peminjaman
     * 
     * @param int $idPeminjaman ID peminjaman yang dibayar
     */
    public function proses($idPeminjaman)
    {
        // Validasi: Cek apakah user adalah Peminjam
        if (session()->get('role') != 'Peminjam') {
            // Jika bukan peminjam, redirect ke login
            return redirect()->to('/login');
        }

        // Ambil ID user yang sedang login dari session
        $idUser = session()->get('id_user');
        
        // Ambil metode pembayaran dari form POST
        $metodePembayaran = $this->request->getPost('metode_bayar');

        // Validasi: Cek apakah metode pembayaran diisi
        if (empty($metodePembayaran)) {
            // Jika kosong, redirect dengan pesan error
            return redirect()->back()->with('error', 'Metode pembayaran harus dipilih');
        }

        // Ambil data peminjaman berdasarkan ID
        $peminjaman = $this->peminjamanModel->find($idPeminjaman);
        
        // Validasi: Cek apakah peminjaman ditemukan dan milik user yang login
        if (!$peminjaman || $peminjaman['id_user'] != $idUser) {
            // Jika tidak valid, redirect dengan pesan error
            return redirect()->to('/peminjam/pembayaran')->with('error', 'Data peminjaman tidak ditemukan');
        }

        // Validasi: Cek apakah peminjaman perlu dibayar
        if ($peminjaman['status'] != 'Menunggu Pengembalian' || $peminjaman['denda'] <= 0) {
            // Jika tidak perlu dibayar, redirect dengan pesan error
            return redirect()->to('/peminjam/pembayaran')->with('error', 'Peminjaman ini tidak memerlukan pembayaran denda');
        }

        // Cek apakah sudah ada pembayaran untuk peminjaman ini
        $pembayaranExist = $this->pembayaranModel->getByPeminjaman($idPeminjaman);
        if ($pembayaranExist) {
            // Jika sudah ada pembayaran, redirect dengan pesan error
            return redirect()->to('/peminjam/pembayaran')->with('error', 'Pembayaran untuk peminjaman ini sudah dilakukan');
        }

        try {
            // Ambil data alat untuk mendapatkan harga
            $alatData = $this->alatModel->db->table('alat')
                ->select('harga') // Pilih kolom harga
                ->where('id_hp', $peminjaman['id_hp']) // Filter berdasarkan id_hp
                ->get() // Eksekusi query
                ->getRowArray(); // Ambil 1 baris sebagai array

            // Siapkan data pembayaran sesuai struktur database
            $dataPembayaran = [
                'id_peminjaman' => $idPeminjaman, // ID peminjaman yang dibayar
                'id_user' => $idUser, // ID user yang membayar
                'waktu' => date('Y-m-d H:i:s'), // Waktu pembayaran (sekarang)
                'harga' => $alatData['harga'] ?? 0, // Harga sewa per hari
                'metode_pembayaran' => $metodePembayaran, // Metode pembayaran (Tunai)
                'tanggal_bayar' => date('Y-m-d'), // Tanggal pembayaran (hari ini)
                'status' => 'Lunas', // Status pembayaran
                'subtotal' => $peminjaman['denda'] // Total yang dibayar (denda)
            ];

            // Insert data pembayaran ke database
            $this->pembayaranModel->insert($dataPembayaran);

            // Update status peminjaman menjadi "Dikembalikan"
            $this->peminjamanModel->update($idPeminjaman, [
                'status' => 'Dikembalikan' // Ubah status
            ]);

            // Update status alat menjadi "Tersedia" agar bisa dipinjam lagi
            $this->alatModel->update($peminjaman['id_hp'], [
                'status' => 'Tersedia' // Ubah status HP
            ]);

            // Catat aktivitas ke log sistem
            logAktivitas(
                'Pembayaran denda berhasil: Rp ' . number_format($peminjaman['denda'], 0, ',', '.') . 
                ' via ' . $metodePembayaran . ' untuk peminjaman ID ' . $idPeminjaman
            );

            // Redirect ke halaman pembayaran dengan pesan sukses
            return redirect()->to('/peminjam/pembayaran')->with('success', 
                'Pembayaran denda sebesar Rp ' . number_format($peminjaman['denda'], 0, ',', '.') . 
                ' berhasil dikonfirmasi. Pengembalian telah diselesaikan.'
            );

        } catch (\Exception $e) {
            // Jika terjadi error, redirect dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk menampilkan detail pembayaran
     * 
     * @param int $idPembayaran ID pembayaran yang akan ditampilkan
     */
    public function detail($idPembayaran)
    {
        // Validasi: Cek apakah user adalah Peminjam
        if (session()->get('role') != 'Peminjam') {
            // Jika bukan peminjam, redirect ke login
            return redirect()->to('/login');
        }

        // Ambil ID user yang sedang login dari session
        $idUser = session()->get('id_user');

        // Ambil data pembayaran dengan JOIN ke tabel peminjaman dan alat
        $pembayaran = $this->pembayaranModel
            ->select('pembayaran.*, peminjaman.waktu as waktu_pinjam, peminjaman.kondisi_hp, alat.merk, alat.tipe, alat.harga as harga_alat') // Pilih kolom yang dibutuhkan
            ->join('peminjaman', 'peminjaman.id_peminjaman = pembayaran.id_peminjaman') // JOIN dengan tabel peminjaman
            ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
            ->where('pembayaran.id_pembayaran', $idPembayaran) // Filter berdasarkan ID pembayaran
            ->where('pembayaran.id_user', $idUser) // Filter hanya pembayaran milik user
            ->first(); // Ambil 1 data pertama

        // Validasi: Cek apakah data pembayaran ditemukan
        if (!$pembayaran) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->to('/peminjam/pembayaran')->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Render view detail pembayaran dengan data yang sudah diambil
        return view('peminjam/pembayaran/detail', [
            'pembayaran' => $pembayaran
        ]);
    }
}
