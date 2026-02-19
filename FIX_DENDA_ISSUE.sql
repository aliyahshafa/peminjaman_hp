-- Script untuk memperbaiki masalah denda yang jadi 0

-- 1. Cek struktur kolom denda
DESCRIBE peminjaman;

-- 2. Cek tipe data kolom denda dan kondisi_hp
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'peminjaman' 
AND COLUMN_NAME IN ('denda', 'kondisi_hp');

-- 3. Jika kolom denda bertipe VARCHAR atau TEXT, ubah ke DECIMAL
ALTER TABLE peminjaman 
MODIFY COLUMN denda DECIMAL(10,2) DEFAULT 0.00;

-- 4. Jika kolom kondisi_hp bertipe ENUM, ubah ke VARCHAR
ALTER TABLE peminjaman 
MODIFY COLUMN kondisi_hp VARCHAR(50) DEFAULT 'baik';

-- 5. Test manual update
-- Ganti [ID] dengan ID peminjaman yang bermasalah
UPDATE peminjaman 
SET denda = 10000, kondisi_hp = 'rusak ringan'
WHERE id_peminjaman = [ID];

-- 6. Cek hasil
SELECT id_peminjaman, kondisi_hp, denda, status 
FROM peminjaman 
WHERE id_peminjaman = [ID];

-- 7. Jika masih 0, cek apakah ada trigger yang mengubah nilai
SHOW TRIGGERS LIKE 'peminjaman';

-- 8. Jika ada trigger yang bermasalah, drop trigger
-- DROP TRIGGER IF EXISTS [nama_trigger];

-- 9. Cek data yang bermasalah
SELECT id_peminjaman, nama_user, kondisi_hp, denda, status, waktu
FROM peminjaman
WHERE kondisi_hp IN ('rusak ringan', 'rusak berat')
ORDER BY id_peminjaman DESC
LIMIT 10;
