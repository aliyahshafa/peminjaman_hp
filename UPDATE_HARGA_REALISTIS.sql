-- UPDATE HARGA HP MENJADI LEBIH REALISTIS (DI BAWAH 200RB)
-- Jalankan SQL ini untuk mengubah harga HP yang sudah ada

-- Set harga default untuk semua HP yang belum ada harga
UPDATE alat SET harga = 150000 WHERE harga = 0 OR harga IS NULL;

-- Update harga berdasarkan merk (contoh harga realistis)
UPDATE alat SET harga = 180000 WHERE merk LIKE '%iPhone%' OR merk LIKE '%Apple%';
UPDATE alat SET harga = 160000 WHERE merk LIKE '%Samsung%';
UPDATE alat SET harga = 140000 WHERE merk LIKE '%Xiaomi%' OR merk LIKE '%Redmi%';
UPDATE alat SET harga = 120000 WHERE merk LIKE '%Oppo%';
UPDATE alat SET harga = 110000 WHERE merk LIKE '%Vivo%';
UPDATE alat SET harga = 100000 WHERE merk LIKE '%Realme%';
UPDATE alat SET harga = 90000 WHERE merk LIKE '%Infinix%';
UPDATE alat SET harga = 80000 WHERE merk LIKE '%Tecno%';

-- Update harga berdasarkan kondisi (harga lebih rendah untuk kondisi rusak)
UPDATE alat SET harga = harga * 0.8 WHERE kondisi = 'Rusak Ringan';
UPDATE alat SET harga = harga * 0.6 WHERE kondisi = 'Rusak Berat';

-- Pastikan tidak ada harga di atas 200rb
UPDATE alat SET harga = 190000 WHERE harga > 200000;

-- Cek hasil update
SELECT merk, tipe, kondisi, harga FROM alat ORDER BY harga DESC;