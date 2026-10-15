-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-08-2026 a las 15:30:51
-- Versión del servidor: 10.11.16-MariaDB
-- Versión de PHP: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `capacit7_plataforma`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `advisors`
--

CREATE TABLE `advisors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `advisors`
--

INSERT INTO `advisors` (`id`, `name`, `position`, `phone`, `avatar_url`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Karla', 'Asesor Académico', '+51965244780', 'https://ui-avatars.com/api/?name=Asesor&background=213A57&color=fff', 1, '2026-05-13 10:39:34', '2026-06-17 04:20:20', 1),
(2, 'Yuri', 'Ventas', '+51944508218', 'https://ui-avatars.com/api/?name=Asesor&background=213A57&color=fff', 1, '2026-05-13 10:41:06', '2026-06-17 04:19:52', 1),
(3, 'Paola', 'Ventas', '+51972539712', 'https://ui-avatars.com/api/?name=Asesor&background=213A57&color=fff', 1, '2026-05-17 03:54:44', '2026-06-17 04:20:06', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `attended_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `attendances`
--

INSERT INTO `attendances` (`id`, `student_id`, `course_id`, `topic_id`, `answers`, `attended_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 141, '[\"asda\",\"dfgdfg\",\"Regular\"]', '2025-12-24 21:01:23', '2025-12-24 21:01:23', '2025-12-24 21:01:23'),
(2, 2, 1, 142, '[\"dfgdfg\",\"dfgfgh\",\"Bueno\"]', '2025-12-24 21:08:22', '2025-12-24 21:08:22', '2025-12-24 21:08:22'),
(3, 2, 1, 143, '[\"ghjghj\",\"ghj\",\"Regular\"]', '2025-12-24 21:10:31', '2025-12-24 21:10:31', '2025-12-24 21:10:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attendance_records`
--

CREATE TABLE `attendance_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `student_dni` varchar(255) NOT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `backup_logs`
--

CREATE TABLE `backup_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `records_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `backup_logs`
--

INSERT INTO `backup_logs` (`id`, `filename`, `path`, `size`, `records_count`, `created_at`, `updated_at`) VALUES
(11, 'backup_2026-07-21_23-38-49.xls', 'backups/backup_2026-07-21_23-38-49.xls', '0 B', 74, '2026-07-22 03:38:49', '2026-07-22 03:38:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_type` varchar(255) NOT NULL DEFAULT 'both'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `status`, `created_at`, `updated_at`, `user_id`, `target_type`) VALUES
(1, 'AGROPECUARIA', 'agropecuaria', NULL, 'ph-leaf', 1, '2025-12-24 14:42:48', '2026-06-28 07:41:27', 1, 'course'),
(2, 'DERECHO', 'derecho', NULL, 'ph-scales', 0, '2025-12-24 14:43:03', '2026-06-28 07:42:48', 1, 'course'),
(3, 'INDUSTRIAS ALIMENTARIAS', 'industrias-alimentarias', NULL, 'ph-buildings', 0, '2025-12-24 14:43:11', '2026-06-28 07:44:02', 1, 'course'),
(4, 'MEDIO AMBIENTE', 'medio-ambiente', 'Área para ingenieros ambientales y sobre todo consultores ambientales.', 'ph-leaf', 1, '2025-12-24 14:43:17', '2026-06-28 07:49:15', 1, 'course'),
(6, 'PSICOLOGÍA', 'psicologia', NULL, 'ph-heartbeat', 1, '2025-12-24 14:43:31', '2026-06-28 07:45:59', 1, 'course'),
(7, 'Especialidad', 'especialidad', NULL, 'ph-shield-check', 1, '2026-05-17 05:14:24', '2026-06-28 07:43:13', 1, 'course'),
(8, 'EDUCACIÓN', 'educacion', NULL, 'ph-graduation-cap', 1, '2026-06-16 07:30:18', '2026-06-28 07:42:59', 1, 'course'),
(9, 'CONTABILIDAD Y FINANZAS', 'contabilidad-y-finanzas', NULL, 'ph-chart-line', 0, '2026-06-16 07:39:55', '2026-06-28 07:42:30', 1, 'course'),
(10, 'IDIOMAS', 'idiomas', NULL, 'ph-globe', 0, '2026-06-17 02:36:49', '2026-06-28 07:43:37', 1, 'course'),
(11, 'CIENCIAS DE LA SALUD', 'ciencias-de-la-salud', NULL, 'ph-first-aid', 1, '2026-06-17 02:39:57', '2026-06-28 07:42:17', 1, 'course'),
(12, 'INGENIERÍA  CIVIL', 'ingenieria-civil', NULL, 'ph-hard-hat', 0, '2026-06-17 02:40:26', '2026-06-28 07:45:06', 1, 'course'),
(13, 'INFORMÁTICA   E INTELIGENCIA ARTIFICIAL', 'informatica-e-inteligencia-artificial', NULL, 'ph-desktop', 0, '2026-06-17 02:49:21', '2026-06-28 07:44:46', 1, 'course'),
(14, 'INGENIERÍA  AMBIENTAL', 'ingenieria-ambiental', NULL, 'ph-leaf', 0, '2026-06-17 02:51:44', '2026-06-28 07:44:55', 1, 'course'),
(15, 'ADMINISTRACIÓN Y GESTIÓN PÚBLICA', 'administracion-y-gestion-publica', NULL, 'ph-buildings', 0, '2026-06-17 02:53:15', '2026-06-28 07:41:11', 1, 'course'),
(16, 'MEDICINA VETERINARIA', 'medicina-veterinaria', NULL, 'ph-first-aid', 0, '2026-06-17 02:58:46', '2026-06-28 07:45:28', 1, 'course'),
(17, 'Fundamentos básicos del derecho administrativo PAD - PAS', 'fundamentos-basicos-del-derecho-administrativo-pad-pas', NULL, 'ph-buildings', 1, '2026-08-06 19:07:07', '2026-08-06 19:07:07', 4, 'course');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category_course`
--

CREATE TABLE `category_course` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `category_course`
--

INSERT INTO `category_course` (`id`, `course_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 4, NULL, NULL),
(3, 6, 2, NULL, NULL),
(4, 7, 6, NULL, NULL),
(6, 9, 8, NULL, NULL),
(7, 10, 8, NULL, NULL),
(8, 11, 8, NULL, NULL),
(9, 12, 8, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `download_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `certificates`
--

INSERT INTO `certificates` (`id`, `uuid`, `student_id`, `course_id`, `issued_at`, `download_url`, `created_at`, `updated_at`) VALUES
(1, 'HWPX-2026-D9S7', 2, 1, '2026-05-16 09:37:08', NULL, '2026-05-14 08:32:37', '2026-05-14 08:32:37'),
(2, '8AUF-2026-C6YL', 11, 7, '2026-06-01 02:10:56', NULL, '2026-06-01 02:10:56', '2026-06-01 02:10:56'),
(3, 'WMWU-2026-STDD', 12, 7, '2026-06-01 02:22:34', NULL, '2026-06-01 02:22:34', '2026-06-01 02:22:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `claims`
--

CREATE TABLE `claims` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `issue_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'course',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duration` int(11) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `benefits` text DEFAULT NULL,
  `learning_outcomes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`learning_outcomes`)),
  `discount_price` decimal(10,2) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level` varchar(255) DEFAULT 'Básico',
  `category` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `evaluation_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `certificate_template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `attendance_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `resources` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`resources`)),
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `individual_certificate_id` bigint(20) UNSIGNED DEFAULT NULL,
  `course_certificate_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`id`, `type`, `title`, `slug`, `description`, `short_description`, `price`, `duration`, `metadata`, `benefits`, `learning_outcomes`, `discount_price`, `cover_image`, `instructor_id`, `level`, `category`, `status`, `featured`, `evaluation_config`, `certificate_template`, `attendance_config`, `created_at`, `updated_at`, `resources`, `user_id`, `individual_certificate_id`, `course_certificate_id`) VALUES
(1, 'course', 'Crianza y Manejo de Gallinas', 'curso-694a930fb1834', 'COMPETENCIAS A OBTENER\r\n\r\nAl finalizar el curso, los participantes serán capaces de seleccionar adecuadamente gallinas ponedoras según criterios productivos, implementar prácticas efectivas para la recepción y manejo inicial de pollitos, establecer condiciones óptimas de espacio, alimentación y ambiente para las aves, prevenir y controlar plagas y enfermedades comunes, así como evaluar los costos de producción y aplicar principios de sostenibilidad en la crianza.', NULL, 207.00, 45, '{\"start_date\":\"2026-05-14\",\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null,\"registration_number\":\"100\",\"end_date\":\"2026-05-16\"}', NULL, NULL, NULL, 'storage/courses/iiIdY8QabStIQBqBe2fFj9jRmxzulG7EuS9xlFO1.webp', 1, 'Intermedio', 'Seguridad', 'published', 0, '{\"min_score\":\"51\",\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":true,\"final_exam_data\":{\"start_date\":\"2025-12-24T13:33\",\"end_date\":\"2025-12-26T13:33\",\"instructions\":\"<h1>Holaa<\\/h1><p>como esas<\\/p>\",\"questions\":[{\"type\":\"single\",\"text\":\"\",\"points\":\"40\",\"options\":\"a,b,c\",\"correct\":null},{\"type\":\"single\",\"text\":\"\",\"points\":\"40\",\"options\":\"a,b,c\",\"correct\":\"b\"}]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"2025-12-25T13:47\",\"end_date\":\"2025-12-26T13:47\",\"instructions\":\"<p>qweert<\\/p>\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[\"docx\",\"pdf\",\"images\"]}}', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Mvn2PiW8nqY9hXmXk2INfUupb1eSFWvhxlxiqmRN.jpg\",\"elements\":[{\"id\":\"el_1778731339587\",\"type\":\"student_name\",\"x\":215.3678847262248154947883449494838714599609375,\"y\":249.8370461095100836246274411678314208984375,\"style\":{\"fontSize\":\"30px\",\"color\":\"#000000\",\"fontFamily\":\"font-lato\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"690px\"},\"content\":[]},{\"id\":\"el_1778731557969\",\"type\":\"qr\",\"x\":898.23000000000001818989403545856475830078125,\"y\":518.451999999999998181010596454143524169921875,\"style\":{\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontFamily\":\"font-lato\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"160px\",\"height\":\"160px\"},\"content\":[]},{\"id\":\"el_1779426674813\",\"type\":\"student_name\",\"x\":246.66699999999997316990629769861698150634765625,\"y\":250,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"625.3333333333334px\",\"height\":\"34.666666666666664px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779426685678\",\"type\":\"course_title\",\"x\":418.3333333333333712289459072053432464599609375,\"y\":300,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"Crianza y Manejo de Gallinas\"},{\"id\":\"el_1779426700862\",\"type\":\"certificate_body\",\"x\":203.333333333333342807236476801335811614990234375,\"y\":350,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"725px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Realizado el <b>14 de mayo del 2026<\\/b> al <b>16 de mayo del 2026<\\/b>, con una duraci\\u00f3n de <b>45 horas acad\\u00e9micas<\\/b>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779426708014\",\"type\":\"issue_date\",\"x\":360,\"y\":493.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 16 de mayo del 2026<\\/p>\"},{\"id\":\"el_1779426736815\",\"type\":\"executive_signature\",\"x\":264.99966666666665560114779509603977203369140625,\"y\":566.6670000000000300133251585066318511962890625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"200px\"},\"content\":{\"name\":\"Director Ejecutivo\",\"role\":\"DIRECTOR EJECUTIVO\",\"size\":80,\"nameStyle\":{\"fontSize\":\"14px\",\"color\":\"#000000\",\"fontWeight\":\"bold\"},\"roleStyle\":{\"fontSize\":\"10px\",\"color\":\"#666666\"}}},{\"id\":\"el_1779426740471\",\"type\":\"academic_signature\",\"x\":585,\"y\":568.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"200px\"},\"content\":{\"name\":\"Director Acad\\u00e9mico\",\"role\":\"DIRECTOR ACAD\\u00c9MICO\",\"size\":80,\"nameStyle\":{\"fontSize\":\"14px\",\"color\":\"#000000\",\"fontWeight\":\"bold\"},\"roleStyle\":{\"fontSize\":\"10px\",\"color\":\"#666666\"}}},{\"id\":\"el_1779426759111\",\"type\":\"registration_number\",\"x\":913.333666666666658784379251301288604736328125,\"y\":463.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"118.33333333333331px\",\"height\":\"20px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/QUWRIj5KOKDKwj7RHzy9uJsTAjJ2we2YeDUVxzxY.jpg\",\"elements2\":[{\"id\":\"el_1779426779832\",\"type\":\"qr\",\"x\":896.66733333333331756875850260257720947265625,\"y\":514.99966666666659875772893428802490234375,\"style\":{\"width\":\"161.66666666666669px\",\"height\":\"161.66666666666669px\"},\"content\":\"\"}]}', '{\"scope\":\"all\",\"questions\":[{\"type\":\"text\",\"title\":\"Como evaluas al docente\",\"options\":null},{\"type\":\"text\",\"title\":\"Que te parecio la sesion de zoom\",\"options\":null},{\"type\":\"choice\",\"title\":\"Que te parecio el curso\",\"options\":\"Bueno, Regular, Malo\"}],\"score\":20}', '2025-12-23 13:03:11', '2026-07-02 08:29:56', '{\"materials\":null,\"presentations\":null}', 1, NULL, 3),
(2, 'course', 'Licenciamiento Ambiental para Edificios', 'licenciamiento-ambiental-para-edificios-694fcdc843311', '	Normativa ambiental y Resolución Secretarial\r\n	Planilla del Manifiesto Ambiental e identificación de las actividades \r\n	Flujograma de procesos según tipo de actividad\r\n	Identificación de impactos ambientales\r\n	Plan de adecuación ambiental\r\n	Programa de aplicación y seguimiento ambiental\r\n	Plan de contingencias \r\n	Plan de cierre y abandono\r\n	Casos de estudio para distintas actividades\r\n	Elaboración de cotizaciones y contratos', 'El curso dota al profesional de las competencias necesarias para obtener la Licencia Ambiental (LA) de actividades urbanas de alto impacto. Los participantes logran un dominio técnico-legal para elaborar el Manifiesto Ambiental (MA), planificar la corrección mediante el PAA-PASA, gestionar riesgos (Plan de contingencias) y el plan de cierre. El enfoque práctico incluye el uso de MA’s editables, habilidades de negocio (cotizaciones/contratos), y una certificación de 30 horas.', 200.00, NULL, '{\"start_date\":null,\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', NULL, NULL, NULL, 'storage/courses/tOAXeHFr4xzn6vUN3JmfJFXqpZ5dOYUvwGvjnD01.jpg', 1, 'Intermedio', NULL, 'published', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":true,\"final_exam_data\":{\"start_date\":\"2025-12-30T08:18\",\"end_date\":\"2025-12-31T08:18\",\"instructions\":\"\",\"questions\":[{\"type\":\"single\",\"text\":\"\\u00bfQue categor\\u00eda de RENCA puede elaborar los MA?\",\"points\":\"100\",\"options\":\"A,B,C,D\",\"correct\":null}]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', '{\"elements\":[{\"id\":\"el_1778728907823\",\"type\":\"image\",\"x\":133.75,\"y\":123.74999999999997,\"style\":{\"width\":\"150px\",\"height\":\"150px\"},\"src\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/Mvn2PiW8nqY9hXmXk2INfUupb1eSFWvhxlxiqmRN.jpg\",\"content\":[]},{\"id\":\"el_1778728920999\",\"type\":\"student_name\",\"x\":186.25,\"y\":325,\"style\":{\"fontSize\":\"30px\",\"color\":\"#000000\",\"fontFamily\":\"font-lato\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":[]}]}', '{\"scope\":\"all\",\"questions\":[{\"type\":\"choice\",\"title\":\"COmo estuvo la clase\",\"options\":\"Mala, Buena, Regular, P\\u00e9sima\"}]}', '2025-12-27 12:15:04', '2026-06-17 05:04:39', '{\"materials\":null,\"presentations\":null}', 1, NULL, NULL),
(3, 'course', 'CONTROL DE BROTES DE VECTORES Y RESERVORIOS DE LEPTOSPIROSIS, DENGUE Y MALARIA', 'control-de-brotes-de-vectores-y-reservorios-de-leptospirosis-dengue-y-malaria-6a0b3234748d1', NULL, NULL, 0.00, NULL, '{\"start_date\":null,\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', NULL, NULL, NULL, NULL, NULL, 'Básico', NULL, 'draft', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":false,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', NULL, '{\"scope\":\"all\",\"questions\":[]}', '2026-05-18 19:37:24', '2026-06-17 05:04:39', '{\"materials\":null,\"presentations\":null}', 1, NULL, NULL),
(6, 'diploma', 'Diplomado Ganaderia', 'diplomado-ganaderia-6a111cefe6d3e', NULL, NULL, 1500.00, 150, '{\"registration_number\":\"100\",\"start_date\":\"2026-05-27\",\"end_date\":\"2026-06-29\",\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', NULL, NULL, NULL, NULL, 1, 'Básico', NULL, 'published', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":false,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', NULL, '{\"scope\":\"all\",\"questions\":[]}', '2026-05-23 07:20:15', '2026-06-17 05:04:39', '{\"materials\":null,\"presentations\":null}', 1, NULL, NULL),
(7, 'course', 'Especialista en Seguridad Industrial', 'especialisat-en-seguridad-industrial-6a1ca5387ec12', NULL, NULL, 150.00, 80, '{\"registration_number\":\"101\",\"start_date\":\"2026-06-01\",\"end_date\":\"2026-06-03\",\"modality\":\"Virtual\",\"planning\":\"<p>Nota del cuso<\\/p>\",\"whatsapp_group\":null}', NULL, NULL, NULL, 'storage/courses/EiXmvJqmYrmRv3SLZUrkGC8g559bw4jDCLD95wRj.jpg', 2, 'Básico', NULL, 'published', 0, '{\"min_score\":51,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":true,\"final_exam_data\":{\"start_date\":\"2026-05-31T17:00\",\"end_date\":\"2026-05-31T19:00\",\"instructions\":\"<p>No copiar<\\/p>\",\"questions\":[{\"type\":\"single\",\"text\":\"Cuanto es 2+2\",\"points\":\"50\",\"options\":\"2,5,4\",\"correct\":\"4\"},{\"type\":\"single\",\"text\":\"De que color es el Sol\",\"points\":\"30\",\"options\":\"Azul, Rojo, Verde, Amarillo\",\"correct\":\"Amarillo\"},{\"type\":\"single\",\"text\":\"Cuantas esquinas tiene un trinagulo\",\"points\":\"20\",\"options\":\"4,6,3\",\"correct\":\"3\"}]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', '{\"pages\":1,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/CP5uYEaRhpnvgPZDRVxpQafxKFiOHZ8XIDDTu5ZQ.png\",\"elements\":[{\"id\":\"el_1780262850134\",\"type\":\"student_name\",\"x\":380.00033333333334439885220490396022796630859375,\"y\":338.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"637px\",\"height\":\"29.666666666666668px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780262859960\",\"type\":\"course_title\",\"x\":490,\"y\":411.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#284c98\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>Especialista en Seguridad Industrial<\\/p>\"},{\"id\":\"el_1780262876407\",\"type\":\"certificate_body\",\"x\":396.66666666666668561447295360267162322998046875,\"y\":453.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"628.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"96.66666666666666px\"},\"content\":\"<p>Realizado el <strong>1 de junio del 2026<\\/strong> al <strong>3 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>80 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780262888696\",\"type\":\"issue_date\",\"x\":498.33300000000002683009370230138301849365234375,\"y\":573.333666666666658784379251301288604736328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 3 de junio del 2026<\\/p>\"},{\"id\":\"el_1780262900087\",\"type\":\"qr\",\"x\":145,\"y\":618.3333333333333712289459072053432464599609375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1780262905663\",\"type\":\"registration_number\",\"x\":78.3333333333333285963817615993320941925048828125,\"y\":588.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#ffffff\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"250px\",\"height\":\"20px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":null,\"elements2\":[]}', '{\"scope\":\"all\",\"questions\":[{\"type\":\"choice\",\"title\":\"\\u00bfTe gusto el curso?\",\"options\":\"Si, No\"},{\"type\":\"choice\",\"title\":\"Caunto calificas al instructor\",\"options\":\"Bueno, Regular, Malo\"}]}', '2026-06-01 01:16:40', '2026-07-02 08:38:51', '{\"materials\":null,\"presentations\":null}', 1, NULL, 4),
(8, 'diploma', 'FORMULACIÓN, EJECUCIÓN Y LIQUIDACIÓN EN PROYECTOS AGROPECUARIOS Y FORESTALES EN EL MARCO DE INVIERTE.PE', 'formulacion-ejecucion-y-liquidacion-en-proyectos-agropecuarios-y-forestales-en-el-marco-de-inviertepe-6a20369a8926b', NULL, NULL, 0.00, 200, '{\"registration_number\":\"103\",\"start_date\":\"2026-03-12\",\"end_date\":\"2026-05-29\",\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', NULL, NULL, NULL, 'storage/courses/4EiTRihbmewok1JDXFzvC2s5F1zptAxeJ9WvmyIk.png', NULL, 'Básico', NULL, 'draft', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":false,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', NULL, '{\"scope\":\"all\",\"questions\":[]}', '2026-06-03 18:13:46', '2026-06-17 05:04:39', '{\"materials\":null,\"presentations\":null}', 1, NULL, NULL),
(9, 'course', 'PSICOMOTRICIDAD EDUCATIVA', 'psicomotricidad-educativa-6a30b128aac7b', 'El curso de Psicomotricidad Educativa de Capacita Perú está diseñado para brindar a los profesionales de la educación y la salud las competencias necesarias para entender y abordar el desarrollo integral del niño. A través del movimiento, el cuerpo y el juego, la psicomotricidad permite a los niños construir su identidad, relacionarse con su entorno y mejorar su aprendizaje. En este programa exploraremos desde los fundamentos teóricos hasta la planificación y ejecución de sesiones prácticas en el aula, convirtiendo el espacio educativo en un entorno enriquecedor y estimulante.', 'Aprende a potenciar el desarrollo cognitivo, emocional y motor de los niños a través del movimiento y el juego. Este curso te brindará las herramientas teóricas y prácticas necesarias para diseñar y aplicar sesiones de psicomotricidad en el ámbito educativo.', 60.00, 90, '{\"registration_number\":null,\"start_date\":null,\"end_date\":null,\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', '<p>- &nbsp;Comprender los conceptos fundamentales de la psicomotricidad y su importancia en las diferentes etapas del desarrollo infantil.</p><p>- &nbsp;Identificar trastornos o dificultades en el desarrollo psicomotor de los niños.</p><p>&nbsp;- Diseñar, planificar y dirigir sesiones de psicomotricidad adaptadas al entorno escolar.</p><p>&nbsp;- Aplicar el juego como herramienta pedagógica y terapéutica.</p>', NULL, 45.00, 'storage/courses/MH736jWWINJHVG6wBO5dj6QCb1FyiQTpbBzOQlEf.png', 4, 'Intermedio', NULL, 'published', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":false,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', '{\"pages\":2,\"background\":null,\"elements\":[],\"background2\":null,\"elements2\":[]}', '{\"scope\":\"all\",\"questions\":[]}', '2026-06-16 06:12:56', '2026-07-02 20:54:48', '{\"materials\":\"https:\\/\\/www.youtube.com\\/watch?v=zNasODXeWcA\",\"presentations\":null}', 1, NULL, 6),
(10, 'diploma', 'Estimulación del Lenguaje y Psicomotricidad Infantil', 'estimulacion-del-lenguaje-y-psicomotricidad-infantil-6a31d984dd282', 'Este diplomado de especialización está diseñado para dotar a docentes de educación inicial, psicólogos y terapeutas de herramientas prácticas y fundamentadas en la neurociencia. Liderado por la destacada investigadora y docente, la Dra. Gloria Cunia Huamán, el programa cierra la brecha entre la teoría clínica y la realidad del aula.\r\n\r\nA lo largo de 6 meses de estudio, comprenderás los hitos neurobiológicos del desarrollo infantil y aprenderás a diseñar programas didácticos efectivos para destrabar problemas de lenguaje. Además, descubrirás cómo utilizar la psicomotricidad y los juegos tradicionales como motor del aprendizaje cognitivo y pre-escritura.', 'Domina estrategias clínicas y pedagógicas para potenciar la expresión oral y el desarrollo psicomotriz en la primera infancia. Aprende a detectar rezagos y aplica programas de intervención temprana basados en evidencia científica y juegos tradicionales.', 500.00, 384, '{\"registration_number\":\"1000\",\"start_date\":\"2026-07-16\",\"end_date\":\"2026-10-16\",\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":\"https:\\/\\/chat.whatsapp.com\\/F0yeJeUErTDEQy6twfyjLf\"}', '<p><strong>Módulo 1:</strong> Hitos neurobiológicos de la expresión oral y el movimiento (0 a 6 años).</p><p><strong>Módulo 2:</strong> Detección temprana de rezagos y trastornos del desarrollo del lenguaje.</p><p><strong>Módulo 3:</strong> Diseño de programas didácticos para destrabar la expresión oral.</p><p><strong>Módulo 4:</strong> La psicomotricidad como motor del aprendizaje cognitivo y pre-escritura.</p><p><strong>Módulo 5:</strong> Juegos tradicionales como intervención motriz y vincular.</p><p><strong>Módulo 6:</strong> Elaboración de material lúdico-terapéutico de bajo costo</p>', NULL, 485.00, 'storage/courses/b04ks8fXpCR44n81Ul8AtU1hvhc8EA3fxqK9pCMT.png', 4, 'Intermedio', NULL, 'published', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":true,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[{\"type\":\"single\",\"text\":\"DFNDKJFDSFJ\",\"points\":10,\"options\":\"A) DFG, B) JDSJDLKS, C) KSKD,S, D) NSJS\",\"correct\":null},{\"type\":\"single\",\"text\":\"FKDSNFKFN\",\"points\":10,\"options\":\"A)DJFN, B)SFJ, C) FDJFB, D)DKVD\",\"correct\":null}]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', '{\"pages\":1,\"background\":null,\"elements\":[{\"id\":\"el_1781653961089\",\"type\":\"image\",\"x\":11.666669999999999873807610129006206989288330078125,\"y\":-38.33333333333333570180911920033395290374755859375,\"style\":{\"width\":\"1072px\",\"height\":\"803.3333333333334px\"},\"content\":\"\",\"src\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/7XmB08ra2Ofcrd917je04YHh0JkZmvtWrCpStH7p.png\"}],\"background2\":null,\"elements2\":[]}', '{\"scope\":\"all\",\"questions\":[]}', '2026-06-17 03:17:24', '2026-06-17 05:05:18', '{\"materials\":\"https:\\/\\/drive.google.com\\/drive\\/u\\/5\\/folders\\/1soFEphASiOxJkKS3RtmmurNOzfJw_uXq\",\"presentations\":\"https:\\/\\/drive.google.com\\/drive\\/u\\/5\\/folders\\/1soFEphASiOxJkKS3RtmmurNOzfJw_uXq\"}', 1, NULL, NULL),
(11, 'course', 'INTELIGENCIA ARTIFICIAL PARA DOCENTES', 'inteligencia-artificial-para-docentes-6a3308e0df845', 'La educación está evolucionando y la Inteligencia Artificial es la principal aliada del docente moderno. En este curso, de la mano de la Dra. Gloria Cunia Huaman, aprenderás a utilizar herramientas de IA para automatizar la planificación de clases, crear recursos didácticos innovadores y personalizar la enseñanza.\r\n\r\nEs un evento ideal para docentes, directivos y estudiantes de educación que buscan actualizarse. Tu inscripción con opción a certificación incluye el acceso al material de estudio exclusivo y un certificado con respaldo por 90 horas académicas, verificable mediante código QR en el portal oficial de Capacita Perú.', 'Curso 100% online diseñado para que los educadores aprendan a integrar herramientas de Inteligencia Artificial en sus clases, optimizando su tiempo y potenciando el aprendizaje de sus estudiantes.', 60.00, NULL, '{\"registration_number\":null,\"start_date\":null,\"end_date\":null,\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', '<ul><li>Material de estudio exclusivo de la clase</li><li>Certificación con validez oficial</li><li>Documento con Código QR y Registros Únicos verificables</li></ul>', NULL, NULL, 'storage/courses/aoFRtzsJQ5kdwFOu3cdb9LkEsXBKq5v1O3Pv5CBR.png', 4, 'Intermedio', NULL, 'published', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":false,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', NULL, '{\"scope\":\"all\",\"questions\":[]}', '2026-06-18 00:51:44', '2026-06-18 00:51:44', '{\"materials\":null,\"presentations\":null}', 1, NULL, NULL),
(12, 'course', 'MAESTRA SOMBRA', 'maestra-sombra-6a330c0554572', 'Descubre la importancia y las funciones clave de la \"Maestra Sombra\" (o acompañante terapéutico/educativo) en el entorno escolar. En este curso gratuito y 100% en vivo, aprenderás de la mano de la Dra. Gloria Cunia Huaman las herramientas y estrategias necesarias para facilitar la inclusión, el aprendizaje y la autonomía de niños con necesidades educativas especiales dentro del aula regular.', 'Curso gratuito y 100% online diseñado para brindar estrategias sobre el rol de la Maestra Sombra en la educación inclusiva. Impartido en vivo por la Dra. Gloria Cunia Huaman.', 60.00, NULL, '{\"registration_number\":null,\"start_date\":null,\"end_date\":\"2026-06-19\",\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', '<ul><li>Acceso a la transmisión en VIVO</li><li>Material de estudio complementario</li><li>Opción a certificación inmediata y con validez (con código QR)</li></ul>', NULL, NULL, 'storage/courses/pTRrzE3GAsWjgHkTqNTPx5fS8zfXzAb2gfefQvrV.png', 4, 'Intermedio', NULL, 'published', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":false,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', '{\"pages\":1,\"background\":null,\"elements\":[],\"background2\":null,\"elements2\":[]}', '{\"scope\":\"all\",\"questions\":[]}', '2026-06-18 01:05:09', '2026-06-18 01:06:04', '{\"materials\":null,\"presentations\":null}', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `course_certificates`
--

CREATE TABLE `course_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `registration_number` varchar(255) DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `certificate_template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certificate_template`)),
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `individual_certificate_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `course_certificates`
--

INSERT INTO `course_certificates` (`id`, `uuid`, `course_id`, `instructor_id`, `title`, `registration_number`, `hours`, `start_date`, `end_date`, `certificate_template`, `status`, `user_id`, `created_at`, `updated_at`, `individual_certificate_id`) VALUES
(3, 'CP-DOC-2026-JNU6', 1, 1, 'Crianza y Manejo de Gallinas', NULL, 45, '2026-05-14', '2026-05-16', NULL, 'published', 1, '2026-07-02 08:29:55', '2026-07-02 08:29:55', NULL),
(4, 'CP-DOC-2026-X3WK', 7, 2, 'Especialista en Seguridad Industrial', NULL, 80, '2026-06-01', '2026-06-03', '{\"pages\":1,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/7XmB08ra2Ofcrd917je04YHh0JkZmvtWrCpStH7p.png\",\"elements\":[{\"id\":\"el_1782967136616\",\"type\":\"student_name\",\"x\":598.3333333333333712289459072053432464599609375,\"y\":335,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"Gonzalo Galvez\"},{\"id\":\"el_1782967144024\",\"type\":\"course_title\",\"x\":508.33333333333331438552704639732837677001953125,\"y\":404.9996666666667124445666559040546417236328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>Especialista en Seguridad Industrial<\\/p>\"},{\"id\":\"el_1782967147600\",\"type\":\"certificate_body\",\"x\":383.333666666666658784379251301288604736328125,\"y\":441.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"648.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"103.33333333333333px\"},\"content\":\"<p>Realizado el <strong>1 de junio del 2026<\\/strong> al <strong>3 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>80 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1782967154400\",\"type\":\"issue_date\",\"x\":466.66666666666668561447295360267162322998046875,\"y\":570,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 3 de junio del 2026<\\/p>\"},{\"id\":\"el_1782967159088\",\"type\":\"qr\",\"x\":155,\"y\":590,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1782967164049\",\"type\":\"registration_number\",\"x\":60,\"y\":558.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#ffffff\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 CP-2024-001<\\/p>\"}],\"background2\":null,\"elements2\":[]}', 'published', 1, '2026-07-02 08:38:51', '2026-07-02 08:39:50', NULL),
(5, 'CP-DOC-2026-PEJK', NULL, 1, 'IMPLEMENTACIÓN DE PLAN HCCP, BPM, PHS Y POES', NULL, 90, '2026-05-26', '2026-06-26', '{\"pages\":1,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/7XmB08ra2Ofcrd917je04YHh0JkZmvtWrCpStH7p.png\",\"elements\":[{\"id\":\"el_1782969391169\",\"type\":\"student_name\",\"x\":603.333666666666658784379251301288604736328125,\"y\":326.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"Ing. Juan Perez\"},{\"id\":\"el_1782969395416\",\"type\":\"course_title\",\"x\":386.66699999999997316990629769861698150634765625,\"y\":406.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>IMPLEMENTACI\\u00d3N DE PLAN HCCP, BPM, PHS Y POES<\\/p>\"}],\"background2\":null,\"elements2\":[]}', 'published', 1, '2026-07-02 09:11:21', '2026-07-02 09:17:29', 23),
(6, 'CP-DOC-2026-KZCO', 9, 4, 'PSICOMOTRICIDAD EDUCATIVA', NULL, 90, NULL, NULL, '{\"pages\":1,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/5lxal5kpGGeDRERirWE2AnpkYQQXo00GZUpJ9W2e.png\",\"elements\":[{\"id\":\"el_1783011117693\",\"type\":\"student_name\",\"x\":485,\"y\":320,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"DRA. GLORIA CUNIA HUAMAN\"},{\"id\":\"el_1783011127364\",\"type\":\"certificate_body\",\"x\":298.3333333333333712289459072053432464599609375,\"y\":435,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"725px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Realizado el <strong>11 de octubre del 2025<\\/strong> al <strong>03 de noviembre del 2025<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se esmite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1783011141340\",\"type\":\"issue_date\",\"x\":636.666666666666742457891814410686492919921875,\"y\":555,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 03 de noviembre del 2025<\\/p>\"},{\"id\":\"el_1783011146837\",\"type\":\"qr\",\"x\":123.333333333333342807236476801335811614990234375,\"y\":590,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}],\"background2\":null,\"elements2\":[]}', 'published', 1, '2026-07-02 20:54:48', '2026-07-02 20:55:42', NULL),
(8, 'CP-DOC-2026-Z2NQ', NULL, 5, 'TAMIZAJE NEONATAL', NULL, 90, '2026-07-02', '2026-08-05', '{\"pages\":1,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/ZqqRnp5Jco9RDXziooiVC3fshmidluO9CvqqBMTO.png\",\"elements\":[{\"id\":\"el_1786044196339\",\"type\":\"student_name\",\"x\":488.3333333333333712289459072053432464599609375,\"y\":318.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"CLARINIZ QUISPE NAJARRO\"},{\"id\":\"el_1786044202598\",\"type\":\"qr\",\"x\":125,\"y\":586.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}],\"background2\":null,\"elements2\":[]}', 'published', 1, '2026-08-06 23:23:00', '2026-08-06 23:23:37', 26);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `course_lessons`
--

CREATE TABLE `course_lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `type` enum('video','text','quiz','zoom','assignment') NOT NULL DEFAULT 'video',
  `content` text DEFAULT NULL,
  `video_source` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 0,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `is_preview` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `course_lessons`
--

INSERT INTO `course_lessons` (`id`, `course_id`, `topic_id`, `title`, `slug`, `type`, `content`, `video_source`, `video_url`, `duration_minutes`, `attachments`, `order_index`, `is_preview`, `created_at`, `updated_at`) VALUES
(199, 1, 147, 'abc123', 'abc123-694c5cabb5a18', 'video', '<h1><br></h1>', 'video_first', 'https://www.youtube.com/watch?v=3khYC-UOogE', 60, NULL, 0, 0, '2025-12-24 21:35:39', '2025-12-24 21:35:39'),
(200, 1, 147, 'Evaluación', 'evaluacion-694c5cabede9e', 'quiz', '', 'video_first', '', 0, '{\"questions\":[{\"type\":\"single\",\"type_label\":\"Selecci\\u00f3n \\u00danica\",\"title\":\"qwe\",\"points\":10,\"options\":\"a, b, d, e\",\"correct\":\"b\"}],\"start_date\":\"\",\"end_date\":\"\"}', 1, 0, '2025-12-24 21:35:39', '2025-12-24 21:35:39'),
(201, 1, 148, 'Sesión 2', 'sesion-2-694c5cac68dde', 'video', '<h1><strong>Hola</strong></h1><p>Hola123</p>', 'video_first', 'https://www.youtube.com/watch?v=bXhEiVNg_Nk', 60, NULL, 0, 0, '2025-12-24 21:35:40', '2025-12-24 21:35:40'),
(202, 1, 149, 'Sesión 33', 'sesion-33-694c5cacda4cc', 'video', NULL, 'video_first', 'sdsd', 60, NULL, 0, 0, '2025-12-24 21:35:40', '2025-12-24 21:35:40'),
(203, 1, 149, 'Evaluación', 'evaluacion-694c5cad22563', 'quiz', NULL, 'video_first', '', 0, '{\"questions\":[{\"type\":\"single\",\"type_label\":\"Selecci\\u00f3n \\u00danica\",\"title\":\"Hola\",\"points\":10,\"options\":\"a,b,c\",\"correct\":\"a\"},{\"type\":\"single\",\"type_label\":\"Selecci\\u00f3n \\u00danica\",\"title\":\"hola2\",\"points\":10,\"options\":\"d,e,f\",\"correct\":\"e\"},{\"type\":\"single\",\"type_label\":\"Selecci\\u00f3n \\u00danica\",\"title\":\"Hola3\",\"points\":10,\"options\":\"g,h,i\",\"correct\":\"i\"}],\"start_date\":\"2025-12-24T10:25\",\"end_date\":\"2025-12-27T10:25\"}', 1, 0, '2025-12-24 21:35:41', '2025-12-24 21:35:41'),
(208, 2, 153, 'Evaluación', 'evaluacion-694fd05c393b6', 'quiz', '', 'video_first', '', 0, '{\"questions\":[{\"type\":\"single\",\"type_label\":\"Selecci\\u00f3n \\u00danica\",\"title\":\"Que se llama la LA para MA\",\"points\":\"100\",\"options\":\"DAA, DIA, CD3\",\"correct\":null}],\"start_date\":\"2025-12-30T08:17\",\"end_date\":\"2025-12-31T08:17\"}', 0, 0, '2025-12-27 12:26:04', '2025-12-27 12:26:04'),
(209, 2, 153, 'Nueva Lección', 'nueva-leccion-694fd05c7067f', 'video', '<p>Video de la clase 1</p>', 'video_first', 'https://www.youtube.com/watch?v=vVm5cAsoFwc', 120, NULL, 1, 1, '2025-12-27 12:26:04', '2025-12-27 12:26:04'),
(210, 7, 154, 'Nueva Lección', 'nueva-leccion-6a1ca7ac860a6', 'video', '<p>Descripcion de &nbsp;clase</p>', 'video_first', 'https://www.youtube.com/watch?v=InfjHjooMAg', 50, NULL, 0, 0, '2026-06-01 01:27:08', '2026-06-01 01:27:08'),
(211, 7, 155, 'Nueva Lección', 'nueva-leccion-6a1ca7ac86751', 'video', '<p>Esta es la descripcion de la clase</p>', 'video_first', 'https://www.youtube.com/watch?v=6FZG213NJPw', 60, NULL, 0, 0, '2026-06-01 01:27:08', '2026-06-01 01:27:08'),
(212, 10, 156, 'Evaluación', 'evaluacion-6a31e201f00f1', 'quiz', '', 'video_first', '', 0, '{\"questions\":[]}', 0, 0, '2026-06-17 03:53:37', '2026-06-17 03:53:37'),
(213, 10, 157, 'Evaluación', 'evaluacion-6a31e201f0e49', 'quiz', '', 'video_first', '', 0, '{\"questions\":[{\"type\":\"multiple\",\"type_label\":\"Selecci\\u00f3n M\\u00faltiple\",\"title\":\"ABCA\",\"points\":10,\"options\":\"A,B,C,D\",\"correct\":[\"B\"]},{\"type\":\"multiple\",\"type_label\":\"Selecci\\u00f3n M\\u00faltiple\",\"title\":\"DFDF\",\"points\":10,\"options\":\"A,B,C,D\",\"correct\":[\"C\"]}],\"start_date\":\"\",\"end_date\":\"\"}', 0, 0, '2026-06-17 03:53:37', '2026-06-17 03:53:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `course_topics`
--

CREATE TABLE `course_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `order_index` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `course_topics`
--

INSERT INTO `course_topics` (`id`, `course_id`, `title`, `summary`, `order_index`, `created_at`, `updated_at`) VALUES
(147, 1, 'Módulo A', NULL, 0, '2025-12-24 21:35:39', '2025-12-24 21:35:39'),
(148, 1, 'Módulo 2', NULL, 1, '2025-12-24 21:35:40', '2025-12-24 21:35:40'),
(149, 1, 'Módulo 3', NULL, 2, '2025-12-24 21:35:40', '2025-12-24 21:35:40'),
(153, 2, 'Módulo único', NULL, 0, '2025-12-27 12:26:04', '2025-12-27 12:26:04'),
(154, 7, 'Nuevo Módulo', NULL, 0, '2026-06-01 01:27:08', '2026-06-01 01:27:08'),
(155, 7, 'Nuevo Módulo', NULL, 1, '2026-06-01 01:27:08', '2026-06-01 01:27:08'),
(156, 10, 'Módulo 1', NULL, 0, '2026-06-17 03:45:27', '2026-06-17 03:45:27'),
(157, 10, 'Módulo 2', NULL, 1, '2026-06-17 03:45:27', '2026-06-17 03:45:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `price_paid` decimal(10,2) NOT NULL,
  `receipt_url` varchar(255) DEFAULT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `progress_percent` int(11) NOT NULL DEFAULT 0,
  `final_score` int(11) DEFAULT NULL,
  `exam_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`exam_answers`)),
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `price_paid`, `receipt_url`, `enrolled_at`, `progress_percent`, `final_score`, `exam_answers`, `is_completed`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(14, 13, 9, 60.00, 'storage/receipts/vucsukWNi1ceM4iRjpEswoByx0jGUna6fIdcT9ql.jpg', '2026-06-28 03:09:49', 0, NULL, NULL, 0, 'approved', '2026-06-23 04:55:13', '2026-06-23 04:55:13', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `individual_certificates`
--

CREATE TABLE `individual_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `registration_number` varchar(255) DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `certificate_template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`certificate_template`)),
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `executive_signature` varchar(255) DEFAULT NULL,
  `academic_signature` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `individual_certificates`
--

INSERT INTO `individual_certificates` (`id`, `title`, `slug`, `registration_number`, `hours`, `instructor_id`, `category_id`, `start_date`, `end_date`, `certificate_template`, `cover_image`, `status`, `created_at`, `updated_at`, `executive_signature`, `academic_signature`, `user_id`) VALUES
(4, 'CONTROL DE BROTES DE VECTORES Y RESERVORIOS DE LEPTOSPIROSIS, DENGUE Y MALARIA', 'control-de-brotes-de-vectores-y-reservorios-de-leptospirosis-dengue-y-malaria-6a0b3115664de', '201', 90, 1, 4, '2026-01-12', '2026-02-10', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/5lxal5kpGGeDRERirWE2AnpkYQQXo00GZUpJ9W2e.png\",\"elements\":[{\"id\":\"el_1779118418420\",\"type\":\"qr\",\"x\":126.6666666666666714036182384006679058074951171875,\"y\":601.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1779118451860\",\"type\":\"certificate_body\",\"x\":368.333666666666658784379251301288604736328125,\"y\":431.66733333333331756875850260257720947265625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"585.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"83.33333333333333px\"},\"content\":\"<p>Realizado el <strong>12 de enero del 2026<\\/strong> al <strong>10 de febrero del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779118484588\",\"type\":\"issue_date\",\"x\":590,\"y\":560,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 10 de febrero del 2026.<\\/p>\"},{\"id\":\"el_1779118740061\",\"type\":\"text\",\"x\":426.66666666666668561447295360267162322998046875,\"y\":316.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"italic\"},\"content\":\"<p>HERMOZA PALOMINO, CRISTEL DANE\\u00c9<\\/p>\"}]}', NULL, 'published', '2026-05-18 19:32:37', '2026-05-18 19:39:35', NULL, NULL, 1),
(5, 'SANEAMIENTO AMBIENTAL Y CONTROL INTEGRAL DE VECTORES Y RESERVORIOS DE ENFERMEDADES METAXÉNICAS Y ZOONOTICAS', 'saneamiento-ambiental-y-control-integral-de-vectores-y-reservorios-de-enfermedades-metaxenicas-y-zoonoticas-6a0b3a379b4ae', '301', 90, 2, 4, '2026-02-23', '2026-03-20', '{\"pages\":1,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/lv78QuMNd278NsYfBc7Kcf89u0YYTX9OQqUOXLvO.png\",\"elements\":[{\"id\":\"el_1779120723544\",\"type\":\"qr\",\"x\":123.33333333333334,\"y\":606.6666666666667,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1779120730211\",\"type\":\"student_name\",\"x\":490,\"y\":311.6666666666667,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779120822501\",\"type\":\"certificate_body\",\"x\":383.3333333333333,\"y\":460,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"17px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"543.3333333333333px\",\"fontStyle\":\"italic\",\"height\":\"60px\"},\"content\":\"<p>Realizado el <b>23 de febrero del 2026<\\/b> al <b>20 de marzo del 2026<\\/b>, con una duraci\\u00f3n de <b>90 horas acad\\u00e9micas<\\/b>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779120838978\",\"type\":\"issue_date\",\"x\":573.3333333333334,\"y\":568.334,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 20 de marzo del 2026<\\/p>\"}],\"background2\":null,\"elements2\":[]}', NULL, 'published', '2026-05-18 20:11:35', '2026-07-02 08:56:38', NULL, NULL, 1),
(6, 'ENFERMEDADES METAXÉNICAS Y ZOONOSIS', 'enfermedades-metaxenicas-y-zoonosis-6a0b3c6d6155c', '401', 90, 1, 4, '2026-04-20', '2026-05-11', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/ZqqRnp5Jco9RDXziooiVC3fshmidluO9CvqqBMTO.png\",\"elements\":[{\"id\":\"el_1779121370114\",\"type\":\"qr\",\"x\":121.6666666666666714036182384006679058074951171875,\"y\":591.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1779121406516\",\"type\":\"student_name\",\"x\":471.66666666666668561447295360267162322998046875,\"y\":318.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779121419228\",\"type\":\"certificate_body\",\"x\":343.33300000000002683009370230138301849365234375,\"y\":440,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"600px\",\"fontStyle\":\"italic\",\"height\":\"85px\"},\"content\":\"<p>Realizado el <strong>20 de abril del 2026<\\/strong> al <strong>11 de mayo del 2026<\\/strong>, con una duraci\\u00f3n de <strong>88 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779121432322\",\"type\":\"issue_date\",\"x\":590,\"y\":563.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 11 de mayo del 2026<\\/p>\"}]}', NULL, 'published', '2026-05-18 20:21:01', '2026-05-18 20:25:34', NULL, NULL, 1),
(7, 'SISTEMA INTEGRADO DE ADMINISTRACIÓN FINANCIERA - SIAF', 'sistema-integrado-de-administracion-financiera-siaf-6a0b62f89acaa', '101', 130, 2, 7, '2026-02-09', '2026-04-06', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1779131180776\",\"type\":\"course_title\",\"x\":313.333666666666658784379251301288604736328125,\"y\":391.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"22px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>SISTEMA INTEGRADO DE ADMINISTRACI\\u00d3N FINANCIERA - SIAF<\\/p>\"},{\"id\":\"el_1779131231532\",\"type\":\"certificate_body\",\"x\":316.66666666666668561447295360267162322998046875,\"y\":423.33266666666668243124149739742279052734375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"680px\",\"fontStyle\":\"italic\",\"height\":\"95px\"},\"content\":\"<p>Realizado el <strong>9 de febrero del 2026<\\/strong> al <strong>6 de abril del 2026<\\/strong>, con una duraci\\u00f3n de <strong>130 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779131259709\",\"type\":\"student_name\",\"x\":503.3333333333333712289459072053432464599609375,\"y\":316.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779131268978\",\"type\":\"issue_date\",\"x\":643.3333333333333712289459072053432464599609375,\"y\":555,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 18 de mayo del 2026<\\/p>\"},{\"id\":\"el_1779131280625\",\"type\":\"qr\",\"x\":120,\"y\":593.3333333333333712289459072053432464599609375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}]}', NULL, 'published', '2026-05-18 23:05:28', '2026-05-18 23:08:44', NULL, NULL, 1),
(8, 'OFIMÁTICA', 'ofimatica-6a0c80d5cb633', '501', 90, NULL, NULL, '2025-11-10', '2025-11-28', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/CP5uYEaRhpnvgPZDRVxpQafxKFiOHZ8XIDDTu5ZQ.png\",\"elements\":[{\"id\":\"el_1779204404077\",\"type\":\"student_name\",\"x\":553.3333333333333712289459072053432464599609375,\"y\":335,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779204415372\",\"type\":\"certificate_body\",\"x\":396.66699999999997316990629769861698150634765625,\"y\":459.9996666666667124445666559040546417236328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"608.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"73.33333333333333px\"},\"content\":\"<p>Realizado el <strong>10 de noviembre del 2025<\\/strong> al <strong>28 de noviembre del 2025<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779204430212\",\"type\":\"issue_date\",\"x\":615.00033333333340124227106571197509765625,\"y\":595.00033333333340124227106571197509765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 28 de noviembre del 2025.<\\/p>\"},{\"id\":\"el_1779204477543\",\"type\":\"course_title\",\"x\":603.333666666666658784379251301288604736328125,\"y\":411.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-opensans\",\"fontSize\":\"36px\",\"color\":\"#222bd2\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>OFIM\\u00c1TICA<\\/p>\"},{\"id\":\"el_1779204537081\",\"type\":\"qr\",\"x\":148.333333333333342807236476801335811614990234375,\"y\":628.3333333333333712289459072053432464599609375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}]}', NULL, 'published', '2026-05-19 19:25:09', '2026-05-19 19:29:58', NULL, NULL, 1),
(11, 'EXCEL INTERMEDIO', 'excel-intermedio-6a1e3f8b6db02', '101', 90, NULL, 7, '2026-05-02', '2026-06-01', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/uB5At3UvSel9ODYODaQaFD2eLD1ribHeZJCWNMKM.png\",\"elements\":[{\"id\":\"el_1780367357073\",\"type\":\"student_name\",\"x\":333.33300000000002683009370230138301849365234375,\"y\":316.66733333333331756875850260257720947265625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"636px\",\"height\":\"36.66666666666667px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780367386703\",\"type\":\"course_title\",\"x\":458.333666666666658784379251301288604736328125,\"y\":385,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"35px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p><strong>EXCEL INTERMEDIO<\\/strong><\\/p>\"},{\"id\":\"el_1780367441098\",\"type\":\"certificate_body\",\"x\":341.66699999999997316990629769861698150634765625,\"y\":436.66599999999999681676854379475116729736328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"639.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"118px\"},\"content\":\"<p>Realizado el <strong>2 de mayo del 2026<\\/strong> al <strong>1 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780367715755\",\"type\":\"issue_date\",\"x\":619.99966666666659875772893428802490234375,\"y\":561.6670000000000300133251585066318511962890625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 1 de junio del 2026.<\\/p>\"},{\"id\":\"el_1780367766893\",\"type\":\"qr\",\"x\":120.0003333333333301879974897019565105438232421875,\"y\":608.3329999999999699866748414933681488037109375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1780367797485\",\"type\":\"registration_number\",\"x\":31.666666666666657192763523198664188385009765625,\"y\":583.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1780367847116\",\"type\":\"text\",\"x\":100,\"y\":98.3333333333333285963817615993320941925048828125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"36px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"left\",\"width\":\"215px\",\"fontStyle\":\"normal\",\"height\":\"63.66666666666667px\"},\"content\":\"<p><strong style=\\\"color: rgb(0, 71, 178);\\\">TEMARIO<\\/strong><\\/p>\"},{\"id\":\"el_1780367934703\",\"type\":\"text\",\"x\":106.6666666666666714036182384006679058074951171875,\"y\":185,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"670px\",\"fontStyle\":\"normal\",\"height\":\"237px\"},\"content\":\"<p>-  A<\\/p><p>-  B<\\/p><p>-  C<\\/p><p>-  D<\\/p><p>-  E<\\/p>\"}]}', NULL, 'published', '2026-06-02 06:27:23', '2026-06-13 06:35:39', NULL, NULL, 1),
(12, 'PSICOMOTRICIDAD EDUCATIVA', 'psicomotricidad-educativa-6a1f78dad6e97', '101', 90, NULL, 7, '2026-05-02', '2026-06-02', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/uB5At3UvSel9ODYODaQaFD2eLD1ribHeZJCWNMKM.png\",\"elements\":[{\"id\":\"el_1780456685440\",\"type\":\"student_name\",\"x\":319.9993333333333112022955901920795440673828125,\"y\":314.9996666666667124445666559040546417236328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"28px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"664.3333333333334px\",\"height\":\"23.666666666666668px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780456748190\",\"type\":\"course_title\",\"x\":376.66699999999997316990629769861698150634765625,\"y\":385,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"35px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>PSICOMOTRICIDAD EDUCATIVA<\\/p>\"},{\"id\":\"el_1780456953916\",\"type\":\"certificate_body\",\"x\":336.66699999999997316990629769861698150634765625,\"y\":429.9993333333333112022955901920795440673828125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"631.6666666666666px\",\"fontStyle\":\"normal\",\"height\":\"135px\"},\"content\":\"<p>Realizado el <strong>2 de mayo del 2026<\\/strong> al <strong>2 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780457040737\",\"type\":\"issue_date\",\"x\":636.666333333333341215620748698711395263671875,\"y\":558.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"normal\"},\"content\":\"<p>Ayacucho, 2 de junio del 2026<\\/p>\"},{\"id\":\"el_1780457157551\",\"type\":\"qr\",\"x\":116.6663333333333270047660334967076778411865234375,\"y\":593.3329999999999699866748414933681488037109375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1780457171605\",\"type\":\"registration_number\",\"x\":28.3333666666666630362669820897281169891357421875,\"y\":571.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1780457226280\",\"type\":\"text\",\"x\":110,\"y\":111.6666666666666714036182384006679058074951171875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"45px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"216.66666666666669px\",\"fontStyle\":\"normal\",\"height\":\"55px\"},\"content\":\"<p>TEMARIO<\\/p>\"},{\"id\":\"el_1780457355570\",\"type\":\"text\",\"x\":105,\"y\":208.333333333333342807236476801335811614990234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"30px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"516.6666666666667px\",\"fontStyle\":\"normal\",\"height\":\"215.66666666666666px\"},\"content\":\"<p>- Fundamentos de la psicomotricidad<\\/p><p>- Desarrollo psicomotor del ni\\u00f1o<\\/p><p>- Componentes de la psicomotricidad<\\/p><p>- Psicomotricidad y curr\\u00edculo nacional<\\/p><p>- Estrategias metodol\\u00f3gicas<\\/p>\"}]}', NULL, 'published', '2026-06-03 04:44:10', '2026-06-13 06:39:49', NULL, NULL, 1),
(13, 'FORMULACIÓN, EJECUCIÓN Y LIQUIDACIÓN EN PROYECTOS AGROPECUARIOS Y FORESTALES EN EL MARCO DE INVIERTE.PE', 'formulacion-ejecucion-y-liquidacion-en-proyectos-agropecuarios-y-forestales-en-el-marco-de-inviertepe-6a2039bc8b29f', '1001', 200, NULL, 7, '2026-03-13', '2026-05-29', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/7XmB08ra2Ofcrd917je04YHh0JkZmvtWrCpStH7p.png\",\"elements\":[{\"id\":\"el_1780497205108\",\"type\":\"student_name\",\"x\":386.66699999999997316990629769861698150634765625,\"y\":335,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"610px\",\"height\":\"20px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780497358897\",\"type\":\"course_title\",\"x\":376.66599999999999681676854379475116729736328125,\"y\":403.33266666666668243124149739742279052734375,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"24px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"left\",\"width\":\"652.6666666666666px\",\"height\":\"42px\"},\"content\":\"<p>FORMULACI\\u00d3N, EJECUCI\\u00d3N Y LIQUIDACI\\u00d3N EN PROYECTOS AGROPECUARIOS Y FORESTALES EN EL MARCO DE INVIERTE.PE<\\/p>\"},{\"id\":\"el_1780497470349\",\"type\":\"certificate_body\",\"x\":375,\"y\":491.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"645px\",\"fontStyle\":\"italic\",\"height\":\"126.66666666666667px\"},\"content\":\"<p>Realizado el <strong>13 de marzo del 2026<\\/strong> al <strong>29 de mayo del 2026<\\/strong>, con una duraci\\u00f3n de <strong>200 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente DIPLOMA para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780497544096\",\"type\":\"issue_date\",\"x\":606.6670000000000300133251585066318511962890625,\"y\":611.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"right\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 29 de mayo del 2026<\\/p>\"},{\"id\":\"el_1780497607001\",\"type\":\"registration_number\",\"x\":126.6663333333333270047660334967076778411865234375,\"y\":626.66733333333331756875850260257720947265625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#ffffff\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"136.66666666666666px\",\"height\":\"20px\"},\"content\":\"<p>Reg. N\\u00b0 1001<\\/p>\"},{\"id\":\"el_1780497630618\",\"type\":\"qr\",\"x\":130,\"y\":651.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/63jarAU9Djz3nirhuUavs3WkLczYwsWN1FuHRsJc.png\",\"elements2\":[{\"id\":\"el_1780499061807\",\"type\":\"text\",\"x\":100.0003333333333301879974897019565105438232421875,\"y\":140.00033333333334439885220490396022796630859375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"592px\",\"fontStyle\":\"normal\",\"height\":\"479px\"},\"content\":\"<p><span class=\\\"ql-cursor\\\">\\ufeff\\ufeff\\ufeff\\ufeff<\\/span>M\\u00f3dulo I: Fundamentos y marco normativo de Invierte.pe.<\\/p><p>M\\u00f3dulo II: Identificaci\\u00f3n, formulaci\\u00f3n y evaluaci\\u00f3n de proyectos agropecuarios y forestales.<\\/p><p>M\\u00f3dulo III: Elaboraci\\u00f3n de fichas t\\u00e9cnicas y estudios de inversi\\u00f3n.<\\/p><p>M\\u00f3dulo IV: Gesti\\u00f3n y ejecuci\\u00f3n de proyectos de inversi\\u00f3n p\\u00fablica.<\\/p><p>M\\u00f3dulo V: Seguimiento, monitoreo y control de inversiones.<\\/p><p>M\\u00f3dulo VI: Liquidaci\\u00f3n t\\u00e9cnica y financiera de proyectos.<\\/p><p>M\\u00f3dulo VII: Casos pr\\u00e1cticos aplicados a proyectos agropecuarios y forestales.<\\/p><p>M\\u00f3dulo VIII: Taller integrador y desarrollo de proyectos bajo la metodolog\\u00eda Invierte.pe.<\\/p><p><br><\\/p>\"}]}', NULL, 'published', '2026-06-03 18:27:08', '2026-06-03 19:51:11', NULL, NULL, 1),
(14, 'IMPLEMENTACIÓN DE HERRAMIENTAS DE IA EN EL DESARROLLO WEB', 'implementacion-de-herramientas-de-ia-en-el-desarrollo-web-6a219792be00c', '101', 90, NULL, NULL, '2026-05-04', '2026-06-04', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/uB5At3UvSel9ODYODaQaFD2eLD1ribHeZJCWNMKM.png\",\"elements\":[{\"id\":\"el_1780586482327\",\"type\":\"student_name\",\"x\":343.33333333333331438552704639732837677001953125,\"y\":318.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"27px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"611.3333333333334px\",\"height\":\"20px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780586533023\",\"type\":\"course_title\",\"x\":358.33333333333331438552704639732837677001953125,\"y\":385,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"26px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"571.3333333333334px\",\"height\":\"78.33333333333334px\"},\"content\":\"<p>IMPLEMENTACI\\u00d3N DE HERRAMIENTAS <\\/p><p>DE IA EN EL DESARROLLO WEB<\\/p>\"},{\"id\":\"el_1780586595569\",\"type\":\"certificate_body\",\"x\":381.66599999999999681676854379475116729736328125,\"y\":454.999000000000023646862246096134185791015625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"525px\",\"fontStyle\":\"normal\",\"height\":\"45px\"},\"content\":\"<p>Realizado el <strong>4 de mayo del 2026<\\/strong> al <strong>4 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780586676226\",\"type\":\"issue_date\",\"x\":563.33300000000008367351256310939788818359375,\"y\":564.9996666666667124445666559040546417236328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"normal\"},\"content\":\"<p>Ayacucho, 4 de junio del 2026.<\\/p>\"},{\"id\":\"el_1780586950979\",\"type\":\"qr\",\"x\":120,\"y\":593.3333333333333712289459072053432464599609375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1780586961381\",\"type\":\"registration_number\",\"x\":31.666666666666657192763523198664188385009765625,\"y\":573.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1780586727840\",\"type\":\"text\",\"x\":125,\"y\":100,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"30px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"167.33333333333334px\",\"fontStyle\":\"normal\",\"height\":\"39.333333333333336px\"},\"content\":\"<p>TEMARIO<\\/p>\"},{\"id\":\"el_1780586766371\",\"type\":\"text\",\"x\":135,\"y\":163.333333333333342807236476801335811614990234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"auto\",\"fontStyle\":\"italic\"},\"content\":\"<p><span style=\\\"color: rgb(45, 55, 72);\\\">\\u2022 <\\/span><strong style=\\\"color: rgb(26, 32, 44);\\\">Ensamble y Optimizaci\\u00f3n:<\\/strong><\\/p><p><span style=\\\"color: rgb(45, 55, 72);\\\">\\u2022 <\\/span><strong style=\\\"color: rgb(26, 32, 44);\\\">Buenas Pr\\u00e1cticas de Flujo \\u00c1gil:<\\/strong><\\/p><p><span style=\\\"color: rgb(45, 55, 72);\\\">\\u2022 <\\/span><strong style=\\\"color: rgb(26, 32, 44);\\\">Auditor\\u00eda y Resoluci\\u00f3n de Bugs:<\\/strong><\\/p><p><span style=\\\"color: rgb(45, 55, 72);\\\">\\u2022 <\\/span><strong style=\\\"color: rgb(26, 32, 44);\\\">Prompting de C\\u00f3digo e Iteraci\\u00f3n:<\\/strong><\\/p><ul><li><span style=\\\"color: rgb(45, 55, 72);\\\">\\u2022 <\\/span><strong style=\\\"color: rgb(26, 32, 44);\\\">Configuraci\\u00f3n del Entorno Asistido:<\\/strong><\\/li><\\/ul>\"}]}', NULL, 'published', '2026-06-04 19:19:46', '2026-06-05 18:14:28', NULL, NULL, 1),
(15, 'PROCEDIMIENTO ADMINISTRATIVO DISCIPLINARIO EN EL MARCO DE LA LEY DEL SERVICIO CIVIL', 'procedimiento-administrativo-disciplinario-en-el-marco-de-la-ley-del-servicio-civil-6a22f5c5a9240', '100', 90, NULL, NULL, '2026-05-05', '2026-06-05', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1780676118290\",\"type\":\"student_name\",\"x\":371.66666666666668561447295360267162322998046875,\"y\":318.33400000000000318323145620524883270263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"26px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"530.3333333333334px\",\"height\":\"24.333333333333332px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780676170376\",\"type\":\"course_title\",\"x\":331.66699999999997316990629769861698150634765625,\"y\":376.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"23px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"640px\",\"height\":\"51px\"},\"content\":\"<p>PROCEDIMIENTO ADMINISTRATIVO DISCIPLINARIO EN <\\/p><p>EL MARCO DE LA LEY DEL SERVICIO CIVIL<\\/p>\"},{\"id\":\"el_1780676364223\",\"type\":\"certificate_body\",\"x\":330,\"y\":441.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"640px\",\"fontStyle\":\"italic\",\"height\":\"96.66666666666666px\"},\"content\":\"<p>Realizado el <strong>5 de mayo del 2026<\\/strong> al <strong>5 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780676458026\",\"type\":\"issue_date\",\"x\":636.6666666666666287710540927946567535400390625,\"y\":559.99966666666659875772893428802490234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"normal\"},\"content\":\"<p>Ayacucho, 5 de junio del 2026<\\/p>\"},{\"id\":\"el_1780676491504\",\"type\":\"qr\",\"x\":119.9996666666666698120025102980434894561767578125,\"y\":598.3333333333333712289459072053432464599609375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1780676503579\",\"type\":\"registration_number\",\"x\":28.3333333333333285963817615993320941925048828125,\"y\":573.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1780676897259\",\"type\":\"text\",\"x\":91.6666666666666714036182384006679058074951171875,\"y\":80.0003333333333301879974897019565105438232421875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"32px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p><strong>TEMARIO<\\/strong><\\/p>\"},{\"id\":\"el_1780676991527\",\"type\":\"text\",\"x\":100,\"y\":154.99966666666665560114779509603977203369140625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"auto\",\"fontStyle\":\"italic\"},\"content\":\"<ul><li><strong style=\\\"color: rgba(0,0,0,var(--O42jJQ,1));\\\">Principios aplicables al PAD<\\/strong><\\/li><li><strong style=\\\"color: rgba(0,0,0,var(--O42jJQ,1));\\\"> \\u00c1mbito de aplicaci\\u00f3n del PAD<\\/strong><\\/li><li><strong style=\\\"color: rgba(0,0,0,var(--O42jJQ,1));\\\">Actores del PAD<\\/strong><\\/li><li><strong style=\\\"color: rgba(0,0,0,var(--O42jJQ,1));\\\">Faltas Disciplinarias<\\/strong><\\/li><li><strong style=\\\"color: rgba(0,0,0,var(--O42jJQ,1));\\\">Sanciones Disciplinarias<\\/strong><\\/li><li><strong style=\\\"color: rgba(0,0,0,var(--O42jJQ,1));\\\">Fases del PAD<\\/strong><\\/li><li><strong style=\\\"color: rgba(0,0,0,var(--O42jJQ,1));\\\">Plazos de prescripci\\u00f3n dentro de un PAD<\\/strong><\\/li><\\/ul><p><br><\\/p>\"}]}', NULL, 'published', '2026-06-05 20:13:57', '2026-06-10 22:15:00', NULL, NULL, 1),
(16, 'TRIBUTACIÓN', 'tributacion-6a28964369536', '101', 90, NULL, 7, '2026-05-09', '2026-06-09', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1781044980766\",\"type\":\"student_name\",\"x\":341.66733333333331756875850260257720947265625,\"y\":315,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"630px\",\"height\":\"31.666666666666668px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1781045179520\",\"type\":\"course_title\",\"x\":510,\"y\":376.66599999999999681676854379475116729736328125,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"39px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p><strong>TRIBUTACI\\u00d3N<\\/strong><\\/p>\"},{\"id\":\"el_1781045489509\",\"type\":\"certificate_body\",\"x\":371.668333333333293921896256506443023681640625,\"y\":423.33433333333329073866480030119419097900390625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"559px\",\"fontStyle\":\"normal\",\"height\":\"131px\"},\"content\":\"<p>Realizado el <strong>9 de mayo del 2026<\\/strong> al <strong>9 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1781045626511\",\"type\":\"issue_date\",\"x\":641.666333333333341215620748698711395263671875,\"y\":550,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"316.66666666666663px\",\"fontStyle\":\"normal\",\"height\":\"21.333333333333332px\"},\"content\":\"<p>Ayacucho, 9 de junio del 2026<\\/p>\"},{\"id\":\"el_1781045898821\",\"type\":\"qr\",\"x\":118.332999999999998408384271897375583648681640625,\"y\":588.3323333333333948758081533014774322509765625,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1781045918001\",\"type\":\"registration_number\",\"x\":116.6663333333333270047660334967076778411865234375,\"y\":566.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"121.33333333333333px\",\"height\":\"20px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1781046009758\",\"type\":\"text\",\"x\":121.6666666666666714036182384006679058074951171875,\"y\":100,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"36px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p><strong>TEMARIO<\\/strong><\\/p>\"},{\"id\":\"el_1781046354327\",\"type\":\"text\",\"x\":125,\"y\":166.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"25px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"567.6666666666666px\",\"fontStyle\":\"italic\",\"height\":\"240px\"},\"content\":\"<p>-  Definici\\u00f3n sobre el enfoque tributario<\\/p><p>-  Base legal<\\/p><p>-  Principios tributarios<\\/p><p>-  Clasificaci\\u00f3n de tributos<\\/p><p>-  Reg\\u00edmenes tributarios<\\/p><p>-  Caso pr\\u00e1ctico de determinaci\\u00f3n del tributo<\\/p>\"}]}', NULL, 'published', '2026-06-10 02:40:03', '2026-06-10 03:22:01', NULL, NULL, 1),
(17, 'INTELIGENCIA ARTIFICIAL PARA DOCENTES', 'inteligencia-artificial-para-docentes-6a2b3762b1d4b', '101', 90, NULL, 8, '2026-05-11', '2026-05-11', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1781217209219\",\"type\":\"student_name\",\"x\":336.666333333333341215620748698711395263671875,\"y\":314.9993333333333112022955901920795440673828125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"639px\",\"height\":\"33.333333333333336px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1781217308532\",\"type\":\"course_title\",\"x\":336.66566666666670926133519969880580902099609375,\"y\":376.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"34px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"637.3333333333334px\",\"height\":\"76.33333333333333px\"},\"content\":\"<p><span class=\\\"ql-cursor\\\">\\ufeff\\ufeff\\ufeff\\ufeff\\ufeff\\ufeff\\ufeff<\\/span><strong>INTELIGENCIA ARTIFICIAL PARA DOCENTES<\\/strong><\\/p>\"},{\"id\":\"el_1781217406280\",\"type\":\"certificate_body\",\"x\":363.333666666666658784379251301288604736328125,\"y\":451.66566666666670926133519969880580902099609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"610.3333333333334px\",\"fontStyle\":\"normal\",\"height\":\"103.66666666666667px\"},\"content\":\"<p>Realizado el <strong>11 de mayo del 2026<\\/strong> al <strong>11 de mayo del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1781217473428\",\"type\":\"issue_date\",\"x\":674.99966666666659875772893428802490234375,\"y\":568.3339999999999463398125953972339630126953125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"318.3333333333333px\",\"fontStyle\":\"normal\",\"height\":\"23px\"},\"content\":\"<p>Ayacucho, 11 de mayo del 2026<\\/p>\"},{\"id\":\"el_1781217545931\",\"type\":\"qr\",\"x\":118.333333333333342807236476801335811614990234375,\"y\":586.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1781217558964\",\"type\":\"registration_number\",\"x\":113.3336666666666729952339665032923221588134765625,\"y\":565,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"136.66666666666666px\",\"height\":\"21.333333333333332px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1781220212970\",\"type\":\"text\",\"x\":128.333666666666658784379251301288604736328125,\"y\":125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"36px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"left\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p><strong>TEMARIO<\\/strong><\\/p>\"},{\"id\":\"el_1781220299833\",\"type\":\"text\",\"x\":133.332999999999998408384271897375583648681640625,\"y\":183.332999999999998408384271897375583648681640625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"28px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"472.6666666666667px\",\"fontStyle\":\"normal\",\"height\":\"341.6666666666667px\"},\"content\":\"<p>-  Chat GPT<\\/p><p>-  Gemini<\\/p><p>-  Genially<\\/p><p>-  Removebg<\\/p><p>-  Picsart<\\/p><p><br><\\/p>\"}]}', NULL, 'published', '2026-06-12 02:32:02', '2026-06-18 22:05:17', NULL, NULL, 1),
(18, 'TRABAJO COMUNITARIO EN PROYECTOS Y PROGRAMAS SOCIALES', 'trabajo-comunitario-en-proyectos-y-programas-sociales-6a300f90d9ccf', '100', 90, 1, 7, '2026-05-11', '2026-06-10', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1781535858946\",\"type\":\"student_name\",\"x\":360,\"y\":318.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"547px\",\"height\":\"33px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1781535868425\",\"type\":\"course_title\",\"x\":316.666333333333341215620748698711395263671875,\"y\":390,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"691.3333333333334px\",\"height\":\"43.33333333333333px\"},\"content\":\"TRABAJO COMUNITARIO EN PROYECTOS Y PROGRAMAS SOCIALES\"},{\"id\":\"el_1781535906523\",\"type\":\"certificate_body\",\"x\":341.66699999999997316990629769861698150634765625,\"y\":450,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"633.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"106.66666666666667px\"},\"content\":\"<p>Realizado el <b>11 de mayo del 2026<\\/b> al <b>10 de junio del 2026<\\/b>, con una duraci\\u00f3n de <b>90 horas acad\\u00e9micas<\\/b>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1781535919753\",\"type\":\"issue_date\",\"x\":646.666666666666742457891814410686492919921875,\"y\":563.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 10 de junio del 2026<\\/p>\"},{\"id\":\"el_1781535931474\",\"type\":\"qr\",\"x\":120,\"y\":595,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1781535939154\",\"type\":\"registration_number\",\"x\":26.666666666666657192763523198664188385009765625,\"y\":571.666666666666742457891814410686492919921875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1781535961862\",\"type\":\"text\",\"x\":100,\"y\":100,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"40px\",\"color\":\"#222bd2\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p>TEMARIO<\\/p>\"},{\"id\":\"el_1781535990187\",\"type\":\"text\",\"x\":130,\"y\":173.333333333333342807236476801335811614990234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"22px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Introducci\\u00f3n y bases de la intervenci\\u00f3n social <\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Definici\\u00f3n y alcance de un \\\"Plan\\\"<\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Programas sociales<\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Proyectos sociales <\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Diferencias y jerarqu\\u00eda: plan, programa y proyecto <\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>El concepto de comunidad <\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>El trabajo comunitario <\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Fases del proceso de intervenci\\u00f3n comunitaria <\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Modelos de intervenci\\u00f3n de la comunidad <\\/strong><\\/p><p>\\u00b7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Estrategias y conclusiones finales&nbsp;<\\/strong><\\/p>\"}]}', NULL, 'published', '2026-06-15 18:43:28', '2026-07-02 19:11:21', NULL, NULL, 1),
(20, 'RELACIONES COMUNITARIAS Y RESOLUCIÓN DE CONFLICTOS SOCIALES', 'relaciones-comunitarias-y-resolcion-de-conflictos-sociales-6a33581b208a4', '100', 90, 5, 7, '2026-05-16', '2026-06-17', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1781749817056\",\"type\":\"student_name\",\"x\":310.0006666666666887977044098079204559326171875,\"y\":321.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"27px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"640.3333333333334px\",\"height\":\"35px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1781749857570\",\"type\":\"course_title\",\"x\":346.66666666666668561447295360267162322998046875,\"y\":390,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"618.6666666666666px\",\"height\":\"72px\"},\"content\":\"RELACIONES COMUNITARIAS Y RESOLUCI\\u00d3N DE CONFLICTOS SOCIALES\"},{\"id\":\"el_1781749901990\",\"type\":\"certificate_body\",\"x\":348.333666666666658784379251301288604736328125,\"y\":450,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"636.6666666666666px\",\"fontStyle\":\"italic\",\"height\":\"106.66666666666667px\"},\"content\":\"<p>Realizado el <b>16 de mayo del 2026<\\/b> al <b>17 de junio del 2026<\\/b>, con una duraci\\u00f3n de <b>90 horas acad\\u00e9micas<\\/b>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1781749943252\",\"type\":\"issue_date\",\"x\":650,\"y\":568.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 17 de junio del 2026<\\/p>\"},{\"id\":\"el_1781749959219\",\"type\":\"registration_number\",\"x\":35,\"y\":586.666666666666742457891814410686492919921875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"},{\"id\":\"el_1781749964962\",\"type\":\"qr\",\"x\":123.332999999999998408384271897375583648681640625,\"y\":608.3329999999999699866748414933681488037109375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1781749994227\",\"type\":\"text\",\"x\":103.3333333333333285963817615993320941925048828125,\"y\":100,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"40px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p>TEMARIO<\\/p>\"},{\"id\":\"el_1781750021015\",\"type\":\"text\",\"x\":108.3333333333333285963817615993320941925048828125,\"y\":160,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"623px\",\"fontStyle\":\"italic\",\"height\":\"201px\"},\"content\":\"<p>1. Conflicto social&nbsp;<\\/p><p>2. \\u2060an\\u00e1lisis del problema del conflicto social.<\\/p><p>3. \\u2060clasificaci\\u00f3n del conflicto social&nbsp;<\\/p><p>4. \\u2060an\\u00e1lisis de actores o partes<\\/p><p>5. \\u2060proceso del conflicto social<\\/p><p>6. \\u2060relaciones comunitarias.<\\/p>\"}]}', NULL, 'published', '2026-06-18 06:29:47', '2026-07-05 06:18:09', NULL, NULL, 1);
INSERT INTO `individual_certificates` (`id`, `title`, `slug`, `registration_number`, `hours`, `instructor_id`, `category_id`, `start_date`, `end_date`, `certificate_template`, `cover_image`, `status`, `created_at`, `updated_at`, `executive_signature`, `academic_signature`, `user_id`) VALUES
(21, 'MAESTRA SOMBRA', 'maestra-sombra-6a35e27a2474e', '100', 90, NULL, 8, '2026-05-19', '2026-06-19', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1781916334547\",\"type\":\"student_name\",\"x\":328.334,\"y\":319.9996666666667,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"648.6666666666666px\",\"height\":\"21.666666666666668px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1781916505493\",\"type\":\"course_title\",\"x\":468.33366666666666,\"y\":380,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"35px\",\"color\":\"#ee731f\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>MAESTRA SOMBRA<\\/p>\"},{\"id\":\"el_1781916616030\",\"type\":\"certificate_body\",\"x\":330.0003333333333,\"y\":425,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"630px\",\"fontStyle\":\"normal\",\"height\":\"125px\"},\"content\":\"<p>Realizado el <strong>19 de mayo del 2026<\\/strong> al <strong>19 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1781916646315\",\"type\":\"issue_date\",\"x\":658.3336666666667,\"y\":553.3333333333334,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"306.66666666666663px\",\"fontStyle\":\"normal\",\"height\":\"33px\"},\"content\":\"<p>Ayacucho, 19 de junio del 2026<\\/p>\"},{\"id\":\"el_1781916717264\",\"type\":\"qr\",\"x\":120,\"y\":583.3333333333334,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1781916727883\",\"type\":\"registration_number\",\"x\":118.33366666666667,\"y\":563.333,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"123.33333333333331px\",\"height\":\"21.333333333333332px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1781916779114\",\"type\":\"text\",\"x\":100.00003333333333,\"y\":98.3333,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"36px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p><strong>TEMARIO<span class=\\\"ql-cursor\\\">\\ufeff\\ufeff\\ufeff<\\/span><\\/strong><\\/p>\"},{\"id\":\"el_1781962947619\",\"type\":\"text\",\"x\":106.667,\"y\":160.00033333333334,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"853.3333333333334px\",\"fontStyle\":\"normal\",\"height\":\"367.6666666666667px\"},\"content\":\"<p>- Qu\\u00e9 significa Maestra Sombra<\\/p><p>- Funciones principales de una Maestra Shadow<\\/p><p>- El rol emocional de la Maestra Sombra<\\/p><p>- Habilidades necesarias para ser una Maestra Shadow<\\/p><p>- La Maestra Sombra como puente entre la escuela y la familia<\\/p>\"}]}', NULL, 'published', '2026-06-20 04:44:42', '2026-06-28 06:09:31', NULL, NULL, 1),
(22, 'TERAPIA DE LENGUAJE', 'terapia-de-lenguaje-6a3b1b4843139', '100', 90, NULL, 8, '2026-05-23', '2026-06-23', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/GPWrVnfOeXVwPQaLMGYie33CE6kCao7qGyoYySzy.png\",\"elements\":[{\"id\":\"el_1782258580916\",\"type\":\"student_name\",\"x\":326.66733333333331756875850260257720947265625,\"y\":314.999000000000023646862246096134185791015625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"653.3333333333334px\",\"height\":\"26px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1782258614379\",\"type\":\"course_title\",\"x\":461.66699999999997316990629769861698150634765625,\"y\":383.33266666666668243124149739742279052734375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"30px\",\"color\":\"#f97317\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>TERAPIA DE LENGUAJE<\\/p>\"},{\"id\":\"el_1782258791932\",\"type\":\"certificate_body\",\"x\":325,\"y\":421.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"646.6666666666666px\",\"fontStyle\":\"normal\",\"height\":\"96.66666666666666px\"},\"content\":\"<p>Realizado el <strong>23 de mayo del 2026<\\/strong> al <strong>23 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1782258808243\",\"type\":\"issue_date\",\"x\":663.3333333333333712289459072053432464599609375,\"y\":546.6660000000000536601874046027660369873046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"345px\",\"fontStyle\":\"normal\",\"height\":\"31.333333333333336px\"},\"content\":\"<p>Ayacucho, 23 de junio del 2026<\\/p>\"},{\"id\":\"el_1782258820595\",\"type\":\"registration_number\",\"x\":29.99993333333333112022955901920795440673828125,\"y\":568.333666666666658784379251301288604736328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"},{\"id\":\"el_1782258883141\",\"type\":\"qr\",\"x\":120.0003333333333301879974897019565105438232421875,\"y\":588.33266666666668243124149739742279052734375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1782258915141\",\"type\":\"text\",\"x\":100,\"y\":100,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#222bd2\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p>TEMARIO<\\/p>\"},{\"id\":\"el_1782258943047\",\"type\":\"certificate_body\",\"x\":1255,\"y\":-271.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"725px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Realizado el <strong>23 de mayo del 2026<\\/strong> al <strong>23 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1782258960266\",\"type\":\"text\",\"x\":120,\"y\":161.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p>-Beneficios de la terapia de lenguaje<\\/p><p>-Se\\u00f1ales de alerta<\\/p><p>-Tipos  de retraso de lenguaje<\\/p><p>-4 pilaresde lenguaje<\\/p><p>-Transtornos del lenguaje<\\/p>\"}]}', NULL, 'published', '2026-06-24 03:48:24', '2026-06-24 20:00:12', NULL, NULL, 1),
(23, 'IMPLEMENTACIÓN DE PLAN HCCP, BPM, PHS Y POES', 'implementacion-de-plan-hccp-bpm-phs-y-poes-6a3c0185af047', '100', 90, 1, 7, '2026-05-26', '2026-06-26', '{\"pages\":1,\"background\":null,\"elements\":[],\"background2\":null,\"elements2\":[]}', NULL, 'published', '2026-06-24 20:10:45', '2026-07-02 09:09:42', NULL, NULL, 1),
(24, 'GESTIÓN EN TRABAJOS EN ALTURA', 'gestion-en-trabajos-en-altura-6a57a1f735c05', '100', 90, 2, 7, '2026-03-09', '2026-04-11', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1784128143128\",\"type\":\"student_name\",\"x\":378.33333333333331438552704639732837677001953125,\"y\":321.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"left\",\"width\":\"530.3333333333334px\",\"height\":\"33px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1784128164711\",\"type\":\"course_title\",\"x\":423.33300000000002683009370230138301849365234375,\"y\":390.0003333333332875554333440959453582763671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"26px\",\"color\":\"#ee731f\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>GESTI\\u00d3N EN TRABAJOS EN ALTURA<\\/p>\"},{\"id\":\"el_1784128184796\",\"type\":\"certificate_body\",\"x\":333.3333333333333712289459072053432464599609375,\"y\":430,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"631.6666666666666px\",\"fontStyle\":\"italic\",\"height\":\"96.66666666666666px\"},\"content\":\"<p>Realizado el <strong>9 de marzo del 2026<\\/strong> al <strong>11 de abril del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1784128205291\",\"type\":\"issue_date\",\"x\":631.666666666666742457891814410686492919921875,\"y\":558.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 11 de abril del 2026<\\/p>\"},{\"id\":\"el_1784128216436\",\"type\":\"registration_number\",\"x\":30.000033333333334439885220490396022796630859375,\"y\":571.66699999999991632648743689060211181640625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"},{\"id\":\"el_1784128220935\",\"type\":\"qr\",\"x\":125,\"y\":600,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1784128308262\",\"type\":\"text\",\"x\":100,\"y\":100,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"26px\",\"color\":\"#222bd0\",\"fontWeight\":\"bold\",\"textAlign\":\"left\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p>TEMARIO:<\\/p>\"},{\"id\":\"el_1784128339638\",\"type\":\"text\",\"x\":110,\"y\":148.332999999999998408384271897375583648681640625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"886.6666666666667px\",\"fontStyle\":\"normal\",\"height\":\"314px\"},\"content\":\"<p>-Introducci\\u00f3n a los trabajos en altura.<\\/p><p><br><\\/p><p>-Normativa legal y est\\u00e1ndares de seguridad.<\\/p><p><br><\\/p><p>-Identificaci\\u00f3n de peligros y evaluaci\\u00f3n de riesgos.<\\/p><p><br><\\/p><p>- Roles y responsabilidades del personal.<\\/p><p><br><\\/p><p>-Equipos de protecci\\u00f3n personal (EPP) para trabajos en altura.<\\/p><p><br><\\/p><p>-Sistemas de protecci\\u00f3n contra ca\\u00eddas.<\\/p><p><br><\\/p><p>-Inspecci\\u00f3n, uso y mantenimiento de equipos.<\\/p><p><br><\\/p><p>-Permiso de trabajo y planificaci\\u00f3n de tareas.<\\/p><p><br><\\/p><p>-Uso seguro de escaleras, andamios y plataformas.<\\/p><p><br><\\/p><p>-T\\u00e9cnicas b\\u00e1sicas de trabajo seguro en altura.<\\/p><p><br><\\/p><p>-Plan de rescate y respuesta ante emergencias.<\\/p><p><br><\\/p><p>-Investigaci\\u00f3n y reporte de incidentes.<\\/p><p><br><\\/p><p><br><\\/p>\"}]}', NULL, 'published', '2026-07-15 19:06:31', '2026-07-15 19:15:38', NULL, NULL, 2),
(25, 'OFIMÁTICA BÁSICA E INTERMEDIA (Word, Excel y PowerPoint)', 'ofimatica-basica-e-intermedia-word-excel-y-powerpoint-6a724af7de512', '100', 90, 1, 7, '2026-01-05', '2026-02-05', '{\"pages\":2,\"background\":null,\"elements\":[{\"id\":\"el_1785875327738\",\"type\":\"image\",\"x\":-0.333299999999999985167420391007908619940280914306640625,\"y\":-7.16666000000000025238477974198758602142333984375,\"style\":{\"width\":\"1127px\",\"height\":\"794.5px\"},\"content\":\"\",\"src\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/GPWrVnfOeXVwPQaLMGYie33CE6kCao7qGyoYySzy.png\"},{\"id\":\"el_1785875454398\",\"type\":\"student_name\",\"x\":338.0003333333332875554333440959453582763671875,\"y\":308.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"26px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"633px\",\"height\":\"46px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1785875689990\",\"type\":\"course_title\",\"x\":301.66699999999997316990629769861698150634765625,\"y\":376.66599999999999681676854379475116729736328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"25px\",\"color\":\"#f97316\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p><strong>OFIM\\u00c1TICA B\\u00c1SICA E INTERMEDIA (Word, Excel y PowerPoint)<\\/strong><\\/p>\"},{\"id\":\"el_1785875838991\",\"type\":\"certificate_body\",\"x\":301.000999999999976353137753903865814208984375,\"y\":421.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"725px\",\"fontStyle\":\"normal\"},\"content\":\"<p>Realizado el <strong>5 de enero del 2026<\\/strong> al <strong>5 de febrero del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1785875961945\",\"type\":\"issue_date\",\"x\":684.3329999999999699866748414933681488037109375,\"y\":548.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"normal\"},\"content\":\"<p>Ayacucho, 5 de febrero del 2026<\\/p>\"},{\"id\":\"el_1785876022695\",\"type\":\"registration_number\",\"x\":26,\"y\":562,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"},{\"id\":\"el_1785876032934\",\"type\":\"qr\",\"x\":122,\"y\":590,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}],\"background2\":null,\"elements2\":[{\"id\":\"el_1785875600801\",\"type\":\"image\",\"x\":-2,\"y\":0,\"style\":{\"width\":\"1120px\",\"height\":\"790px\"},\"content\":\"\",\"src\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\"},{\"id\":\"el_1785876147232\",\"type\":\"text\",\"x\":100,\"y\":100,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"35px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"normal\"},\"content\":\"<p><strong>TEMARIO<\\/strong><\\/p>\"},{\"id\":\"el_1785876217064\",\"type\":\"text\",\"x\":104,\"y\":166,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"25px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"933px\",\"fontStyle\":\"normal\",\"height\":\"444px\"},\"content\":\"<p>-Entorno de trabajo, configuraci\\u00f3n y formatos en Microsoft Word. <\\/p><p>-Uso de estilos, dise\\u00f1o de tablas e inserci\\u00f3n de elementos gr\\u00e1ficos.<\\/p><p>-Creaci\\u00f3n de tablas de contenido, referencias y combinaci\\u00f3n de correspondencia.<\\/p><p>-Interfaz, tipos de datos y formato de celdas en Microsoft Excel. <\\/p><p>-Aplicaci\\u00f3n de f\\u00f3rmulas matem\\u00e1ticas, estad\\u00edsticas y de texto.<\\/p><p>-Uso de funciones l\\u00f3gicas y de b\\u00fasqueda (BUSCARV, BUSCARH). <\\/p><p>-Gesti\\u00f3n de base de datos: filtros, ordenamiento y formato condicional.<\\/p><p>-Creaci\\u00f3n, personalizaci\\u00f3n y an\\u00e1lisis con gr\\u00e1ficos estad\\u00edsticos. <\\/p><p>-Dise\\u00f1o de diapositivas, uso de patrones y plantillas en PowerPoint. <\\/p><p>-Configuraci\\u00f3n de transiciones, animaciones personalizadas y temporizaci\\u00f3n. <\\/p><p>-Inserci\\u00f3n de elementos multimedia y t\\u00e9cnicas para presentaciones efectivas.<\\/p>\"}]}', NULL, 'published', '2026-08-05 00:26:31', '2026-08-05 01:03:13', NULL, NULL, 4),
(26, 'TAMIZAJE NEONATAL', 'tamizaje-neonatal-6a73c767d59bf', '100', 90, 5, 7, '2026-07-02', '2026-08-05', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1785972919416\",\"type\":\"course_title\",\"x\":480.0003333333332875554333440959453582763671875,\"y\":388.333666666666658784379251301288604736328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"31px\",\"color\":\"#ee731f\",\"fontWeight\":\"bold\",\"textAlign\":\"justify\",\"width\":\"auto\"},\"content\":\"TAMIZAJE NEONATAL\"},{\"id\":\"el_1785972973163\",\"type\":\"certificate_body\",\"x\":326.66666666666668561447295360267162322998046875,\"y\":429.9996666666667124445666559040546417236328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"justify\",\"width\":\"653.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"106.66666666666667px\"},\"content\":\"<p>Realizado el <b>2 de julio del 2026<\\/b> al <b>5 de agosto del 2026<\\/b>, con una duraci\\u00f3n de <b>90 horas acad\\u00e9micas<\\/b>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1785972994146\",\"type\":\"issue_date\",\"x\":628.3333333333333712289459072053432464599609375,\"y\":555,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 5 de agosto del 2026<\\/p>\"},{\"id\":\"el_1785973004286\",\"type\":\"qr\",\"x\":121.6666666666666714036182384006679058074951171875,\"y\":596.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1785973012784\",\"type\":\"student_name\",\"x\":481.66666666666668561447295360267162322998046875,\"y\":318.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1785973136894\",\"type\":\"registration_number\",\"x\":31.66669999999999873807610129006206989288330078125,\"y\":571.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[]}', NULL, 'published', '2026-08-06 03:29:43', '2026-08-06 23:21:35', NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `individual_certificate_students`
--

CREATE TABLE `individual_certificate_students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `individual_certificate_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dni` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_custom` tinyint(1) NOT NULL DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `registration_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `individual_certificate_students`
--

INSERT INTO `individual_certificate_students` (`id`, `individual_certificate_id`, `user_id`, `dni`, `fullname`, `uuid`, `created_at`, `updated_at`, `is_custom`, `start_date`, `end_date`, `hours`, `issue_date`, `registration_number`) VALUES
(9, 3, NULL, '123456', 'Yuri', 'KZPU-2026-HSIC', '2026-05-17 04:18:46', '2026-05-17 04:18:46', 0, NULL, NULL, NULL, NULL, NULL),
(10, 2, NULL, '1234', 'Juan', 'Q619-2026-1ATH', '2026-05-17 04:29:50', '2026-05-17 04:29:50', 0, NULL, NULL, NULL, NULL, NULL),
(11, 2, NULL, '9210292', 'Deivid Chipana', 'DMLX-2026-NRQ1', '2026-05-17 04:29:50', '2026-05-17 04:29:50', 0, NULL, NULL, NULL, NULL, NULL),
(12, 2, NULL, '123456', 'Yuri Janampa', 'QHVD-2026-HE1U', '2026-05-17 04:29:50', '2026-05-17 04:29:50', 0, NULL, NULL, NULL, NULL, NULL),
(14, 4, NULL, '72882466', 'HERMOZA PALOMINO, CRISTEL DANEÉ', 'DALA-2026-RAMR', '2026-05-18 19:45:59', '2026-05-18 19:45:59', 0, NULL, NULL, NULL, NULL, NULL),
(16, 5, 1, '72882466', 'HERMOZA PALOMINO, CRISTEL DANEÉ', 'PCZH-2026-MCLQ', '2026-05-18 20:18:41', '2026-07-02 08:56:38', 0, NULL, NULL, NULL, NULL, '302'),
(19, 6, NULL, '72882466', 'HERMOZA PALOMINO, CRISTEL DANEÉ', 'B8BL-2026-V0QO', '2026-05-18 20:25:34', '2026-05-18 20:25:34', 0, NULL, NULL, NULL, NULL, NULL),
(20, 7, NULL, '70422280', 'VARGAS RUIZ, GABRIELA', 'BFIV-2026-LI3N', '2026-05-18 23:09:13', '2026-05-18 23:09:13', 0, NULL, NULL, NULL, NULL, NULL),
(22, 8, NULL, '70422280', 'VARGAS RUIZ, GABRIELA', 'LWZR-2026-XHGT', '2026-05-19 19:30:48', '2026-05-19 19:30:48', 0, NULL, NULL, NULL, NULL, NULL),
(27, 9, 1, '12345', 'Jan', '88DZ-2026-IPFX', '2026-05-22 07:54:09', '2026-05-22 08:14:32', 0, NULL, NULL, NULL, NULL, '102'),
(28, 9, 1, '9210292', 'Deivid', 'ZOSD-2026-IIKG', '2026-05-22 07:55:49', '2026-06-04 09:43:57', 1, '2026-05-27', '2026-05-29', 65, '2026-05-29', '202'),
(29, 9, 1, '1231', 'Jorge peredo', 'WC4Q-2026-WLLG', '2026-05-22 08:14:33', '2026-05-22 08:14:33', 0, NULL, NULL, NULL, NULL, '103'),
(30, 9, 1, '654123', 'Sergio Galarza', 'VJKI-2026-TXWJ', '2026-05-22 10:10:40', '2026-05-22 10:10:40', 0, NULL, NULL, NULL, NULL, '104'),
(31, 10, 1, '987654', 'Juan Perez Garcia', 'XUER-2026-TUXO', '2026-06-01 01:04:04', '2026-06-01 01:04:04', 0, NULL, NULL, NULL, NULL, '102'),
(32, 10, 1, '963258', 'Fernando Gutierrez', 'PBDR-2026-28VO', '2026-06-01 01:04:04', '2026-06-01 01:04:04', 1, '2026-06-08', '2026-06-09', 90, '2026-06-10', '202'),
(34, 12, 4, '72507178', 'MARLENE YACKELINE SEMINARIO FRANCO', 'NFN2-2026-GACG', '2026-06-03 07:43:34', '2026-06-03 07:43:34', 0, NULL, NULL, NULL, NULL, '102'),
(35, 12, 4, '45002778', 'CLARITZA JOHANI MENDOZA GIRON', 'O3A7-2026-Z0E8', '2026-06-03 07:43:34', '2026-06-03 07:43:34', 0, NULL, NULL, NULL, NULL, '103'),
(36, 13, 3, '42929114', 'YENY LLIMPE ANYOSA', 'HBXA-2026-B5TO', '2026-06-03 19:29:24', '2026-06-03 19:32:10', 0, NULL, NULL, NULL, NULL, '1002'),
(37, 12, 4, '73516988', 'SIXTA LILIANA CHEVEZ LLENQUE', 'IRNE-2026-8TQK', '2026-06-03 19:30:32', '2026-06-03 19:30:32', 0, NULL, NULL, NULL, NULL, '104'),
(38, 14, 1, '02449249', 'FRISANCHO QUISPE, BERNARDO', 'QOPF-2026-WD8D', '2026-06-04 19:31:08', '2026-06-05 18:07:41', 0, NULL, NULL, NULL, NULL, '102'),
(39, 16, 4, '71437375', 'JESÚS GUIANFRANCO MANRIQUE LÓPEZ', 'PCTR-2026-W5XE', '2026-06-10 03:15:12', '2026-06-10 03:15:12', 0, NULL, NULL, NULL, NULL, '102'),
(40, 15, 2, '10470528', 'CARLOS ANTONIO ACOSTA GALLEGOS', 'U6PC-2026-9DLD', '2026-06-10 22:07:09', '2026-06-10 22:07:09', 0, NULL, NULL, NULL, NULL, '101'),
(41, 12, 4, '46254878', 'MERCEDES VICTORIA DELGADO GIRON DÉ NEYRA', 'RGFN-2026-2V6B', '2026-06-10 23:54:38', '2026-06-10 23:54:38', 0, NULL, NULL, NULL, NULL, '105'),
(42, 18, 4, '00498080', 'ROSALBINA GIOVANNA ALVARADO BRICEÑO', 'TEYL-2026-XK31', '2026-06-15 19:09:03', '2026-07-30 03:03:44', 0, NULL, NULL, NULL, NULL, '101'),
(44, 20, 2, '10477768', 'PIERO GARCÍA PÉREZ', 'JKHQ-2026-TMMW', '2026-06-18 06:34:58', '2026-07-05 06:13:41', 0, NULL, NULL, NULL, NULL, '101'),
(45, 20, 2, '71852664', 'ESTHER YANETH COSI HUANCA', 'I37R-2026-ETYJ', '2026-06-18 21:29:49', '2026-07-05 06:13:41', 0, NULL, NULL, NULL, NULL, '102'),
(46, 17, 2, '45002778', 'CLARIZA JOHANI MENDOZA GIRON', 'U60X-2026-VV8P', '2026-06-18 22:04:11', '2026-06-18 22:04:11', 0, NULL, NULL, NULL, NULL, '102'),
(50, 21, 1, '46131712', 'ANHELA ESCARLY PACHERRE MORE', 'GIHF-2026-1III', '2026-06-20 21:34:42', '2026-06-28 06:09:32', 0, NULL, NULL, NULL, NULL, '101'),
(51, 21, 1, '73438985', 'LUZ CLARITA CORBACHO ESTRADA', '6TI0-2026-QPOL', '2026-06-21 19:39:06', '2026-06-28 06:09:32', 0, NULL, NULL, NULL, NULL, '102'),
(52, 22, 4, '48482138', 'DIANA JULIA ISABEL OBANDO VERA', 'IRXT-2026-NIIG', '2026-06-24 04:00:57', '2026-06-24 05:27:37', 0, NULL, NULL, NULL, NULL, '101'),
(53, 20, 2, '74806665', 'ANGUI JULIA CHOCTAYA BENAVIDES', '7VAN-2026-00CQ', '2026-06-27 18:45:07', '2026-07-05 06:13:41', 0, NULL, NULL, NULL, NULL, '103'),
(54, 18, 4, '71924775', 'ENRIQUEZ AYUQUE MIRIAM ROCIO', '5A1Q-2026-9GDF', '2026-07-02 19:11:21', '2026-07-30 03:03:44', 0, NULL, NULL, NULL, NULL, '102'),
(56, 24, 2, '70108688', 'CHILLCCE PUCUHUANCA, BRAYAN SMITH', 'MVWK-2026-HRMW', '2026-07-15 19:16:30', '2026-07-15 19:16:30', 0, NULL, NULL, NULL, NULL, '101'),
(57, 18, 4, '74306823', 'ASTRID HELEN PAOLA REATEGUI DE LA CRUZ', 'VGT5-2026-UVRS', '2026-07-30 03:03:44', '2026-07-30 03:03:44', 1, '2025-12-05', '2026-01-05', 90, '2026-01-05', '201'),
(58, 25, 4, '70416346', 'NAYSA GOMEZ FERNANDEZ', 'VR96-2026-1OA4', '2026-08-05 00:47:37', '2026-08-05 00:47:37', 0, NULL, NULL, NULL, NULL, '101'),
(59, 25, 4, '71544576', 'LILIAN YHONATAN DE LA CRUZ VASQUEZ', 'RZU3-2026-I2IR', '2026-08-05 00:57:12', '2026-08-05 00:57:12', 0, NULL, NULL, NULL, NULL, '102'),
(60, 25, 4, '41173643', 'DORIS MARLENI CARRIÓN NUÑEZ', 'PSLG-2026-YUPU', '2026-08-05 00:57:12', '2026-08-05 00:57:12', 0, NULL, NULL, NULL, NULL, '103'),
(61, 25, 4, '45644862', 'YOBIT GOMEZ FERNANDEZ', 'KRM4-2026-WNNZ', '2026-08-05 00:59:13', '2026-08-05 00:59:13', 0, NULL, NULL, NULL, NULL, '104');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructors`
--

CREATE TABLE `instructors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `certificate_name` varchar(255) DEFAULT NULL,
  `signature_url` varchar(255) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `username`, `email`, `password`, `phone`, `specialty`, `bio`, `certificate_name`, `signature_url`, `avatar_url`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Ing. Juan Perez', NULL, 'rsirpa@gmail.com', '$2y$12$4CyMOruD7qu8ZavO69iWf.XVZwRgr5IMYa/oLi6gQrwKyHWQxh9ZK', '73723677', 'Ingeniero Industrial & Especialista', 'Más de 10 años de experiencia implementando sistemas de gestión de seguridad en empresas mineras y petroleras. Auditor líder ISO 45001 y docente universitario apasionado por la seguridad laboral.', NULL, NULL, NULL, 'active', '2025-12-24 14:23:50', '2026-05-13 10:32:30', 1),
(2, 'Gonzalo Galvez', NULL, 'ggalvez@gmail.com', '$2y$12$onjyGxDbcEB1q.d9qBzmzuci331UOAnpMbUp6RlzQ3D4c9vCsPV9.', '71234657', 'Ingeniero Ambiental', 'Más de 10 años de experiencia implementando sistemas de gestión de seguridad en empresas mineras y petroleras. Auditor líder ISO 45001 y docente universitario apasionado por la seguridad laboral.', NULL, NULL, NULL, 'active', '2025-12-24 14:46:46', '2026-05-13 10:33:04', 1),
(4, 'DRA. GLORIA CUNIA HUAMAN', NULL, 'INFO@CAPACITAPERU', '$2y$12$T8j/3UYsB9OMixr7jopdA.gEN2NEr6/PBCJ79ygV1Hn3uhbCP8Y5y', '+51944508218', 'Especialista en Neuroeducación, Educación Inclusiva y Primera Infancia', 'La Dra. Gloria Cunia Huamán es una destacada profesional que combina su formación como Licenciada en Educación Inicial y Psicóloga con una rigurosa trayectoria como investigadora científica. Posee una Maestría en Educación Inclusiva, una Maestría en Investigación y Docencia Universitaria, y una Segunda Especialidad en Neuroeducación.', 'DRA GLORIA CUNIA HUAMAN', NULL, NULL, 'active', '2026-06-16 06:20:38', '2026-06-18 00:08:23', 1),
(5, 'CLARINIZ QUISPE NAJARRO', NULL, 'gianella@gmail.com', '$2y$12$LG80UXFeMRvTySPFvnXOnOjeiZTE8rklNPwbBjKMR32FrUBbFHTai', '0000000000', 'SOCIAL', NULL, NULL, NULL, NULL, 'active', '2026-07-05 06:17:08', '2026-07-05 06:19:25', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 1,
  `score` int(11) DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `lesson_progress`
--

INSERT INTO `lesson_progress` (`id`, `student_id`, `lesson_id`, `completed`, `score`, `completed_at`, `created_at`, `updated_at`) VALUES
(5, 4, 200, 1, 0, '2025-12-24 21:40:27', '2025-12-24 21:40:27', '2025-12-24 21:40:27'),
(6, 2, 200, 1, 0, '2026-05-17 04:48:25', '2026-05-17 04:48:25', '2026-05-17 04:48:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_01_01_000000_create_lms_schema', 1),
(2, '2025_12_22_201845_add_json_fields_and_attendance_to_lms', 1),
(3, '2024_01_01_000000_create_categories_table', 2),
(4, '2024_01_02_create_category_course_table', 3),
(5, '2025_12_24_160756_add_details_to_enrollments_table', 4),
(6, '2024_01_03_add_scores_to_tables', 5),
(7, '2025_12_24_204947_create_attendances_table', 6),
(8, '2024_01_03_000000_create_project_submissions_table', 7),
(9, '2020_01_02_000000_create_lms_schema', 1),
(10, '2024_01_02_000000_add_resources_to_courses_table', 8),
(11, '2024_01_05_000000_add_discount_price_to_courses_table', 8),
(12, '2024_01_05_add_certificate_fields_to_instructors', 8),
(13, '2024_01_10_000000_add_exception_ids_to_promotions_table', 8),
(14, '2026_01_03_04964_tokens', 8),
(15, '2026_01_03_061856_add_new_fields_to_courses_table', 8),
(16, '2026_01_07_04561_add_benefits_table', 8),
(17, '2026_01_07_04561_add_promotions_table', 8),
(18, '2026_01_16_04561_permision', 8),
(19, '2026_01_17_125132_resetpass', 8),
(20, '2026_05_13_063419_create_advisors_table', 9),
(21, '2026_05_14_032910_create_settings_table', 10),
(22, '2026_05_14_043024_fix_certificates_auto_increment', 11),
(23, '2026_05_14_043140_fix_all_tables_auto_increment', 12),
(24, '2026_05_16_055904_create_individual_certificates_table', 13),
(25, '2026_05_16_062837_add_signatures_to_individual_certificates_table', 14),
(26, '2026_05_16_062843_create_individual_certificate_students_table', 15),
(27, '2026_05_18_152308_add_bio_to_students_table', 15),
(28, '2026_05_22_032435_add_custom_fields_to_individual_certificate_students_table', 16),
(29, '2026_05_22_040757_add_user_id_to_individual_certificate_students_table', 17),
(30, '2026_05_22_053905_add_type_to_courses_table', 18),
(31, '2026_05_23_000000_add_exception_ids_to_promotions_table_v2', 19),
(32, '2026_05_23_010000_fix_category_course_auto_increment', 20),
(33, '2026_05_31_180000_add_exam_answers_to_enrollments_table', 21),
(34, '2026_06_13_061325_create_backup_logs_table', 22),
(35, '2026_06_28_030923_add_user_id_to_various_tables', 23),
(36, '2026_06_28_032810_add_target_type_to_categories_table', 24),
(37, '2026_06_28_054700_create_claims_table', 25),
(39, '2026_07_02_000000_create_course_certificates_table', 26),
(40, '2026_07_02_050527_add_individual_certificate_id_to_course_certificates_table', 27);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `key`, `group`, `label`, `created_at`, `updated_at`) VALUES
(1, 'users.view', 'Gestión de Usuarios', 'Ver lista de usuarios', NULL, NULL),
(2, 'users.create', 'Gestión de Usuarios', 'Crear nuevos usuarios', NULL, NULL),
(3, 'users.edit', 'Gestión de Usuarios', 'Editar información de usuarios', NULL, NULL),
(4, 'users.delete', 'Gestión de Usuarios', 'Eliminar usuarios', NULL, NULL),
(5, 'users.permissions', 'Gestión de Usuarios', 'Gestionar Permisos', NULL, NULL),
(6, 'courses.view', 'Académico', 'Ver cursos', NULL, NULL),
(7, 'courses.create', 'Académico', 'Crear/Editar cursos', NULL, NULL),
(8, 'courses.publish', 'Académico', 'Publicar cursos', NULL, NULL),
(9, 'courses.grade', 'Académico', 'Calificar tareas', NULL, NULL),
(10, 'finance.view', 'Finanzas', 'Ver reportes financieros', NULL, NULL),
(11, 'finance.refund', 'Finanzas', 'Emitir reembolsos', NULL, NULL),
(12, 'settings.view', 'Sistema', 'Ver configuración', NULL, NULL),
(13, 'promotions.view', 'Marketing', 'Ver Promociones', '2026-06-28 06:42:18', '2026-06-28 06:42:18'),
(14, 'promotions.create', 'Marketing', 'Crear Promociones', '2026-06-28 06:42:18', '2026-06-28 06:42:18'),
(15, 'promotions.edit', 'Marketing', 'Editar Promociones', '2026-06-28 06:42:19', '2026-06-28 06:42:19'),
(16, 'promotions.delete', 'Marketing', 'Eliminar Promociones', '2026-06-28 06:42:19', '2026-06-28 06:42:19'),
(17, 'categories.view', 'Académico', 'Ver Categorías', '2026-06-28 06:42:20', '2026-06-28 06:42:20'),
(18, 'categories.manage', 'Académico', 'Gestionar Categorías', '2026-06-28 06:42:20', '2026-06-28 06:42:20'),
(19, 'students.view', 'Estudiantes', 'Ver Estudiantes', '2026-06-28 06:42:21', '2026-06-28 06:42:21'),
(20, 'students.manage', 'Estudiantes', 'Gestionar Estudiantes', '2026-06-28 06:42:21', '2026-06-28 06:42:21'),
(21, 'instructors.view', 'Docentes', 'Ver Docentes', '2026-06-28 06:42:22', '2026-06-28 06:42:22'),
(22, 'instructors.manage', 'Docentes', 'Gestionar Docentes', '2026-06-28 06:42:23', '2026-06-28 06:42:23'),
(23, 'enrollments.view', 'Ventas', 'Ver Inscripciones', '2026-06-28 06:42:23', '2026-06-28 06:42:23'),
(24, 'enrollments.manage', 'Ventas', 'Gestionar Inscripciones', '2026-06-28 06:42:24', '2026-06-28 06:42:24'),
(25, 'users.manage', 'Administración', 'Gestionar Staff', '2026-06-28 06:42:24', '2026-06-28 06:42:24'),
(26, 'individual-certificates.view', 'Certificación', 'Ver Certificados Individuales', '2026-06-28 06:44:23', '2026-06-28 06:44:23'),
(28, 'advisors.view', 'Contacto', 'Ver Asesores', '2026-06-28 06:44:24', '2026-06-28 06:44:24'),
(29, 'advisors.manage', 'Contacto', 'Gestionar Asesores', '2026-06-28 06:44:24', '2026-06-28 06:44:24'),
(30, 'backups.view', 'Sistema', 'Ver Backups', '2026-06-28 06:44:25', '2026-06-28 06:44:25'),
(31, 'backups.manage', 'Sistema', 'Gestionar Backups', '2026-06-28 06:44:25', '2026-06-28 06:44:25'),
(32, 'landing.edit', 'Sistema', 'Editar Landing', '2026-06-28 06:44:26', '2026-06-28 06:44:26'),
(33, 'individual-certificates.create', 'Certificación', 'Crear Certificados Individuales', '2026-06-28 06:54:56', '2026-06-28 06:54:56'),
(34, 'individual-certificates.edit', 'Certificación', 'Editar Certificados Individuales', '2026-06-28 06:54:57', '2026-06-28 06:54:57'),
(35, 'individual-certificates.delete', 'Certificación', 'Eliminar Certificados Individuales', '2026-06-28 06:54:57', '2026-06-28 06:54:57'),
(36, 'individual-certificates.students', 'Certificación', 'Gestionar Alumnos de Certificados', '2026-06-28 06:54:58', '2026-06-28 06:54:58'),
(37, 'claims.view', 'Reclamaciones', 'Ver Reclamaciones', '2026-06-28 09:49:35', '2026-06-28 09:49:35'),
(38, 'claims.manage', 'Reclamaciones', 'Gestionar Reclamaciones', '2026-06-28 09:49:36', '2026-06-28 09:49:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_user`
--

CREATE TABLE `permission_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permission_user`
--

INSERT INTO `permission_user` (`user_id`, `permission_id`) VALUES
(1, 26),
(1, 33),
(1, 34),
(1, 35),
(1, 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_submissions`
--

CREATE TABLE `project_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`files`)),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `score` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `discount_type` enum('percent','fixed') NOT NULL,
  `value` decimal(8,2) NOT NULL,
  `target_type` enum('all','category','course') NOT NULL,
  `target_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_ids`)),
  `exception_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`exception_ids`)),
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `code`, `discount_type`, `value`, `target_type`, `target_ids`, `exception_ids`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`, `user_id`) VALUES
(3, 'test', NULL, 'percent', 10.00, 'course', '[\"6\"]', '[]', '2026-05-23 07:45:00', '2026-05-25 08:44:00', 1, '2026-05-23 07:45:02', '2026-05-23 07:46:51', 1),
(5, 'MES DEL PADRE', NULL, 'fixed', 15.00, 'category', '[\"8\"]', '[]', '2026-06-17 04:50:00', '2026-07-01 04:51:00', 1, '2026-06-17 04:51:17', '2026-06-17 05:05:18', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registration_tokens`
--

CREATE TABLE `registration_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'new',
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registration_tokens`
--

INSERT INTO `registration_tokens` (`id`, `token`, `course_id`, `type`, `is_used`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'ouHIUqbXTImsqLNaaNytUcUu7drA2hmn', 1, 'new', 1, '2026-05-24 03:33:01', '2026-05-17 03:33:01', '2026-05-17 03:34:56'),
(2, 'r5vgxMlLfwcAel3si7PBRSm1XwHOi6wD', 1, 'new', 1, '2026-05-25 18:46:06', '2026-05-18 18:46:06', '2026-05-18 18:49:31'),
(3, 'W7YAmpOUUpbk85sfPBsKRvFDquFnOhHF', 2, 'new', 1, '2026-05-25 18:56:28', '2026-05-18 18:56:28', '2026-05-18 18:57:56'),
(4, 'rTiUSK8ZxAJMTCasquvBOksOWiBgrxLz', 1, 'new', 1, '2026-05-25 19:05:45', '2026-05-18 19:05:45', '2026-05-18 19:06:11'),
(5, 'qEaRmCG6prmWUpkS2leVvNkJtblLfmgg', 1, 'new', 1, '2026-05-25 19:19:16', '2026-05-18 19:19:16', '2026-05-18 19:19:42'),
(6, 'MVoGUBBYuBflaWc40pXTCn31fUyXi65K', 7, 'new', 1, '2026-06-08 01:46:26', '2026-06-01 01:46:26', '2026-06-01 01:48:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'CAPACITAPERU', '2026-05-14 07:35:22', '2026-05-17 02:53:53'),
(2, 'app_currency', 'S/', '2026-05-14 07:35:23', '2026-05-17 02:53:01'),
(3, 'app_country', 'Perú', '2026-05-14 07:35:23', '2026-05-14 07:43:21'),
(4, 'app_timezone', 'America/Lima', '2026-05-14 07:35:24', '2026-05-14 07:43:23'),
(5, 'app_logo', 'img/logo.png', '2026-05-14 07:35:24', '2026-05-14 07:35:24'),
(6, 'app_logo_white', 'img/logo-white.png', '2026-05-14 07:35:25', '2026-05-14 07:35:25'),
(7, 'individual_cert_executive_signature', 'storage/signatures/eoKXEeOlCQ7ewXjPW0BXyXqL5tzWaTP0NDuzvBhU.png', '2026-05-16 11:37:53', '2026-05-16 11:37:53'),
(8, 'individual_cert_academic_signature', 'storage/signatures/1CC7E54MrzFgnU3tjHrincbuWAQfFVm2ntd6bbWr.png', '2026-05-16 11:37:53', '2026-05-16 11:37:53'),
(9, 'nav_btn_text', 'Ingresar', '2026-06-13 11:02:36', '2026-06-13 11:02:36'),
(10, 'footer_desc', 'Líderes en capacitación técnica y profesional. Potenciamos tu talento con certificación de alto impacto nacional y respaldo institucional.', '2026-06-13 11:02:36', '2026-06-13 11:02:36'),
(11, 'social_facebook', '#', '2026-06-13 11:02:37', '2026-06-13 11:02:37'),
(12, 'social_instagram', '#', '2026-06-13 11:02:37', '2026-06-13 11:02:37'),
(13, 'social_linkedin', '#', '2026-06-13 11:02:38', '2026-06-13 11:02:38'),
(14, 'social_youtube', '#', '2026-06-13 11:02:38', '2026-06-13 11:02:38'),
(15, 'footer_col1_title', 'Formación', '2026-06-13 11:02:39', '2026-06-17 04:11:32'),
(16, 'footer_col1_l1_text', 'Explorar Cursos', '2026-06-13 11:02:40', '2026-06-13 11:02:40'),
(17, 'footer_col1_l1_url', '/catalogo', '2026-06-13 11:02:40', '2026-06-13 11:02:40'),
(18, 'footer_col1_l2_text', 'Especializaciones', '2026-06-13 11:02:41', '2026-06-13 11:02:41'),
(19, 'footer_col1_l2_url', '/catalogo', '2026-06-13 11:02:41', '2026-06-13 11:02:41'),
(20, 'footer_col1_l3_text', 'Para Empresas', '2026-06-13 11:02:42', '2026-06-13 11:02:42'),
(21, 'footer_col1_l3_url', '/contacto', '2026-06-13 11:02:42', '2026-06-13 11:02:42'),
(22, 'footer_col2_title', 'Recursos', '2026-06-13 11:02:43', '2026-06-13 11:02:43'),
(23, 'footer_col2_l1_text', 'Nuestra Historia', '2026-06-13 11:02:43', '2026-06-13 11:02:43'),
(24, 'footer_col2_l1_url', '/nosotros', '2026-06-13 11:02:44', '2026-06-13 11:02:44'),
(25, 'footer_col2_l2_text', 'Verificar Certificado', '2026-06-13 11:02:44', '2026-06-13 11:02:44'),
(26, 'footer_col2_l2_url', '/certificado', '2026-06-13 11:02:45', '2026-06-13 11:02:45'),
(27, 'footer_col2_l3_text', 'Preguntas Frecuentes', '2026-06-13 11:02:45', '2026-06-13 11:02:45'),
(28, 'footer_col2_l3_url', '#', '2026-06-13 11:02:46', '2026-06-13 11:02:46'),
(29, 'footer_col3_title', 'Atención', '2026-06-13 11:02:46', '2026-06-13 11:17:10'),
(30, 'footer_col3_email', 'capacitaperu08@gmail.com', '2026-06-13 11:02:47', '2026-06-13 11:02:47'),
(31, 'footer_col3_phone', '+51 972 539 712', '2026-06-13 11:02:47', '2026-06-13 11:02:47'),
(32, 'footer_copyright', '© 2026 CAPACITAPERU. Centro de Formación y Actualización Profesional. Todos los derechos reservados.', '2026-06-13 11:02:48', '2026-06-25 01:33:11'),
(33, 'home_hero_tag', 'Plataforma Educativa', '2026-06-13 11:02:48', '2026-06-13 11:02:48'),
(34, 'home_hero_title', 'Bienvenidos a <br> <span class=\"text-[#FF7A00]\">CAPACITAPERU</span>', '2026-06-13 11:02:49', '2026-06-13 11:02:49'),
(35, 'home_hero_desc', 'Tu espacio creado para responder a las nuevas demandas del mercado laboral. Ofrecemos capacitación especializada y accesible para profesionales, técnicos y estudiantes, respaldados por certificación reconocida a nivel nacional.', '2026-06-13 11:02:49', '2026-06-13 11:02:49'),
(36, 'home_hero_btn1_text', 'Ver Programas Disponibles', '2026-06-13 11:02:50', '2026-06-13 11:02:50'),
(37, 'home_hero_btn2_text', 'Validar Certificado', '2026-06-13 11:02:50', '2026-06-13 11:02:50'),
(38, 'home_hero_badge1_title', 'Certificación', '2026-06-13 11:02:51', '2026-06-13 11:02:51'),
(39, 'home_hero_badge1_desc', 'Oficial e Inmediata', '2026-06-13 11:02:51', '2026-06-13 11:02:51'),
(40, 'home_hero_badge2_title', 'Asesoría Activa', '2026-06-13 11:02:52', '2026-06-13 11:02:52'),
(41, 'home_hero_badge2_desc', 'Respuesta Inmediata', '2026-06-13 11:02:52', '2026-06-13 11:02:52'),
(42, 'home_hero_badge3_text', '100% Virtual', '2026-06-13 11:02:53', '2026-06-13 11:02:53'),
(43, 'home_about_tag', 'Sobre Nosotros', '2026-06-13 11:02:53', '2026-06-13 11:02:53'),
(44, 'home_about_title', 'Fortaleciendo el Futuro <br> Profesional de la Regiónn', '2026-06-13 11:02:54', '2026-07-02 20:58:46'),
(45, 'home_about_quote', '\"En CAPACITAPERU, nos comprometemos en fortalecer tus competencias e impulsamos tu crecimiento profesional, preparándote para afrontar con éxito los retos actuales y futuros.\"', '2026-06-13 11:02:54', '2026-06-13 11:02:54'),
(46, 'home_about_desc', 'Somos un centro de formación y actualización profesional creado para responder a las nuevas demandas del mercado laboral. Ofrecemos capacitación especializada y accesible para profesionales, técnicos, estudiantes y trabajadores de diversos sectores, respaldados por certificación reconocida a nivel nacional.', '2026-06-13 11:02:55', '2026-06-13 11:02:55'),
(47, 'home_about_badge_title', 'Nuestra Misión', '2026-06-13 11:02:56', '2026-06-13 11:02:56'),
(48, 'home_about_badge_desc', 'Fortalecer tus competencias e impulsar tu crecimiento profesional para los retos del futuro.', '2026-06-13 11:02:56', '2026-06-13 11:02:56'),
(49, 'home_about_stat1_val', '+10,000', '2026-06-13 11:02:57', '2026-06-13 11:02:57'),
(50, 'home_about_stat1_lbl', 'Estudiantes', '2026-06-13 11:02:57', '2026-06-13 11:02:57'),
(51, 'home_about_stat2_val', '+8', '2026-06-13 11:02:58', '2026-06-13 11:02:58'),
(52, 'home_about_stat2_lbl', 'Años de Exp.', '2026-06-13 11:02:58', '2026-06-13 11:02:58'),
(53, 'home_about_stat3_val', '+150', '2026-06-13 11:02:59', '2026-06-13 11:02:59'),
(54, 'home_about_stat3_lbl', 'Cursos Dictados', '2026-06-13 11:02:59', '2026-06-13 11:02:59'),
(55, 'home_about_stat4_val', '99%', '2026-06-13 11:03:00', '2026-06-13 11:03:00'),
(56, 'home_about_stat4_lbl', 'Satisfacción', '2026-06-13 11:03:00', '2026-06-13 11:03:00'),
(57, 'home_bento1_title', 'Eleva tu Carrera Profesional', '2026-06-13 11:03:01', '2026-06-13 11:03:01'),
(58, 'home_bento1_desc', 'Diseñado para profesionales que buscan entrar al ecosistema industrial y tecnológico de Perú. No necesitas experiencia previa, solo las ganas de aprender.', '2026-06-13 11:03:01', '2026-06-13 11:03:01'),
(59, 'home_bento2_title', 'Tu Ritmo', '2026-06-13 11:03:02', '2026-06-13 11:03:02'),
(60, 'home_bento2_sub', 'Acceso vitalicio 24/7', '2026-06-13 11:03:02', '2026-06-13 11:03:02'),
(61, 'home_bento3_title', 'QR Seguro', '2026-06-13 11:03:03', '2026-06-13 11:03:03'),
(62, 'home_bento3_sub', 'Verificación inmediata', '2026-06-13 11:03:03', '2026-06-13 11:03:03'),
(63, 'home_cat_tag', 'Explora Áreas', '2026-06-13 11:03:04', '2026-06-13 11:03:04'),
(64, 'home_cat_title', 'Especialízate en las <br> Mejores Categorías', '2026-06-13 11:03:04', '2026-06-13 11:03:04'),
(65, 'home_cat_desc', 'Descubre rutas de aprendizaje diseñadas para las demandas reales de la industria técnica peruana.', '2026-06-13 11:07:39', '2026-06-13 11:07:39'),
(66, 'home_pay_tag', 'Inscripción Simple', '2026-06-13 11:07:39', '2026-06-13 11:07:39'),
(67, 'home_pay_title', 'Métodos de Pago Seguros', '2026-06-13 11:07:39', '2026-06-13 11:07:39'),
(68, 'home_pay_c1_title', 'Métodos de Pago', '2026-06-13 11:07:40', '2026-06-28 09:31:47'),
(69, 'home_pay_c1_desc', 'Paga de forma inmediata escaneando nuestro código QR desde cualquier aplicación bancaria.', '2026-06-13 11:07:40', '2026-06-13 11:07:40'),
(70, 'home_pay_c2_title', 'Plin', '2026-06-13 11:07:40', '2026-06-15 19:32:02'),
(71, 'home_pay_c2_desc', 'Realiza tu pago de forma rápida y segura a través de tu billetera Plin.', '2026-06-13 11:07:40', '2026-06-15 19:51:34'),
(72, 'home_pay_c3_title', 'Tarjetas de Crédito/Débito', '2026-06-13 11:07:41', '2026-06-13 11:07:41'),
(73, 'home_pay_c3_desc', 'Aceptamos todas las tarjetas nacionales e internacionales con procesamiento seguro.', '2026-06-13 11:07:41', '2026-06-13 11:07:41'),
(74, 'home_cta_title', 'Comienza hoy tu camino hacia el éxito profesional', '2026-06-13 11:07:41', '2026-06-13 11:07:41'),
(75, 'home_cta_desc', 'Aprovecha las oportunidades laborales con las certificaciones que las empresas más buscann.', '2026-06-13 11:07:42', '2026-06-13 11:09:43'),
(76, 'home_cta_btn_text', 'Contactar por WhatsApp', '2026-06-13 11:07:42', '2026-06-13 11:07:42'),
(77, 'home_cta_whatsapp', '+51972539712', '2026-06-13 11:07:42', '2026-07-21 21:32:40'),
(78, 'courses_header_tag', 'Nuestra Oferta Académica', '2026-06-13 11:07:42', '2026-06-13 11:07:42'),
(79, 'courses_header_title', 'Especialízate <br><span class=\"text-[#FF7A00]\">Sin Límites</span>', '2026-06-13 11:07:43', '2026-06-13 11:07:43'),
(80, 'courses_header_desc', 'Programas diseñados para responder a las demandas reales del mercado laboral, con certificación nacional.', '2026-06-13 11:07:43', '2026-06-13 11:07:43'),
(81, 'courses_stat1_val', '+150', '2026-06-13 11:07:43', '2026-06-13 11:07:43'),
(82, 'courses_stat1_lbl', 'Cursos', '2026-06-13 11:07:43', '2026-06-13 11:07:43'),
(83, 'courses_stat2_val', '98%', '2026-06-13 11:07:44', '2026-06-13 11:07:44'),
(84, 'courses_stat2_lbl', 'Éxito', '2026-06-13 11:07:44', '2026-06-13 11:07:44'),
(85, 'courses_val1_title', 'Certificación Nacional', '2026-06-13 11:07:44', '2026-06-13 11:07:44'),
(86, 'courses_val1_desc', 'Validez Profesional', '2026-06-13 11:07:44', '2026-06-13 11:07:44'),
(87, 'courses_val2_title', 'Asesores Expertos', '2026-06-13 11:07:45', '2026-06-13 11:07:45'),
(88, 'courses_val2_desc', 'Acompañamiento Real', '2026-06-13 11:07:45', '2026-06-13 11:07:45'),
(89, 'courses_val3_title', 'Acceso de por Vida', '2026-06-13 11:07:45', '2026-06-13 11:07:45'),
(90, 'courses_val3_desc', 'A tu propio ritmo', '2026-06-13 11:07:45', '2026-06-13 11:07:45'),
(91, 'diplomas_header_tag', 'Nuestros Diplomados', '2026-06-13 11:07:46', '2026-06-13 11:07:46'),
(92, 'diplomas_header_title', 'Diplomados <br><span class=\"text-[#FF7A00]\">Profesionales</span>', '2026-06-13 11:07:46', '2026-06-13 11:07:46'),
(93, 'diplomas_header_desc', 'Programas avanzados de especialización con certificación oficial y validez nacional.', '2026-06-13 11:07:46', '2026-06-13 11:07:46'),
(94, 'diplomas_stat1_val', '+150', '2026-06-13 11:07:46', '2026-06-13 11:07:46'),
(95, 'diplomas_stat1_lbl', 'Cursos', '2026-06-13 11:07:47', '2026-06-13 11:07:47'),
(96, 'diplomas_stat2_val', '98%', '2026-06-13 11:07:47', '2026-06-13 11:07:47'),
(97, 'diplomas_stat2_lbl', 'Éxito', '2026-06-13 11:07:47', '2026-06-13 11:07:47'),
(98, 'about_hero_tag', 'Nuestra Identidad', '2026-06-13 11:07:47', '2026-06-13 11:07:47'),
(99, 'about_hero_title', 'Impulsando el <br> <span class=\"text-transparent bg-clip-text bg-gradient-to-r from-[#FF7A00] to-orange-300\">Potencial Profesional</span>', '2026-06-13 11:07:48', '2026-06-13 11:07:48'),
(100, 'about_hero_desc', 'En CapacitaPerú, transformamos la educación técnica en una herramienta real de crecimiento y éxito laboral.', '2026-06-13 11:07:48', '2026-06-13 11:07:48'),
(101, 'about_hist_title', 'Nuestra Historia', '2026-06-13 11:07:48', '2026-06-13 11:07:48'),
(102, 'about_hist_desc1', 'El Centro de Formación y Actualización Profesional – CapacitaPerú surge con el compromiso de responder a las nuevas demandas del mercado laboral, ofreciendo capacitación especializada y accesible para profesionales, técnicos, estudiantes y trabajadores de distintos sectores.', '2026-06-13 11:07:48', '2026-06-13 11:07:48'),
(103, 'about_hist_desc2', 'Respaldados con certificación reconocida a nivel nacional. Nuestro propósito es acompañar a cada participante en el fortalecimiento de sus competencias y en el impulso de su crecimiento profesional.', '2026-06-13 11:07:49', '2026-06-13 11:07:49'),
(104, 'about_hist_badge_title', '100%', '2026-06-13 11:07:49', '2026-06-13 11:07:49'),
(105, 'about_hist_badge_desc', 'Compromiso Real', '2026-06-13 11:07:49', '2026-06-13 11:07:49'),
(106, 'about_hist_stat1_val', '+5,000', '2026-06-13 11:07:49', '2026-06-13 11:07:49'),
(107, 'about_hist_stat1_lbl', 'Estudiantes Formados', '2026-06-13 11:07:50', '2026-06-13 11:07:50'),
(108, 'about_hist_stat2_val', '50+', '2026-06-13 11:07:50', '2026-06-13 11:07:50'),
(109, 'about_hist_stat2_lbl', 'Cursos Activos', '2026-06-13 11:07:50', '2026-06-13 11:07:50'),
(110, 'about_mis_title', 'Misión', '2026-06-13 11:07:50', '2026-06-13 11:07:50'),
(111, 'about_mis_desc', '\"Ofrecer programas de capacitación y actualización profesional accesibles y de alta calidad, enfocados en el desarrollo de habilidades prácticas y competencias clave para el éxito laboral. Buscamos ser un aliado estratégico para los profesionales que desean fortalecer su perfil y adaptarse a los cambios del mercado.\"', '2026-06-13 11:07:51', '2026-06-13 11:07:51'),
(112, 'about_vis_title', 'Visión', '2026-06-13 11:07:51', '2026-06-13 11:07:51'),
(113, 'about_vis_desc', '\"Consolidarnos como una empresa emergente líder en la formación profesional, reconocida por nuestra capacidad de transformar el potencial de los profesionales mediante una educación innovadora, flexible y accesible, que impulse su crecimiento en un mundo laboral en constante evolución.\"', '2026-06-13 11:07:51', '2026-06-13 11:07:51'),
(114, 'about_dif_title', '¿Qué nos hace diferentes?', '2026-06-13 11:07:52', '2026-06-13 11:07:52'),
(115, 'about_dif_c1_title', 'Docentes Expertos', '2026-06-13 11:07:52', '2026-06-13 11:07:52'),
(116, 'about_dif_c1_desc', 'Nuestros instructores no solo enseñan teoría, son profesionales en activo que comparten casos reales y experiencia de campo.', '2026-06-13 11:07:52', '2026-06-13 11:07:52'),
(117, 'about_dif_c2_title', 'Certificación Nacional', '2026-06-13 11:07:52', '2026-06-13 11:07:52'),
(118, 'about_dif_c2_desc', 'Emitimos certificados con respaldo institucional y validación mediante códigos QR, listos para tu perfil profesional.', '2026-06-13 11:07:53', '2026-06-13 11:07:53'),
(119, 'about_dif_c3_title', 'Acceso 24/7', '2026-06-13 11:07:53', '2026-06-13 11:07:53'),
(120, 'about_dif_c3_desc', 'Aprende a tu ritmo desde cualquier dispositivo. Nuestra plataforma está diseñada para adaptarse a tu estilo de vida.', '2026-06-13 11:07:53', '2026-06-13 11:07:53'),
(121, 'about_eq_m1_name', 'Ing. Deivid Chipana', '2026-06-13 11:07:53', '2026-06-13 11:07:53'),
(122, 'about_eq_m1_role', 'Fundador & CEO', '2026-06-13 11:07:54', '2026-06-13 11:07:54'),
(123, 'about_eq_m1_desc', 'Impulsando la visión tecnológica de CapacitaPerú.', '2026-06-13 11:07:54', '2026-06-13 11:07:54'),
(124, 'about_eq_m2_name', 'Ing. Ana Sola', '2026-06-13 11:07:54', '2026-06-13 11:07:54'),
(125, 'about_eq_m2_role', 'Dirección Académica', '2026-06-13 11:07:54', '2026-06-13 11:07:54'),
(126, 'about_eq_m2_desc', 'Experticia en calidad y formación técnica superior.', '2026-06-13 11:07:55', '2026-06-13 11:07:55'),
(127, 'about_eq_m3_name', 'Ing. Carlos V.', '2026-06-13 11:07:55', '2026-06-13 11:07:55'),
(128, 'about_eq_m3_role', 'Arquitectura Tecnológica', '2026-06-13 11:07:55', '2026-06-13 11:07:55'),
(129, 'about_eq_m3_desc', 'Garantizando la mejor experiencia en nuestra plataforma.', '2026-06-13 11:07:55', '2026-06-13 11:07:55'),
(130, 'about_eq_m4_name', 'Lic. Maria L.', '2026-06-13 11:07:56', '2026-06-13 11:07:56'),
(131, 'about_eq_m4_role', 'Éxito Estudiantil', '2026-06-13 11:07:56', '2026-06-13 11:07:56'),
(132, 'about_eq_m4_desc', 'Acompañando a nuestros alumnos en cada paso.', '2026-06-13 11:07:56', '2026-06-13 11:07:56'),
(133, 'about_cta_title', '¿Listo para transformar tu futuro?', '2026-06-13 11:07:56', '2026-06-13 11:07:56'),
(134, 'about_cta_desc', 'Únete a los miles de profesionales que ya están impulsando su carrera con CapacitaPerú.', '2026-06-13 11:07:57', '2026-06-13 11:07:57'),
(135, 'about_cta_btn1', 'Ver Programas', '2026-06-13 11:07:57', '2026-06-13 11:07:57'),
(136, 'about_cta_btn2', 'Contactar', '2026-06-13 11:07:57', '2026-06-13 11:07:57'),
(137, 'contact_hero_tag', 'Hablemos', '2026-06-13 11:07:58', '2026-06-13 11:07:58'),
(138, 'contact_hero_title', '¿Cómo podemos <br> <span class=\"text-transparent bg-clip-text bg-gradient-to-r from-[#FF7A00] to-orange-300\">Ayudarte Hoy?</span>', '2026-06-13 11:07:58', '2026-06-13 11:07:58'),
(139, 'contact_hero_desc', 'Nuestro equipo de asesores está listo para brindarte la información que necesitas para dar el siguiente paso en tu carrera.', '2026-06-13 11:07:58', '2026-06-13 11:07:58'),
(140, 'contact_info_title', 'Conéctate con Nosotros', '2026-06-13 11:07:58', '2026-06-13 11:07:58'),
(141, 'contact_info_desc', 'Ya sea que tengas una duda sobre un curso, necesites capacitación corporativa o simplemente quieras saludarnos, estamos a un clic de distancia.', '2026-06-13 11:07:59', '2026-06-13 11:07:59'),
(142, 'contact_info_c1_title', 'WhatsApp Oficial', '2026-06-13 11:07:59', '2026-06-13 11:07:59'),
(143, 'contact_info_c1_val', '+51 972 539 712', '2026-06-13 11:07:59', '2026-06-13 11:07:59'),
(144, 'contact_info_c1_lnk', '51972539712', '2026-06-13 11:07:59', '2026-06-13 11:07:59'),
(145, 'contact_info_c1_desc', 'Respuesta Inmediata', '2026-06-13 11:08:00', '2026-06-13 11:08:00'),
(146, 'contact_info_c2_title', 'Correo Electrónico', '2026-06-13 11:08:00', '2026-06-13 11:08:00'),
(147, 'contact_info_c2_val', 'capacitaperu08@gmail.com', '2026-06-13 11:08:00', '2026-06-13 11:08:00'),
(148, 'contact_info_c2_desc', 'Soporte Académico', '2026-06-13 11:08:00', '2026-06-13 11:08:00'),
(149, 'contact_blue_title', '¿Por qué contactarnos?', '2026-06-13 11:08:01', '2026-06-13 11:08:01'),
(150, 'contact_blue_bullets', 'Asesoría personalizada sobre tu plan de estudios.\r\nCotizaciones a medida para empresas y grupos.\r\nResolución de dudas técnicas y acceso a plataforma.', '2026-06-13 11:08:01', '2026-06-13 11:08:01'),
(151, 'contact_form_title', 'Envíanos un Mensaje', '2026-06-13 11:08:01', '2026-06-13 11:08:01'),
(152, 'contact_form_btn', 'Enviar Mensaje Ahora', '2026-06-13 11:08:01', '2026-06-13 11:08:01'),
(153, 'contact_hero_bg', 'storage/landing/CxGuTSHGLPqvxusz1l5mvOo1x6FCL9yEMn7pUTXs.jpg', '2026-06-13 11:19:46', '2026-06-13 11:19:46'),
(154, 'about_hist_img', 'storage/landing/bnKVpaulibQKuQXjPdqRDMYbypm7hGgB0xxycaTz.webp', '2026-06-13 11:33:23', '2026-06-13 11:33:23'),
(155, 'footer_razon_social', 'CENTRO DE FORMACION Y ACTUALIZACION PROFESIONAL-CAPACITAPERU S.A.C.', '2026-06-28 09:27:33', '2026-07-08 06:46:23'),
(156, 'footer_ruc', '20614554640', '2026-06-28 09:27:33', '2026-07-08 06:46:23'),
(157, 'social_net_1_type', 'facebook', '2026-06-28 09:27:34', '2026-06-28 09:27:34'),
(158, 'social_net_2_type', 'youtube', '2026-06-28 09:27:34', '2026-06-28 09:27:34'),
(159, 'social_net_3_type', 'instagram', '2026-06-28 09:27:34', '2026-06-28 09:27:34'),
(160, 'social_net_4_type', 'linkedin', '2026-06-28 09:27:34', '2026-07-21 21:30:47'),
(161, 'social_net_5_type', 'tiktok', '2026-06-28 09:27:35', '2026-06-28 09:27:35'),
(162, 'social_net_1_url', 'www.facebook.com/share/1BHqBhpuWA/', '2026-06-28 09:28:00', '2026-07-21 21:30:47'),
(163, 'home_pay_qr_desc', 'Escanea este código QR desde tu app bancaria favorita (Yape, Plin, o banca móvil) para realizar el pago de forma instantánea.', '2026-07-02 20:58:46', '2026-07-02 20:58:46'),
(164, 'social_net_2_url', 'youtube.com/@centrodeformacionactualizacion', '2026-07-21 21:30:47', '2026-07-21 21:30:47'),
(165, 'social_net_3_url', 'www.instagram.com/capacita.peru', '2026-07-21 21:30:47', '2026-07-21 21:30:47'),
(166, 'social_net_5_url', 'www.tiktok.com/@capacitaperu5', '2026-07-21 21:30:47', '2026-07-21 21:30:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `pin` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `ci_nit` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Bolivia',
  `avatar_url` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`id`, `name`, `username`, `email`, `password`, `force_pass_reset`, `pin`, `phone`, `ci_nit`, `city`, `country`, `avatar_url`, `bio`, `status`, `created_at`, `updated_at`, `user_id`) VALUES
(13, 'Gianella Migceli Roca Torres', 'gianella-migceli-roca-torres835', 'gianellamorat12@gmail.com', '$2y$12$yj.il.3c3qofzDB4//vZCugEVkmiOutY72wdr3ci0LT/QVz7GXdVW', 0, '$2y$12$8MolHkHTOKNWSZL1dHUWeO.QPCqeyL8JTWXjxLEDb78rFSN1XvNqK', '+51 978678318', '75812777', 'ayacuho', 'Perú', 'img/avatars/students/student_16.jpg', NULL, 'active', '2026-06-23 04:55:13', '2026-06-23 05:01:12', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `pin` varchar(255) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `force_pass_reset`, `role`, `pin`, `avatar_url`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin', 'admin@capacitaperu.com', NULL, '$2y$12$cu5GTW6/gzZZtL8953GGre0Y4HMBgKd4DZqcM.yzU/D0LMup3w56m', 0, 'superadmin', '$2y$12$Fo.4m4Y9S0/Dx2Dpq60eXOFKDma8wZp1A4DgkBCmOOILXAJyp17YG', 'https://www.capacitaperu.com/img/avatars/admin/admin_1.jpg', 'active', NULL, '2026-05-10 09:41:26', '2026-06-03 12:37:23'),
(2, 'Paola', 'gianella', 'gianella@gmail.com', NULL, '$2y$12$PWHfpFbUEBroY.y.qI3NiemxYj1W4dw5bXM/6aBAymUDWyZZjODoe', 0, 'admin', '$2y$12$LBzukMT1rwYa2douqox2feWyK9fBX9h23oaLN0zvxmP9Ex/1s9s8y', 'https://www.capacitaperu.com/img/avatars/admin/admin_13.jpg', 'active', 'irra7SiLJxjol5HUjeeMpTR4PoQwF6OvmZGkGfbSZo3bXjyF2T6GM3QNzfCS', '2026-05-18 17:42:36', '2026-06-16 05:44:31'),
(3, 'Carla', 'carmen', 'carmen@gmail.com', NULL, '$2y$12$koHYqU57UKa9MbivPC5LtuTPyvDf0EAwL/BPsN81aqo1mqdynqzV2', 0, 'admin', '$2y$12$OOEvpy5mffyV1V8F3u5JqewTPBj3/zBt8pnnQtPhcTo.wZWHR5Vfu', 'https://www.capacitaperu.com/img/avatars/admin/admin_11.jpg', 'active', 'RBi5s5atNMTDtW3JWvjQJ2KWjzV0abvLuC4LaFsWjpFvJDyxB3K3mp8mZODC', '2026-05-18 17:43:26', '2026-06-16 05:43:52'),
(4, 'Yuri', 'yuri', 'yuri@gmail.com', NULL, '$2y$12$k3UKrvparZkr.p89ejxh6euVNkS/C3K4RoT3MNr2xJT3WKrZzx1U6', 0, 'admin', '$2y$12$xEk5uoAkRzo0Bk5T/cIO4uju636C4p6YcJ408wEbx8qF1J1TiCXly', 'https://www.capacitaperu.com/img/avatars/admin/admin_2.jpg', 'active', NULL, '2026-05-18 17:44:05', '2026-06-16 05:44:43'),
(8, 'Jose', 'jose@capacitaperu', 'jose@capacitaperu', NULL, '$2y$12$/23xLwg8lYcgox34R4z1quVoWlX4cDLXTT2tNZzm1EjJQmcEiQaz2', 0, 'moderator', '$2y$12$aSouVhxHeasN8L8hWry7sO43lv/CKeBN2Ccq6kdEekjig4WeiAVy.', 'https://www.capacitaperu.com/img/avatars/admin/admin_1.jpg', 'active', NULL, '2026-06-17 04:35:42', '2026-06-17 04:38:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `advisors`
--
ALTER TABLE `advisors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advisors_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendances_student_id_topic_id_unique` (`student_id`,`topic_id`),
  ADD KEY `attendances_course_id_foreign` (`course_id`);

--
-- Indices de la tabla `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_records_topic_id_foreign` (`topic_id`),
  ADD KEY `attendance_records_course_id_topic_id_index` (`course_id`,`topic_id`),
  ADD KEY `attendance_records_student_dni_index` (`student_dni`);

--
-- Indices de la tabla `backup_logs`
--
ALTER TABLE `backup_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `category_course`
--
ALTER TABLE `category_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_course_course_id_foreign` (`course_id`),
  ADD KEY `category_course_category_id_foreign` (`category_id`);

--
-- Indices de la tabla `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificates_uuid_unique` (`uuid`),
  ADD KEY `certificates_student_id_foreign` (`student_id`),
  ADD KEY `certificates_course_id_foreign` (`course_id`);

--
-- Indices de la tabla `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`),
  ADD KEY `courses_instructor_id_foreign` (`instructor_id`),
  ADD KEY `courses_user_id_foreign` (`user_id`),
  ADD KEY `courses_individual_certificate_id_foreign` (`individual_certificate_id`),
  ADD KEY `courses_course_certificate_id_foreign` (`course_certificate_id`);

--
-- Indices de la tabla `course_certificates`
--
ALTER TABLE `course_certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_certificates_uuid_unique` (`uuid`),
  ADD KEY `course_certificates_course_id_foreign` (`course_id`),
  ADD KEY `course_certificates_instructor_id_foreign` (`instructor_id`),
  ADD KEY `course_certificates_user_id_foreign` (`user_id`),
  ADD KEY `course_certificates_individual_certificate_id_foreign` (`individual_certificate_id`);

--
-- Indices de la tabla `course_lessons`
--
ALTER TABLE `course_lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_lessons_course_id_foreign` (`course_id`),
  ADD KEY `course_lessons_topic_id_foreign` (`topic_id`);

--
-- Indices de la tabla `course_topics`
--
ALTER TABLE `course_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_topics_course_id_foreign` (`course_id`);

--
-- Indices de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollments_student_id_foreign` (`student_id`),
  ADD KEY `enrollments_course_id_foreign` (`course_id`),
  ADD KEY `enrollments_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `individual_certificates`
--
ALTER TABLE `individual_certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `individual_certificates_slug_unique` (`slug`),
  ADD KEY `individual_certificates_instructor_id_foreign` (`instructor_id`),
  ADD KEY `individual_certificates_category_id_foreign` (`category_id`),
  ADD KEY `individual_certificates_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `individual_certificate_students`
--
ALTER TABLE `individual_certificate_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `individual_certificate_students_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instructors_email_unique` (`email`),
  ADD UNIQUE KEY `instructors_username_unique` (`username`),
  ADD KEY `instructors_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_progress_student_id_foreign` (`student_id`),
  ADD KEY `lesson_progress_lesson_id_foreign` (`lesson_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_key_unique` (`key`);

--
-- Indices de la tabla `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indices de la tabla `project_submissions`
--
ALTER TABLE `project_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_submissions_course_id_foreign` (`course_id`),
  ADD KEY `project_submissions_student_id_foreign` (`student_id`);

--
-- Indices de la tabla `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `registration_tokens`
--
ALTER TABLE `registration_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_tokens_token_unique` (`token`),
  ADD KEY `registration_tokens_course_id_foreign` (`course_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD UNIQUE KEY `students_username_unique` (`username`),
  ADD KEY `students_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_student_id_foreign` (`student_id`),
  ADD KEY `wishlists_course_id_foreign` (`course_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `advisors`
--
ALTER TABLE `advisors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `backup_logs`
--
ALTER TABLE `backup_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `category_course`
--
ALTER TABLE `category_course`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `claims`
--
ALTER TABLE `claims`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `course_certificates`
--
ALTER TABLE `course_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `course_lessons`
--
ALTER TABLE `course_lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT de la tabla `course_topics`
--
ALTER TABLE `course_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `individual_certificates`
--
ALTER TABLE `individual_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `individual_certificate_students`
--
ALTER TABLE `individual_certificate_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `project_submissions`
--
ALTER TABLE `project_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registration_tokens`
--
ALTER TABLE `registration_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `advisors`
--
ALTER TABLE `advisors`
  ADD CONSTRAINT `advisors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_course_certificate_id_foreign` FOREIGN KEY (`course_certificate_id`) REFERENCES `course_certificates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_individual_certificate_id_foreign` FOREIGN KEY (`individual_certificate_id`) REFERENCES `individual_certificates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `course_certificates`
--
ALTER TABLE `course_certificates`
  ADD CONSTRAINT `course_certificates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `course_certificates_individual_certificate_id_foreign` FOREIGN KEY (`individual_certificate_id`) REFERENCES `individual_certificates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_certificates_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `course_certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `individual_certificates`
--
ALTER TABLE `individual_certificates`
  ADD CONSTRAINT `individual_certificates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `individual_certificates_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `individual_certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `individual_certificate_students`
--
ALTER TABLE `individual_certificate_students`
  ADD CONSTRAINT `individual_certificate_students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `instructors`
--
ALTER TABLE `instructors`
  ADD CONSTRAINT `instructors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `project_submissions`
--
ALTER TABLE `project_submissions`
  ADD CONSTRAINT `project_submissions_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `registration_tokens`
--
ALTER TABLE `registration_tokens`
  ADD CONSTRAINT `registration_tokens_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
