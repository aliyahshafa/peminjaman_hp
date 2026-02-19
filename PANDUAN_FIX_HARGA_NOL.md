# PANDUAN: Fix Harga Masih Nol di Dashboard

## MASALAH
Kolom harga sudah muncul di dashboard tapi nilainya masih **Rp 0**

## PENYEBAB KEMUNGKINAN
1. ❌ **Kolom harga belum ada** di tabel `alat`
2. ❌ **Data harga belum diisi** (masih 0 atau NULL)
3. ❌ **Query tidak mengambil kolom harga** (sudah diperbaiki)

## LANGKAH DEBUGGING

### Step 1: Jalankan File Debug
Buka di browser: `http://localhost/your-project/DEBUG_HARGA_DASHBOARD.php`

**Cek hasil:**
- ✅ Kolom harga ada di struktur tabel?
- ✅ Data harga > 0 di tabel alat?
- ✅ Query dashboard berhasil ambil harga?

### Step 2: Test Simple
Buka di browser: `http://localhost/your-project/TEST_HARGA_SIMPLE.php`

**Cek hasil:**
- ✅ Database langsung menunjukkan harga > 0?
- ✅ AlatModel berhasil ambil harga?

## SOLUSI BERDASARKAN HASIL DEBUG

### Jika Kolom Harga Belum Ada:
```sql
-- Tambah kolom harga
ALTER TABLE alat ADD COLUMN harga DECIMAL(10,2) DEFAULT 0 AFTER tipe;
```

### Jika Data Harga Masih 0:
```sql
-- Update semua data dengan harga default
UPDATE alat SET harga = 150000;

-- Atau berdasarkan merk
UPDATE alat SET harga = 180000 WHERE merk LIKE '%iPhone%';
UPDATE alat SET harga = 160000 WHERE merk LIKE '%Samsung%';
UPDATE alat SET harga = 140000 WHERE merk LIKE '%Xiaomi%';
```

### Jika Masih Bermasalah:
Jalankan file `PASTI_FIX_HARGA.sql` **step by step**:

1. Cek kolom harga ada
2. Tambah kolom jika belum ada  
3. Cek data saat ini
4. Update semua data
5. Cek hasil update
6. Update berdasarkan merk
7. Cek hasil akhir

## VERIFIKASI SETELAH FIX

### 1. Cek Database Langsung
```sql
SELECT id_hp, merk, tipe, harga FROM alat LIMIT 5;
```
**Hasil yang diharapkan:** Semua harga > 0

### 2. Cek Dashboard
- Login sebagai Admin → Dashboard → Tabel HP Terbaru
- Login sebagai Petugas → Dashboard → Tabel Peminjaman Menunggu  
- Login sebagai Peminjam → Dashboard → Tabel Riwayat Peminjaman

**Hasil yang diharapkan:** Kolom harga menunjukkan `Rp 150.000` (bukan `Rp 0`)

## TROUBLESHOOTING LANJUTAN

### Jika Masih Rp 0 Setelah SQL:

1. **Cek Cache Browser**
   - Refresh halaman dengan Ctrl+F5
   - Clear browser cache

2. **Cek Session/Login Ulang**
   - Logout dan login kembali
   - Pastikan role user benar

3. **Cek Error Log**
   - Lihat error di browser console (F12)
   - Cek log CodeIgniter di `writable/logs/`

4. **Manual Test Query**
   ```php
   // Test di controller atau debug file
   $alatModel = new \App\Models\AlatModel();
   $data = $alatModel->first();
   var_dump($data['harga']); // Harus > 0
   ```

## HASIL AKHIR YANG DIHARAPKAN

### Dashboard Admin:
```
| Merk    | Tipe      | Harga      | Kategori | Kondisi |
|---------|-----------|------------|----------|---------|
| Samsung | Galaxy A12| Rp 160.000 | Android  | Baik    |
| Xiaomi  | Redmi 9A  | Rp 140.000 | Android  | Baik    |
```

### Dashboard Petugas:
```
| Tanggal  | Peminjam | Alat        | Harga      | Status   |
|----------|----------|-------------|------------|----------|
| 15/02/26 | John Doe | Samsung A12 | Rp 160.000 | Menunggu |
```

### Dashboard Peminjam:
```
| No | HP          | Harga      | Tanggal Pinjam | Status    |
|----|-------------|------------|----------------|-----------|
| 1  | Samsung A12 | Rp 160.000 | 15/02/26       | Disetujui |
```

## CATATAN PENTING
- ✅ **Logic tidak diubah** - hanya menambah kolom dan data
- ✅ **Format konsisten** - `Rp 150.000` dengan titik pemisah
- ✅ **Fallback aman** - `$item['harga'] ?? 0` jika data kosong
- ✅ **Responsive** - tampil baik di desktop dan mobile

**Jika masih bermasalah setelah semua langkah, screenshot hasil debug dan kirim untuk analisis lebih lanjut.**