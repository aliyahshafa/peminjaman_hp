# 📋 Dokumentasi Sistem Log Aktivitas

## Daftar Isi
1. [Fitur Utama](#fitur-utama)
2. [Instalasi](#instalasi)
3. [Penggunaan Helper Functions](#penggunaan-helper-functions)
4. [Endpoint API](#endpoint-api)
5. [Contoh Implementasi](#contoh-implementasi)
6. [Maintenance](#maintenance)

---

## Fitur Utama

### ✅ Fitur yang Tersedia

1. **Pencatatan Otomatis**
   - ID User dan nama user
   - Role pengguna
   - Deskripsi aktivitas
   - IP Address
   - User Agent (browser/device)
   - HTTP Method (GET, POST, dll)
   - URL yang diakses
   - Timestamp otomatis

2. **Filter dan Pencarian**
   - Filter berdasarkan role
   - Filter berdasarkan user
   - Filter berdasarkan rentang tanggal
   - Pencarian teks pada aktivitas

3. **Statistik dan Laporan**
   - Total log aktivitas
   - Aktivitas hari ini
   - Aktivitas per role
   - Top 10 user paling aktif
   - Grafik aktivitas 7 hari terakhir

4. **Export Data**
   - Export ke CSV
   - Export ke PDF (print-friendly)

5. **Maintenance**
   - Hapus log lama berdasarkan periode
   - Pagination untuk performa

---

## Instalasi

### 1. Update Database

Jalankan SQL berikut untuk menambah kolom baru:

```sql
ALTER TABLE log_aktivitas 
ADD COLUMN IF NOT EXISTS ip_address VARCHAR(45) AFTER aktivitas,
ADD COLUMN IF NOT EXISTS user_agent TEXT AFTER ip_address,
ADD COLUMN IF NOT EXISTS method VARCHAR(10) AFTER user_agent,
ADD COLUMN IF NOT EXISTS url TEXT AFTER method;
```

Atau gunakan file `database_log_aktivitas.sql` yang sudah disediakan.

### 2. Update Routes

Tambahkan routes berikut di `app/Config/Routes.php`:

```php
// Log Routes (Admin only)
$routes->group('admin/log', ['filter' => 'role:Admin'], function($routes) {
    $routes->get('/', 'LogController::index');
    $routes->get('stats', 'LogController::stats');
    $routes->get('export', 'LogController::export');
    $routes->get('clear', 'LogController::clear');
    $routes->post('clear', 'LogController::clear');
});
```

### 3. Load Helper

Pastikan helper log dimuat di `BaseController.php`:

```php
protected $helpers = ['log'];
```

Atau load manual di controller yang membutuhkan:

```php
helper('log');
```

---

## Penggunaan Helper Functions

### 1. Log Aktivitas Umum

```php
// Log aktivitas sederhana
logAktivitas('Mengakses halaman dashboard');

// Log dengan detail tambahan
logAktivitas('Mengubah pengaturan sistem', [
    'detail' => 'Mengubah timeout session menjadi 3600 detik'
]);
```

### 2. Log Login/Logout

```php
// Login berhasil
logLogin($userId, $namaUser, $role, 'success');

// Login gagal
logLogin($userId, $namaUser, $role, 'failed');

// Logout
logLogout();
```

### 3. Log CRUD Operations

```php
// Create
logCreate('alat', 'Mikroskop Digital XYZ-100');

// Update
logUpdate('user', 'John Doe');

// Delete
logDelete('peminjaman', 'PJ-2024-001');

// View
logView('laporan peminjaman', 'Januari 2024');
```

### 4. Log Export

```php
logExport('data peminjaman', 'PDF');
logExport('laporan bulanan', 'Excel');
```

### 5. Log Peminjaman (Khusus)

```php
// Membuat peminjaman
logPeminjaman('create', 'ALT-001', 'John Doe');

// Menyetujui peminjaman
logPeminjaman('approve', 'ALT-001', 'John Doe');

// Menolak peminjaman
logPeminjaman('reject', 'ALT-001', 'John Doe');

// Mengembalikan alat
logPeminjaman('return', 'ALT-001', 'John Doe');

// Membatalkan peminjaman
logPeminjaman('cancel', 'ALT-001', 'John Doe');
```

---

## Endpoint API

### 1. Lihat Log (dengan filter)

**URL:** `/admin/log`  
**Method:** GET  
**Parameters:**
- `role` - Filter berdasarkan role
- `user` - Filter berdasarkan ID user
- `date_from` - Tanggal mulai (YYYY-MM-DD)
- `date_to` - Tanggal akhir (YYYY-MM-DD)
- `search` - Pencarian teks
- `page` - Halaman pagination

**Contoh:**
```
/admin/log?role=Admin&date_from=2024-01-01&date_to=2024-01-31
```

### 2. Statistik Log

**URL:** `/admin/log/stats`  
**Method:** GET

### 3. Export Log

**URL:** `/admin/log/export`  
**Method:** GET  
**Parameters:**
- `format` - csv atau pdf
- `role` - Filter role (opsional)
- `date_from` - Tanggal mulai (opsional)
- `date_to` - Tanggal akhir (opsional)

**Contoh:**
```
/admin/log/export?format=csv&role=Peminjam
```

### 4. Bersihkan Log Lama

**URL:** `/admin/log/clear`  
**Method:** GET (form) / POST (submit)  
**Parameters (POST):**
- `days` - Hapus log lebih dari X hari

---

## Contoh Implementasi

### Contoh 1: Log di Controller Alat

```php
<?php
namespace App\Controllers;

class AlatController extends BaseController
{
    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();
            
            if ($this->alatModel->insert($data)) {
                // Log aktivitas
                logCreate('alat', $data['nama_alat']);
                
                return redirect()->to('/admin/alat')
                    ->with('success', 'Alat berhasil ditambahkan');
            }
        }
        
        return view('admin/alat/create');
    }
    
    public function update($id)
    {
        $alat = $this->alatModel->find($id);
        
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();
            
            if ($this->alatModel->update($id, $data)) {
                // Log aktivitas
                logUpdate('alat', $alat['nama_alat']);
                
                return redirect()->to('/admin/alat')
                    ->with('success', 'Alat berhasil diupdate');
            }
        }
        
        return view('admin/alat/edit', ['alat' => $alat]);
    }
    
    public function delete($id)
    {
        $alat = $this->alatModel->find($id);
        
        if ($this->alatModel->delete($id)) {
            // Log aktivitas
            logDelete('alat', $alat['nama_alat']);
            
            return redirect()->to('/admin/alat')
                ->with('success', 'Alat berhasil dihapus');
        }
    }
}
```

### Contoh 2: Log di Controller Auth

```php
<?php
namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            
            $user = $this->userModel->where('username', $username)->first();
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session
                session()->set([
                    'id_user' => $user['id_user'],
                    'nama_user' => $user['nama_user'],
                    'role' => $user['role'],
                    'logged_in' => true
                ]);
                
                // Log login berhasil
                logLogin($user['id_user'], $user['nama_user'], $user['role'], 'success');
                
                return redirect()->to('/dashboard');
            } else {
                // Log login gagal
                logLogin(0, $username, 'Unknown', 'failed');
                
                return redirect()->back()
                    ->with('error', 'Username atau password salah');
            }
        }
        
        return view('auth/login');
    }
    
    public function logout()
    {
        // Log logout
        logLogout();
        
        session()->destroy();
        return redirect()->to('/login');
    }
}
```

### Contoh 3: Log di Controller Peminjaman

```php
<?php
namespace App\Controllers;

class PeminjamanController extends BaseController
{
    public function approve($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        
        $data = [
            'status' => 'Disetujui',
            'tanggal_disetujui' => date('Y-m-d H:i:s')
        ];
        
        if ($this->peminjamanModel->update($id, $data)) {
            // Log aktivitas peminjaman
            logPeminjaman(
                'approve', 
                $peminjaman['id_hp'], 
                $peminjaman['nama_peminjam']
            );
            
            return redirect()->to('/admin/peminjaman')
                ->with('success', 'Peminjaman berhasil disetujui');
        }
    }
    
    public function return($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        
        $data = [
            'status' => 'Dikembalikan',
            'tanggal_kembali' => date('Y-m-d H:i:s')
        ];
        
        if ($this->peminjamanModel->update($id, $data)) {
            // Update stok alat
            $this->alatModel->increment('stok', $peminjaman['id_alat']);
            
            // Log aktivitas pengembalian
            logPeminjaman(
                'return', 
                $peminjaman['id_hp'], 
                $peminjaman['nama_peminjam']
            );
            
            return redirect()->to('/admin/pengembalian')
                ->with('success', 'Alat berhasil dikembalikan');
        }
    }
}
```

---

## Maintenance

### Pembersihan Log Otomatis

Anda bisa membuat cron job atau scheduled task untuk membersihkan log lama secara otomatis:

```php
// app/Commands/CleanOldLogs.php
<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use App\Models\LogModel;

class CleanOldLogs extends BaseCommand
{
    protected $group = 'maintenance';
    protected $name = 'logs:clean';
    protected $description = 'Hapus log aktivitas yang lebih lama dari 30 hari';

    public function run(array $params)
    {
        $logModel = new LogModel();
        $date = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        $deleted = $logModel->where('created_at <', $date)->delete();
        
        $this->write("Berhasil menghapus {$deleted} log lama.", 'green');
    }
}
```

Jalankan dengan:
```bash
php spark logs:clean
```

### Tips Performa

1. **Indexing**: Pastikan kolom yang sering difilter memiliki index
2. **Pagination**: Gunakan pagination untuk menampilkan data
3. **Archive**: Pindahkan log lama ke tabel archive jika diperlukan
4. **Cleanup**: Hapus log lama secara berkala

---

## Troubleshooting

### Log tidak tercatat

1. Pastikan helper log sudah dimuat
2. Cek apakah session user sudah diset
3. Periksa koneksi database

### Error saat export

1. Pastikan folder writable untuk temporary files
2. Cek memory limit PHP untuk data besar

### Performa lambat

1. Tambahkan index pada kolom yang sering difilter
2. Gunakan pagination
3. Hapus log lama secara berkala

---

## Kesimpulan

Sistem log aktivitas ini memberikan:
- ✅ Tracking lengkap semua aktivitas user
- ✅ Filter dan pencarian yang fleksibel
- ✅ Statistik dan visualisasi data
- ✅ Export untuk reporting
- ✅ Maintenance tools untuk performa optimal

Untuk pertanyaan atau masalah, silakan hubungi tim development.
