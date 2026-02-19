-- Update denda untuk data yang sudah ada berdasarkan kondisi HP

-- Cek data yang perlu diupdate
SELECT id_peminjaman, nama_user, kondisi_hp, denda, status
FROM peminjaman
WHERE kondisi_hp IN ('rusak ringan', 'rusak berat', 'Rusak Ringan', 'Rusak Berat')
AND (denda = 0 OR denda IS NULL)
ORDER BY id_peminjaman DESC;

-- Update denda untuk kondisi rusak ringan
UPDATE peminjaman 
SET denda = 10000
WHERE LOWER(TRIM(kondisi_hp)) = 'rusak ringan' 
AND (denda = 0 OR denda IS NULL);

-- Update denda untuk kondisi rusak berat  
UPDATE peminjaman 
SET denda = 20000
WHERE LOWER(TRIM(kondisi_hp)) = 'rusak berat' 
AND (denda = 0 OR denda IS NULL);

-- Verifikasi hasil update
SELECT id_peminjaman, nama_user, kondisi_hp, denda, status
FROM peminjaman
WHERE kondisi_hp IN ('rusak ringan', 'rusak berat', 'Rusak Ringan', 'Rusak Berat')
ORDER BY id_peminjaman DESC;