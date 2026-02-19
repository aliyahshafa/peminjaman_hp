-- SQL untuk membuat tabel pembayaran
-- Jalankan ini jika tabel pembayaran belum ada

CREATE TABLE IF NOT EXISTS `pembayaran` (
  `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `metode_bayar` varchar(50) NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `status_bayar` enum('Menunggu','Lunas','Gagal') DEFAULT 'Lunas',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  KEY `id_peminjaman` (`id_peminjaman`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cek apakah tabel berhasil dibuat
SELECT 'Tabel pembayaran berhasil dibuat atau sudah ada' as status;