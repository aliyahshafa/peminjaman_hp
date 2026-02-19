<?php

// Deklarasi namespace untuk mengorganisir class
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar CodeIgniter
use CodeIgniter\HTTP\ResponseInterface; // Interface untuk HTTP response
use App\Models\UserModel; // Model untuk mengelola data user

/**
 * Controller untuk menangani autentikasi (login/logout)
 * Mengelola proses login, validasi user, dan logout
 */
class AuthController extends BaseController
{
    /**
     * Method untuk menampilkan halaman login
     * Dipanggil saat user mengakses halaman utama
     */
    public function index()
    {
        // Render view halaman login
        return view('auth/login');
    }

    /**
     * Method untuk menampilkan form login
     * Sama dengan method index()
     */
    public function login() 
    {
        // Render view halaman login
        return view('auth/login');
    }

    /**
     * Method untuk memproses login user
     * Validasi email dan password, lalu redirect sesuai role
     */
    public function process()
    {
        // Buat instance UserModel untuk mengakses data user
        $userModel = new \App\Models\UserModel();

        // Ambil input email dari form POST
        $email    = $this->request->getPost('email');
        
        // Ambil input password dari form POST
        $password = $this->request->getPost('password');

        // Cari user di database berdasarkan email
        // Method where() untuk filter, first() untuk ambil 1 data pertama
        $user = $userModel->where('email', $email)->first();

        // Validasi: Cek apakah user dengan email tersebut ditemukan
        if (!$user) {
            // Jika tidak ditemukan, redirect kembali dengan pesan error
            return redirect()->back()->with('error','Email tidak ditemukan');
        }

        // Validasi password menggunakan password_verify()
        // Membandingkan password input dengan password hash di database
        if (!password_verify($password, $user['password'])) {
            // Jika password salah, redirect kembali dengan pesan error
            return redirect()->back()->with('error','Password salah');
        }

        // Jika validasi berhasil, simpan data user ke session
        session()->set([
            'logged_in' => true, // Status login
            'id_user'   => $user['id_user'], // ID user
            'nama_user' => $user['nama_user'], // Nama user
            'role'      => $user['role'], // Role user (Admin/Petugas/Peminjam)
            'logged_in' => true // Status login (duplikat, bisa dihapus)
        ]);

        // Redirect user ke dashboard sesuai role mereka
        if ($user['role'] == 'Admin') {
            // Jika Admin, redirect ke dashboard admin
            return redirect()->to('/admin'); 
        } elseif ($user['role'] == 'Petugas') {
            // Jika Petugas, redirect ke dashboard petugas
            return redirect()->to('/petugas');
        } else {
            // Jika Peminjam, redirect ke dashboard peminjam
            return redirect()->to('/peminjam');
        }
    }

    /**
     * Method untuk logout user
     * Menghapus semua data session dan redirect ke halaman login
     */
    public function logout()
    {
        // Hapus semua data session (logout)
        session()->destroy();

        // Redirect ke halaman login
        return redirect()->to('/');
    }
}

