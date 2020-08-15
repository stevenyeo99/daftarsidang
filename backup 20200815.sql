-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2020 at 04:48 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gosidang`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`id`, `name`, `place`, `year`, `student_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Prestasi Test', 'Tempat Test', 2020, 1, NULL, '2020-05-27 13:02:23', '2020-05-27 13:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`id`, `name`, `type`, `file_name`, `student_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, '1831074-IJAZAHSMA-1590584291-1.jfif', 2, '1831074-IJAZAHSMA-1590584291-1.jfif', 1, NULL, '2020-05-27 12:58:11', '2020-05-27 12:58:11'),
(4, '1831074-KK-1590584293-1.jfif', 1, '1831074-KK-1590584293-1.jfif', 1, NULL, '2020-05-27 12:58:13', '2020-05-27 12:58:13'),
(5, '1831074-KTP-1590584295-2.jfif', 0, '1831074-KTP-1590584295-2.jfif', 1, NULL, '2020-05-27 12:58:15', '2020-05-27 12:58:15'),
(6, '1831074-AK-1590584558-2.jfif', 4, '1831074-AK-1590584558-2.jfif', 1, NULL, '2020-05-27 13:02:38', '2020-05-27 13:02:38'),
(7, '1831074-IJAZAHS1-1594221085-favicon.png', 3, '1831074-IJAZAHS1-1594221085-favicon.png', 1, NULL, '2020-07-08 15:11:25', '2020-07-08 15:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `berita_acara_note_revisi`
--

CREATE TABLE `berita_acara_note_revisi` (
  `id` int(10) UNSIGNED NOT NULL,
  `berita_acara_participant_id` int(10) UNSIGNED NOT NULL,
  `note_revisi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `berita_acara_participant`
--

CREATE TABLE `berita_acara_participant` (
  `id` int(10) UNSIGNED NOT NULL,
  `berita_acara_report_id` int(10) UNSIGNED NOT NULL,
  `participant_id` int(10) UNSIGNED NOT NULL,
  `participant_type` int(11) NOT NULL,
  `have_revision` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `berita_acara_report`
--

CREATE TABLE `berita_acara_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `penjadwalan_sidang_id` int(10) UNSIGNED NOT NULL,
  `nilai_ip` decimal(5,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `permission_by` int(11) DEFAULT NULL,
  `permission_at` datetime DEFAULT NULL,
  `scheduled_at` datetime DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `pembimbing_submit_at` datetime DEFAULT NULL,
  `penguji_submit_at` datetime DEFAULT NULL,
  `score_submit_at` datetime DEFAULT NULL,
  `scored_by` int(11) DEFAULT NULL,
  `penguji_user_id` int(10) UNSIGNED DEFAULT NULL,
  `ketua_penguji_user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nilai_score` decimal(5,2) DEFAULT NULL,
  `nilai_index` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `name`, `place`, `year`, `student_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Sertifikasi Test', 'Test Tempat', 2020, 1, NULL, '2020-05-27 13:02:04', '2020-05-27 13:02:04');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `field`, `address`, `phone_number`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1', '1', '1', '082169652699', NULL, '2020-05-27 12:51:12', '2020-05-27 12:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`id`, `name`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Ilmu Komputer', 1, NULL, '2018-06-06 15:27:16', '2018-06-06 15:27:16'),
(2, 'Ekonomi', 1, NULL, '2018-06-06 15:27:55', '2018-06-06 15:27:55'),
(3, 'Teknik Sipil dan Perencanaan', 1, NULL, '2018-06-24 14:16:10', '2018-06-24 14:16:10'),
(4, 'Teknologi Industri', 1, NULL, '2018-06-24 14:16:23', '2018-06-24 14:16:23'),
(5, 'Hukum', 1, NULL, '2018-06-24 14:16:55', '2018-06-24 14:16:55'),
(6, 'Pendidikan', 1, NULL, '2018-06-24 14:17:14', '2018-06-24 14:17:14');

-- --------------------------------------------------------

--
-- Table structure for table `finance_user`
--

CREATE TABLE `finance_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finance_user`
--

INSERT INTO `finance_user` (`id`, `username`, `email`, `is_admin`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'TestFinance', 'ryokusnadi@yahoo.com', 1, '$2y$10$Tjgrgdsj63lnUy2x4Ml/vOIuBW7pPMPfazGjbOhN2QPnc9ZuQx57W', 'L72e96Vu1HfEMHkgZa7lKKM19fzKDiZE2jwSvOR5kx9x97tm1wt15JdASBu7', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hardcover_mahasiswa`
--

CREATE TABLE `hardcover_mahasiswa` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `nama_mahasiswa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `npm` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pembimbing` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_submit` datetime DEFAULT NULL,
  `tanggal_validasi` datetime DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hardcover_mahasiswa`
--

INSERT INTO `hardcover_mahasiswa` (`id`, `type`, `nama_mahasiswa`, `npm`, `prodi`, `nama_pembimbing`, `tanggal_submit`, `tanggal_validasi`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Robby Kurniawan', '16124404', '', 'Agustinus Setyawan, ST., MM ', '2018-09-09 19:10:18', '2018-11-14 18:42:42', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(2, 2, 'Mehdar Badrus Zaman', '16124413', '', 'Lily Sudhartio, Dr., M.Sc. ', '2018-09-11 23:37:51', '2018-11-14 19:05:08', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(3, 2, 'Janssen Copernicus Firstman', '16124424', '', 'Teddy Jurnali, Dr., Teddy Jurnali, Dr.', '2018-09-11 20:30:33', '2018-11-14 19:10:49', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(4, 2, 'Silvy Ekaputri', '16124408', '', 'Mardianto, SE., MM , Mardianto, SE., MM ', '2018-09-12 20:51:34', '2018-11-15 13:04:27', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(5, 2, 'Nafisatul Hasanah', '15104422', '', 'Hepy Hefri Ariyanto, Dr.', '2018-09-12 06:53:55', '2018-11-15 13:34:48', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(6, 2, 'Yosmiyanti', '16124411', '', 'Natalis Christian, SE., MM ', '2018-09-11 20:36:48', '2018-11-15 13:43:22', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(7, 2, 'Riesnawati', '15104426', '', 'Hepy Hefri Ariyanto, Dr.', '2018-09-12 14:53:39', '2018-11-15 15:05:31', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(8, 2, 'Meily Juliani', '16124410', '', 'Mardianto, SE., MM ', '2018-09-12 19:10:38', '2018-11-15 15:29:26', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(9, 2, 'Maria Ulva', '15104411', '', 'Hepy Hefri Ariyanto, Dr.', '2018-09-12 14:30:17', '2018-11-15 15:49:38', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(10, 2, 'Muhammad Jufri', '16124432', '', 'Hepy Hefri Ariyanto, Dr.', '2018-09-11 18:57:24', '2018-11-15 16:43:41', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(11, 2, 'Johan', '15104404', '', 'Hepy Hefri Ariyanto, Dr.', '2018-09-15 14:04:12', '2018-11-15 17:46:59', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(12, 2, 'Putri Septiani', '16124423', '', 'Natalis Christian, SE., MM ', '2018-10-03 16:11:40', '2018-11-15 20:02:51', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(13, 2, 'Androni Susanto', '16124414', '', 'Natalis Christian, SE., MM ', '2018-09-09 20:47:12', '2018-11-16 13:46:08', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(14, 2, 'Yuliana', '16124415', '', 'Agustinus Setyawan, ST., MM ', '2018-10-13 11:10:08', '2018-11-16 14:20:05', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(15, 2, 'Leini', '16124422', '', 'Teddy Jurnali, Dr.', '2018-09-14 07:48:51', '2018-11-16 14:55:26', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(16, 2, 'Antoni Wijaya', '16124402', '', 'Teddy Jurnali, Dr.', '2018-09-09 20:07:12', '2018-11-16 15:27:14', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(17, 2, 'Fitri Janti Katili', '15944028', '', 'Hepy Hefri Ariyanto, Dr.', '2018-09-15 10:39:54', '2018-11-16 16:16:24', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(18, 2, 'Efti Novita Sari', '15104423', '', 'Hepy Hefri Ariyanto, Dr.', '2018-09-13 13:50:17', '2018-11-16 16:56:56', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(19, 2, 'Juana Sihite', '16114427', 'Teknik Sipil', 'Muhammad Donal Mon, SE., MM ', '2018-09-20 22:45:41', '2018-11-16 18:10:16', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(20, 2, 'Dame Afrina Sihombing', '15104424', '', 'Hepy Hefri Ariyanto, Dr.', '2018-09-12 19:01:40', '2018-11-16 19:45:44', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(21, 2, 'Mulyadi', '16105217', '', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', '2018-09-10 00:00:00', '2018-10-04 19:33:49', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(22, 2, 'Agus Haryono', '16105218', '', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', '2018-09-29 15:56:30', '2018-10-15 16:56:32', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(23, 2, 'Beri Yandie', '16115202', 'Teknik Sipil', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', '2018-09-29 16:01:50', '2018-10-15 17:38:53', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(24, 2, 'Lie Ling Als Aiereen Lee', '16105204', '', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', '2018-09-13 07:46:41', '2018-10-15 19:49:57', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(25, 2, 'Desva Eka Saputra', '16105227', '', 'Rufinus Hotmaulana Hutauruk, Dr, SH., MM., MH ', '2018-09-29 07:52:58', '2018-10-15 20:43:10', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(26, 2, 'Noverina Syukura Linanda', '16105219', '', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', '2018-09-10 00:00:00', '2019-04-12 17:57:55', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(27, 2, 'Lily Lee', '16105209', '', 'Dr. Hj. Elza Syarief, SH, MH', '2018-12-01 00:00:00', '2019-04-15 15:27:52', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(28, 2, 'Winda Fitri', '16105233', '', 'Elza Syarief, Dr, SH., MH , Yudi Priyo Amboro, Dr., S.H., M.Hum ', '2018-12-01 00:00:00', '2019-04-15 17:39:36', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(29, 2, 'Shenti Agustini', '16105216', '', 'Elza Syarief, Dr, SH., MH , Yudi Priyo Amboro, Dr., S.H., M.Hum ', '2018-12-01 00:00:00', '2019-04-15 19:51:23', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(30, 2, 'Andika', '16124403', '', 'Mardianto, SE., MM , Ferdinand Nainggolan, Dr., MBA. ', '2018-09-12 20:32:10', '2019-01-16 08:54:22', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(31, 2, 'Desnal', '15944012', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2018-09-11 13:45:03', '2019-01-16 09:48:35', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(32, 2, 'Dewi Sartika', '15104409', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2018-09-15 10:39:54', '2019-01-16 10:05:45', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(33, 2, 'Ardiansyah', '16124421', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2019-01-16 10:49:05', '2019-01-16 11:01:56', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(34, 2, 'Agus Susanto', '16124405', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2019-01-17 06:41:03', '2019-01-17 06:49:27', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(35, 2, 'Antonius Johandi', '16124412', '', 'Natalis Christian, SE., MM , Ferdinand Nainggolan, Dr., MBA. ', '2018-09-13 18:47:22', '2019-01-17 07:58:05', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(36, 2, 'Delwin', '16114415', 'Teknik Sipil', 'Lily Sudhartio, Dr., M.Sc. , Ferdinand Nainggolan, Dr., MBA. ', '2019-01-17 08:28:46', '2019-01-17 08:37:44', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(37, 2, 'Damuzar', '16124420', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2019-01-18 10:25:04', '2019-01-18 10:43:36', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(38, 2, 'Addo Anextio', '16114422', 'Teknik Sipil', 'Lily Purwianty, S.E., M.M , Ferdinand Nainggolan, Dr., MBA. ', '2018-09-27 20:59:45', '2019-01-18 14:36:06', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(39, 2, 'Edward Prasetya', '16114410', 'Teknik Sipil', 'Agustinus Setyawan, ST., MM , Ferdinand Nainggolan, Dr., MBA. ', '2018-09-15 10:39:54', '2019-01-18 15:13:44', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(40, 2, 'Alden Nelson', '17134437', '', 'Agustinus Setyawan, ST., MM , Ferdinand Nainggolan, Dr., MBA. ', '2019-03-26 12:55:47', '2019-03-26 20:31:28', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(41, 2, 'Yeli', '17134441', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2019-03-26 04:45:02', '2019-03-28 13:34:14', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(42, 2, 'Erilia Kesumahati', '17134442', '', 'Teddy Jurnali, Dr.', '2019-03-26 10:10:49', '2019-03-28 17:48:55', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(43, 2, 'Immanuel Zai', '17134434', '', 'Hepy Hefri Ariyanto, Dr., Hepy Hefri Ariyanto, Dr.', '2019-03-25 08:40:40', '2019-03-29 19:22:55', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(44, 2, 'Eli Jayanti', '16124425', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2019-01-31 00:00:00', '2019-04-25 18:46:40', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(45, 2, 'Listia Nurjanah', '15104421', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2019-04-25 19:06:10', '2019-05-10 11:30:24', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(46, 2, 'Heny Reseki Hutapea', '15104407', '', 'Evi Silvana Muhsinati, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2019-01-31 00:00:00', '2019-05-14 11:58:44', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(47, 2, 'Lian Tamara', '15104406', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', '2019-01-31 00:00:00', '2019-05-14 13:12:55', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(48, 2, 'Liendriani', '17134482', '', 'Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-09-30 13:58:26', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(49, 2, 'Maryana Yunani, S.Pd', '17134476', '', 'Tri suhartati, Dr., S.Pd., M.Pd , Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-10-02 19:41:41', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(50, 2, 'Gusmeri Sari', '17134472', '', 'Tri suhartati, Dr., S.Pd., M.Pd , Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-10-02 20:39:18', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(51, 2, 'Manahan Manurung', '17134436', '', 'Agustinus Setyawan, ST., MM , Ferdinand Nainggolan, Dr., MBA. ', NULL, '2019-11-12 16:23:22', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(52, 2, 'Wendy Calvindo', '17134483', '', 'Lily Purwianty, S.E., M.M , Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 16:24:28', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(53, 2, 'Raja Desi Aprianti', '17134469', '', 'Hepy Hefri Ariyanto, Dr., Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 16:25:26', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(54, 2, 'Salawati, S.Pd', '17134452', '', 'Tri suhartati, Dr., S.Pd., M.Pd , Ferdinand Nainggolan, Dr., MBA. ', NULL, '2019-11-12 16:30:02', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(55, 2, 'Enny', '17134445', '', 'Natalis Christian, SE., MM ', NULL, '2019-11-12 16:39:19', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(56, 2, 'Aris Djafril', '17134461', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 16:40:49', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(57, 2, 'Syaiful Anwar', '17134474', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:16:31', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(58, 2, 'Ermala Meilina', '17134402', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:17:22', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(59, 2, 'Yulismar', '17134424', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:19:18', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(60, 2, 'Hijah Zuliar', '17134409', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:20:09', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(61, 2, 'Fitriandriani', '17134425', '', 'Tri suhartati, Dr., S.Pd., M.Pd , Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:20:58', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(62, 2, 'Perwanto', '17134458', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:21:43', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(63, 2, 'Suyono', '17134413', '', 'Muhd. Dali, Dr., Muhd. Dali, Dr.', NULL, '2019-11-12 18:22:30', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(64, 2, 'Rudy Hartono', '17134463', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:23:12', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(65, 2, 'Rika Rusnita', '17134464', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:23:56', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(66, 2, 'Arina Juwita', '17134428', '', 'Teddy Jurnali, Dr., Teddy Jurnali, Dr.', NULL, '2019-11-12 18:25:05', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(67, 2, 'Paramita Rizki Harahap', '17134480', '', 'Lili Purwianti, SE., MM , Lili Purwianti, SE., MM ', NULL, '2019-11-12 18:26:41', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(68, 2, 'Tiara Mentari Ulfa', '17134439', '', 'Lili Purwianti, SE., MM , Lili Purwianti, SE., MM ', NULL, '2019-11-12 18:29:22', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(69, 2, 'Shinta Helen Angela Hutapea', '15944018', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:30:13', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(70, 2, 'Afridawati', '17134466', '', 'Hepy Hefri Ariyanto, Dr., Ferdinand Nainggolan, Dr., MBA. ', NULL, '2019-11-12 18:31:50', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(71, 2, 'Sri Tutini', '17134465', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:32:35', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(72, 2, 'Sunardi', '17134414', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:33:19', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(73, 2, 'Ermayati', '17134401', '', 'Hepy Hefri Ariyanto, Dr., Muhd. Dali, Dr.', NULL, '2019-11-12 18:34:05', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(74, 2, 'Martelia Puspa', '17134411', '', 'Muhd. Dali, Dr.', NULL, '2019-11-12 18:35:51', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(75, 2, 'Zulhajidan', '17134417', '', 'Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-11-12 18:36:36', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(76, 2, 'Summy Dinur Aisyah', '17134467', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:37:27', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(77, 2, 'Daman Huri', '17134422', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:39:19', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(78, 2, 'Riniatun', '17134420', '', 'Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-11-12 18:40:04', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(79, 2, 'Darsudi', '17134462', '', 'Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-11-12 18:40:47', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(80, 2, 'Ulfah Ismiati', '17134471', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:41:51', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(81, 2, 'Zulfahri', '17134459', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 18:42:38', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(82, 2, 'Maryanto, S.Pd', '17134447', '', 'Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-11-12 18:51:12', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(83, 2, 'Antony Sunarko', '17134478', '', 'Lili Purwianti, SE., MM ', NULL, '2019-11-12 18:52:02', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(84, 2, 'Ahmad Zaini', '17134455', '', 'Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-11-12 18:59:32', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(85, 2, 'Yayuk Sri Mulyani Rahayu', '17134405', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-12 19:43:06', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(86, 2, 'Armaisal', '17134406', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-13 13:38:46', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(87, 2, 'Yunita Kirnawati', '17134408', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-13 14:17:25', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(88, 2, 'Wiwik Sofyanti', '17134404', '', 'Tri suhartati, Dr., S.Pd., M.Pd ', NULL, '2019-11-13 15:09:14', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(89, 2, 'Joko Rianto, S.E.', '17134419', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-13 15:42:26', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(90, 2, 'Nurul Yusra Tanjung', '17134410', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-13 16:06:14', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(91, 2, 'Alice Erni Husein', '17134432', '', 'Hepy Hefri Ariyanto, Dr.', NULL, '2019-11-14 15:07:23', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(92, 2, 'Abdul rahman', '17134444', '', 'Evi Silvana Muhsinati, Dr.', NULL, '2019-11-14 15:09:22', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(93, 2, 'Masaju Heningsari', '17134451', '', 'Tri Suhartati, Dr., M.Pd', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(94, 2, 'Zurnelis', '17134457', '', 'Tri Suhartati, Dr., M.Pd', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(95, 2, 'Hadjad Widogdo', '16114407', 'Teknik Sipil', 'Agustinus Setyawan, ST, MM', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(96, 2, 'Catur Jati Atmanto', '17134489', '', 'Hepy H. Arianto, Dr. ', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(97, 2, 'Syarifah Asyura', '17134403', '', 'Tri Suhartati, Dr., M.Pd', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(98, 2, 'Zulvianingsih Z', '17134421', '', 'Hepy H. Arianto, Dr. , 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(99, 2, 'Maulana Malik Ibrahim', '17134448', '', 'Hepy H. Arianto, Dr. , 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(100, 2, 'Muhammad Iqbal', '17134449', '', 'Hepy H. Arianto, Dr. , 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(101, 2, 'Tri Elis Setiyowati', '17134418', '', 'Hepy H. Arianto, Dr. , 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(102, 2, 'Linda Puspadewi', '17134450', '', 'Tri Suhartati, Dr., M.Pd, 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(103, 2, 'Jefrizal', '17134490', '', 'Hepy H. Arianto, Dr. , 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(104, 2, 'Supeni Nugrahawati', '17134415', '', 'Tri Suhartati, Dr., M.Pd, 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(105, 2, 'Suhono', '17134407', '', 'Muhammad Dali, Dr., MM, 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(106, 2, 'Ernita Tambunan', '17134456', '', 'Tri Suhartati, Dr., M.Pd, 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(107, 2, 'Erny Yusnita\r\n\r\n', '17134423', '', 'Tri Suhartati, Dr., M.Pd\r\n', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(108, 2, 'Aldian Sanjaya', '17134479', '', 'Hepy H. Arianto, Dr. , 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(109, 2, 'Novianti', '17134433', '', 'Lily Purwianti, SE, MM, 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(110, 2, 'Sulasmi', '17134412', '', 'Muhammad Dali, Dr., MM, 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(111, 2, 'Herwandi', '17134416', '', 'Hepy H. Arianto, Dr. , 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(112, 2, 'Faby Izaura Yulvahera Barus', '17134435', '', 'Lily Purwianti, SE, MM, 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(113, 2, 'Veronica Jenita Ratu Rosari', '17134481', '', 'Agustinus Setyawan, ST, MM, 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(114, 2, 'Frenky', '17134446', '', 'Hepy H. Arianto, Dr. , 0', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(115, 2, 'Suci Frawitta', '16115210', 'Teknik Sipil', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', '2019-03-22 09:51:24', '2019-03-22 17:31:30', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(116, 2, 'Robert Garry Hawidi', '16115201', 'Teknik Sipil', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', '2019-03-22 10:10:28', '2019-03-22 17:38:23', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(117, 2, 'Bernard', '16105205', '', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', '2019-03-27 12:38:41', '2019-03-28 17:52:08', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(118, 2, 'Wasrizal', '16115222', 'Teknik Sipil', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Lu Sudirman, Dr., SH., MM., M.Hum. ', '2019-03-22 10:47:51', '2019-03-29 15:03:43', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(119, 2, 'Alfi Ramadania', '16115207', 'Teknik Sipil', 'Rufinus Hotmaulana Hutauruk, Dr, SH., MM., MH , Yudi Priyo Amboro, Dr., S.H., M.Hum ', '2019-03-22 10:49:15', '2019-03-29 17:29:29', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(120, 2, 'Maryeni Defrita', '16115214', 'Teknik Sipil', 'Ampuan Situmeang, SH., MH , Lu Sudirman, Dr., SH., MM., M.Hum. ', '2019-03-28 06:26:23', '2019-03-29 17:58:03', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(121, 2, 'Bambang Sulistyono', '14852036', '', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Asmin Patros, S.H., M.Hum ', '2019-03-22 11:05:58', '2019-03-29 18:55:04', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(122, 2, 'Hijratul Pahsyah', '17125213', '', 'Triana Dewi Seroja, Dr., SH, M.Hum\r\n, Wagiman Martedjo, S.Fil, SH, MH\r\n', '2019-09-25 04:41:22', NULL, 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(123, 2, 'Rahmi Ayunda', '16105231', '', 'Elza Syarief, Dr,. SH, MH, Yudhi Priyo Amboro, Dr., SH, M.Hum', '2019-09-27 00:00:00', '2019-09-27 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(124, 2, 'Edi Syahputra\r\n', '17125214', '', 'Triana Dewi Seroja, Dr., SH, M.Hum\r\n, Lu Sudirman, Dr., SH, MM, M.Hum\r\n', '2019-09-30 00:00:00', '2019-09-30 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(125, 2, 'Sugito', '17125212', '', 'Triana Dewi Seroja, Dr., SH, M.Hum, Lu Sudirman, Dr., SH, MM, M.Hum', '2019-09-27 00:00:00', '2019-09-28 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(126, 2, 'Brenda Christie', '16115211', 'Teknik Sipil', 'Rina Shahriyani Shahrullah, SH, MCI, Mp.D, Wagiman Martedjo, S.Fil, SH, MH', '2019-09-27 00:00:00', '2019-09-29 00:00:00', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(127, 2, 'Hendra Saputra', '17125217', '', 'Ampuan Situmeang, SH., MH , Rina Shahriyani Shahrullah, SH., M.CL., Ph.D ', NULL, '2019-11-12 16:26:27', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(128, 2, 'Awang Sasongko', '16105214', '', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', NULL, '2019-11-12 16:28:26', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(129, 2, 'Firmansyah', '17125211', '', 'Ampuan Situmeang, SH., MH , Winsherly Tan, SH., MH ', NULL, '2019-11-12 16:29:13', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(130, 2, 'Aprillia Crystina', '16115219', 'Teknik Sipil', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', NULL, '2019-11-12 18:28:18', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(131, 2, 'Fedryk Soaloon Harahap', '17125204', '', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', NULL, '2019-11-12 18:31:04', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(132, 2, 'Su Cen', '16115221', 'Teknik Sipil', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', NULL, '2019-11-12 18:53:06', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(133, 2, 'Suherman', '16115204', 'Teknik Sipil', 'Rina Shahriyani Shahrullah, SH., M.CL., Ph.D , Wagiman, S.Fil.,SH.,MH ', NULL, '2019-11-12 18:53:52', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(134, 2, 'Edwar Kelvin', '16105211', '', 'Junimart Girsang, Dr, SH., MBA., MH , Yudi Priyo Amboro, Dr., S.H., M.Hum ', NULL, '2019-11-13 16:52:44', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49'),
(135, 2, 'Beny Kaissar Simanjuntak', '16105235', '', 'Junimart Girsang, Dr, SH., MBA., MH , Yudi Priyo Amboro, Dr., S.H., M.Hum ', NULL, '2019-11-14 16:34:33', 'VALIDATED', '2020-05-27 13:51:47', '2020-05-28 14:42:49');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_04_04_191045_create_roles_table', 1),
(4, '2018_04_04_191210_create_user_roles_table', 1),
(5, '2018_04_04_192933_create_faculties_table', 1),
(6, '2018_04_05_170137_create_study_programs_table', 1),
(7, '2018_04_05_170459_create_semesters_table', 1),
(8, '2018_04_05_171104_create_companies_table', 1),
(9, '2018_04_05_171316_create_students_table', 1),
(10, '2018_04_05_172750_create_achievements_table', 1),
(11, '2018_04_05_173235_create_certificates_table', 1),
(12, '2018_04_05_173505_create_attachments_table', 1),
(13, '2018_04_05_173658_create_parents_table', 1),
(14, '2018_04_05_173742_create_requests_table', 1),
(15, '2018_08_08_094850_alter_students_table_change_phone_number_length', 1),
(16, '2018_08_12_081308_create_session_statuses_table', 1),
(17, '2019_11_26_234131_create_prodi_users_table', 1),
(18, '2019_12_06_225518_create_table_prodi_user_existing_assignment', 1),
(19, '2019_12_06_225519_create_table_ruangan_sidang', 1),
(20, '2019_12_06_225520_create_penjadwalan_sidangs_table', 1),
(21, '2019_12_06_231039_alter_request_table_change_mentor_name_to_mentor_id', 1),
(22, '2019_12_07_131957_alter_request_add_column_tanggal_validasi_prodi_and_prodi_scheduled', 1),
(23, '2019_12_07_151323_alter_penjadwalan_tanggal_sidang', 1),
(24, '2019_12_10_193144_alter_dosen_pembimbing_backup', 1),
(25, '2019_12_15_180556_alter_penjadwalan_sidang_add_ruangan_id', 1),
(26, '2019_12_16_195118_alter_table_prodi_user_add_initial_name', 1),
(27, '2019_12_19_145150_alter_table_penjadwalan_sidang_add_tanggal_revisi_sidang', 1),
(28, '2019_12_19_154116_alter_table_penjadwalan_add_column_penjadwalan_expired', 1),
(29, '2019_12_21_013051_create_table_reschedule_history', 1),
(30, '2019_12_24_170325_alter_request_add_status_sidang', 1),
(31, '2019_12_26_203755_create_table_berita_acara', 1),
(32, '2019_12_26_222634_berita_acara_participant', 1),
(33, '2019_12_26_223240_create_table_berita_acara_note_revisi', 1),
(34, '2020_01_07_223005_alter_table_request_kelulusan', 1),
(35, '2020_01_11_183531_alter_berita_acara_report_add_columns', 1),
(36, '2020_01_11_233236_alter_acara_master_decimal_type', 1),
(37, '2020_01_15_135544_create_table_finance', 1),
(38, '2020_01_15_140655_create_table_hardcover_attachment', 1),
(39, '2020_01_15_140718_create_table_request_attachment', 1),
(40, '2020_01_15_141735_alter_table_request_review_by_finance_id', 1),
(41, '2020_02_08_151608_alter_request_turnitin_percent', 1),
(42, '2020_02_16_012954_alter_request_add_total_sa_point', 1),
(43, '2020_02_17_214133_alter_request_add_status_keuangan', 1),
(44, '2020_02_23_153643_create_prodi_user_table', 1),
(45, '2020_02_23_154003_create_prodi_user_program_study', 1);

-- --------------------------------------------------------

--
-- Table structure for table `old_penjadwalan_sidang_history`
--

CREATE TABLE `old_penjadwalan_sidang_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `penjadwalan_sidang_id` int(10) UNSIGNED NOT NULL,
  `dosen_pembimbing_or_backup_id` int(10) UNSIGNED NOT NULL,
  `dosen_penguji_id` int(10) UNSIGNED NOT NULL,
  `ruangan_sidang_id` int(11) NOT NULL,
  `tanggal_waktu_sidang` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`id`, `name`, `type`, `student_id`, `company_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Hadi', 0, 1, 1, NULL, '2020-05-27 12:51:12', '2020-05-27 12:51:12'),
(2, 'Rina', 1, 1, NULL, NULL, '2020-05-27 12:51:12', '2020-05-27 12:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penjadwalan_sidang`
--

CREATE TABLE `penjadwalan_sidang` (
  `id` int(10) UNSIGNED NOT NULL,
  `tanggal_sidang` datetime DEFAULT NULL,
  `dosen_pembimbing_id` int(10) UNSIGNED NOT NULL,
  `dosen_penguji_id` int(10) UNSIGNED DEFAULT NULL,
  `penjadwalan_by` int(11) NOT NULL,
  `ruangan_sidang_id` int(10) UNSIGNED DEFAULT NULL,
  `status_pengiriman` int(11) DEFAULT NULL,
  `penempatan_by` int(11) DEFAULT NULL,
  `sidang_type` int(11) NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `dosen_pembimbing_backup` int(11) DEFAULT NULL,
  `tanggal_revisi_sidang` datetime DEFAULT NULL,
  `tanggal_expired_revisi_sidang` datetime DEFAULT NULL,
  `status_penjadwalan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_penjadwalan_expired` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prodi_user`
--

CREATE TABLE `prodi_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `study_programs_id` int(10) UNSIGNED NOT NULL,
  `initial_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodi_user`
--

INSERT INTO `prodi_user` (`id`, `username`, `email`, `is_admin`, `password`, `remember_token`, `study_programs_id`, `initial_name`, `created_at`, `updated_at`) VALUES
(1, 'TestDosen', 'ryokusnadi@yahoo.com', 1, '$2y$10$nD1Afi0ScjkXWQUahCfiYOHNGFGgt2LRQhJhALGGLMW5YWBv/PBF2', 'w7Jrqk4XO1wsFFHxwI9gnYa0eZ51G7Xb1QSBhmqLDg8Ja0SZMv2xpLLjY2if', 1, '', '2019-11-27 07:48:15', NULL),
(2, 'DosenNonadmin', 'ryokusnadi@gmail.com', 0, '$2y$10$nD1Afi0ScjkXWQUahCfiYOHNGFGgt2LRQhJhALGGLMW5YWBv/PBF2', 'u6GkIindvXxqb05nwA54fUYkQHu5n8inhwB7dar4NrjJg1C0ltNMsEcbpLFj', 1, 'DosenTestNA', '2020-05-27 13:28:59', NULL),
(3, 'DosenTest2', 'rkjournies@gmail.com', 0, '$2y$10$nD1Afi0ScjkXWQUahCfiYOHNGFGgt2LRQhJhALGGLMW5YWBv/PBF2', 'YyWqU22Gi9wrItBBol1Ry2LCe0l4flOOG5ZHnMFAg2YGnDfVmB35nUSsbonq', 1, 'DosenTest2', '2020-05-27 13:37:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prodi_user_assignment`
--

CREATE TABLE `prodi_user_assignment` (
  `id` int(10) UNSIGNED NOT NULL,
  `prodi_user_id` int(10) UNSIGNED NOT NULL,
  `study_program_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prodi_user_assignment`
--

INSERT INTO `prodi_user_assignment` (`id`, `prodi_user_id`, `study_program_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2020-05-27 13:28:59', NULL),
(2, 3, 1, '2020-05-27 13:37:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `session` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `repeat_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mentor_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `mentor_id` int(10) UNSIGNED DEFAULT NULL,
  `tanggal_validasi_prodi` date DEFAULT NULL,
  `scheduled_status` int(11) DEFAULT NULL,
  `status_sidang` int(11) DEFAULT NULL,
  `status_lulus` int(11) DEFAULT NULL,
  `review_finance_user_id` int(10) UNSIGNED DEFAULT NULL,
  `turnitin_percentage` int(11) DEFAULT NULL,
  `sa_point` int(11) DEFAULT NULL,
  `status_keuangan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `session`, `type`, `title`, `status`, `start_date`, `end_date`, `expiry_date`, `repeat_reason`, `reject_reason`, `mentor_name`, `student_id`, `deleted_at`, `created_at`, `updated_at`, `mentor_id`, `tanggal_validasi_prodi`, `scheduled_status`, `status_sidang`, `status_lulus`, `review_finance_user_id`, `turnitin_percentage`, `sa_point`, `status_keuangan`) VALUES
(17, 1, 0, 'Test Skripsi', 1, NULL, NULL, '2020-08-12 00:00:00', NULL, NULL, 'DosenNonadmin', 1, NULL, '2020-08-05 14:31:46', '2020-08-05 14:32:10', 1, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL),
(18, 1, 1, 'Cara Menulis Buku Menggunakan Indera Keenam', 7, '2020-08-01 00:00:00', '2020-08-04 00:00:00', '2020-08-12 00:00:00', NULL, NULL, 'DosenNonadmin', 1, NULL, '2020-08-05 14:33:10', '2020-08-05 14:37:56', 1, NULL, NULL, 0, NULL, 1, NULL, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `request_attachment`
--

CREATE TABLE `request_attachment` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL,
  `attachment_type` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_on` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_attachment`
--

INSERT INTO `request_attachment` (`id`, `request_id`, `attachment_type`, `file_name`, `file_display_name`, `file_path`, `uploaded_on`, `created_at`, `updated_at`) VALUES
(1, 17, 1, '1831074-KP-1596637906-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Lembar Persetujuan/KP/1831074-Ryo Kusnadi/17/1831074-KP-1596637906-ktm.PNG', '2020-08-05 21:31:46', '2020-08-05 14:31:46', NULL),
(2, 17, 0, '1831074-KP-1596637906-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Kartu Bimbingan/KP/1831074-Ryo Kusnadi/17/1831074-KP-1596637906-ktm.PNG', '2020-08-05 21:31:46', '2020-08-05 14:31:46', NULL),
(3, 17, 6, '1831074-KP-1596637906-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Lembar Turnitin/KP/1831074-Ryo Kusnadi/17/1831074-KP-1596637906-ktm.PNG', '2020-08-05 21:31:46', '2020-08-05 14:31:46', NULL),
(4, 17, 7, '1831074-KP-1596637915-new_resume_001.docx', 'new_resume_001.docx', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Laporan KP/KP/1831074-Ryo Kusnadi/17/1831074-KP-1596637915-new_resume_001.docx', '2020-08-05 21:31:55', '2020-08-05 14:31:46', '2020-08-05 14:31:55'),
(5, 18, 1, '1831074-SKRIPSI-1596637990-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Lembar Persetujuan/SKRIPSI/1831074-Ryo Kusnadi/18/1831074-SKRIPSI-1596637990-ktm.PNG', '2020-08-05 21:33:10', '2020-08-05 14:33:10', NULL),
(6, 18, 0, '1831074-SKRIPSI-1596637990-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Kartu Bimbingan/SKRIPSI/1831074-Ryo Kusnadi/18/1831074-SKRIPSI-1596637990-ktm.PNG', '2020-08-05 21:33:10', '2020-08-05 14:33:10', NULL),
(7, 18, 6, '1831074-SKRIPSI-1596637990-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Lembar Turnitin/SKRIPSI/1831074-Ryo Kusnadi/18/1831074-SKRIPSI-1596637990-ktm.PNG', '2020-08-05 21:33:10', '2020-08-05 14:33:10', NULL),
(8, 18, 3, '1831074-SKRIPSI-1596637990-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Anti Plagiat/SKRIPSI/1831074-Ryo Kusnadi/18/1831074-SKRIPSI-1596637990-ktm.PNG', '2020-08-05 21:33:10', '2020-08-05 14:33:10', NULL),
(9, 18, 5, '1831074-SKRIPSI-1596637990-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Foto Meteor/SKRIPSI/1831074-Ryo Kusnadi/18/1831074-SKRIPSI-1596637990-ktm.PNG', '2020-08-05 21:33:10', '2020-08-05 14:33:10', NULL),
(10, 18, 2, '1831074-SKRIPSI-1596637990-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Toeic Official/SKRIPSI/1831074-Ryo Kusnadi/18/1831074-SKRIPSI-1596637990-ktm.PNG', '2020-08-05 21:33:10', '2020-08-05 14:33:10', NULL),
(11, 18, 4, '1831074-SKRIPSI-1596637990-ktm.PNG', 'ktm.PNG', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Abstract UCLC/SKRIPSI/1831074-Ryo Kusnadi/18/1831074-SKRIPSI-1596637990-ktm.PNG', '2020-08-05 21:33:10', '2020-08-05 14:33:10', NULL),
(12, 18, 8, '1831074-SKRIPSI-1596637990-4 (2).doc', '4 (2).doc', 'C:\\xampp\\htdocs\\DaftarSidang\\public/Laporan Skripsi/SKRIPSI/1831074-Ryo Kusnadi/18/1831074-SKRIPSI-1596637990-4 (2).doc', '2020-08-05 21:33:10', '2020-08-05 14:33:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `code`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'SADM', 'Super Admin', NULL, '2018-06-06 12:21:56', '2018-06-06 12:21:56'),
(2, 'ADM', 'Admin', NULL, '2018-06-06 12:21:56', '2018-06-06 12:21:56'),
(3, 'USR', 'User', NULL, '2018-06-06 12:21:56', '2018-06-06 12:21:56'),
(4, 'MTRUSR', 'Meteor User', NULL, '2018-09-04 12:48:07', '2018-09-04 12:48:07');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan_sidang`
--

CREATE TABLE `ruangan_sidang` (
  `id` int(10) UNSIGNED NOT NULL,
  `gedung` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangan_sidang`
--

INSERT INTO `ruangan_sidang` (`id`, `gedung`, `ruangan`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'A', '101', 1, NULL, NULL),
(2, 'A', '201', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int(10) UNSIGNED NOT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `year`, `type`, `text`, `is_active`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '2017/2018', 0, '2017/2018 - Genap', 1, 1, NULL, '2018-06-06 15:26:56', '2020-05-27 13:17:10'),
(2, '2018/2019', 1, '2018/2019 - Ganjil', 1, 1, NULL, '2018-09-09 19:13:43', '2020-05-27 13:14:22'),
(3, '2018/2019', 0, '2018/2019 - Genap', 1, 1, NULL, '2019-03-31 17:33:37', '2019-03-31 17:33:37');

-- --------------------------------------------------------

--
-- Table structure for table `session_statuses`
--

CREATE TABLE `session_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `type` int(11) NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(10) UNSIGNED NOT NULL,
  `npm` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` int(11) NOT NULL,
  `NIK` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `toeic_grade` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_place` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` datetime DEFAULT NULL,
  `religion` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_status` int(11) DEFAULT NULL,
  `toga_size` int(11) DEFAULT NULL,
  `consumption_type` int(11) DEFAULT NULL,
  `existing_degree` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certification_degree` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_filled` tinyint(1) NOT NULL DEFAULT 0,
  `is_profile_accurate` tinyint(1) NOT NULL DEFAULT 1,
  `must_fill_attachment` tinyint(1) NOT NULL DEFAULT 0,
  `semester_id` int(10) UNSIGNED DEFAULT NULL,
  `study_program_id` int(10) UNSIGNED DEFAULT NULL,
  `company_id` int(10) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `npm`, `password`, `name`, `sex`, `NIK`, `toeic_grade`, `birth_place`, `birthdate`, `religion`, `email`, `phone_number`, `address`, `work_status`, `toga_size`, `consumption_type`, `existing_degree`, `certification_degree`, `profile_filled`, `is_profile_accurate`, `must_fill_attachment`, `semester_id`, `study_program_id`, `company_id`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '1831074', '$2y$10$ViUnhRV3yZPcQurFYAD/ieulLuc/VFdtLwmMFqvoIfpEYvkzflhNe', 'Ryo Kusnadi', 0, '1404040212000002', '700', 'Tembilahan', '2000-12-02 00:00:00', 'BUDDHA', 'ryokusnadi@gmail.com', '082169652699', 'Komplek Ruko Baloi Kusuma Blok A No.6', 1, 1, 1, 'Test Gelar', 'Test Sertifikasi', 1, 1, 0, 3, 1, NULL, 'dCC4Mdw42Tx0868qcES0fsMaNs0I1iikb6rrOfe7gVjKNmE2OKlwbJjdWnqc', NULL, '2020-05-27 12:38:17', '2020-08-11 13:04:53');

-- --------------------------------------------------------

--
-- Table structure for table `study_programs`
--

CREATE TABLE `study_programs` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faculty_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `study_programs`
--

INSERT INTO `study_programs` (`id`, `code`, `name`, `faculty_id`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '31', 'Sistem Informasi', 1, 1, NULL, '2018-06-06 15:27:41', '2018-06-06 15:27:41'),
(2, '41', 'Manajemen', 2, 1, NULL, '2018-06-06 15:28:15', '2018-06-06 15:28:15'),
(3, '42', 'Akuntansi', 2, 1, NULL, '2018-06-06 15:28:24', '2018-06-06 15:28:24'),
(4, '44', 'Magister Manajemen', 2, 1, NULL, '2018-06-06 15:28:43', '2018-06-24 14:22:49'),
(5, '11', 'Teknik Sipil', 3, 1, NULL, '2018-06-24 14:19:05', '2018-06-24 14:19:05'),
(6, '21', 'Teknik Elektro', 4, 1, NULL, '2018-06-24 14:19:19', '2018-06-24 14:19:19'),
(7, '51', 'Ilmu Hukum', 5, 1, NULL, '2018-06-24 14:19:38', '2018-06-24 14:19:38'),
(8, '46', 'Pariwisata', 2, 1, NULL, '2018-06-24 14:19:53', '2018-06-24 14:19:53'),
(9, '52', 'Magister Hukum', 5, 1, NULL, '2018-06-24 14:24:12', '2018-06-24 14:24:12'),
(10, '61', 'Pendidikan Bahasa Inggris', 6, 1, NULL, '2018-06-24 14:24:27', '2018-06-24 14:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `study_program_users`
--

CREATE TABLE `study_program_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nip` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` int(11) NOT NULL,
  `gender` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_program_user_roles`
--

CREATE TABLE `study_program_user_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `study_program_user_id` int(10) UNSIGNED NOT NULL,
  `study_program_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@admin.com', '$2y$10$Tjgrgdsj63lnUy2x4Ml/vOIuBW7pPMPfazGjbOhN2QPnc9ZuQx57W', 'N2IAhep9ktuOYh21QQiP9cmhvLbJf2UmQuulZ2kItP5PlwUMwyFMvSKxvBLU', NULL, '2018-06-06 12:21:56', '2019-07-16 18:07:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '2018-06-06 12:21:56', '2018-06-06 12:21:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achievements_student_id_foreign` (`student_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_student_id_foreign` (`student_id`);

--
-- Indexes for table `berita_acara_note_revisi`
--
ALTER TABLE `berita_acara_note_revisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_acara_note_revisi_berita_acara_participant_id_foreign` (`berita_acara_participant_id`);

--
-- Indexes for table `berita_acara_participant`
--
ALTER TABLE `berita_acara_participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_acara_participant_berita_acara_report_id_foreign` (`berita_acara_report_id`),
  ADD KEY `berita_acara_participant_participant_id_foreign` (`participant_id`);

--
-- Indexes for table `berita_acara_report`
--
ALTER TABLE `berita_acara_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_acara_report_request_id_foreign` (`request_id`),
  ADD KEY `berita_acara_report_penjadwalan_sidang_id_foreign` (`penjadwalan_sidang_id`),
  ADD KEY `berita_acara_report_penguji_user_id_foreign` (`penguji_user_id`),
  ADD KEY `berita_acara_report_ketua_penguji_user_id_foreign` (`ketua_penguji_user_id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificates_student_id_foreign` (`student_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_name_unique` (`name`),
  ADD UNIQUE KEY `companies_phone_number_unique` (`phone_number`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculties_name_unique` (`name`),
  ADD KEY `faculties_created_by_foreign` (`created_by`);

--
-- Indexes for table `finance_user`
--
ALTER TABLE `finance_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hardcover_mahasiswa`
--
ALTER TABLE `hardcover_mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `old_penjadwalan_sidang_history`
--
ALTER TABLE `old_penjadwalan_sidang_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dospem_id` (`dosen_pembimbing_or_backup_id`),
  ADD KEY `old_penjadwalan_sidang_history_dosen_penguji_id_foreign` (`dosen_penguji_id`),
  ADD KEY `old_penjadwalan_sidang_history_penjadwalan_sidang_id_foreign` (`penjadwalan_sidang_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parents_student_id_foreign` (`student_id`),
  ADD KEY `parents_company_id_foreign` (`company_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `penjadwalan_sidang`
--
ALTER TABLE `penjadwalan_sidang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjadwalan_sidang_dosen_pembimbing_id_foreign` (`dosen_pembimbing_id`),
  ADD KEY `penjadwalan_sidang_dosen_penguji_id_foreign` (`dosen_penguji_id`),
  ADD KEY `penjadwalan_sidang_ruangan_sidang_id_foreign` (`ruangan_sidang_id`),
  ADD KEY `penjadwalan_sidang_request_id_foreign` (`request_id`);

--
-- Indexes for table `prodi_user`
--
ALTER TABLE `prodi_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodi_user_study_programs_id_foreign` (`study_programs_id`);

--
-- Indexes for table `prodi_user_assignment`
--
ALTER TABLE `prodi_user_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodi_user_assignment_prodi_user_id_foreign` (`prodi_user_id`),
  ADD KEY `prodi_user_assignment_study_program_id_foreign` (`study_program_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requests_student_id_foreign` (`student_id`),
  ADD KEY `requests_mentor_id_foreign` (`mentor_id`),
  ADD KEY `requests_review_finance_user_id_foreign` (`review_finance_user_id`);

--
-- Indexes for table `request_attachment`
--
ALTER TABLE `request_attachment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_attachment_request_id_foreign` (`request_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ruangan_sidang`
--
ALTER TABLE `ruangan_sidang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ruangan_sidang_created_by_foreign` (`created_by`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semesters_created_by_foreign` (`created_by`);

--
-- Indexes for table `session_statuses`
--
ALTER TABLE `session_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_statuses_student_id_foreign` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_npm_unique` (`npm`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD UNIQUE KEY `students_phone_number_unique` (`phone_number`),
  ADD KEY `students_semester_id_foreign` (`semester_id`),
  ADD KEY `students_study_program_id_foreign` (`study_program_id`),
  ADD KEY `students_company_id_foreign` (`company_id`);

--
-- Indexes for table `study_programs`
--
ALTER TABLE `study_programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `study_programs_code_unique` (`code`),
  ADD UNIQUE KEY `study_programs_name_unique` (`name`),
  ADD KEY `study_programs_faculty_id_foreign` (`faculty_id`),
  ADD KEY `study_programs_created_by_foreign` (`created_by`);

--
-- Indexes for table `study_program_users`
--
ALTER TABLE `study_program_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `study_program_users_nip_unique` (`nip`),
  ADD UNIQUE KEY `study_program_users_username_unique` (`username`),
  ADD KEY `study_program_users_created_by_foreign` (`created_by`),
  ADD KEY `study_program_users_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `study_program_user_roles`
--
ALTER TABLE `study_program_user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `study_program_user_roles_study_program_user_id_foreign` (`study_program_user_id`),
  ADD KEY `study_program_user_roles_study_program_id_foreign` (`study_program_id`),
  ADD KEY `study_program_user_roles_created_by_foreign` (`created_by`),
  ADD KEY `study_program_user_roles_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_roles_user_id_foreign` (`user_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `berita_acara_note_revisi`
--
ALTER TABLE `berita_acara_note_revisi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `berita_acara_participant`
--
ALTER TABLE `berita_acara_participant`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `berita_acara_report`
--
ALTER TABLE `berita_acara_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `finance_user`
--
ALTER TABLE `finance_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hardcover_mahasiswa`
--
ALTER TABLE `hardcover_mahasiswa`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `old_penjadwalan_sidang_history`
--
ALTER TABLE `old_penjadwalan_sidang_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penjadwalan_sidang`
--
ALTER TABLE `penjadwalan_sidang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prodi_user`
--
ALTER TABLE `prodi_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prodi_user_assignment`
--
ALTER TABLE `prodi_user_assignment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `request_attachment`
--
ALTER TABLE `request_attachment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ruangan_sidang`
--
ALTER TABLE `ruangan_sidang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `session_statuses`
--
ALTER TABLE `session_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `study_programs`
--
ALTER TABLE `study_programs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `study_program_users`
--
ALTER TABLE `study_program_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_program_user_roles`
--
ALTER TABLE `study_program_user_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `berita_acara_note_revisi`
--
ALTER TABLE `berita_acara_note_revisi`
  ADD CONSTRAINT `berita_acara_note_revisi_berita_acara_participant_id_foreign` FOREIGN KEY (`berita_acara_participant_id`) REFERENCES `berita_acara_participant` (`id`);

--
-- Constraints for table `berita_acara_participant`
--
ALTER TABLE `berita_acara_participant`
  ADD CONSTRAINT `berita_acara_participant_berita_acara_report_id_foreign` FOREIGN KEY (`berita_acara_report_id`) REFERENCES `berita_acara_report` (`id`),
  ADD CONSTRAINT `berita_acara_participant_participant_id_foreign` FOREIGN KEY (`participant_id`) REFERENCES `prodi_user` (`id`);

--
-- Constraints for table `berita_acara_report`
--
ALTER TABLE `berita_acara_report`
  ADD CONSTRAINT `berita_acara_report_ketua_penguji_user_id_foreign` FOREIGN KEY (`ketua_penguji_user_id`) REFERENCES `prodi_user` (`id`),
  ADD CONSTRAINT `berita_acara_report_penguji_user_id_foreign` FOREIGN KEY (`penguji_user_id`) REFERENCES `prodi_user` (`id`),
  ADD CONSTRAINT `berita_acara_report_penjadwalan_sidang_id_foreign` FOREIGN KEY (`penjadwalan_sidang_id`) REFERENCES `penjadwalan_sidang` (`id`),
  ADD CONSTRAINT `berita_acara_report_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`);

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `faculties`
--
ALTER TABLE `faculties`
  ADD CONSTRAINT `faculties_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `old_penjadwalan_sidang_history`
--
ALTER TABLE `old_penjadwalan_sidang_history`
  ADD CONSTRAINT `dospem_id` FOREIGN KEY (`dosen_pembimbing_or_backup_id`) REFERENCES `prodi_user_assignment` (`id`),
  ADD CONSTRAINT `old_penjadwalan_sidang_history_dosen_penguji_id_foreign` FOREIGN KEY (`dosen_penguji_id`) REFERENCES `prodi_user_assignment` (`id`),
  ADD CONSTRAINT `old_penjadwalan_sidang_history_penjadwalan_sidang_id_foreign` FOREIGN KEY (`penjadwalan_sidang_id`) REFERENCES `penjadwalan_sidang` (`id`);

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `parents_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `parents_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `penjadwalan_sidang`
--
ALTER TABLE `penjadwalan_sidang`
  ADD CONSTRAINT `penjadwalan_sidang_dosen_pembimbing_id_foreign` FOREIGN KEY (`dosen_pembimbing_id`) REFERENCES `prodi_user_assignment` (`id`),
  ADD CONSTRAINT `penjadwalan_sidang_dosen_penguji_id_foreign` FOREIGN KEY (`dosen_penguji_id`) REFERENCES `prodi_user_assignment` (`id`),
  ADD CONSTRAINT `penjadwalan_sidang_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`),
  ADD CONSTRAINT `penjadwalan_sidang_ruangan_sidang_id_foreign` FOREIGN KEY (`ruangan_sidang_id`) REFERENCES `ruangan_sidang` (`id`);

--
-- Constraints for table `prodi_user`
--
ALTER TABLE `prodi_user`
  ADD CONSTRAINT `prodi_user_study_programs_id_foreign` FOREIGN KEY (`study_programs_id`) REFERENCES `study_programs` (`id`);

--
-- Constraints for table `prodi_user_assignment`
--
ALTER TABLE `prodi_user_assignment`
  ADD CONSTRAINT `prodi_user_assignment_prodi_user_id_foreign` FOREIGN KEY (`prodi_user_id`) REFERENCES `prodi_user` (`id`),
  ADD CONSTRAINT `prodi_user_assignment_study_program_id_foreign` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_mentor_id_foreign` FOREIGN KEY (`mentor_id`) REFERENCES `prodi_user_assignment` (`id`),
  ADD CONSTRAINT `requests_review_finance_user_id_foreign` FOREIGN KEY (`review_finance_user_id`) REFERENCES `finance_user` (`id`),
  ADD CONSTRAINT `requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `request_attachment`
--
ALTER TABLE `request_attachment`
  ADD CONSTRAINT `request_attachment_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`);

--
-- Constraints for table `ruangan_sidang`
--
ALTER TABLE `ruangan_sidang`
  ADD CONSTRAINT `ruangan_sidang_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `semesters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `session_statuses`
--
ALTER TABLE `session_statuses`
  ADD CONSTRAINT `session_statuses_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `students_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`),
  ADD CONSTRAINT `students_study_program_id_foreign` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`);

--
-- Constraints for table `study_programs`
--
ALTER TABLE `study_programs`
  ADD CONSTRAINT `study_programs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `study_programs_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`);

--
-- Constraints for table `study_program_users`
--
ALTER TABLE `study_program_users`
  ADD CONSTRAINT `study_program_users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `study_program_users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `study_program_user_roles`
--
ALTER TABLE `study_program_user_roles`
  ADD CONSTRAINT `study_program_user_roles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `study_program_user_roles_study_program_id_foreign` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`),
  ADD CONSTRAINT `study_program_user_roles_study_program_user_id_foreign` FOREIGN KEY (`study_program_user_id`) REFERENCES `study_program_users` (`id`),
  ADD CONSTRAINT `study_program_user_roles_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
