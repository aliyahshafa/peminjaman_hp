<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use App\Models\PeminjamanModel; // Model peminjaman
use App\Models\AlatModel; // Model alat

/**
 * Controller untuk mengelola peminjaman
 * Menangani CRUD peminjaman untuk Admin dan Peminjam
 */
class PeminjamanController extends BaseController
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
        $this->alatModel = new AlatModel();
    }

    /**
     * Method untuk menampilkan form peminjaman HP
     * Hanya bisa diakses oleh Peminjam
     * 
     * @param int $id_hp ID HP yang akan dipinjam
     */
    public function create($id_hp)
    {
        // Buat instance AlatModel
        $alatModel = new \App\Models\AlatModel();

        // Cari data HP berdasarkan ID
        $alat = $this->alatModel->find($id_hp);
        
        // Validasi: Cek apakah HP ditemukan
        if (!$alat) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->to('peminjam/alat')->with('error', 'HP tidak ditemukan');
        }

        // Ambil data HP yang dibutuhkan untuk form (id, merk, tipe, harga, kondisi, status)
        $data['alat'] = $alatModel->select('id_hp, merk, tipe, harga, kondisi, status')->find($id_hp);
        
        // Render view form peminjaman dengan data HP
        return view('peminjam/peminjaman/create', $data);
    }    


    /**
     * Method untuk menampilkan daftar peminjaman (untuk Admin)
     * Hanya bisa diakses oleh Admin
     */
    public function index()
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect ke halaman login
            return redirect()->to('/login');
        }

        // Ambil filter dari query string
        $status  = $this->request->getGet('status');
        $keyword = $this->request->getGet('keyword');

        // Query peminjaman aktif saja (Diajukan, Disetujui, Dipinjam, Menunggu Pengembalian)
        $db = \Config\Database::connect();
        $builder = $db->table('peminjaman')
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left')
            ->whereIn('peminjaman.status', ['Diajukan', 'Disetujui', 'Dipinjam', 'Menunggu Pengembalian'])
            ->where('peminjaman.deleted_at IS NULL')
            ->orderBy('peminjaman.id_peminjaman', 'DESC');

        if ($status) {
            $builder->where('peminjaman.status', $status);
        }
        if ($keyword) {
            $builder->like('peminjaman.nama_user', $keyword);
        }

        $data['peminjaman'] = $builder->get()->getResultArray();
        $data['status']     = $status;
        $data['keyword']    = $keyword;

        // Ambil daftar HP tersedia untuk form pinjam admin
        $data['alatTersedia'] = $db->table('alat')
            ->select('id_hp, merk, tipe, harga')
            ->where('status', 'Tersedia')
            ->where('deleted_at IS NULL')
            ->get()->getResultArray();

        // Ambil history peminjaman (Dikembalikan & Ditolak)
        $data['historyPeminjaman'] = $db->table('peminjaman')
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left')
            ->whereIn('peminjaman.status', ['Dikembalikan', 'Ditolak'])
            ->where('peminjaman.deleted_at IS NULL')
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->get()->getResultArray();

        // Render view daftar peminjaman untuk admin
        return view('admin/peminjaman/index', $data);
    }

    /**
     * Method untuk admin meminjam HP langsung
     * Hanya bisa diakses oleh Admin
     */
    public function adminPinjam()
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/login');
        }

        $id_hp     = $this->request->getPost('id_hp');
        $nama_user = $this->request->getPost('nama_user');
        $catatan   = $this->request->getPost('catatan') ?? '-';

        // Validasi HP tersedia
        $alat = $this->alatModel->find($id_hp);
        if (!$alat || strtolower($alat['status']) !== 'tersedia') {
            return redirect()->back()->with('error', 'HP tidak tersedia untuk dipinjam');
        }

        $db = \Config\Database::connect();
        $db->table('peminjaman')->insert([
            'id_user'         => session()->get('id_user'),
            'id_hp'           => $id_hp,
            'nama_user'       => $nama_user,
            'waktu'           => date('Y-m-d H:i:s'),
            'status'          => 'Disetujui',
            'tanggal_kembali' => null,
            'kondisi_hp'      => 'baik',
            'denda'           => 0,
            'catatan'         => $catatan,
        ]);

        $this->alatModel->update($id_hp, ['status' => 'dipinjam']);

        logAktivitas('Admin meminjamkan HP ' . $alat['merk'] . ' ' . $alat['tipe'] . ' kepada ' . $nama_user);

        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman berhasil dibuat');
    }

    /**
     * Method untuk menyimpan peminjaman baru
     * Hanya bisa diakses oleh Peminjam
     */
    public function store()
    {
        // Validasi: Cek apakah user adalah Peminjam
        if (session()->get('role') !== 'Peminjam') {
            // Jika bukan peminjam, redirect dengan pesan error
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        // Ambil ID user dari session
        $id_user = session()->get('id_user');
        
        // Validasi: Cek apakah session user valid
        if (!$id_user) {
            // Jika session tidak valid, redirect ke login
            return redirect()->to('/login')->with('error', 'Session user tidak valid');
        }

        // Ambil ID HP dari form POST
        $id_hp = $this->request->getPost('id_hp');

        // Ambil data HP berdasarkan ID
        $alat = $this->alatModel->find($id_hp);
        
        // Validasi: Cek apakah HP ditemukan
        if (!$alat) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'HP tidak ditemukan');
        }

        // Validasi: Cek status HP (harus "tersedia")
        // Menggunakan strtolower() untuk case-insensitive comparison
        if (strtolower($alat['status']) !== 'tersedia') {
            // Jika HP tidak tersedia, redirect dengan pesan error
            return redirect()->back()->with('error', 'HP tidak tersedia untuk dipinjam');
        }

        // Simpan peminjaman menggunakan database builder langsung
        // Untuk memastikan data tersimpan dengan benar
        $db = \Config\Database::connect(); // Buat koneksi database
        
        // Siapkan data yang akan diinsert
        $dataInsert = [
            'id_user'   => $id_user, // ID user yang meminjam
            'id_hp'     => $id_hp, // ID HP yang dipinjam
            'nama_user' => session()->get('nama_user'), // Nama user dari session
            'waktu'     => date('Y-m-d H:i:s'), // Waktu peminjaman (sekarang)
            'status'    => 'diajukan', // Status awal: diajukan
            'tanggal_kembali' => $this->request->getPost('tanggal_kembali') ?: null, // Tanggal kembali dari form
            'kondisi_hp'=> 'baik', // Kondisi HP awal: baik
            'denda'     => 0, // Denda awal: 0
            'catatan'   => $this->request->getPost('catatan') ?? '-', // Catatan dari form, jika kosong set '-'
        ];
        
        // Insert data peminjaman ke database
        $db->table('peminjaman')->insert($dataInsert);

        // Update status HP menjadi "dipinjam" agar tidak bisa dipinjam lagi
        $this->alatModel->update($id_hp, [
            'status' => 'dipinjam' // Ubah status HP
        ]);

        // Catat aktivitas ke log sistem
        logAktivitas(
            'Peminjam mengajukan peminjaman HP ' .
            $alat['merk'] . ' ' . $alat['tipe'] // Log dengan merk dan tipe HP
        );

        // Redirect ke halaman daftar peminjaman dengan pesan sukses
        return redirect()->to('/peminjam/peminjaman')
            ->with('success', 'Peminjaman berhasil diajukan');
    }

    /**
     * Method untuk memproses pengembalian HP
     * Menghitung denda berdasarkan kondisi HP
     * 
     * @param int $id ID peminjaman yang akan dikembalikan
     */
    public function kembalikan($id)
    {
        // Ambil kondisi HP dari form POST
        $kondisi = $this->request->getPost('kondisi_hp');

        // Hitung denda berdasarkan kondisi HP
        if ($kondisi == 'Baik') {
           $denda = 0; // Tidak ada denda jika kondisi baik
        } elseif ($kondisi == 'Rusak Ringan') {
            $denda = 10000; // Denda Rp 10.000 untuk rusak ringan
        } else {
            $denda = 20000; // Denda Rp 20.000 untuk rusak berat
        }

        // Update data peminjaman dengan status "dikembalikan"
        $this->peminjamanModel->update($id, [
            'status' => 'dikembalikan', // Ubah status menjadi dikembalikan
            'tanggal_kembali' => date('Y-m-d'), // Simpan tanggal pengembalian (hari ini)
            'kondisi_hp' => $kondisi, // Simpan kondisi HP saat dikembalikan
            'denda' => $denda // Simpan jumlah denda
        ]);

        // Catat aktivitas ke log sistem
        logAktivitas('Admin mengubah status menjadi dikembalikan');

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'HP berhasil dikembalikan');
    }

    /**
     * Method untuk menampilkan form edit peminjaman
     * Hanya bisa diakses oleh Admin
     * 
     * @param int $id ID peminjaman yang akan diedit
     */
    public function edit($id)
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect kembali
            return redirect()->back();
        }

        // Ambil data peminjaman berdasarkan ID
        $peminjaman = $this->peminjamanModel->find($id);

        // Validasi: Cek apakah data peminjaman ditemukan
        if (!$peminjaman) {
            // Jika tidak ditemukan, throw exception PageNotFoundException
            throw new
        \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Render view form edit dengan data peminjaman
        return view('admin/peminjaman/edit', [
            'peminjaman' => $peminjaman
        ]);
    }

    /**
     * Method untuk memproses update data peminjaman
     * Hanya bisa diakses oleh Admin
     * 
     * @param int $id ID peminjaman yang akan diupdate
     */
    public function update($id)
    {
        // Validasi: Cek apakah user adalah Admin
        if (session()->get('role') !== 'Admin') {
            // Jika bukan admin, redirect kembali
            return redirect()->back();
        }

        // Ambil status dari form POST (tidak digunakan, bisa dihapus)
        $status = $this->request->getPost('status');

        // Update data peminjaman di database
        $this->peminjamanModel->update($id, [
            'waktu'   => $this->request->getPost('waktu'), // Update waktu peminjaman
            'status'  => $this->request->getPost('status'), // Update status
            'catatan' => $this->request->getPost('catatan'), // Update catatan
        ]);

        // Catat aktivitas ke log sistem
        logAktivitas('Admin mengubah data peminjaman ID ' . $id);

        // Redirect ke halaman daftar peminjaman dengan pesan sukses
        return redirect()->to('admin/peminjaman')
            ->with('success', 'Data peminjaman berhasil diupdate');
    }

    /**
     * Method untuk menghapus/mengarsipkan peminjaman
     * Hanya bisa menghapus peminjaman yang sudah dikembalikan
     * Hanya bisa diakses oleh Admin
     * 
     * @param int $id ID peminjaman yang akan dihapus
     */
    public function delete($id)
    {
        // Ambil data peminjaman dengan JOIN ke tabel alat untuk mendapatkan merk dan tipe HP
        $p = $this->peminjamanModel
            ->select('peminjaman.*, alat.merk, alat.tipe') // Pilih kolom yang dibutuhkan
            ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
            ->find($id); // Cari berdasarkan ID

        // Validasi: Cek apakah peminjaman sudah dikembalikan
        // Hanya peminjaman yang sudah dikembalikan yang boleh diarsipkan
        if ($p['status'] !== 'dikembalikan') {
            // Jika belum dikembalikan, redirect dengan pesan error
            return redirect()->back()
                ->with('error', 'Peminjaman belum selesai');
        }

        // Hapus data peminjaman dari database (soft delete)
        $this->peminjamanModel->delete($id);

        // Catat aktivitas ke log sistem
        logAktivitas(
            'Admin mengarsipkan peminjaman HP ' .
            $p['merk'] . ' ' . $p['tipe'] // Log dengan merk dan tipe HP
        );

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data masuk arsip');
    }

    /**
     * Method untuk menyetujui pengembalian HP
     * Mengubah status peminjaman menjadi "selesai" dan status HP menjadi "tersedia"
     * 
     * @param int $id ID peminjaman yang pengembaliannya akan disetujui
     */
    public function setujuiPengembalian($id)
    {
        // Update status peminjaman menjadi "selesai"
        $this->peminjamanModel->update($id, [
            'status' => 'selesai' // Ubah status menjadi selesai
        ]);

        // Ambil data peminjaman untuk mendapatkan id_hp
        $data = $this->peminjamanModel->find($id);

        // Update status HP menjadi "tersedia" agar bisa dipinjam lagi
        $this->alatModel->update($data['id_hp'], [
            'status' => 'tersedia' // Ubah status HP menjadi tersedia
        ]);

        // Catat aktivitas ke log sistem
        logAktivitas('Admin menyetujui pengembalian HP');

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Pengembalian disetujui');
    }
}
