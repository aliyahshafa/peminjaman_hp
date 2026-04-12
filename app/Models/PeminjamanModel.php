<?php

// Deklarasi namespace
namespace App\Models;

// Import class Model dari CodeIgniter
use CodeIgniter\Model;

/**
 * Model untuk mengelola data peminjaman
 * Menangani operasi database untuk tabel peminjaman
 */
class PeminjamanModel extends Model
{
    // Nama tabel di database
    protected $table            = 'peminjaman';
    
    // Nama kolom primary key
    protected $primaryKey       = 'id_peminjaman';
    
    // Gunakan auto increment untuk primary key
    protected $useAutoIncrement = true;
    
    // Tipe data return (array atau object)
    protected $returnType       = 'array';
    
    // Aktifkan soft delete (data tidak benar-benar dihapus)
    protected $useSoftDeletes   = true;
    
    // Aktifkan proteksi field (hanya field yang ada di allowedFields yang bisa diisi)
    protected $protectFields    = true;
    
    // Daftar field yang boleh diisi (mass assignment)
    protected $allowedFields    = [
        'id_user',          // ID user yang meminjam
        'id_hp',            // ID HP yang dipinjam
        'nama_user',        // Nama user
        'waktu',            // Waktu peminjaman
        'status',           // Status peminjaman (diajukan/disetujui/dipinjam/dikembalikan)
        'tanggal_kembali',  // Tanggal pengembalian
        'kondisi_hp',       // Kondisi HP saat dikembalikan
        'denda',            // Jumlah denda
        'catatan'           // Catatan tambahan
    ];

    /**
     * Method untuk mengambil data pengembalian milik user tertentu
     * Menampilkan peminjaman dengan status tertentu dan data HP
     * 
     * @param int $id_user ID user yang datanya akan diambil
     * @return array Data peminjaman dengan informasi HP
     */
    public function getPengembalianByUser($id_user)
    {
        // Query dengan JOIN ke tabel alat untuk mendapatkan data HP
        return $this->select('peminjaman.*, alat.merk, alat.tipe, alat.harga') // Pilih semua kolom peminjaman + data alat
                    ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
                    ->where('peminjaman.id_user', $id_user) // Filter berdasarkan ID user
                    ->whereIn('peminjaman.status', ['Disetujui', 'Menunggu Pengembalian', 'Dikembalikan']) // Filter status
                    ->findAll(); // Ambil semua data
    }

    /**
     * Method untuk mengambil data peminjaman dengan informasi user dan alat
     * Digunakan untuk menampilkan daftar peminjaman lengkap
     * 
     * @return array Data peminjaman dengan informasi user dan HP
     */
    public function getPeminjamanWithUserAlat() 
    {
        // Query dengan JOIN ke tabel user dan alat
        return $this->select('
                peminjaman.id_peminjaman,
                peminjaman.nama_user,
                peminjaman.waktu,
                peminjaman.status AS status_peminjaman,
                peminjaman.id_user,
                peminjaman.id_hp,
                alat.merk,
                alat.tipe,
                alat.harga
            ')
            ->join('user', 'user.id_user = peminjaman.id_user', 'left') // LEFT JOIN dengan tabel user
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left') // LEFT JOIN dengan tabel alat
            ->orderBy('peminjaman.id_peminjaman', 'DESC') // Urutkan dari terbaru
            ->findAll(); // Ambil semua data
    }

    /**
     * Method untuk mengambil riwayat peminjaman user
     * Menggunakan database builder langsung untuk memastikan harga muncul
     * 
     * @param int $idUser ID user yang riwayatnya akan diambil
     * @return array Riwayat peminjaman user
     */
    public function getRiwayatByUser($idUser)
    {
        // Query langsung dengan database builder untuk menghindari masalah soft delete
        return $this->db->table('peminjaman')
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga') // Pilih semua kolom peminjaman + data alat
            ->join('alat', 'alat.id_hp = peminjaman.id_hp', 'left') // LEFT JOIN dengan tabel alat
            ->where('peminjaman.id_user', $idUser) // Filter berdasarkan ID user
            ->whereIn('peminjaman.status', ['Diajukan', 'Disetujui', 'Menunggu Pengembalian', 'Dikembalikan']) // Filter status
            ->orderBy('peminjaman.id_peminjaman', 'DESC') // Urutkan dari terbaru
            ->get() // Eksekusi query
            ->getResultArray(); // Ambil hasil sebagai array
    }

    /**
     * Method untuk mengambil data peminjaman dengan informasi HP
     * Digunakan untuk menampilkan daftar peminjaman dengan data HP
     * 
     * @return array Data peminjaman dengan informasi HP
     */
    public function getPeminjamanWithHp()
    {
        // Query dengan JOIN ke tabel alat
        return $this->select(
            'peminjaman.*, alat.merk, alat.tipe, alat.harga' // Pilih semua kolom peminjaman + data alat
        )
        ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
        ->findAll(); // Ambil semua data
    }

    /**
     * Method untuk mengambil peminjaman yang perlu diverifikasi
     * Menampilkan peminjaman dengan status Diajukan atau Disetujui
     * 
     * @return array Data peminjaman yang perlu diverifikasi
     */
    public function getPeminjamanVerifikasi()
    {
        // Query langsung dengan database builder
        return $this->db->table('peminjaman')
            ->select('
                peminjaman.id_peminjaman,
                peminjaman.nama_user,
                peminjaman.waktu,
                peminjaman.status,
                alat.merk,
                alat.tipe,
                alat.harga
            ')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
            ->whereIn('peminjaman.status', ['Diajukan', 'Disetujui']) // Filter status yang perlu diverifikasi
            ->get() // Eksekusi query
            ->getResultArray(); // Ambil hasil sebagai array
    }

    /**
     * Method untuk mengambil peminjaman yang perlu diverifikasi oleh Petugas
     * Sama dengan getPeminjamanVerifikasi, khusus untuk Petugas
     * 
     * @return array Data peminjaman yang perlu diverifikasi
     */
    public function getVerifikasiPetugas()
    {
        // Query langsung dengan database builder
        return $this->db->table('peminjaman')
            ->select('
                peminjaman.id_peminjaman,
                peminjaman.nama_user,
                peminjaman.waktu,
                peminjaman.status,
                alat.merk,
                alat.tipe,
                alat.harga
            ')
            ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
            ->whereIn('peminjaman.status', ['Diajukan', 'Disetujui']) // Filter status yang perlu diverifikasi
            ->get() // Eksekusi query
            ->getResultArray(); // Ambil hasil sebagai array
    }

    /**
     * Method untuk monitoring pengembalian HP
     * Menampilkan peminjaman yang sedang berjalan atau sudah dikembalikan
     * 
     * @return array Data peminjaman untuk monitoring pengembalian
     */
    public function getMonitoringPengembalian()
    {
        // Query langsung dengan database builder
        return $this->db->table('peminjaman')
            ->select('peminjaman.*, alat.merk, alat.tipe, alat.harga') // Pilih semua kolom peminjaman + data alat
            ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
            ->whereIn('peminjaman.status', [
                'Disetujui',              // Peminjaman yang disetujui
                'Menunggu Pengembalian',  // Menunggu pengembalian
                'Dikembalikan'            // Sudah dikembalikan
            ])
            ->orderBy('peminjaman.id_peminjaman', 'DESC') // Urutkan dari terbaru
            ->get() // Eksekusi query
            ->getResultArray(); // Ambil hasil sebagai array
    }

    /**
     * Method untuk mengambil data pengembalian untuk Admin
     * Menampilkan peminjaman yang menunggu atau sudah dikembalikan
     * 
     * @return array Data pengembalian untuk Admin
     */
    public function getPengembalianAdmin()
    {
        // Query dengan JOIN ke tabel alat
        return $this->select('peminjaman.*, alat.merk, alat.tipe, alat.harga') // Pilih semua kolom peminjaman + data alat
            ->join('alat', 'alat.id_hp = peminjaman.id_hp') // JOIN dengan tabel alat
            ->whereIn('peminjaman.status', ['Menunggu Pengembalian', 'Dikembalikan']) // Filter status pengembalian
            ->findAll(); // Ambil semua data
    }

    // Konfigurasi tambahan model
    protected bool $allowEmptyInserts = false; // Tidak boleh insert data kosong
    protected bool $updateOnlyChanged = true; // Hanya update field yang berubah

    protected array $casts = []; // Casting tipe data
    protected array $castHandlers = []; // Handler untuk casting

    // Konfigurasi timestamp
    protected $useTimestamps = false; // Tidak gunakan timestamp otomatis
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

