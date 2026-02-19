# DEBUG: Harga Ada di Database Tapi Web Masih Rp 0

## MASALAH
- ✅ Database sudah ada kolom harga dengan nilai > 0
- ❌ Web masih menampilkan Rp 0

## KEMUNGKINAN PENYEBAB
1. **Query controller tidak mengambil kolom harga**
2. **Cache browser/session**
3. **Masalah di view/template**
4. **Data tidak ter-join dengan benar**

## PERBAIKAN YANG SUDAH DILAKUKAN

### 1. AdminController
**Sebelum:**
```php
$data['recentAlat'] = $alatModel->select('alat.*, category.nama_category')
    ->join('category', 'category.id_category = alat.id_category', 'left')
    ->orderBy('alat.created_at', 'DESC')
```

**Sesudah:**
```php
$data['recentAlat'] = $alatModel->select('alat.id_hp, alat.merk, alat.tipe, alat.harga, alat.kondisi, alat.status, category.nama_category')
    ->join('category', 'category.id_category = alat.id_category', 'left')
    ->orderBy('alat.id_hp', 'DESC')
```

### 2. PetugasController
**Sudah benar** - sudah ada `alat.harga` di select

### 3. PeminjamanModel
**Sudah benar** - sudah ada `alat.harga` di select

### 4. View Debug
**Ditambahkan debug comment** di semua dashboard untuk melihat nilai harga sebenarnya

## LANGKAH DEBUGGING

### Step 1: Jalankan Test Controller
Buka di browser: `http://localhost/your-project/TEST_CONTROLLER_HARGA.php`

**Cek hasil:**
- Apakah direct database query menunjukkan harga > 0?
- Apakah controller query menunjukkan harga > 0?
- Apakah ada perbedaan antara keduanya?

### Step 2: Cek Debug Comment di Dashboard
1. Login ke dashboard admin/petugas/peminjam
2. Lihat source code halaman (Ctrl+U)
3. Cari comment `<!-- DEBUG: HARGA: ... -->`
4. Lihat apakah ada nilai harga atau "HARGA NOT SET"

### Step 3: Clear Cache
1. **Browser Cache:** Ctrl+F5 atau clear browser cache
2. **Session:** Logout dan login kembali
3. **CodeIgniter Cache:** Hapus folder `writable/cache/`

## KEMUNGKINAN HASIL DEBUG

### Jika Direct Query = Harga > 0, Controller Query = 0
**Masalah:** Query controller salah
**Solusi:** Perbaiki query di controller

### Jika Controller Query = Harga > 0, Web = Rp 0
**Masalah:** View atau cache
**Solusi:** Clear cache, cek debug comment

### Jika Debug Comment = "HARGA NOT SET"
**Masalah:** Kolom harga tidak ada di array hasil query
**Solusi:** Perbaiki select query

### Jika Debug Comment = "HARGA: 0"
**Masalah:** Data di database masih 0
**Solusi:** Update data database

### Jika Debug Comment = "HARGA: 150000" tapi tampil Rp 0
**Masalah:** Function number_format atau fallback
**Solusi:** Cek syntax PHP di view

## SOLUSI BERDASARKAN HASIL

### Jika Masalah di Query:
```php
// Pastikan select explicit include harga
->select('alat.id_hp, alat.merk, alat.tipe, alat.harga, ...')
```

### Jika Masalah di View:
```php
// Cek syntax number_format
<?= number_format($item['harga'] ?? 0, 0, ',', '.') ?>

// Atau coba tanpa number_format dulu
<?= $item['harga'] ?? 0 ?>
```

### Jika Masalah Cache:
1. Clear browser cache (Ctrl+F5)
2. Logout/login kembali
3. Restart web server jika perlu

## NEXT STEPS
1. Jalankan `TEST_CONTROLLER_HARGA.php`
2. Cek debug comment di dashboard
3. Report hasil untuk analisis lebih lanjut

**Setelah debug, kita bisa hapus comment debug dan fix masalah yang ditemukan.**