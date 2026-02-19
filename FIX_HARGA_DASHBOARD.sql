-- FIX HARGA DI DASHBOARD
-- Pastikan kolom harga ada dan berisi data

-- 1. Cek apakah kolom harga sudah ada
DESCRIBE alat;

-- 2. Jika kolom harga belum ada, tambahkan
ALTER TABLE `alat` ADD COLUMN IF NOT EXISTS `harga` decimal(10,2) DEFAULT 0 AFTER `tipe`;

-- 3. Update harga untuk data yang masih 0 atau NULL
UPDATE alat SET harga = 150000 WHERE harga = 0 OR harga IS NULL;

-- 4. Update harga berdasarkan merk (contoh harga realistis)
UPDATE alat SET harga = 180000 WHERE merk LIKE '%iPhone%' OR merk LIKE '%Apple%';
UPDATE alat SET harga = 160000 WHERE merk LIKE '%Samsung%';
UPDATE alat SET harga = 140000 WHERE merk LIKE '%Xiaomi%' OR merk LIKE '%Redmi%';
UPDATE alat SET harga = 120000 WHERE merk LIKE '%Oppo%';
UPDATE alat SET harga = 110000 WHERE merk LIKE '%Vivo%';
UPDATE alat SET harga = 100000 WHERE merk LIKE '%Realme%';
UPDATE alat SET harga = 90000 WHERE merk LIKE '%Infinix%';
UPDATE alat SET harga = 80000 WHERE merk LIKE '%Tecno%';

-- 5. Kurangi harga untuk kondisi rusak
UPDATE alat SET harga = ROUND(harga * 0.8) WHERE kondisi = 'Rusak Ringan';
UPDATE alat SET harga = ROUND(harga * 0.6) WHERE kondisi = 'Rusak Berat';

-- 6. Pastikan tidak ada harga di atas 200rb
UPDATE alat SET harga = 190000 WHERE harga > 200000;

-- 7. Cek hasil
SELECT id_hp, merk, tipe, harga, kondisi, status FROM alat ORDER BY harga DESC LIMIT 10;