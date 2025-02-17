-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 04:08 PM
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
-- Database: `undangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokumentasi`
--

CREATE TABLE `dokumentasi` (
  `id` int(11) NOT NULL,
  `fid_undangan` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `fid_dokumentasi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plus`
--

CREATE TABLE `plus` (
  `plus_id` int(11) NOT NULL,
  `judul_undangan` varchar(100) NOT NULL,
  `nama_event` varchar(100) NOT NULL,
  `logo_event` varchar(100) NOT NULL,
  `logo_event2` varchar(100) NOT NULL,
  `desc_event` varchar(255) NOT NULL,
  `start_event` datetime NOT NULL,
  `end_event` datetime NOT NULL,
  `tempat_event` varchar(122) NOT NULL,
  `alamat_event` text NOT NULL,
  `template` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `email` varchar(200) NOT NULL,
  `reset_token` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `send`
--

CREATE TABLE `send` (
  `id` int(11) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `level` enum('VIP','REGULAR') NOT NULL,
  `plus_id` int(150) NOT NULL,
  `telepon` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tamu`
--

CREATE TABLE `tamu` (
  `id` int(150) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `telepon` int(150) NOT NULL,
  `token` int(150) NOT NULL,
  `fid_events` int(150) NOT NULL,
  `level` enum('VIP','REGULAR') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`) VALUES
(3, 'SMKN 71 JAKARTA', 'admin123@gmail.com', '59d5396578ed135cf066fbf8ddd6f0d2;7a5f8402658bcc9007a53000f69e04b69d4cb61032bca32e8878d0b8a003fb37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fid_undangan` (`fid_undangan`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fid_dokumentasi` (`fid_dokumentasi`);

--
-- Indexes for table `plus`
--
ALTER TABLE `plus`
  ADD PRIMARY KEY (`plus_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `send`
--
ALTER TABLE `send`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plus_id` (`plus_id`);

--
-- Indexes for table `tamu`
--
ALTER TABLE `tamu`
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
-- AUTO_INCREMENT for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plus`
--
ALTER TABLE `plus`
  MODIFY `plus_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `send`
--
ALTER TABLE `send`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD CONSTRAINT `dokumentasi_ibfk_1` FOREIGN KEY (`fid_undangan`) REFERENCES `plus` (`plus_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`fid_dokumentasi`) REFERENCES `dokumentasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `plus`
--
ALTER TABLE `plus`
  ADD CONSTRAINT `plus_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `send`
--
ALTER TABLE `send`
  ADD CONSTRAINT `send_ibfk_1` FOREIGN KEY (`plus_id`) REFERENCES `plus` (`plus_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
