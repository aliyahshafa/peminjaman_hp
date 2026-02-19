<?php

// Deklarasi namespace
namespace App\Controllers;

// Import class yang dibutuhkan
use App\Controllers\BaseController; // Controller dasar
use App\Models\LogModel; // Model log aktivitas
use App\Models\UserModel; // Model user
use CodeIgniter\HTTP\ResponseInterface; // Interface HTTP response

/**
 * Controller untuk mengelola log aktivitas sistem
 * Menangani view, filter, export, dan statistik log aktivitas
 */
class LogController extends BaseController
{
    // Property untuk menyimpan instance LogModel
    protected $logModel;

    /**
     * Constructor - dipanggil saat class diinstansiasi
     * Menginisialisasi model dan helper yang dibutuhkan
     */
    public function __construct()
    {
        // Buat instance LogModel
        $this->logModel = new LogModel();
        
        // Load helper log untuk fungsi-fungsi log
        helper('log');
    }

    /**
     * Method untuk menampilkan daftar log aktivitas
     * Mendukung pagination dan filter berdasarkan role, user, tanggal, dan pencarian
     */
    public function index()
    {
        // Jumlah data per halaman
        $perPage = 20;
        
        // Ambil nomor halaman dari URL, default halaman 1
        $page = $this->request->getVar('page') ?? 1;
        
        // Ambil parameter filter dari URL
        $role = $this->request->getVar('role'); // Filter berdasarkan role
        $user = $this->request->getVar('user'); // Filter berdasarkan user
        $dateFrom = $this->request->getVar('date_from'); // Filter tanggal mulai
        $dateTo = $this->request->getVar('date_to'); // Filter tanggal akhir
        $search = $this->request->getVar('search'); // Keyword pencarian

        // Inisialisasi query builder dengan urutan terbaru dulu
        $builder = $this->logModel->orderBy('created_at', 'DESC');

        // Terapkan filter jika ada
        // Filter berdasarkan role
        if ($role) {
            $builder->where('role', $role);
        }
        
        // Filter berdasarkan user
        if ($user) {
            $builder->where('id_user', $user);
        }
        
        // Filter berdasarkan tanggal mulai (dari jam 00:00:00)
        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        
        // Filter berdasarkan tanggal akhir (sampai jam 23:59:59)
        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }
        
        // Filter berdasarkan keyword pencarian di kolom aktivitas
        if ($search) {
            $builder->like('aktivitas', $search);
        }

        // Ambil data log dengan pagination
        $data['logs'] = $builder->paginate($perPage);
        
        // Ambil object pager untuk navigasi halaman
        $data['pager'] = $this->logModel->pager;
        
        // Ambil data untuk dropdown filter role (distinct role)
        $data['roles'] = $this->logModel->select('role')->distinct()->findAll();
        
        // Ambil data user untuk dropdown filter user
        $userModel = new UserModel();
        $data['users'] = $userModel->select('id_user, nama_user')->findAll();
        
        // Simpan nilai filter untuk ditampilkan kembali di form
        $data['filters'] = [
            'role' => $role,
            'user' => $user,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'search' => $search
        ];

        // Catat aktivitas view ke log sistem
        logView('log aktivitas');
        
        // Render view daftar log dengan data yang sudah disiapkan
        return view('admin/log/index', $data);
    }

    /**
     * Method untuk menampilkan detail log aktivitas
     * 
     * @param int $id ID log yang akan ditampilkan
     */
    public function detail($id)
    {
        // Ambil data log berdasarkan ID
        $data['log'] = $this->logModel->find($id);
        
        // Validasi: Cek apakah log ditemukan
        if (!$data['log']) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->to('/admin/log')->with('error', 'Log tidak ditemukan');
        }

        // Render view detail log dengan data yang sudah diambil
        return view('admin/log/detail', $data);
    }

    /**
     * Method untuk menghapus log lama
     * Menghapus log yang lebih lama dari jumlah hari tertentu
     */
    public function clear()
    {
        // Cek apakah request method adalah POST (form sudah disubmit)
        if ($this->request->getMethod() === 'post') {
            // Ambil jumlah hari dari form, default 30 hari
            $days = $this->request->getPost('days') ?? 30;
            
            // Hitung tanggal batas (X hari yang lalu)
            $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
            
            // Hapus log yang lebih lama dari tanggal batas
            $deleted = $this->logModel->where('created_at <', $date)->delete();
            
            // Catat aktivitas penghapusan ke log sistem
            logAktivitas("Menghapus log aktivitas lebih dari {$days} hari ({$deleted} record)");
            
            // Redirect ke halaman log dengan pesan sukses
            return redirect()->to('/admin/log')->with('success', "Berhasil menghapus {$deleted} log lama");
        }

        // Jika bukan POST, tampilkan form konfirmasi hapus
        return view('admin/log/clear');
    }

    /**
     * Method untuk export log aktivitas
     * Mendukung format CSV dan PDF
     */
    public function export()
    {
        // Ambil format export dari URL, default CSV
        $format = $this->request->getVar('format') ?? 'csv';
        
        // Ambil parameter filter dari URL
        $role = $this->request->getVar('role'); // Filter role
        $dateFrom = $this->request->getVar('date_from'); // Filter tanggal mulai
        $dateTo = $this->request->getVar('date_to'); // Filter tanggal akhir

        // Inisialisasi query builder dengan urutan terbaru dulu
        $builder = $this->logModel->orderBy('created_at', 'DESC');

        // Terapkan filter jika ada
        // Filter berdasarkan role
        if ($role) {
            $builder->where('role', $role);
        }
        
        // Filter berdasarkan tanggal mulai
        if ($dateFrom) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        
        // Filter berdasarkan tanggal akhir
        if ($dateTo) {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        // Ambil semua data log sesuai filter
        $logs = $builder->findAll();

        // Export sesuai format yang dipilih
        if ($format === 'csv') {
            // Export ke CSV
            return $this->exportCSV($logs);
        } else {
            // Export ke PDF
            return $this->exportPDF($logs);
        }
    }

    /**
     * Method private untuk export log ke format CSV
     * 
     * @param array $logs Data log yang akan di-export
     */
    private function exportCSV($logs)
    {
        // Buat nama file dengan timestamp
        $filename = 'log_aktivitas_' . date('Y-m-d_His') . '.csv';
        
        // Set header untuk download file CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Buka output stream untuk menulis CSV
        $output = fopen('php://output', 'w');
        
        // Tulis header kolom CSV
        fputcsv($output, ['No', 'Nama User', 'Role', 'Aktivitas', 'IP Address', 'Waktu']);
        
        // Tulis data log ke CSV
        $no = 1; // Nomor urut
        foreach ($logs as $log) {
            // Tulis setiap baris data
            fputcsv($output, [
                $no++, // Nomor urut
                $log['nama_user'], // Nama user
                $log['role'], // Role user
                $log['aktivitas'], // Aktivitas yang dilakukan
                $log['ip_address'] ?? '-', // IP address, jika ada
                $log['created_at'] // Waktu aktivitas
            ]);
        }
        
        // Tutup output stream
        fclose($output);
        
        // Catat aktivitas export ke log sistem
        logExport('log aktivitas', 'CSV');
        
        // Hentikan eksekusi script (file sudah di-download)
        exit;
    }

    /**
     * Method private untuk export log ke format PDF
     * 
     * @param array $logs Data log yang akan di-export
     */
    private function exportPDF($logs)
    {
        // Siapkan data untuk view PDF
        $data['logs'] = $logs; // Data log
        $data['title'] = 'Laporan Log Aktivitas'; // Judul laporan
        $data['date'] = date('d-m-Y H:i:s'); // Tanggal export
        
        // Catat aktivitas export ke log sistem
        logExport('log aktivitas', 'PDF');
        
        // Render view PDF dengan data yang sudah disiapkan
        return view('admin/log/export_pdf', $data);
    }

    /**
     * Method untuk menampilkan statistik log aktivitas
     * Menampilkan berbagai statistik dan grafik aktivitas
     */
    public function stats()
    {
        // Hitung total semua log di database
        $data['total_logs'] = $this->logModel->countAll();
        
        // Hitung log hari ini
        $data['today_logs'] = $this->logModel
            ->where('DATE(created_at)', date('Y-m-d')) // Filter tanggal hari ini
            ->countAllResults(); // Hitung jumlah
        
        // Statistik aktivitas per role
        // GROUP BY role untuk menghitung jumlah per role
        $data['by_role'] = $this->logModel
            ->select('role, COUNT(*) as total') // Pilih role dan hitung total
            ->groupBy('role') // Group berdasarkan role
            ->findAll(); // Ambil semua data
        
        // Statistik aktivitas per user (top 10 user paling aktif)
        $data['top_users'] = $this->logModel
            ->select('nama_user, COUNT(*) as total') // Pilih nama user dan hitung total
            ->groupBy('nama_user') // Group berdasarkan nama user
            ->orderBy('total', 'DESC') // Urutkan dari yang terbanyak
            ->limit(10) // Batasi 10 user teratas
            ->findAll(); // Ambil semua data
        
        // Statistik aktivitas 7 hari terakhir (untuk grafik)
        $data['last_7_days'] = []; // Inisialisasi array kosong
        
        // Loop 7 hari terakhir (dari 6 hari lalu sampai hari ini)
        for ($i = 6; $i >= 0; $i--) {
            // Hitung tanggal (X hari yang lalu)
            $date = date('Y-m-d', strtotime("-{$i} days"));
            
            // Hitung jumlah log pada tanggal tersebut
            $count = $this->logModel
                ->where('DATE(created_at)', $date) // Filter berdasarkan tanggal
                ->countAllResults(); // Hitung jumlah
            
            // Simpan data tanggal dan jumlah ke array
            $data['last_7_days'][] = [
                'date' => $date, // Tanggal
                'count' => $count // Jumlah aktivitas
            ];
        }

        // Render view statistik dengan data yang sudah disiapkan
        return view('admin/log/stats', $data);
    }
}

