-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 02:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `peminjaman_hp`
--

-- --------------------------------------------------------

--
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id_hp` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `merk` varchar(20) NOT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL,
  `tipe` varchar(30) NOT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `status` enum('Tersedia','Dipinjam') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alat`
--

INSERT INTO `alat` (`id_hp`, `id_category`, `merk`, `kondisi`, `tipe`, `harga`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(58, 2, 'Redmi', 'Baik', '50 Pro', 50000.00, 'Tersedia', '2026-02-04 07:25:00', '2026-04-09 02:35:02', NULL),
(60, 1, 'Apple', 'Baik', '13 Pro', 75000.00, 'Tersedia', '2026-02-04 08:48:55', '2026-04-09 02:34:59', NULL),
(64, 2, 'Samsung', 'Rusak Ringan', 'Galaxy S25 Ultra', 75000.00, 'Tersedia', '2026-02-04 09:19:14', '2026-04-09 02:36:03', NULL),
(70, 1, 'Apple', 'Baik', 'XS', 75000.00, 'Tersedia', '2026-02-04 13:45:20', '2026-04-01 04:09:01', '2026-02-12 05:58:39'),
(72, 1, 'Apple', 'Rusak Berat', '11 Pro Max', 75000.00, 'Tersedia', '2026-02-10 02:01:59', '2026-04-04 04:51:33', NULL),
(73, 1, 'Apple', 'Baik', '18 Pro Max', 75000.00, 'Tersedia', '2026-02-12 05:26:42', '2026-04-09 02:36:49', NULL),
(74, 2, 'Techno', 'Baik', 'Camon Pro 5G', 55000.00, 'Tersedia', '2026-02-12 06:09:25', '2026-04-09 02:34:55', NULL),
(75, 1, 'Apple', 'Baik', '17 Pro', 75000.00, 'Tersedia', '2026-02-14 13:55:30', '2026-04-09 02:35:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id_category` int(11) NOT NULL,
  `nama_category` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id_category`, `nama_category`) VALUES
(1, 'Ios'),
(2, 'Android');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_user` varchar(100) NOT NULL,
  `role` enum('Admin','Petugas','Peminjam') NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `user_agent` varchar(100) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `nama_user`, `role`, `aktivitas`, `user_agent`, `ip_address`, `created_at`, `updated_at`) VALUES
(319, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:47:31', '2026-03-28 01:47:31'),
(320, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:47:35', '2026-03-28 01:47:35'),
(321, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:48:58', '2026-03-28 01:48:58'),
(322, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:49:00', '2026-03-28 01:49:00'),
(323, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:49:01', '2026-03-28 01:49:01'),
(324, 1, 'admin', 'Admin', 'Admin mengedit data handphone: Apple 13 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:49:13', '2026-03-28 01:49:13'),
(325, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:49:40', '2026-03-28 01:49:40'),
(326, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:49:43', '2026-03-28 01:49:43'),
(327, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:50:05', '2026-03-28 01:50:05'),
(328, 1, 'admin', 'Admin', 'Admin menambahkan data handphone: Apple', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:51:20', '2026-03-28 01:51:20'),
(329, 1, 'admin', 'Admin', 'Admin mengedit data handphone: Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:52:42', '2026-03-28 01:52:42'),
(330, 1, 'admin', 'Admin', 'Admin mengedit data handphone: Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:58:02', '2026-03-28 01:58:02'),
(331, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Sa', '::1', '2026-03-28 01:58:14', '2026-03-28 01:58:14'),
(332, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 01:59:23', '2026-03-28 01:59:23'),
(333, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 01:59:33', '2026-03-28 01:59:33'),
(334, 1, 'admin', 'Admin', 'Menambah data user: Rajkaizar', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:00:22', '2026-03-28 02:00:22'),
(335, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:00:22', '2026-03-28 02:00:22'),
(336, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Apple 18 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:01:24', '2026-03-28 02:01:24'),
(337, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Apple 18 Pro Max', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:10:07', '2026-03-28 02:10:07'),
(338, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Techno Camon Pro 5G', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:13:02', '2026-03-28 02:13:02'),
(339, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Redmi 50 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:13:26', '2026-03-28 02:13:26'),
(340, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:14:05', '2026-03-28 02:14:05'),
(341, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:14:17', '2026-03-28 02:14:17'),
(342, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 80.000 (1 hari) = Total Rp 80.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:21:48', '2026-03-28 02:21:48'),
(343, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:22:22', '2026-03-28 02:22:22'),
(344, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:28:04', '2026-03-28 02:28:04'),
(345, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:29:14', '2026-03-28 02:29:14'),
(346, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:29:17', '2026-03-28 02:29:17'),
(347, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 02:44:43', '2026-03-28 02:44:43'),
(348, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:05:03', '2026-03-28 03:05:03'),
(349, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:06:13', '2026-03-28 03:06:13'),
(350, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:10:28', '2026-03-28 03:10:28'),
(351, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:10:39', '2026-03-28 03:10:39'),
(352, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:15:42', '2026-03-28 03:15:42'),
(353, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 150.000 (3 hari) = Total Rp 150.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:54:24', '2026-03-28 03:54:24'),
(354, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 150.000 (2 hari) + Denda Rp 10.000 = Total Rp 160.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:54:44', '2026-03-28 03:54:44'),
(355, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:55:43', '2026-03-28 03:55:43'),
(356, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:55:48', '2026-03-28 03:55:48'),
(357, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 03:56:06', '2026-03-28 03:56:06'),
(358, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:00:39', '2026-03-28 04:00:39'),
(359, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:01:42', '2026-03-28 04:01:42'),
(360, 1, 'admin', 'Admin', 'Admin mengedit data handphone: Techno Camon Pro 5G', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:22:44', '2026-03-28 04:22:44'),
(361, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:23:14', '2026-03-28 04:23:14'),
(362, 18, 'aliyah', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:23:53', '2026-03-28 04:23:53'),
(363, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:30:29', '2026-03-28 04:30:29'),
(364, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:30:54', '2026-03-28 04:30:54'),
(365, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:37:03', '2026-03-28 04:37:03'),
(366, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:37:29', '2026-03-28 04:37:29'),
(367, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:40:17', '2026-03-28 04:40:17'),
(368, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:43:05', '2026-03-28 04:43:05'),
(369, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:45:27', '2026-03-28 04:45:27'),
(370, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:45:48', '2026-03-28 04:45:48'),
(371, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:55:40', '2026-03-28 04:55:40'),
(373, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:58:26', '2026-03-28 04:58:26'),
(374, 1, 'admin', 'Admin', 'Menambah data user: indri', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:59:22', '2026-03-28 04:59:22'),
(375, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:59:22', '2026-03-28 04:59:22'),
(376, 1, 'admin', 'Admin', 'Menghapus data user: indri', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:59:27', '2026-03-28 04:59:27'),
(377, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 04:59:28', '2026-03-28 04:59:28'),
(378, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 05:00:14', '2026-03-28 05:00:14'),
(379, 1, 'admin', 'Admin', 'Admin menghapus log aktivitas: Admin memindahkan HP ke trash: Apple 18 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 05:00:20', '2026-03-28 05:00:20'),
(380, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 05:00:20', '2026-03-28 05:00:20'),
(381, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 05:00:24', '2026-03-28 05:00:24'),
(382, 1, 'admin', 'Admin', 'Admin memindahkan HP ke trash: Apple 18 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-28 05:05:27', '2026-03-28 05:05:27'),
(383, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-31 02:10:16', '2026-03-31 02:10:16'),
(384, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Redmi 50 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-31 02:10:26', '2026-03-31 02:10:26'),
(385, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Apple 18 Pro Max', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-31 02:10:36', '2026-03-31 02:10:36'),
(386, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-31 02:13:09', '2026-03-31 02:13:09'),
(387, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-31 02:14:26', '2026-03-31 02:14:26'),
(388, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-31 02:14:45', '2026-03-31 02:14:45'),
(389, 22, 'Rajkaizar', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 75.000 (1 hari) + Denda Rp 10.000 = Total Rp 85.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-03-31 02:15:20', '2026-03-31 02:15:20'),
(390, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:10:03', '2026-04-01 00:10:03'),
(391, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:10:06', '2026-04-01 00:10:06'),
(392, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:10:20', '2026-04-01 00:10:20'),
(393, 1, 'admin', 'Admin', 'Menambah data user: jdhsd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:10:48', '2026-04-01 00:10:48'),
(394, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:10:48', '2026-04-01 00:10:48'),
(395, 1, 'admin', 'Admin', 'Menghapus data user: jdhsd', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:10:56', '2026-04-01 00:10:56'),
(396, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:10:56', '2026-04-01 00:10:56'),
(397, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:11:00', '2026-04-01 00:11:00'),
(398, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:11:06', '2026-04-01 00:11:06'),
(399, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:22:45', '2026-04-01 00:22:45'),
(400, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:22:49', '2026-04-01 00:22:49'),
(401, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:24:11', '2026-04-01 00:24:11'),
(402, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:29:43', '2026-04-01 00:29:43'),
(403, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:29:56', '2026-04-01 00:29:56'),
(404, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Techno Camon Pro 5G', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:35:28', '2026-04-01 00:35:28'),
(405, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:35:54', '2026-04-01 00:35:54'),
(406, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:36:06', '2026-04-01 00:36:06'),
(407, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 55.000 (1 hari) = Total Rp 55.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:36:55', '2026-04-01 00:36:55'),
(408, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:44:04', '2026-04-01 00:44:04'),
(409, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:45:25', '2026-04-01 00:45:25'),
(410, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:45:48', '2026-04-01 00:45:48'),
(411, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Techno Camon Pro 5G', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:45:55', '2026-04-01 00:45:55'),
(412, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:46:00', '2026-04-01 00:46:00'),
(413, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:46:10', '2026-04-01 00:46:10'),
(414, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:53:09', '2026-04-01 00:53:09'),
(415, 1, 'admin', 'Admin', 'Admin menambahkan data handphone: Samsung', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:53:50', '2026-04-01 00:53:50'),
(416, 1, 'admin', 'Admin', 'Admin memindahkan HP ke trash: Samsung A 56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 00:54:06', '2026-04-01 00:54:06'),
(417, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 03:46:45', '2026-04-01 03:46:45'),
(418, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Apple 11 Pro Max', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 03:48:20', '2026-04-01 03:48:20'),
(419, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 03:53:57', '2026-04-01 03:53:57'),
(420, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 03:56:12', '2026-04-01 03:56:12'),
(421, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:00:44', '2026-04-01 04:00:44'),
(422, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 75.000 (1 hari) + Denda Rp 20.000 = Total Rp 95.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:06:04', '2026-04-01 04:06:04'),
(423, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:06:48', '2026-04-01 04:06:48'),
(424, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Apple XS', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:07:33', '2026-04-01 04:07:33'),
(425, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:07:59', '2026-04-01 04:07:59'),
(426, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 225.000 (3 hari) + Denda Rp 10.000 = Total Rp 235.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:08:33', '2026-04-01 04:08:33'),
(427, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:09:01', '2026-04-01 04:09:01'),
(428, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:09:12', '2026-04-01 04:09:12'),
(429, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Apple XS', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:09:24', '2026-04-01 04:09:24'),
(430, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:09:48', '2026-04-01 04:09:48'),
(431, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-01 04:10:02', '2026-04-01 04:10:02'),
(432, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Apple 18 Pro Max', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:46:09', '2026-04-02 06:46:09'),
(433, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:46:34', '2026-04-02 06:46:34'),
(434, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 75.000 (1 hari) = Total Rp 75.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:47:12', '2026-04-02 06:47:12'),
(435, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Redmi 50 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:48:55', '2026-04-02 06:48:55'),
(436, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:49:30', '2026-04-02 06:49:30'),
(437, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:55:26', '2026-04-02 06:55:26'),
(438, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:55:46', '2026-04-02 06:55:46'),
(439, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:55:50', '2026-04-02 06:55:50'),
(440, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:56:17', '2026-04-02 06:56:17'),
(441, 1, 'admin', 'Admin', 'Admin mengubah data peminjaman ID 56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 06:57:36', '2026-04-02 06:57:36'),
(442, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-02 07:05:39', '2026-04-02 07:05:39'),
(443, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 01:51:37', '2026-04-03 01:51:37'),
(444, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 01:51:42', '2026-04-03 01:51:42'),
(445, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 02:13:16', '2026-04-03 02:13:16'),
(446, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Apple 17 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 02:44:46', '2026-04-03 02:44:46'),
(447, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 100.000 (2 hari) + Denda Rp 10.000 = Total Rp 110.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 02:49:35', '2026-04-03 02:49:35'),
(448, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 02:51:35', '2026-04-03 02:51:35'),
(449, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 02:51:41', '2026-04-03 02:51:41'),
(450, 1, 'admin', 'Admin', 'Menambah data user: Aliyah Shafa', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:09:27', '2026-04-03 03:09:27'),
(451, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:09:27', '2026-04-03 03:09:27'),
(452, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:18:28', '2026-04-03 03:18:28'),
(453, 1, 'admin', 'Admin', 'Mengubah data user: Aliyah Shafa', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:22:33', '2026-04-03 03:22:33'),
(454, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:22:33', '2026-04-03 03:22:33'),
(455, 1, 'admin', 'Admin', 'Mengubah data user: Aliyah Shafa', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:23:02', '2026-04-03 03:23:02'),
(456, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:23:02', '2026-04-03 03:23:02'),
(457, 1, 'admin', 'Admin', 'Menghapus data user: Aliyah Shafa', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:31:43', '2026-04-03 03:31:43'),
(458, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:31:44', '2026-04-03 03:31:44'),
(459, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:35:18', '2026-04-03 03:35:18'),
(460, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:35:26', '2026-04-03 03:35:26'),
(461, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:54:09', '2026-04-03 03:54:09'),
(462, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 03:54:12', '2026-04-03 03:54:12'),
(463, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:18:57', '2026-04-03 04:18:57'),
(464, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:19:03', '2026-04-03 04:19:03'),
(465, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:19:05', '2026-04-03 04:19:05'),
(466, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:19:57', '2026-04-03 04:19:57'),
(467, 1, 'admin', 'Admin', 'Admin menambahkan data handphone: Xiaomi', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:29:36', '2026-04-03 04:29:36'),
(468, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:29:49', '2026-04-03 04:29:49'),
(469, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:32:15', '2026-04-03 04:32:15'),
(470, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:32:21', '2026-04-03 04:32:21'),
(471, 1, 'admin', 'Admin', 'Admin memindahkan HP ke trash: Xiaomi 15T Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:36:49', '2026-04-03 04:36:49'),
(472, 1, 'admin', 'Admin', 'Admin menambahkan kategori: jhdgsjyfg', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:56:45', '2026-04-03 04:56:45'),
(473, 1, 'admin', 'Admin', 'Admin mengedit kategori: jhdgsjyfg', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 04:58:51', '2026-04-03 04:58:51'),
(474, 1, 'admin', 'Admin', 'Admin menghapus kategori: jhdgsjyfg', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 05:00:25', '2026-04-03 05:00:25'),
(475, 1, 'admin', 'Admin', 'Admin menambahkan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 05:06:54', '2026-04-03 05:06:54'),
(476, 1, 'admin', 'Admin', 'Admin mengubah data peminjaman ID 62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 05:14:46', '2026-04-03 05:14:46'),
(477, 1, 'admin', 'Admin', 'Admin mengarsipkan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 05:16:15', '2026-04-03 05:16:15'),
(478, 1, 'admin', 'Admin', 'Admin mencatat pengembalian HP ID peminjaman: 62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 05:33:10', '2026-04-03 05:33:10'),
(479, 1, 'admin', 'Admin', 'Admin mengubah data peminjaman ID 61', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 05:35:20', '2026-04-03 05:35:20'),
(480, 1, 'admin', 'Admin', 'Admin mengedit pengembalian ID: 56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 05:51:47', '2026-04-03 05:51:47'),
(481, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:04:09', '2026-04-03 06:04:09'),
(482, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:10:07', '2026-04-03 06:10:07'),
(483, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:44:16', '2026-04-03 06:44:16'),
(484, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:44:39', '2026-04-03 06:44:39'),
(485, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:45:23', '2026-04-03 06:45:23'),
(486, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:45:35', '2026-04-03 06:45:35'),
(487, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:46:11', '2026-04-03 06:46:11'),
(488, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:48:38', '2026-04-03 06:48:38'),
(489, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:55:29', '2026-04-03 06:55:29'),
(490, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:56:38', '2026-04-03 06:56:38'),
(491, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:57:16', '2026-04-03 06:57:16'),
(492, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:57:31', '2026-04-03 06:57:31'),
(493, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 06:57:58', '2026-04-03 06:57:58'),
(494, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 07:00:55', '2026-04-03 07:00:55'),
(495, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 07:01:24', '2026-04-03 07:01:24'),
(496, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 07:02:11', '2026-04-03 07:02:11'),
(497, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 07:04:50', '2026-04-03 07:04:50'),
(498, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 07:11:05', '2026-04-03 07:11:05'),
(499, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 07:24:44', '2026-04-03 07:24:44'),
(500, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Redmi 50 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 08:52:08', '2026-04-03 08:52:08'),
(501, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 225.000 (3 hari) + Denda Rp 10.000 = Total Rp 235.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 08:56:47', '2026-04-03 08:56:47'),
(502, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 09:11:29', '2026-04-03 09:11:29'),
(503, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 09:11:34', '2026-04-03 09:11:34'),
(504, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-03 10:52:39', '2026-04-03 10:52:39'),
(505, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 01:43:16', '2026-04-04 01:43:16'),
(506, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 03:07:20', '2026-04-04 03:07:20'),
(507, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 03:07:22', '2026-04-04 03:07:22'),
(508, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 04:48:45', '2026-04-04 04:48:45'),
(509, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 04:50:53', '2026-04-04 04:50:53'),
(510, 1, 'admin', 'Admin', 'Admin mengedit data handphone: Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 04:51:18', '2026-04-04 04:51:18'),
(511, 1, 'admin', 'Admin', 'Admin mengedit data handphone: Apple 11 Pro Max', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 04:51:33', '2026-04-04 04:51:33'),
(512, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 05:01:13', '2026-04-04 05:01:13'),
(513, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 05:09:38', '2026-04-04 05:09:38'),
(514, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 05:11:06', '2026-04-04 05:11:06'),
(515, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Apple 13 Pro', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 05:30:11', '2026-04-04 05:30:11'),
(516, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-04 05:30:40', '2026-04-04 05:30:40'),
(517, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:03:48', '2026-04-07 02:03:48'),
(518, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:03:52', '2026-04-07 02:03:52'),
(519, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:03:54', '2026-04-07 02:03:54'),
(520, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:11', '2026-04-07 02:04:11'),
(521, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:22', '2026-04-07 02:04:22'),
(522, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:24', '2026-04-07 02:04:24'),
(523, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:30', '2026-04-07 02:04:30'),
(524, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:33', '2026-04-07 02:04:33'),
(525, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:35', '2026-04-07 02:04:35'),
(526, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:52', '2026-04-07 02:04:52'),
(527, 1, 'admin', 'Admin', 'Menghapus data user: Rajkaizar', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:58', '2026-04-07 02:04:58'),
(528, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 02:04:58', '2026-04-07 02:04:58'),
(529, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 04:31:47', '2026-04-07 04:31:47'),
(530, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 04:32:47', '2026-04-07 04:32:47'),
(531, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 04:32:50', '2026-04-07 04:32:50'),
(532, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 13:52:46', '2026-04-07 13:52:46'),
(533, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 13:52:50', '2026-04-07 13:52:50'),
(534, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 13:53:24', '2026-04-07 13:53:24'),
(535, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 13:55:46', '2026-04-07 13:55:46'),
(536, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 13:57:45', '2026-04-07 13:57:45'),
(537, 1, 'admin', 'Admin', 'Admin menghapus permanen data dari trash: users ID: 22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 14:02:05', '2026-04-07 14:02:05'),
(538, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 14:04:35', '2026-04-07 14:04:35'),
(539, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 14:05:04', '2026-04-07 14:05:04');
INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `nama_user`, `role`, `aktivitas`, `user_agent`, `ip_address`, `created_at`, `updated_at`) VALUES
(540, 1, 'admin', 'Admin', 'Melihat data daftar user', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 14:08:56', '2026-04-07 14:08:56'),
(541, 1, 'admin', 'Admin', 'Melihat data log aktivitas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 14:19:09', '2026-04-07 14:19:09'),
(542, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-07 14:20:49', '2026-04-07 14:20:49'),
(543, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-08 02:09:23', '2026-04-08 02:09:23'),
(544, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:08:23', '2026-04-09 00:08:23'),
(545, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:12:15', '2026-04-09 00:12:15'),
(546, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 100.000 (2 hari) = Total Rp 100.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:13:16', '2026-04-09 00:13:16'),
(547, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:13:57', '2026-04-09 00:13:57'),
(548, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 225.000 (3 hari) = Total Rp 225.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:14:24', '2026-04-09 00:14:24'),
(549, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Techno Camon Pro 5G', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:30:31', '2026-04-09 00:30:31'),
(550, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:31:33', '2026-04-09 00:31:33'),
(551, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan peminjaman HP Samsung Galaxy S25 Ultra', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:32:51', '2026-04-09 00:32:51'),
(552, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 00:33:09', '2026-04-09 00:33:09'),
(553, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 01:24:57', '2026-04-09 01:24:57'),
(554, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:30:06', '2026-04-09 02:30:06'),
(555, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 165.000 (3 hari) = Total Rp 165.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:34:31', '2026-04-09 02:34:31'),
(556, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:34:55', '2026-04-09 02:34:55'),
(557, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:34:59', '2026-04-09 02:34:59'),
(558, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:35:02', '2026-04-09 02:35:02'),
(559, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:35:05', '2026-04-09 02:35:05'),
(560, 2, 'petugas', 'Petugas', 'Petugas mengupdate peminjaman HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:35:09', '2026-04-09 02:35:09'),
(561, 3, 'peminjam', 'Peminjam', 'Peminjam mengajukan pengembalian HP: Biaya sewa Rp 150.000 (2 hari) + Denda Rp 10.000 = Total Rp 160.000 via Tunai - Menunggu konfirmasi petugas', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:35:38', '2026-04-09 02:35:38'),
(562, 2, 'petugas', 'Petugas', 'Petugas mengkonfirmasi pengembalian HP', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:36:03', '2026-04-09 02:36:03'),
(563, 1, 'admin', 'Admin', 'Melihat data dashboard admin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:36:28', '2026-04-09 02:36:28'),
(564, 1, 'admin', 'Admin', 'Admin mengedit data handphone: Apple 18 Pro Max', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Sa', '::1', '2026-04-09 02:36:49', '2026-04-09 02:36:49');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `waktu` enum('1 Hari','2 Hari','3 Hari') NOT NULL,
  `harga` int(11) NOT NULL,
  `metode_pembayaran` enum('Tunai','Transfer') NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `status` enum('Belum bayar','Lunas') NOT NULL,
  `subtotal` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_user`, `id_peminjaman`, `waktu`, `harga`, `metode_pembayaran`, `tanggal_bayar`, `status`, `subtotal`) VALUES
(12, 22, 48, '', 80000, 'Tunai', '2026-03-28', 'Lunas', '80000'),
(16, 3, 58, '', 55000, 'Tunai', '2026-04-01', 'Lunas', '55000'),
(17, 3, 59, '', 75000, 'Tunai', '2026-04-01', 'Lunas', '95000'),
(19, 3, 61, '', 75000, 'Tunai', '2026-04-02', 'Lunas', '75000'),
(20, 3, 62, '', 50000, 'Tunai', '2026-04-03', 'Lunas', '110000'),
(21, 3, 63, '', 75000, 'Tunai', '2026-04-03', 'Lunas', '235000'),
(22, 3, 65, '', 50000, 'Tunai', '2026-04-09', 'Lunas', '100000'),
(23, 3, 66, '', 75000, 'Tunai', '2026-04-09', 'Lunas', '225000'),
(24, 3, 67, '', 55000, 'Tunai', '2026-04-09', 'Lunas', '165000'),
(25, 3, 68, '', 75000, 'Tunai', '2026-04-09', 'Lunas', '160000');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_hp` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `no_hp` int(11) NOT NULL,
  `waktu` enum('1 Hari','2 Hari','3 Hari') NOT NULL,
  `status` enum('Diajukan','Disetujui','Ditolak','Dipinjam','Dikembalikan','Menunggu Pengembalian') NOT NULL,
  `tanggal_pinjam` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `kondisi_hp` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL,
  `denda` int(11) NOT NULL,
  `catatan` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `id_hp`, `nama_user`, `no_hp`, `waktu`, `status`, `tanggal_pinjam`, `tanggal_kembali`, `kondisi_hp`, `denda`, `catatan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(48, 22, 79, 'Rajkaizar', 0, '1 Hari', 'Dikembalikan', '2026-03-28', '2026-03-28', 'Baik', 0, '', '2026-03-28 00:00:00', '2026-03-28 10:03:16', NULL),
(56, 22, 64, 'Rajkaizar', 0, '', 'Dikembalikan', '2026-03-28', '2026-04-03', 'Baik', 0, '', '2026-03-28 04:45:27', '2026-04-03 07:24:44', NULL),
(58, 3, 74, 'peminjam', 0, '3 Hari', 'Dikembalikan', '2026-04-01', '2026-04-03', 'Baik', 0, '', '2026-04-01 00:35:28', '2026-04-01 00:44:04', NULL),
(59, 3, 72, 'peminjam', 0, '', 'Dikembalikan', '2026-04-01', '2026-04-01', 'Rusak Berat', 20000, '', '2026-04-01 03:48:20', '2026-04-01 04:06:48', NULL),
(61, 3, 73, 'peminjam', 0, '', 'Dikembalikan', '2026-04-02', '2026-04-02', 'Baik', 0, '', '2026-04-02 06:46:09', '2026-04-03 05:35:20', NULL),
(62, 3, 58, 'peminjam', 0, '', 'Dikembalikan', '2026-04-02', '2026-04-03', 'Baik', 0, '', '2026-04-02 06:48:55', '2026-04-03 05:33:10', NULL),
(63, 3, 75, 'peminjam', 0, '3 Hari', 'Dikembalikan', '2026-04-03', '2026-04-03', 'Rusak Ringan', 10000, '', '2026-04-03 02:44:46', '2026-04-09 02:35:04', NULL),
(65, 3, 58, 'peminjam', 0, '2 Hari', 'Dikembalikan', '2026-04-03', '2026-04-09', 'Baik', 0, 'pinjem', '2026-04-03 08:52:08', '2026-04-09 02:35:02', NULL),
(66, 3, 60, 'peminjam', 0, '3 Hari', 'Dikembalikan', '2026-04-04', '2026-04-09', 'Baik', 0, '', '2026-04-04 05:30:11', '2026-04-09 02:34:59', NULL),
(67, 3, 74, 'peminjam', 0, '3 Hari', 'Dikembalikan', '2026-04-09', '2026-04-09', 'Baik', 0, '', '2026-04-09 00:30:31', '2026-04-09 02:34:55', NULL),
(68, 3, 64, 'peminjam', 0, '2 Hari', 'Dikembalikan', '2026-04-09', '2026-04-09', 'Rusak Ringan', 10000, '', '2026-04-09 00:32:51', '2026-04-09 02:36:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trash`
--

CREATE TABLE `trash` (
  `id_trash` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `data_backup` longtext NOT NULL,
  `deleted_by` int(11) NOT NULL,
  `deleted_at` datetime NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trash`
--

INSERT INTO `trash` (`id_trash`, `table_name`, `record_id`, `data_backup`, `deleted_by`, `deleted_at`, `reason`, `created_at`, `updated_at`) VALUES
(1, 'alat', 70, '{\"id_hp\":\"70\",\"id_category\":\"1\",\"merk\":\"Apple\",\"kondisi\":\"Baik\",\"tipe\":\"XS\",\"status\":\"Tersedia\",\"created_at\":\"2026-02-04 13:45:20\",\"updated_at\":\"2026-02-11 14:03:01\",\"deleted_at\":null}', 1, '2026-02-12 05:58:39', 'Dihapus oleh admin', '2026-02-12 05:58:39', '2026-02-12 05:58:39'),
(2, 'users', 21, '', 1, '2026-03-10 12:58:52', 'Dihapus oleh admin', '2026-03-10 12:58:52', '2026-03-10 12:58:52'),
(3, 'log_aktivitas', 276, '', 1, '2026-03-11 09:40:57', 'Dihapus oleh admin dari log aktivitas', '2026-03-11 09:40:57', '2026-03-11 09:40:57'),
(4, 'alat', 79, '{\"id_hp\":\"79\",\"id_category\":\"1\",\"merk\":\"Apple\",\"kondisi\":\"Baik\",\"tipe\":\"18 Pro\",\"harga\":\"80000.00\",\"status\":\"Tersedia\",\"created_at\":\"2026-03-28 01:51:20\",\"updated_at\":\"2026-03-28 02:22:22\",\"deleted_at\":null}', 1, '2026-03-28 04:56:01', 'Dihapus oleh admin', '2026-03-28 04:56:01', '2026-03-28 04:56:01'),
(5, 'users', 23, '', 1, '2026-03-28 04:59:27', 'Dihapus oleh admin', '2026-03-28 04:59:27', '2026-03-28 04:59:27'),
(6, 'peminjaman', 51, '', 1, '2026-03-28 04:59:40', 'Dihapus oleh admin', '2026-03-28 04:59:40', '2026-03-28 04:59:40'),
(7, 'peminjaman', 48, '', 1, '2026-03-28 05:00:03', 'Dihapus oleh admin', '2026-03-28 05:00:03', '2026-03-28 05:00:03'),
(8, 'log_aktivitas', 372, '', 1, '2026-03-28 05:00:20', 'Dihapus oleh admin dari log aktivitas', '2026-03-28 05:00:20', '2026-03-28 05:00:20'),
(9, 'alat', 79, '{\"id_hp\":\"79\",\"id_category\":\"1\",\"merk\":\"Apple\",\"kondisi\":\"Baik\",\"tipe\":\"18 Pro\",\"harga\":\"80000.00\",\"status\":\"Tersedia\",\"created_at\":\"2026-03-28 01:51:20\",\"updated_at\":\"2026-03-28 02:22:22\",\"deleted_at\":null}', 1, '2026-03-28 05:05:27', 'Dihapus oleh admin', '2026-03-28 05:05:27', '2026-03-28 05:05:27'),
(10, 'peminjaman', 51, '', 1, '2026-03-28 05:05:38', 'Dihapus oleh admin', '2026-03-28 05:05:38', '2026-03-28 05:05:38'),
(11, 'peminjaman', 51, '', 1, '2026-03-28 05:06:07', 'Dihapus oleh admin', '2026-03-28 05:06:07', '2026-03-28 05:06:07'),
(12, 'peminjaman', 51, '', 1, '2026-03-31 02:10:26', 'Dihapus oleh admin', '2026-03-31 02:10:26', '2026-03-31 02:10:26'),
(13, 'peminjaman', 49, '', 1, '2026-03-31 02:10:36', 'Dihapus oleh admin', '2026-03-31 02:10:36', '2026-03-31 02:10:36'),
(14, 'peminjaman', 55, '', 1, '2026-03-31 02:13:09', 'Dihapus oleh admin', '2026-03-31 02:13:09', '2026-03-31 02:13:09'),
(15, 'users', 24, '', 1, '2026-04-01 00:10:56', 'Dihapus oleh admin', '2026-04-01 00:10:56', '2026-04-01 00:10:56'),
(16, 'peminjaman', 57, '', 1, '2026-04-01 00:45:48', 'Dihapus oleh admin', '2026-04-01 00:45:48', '2026-04-01 00:45:48'),
(17, 'peminjaman', 50, '', 1, '2026-04-01 00:45:55', 'Dihapus oleh admin', '2026-04-01 00:45:55', '2026-04-01 00:45:55'),
(18, 'peminjaman', 52, '', 1, '2026-04-01 00:46:00', 'Dihapus oleh admin', '2026-04-01 00:46:00', '2026-04-01 00:46:00'),
(19, 'peminjaman', 53, '', 1, '2026-04-01 00:46:10', 'Dihapus oleh admin', '2026-04-01 00:46:10', '2026-04-01 00:46:10'),
(20, 'alat', 80, '{\"id_hp\":\"80\",\"id_category\":\"2\",\"merk\":\"Samsung\",\"kondisi\":\"Baik\",\"tipe\":\"A 56\",\"harga\":\"60000.00\",\"status\":\"Tersedia\",\"created_at\":\"2026-04-01 00:53:50\",\"updated_at\":\"2026-04-01 00:53:50\",\"deleted_at\":null}', 1, '2026-04-01 00:54:06', 'Dihapus oleh admin', '2026-04-01 00:54:06', '2026-04-01 00:54:06'),
(21, 'peminjaman', 60, '', 1, '2026-04-01 04:09:24', 'Dihapus oleh admin', '2026-04-01 04:09:24', '2026-04-01 04:09:24'),
(22, 'peminjaman', 54, '', 1, '2026-04-02 06:56:17', 'Dihapus oleh admin', '2026-04-02 06:56:17', '2026-04-02 06:56:17'),
(23, 'users', 25, '', 1, '2026-04-03 03:31:43', 'Dihapus oleh admin', '2026-04-03 03:31:43', '2026-04-03 03:31:43'),
(24, 'alat', 81, '{\"id_hp\":\"81\",\"id_category\":\"2\",\"merk\":\"Xiaomi\",\"kondisi\":\"Baik\",\"tipe\":\"15T Pro\",\"harga\":\"70000.00\",\"status\":\"Tersedia\",\"created_at\":\"2026-04-03 04:29:36\",\"updated_at\":\"2026-04-03 04:29:36\",\"deleted_at\":null}', 1, '2026-04-03 04:36:49', 'Dihapus oleh admin', '2026-04-03 04:36:49', '2026-04-03 04:36:49'),
(25, 'category', 4, '', 1, '2026-04-03 05:00:25', 'Dihapus oleh admin', '2026-04-03 05:00:25', '2026-04-03 05:00:25'),
(26, 'peminjaman', 64, '', 1, '2026-04-03 05:16:15', 'Dihapus oleh admin', '2026-04-03 05:16:15', '2026-04-03 05:16:15');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(55) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Petugas','Peminjam') NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `email`, `password`, `role`, `no_hp`, `alamat`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'aliyah', 'admin@gmail.com', '$2y$10$m.QzhlESYdqFAWSJ0w8DnOTL8gWtxYF4KjobwToDcdw9xULbjhi2S', 'Admin', '0812', 'Bandung', NULL, '2026-02-09 09:13:32', NULL),
(2, 'petugas', 'p3tugas', 'petugas@gmail.com', '$2y$10$crBDm9OkrgpASV000n09/uXsG.Y.2T8DXmE3A7gZOBzvZiBJuoRBa', 'Petugas', '082', 'Bandung', NULL, '2026-02-09 09:16:01', NULL),
(3, 'peminjam', 'aku', 'peminjam@gmail.com', '$2y$10$iGKr8/noBI7KQZh.Mpl.3.cNIWe1lAWggnQwW4J6e27yN7NzOd9JK', 'Peminjam', '083', 'Bandung\r\n', NULL, '2026-02-09 09:16:55', NULL),
(18, 'aliyah', '', 'aliyah@gmail.com', '$2y$10$tv1GVRvwqtEgvTHf3ajc4.mEwaQt1vqcOfxNtmT0dfnJrGx1HpmAi', 'Petugas', '0888', 'Bandung', '2026-02-11 00:57:45', '2026-02-11 00:57:45', NULL),
(22, 'Rajkaizar', '', 'kaizar@gmail.com', '$2y$10$KylBdHkSohjvqhBO7WHdgeALfyQ4TYIhOhPOGfPJjkOYi9XPxYdqa', 'Peminjam', '087777', 'Bandung', '2026-03-28 02:00:22', '2026-04-07 02:04:58', '2026-04-07 02:04:58'),
(23, 'indri', '', 'dri@gmail.com', '$2y$10$9CfMVAJzE7zShPDEIfQDdub3T8/1v9WuOluCKRw03pGmHF35Ny7sG', 'Peminjam', '08999', 'cimahi', '2026-03-28 04:59:22', '2026-03-28 04:59:27', '2026-03-28 04:59:27'),
(24, 'jdhsd', '', 'hcshdfg@gmail.com', '$2y$10$dw0poy8gDFYJxrVCnvUDX.ESIV/LcjC9PsgbMCHF.nLprI/FlDplq', 'Peminjam', '07777', '', '2026-04-01 00:10:48', '2026-04-01 00:10:56', '2026-04-01 00:10:56'),
(25, 'Aliyah Shafa', '', 'a@gmail.com', '$2y$10$I7HZzmIYvSLsFE7xhzm9duaFSHd92XQsYVTvNPcdzJ1/RhsXytmjG', 'Peminjam', '0666666', 'Bandung', '2026-04-03 03:09:27', '2026-04-03 03:31:43', '2026-04-03 03:31:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id_hp`),
  ADD KEY `id_category` (`id_category`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_peminjaman` (`id_peminjaman`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_hp` (`id_hp`);

--
-- Indexes for table `trash`
--
ALTER TABLE `trash`
  ADD PRIMARY KEY (`id_trash`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_deleted_by` (`deleted_by`),
  ADD KEY `idx_deleted_at` (`deleted_at`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id_hp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=565;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `trash`
--
ALTER TABLE `trash`
  MODIFY `id_trash` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alat`
--
ALTER TABLE `alat`
  ADD CONSTRAINT `alat_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`);

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_hp`) REFERENCES `alat` (`id_hp`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
