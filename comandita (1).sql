-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-07-2024 a las 22:44:03
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
-- Base de datos: `comandita`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `cuenta` int(11) DEFAULT NULL,
  `fechaCobro` date DEFAULT NULL,
  `mesa` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombre`, `apellido`, `dni`, `cuenta`, `fechaCobro`, `mesa`) VALUES
(1, 'Julieta', 'Bogardo', '4578954', 10800, '2024-07-26', 'lfzMl'),
(2, 'Cristian', 'Dimarco', '445067234', NULL, NULL, NULL),
(3, 'Griselda', 'Gonzalez', '44879417', 12000, '2024-07-30', 'PhT2r'),
(4, 'Rodrigo', 'Canosa', '44879245', NULL, NULL, NULL),
(5, 'Susana', 'Amodeo', '20784541', NULL, NULL, NULL),
(6, 'griselda', 'canosa', '35408798', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `puntuacionMozo` int(11) DEFAULT NULL,
  `puntuacionCocina` int(11) DEFAULT NULL,
  `puntuacionMesa` int(11) DEFAULT NULL,
  `puntuacionBebidas` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `codigoMesa` varchar(50) DEFAULT NULL,
  `codigoPedido` varchar(50) DEFAULT NULL,
  `fechaAlta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`id`, `puntuacionMozo`, `puntuacionCocina`, `puntuacionMesa`, `puntuacionBebidas`, `comentario`, `codigoMesa`, `codigoPedido`, `fechaAlta`) VALUES
(1, 7, 4, 8, 4, 'Increible atencion asquerosa comida', 'lfzMl', '6', '2024-07-01'),
(2, 7, 4, 8, 4, 'Increible atencion asquerosa comida', 'lfzMl', '6', '2024-07-01'),
(3, 7, 4, 8, 4, 'Increible atencion asquerosa comida', 'PhT2r', '6', '2024-07-01'),
(4, 1, 1, 1, 1, 'Increible atencion asquerosa comida', 'wOkux', '9', '2024-07-01'),
(5, 10, 10, 10, 10, 'Increible atencion asquerosa comida', '1HzsB', '10', '2024-07-01'),
(6, 4, 2, 8, 10, 'Ni tan mal', 'PhT2r', '13', '2024-07-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `sector` varchar(50) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `metodo` varchar(50) DEFAULT NULL,
  `url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `dni`, `sector`, `fecha`, `metodo`, `url`) VALUES
(3, '43821789', 'mozo', '2024-07-02 12:20:23', 'POST', '/pedido/cliente'),
(4, '43820796', 'socio', '2024-07-02 12:24:42', 'POST', '/socioLogs'),
(5, '43820796', 'socio', '2024-07-02 12:24:49', 'GET', '/socioLogs'),
(6, '43820796', 'socio', '2024-07-02 12:25:29', 'GET', '/socioLogs'),
(7, '43820796', 'socio', '2024-07-02 12:26:08', 'GET', '/socioLogs'),
(8, '43820796', 'socio', '2024-07-02 12:26:24', 'GET', '/socioLogs'),
(9, '43820796', 'socio', '2024-07-02 12:27:41', 'GET', '/socioLogs'),
(10, '43820796', 'socio', '2024-07-02 12:29:02', 'GET', '/empleadoYSector'),
(11, '43820796', 'socio', '2024-07-02 12:29:41', 'GET', '/empleadoYSector'),
(12, '43820796', 'socio', '2024-07-02 12:30:05', 'GET', '/socioLogs/empleadoYSector'),
(13, '43820796', 'socio', '2024-07-02 12:30:31', 'GET', '/socioLogs/empleadoYSector'),
(14, '43820796', 'socio', '2024-07-02 12:31:05', 'GET', '/socioLogs/empleadoDiasHorarios'),
(15, '43820796', 'socio', '2024-07-02 12:32:02', 'POST', '/socioLogs/empleadoDiasHorarios'),
(16, '43820796', 'socio', '2024-07-02 12:33:19', 'POST', '/socioLogs/empleadoDiasHorarios'),
(17, '43820796', 'socio', '2024-07-02 12:34:15', 'POST', '/socioLogs/empleadoDiasHorarios'),
(18, '43820796', 'socio', '2024-07-02 12:34:58', 'POST', '/socioLogs/empleadoDiasHorarios'),
(19, '43820796', 'socio', '2024-07-02 12:35:12', 'POST', '/socioLogs/empleadoDiasHorarios'),
(20, '43820796', 'socio', '2024-07-02 12:36:29', 'POST', '/socioLogs/empleadoDiasHorarios'),
(21, '43820796', 'socio', '2024-07-02 19:48:05', 'GET', '/download'),
(22, '43820796', 'socio', '2024-07-02 19:48:16', 'GET', '/download'),
(23, '43820796', 'socio', '2024-07-02 19:50:08', 'GET', '/download'),
(24, '43820796', 'socio', '2024-07-02 19:50:11', 'GET', '/download'),
(25, '43820796', 'socio', '2024-07-02 19:51:53', 'GET', '/download'),
(26, '43820796', 'socio', '2024-07-02 19:52:08', 'GET', '/download'),
(27, '43820796', 'socio', '2024-07-02 19:52:51', 'GET', '/download'),
(28, '43820796', 'socio', '2024-07-02 19:53:08', 'GET', '/download'),
(29, '43820796', 'socio', '2024-07-02 19:53:20', 'GET', '/download'),
(30, '43820796', 'socio', '2024-07-02 19:54:48', 'GET', '/download'),
(31, '43820796', 'socio', '2024-07-02 19:55:14', 'GET', '/download'),
(32, '43820796', 'socio', '2024-07-02 19:55:24', 'GET', '/download'),
(33, '43820796', 'socio', '2024-07-02 19:56:12', 'GET', '/download'),
(34, '43820796', 'socio', '2024-07-02 19:56:25', 'GET', '/download'),
(35, '43820796', 'socio', '2024-07-02 19:59:16', 'GET', '/download'),
(36, '43820796', 'socio', '2024-07-02 19:59:23', 'GET', '/download'),
(37, '43820796', 'socio', '2024-07-02 20:00:57', 'GET', '/download'),
(38, '43820796', 'socio', '2024-07-02 20:02:04', 'GET', '/download'),
(39, '43820796', 'socio', '2024-07-02 20:02:14', 'GET', '/download'),
(40, '43820796', 'socio', '2024-07-02 20:02:30', 'GET', '/download'),
(41, '43820796', 'socio', '2024-07-02 20:02:40', 'GET', '/download'),
(42, '43820796', 'socio', '2024-07-02 20:04:04', 'GET', '/download'),
(43, '43820796', 'socio', '2024-07-02 20:04:44', 'GET', '/download'),
(44, '43820796', 'socio', '2024-07-02 20:04:49', 'GET', '/download'),
(45, '43820796', 'socio', '2024-07-02 20:05:37', 'GET', '/download'),
(46, '43820796', 'socio', '2024-07-02 20:06:28', 'GET', '/download'),
(47, '43820796', 'socio', '2024-07-02 20:06:52', 'GET', '/download'),
(48, '43820796', 'socio', '2024-07-02 20:06:54', 'GET', '/download'),
(49, '43820796', 'socio', '2024-07-02 20:07:37', 'GET', '/download'),
(50, '43820796', 'socio', '2024-07-02 20:07:40', 'GET', '/download'),
(51, '43820796', 'socio', '2024-07-02 20:10:01', 'GET', '/download'),
(52, '43820796', 'socio', '2024-07-02 20:10:40', 'GET', '/download'),
(53, '43820796', 'socio', '2024-07-02 20:11:14', 'GET', '/download'),
(54, '43820796', 'socio', '2024-07-02 20:11:23', 'GET', '/download'),
(55, '43820796', 'socio', '2024-07-02 20:19:15', 'GET', '/download'),
(56, '43820796', 'socio', '2024-07-02 20:21:11', 'GET', '/download'),
(57, '43820796', 'socio', '2024-07-02 20:23:31', 'GET', '/download'),
(58, '43820796', 'socio', '2024-07-02 20:28:34', 'GET', '/download'),
(59, '43820796', 'socio', '2024-07-02 20:29:02', 'GET', '/download'),
(60, '43820796', 'socio', '2024-07-02 20:29:18', 'GET', '/download'),
(61, '43820796', 'socio', '2024-07-02 20:31:50', 'GET', '/download'),
(62, '43820796', 'socio', '2024-07-02 20:32:00', 'GET', '/download'),
(63, '43820796', 'socio', '2024-07-02 20:34:08', 'GET', '/download'),
(64, '43820796', 'socio', '2024-07-02 20:34:24', 'GET', '/download'),
(65, '43820796', 'socio', '2024-07-02 20:34:48', 'GET', '/download'),
(66, '43820796', 'socio', '2024-07-02 20:35:51', 'GET', '/download'),
(67, '43820796', 'socio', '2024-07-02 21:24:45', 'POST', '/producto'),
(68, '40598789', 'cervecero', '2024-07-27 20:59:17', 'POST', '/cliente/consultar'),
(69, '40598789', 'cervecero', '2024-07-27 21:01:58', 'POST', '/cliente/consultar'),
(70, '40598789', 'cervecero', '2024-07-27 21:04:13', 'POST', '/cliente/consultar'),
(71, '40598789', 'cervecero', '2024-07-27 21:11:18', 'POST', '/cliente/consultar'),
(72, '40598789', 'cervecero', '2024-07-27 21:12:53', 'GET', '/cliente/consultar'),
(73, '40598789', 'cervecero', '2024-07-27 21:13:57', 'POST', '/cliente/consultar'),
(74, '43820796', 'socio', '2024-07-27 21:15:19', 'POST', '/producto'),
(75, NULL, NULL, '2024-07-27 21:15:26', 'POST', '/mesa'),
(76, NULL, NULL, '2024-07-27 21:15:34', 'POST', '/mesa'),
(77, '43820796', 'socio', '2024-07-27 21:15:45', 'POST', '/mesa'),
(78, NULL, NULL, '2024-07-27 21:16:20', 'POST', '/pedido/cliente'),
(79, '43820796', 'socio', '2024-07-27 21:16:26', 'POST', '/pedido/cliente'),
(80, '43821789', 'mozo', '2024-07-27 21:16:34', 'POST', '/pedido/cliente'),
(81, '43821789', 'mozo', '2024-07-27 21:17:04', 'POST', '/pedido'),
(82, '43821789', 'mozo', '2024-07-27 21:18:31', 'POST', '/pedido'),
(83, '43821789', 'mozo', '2024-07-27 21:21:43', 'POST', '/pedido'),
(84, '43821789', 'mozo', '2024-07-27 21:22:32', 'POST', '/pedido'),
(85, '43821789', 'mozo', '2024-07-27 21:24:41', 'POST', '/pedido'),
(86, '43821789', 'mozo', '2024-07-27 21:26:20', 'POST', '/pedido'),
(87, '43821789', 'mozo', '2024-07-27 21:26:54', 'POST', '/pedido'),
(88, '43821789', 'mozo', '2024-07-27 21:27:03', 'POST', '/pedido'),
(89, '43821789', 'mozo', '2024-07-27 21:27:23', 'POST', '/pedido'),
(90, '43821789', 'mozo', '2024-07-27 21:28:20', 'POST', '/pedido'),
(91, '43821789', 'mozo', '2024-07-27 21:29:19', 'POST', '/pedido'),
(92, '43821789', 'mozo', '2024-07-27 21:30:03', 'POST', '/pedido'),
(93, NULL, NULL, '2024-07-27 21:30:35', 'POST', '/pedido/productos'),
(94, '43821789', 'mozo', '2024-07-27 21:30:45', 'POST', '/pedido/productos'),
(95, '43821789', 'mozo', '2024-07-27 21:33:19', 'POST', '/pedido/productos'),
(96, '43821789', 'mozo', '2024-07-27 21:36:01', 'POST', '/pedido'),
(97, '43821789', 'mozo', '2024-07-27 21:36:48', 'POST', '/pedido/productos'),
(98, '43821789', 'mozo', '2024-07-27 21:37:46', 'POST', '/pedido/productos'),
(99, '43820796', 'socio', '2024-07-27 21:38:53', 'POST', '/producto'),
(100, '43821789', 'mozo', '2024-07-27 21:39:00', 'POST', '/pedido/productos'),
(101, '43821789', 'mozo', '2024-07-27 21:41:22', 'POST', '/pedido/productos'),
(102, '43821789', 'mozo', '2024-07-27 21:41:45', 'POST', '/pedido/productos'),
(103, '43821789', 'mozo', '2024-07-27 21:47:25', 'POST', '/pedido/productos'),
(113, '43820796', 'socio', '2024-07-27 21:53:46', 'POST', '/socioLogs/empleadoDiasHorarios'),
(114, '43820796', 'socio', '2024-07-27 21:58:04', 'POST', '/socioLogs/empleadoDiasHorarios'),
(115, NULL, NULL, '2024-07-27 22:04:37', 'GET', '/socioLogs'),
(116, '43820796', 'socio', '2024-07-27 22:04:57', 'GET', '/socioLogs'),
(117, NULL, NULL, '2024-07-27 22:05:59', 'GET', '/socioLogs/empleadoYSector'),
(118, '43820796', 'socio', '2024-07-27 22:06:05', 'GET', '/socioLogs/empleadoYSector'),
(119, '43820796', 'socio', '2024-07-27 22:10:48', 'GET', '/productos/masVendidos'),
(120, '43820796', 'socio', '2024-07-27 22:11:18', 'GET', '/productos/masVendidos'),
(121, '43820796', 'socio', '2024-07-27 22:11:47', 'GET', '/productos/menosVendidos'),
(122, '43820796', 'socio', '2024-07-27 22:19:39', 'GET', '/mesa/masFacturo'),
(123, '43820796', 'socio', '2024-07-27 22:20:02', 'GET', '/mesa/masFacturo'),
(124, '43820796', 'socio', '2024-07-27 22:21:22', 'GET', '/productos/masVendidos'),
(125, '43820796', 'socio', '2024-07-27 22:26:16', 'GET', '/mesa/masFacturo'),
(126, '43820796', 'socio', '2024-07-27 22:27:05', 'GET', '/mesa/masFacturo'),
(127, '43820796', 'socio', '2024-07-27 22:29:15', 'GET', '/mesa/menosFacturo'),
(128, '43820796', 'socio', '2024-07-27 23:11:00', 'GET', '/mesa/menosFacturoEntreFechas'),
(129, '43820796', 'socio', '2024-07-27 23:11:40', 'GET', '/mesa/menosFacturoEntreFechas'),
(130, '43820796', 'socio', '2024-07-27 23:11:44', 'GET', '/mesa/menosFacturoEntreFechas'),
(131, '43820796', 'socio', '2024-07-27 23:12:43', 'GET', '/mesa/menosFacturoEntreFechas'),
(132, '43820796', 'socio', '2024-07-28 20:39:19', 'GET', '/mesa/menosFacturoEntreFechas'),
(133, '43820796', 'socio', '2024-07-28 20:39:38', 'GET', '/mesa/menosFacturoEntreFechas'),
(134, '43820796', 'socio', '2024-07-28 20:40:16', 'GET', '/mesa/menosFacturoEntreFechas'),
(135, '43820796', 'socio', '2024-07-28 20:40:26', 'GET', '/mesa/facturoEntreFechas'),
(136, '43820796', 'socio', '2024-07-28 20:40:39', 'GET', '/mesa/facturoEntreFechas'),
(137, '43820796', 'socio', '2024-07-28 20:41:04', 'GET', '/mesa/facturoEntreFechas'),
(138, '43820796', 'socio', '2024-07-28 20:41:19', 'GET', '/encuesta/mejoresComentarios'),
(139, '43820796', 'socio', '2024-07-28 20:42:29', 'GET', '/encuesta/mejoresComentarios'),
(140, NULL, NULL, '2024-07-28 20:43:55', 'POST', '/usuario'),
(141, '43820796', 'socio', '2024-07-28 20:44:08', 'POST', '/usuario'),
(142, '43820796', 'socio', '2024-07-28 20:45:30', 'GET', '/mesa/facturoEntreFechas'),
(143, '43820796', 'socio', '2024-07-28 20:46:35', 'GET', '/mesa/facturoEntreFechas'),
(144, '43820796', 'socio', '2024-07-28 20:47:46', 'GET', '/mesa/facturoEntreFechas'),
(145, '43820796', 'socio', '2024-07-28 20:47:57', 'GET', '/mesa/facturoEntreFechas'),
(146, '43820796', 'socio', '2024-07-28 20:48:18', 'GET', '/mesa/facturoEntreFechas'),
(147, '43820796', 'socio', '2024-07-28 20:48:43', 'GET', '/mesa/facturoEntreFechas'),
(148, '43820796', 'socio', '2024-07-28 20:49:27', 'GET', '/mesa/facturoEntreFechas'),
(149, '43820796', 'socio', '2024-07-28 20:50:02', 'GET', '/mesa/facturoEntreFechas'),
(150, '43820796', 'socio', '2024-07-28 20:50:26', 'GET', '/mesa/facturoEntreFechas'),
(151, '43820796', 'socio', '2024-07-28 20:50:27', 'GET', '/mesa/facturoEntreFechas'),
(152, '43820796', 'socio', '2024-07-28 20:50:36', 'GET', '/mesa/facturoEntreFechas'),
(153, '43820796', 'socio', '2024-07-28 20:51:03', 'GET', '/mesa/facturoEntreFechas'),
(154, '43820796', 'socio', '2024-07-28 20:53:53', 'GET', '/mesa/facturoEntreFechas'),
(155, '43820796', 'socio', '2024-07-28 20:54:21', 'POST', '/usuario'),
(156, '40598789', 'cervecero', '2024-07-28 20:54:28', 'GET', '/pedido/listar/cervecero/cervecero'),
(157, '40598789', 'cervecero', '2024-07-28 20:54:43', 'GET', '/pedido/listar/cervecero/cervecero'),
(158, '40598789', 'cervecero', '2024-07-28 20:54:56', 'GET', '/pedido/listar/cervecero/cervecero'),
(159, NULL, NULL, '2024-07-28 20:55:06', 'POST', '/login'),
(160, '43820796', 'socio', '2024-07-28 20:55:22', 'POST', '/mesa'),
(161, '43820796', 'socio', '2024-07-28 20:55:52', 'GET', '/mesa/facturoEntreFechas'),
(162, '43821789', 'mozo', '2024-07-28 21:30:50', 'POST', '/pedido/servir'),
(163, NULL, NULL, '2024-07-28 21:31:29', 'POST', '/login'),
(164, NULL, NULL, '2024-07-28 21:31:36', 'POST', '/login'),
(165, '43821789', 'mozo', '2024-07-28 21:31:51', 'POST', '/pedido/servir'),
(166, '43821789', 'mozo', '2024-07-28 21:32:11', 'POST', '/pedido/servir'),
(167, '43821789', 'mozo', '2024-07-28 21:33:10', 'POST', '/pedido/servir'),
(168, '43821789', 'mozo', '2024-07-28 21:34:05', 'POST', '/pedido/servir'),
(169, '43821789', 'mozo', '2024-07-28 21:34:27', 'POST', '/pedido/servir'),
(170, '43821789', 'mozo', '2024-07-28 21:45:04', 'POST', '/pedido/servir'),
(171, '43821789', 'mozo', '2024-07-28 21:46:18', 'POST', '/pedido/servir'),
(172, '43821789', 'mozo', '2024-07-28 21:47:34', 'POST', '/pedido/servir'),
(173, '43821789', 'mozo', '2024-07-28 21:47:46', 'POST', '/pedido/servir'),
(174, '43821789', 'mozo', '2024-07-28 21:48:11', 'POST', '/pedido/servir'),
(175, '43821789', 'mozo', '2024-07-28 21:49:45', 'POST', '/pedido/servir'),
(176, '43821789', 'mozo', '2024-07-28 22:12:29', 'POST', '/pedido/servir'),
(177, '43821789', 'mozo', '2024-07-28 22:13:22', 'POST', '/pedido/servir'),
(178, '43821789', 'mozo', '2024-07-28 22:14:20', 'POST', '/pedido/servir'),
(179, '43821789', 'mozo', '2024-07-28 22:14:55', 'POST', '/pedido/servir'),
(180, '43821789', 'mozo', '2024-07-28 22:15:31', 'POST', '/pedido/servir'),
(181, '43821789', 'mozo', '2024-07-28 22:17:27', 'POST', '/pedido/servir'),
(182, '43821789', 'mozo', '2024-07-28 22:17:59', 'POST', '/pedido/servir'),
(183, '43821789', 'mozo', '2024-07-28 22:20:30', 'POST', '/pedido/servir'),
(184, '43821789', 'mozo', '2024-07-28 22:22:50', 'POST', '/pedido/servir'),
(185, '43821789', 'mozo', '2024-07-28 22:24:14', 'POST', '/pedido/servir'),
(186, '43821789', 'mozo', '2024-07-28 22:25:13', 'POST', '/pedido/servir'),
(187, '43821789', 'mozo', '2024-07-28 22:26:08', 'POST', '/pedido/servir'),
(188, '43821789', 'mozo', '2024-07-28 22:29:16', 'POST', '/pedido/servir'),
(189, '43821789', 'mozo', '2024-07-28 22:29:29', 'POST', '/pedido/servir'),
(190, '43821789', 'mozo', '2024-07-28 22:29:51', 'POST', '/pedido/servir'),
(191, NULL, NULL, '2024-07-29 23:21:34', 'GET', '/mesa/servidoConDemora'),
(192, '43821789', 'mozo', '2024-07-29 23:21:51', 'GET', '/mesa/servidoConDemora'),
(193, '43820796', 'socio', '2024-07-29 23:22:06', 'GET', '/mesa/servidoConDemora'),
(194, '43820796', 'socio', '2024-07-29 23:22:27', 'GET', '/mesa/servidoConDemora'),
(195, '43820796', 'socio', '2024-07-29 23:24:49', 'GET', '/mesa/servidoConDemora'),
(196, '43820796', 'socio', '2024-07-29 23:25:24', 'GET', '/mesa/servidoConDemora'),
(197, '43820796', 'socio', '2024-07-30 00:58:55', 'POST', '/usuario'),
(198, '43820796', 'socio', '2024-07-30 00:59:05', 'POST', '/usuario'),
(199, '43820796', 'socio', '2024-07-30 00:59:09', 'POST', '/usuario'),
(200, '43820796', 'socio', '2024-07-30 00:59:46', 'POST', '/usuario'),
(201, '43820796', 'socio', '2024-07-30 01:00:05', 'POST', '/usuario'),
(202, '43820796', 'socio', '2024-07-30 01:00:25', 'POST', '/usuario'),
(203, '43820796', 'socio', '2024-07-30 01:00:31', 'POST', '/usuario'),
(204, '43820796', 'socio', '2024-07-30 01:06:53', 'POST', '/usuario'),
(205, '43820796', 'socio', '2024-07-30 01:07:24', 'POST', '/usuario'),
(206, '43820796', 'socio', '2024-07-30 01:07:41', 'POST', '/mesa'),
(207, NULL, NULL, '2024-07-30 01:08:47', 'POST', '/login'),
(208, NULL, NULL, '2024-07-30 01:08:51', 'POST', '/login'),
(209, NULL, NULL, '2024-07-30 01:10:09', 'POST', '/login'),
(210, NULL, NULL, '2024-07-30 01:10:40', 'POST', '/login'),
(211, '43820796', 'socio', '2024-07-30 01:11:27', 'GET', '/archivoProductos/guardar'),
(212, '43820796', 'socio', '2024-07-30 01:12:09', 'GET', '/archivoProductos/guardar'),
(213, '43820796', 'socio', '2024-07-30 01:12:44', 'GET', '/archivoProductos/guardar'),
(214, '43820796', 'socio', '2024-07-30 01:12:48', 'GET', '/archivoProductos/guardar'),
(215, '43820796', 'socio', '2024-07-30 01:12:55', 'GET', '/archivoProductos/guardar'),
(216, '43820796', 'socio', '2024-07-30 01:13:44', 'POST', '/archivoProductos/importar-csv'),
(217, '43820796', 'socio', '2024-07-30 01:14:17', 'GET', '/archivoProductos/guardar'),
(218, '43820796', 'socio', '2024-07-30 01:15:35', 'GET', '/archivoProductos/guardar'),
(219, '43820796', 'socio', '2024-07-30 01:16:14', 'GET', '/archivoProductos/guardar/'),
(220, '43820796', 'socio', '2024-07-30 01:16:17', 'GET', '/archivoProductos/guardar/'),
(221, '43820796', 'socio', '2024-07-30 01:17:00', 'GET', '/archivoProductos/guardar'),
(222, '43820796', 'socio', '2024-07-30 01:17:14', 'GET', '/archivoProductos/guardar'),
(223, '43820796', 'socio', '2024-07-30 01:19:37', 'GET', '/archivoProductos/guardar'),
(224, '43820796', 'socio', '2024-07-30 01:19:47', 'GET', '/archivoProductos/guardar'),
(225, '43820796', 'socio', '2024-07-30 01:20:03', 'GET', '/archivoProductos/guardar'),
(226, '43820796', 'socio', '2024-07-30 01:20:36', 'POST', '/socioLogs/empleadoDiasHorarios'),
(227, '43820796', 'socio', '2024-07-30 01:20:41', 'POST', '/socioLogs/empleadoDiasHorarios'),
(228, '43820796', 'socio', '2024-07-30 01:20:50', 'POST', '/socioLogs/empleadoDiasHorarios'),
(229, NULL, NULL, '2024-07-30 01:25:45', 'GET', '/download'),
(230, '43820796', 'socio', '2024-07-30 01:25:52', 'GET', '/download'),
(231, '43820796', 'socio', '2024-07-30 01:25:58', 'GET', '/download'),
(232, '40598789', 'cervecero', '2024-07-30 01:32:44', 'GET', '/pedido/listar/cocinero/cocinero'),
(233, '43820796', 'socio', '2024-07-30 01:35:36', 'POST', '/producto'),
(234, '43820796', 'socio', '2024-07-30 01:35:43', 'POST', '/producto'),
(235, '43820796', 'socio', '2024-07-30 01:35:56', 'POST', '/producto'),
(236, '43820796', 'socio', '2024-07-30 01:36:21', 'POST', '/producto'),
(237, '43820796', 'socio', '2024-07-30 01:37:19', 'POST', '/producto'),
(238, '43821789', 'mozo', '2024-07-30 01:37:58', 'POST', '/pedido'),
(239, '43820796', 'socio', '2024-07-30 01:38:12', 'POST', '/pedido'),
(240, '43821789', 'mozo', '2024-07-30 01:38:22', 'POST', '/pedido'),
(241, '43821789', 'mozo', '2024-07-30 01:39:14', 'POST', '/pedido'),
(242, '43821789', 'mozo', '2024-07-30 01:39:31', 'POST', '/pedido'),
(243, '43821789', 'mozo', '2024-07-30 01:39:40', 'POST', '/pedido'),
(244, '43821789', 'mozo', '2024-07-30 01:40:41', 'POST', '/pedido'),
(245, '43821789', 'mozo', '2024-07-30 01:42:41', 'POST', '/pedido'),
(246, '43821789', 'mozo', '2024-07-30 01:42:55', 'POST', '/pedido'),
(247, '43821789', 'mozo', '2024-07-30 01:43:19', 'POST', '/pedido'),
(248, '43821789', 'mozo', '2024-07-30 01:44:18', 'POST', '/pedido'),
(249, '43821789', 'mozo', '2024-07-30 01:44:45', 'POST', '/pedido'),
(250, '43821789', 'mozo', '2024-07-30 01:45:31', 'POST', '/pedido/productos'),
(251, '43821789', 'mozo', '2024-07-30 01:45:44', 'POST', '/pedido/productos'),
(252, '43821789', 'mozo', '2024-07-30 01:46:08', 'POST', '/pedido/productos'),
(253, '43821789', 'mozo', '2024-07-30 01:46:19', 'POST', '/pedido/productos'),
(254, '23458797', 'cervecero', '2024-07-30 01:48:56', 'GET', '/pedido/listar/cervecero/cervecero'),
(255, '44789124', 'bartender', '2024-07-30 01:49:26', 'GET', '/pedido/listar/cervecero/bartender'),
(256, '44789124', 'bartender', '2024-07-30 01:49:32', 'GET', '/pedido/listar/bartender/bartender'),
(257, '44789124', 'bartender', '2024-07-30 01:49:37', 'GET', '/pedido/listar/bartender/bartender'),
(258, '44789124', 'bartender', '2024-07-30 01:50:03', 'GET', '/pedido/listar/bartender/bartender'),
(259, '40598789', 'cervecero', '2024-07-30 01:50:17', 'GET', '/pedido/listar/bartender/bartender'),
(260, NULL, NULL, '2024-07-30 01:50:42', 'POST', '/login'),
(261, '40598789', 'cervecero', '2024-07-30 01:50:55', 'GET', '/pedido/listar/bartender/bartender'),
(262, '40598789', 'cervecero', '2024-07-30 01:52:04', 'GET', '/pedido/listar/bartender/bartender'),
(263, '40598789', 'cervecero', '2024-07-30 01:52:05', 'GET', '/pedido/listar/bartender/bartender'),
(264, '40598789', 'cervecero', '2024-07-30 01:52:23', 'GET', '/pedido/listar/bartender/bartender'),
(265, '40598789', 'cervecero', '2024-07-30 01:52:46', 'GET', '/pedido/listar/bartender/bartender'),
(266, '40598789', 'cervecero', '2024-07-30 01:52:53', 'GET', '/pedido/listar/bartender/bartender'),
(267, '40598789', 'cervecero', '2024-07-30 01:53:00', 'GET', '/pedido/listar/bartender/bartender'),
(268, '40598789', 'cervecero', '2024-07-30 01:53:41', 'GET', '/pedido/listar/bartender/bartender'),
(269, '44789124', 'bartender', '2024-07-30 01:54:36', 'GET', '/pedido/listar/bartender/bartender'),
(270, '44789124', 'bartender', '2024-07-30 01:54:57', 'GET', '/pedido/listar/bartender/bartender'),
(271, NULL, NULL, '2024-07-30 01:55:59', 'POST', '/login'),
(272, NULL, NULL, '2024-07-30 01:56:14', 'POST', '/login'),
(273, NULL, NULL, '2024-07-30 01:56:16', 'POST', '/login'),
(274, NULL, NULL, '2024-07-30 01:56:21', 'POST', '/login'),
(275, NULL, NULL, '2024-07-30 01:57:01', 'POST', '/login'),
(276, NULL, NULL, '2024-07-30 01:57:06', 'POST', '/login'),
(277, NULL, NULL, '2024-07-30 01:57:08', 'POST', '/login'),
(278, '23458790', 'bartender', '2024-07-30 01:58:55', 'GET', '/pedido/listar/bartender/bartender'),
(279, '23458790', 'bartender', '2024-07-30 01:59:17', 'GET', '/pedido/listar/bartender/bartender'),
(280, '23458797', 'cervecero', '2024-07-30 02:28:32', 'GET', '/pedido/listar/cocinero/cocinero'),
(281, NULL, NULL, '2024-07-30 02:28:48', 'POST', '/login'),
(282, '23458792', 'cocinero', '2024-07-30 02:29:03', 'GET', '/pedido/listar/cocinero/cocinero'),
(283, '43820796', 'socio', '2024-07-30 02:33:55', 'GET', '/socio/consulta/DemoraPedidos'),
(284, '43820796', 'socio', '2024-07-30 02:34:13', 'GET', '/socio/consulta/DemoraPedidos'),
(285, '43821789', 'mozo', '2024-07-30 03:11:04', 'GET', '/pedido/listoParaServir'),
(286, '43821789', 'mozo', '2024-07-30 03:12:28', 'GET', '/pedido/paraServir'),
(287, '43821789', 'mozo', '2024-07-30 03:12:31', 'GET', '/pedido/paraServir'),
(288, '43821789', 'mozo', '2024-07-30 03:12:43', 'GET', '/pedido/paraServir'),
(289, '43821789', 'mozo', '2024-07-30 05:06:48', 'GET', '/pedido/paraServir'),
(290, '43821789', 'mozo', '2024-07-30 05:08:32', 'GET', '/pedido/paraServir'),
(291, '43821789', 'mozo', '2024-07-30 05:38:23', 'GET', '/pedido/paraServir'),
(292, '43821789', 'mozo', '2024-07-30 05:38:58', 'GET', '/pedido/paraServir'),
(293, '43820796', 'socio', '2024-07-30 19:24:05', 'POST', '/producto'),
(294, '43820796', 'socio', '2024-07-30 19:30:18', 'POST', '/producto'),
(295, '43820796', 'socio', '2024-07-30 19:30:25', 'POST', '/producto'),
(296, '43821789', 'mozo', '2024-07-30 19:31:34', 'POST', '/pedido/cliente'),
(297, NULL, NULL, '2024-07-30 19:33:00', 'POST', '/pedido'),
(298, '43821789', 'mozo', '2024-07-30 19:33:09', 'POST', '/pedido'),
(299, '43821789', 'mozo', '2024-07-30 19:33:29', 'POST', '/pedido'),
(300, '43821789', 'mozo', '2024-07-30 19:33:48', 'POST', '/pedido'),
(301, '43821789', 'mozo', '2024-07-30 19:33:59', 'POST', '/pedido'),
(302, '43821789', 'mozo', '2024-07-30 19:34:12', 'POST', '/pedido'),
(303, '44789124', 'bartender', '2024-07-30 19:36:18', 'GET', '/pedido/listar/bartender/bartender'),
(304, '23458790', 'bartender', '2024-07-30 19:36:34', 'GET', '/pedido/listar/bartender/bartender'),
(305, '23458790', 'bartender', '2024-07-30 19:38:16', 'GET', '/pedido/listar/bartender/bartender'),
(306, '44789124', 'bartender', '2024-07-30 19:38:41', 'PUT', '/pedido/modificar/bartender/bartender'),
(307, '23458790', 'bartender', '2024-07-30 19:38:48', 'PUT', '/pedido/modificar/bartender/bartender'),
(308, '23458790', 'bartender', '2024-07-30 19:39:05', 'PUT', '/pedido/modificar/bartender/bartender'),
(309, '23458790', 'bartender', '2024-07-30 19:40:15', 'PUT', '/pedido/modificar/bartender/bartender'),
(310, '23458790', 'bartender', '2024-07-30 19:42:08', 'PUT', '/pedido/modificar/bartender/bartender'),
(311, '23458790', 'bartender', '2024-07-30 19:42:15', 'PUT', '/pedido/modificar/bartender/bartender'),
(312, '40598789', 'cervecero', '2024-07-30 19:44:28', 'POST', '/cliente/consultar'),
(313, '40598789', 'cervecero', '2024-07-30 19:48:35', 'POST', '/cliente/consultar'),
(314, '40598789', 'cervecero', '2024-07-30 19:48:52', 'POST', '/cliente/consultar'),
(315, '40598789', 'cervecero', '2024-07-30 19:50:40', 'POST', '/cliente/consultar'),
(316, '40598789', 'cervecero', '2024-07-30 19:51:19', 'POST', '/cliente/consultar'),
(317, '40598789', 'cervecero', '2024-07-30 19:51:35', 'POST', '/cliente/consultar'),
(318, '40598789', 'cervecero', '2024-07-30 19:52:24', 'POST', '/cliente/consultar'),
(319, '40598789', 'cervecero', '2024-07-30 19:54:37', 'POST', '/cliente/consultar'),
(320, '40598789', 'cervecero', '2024-07-30 19:55:23', 'POST', '/cliente/consultar'),
(321, '40598789', 'cervecero', '2024-07-30 19:55:52', 'POST', '/cliente/consultar'),
(322, '23458792', 'cocinero', '2024-07-30 19:56:43', 'GET', '/pedido/listar/cocinero/cocinero'),
(323, '23458790', 'bartender', '2024-07-30 19:57:04', 'PUT', '/pedido/modificar/cocinero/cocinero'),
(324, '23458792', 'cocinero', '2024-07-30 19:57:11', 'PUT', '/pedido/modificar/cocinero/cocinero'),
(325, '23458792', 'cocinero', '2024-07-30 19:57:17', 'PUT', '/pedido/modificar/cocinero/cocinero'),
(326, '23458792', 'cocinero', '2024-07-30 20:00:36', 'PUT', '/pedido/modificar/cocinero/cocinero'),
(327, '23458792', 'cocinero', '2024-07-30 20:01:19', 'PUT', '/pedido/modificar/cocinero/cocinero'),
(328, '23458792', 'cocinero', '2024-07-30 20:01:42', 'PUT', '/pedido/modificar/cocinero/cocinero'),
(329, '40598789', 'cervecero', '2024-07-30 20:01:50', 'POST', '/cliente/consultar'),
(330, '40598789', 'cervecero', '2024-07-30 20:02:07', 'POST', '/cliente/consultar'),
(331, '43820796', 'socio', '2024-07-30 20:03:48', 'GET', '/socio/consulta/DemoraPedidos'),
(332, '43820796', 'socio', '2024-07-30 20:05:05', 'GET', '/socio/consulta/DemoraPedidos'),
(333, '23458797', 'cervecero', '2024-07-30 20:08:02', 'GET', '/producto/pendiente/cervecero/cervecero'),
(334, '23458797', 'cervecero', '2024-07-30 20:09:14', 'GET', '/producto/pendiente/cervecero/cervecero'),
(335, '23458790', 'bartender', '2024-07-30 20:10:04', 'GET', '/producto/pendiente/bartender/bartender'),
(336, '40598789', 'cervecero', '2024-07-30 20:10:55', 'GET', '/pedido/listar/bartender/bartender'),
(337, '23458790', 'bartender', '2024-07-30 20:11:04', 'GET', '/pedido/listar/bartender/bartender'),
(338, '43821789', 'mozo', '2024-07-30 20:13:44', 'GET', '/pedido/paraServir'),
(339, '43821789', 'mozo', '2024-07-30 20:15:28', 'GET', '/pedido/paraServir'),
(340, '43821789', 'mozo', '2024-07-30 20:16:32', 'POST', '/pedido/productos'),
(341, '43821789', 'mozo', '2024-07-30 20:16:41', 'POST', '/pedido/productos'),
(342, '23458792', 'cocinero', '2024-07-30 20:16:50', 'GET', '/pedido/listar/cocinero/cocinero'),
(343, '23458792', 'cocinero', '2024-07-30 20:17:26', 'PUT', '/pedido/modificar/cocinero/cocinero'),
(344, '23458792', 'cocinero', '2024-07-30 20:17:42', 'PUT', '/pedido/modificar/cocinero/cocinero'),
(345, '43821789', 'mozo', '2024-07-30 20:17:54', 'GET', '/pedido/paraServir'),
(346, '43821789', 'mozo', '2024-07-30 20:18:11', 'POST', '/pedido/servir'),
(347, '43821789', 'mozo', '2024-07-30 20:18:20', 'POST', '/pedido/servir'),
(348, '43821789', 'mozo', '2024-07-30 20:18:56', 'POST', '/pedido/cobrar'),
(349, '43821789', 'mozo', '2024-07-30 20:20:04', 'POST', '/pedido/cobrar'),
(350, '43821789', 'mozo', '2024-07-30 20:22:16', 'POST', '/pedido/cobrar'),
(351, '43821789', 'mozo', '2024-07-30 20:23:01', 'POST', '/pedido/cobrar'),
(352, '43821789', 'mozo', '2024-07-30 20:25:01', 'POST', '/pedido/cobrar'),
(353, '43821789', 'mozo', '2024-07-30 20:32:05', 'POST', '/pagar'),
(354, '43821789', 'mozo', '2024-07-30 20:32:38', 'POST', '/pagar'),
(355, '43821789', 'mozo', '2024-07-30 20:33:07', 'POST', '/pagar'),
(356, '43821789', 'mozo', '2024-07-30 20:34:22', 'POST', '/pagar'),
(357, '43821789', 'mozo', '2024-07-30 20:36:43', 'POST', '/pagar'),
(358, '43820796', 'socio', '2024-07-30 20:39:13', 'POST', '/producto'),
(359, '43820796', 'socio', '2024-07-30 20:40:00', 'POST', '/producto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `codigoMesa` varchar(50) NOT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `fechaAlta` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesa`
--

INSERT INTO `mesa` (`id`, `codigoMesa`, `estado`, `fechaAlta`) VALUES
(1, 'lfzMl', 'servido con demora', '2024-06-30'),
(2, 'PhT2r', 'abierta', '2024-06-20'),
(3, 'wOkux', 'con cliente esperando pedido', '2024-07-01'),
(4, '1HzsB', 'con cliente esperando pedido', '2024-07-07'),
(5, 'iNNXO', 'con cliente esperando pedido', '2024-07-10'),
(6, 'sVbw9', 'con cliente esperando pedido', '2024-07-28'),
(7, 'o4iym', 'abierta', '2024-07-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `cliente` int(11) DEFAULT NULL,
  `mesa` varchar(5) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `tiempoPreparacionEstimado` int(11) DEFAULT NULL,
  `idEmpleado` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id`, `cliente`, `mesa`, `estado`, `tiempoPreparacionEstimado`, `idEmpleado`, `foto`) VALUES
(6, 1, 'lfzMl', 'servido con demora', 0, 1, 'ImagenesDeLaMesa/2024/11_20240630_161802.jpg'),
(7, 2, 'lfzMl', 'servido', 20, 3, NULL),
(8, 3, 'PhT2r', 'con cliente pagando', 20, 3, NULL),
(10, 4, '1HzsB', 'con cliente esperando pedido', 20, 3, NULL),
(12, 6, 'iNNXO', 'con cliente esperando pedido', 20, 1, NULL),
(13, 6, 'PhT2r', 'con cliente pagando', 20, 1, NULL),
(14, 3, 'wOkux', 'con cliente esperando pedido', 20, 1, NULL),
(15, 4, '1HzsB', 'con cliente esperando pedido', 20, 1, NULL),
(16, 2, 'sVbw9', 'con cliente esperando pedido', 20, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `sector` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `tiempoPreparacion` int(11) DEFAULT NULL,
  `fechaAlta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `sector`, `nombre`, `precio`, `stock`, `tiempoPreparacion`, `fechaAlta`) VALUES
(14, 'cervecero', 'skol', 1200.00, 100, 4, '2024-07-27'),
(15, 'cocinero', 'milanesa a caballo', 4000.00, 95, 10, '2024-07-27'),
(16, 'cocinero', 'milanesa de carne', 4000.00, 100, 10, '2024-07-30'),
(18, 'bartender', 'daikiri', 1800.00, 100, 4, '2024-07-30'),
(19, 'cocinero', 'hamburguesa de garbanzo', 7500.00, 100, 7, '2024-07-30'),
(20, 'cervecero', 'corona', 1000.00, 100, 2, '2024-07-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_pedidos`
--

CREATE TABLE `productos_pedidos` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `sector` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `tiempoEstimado` int(11) DEFAULT NULL,
  `fechaModf` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_pedidos`
--

INSERT INTO `productos_pedidos` (`id`, `pedido_id`, `producto`, `cantidad`, `sector`, `estado`, `tiempoEstimado`, `fechaModf`) VALUES
(1, 6, 'hamburguesa de garbanzo', 2, 'cocina', 'listo para servir', 5, '2024-07-27 19:20:16'),
(2, 6, 'daiquiri', 1, 'bartender', 'en preparacion', 3, '2024-07-28 22:28:16'),
(3, 6, 'corona', 1, 'cervecero', 'listo para servir', 5, '2024-07-27 19:20:16'),
(4, 12, 'milanesa a caballo', 2, 'cocinero', 'en preparacion', 17, '2024-07-30 20:01:42'),
(5, 13, 'milanesa a caballo', 1, 'cocinero', 'en preparacion', 11, '2024-07-30 20:01:19'),
(6, 8, 'milanesa a caballo', 2, 'cocinero', 'con cliente pagando', 17, '2024-07-30 20:17:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `tipo` enum('socio','mozo','bartender','cocinero','cervecero') NOT NULL,
  `fechaAlta` date NOT NULL,
  `clave` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `dni`, `tipo`, `fechaAlta`, `clave`, `estado`) VALUES
(1, 'Gabriela', 'Sosa', '43821789', 'mozo', '2024-06-30', '$2y$10$3iywCJ2Hpuny.CbGMYdzXuU2zQm7aswzBsjUz4YdFZzVduScspmTy', 'activo'),
(2, 'Francisco', 'Hulej', '43820796', 'socio', '2024-06-30', '$2y$10$NJ8/EIYvV./CuxFoIDw1WOpT778sbHSmQxN7VRI5.p39DgJrYoE8e', 'activo'),
(3, 'claudio', 'gomez', '40598789', 'cervecero', '2024-06-30', '$2y$10$ic2jYMhEsbQn2K9xFlruPupdHpr/MyHn1ysAZ/ulcCklMCKQBVKmy', 'activo'),
(5, 'manuel', 'heck', '44789124', 'bartender', '2024-06-30', '$2y$10$Tyi0qCcRYSupwtjKhS6oUO7h3lJZTUvcncbTi8ulW2AGBXIA3/71u', 'activo'),
(6, 'Matias', 'Moreno', '23458797', 'cervecero', '2024-07-30', '$2y$10$BTqyAhVeVHkNK9mvmiL4f.Q7UbbXHXOytqEyiHWqrP95.K6dQM0vG', 'activo'),
(8, 'Tomas', 'Musetto', '23458792', 'cocinero', '2024-07-30', '$2y$10$il79IioXOAx7ShEPgG23d.uVj6PnAQPLJhrCsqhYtueCPDJGOAJH2', 'activo'),
(10, 'Lucho', 'Mendoza', '23458790', 'bartender', '2024-07-30', '$2y$10$7Liv6i5YIu9nuFcrWqFNA.32x4xIdPiLmBRy9Va5KfT/773oDRAEK', 'activo'),
(11, 'bruno', 'cardoso', '23458793', 'socio', '2024-07-30', '$2y$10$I.sgVFcExpbnZZhMorAyP.AnFAhFBgn.dPlOPeDZcti4mp7SjNTwu', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos_pedidos`
--
ALTER TABLE `productos_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `productos_pedidos`
--
ALTER TABLE `productos_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
