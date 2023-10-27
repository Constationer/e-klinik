-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2023 at 12:11 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `checks`
--

CREATE TABLE `checks` (
  `id` int(5) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `employee_id` int(5) DEFAULT NULL,
  `doctor_id` int(5) DEFAULT NULL,
  `check_type` enum('Periksa','Non Periksa') DEFAULT NULL,
  `date` date DEFAULT NULL,
  `tinggi` varchar(255) DEFAULT NULL,
  `berat` varchar(255) DEFAULT NULL,
  `suhu` varchar(255) DEFAULT NULL,
  `tekanan` varchar(255) DEFAULT NULL,
  `asam_urat` varchar(255) DEFAULT NULL,
  `kolesterol` varchar(255) DEFAULT NULL,
  `hasil` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `therapy` text DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `nextdate` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(3) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checks`
--

INSERT INTO `checks` (`id`, `code`, `employee_id`, `doctor_id`, `check_type`, `date`, `tinggi`, `berat`, `suhu`, `tekanan`, `asam_urat`, `kolesterol`, `hasil`, `description`, `diagnosis`, `therapy`, `status`, `nextdate`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(3, 'CHECK-20231026-001', 5, 1, 'Periksa', '2023-12-31', '150cm', '2kg', '10', '10/10', NULL, NULL, 'hasil', NULL, NULL, NULL, 1, '2023-12-31', '2023-10-26 14:45:45', 1, '2023-10-26 14:45:45', 1, NULL, NULL),
(4, 'CHECK-20231026-002', 5, 1, 'Periksa', '2023-12-31', '170cm', '50kg', '37c', '90/100', '1', '2', 'berat badan melebihi berat badan ideal', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 1, '2023-12-31', '2023-10-26 14:49:08', 1, '2023-10-27 12:32:51', 1, NULL, NULL),
(5, 'CHECK-20231027-001', 5, 1, 'Periksa', '2023-12-31', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 1, '2023-12-01', '2023-10-27 10:25:20', 1, '2023-10-27 10:25:20', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `check_medicines`
--

CREATE TABLE `check_medicines` (
  `id` int(10) NOT NULL,
  `check_id` int(5) DEFAULT NULL COMMENT 'checks',
  `medicine_id` int(5) DEFAULT NULL COMMENT 'medicines',
  `medicine_purchase_id` int(10) DEFAULT NULL COMMENT 'medicine_purchases.id',
  `qty` int(5) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(3) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(5) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `specialist` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(3) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `position`, `specialist`, `phone`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'Dokter A', 'Dokter', 'Semua', '01', 1, '2023-10-26 08:37:43', 1, '2023-10-26 08:37:43', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(5) NOT NULL,
  `data_type` enum('Pegawai','Istri','Anak') NOT NULL DEFAULT 'Pegawai',
  `data_parent` int(5) DEFAULT NULL COMMENT 'employees.id',
  `nip` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `religion` enum('Islam','Kristen Protestan','Katolik','Hindu','Buddha','Konghucu') DEFAULT NULL,
  `gender` enum('Pria','Wanita') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `marital_status` enum('Belum Nikah','Nikah','Cerai Hidup','Cerai Mati') DEFAULT NULL,
  `education` varchar(20) DEFAULT NULL,
  `rank` varchar(50) DEFAULT NULL COMMENT 'pangkat',
  `class` varchar(50) DEFAULT NULL COMMENT 'golongan',
  `position` varchar(50) DEFAULT NULL COMMENT 'jabatan',
  `work_unit` varchar(50) DEFAULT NULL COMMENT 'unit kerja',
  `handphone` varchar(15) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `akses` varchar(6) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(3) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `data_type`, `data_parent`, `nip`, `name`, `place_of_birth`, `date_of_birth`, `religion`, `gender`, `address`, `blood_type`, `marital_status`, `education`, `rank`, `class`, `position`, `work_unit`, `handphone`, `status`, `akses`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(5, 'Pegawai', 0, '123456789', 'Cidam', 'Makassar', '2023-12-31', 'Islam', 'Pria', 'Jl Baru', 'O-', 'Belum Nikah', '1', '2', '3', '4', '5', '081996177775', 1, 'FIWPOE', '2023-10-26 14:44:29', 1, '2023-10-26 14:53:40', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(10) NOT NULL,
  `source` varchar(50) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `source`, `user_id`, `description`, `updated_at`) VALUES
(1, 'master_pegawai', 1, 'Menambahkan data Dimas', '2023-10-26 08:36:59'),
(2, 'master_dokter', 1, 'Menambahkan data Dokter A', '2023-10-26 08:37:43'),
(3, 'master_pegawai', 1, 'Menambahkan data 2', '2023-10-26 11:10:16'),
(4, 'master_pegawai', 1, 'Mengubah data AA Cidam', '2023-10-26 11:11:00'),
(5, 'master_pegawai', 1, 'Mengubah data AA Cidam', '2023-10-26 11:21:08'),
(6, 'master_pegawai', 1, 'Menambahkan data 1', '2023-10-26 11:27:36'),
(7, 'master_pegawai', 1, 'Menambahkan data Master Cidam', '2023-10-26 11:29:03'),
(8, 'master_pegawai', 1, 'Menambahkan data Cidam', '2023-10-26 14:44:33');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(5) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `category` enum('Padat','Cair','Gel/Salep','Serbuk','Lainnya') DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(3) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_purchases`
--

CREATE TABLE `medicine_purchases` (
  `id` int(10) NOT NULL,
  `medicine_id` int(5) DEFAULT NULL COMMENT 'medicine',
  `invoice_number` varchar(255) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(5) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(5) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(5) NOT NULL,
  `section` enum('front','back') NOT NULL DEFAULT 'back',
  `parent` int(2) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `order` int(1) DEFAULT NULL,
  `link` varchar(100) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `divider` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` int(1) NOT NULL DEFAULT 1,
  `user_group_1` int(1) NOT NULL DEFAULT 1,
  `user_group_2` int(1) NOT NULL DEFAULT 1,
  `user_group_3` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `section`, `parent`, `name`, `order`, `link`, `icon`, `divider`, `status`, `user_group_1`, `user_group_2`, `user_group_3`) VALUES
(1, 'back', NULL, 'Dashboard', 1, 'dashboard', 'bi bi-menu-button-wide', 'N', 1, 1, 1, 0),
(2, 'back', NULL, 'Master', 2, '#', 'bi bi-grid', 'N', 1, 1, 1, 0),
(3, 'back', NULL, 'Pemeriksaan', 3, 'periksa', 'bi bi-bookmark-heart-fill', 'N', 1, 1, 1, 0),
(4, 'back', NULL, 'Laporan', 4, '#', 'bi bi-clipboard-check', 'N', 1, 1, 1, 0),
(5, 'back', NULL, 'Pengaturan', 5, 'pengaturan', 'bi bi-wrench', 'N', 1, 1, 1, 0),
(6, 'back', 2, 'Obat', 1, 'master-obat', NULL, 'N', 1, 1, 1, 0),
(7, 'back', 2, 'Pegawai', 2, 'master-pegawai', NULL, 'N', 1, 1, 1, 0),
(8, 'back', 2, 'Dokter', 3, 'master-dokter', NULL, 'N', 1, 1, 1, 0),
(9, 'back', 2, 'User', 4, 'master-user', NULL, 'N', 1, 1, 1, 0),
(10, 'back', 4, 'Mutasi Obat', 1, 'laporan-mutasi-obat', NULL, 'N', 1, 1, 1, 0),
(11, 'back', 4, 'Persediaan Obat', 2, 'laporan-persediaan-obat', NULL, 'N', 1, 1, 1, 0),
(12, 'back', 4, 'Kunjungan Pasien', 3, 'laporan-kunjungan-pasien', NULL, 'N', 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting_sites`
--

CREATE TABLE `setting_sites` (
  `id` int(2) NOT NULL,
  `note` varchar(50) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setting_sites`
--

INSERT INTO `setting_sites` (`id`, `note`, `value`, `updated_at`, `updated_by`) VALUES
(1, 'web_name', 'E-Klinik BPK Sulteng', '2023-07-03 05:53:42', 1),
(2, 'web_desc', 'E-Klinik Badan Pemeriksa Keuangan Perwakilan Provinsi Sulawesi Tengah', '2023-06-26 11:05:47', 1),
(3, 'logo_name', 'logo.png', '2022-07-25 03:01:20', 1),
(4, 'logo_width', '200px', '2022-07-25 03:01:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(3) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `remember_token`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'SuperAdmin', 'superadmin@email.com', NULL, '$2y$10$PGXuvkbT/B8dUaa4eHezPuaavSuL8hRNMTyCEAGLluVtiZ1PtH7lm', '081', NULL, 1, '2022-07-28 15:21:14', NULL, '2023-06-24 01:14:42', 1, '2023-06-10 02:22:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id` int(5) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(3) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(3) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(3) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `name`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `status`) VALUES
(1, 'Admin', '2023-06-10 01:54:39', 1, '2023-06-10 01:54:39', 1, NULL, NULL, 1),
(2, 'User', '2023-06-10 01:54:39', 1, '2023-06-10 01:54:39', 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(10) NOT NULL,
  `user_id` int(10) DEFAULT NULL,
  `user_group_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `user_group_id`) VALUES
(1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checks`
--
ALTER TABLE `checks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `check_medicines`
--
ALTER TABLE `check_medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicine_purchases`
--
ALTER TABLE `medicine_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `setting_sites`
--
ALTER TABLE `setting_sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checks`
--
ALTER TABLE `checks`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `check_medicines`
--
ALTER TABLE `check_medicines`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine_purchases`
--
ALTER TABLE `medicine_purchases`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting_sites`
--
ALTER TABLE `setting_sites`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
