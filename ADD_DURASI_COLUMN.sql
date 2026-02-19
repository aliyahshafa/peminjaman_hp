-- Tambah kolom durasi ke tabel peminjaman
-- Durasi dalam satuan hari

-- Cek apakah kolom durasi sudah ada
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'peminjaman' 
AND COLUMN_NAME = 'durasi';

-- Jika belum ada, tambahkan kolom durasi
ALTER TABLE peminjaman 
ADD COLUMN durasi INT DEFAULT 1 AFTER waktu;

-- Update data existing dengan durasi 1 hari (default)
UPDATE peminjaman 
SET durasi = 1 
WHERE durasi IS NULL OR durasi = 0;

-- Verifikasi
DESCRIBE peminjaman;

SELECT id_peminjaman, nama_user, waktu, durasi, status 
FROM peminjaman 
LIMIT 5;