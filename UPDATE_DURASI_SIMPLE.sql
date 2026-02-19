-- Update durasi untuk semua data peminjaman yang sudah ada
-- Jalankan query ini di database kamu

-- Pastikan kolom durasi ada (jalankan dulu ADD_DURASI_COLUMN.sql jika belum)

-- Update durasi = 1 untuk semua data yang durasi nya NULL atau 0
UPDATE peminjaman 
SET durasi = 1 
WHERE durasi IS NULL OR durasi = 0;

-- Cek hasilnya
SELECT id_peminjaman, nama_user, waktu, durasi, status 
FROM peminjaman 
ORDER BY id_peminjaman DESC;
