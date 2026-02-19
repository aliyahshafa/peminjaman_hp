-- JALANKAN SQL INI DI DATABASE ANDA UNTUK MEMBUAT TABEL TRASH
-- Buat tabel trash untuk sistem recycle bin
CREATE TABLE IF NOT EXISTS `trash` (
  `id_trash` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `data_backup` longtext NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `deleted_at` datetime NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_trash`),
  KEY `idx_table_name` (`table_name`),
  KEY `idx_deleted_by` (`deleted_by`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tambahkan kolom harga ke tabel alat jika belum ada
ALTER TABLE `alat` ADD COLUMN `harga` decimal(10,2) DEFAULT 0 AFTER `tipe`;

-- Update harga untuk data yang sudah ada (contoh harga di bawah 200rb)
UPDATE alat SET harga = 150000 WHERE harga = 0 OR harga IS NULL;