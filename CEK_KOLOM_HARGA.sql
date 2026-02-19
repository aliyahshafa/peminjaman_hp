-- CEK APAKAH KOLOM HARGA SUDAH ADA DI TABEL ALAT
DESCRIBE alat;

-- JIKA KOLOM HARGA BELUM ADA, JALANKAN INI:
-- ALTER TABLE `alat` ADD COLUMN `harga` decimal(10,2) DEFAULT 0 AFTER `tipe`;

-- CEK DATA ALAT DENGAN HARGA
SELECT id_hp, merk, tipe, harga, kondisi, status FROM alat LIMIT 5;

-- CONTOH UPDATE HARGA UNTUK DATA YANG SUDAH ADA (HARGA DI BAWAH 200RB)
-- UPDATE alat SET harga = 150000 WHERE merk = 'Samsung';
-- UPDATE alat SET harga = 180000 WHERE merk = 'iPhone';
-- UPDATE alat SET harga = 120000 WHERE merk = 'Xiaomi';
-- UPDATE alat SET harga = 100000 WHERE merk = 'Oppo';
-- UPDATE alat SET harga = 90000 WHERE merk = 'Vivo';