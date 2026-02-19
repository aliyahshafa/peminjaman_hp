-- SIMPLE FIX HARGA - TANPA INFORMATION_SCHEMA
-- Jalankan SQL ini satu per satu

-- STEP 1: Cek struktur tabel alat (untuk lihat apakah kolom harga sudah ada)
DESCRIBE alat;

-- STEP 2: Tambah kolom harga (jika belum ada, akan error jika sudah ada - itu normal)
ALTER TABLE alat ADD COLUMN harga DECIMAL(10,2) DEFAULT 0 AFTER tipe;

-- STEP 3: Cek data alat saat ini
SELECT id_hp, merk, tipe, harga FROM alat LIMIT 5;

-- STEP 4: Update SEMUA data dengan harga default 150rb
UPDATE alat SET harga = 150000;

-- STEP 5: Cek lagi setelah update
SELECT id_hp, merk, tipe, harga FROM alat LIMIT 5;

-- STEP 6: Update harga berdasarkan merk (opsional)
UPDATE alat SET harga = 180000 WHERE merk LIKE '%iPhone%' OR merk LIKE '%Apple%';
UPDATE alat SET harga = 160000 WHERE merk LIKE '%Samsung%';
UPDATE alat SET harga = 140000 WHERE merk LIKE '%Xiaomi%' OR merk LIKE '%Redmi%';
UPDATE alat SET harga = 120000 WHERE merk LIKE '%Oppo%';
UPDATE alat SET harga = 110000 WHERE merk LIKE '%Vivo%';

-- STEP 7: Cek hasil akhir
SELECT id_hp, merk, tipe, harga, kondisi FROM alat ORDER BY harga DESC;