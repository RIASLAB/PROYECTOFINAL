-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 02-11-2025 a las 04:23:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vetapp_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

DROP TABLE IF EXISTS `citas`;
CREATE TABLE IF NOT EXISTS `citas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mascota_id` bigint(20) UNSIGNED NOT NULL,
  `vet_id` bigint(20) UNSIGNED DEFAULT NULL,
  `motivo` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'pendiente',
  `notas` text DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `citas_mascota_id_foreign` (`mascota_id`),
  KEY `citas_vet_id_index` (`vet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `mascota_id`, `vet_id`, `motivo`, `estado`, `notas`, `fecha`, `hora`, `observaciones`, `created_at`, `updated_at`) VALUES
(2, 2, 5, 'cancer', 'completada', NULL, '2025-10-15', '15:19:00', 'wwew', '2025-10-06 10:16:25', '2025-10-30 08:27:08'),
(3, 3, 5, 'cancer', 'completada', NULL, '2025-10-21', '00:28:00', 'eeee', '2025-10-06 10:28:53', '2025-10-26 10:50:44'),
(4, 4, 5, 'dientes', 'completada', NULL, '2025-10-21', '10:11:00', 'bien', '2025-10-08 05:11:53', '2025-10-27 05:24:18'),
(10, 15, 5, 'dentadura', 'completada', NULL, '2025-10-29', '20:30:00', 'rrwetwer', '2025-10-24 06:29:05', '2025-10-27 17:48:29'),
(11, 18, 5, 'dentadura', 'completada', NULL, '2025-10-25', '22:35:00', 'sdfafadf', '2025-10-25 08:33:08', '2025-10-27 11:55:53'),
(12, 19, 5, 'kaka', 'completada', NULL, '2025-10-23', '09:50:00', 'dasdsadasdfsadf', '2025-10-27 17:50:19', '2025-10-29 02:39:42'),
(13, 20, 5, 'cancer de popo', 'completada', NULL, '2025-11-01', '07:30:00', 'sdfwerfqewqe', '2025-11-01 11:14:56', '2025-11-01 11:16:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

DROP TABLE IF EXISTS `facturas`;
CREATE TABLE IF NOT EXISTS `facturas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `historia_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cliente` varchar(255) DEFAULT NULL,
  `mascota` varchar(255) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `impuesto` decimal(6,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` enum('borrador','pendiente','pagada') NOT NULL DEFAULT 'pendiente',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `facturas_user_id_foreign` (`user_id`),
  KEY `facturas_historia_id_estado_index` (`historia_id`,`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `historia_id`, `user_id`, `cliente`, `mascota`, `subtotal`, `impuesto`, `total`, `estado`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 4, 6, 'jhon', NULL, 100000.00, 0.00, 100000.00, 'pendiente', NULL, '2025-10-31 10:00:20', '2025-10-31 10:00:20'),
(2, 4, 6, 'jhon', NULL, 15000.00, 0.00, 15000.00, 'pendiente', NULL, '2025-10-31 10:04:07', '2025-10-31 10:04:07'),
(3, 4, 6, 'jhon', NULL, 100000000.00, 0.00, 100000000.00, 'pendiente', NULL, '2025-10-31 10:12:54', '2025-10-31 10:12:54'),
(4, 4, 6, NULL, NULL, 123345.00, 0.00, 123345.00, 'pagada', '2025-10-31 10:17:12', '2025-10-31 10:16:36', '2025-10-31 10:17:12'),
(5, 4, 6, 'jhon', 'stiven', 150000.00, 0.00, 150000.00, 'pendiente', NULL, '2025-10-31 10:26:09', '2025-10-31 10:26:09'),
(6, 4, 6, 'jhon', 'stiven', 10000.00, 0.00, 10000.00, 'pagada', '2025-10-31 10:26:53', '2025-10-31 10:26:44', '2025-10-31 10:26:53'),
(7, 4, 6, 'jhon', 'stiven', 30000.00, 0.00, 30000.00, 'pendiente', NULL, '2025-10-31 10:34:47', '2025-10-31 10:34:47'),
(8, 4, 6, NULL, 'stiven', 10000.00, 0.00, 10000.00, 'pagada', '2025-10-31 10:35:20', '2025-10-31 10:35:13', '2025-10-31 10:35:20'),
(9, 4, 6, 'jhon', 'stiven', 10000.00, 0.00, 10000.00, 'pagada', '2025-10-31 10:55:24', '2025-10-31 10:55:19', '2025-10-31 10:55:24'),
(10, 4, 6, 'jhon', 'stiven', 15000.00, 0.00, 15000.00, 'pagada', '2025-10-31 11:10:21', '2025-10-31 11:10:16', '2025-10-31 11:10:21'),
(11, 4, 6, 'jhon', 'stiven', 56000.00, 0.00, 56000.00, 'pagada', '2025-10-31 11:20:03', '2025-10-31 11:19:59', '2025-10-31 11:20:03'),
(12, 4, 6, 'jhon', 'stiven', 23333.00, 0.00, 23333.00, 'pagada', '2025-10-31 11:27:27', '2025-10-31 11:27:24', '2025-10-31 11:27:27'),
(13, 4, 6, 'jhon', 'stiven', 10000.00, 0.00, 10000.00, 'pagada', '2025-10-31 11:28:17', '2025-10-31 11:28:15', '2025-10-31 11:28:17'),
(14, 4, 6, 'jhon', 'stiven', 78000.00, 0.00, 78000.00, 'pagada', '2025-10-31 11:36:56', '2025-10-31 11:36:52', '2025-10-31 11:36:56'),
(15, 4, 6, 'jhon', 'stiven', 1000.00, 0.00, 1000.00, 'pagada', '2025-10-31 11:45:44', '2025-10-31 11:45:40', '2025-10-31 11:45:44'),
(16, 6, 6, 'jhon', 'antonella', 70000.00, 0.00, 70000.00, 'pagada', '2025-11-01 11:18:43', '2025-11-01 11:18:26', '2025-11-01 11:18:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_items`
--

DROP TABLE IF EXISTS `factura_items`;
CREATE TABLE IF NOT EXISTS `factura_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `factura_id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `factura_items_factura_id_foreign` (`factura_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `factura_items`
--

INSERT INTO `factura_items` (`id`, `factura_id`, `descripcion`, `cantidad`, `precio`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 'Receta – 56745745', 1, 100000.00, 100000.00, '2025-10-31 10:00:20', '2025-10-31 10:00:20'),
(2, 1, 'Receta – 56745745', 1, 100000.00, 100000.00, '2025-10-31 10:00:20', '2025-10-31 10:00:20'),
(3, 2, 'Receta – 56745745', 1, 15000.00, 15000.00, '2025-10-31 10:04:07', '2025-10-31 10:04:07'),
(4, 2, 'Receta – 56745745', 1, 15000.00, 15000.00, '2025-10-31 10:04:07', '2025-10-31 10:04:07'),
(5, 3, 'Receta – 56745745', 1, 100000000.00, 100000000.00, '2025-10-31 10:12:54', '2025-10-31 10:12:54'),
(6, 3, 'Receta – 56745745', 1, 100000000.00, 100000000.00, '2025-10-31 10:12:54', '2025-10-31 10:12:54'),
(7, 4, 'Receta – 56745745', 1, 123345.00, 123345.00, '2025-10-31 10:16:36', '2025-10-31 10:16:36'),
(8, 4, 'Receta – 56745745', 1, 123345.00, 123345.00, '2025-10-31 10:16:36', '2025-10-31 10:16:36'),
(9, 5, 'Receta – 56745745', 1, 150000.00, 150000.00, '2025-10-31 10:26:09', '2025-10-31 10:26:09'),
(10, 5, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 10:26:09', '2025-10-31 10:26:09'),
(11, 6, 'Receta – 56745745', 1, 10000.00, 10000.00, '2025-10-31 10:26:44', '2025-10-31 10:26:44'),
(12, 6, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 10:26:44', '2025-10-31 10:26:44'),
(13, 7, 'Receta – 56745745', 1, 30000.00, 30000.00, '2025-10-31 10:34:47', '2025-10-31 10:34:47'),
(14, 7, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 10:34:47', '2025-10-31 10:34:47'),
(15, 8, 'Receta – 56745745', 1, 10000.00, 10000.00, '2025-10-31 10:35:13', '2025-10-31 10:35:13'),
(16, 8, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 10:35:13', '2025-10-31 10:35:13'),
(17, 9, 'Receta – 56745745', 1, 10000.00, 10000.00, '2025-10-31 10:55:19', '2025-10-31 10:55:19'),
(18, 9, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 10:55:19', '2025-10-31 10:55:19'),
(19, 10, 'Receta – 56745745', 1, 15000.00, 15000.00, '2025-10-31 11:10:16', '2025-10-31 11:10:16'),
(20, 10, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 11:10:16', '2025-10-31 11:10:16'),
(21, 11, 'Receta – 56745745', 1, 56000.00, 56000.00, '2025-10-31 11:19:59', '2025-10-31 11:19:59'),
(22, 11, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 11:19:59', '2025-10-31 11:19:59'),
(23, 12, 'Receta – 56745745', 1, 23333.00, 23333.00, '2025-10-31 11:27:24', '2025-10-31 11:27:24'),
(24, 12, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 11:27:24', '2025-10-31 11:27:24'),
(25, 13, 'Receta – 56745745', 1, 10000.00, 10000.00, '2025-10-31 11:28:15', '2025-10-31 11:28:15'),
(26, 13, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 11:28:15', '2025-10-31 11:28:15'),
(27, 14, 'Receta – 56745745', 1, 78000.00, 78000.00, '2025-10-31 11:36:52', '2025-10-31 11:36:52'),
(28, 14, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 11:36:52', '2025-10-31 11:36:52'),
(29, 15, 'Receta – 56745745', 1, 1000.00, 1000.00, '2025-10-31 11:45:40', '2025-10-31 11:45:40'),
(30, 15, 'Receta – ghjkgkgj', 1, 0.00, 0.00, '2025-10-31 11:45:40', '2025-10-31 11:45:40'),
(31, 16, 'Receta – 324141324', 1, 70000.00, 70000.00, '2025-11-01 11:18:26', '2025-11-01 11:18:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historias`
--

DROP TABLE IF EXISTS `historias`;
CREATE TABLE IF NOT EXISTS `historias` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cita_id` bigint(20) UNSIGNED NOT NULL,
  `vet_id` bigint(20) UNSIGNED DEFAULT NULL,
  `motivo` varchar(255) NOT NULL,
  `anamnesis` text DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `tratamiento` text DEFAULT NULL,
  `recomendaciones` text DEFAULT NULL,
  `pendiente_cobro` tinyint(1) NOT NULL DEFAULT 0,
  `enviado_caja_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historias_cita_id_index` (`cita_id`),
  KEY `historias_vet_id_index` (`vet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historias`
--

INSERT INTO `historias` (`id`, `cita_id`, `vet_id`, `motivo`, `anamnesis`, `diagnostico`, `tratamiento`, `recomendaciones`, `pendiente_cobro`, `enviado_caja_at`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 'FGERG', 'RET', 'RET', 'ERT', 'TR', 0, NULL, '2025-10-27 05:24:12', '2025-10-27 05:24:12'),
(2, 11, 5, 'erewrtret', 'retwtw', 'retewt', 'rtewtrew', 'retw', 0, NULL, '2025-10-27 07:26:43', '2025-10-27 07:26:43'),
(3, 10, 5, 'dentadura', 'WETERWT', 'ERTEW', 'REWTE', 'ERTREWT', 0, NULL, '2025-10-27 11:38:05', '2025-10-27 11:38:05'),
(4, 12, 5, 'kaka', 'dfsdfdsatret5646363', 'dsgsdgdf', 'yuutououy', 'sdfgfg', 1, NULL, '2025-10-27 17:52:34', '2025-10-31 11:45:44'),
(5, 2, 5, 'cancer', 'sadfasf', 'kaka', 'fdgfdsghfh', 'gfhgdhgj', 0, NULL, '2025-10-30 08:26:50', '2025-10-30 08:27:01'),
(6, 13, 5, 'cancer de popo', '234r15', 'r432423', '3153125312', '1325215132531', 1, NULL, '2025-11-01 11:16:22', '2025-11-01 11:17:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

DROP TABLE IF EXISTS `mascotas`;
CREATE TABLE IF NOT EXISTS `mascotas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `especie` varchar(255) NOT NULL,
  `raza` varchar(255) DEFAULT NULL,
  `edad` int(11) DEFAULT NULL,
  `dueno` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id`, `nombre`, `especie`, `raza`, `edad`, `dueno`, `created_at`, `updated_at`) VALUES
(2, 'yuyu', 'perro', 'pitbul', 13, 'jhon', '2025-10-06 07:47:20', '2025-10-06 07:47:20'),
(3, 'yoyo', 'perro', 'bulldog', 15, 'jhon', '2025-10-06 09:05:03', '2025-10-06 09:05:03'),
(4, 'pai', 'perro', 'bulldog', 20, 'jhon', '2025-10-06 19:35:51', '2025-10-07 09:55:23'),
(6, 'yaya', 'perro', 'bbb', 7, 'jhon', '2025-10-09 08:56:16', '2025-10-20 01:47:28'),
(7, 'vivi', 'perrp', 'bulldog', 12, 'jhon', '2025-10-20 06:08:36', '2025-10-20 06:08:36'),
(8, 'pipi', 'perro', 'bulldog', 17, 'jhon', '2025-10-22 11:05:08', '2025-10-22 11:05:08'),
(9, 'papa', 'perro', 'bulldog', 23, 'juan', '2025-10-23 10:26:44', '2025-10-23 10:26:44'),
(10, 'cheila', 'perro', 'bulldog', 15, '1', '2025-10-23 10:50:21', '2025-10-23 10:50:21'),
(11, 'kaka', 'perro', 'bulldog', 34, '1', '2025-10-23 10:54:30', '2025-10-23 10:54:30'),
(12, 'yiyi', 'perro', 'bbb', 1231, '1', '2025-10-24 06:18:53', '2025-10-24 06:18:53'),
(13, 'coco', 'perro', 'bulldog', 1, '1', '2025-10-24 06:26:19', '2025-10-24 10:32:12'),
(14, 'viva', 'perro', 'bulldog', 122334, '8', '2025-10-24 06:28:07', '2025-10-24 06:28:07'),
(15, 'baba', 'we2e23', 'pitbul', 21314234, '8', '2025-10-24 06:28:38', '2025-10-24 06:28:38'),
(16, 'ai', 'perro', 'bulldog', 123243, '8', '2025-10-24 09:00:52', '2025-10-24 09:00:52'),
(17, 'tt', 'perro', 'bulldog', 56, '1', '2025-10-24 10:32:52', '2025-10-24 10:32:52'),
(18, 'popa', 'perro', 'bulldog', 2434, '1', '2025-10-25 08:32:34', '2025-10-25 08:32:34'),
(19, 'stiven', 'perro', 'bulldog', 1234, '1', '2025-10-27 17:49:42', '2025-10-27 17:49:42'),
(20, 'antonella', 'perro', 'bulldog', 34, '1', '2025-11-01 11:14:13', '2025-11-01 11:14:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_05_214907_create_mascotas_table', 1),
(5, '2025_10_05_215048_create_citas_table', 1),
(6, '2025_10_19_220341_create_permission_tables', 2),
(7, '2025_10_20_012259_add_role_to_users_table', 3),
(8, '2025_10_20_041712_add_role_to_users_table', 4),
(9, '2025_10_21_005205_add_role_status_vet_to_users_table', 5),
(10, '2025_10_21_014728_add_admin_user_fields_to_users_table', 6),
(11, '2025_10_21_015758_remove_assigned_vet_id_from_users_table', 7),
(12, '2025_10_23_060107_add_estado_and_notas_to_citas_table', 8),
(13, '2025_10_25_041613_add_vet_id_to_citas_table', 9),
(14, '2025_10_25_202303_create_historias_table', 10),
(15, '2025_10_25_204716_add_vet_id_to_citas_table', 11),
(16, '2025_10_25_235900_add_vet_id_to_citas_table', 12),
(17, '2025_10_26_215753_update_historias_table_add_missing_columns', 13),
(18, '2025_10_26_221158_update_historias_add_foreigns_and_fields', 14),
(19, '2025_10_26_224015_force_add_cita_vet_to_historias', 15),
(20, '2025_10_26_231436_add_historia_fields_to_historias_table', 16),
(21, 'xxxx_xx_xx_create_recetas_tables', 17),
(22, '2025_10_29_012048_create_recetas_tables', 18),
(23, '2025_10_29_000001_add_caja_flags_to_historias', 19),
(24, '2025_10_29_000001_add_pendiente_cobro_to_historias_table', 20),
(25, '2025_10_30_000001_add_pendiente_cobro_to_historias_table', 21),
(26, '2025_10_30_000000_create_facturas_table', 22),
(27, '2025_10_30_000001_create_factura_items_table', 22);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ver_usuarios', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(2, 'crear_usuarios', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(3, 'editar_usuarios', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(4, 'desactivar_usuarios', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(5, 'ver_mascotas', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(6, 'crear_mascota', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(7, 'editar_mascota', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(8, 'eliminar_mascota', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(9, 'ver_citas', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(10, 'crear_cita', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(11, 'editar_cita', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(12, 'cancelar_cita', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(13, 'ver_reportes', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

DROP TABLE IF EXISTS `recetas`;
CREATE TABLE IF NOT EXISTS `recetas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `historia_id` bigint(20) UNSIGNED NOT NULL,
  `vet_id` bigint(20) UNSIGNED NOT NULL,
  `mascota_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `indicaciones` text DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recetas_historia_id_foreign` (`historia_id`),
  KEY `recetas_vet_id_foreign` (`vet_id`),
  KEY `recetas_mascota_id_foreign` (`mascota_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `historia_id`, `vet_id`, `mascota_id`, `fecha`, `indicaciones`, `notas`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 19, '2025-10-29', 'ghjkgkgj', NULL, '2025-10-29 10:30:50', '2025-10-29 10:30:50'),
(2, 4, 5, 19, '2025-10-29', '56745745', 'rtyutryurtyu', '2025-10-29 10:37:55', '2025-10-29 10:37:55'),
(3, 6, 5, 20, '2025-11-01', '324141324', '234132421342', '2025-11-01 11:17:00', '2025-11-01 11:17:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_items`
--

DROP TABLE IF EXISTS `receta_items`;
CREATE TABLE IF NOT EXISTS `receta_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `receta_id` bigint(20) UNSIGNED NOT NULL,
  `medicamento` varchar(255) NOT NULL,
  `dosis` varchar(255) DEFAULT NULL,
  `frecuencia` varchar(255) DEFAULT NULL,
  `duracion` varchar(255) DEFAULT NULL,
  `via` varchar(255) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receta_items_receta_id_foreign` (`receta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'administrador', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(2, 'veterinario', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(3, 'recepcionista', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24'),
(4, 'cliente', 'web', '2025-10-20 03:20:24', '2025-10-20 03:20:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(5, 2),
(5, 3),
(6, 1),
(6, 3),
(7, 1),
(7, 2),
(8, 1),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(10, 1),
(10, 3),
(10, 4),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(12, 3),
(13, 1),
(13, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `status`) VALUES
(1, 'jhon', 'riascoslabradajhonedinson@gmail.com', 'user', NULL, '$2y$12$PHDVEMyItipHBVfnANyZUuObg5LS6JWj2n2T.HYH1f78BPSCLx/9K', NULL, '2025-10-06 07:44:57', '2025-10-06 07:44:57', 'activo'),
(4, 'Administrador', 'admin@vetapp.local', 'admin', NULL, '$2y$12$gCquaBd5Vaf8CPwW.r4kpOQL/aUDPGGdNy78N/e2/rnAS6/IX3I3u', NULL, '2025-10-20 09:19:38', '2025-10-20 09:19:38', 'activo'),
(5, 'veterinario1', 'veterinario1@gmail.com', 'veterinario', NULL, '$2y$12$jvr/yH4Jzb/W80vyAr5jA./kloQfBggZNiDxQ9slkBa73c2.iiwea', NULL, '2025-10-21 08:49:29', '2025-10-21 08:49:29', 'activo'),
(6, 'recepcionista1', 'recepcionista1@gmail.com', 'recepcionista', NULL, '$2y$12$0qtSd9sniWlnQ85nzX7uN.q5xv4o6jHEOp3YaV3kSDaC6aKwDtrye', NULL, '2025-10-22 06:04:12', '2025-10-22 06:04:12', 'activo'),
(8, 'juan', 'juan@gmail.com', 'user', NULL, '$2y$12$.gVObtkqzi/JqZO2Mu3zgeLO6BYWEzaX7k5Edert9xRqs0Pu9nage', NULL, '2025-10-23 03:06:18', '2025-10-23 03:06:18', 'activo');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_mascota_id_foreign` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `citas_vet_id_foreign` FOREIGN KEY (`vet_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_historia_id_foreign` FOREIGN KEY (`historia_id`) REFERENCES `historias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `facturas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `factura_items`
--
ALTER TABLE `factura_items`
  ADD CONSTRAINT `factura_items_factura_id_foreign` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `recetas_historia_id_foreign` FOREIGN KEY (`historia_id`) REFERENCES `historias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recetas_mascota_id_foreign` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recetas_vet_id_foreign` FOREIGN KEY (`vet_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `receta_items`
--
ALTER TABLE `receta_items`
  ADD CONSTRAINT `receta_items_receta_id_foreign` FOREIGN KEY (`receta_id`) REFERENCES `recetas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
