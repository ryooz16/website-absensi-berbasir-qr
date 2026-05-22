-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 22, 2026 at 05:57 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_absensi_sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi_guru`
--

CREATE TABLE `absensi_guru` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `status` enum('hadir','terlambat','alpha','izin','sakit') COLLATE utf8mb4_unicode_ci DEFAULT 'hadir',
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `absensi_siswa`
--

CREATE TABLE `absensi_siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_kelas_id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `mata_pelajaran_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','izin','sakit','alpha') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `created_at`, `updated_at`) VALUES
(5, '7-1', '2026-05-03 13:30:03', '2026-05-03 13:30:03'),
(6, '7-2', '2026-05-03 13:30:53', '2026-05-03 13:30:53'),
(8, '8-2', '2026-05-03 13:37:33', '2026-05-03 13:37:33'),
(9, '8-1', '2026-05-03 13:37:38', '2026-05-03 13:37:38'),
(10, '9-1', '2026-05-03 13:56:36', '2026-05-03 13:56:36'),
(11, '9-2', '2026-05-03 13:56:45', '2026-05-03 13:56:45'),
(13, '7-3', '2026-05-13 02:07:18', '2026-05-13 02:07:18'),
(14, '7-4', '2026-05-13 02:07:42', '2026-05-13 02:07:42'),
(15, '8-3', '2026-05-13 02:07:52', '2026-05-13 02:07:52'),
(16, '8-4', '2026-05-13 02:08:11', '2026-05-13 02:08:11'),
(17, '7-5', '2026-05-13 07:09:15', '2026-05-13 07:09:15'),
(18, '9-3', '2026-05-13 07:28:44', '2026-05-13 07:28:44'),
(19, '9-4', '2026-05-13 07:29:43', '2026-05-13 07:29:43');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id`, `nama_mapel`, `created_at`, `updated_at`) VALUES
(2, 'Matematika', '2026-05-03 02:36:19', '2026-05-03 02:36:19'),
(3, 'Bahasa Indonesia', '2026-05-03 02:36:19', '2026-05-03 02:36:19'),
(4, 'PJOK', '2026-05-03 02:36:19', '2026-05-03 02:36:19'),
(5, 'Bahasa Inggris', '2026-05-03 02:36:19', '2026-05-03 02:36:19'),
(6, 'Teknologi Informasi', '2026-05-03 02:36:19', '2026-05-03 02:36:19'),
(8, 'Ilmu Pengetahuan Sosial', '2026-05-03 02:36:19', '2026-05-03 02:36:19'),
(10, 'Pendidikan Agama Islam', '2026-05-03 02:36:34', '2026-05-03 02:36:34'),
(12, 'Teknologi Informasi Dasar', '2026-05-10 05:38:16', '2026-05-10 05:38:16'),
(13, 'Ilmu Pengetahuan Alam', '2026-05-10 05:38:42', '2026-05-10 05:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_28_150120_create_siswas_table', 1),
(5, '2026_04_28_150121_create_mata_pelajarans_table', 1),
(6, '2026_04_28_150122_create_kelas_table', 1),
(7, '2026_04_28_150123_create_siswa_kelas_table', 1),
(8, '2026_04_28_150125_create_absensi_siswas_table', 1),
(9, '2026_04_28_150127_create_absensi_gurus_table', 1),
(10, '2026_04_28_150129_create_wali_kelas_table', 1),
(11, '2026_05_02_145349_create_settings_table', 2),
(12, '2026_05_03_005022_add_izin_sakit_to_absensi_guru_status', 3),
(13, '2026_05_03_211106_create_tahun_ajarans_table', 4),
(14, '2026_05_03_211316_add_tahun_ajaran_id_to_siswa_kelas_table', 4),
(15, '2026_05_03_211956_add_tahun_ajaran_id_to_absensi_siswa_table', 5),
(16, '2026_05_03_212324_add_tahun_ajaran_id_to_absensi_guru_table', 6),
(17, '2026_05_04_002922_add_tahun_ajaran_id_to_wali_kelas_table', 7),
(18, '2026_05_05_232649_add_date_range_to_tahun_ajarans_table', 8),
(19, '2026_05_22_003828_add_path_arsip_to_tahun_ajarans_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('8RZXmlwE5oo1h79ffS0sIUUwWQereOg4DtbEOiXy', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYlAwS3RVZFNpMWprWnNBdUVGS2JKVWwzeGVFclNuU2c3Nml6UWF2bCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHA6Ly91bnR5aW5nLWRpYXBlci1odW5ncnkubmdyb2stZnJlZS5kZXYvYWRtaW4vcXItcHJlc2Vuc2kvZnVsbCI7czo1OiJyb3V0ZSI7czoyMToiYWRtaW4ucXItYWJzZW5zaS5mdWxsIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjQ7fQ==', 1779385552),
('Ypo9PVUjgip6UyD4Lz86Muc7AgR3lP5tbM4ngGup', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZkFrUXNTd3Z2cTVyMkxKT0xqZzBKSm02MG81WTBtU3I5ZXc2VFQ1NiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9tYXBlbCI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ubWFwZWwuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyNDt9', 1779387004);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'qr_interval', '3', '2026-05-02 07:54:11', '2026-05-15 05:50:23'),
(2, 'qr_active', '1', '2026-05-02 10:28:47', '2026-05-15 05:49:30'),
(3, 'qr_start_time', '00:00', '2026-05-02 10:28:47', '2026-05-02 10:30:21'),
(4, 'qr_end_time', '15:00', '2026-05-02 10:28:47', '2026-05-12 08:21:55');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `siswa_kelas`
--

CREATE TABLE `siswa_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajarans`
--

CREATE TABLE `tahun_ajarans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_tahun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nonaktif',
  `path_arsip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_ajarans`
--

INSERT INTO `tahun_ajarans` (`id`, `nama_tahun`, `tanggal_mulai`, `tanggal_selesai`, `status`, `path_arsip`, `created_at`, `updated_at`) VALUES
(17, '2026/2027', '2027-08-01', '2028-08-02', 'aktif', NULL, '2026-05-21 17:52:39', '2026-05-21 17:52:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guru',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(3, 'Bayu Firmansyah', 'bayu@gmail.com', '$2y$12$wijieqdFFTjKlal02XPeMOmn6/HuvPdLjKprovp0r0kem7xVSMbD6', 'guru', '2026-04-28 09:52:20', '2026-04-28 09:52:20'),
(4, 'Andi Saputra', 'andi@gmail.com', '$2y$12$WQroZSzzt3gIDHNFtMh3guStRdZbbsrZNs19pKMbmFoz9BzRTklm.', 'guru', '2026-05-03 02:32:43', '2026-05-03 02:32:43'),
(5, 'Rizky Pratama', 'rizky@gmail.com', '$2y$12$fAhC77LfngWkP7LZIARCrOHnnfgp2YAXJ/MDEJdo2vaScbYzTg4iC', 'guru', '2026-05-03 02:32:43', '2026-05-03 02:32:43'),
(6, 'Dimas Setiawan', 'dimas@gmail.com', '$2y$12$OOWMBlZSpD1bCWLAEW4iKuQqDVC3lw5XkvX59TtbiCp.Cge3GhXmm', 'guru', '2026-05-03 02:32:43', '2026-05-03 02:32:43'),
(7, 'Fajar Nugroho', 'fajar@gmail.com', '$2y$12$0/QYsE0FQYB5j1/llU2fZuUX5U4TQkqAhKTwi3F1tqM29yidyoDPi', 'guru', '2026-05-03 02:32:44', '2026-05-03 02:32:44'),
(8, 'Agus Salim', 'agus@gmail.com', '$2y$12$SoqVe0a7zltraCoC4JPg9u4siJ2bpS/N9C3N7dqyWRNI1LnphOiCW', 'guru', '2026-05-03 02:32:44', '2026-05-03 02:32:44'),
(9, 'Budi Santoso', 'budi@gmail.com', '$2y$12$Qv4ZjLnva582GeJ0F0G/uOSTsOdCLNKCd3Zxx3GY.H9KFkurFX0by', 'guru', '2026-05-03 02:32:44', '2026-05-03 02:32:44'),
(11, 'Eko Prasetyo', 'eko@gmail.com', '$2y$12$3A9nte9BbTM2LgIaJE6NPuiLhTeMSRNRApB8QCSncguz9yhibPmUO', 'guru', '2026-05-03 02:32:45', '2026-05-13 02:23:33'),
(12, 'Hendra Wijaya', 'hendra@gmail.com', '$2y$12$On0EkTj.TgeR0Co1N5cuTeKqbDfMLjtEhhJAdjHMQX8vwMtS9w3IG', 'guru', '2026-05-03 02:32:45', '2026-05-12 03:12:05'),
(13, 'Taufik Hidayat', 'taufik@gmail.com', '$2y$12$/cFBg8YSbMo6iZI4jiodxuUs9w8xXMv3fMzOm62Vcze1OQSPJrM5a', 'guru', '2026-05-03 02:32:45', '2026-05-03 02:32:45'),
(14, 'Yoga Permana', 'yoga@gmail.com', '$2y$12$fVj14hC18ZmiCa3LBCjZOOOh3A6F9j.NaxZu/P3AmDDxKXtgz5DNu', 'guru', '2026-05-03 02:32:46', '2026-05-03 02:32:46'),
(15, 'Ilham Maulana', 'ilham@gmail.com', '$2y$12$Iz6ZPskekySPnjGetDGBxuu.xJ/CrSl3f.JQxSfAHZUrftlQSKChK', 'guru', '2026-05-03 02:32:46', '2026-05-03 02:32:46'),
(16, 'Arif Rahman', 'arif@gmail.com', '$2y$12$UvoEDsBYqZrE8URIndViHuqR3elpTrbaIXGIymvlQvMpXlNcqIiPC', 'guru', '2026-05-03 02:32:46', '2026-05-03 02:32:46'),
(17, 'Zaki Mubarak', 'zaki@gmail.com', '$2y$12$YXn2Sk1D2oPX86gDbu3FquKAHF.0qzTItprGBvWFWdDfKS3KgFOL6', 'guru', '2026-05-03 02:32:46', '2026-05-03 02:32:46'),
(20, 'Naufal Fadillah', 'naufal@gmail.com', '$2y$12$As510BJxqwqIbPwBLCWHwu1HtKqDt.JEnnMyrJTr5udjjaOdCA6e2', 'guru', '2026-05-03 02:32:47', '2026-05-13 07:39:39'),
(21, 'Iqbal Ramadhan', 'iqbal@gmail.com', '$2y$12$rvvodlRAPsACrDmLNt1huunBltwg.aBXyu2o/Af5wK04uppKAtQSe', 'guru', '2026-05-03 02:32:47', '2026-05-12 07:24:54'),
(22, 'Rafi Saputra', 'rafi@gmail.com', '$2y$12$xPz9UP8YHZwxvUoeBY2ka.UmVXogxTJD/b9i5McD/eOx3mJg.E3Ny', 'guru', '2026-05-03 02:32:47', '2026-05-11 08:27:27'),
(24, 'andi gumar', 'andi@admin.com', '$2y$12$w/h9wfkFQWlrJSRxQp8vHuXKRkU5roE9FMyH3vWzMA0zBCf56HWOC', 'admin', '2026-05-03 17:06:13', '2026-05-09 05:03:44');

-- --------------------------------------------------------

--
-- Table structure for table `wali_kelas`
--

CREATE TABLE `wali_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint UNSIGNED DEFAULT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi_guru`
--
ALTER TABLE `absensi_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensi_guru_guru_id_foreign` (`guru_id`),
  ADD KEY `absensi_guru_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensi_siswa_siswa_kelas_id_foreign` (`siswa_kelas_id`),
  ADD KEY `absensi_siswa_guru_id_foreign` (`guru_id`),
  ADD KEY `absensi_siswa_kelas_id_foreign` (`kelas_id`),
  ADD KEY `absensi_siswa_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  ADD KEY `absensi_siswa_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswa_nis_unique` (`nis`);

--
-- Indexes for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_kelas_siswa_id_foreign` (`siswa_id`),
  ADD KEY `siswa_kelas_kelas_id_foreign` (`kelas_id`),
  ADD KEY `siswa_kelas_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `tahun_ajarans`
--
ALTER TABLE `tahun_ajarans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wali_kelas_guru_id_foreign` (`guru_id`),
  ADD KEY `wali_kelas_kelas_id_foreign` (`kelas_id`),
  ADD KEY `wali_kelas_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi_guru`
--
ALTER TABLE `absensi_guru`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=736;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=950;

--
-- AUTO_INCREMENT for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2343;

--
-- AUTO_INCREMENT for table `tahun_ajarans`
--
ALTER TABLE `tahun_ajarans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi_guru`
--
ALTER TABLE `absensi_guru`
  ADD CONSTRAINT `absensi_guru_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_guru_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  ADD CONSTRAINT `absensi_siswa_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_siswa_kelas_id_foreign` FOREIGN KEY (`siswa_kelas_id`) REFERENCES `siswa_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  ADD CONSTRAINT `siswa_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_kelas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_kelas_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wali_kelas`
--
ALTER TABLE `wali_kelas`
  ADD CONSTRAINT `wali_kelas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wali_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wali_kelas_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
