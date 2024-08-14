-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-05-2024 a las 18:19:12
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
-- Base de datos: `db_emergencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_camb_asig`
--

CREATE TABLE `tm_camb_asig` (
  `camb_asig_id` int(11) NOT NULL,
  `ev_id` int(11) NOT NULL,
  `antigua_asig` varchar(25) NOT NULL,
  `nueva_asig` varchar(25) NOT NULL,
  `fec_cambio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_camb_asig`
--

INSERT INTO `tm_camb_asig` (`camb_asig_id`, `ev_id`, `antigua_asig`, `nueva_asig`, `fec_cambio`) VALUES
(1, 114, '5', '4', '2024-02-05 09:55:25'),
(2, 114, '5', '1', '2024-02-05 09:55:39'),
(3, 114, '5', '4', '2024-02-05 09:57:46'),
(4, 114, '5', '4', '2024-02-05 09:58:46'),
(5, 114, '2', '1', '2024-02-05 10:01:44'),
(6, 114, '1', '1', '2024-02-05 10:01:52'),
(7, 114, '3', '1', '2024-02-05 10:01:58'),
(8, 114, '1', '1', '2024-02-05 10:06:06'),
(9, 114, '1', '1', '2024-02-05 10:06:16'),
(10, 115, '0', '2', '2024-02-05 10:10:48'),
(11, 141, '0', '2', '2024-02-05 10:15:14'),
(12, 141, 'No hay unidades asignadas', '', '2024-02-05 10:16:39'),
(13, 141, 'No hay unidades asignadas', '', '2024-02-05 10:16:45'),
(14, 141, '2', '', '2024-02-05 10:17:05'),
(15, 141, 'No hay unidades asignadas', '2', '2024-02-05 10:32:42'),
(16, 115, 'No hay unidades asignadas', '', '2024-02-05 10:33:17'),
(17, 141, '2', '', '2024-02-05 10:35:58'),
(18, 141, 'No hay unidades asignadas', '2', '2024-02-05 10:46:36'),
(19, 141, 'No hay unidades asignadas', '', '2024-02-05 10:46:53'),
(20, 141, 'No hay unidades asignadas', 'No hay unidades asignadas', '2024-02-05 10:52:13'),
(21, 141, 'No hay unidades asignadas', 'No hay unidades asignadas', '2024-02-05 10:52:18'),
(22, 141, 'No hay unidades asignadas', 'No hay unidades asignadas', '2024-02-05 10:52:35'),
(23, 141, '2', '', '2024-02-05 11:13:59'),
(24, 141, 'No hay unidades asignadas', '3', '2024-02-05 11:14:51'),
(25, 141, '3', 'No hay unidades', '2024-02-05 11:16:40'),
(26, 141, 'No hay unidades asignadas', '2', '2024-02-05 11:16:43'),
(27, 141, '2', 'No hay unidades', '2024-02-05 11:17:02'),
(28, 141, 'No hay unidades asignadas', '3', '2024-02-05 11:33:39'),
(29, 141, '3', '3,4', '2024-02-05 11:34:03'),
(30, 141, '3,4', '4', '2024-02-05 12:27:07'),
(31, 114, '1,2', '1,2', '2024-02-15 10:13:02'),
(32, 143, '2,3,5,4', '2,3,4,5', '2024-02-15 10:34:54'),
(33, 143, '2,3,4,5', '2,3,4,5', '2024-02-15 11:27:33'),
(34, 144, '2,3,4,5', '2,3,4,5', '2024-02-15 12:02:39'),
(35, 114, '1,2', '1,2', '2024-02-15 15:56:07'),
(36, 114, '1,2', '1,2', '2024-02-15 16:49:22'),
(37, 144, '3,2,4,5', '2,3,4,5', '2024-02-15 16:49:26'),
(38, 146, '4,5,3,2', '5', '2024-03-21 17:25:37'),
(39, 146, '5', '5', '2024-03-21 17:25:52'),
(40, 146, '5', '1', '2024-03-22 09:43:17'),
(41, 146, '1', '1', '2024-03-22 09:46:17'),
(42, 147, '2', '1', '2024-03-22 11:34:38'),
(43, 147, '1', '3,4', '2024-03-22 14:59:46'),
(44, 147, '3,4', '5', '2024-03-22 15:00:21'),
(45, 145, '2,3', '2,5', '2024-03-22 15:00:58'),
(46, 145, '2,5', '3,5', '2024-03-22 15:01:16'),
(47, 147, '5', 'No hay unidades', '2024-03-22 15:10:44'),
(48, 147, 'No hay unidades asignadas', 'No hay unidades', '2024-03-22 16:03:20'),
(49, 147, 'No hay unidades asignadas', 'No hay unidades', '2024-03-22 16:03:26'),
(50, 147, 'No hay unidades asignadas', '1', '2024-03-22 16:06:35'),
(51, 148, '3', '3', '2024-03-22 16:12:50'),
(52, 148, '3', 'No hay unidades', '2024-03-22 16:21:20'),
(53, 148, 'No hay unidades asignadas', '5', '2024-03-22 16:31:04'),
(54, 148, '5', '1', '2024-03-25 11:06:55'),
(55, 147, 'No hay unidades asignadas', '2,3,4', '2024-03-25 16:44:17'),
(56, 149, '2,4,3,5', '4,5', '2024-03-25 16:50:36'),
(57, 148, '1', '3', '2024-03-25 16:54:56'),
(58, 148, '3', '5', '2024-03-25 16:55:45'),
(59, 149, '4,5', '5', '2024-03-25 16:56:02'),
(60, 148, '5', '4', '2024-03-25 16:56:18'),
(61, 148, '4', '3', '2024-03-25 16:56:29'),
(62, 114, '1,2', '4', '2024-03-25 16:56:39'),
(63, 144, '2,3,4,5', '4', '2024-03-25 16:58:02'),
(64, 145, '3,5', '3', '2024-03-25 17:05:48'),
(65, 150, '2,3', '3', '2024-03-26 10:09:06'),
(66, 150, '3', '3,5', '2024-03-26 10:09:20'),
(67, 151, 'No hay unidades asignadas', '3', '2024-05-22 23:23:45'),
(68, 152, '3', '3', '2024-05-22 23:28:53'),
(69, 158, '2', '1', '2024-05-27 09:21:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_categoria`
--

CREATE TABLE `tm_categoria` (
  `cat_id` int(11) NOT NULL,
  `cat_nom` varchar(50) NOT NULL,
  `nivel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tm_categoria`
--

INSERT INTO `tm_categoria` (`cat_id`, `cat_nom`, `nivel`) VALUES
(1, 'Incendios', 1),
(2, 'Asaltos', 1),
(3, 'Accidente Vehicular', 1),
(4, 'Desorden Publico', 1),
(5, 'Otros', 1),
(7, 'Caida de arbol', 1),
(9, 'Intoxicación', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_ciudad`
--

CREATE TABLE `tm_ciudad` (
  `ciudad_id` int(11) NOT NULL,
  `ciudad_nom` varchar(50) NOT NULL,
  `comuna_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_comuna`
--

CREATE TABLE `tm_comuna` (
  `comuna_id` int(11) NOT NULL,
  `comuna_nom` varchar(50) NOT NULL,
  `region_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_comuna`
--

INSERT INTO `tm_comuna` (`comuna_id`, `comuna_nom`, `region_id`) VALUES
(1, 'Arica', 1),
(2, 'Camarones', 1),
(3, 'General Lagos', 1),
(4, 'Putre', 1),
(5, 'Alto Hospicio', 2),
(6, 'Camiña', 2),
(7, 'Colchane', 2),
(8, 'Huara', 2),
(9, 'Iquique', 2),
(10, 'Pica', 2),
(11, 'Pozo Almonte', 2),
(12, 'Antofagasta', 3),
(13, 'Calama', 3),
(14, 'Maria Elena', 3),
(15, 'Mejillones', 3),
(16, 'Ollagüe', 3),
(17, 'San Pedro de Atacama', 3),
(18, 'Sierra Gorda', 3),
(19, 'Taltal', 3),
(20, 'Tocopilla', 3),
(21, 'Alto del Carmen', 4),
(22, 'Caldera', 4),
(23, 'Chañaral', 4),
(24, 'Copiapó', 4),
(25, 'Diego de Almagro', 4),
(26, 'Freirina', 4),
(27, 'Huasco', 4),
(28, 'Tierra Amarilla', 4),
(29, 'Vallenar', 4),
(30, 'Andacollo', 5),
(31, 'Canela', 5),
(32, 'Combarbalá', 5),
(33, 'Coquimbo', 5),
(34, 'Illapel', 5),
(35, 'La Higuera', 5),
(36, 'La Serena', 5),
(37, 'Los Vilos', 5),
(38, 'Monte Patria', 5),
(39, 'Ovalle', 5),
(40, 'Paihuano', 5),
(41, 'Punitaqui', 5),
(42, 'Río Hurtado', 5),
(43, 'Salamanca', 5),
(44, 'Vicuña', 5),
(45, 'Algarrobo', 6),
(46, 'Cabildo', 6),
(47, 'Calera', 6),
(48, 'Calle Larga', 6),
(49, 'Cartagena', 6),
(50, 'Casablanca', 6),
(51, 'Catemu', 6),
(52, 'Concón', 6),
(53, 'El Quisco', 6),
(54, 'El Tabo', 6),
(55, 'Hijuelas', 6),
(56, 'Isla de Pascua', 6),
(57, 'Juan Fernández', 6),
(58, 'La Cruz', 6),
(59, 'La Ligua', 6),
(60, 'Limache', 6),
(61, 'Llaillay', 6),
(62, 'Los Andes', 6),
(63, 'Nogales', 6),
(64, 'Olmué', 6),
(65, 'Panquehue', 6),
(66, 'Papudo', 6),
(67, 'Petorca', 6),
(68, 'Puchuncaví', 6),
(69, 'Putaendo', 6),
(70, 'Quillota', 6),
(71, 'Quilpué', 6),
(72, 'Quintero', 6),
(73, 'Rinconada', 6),
(74, 'San Antonio', 6),
(75, 'San Esteban', 6),
(76, 'San Felipe', 6),
(77, 'Santa María', 6),
(78, 'Santo Domingo', 6),
(79, 'Valparaiso', 6),
(80, 'Villa Alemana', 6),
(81, 'Viña del Mar', 6),
(82, 'Zapallar', 6),
(83, 'Chimbarongo', 7),
(84, 'Chépica', 7),
(85, 'Codegua', 7),
(86, 'Coltauco', 7),
(87, 'Coínco', 7),
(88, 'Doñihue', 7),
(89, 'Graneros', 7),
(90, 'La Estrella', 7),
(91, 'Las Cabras', 7),
(92, 'Litueche', 7),
(93, 'Lolol', 7),
(94, 'Machalí', 7),
(95, 'Malloa', 7),
(96, 'Marchihue', 7),
(97, 'Mostazal', 7),
(98, 'Nancagua', 7),
(99, 'Navidad', 7),
(100, 'Olivar', 7),
(101, 'Palmilla', 7),
(102, 'Paredones', 7),
(103, 'Peralillo', 7),
(104, 'Peumo', 7),
(105, 'Pichidegua', 7),
(106, 'Pichilemu', 7),
(107, 'Placilla', 7),
(108, 'Pumanque', 7),
(109, 'Quinta de Tilcoco', 7),
(110, 'Rancagua', 7),
(111, 'Rengo', 7),
(112, 'Requínoa', 7),
(113, 'San Fernando', 7),
(114, 'San Vicente', 7),
(115, 'Santa Cruz', 7),
(116, 'Cauquenes', 8),
(117, 'Chanco', 8),
(118, 'Colbún', 8),
(119, 'Constitución', 8),
(120, 'Curepto', 8),
(121, 'Curicó', 8),
(122, 'Empedrado', 8),
(123, 'Hualañé', 8),
(124, 'Licantén', 8),
(125, 'Linares', 8),
(126, 'Longaví', 8),
(127, 'Maule', 8),
(128, 'Molina', 8),
(129, 'Parral', 8),
(130, 'Pelarco', 8),
(131, 'Pelluhue', 8),
(132, 'Pencahue', 8),
(133, 'Rauco', 8),
(134, 'Retiro', 8),
(135, 'Romeral', 8),
(136, 'Río Claro', 8),
(137, 'Sagrada Familia', 8),
(138, 'San Clemente', 8),
(139, 'San Javier', 8),
(140, 'San Rafael', 8),
(141, 'Talca', 8),
(142, 'Teno', 8),
(143, 'Vichuquén', 8),
(144, 'Villa Alegre', 8),
(145, 'Yerbas Buenas', 8),
(146, 'Bulnes', 9),
(147, 'Cabrero', 9),
(148, 'Chiguayante', 9),
(149, 'Chillán', 9),
(150, 'Chillán Viejo', 9),
(151, 'Cobquecura', 9),
(152, 'Coelemu', 9),
(153, 'Coihueco', 9),
(154, 'Concepción', 9),
(155, 'Contulmo', 9),
(156, 'Coronel', 9),
(157, 'Curanilahue', 9),
(158, 'El Carmen', 9),
(159, 'Florida', 9),
(160, 'Hualpén', 9),
(161, 'Hualqui', 9),
(162, 'Laja', 9),
(163, 'Lebu', 9),
(164, 'Los Álamos', 9),
(165, 'Los Ángeles', 9),
(166, 'Lota', 9),
(167, 'Mulchén', 9),
(168, 'Nacimiento', 9),
(169, 'Negrete', 9),
(170, 'Ninhue', 9),
(171, 'Ñiquén', 9),
(172, 'Pemuco', 9),
(173, 'Penco', 9),
(174, 'Pinto', 9),
(175, 'Portezuelo', 9),
(176, 'Quilaco', 9),
(177, 'Quilleco', 9),
(178, 'Quillón', 9),
(179, 'Quirihue', 9),
(180, 'Ránquil', 9),
(181, 'San Carlos', 9),
(182, 'San Fabián', 9),
(183, 'San Ignacio', 9),
(184, 'San Nicolás', 9),
(185, 'San Pedro de la Paz', 9),
(186, 'San Rosendo', 9),
(187, 'Santa Bárbara', 9),
(188, 'Santa Juana', 9),
(189, 'Talcahuano', 9),
(190, 'Tirúa', 9),
(191, 'Tomé', 9),
(192, 'Treguaco', 9),
(193, 'Tucapel', 9),
(194, 'Yumbel', 9),
(195, 'Yungay', 9),
(196, 'Angol', 10),
(197, 'Carahue', 10),
(198, 'Cholchol', 10),
(199, 'Collipulli', 10),
(200, 'Cunco', 10),
(201, 'Curacautín', 10),
(202, 'Curarrehue', 10),
(203, 'Ercilla', 10),
(204, 'Freire', 10),
(205, 'Galvarino', 10),
(206, 'Gorbea', 10),
(207, 'Lautaro', 10),
(208, 'Loncoche', 10),
(209, 'Lonquimay', 10),
(210, 'Los Sauces', 10),
(211, 'Lumaco', 10),
(212, 'Melipeuco', 10),
(213, 'Nueva Imperial', 10),
(214, 'Padre las Casas', 10),
(215, 'Perquenco', 10),
(216, 'Pitrufquén', 10),
(217, 'Pucón', 10),
(218, 'Purén', 10),
(219, 'Renaico', 10),
(220, 'Saavedra', 10),
(221, 'Temuco', 10),
(222, 'Teodoro Schmidt', 10),
(223, 'Toltén', 10),
(224, 'Traiguén', 10),
(225, 'Victoria', 10),
(226, 'Vilcún', 10),
(227, 'Villarrica', 10),
(228, 'Corral', 11),
(229, 'Futrono', 11),
(230, 'La Unión', 11),
(231, 'Lago Ranco', 11),
(232, 'Lanco', 11),
(233, 'Los Lagos', 11),
(234, 'Máfil', 11),
(235, 'Mariquina', 11),
(236, 'Paillaco', 11),
(237, 'Panguipulli', 11),
(238, 'Río Bueno', 11),
(239, 'Ancud', 12),
(240, 'Calbuco', 12),
(241, 'Castro', 12),
(242, 'Chaitén', 12),
(243, 'Chonchi', 12),
(244, 'Cochamó', 12),
(245, 'Curaco de Vélez', 12),
(246, 'Dalcahue', 12),
(247, 'Fresia', 12),
(248, 'Frutillar', 12),
(249, 'Futaleufú', 12),
(250, 'Hualaihué', 12),
(251, 'Llanquihue', 12),
(252, 'Los Muermos', 12),
(253, 'Maullín', 12),
(254, 'Osorno', 12),
(255, 'Palena', 12),
(256, 'Puerto Montt', 12),
(257, 'Puerto Octay', 12),
(258, 'Puerto Varas', 12),
(259, 'Puqueldón', 12),
(260, 'Purranque', 12),
(261, 'Puyehue', 12),
(262, 'Queilén', 12),
(263, 'Quellón', 12),
(264, 'Quemchi', 12),
(265, 'Quinchao', 12),
(266, 'Río Negro', 12),
(267, 'San Juan de la Costa', 12),
(268, 'San Pablo', 12),
(269, 'Aisén', 13),
(270, 'Chile Chico', 13),
(271, 'Cisnes', 13),
(272, 'Cochrane', 13),
(273, 'Coyhaique', 13),
(274, 'Guaitecas', 13),
(275, 'Lago Verde', 13),
(276, "O\'Higgins", 13),
(277, 'Río Ibáñez', 13),
(278, 'Tortel', 13),
(279, 'Antártica', 14),
(280, 'Cabo de Hornos', 14),
(281, 'Laguna Blanca', 14),
(282, 'Natales', 14),
(283, 'Porvenir', 14),
(284, 'Primavera', 14),
(285, 'Punta Arenas', 14),
(286, 'Río Verde', 14),
(287, 'San Gregorio', 14),
(288, 'Timaukel', 14),
(289, 'Torres del Paine', 14),
(352, 'Antártica', 15),
(353, 'Cabo de Hornos', 15),
(354, 'Laguna Blanca', 15),
(355, 'Natales', 15),
(356, 'Porvenir', 15),
(357, 'Primavera', 15),
(358, 'Punta Arenas', 15),
(359, 'Río Verde', 15),
(360, 'San Gregorio', 15),
(361, 'Timaukel', 15),
(362, 'Torres del Paine', 15),
(363, 'Alhue', 16),
(364, 'Buin', 16),
(365, 'Calera de Tango', 16),
(366, 'Cerrillos', 16),
(367, 'Cerro Navia', 16),
(368, 'Colina', 16),
(369, 'Conchalí', 16),
(370, 'Curacaví', 16),
(371, 'El Bosque', 16),
(372, 'El Monte', 16),
(373, 'Estación Central', 16),
(374, 'Huechuraba', 16),
(375, 'Independencia', 16),
(376, 'Isla de Maipo', 16),
(377, 'La Cisterna', 16),
(378, 'La Florida', 16),
(379, 'La Granja', 16),
(380, 'La Pintana', 16),
(381, 'La Reina', 16),
(382, 'Lampa', 16),
(383, 'Las Condes', 16),
(384, 'Lo Barnechea', 16),
(385, 'Lo Espejo', 16),
(386, 'Lo Prado', 16),
(387, 'Macul', 16),
(388, 'Maipú', 16),
(389, 'María Pinto', 16),
(390, 'Melipilla', 16),
(391, 'Padre Hurtado', 16),
(392, 'Paine', 16),
(393, 'Pedro Aguirre Cerda', 16),
(394, 'Peñaflor', 16),
(395, 'Peñalolén', 16),
(396, 'Pirque', 16),
(397, 'Providencia', 16),
(398, 'Puente Alto', 16),
(399, 'Quilicura', 16),
(400, 'Quinta Normal', 16),
(401, 'Recoleta', 16),
(402, 'Renca', 16),
(403, 'San Bernardo', 16),
(404, 'San Joaquín', 16),
(405, 'San José de Maipo', 16),
(406, 'San Miguel', 16),
(407, 'San Pedro', 16),
(408, 'San Ramón', 16),
(409, 'Santiago', 16),
(410, 'Talagante', 16),
(411, 'Tiltil', 16),
(412, 'Vitacura', 16),
(413, 'Ñuñoa', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_estado`
--

CREATE TABLE `tm_estado` (
  `est_id` int(11) NOT NULL,
  `est_nom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_estado`
--

INSERT INTO `tm_estado` (`est_id`, `est_nom`) VALUES
(1, 'En Proceso'),
(2, 'Finalizado'),
(3, 'Controlado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_est_unidad`
--

CREATE TABLE `tm_est_unidad` (
  `est_un_id` int(11) NOT NULL,
  `est_un_nom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_est_unidad`
--

INSERT INTO `tm_est_unidad` (`est_un_id`, `est_un_nom`) VALUES
(1, 'Disponible'),
(2, 'Fuera de Servicio'),
(3, 'No Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_evento`
--

CREATE TABLE `tm_evento` (
  `ev_id` int(11) NOT NULL,
  `usu_id` int(11) NOT NULL,
  `ev_desc` varchar(50) NOT NULL,
  `ev_est` int(11) NOT NULL,
  `ev_inicio` datetime NOT NULL,
  `ev_final` datetime DEFAULT NULL,
  `ev_direc` varchar(100) NOT NULL,
  `ev_latitud`  DOUBLE NOT NULL,
  `ev_longitud` DOUBLE NOT NULL,
  `cat_id` int(11) NOT NULL,
  `ev_niv` int(11) NOT NULL,
  `ev_img` varchar(123)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_evento`
--

INSERT INTO `tm_evento` (`ev_id`, `usu_id`, `ev_desc`, `ev_est`, `ev_inicio`, `ev_final`, `ev_direc`, `ev_latitud`, `ev_longitud`, `cat_id`, `ev_niv`, `ev_img`) VALUES
(10, 1, 'Descripcion', 2, '2024-01-08 12:09:06', NULL, 'Anita Fresno 327', -33.6860493, -71.2150019, 9, 1, NULL),
(16, 1, 'Descripcion', 2, '2024-01-08 17:03:26', NULL, 'loica parcela 7', -33.6860493, -71.2150019, 1, 3, NULL),
(19, 1, 'Descripcion', 2, '2024-01-11 12:53:31', NULL, 'Colonial 2', -33.6855446, -71.2171806, 7, 1, NULL),
(21, 1, 'Descripcion', 2, '2024-01-15 13:11:52', NULL, 'Libertad 2031', -33.6855446, -71.2171806, 5, 3, NULL),
(22, 1, 'Constado de carretera km18', 2, '2024-01-16 18:44:15', NULL, 'Ruta 78', -33.6855446, -71.2171806, 1, 3, NULL),
(24, 1, 'Costado de Carretera direccion santiago', 2, '2024-01-16 19:21:18', NULL, 'Ruta 78', -33.6855446, -71.2171806, 1, 2, NULL),
(85, 1, 'Planta interior', 2, '2024-01-21 20:35:37', NULL, 'Anita Fresno 327', -33.6860493, -71.2150019, 1, 2, NULL),
(114, 2, 'caso de incendio en la villa colonial', 2, '2024-01-31 09:13:23', '2024-05-24 15:48:12', 'colonial 13', -33.6855421, -71.2171669, 1, 1, ''),
(141, 2, 'Planta interior', 2, '2024-02-02 08:18:52', '2024-02-06 10:45:59', 'Plaza cardenales', -33.6855446, -71.2171806, 1, 1, ''),
(143, 2, 'Planta interior', 2, '2024-02-06 11:26:10', '2024-02-15 11:27:38', 'Sin dirección', -33.6855446, -71.2171806, 1, 0, ''),
(144, 2, 'Asalto', 2, '2024-02-15 11:30:32', '2024-05-24 10:19:11', 'cunco2207', -33.6779895, -71.1743368, 1, 2, ''),
(145, 2, 'evento test', 2, '2024-02-16 09:29:05', '2024-05-24 12:13:39', 'muni melipilla', -33.6860493, -71.2150019, 4, 2, ''),
(146, 2, 'gdfhfgdh', 2, '2024-03-21 17:21:00', '2024-03-22 11:17:20', 'gfhfdghhggh', -33.6860493, -71.2150019, 9, 3, ''),
(147, 2, 'se cayo un arbol', 2, '2024-03-22 09:37:49', '2024-05-24 10:03:19', 'Sin dirección', -33.6856435, -71.2230970, 7, 0, ''),
(148, 2, 'asaltacion', 2, '2024-03-22 16:10:33', '2024-05-24 15:49:22', 'Sin dirección', -33.6855738, -71.2172411, 2, 1, ''),
(149, 2, 'efsdfsdfds', 2, '2024-03-25 16:50:19', '2024-05-24 12:13:42', 'Sin dirección', -33.6855794, -71.2172355, 9, 2, ''),
(150, 2, 'barristas quemando neumaticos', 2, '2024-03-26 10:08:15', '2024-05-24 15:49:19', 'centro de melipila', -33.6855446, -71.2171806, 4, 1, ''),
(151, 2, 'barristas quemando neumaticos', 2, '2024-05-22 10:08:15', '2024-05-24 15:39:45', 'centro de melipila', -33.6855446, -71.2171806, 4, 1, ''),
(152, 3, 'dfsafdsrgde', 2, '2024-05-22 23:27:39', '2024-05-24 15:07:47', 'Sin dirección', -33.6855446, -71.2171806, 2, 1, ''),
(153, 3, 'accidente arma corta', 2, '2024-05-24 09:56:27', '2024-05-24 10:03:30', 'Sin dirección', -33.6809571, -71.2166493, 2, 0, ''),
(154, 3, 'barricadas', 2, '2024-05-24 10:01:55', '2024-05-24 10:03:33', 'mackena #5465', -33.6855446, -71.2171806, 4, 0, ''),
(155, 3, 'casa habitacion', 2, '2024-05-24 10:02:36', '2024-05-24 10:03:36', 'bernardo leigthon 87687', -33.6855446, -71.2171806, 1, 0, ''),
(156, 3, 'casa habitacion', 1, '2024-05-24 15:50:11', NULL, 'los jazmines 342', -33.6855446, -71.2171806, 1, 0, ''),
(157, 3, 'niño intoxicado', 1, '2024-05-24 15:50:49', NULL, 'colegio melipilla', -33.6855446, -71.2171806, 9, 0, ''),
(158, 3, 'caída de árbol obstruye la vía', 1, '2024-05-24 15:51:40', NULL, 'Av. Loreto', -33.6855446, -71.2171806, 1, 1, '');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_ev_cierre`
--

CREATE TABLE `tm_ev_cierre` (
  `id_cierre` int(11) NOT NULL,
  `usu_id` int(11) NOT NULL,
  `ev_id` int(11) NOT NULL,
  `detalle` varchar(300) NOT NULL,
  `motivo` int NOT NULL,
  `adjunto` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_ev_niv`
--

CREATE TABLE `tm_ev_niv` (
  `ev_niv_id` int(11) NOT NULL,
  `ev_niv_nom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_ev_niv`
--

INSERT INTO `tm_ev_niv` (`ev_niv_id`, `ev_niv_nom`) VALUES
(0, 'General'),
(1, 'Critico'),
(2, 'Medio'),
(3, 'Bajo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_ev_tm_unid`
--

CREATE TABLE `tm_ev_tm_unid` (
  `id_inter` int(11) NOT NULL,
  `ev_id` int(11) DEFAULT NULL,
  `unid_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_ev_tm_unid`
--

INSERT INTO `tm_ev_tm_unid` (`id_inter`, `ev_id`, `unid_id`) VALUES
(6, 85, 2),
(7, 85, 5),
(8, 84, 3),
(9, 84, 4),
(10, 84, 5),
(11, 83, 1),
(12, 37, 1),
(13, 24, 3),
(14, 24, 4),
(15, 24, 5),
(16, 22, 1),
(17, 22, 2),
(18, 22, 3),
(19, 21, 1),
(20, 21, 2),
(21, 21, 5),
(22, 19, 2),
(23, 16, 4),
(24, 10, 1),
(25, 10, 3),
(26, 10, 5),
(111, 137, 2),
(115, 138, 3),
(116, 138, 4),
(118, 139, 2),
(119, 140, 2),
(121, 140, 4),
(220, 115, 2),
(229, 141, 4),
(230, 142, 2),
(231, 142, 3),
(232, 142, 4),
(233, 142, 5),
(244, 143, 2),
(245, 143, 3),
(246, 143, 4),
(247, 143, 5),
(274, 146, 1),
(288, 147, 2),
(289, 147, 4),
(290, 147, 3),
(299, 149, 5),
(301, 148, 3),
(302, 114, 4),
(303, 144, 4),
(304, 145, 3),
(308, 150, 3),
(309, 150, 5),
(310, 151, 3),
(312, 152, 3),
(313, 153, 3),
(314, 154, 2),
(315, 154, 3),
(316, 155, 2),
(317, 155, 3),
(318, 155, 4),
(319, 155, 5),
(320, 156, 2),
(321, 156, 5),
(322, 156, 4),
(323, 156, 3),
(324, 157, 2),
(325, 157, 4),
(326, 157, 3),
(327, 157, 5),
(329, 158, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_funcionario`
--

CREATE TABLE `tm_funcionario` (
  `funci_rut` int(8) NOT NULL,
  `funci_pnom` varchar(15) NOT NULL,
  `funci_snom` varchar(15) DEFAULT NULL,
  `funci_appater` varchar(15) NOT NULL,
  `funci_apmater` varchar(15) DEFAULT NULL,
  `funci_email` varchar(50) NOT NULL,
  `funci_celular` int(11) NOT NULL,
  `sector_id` int(11) DEFAULT NULL,
  `funci_direc` varchar(50) NOT NULL,
  `ciudad_id` int(11) DEFAULT NULL,
  `comuna_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_f_territorial`
--

CREATE TABLE `tm_f_territorial` (
  `f_terri_rut` int(11) NOT NULL,
  `f_terri_pnom` varchar(15) NOT NULL,
  `f_terri_direc` varchar(50) NOT NULL,
  `f_terri_email` varchar(50) NOT NULL,
  `f_terri_celular` int(9) NOT NULL,
  `sector_id` int(11) DEFAULT NULL,
  `ciudad_id` int(11) DEFAULT NULL,
  `f_terri_snom` varchar(15) DEFAULT NULL,
  `f_terri_apmater` varchar(15) DEFAULT NULL,
  `f_terri_appater` varchar(15) NOT NULL,
  `comuna_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_institucion`
--

CREATE TABLE `tm_institucion` (
  `institu_id` int(11) NOT NULL,
  `institu_nom` varchar(50) NOT NULL,
  `institu_direc` varchar(50) NOT NULL,
  `sector_id` int(11) DEFAULT NULL,
  `ciudad_id` int(11) DEFAULT NULL,
  `comuna_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_pais`
--

CREATE TABLE `tm_pais` (
  `pais_id` int(11) NOT NULL,
  `pais_nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_pais`
--

INSERT INTO `tm_pais` (`pais_id`, `pais_nom`) VALUES
(1, 'Afganistán'),
(2, 'Albania'),
(3, 'Alemania'),
(4, 'Andorra'),
(5, 'Angola'),
(6, 'Antigua y Barbuda'),
(7, 'Arabia Saudita'),
(8, 'Argelia'),
(9, 'Argentina'),
(10, 'Armenia'),
(11, 'Australia'),
(12, 'Austria'),
(13, 'Azerbaiyán'),
(14, 'Bahamas'),
(15, 'Bangladés'),
(16, 'Barbados'),
(17, 'Baréin'),
(18, 'Bélgica'),
(19, 'Belice'),
(20, 'Benín'),
(21, 'Bielorrusia'),
(22, 'Birmania/Myanmar'),
(23, 'Bolivia'),
(24, 'Bosnia y Herzegovina'),
(25, 'Botsuana'),
(26, 'Brasil'),
(27, 'Brunéi'),
(28, 'Bulgaria'),
(29, 'Burkina Faso'),
(30, 'Burundi'),
(31, 'Bután'),
(32, 'Cabo Verde'),
(33, 'Camboya'),
(34, 'Camerún'),
(35, 'Canadá'),
(36, 'Catar'),
(37, 'Chad'),
(38, 'Chile'),
(39, 'China'),
(40, 'Chipre'),
(41, 'Ciudad del Vaticano'),
(42, 'Colombia'),
(43, 'Comoras'),
(44, 'Corea del Norte'),
(45, 'Corea del Sur'),
(46, 'Costa de Marfil'),
(47, 'Costa Rica'),
(48, 'Croacia'),
(49, 'Cuba'),
(50, 'Dinamarca'),
(51, 'Dominica'),
(52, 'Ecuador'),
(53, 'Egipto'),
(54, 'El Salvador'),
(55, 'Emiratos Árabes Unidos'),
(56, 'Eritrea'),
(57, 'Eslovaquia'),
(58, 'Eslovenia'),
(59, 'España'),
(60, 'Estados Unidos'),
(61, 'Estonia'),
(62, 'Etiopía'),
(63, 'Filipinas'),
(64, 'Finlandia'),
(65, 'Fiyi'),
(66, 'Francia'),
(67, 'Gabón'),
(68, 'Gambia'),
(69, 'Georgia'),
(70, 'Ghana'),
(71, 'Granada'),
(72, 'Grecia'),
(73, 'Guatemala'),
(74, 'Guyana'),
(75, 'Guinea'),
(76, 'Guinea ecuatorial'),
(77, 'Guinea-Bisáu'),
(78, 'Haití'),
(79, 'Honduras'),
(80, 'Hungría'),
(81, 'India'),
(82, 'Indonesia'),
(83, 'Irak'),
(84, 'Irán'),
(85, 'Irlanda'),
(86, 'Islandia'),
(87, 'Islas Marshall'),
(88, 'Islas Salomón'),
(89, 'Israel'),
(90, 'Italia'),
(91, 'Jamaica'),
(92, 'Japón'),
(93, 'Jordania'),
(94, 'Kazajistán'),
(95, 'Kenia'),
(96, 'Kirguistán'),
(97, 'Kiribati'),
(98, 'Kuwait'),
(99, 'Laos'),
(100, 'Lesoto'),
(101, 'Letonia'),
(102, 'Líbano'),
(103, 'Liberia'),
(104, 'Libia'),
(105, 'Liechtenstein'),
(106, 'Lituania'),
(107, 'Luxemburgo'),
(108, 'Macedonia del Norte'),
(109, 'Madagascar'),
(110, 'Malasia'),
(111, 'Malaui'),
(112, 'Maldivas'),
(113, 'Malí'),
(114, 'Malta'),
(115, 'Marruecos'),
(116, 'Mauricio'),
(117, 'Mauritania'),
(118, 'México'),
(119, 'Micronesia'),
(120, 'Moldavia'),
(121, 'Mónaco'),
(122, 'Mongolia'),
(123, 'Montenegro'),
(124, 'Mozambique'),
(125, 'Namibia'),
(126, 'Nauru'),
(127, 'Nepal'),
(128, 'Nicaragua'),
(129, 'Níger'),
(130, 'Nigeria'),
(131, 'Noruega'),
(132, 'Nueva Zelanda'),
(133, 'Omán'),
(134, 'Países Bajos'),
(135, 'Pakistán'),
(136, 'Palaos'),
(137, 'Panamá'),
(138, 'Papúa Nueva Guinea'),
(139, 'Paraguay'),
(140, 'Perú'),
(141, 'Polonia'),
(142, 'Portugal'),
(143, 'Reino Unido'),
(144, 'República Centroafricana'),
(145, 'República Checa'),
(146, 'República del Congo'),
(147, 'República Democrática del Congo'),
(148, 'República Dominicana'),
(149, 'República Sudafricana'),
(150, 'Ruanda'),
(151, 'Rumanía'),
(152, 'Rusia'),
(153, 'Samoa'),
(154, 'San Cristóbal y Nieves'),
(155, 'San Marino'),
(156, 'San Vicente y las Granadinas'),
(157, 'Santa Lucía'),
(158, 'Santo Tomé y Príncipe'),
(159, 'Senegal'),
(160, 'Serbia'),
(161, 'Seychelles'),
(162, 'Sierra Leona'),
(163, 'Singapur'),
(164, 'Siria'),
(165, 'Somalia'),
(166, 'Sri Lanka'),
(167, 'Suazilandia'),
(168, 'Sudán'),
(169, 'Sudán del Sur'),
(170, 'Suecia'),
(171, 'Suiza'),
(172, 'Surinam'),
(173, 'Tailandia'),
(174, 'Tanzania'),
(175, 'Tayikistán'),
(176, 'Timor Oriental'),
(177, 'Togo'),
(178, 'Tonga'),
(179, 'Trinidad y Tobago'),
(180, 'Túnez'),
(181, 'Turkmenistán'),
(182, 'Turquía'),
(183, 'Tuvalu'),
(184, 'Ucrania'),
(185, 'Uganda'),
(186, 'Uruguay'),
(187, 'Uzbekistán'),
(188, 'Vanuatu'),
(189, 'Venezuela'),
(190, 'Vietnam'),
(191, 'Yemen'),
(192, 'Yibuti'),
(193, 'Zambia'),
(194, 'Zimbabue');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_region`
--

CREATE TABLE `tm_region` (
  `region_id` int(11) NOT NULL,
  `region_nom` varchar(50) NOT NULL,
  `pais_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_region`
--

INSERT INTO `tm_region` (`region_id`, `region_nom`, `pais_id`) VALUES
(1, 'Arica y Parinacota', 38),
(2, 'Tarapacá', 38),
(3, 'Antofagasta', 38),
(4, 'Atacama', 38),
(5, 'Coquimbo', 38),
(6, 'Valparaíso', 38),
(7, 'Libertador General Bernardo O’Higgins', 38),
(8, 'Maule', 38),
(9, 'Ñuble', 38),
(10, 'Biobío', 38),
(11, 'La Araucanía', 38),
(12, 'Los Ríos', 38),
(13, 'Los Lagos', 38),
(14, 'Aysén del General Carlos Ibáñez del Campo', 38),
(15, 'Magallanes y la Antártica Chilena', 38),
(16, 'Metropolitana de Santiago', 38);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_sector`
--

CREATE TABLE `tm_sector` (
  `sector_id` int(11) NOT NULL,
  `sector_nom` varchar(50) NOT NULL,
  `ciudad_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_usu_tipo`
--

CREATE TABLE `tm_usu_tipo` (
  `usu_tipo_id` int(11) NOT NULL,
  `usu_tipo_nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_usu_tipo`
--

INSERT INTO `tm_usu_tipo` (`usu_tipo_id`, `usu_tipo_nom`) VALUES
(1, 'Basico'),
(2, 'Administrador'),
(3, 'Super usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_unidad`
--

CREATE TABLE `tm_unidad` (
  `unid_id` int(11) NOT NULL,
  `unid_nom` varchar(20) NOT NULL,
  `unid_est` int(11) NOT NULL,
  `responsable_rut` int(8) DEFAULT NULL,
  `reemplazante_rut` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_unidad`
--

INSERT INTO `tm_unidad` (`unid_id`, `unid_nom`, `unid_est`, `responsable_rut`, `reemplazante_rut`) VALUES
(1, 'DGA', 2, 20879105, 20879105),
(2, 'Seguridad Publica', 1, 20879105, 20879105),
(3, 'Carabineros', 1, 20879105, 20879105),
(4, 'Bomberos', 1, 20879105, 20879105),
(5, 'Ambulancia', 3, 20879105, 20879105);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_usuario`
--

CREATE TABLE `tm_usuario` (
  `usu_id` int(11) NOT NULL,
  `usu_nom` varchar(150) DEFAULT NULL,
  `usu_ape` varchar(150) DEFAULT NULL,
  `usu_correo` varchar(150) NOT NULL,
  `usu_telefono` int(11) NOT NULL,
  `usu_name` varchar(45) NOT NULL,
  `usu_pass` varchar(33) NOT NULL,
  `fecha_crea` datetime DEFAULT NULL,
  `fecha_modi` datetime DEFAULT NULL,
  `fecha_elim` datetime DEFAULT NULL,
  `estado` int(11) NOT NULL,
  `usu_unidad` int(11) NOT NULL,
  `usu_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla mantenedor de usuarios';

--
-- Volcado de datos para la tabla `tm_usuario`
--

INSERT INTO `tm_usuario` (`usu_id`, `usu_nom`, `usu_ape`, `usu_correo`, `usu_telefono`, `usu_name`, `usu_pass`, `fecha_crea`, `fecha_modi`, `fecha_elim`, `estado`, `usu_tipo`, `usu_unidad`) VALUES
(1, 'Cristian', 'Suazo', 'crhiiss26@gmail.com', 968093527, 'csuazo', 'e10adc3949ba60abbe56e057f20f883e', '2023-12-30 13:15:58', NULL, NULL, 1, 2,1),
(2, 'Admin', 'admin', 'admin@mail.com', 961718297, 'admin', '202cb962ac59075b964b07152d234b70', '2024-01-03 18:09:32', NULL, NULL, 1, 2,1),
(3, 'Marco', 'Silva', 'marco.silvaponce10@gmail.com', 997827161, 'msilva', '827ccb0eea8a706c4c34a16891f84e7b', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3,2),
(4, 'marquisio', 'Silviña', 'memo@test.com', 965412724, 'marquisio', '827ccb0eea8a706c4c34a16891f84e7b', '2024-03-20 13:01:58', NULL, NULL, 1, 3,3);



--
-- Estructura de tabla para la tabla tm_rob_pass
--
CREATE TABLE tm_rob_pass (
	`rob_id` INT NOT NUll auto_increment,
    `usu_id` INT NOT NULL unique,
    `mayuscula` BOOLEAN NOT NULL,
    `minuscula` BOOLEAN NOT NULL,
    `especiales` BOOLEAN NOT NULL,
    `numeros` BOOLEAN NOT NULL,
    `largo` BOOLEAN NOT NULL,
    `fecha_modi` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`rob_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
--  Estructura de robusted que debe cumplir una undiad
--
CREATE TABLE tm_rob_unidad(
	`rob_id` INT NOT NUll auto_increment,
  `usu_unidad` INT NOT NULL unique,
  `mayuscula` BOOLEAN NOT NULL,
  `minuscula` BOOLEAN NOT NULL,
  `especiales` BOOLEAN NOT NULL,
  `numeros` BOOLEAN NOT NULL,
  `largo` INT NOT NULL,
  `camb_dias` INT NOT NULL,
  `fecha_modi` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`rob_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tm_rob_unidad (usu_unidad, mayuscula, minuscula, especiales, numeros, largo, camb_dias)
VALUES (1, 1, 1, 0, 1,8, 60),
(2, 1, 1, 0, 1,8, 60),
(3, 1, 1, 0, 1,8, 60),
(4, 1, 1, 0, 1,8, 60),
(5, 1, 1, 0, 1,8, 60);
--
-- volcado de datos para la tabla tm_rob_pass
--
insert into tm_rob_pass(`usu_id`,`mayuscula`,`minuscula`,`especiales`,`numeros`,`largo`,`fecha_modi`) values
(1,false,false,false,true,false,'2024-05-16 15:47:31'),
(2,false,false,false,true,false,'2024-05-16 15:47:31'),
(3,false,false,false,true,false,'2024-05-16 15:47:31'),
(4,false,false,false,true,false,'2024-05-16 15:47:31');
--
-- Estructua de tabla para tm_reg_log
--
CREATE TABLE tm_reg_log(
`log_id` INT NOT NULL AUTO_INCREMENT,
`usu_id` INT NOT NULL,
`op` VARCHAR(150),
`fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
`detalle` VARCHAR(250),
PRIMARY KEY (`log_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- estructura de tabla para tm_cierre_motivo
--
CREATE TABLE tm_cierre_motivo(
	`mov_id` INT NOT NUll auto_increment,
    `motivo` VARCHAR(250) NOT NULL unique,
    `fecha_crea` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`mov_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Insercion de datos para tm_cierre_motivo
--

INSERT INTO `tm_cierre_motivo`(`mov_id`,`motivo`) VALUES
(1,'Controlado y extinguido'),
(2,'Sin víctimas ni daños mayores'),
(3,'Daños estructurales controlados'),
(4,'Necesidad de investigacion adicional'),
(5,'Requiere seguimiento por posibles puntos calientes'),
(6,'Sospechosos detenidos y bajo custodia'),
(7,'Sin heridos mayores'),
(8,'Recuperación de bienes robados'),
(9,'Situación controlada y resuelta'),
(10,'Víctimas atendidas y trasladadas'),
(11,'Intoxicación leve, tratado en el lugar'),
(12,'Causa de intoxicación identificada y mitigada'),
(13,'Necesidad de seguimiento médico adicional'),
(14,'Contaminación controlada y descontaminación realizada'),
(15,'Árbol removido y área despejada'),
(16,'Sin daños a personas o propiedades'),
(17,'Daños a infraestructura reparados'),
(18,'Necesidad de evaluación adicional de árboles cercanos'),
(19,'Servicios públicos restablecidos'),
(20,'Víctimas estabilizadas y trasladadas a hospital'),
(21,'Vehículos retirados y tráfico restablecido'),
(22,'Necesidad de investigación adicional por parte de la policía'),
(23,'Seguimiento de seguro y responsabilidades'),
(24,'Situación controlada y disuelta'),
(25,'Detenciones realizadas y sospechosos en custodia'),
(26,'Necesidad de patrullaje adicional en la zona'),
(27,'Investigación adicional requerida');

--
-- ESTRUCTURA DE TABLA tm_mv_ca
--
CREATE TABLE tm_motivo_cate(
    `mov_cat_id` INT NOT NUll auto_increment,
    `cat_id` INT NOT NULL,
    `mov_id` INT NOT NULL,
    `activo` BOOLEAN NOT NULL,
    PRIMARY KEY (`mov_cat_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Índices para tablas volcadas
--

INSERT INTO `tm_motivo_cate` (`cat_id`, `mov_id`, `activo`) VALUES
(1, 1, TRUE), -- Incendios - Controlado y extinguido
(1, 2, TRUE), -- Incendios - Sin víctimas ni daños mayores
(1, 3, TRUE), -- Incendios - Daños estructurales controlados
(1, 4, TRUE), -- Incendios - Necesidad de investigacion adicional
(1, 5, TRUE), -- Incendios - Requiere seguimiento por posibles puntos calientes
(2, 22, TRUE), -- Asaltos - Necesidad de investigación adicional por parte de la policía
(2, 25, TRUE), -- Asaltos - Detenciones realizadas y sospechosos en custodia
(2, 26, TRUE), -- Asaltos - Necesidad de patrullaje adicional en la zona
(2, 27, TRUE), -- Asaltos - Investigación adicional requerida
(3, 20, TRUE), -- Accidente Vehicular - Víctimas estabilizadas y trasladadas a hospital
(3, 21, TRUE), -- Accidente Vehicular - Vehículos retirados y tráfico restablecido
(3, 22, TRUE), -- Accidente Vehicular - Necesidad de investigación adicional por parte de la policía
(3, 23, TRUE), -- Accidente Vehicular - Seguimiento de seguro y responsabilidades
(4, 24, TRUE), -- Desorden Público - Situación controlada y disuelta
(4, 25, TRUE), -- Desorden Público - Detenciones realizadas y sospechosos en custodia
(4, 26, TRUE), -- Desorden Público - Necesidad de patrullaje adicional en la zona
(4, 27, TRUE), -- Desorden Público - Investigación adicional requerida
(5, 6, TRUE),  -- Otros - Sospechosos detenidos y bajo custodia
(5, 9, TRUE),  -- Otros - Situación controlada y resuelta
(9, 11, TRUE), -- Intoxicación - Intoxicación leve, tratado en el lugar
(9, 12, TRUE), -- Intoxicación - Causa de intoxicación identificada y mitigada
(9, 13, TRUE), -- Intoxicación - Necesidad de seguimiento médico adicional
(9, 14, TRUE), -- Intoxicación - Contaminación controlada y descontaminación realizada
(7, 15, TRUE), -- Caida de arbol - Árbol removido y área despejada
(7, 16, TRUE), -- Caida de arbol - Sin daños a personas o propiedades
(7, 17, TRUE), -- Caida de arbol - Daños a infraestructura reparados
(7, 18, TRUE), -- Caida de arbol - Necesidad de evaluación adicional de árboles cercanos
(7, 19, TRUE); -- Caida de arbol - Servicios públicos restablecidos


--
-- estructura de tabla para tm_cierre_motivo
--
CREATE TABLE tm_emergencia_detalle(
	`emergencia_id` INT NOT NUll auto_increment,
  `ev_id` INT NOT NULL,
  `usu_id` INT NOT NULL,
  `ev_desc` MEDIUMTEXT NOT NULL, 
  `ev_inicio` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `ev_est` INT NOT NULL,
  PRIMARY KEY (`emergencia_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Insercion de datos para tm_cierre_motivo
--

INSERT INTO `tm_emergencia_detalle`(`emergencia_id`,`ev_id`,`usu_id`,`ev_desc`,`ev_est`) VALUES
(1,161,1,'Requiero Soporte',1),
(2,161,2,'Como puedo ayudarte',1),
(3,161,1,'Requiero su ayuda para cambiar la SSD de mi laptop',1),
(4,161,2,'No hay problema indicame numero de serie',1),
(5,161,1,'SN:816511616XX',1);


--
-- Indices de la tabla `tm_camb_asig`
--
ALTER TABLE `tm_camb_asig`
  ADD PRIMARY KEY (`camb_asig_id`);

--
-- Indices de la tabla `tm_categoria`
--
ALTER TABLE `tm_categoria`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indices de la tabla `tm_ciudad`
--
ALTER TABLE `tm_ciudad`
  ADD PRIMARY KEY (`ciudad_id`);

--
-- Indices de la tabla `tm_comuna`
--
ALTER TABLE `tm_comuna`
  ADD PRIMARY KEY (`comuna_id`);

--
-- Indices de la tabla `tm_estado`
--
ALTER TABLE `tm_estado`
  ADD PRIMARY KEY (`est_id`);

--
-- Indices de la tabla `tm_est_unidad`
--
ALTER TABLE `tm_est_unidad`
  ADD PRIMARY KEY (`est_un_id`);

--
-- Indices de la tabla `tm_evento`
--
ALTER TABLE `tm_evento`
  ADD PRIMARY KEY (`ev_id`);

--
-- Indices de la tabla `tm_ev_cierre`
--
ALTER TABLE `tm_ev_cierre`
  ADD PRIMARY KEY (`id_cierre`);

--
-- Indices de la tabla `tm_ev_niv`
--
ALTER TABLE `tm_ev_niv`
  ADD PRIMARY KEY (`ev_niv_id`);

--
-- Indices de la tabla `tm_ev_tm_unid`
--
ALTER TABLE `tm_ev_tm_unid`
  ADD PRIMARY KEY (`id_inter`);

--
-- Indices de la tabla `tm_funcionario`
--
ALTER TABLE `tm_funcionario`
  ADD PRIMARY KEY (`funci_rut`);

--
-- Indices de la tabla `tm_f_territorial`
--
ALTER TABLE `tm_f_territorial`
  ADD PRIMARY KEY (`f_terri_rut`);

--
-- Indices de la tabla `tm_institucion`
--
ALTER TABLE `tm_institucion`
  ADD PRIMARY KEY (`institu_id`);

--
-- Indices de la tabla `tm_pais`
--
ALTER TABLE `tm_pais`
  ADD PRIMARY KEY (`pais_id`);

--
-- Indices de la tabla `tm_region`
--
ALTER TABLE `tm_region`
  ADD PRIMARY KEY (`region_id`);

--
-- Indices de la tabla `tm_sector`
--
ALTER TABLE `tm_sector`
  ADD PRIMARY KEY (`sector_id`);

--
-- Indices de la tabla `tm_usu_tipo`
--
ALTER TABLE `tm_usu_tipo`
  ADD PRIMARY KEY (`usu_tipo_id`);

--
-- Indices de la tabla `tm_unidad`
--
ALTER TABLE `tm_unidad`
  ADD PRIMARY KEY (`unid_id`);

--
-- Indices de la tabla `tm_usuario`
--
ALTER TABLE `tm_usuario`
  ADD PRIMARY KEY (`usu_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tm_camb_asig`
--
ALTER TABLE `tm_camb_asig`
  MODIFY `camb_asig_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `tm_categoria`
--
ALTER TABLE `tm_categoria`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tm_ciudad`
--
ALTER TABLE `tm_ciudad`
  MODIFY `ciudad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_comuna`
--
ALTER TABLE `tm_comuna`
  MODIFY `comuna_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=414;

--
-- AUTO_INCREMENT de la tabla `tm_estado`
--
ALTER TABLE `tm_estado`
  MODIFY `est_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tm_est_unidad`
--
ALTER TABLE `tm_est_unidad`
  MODIFY `est_un_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tm_evento`
--
ALTER TABLE `tm_evento`
  MODIFY `ev_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT de la tabla `tm_ev_cierre`
--
ALTER TABLE `tm_ev_cierre`
  MODIFY `id_cierre` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_ev_niv`
--
ALTER TABLE `tm_ev_niv`
  MODIFY `ev_niv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tm_ev_tm_unid`
--
ALTER TABLE `tm_ev_tm_unid`
  MODIFY `id_inter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT de la tabla `tm_f_territorial`
--
ALTER TABLE `tm_f_territorial`
  MODIFY `f_terri_rut` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_pais`
--
ALTER TABLE `tm_pais`
  MODIFY `pais_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT de la tabla `tm_region`
--
ALTER TABLE `tm_region`
  MODIFY `region_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `tm_sector`
--
ALTER TABLE `tm_sector`
  MODIFY `sector_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_usu_tipo`
--
ALTER TABLE `tm_usu_tipo`
  MODIFY `usu_tipo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tm_unidad`
--
ALTER TABLE `tm_unidad`
  MODIFY `unid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tm_usuario`
--
ALTER TABLE `tm_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
