-- Langkah 1: Cek data yang bermasalah
SELECT id_peminjaman, nama_user, kondisi_hp, denda, status, waktu
FROM peminjaman
WHERE kondisi_hp IN ('rusak ringan', 'rusak berat', 'Rusak Ringan', 'Rusak Berat')
ORDER BY id_peminjaman DESC
LIMIT 10;

-- Langkah 2: Cek tipe data kolom denda
SHOW COLUMNS FROM peminjaman LIKE 'denda';

-- Langkah 3: Jika tipe data VARCHAR atau TEXT, ubah ke DECIMAL
ALTER TABLE peminjaman 
MODIFY COLUMN denda DECIMAL(10,2) DEFAULT 0.00;

-- Langkah 4: Update manual data yang salah (sesuaikan ID)
-- Untuk rusak ringan
UPDATE peminjaman 
SET denda = 10000.00
WHERE kondisi_hp = 'rusak ringan' AND denda = 0;

-- Untuk rusak berat
UPDATE peminjaman 
SET denda = 20000.00
WHERE kondisi_hp = 'rusak berat' AND denda = 0;

-- Langkah 5: Verifikasi hasil
SELECT id_peminjaman, nama_user, kondisi_hp, denda, status
FROM peminjaman
WHERE kondisi_hp IN ('rusak ringan', 'rusak berat')
ORDER BY id_peminjaman DESC
LIMIT 10;
