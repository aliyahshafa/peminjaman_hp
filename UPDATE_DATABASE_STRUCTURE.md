# 🔄 Update Struktur Database

## Struktur Database Aktual

### Tabel: `alat` (HP)
```sql
- id_hp (PK)
- id_category (FK)
- merk
- tipe
- kondisi (enum: 'Baik', 'Rusak Ringan', 'Rusak Berat')
- status (enum: 'Tersedia', 'Dipinjam')
- created_at
- updated_at
- deleted_at
```

### Tabel: `peminjaman`
```sql
- id_peminjaman (PK)
- id_user (FK)
- id_hp (FK)
- nama_user
- waktu (tanggal peminjaman)
- status (enum: 'Diajukan', 'Disetujui', 'Menunggu Pengembalian', 'Dikembalikan')
- tanggal_dikembalikan
- kondisi_hp
- denda
- catatan
- created_at
- updated_at
- deleted_at
```

### Tabel: `user`
```sql
- id_user (PK)
- nama_user
- username
- email
- password
- role (enum: 'Admin', 'Petugas', 'Peminjam')
- no_hp
- alamat
- created_at
- updated_at
- deleted_at
```

### Tabel: `category`
```sql
- id_category (PK)
- nama_category
```

## Mapping Field Lama → Baru

| View Lama | Field Lama | Field Baru |
|-----------|------------|------------|
| Alat | kode_alat | - (tidak ada) |
| Alat | nama_alat | merk + tipe |
| Alat | kategori | nama_category (dari join) |
| Alat | stok | - (tidak ada, pakai status) |
| Peminjaman | tanggal_pinjam | waktu |
| Peminjaman | tanggal_kembali | tanggal_dikembalikan |
| Peminjaman | keperluan | catatan |
| Peminjaman | kondisi_kembali | kondisi_hp |

## Status Peminjaman

- **Diajukan** = Menunggu persetujuan
- **Disetujui** = Sudah disetujui, sedang dipinjam
- **Menunggu Pengembalian** = Proses pengembalian
- **Dikembalikan** = Sudah dikembalikan

## File yang Perlu Diupdate

✅ Sudah diupdate:
- [x] app/Views/admin/alat/index.php

📝 Perlu diupdate:
- [ ] app/Views/admin/alat/create.php
- [ ] app/Views/admin/alat/edit.php
- [ ] app/Views/admin/peminjaman/index.php
- [ ] app/Views/admin/peminjaman/edit.php
- [ ] app/Views/admin/pengembalian/index.php
- [ ] app/Views/peminjam/alat/index.php
- [ ] app/Views/peminjam/peminjaman/index.php
- [ ] app/Views/peminjam/peminjaman/create.php
- [ ] app/Views/peminjam/pengembalian/index.php
- [ ] app/Views/petugas/alat/index.php
- [ ] app/Views/petugas/peminjaman/index.php
- [ ] app/Views/petugas/pengembalian/index.php
- [ ] app/Views/petugas/laporan/index.php
- [ ] app/Views/petugas/laporan/cetak.php
- [ ] app/Views/dashboard/admin.php
- [ ] app/Views/dashboard/petugas.php
- [ ] app/Views/dashboard/peminjam.php

## Catatan Penting

1. **Tidak ada field stok** - Gunakan status 'Tersedia' atau 'Dipinjam'
2. **Tidak ada kode_alat** - Gunakan kombinasi merk + tipe
3. **Field waktu** bukan tanggal_pinjam
4. **Field tanggal_dikembalikan** bukan tanggal_kembali
5. **Status peminjaman** menggunakan 4 status berbeda
6. **Kategori** dari tabel terpisah dengan join

## Query Join yang Benar

```php
// Alat dengan kategori
$this->select('alat.*, category.nama_category')
     ->join('category', 'category.id_category = alat.id_category')
     ->findAll();

// Peminjaman dengan user dan alat
$this->select('peminjaman.*, alat.merk, alat.tipe, user.nama_user')
     ->join('alat', 'alat.id_hp = peminjaman.id_hp')
     ->join('user', 'user.id_user = peminjaman.id_user')
     ->findAll();
```
