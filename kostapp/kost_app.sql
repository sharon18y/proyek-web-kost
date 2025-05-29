-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2025 at 06:13 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kost_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `id_pencari` int(11) DEFAULT NULL,
  `id_pemilik` int(11) DEFAULT NULL,
  `id_kost` int(11) DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `tanggal_booking` date DEFAULT NULL,
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `id_pencari`, `id_pemilik`, `id_kost`, `nomor_telepon`, `tanggal_booking`, `status`) VALUES
(1, 6, 0, 0, '082211775186', '2025-05-12', 'pending'),
(2, 6, 0, 0, '082211775186', '2025-05-12', 'pending'),
(7, 8, 1, 5, '082211775186', '2025-05-15', 'pending'),
(10, 6, 9, 9, '082211775186', '2025-05-15', 'diterima');

-- --------------------------------------------------------

--
-- Table structure for table `kost`
--

CREATE TABLE `kost` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_kost` varchar(100) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `harga_perbulan` int(11) NOT NULL,
  `fasilitas_kamar` text DEFAULT NULL,
  `fasilitas_kamar_mandi` text DEFAULT NULL,
  `fasilitas_umum` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `jumlah_kamar` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kost`
--

INSERT INTO `kost` (`id`, `user_id`, `nama_kost`, `lokasi`, `harga_perbulan`, `fasilitas_kamar`, `fasilitas_kamar_mandi`, `fasilitas_umum`, `foto`, `created_at`, `jumlah_kamar`) VALUES
(4, 1, 'super', 'jakarta', 1000000, 'Kasur, Meja, Lemari', 'Kloset Jongkok, Kamar Mandi Dalam, Bak Mandi', 'WiFi', '6820423acefab_kost-rapih-bersih-harga-terjangkau-di-1785639353_large.jpg', '2025-05-11 06:22:50', 0),
(5, 1, 'PREMIUM', 'JAKARTA', 2000000, 'Kasur, Meja, Lemari, Kursi, Kipas, AC, Dapur', 'Kloset Jongkok, Kamar Mandi Dalam, Bak Mandi', 'WiFi, Jemuran, Parkiran', '68216fadb3a8a_WhatsApp Image 2025-05-12 at 10.44.20.jpeg', '2025-05-11 15:41:13', 0),
(6, 1, 'AEON', 'SURABAYA', 750000, 'Kasur, Meja, Lemari, AC, Dapur', 'Kloset Jongkok, Bak Mandi', 'WiFi, Jemuran, Parkiran', '68216f24d6e28_WhatsApp Image 2025-05-12 at 10.44.19.jpeg', '2025-05-12 03:46:44', 0),
(7, 1, 'MAYA KOST', 'MAKASSAR', 1500000, 'Kasur, Meja, Lemari, AC, Dapur', 'Kamar Mandi Dalam', 'WiFi, Parkiran', '68216f8059c7f_WhatsApp Image 2025-05-12 at 10.44.19 (1).jpeg', '2025-05-12 03:48:16', 0),
(8, 1, 'ROMA KOST', 'BOGOR', 3000000, 'Kasur, Meja, Lemari, Kursi, AC', 'Kamar Mandi Dalam, Bak Mandi', 'Parkiran', '68216ff887eae_WhatsApp Image 2025-05-12 at 10.44.21.jpeg', '2025-05-12 03:50:16', 0),
(9, 9, 'kost aira', 'pare pare', 2000000, 'Kasur, Meja', 'Kloset Jongkok', 'WiFi', '6825fa4acfe90_gambar kost.jpeg', '2025-05-15 14:29:30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pemilik','pencari') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`) VALUES
(1, 'sharon@gmail.com', '$2y$10$04birPcWqCidFCy4UWVkneHCf7XrG65i1ADz2D4qrrEnHJxqL7GJu', 'pemilik'),
(2, 'test@gmail.com', '$2y$10$7UXQ/fRgI4pZDCDJCUTfqOBBEmMCyoD16HcGtCVrm8ynQxqT0HAaW', 'pencari'),
(3, 'sharon1@gmail.com', '$2y$10$vmleQeTeJPpxdEU9wOnAcepPL93P224mmP0S5NRZ2SGZvjLUIoVfK', 'pemilik'),
(4, 'coba@gmail.com', '$2y$10$.7BEU0sjmZb.JU7L3sy/f.jWxrTt8LmkUzmRx0XN2WK7WZAMMxcl2', 'pencari'),
(5, 'saya@gmail.com', '$2y$10$8zE3nZSn6cuOqjC4TV6LbOgn6NNpzVDO/48m/hAzE7T.oNEg6LPBO', 'pemilik'),
(6, 'bravo@gmail.com', '$2y$10$AWf4xt5hrJfOOIbCn4RwAO/gDpVsOWUtGNm1wJSaI8galCDFH528i', 'pencari'),
(7, 'haahah@gmail.com', '$2y$10$6Ej/BJdoX55nIis//EN6PeXJey/42I0WJje.kyPnQ3C1Nw6kCbeIO', 'pencari'),
(8, 'punyakelompokaqmal@gmail.com', '$2y$10$d3UI/thCNoa5lrm12d1T.uLp2wdaOg2jGOXtD/HFC7xIbHM6KOCLy', 'pencari'),
(9, 'sharon11@gmail.com', '$2y$10$5ucdfiKrNxOzUhB61.ZpSON/3UYn1zkAZLaMKFhvkLclMHMKiASLa', 'pemilik');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kost`
--
ALTER TABLE `kost`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kost`
--
ALTER TABLE `kost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kost`
--
ALTER TABLE `kost`
  ADD CONSTRAINT `kost_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
