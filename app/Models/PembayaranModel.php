<?php

// Deklarasi namespace
namespace App\Models;

// Import class Model dari CodeIgniter
use CodeIgniter\Model;

/**
 * Model untuk mengelola data pembayaran
 * Menangani operasi database untuk tabel pembayaran
 */
class PembayaranModel extends Model
{
    // Nama tabel di database
    protected $table            = 'pembayaran';
    
    // Nama kolom primary key
    protected $primaryKey       = 'id_pembayaran';
    
    // Gunakan auto increment untuk primary key
    protected $useAutoIncrement = true;
    
    // Tipe data return (array atau object)
    protected $returnType       = 'array';
    
    // Nonaktifkan soft delete (data benar-benar dihapus)
    protected $useSoftDeletes   = false;
    
    // Aktifkan proteksi field (hanya field yang ada di allowedFields yang bisa diisi)
    protected $protectFields    = true;
    
    // Daftar field yang boleh diisi (mass assignment)
    protected $allowedFields    = [
        'id_peminjaman',      // ID peminjaman yang dibayar
        'id_user',            // ID user yang membayar
        'waktu',              // Waktu pembayaran
        'harga',              // Harga sewa per hari
        'metode_pembayaran',  // Metode pembayaran (Tunai/Transfer Bank)
        'tanggal_bayar',      // Tanggal pembayaran
        'status',             // Status pembayaran (Lunas/Belum Lunas)
        'subtotal'            // Total yang harus dibayar
    ];

    // Konfigurasi tambahan model
    protected bool $allowEmptyInserts = false; // Tidak boleh insert data kosong
    protected bool $updateOnlyChanged = true; // Hanya update field yang berubah

    protected array $casts = []; // Casting tipe data
    protected array $castHandlers = []; // Handler untuk casting

    // Konfigurasi timestamp
    protected $useTimestamps = true; // Gunakan timestamp otomatis
    protected $dateFormat    = 'datetime'; // Format tanggal
    protected $createdField  = 'created_at'; // Nama kolom created_at
    protected $updatedField  = 'updated_at'; // Nama kolom updated_at
    protected $deletedField  = 'deleted_at'; // Nama kolom deleted_at (untuk soft delete)

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

    /**
     * Method untuk mengambil data pembayaran berdasarkan ID peminjaman
     * Digunakan untuk mengecek apakah peminjaman sudah dibayar atau belum
     * 
     * @param int $idPeminjaman ID peminjaman yang akan dicek
     * @return array|null Data pembayaran atau null jika belum ada
     */
    public function getByPeminjaman($idPeminjaman)
    {
        // Cari pembayaran berdasarkan ID peminjaman
        // Method first() akan return 1 data pertama atau null jika tidak ada
        return $this->where('id_peminjaman', $idPeminjaman)->first();
    }

    /**
     * Method untuk mengambil riwayat pembayaran user
     * Menampilkan semua pembayaran yang pernah dilakukan user
     * 
     * @param int $idUser ID user yang riwayatnya akan diambil
     * @return array Riwayat pembayaran user
     */
    public function getByUser($idUser)
    {
        // Query dengan JOIN ke tabel peminjaman dan alat untuk mendapatkan detail lengkap
        return $this->select('pembayaran.*, peminjaman.waktu as waktu_pinjam, alat.merk, alat.tipe') // Pilih data pembayaran + waktu pinjam + data HP
            ->join('peminjaman', 'peminjaman.id_peminjaman = pembayaran.id_peminjaman') // JOIN dengan tabel peminjaman
            ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
            ->where('pembayaran.id_user', $idUser) // Filter berdasarkan ID user
            ->orderBy('pembayaran.tanggal_bayar', 'DESC') // Urutkan dari tanggal terbaru
            ->findAll(); // Ambil semua data
    }
}

