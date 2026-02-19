-- Tambah kolom harga di tabel alat
ALTER TABLE alat 
ADD COLUMN harga DECIMAL(10,2) DEFAULT 0.00 AFTER tipe;

-- Tambah kolom no_hp di tabel users (jika belum ada)
ALTER TABLE users 
ADD COLUMN no_hp VARCHAR(15) NULL AFTER nama_user;

-- Verifikasi perubahan
DESCRIBE alat;
DESCRIBE users;