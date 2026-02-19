-- PASTI FIX HARGA - JALANKAN SQL INI STEP BY STEP

-- STEP 1: Cek apakah kolom harga sudah ada
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'alat' AND COLUMN_NAME = 'harga';

-- STEP 2: Jika hasil kosong, jalankan ini untuk tambah kolom
ALTER TABLE alat ADD COLUMN harga DECIMAL(10,2) DEFAULT 0 AFTER tipe;

-- STEP 3: Cek data alat saat ini
SELECT id_hp, merk, tipe, harga FROM alat LIMIT 5;

-- STEP 4: Update SEMUA data dengan harga default
UPDATE alat SET harga = 150000;

-- STEP 5: Cek lagi setelah update
SELECT id_hp, merk, tipe, harga FROM alat LIMIT 5;

-- STEP 6: Update harga berdasarkan merk (opsional)
UPDATE alat SET harga = 180000 WHERE merk LIKE '%iPhone%';
UPDATE alat SET harga = 160000 WHERE merk LIKE '%Samsung%';
UPDATE alat SET harga = 140000 WHERE merk LIKE '%Xiaomi%';
UPDATE alat SET harga = 120000 WHERE merk LIKE '%Oppo%';
UPDATE alat SET harga = 110000 WHERE merk LIKE '%Vivo%';

-- STEP 7: Cek hasil akhir
SELECT id_hp, merk, tipe, harga, kondisi FROM alat ORDER BY harga DESC;