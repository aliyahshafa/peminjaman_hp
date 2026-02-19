-- Script untuk memperbaiki kolom denda di tabel peminjaman
-- Jalankan script ini jika denda selalu jadi 0

-- 1. Cek struktur tabel peminjaman saat ini
DESCRIBE peminjaman;

-- 2. Cek tipe data kolom denda
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'peminjaman' 
AND COLUMN_NAME = 'denda';

-- 3. Jika kolom denda tidak ada, tambahkan
ALTER TABLE peminjaman 
ADD COLUMN IF NOT EXISTS denda DECIMAL(10,2) DEFAULT 0.00 AFTER tanggal_dikembalikan;

-- 4. Jika kolom denda ada tapi tipe data salah (VARCHAR, TEXT, dll), ubah ke DECIMAL
ALTER TABLE peminjaman 
MODIFY COLUMN denda DECIMAL(10,2) DEFAULT 0.00;

-- 5. Jika kolom kondisi_hp tidak ada, tambahkan
ALTER TABLE peminjaman 
ADD COLUMN IF NOT EXISTS kondisi_hp VARCHAR(50) DEFAULT 'baik' AFTER tanggal_dikembalikan;

-- 6. Jika kolom catatan tidak ada, tambahkan
ALTER TABLE peminjaman 
ADD COLUMN IF NOT EXISTS catatan TEXT NULL AFTER denda;

-- 7. Verifikasi perubahan
DESCRIBE peminjaman;

-- 8. Test update manual
-- Ganti [ID] dengan ID peminjaman yang mau ditest
UPDATE peminjaman 
SET denda = 20000, kondisi_hp = 'rusak berat'
WHERE id_peminjaman = [ID];

-- 9. Cek hasil update
SELECT id_peminjaman, kondisi_hp, denda, status 
FROM peminjaman 
WHERE id_peminjaman = [ID];

-- 10. Jika masih 0, cek apakah ada trigger
SHOW TRIGGERS LIKE 'peminjaman';

-- 11. Jika ada trigger yang mengubah denda, drop trigger tersebut
-- DROP TRIGGER IF EXISTS [nama_trigger];
