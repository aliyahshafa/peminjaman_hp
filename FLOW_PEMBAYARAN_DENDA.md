# Flow Pembayaran Denda - Sistem Peminjaman HP

## Alur Lengkap Pengembalian dengan Pembayaran Denda

### 1. Peminjam Mengajukan Pengembalian
- Peminjam klik tombol "Kembalikan" pada peminjaman dengan status "Disetujui"
- Peminjam mengisi form kondisi HP:
  - Baik (Tidak ada kerusakan) → Denda Rp 0
  - Rusak Ringan (Kerusakan kecil) → Denda Rp 10.000
  - Rusak Berat (Kerusakan parah) → Denda Rp 20.000
- Status berubah menjadi **"Menunggu Pengembalian"**
- Data kondisi HP dan denda tersimpan

### 2. Petugas Memverifikasi Pengembalian
- Petugas melihat daftar peminjaman dengan status "Menunggu Pengembalian"
- Petugas klik "Verifikasi" atau "Edit" untuk memeriksa kondisi HP
- Petugas dapat mengubah:
  - Kondisi HP (jika tidak sesuai dengan yang dilaporkan peminjam)
  - Denda (otomatis dihitung berdasarkan kondisi)
  - Catatan tambahan
- Status tetap **"Menunggu Pengembalian"** (tidak langsung "Dikembalikan")
- Peminjam akan menerima notifikasi untuk membayar denda

### 3. Peminjam Membayar Denda
**Jika ada denda (Rp > 0):**
- Peminjam melihat tombol "Bayar Denda" berwarna merah pada daftar peminjaman
- Peminjam klik tombol tersebut
- Peminjam memilih metode pembayaran:
  - Tunai
  - Transfer Bank
  - E-Wallet (OVO, GoPay, Dana)
- Peminjam klik "Konfirmasi Pembayaran"
- Sistem menyimpan data pembayaran ke tabel `pembayaran`
- Status peminjaman berubah menjadi **"Dikembalikan"**
- Status HP berubah menjadi **"Tersedia"**
- Denda di tabel peminjaman menjadi 0

**Jika tidak ada denda (Rp 0):**
- Peminjam melihat badge "Menunggu Verifikasi Petugas"
- Setelah petugas verifikasi dan denda = 0, status langsung "Dikembalikan"
- HP langsung tersedia kembali

### 4. Pengembalian Selesai
- Status peminjaman: **"Dikembalikan"**
- Status HP: **"Tersedia"**
- Riwayat pembayaran tersimpan di tabel `pembayaran`
- Log aktivitas tercatat

## Tabel Database yang Terlibat

### Tabel `peminjaman`
```sql
- id_peminjaman
- id_user
- id_hp
- waktu (tanggal pinjam)
- tanggal_dikembalikan
- status (Diajukan, Disetujui, Menunggu Pengembalian, Dikembalikan)
- kondisi_hp (baik, rusak ringan, rusak berat)
- denda (0, 10000, 20000)
- catatan
```

### Tabel `pembayaran` (BARU)
```sql
- id_pembayaran
- id_peminjaman
- id_user
- jumlah (nominal denda yang dibayar)
- metode_bayar (Tunai, Transfer Bank, E-Wallet)
- tanggal_bayar
- status_bayar (Pending, Lunas, Dibatalkan)
- bukti_bayar (untuk fitur upload bukti di masa depan)
- created_at
- updated_at
```

### Tabel `alat`
```sql
- id_hp
- status (Tersedia, Dipinjam)
```

## Perhitungan Denda

| Kondisi HP | Denda |
|------------|-------|
| Baik | Rp 0 |
| Rusak Ringan | Rp 10.000 |
| Rusak Berat | Rp 20.000 |

## File yang Dimodifikasi

1. **Model Baru:**
   - `app/Models/PembayaranModel.php` - Model untuk tabel pembayaran

2. **Controller:**
   - `app/Controllers/DashboardPeminjam.php` - Update method pembayaran dan prosesPembayaran
   - `app/Controllers/PetugasPeminjamanController.php` - Update method update untuk verifikasi

3. **View:**
   - `app/Views/peminjam/peminjaman/index.php` - Tombol bayar denda
   - `app/Views/peminjam/pembayaran/index.php` - Form pembayaran
   - `app/Views/petugas/peminjaman/edit.php` - Form verifikasi petugas

4. **Database:**
   - `database_pembayaran.sql` - SQL untuk membuat tabel pembayaran

## Cara Install

1. Jalankan SQL untuk membuat tabel pembayaran:
```bash
mysql -u root -p nama_database < database_pembayaran.sql
```

2. Atau copy-paste isi file `database_pembayaran.sql` ke phpMyAdmin

3. Sistem siap digunakan!

## Testing Flow

1. Login sebagai Peminjam
2. Ajukan peminjaman HP
3. Login sebagai Petugas, setujui peminjaman
4. Login sebagai Peminjam, ajukan pengembalian dengan kondisi "Rusak Berat"
5. Login sebagai Petugas, verifikasi pengembalian
6. Login sebagai Peminjam, bayar denda Rp 20.000
7. Cek status peminjaman sudah "Dikembalikan"
8. Cek HP sudah "Tersedia" kembali

## Keamanan

- Peminjam hanya bisa bayar denda untuk peminjaman miliknya sendiri
- Validasi status peminjaman sebelum pembayaran
- Validasi jumlah denda > 0 sebelum pembayaran
- Cek duplikasi pembayaran (tidak bisa bayar 2x untuk peminjaman yang sama)
- Log semua aktivitas pembayaran

## Fitur Tambahan (Opsional untuk Masa Depan)

- Upload bukti pembayaran
- Notifikasi email/SMS setelah pembayaran
- Laporan pembayaran untuk admin
- Export data pembayaran ke Excel/PDF
- Integrasi payment gateway (Midtrans, dll)
