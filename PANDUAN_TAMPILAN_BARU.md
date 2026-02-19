# 🎨 Panduan Implementasi Tampilan Baru

## Yang Sudah Dibuat

### 1. **CSS Framework**
- `public/assets/css/style.css` - Stylesheet lengkap dan responsif

### 2. **Layout Template**
- `app/Views/layouts/template.php` - Template utama dengan sidebar dan topbar

### 3. **Halaman Auth**
- `app/Views/auth/login.php` - Halaman login modern dan responsif

### 4. **Halaman User Management**
- `app/Views/user/index.php` - Daftar user dengan search
- `app/Views/user/create.php` - Form tambah user
- `app/Views/user/edit.php` - Form edit user

### 5. **Dashboard**
- `app/Views/dashboard/admin.php` - Dashboard admin dengan statistik

---

## Cara Menggunakan Template

### 1. Untuk Halaman Baru

Buat file view baru dengan struktur:

```php
<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<!-- Konten halaman di sini -->
<div class="page-header">
    <h2 class="page-title">Judul Halaman</h2>
    <p class="page-subtitle">Deskripsi halaman</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Judul Card</h3>
    </div>
    <div class="card-body">
        <!-- Konten card -->
    </div>
</div>

<?= $this->endSection() ?>
```

### 2. Menambahkan CSS/JS Khusus

```php
<?= $this->extend('layouts/template') ?>

<?= $this->section('styles') ?>
<style>
    /* CSS khusus untuk halaman ini */
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Konten -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // JavaScript khusus untuk halaman ini
</script>
<?= $this->endSection() ?>
```

---

## Komponen yang Tersedia

### 1. **Buttons**

```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-warning">Warning</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-outline">Outline</button>

<!-- Ukuran -->
<button class="btn btn-primary btn-sm">Small</button>
<button class="btn btn-primary">Normal</button>
<button class="btn btn-primary btn-lg">Large</button>

<!-- Dengan icon -->
<button class="btn btn-primary">
    <i class="fas fa-save"></i> Simpan
</button>
```

### 2. **Cards**

```html
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Judul Card</h3>
        <button class="btn btn-primary btn-sm">Action</button>
    </div>
    <div class="card-body">
        Konten card
    </div>
    <div class="card-footer">
        Footer card
    </div>
</div>
```

### 3. **Stats Cards**

```html
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-details">
            <h3>150</h3>
            <p>Total User</p>
        </div>
    </div>
</div>
```

### 4. **Forms**

```html
<form>
    <div class="form-group">
        <label class="form-label required">Nama</label>
        <input type="text" class="form-control" placeholder="Masukkan nama">
        <small class="form-text">Teks bantuan</small>
    </div>
    
    <!-- Form Row (2 kolom) -->
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" class="form-control">
        </div>
        <div class="form-group">
            <label class="form-label">No. HP</label>
            <input type="text" class="form-control">
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

### 5. **Tables**

```html
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>john@example.com</td>
                <td>
                    <button class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

### 6. **Badges**

```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-danger">Danger</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-secondary">Secondary</span>
```

### 7. **Alerts**

```html
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    Berhasil menyimpan data!
</div>

<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i>
    Terjadi kesalahan!
</div>

<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    Peringatan!
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    Informasi penting
</div>
```

### 8. **Search Box**

```html
<div class="search-box">
    <form method="get">
        <i class="fas fa-search"></i>
        <input type="text" name="keyword" class="form-control" placeholder="Cari...">
    </form>
</div>
```

### 9. **Empty State**

```html
<div class="empty-state">
    <i class="fas fa-inbox"></i>
    <h3>Tidak ada data</h3>
    <p>Belum ada data yang tersedia</p>
</div>
```

---

## Halaman yang Perlu Dibuat Ulang

### ✅ Sudah Dibuat:
- [x] Login
- [x] User Index
- [x] User Create
- [x] User Edit
- [x] Dashboard Admin

### 📝 Perlu Dibuat:
- [ ] Dashboard Petugas
- [ ] Dashboard Peminjam
- [ ] Alat Index
- [ ] Alat Create
- [ ] Alat Edit
- [ ] Peminjaman Index
- [ ] Peminjaman Create
- [ ] Peminjaman Edit
- [ ] Pengembalian Index
- [ ] Profile Index
- [ ] Profile Edit
- [ ] Laporan (Petugas)

---

## Tips Responsive Design

### 1. Grid System
```html
<!-- Auto-fit columns -->
<div class="stats-grid">
    <!-- Otomatis menyesuaikan jumlah kolom -->
</div>

<!-- Form row -->
<div class="form-row">
    <!-- 2 kolom di desktop, 1 kolom di mobile -->
</div>
```

### 2. Utility Classes
```html
<!-- Display -->
<div class="d-flex justify-between align-center gap-2">
    <!-- Flex container -->
</div>

<!-- Spacing -->
<div class="mt-2 mb-3">
    <!-- Margin top 20px, margin bottom 30px -->
</div>

<!-- Text -->
<p class="text-center text-primary">Centered primary text</p>

<!-- Width -->
<div class="w-100">Full width</div>
```

### 3. Breakpoints
- Desktop: > 768px
- Mobile: ≤ 768px

---

## Contoh Implementasi Lengkap

### Halaman Alat Index

```php
<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <h2 class="page-title">Kelola Alat</h2>
    <p class="page-subtitle">Manajemen data alat</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-box"></i> Daftar Alat</h3>
        <a href="<?= base_url('/admin/alat/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Alat
        </a>
    </div>
    <div class="card-body">
        <div class="search-box">
            <form method="get">
                <i class="fas fa-search"></i>
                <input type="text" name="keyword" class="form-control" placeholder="Cari alat...">
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alat as $key => $item): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= esc($item['kode_alat']) ?></td>
                        <td><?= esc($item['nama_alat']) ?></td>
                        <td><?= esc($item['kategori']) ?></td>
                        <td><?= $item['stok'] ?></td>
                        <td>
                            <?php if ($item['stok'] > 0): ?>
                                <span class="badge badge-success">Tersedia</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Habis</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('/admin/alat/edit/' . $item['id_alat']) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url('/admin/alat/delete/' . $item['id_alat']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
```

---

## Troubleshooting

### 1. CSS tidak muncul
- Pastikan file `public/assets/css/style.css` ada
- Cek base_url di config
- Clear browser cache

### 2. Sidebar tidak muncul
- Pastikan menggunakan `$this->extend('layouts/template')`
- Cek session user sudah diset

### 3. Responsive tidak bekerja
- Pastikan ada meta viewport di head
- Test di browser dengan dev tools

---

## Next Steps

1. Buat halaman-halaman yang belum ada menggunakan template
2. Sesuaikan controller untuk mengirim data ke view
3. Test responsive di berbagai device
4. Tambahkan fitur tambahan sesuai kebutuhan

Selamat mengembangkan! 🚀
