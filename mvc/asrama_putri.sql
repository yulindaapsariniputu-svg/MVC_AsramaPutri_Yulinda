-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2026 at 04:57 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asrama_putri`
--

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int NOT NULL,
  `nomor_kamar` varchar(50) NOT NULL,
  `kapasitas` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nim` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `prodi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_penghuni` int NOT NULL,
  `bulan_bayar` varchar(50) NOT NULL,
  `jumlah_bayar` int NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penghuni`
--

CREATE TABLE `penghuni` (
  `id_penghuni` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `id_kamar` int NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `status_penghuni` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('Super Admin','Admin') NOT NULL DEFAULT 'Admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `username`, `password`, `email`, `nama_lengkap`, `role`, `created_at`) VALUES
(111, 'admin12', '$2y$10$HTow5KpLy4DkWGMlivX.Iu0S3Q9crIMrrtr5p8tKEFdl.uakE.7Ha', 'admin@gmail.com', 'admin', 'Admin', '2026-10-31 10:20:28'),
(222, 'kiko123', '123456', 'kiko@gmail.com', 'kiko', 'Admin', '2026-10-31 10:14:41'),
(333, 'yuli123', '$2y$10$8bhdCOvRJ4DeZ1ZquZN9seySFEHPh6xRKdbUeeCFsC/AOFEyWlhF.', 'yuli@gmail.com', 'yuli', 'Admin', '2026-11-02 06:07:04'),
(444, 'apap123', '$2y$10$AFtR/Mw3siKO5YmQ3gxbIOMSb9RLvaP7/OIAd4PQwO09R49RCIdE.', 'apap@gmail.com', 'apap', 'Admin', '2026-11-02 07:20:12');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kamar`
--

CREATE TABLE `tb_kamar` (
  `id_kamar` int NOT NULL,
  `nomor_kamar` varchar(10) NOT NULL,
  `kapasitas` int NOT NULL,
  `harga_per_bulan` decimal(10,2) NOT NULL,
  `status` enum('Tersedia','Penuh') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_kamar`
--

INSERT INTO `tb_kamar` (`id_kamar`, `nomor_kamar`, `kapasitas`, `harga_per_bulan`, `status`) VALUES
(7, '203', 4, '100000.00', 'Tersedia'),
(8, '101', 4, '100000.00', 'Penuh'),
(9, '102', 4, '100000.00', 'Tersedia'),
(10, '104', 4, '100000.00', 'Tersedia'),
(11, '103', 4, '100000.00', 'Tersedia'),
(12, '201', 4, '100000.00', 'Tersedia'),
(13, '202', 4, '100000.00', 'Tersedia'),
(14, '204', 4, '100000.00', 'Tersedia'),
(15, '205', 4, '100000.00', 'Tersedia'),
(16, '206', 4, '100000.00', 'Tersedia'),
(17, '208', 4, '100000.00', 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `tb_mahasiswa`
--

CREATE TABLE `tb_mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_mahasiswa`
--

INSERT INTO `tb_mahasiswa` (`id_mahasiswa`, `nim`, `nama`, `prodi`, `no_hp`, `email`, `alamat`) VALUES
(1, '240101001', 'Ni Putu Yulinda', 'Informatika', '081234567890', 'yulinda@gmail.com', 'Bangli'),
(4, '2413231018', 'putu lina', 'hukum hindu', '08999999', 'lina@gmail.com', 'br.selati'),
(5, '2121', 'hhh', 'Kewirausahaan', '08111111111', 'una2y@gmail.com', NULL),
(6, '56666', 'ggg', 'Pendidikan Guru Sekolah Dasar Hindu', '777777', 'gg@gmail.com', NULL),
(7, '7777', 'uu', 'Ilmu Komunikasi Hindu', '111111', 'uu@gmail.com', NULL),
(8, '6666', 'dd', 'Informatika', '56666', 'dd@gmail.com', NULL),
(9, '3434', 'juju', 'Desain Komunikasi Visual', '67676', 'juju@gmail.com', NULL),
(10, '87878', 'liu', 'Pendidikan Guru Sekolah Dasar Hindu', '8888888', 'liu@gmail.com', NULL),
(11, '1212125', 'ris', 'Industri Perjalanan', '89890000', 'ris@gmail.com', NULL),
(12, '2424242424', 'yuja', 'Penerangan Agama Hindu', '089777777111', 'yuja@gmail.com', NULL),
(13, '234131441414', 'jojo', 'Desain Komunikasi Visual', '0899994555', 'jojo@gmail.com', NULL),
(16, '78790000', 'jujuyyy', 'Kewirausahaan', '0877777756544', 'jujuyyy@gmail.com', NULL),
(17, '4546777', 'bujang', 'Pendidikan Guru Sekolah Dasar Hindu', '08999999', 'bujang@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_penghuni` int NOT NULL,
  `bulan_bayar` varchar(20) NOT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `tanggal_bayar` date NOT NULL,
  `status_bayar` enum('Lunas','Belum Lunas') DEFAULT 'Lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pembayaran`
--

INSERT INTO `tb_pembayaran` (`id_pembayaran`, `id_penghuni`, `bulan_bayar`, `jumlah_bayar`, `tanggal_bayar`, `status_bayar`) VALUES
(3, 10, '2026-11', '100000.00', '2026-11-02', 'Lunas'),
(4, 11, '2026-11', '100000.00', '2026-11-02', 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `tb_penghuni`
--

CREATE TABLE `tb_penghuni` (
  `id_penghuni` int NOT NULL,
  `id_mahasiswa` int NOT NULL,
  `id_kamar` int NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `status_penghuni` enum('Aktif','Keluar') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_penghuni`
--

INSERT INTO `tb_penghuni` (`id_penghuni`, `id_mahasiswa`, `id_kamar`, `tanggal_masuk`, `status_penghuni`) VALUES
(3, 5, 8, '2026-10-31', 'Aktif'),
(4, 1, 8, '2026-10-31', 'Aktif'),
(5, 6, 8, '2026-10-31', 'Aktif'),
(10, 8, 8, '2026-11-02', 'Aktif'),
(11, 9, 9, '2026-11-02', 'Aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `penghuni`
--
ALTER TABLE `penghuni`
  ADD PRIMARY KEY (`id_penghuni`);

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tb_kamar`
--
ALTER TABLE `tb_kamar`
  ADD PRIMARY KEY (`id_kamar`),
  ADD UNIQUE KEY `nomor_kamar` (`nomor_kamar`);

--
-- Indexes for table `tb_mahasiswa`
--
ALTER TABLE `tb_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`);

--
-- Indexes for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_penghuni` (`id_penghuni`);

--
-- Indexes for table `tb_penghuni`
--
ALTER TABLE `tb_penghuni`
  ADD PRIMARY KEY (`id_penghuni`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penghuni`
--
ALTER TABLE `penghuni`
  MODIFY `id_penghuni` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=445;

--
-- AUTO_INCREMENT for table `tb_kamar`
--
ALTER TABLE `tb_kamar`
  MODIFY `id_kamar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_mahasiswa`
--
ALTER TABLE `tb_mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_penghuni`
--
ALTER TABLE `tb_penghuni`
  MODIFY `id_penghuni` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD CONSTRAINT `tb_pembayaran_ibfk_1` FOREIGN KEY (`id_penghuni`) REFERENCES `tb_penghuni` (`id_penghuni`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_penghuni`
--
ALTER TABLE `tb_penghuni`
  ADD CONSTRAINT `tb_penghuni_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `tb_mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_penghuni_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `tb_kamar` (`id_kamar`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
