-- Update harga HP menjadi lebih terjangkau (under 80rb)
-- Harga sewa per hari: 50rb - 75rb

-- Update semua harga menjadi range yang lebih terjangkau
UPDATE alat SET harga = 50000 WHERE harga > 0 AND harga < 100000;
UPDATE alat SET harga = 60000 WHERE harga >= 100000 AND harga < 150000;
UPDATE alat SET harga = 70000 WHERE harga >= 150000 AND harga < 170000;
UPDATE alat SET harga = 75000 WHERE harga >= 170000;

-- Atau update secara spesifik per merk (lebih realistis)
UPDATE alat SET harga = 50000 WHERE merk = 'Redmi';
UPDATE alat SET harga = 55000 WHERE merk = 'Realme';
UPDATE alat SET harga = 60000 WHERE merk = 'Samsung';
UPDATE alat SET harga = 65000 WHERE merk = 'Oppo';
UPDATE alat SET harga = 70000 WHERE merk = 'Vivo';
UPDATE alat SET harga = 75000 WHERE merk = 'Apple';

-- Cek hasil update
SELECT merk, tipe, harga FROM alat ORDER BY harga;

-- Verifikasi semua harga under 80rb
SELECT 
    COUNT(*) as total_hp,
    MIN(harga) as harga_termurah,
    MAX(harga) as harga_termahal,
    AVG(harga) as harga_rata_rata
FROM alat 
WHERE harga > 0;