-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2026 at 01:46 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penyuluhan_kb`
--

-- --------------------------------------------------------

--
-- Table structure for table `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `metode_kb` varchar(50) DEFAULT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftar`
--

INSERT INTO `pendaftar` (`id`, `nama_lengkap`, `nik`, `metode_kb`, `tanggal_daftar`) VALUES
(1, 'joko', '1111', 'Suntik', '2026-03-15 11:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin','penyuluh','kader') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'admin', '123', 'mmm', 'admin'),
(3, 'admin1', '$2y$10$89vLqF3G5z8qVjFz/nQ0MeO6eGZ8VfXw6.hP.eO7X1Y2Z3A4B5C6D', 'Administrator Utama', 'admin'),
(6, 'admin2', '$2y$10$5w1OwYxcXCyLeYh6rULWOOEudhcnPgKLyBj1cbM1doIElt0X5Bvri', 'Administrator Utama', 'admin'),
(7, 'HRD', '$2y$10$5w1OwYxcXCyLeYh6rULWOOEudhcnPgKLyBj1cbM1doIElt0X5Bvri', 'sss', 'penyuluh'),
(8, 'Kader', '$2y$10$OYAf4Oh17u2GYHK0xLknleAsKhSbDcb/wllPNDsTmS6digIBnEZqa', 'mayati', 'penyuluh'),
(9, 'mm', '$2y$10$fol6BoJe1OoeP3A2H3v4NOni4tHghxdfKfKDh6fVnyen.woO917tu', 'nnn', 'kader');

-- --------------------------------------------------------

--
-- Table structure for table `warga_kb`
--

CREATE TABLE `warga_kb` (
  `id` int(11) NOT NULL,
  `nama_istri` varchar(100) DEFAULT NULL,
  `nama_suami` varchar(100) DEFAULT NULL,
  `jumlah_anak` int(11) DEFAULT NULL,
  `metode_kontrasepsi` varchar(50) DEFAULT NULL,
  `tanggal_kunjungan` date DEFAULT NULL,
  `kader_penginput` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warga_kb`
--

INSERT INTO `warga_kb` (`id`, `nama_istri`, `nama_suami`, `jumlah_anak`, `metode_kontrasepsi`, `tanggal_kunjungan`, `kader_penginput`, `keterangan`) VALUES
(1, 'nnn', 'nn', 2, 'Implan', '2026-03-15', 'mm', 'nnnn');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `warga_kb`
--
ALTER TABLE `warga_kb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `warga_kb`
--
ALTER TABLE `warga_kb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
