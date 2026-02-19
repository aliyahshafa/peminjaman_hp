# 📝 Panduan Update View Sesuai Database

## ✅ File yang Sudah Diupdate

1. ✅ `app/Views/admin/alat/index.php`
2. ✅ `app/Views/admin/alat/create.php`
3. ✅ `app/Views/admin/alat/edit.php`
4. ✅ `app/Views/admin/peminjaman/index.php`

## 🔄 Perubahan Field Database

### Tabel Alat (HP)
| Field Lama (View) | Field Baru (Database) | Keterangan |
|-------------------|----------------------|------------|
| `kode_alat` | - | Tidak ada, hapus |
| `nama_alat` | `merk` + `tipe` | Gabungan 2 field |
| `kategori` | `nama_category` | Dari join table category |
| `stok` | - | Tidak ada, pakai `status` |
| `lokasi` | - | Tidak ada, hapus |
| `deskripsi` | - | Tidak ada, hapus |

### Tabel Peminjaman
| Field Lama (View) | Field Baru (Database) | Keterangan |
|-------------------|----------------------|------------|
| `tanggal_pinjam` | `waktu` | Rename |
| `tanggal_kembali` | `tanggal_dikembalikan` | Rename |
| `keperluan` | `catatan` | Rename |
| `kondisi_kembali` | `kondisi_hp` | Rename |
| `nama_peminjam` | `nama_user` | Dari field peminjaman |
| `nama_alat` | `merk` + `tipe` | Dari join table alat |

### Status Peminjaman
- `Menunggu` → `Diajukan`
- `Disetujui` → `Disetujui` (sama)
- `Ditolak` → Tidak ada (hapus)
- `Dikembalikan` → `Dikembalikan` (sama)
- Tambahan: `Menunggu Pengembalian`

## 📋 Checklist Update Per File

### Admin - Alat
- [x] index.php - Sudah diupdate
- [x] create.php - Sudah diupdate
- [x] edit.php - Sudah diupdate

### Admin - Peminjaman
- [x] index.php - Sudah diupdate
- [ ] edit.php - Perlu update

### Admin - Pengembalian
- [ ] index.php - Perlu update

### Peminjam - Alat
- [ ] index.php - Perlu update

### Peminjam - Peminjaman
- [ ] index.php - Perlu update
- [ ] create.php - Perlu update

### Peminjam - Pengembalian
- [ ] index.php - Perlu update

### Petugas - Alat
- [ ] index.php - Perlu update

### Petugas - Peminjaman
- [ ] index.php - Perlu update

### Petugas - Pengembalian
- [ ] index.php - Perlu update

### Petugas - Laporan
- [ ] index.php - Perlu update
- [ ] cetak.php - Perlu update

### Dashboard
- [ ] admin.php - Perlu update
- [ ] petugas.php - Perlu update
- [ ] peminjam.php - Perlu update

## 🔧 Cara Update Manual

### 1. Update Field Alat

**Ganti:**
```php
<?= esc($item['nama_alat']) ?>
```

**Dengan:**
```php
<?= esc($item['merk']) ?> <?= esc($item['tipe']) ?>
```

**Ganti:**
```php
<?= esc($item['kode_alat']) ?>
```

**Dengan:**
```php
<?= esc($item['merk']) ?>
```

**Ganti:**
```php
<?= esc($item['kategori']) ?>
```

**Dengan:**
```php
<?= esc($item['nama_category'] ?? '-') ?>
```

**Hapus:**
```php
<td><?= $item['stok'] ?></td>
```

### 2. Update Field Peminjaman

**Ganti:**
```php
<?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?>
```

**Dengan:**
```php
<?= date('d/m/Y', strtotime($p['waktu'])) ?>
```

**Ganti:**
```php
<?= date('d/m/Y', strtotime($p['tanggal_kembali'])) ?>
```

**Dengan:**
```php
<?= $p['tanggal_dikembalikan'] ? date('d/m/Y', strtotime($p['tanggal_dikembalikan'])) : '-' ?>
```

**Ganti:**
```php
<?= esc($p['nama_peminjam']) ?>
```

**Dengan:**
```php
<?= esc($p['nama_user']) ?>
```

**Ganti:**
```php
<?= esc($p['nama_alat']) ?>
```

**Dengan:**
```php
<?= esc($p['merk']) ?> <?= esc($p['tipe']) ?>
```

### 3. Update Status Badge

**Ganti:**
```php
<?php if ($p['status'] == 'Menunggu'): ?>
    <span class="badge badge-warning">Menunggu</span>
```

**Dengan:**
```php
<?php if ($p['status'] == 'Diajukan'): ?>
    <span class="badge badge-warning">Diajukan</span>
```

**Tambahkan:**
```php
<?php elseif ($p['status'] == 'Menunggu Pengembalian'): ?>
    <span class="badge badge-info">Menunggu Pengembalian</span>
```

**Hapus:**
```php
<?php elseif ($p['status'] == 'Ditolak'): ?>
    <span class="badge badge-danger">Ditolak</span>
```

### 4. Update Form Input

**Form Alat - Ganti:**
```php
<input type="text" name="kode_alat" ...>
<input type="text" name="nama_alat" ...>
<input type="number" name="stok" ...>
```

**Dengan:**
```php
<input type="text" name="merk" ...>
<input type="text" name="tipe" ...>
<select name="id_category" ...>
```

**Form Peminjaman - Ganti:**
```php
<input type="date" name="tanggal_pinjam" ...>
<input type="date" name="tanggal_kembali" ...>
<textarea name="keperluan" ...>
```

**Dengan:**
```php
<input type="date" name="waktu" ...>
<input type="date" name="tanggal_dikembalikan" ...>
<textarea name="catatan" ...>
```

## 🚀 Quick Fix Script

Buat file `fix_views.php` di root project:

```php
<?php
// Script untuk replace field lama dengan field baru
$replacements = [
    // Alat
    "['nama_alat']" => "['merk'] . ' ' . \$item['tipe']",
    "['kode_alat']" => "['merk']",
    "['kategori']" => "['nama_category']",
    
    // Peminjaman
    "['tanggal_pinjam']" => "['waktu']",
    "['tanggal_kembali']" => "['tanggal_dikembalikan']",
    "['nama_peminjam']" => "['nama_user']",
    "['keperluan']" => "['catatan']",
    "['kondisi_kembali']" => "['kondisi_hp']",
    
    // Status
    "'Menunggu'" => "'Diajukan'",
    "badge-warning\">Menunggu" => "badge-warning\">Diajukan",
];

$files = [
    'app/Views/peminjam/alat/index.php',
    'app/Views/peminjam/peminjaman/index.php',
    'app/Views/peminjam/peminjaman/create.php',
    'app/Views/peminjam/pengembalian/index.php',
    'app/Views/petugas/alat/index.php',
    'app/Views/petugas/peminjaman/index.php',
    'app/Views/petugas/pengembalian/index.php',
    'app/Views/petugas/laporan/index.php',
    'app/Views/petugas/laporan/cetak.php',
    'app/Views/admin/peminjaman/edit.php',
    'app/Views/admin/pengembalian/index.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        foreach ($replacements as $old => $new) {
            $content = str_replace($old, $new, $content);
        }
        file_put_contents($file, $content);
        echo "✅ Updated: $file\n";
    }
}

echo "\n🎉 Done! All files updated.\n";
```

Jalankan:
```bash
php fix_views.php
```

## ⚠️ Catatan Penting

1. **Backup dulu** semua file sebelum update
2. **Test satu per satu** setelah update
3. **Cek controller** apakah sudah mengirim data yang benar
4. **Join table** pastikan query di controller sudah benar
5. **Status** pastikan menggunakan status yang benar

## 🎯 Prioritas Update

1. **High Priority** (Harus diupdate):
   - Admin Alat (✅ Done)
   - Admin Peminjaman (✅ Done)
   - Peminjam Alat
   - Peminjam Peminjaman

2. **Medium Priority**:
   - Petugas Peminjaman
   - Dashboard (semua)
   - Pengembalian (semua)

3. **Low Priority**:
   - Laporan
   - Profile (tidak perlu update)

## 📞 Jika Ada Error

1. Cek nama field di database
2. Cek query join di controller
3. Cek apakah data sudah dikirim ke view
4. Lihat error di browser console atau log CodeIgniter

Semoga membantu! 🚀
