-- Tabel untuk menyimpan data yang dihapus (Trash/Recycle Bin)
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

-- Tambah kolom booking_status di tabel alat untuk sistem booking
ALTER TABLE alat 
ADD COLUMN booking_status ENUM('available', 'booked', 'borrowed') DEFAULT 'available' AFTER status;

-- Tambah kolom booked_by dan booked_at untuk tracking booking
ALTER TABLE alat 
ADD COLUMN booked_by int(11) NULL AFTER booking_status,
ADD COLUMN booked_at datetime NULL AFTER booked_by;