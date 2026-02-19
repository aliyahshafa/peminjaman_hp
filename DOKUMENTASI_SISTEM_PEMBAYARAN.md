# DOKUMENTASI SISTEM PEMBAYARAN

## Overview
Sistem pembayaran denda untuk peminjam yang sudah meminjam barang dan perlu membayar denda berdasarkan kondisi HP saat dikembalikan.

## Fitur Utama

### 1. **Halaman Pembayaran** (`/peminjam/pembayaran`)
- Menampilkan daftar peminjaman yang perlu dibayar dendanya
- Menampilkan riwayat pembayaran yang sudah dilakukan
- Status pembayaran (Belum Bayar, Lunas)

### 2. **Form Pembayaran** (`/peminjam/pembayaran/bayar/{id}`)
- Form untuk melakukan pembayaran denda
- Pilihan metode pembayaran (Tunai, Transfer Bank, E-Wallet, Kartu Kredit)
- Konfirmasi pembayaran dengan detail lengkap

### 3. **Detail Pembayaran** (`/peminjam/pembayaran/detail/{id}`)
- Informasi lengkap pembayaran yang sudah dilakukan
- Fitur cetak untuk bukti pembayaran
- Status lunas dengan konfirmasi visual

## Struktur Database

### Tabel `pembayaran`
```sql
CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `metode_bayar` varchar(50) NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `status_bayar` enum('Menunggu','Lunas','Gagal') DEFAULT 'Lunas',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`)
);
```

## Flow Pembayaran

### 1. **Kondisi Peminjaman yang Perlu Dibayar**
- Status: `Menunggu Pengembalian`
- Denda > 0 (berdasarkan kondisi HP)

### 2. **Proses Pembayaran**
1. Peminjam mengakses `/peminjam/pembayaran`
2. Melihat daftar peminjaman yang perlu dibayar
3. Klik "Bayar Sekarang" untuk peminjaman tertentu
4. Pilih metode pembayaran
5. Konfirmasi pembayaran
6. Sistem otomatis:
   - Simpan data pembayaran
   - Update status peminjaman menjadi `Dikembalikan`
   - Update status alat menjadi `Tersedia`
   - Log aktivitas

### 3. **Perhitungan Denda**
- **Baik**: Rp 0
- **Rusak Ringan**: Rp 10.000
- **Rusak Berat**: Rp 20.000

## Files yang Dibuat/Dimodifikasi

### Controllers
- `app/Controllers/PembayaranController.php` - Controller utama pembayaran

### Models
- `app/Models/PembayaranModel.php` - Model untuk tabel pembayaran (sudah ada)

### Views
- `app/Views/peminjam/pembayaran/index.php` - Halaman utama pembayaran
- `app/Views/peminjam/pembayaran/bayar.php` - Form pembayaran
- `app/Views/peminjam/pembayaran/detail.php` - Detail pembayaran

### Routes
```php
$routes->get('pembayaran', 'PembayaranController::index');
$routes->get('pembayaran/bayar/(:num)', 'PembayaranController::bayar/$1');
$routes->post('pembayaran/proses/(:num)', 'PembayaranController::proses/$1');
$routes->get('pembayaran/detail/(:num)', 'PembayaranController::detail/$1');
```

### Menu
- Ditambahkan menu "Pembayaran Denda" di sidebar peminjam

## Keamanan

### 1. **Proteksi Role**
- Hanya peminjam yang bisa mengakses sistem pembayaran
- Validasi kepemilikan data (user hanya bisa bayar peminjamannya sendiri)

### 2. **Validasi Data**
- Cek status peminjaman sebelum pembayaran
- Cek apakah sudah ada pembayaran sebelumnya
- Validasi jumlah denda > 0

### 3. **CSRF Protection**
- Semua form menggunakan `csrf_field()`

## Testing

### File Test
- `TEST_PEMBAYARAN_SYSTEM.php` - Test komprehensif sistem pembayaran

### Setup Database
- `CREATE_PEMBAYARAN_TABLE.sql` - SQL untuk membuat tabel pembayaran

## Cara Penggunaan

### 1. **Setup Database**
```sql
-- Jalankan SQL ini untuk membuat tabel pembayaran
source CREATE_PEMBAYARAN_TABLE.sql;
```

### 2. **Test System**
```
http://localhost/TEST_PEMBAYARAN_SYSTEM.php
```

### 3. **Akses Pembayaran**
```
http://localhost/peminjam/pembayaran
```

## Integrasi dengan Sistem Existing

### 1. **Dengan Sistem Peminjaman**
- Otomatis mendeteksi peminjaman yang perlu dibayar
- Update status setelah pembayaran

### 2. **Dengan Sistem Pengembalian**
- Terintegrasi dengan flow pengembalian HP
- Update status alat setelah pembayaran lunas

### 3. **Dengan Log Aktivitas**
- Setiap pembayaran tercatat di log aktivitas
- Tracking untuk audit trail

## Fitur Tambahan

### 1. **Print Receipt**
- Halaman detail pembayaran bisa dicetak
- CSS khusus untuk print layout

### 2. **Status Visual**
- Badge warna untuk status pembayaran
- Alert sukses/error yang informatif

### 3. **Responsive Design**
- Kompatibel dengan mobile device
- Table responsive untuk data banyak