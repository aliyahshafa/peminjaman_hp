<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use CodeIgniter\HTTP\ResponseInterface; // Interface HTTP response
use App\Models\UserModel; // Model user

/**
 * Controller untuk mengelola data user
 * Menangani CRUD user untuk Admin
 */
class UserController extends BaseController
{
    // Property untuk menyimpan instance UserModel
    protected $userModel;

    /**
     * Constructor - dipanggil saat class diinstansiasi
     * Menginisialisasi model yang dibutuhkan
     */
    public function __construct()
    {
        // Buat instance UserModel
        $this->userModel = new UserModel();
    }

    /**
     * Method untuk menampilkan daftar user
     * Mendukung fitur pencarian berdasarkan nama atau email
     */
    public function index()
    {
        // Buat instance UserModel
        $userModel = new UserModel();

        // Ambil keyword pencarian dari URL query string
        $keyword = $this->request->getGet('keyword');

        // Inisialisasi query builder dengan UserModel
        $builder = $userModel;
        
        // Jika ada keyword pencarian, tambahkan filter
        if ($keyword) {
            // Cari berdasarkan nama_user atau email (LIKE)
            $builder = $builder->like('nama_user', $keyword) // Cari di kolom nama_user
                               ->orlike('email', $keyword); // Atau cari di kolom email
        } 
        
        // Siapkan data untuk dikirim ke view
        $data = [
            'users'     => $builder->findAll(), // Ambil semua user (dengan filter jika ada)
            'keyword'   => $keyword // Keyword untuk ditampilkan di form pencarian
        ];

        // Catat aktivitas view ke log sistem
        logView('daftar user');

        // Render view daftar user dengan data yang sudah disiapkan
        return view('user/index', $data);
    }

    /**
     * Method untuk menampilkan form tambah user baru
     */
    public function create()
    {
        // Render view form tambah user
        return view('user/create');
    }

    /**
     * Method untuk menyimpan user baru ke database
     */
    public function store()
    {
        // Buat instance UserModel
        $userModel = new \App\Models\UserModel();

        // Ambil nama user dari form POST
        $namaUser = $this->request->getPost('nama_user');
        
        // Ambil password dari form POST
        $password = $this->request->getPost('password');

        // Hash password sebelum disimpan ke database (untuk keamanan)
        // Menggunakan PASSWORD_DEFAULT untuk algoritma hash terbaik
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data user baru ke database
        $userModel->insert([
            'nama_user' => $namaUser, // Nama user
            'email'     => $this->request->getPost('email'), // Email user
            'password'  => $hashedPassword, // Password yang sudah di-hash
            'role'      => $this->request->getPost('role'), // Role user (Admin/Petugas/Peminjam)
            'no_hp'     => $this->request->getPost('no_hp'), // Nomor HP
            'alamat'    => $this->request->getPost('alamat') // Alamat
        ]);

        // Catat aktivitas create ke log sistem
        logCreate('user', $namaUser);

        // Redirect ke halaman daftar user dengan pesan sukses
        return redirect()->to('/admin/user')->with('success', 'User baru berhasil ditambahkan!');
    }

    /**
     * Method untuk menampilkan form edit user
     * 
     * @param int $id ID user yang akan diedit
     */
    public function edit($id)
    {
        // Buat instance UserModel
        $userModel = new \App\Models\UserModel();
        
        // Ambil data user berdasarkan ID
        $data['user'] = $this->userModel->find($id);

        // Render view form edit dengan data user
        return view('user/edit', $data);
    }

    /**
     * Method untuk memproses update data user
     * 
     * @param int $id ID user yang akan diupdate
     */
    public function update($id)
    {
        // Buat instance UserModel
        $userModel = new \App\Models\UserModel();
        
        // Ambil data user lama untuk keperluan log
        $user = $userModel->find($id);

        // Siapkan data yang akan diupdate
        $data = [
            'nama_user' => $this->request->getPost('nama_user'), // Update nama user
            'email'     => $this->request->getPost('email'), // Update email
            'role'      => $this->request->getPost('role'), // Update role
            'no_hp'     => $this->request->getPost('no_hp'), // Update nomor HP
            'alamat'    => $this->request->getPost('alamat'), // Update alamat
        ];

        // Ambil password dari form POST
        $password = $this->request->getPost('password');
        
        // Jika password diisi, baru diupdate (password opsional saat edit)
        if ($password){
                // Hash password baru sebelum disimpan
                $data['password'] = password_hash($password,
                    PASSWORD_DEFAULT);
        }

        // Update data user di database
        $userModel->update($id, $data);

        // Catat aktivitas update ke log sistem
        logUpdate('user', $user['nama_user']);

        // Redirect ke halaman daftar user dengan pesan sukses
        return redirect()->to('/admin/user')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Method untuk menghapus user
     * Data akan di-soft delete (tidak benar-benar dihapus)
     * 
     * @param int $id ID user yang akan dihapus
     */
    public function delete($id)
    {
        // Ambil data user sebelum dihapus untuk keperluan log
        $user = $this->userModel->find($id);
        
        // Hapus user dari database (soft delete)
        $this->userModel->delete($id);

        // Catat aktivitas delete ke log sistem
        logDelete('user', $user['nama_user']);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'User berhasil diarsipkan');
    }

}

