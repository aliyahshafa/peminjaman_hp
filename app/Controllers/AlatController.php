<?php

// Deklarasi namespace untuk mengorganisir class dalam struktur folder
namespace App\Controllers;

// Import class-class yang dibutuhkan
use App\Models\AlatModel; // Model untuk mengelola data alat/HP
use App\Models\CategoryModel; // Model untuk mengelola data kategori
use CodeIgniter\HTTP\ResponseInterface; // Interface untuk response HTTP
use App\Controllers\BaseController; // Controller dasar CodeIgniter

/**
 * Controller untuk mengelola data alat/HP
 * Menangani CRUD (Create, Read, Update, Delete) alat untuk Admin, Petugas, dan Peminjam
 */
class AlatController extends BaseController
{
    // Property untuk menyimpan instance AlatModel
    protected $alatModel;

    /**
     * Constructor - dipanggil saat class diinstansiasi
     * Menginisialisasi model yang dibutuhkan
     */
    public function __construct()
    {
        // Membuat instance baru dari AlatModel untuk digunakan di seluruh method
        $this->alatModel = new AlatModel();
    }

    /**
     * Method untuk menampilkan daftar alat/HP
     * Menampilkan view berbeda berdasarkan role user (Admin/Petugas/Peminjam)
     * Mendukung fitur pencarian dan filter kategori
     */
    public function index()
    {
        try {
            // Buat instance model yang dibutuhkan
            $alatModel = new \App\Models\AlatModel(); // Model untuk data alat
            $categoryModel = new CategoryModel(); // Model untuk data kategori
            
            // Ambil role user yang sedang login dari session
            $role = session()->get('role');

            // =========================
            // TAMPILAN UNTUK PEMINJAM
            // =========================
            if ($role === 'Peminjam') {
                // Ambil keyword pencarian dari URL query string (GET)
                $keyword = $this->request->getGet('keyword');
                
                // Query langsung ke database untuk memastikan harga muncul
                // Menggunakan database builder untuk menghindari masalah soft delete
                $builder = $alatModel->db->table('alat')
                    ->select('alat.*, category.nama_category') // Pilih semua kolom alat + nama kategori
                    ->join('category', 'category.id_category = alat.id_category', 'left') // LEFT JOIN dengan tabel category
                    ->where('alat.status', 'Tersedia'); // Filter hanya HP yang tersedia
                
                // Jika ada keyword pencarian, tambahkan filter LIKE
                if ($keyword) {
                    $builder->like('alat.tipe', $keyword); // Cari berdasarkan tipe HP
                }
                
                // Siapkan data untuk dikirim ke view
                $data = [
                    'alat' => $builder->get()->getResultArray(), // Eksekusi query dan ambil hasil sebagai array
                    'keyword' => $keyword, // Keyword untuk ditampilkan di form pencarian
                    'category' => $categoryModel->findAll() // Ambil semua kategori untuk filter
                ];
                
                // Render view untuk peminjam
                return view('peminjam/alat/index', $data);
            }

            // =========================
            // TAMPILAN UNTUK ADMIN & PETUGAS
            // =========================
            
            // Ambil parameter pencarian dan filter dari URL
            $keyword  = $this->request->getGet('keyword'); // Keyword pencarian
            $category = $this->request->getGet('category'); // Filter kategori

            // Query langsung ke database untuk memastikan harga muncul
            $builder = $alatModel->db->table('alat')
                ->select('alat.*, category.nama_category') // Pilih semua kolom alat + nama kategori
                ->join('category', 'category.id_category = alat.id_category', 'left'); // LEFT JOIN dengan tabel category
            
            // Jika ada keyword pencarian, tambahkan filter LIKE
            if ($keyword) {
                $builder->like('alat.tipe', $keyword); // Cari berdasarkan tipe HP
            }
            
            // Jika ada filter kategori, tambahkan filter WHERE
            if ($category) {
                $builder->where('alat.id_category', $category); // Filter berdasarkan ID kategori
            }

            // Siapkan data untuk dikirim ke view
            $data = [
                'alat'       => $builder->get()->getResultArray(), // Eksekusi query dan ambil hasil
                'category'   => $categoryModel->findAll(), // Ambil semua kategori untuk dropdown filter
                'keyword'    => $keyword, // Keyword untuk form pencarian
                'catFilter'  => $category, // Kategori terpilih untuk dropdown filter
            ];

            // =========================
            // TAMPILAN UNTUK PETUGAS
            // =========================
            if ($role === 'Petugas') {
                // Render view untuk petugas
                return view('petugas/alat/index', $data);
            }

            // =========================
            // TAMPILAN UNTUK ADMIN (DEFAULT)
            // =========================
            // Render view untuk admin
            return view('admin/alat/index', $data);
            
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan pesan error untuk debugging
            // Menampilkan pesan error, file, dan line number
            die('Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine());
        }
    }

    /**
     * Method untuk menyimpan data alat/HP baru ke database
     * Hanya bisa diakses oleh Admin
     */
    public function store()
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect kembali dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Insert data alat baru ke database menggunakan Model
        $this->alatModel->insert([
            'id_category' => $this->request->getPost('id_category'), // ID kategori dari form
            'merk'        => $this->request->getPost('merk'), // Merk HP dari form
            'tipe'        => $this->request->getPost('tipe'), // Tipe HP dari form
            'harga'       => $this->request->getPost('harga'), // Harga sewa per hari dari form
            'kondisi'     => $this->request->getPost('kondisi'), // Kondisi HP dari form
            'status'      => $this->request->getPost('status') // Status HP (Tersedia/Dipinjam) dari radio button
        ]);

        // Catat aktivitas ke log sistem
        logAktivitas('Admin menambahkan data handphone: ' .
        $this->request->getPost('merk')); // Log dengan merk HP yang ditambahkan

        // Redirect ke halaman daftar alat dengan pesan sukses
        return redirect()->to('admin/alat')->with('success', 'Data handphone berhasil ditambahkan');
    }

    /**
     * Method untuk menampilkan form edit alat/HP
     * Hanya bisa diakses oleh Admin
     * 
     * @param int $id ID alat yang akan diedit
     */
    public function edit($id)
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect kembali
            return redirect()->back();
        }

        // Buat instance AlatModel
        $alatModel = new \App\Models\AlatModel();

        // Buat instance CategoryModel untuk dropdown kategori
        $categoryModel = new CategoryModel();

        // Ambil data alat berdasarkan ID
        $alat = $this->alatModel->find($id);

        // Validasi: Cek apakah data alat ditemukan
        if (!$alat) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'Data handphone tidak ditemukan');
        }

        // Ambil semua data kategori untuk dropdown
        $category = $categoryModel->findAll();

        // Ambil daftar enum status dari struktur tabel database
        $db = \Config\Database::connect(); // Buat koneksi database
        $query = $db->query("SHOW COLUMNS FROM alat WHERE Field = 'status'"); // Query untuk melihat struktur kolom status
        $row = $query->getRow(); // Ambil hasil query

        // Parse enum values dari tipe kolom menggunakan regex
        preg_match("/^enum\((.*)\)$/", $row->Type, $matches); // Extract nilai enum
        $statusList = array_map(
            fn($v) => trim($v, "'"), // Hapus tanda petik dari setiap nilai
            explode(',', $matches[1]) // Pisahkan nilai berdasarkan koma
        );

        // Render view form edit dengan data yang sudah disiapkan
        return view('admin/alat/edit', [
            'alat'       => $alat, // Data alat yang akan diedit
            'category'   => $category, // Daftar kategori untuk dropdown
            'statusList' => $statusList // Daftar status untuk dropdown
        ]);
    }

    /**
     * Method untuk memproses update data alat/HP
     * Hanya bisa diakses oleh Admin
     * 
     * @param int $id ID alat yang akan diupdate
     */
    public function update($id)
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Ambil data alat lama untuk keperluan log
        $alat = $this->alatModel->find($id);

        // Validasi: Cek apakah data alat ditemukan
        if (!$alat) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'Data handphone tidak ditemukan');
        }

        // Update data alat di database
        $this->alatModel->update($id, [
            'id_category' => $this->request->getPost('id_category'), // Update kategori dari form
            'merk'        => $this->request->getPost('merk'), // Update merk dari form
            'tipe'        => $this->request->getPost('tipe'), // Update tipe dari form
            'harga'       => $this->request->getPost('harga'), // Update harga dari form
            'kondisi'     => $this->request->getPost('kondisi'), // Update kondisi dari form
            'status'      => $this->request->getPost('status') // Update status dari form
        ]);

        // Catat aktivitas ke log sistem dengan data alat lama
        logAktivitas(
            'Admin mengedit data handphone: ' .
            $alat['merk'] . ' ' . $alat['tipe'] // Log dengan merk dan tipe HP
        );

        // Redirect ke halaman daftar alat dengan pesan sukses
        return redirect()->to('/admin/alat')->with('success', 'Data handphone berhasil diperbarui');
    }

    /**
     * Method untuk menghapus data alat/HP
     * Data akan dibackup ke tabel trash sebelum dihapus (jika tabel trash ada)
     * Hanya bisa diakses oleh Admin
     * 
     * @param int $id ID alat yang akan dihapus
     */
    public function delete($id)
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Ambil data alat sebelum dihapus untuk keperluan log dan backup
        $alat = $this->alatModel->find($id);
        
        // Validasi: Cek apakah data HP ditemukan
        if (!$alat) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'Data HP tidak ditemukan');
        }

        // Cek apakah tabel trash sudah ada di database
        $db = \Config\Database::connect(); // Buat koneksi database
        $tableExists = $db->tableExists('trash'); // Cek keberadaan tabel trash

        // Jika tabel trash ada, lakukan backup sebelum hapus
        if ($tableExists) {
            // Backup ke trash sebelum hapus
            try {
                // Buat instance TrashModel untuk backup
                $trashModel = new \App\Models\TrashModel();
                
                // Backup data ke tabel trash
                $backupResult = $trashModel->backupData('alat', $id, $alat, 'Dihapus oleh admin');
                
                // Cek hasil backup
                if ($backupResult) {
                    // Jika backup berhasil, catat ke log
                    logAktivitas('Admin memindahkan HP ke trash: ' . $alat['merk'] . ' ' . $alat['tipe']);
                    $message = 'HP berhasil dipindahkan ke trash'; // Pesan sukses
                } else {
                    // Jika backup gagal, tetap hapus tapi beri peringatan
                    logAktivitas('Admin menghapus HP (backup gagal): ' . $alat['merk'] . ' ' . $alat['tipe']);
                    $message = 'HP berhasil dihapus (backup ke trash gagal)'; // Pesan warning
                }
            } catch (\Exception $e) {
                // Jika ada error saat backup, tangkap exception
                // Tetap hapus tapi log error yang terjadi
                logAktivitas('Admin menghapus HP (error backup): ' . $alat['merk'] . ' ' . $alat['tipe'] . ' - Error: ' . $e->getMessage());
                $message = 'HP berhasil dihapus (error backup: ' . $e->getMessage() . ')'; // Pesan error
            }
        } else {
            // Jika tabel trash belum dibuat, langsung hapus tanpa backup
            logAktivitas('Admin menghapus HP: ' . $alat['merk'] . ' ' . $alat['tipe']);
            $message = 'HP berhasil dihapus (Tabel trash belum dibuat)'; // Pesan info
        }

        // Hapus data dari tabel utama (alat)
        $this->alatModel->delete($id);

        // Redirect kembali dengan pesan sesuai hasil proses
        return redirect()->back()->with('success', $message);
    }

    /**
     * Method untuk menampilkan form tambah alat/HP baru
     * Hanya bisa diakses oleh Admin
     */
    public function create()
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Buat instance CategoryModel untuk dropdown kategori
        $categoryModel = new \App\Models\CategoryModel();

        // Ambil semua data kategori untuk dropdown di form
        $data['category'] = $categoryModel->findAll();
        
        // Render view form tambah alat dengan data kategori
        return view('admin/alat/create', $data);
    }

}
