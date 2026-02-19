<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use App\Models\TrashModel; // Model trash
use App\Models\AlatModel; // Model alat
use App\Models\UserModel; // Model user
use App\Models\PeminjamanModel; // Model peminjaman

/**
 * Controller untuk mengelola trash/recycle bin
 * Menangani restore dan penghapusan permanen data yang sudah dihapus
 */
class TrashController extends BaseController
{
    // Property untuk menyimpan instance TrashModel
    protected $trashModel;

    /**
     * Constructor - dipanggil saat class diinstansiasi
     * Menginisialisasi model yang dibutuhkan
     */
    public function __construct()
    {
        // Buat instance TrashModel
        $this->trashModel = new TrashModel();
    }

    /**
     * Method untuk menampilkan daftar data di trash
     * Hanya bisa diakses oleh Admin
     */
    public function index()
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        try {
            // Ambil semua data trash, diurutkan dari yang terbaru dihapus
            $trash = $this->trashModel->orderBy('deleted_at', 'DESC')->findAll();

            // Siapkan informasi debug untuk troubleshooting
            $debugInfo = [
                'total_records' => count($trash), // Jumlah data di trash
                'table_exists' => \Config\Database::connect()->tableExists('trash'), // Cek apakah tabel trash ada
                'user_id' => session()->get('id_user') // ID user yang sedang login
            ];

            // Decode JSON data backup untuk setiap item di trash
            // Data backup disimpan dalam format JSON, perlu di-decode untuk ditampilkan
            foreach ($trash as &$item) { // & untuk reference agar bisa diubah
                // Decode JSON string menjadi array PHP
                $item['data_decoded'] = json_decode($item['data_backup'], true);
            }

            // Render view trash dengan data yang sudah disiapkan
            return view('admin/trash/index', [
                'trash' => $trash, // Data trash
                'debug' => $debugInfo // Informasi debug
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan view dengan data kosong dan pesan error
            return view('admin/trash/index', [
                'trash' => [], // Data kosong
                'error' => $e->getMessage() // Pesan error
            ]);
        }
    }

    /**
     * Method untuk restore data dari trash ke tabel aslinya
     * Hanya bisa diakses oleh Admin
     * 
     * @param int $id ID data di tabel trash yang akan di-restore
     */
    public function restore($id)
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Ambil data trash berdasarkan ID
        $trash = $this->trashModel->find($id);
        
        // Validasi: Cek apakah data trash ditemukan
        if (!$trash) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'Data trash tidak ditemukan');
        }

        // Decode JSON data backup menjadi array
        $data = json_decode($trash['data_backup'], true);
        
        // Ambil nama tabel asli dari data trash
        $tableName = $trash['table_name'];

        try {
            // Insert data kembali ke tabel aslinya
            $db = \Config\Database::connect(); // Buat koneksi database
            $result = $db->table($tableName)->insert($data); // Insert data ke tabel asli

            // Cek hasil insert
            if ($result) {
                // Jika berhasil, hapus data dari trash
                $this->trashModel->delete($id);

                // Catat aktivitas ke log sistem
                logAktivitas('Admin memulihkan data dari trash: ' . $tableName . ' ID: ' . $trash['record_id']);

                // Redirect kembali dengan pesan sukses
                return redirect()->back()->with('success', 'Data berhasil dipulihkan');
            } else {
                // Jika gagal insert, redirect dengan pesan error
                return redirect()->back()->with('error', 'Gagal memulihkan data');
            }
        } catch (\Exception $e) {
            // Jika terjadi error, redirect dengan pesan error
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk menghapus data secara permanen dari trash
     * Data yang dihapus tidak bisa dikembalikan lagi
     * Hanya bisa diakses oleh Admin
     * 
     * @param int $id ID data di tabel trash yang akan dihapus permanen
     */
    public function permanentDelete($id)
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Ambil data trash berdasarkan ID untuk keperluan log
        $trash = $this->trashModel->find($id);
        
        // Validasi: Cek apakah data trash ditemukan
        if (!$trash) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'Data trash tidak ditemukan');
        }

        // Hapus data dari trash secara permanen
        $this->trashModel->delete($id);

        // Catat aktivitas ke log sistem
        logAktivitas('Admin menghapus permanen data dari trash: ' . $trash['table_name'] . ' ID: ' . $trash['record_id']);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data berhasil dihapus permanen');
    }

    /**
     * Method untuk mengosongkan trash (menghapus semua data di trash)
     * Semua data di trash akan dihapus permanen
     * Hanya bisa diakses oleh Admin
     */
    public function clear()
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Hitung jumlah data di trash sebelum dihapus (untuk log)
        $count = $this->trashModel->countAllResults();
        
        // Kosongkan tabel trash (hapus semua data)
        $this->trashModel->emptyTable();

        // Catat aktivitas ke log sistem dengan jumlah data yang dihapus
        logAktivitas('Admin mengosongkan trash (' . $count . ' item dihapus permanen)');

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Trash berhasil dikosongkan');
    }
}
