<?php

// Deklarasi namespace
namespace App\Models;

// Import class Model dari CodeIgniter
use CodeIgniter\Model;

/**
 * Model untuk mengelola trash/recycle bin
 * Menyimpan backup data yang dihapus sebelum dihapus permanen
 */
class TrashModel extends Model
{
    // Nama tabel di database
    protected $table            = 'trash';
    
    // Nama kolom primary key
    protected $primaryKey       = 'id_trash';
    
    // Gunakan auto increment untuk primary key
    protected $useAutoIncrement = true;
    
    // Tipe data return (array atau object)
    protected $returnType       = 'array';
    
    // Nonaktifkan soft delete (data di trash benar-benar dihapus jika di-delete)
    protected $useSoftDeletes   = false;
    
    // Aktifkan proteksi field (hanya field yang ada di allowedFields yang bisa diisi)
    protected $protectFields    = true;
    
    // Daftar field yang boleh diisi (mass assignment)
    protected $allowedFields    = [
        'table_name',   // Nama tabel asal data
        'record_id',    // ID record di tabel asal
        'data_backup',  // Backup data dalam format JSON
        'deleted_by',   // ID user yang menghapus
        'deleted_at',   // Waktu penghapusan
        'reason'        // Alasan penghapusan
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
    protected $deletedField  = ''; // Kosongkan karena tidak pakai soft delete di trash

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
     * Method untuk backup data sebelum dihapus
     * Menyimpan data ke trash sebelum dihapus dari tabel asli
     * 
     * @param string $tableName Nama tabel asal data
     * @param int $recordId ID record yang akan dibackup
     * @param array $data Data yang akan dibackup
     * @param string $reason Alasan penghapusan
     * @return bool True jika berhasil, false jika gagal
     */
    public function backupData($tableName, $recordId, $data, $reason = '')
    {
        // Insert data backup ke tabel trash
        return $this->insert([
            'table_name' => $tableName, // Nama tabel asal
            'record_id' => $recordId, // ID record di tabel asal
            'data_backup' => json_encode($data), // Encode data ke JSON
            'deleted_by' => session()->get('id_user'), // ID user yang menghapus
            'deleted_at' => date('Y-m-d H:i:s'), // Waktu penghapusan
            'reason' => $reason // Alasan penghapusan
        ]);
    }

    /**
     * Method untuk mengambil data trash berdasarkan nama tabel
     * Menampilkan semua data yang dihapus dari tabel tertentu
     * 
     * @param string $tableName Nama tabel yang akan dicari
     * @return array Data trash dari tabel tersebut
     */
    public function getTrashByTable($tableName)
    {
        // Cari data trash berdasarkan nama tabel
        return $this->where('table_name', $tableName) // Filter berdasarkan nama tabel
                   ->orderBy('deleted_at', 'DESC') // Urutkan dari yang terbaru dihapus
                   ->findAll(); // Ambil semua data
    }

    /**
     * Method untuk restore data dari trash ke tabel aslinya
     * Mengembalikan data yang sudah dihapus
     * 
     * @param int $idTrash ID data di tabel trash
     * @return bool True jika berhasil, false jika gagal
     */
    public function restoreData($idTrash)
    {
        // Ambil data trash berdasarkan ID
        $trash = $this->find($idTrash);
        
        // Validasi: Cek apakah data trash ditemukan
        if (!$trash) {
            return false; // Return false jika tidak ditemukan
        }

        // Decode JSON data backup menjadi array
        $data = json_decode($trash['data_backup'], true);
        
        // Ambil nama tabel asal
        $tableName = $trash['table_name'];

        // Insert data kembali ke tabel aslinya
        $db = \Config\Database::connect(); // Buat koneksi database
        $result = $db->table($tableName)->insert($data); // Insert data ke tabel asal

        // Cek hasil insert
        if ($result) {
            // Jika berhasil, hapus data dari trash
            $this->delete($idTrash);
            return true; // Return true jika berhasil
        }

        return false; // Return false jika gagal
    }
}
