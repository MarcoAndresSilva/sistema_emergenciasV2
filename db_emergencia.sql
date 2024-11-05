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
  `ev_id` int(11) NOT NULL AUTO_INCREMENT,
  `usu_id` int(11) NOT NULL,
  `ev_desc` varchar(50) NOT NULL,
  `ev_est` int(11) NOT NULL,
  `ev_inicio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ev_final` datetime DEFAULT NULL,
  `ev_direc` varchar(100) NOT NULL,
  `ev_latitud`  DOUBLE NOT NULL,
  `ev_longitud` DOUBLE NOT NULL,
  `cat_id` int(11) NOT NULL,
  `ev_niv` int(11) NOT NULL,
  `ev_img` varchar(123),
  PRIMARY KEY (`ev_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_evento`
--

INSERT INTO `tm_evento` (`usu_id`, `ev_desc`, `ev_est`, `ev_inicio`, `ev_final`, `ev_direc`, `ev_latitud`, `ev_longitud`, `cat_id`, `ev_niv`, `ev_img`) VALUES
( 1, 'Descripcion', 2, '2024-01-08 12:09:06', NULL, 'Anita Fresno 327', -33.6860493, -71.2150019, 9, 1, NULL),
( 1, 'Descripcion', 2, '2024-01-08 17:03:26', NULL, 'loica parcela 7', -33.6860493, -71.2150019, 1, 3, NULL),
( 1, 'Descripcion', 2, '2024-01-11 12:53:31', NULL, 'Colonial 2', -33.6855446, -71.2171806, 7, 1, NULL),
( 1, 'Descripcion', 2, '2024-01-15 13:11:52', NULL, 'Libertad 2031', -33.6855446, -71.2171806, 5, 3, NULL),
( 1, 'Constado de carretera km18', 2, '2024-01-16 18:44:15', NULL, 'Ruta 78', -33.6855446, -71.2171806, 1, 3, NULL),
( 1, 'Costado de Carretera direccion santiago', 2, '2024-01-16 19:21:18', NULL, 'Ruta 78', -33.6855446, -71.2171806, 1, 2, NULL),
( 1, 'Planta interior', 2, '2024-01-21 20:35:37', NULL, 'Anita Fresno 327', -33.6860493, -71.2150019, 1, 2, NULL),
( 2, 'caso de incendio en la villa colonial', 2, '2024-01-31 09:13:23', '2024-05-24 15:48:12', 'colonial 13', -33.6855421, -71.2171669, 1, 1, ''),
( 2, 'Planta interior', 2, '2024-02-02 08:18:52', '2024-02-06 10:45:59', 'Plaza cardenales', -33.6855446, -71.2171806, 1, 1, ''),
( 2, 'Planta interior', 2, '2024-02-06 11:26:10', '2024-02-15 11:27:38', 'Sin dirección', -33.6855446, -71.2171806, 1, 0, ''),
( 2, 'Asalto', 2, '2024-02-15 11:30:32', '2024-05-24 10:19:11', 'cunco2207', -33.6779895, -71.1743368, 1, 2, ''),
( 2, 'evento test', 2, '2024-02-16 09:29:05', '2024-05-24 12:13:39', 'muni melipilla', -33.6860493, -71.2150019, 4, 2, ''),
( 2, 'gdfhfgdh', 2, '2024-03-21 17:21:00', '2024-03-22 11:17:20', 'gfhfdghhggh', -33.6860493, -71.2150019, 9, 3, ''),
( 2, 'se cayo un arbol', 2, '2024-03-22 09:37:49', '2024-05-24 10:03:19', 'Sin dirección', -33.6856435, -71.2230970, 7, 0, ''),
( 2, 'asaltacion', 2, '2024-03-22 16:10:33', '2024-05-24 15:49:22', 'Sin dirección', -33.6855738, -71.2172411, 2, 1, ''),
( 2, 'efsdfsdfds', 2, '2024-03-25 16:50:19', '2024-05-24 12:13:42', 'Sin dirección', -33.6855794, -71.2172355, 9, 2, ''),
( 2, 'barristas quemando neumaticos', 2, '2024-03-26 10:08:15', '2024-05-24 15:49:19', 'centro de melipila', -33.6855446, -71.2171806, 4, 1, ''),
( 2, 'barristas quemando neumaticos', 2, '2024-05-22 10:08:15', '2024-05-24 15:39:45', 'centro de melipila', -33.6855446, -71.2171806, 4, 1, ''),
( 3, 'dfsafdsrgde', 2, '2024-05-22 23:27:39', '2024-05-24 15:07:47', 'Sin dirección', -33.6855446, -71.2171806, 2, 1, ''),
( 3, 'accidente arma corta', 2, '2024-05-24 09:56:27', '2024-05-24 10:03:30', 'Sin dirección', -33.6809571, -71.2166493, 2, 0, ''),
( 3, 'barricadas', 2, '2024-05-24 10:01:55', '2024-05-24 10:03:33', 'mackena #5465', -33.6855446, -71.2171806, 4, 0, ''),
( 3, 'casa habitacion', 2, '2024-05-24 10:02:36', '2024-05-24 10:03:36', 'bernardo leigthon 87687', -33.6855446, -71.2171806, 1, 0, ''),
( 3, 'casa habitacion', 1, '2024-05-24 15:50:11', NULL, 'los jazmines 342', -33.6855446, -71.2171806, 1, 0, ''),
( 3, 'niño intoxicado', 1, '2024-05-24 15:50:49', NULL, 'colegio melipilla', -33.6855446, -71.2171806, 9, 0, ''),
( 3, 'caída de árbol obstruye la vía', 1, '2024-05-24 15:51:40', NULL, 'Av. Loreto', -33.6855446, -71.2171806, 1, 1, '');
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
  `adjunto` varchar(300)
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
-- Estructura de tabla para la tabla `tm_asignado`
--

CREATE TABLE `tm_asignado` (
  `id_inter` int(11) NOT NULL AUTO_INCREMENT,
  `ev_id` int(11) DEFAULT NULL,
  `sec_id` int(11) NOT NULL,
  PRIMARY KEY (`id_inter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tm_asignado`
--

INSERT INTO `tm_asignado` ( `ev_id`, `sec_id`) VALUES
( 10, 2),( 10, 5),
( 9, 3),( 9, 4), ( 9, 5), 
( 8, 1),
( 12, 1),
( 13, 3),( 13, 4),( 13, 5),
( 14, 1),( 14, 2),( 14, 3),
( 15, 1),( 15, 2),( 15, 5),
( 16, 2),
( 17, 4),
( 18, 1),( 18, 3),( 18, 5),
( 19, 2),( 20, 3),( 20, 4),
( 21, 2),( 22, 2),( 22, 4),
( 23, 2),
( 24, 4),
( 25, 2),( 25, 3),( 25, 4),( 25, 5),
( 1, 2), ( 1, 3), ( 1, 4), ( 1, 5),
( 2, 1),
( 3, 2), ( 3, 4), ( 3, 3),
( 4, 5), 
( 5, 3), 
( 6, 4), 
( 7, 4), 
( 8, 3), 
( 9, 3), ( 9, 5),
( 10, 3),
( 11, 3),
( 12, 3),
( 13, 2),( 13, 3),
( 14, 2),( 14, 3),( 14, 4),( 14, 5),
( 15, 2),( 15, 5),( 15, 4),( 15, 3),
( 16, 2),( 16, 4),( 16, 3),( 16, 5),
( 17, 1);


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
(1, 'DGA', 2, 20879107, 20879105),
(2, 'Seguridad Publica', 1, 20879105, 20879105),
(3, 'Control', 1, 20879106, 20879105),
(4, 'Emergencias', 1, 20879139, 20879106),
(5, 'Informatica', 3, 20879111, 20879106);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_usuario`
--

CREATE TABLE IF NOT EXISTS `tm_seccion` (
  `sec_id` int(11) NOT NULL auto_increment,
  `sec_unidad` int(11) NOT NULL,
  `sec_nombre` varchar(150) NOT NULL,
  `sec_detalle` varchar(250) NOT NULL,
  `sec_est` varchar(250) DEFAULT 1,
  PRIMARY KEY (`sec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `tm_seccion` (`sec_id`, `sec_nombre`, `sec_detalle`, `sec_unidad`) VALUES
(1, 'grupo1', 'Administracion de la aplicacion', '1'),
(2, 'grupo2', 'Administracion de usuarios', '2'),
(3, 'grupo3', 'Administrar Evento', '3'),
(4, 'grupo4', 'Encargados de tramposte', '4'),
(5, 'grupo5', 'Desarrollo de la aplicacion', '5');

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
  `usu_seccion` int(11) NOT NULL,
  `usu_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla mantenedor de usuarios';

--
-- Volcado de datos para la tabla `tm_usuario`
--

INSERT INTO `tm_usuario` (`usu_id`, `usu_nom`, `usu_ape`, `usu_correo`, `usu_telefono`, `usu_name`, `usu_pass`, `fecha_crea`, `fecha_modi`, `fecha_elim`, `estado`, `usu_tipo`, `usu_unidad`, `usu_seccion`) VALUES
(1, 'Cristian', 'Suazo', 'cristian.suazo@munimelipilla.cl', 968093527, 'csuazo', 'e10adc3949ba60abbe56e057f20f883e', '2023-12-30 13:15:58', NULL, NULL, 1, 2,1,3),
(2, 'Admin', 'admin', 'admin@mail.com', 961718297, 'admin', md5('123'), '2024-01-03 18:09:32', NULL, NULL, 1, 2,5,5),
(3, 'Marco', 'Silva', 'marco.silva@munimelipilla.cl', 997827161, 'msilva', '827ccb0eea8a706c4c34a16891f84e7b', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 3,3,5),
(4, 'Nelson', 'Navarro', 'nelson.navarro@munimelipilla.cl', 965412724, 'nnavarro', 'b08cab3a853b8d1e609905d7161daf80', '2024-03-20 13:01:58', NULL, NULL, 1, 3,4,5),
(5, 'Dangelo', 'Contreras', 'dangelo.contreras@munimelipilla.cl', 965412724, 'dcontreras',  md5('Dcontrer!123') , '2024-03-20 13:01:58', NULL, NULL, 1, 3,2,2),
(6, 'Jose', 'Ruminot', 'joseluis.ruminot@munimelipilla.cl', 965412724, 'JRumino',  md5('JRumino!1234') , '2024-03-20 13:01:58', NULL, NULL, 1, 3,2,2);



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
(4,true,true,true,true,true,'2024-05-16 15:47:31');
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
  `privado` INT DEFAULT 0,
  PRIMARY KEY (`emergencia_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Insercion de datos para tm_cierre_motivo
--

INSERT INTO `tm_emergencia_detalle`(`emergencia_id`,`ev_id`,`usu_id`,`ev_desc`,`ev_est`) VALUES
(1,1,1,'Requiero Soporte',1),
(2,1,2,'Como puedo ayudarte',1),
(3,1,1,'Requiero su ayuda para cambiar la SSD de mi laptop',1),
(4,1,2,'No hay problema indicame numero de serie',1),
(5,1,1,'SN:816511616XX',1);

--
-- estructura de tm_noticia 
--

CREATE TABLE tm_noticia(
`noticia_id` INT NOT NULL auto_increment,
`asunto` VARCHAR(50) NOT NULL,
`mensaje` VARCHAR(150) NOT NULL,
`url` VARCHAR(250),
`fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`noticia_id`)
);
--
-- estructura de tm_noticia_usuario
--
CREATE TABLE tm_noticia_usuario(
`noti_usu_id` INT NOT NULL auto_increment,
`noticia_id` INT NOT NULL,
`usu_id` INT NOT NULL,
`leido` BOOLEAN NOT NULL DEFAULT false,
`fecha_lectura` DATETIME DEFAULT NULL,
PRIMARY KEY (`noti_usu_id`)
);
--
-- estructura de tm_regla_envio
--

CREATE TABLE tm_regla_envio (
  id_regla INT  NOT NULL auto_increment,
  asunto VARCHAR(150) UNIQUE,
  unidad VARCHAR(150),
  seccion VARCHAR(150),
  usuario VARCHAR(150),
  tipo_usuario VARCHAR(50),
PRIMARY KEY (id_regla)
);
--
-- insercion de tm_regla_envio
-- * NOTE: se usa la logica de los datos de unidad seccion usuario y tipo sea
--         un string con los valores de la ids de cada entidad separado por coma

INSERT INTO tm_regla_envio (asunto, unidad, seccion, usuario, tipo_usuario)
VALUES  ('Nuevo Evento', '1,2,4', '1,2,4', '1,2,4', '1,2,4'),
        ('Agregar Permisos',"5", null,null, null),
        ('Evento Cerrado', null, null, '4', '1');

--
-- estructura de tm_permisos
--
CREATE TABLE tm_permisos(
  id_permiso INT  NOT NULL auto_increment,
  permiso VARCHAR(150) UNIQUE,
  unidad VARCHAR(150),
  seccion VARCHAR(150),
  usuario VARCHAR(150),
  tipo_usuario VARCHAR(50),
PRIMARY KEY (id_permiso)
);

INSERT INTO tm_permisos(permiso,unidad,seccion,usuario,tipo_usuario) VALUES
('Crear Nuevo Evento','','3','',''),
('Agregar Permisos','5','','','3'),
('Derivar','5','','','3'),
('Control Unidad','5','','','3'),
('Control Noticia','5','','','3'),
('Control Categoria','5','','','3'),
('Control Motivos','5','','','3'),
('Control password','5','','','3'),
('Control passunidad','5','','','3'),
('Control usuario','5','','','3'),
('Cerrar Evento','1,2,4','1,2,4','4','1'),
('Crear Usuario','1,2,4','1,2,4','1,2,4','1,2,4');
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
  MODIFY `camb_asig_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_categoria`
--
ALTER TABLE `tm_categoria`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tm_estado`
--
ALTER TABLE `tm_estado`
  MODIFY `est_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_est_unidad`
--
ALTER TABLE `tm_est_unidad`
  MODIFY `est_un_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_ev_cierre`
--
ALTER TABLE `tm_ev_cierre`
  MODIFY `id_cierre` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_ev_niv`
--
ALTER TABLE `tm_ev_niv`
  MODIFY `ev_niv_id` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT de la tabla `tm_usu_tipo`
--
ALTER TABLE `tm_usu_tipo`
  MODIFY `usu_tipo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_unidad`
--
ALTER TABLE `tm_unidad`
  MODIFY `unid_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tm_usuario`
--
ALTER TABLE `tm_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
