-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 07, 2022 at 12:50 AM
-- Server version: 5.7.33
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new_antrian`
--

-- --------------------------------------------------------

--
-- Table structure for table `antrian`
--

CREATE TABLE `antrian` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `poli` int(11) NOT NULL,
  `nomor_antrian` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('BELUM SELESAI','SELESAI') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `antrian`
--

INSERT INTO `antrian` (`id`, `user`, `poli`, `nomor_antrian`, `date`, `status`) VALUES
(1, 1, 2, 1, '2022-06-07', 'BELUM SELESAI'),
(41, 99, 2, 2, '2022-06-07', 'SELESAI');

-- --------------------------------------------------------

--
-- Table structure for table `poli`
--

CREATE TABLE `poli` (
  `id` int(11) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `title` varchar(255) NOT NULL,
  `limits` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `poli`
--

INSERT INTO `poli` (`id`, `kode`, `title`, `limits`) VALUES
(2, 'PANAK', 'Poli Anak', 50),
(3, 'POIH', 'Poliklinik Ibu Hamil', 30);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nomor_identitas` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `tl` date DEFAULT NULL,
  `address` text NOT NULL,
  `gender` enum('laki-laki','perempuan') NOT NULL,
  `role` enum('pasien','admin') NOT NULL,
  `qrcode` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nomor_identitas`, `username`, `password`, `name`, `phone`, `tl`, `address`, `gender`, `role`, `qrcode`, `created_at`, `updated_at`) VALUES
(1, '3512323127362', 'admin', '$2a$12$uTVw1FwEA7GW.hpkrwkMwOxqJIo4/fia/ooRqrdii6jKA3XdballO', 'admin', '0823746627362', '2022-06-06', 'Jember', 'laki-laki', 'admin', NULL, '2022-06-07 02:05:27', '2022-06-07 02:05:27'),
(99, '35123421233453', 'test123', '$2a$12$yGnPJUXQ3OKDWmXvtHNPTOBuPMW2MoVyTGtwDysAPbFnGWkw2/she', 'test123', '087675647323', '2022-06-23', 'Jember', 'laki-laki', 'pasien', NULL, '2022-06-06 23:40:00', '2022-06-06 23:40:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrian`
--
ALTER TABLE `antrian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antrian`
--
ALTER TABLE `antrian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `poli`
--
ALTER TABLE `poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;