-- Update denda untuk data yang sudah ada berdasarkan kondisi HP

-- Update untuk kondisi rusak ringan yang denda nya 0
UPDATE peminjaman 
SET denda = 10000
WHERE kondisi_hp = 'rusak ringan' AND (denda = 0 OR denda IS NULL);

-- Update untuk kondisi rusak berat yang denda nya 0
UPDATE peminjaman 
SET denda = 20000
WHERE kondisi_hp = 'rusak berat' AND (denda = 0 OR denda IS NULL);

-- Verifikasi hasil
SELECT id_peminjaman, nama_user, kondisi_hp, denda, status
FROM peminjaman
WHERE kondisi_hp IN ('rusak ringan', 'rusak berat')
ORDER BY id_peminjaman DESC;
