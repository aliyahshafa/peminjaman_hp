<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use App\Models\UserModel; // Model user

/**
 * Controller untuk mengelola profil user
 * User bisa melihat dan mengedit profil mereka sendiri
 */
class ProfileController extends BaseController
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
     * Method untuk menampilkan halaman profil (read only)
     * Menampilkan data profil user yang sedang login
     */
    public function index()
    {
        // Ambil ID user yang sedang login dari session
        $id_user = session()->get('id_user');

        // Ambil data user berdasarkan ID
        $user = $this->userModel->find($id_user);

        // Render view profil dengan data user
        return view('profile/index', [
            'user' => $user
        ]);
    }

    /**
     * Method untuk menampilkan form edit profil
     * User bisa mengedit profil mereka sendiri
     */
    public function edit()
    {
        // Ambil ID user yang sedang login dari session
        $id_user = session()->get('id_user');

        // Ambil data user berdasarkan ID
        $user = $this->userModel->find($id_user);

        // Render view form edit profil dengan data user
        return view('profile/edit', [
            'user' => $user
        ]);
    }

    /**
     * Method untuk memproses update profil
     * User mengupdate profil mereka sendiri
     */
    public function update()
    {
        // Ambil ID user yang sedang login dari session
        $id_user = session()->get('id_user');

        // Siapkan data yang akan diupdate
        $data = [
            'nama_user' => $this->request->getPost('nama_user'), // Update nama user dari form
            'username'  => $this->request->getPost('username'), // Update username dari form
        ];

        // Cek apakah password diisi (password opsional)
        if ($this->request->getPost('password')) {
            // Jika password diisi, hash password baru dan tambahkan ke data update
            $data['password'] = password_hash(
                $this->request->getPost('password'), // Password dari form
                PASSWORD_DEFAULT // Algoritma hash terbaik
            );
        }

        // Update data user di database
        $this->userModel->update($id_user, $data);

        // Catat aktivitas ke log sistem
        logAktivitas('User mengubah profil sendiri');

        // Redirect ke halaman profil dengan pesan sukses
        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui');
    }
}
