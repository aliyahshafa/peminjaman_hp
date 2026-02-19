-- Script untuk memperbaiki harga yang Rp 0 di database
-- Jalankan script ini di phpMyAdmin atau MySQL client

-- 1. Cek dulu berapa banyak yang harga nya 0 atau NULL
SELECT COUNT(*) as 'Total Harga 0 atau NULL' 
FROM alat 
WHERE harga = 0 OR harga IS NULL;

-- 2. Lihat data yang harga nya 0 atau NULL
SELECT id_hp, merk, tipe, harga, status 
FROM alat 
WHERE harga = 0 OR harga IS NULL;

-- 3. Update semua harga yang 0 atau NULL dengan harga default (50.000 - 75.000)
-- Sesuaikan dengan permintaan user: harga di bawah 80k

UPDATE alat SET harga = 50000 WHERE (harga = 0 OR harga IS NULL) AND merk = 'Redmi';
UPDATE alat SET harga = 75000 WHERE (harga = 0 OR harga IS NULL) AND merk = 'Apple';
UPDATE alat SET harga = 60000 WHERE (harga = 0 OR harga IS NULL) AND merk = 'Samsung';
UPDATE alat SET harga = 55000 WHERE (harga = 0 OR harga IS NULL) AND merk = 'Oppo';
UPDATE alat SET harga = 50000 WHERE (harga = 0 OR harga IS NULL) AND merk = 'Vivo';
UPDATE alat SET harga = 65000 WHERE (harga = 0 OR harga IS NULL) AND merk = 'Realme';
UPDATE alat SET harga = 70000 WHERE (harga = 0 OR harga IS NULL) AND merk = 'Xiaomi';

-- Update sisanya yang masih 0 dengan harga default 55.000
UPDATE alat SET harga = 55000 WHERE harga = 0 OR harga IS NULL;

-- 4. Verifikasi hasil update
SELECT id_hp, merk, tipe, harga, status 
FROM alat 
ORDER BY id_hp;

-- 5. Cek lagi apakah masih ada yang 0
SELECT COUNT(*) as 'Total Harga 0 atau NULL (Setelah Update)' 
FROM alat 
WHERE harga = 0 OR harga IS NULL;
