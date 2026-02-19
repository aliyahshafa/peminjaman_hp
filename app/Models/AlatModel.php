<?php

// Deklarasi namespace
namespace App\Models;

// Import class Model dari CodeIgniter
use CodeIgniter\Model;

/**
 * Model untuk mengelola data alat/HP
 * Menangani operasi database untuk tabel alat
 */
class AlatModel extends Model
{
    // Nama tabel di database
    protected $table            = 'alat';
    
    // Nama kolom primary key
    protected $primaryKey       = 'id_hp';
    
    // Gunakan auto increment untuk primary key
    protected $useAutoIncrement = true;
    
    // Tipe data return (array atau object)
    protected $returnType       = 'array';
    
    // Nonaktifkan soft delete (data benar-benar dihapus)
    protected $useSoftDeletes   = false;
    
    // Nonaktifkan proteksi field (semua field bisa diisi)
    // PENTING: Dinonaktifkan untuk menghindari masalah dengan kolom harga
    protected $protectFields    = false;
    
    // Daftar field yang boleh diisi (mass assignment)
    protected $allowedFields    = [
        'id_category',  // ID kategori HP
        'merk',         // Merk HP
        'tipe',         // Tipe HP
        'harga',        // Harga sewa per hari
        'kondisi',      // Kondisi HP (baik/rusak ringan/rusak berat)
        'status'        // Status HP (tersedia/dipinjam)
    ];

    /**
     * Method untuk mengambil data alat yang tersedia
     * Hanya menampilkan HP yang statusnya tersedia dan tidak sedang dipinjam
     * 
     * @return array Data alat yang tersedia
     */
    public function getAlatWithStatus()
    {
        // Query langsung dengan database builder
        return $this->db->table('alat')
            ->select('alat.*, category.nama_category') // Pilih semua kolom alat + nama kategori
            ->join('category', 'category.id_category = alat.id_category', 'left') // LEFT JOIN dengan tabel category
            ->where('alat.status', 'tersedia') // Filter hanya yang tersedia
            ->whereNotIn('alat.id_hp', function ($builder) {
                // Subquery: Exclude HP yang sedang dipinjam
                return $builder->select('id_hp')
                    ->from('peminjaman')
                    ->where('status', 'dipinjam');
            })
            ->get() // Eksekusi query
            ->getResultArray(); // Ambil hasil sebagai array
    }

    /**
     * Method untuk mengambil data alat dengan filter dan status peminjaman
     * Mendukung filter berdasarkan keyword dan kategori
     * 
     * @param string|null $keyword Keyword pencarian
     * @param int|null $category ID kategori untuk filter
     * @return array Data alat dengan status peminjaman
     */
    public function getAlatFilteredWithStatus($keyword = null, $category = null)
    {
        // Inisialisasi query builder dengan JOIN
        $builder = $this->db->table('alat')
            ->select('alat.*, category.nama_category, peminjaman.status AS status_peminjaman') // Pilih data alat + kategori + status peminjaman
            ->join('category', 'category.id_category = alat.id_category', 'left') // LEFT JOIN dengan tabel category
            ->join(
                'peminjaman',
                'peminjaman.id_hp = alat.id_hp AND peminjaman.status != "dikembalikan"', // JOIN dengan peminjaman yang belum dikembalikan
                'left'
            );

        // Jika ada keyword, tambahkan filter LIKE
        if ($keyword) {
            $builder->like('alat.tipe', $keyword); // Cari berdasarkan tipe HP
        }

        // Jika ada filter kategori, tambahkan filter WHERE
        if ($category) {
            $builder->where('alat.id_category', $category); // Filter berdasarkan kategori
        }

        // Eksekusi query dengan GROUP BY untuk menghindari duplikat
        return $builder
                ->groupBy('alat.id_hp') // Group berdasarkan ID HP
                ->get() // Eksekusi query
                ->getResultArray(); // Ambil hasil sebagai array
    }

    /**
     * Method untuk mengambil data alat dengan informasi kategori
     * Menampilkan semua alat dengan nama kategorinya
     * 
     * @return array Data alat dengan nama kategori
     */
    public function getAlatWithCategory()
    {
        // Query dengan JOIN ke tabel category
        return $this->select('alat.*, category.nama_category') // Pilih semua kolom alat + nama kategori
                    ->join('category', 'category.id_category = alat.id_category') // JOIN dengan tabel category
                    ->findAll(); // Ambil semua data
    }

    /**
     * Method untuk mengambil data alat dengan filter
     * Mendukung filter berdasarkan keyword dan kategori
     * Mengembalikan query builder untuk chaining
     * 
     * @param string|null $keyword Keyword pencarian
     * @param int|null $category ID kategori untuk filter
     * @return object Query builder untuk chaining
     */
    public function getAlatFiltered($keyword = null, $category = null)
    {
        // Inisialisasi query builder dengan JOIN
        $builder = $this->select('alat.*, category.nama_category') // Pilih semua kolom alat + nama kategori
                        ->join('category', 'category.id_category = alat.id_category', 'left'); // LEFT JOIN dengan tabel category
        
        // Jika ada keyword, tambahkan filter LIKE
        if($keyword) {
            $builder->like ('alat.tipe', $keyword); // Cari berdasarkan tipe HP
        }

        // Jika ada filter kategori, tambahkan filter WHERE
        if ($category) {
            $builder->where('alat.id_category', $category); // Filter berdasarkan kategori
        }

        // Return query builder untuk chaining (bisa ditambahkan method lain)
        return $builder;
    }

    /**
     * Method debug untuk mengecek nilai harga
     * Digunakan untuk troubleshooting masalah harga yang tidak muncul
     * 
     * @return array Data alat dengan informasi harga dan deleted_at
     */
    public function debugHarga()
    {
        // Query langsung untuk bypass filtering model
        return $this->db->table('alat')
            ->select('id_hp, merk, tipe, harga, deleted_at') // Pilih kolom untuk debug
            ->limit(5) // Batasi 5 data
            ->get() // Eksekusi query
            ->getResultArray(); // Ambil hasil sebagai array
    }

    /**
     * Method untuk memastikan harga muncul
     * Menggunakan query langsung untuk menghindari masalah soft delete
     * 
     * @return array Data alat dengan harga yang pasti muncul
     */
    public function getAlatWithHargaForced()
    {
        // Query langsung dengan database builder
        return $this->db->table('alat')
            ->select('*') // Pilih semua kolom
            ->where('deleted_at IS NULL') // Filter hanya data yang tidak dihapus
            ->orderBy('id_hp', 'DESC') // Urutkan dari ID terbesar (terbaru)
            ->get() // Eksekusi query
            ->getResultArray(); // Ambil hasil sebagai array
    }

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
}

