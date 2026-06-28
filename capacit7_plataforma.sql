-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-06-2026 a las 04:47:38
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `advisors`
--

INSERT INTO `advisors` (`id`, `name`, `position`, `phone`, `avatar_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Carmen Castillo', 'Asesor Académico', '+51965244780', 'https://ui-avatars.com/api/?name=Asesor&background=213A57&color=fff', 1, '2026-05-13 10:39:34', '2026-06-01 01:35:08'),
(2, 'Yuri Janampa', 'Ventas', '+51972539712', 'https://ui-avatars.com/api/?name=Asesor&background=213A57&color=fff', 1, '2026-05-13 10:41:06', '2026-06-01 01:35:03'),
(3, 'Gianella', 'Ventas', '+59173723677', 'https://ui-avatars.com/api/?name=Asesor&background=213A57&color=fff', 1, '2026-05-17 03:54:44', '2026-05-17 03:54:44');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Salud', 'salud', NULL, NULL, 1, '2025-12-24 14:42:48', '2026-05-16 12:51:16'),
(2, 'Emprendimiento', 'emprendimiento', NULL, NULL, 1, '2025-12-24 14:43:03', '2025-12-24 14:43:03'),
(3, 'Inocuidad Alimentaria', 'inocuidad-alimentaria', NULL, NULL, 1, '2025-12-24 14:43:11', '2025-12-24 14:43:11'),
(4, 'Medio Ambiente', 'medio-ambiente', 'Área para ingenieros ambientales y sobre todo consultores ambientales.', NULL, 1, '2025-12-24 14:43:17', '2025-12-27 12:35:56'),
(5, 'Multiárea', 'multiarea', NULL, NULL, 1, '2025-12-24 14:43:24', '2025-12-24 14:43:24'),
(6, 'Seguridad y Salud Ocupacional', 'seguridad-y-salud-ocupacional', NULL, NULL, 1, '2025-12-24 14:43:31', '2025-12-24 14:43:31'),
(7, 'Especialidad', 'especialidad', NULL, NULL, 1, '2026-05-17 05:14:24', '2026-05-17 05:14:24');

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
(4, 7, 6, NULL, NULL);

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
  `resources` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`resources`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`id`, `type`, `title`, `slug`, `description`, `short_description`, `price`, `duration`, `metadata`, `benefits`, `learning_outcomes`, `discount_price`, `cover_image`, `instructor_id`, `level`, `category`, `status`, `featured`, `evaluation_config`, `certificate_template`, `attendance_config`, `created_at`, `updated_at`, `resources`) VALUES
(1, 'course', 'Crianza y Manejo de Gallinas', 'curso-694a930fb1834', 'COMPETENCIAS A OBTENER\r\n\r\nAl finalizar el curso, los participantes serán capaces de seleccionar adecuadamente gallinas ponedoras según criterios productivos, implementar prácticas efectivas para la recepción y manejo inicial de pollitos, establecer condiciones óptimas de espacio, alimentación y ambiente para las aves, prevenir y controlar plagas y enfermedades comunes, así como evaluar los costos de producción y aplicar principios de sostenibilidad en la crianza.', NULL, 207.00, 45, '{\"start_date\":\"2026-05-14\",\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null,\"registration_number\":\"100\",\"end_date\":\"2026-05-16\"}', NULL, NULL, NULL, 'storage/courses/iiIdY8QabStIQBqBe2fFj9jRmxzulG7EuS9xlFO1.webp', 1, 'Intermedio', 'Seguridad', 'published', 0, '{\"min_score\":\"51\",\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":true,\"final_exam_data\":{\"start_date\":\"2025-12-24T13:33\",\"end_date\":\"2025-12-26T13:33\",\"instructions\":\"<h1>Holaa<\\/h1><p>como esas<\\/p>\",\"questions\":[{\"type\":\"single\",\"text\":\"\",\"points\":\"40\",\"options\":\"a,b,c\",\"correct\":null},{\"type\":\"single\",\"text\":\"\",\"points\":\"40\",\"options\":\"a,b,c\",\"correct\":\"b\"}]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"2025-12-25T13:47\",\"end_date\":\"2025-12-26T13:47\",\"instructions\":\"<p>qweert<\\/p>\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[\"docx\",\"pdf\",\"images\"]}}', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Mvn2PiW8nqY9hXmXk2INfUupb1eSFWvhxlxiqmRN.jpg\",\"elements\":[{\"id\":\"el_1778731339587\",\"type\":\"student_name\",\"x\":215.3678847262248154947883449494838714599609375,\"y\":249.8370461095100836246274411678314208984375,\"style\":{\"fontSize\":\"30px\",\"color\":\"#000000\",\"fontFamily\":\"font-lato\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"690px\"},\"content\":[]},{\"id\":\"el_1778731557969\",\"type\":\"qr\",\"x\":898.23000000000001818989403545856475830078125,\"y\":518.451999999999998181010596454143524169921875,\"style\":{\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontFamily\":\"font-lato\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"160px\",\"height\":\"160px\"},\"content\":[]},{\"id\":\"el_1779426674813\",\"type\":\"student_name\",\"x\":246.66699999999997316990629769861698150634765625,\"y\":250,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"625.3333333333334px\",\"height\":\"34.666666666666664px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779426685678\",\"type\":\"course_title\",\"x\":418.3333333333333712289459072053432464599609375,\"y\":300,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"Crianza y Manejo de Gallinas\"},{\"id\":\"el_1779426700862\",\"type\":\"certificate_body\",\"x\":203.333333333333342807236476801335811614990234375,\"y\":350,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"725px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Realizado el <b>14 de mayo del 2026<\\/b> al <b>16 de mayo del 2026<\\/b>, con una duraci\\u00f3n de <b>45 horas acad\\u00e9micas<\\/b>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779426708014\",\"type\":\"issue_date\",\"x\":360,\"y\":493.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 16 de mayo del 2026<\\/p>\"},{\"id\":\"el_1779426736815\",\"type\":\"executive_signature\",\"x\":264.99966666666665560114779509603977203369140625,\"y\":566.6670000000000300133251585066318511962890625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"200px\"},\"content\":{\"name\":\"Director Ejecutivo\",\"role\":\"DIRECTOR EJECUTIVO\",\"size\":80,\"nameStyle\":{\"fontSize\":\"14px\",\"color\":\"#000000\",\"fontWeight\":\"bold\"},\"roleStyle\":{\"fontSize\":\"10px\",\"color\":\"#666666\"}}},{\"id\":\"el_1779426740471\",\"type\":\"academic_signature\",\"x\":585,\"y\":568.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"200px\"},\"content\":{\"name\":\"Director Acad\\u00e9mico\",\"role\":\"DIRECTOR ACAD\\u00c9MICO\",\"size\":80,\"nameStyle\":{\"fontSize\":\"14px\",\"color\":\"#000000\",\"fontWeight\":\"bold\"},\"roleStyle\":{\"fontSize\":\"10px\",\"color\":\"#666666\"}}},{\"id\":\"el_1779426759111\",\"type\":\"registration_number\",\"x\":913.333666666666658784379251301288604736328125,\"y\":463.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"118.33333333333331px\",\"height\":\"20px\"},\"content\":\"<p>Reg. N\\u00b0 100<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/QUWRIj5KOKDKwj7RHzy9uJsTAjJ2we2YeDUVxzxY.jpg\",\"elements2\":[{\"id\":\"el_1779426779832\",\"type\":\"qr\",\"x\":896.66733333333331756875850260257720947265625,\"y\":514.99966666666659875772893428802490234375,\"style\":{\"width\":\"161.66666666666669px\",\"height\":\"161.66666666666669px\"},\"content\":\"\"}]}', '{\"scope\":\"all\",\"questions\":[{\"type\":\"text\",\"title\":\"Como evaluas al docente\",\"options\":null},{\"type\":\"text\",\"title\":\"Que te parecio la sesion de zoom\",\"options\":null},{\"type\":\"choice\",\"title\":\"Que te parecio el curso\",\"options\":\"Bueno, Regular, Malo\"}],\"score\":20}', '2025-12-23 13:03:11', '2026-05-23 07:44:25', '{\"materials\":null,\"presentations\":null}'),
(2, 'course', 'Licenciamiento Ambiental para Edificios', 'licenciamiento-ambiental-para-edificios-694fcdc843311', '	Normativa ambiental y Resolución Secretarial\r\n	Planilla del Manifiesto Ambiental e identificación de las actividades \r\n	Flujograma de procesos según tipo de actividad\r\n	Identificación de impactos ambientales\r\n	Plan de adecuación ambiental\r\n	Programa de aplicación y seguimiento ambiental\r\n	Plan de contingencias \r\n	Plan de cierre y abandono\r\n	Casos de estudio para distintas actividades\r\n	Elaboración de cotizaciones y contratos', 'El curso dota al profesional de las competencias necesarias para obtener la Licencia Ambiental (LA) de actividades urbanas de alto impacto. Los participantes logran un dominio técnico-legal para elaborar el Manifiesto Ambiental (MA), planificar la corrección mediante el PAA-PASA, gestionar riesgos (Plan de contingencias) y el plan de cierre. El enfoque práctico incluye el uso de MA’s editables, habilidades de negocio (cotizaciones/contratos), y una certificación de 30 horas.', 200.00, NULL, '{\"start_date\":null,\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', NULL, NULL, NULL, 'storage/courses/tOAXeHFr4xzn6vUN3JmfJFXqpZ5dOYUvwGvjnD01.jpg', 1, 'Intermedio', NULL, 'published', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":true,\"final_exam_data\":{\"start_date\":\"2025-12-30T08:18\",\"end_date\":\"2025-12-31T08:18\",\"instructions\":\"\",\"questions\":[{\"type\":\"single\",\"text\":\"\\u00bfQue categor\\u00eda de RENCA puede elaborar los MA?\",\"points\":\"100\",\"options\":\"A,B,C,D\",\"correct\":null}]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', '{\"elements\":[{\"id\":\"el_1778728907823\",\"type\":\"image\",\"x\":133.75,\"y\":123.74999999999997,\"style\":{\"width\":\"150px\",\"height\":\"150px\"},\"src\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/Mvn2PiW8nqY9hXmXk2INfUupb1eSFWvhxlxiqmRN.jpg\",\"content\":[]},{\"id\":\"el_1778728920999\",\"type\":\"student_name\",\"x\":186.25,\"y\":325,\"style\":{\"fontSize\":\"30px\",\"color\":\"#000000\",\"fontFamily\":\"font-lato\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":[]}]}', '{\"scope\":\"all\",\"questions\":[{\"type\":\"choice\",\"title\":\"COmo estuvo la clase\",\"options\":\"Mala, Buena, Regular, P\\u00e9sima\"}]}', '2025-12-27 12:15:04', '2026-05-14 07:22:13', '{\"materials\":null,\"presentations\":null}'),
(3, 'course', 'CONTROL DE BROTES DE VECTORES Y RESERVORIOS DE LEPTOSPIROSIS, DENGUE Y MALARIA', 'control-de-brotes-de-vectores-y-reservorios-de-leptospirosis-dengue-y-malaria-6a0b3234748d1', NULL, NULL, 0.00, NULL, '{\"start_date\":null,\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', NULL, NULL, NULL, NULL, NULL, 'Básico', NULL, 'draft', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":false,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', NULL, '{\"scope\":\"all\",\"questions\":[]}', '2026-05-18 19:37:24', '2026-05-18 19:37:24', '{\"materials\":null,\"presentations\":null}'),
(6, 'diploma', 'Diplomado Ganaderia', 'diplomado-ganaderia-6a111cefe6d3e', NULL, NULL, 1500.00, 150, '{\"registration_number\":\"100\",\"start_date\":\"2026-05-27\",\"end_date\":\"2026-06-29\",\"modality\":\"Virtual\",\"planning\":null,\"whatsapp_group\":null}', NULL, NULL, 1350.00, NULL, 1, 'Básico', NULL, 'published', 0, '{\"min_score\":70,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":false,\"final_exam_data\":{\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"questions\":[]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', NULL, '{\"scope\":\"all\",\"questions\":[]}', '2026-05-23 07:20:15', '2026-05-23 07:46:51', '{\"materials\":null,\"presentations\":null}'),
(7, 'course', 'Especialista en Seguridad Industrial', 'especialisat-en-seguridad-industrial-6a1ca5387ec12', NULL, NULL, 150.00, 80, '{\"registration_number\":\"101\",\"start_date\":\"2026-06-01\",\"end_date\":\"2026-06-03\",\"modality\":\"Virtual\",\"planning\":\"<p>Nota del cuso<\\/p>\",\"whatsapp_group\":null}', NULL, NULL, NULL, 'storage/courses/MCQkX8HKptynKs0IDYP9hSmxW7byi8caxBlZQdhl.jpg', 2, 'Básico', NULL, 'published', 0, '{\"min_score\":51,\"attendance_weight\":20,\"final_assessment_weight\":80,\"final_exam_enabled\":true,\"final_exam_data\":{\"start_date\":\"2026-05-31T17:00\",\"end_date\":\"2026-05-31T19:00\",\"instructions\":\"<p>No copiar<\\/p>\",\"questions\":[{\"type\":\"single\",\"text\":\"Cuanto es 2+2\",\"points\":\"50\",\"options\":\"2,5,4\",\"correct\":\"4\"},{\"type\":\"single\",\"text\":\"De que color es el Sol\",\"points\":\"30\",\"options\":\"Azul, Rojo, Verde, Amarillo\",\"correct\":\"Amarillo\"},{\"type\":\"single\",\"text\":\"Cuantas esquinas tiene un trinagulo\",\"points\":\"20\",\"options\":\"4,6,3\",\"correct\":\"3\"}]},\"final_project_enabled\":false,\"final_project_data\":{\"score\":\"100\",\"start_date\":\"\",\"end_date\":\"\",\"instructions\":\"\",\"max_files\":\"1\",\"max_file_size\":\"5\",\"file_types\":[]}}', '{\"pages\":1,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/CP5uYEaRhpnvgPZDRVxpQafxKFiOHZ8XIDDTu5ZQ.png\",\"elements\":[{\"id\":\"el_1780262850134\",\"type\":\"student_name\",\"x\":380.00033333333334439885220490396022796630859375,\"y\":338.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"637px\",\"height\":\"29.666666666666668px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780262859960\",\"type\":\"course_title\",\"x\":490,\"y\":411.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#284c98\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>Especialista en Seguridad Industrial<\\/p>\"},{\"id\":\"el_1780262876407\",\"type\":\"certificate_body\",\"x\":396.66666666666668561447295360267162322998046875,\"y\":453.33300000000002683009370230138301849365234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"628.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"96.66666666666666px\"},\"content\":\"<p>Realizado el <strong>1 de junio del 2026<\\/strong> al <strong>3 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>80 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780262888696\",\"type\":\"issue_date\",\"x\":498.33300000000002683009370230138301849365234375,\"y\":573.333666666666658784379251301288604736328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 3 de junio del 2026<\\/p>\"},{\"id\":\"el_1780262900087\",\"type\":\"qr\",\"x\":145,\"y\":618.3333333333333712289459072053432464599609375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1780262905663\",\"type\":\"registration_number\",\"x\":78.3333333333333285963817615993320941925048828125,\"y\":588.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#ffffff\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"250px\",\"height\":\"20px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":null,\"elements2\":[]}', '{\"scope\":\"all\",\"questions\":[{\"type\":\"choice\",\"title\":\"\\u00bfTe gusto el curso?\",\"options\":\"Si, No\"},{\"type\":\"choice\",\"title\":\"Caunto calificas al instructor\",\"options\":\"Bueno, Regular, Malo\"}]}', '2026-06-01 01:16:40', '2026-06-01 02:10:30', '{\"materials\":null,\"presentations\":null}');

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
(211, 7, 155, 'Nueva Lección', 'nueva-leccion-6a1ca7ac86751', 'video', '<p>Esta es la descripcion de la clase</p>', 'video_first', 'https://www.youtube.com/watch?v=6FZG213NJPw', 60, NULL, 0, 0, '2026-06-01 01:27:08', '2026-06-01 01:27:08');

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
(155, 7, 'Nuevo Módulo', NULL, 1, '2026-06-01 01:27:08', '2026-06-01 01:27:08');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `academic_signature` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `individual_certificates`
--

INSERT INTO `individual_certificates` (`id`, `title`, `slug`, `registration_number`, `hours`, `instructor_id`, `category_id`, `start_date`, `end_date`, `certificate_template`, `cover_image`, `status`, `created_at`, `updated_at`, `executive_signature`, `academic_signature`) VALUES
(4, 'CONTROL DE BROTES DE VECTORES Y RESERVORIOS DE LEPTOSPIROSIS, DENGUE Y MALARIA', 'control-de-brotes-de-vectores-y-reservorios-de-leptospirosis-dengue-y-malaria-6a0b3115664de', '201', 90, 1, 4, '2026-01-12', '2026-02-10', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/5lxal5kpGGeDRERirWE2AnpkYQQXo00GZUpJ9W2e.png\",\"elements\":[{\"id\":\"el_1779118418420\",\"type\":\"qr\",\"x\":126.6666666666666714036182384006679058074951171875,\"y\":601.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1779118451860\",\"type\":\"certificate_body\",\"x\":368.333666666666658784379251301288604736328125,\"y\":431.66733333333331756875850260257720947265625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"585.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"83.33333333333333px\"},\"content\":\"<p>Realizado el <strong>12 de enero del 2026<\\/strong> al <strong>10 de febrero del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779118484588\",\"type\":\"issue_date\",\"x\":590,\"y\":560,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 10 de febrero del 2026.<\\/p>\"},{\"id\":\"el_1779118740061\",\"type\":\"text\",\"x\":426.66666666666668561447295360267162322998046875,\"y\":316.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\",\"fontStyle\":\"italic\"},\"content\":\"<p>HERMOZA PALOMINO, CRISTEL DANE\\u00c9<\\/p>\"}]}', NULL, 'published', '2026-05-18 19:32:37', '2026-05-18 19:39:35', NULL, NULL),
(5, 'SANEAMIENTO AMBIENTAL Y CONTROL INTEGRAL DE VECTORES Y RESERVORIOS DE ENFERMEDADES METAXÉNICAS Y ZOONOTICAS', 'saneamiento-ambiental-y-control-integral-de-vectores-y-reservorios-de-enfermedades-metaxenicas-y-zoonoticas-6a0b3a379b4ae', '301', 90, 1, 4, '2026-02-23', '2026-03-20', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/lv78QuMNd278NsYfBc7Kcf89u0YYTX9OQqUOXLvO.png\",\"elements\":[{\"id\":\"el_1779120723544\",\"type\":\"qr\",\"x\":123.333333333333342807236476801335811614990234375,\"y\":606.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1779120730211\",\"type\":\"student_name\",\"x\":490,\"y\":311.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779120822501\",\"type\":\"certificate_body\",\"x\":383.33333333333331438552704639732837677001953125,\"y\":460,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"17px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"543.3333333333333px\",\"fontStyle\":\"italic\",\"height\":\"60px\"},\"content\":\"<p>Realizado el <strong>23 de febrero del 2026<\\/strong> al <strong>20 de marzo del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779120838978\",\"type\":\"issue_date\",\"x\":573.3333333333333712289459072053432464599609375,\"y\":568.3339999999999463398125953972339630126953125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"18px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 20 de marzo del 2026.<\\/p>\"}]}', NULL, 'published', '2026-05-18 20:11:35', '2026-05-18 20:15:04', NULL, NULL),
(6, 'ENFERMEDADES METAXÉNICAS Y ZOONOSIS', 'enfermedades-metaxenicas-y-zoonosis-6a0b3c6d6155c', '401', 90, 1, 4, '2026-04-20', '2026-05-11', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/ZqqRnp5Jco9RDXziooiVC3fshmidluO9CvqqBMTO.png\",\"elements\":[{\"id\":\"el_1779121370114\",\"type\":\"qr\",\"x\":121.6666666666666714036182384006679058074951171875,\"y\":591.666666666666742457891814410686492919921875,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1779121406516\",\"type\":\"student_name\",\"x\":471.66666666666668561447295360267162322998046875,\"y\":318.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779121419228\",\"type\":\"certificate_body\",\"x\":343.33300000000002683009370230138301849365234375,\"y\":440,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"600px\",\"fontStyle\":\"italic\",\"height\":\"85px\"},\"content\":\"<p>Realizado el <strong>20 de abril del 2026<\\/strong> al <strong>11 de mayo del 2026<\\/strong>, con una duraci\\u00f3n de <strong>88 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779121432322\",\"type\":\"issue_date\",\"x\":590,\"y\":563.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 11 de mayo del 2026<\\/p>\"}]}', NULL, 'published', '2026-05-18 20:21:01', '2026-05-18 20:25:34', NULL, NULL),
(7, 'SISTEMA INTEGRADO DE ADMINISTRACIÓN FINANCIERA - SIAF', 'sistema-integrado-de-administracion-financiera-siaf-6a0b62f89acaa', '101', 130, 2, 7, '2026-02-09', '2026-04-06', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/DjE95DypXzqZOb2levdrPOsOdkueC6MgB72KLCK4.png\",\"elements\":[{\"id\":\"el_1779131180776\",\"type\":\"course_title\",\"x\":313.333666666666658784379251301288604736328125,\"y\":391.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"22px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>SISTEMA INTEGRADO DE ADMINISTRACI\\u00d3N FINANCIERA - SIAF<\\/p>\"},{\"id\":\"el_1779131231532\",\"type\":\"certificate_body\",\"x\":316.66666666666668561447295360267162322998046875,\"y\":423.33266666666668243124149739742279052734375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"680px\",\"fontStyle\":\"italic\",\"height\":\"95px\"},\"content\":\"<p>Realizado el <strong>9 de febrero del 2026<\\/strong> al <strong>6 de abril del 2026<\\/strong>, con una duraci\\u00f3n de <strong>130 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779131259709\",\"type\":\"student_name\",\"x\":503.3333333333333712289459072053432464599609375,\"y\":316.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779131268978\",\"type\":\"issue_date\",\"x\":643.3333333333333712289459072053432464599609375,\"y\":555,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 18 de mayo del 2026<\\/p>\"},{\"id\":\"el_1779131280625\",\"type\":\"qr\",\"x\":120,\"y\":593.3333333333333712289459072053432464599609375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}]}', NULL, 'published', '2026-05-18 23:05:28', '2026-05-18 23:08:44', NULL, NULL),
(8, 'OFIMÁTICA', 'ofimatica-6a0c80d5cb633', '501', 90, 3, 5, '2025-11-10', '2025-11-28', '{\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/CP5uYEaRhpnvgPZDRVxpQafxKFiOHZ8XIDDTu5ZQ.png\",\"elements\":[{\"id\":\"el_1779204404077\",\"type\":\"student_name\",\"x\":553.3333333333333712289459072053432464599609375,\"y\":335,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779204415372\",\"type\":\"certificate_body\",\"x\":396.66699999999997316990629769861698150634765625,\"y\":459.9996666666667124445666559040546417236328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"608.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"73.33333333333333px\"},\"content\":\"<p>Realizado el <strong>10 de noviembre del 2025<\\/strong> al <strong>28 de noviembre del 2025<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779204430212\",\"type\":\"issue_date\",\"x\":615.00033333333340124227106571197509765625,\"y\":595.00033333333340124227106571197509765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 28 de noviembre del 2025.<\\/p>\"},{\"id\":\"el_1779204477543\",\"type\":\"course_title\",\"x\":603.333666666666658784379251301288604736328125,\"y\":411.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-opensans\",\"fontSize\":\"36px\",\"color\":\"#222bd2\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>OFIM\\u00c1TICA<\\/p>\"},{\"id\":\"el_1779204537081\",\"type\":\"qr\",\"x\":148.333333333333342807236476801335811614990234375,\"y\":628.3333333333333712289459072053432464599609375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"}]}', NULL, 'published', '2026-05-19 19:25:09', '2026-05-19 19:29:58', NULL, NULL),
(9, 'Curso de pruebaa', 'curso-de-prueba-6a0fc0adbd821', '101', 50, NULL, 1, '2026-05-21', '2026-05-24', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Mvn2PiW8nqY9hXmXk2INfUupb1eSFWvhxlxiqmRN.jpg\",\"elements\":[{\"id\":\"el_1779417537713\",\"type\":\"student_name\",\"x\":234.99966666666665560114779509603977203369140625,\"y\":258.3329999999999699866748414933681488037109375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"653.6666666666667px\",\"height\":\"26.333333333333332px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1779417555409\",\"type\":\"course_title\",\"x\":485,\"y\":313.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"Curso de pruebaa\"},{\"id\":\"el_1779417563417\",\"type\":\"certificate_body\",\"x\":236.667000000000001591615728102624416351318359375,\"y\":356.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"725px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Realizado el <strong>21 de mayo del 2026<\\/strong> al <strong>24 de mayo del 2026<\\/strong>, con una duraci\\u00f3n de <strong>50 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1779417584882\",\"type\":\"qr\",\"x\":898.3329999999999699866748414933681488037109375,\"y\":521.6666666666666287710540927946567535400390625,\"style\":{\"width\":\"158.33333333333334px\",\"height\":\"158.33333333333334px\"},\"content\":\"\"},{\"id\":\"el_1779417596619\",\"type\":\"issue_date\",\"x\":383.3333333333333712289459072053432464599609375,\"y\":495,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 24 de mayo del 2026<\\/p>\"},{\"id\":\"el_1779417602578\",\"type\":\"executive_signature\",\"x\":240,\"y\":545,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"200px\"},\"content\":{\"name\":\"Director Ejecutivo\",\"role\":\"DIRECTOR EJECUTIVO\",\"size\":80,\"nameStyle\":{\"fontSize\":\"14px\",\"color\":\"#000000\",\"fontWeight\":\"bold\"},\"roleStyle\":{\"fontSize\":\"10px\",\"color\":\"#666666\"}}},{\"id\":\"el_1779417606218\",\"type\":\"academic_signature\",\"x\":596.666666666666742457891814410686492919921875,\"y\":561.666666666666742457891814410686492919921875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"200px\"},\"content\":{\"name\":\"Director Acad\\u00e9mico\",\"role\":\"DIRECTOR ACAD\\u00c9MICO\",\"size\":80,\"nameStyle\":{\"fontSize\":\"14px\",\"color\":\"#000000\",\"fontWeight\":\"bold\"},\"roleStyle\":{\"fontSize\":\"10px\",\"color\":\"#666666\"}}},{\"id\":\"el_1779419532721\",\"type\":\"registration_number\",\"x\":915,\"y\":461.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"110px\",\"height\":\"20px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/QUWRIj5KOKDKwj7RHzy9uJsTAjJ2we2YeDUVxzxY.jpg\",\"elements2\":[{\"id\":\"el_1779418337692\",\"type\":\"qr\",\"x\":894.99966666666659875772893428802490234375,\"y\":518.3329999999999699866748414933681488037109375,\"style\":{\"width\":\"163.33333333333334px\",\"height\":\"163.33333333333334px\"},\"content\":\"\"}]}', NULL, 'published', '2026-05-22 06:34:21', '2026-05-22 10:09:59', NULL, NULL),
(10, 'Curso de Primeros Axulios', 'curso-de-primeros-axulios-6a1ca05f1e941', '101', 80, NULL, 1, '2026-06-01', '2026-06-02', '{\"pages\":1,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Mvn2PiW8nqY9hXmXk2INfUupb1eSFWvhxlxiqmRN.jpg\",\"elements\":[{\"id\":\"el_1780261024191\",\"type\":\"student_name\",\"x\":130.00033333333334439885220490396022796630859375,\"y\":255,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"880px\",\"height\":\"28px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780261076513\",\"type\":\"course_title\",\"x\":383.33266666666668243124149739742279052734375,\"y\":306.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"28px\",\"color\":\"#005fdb\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>Curso de Primeros Axulios<\\/p>\"},{\"id\":\"el_1780261133970\",\"type\":\"certificate_body\",\"x\":203.333666666666658784379251301288604736328125,\"y\":351.66699999999997316990629769861698150634765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"725px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Realizado el <strong>1 de junio del 2026<\\/strong> al <strong>2 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>80 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780261170859\",\"type\":\"issue_date\",\"x\":348.3333333333333712289459072053432464599609375,\"y\":491.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 2 de junio del 2026<\\/p>\"},{\"id\":\"el_1780261188123\",\"type\":\"qr\",\"x\":893.3333333333333712289459072053432464599609375,\"y\":516.6670000000000300133251585066318511962890625,\"style\":{\"width\":\"166.66666666666669px\",\"height\":\"166.66666666666669px\"},\"content\":\"\"},{\"id\":\"el_1780261196139\",\"type\":\"registration_number\",\"x\":916.6670000000000300133251585066318511962890625,\"y\":464.9996666666667124445666559040546417236328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"113.33333333333331px\",\"height\":\"20px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"},{\"id\":\"el_1780261210036\",\"type\":\"executive_signature\",\"x\":255.000666666666660375994979403913021087646484375,\"y\":571.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"200px\"},\"content\":{\"name\":\"Director Ejecutivo\",\"role\":\"DIRECTOR EJECUTIVO\",\"size\":80,\"nameStyle\":{\"fontSize\":\"14px\",\"color\":\"#000000\",\"fontWeight\":\"bold\"},\"roleStyle\":{\"fontSize\":\"10px\",\"color\":\"#666666\"}}},{\"id\":\"el_1780261214724\",\"type\":\"academic_signature\",\"x\":601.6666666666666287710540927946567535400390625,\"y\":576.6666666666666287710540927946567535400390625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"200px\"},\"content\":{\"name\":\"Director Acad\\u00e9mico\",\"role\":\"DIRECTOR ACAD\\u00c9MICO\",\"size\":80,\"nameStyle\":{\"fontSize\":\"14px\",\"color\":\"#000000\",\"fontWeight\":\"bold\"},\"roleStyle\":{\"fontSize\":\"10px\",\"color\":\"#666666\"}}}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Mvn2PiW8nqY9hXmXk2INfUupb1eSFWvhxlxiqmRN.jpg\",\"elements2\":[]}', NULL, 'published', '2026-06-01 00:55:59', '2026-06-01 01:01:04', NULL, NULL),
(11, 'EXCEL INTERMEDIO', 'excel-intermedio-6a1e3f8b6db02', '101', 90, NULL, 7, '2026-05-02', '2026-06-01', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/uB5At3UvSel9ODYODaQaFD2eLD1ribHeZJCWNMKM.png\",\"elements\":[{\"id\":\"el_1780367357073\",\"type\":\"student_name\",\"x\":465,\"y\":311.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780367386703\",\"type\":\"course_title\",\"x\":458.333666666666658784379251301288604736328125,\"y\":385,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"35px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p><strong>EXCEL INTERMEDIO<\\/strong><\\/p>\"},{\"id\":\"el_1780367441098\",\"type\":\"certificate_body\",\"x\":341.66699999999997316990629769861698150634765625,\"y\":436.66599999999999681676854379475116729736328125,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"639.3333333333334px\",\"fontStyle\":\"italic\",\"height\":\"118px\"},\"content\":\"<p>Realizado el <strong>2 de mayo del 2026<\\/strong> al <strong>1 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780367715755\",\"type\":\"issue_date\",\"x\":619.99966666666659875772893428802490234375,\"y\":561.6670000000000300133251585066318511962890625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 1 de junio del 2026.<\\/p>\"},{\"id\":\"el_1780367766893\",\"type\":\"qr\",\"x\":120.0003333333333301879974897019565105438232421875,\"y\":608.3329999999999699866748414933681488037109375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1780367797485\",\"type\":\"registration_number\",\"x\":31.666666666666657192763523198664188385009765625,\"y\":583.3333333333333712289459072053432464599609375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1780367847116\",\"type\":\"text\",\"x\":100,\"y\":100,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"36px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"left\",\"width\":\"215px\",\"fontStyle\":\"normal\",\"height\":\"63.66666666666667px\"},\"content\":\"<p><strong style=\\\"color: rgb(0, 71, 178);\\\">TEMARIO<\\/strong><\\/p>\"},{\"id\":\"el_1780367934703\",\"type\":\"text\",\"x\":106.6666666666666714036182384006679058074951171875,\"y\":185,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"24px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"670px\",\"fontStyle\":\"italic\",\"height\":\"237px\"},\"content\":\"<p>-   FGFB<\\/p><p>-  DFNJGGFN<\\/p><ul><li>  FGVGF<\\/li><li>  FGBGF<\\/li><li>  FVBF<\\/li><li> F H HRH<\\/li><\\/ul>\"}]}', NULL, 'published', '2026-06-02 06:27:23', '2026-06-02 06:44:31', NULL, NULL),
(12, 'PSICOMOTRICIDAD EDUCATIVA', 'psicomotricidad-educativa-6a1f78dad6e97', '101', 90, NULL, 7, '2026-05-02', '2026-06-02', '{\"pages\":2,\"background\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/uB5At3UvSel9ODYODaQaFD2eLD1ribHeZJCWNMKM.png\",\"elements\":[{\"id\":\"el_1780456685440\",\"type\":\"student_name\",\"x\":338.33300000000002683009370230138301849365234375,\"y\":316.66666666666668561447295360267162322998046875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"29px\",\"color\":\"#000000\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"631px\",\"height\":\"21.666666666666668px\"},\"content\":\"JUAN P\\u00c9REZ DE LA CRUZ\"},{\"id\":\"el_1780456748190\",\"type\":\"course_title\",\"x\":376.66699999999997316990629769861698150634765625,\"y\":385,\"style\":{\"fontFamily\":\"font-montserrat\",\"fontSize\":\"35px\",\"color\":\"#f97316\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"auto\"},\"content\":\"<p>PSICOMOTRICIDAD EDUCATIVA<\\/p>\"},{\"id\":\"el_1780456953916\",\"type\":\"certificate_body\",\"x\":330,\"y\":431.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"631.6666666666666px\",\"fontStyle\":\"normal\",\"height\":\"135px\"},\"content\":\"<p>Realizado el <strong>2 de mayo del 2026<\\/strong> al <strong>2 de junio del 2026<\\/strong>, con una duraci\\u00f3n de <strong>90 horas acad\\u00e9micas<\\/strong>.<\\/p><p><br><\\/p><p>En consecuencia, se emite el presente CERTIFICADO para que se le otorgue el reconocimiento correspondiente.<\\/p>\"},{\"id\":\"el_1780457040737\",\"type\":\"issue_date\",\"x\":618.3329999999999699866748414933681488037109375,\"y\":560.00033333333340124227106571197509765625,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"20px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"400px\",\"fontStyle\":\"italic\"},\"content\":\"<p>Ayacucho, 2 de junio del 2026<\\/p>\"},{\"id\":\"el_1780457157551\",\"type\":\"qr\",\"x\":116.6663333333333270047660334967076778411865234375,\"y\":593.3329999999999699866748414933681488037109375,\"style\":{\"width\":\"120px\",\"height\":\"120px\"},\"content\":\"\"},{\"id\":\"el_1780457171605\",\"type\":\"registration_number\",\"x\":28.3333666666666630362669820897281169891357421875,\"y\":571.666333333333341215620748698711395263671875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"16px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"center\",\"width\":\"300px\"},\"content\":\"<p>Reg. N\\u00b0 101<\\/p>\"}],\"background2\":\"https:\\/\\/www.capacitaperu.com\\/storage\\/media\\/Aw1iBvEy2F5hFfOB4NTVwe4RPf1s1HvEXeVrhEEx.png\",\"elements2\":[{\"id\":\"el_1780457226280\",\"type\":\"text\",\"x\":110,\"y\":111.6666666666666714036182384006679058074951171875,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"45px\",\"color\":\"#2b4697\",\"fontWeight\":\"bold\",\"textAlign\":\"center\",\"width\":\"216.66666666666669px\",\"fontStyle\":\"normal\",\"height\":\"55px\"},\"content\":\"<p>TEMARIO<\\/p>\"},{\"id\":\"el_1780457355570\",\"type\":\"text\",\"x\":105,\"y\":208.333333333333342807236476801335811614990234375,\"style\":{\"fontFamily\":\"font-lato\",\"fontSize\":\"30px\",\"color\":\"#000000\",\"fontWeight\":\"normal\",\"textAlign\":\"left\",\"width\":\"516.6666666666667px\",\"fontStyle\":\"italic\",\"height\":\"215.66666666666666px\"},\"content\":\"<p>- Fundamentos de la psicomotricidad<\\/p><p>- Desarrollo psicomotor del ni\\u00f1o<\\/p><p>- Componentes de la psicomotricidad<\\/p><p>- Psicomotricidad y curr\\u00edculo nacional<\\/p><p>- Estrategias metodol\\u00f3gicas<\\/p>\"}]}', NULL, 'published', '2026-06-03 04:44:10', '2026-06-03 07:48:09', NULL, NULL);

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
(16, 5, NULL, '72882466', 'HERMOZA PALOMINO, CRISTEL DANEÉ', 'PCZH-2026-MCLQ', '2026-05-18 20:18:41', '2026-05-18 20:18:41', 0, NULL, NULL, NULL, NULL, NULL),
(19, 6, NULL, '72882466', 'HERMOZA PALOMINO, CRISTEL DANEÉ', 'B8BL-2026-V0QO', '2026-05-18 20:25:34', '2026-05-18 20:25:34', 0, NULL, NULL, NULL, NULL, NULL),
(20, 7, NULL, '70422280', 'VARGAS RUIZ, GABRIELA', 'BFIV-2026-LI3N', '2026-05-18 23:09:13', '2026-05-18 23:09:13', 0, NULL, NULL, NULL, NULL, NULL),
(22, 8, NULL, '70422280', 'VARGAS RUIZ, GABRIELA', 'LWZR-2026-XHGT', '2026-05-19 19:30:48', '2026-05-19 19:30:48', 0, NULL, NULL, NULL, NULL, NULL),
(27, 9, 1, '12345', 'Jan', '88DZ-2026-IPFX', '2026-05-22 07:54:09', '2026-05-22 08:14:32', 0, NULL, NULL, NULL, NULL, '102'),
(28, 9, 1, '12345677', 'Jon Garcia', 'ZOSD-2026-IIKG', '2026-05-22 07:55:49', '2026-05-22 08:14:32', 1, '2026-05-27', '2026-05-29', 65, '2026-05-29', '202'),
(29, 9, 1, '1231', 'Jorge peredo', 'WC4Q-2026-WLLG', '2026-05-22 08:14:33', '2026-05-22 08:14:33', 0, NULL, NULL, NULL, NULL, '103'),
(30, 9, 1, '654123', 'Sergio Galarza', 'VJKI-2026-TXWJ', '2026-05-22 10:10:40', '2026-05-22 10:10:40', 0, NULL, NULL, NULL, NULL, '104'),
(31, 10, 1, '987654', 'Juan Perez Garcia', 'XUER-2026-TUXO', '2026-06-01 01:04:04', '2026-06-01 01:04:04', 0, NULL, NULL, NULL, NULL, '102'),
(32, 10, 1, '963258', 'Fernando Gutierrez', 'PBDR-2026-28VO', '2026-06-01 01:04:04', '2026-06-01 01:04:04', 1, '2026-06-08', '2026-06-09', 90, '2026-06-10', '202'),
(33, 11, 2, '76088509', 'YURI JANAMPA', 'BLZH-2026-SUIB', '2026-06-02 06:49:01', '2026-06-02 06:49:01', 0, NULL, NULL, NULL, NULL, '102'),
(34, 12, 4, '72507178', 'MARLENE YACKELINE SEMINARIO FRANCO', 'NFN2-2026-GACG', '2026-06-03 07:43:34', '2026-06-03 07:43:34', 0, NULL, NULL, NULL, NULL, '102'),
(35, 12, 4, '45002778', 'CLARITZA JOHANI MENDOZA GIRON', 'O3A7-2026-Z0E8', '2026-06-03 07:43:34', '2026-06-03 07:43:34', 0, NULL, NULL, NULL, NULL, '103');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `username`, `email`, `password`, `phone`, `specialty`, `bio`, `certificate_name`, `signature_url`, `avatar_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ing. Juan Perez', NULL, 'rsirpa@gmail.com', '$2y$12$4CyMOruD7qu8ZavO69iWf.XVZwRgr5IMYa/oLi6gQrwKyHWQxh9ZK', '73723677', 'Ingeniero Industrial & Especialista', 'Más de 10 años de experiencia implementando sistemas de gestión de seguridad en empresas mineras y petroleras. Auditor líder ISO 45001 y docente universitario apasionado por la seguridad laboral.', NULL, NULL, NULL, 'active', '2025-12-24 14:23:50', '2026-05-13 10:32:30'),
(2, 'Gonzalo Galvez', NULL, 'ggalvez@gmail.com', '$2y$12$onjyGxDbcEB1q.d9qBzmzuci331UOAnpMbUp6RlzQ3D4c9vCsPV9.', '71234657', 'Ingeniero Ambiental', 'Más de 10 años de experiencia implementando sistemas de gestión de seguridad en empresas mineras y petroleras. Auditor líder ISO 45001 y docente universitario apasionado por la seguridad laboral.', NULL, NULL, NULL, 'active', '2025-12-24 14:46:46', '2026-05-13 10:33:04'),
(3, 'Ing. Pedro Fernandez', NULL, 'pedro.fernandez@gmail.com', '$2y$12$XOOep8CJ57aY.Z62/wZkHuuJinA.FIYFdkqGpfmeTnnuz6vnQbMOO', '78945612', 'Ing. de Alimentos', 'Pedro Fernandez (Acapulco, 15 de octubre de 1966) es un futbolista y ex entrenador mexicano que se desempeña como guardameta y delantero. Actualmente juega en el México FC de la Tercera Federación de España.[7]​\r\n\r\nUn destacado jugador de México en la década de los 90, Campos era un jugador excéntrico conocido por su juego constante fuera del área de penalti, a menudo funcionando como portero-delantero, así como por su estilo de portería acrobático, arriesgado y extravagante, y sus coloridos atuendos de juego.[8]​ Sus principales fortalezas como portero eran su capacidad de salto, atletismo y velocidad al salir corriendo de su línea, al igual que su capacidad para organizar su defensa, lo que le permitió superar su baja estatura. En 1993, fue galardonado por la IFFHS como el tercer mejor portero del mundo, y en años recientes como uno de los mejores porteros de Concacaf y del mundo. Con 47 goles anotados a lo largo de su trayectoria profesional, es el portero mexicano más goleador y el tercero más goleador en la historia del fútbol. Es considerado uno de los mejores porteros de su generación.', NULL, NULL, 'storage/instructors/ohKm7VBfr44DSyAeogwhzLxWLhioCsRfmERff6ho.jpg', 'active', '2025-12-27 12:31:14', '2026-05-13 10:33:48');

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
(33, '2026_05_31_180000_add_exam_answers_to_enrollments_table', 21);

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
(12, 'settings.view', 'Sistema', 'Ver configuración', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permission_user`
--

CREATE TABLE `permission_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `code`, `discount_type`, `value`, `target_type`, `target_ids`, `exception_ids`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'test', NULL, 'percent', 10.00, 'course', '[\"6\"]', '[]', '2026-05-23 07:45:00', '2026-05-25 08:44:00', 1, '2026-05-23 07:45:02', '2026-05-23 07:46:51');

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
(8, 'individual_cert_academic_signature', 'storage/signatures/1CC7E54MrzFgnU3tjHrincbuWAQfFVm2ntd6bbWr.png', '2026-05-16 11:37:53', '2026-05-16 11:37:53');

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 'Gianella Roca', 'gianella', 'gianella@gmail.com', NULL, '$2y$12$PWHfpFbUEBroY.y.qI3NiemxYj1W4dw5bXM/6aBAymUDWyZZjODoe', 0, 'admin', '$2y$12$LBzukMT1rwYa2douqox2feWyK9fBX9h23oaLN0zvxmP9Ex/1s9s8y', 'https://www.capacitaperu.com/img/avatars/admin/admin_13.jpg', 'active', 'irra7SiLJxjol5HUjeeMpTR4PoQwF6OvmZGkGfbSZo3bXjyF2T6GM3QNzfCS', '2026-05-18 17:42:36', '2026-06-03 12:37:10'),
(3, 'Carmen Castillo', 'carmen', 'carmen@gmail.com', NULL, '$2y$12$koHYqU57UKa9MbivPC5LtuTPyvDf0EAwL/BPsN81aqo1mqdynqzV2', 0, 'admin', '$2y$12$OOEvpy5mffyV1V8F3u5JqewTPBj3/zBt8pnnQtPhcTo.wZWHR5Vfu', 'https://www.capacitaperu.com/img/avatars/admin/admin_11.jpg', 'active', 'izv2qBy2iE6qwCY9eQoKeEeDWygcSBzmspTMccc9nePWAt4neYsMgr4qMDTz', '2026-05-18 17:43:26', '2026-06-03 12:37:02'),
(4, 'Yuri Janampa', 'yuri', 'yuri@gmail.com', NULL, '$2y$12$k3UKrvparZkr.p89ejxh6euVNkS/C3K4RoT3MNr2xJT3WKrZzx1U6', 0, 'admin', '$2y$12$xEk5uoAkRzo0Bk5T/cIO4uju636C4p6YcJ408wEbx8qF1J1TiCXly', 'https://www.capacitaperu.com/img/avatars/admin/admin_2.jpg', 'active', NULL, '2026-05-18 17:44:05', '2026-06-03 12:36:47');

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
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

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
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`),
  ADD KEY `courses_instructor_id_foreign` (`instructor_id`);

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
  ADD KEY `enrollments_course_id_foreign` (`course_id`);

--
-- Indices de la tabla `individual_certificates`
--
ALTER TABLE `individual_certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `individual_certificates_slug_unique` (`slug`),
  ADD KEY `individual_certificates_instructor_id_foreign` (`instructor_id`),
  ADD KEY `individual_certificates_category_id_foreign` (`category_id`);

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
  ADD UNIQUE KEY `instructors_username_unique` (`username`);

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
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `students_username_unique` (`username`);

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
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `category_course`
--
ALTER TABLE `category_course`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `course_lessons`
--
ALTER TABLE `course_lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT de la tabla `course_topics`
--
ALTER TABLE `course_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `individual_certificates`
--
ALTER TABLE `individual_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `individual_certificate_students`
--
ALTER TABLE `individual_certificate_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `project_submissions`
--
ALTER TABLE `project_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `registration_tokens`
--
ALTER TABLE `registration_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `individual_certificates`
--
ALTER TABLE `individual_certificates`
  ADD CONSTRAINT `individual_certificates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `individual_certificates_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `individual_certificate_students`
--
ALTER TABLE `individual_certificate_students`
  ADD CONSTRAINT `individual_certificate_students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
-- Filtros para la tabla `registration_tokens`
--
ALTER TABLE `registration_tokens`
  ADD CONSTRAINT `registration_tokens_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
