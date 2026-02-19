-- SUPER SIMPLE - PASTI TIDAK ERROR
-- Jalankan satu per satu, jika ada error skip ke yang berikutnya

-- 1. Cek data alat saat ini
SELECT * FROM alat LIMIT 3;

-- 2. Coba tambah kolom harga (jika error "Duplicate column" berarti sudah ada)
ALTER TABLE alat ADD COLUMN harga DECIMAL(10,2) DEFAULT 150000 AFTER tipe;

-- 3. Jika step 2 error, berarti kolom sudah ada, jalankan ini untuk update data
UPDATE alat SET harga = 150000 WHERE harga = 0 OR harga IS NULL;

-- 4. Cek hasil
SELECT id_hp, merk, tipe, harga FROM alat LIMIT 5;