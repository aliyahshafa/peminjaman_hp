# Debug Masalah Denda = 0

## Langkah-langkah Debug:

### 1. Cek Log File
Buka file: `writable/logs/log-[tanggal-hari-ini].php`

Cari baris yang berisi:
```
DEBUG --> Kondisi HP diterima (asli): rusak berat
DEBUG --> Kondisi HP setelah normalisasi: rusak berat
DEBUG --> Denda dihitung: 20000
```

Jika denda dihitung = 20000 tapi di database = 0, berarti ada masalah di database.

### 2. Cek Database Langsung
Jalankan query ini di phpMyAdmin atau MySQL:

```sql
-- Cek data peminjaman terakhir
SELECT id_peminjaman, id_user, kondisi_hp, denda, status, tanggal_dikembalikan
FROM peminjaman
ORDER BY id_peminjaman DESC
LIMIT 5;
```

### 3. Cek Tipe Data Kolom Denda
```sql
-- Cek struktur tabel peminjaman
DESCRIBE peminjaman;
```

Pastikan kolom `denda` bertipe:
- INT atau DECIMAL atau FLOAT
- BUKAN VARCHAR atau TEXT

### 4. Test Manual Insert
```sql
-- Test insert manual
UPDATE peminjaman 
SET denda = 20000, kondisi_hp = 'rusak berat'
WHERE id_peminjaman = [ID_TERAKHIR];

-- Cek hasilnya
SELECT denda, kondisi_hp FROM peminjaman WHERE id_peminjaman = [ID_TERAKHIR];
```

### 5. Cek Trigger Database
```sql
-- Cek apakah ada trigger yang mengubah denda
SHOW TRIGGERS LIKE 'peminjaman';
```

### 6. Kemungkinan Masalah:

#### A. Tipe Data Salah
Jika kolom `denda` bertipe VARCHAR, nilai 20000 mungkin tidak tersimpan dengan benar.

**Solusi:**
```sql
ALTER TABLE peminjaman 
MODIFY COLUMN denda DECIMAL(10,2) DEFAULT 0;
```

#### B. Ada Kode Lain yang Override
Cek apakah ada kode di:
- Model `PeminjamanModel.php` (beforeUpdate, afterUpdate callback)
- Controller lain yang mengubah denda

#### C. Session atau Cache
Clear cache CodeIgniter:
```bash
# Hapus folder cache
rm -rf writable/cache/*
```

### 7. Test dengan var_dump()

Tambahkan di controller sebelum update:

```php
public function kembalikan($id)
{
    $kondisi = $this->request->getPost('kondisi_hp');
    $kondisi = trim(strtolower($kondisi));
    
    $denda = 0;
    if ($kondisi === 'rusak ringan') {
        $denda = 10000;
    } elseif ($kondisi === 'rusak berat') {
        $denda = 20000;
    }
    
    // DEBUG: Tampilkan nilai sebelum update
    var_dump([
        'kondisi' => $kondisi,
        'denda' => $denda,
        'id' => $id
    ]);
    die(); // Stop eksekusi untuk lihat output
    
    // ... kode selanjutnya
}
```

Setelah test, hapus var_dump() dan die().

### 8. Cek Browser Console

Buka browser console (F12) saat submit form kembalikan.
Lihat apakah ada error JavaScript atau nilai yang salah.

### 9. Cek Network Tab

Di browser (F12 → Network tab):
- Submit form kembalikan
- Klik request POST ke `/peminjam/peminjaman/kembalikan/[ID]`
- Lihat di tab "Payload" atau "Form Data"
- Pastikan `kondisi_hp` = "rusak berat" (bukan "Rusak Berat" atau yang lain)

### 10. Solusi Sementara: Kirim Denda dari Form

Jika masih gagal, tambahkan hidden input di form:

```php
<!-- Di app/Views/peminjam/peminjaman/kembalikan.php -->
<input type="hidden" name="denda" id="dendaValue" value="0">
```

Update JavaScript:
```javascript
function hitungDenda() {
    const kondisi = document.getElementById('kondisiHp').value;
    let denda = 0;
    
    if (kondisi === 'rusak ringan') {
        denda = 10000;
    } else if (kondisi === 'rusak berat') {
        denda = 20000;
    }
    
    // Set hidden input
    document.getElementById('dendaValue').value = denda;
    
    // ... kode lainnya
}
```

Update controller:
```php
public function kembalikan($id)
{
    $kondisi = trim(strtolower($this->request->getPost('kondisi_hp')));
    $denda = (int) $this->request->getPost('denda'); // Ambil dari form
    
    // Validasi ulang di server (jangan percaya client)
    if ($kondisi === 'rusak ringan' && $denda != 10000) {
        $denda = 10000;
    } elseif ($kondisi === 'rusak berat' && $denda != 20000) {
        $denda = 20000;
    } elseif ($kondisi === 'baik') {
        $denda = 0;
    }
    
    // ... update database
}
```

## Hasil yang Diharapkan:

Setelah submit form dengan kondisi "Rusak Berat":
- Log file menunjukkan: `Denda dihitung: 20000`
- Database menunjukkan: `denda = 20000.00` atau `20000`
- Flash message: "Pengembalian berhasil diajukan dengan denda Rp 20.000"
