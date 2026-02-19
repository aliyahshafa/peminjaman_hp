<?php

// Deklarasi namespace
namespace App\Models;

// Import class Model dari CodeIgniter
use CodeIgniter\Model;

/**
 * Model untuk mengelola log aktivitas sistem
 * Mencatat semua aktivitas user di aplikasi
 */
class LogModel extends Model
{
    // Nama tabel di database
    protected $table            = 'log_aktivitas';
    
    // Nama kolom primary key
    protected $primaryKey       = 'id_log';
    
    // Gunakan auto increment untuk primary key
    protected $useAutoIncrement = true;
    
    // Tipe data return (array atau object)
    protected $returnType       = 'array';
    
    // Nonaktifkan soft delete (log tidak perlu soft delete)
    protected $useSoftDeletes   = false;
    
    // Aktifkan proteksi field (hanya field yang ada di allowedFields yang bisa diisi)
    protected $protectFields    = true;
    
    // Daftar field yang boleh diisi (mass assignment)
    protected $allowedFields    = [
        'id_user',      // ID user yang melakukan aktivitas
        'nama_user',    // Nama user
        'role',         // Role user (Admin/Petugas/Peminjam)
        'aktivitas',    // Deskripsi aktivitas yang dilakukan
        'ip_address',   // IP address user
        'user_agent'    // User agent (browser dan OS)
    ];

    // Konfigurasi tambahan model
    protected bool $allowEmptyInserts = false; // Tidak boleh insert data kosong
    protected bool $updateOnlyChanged = true; // Hanya update field yang berubah

    protected array $casts = []; // Casting tipe data
    protected array $castHandlers = []; // Handler untuk casting

    // Konfigurasi timestamp
    protected $useTimestamps = true; // Gunakan timestamp otomatis
    protected $dateFormat    = 'datetime'; // Format tanggal
    protected $createdField  = 'created_at'; // Nama kolom created_at (waktu aktivitas)
    protected $updatedField  = 'updated_at'; // Nama kolom updated_at
    protected $deletedField  = 'deleted_at'; // Nama kolom deleted_at

    // Konfigurasi validasi
    protected $validationRules      = []; // Aturan validasi
    protected $validationMessages   = []; // Pesan validasi
    protected $skipValidation       = false; // Jangan skip validasi
    protected $cleanValidationRules = true; // Bersihkan aturan validasi

    // Konfigurasi callbacks (event hooks)
    protected $allowCallbacks = true; // Aktifkan callbacks
    protected $beforeInsert   = []; // Callback sebelum insert
    protected $afterInsert    = []; // Callback setelah insert
    protected $beforeUpdate   = []; // Callback sebelum update
    protected $afterUpdate    = []; // Callback setelah update
    protected $beforeFind     = []; // Callback sebelum find
    protected $afterFind      = []; // Callback setelah find
    protected $beforeDelete   = []; // Callback sebelum delete
    protected $afterDelete    = []; // Callback setelah delete
}

