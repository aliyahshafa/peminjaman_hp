-- =====================================================
-- FIX DURASI DATABASE
-- File ini untuk memperbaiki kolom durasi di tabel peminjaman
-- =====================================================

-- 1. Cek apakah kolom durasi sudah ada
SELECT COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'peminjaman' 
AND COLUMN_NAME = 'durasi';

-- 2. Jika kolom durasi belum ada, tambahkan kolom
-- (Skip jika sudah ada)
ALTER TABLE peminjaman 
ADD COLUMN IF NOT EXISTS durasi INT DEFAULT 1 AFTER waktu;

-- 3. Update semua data yang durasi nya NULL atau 0 menjadi 1
UPDATE peminjaman 
SET durasi = 1 
WHERE durasi IS NULL OR durasi = 0 OR durasi < 1;

-- 4. Untuk data yang sudah ada, hitung durasi berdasarkan tanggal
-- (Hanya untuk data yang sudah dikembalikan)
UPDATE peminjaman 
SET durasi = GREATEST(1, DATEDIFF(tanggal_kembali, DATE(waktu)))
WHERE tanggal_kembali IS NOT NULL 
AND (durasi IS NULL OR durasi = 0 OR durasi = 1);

-- 5. Verifikasi hasil
SELECT 
    id_peminjaman,
    nama_user,
    DATE(waktu) as tanggal_pinjam,
    tanggal_kembali,
    durasi,
    status
FROM peminjaman 
ORDER BY id_peminjaman DESC 
LIMIT 10;

-- 6. Cek statistik durasi
SELECT 
    durasi,
    COUNT(*) as jumlah,
    status
FROM peminjaman 
GROUP BY durasi, status
ORDER BY durasi;

-- =====================================================
-- SELESAI
-- Sekarang semua data peminjaman sudah punya durasi yang benar
-- =====================================================
