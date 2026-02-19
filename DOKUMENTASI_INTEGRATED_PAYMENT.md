# DOKUMENTASI PEMBAYARAN TERINTEGRASI DENGAN PENGEMBALIAN

## Overview
Sistem pembayaran denda yang terintegrasi langsung dengan proses pengembalian HP. Peminjam tidak perlu ke halaman terpisah untuk bayar denda - semuanya dilakukan dalam satu form pengembalian.

## Flow Terintegrasi

### 1. **Proses Pengembalian Normal (Tanpa Denda)**
```
Peminjam → Form Pengembalian → Pilih "Baik" → Submit
↓
Status: "Menunggu Pengembalian" → Petugas verifikasi → Selesai
```

### 2. **Proses Pengembalian dengan Denda (BARU)**
```
Peminjam → Form Pengembalian → Pilih "Rusak Ringan/Berat"
↓
Form Pembayaran muncul otomatis → Pilih Metode Pembayaran → Submit
↓
Pembayaran tersimpan + Status langsung "Dikembalikan" + HP jadi "Tersedia"
```

## Perubahan yang Dilakukan

### 1. **Form Pengembalian (`kembalikan.php`)**
**DITAMBAHKAN:**
- Form pembayaran yang muncul otomatis jika ada denda
- Pilihan metode pembayaran (Tunai, Transfer, E-Wallet, Kartu Kredit)
- JavaScript untuk show/hide form pembayaran
- Validasi metode pembayaran jika ada denda

### 2. **Controller (`DashboardPeminjam::kembalikan`)**
**DIMODIFIKASI:**
- Validasi metode pembayaran jika ada denda
- Jika ada denda: langsung simpan pembayaran + status "Dikembalikan" + alat "Tersedia"
- Jika tidak ada denda: proses pengembalian biasa (status "Menunggu Pengembalian")
- Error handling yang lebih baik

### 3. **Database Integration**
- Otomatis simpan ke tabel `pembayaran` jika ada denda
- Update status `peminjaman` dan `alat` dalam satu transaksi

## Keuntungan Sistem Terintegrasi

### ✅ **User Experience**
- **Satu langkah**: Pengembalian + Pembayaran dalam satu form
- **Otomatis**: Form pembayaran muncul hanya jika perlu
- **Instant**: Langsung selesai tanpa menunggu verifikasi petugas

### ✅ **Efisiensi**
- Tidak perlu halaman pembayaran terpisah
- Tidak perlu proses verifikasi tambahan untuk denda
- HP langsung tersedia kembali

### ✅ **Konsistensi**
- Menggunakan logic pengembalian yang sudah ada
- Tidak mengubah flow existing untuk kasus tanpa denda

## Technical Details

### **Form Behavior**
```javascript
// Kondisi HP berubah → Hitung denda
if (denda > 0) {
    // Tampilkan form pembayaran
    formPembayaran.style.display = 'block';
    metodePembayaran.required = true;
} else {
    // Sembunyikan form pembayaran  
    formPembayaran.style.display = 'none';
    metodePembayaran.required = false;
}
```

### **Controller Logic**
```php
// Validasi
if ($denda > 0 && empty($metodePembayaran)) {
    return error('Metode pembayaran harus dipilih');
}

// Proses
if ($denda > 0 && !empty($metodePembayaran)) {
    // Simpan pembayaran + Status "Dikembalikan" + Alat "Tersedia"
} else {
    // Proses pengembalian biasa
}
```

### **Database Updates**
```sql
-- Jika ada denda:
INSERT INTO pembayaran (...) VALUES (...);
UPDATE peminjaman SET status='Dikembalikan' WHERE id=...;
UPDATE alat SET status='Tersedia' WHERE id=...;

-- Jika tidak ada denda:
UPDATE peminjaman SET status='Menunggu Pengembalian' WHERE id=...;
```

## Testing

### **Test File**
- `TEST_INTEGRATED_PAYMENT.php` - Test komprehensif sistem terintegrasi

### **Test Scenarios**
1. **Kondisi Baik**: Tidak ada form pembayaran, proses normal
2. **Kondisi Rusak Ringan**: Form pembayaran muncul, denda Rp 10.000
3. **Kondisi Rusak Berat**: Form pembayaran muncul, denda Rp 20.000
4. **Validasi**: Error jika ada denda tapi metode pembayaran kosong

## User Guide

### **Untuk Peminjam**
1. Buka "Peminjaman Saya" → Klik "Kembalikan" pada HP yang dipinjam
2. Pilih kondisi HP saat dikembalikan
3. **Jika kondisi rusak**: Form pembayaran akan muncul otomatis
4. Pilih metode pembayaran → Submit
5. **Selesai!** HP langsung tersedia kembali

### **Untuk Admin/Petugas**
- Tidak perlu verifikasi pembayaran denda lagi
- HP yang sudah dibayar dendanya langsung tersedia
- Bisa lihat riwayat pembayaran di tabel `pembayaran`

## Backward Compatibility

### ✅ **Tidak Mengubah Logic Existing**
- Pengembalian tanpa denda tetap sama seperti sebelumnya
- Semua controller dan model existing tetap berfungsi
- Hanya menambah fitur, tidak menghapus

### ✅ **Database Compatibility**
- Menggunakan tabel `pembayaran` yang sudah ada
- Field mapping sesuai struktur database existing
- Tidak perlu migrasi data

## Monitoring & Logging

### **Log Aktivitas**
- Pengembalian dengan pembayaran: "Pengembalian HP selesai dengan pembayaran denda: Rp X via Y"
- Pengembalian tanpa denda: "Peminjam mengajukan pengembalian HP dengan kondisi: X"

### **Status Tracking**
- `peminjaman.status`: "Dikembalikan" (jika ada pembayaran) atau "Menunggu Pengembalian"
- `alat.status`: "Tersedia" (jika ada pembayaran) atau tetap "Dipinjam"
- `pembayaran.status`: "Lunas"

Sistem pembayaran terintegrasi ini memberikan pengalaman yang seamless untuk peminjam sambil tetap menjaga integritas data dan proses bisnis yang ada.