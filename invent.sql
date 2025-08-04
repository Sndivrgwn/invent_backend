-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 04, 2025 at 02:52 AM
-- Server version: 8.0.42-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invent`
--

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(4, 'Elektronik', 'Dibuat otomatis dari impor', '2025-07-29 06:48:45', '2025-07-29 06:48:45'),
(5, 'Monitor', 'Dibuat otomatis dari impor', '2025-07-29 11:10:41', '2025-07-29 11:10:41'),
(6, 'Aksesori', 'Dibuat otomatis dari impor', '2025-07-29 11:10:41', '2025-07-29 11:10:41'),
(7, 'as', 'Dibuat otomatis dari impor', '2025-08-03 03:58:14', '2025-08-03 03:58:14'),
(21, 'fsafsa', 'Dibuat otomatis dari impor', '2025-08-03 04:44:47', '2025-08-03 04:44:47'),
(25, 'hhhss', 'Dibuat otomatis dari impor', '2025-08-03 05:00:19', '2025-08-03 05:00:19'),
(26, 'yshs', 'Dibuat otomatis dari impor', '2025-08-03 05:01:26', '2025-08-03 05:01:26'),
(27, 'dasdas', 'Dibuat otomatis dari impor', '2025-08-03 05:21:13', '2025-08-03 05:21:13'),
(28, 'aaaa', 'Dibuat otomatis dari impor', '2025-08-03 05:22:52', '2025-08-03 05:22:52'),
(29, 'Mikrotik', 'Dibuat otomatis dari impor', '2025-08-04 02:05:31', '2025-08-04 02:05:31');

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
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('READY','NOT READY') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'READY',
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'items/default.png',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `location_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `code`, `status`, `brand`, `type`, `condition`, `image`, `description`, `created_at`, `updated_at`, `category_id`, `location_id`) VALUES
(1, 'Laptop A', 'SN2025X0000', 'READY', 'BrandX', 'Model-100', 'NOT GOOD', 'items/default.png', 'Deskripsi produk 1', '2025-07-29 06:48:45', '2025-08-04 02:50:00', 4, 2),
(2, 'Laptop B', 'SN2025X0001', 'READY', 'BrandY', 'Model-101', 'FAIR', 'items/default.png', 'Deskripsi produk 2', '2025-07-29 06:48:45', '2025-07-29 08:19:13', 4, 2),
(3, 'Laptop C', 'SN2025X0002', 'READY', 'BrandX', 'Model-102', 'FAIR', 'items/default.png', 'Deskripsi produk 3', '2025-07-29 06:48:45', '2025-07-29 08:19:13', 4, 2),
(4, 'Laptop D', 'SN2025X0003', 'READY', 'BrandY', 'Model-103', 'GOOD', 'items/default.png', 'Deskripsi produk 4', '2025-07-29 06:48:45', '2025-07-29 08:19:13', 4, 2),
(5, 'Laptop E', 'SN2025X0004', 'READY', 'BrandX', 'Model-104', 'FAIR', 'items/default.png', 'Deskripsi produk 5', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(6, 'Laptop F', 'SN2025X0005', 'READY', 'BrandY', 'Model-105', 'FAIR', 'items/default.png', 'Deskripsi produk 6', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(7, 'Laptop G', 'SN2025X0006', 'READY', 'BrandX', 'Model-106', 'GOOD', 'items/default.png', 'Deskripsi produk 7', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(8, 'Laptop H', 'SN2025X0007', 'READY', 'BrandY', 'Model-107', 'FAIR', 'items/default.png', 'Deskripsi produk 8', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(9, 'Laptop I', 'SN2025X0008', 'READY', 'BrandX', 'Model-108', 'FAIR', 'items/default.png', 'Deskripsi produk 9', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(10, 'Laptop J', 'SN2025X0009', 'READY', 'BrandY', 'Model-109', 'GOOD', 'items/default.png', 'Deskripsi produk 10', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(11, 'Laptop K', 'SN2025X0010', 'READY', 'BrandX', 'Model-110', 'FAIR', 'items/default.png', 'Deskripsi produk 11', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(12, 'Laptop L', 'SN2025X0011', 'READY', 'BrandY', 'Model-111', 'FAIR', 'items/default.png', 'Deskripsi produk 12', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(13, 'Laptop M', 'SN2025X0012', 'READY', 'BrandX', 'Model-112', 'GOOD', 'items/default.png', 'Deskripsi produk 13', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(14, 'Laptop N', 'SN2025X0013', 'READY', 'BrandY', 'Model-113', 'GOOD', 'items/PpAjE9nF7nOOvYX2hoP08f68NoDoLm5I4dP80GqF.jpg', 'Deskripsi produk 14', '2025-07-29 06:48:45', '2025-08-02 00:39:21', 4, 2),
(15, 'Laptop O', 'SN2025X0014', 'READY', 'BrandX', 'Model-114', 'FAIR', 'items/default.png', 'Deskripsi produk 15', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(16, 'Laptop P', 'SN2025X0015', 'READY', 'BrandY', 'Model-115', 'GOOD', 'items/default.png', 'Deskripsi produk 16', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(17, 'Laptop Q', 'SN2025X0016', 'READY', 'BrandX', 'Model-116', 'FAIR', 'items/default.png', 'Deskripsi produk 17', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(18, 'Laptop R', 'SN2025X0017', 'READY', 'BrandY', 'Model-117', 'FAIR', 'items/default.png', 'Deskripsi produk 18', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(19, 'Laptop S', 'SN2025X0018', 'READY', 'BrandX', 'Model-118', 'GOOD', 'items/default.png', 'Deskripsi produk 19', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(20, 'Laptop T', 'SN2025X0019', 'READY', 'BrandY', 'Model-119', 'FAIR', 'items/default.png', 'Deskripsi produk 20', '2025-07-29 06:48:45', '2025-07-29 06:48:45', 4, 2),
(21, 'asd ds', 'ds', 'READY', 'sad', 'ds', 'GOOD', 'items/VKu1BmdNskf13cGFeulqDmmFjpBaC9UooFxwjJfF.jpg', 'ds', '2025-07-29 08:50:39', '2025-07-29 11:12:28', 4, 2),
(22, 'Laptop A', 'SN123456', 'READY', 'Acer', 'Aspire 3', 'GOOD', 'items/default.png', 'Laptop kantor lama', '2025-07-29 11:10:41', '2025-07-29 11:10:41', 4, 3),
(23, 'Monitor B', 'SN654321', 'READY', 'LG', 'UltraFine', 'NOT GOOD', 'items/default.png', 'Layar ada garis', '2025-07-29 11:10:41', '2025-07-29 11:10:41', 5, 4),
(24, 'Keyboard C', 'SN987654', 'READY', 'Logitech', 'MX Keys', 'GOOD', 'items/default.png', 'Masih berfungsi baik', '2025-07-29 11:10:41', '2025-07-29 11:10:41', 6, 5),
(25, 'a', 'dsa', 'READY', 'a', 'a', 'GOOD', 'items/default.png', 'dsa', '2025-08-03 03:58:14', '2025-08-03 03:58:14', 7, 6),
(26, 'a', 's', 'READY', 'a', 'a', 'GOOD', 'items/default.png', 'dsa', '2025-08-03 03:58:14', '2025-08-03 03:58:14', 7, 6),
(27, 'a', 'dsaw', 'READY', 'a', 'a', 'GOOD', 'items/default.png', 'dsa', '2025-08-03 03:58:14', '2025-08-03 03:58:14', 7, 6),
(28, 'a', 'dsawa', 'READY', 'a', 'a', 'GOOD', 'items/default.png', 'dsa', '2025-08-03 03:58:14', '2025-08-03 03:58:14', 7, 6),
(48, 'asdsafasdsafsa', '1231', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(49, 'asdsafasdsafsa', '1232', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(50, 'asdsafasdsafsa', '1233', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(51, 'asdsafasdsafsa', '1234', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(52, 'asdsafasdsafsa', '1235', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(53, 'asdsafasdsafsa', '1236', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(54, 'asdsafasdsafsa', '1237', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(55, 'asdsafasdsafsa', '1238', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(56, 'asdsafasdsafsa', '1239', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(57, 'asdsafasdsafsa', '1240', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(58, 'asdsafasdsafsa', '1241', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(59, 'asdsafasdsafsa', '1242', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(60, 'asdsafasdsafsa', '1243', 'READY', 's', 'fsafsa', 'fsafs', 'items/default.png', 'sdsadsa', '2025-08-03 04:44:47', '2025-08-03 04:44:47', 21, 21),
(69, 'babs', 'eyebsx', 'READY', 'sbsvs', 'bsbs', 'hshe', 'items/default.png', 'euhe', '2025-08-03 05:00:19', '2025-08-03 05:00:19', 25, 25),
(70, 'hshsh', 'bdjdug', 'READY', 'hshs', 'bsbsh', 'gsgs', 'items/default.png', 'hpchocgic', '2025-08-03 05:01:26', '2025-08-03 05:01:26', 26, 26),
(80, 'raMikrotik', '1233033', 'READY', 'ap', 'ap', 'GOOD', 'items/default.png', 'gud', '2025-08-03 05:21:10', '2025-08-04 02:16:48', 4, 2),
(81, 'dasds1', '21', 'READY', '21df2', 'fasdf32', 'dasds', 'items/default.png', 'fdd', '2025-08-03 05:21:13', '2025-08-03 05:21:13', 27, 27),
(82, 'dasds2', '22', 'READY', '21df3', 'fasdf33', 'dasds', 'items/default.png', 'fdd', '2025-08-03 05:21:13', '2025-08-03 05:21:13', 27, 27),
(83, 'dasds3', '23', 'READY', '21df4', 'fasdf34', 'dasds', 'items/default.png', 'fdd', '2025-08-03 05:21:13', '2025-08-03 05:21:13', 27, 27),
(84, 'dasds4', '24', 'READY', '21df5', 'fasdf35', 'dasds', 'items/default.png', 'fdd', '2025-08-03 05:21:13', '2025-08-03 05:21:13', 27, 27),
(85, 'dasds5', '25', 'READY', '21df6', 'fasdf36', 'dasds', 'items/default.png', 'fdd', '2025-08-03 05:21:13', '2025-08-03 05:21:13', 27, 27),
(86, 'dasds6', '26', 'READY', '21df7', 'fasdf37', 'dasds', 'items/default.png', 'fdd', '2025-08-03 05:21:13', '2025-08-03 05:21:13', 27, 27),
(87, 'cvdf', '2311', 'READY', 'das', 'das', 'dsa', 'items/default.png', 'fdaf', '2025-08-03 05:22:52', '2025-08-03 05:22:52', 28, 28),
(88, 'cvdf', '2312', 'READY', 'das', 'das', 'dsa', 'items/default.png', 'fdaf', '2025-08-03 05:22:52', '2025-08-03 05:22:52', 28, 28),
(89, 'cvdf', '2313', 'READY', 'das', 'das', 'dsa', 'items/default.png', 'fdaf', '2025-08-03 05:22:53', '2025-08-03 05:22:53', 28, 28),
(90, 'cvdf', '2314', 'READY', 'das', 'das', 'dsa', 'items/default.png', 'fdaf', '2025-08-03 05:22:53', '2025-08-03 05:22:53', 28, 28),
(91, 'cvdf', '2315', 'READY', 'das', 'das', 'dsa', 'items/default.png', 'fdaf', '2025-08-03 05:22:53', '2025-08-03 05:22:53', 28, 28),
(92, 'cvdf', '2316', 'READY', 'das', 'das', 'dsa', 'items/default.png', 'fdaf', '2025-08-03 05:22:53', '2025-08-03 05:22:53', 28, 28),
(93, 'Mikroik1', '20250804', 'READY', 'mikrotik', 'RB', 'GOOD', 'items/default.png', 'mikrotik rb', '2025-08-04 02:05:31', '2025-08-04 02:05:31', 29, 2),
(94, 'Mikroik2', '20250805', 'READY', 'mikrotik', 'RB', 'GOOD', 'items/default.png', 'mikrotik rb', '2025-08-04 02:05:31', '2025-08-04 02:05:31', 29, 2),
(95, 'sd das', 'dasdas', 'READY', 'dsa', 'das', 'NOT GOOD', 'default.png', 'das', '2025-08-04 02:14:08', '2025-08-04 02:14:08', 26, 4),
(96, 'tes produk terbaryu a', 'kopvghbfvdas', 'READY', 'a', 'a', 'NOT GOOD', 'default.png', 'dasdsa', '2025-08-04 02:19:17', '2025-08-04 02:19:17', 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `item_loan`
--

CREATE TABLE `item_loan` (
  `id` bigint UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_loan`
--

INSERT INTO `item_loan` (`id`, `loan_id`, `item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-07-29 07:00:14', '2025-07-29 07:00:14'),
(2, 2, 1, 1, '2025-07-29 08:15:39', '2025-07-29 08:15:39'),
(3, 3, 2, 1, '2025-07-29 08:16:34', '2025-07-29 08:16:34'),
(4, 3, 3, 1, '2025-07-29 08:16:34', '2025-07-29 08:16:34'),
(5, 3, 4, 1, '2025-07-29 08:16:34', '2025-07-29 08:16:34'),
(6, 4, 80, 1, '2025-08-04 02:16:26', '2025-08-04 02:16:26');

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

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(11, 'default', '{\"uuid\":\"a7350890-cc13-45bc-bbce-3b6760655cb1\",\"displayName\":\"App\\\\Jobs\\\\DeleteGuestAfterDelay\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\DeleteGuestAfterDelay\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\DeleteGuestAfterDelay\\\":2:{s:9:\\\"\\u0000*\\u0000userId\\\";i:43;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2025-08-02 09:30:08.772531\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:12:\\\"Asia\\/Jakarta\\\";}}\"}}', 0, NULL, 1754101808, 1754098208),
(12, 'default', '{\"uuid\":\"55e98300-b17b-4ee1-8a93-eccdcf643608\",\"displayName\":\"App\\\\Jobs\\\\DeleteGuestAfterDelay\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\DeleteGuestAfterDelay\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\DeleteGuestAfterDelay\\\":2:{s:9:\\\"\\u0000*\\u0000userId\\\";i:44;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2025-08-02 12:33:16.574112\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:12:\\\"Asia\\/Jakarta\\\";}}\"}}', 0, NULL, 1754112796, 1754109196),
(13, 'default', '{\"uuid\":\"4e3dbe3d-b1c2-415a-ae34-da735a090ca0\",\"displayName\":\"App\\\\Jobs\\\\DeleteGuestAfterDelay\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\DeleteGuestAfterDelay\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\DeleteGuestAfterDelay\\\":2:{s:9:\\\"\\u0000*\\u0000userId\\\";i:45;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2025-08-04 09:56:44.164348\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:12:\\\"Asia\\/Jakarta\\\";}}\"}}', 0, NULL, 1754276204, 1754272604);

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
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint UNSIGNED NOT NULL,
  `code_loans` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_date` date NOT NULL,
  `return_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loaner_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `code_loans`, `loan_date`, `return_date`, `status`, `loaner_name`, `description`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'LN-20250729140003', '2025-07-29', '2025-08-05', 'returned', 'aa', 'aaa', 1, '2025-07-29 07:00:14', '2025-07-29 07:09:22'),
(2, 'LN-20250729151527', '2025-07-29', '2025-07-30', 'returned', 'dsa', 'fsaf', 4, '2025-07-29 08:15:39', '2025-07-29 08:19:06'),
(3, 'LN-20250729151603', '2025-07-29', '2025-08-05', 'returned', 'aaa', 'aaa', 4, '2025-07-29 08:16:34', '2025-07-29 08:19:13'),
(4, 'LN-20250804091603', '2025-08-04', '2025-08-04', 'returned', 'rafli', 'lab tkj', 1, '2025-08-04 02:16:26', '2025-08-04 02:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `description`, `image`, `created_at`, `updated_at`) VALUES
(2, 'Gudang 1', 'Dibuat otomatis dari impor', 'default.png', '2025-07-29 06:48:45', '2025-07-29 06:48:45'),
(3, 'Rak Belakang', 'Dibuat otomatis dari impor', 'default.png', '2025-07-29 11:10:41', '2025-07-29 11:10:41'),
(4, 'Rak Depan', 'Dibuat otomatis dari impor', 'default.png', '2025-07-29 11:10:41', '2025-07-29 11:10:41'),
(5, 'Rak Samping', 'Dibuat otomatis dari impor', 'default.png', '2025-07-29 11:10:41', '2025-07-29 11:10:41'),
(6, 'sd', 'Dibuat otomatis dari impor', 'default.png', '2025-08-03 03:58:14', '2025-08-03 03:58:14'),
(21, 'fasfsafas', 'Dibuat otomatis dari impor', 'default.png', '2025-08-03 04:44:47', '2025-08-03 04:44:47'),
(25, 'dss', 'Dibuat otomatis dari impor', 'default.png', '2025-08-03 05:00:19', '2025-08-03 05:00:19'),
(26, 'ysys', 'Dibuat otomatis dari impor', 'default.png', '2025-08-03 05:01:26', '2025-08-03 05:01:26'),
(27, 'sssss', 'Dibuat otomatis dari impor', 'default.png', '2025-08-03 05:21:13', '2025-08-03 05:21:13'),
(28, 'aaaaaasf', 'Dibuat otomatis dari impor', 'default.png', '2025-08-03 05:22:52', '2025-08-03 05:22:52');

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
(4, '2025_05_25_124959_create_personal_access_tokens_table', 1),
(5, '2025_05_29_192617_create_roles_table', 1),
(6, '2025_05_29_194320_add_role_id_to_users_table', 1),
(7, '2025_05_30_072247_create_categories_table', 1),
(8, '2025_05_30_081852_create_locations_table', 1),
(9, '2025_05_30_083823_create_items_table', 1),
(10, '2025_05_30_092048_create_loans_table', 1),
(11, '2025_05_30_184651_create_return_table', 1),
(12, '2025_06_12_195418_create_item_loan_table', 1),
(13, '2025_07_29_064631_make_category_and_location_name_unique', 1),
(14, '2025_07_29_074735_add_is_guest_to_users_table', 1);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return`
--

CREATE TABLE `return` (
  `id` bigint UNSIGNED NOT NULL,
  `return_date` date NOT NULL,
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `loan_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `return`
--

INSERT INTO `return` (`id`, `return_date`, `condition`, `notes`, `created_at`, `updated_at`, `loan_id`) VALUES
(1, '2025-07-29', 'GOOD', NULL, '2025-07-29 07:09:22', '2025-07-29 07:09:22', 1),
(2, '2025-07-29', 'GOOD', NULL, '2025-07-29 08:19:06', '2025-07-29 08:19:06', 2),
(3, '2025-07-29', 'GOOD', NULL, '2025-07-29 08:19:13', '2025-07-29 08:19:13', 3),
(4, '2025-08-04', 'GOOD', 'mantap', '2025-08-04 02:16:48', '2025-08-04 02:16:48', 4);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'user', NULL, NULL),
(3, 'superadmin', NULL, NULL),
(4, 'km', NULL, NULL);

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
('BTRLeUNzXpxL1iaSeOz6UEOoZILxNBbGqUiLEbpY', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQ0ZLd2pBR0wwUmFoV1l3cVUyeEJzUkU2d0N0T05takx3bjRscklDMyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ5OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvcHJvZHVjdHM/cGFnZT0xJnNvcnREaXI9YXNjIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE1OiJsYXN0X2xvZ2luX3RpbWUiO086MjU6IklsbHVtaW5hdGVcU3VwcG9ydFxDYXJib24iOjM6e3M6NDoiZGF0ZSI7czoyNjoiMjAyNS0wOC0wNCAwOToxMzo0Ni44NzMxMTkiO3M6MTM6InRpbWV6b25lX3R5cGUiO2k6MztzOjg6InRpbWV6b25lIjtzOjEyOiJBc2lhL0pha2FydGEiO319', 1754275813),
('hIHwrPt1THGOKy2MGXggDsBQ8jbCWcvWsUmCZuuy', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWmMxelRTQnJPMUM3bkdNeFFxWXFFd0t1SWRHYXVFNmtrN0lXOVVzMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9pbnZlbnRzLmh0dHBzdGprdHNhdHUubXkuaWQvbG9hbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNToibGFzdF9sb2dpbl90aW1lIjtPOjI1OiJJbGx1bWluYXRlXFN1cHBvcnRcQ2FyYm9uIjozOntzOjQ6ImRhdGUiO3M6MjY6IjIwMjUtMDgtMDQgMDg6NTk6NTIuMzMwMTEyIjtzOjEzOiJ0aW1lem9uZV90eXBlIjtpOjM7czo4OiJ0aW1lem9uZSI7czoxMjoiQXNpYS9KYWthcnRhIjt9fQ==', 1754274324),
('McryFFQUCU4vKGNiC4l2dUgRkz6QHZgbm2lWiRjQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicUtOZ3JPSlRXNFJzMFJSVWpVS3JZU0M5OWR3ZVJBaldCZzlSYzNJUSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NDoiaHR0cDovL2ludmVudHMuaHR0cHN0amt0c2F0dS5teS5pZC9kYXNoYm9hcmQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovL2ludmVudHMuaHR0cHN0amt0c2F0dS5teS5pZC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1754271774),
('OoLb7IVocKozLm6ZLmeXCR0Y11k84XWQIYNn9iwQ', 1, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiemtKZk1DTjYxcUZ2elN2RjNjY2o4MkdBQ3RIRnFaajVwQ0NqZkt4cCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQxOiJodHRwOi8vaW52ZW50cy5odHRwc3Rqa3RzYXR1Lm15LmlkL3Byb2ZpbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNToibGFzdF9sb2dpbl90aW1lIjtPOjI1OiJJbGx1bWluYXRlXFN1cHBvcnRcQ2FyYm9uIjozOntzOjQ6ImRhdGUiO3M6MjY6IjIwMjUtMDgtMDQgMDg6MzE6MjguMzM1NjE3IjtzOjEzOiJ0aW1lem9uZV90eXBlIjtpOjM7czo4OiJ0aW1lem9uZSI7czoxMjoiQXNpYS9KYWthcnRhIjt9fQ==', 1754271143),
('sHNnkYNOwqWXD99HBMY7K1nsTxIazvYqDU7GTPfZ', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY0xRNTFiTk8zOTQwVUZrUk9ZMFNPeG9UMTQ3b0ZYZDFpM3EyMWlJayI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovL2ludmVudHMuaHR0cHN0amt0c2F0dS5teS5pZC9wcm9kdWN0cyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vaW52ZW50cy5odHRwc3Rqa3RzYXR1Lm15LmlkL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1754271810);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_active_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_guest` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `roles_id`, `avatar`, `last_active_at`, `remember_token`, `created_at`, `updated_at`, `is_guest`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$loss51I6AhPEIz2yeREuU.R6hhTygpXasH5Sl4wYHSnHSVlQS4tCO', 1, 'avatars/avatar-1-1753787480.jpg', '2025-08-04 02:50:13', NULL, NULL, '2025-08-04 02:50:13', 0),
(2, 'super admin', 'inventaris.tkj@gmail.com', NULL, '$2y$12$CyPUlzvk7BspDDTZTvKs8excO2YjdzXuk/2uGzCkz8J2pgXhcefFW', 3, NULL, NULL, NULL, NULL, NULL, 0),
(3, 'TKJ 1', 'Xtejekate1.tkj@gmail.com', NULL, '$2y$12$YtdpB7iwhAqsCJJqaZ6vguQz8MmjzL4C0qc8eLUEvnvNlfXPPCZeW', 4, NULL, '2025-07-29 06:43:28', NULL, '2025-07-29 06:38:21', '2025-07-29 06:43:28', 0),
(4, 'TKJ 2', 'Xtejekate2.tkj@gmail.com', NULL, '$2y$12$C3qsqUAd5LQ1dcg.MaGSp..8GmVFN5UvclAp2W9dAm/9mcjmMiA/.', 4, NULL, '2025-07-29 08:17:55', NULL, '2025-07-29 06:38:22', '2025-07-29 08:17:55', 0),
(5, 'TKJ 3', 'Xtejekate3.tkj@gmail.com', NULL, '$2y$12$UkA1PY7mmiRSOLWaCTs3PeYHvSh9H6NRzhGty7vmppnKC0CvRHMHW', 4, NULL, NULL, NULL, '2025-07-29 06:38:22', '2025-07-29 06:38:22', 0),
(43, 'Guest_1qOND', '9db4e136-6400-42bd-b8fa-ad3a11888a15@guest.local', NULL, '$2y$12$bTCTGDl3q3AOWq47seMpFO8MokLiFsY24c8TRrMXs1x8xzkEOYM46', 2, NULL, '2025-08-02 01:30:10', NULL, '2025-08-02 01:30:08', '2025-08-02 01:30:10', 1);

--
-- Indexes for dumped tables
--

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_code_unique` (`code`),
  ADD KEY `items_category_id_foreign` (`category_id`),
  ADD KEY `items_location_id_foreign` (`location_id`);

--
-- Indexes for table `item_loan`
--
ALTER TABLE `item_loan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_loan_loan_id_foreign` (`loan_id`),
  ADD KEY `item_loan_item_id_foreign` (`item_id`);

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
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loans_code_loans_unique` (`code_loans`),
  ADD KEY `loans_user_id_foreign` (`user_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `locations_name_unique` (`name`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `return`
--
ALTER TABLE `return`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_loan_id_foreign` (`loan_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_roles_id_foreign` (`roles_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `item_loan`
--
ALTER TABLE `item_loan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return`
--
ALTER TABLE `return`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_loan`
--
ALTER TABLE `item_loan`
  ADD CONSTRAINT `item_loan_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_loan_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `return`
--
ALTER TABLE `return`
  ADD CONSTRAINT `return_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_roles_id_foreign` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
