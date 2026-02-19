-- =====================================================
-- UPDATE DURASI UNTUK DATA YANG SUDAH ADA
-- Ubah durasi peminjaman yang sudah ada menjadi 2 atau 3 hari
-- =====================================================

-- 1. Lihat data peminjaman yang ada sekarang
SELECT 
    id_peminjaman,
    nama_user,
    DATE(waktu) as tanggal_pinjam,
    tanggal_kembali,
    durasi,
    status
FROM peminjaman 
ORDER BY id_peminjaman DESC;

-- 2. Update durasi untuk peminjaman tertentu (ganti ID sesuai kebutuhan)
-- Contoh: Update peminjaman ID 1 menjadi 2 hari
UPDATE peminjaman 
SET durasi = 2 
WHERE id_peminjaman = 1;

-- Contoh: Update peminjaman ID 2 menjadi 3 hari
UPDATE peminjaman 
SET durasi = 3 
WHERE id_peminjaman = 2;

-- 3. Atau update semua peminjaman yang statusnya 'Disetujui' menjadi 2 hari
UPDATE peminjaman 
SET durasi = 2 
WHERE status = 'Disetujui' OR status = 'disetujui';

-- 4. Atau update semua peminjaman yang statusnya 'Dikembalikan' menjadi 3 hari
UPDATE peminjaman 
SET durasi = 3 
WHERE status = 'Dikembalikan';

-- 5. Atau update berdasarkan user tertentu
-- Contoh: Semua peminjaman user 'John' jadi 2 hari
UPDATE peminjaman 
SET durasi = 2 
WHERE nama_user = 'John';

-- 6. Update secara acak untuk variasi (50% jadi 2 hari, 50% jadi 3 hari)
-- Peminjaman dengan ID genap = 2 hari
UPDATE peminjaman 
SET durasi = 2 
WHERE MOD(id_peminjaman, 2) = 0;

-- Peminjaman dengan ID ganjil = 3 hari
UPDATE peminjaman 
SET durasi = 3 
WHERE MOD(id_peminjaman, 2) = 1;

-- 7. Verifikasi hasil update
SELECT 
    id_peminjaman,
    nama_user,
    DATE(waktu) as tanggal_pinjam,
    durasi,
    status,
    CASE 
        WHEN durasi = 1 THEN '1 hari'
        WHEN durasi = 2 THEN '2 hari'
        WHEN durasi = 3 THEN '3 hari'
        ELSE CONCAT(durasi, ' hari')
    END as durasi_text
FROM peminjaman 
ORDER BY id_peminjaman DESC;

-- 8. Cek statistik durasi
SELECT 
    durasi,
    COUNT(*) as jumlah_peminjaman
FROM peminjaman 
GROUP BY durasi
ORDER BY durasi;

-- =====================================================
-- CATATAN:
-- - Pilih salah satu query UPDATE di atas sesuai kebutuhan
-- - Jangan jalankan semua sekaligus, pilih yang sesuai
-- - Setelah update, data pembayaran akan otomatis pakai durasi baru
-- =====================================================
