# 📋 Sistem Log Aktivitas Lengkap

Sistem log aktivitas yang komprehensif untuk aplikasi peminjaman alat dengan fitur tracking, filtering, statistik, dan export.

## 🚀 Quick Start

### 1. Update Database

Jalankan SQL berikut:

```sql
ALTER TABLE log_aktivitas 
ADD COLUMN IF NOT EXISTS ip_address VARCHAR(45) AFTER aktivitas,
ADD COLUMN IF NOT EXISTS user_agent TEXT AFTER ip_address,
ADD COLUMN IF NOT EXISTS method VARCHAR(10) AFTER user_agent,
ADD COLUMN IF NOT EXISTS url TEXT AFTER method;
```

Atau gunakan file `database_log_aktivitas.sql`

### 2. Tambahkan Routes

Tambahkan ke `app/Config/Routes.php`:

```php
$routes->group('admin/log', ['filter' => 'role:Admin'], function($routes) {
    $routes->get('/', 'LogController::index');
    $routes->get('stats', 'LogController::stats');
    $routes->get('export', 'LogController::export');
    $routes->get('clear', 'LogController::clear');
    $routes->post('clear', 'LogController::clear');
});
```

Lihat file `ROUTES_LOG.php` untuk contoh lengkap.

### 3. Load Helper

Di `app/Controllers/BaseController.php`:

```php
protected $helpers = ['log'];
```

### 4. Gunakan di Controller

```php
// Login
logLogin($userId, $namaUser, $role, 'success');

// CRUD
logCreate('alat', 'Mikroskop XYZ');
logUpdate('user', 'John Doe');
logDelete('peminjaman', 'PJ-001');
logView('dashboard');

// Peminjaman
logPeminjaman('approve', 'ALT-001', 'John Doe');

// Export
logExport('laporan', 'PDF');
```

## ✨ Fitur Utama

### 1. Tracking Lengkap
- User ID & nama
- Role pengguna
- Aktivitas detail
- IP Address
- User Agent (browser/device)
- HTTP Method
- URL yang diakses
- Timestamp otomatis

### 2. Filter & Pencarian
- Filter by role
- Filter by user
- Filter by date range
- Search aktivitas

### 3. Statistik
- Total log
- Aktivitas hari ini
- Aktivitas per role
- Top 10 user aktif
- Grafik 7 hari terakhir

### 4. Export
- CSV format
- PDF format (print-friendly)

### 5. Maintenance
- Hapus log lama
- Pagination otomatis

## 📁 File yang Dibuat

### Controllers
- `app/Controllers/LogController.php` - Controller utama log

### Models
- `app/Models/LogModel.php` - Model log (updated)

### Helpers
- `app/Helpers/log_helper.php` - Helper functions (updated)

### Views
- `app/Views/admin/log/index.php` - Daftar log dengan filter
- `app/Views/admin/log/stats.php` - Statistik log
- `app/Views/admin/log/clear.php` - Form hapus log lama
- `app/Views/admin/log/export_pdf.php` - Template export PDF

### Database
- `database_log_aktivitas.sql` - SQL untuk update tabel

### Dokumentasi
- `DOKUMENTASI_LOG_AKTIVITAS.md` - Dokumentasi lengkap
- `CONTOH_IMPLEMENTASI_LOG.md` - Contoh implementasi di controller
- `ROUTES_LOG.php` - Routes yang perlu ditambahkan
- `README_LOG_AKTIVITAS.md` - File ini

## 🔧 Helper Functions

### logAktivitas()
```php
logAktivitas('Mengakses halaman dashboard');
logAktivitas('Mengubah pengaturan', ['detail' => 'Timeout 3600s']);
```

### logLogin() / logLogout()
```php
logLogin($userId, $namaUser, $role, 'success');
logLogin($userId, $namaUser, $role, 'failed');
logLogout();
```

### logCreate() / logUpdate() / logDelete() / logView()
```php
logCreate('alat', 'Mikroskop XYZ');
logUpdate('user', 'John Doe');
logDelete('peminjaman', 'PJ-001');
logView('laporan peminjaman');
```

### logExport()
```php
logExport('data peminjaman', 'PDF');
logExport('laporan bulanan', 'Excel');
```

### logPeminjaman()
```php
logPeminjaman('create', 'ALT-001', 'John Doe');
logPeminjaman('approve', 'ALT-001', 'John Doe');
logPeminjaman('reject', 'ALT-001', 'John Doe');
logPeminjaman('return', 'ALT-001', 'John Doe');
logPeminjaman('cancel', 'ALT-001', 'John Doe');
```

## 🌐 Endpoints

| URL | Method | Deskripsi |
|-----|--------|-----------|
| `/admin/log` | GET | Daftar log dengan filter |
| `/admin/log/stats` | GET | Statistik log |
| `/admin/log/export` | GET | Export log (CSV/PDF) |
| `/admin/log/clear` | GET/POST | Hapus log lama |

### Query Parameters untuk Filter

```
/admin/log?role=Admin&date_from=2024-01-01&date_to=2024-01-31&search=login
```

- `role` - Filter by role
- `user` - Filter by user ID
- `date_from` - Tanggal mulai (YYYY-MM-DD)
- `date_to` - Tanggal akhir (YYYY-MM-DD)
- `search` - Cari dalam aktivitas
- `page` - Halaman pagination

### Export Parameters

```
/admin/log/export?format=csv&role=Peminjam&date_from=2024-01-01
```

- `format` - csv atau pdf
- `role` - Filter role (opsional)
- `date_from` - Filter tanggal mulai (opsional)
- `date_to` - Filter tanggal akhir (opsional)

## 📊 Contoh Implementasi

### Di AuthController

```php
public function login()
{
    if ($this->request->getMethod() === 'post') {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $user = $this->userModel->where('username', $username)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'id_user' => $user['id_user'],
                'nama_user' => $user['nama_user'],
                'role' => $user['role'],
                'logged_in' => true
            ]);
            
            logLogin($user['id_user'], $user['nama_user'], $user['role'], 'success');
            return redirect()->to('/dashboard');
        } else {
            logLogin(0, $username, 'Unknown', 'failed');
            return redirect()->back()->with('error', 'Login gagal');
        }
    }
    
    return view('auth/login');
}

public function logout()
{
    logLogout();
    session()->destroy();
    return redirect()->to('/login');
}
```

### Di AlatController

```php
public function create()
{
    if ($this->request->getMethod() === 'post') {
        $data = $this->request->getPost();
        
        if ($this->alatModel->insert($data)) {
            logCreate('alat', $data['nama_alat']);
            return redirect()->to('/admin/alat')->with('success', 'Berhasil');
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
            logUpdate('alat', $alat['nama_alat']);
            return redirect()->to('/admin/alat')->with('success', 'Berhasil');
        }
    }
    
    return view('admin/alat/edit', ['alat' => $alat]);
}

public function delete($id)
{
    $alat = $this->alatModel->find($id);
    
    if ($this->alatModel->delete($id)) {
        logDelete('alat', $alat['nama_alat']);
        return redirect()->to('/admin/alat')->with('success', 'Berhasil');
    }
}
```

### Di PeminjamanController

```php
public function approve($id)
{
    $peminjaman = $this->peminjamanModel->find($id);
    
    $data = ['status' => 'Disetujui'];
    
    if ($this->peminjamanModel->update($id, $data)) {
        logPeminjaman('approve', $peminjaman['id_hp'], $peminjaman['nama_peminjam']);
        return redirect()->to('/admin/peminjaman')->with('success', 'Berhasil');
    }
}
```

## 🔍 Testing Checklist

- [ ] Login/Logout tercatat
- [ ] CRUD alat tercatat
- [ ] CRUD user tercatat
- [ ] Peminjaman tercatat (create, approve, reject, return)
- [ ] Filter log berfungsi
- [ ] Statistik menampilkan data benar
- [ ] Export CSV berfungsi
- [ ] Export PDF berfungsi
- [ ] Clear log berfungsi
- [ ] Pagination berfungsi

## 🛠️ Maintenance

### Pembersihan Manual

Akses `/admin/log/clear` untuk menghapus log lama secara manual.

### Pembersihan Otomatis (Cron Job)

Buat command untuk cron job:

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
    protected $description = 'Hapus log lebih dari 30 hari';

    public function run(array $params)
    {
        $logModel = new LogModel();
        $date = date('Y-m-d H:i:s', strtotime('-30 days'));
        $deleted = $logModel->where('created_at <', $date)->delete();
        
        $this->write("Berhasil menghapus {$deleted} log lama.", 'green');
    }
}
```

Jalankan:
```bash
php spark logs:clean
```

Setup cron (Linux):
```bash
0 2 * * * cd /path/to/project && php spark logs:clean
```

## 📝 Tips

1. **Konsisten** - Gunakan helper yang sesuai untuk setiap aktivitas
2. **Deskriptif** - Buat deskripsi yang jelas
3. **Minimal** - Jangan log hal yang tidak penting
4. **Performa** - Gunakan pagination dan cleanup berkala

## 🐛 Troubleshooting

### Log tidak tercatat
- Pastikan helper sudah dimuat
- Cek session user sudah diset
- Periksa koneksi database

### Error saat export
- Pastikan folder writable
- Cek memory limit PHP untuk data besar

### Performa lambat
- Tambahkan index pada kolom yang sering difilter
- Gunakan pagination
- Hapus log lama secara berkala

## 📚 Dokumentasi Lengkap

Lihat file berikut untuk informasi lebih detail:

- `DOKUMENTASI_LOG_AKTIVITAS.md` - Dokumentasi lengkap semua fitur
- `CONTOH_IMPLEMENTASI_LOG.md` - Contoh implementasi di setiap controller
- `ROUTES_LOG.php` - Contoh routes lengkap

## 🎯 Kesimpulan

Sistem log aktivitas ini memberikan:

✅ Tracking lengkap semua aktivitas user  
✅ Filter dan pencarian yang fleksibel  
✅ Statistik dan visualisasi data  
✅ Export untuk reporting  
✅ Maintenance tools untuk performa optimal  

Selamat menggunakan! 🚀
