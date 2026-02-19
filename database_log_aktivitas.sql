-- SQL untuk membuat/update tabel log_aktivitas dengan kolom lengkap

-- CARA 1: Jika tabel sudah ada, tambahkan kolom baru satu per satu
-- Jalankan query ini satu per satu, abaikan error jika kolom sudah ada

ALTER TABLE log_aktivitas ADD COLUMN ip_address VARCHAR(45) NULL AFTER aktivitas;
ALTER TABLE log_aktivitas ADD COLUMN user_agent TEXT NULL AFTER ip_address;
ALTER TABLE log_aktivitas ADD COLUMN method VARCHAR(10) NULL AFTER user_agent;
ALTER TABLE log_aktivitas ADD COLUMN url TEXT NULL AFTER method;

-- Atau buat tabel baru jika belum ada
CREATE TABLE IF NOT EXISTS log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL DEFAULT 0,
    nama_user VARCHAR(100) NOT NULL,
    role VARCHAR(50) NOT NULL,
    aktivitas TEXT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    method VARCHAR(10) NULL,
    url TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_id_user (id_user),
    INDEX idx_role (role),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh query untuk melihat statistik
-- Total log per role
SELECT role, COUNT(*) as total 
FROM log_aktivitas 
GROUP BY role 
ORDER BY total DESC;

-- Log hari ini
SELECT * 
FROM log_aktivitas 
WHERE DATE(created_at) = CURDATE() 
ORDER BY created_at DESC;

-- Top 10 user paling aktif
SELECT nama_user, COUNT(*) as total_aktivitas 
FROM log_aktivitas 
GROUP BY nama_user 
ORDER BY total_aktivitas DESC 
LIMIT 10;

-- Aktivitas 7 hari terakhir
SELECT DATE(created_at) as tanggal, COUNT(*) as total 
FROM log_aktivitas 
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
GROUP BY DATE(created_at) 
ORDER BY tanggal;

-- Hapus log lebih dari 30 hari
-- DELETE FROM log_aktivitas WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
