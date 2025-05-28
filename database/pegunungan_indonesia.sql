-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2025 at 02:05 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pegunungan_indonesia`
--

-- --------------------------------------------------------

--
-- Table structure for table `mountains`
--

CREATE TABLE `mountains` (
  `id` int NOT NULL,
  `nama_gunung` varchar(100) NOT NULL,
  `tinggi` int NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `provinsi` varchar(50) NOT NULL,
  `tingkat_kesulitan` enum('Mudah','Sedang','Sulit','Sangat Sulit') NOT NULL,
  `deskripsi` text,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mountains`
--

INSERT INTO `mountains` (`id`, `nama_gunung`, `tinggi`, `lokasi`, `provinsi`, `tingkat_kesulitan`, `deskripsi`, `gambar`, `created_at`, `updated_at`) VALUES
(10, 'Gunung Kerinci', 3805, 'Kabupaten Kerinci', 'Jambi', 'Sulit', 'Gunung tertinggi di Sumatera dan merupakan gunung berapi aktif tertinggi di Indonesia.', 'gunung1.jpg', '2025-05-27 12:35:48', '2025-05-27 12:35:48'),
(11, 'Gunung Rinjani', 3726, 'Lombok Timur', 'NTT', 'Sulit', 'Gunung berapi aktif dengan danau kawah yang indah', 'gunung2.jpg', '2025-05-27 12:35:48', '2025-05-27 12:45:50'),
(12, 'Gunung Semeru', 3676, 'Lumajang', 'Jawa Timur', 'Sangat Sulit', 'Puncak tertinggi di Pulau Jawa dan salah satu gunung berapi paling aktif.', 'gunung3.jpg', '2025-05-27 12:35:48', '2025-05-27 12:35:48'),
(13, 'Gunung Slamet', 3432, 'Banyumas', 'Jawa Tengah', 'Sulit', 'Gunung tertinggi di Jawa Tengah dengan jalur pendakian yang cukup menantang.', 'gunung4.jpg', '2025-05-27 12:35:48', '2025-05-27 12:35:48'),
(15, 'Gunung Sindoro', 3136, 'Temanggung', 'Jawa Tengah', 'Sedang', 'Gunung kembar dari Gunung Sumbing, menawarkan pemandangan yang indah dari puncak.', 'gunung1.jpg', '2025-05-27 12:35:48', '2025-05-27 12:35:48'),
(18, 'Gunung Merapi', 2930, 'Yogyakarta & Magelang', 'DI Yogyakarta', 'Sangat Sulit', 'Salah satu gunung berapi paling aktif di dunia, sering menjadi pusat penelitian vulkanologi.', 'gunung4.jpg', '2025-05-27 12:35:48', '2025-05-27 12:35:48'),
(19, 'Gunung Lawu', 3265, 'Karanganyar', 'Jawa Tengah', 'Sedang', 'Gunung bersejarah dengan banyak situs spiritual, cocok untuk pendakian budaya dan alam.', 'gunung5.jpg', '2025-05-27 12:35:48', '2025-05-27 12:35:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mountains`
--
ALTER TABLE `mountains`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mountains`
--
ALTER TABLE `mountains`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
