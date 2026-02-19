<?php

// Deklarasi namespace
namespace App\Models;

// Import class Model dari CodeIgniter
use CodeIgniter\Model;

/**
 * Model untuk mengelola data user
 * Menangani operasi database untuk tabel user
 */
class UserModel extends Model
{
    // Nama tabel di database
    protected $table = 'user';

    // Nama kolom primary key
    protected $primaryKey = 'id_user';

    // Daftar field yang boleh diisi (mass assignment)
    protected $allowedFields = [
        'nama_user',   // Nama lengkap user
        'username',    // Username untuk login
        'email',       // Email user
        'password',    // Password (harus di-hash)
        'role',        // Role user (Admin/Petugas/Peminjam)
        'no_hp',       // Nomor HP user
        'alamat',      // Alamat user
        'created_at',  // Tanggal dibuat
        'updated_at'   // Tanggal diupdate
    ];

    // Konfigurasi timestamp
    // Aktifkan timestamp otomatis untuk created_at dan updated_at
    protected $useTimestamps = true;
    
    // Nama kolom untuk menyimpan tanggal pembuatan data
    protected $createdField  = 'created_at';
    
    // Nama kolom untuk menyimpan tanggal update data
    protected $updatedField  = 'updated_at';
    
    // Nama kolom untuk soft delete (data tidak benar-benar dihapus)
    protected $deletedField  = 'deleted_at';
    
    // Aktifkan soft delete (data yang dihapus masih ada di database dengan deleted_at terisi)
    protected $useSoftDeletes = true;

}


