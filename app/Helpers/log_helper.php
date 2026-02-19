<?php

// Import LogModel untuk menyimpan log ke database
use App\Models\LogModel;

/**
 * Fungsi utama untuk mencatat aktivitas user ke database
 * Fungsi ini akan dipanggil oleh fungsi-fungsi helper lainnya
 * 
 * @param string $aktivitas Deskripsi aktivitas yang dilakukan
 * @param array $detail Detail tambahan (opsional)
 */
if (!function_exists('logAktivitas')) {

    function logAktivitas($aktivitas, $detail = [])
    {
        try {
            // Buat instance LogModel untuk menyimpan log
            $logModel = new LogModel();
            
            // Ambil service request untuk mendapatkan IP dan user agent
            $request = \Config\Services::request();

            // Siapkan data log yang akan disimpan
            $data = [
                'id_user'    => session()->get('id_user') ?? 0, // ID user dari session, default 0 jika tidak ada
                'nama_user'  => session()->get('nama_user') ?? 'Guest', // Nama user dari session, default 'Guest'
                'role'       => session()->get('role') ?? 'Guest', // Role user dari session, default 'Guest'
                'aktivitas'  => $aktivitas, // Deskripsi aktivitas
                'ip_address' => $request->getIPAddress(), // IP address user
                'user_agent' => $request->getUserAgent()->getAgentString() // User agent (browser dan OS)
            ];

            // Gabungkan dengan detail tambahan jika ada
            $data = array_merge($data, $detail);

            // Simpan log ke database
            $logModel->insert($data);
        } catch (\Exception $e) {
            // Jika gagal menyimpan log, jangan ganggu proses utama
            // Hanya catat error ke log file CodeIgniter
            log_message('error', 'Log Aktivitas Error: ' . $e->getMessage());
        }
    }
}

/**
 * Fungsi untuk mencatat aktivitas login
 * Dipanggil saat user berhasil atau gagal login
 * 
 * @param int $userId ID user yang login
 * @param string $namaUser Nama user yang login
 * @param string $role Role user
 * @param string $status Status login ('success' atau 'failed')
 */
if (!function_exists('logLogin')) {
    function logLogin($userId, $namaUser, $role, $status = 'success')
    {
        // Tentukan pesan aktivitas berdasarkan status
        $aktivitas = $status === 'success' 
            ? "Login berhasil"  // Jika berhasil
            : "Percobaan login gagal"; // Jika gagal
        
        // Catat aktivitas login dengan detail user
        logAktivitas($aktivitas, [
            'id_user' => $userId,
            'nama_user' => $namaUser,
            'role' => $role
        ]);
    }
}

/**
 * Fungsi untuk mencatat aktivitas logout
 * Dipanggil saat user logout dari sistem
 */
if (!function_exists('logLogout')) {
    function logLogout()
    {
        // Catat aktivitas logout
        logAktivitas("Logout dari sistem");
    }
}

/**
 * Fungsi untuk mencatat aktivitas create/tambah data
 * Dipanggil saat user menambah data baru
 * 
 * @param string $entity Nama entitas/tabel yang ditambah (contoh: 'user', 'alat')
 * @param string $entityName Nama spesifik data yang ditambah (opsional)
 */
if (!function_exists('logCreate')) {
    function logCreate($entity, $entityName = '')
    {
        // Buat pesan aktivitas
        $message = "Menambah data {$entity}";
        
        // Jika ada nama spesifik, tambahkan ke pesan
        if ($entityName) {
            $message .= ": {$entityName}";
        }
        
        // Catat aktivitas create
        logAktivitas($message);
    }
}

/**
 * Fungsi untuk mencatat aktivitas update/ubah data
 * Dipanggil saat user mengubah data
 * 
 * @param string $entity Nama entitas/tabel yang diubah
 * @param string $entityName Nama spesifik data yang diubah (opsional)
 */
if (!function_exists('logUpdate')) {
    function logUpdate($entity, $entityName = '')
    {
        // Buat pesan aktivitas
        $message = "Mengubah data {$entity}";
        
        // Jika ada nama spesifik, tambahkan ke pesan
        if ($entityName) {
            $message .= ": {$entityName}";
        }
        
        // Catat aktivitas update
        logAktivitas($message);
    }
}

/**
 * Fungsi untuk mencatat aktivitas delete/hapus data
 * Dipanggil saat user menghapus data
 * 
 * @param string $entity Nama entitas/tabel yang dihapus
 * @param string $entityName Nama spesifik data yang dihapus (opsional)
 */
if (!function_exists('logDelete')) {
    function logDelete($entity, $entityName = '')
    {
        // Buat pesan aktivitas
        $message = "Menghapus data {$entity}";
        
        // Jika ada nama spesifik, tambahkan ke pesan
        if ($entityName) {
            $message .= ": {$entityName}";
        }
        
        // Catat aktivitas delete
        logAktivitas($message);
    }
}

/**
 * Fungsi untuk mencatat aktivitas view/lihat data
 * Dipanggil saat user melihat halaman atau data tertentu
 * 
 * @param string $entity Nama entitas/halaman yang dilihat
 * @param string $entityName Nama spesifik data yang dilihat (opsional)
 */
if (!function_exists('logView')) {
    function logView($entity, $entityName = '')
    {
        // Buat pesan aktivitas
        $message = "Melihat data {$entity}";
        
        // Jika ada nama spesifik, tambahkan ke pesan
        if ($entityName) {
            $message .= ": {$entityName}";
        }
        
        // Catat aktivitas view
        logAktivitas($message);
    }
}

/**
 * Fungsi untuk mencatat aktivitas export data
 * Dipanggil saat user mengexport data ke file (PDF, Excel, CSV, dll)
 * 
 * @param string $entity Nama entitas/data yang diexport
 * @param string $format Format export (PDF, Excel, CSV, dll)
 */
if (!function_exists('logExport')) {
    function logExport($entity, $format = 'PDF')
    {
        // Catat aktivitas export dengan format yang digunakan
        logAktivitas("Export data {$entity} ke format {$format}");
    }
}

/**
 * Fungsi untuk mencatat aktivitas peminjaman
 * Dipanggil saat ada aktivitas terkait peminjaman (create, approve, reject, return, cancel)
 * 
 * @param string $action Jenis aksi (create/approve/reject/return/cancel)
 * @param string $kodeAlat Kode/ID alat yang dipinjam
 * @param string $namaPeminjam Nama peminjam
 */
if (!function_exists('logPeminjaman')) {
    function logPeminjaman($action, $kodeAlat, $namaPeminjam)
    {
        // Daftar pesan untuk setiap jenis aksi
        $messages = [
            'create' => "Membuat peminjaman alat {$kodeAlat} oleh {$namaPeminjam}", // Saat peminjaman dibuat
            'approve' => "Menyetujui peminjaman alat {$kodeAlat} oleh {$namaPeminjam}", // Saat peminjaman disetujui
            'reject' => "Menolak peminjaman alat {$kodeAlat} oleh {$namaPeminjam}", // Saat peminjaman ditolak
            'return' => "Mengembalikan alat {$kodeAlat} dari {$namaPeminjam}", // Saat alat dikembalikan
            'cancel' => "Membatalkan peminjaman alat {$kodeAlat}" // Saat peminjaman dibatalkan
        ];
        
        // Catat aktivitas peminjaman dengan pesan yang sesuai
        // Jika action tidak ada di daftar, gunakan pesan default
        logAktivitas($messages[$action] ?? "Aktivitas peminjaman: {$action}");
    }
}
