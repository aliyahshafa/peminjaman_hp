-- Tabel Pembayaran untuk sistem denda peminjaman HP
-- Jalankan SQL ini jika tabel pembayaran belum ada

CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL DEFAULT 0.00,
  `metode_bayar` varchar(50) NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `status_bayar` enum('Pending','Lunas','Dibatalkan') NOT NULL DEFAULT 'Pending',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_peminjaman` (`id_peminjaman`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `fk_pembayaran_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  CONSTRAINT `fk_pembayaran_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Index untuk performa query
CREATE INDEX idx_status_bayar ON pembayaran(status_bayar);
CREATE INDEX idx_tanggal_bayar ON pembayaran(tanggal_bayar);
