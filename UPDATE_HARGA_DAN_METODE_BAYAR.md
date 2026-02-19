# UPDATE HARGA DAN METODE PEMBAYARAN

## Perubahan yang Dilakukan

### 1. **Update Harga HP (Under 80rb)**

**Harga Baru per Hari:**
- Redmi: Rp 50.000
- Realme: Rp 55.000
- Samsung: Rp 60.000
- Oppo: Rp 65.000
- Vivo: Rp 70.000
- Apple: Rp 75.000

**Cara Update:**
```sql
-- Jalankan file SQL ini
source UPDATE_HARGA_AFFORDABLE.sql;
```

### 2. **Metode Pembayaran (Disederhanakan)**

**Sebelum:**
- Tunai
- Transfer Bank
- E-Wallet (OVO, GoPay, Dana)
- Kartu Kredit

**Sesudah:**
- Tunai
- Transfer Bank

## Contoh Perhitungan dengan Harga Baru

### **Skenario 1: Samsung, 2 hari, kondisi baik**
- Harga: Rp 60.000/hari
- Durasi: 2 hari
- Biaya Sewa: Rp 60.000 × 2 = **Rp 120.000**
- Denda: **Rp 0**
- **TOTAL: Rp 120.000**

### **Skenario 2: Apple, 3 hari, rusak ringan**
- Harga: Rp 75.000/hari
- Durasi: 3 hari
- Biaya Sewa: Rp 75.000 × 3 = **Rp 225.000**
- Denda: **Rp 10.000**
- **TOTAL: Rp 235.000**

### **Skenario 3: Redmi, 1 hari, kondisi baik**
- Harga: Rp 50.000/hari
- Durasi: 1 hari
- Biaya Sewa: Rp 50.000 × 1 = **Rp 50.000**
- Denda: **Rp 0**
- **TOTAL: Rp 50.000**

## Files yang Dimodifikasi

1. **UPDATE_HARGA_AFFORDABLE.sql** - SQL untuk update harga
2. **app/Views/peminjam/peminjaman/kembalikan.php** - Form pengembalian
3. **app/Views/peminjam/pembayaran/bayar.php** - Form pembayaran

## Keuntungan

### ✅ **Harga Lebih Terjangkau**
- Semua harga under 80rb
- Range: Rp 50.000 - Rp 75.000 per hari
- Lebih realistis untuk peminjaman HP

### ✅ **Metode Pembayaran Lebih Sederhana**
- Hanya 2 opsi: Tunai dan Transfer Bank
- Lebih mudah dikelola
- Fokus pada metode yang paling umum

## Testing

Setelah menjalankan SQL update, test dengan:
1. Buka halaman peminjaman
2. Cek harga HP di daftar alat
3. Lakukan pengembalian
4. Verifikasi perhitungan biaya sewa dengan harga baru
5. Cek metode pembayaran hanya ada 2 opsi

## Verifikasi Harga

```sql
-- Cek semua harga HP
SELECT merk, tipe, harga 
FROM alat 
ORDER BY harga;

-- Pastikan semua under 80rb
SELECT MAX(harga) as harga_tertinggi 
FROM alat;
```

Harga tertinggi harus **≤ 75.000**