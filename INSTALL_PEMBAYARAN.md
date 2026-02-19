# Instalasi Sistem Pembayaran Denda

## Langkah 1: Buat Tabel Pembayaran

Jalankan SQL ini di phpMyAdmin atau MySQL client:

```sql
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL DEFAULT 0.00,
  `metode_bayar` varchar(50) NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `status_bayar` enum('Pending','Lunas','Dibatalkan') NOT NULL DEFAULT 'Pending',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_peminjaman` (`id_peminjaman`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Langkah 2: Cek Kolom Denda di Tabel Peminjaman

```sql
-- Cek apakah kolom denda ada
DESCRIBE peminjaman;

-- Jika kolom denda tidak ada, tambahkan:
ALTER TABLE peminjaman 
ADD COLUMN denda DECIMAL(10,2) DEFAULT 0.00 AFTER tanggal_dikembalikan;

-- Jika kolom kondisi_hp tidak ada, tambahkan:
ALTER TABLE peminjaman 
ADD COLUMN kondisi_hp VARCHAR(50) DEFAULT 'baik' AFTER tanggal_dikembalikan;

-- Jika kolom catatan tidak ada, tambahkan:
ALTER TABLE peminjaman 
ADD COLUMN catatan TEXT NULL AFTER denda;
```

## Langkah 3: Test Flow Pembayaran

1. Login sebagai Peminjam
2. Ajukan pengembalian dengan kondisi "Rusak Berat"
3. Cek di database: `SELECT * FROM peminjaman WHERE id_peminjaman = [ID]`
   - Harusnya denda = 20000.00
   - kondisi_hp = 'rusak berat'
   - status = 'Menunggu Pengembalian'

4. Login sebagai Petugas
5. Verifikasi pengembalian (opsional, bisa ubah denda)

6. Login sebagai Peminjam
7. Klik tombol "Bayar Denda" (warna merah)
8. Pilih metode pembayaran
9. Konfirmasi pembayaran

10. Cek database:
```sql
-- Cek peminjaman
SELECT * FROM peminjaman WHERE id_peminjaman = [ID];
-- Harusnya: status = 'Dikembalikan', denda = 0

-- Cek pembayaran
SELECT * FROM pembayaran WHERE id_peminjaman = [ID];
-- Harusnya ada 1 record dengan status_bayar = 'Lunas'

-- Cek HP
SELECT * FROM alat WHERE id_hp = [ID_HP];
-- Harusnya: status = 'Tersedia'
```

## Troubleshooting

### Denda Masih 0 Padahal Pilih Rusak Berat

1. Cek browser console (F12) - harusnya ada log:
```
Kondisi HP dipilih (raw): rusak berat
Denda dihitung: 20000
```

2. Cek log file `writable/logs/log-[tanggal].log` - harusnya ada:
```
DEBUG --> === KEMBALIKAN DEBUG ===
DEBUG --> Kondisi HP diterima (asli): "rusak berat"
DEBUG --> Denda dari form: 20000
DEBUG --> Denda final yang akan disimpan: 20000
DEBUG --> Data tersimpan - Denda: 20000
```

3. Jika log menunjukkan 20000 tapi database 0, cek tipe data kolom:
```sql
SELECT DATA_TYPE, COLUMN_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'peminjaman' AND COLUMN_NAME = 'denda';
```

Harusnya: DATA_TYPE = 'decimal', COLUMN_TYPE = 'decimal(10,2)'

Jika VARCHAR atau TEXT, ubah:
```sql
ALTER TABLE peminjaman 
MODIFY COLUMN denda DECIMAL(10,2) DEFAULT 0.00;
```

### Tombol Bayar Denda Tidak Muncul

Pastikan:
- Status peminjaman = 'Menunggu Pengembalian'
- Denda > 0
- Login sebagai peminjam yang punya peminjaman tersebut

### Error Saat Bayar

Cek apakah tabel pembayaran sudah dibuat (Langkah 1)

## File yang Terlibat

- `app/Models/PembayaranModel.php` - Model pembayaran
- `app/Controllers/DashboardPeminjam.php` - Controller pembayaran
- `app/Views/peminjam/pembayaran/index.php` - Form pembayaran
- `app/Views/peminjam/peminjaman/index.php` - Tombol bayar denda
- `app/Config/Routes.php` - Routes pembayaran
