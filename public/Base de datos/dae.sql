-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2025 a las 22:05:11
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dae`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `RUN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`RUN`) VALUES
('1.111.111-1'),
('10.000.000-0'),
('12345678-9'),
('20.216.163-4'),
('9.999.999-9');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adminmodpers`
--

CREATE TABLE `adminmodpers` (
  `Fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `RUNAdmin` varchar(50) NOT NULL,
  `RUNPersona` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloque`
--

CREATE TABLE `bloque` (
  `FechaInicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `FechaTermino` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ID` int(11) NOT NULL,
  `FechaInicioSemana` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `RUNTS` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloque`
--

INSERT INTO `bloque` (`FechaInicio`, `FechaTermino`, `ID`, `FechaInicioSemana`, `RUNTS`) VALUES
('2024-12-05 15:05:00', '2024-12-05 15:50:00', 60, '2024-12-02 03:00:00', '5.555.555-5'),
('2024-12-13 21:40:00', '2024-12-13 22:25:00', 61, '2024-12-09 03:00:00', '5.555.555-5'),
('2024-12-05 21:40:00', '2024-12-05 22:25:00', 62, '2024-12-02 03:00:00', '5.555.555-5'),
('2024-12-05 15:05:00', '2024-12-05 15:50:00', 63, '2024-12-02 03:00:00', '7.777.777-7'),
('2024-12-06 20:55:00', '2024-12-06 21:40:00', 64, '2024-12-02 03:00:00', '5.555.555-5'),
('2024-12-18 15:05:00', '2024-12-18 15:50:00', 65, '2024-12-16 03:00:00', '5.555.555-5'),
('2024-12-18 11:00:00', '2024-12-18 11:45:00', 66, '2024-12-16 03:00:00', '5.555.555-5'),
('2024-12-18 19:20:00', '2024-12-18 20:05:00', 67, '2024-12-16 03:00:00', '5.555.555-5'),
('2024-12-13 20:05:00', '2024-12-13 20:50:00', 68, '2024-12-09 03:00:00', '5.555.555-5'),
('2024-12-18 17:45:00', '2024-12-18 18:30:00', 71, '2024-12-16 03:00:00', '6.666.666-6'),
('2024-12-16 12:40:00', '2024-12-16 13:25:00', 72, '2024-12-16 03:00:00', '6.666.666-6'),
('2024-12-16 11:00:00', '2024-12-16 11:45:00', 73, '2024-12-16 03:00:00', '6.666.666-6'),
('2025-01-09 15:05:00', '2025-01-09 15:50:00', 80, '2025-01-06 03:00:00', '5.555.555-5'),
('2025-01-17 21:30:00', '2025-01-17 22:00:00', 83, '2025-01-13 03:00:00', '6.666.666-6'),
('2025-01-22 17:30:00', '2025-01-22 18:00:00', 84, '2025-01-20 03:00:00', '6.666.666-6'),
('2025-01-24 11:00:00', '2025-01-24 11:00:00', 91, '2025-01-20 03:00:00', '6.666.666-6'),
('2025-01-23 18:30:00', '2025-01-23 18:30:00', 92, '2025-01-20 03:00:00', '6.666.666-6'),
('2025-02-07 12:00:00', '2025-02-07 12:30:00', 15217, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 21:00:00', '2025-02-07 21:30:00', 83591, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-01-29 11:00:00', '2025-01-29 11:30:00', 178103, '2025-01-27 06:00:00', '4.444.444-4'),
('2025-02-07 20:30:00', '2025-02-07 21:00:00', 223481, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 12:30:00', '2025-02-07 13:00:00', 242569, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 15:00:00', '2025-02-07 15:30:00', 265157, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 18:30:00', '2025-02-07 19:00:00', 268323, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 19:00:00', '2025-02-07 19:30:00', 292814, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-01-29 11:00:00', '2025-01-29 11:30:00', 360287, '2025-01-27 06:00:00', '5.555.555-5'),
('2025-02-07 20:00:00', '2025-02-07 20:30:00', 397239, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 14:00:00', '2025-02-07 14:30:00', 445475, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-01-29 11:00:00', '2025-01-29 11:30:00', 470638, '2025-01-27 06:00:00', '5.555.555-5'),
('2025-01-29 11:00:00', '2025-01-29 11:30:00', 474347, '2025-01-27 06:00:00', '5.555.555-5'),
('2025-02-07 17:30:00', '2025-02-07 18:00:00', 474492, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 19:30:00', '2025-02-07 20:00:00', 560374, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 13:30:00', '2025-02-07 14:00:00', 609473, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-01-29 11:00:00', '2025-01-29 11:30:00', 644028, '2025-01-27 06:00:00', '5.555.555-5'),
('2025-01-29 11:00:00', '2025-01-29 11:30:00', 668542, '2025-01-27 06:00:00', '5.555.555-5'),
('2025-01-29 11:00:00', '2025-01-29 11:30:00', 668548, '2025-01-27 03:00:00', '6.666.666-6'),
('0000-00-00 00:00:00', '2025-01-29 11:00:00', 668549, '2025-01-27 06:51:13', '6.666.666-6'),
('0000-00-00 00:00:00', '2025-01-29 11:00:00', 668550, '2025-01-27 06:51:33', '6.666.666-6'),
('2025-01-29 11:00:00', '2025-01-29 11:30:00', 668551, '2025-01-27 03:00:00', '7.777.777-7'),
('2025-01-27 08:01:13', '2025-01-27 08:01:13', 668552, '2025-01-27 08:01:13', '6.666.666-6'),
('2025-01-30 11:30:00', '2025-01-30 12:00:00', 668559, '2025-01-27 03:00:00', '0.000.000-0'),
('2025-02-07 14:30:00', '2025-02-07 15:00:00', 701591, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 18:00:00', '2025-02-07 18:30:00', 750246, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 11:30:00', '2025-02-07 12:00:00', 764263, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 11:00:00', '2025-02-07 11:30:00', 861146, '2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-07 13:00:00', '2025-02-07 13:30:00', 887457, '2025-02-03 06:00:00', '5.555.555-5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloqueatencion`
--

CREATE TABLE `bloqueatencion` (
  `Estado` enum('Reservado','Atendido','CanceladoTS','CanceladoAdmin','CanceladoCliente') NOT NULL,
  `Motivo` enum('Gratuidad Mineduc','Becas de arancel Mineduc','Fondo Solidario de Crédito Universitario','Beneficios Junaeb (BAES y Becas de mantención)','Beca Fotocopia UTA','Beca Alimentación UTA','Beca Residencia UTA','Beca Internado UTA','Beca Ayuda Estudiantil UTA','Beca PSU-PDT-PAES UTA','Otro') NOT NULL,
  `ID` int(11) NOT NULL,
  `RUNCliente` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloqueatencion`
--

INSERT INTO `bloqueatencion` (`Estado`, `Motivo`, `ID`, `RUNCliente`) VALUES
('Reservado', 'Becas de arancel Mineduc', 60, '20.775.891-4'),
('Reservado', 'Beca PSU-PDT-PAES UTA', 61, '20.775.891-4'),
('Reservado', 'Beca Alimentación UTA', 62, '20820'),
('Reservado', 'Beca Fotocopia UTA', 63, '20820'),
('Reservado', 'Fondo Solidario de Crédito Universitario', 64, '20.216.400-5'),
('Reservado', 'Fondo Solidario de Crédito Universitario', 65, '19.458.684-4'),
('Reservado', 'Beneficios Junaeb (BAES y Becas de mantención)', 66, '20.775.891-4'),
('Reservado', 'Beneficios Junaeb (BAES y Becas de mantención)', 67, '20.775.891-4'),
('Reservado', 'Gratuidad Mineduc', 68, '20.216.400-5'),
('Reservado', 'Beca Residencia UTA', 71, '20.625.856-2'),
('Reservado', 'Fondo Solidario de Crédito Universitario', 72, '20.216.400-5'),
('Reservado', 'Beca Internado UTA', 73, '20.216.400-5'),
('Reservado', 'Beneficios Junaeb (BAES y Becas de mantención)', 80, '20.216.400-5'),
('Reservado', 'Gratuidad Mineduc', 83, '20.216.400-5'),
('Reservado', 'Becas de arancel Mineduc', 84, '20.216.400-5'),
('Reservado', 'Gratuidad Mineduc', 91, '20.216.400-5'),
('Reservado', 'Becas de arancel Mineduc', 92, '20.216.186-4'),
('Reservado', 'Gratuidad Mineduc', 668559, '20.216.186-4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloquebloqueado`
--

CREATE TABLE `bloquebloqueado` (
  `ID` int(11) NOT NULL,
  `fechainicio` datetime NOT NULL,
  `fechafinal` datetime DEFAULT NULL,
  `RUN` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bloquebloqueado`
--

INSERT INTO `bloquebloqueado` (`ID`, `fechainicio`, `fechafinal`, `RUN`) VALUES
(15217, '2025-02-07 09:00:00', '2025-02-07 09:30:00', '5.555.555-5'),
(83591, '2025-02-07 18:00:00', '2025-02-07 18:30:00', '5.555.555-5'),
(223481, '2025-02-07 17:30:00', '2025-02-07 18:00:00', '5.555.555-5'),
(242569, '2025-02-07 09:30:00', '2025-02-07 10:00:00', '5.555.555-5'),
(265157, '2025-02-07 12:00:00', '2025-02-07 12:30:00', '5.555.555-5'),
(268323, '2025-02-07 15:30:00', '2025-02-07 16:00:00', '5.555.555-5'),
(292814, '2025-02-07 16:00:00', '2025-02-07 16:30:00', '5.555.555-5'),
(360287, '2025-01-29 08:00:00', '2025-01-29 08:30:00', '5.555.555-5'),
(397239, '2025-02-07 17:00:00', '2025-02-07 17:30:00', '5.555.555-5'),
(445475, '2025-02-07 11:00:00', '2025-02-07 11:30:00', '5.555.555-5'),
(474492, '2025-02-07 14:30:00', '2025-02-07 15:00:00', '5.555.555-5'),
(560374, '2025-02-07 16:30:00', '2025-02-07 17:00:00', '5.555.555-5'),
(609473, '2025-02-07 10:30:00', '2025-02-07 11:00:00', '5.555.555-5'),
(701591, '2025-02-07 11:30:00', '2025-02-07 12:00:00', '5.555.555-5'),
(750246, '2025-02-07 15:00:00', '2025-02-07 15:30:00', '5.555.555-5'),
(764263, '2025-02-07 08:30:00', '2025-02-07 09:00:00', '5.555.555-5'),
(861146, '2025-02-07 08:00:00', '2025-02-07 08:30:00', '5.555.555-5'),
(887457, '2025-02-07 10:00:00', '2025-02-07 10:30:00', '5.555.555-5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendariosemanal`
--

CREATE TABLE `calendariosemanal` (
  `FechaInicioSemana` timestamp NOT NULL DEFAULT current_timestamp(),
  `RUNTS` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `calendariosemanal`
--

INSERT INTO `calendariosemanal` (`FechaInicioSemana`, `RUNTS`) VALUES
('2024-11-04 03:00:00', '5.555.555-5'),
('2024-11-04 03:00:00', '7.777.777-7'),
('2024-11-25 03:00:00', '5.555.555-5'),
('2024-11-25 03:00:00', '7.777.777-7'),
('2024-12-02 03:00:00', '5.555.555-5'),
('2024-12-02 03:00:00', '7.777.777-7'),
('2024-12-09 03:00:00', '5.555.555-5'),
('2024-12-16 03:00:00', '5.555.555-5'),
('2024-12-16 03:00:00', '6.666.666-6'),
('2025-01-06 03:00:00', '5.555.555-5'),
('2025-01-13 03:00:00', '5.555.555-5'),
('2025-01-13 03:00:00', '6.666.666-6'),
('2025-01-20 03:00:00', '6.666.666-6'),
('2025-01-27 03:00:00', '0.000.000-0'),
('2025-01-27 03:00:00', '6.666.666-6'),
('2025-01-27 03:00:00', '7.777.777-7'),
('2025-01-27 03:00:00', '9.999.999-9'),
('2025-01-27 06:00:00', '4.444.444-4'),
('2025-01-27 06:00:00', '5.555.555-5'),
('2025-01-27 06:51:13', '6.666.666-6'),
('2025-01-27 06:51:33', '6.666.666-6'),
('2025-01-27 08:01:13', '6.666.666-6'),
('2025-02-03 03:00:00', '6.666.666-6'),
('2025-02-03 06:00:00', '5.555.555-5'),
('2025-02-10 03:00:00', '6.666.666-6');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `COD_CARRERA` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Facultad` varchar(100) NOT NULL,
  `RUNTS` varchar(50) NOT NULL,
  `ReemplazaRUNTS` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`COD_CARRERA`, `Nombre`, `Facultad`, `RUNTS`, `ReemplazaRUNTS`) VALUES
(132, 'INGENIERÍA EN ADMINISTRACIÓN DE EMPRESAS', 'FAC. ADM. Y ECONOMIA CARRERAS DE PREGRADO', '1.111.111-1', '6.666.666-6'),
(151, 'INGENIERÍA COMERCIAL', 'FAC. ADM. Y ECONOMIA CARRERAS DE PREGRADO', '2.222.222-2', '3.333.333-3'),
(153, 'CONTADOR AUDITOR - CONTADOR PUBLICO', 'FAC. ADM. Y ECONOMIA CARRERAS DE PREGRADO', '1.111.111-1', '6.666.666-6'),
(154, 'INGENIERÍA EN INFORMACIÓN Y CONTROL DE GESTIÓN', 'FAC. ADM. Y ECONOMIA CARRERAS DE PREGRADO', '7.777.777-7', '6.666.666-6'),
(155, 'ANTROPOLOGÍA', 'FACSOJUR CARRERAS DE PREGRADO', '3.333.333-3', '6.666.666-6'),
(167, 'TRABAJO SOCIAL', 'FACSOJUR CARRERAS DE PREGRADO', '3.333.333-3', '6.666.666-6'),
(169, 'PSICOLOGÍA', 'FACSOJUR CARRERAS DE PREGRADO', '3.333.333-3', '6.666.666-6'),
(170, 'DERECHO', 'FACULTAD DE DERECHO', '2.222.222-2', '6.666.666-6'),
(202, 'PEDAGOGÍA EN FÍSICA Y MATEMÁTICA', 'FACULTAD DE CIENCIAS CARRERAS DE PREGRADO', '9.999.999-9', '7.777.777-7'),
(203, 'PEDAGOGÍA EN MATEMÁTICA Y COMPUTACIÓN', 'FACULTAD DE CIENCIAS CARRERAS DE PREGRADO', '9.999.999-9', '7.777.777-7'),
(213, 'PEDAGOGÍA EN MATEMÁTICAS', 'FACULTAD DE CIENCIAS CARRERAS DE PREGRADO', '9.999.999-9', '7.777.777-7'),
(217, 'PEDAGOGÍA EN BIOLOGÍA Y CIENCIAS NATURALES', 'FACULTAD DE CIENCIAS CARRERAS DE PREGRADO', '9.999.999-9', '7.777.777-7'),
(229, 'INGENIERÍA QUÍMICA AMBIENTAL', 'FACULTAD DE CIENCIAS CARRERAS DE PREGRADO', '9.999.999-9', '7.777.777-7'),
(230, 'QUÍMICO LABORATORISTA', 'FACULTAD DE CIENCIAS CARRERAS DE PREGRADO', '9.999.999-9', '7.777.777-7'),
(235, 'ENFERMERÍA', 'FACSAL CARRERAS DE PREGRADO', '0.000.000-0', '6.666.666-6'),
(236, 'KINESIOLOGÍA Y REHABILITACIÓN', 'FACSAL CARRERAS DE PREGRADO', '0.000.000-0', '6.666.666-6'),
(237, 'NUTRICIÓN Y DIETÉTICA', 'FACSAL CARRERAS DE PREGRADO', '0.000.000-0', '6.666.666-6'),
(241, 'OBSTETRICIA Y PUERICULTURA MENCIÓN EN GESTIÓN Y SALUD FAMILIAR', 'FACSAL CARRERAS DE PREGRADO', '0.000.000-0', '6.666.666-6'),
(242, 'TECNOLOGÍA MED/OFTALMOLOGÍA Y OPTOMETRÍA', 'FACSAL CARRERAS DE PREGRADO', '2.222.222-2', '6.666.666-6'),
(243, 'TECNOL MEDICA  /IMAGEN Y FÍSICA MEDICA', 'FACSAL CARRERAS DE PREGRADO', '2.222.222-2', '6.666.666-6'),
(244, 'TECNOLOGÍA MEDICA /LAB.CLIN.,HEM.YBCO.', 'FACSAL CARRERAS DE PREGRADO', '2.222.222-2', '6.666.666-6'),
(246, 'MEDICINA', 'FACULTAD DE MEDICINA', '9.999.999-9', '7.777.777-7'),
(304, 'PEDAGOGÍA EN CASTELLANO Y COMUNICACIÓN', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(309, 'PEDAGOGÍA EN EDUCACIÓN DIFERENCIAL', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(310, 'PEDAGOGÍA EN EDUCACIÓN BÁSICA ', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(311, 'PEDAGOGÍA EN HISTORIA Y GEOGRAFÍA', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(312, 'PEDAGOGÍA EN INGLES', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(313, 'PEDAGOGÍA EN EDUCACIÓN BÁSICA ', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(325, 'PEDAGOGÍA EN EDUCACIÓN DIFERENCIAL', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(329, 'PROFESOR DE EDUCACIÓN FÍSICA', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(331, 'EDUCACIÓN PARVULARIA ', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(333, 'LICENCIATURA EN INGLES INGRESO COMÚN', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(334, 'LICENCIATURA EN LENGUAJE Y COMUNICACIÓN INGRESO COMÚN', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(335, 'PROFESOR DE EDUCACIÓN FÍSICA', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(336, 'LICENCIATURA EN HISTORIA Y GEOGRAFÍA', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(337, 'EDUCACIÓN PARVULARIA ', 'FACULTAD DE EDUC. Y HUMANIDADES CARRERAS DE PREGRADO', '4.444.444-4', '7.777.777-7'),
(401, 'AGRONOMÍA', 'FACULTAD DE AGRONOMIA CARRERAS DE PREGRADO', '6.666.666-6', '7.777.777-7'),
(527, 'DISEÑO MULTIMEDIA', 'FAC. ADM. Y ECONOMIA CARRERAS DE PREGRADO', '7.777.777-7', '7.777.777-7'),
(530, 'INGENIERÍA CIVIL ELÉCTRICA', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6'),
(531, 'INGENIERÍA CIVIL ELECTRÓNICA', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6'),
(532, 'INGENIERÍA CIVIL INDUSTRIAL', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '5.555.555-5', '7.777.777-7'),
(533, 'INGENIERÍA CIVIL MECÁNICA', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6'),
(534, 'ING.CIVIL COMPUTACIÓN E INFORMÁTICA', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '5.555.555-5', '7.777.777-7'),
(537, 'INGENIERÍA MECATRONICA', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6'),
(544, 'INGENIERÍA DE EJECUCIÓN ELÉCTRICA', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6'),
(545, 'INGENIERÍA DE EJECUCIÓN ELECTRÓNICA', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6'),
(546, 'INGENIERÍA DE EJECUCIÓN MECÁNICA', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6'),
(549, 'INGENIERIA EN CIENCIA DE DATOS', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '1.111.111-1', '6.666.666-6'),
(552, 'ADMINISTRACIÓN PUBLICA', 'FAC. ADM. Y ECONOMIA CARRERAS DE PREGRADO', '1.111.111-1', '6.666.666-6'),
(577, 'INGENIERÍA INDUSTRIAL', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '1.111.111-1', '6.666.666-6'),
(585, 'ING. CIVIL/EJEC. ELECTRI/ELECTRO ING.COM', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6'),
(587, 'ING. CIVIL/EJEC. MECÁNICA  ING. COMÚN', 'FAC.INGENIERIA CARRERAS DE PREGRADO', '8.888.888-8', '6.666.666-6');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `RUN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`RUN`) VALUES
('11.111.111-1'),
('19.458.684-4'),
('20.203.205-4'),
('20.216.186-4'),
('20.216.400-5'),
('20.625.856-2'),
('20.775.891-4'),
('20.820.467-k'),
('20820');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cookie`
--

CREATE TABLE `cookie` (
  `ID` int(11) NOT NULL,
  `Token` varbinary(200) NOT NULL,
  `FechaCreacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `RUN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cookie`
--

INSERT INTO `cookie` (`ID`, `Token`, `FechaCreacion`, `RUN`) VALUES
(9, 0xcb0cbba8bae289b47f52c968d58f051790ac057628ea7d3cf170aabf0fdd1632dedeb566f4399d52fb71ec6eed97d0ad8efbbd96f944be053edde65932bfb306524f1ee8a04617710e416fb70abe3e40daf893b09abe2a54d8a972d28884761fe2637db5, '2024-11-08 19:41:39', '20.820.467-k'),
(198, 0x95ab69e4f6d04efd601cd395d9aa909fd39afd5a1e6bb424e085a9344ff8f6216073ff4de785db6ae2d5a788ccf6d6a6e182c99279577f3451c792057fc807d041f7371d2deb76344fc3a447ed26f69f97327e656f9666200cd398f2145aae6622bdbe77, '2024-12-05 12:33:46', '12345678-9'),
(201, 0xf5f074230ee05060af848a5a4211225632da49dd94d112f8e401185a3de87e77e606a4447c2750bb8edd04794858a31e3bc6cac22b967097c8d58a95eb15b8e90498217c374c81132f2e7bd0f2045bd00a6c14fa150b9a1f50885974651a918ae839a18e, '2024-12-05 12:36:51', '20820'),
(202, 0xb17ec2ba3ff3f35d7b59190bf9c9219eac59408635006ef80172ed1759b1ebb8609db5f9ff1e2a07daea7f59c7f45034b7b447825d6a7f5aed4d2dfcfa7810c604df1382fb2981a1d60c6650bbdad43adfc9b1555fe49b5880056d9f3819c487eb915878, '2024-12-05 12:37:08', '20.775.891-4'),
(203, 0xfac1cb684036200134d74789d102751be6e4bb64a7eb4ae516a90a91d70abede758d8973bb264658d221aac6053daa854bdc7d857616ee7acdf19e6e0ecf26dd398c9a885a460dd473ce21374b99d70ffa862ac45ee296cfab503fefccb70a008bb2518f, '2024-12-05 12:42:15', '12345678-9'),
(210, 0xb49090bcdd303d8d48d875bb4a676c6f667cc31aec13eab290a7c96f1b00faddbd6bc49d2f6da13487a493a09cf63d69e56a6154f75bda521d7c2da4bb413f8d2e8d5c4e3463123273b5c258195a7aac1b14474df6af28e7fad30f9b55de1a91c377a3e2, '2024-12-05 14:01:56', '20.775.891-4'),
(424, 0x566c10b2bd052d77eb59f3ac3b36bc9c03315cad29649023ac7108d2afb4d4edaa5eafb6a95f768e1d45ad02ec72555386ea1b9b5b14169ddb3e53bede292ee5e00b04dd7c2697404e545cb07ea07bb1b76893d336276d9a1c1231d8cc175a535cc74419, '2025-01-27 19:40:21', '20.216.186-4'),
(425, 0x86858f5355d6acbd762d223da3392288465127dbd0e58f223943e54d3f25f7cc2a9e20896f1ed456a2c9ab593a421d8357ce4b29a8f894dbc5266531a4aa4991059c10bb6057ae692a82bfbdb96ef8d8471c92152d382b6f8b6174c9cc1914cd5750747a, '2025-01-27 20:25:55', '20.216.163-4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `RUN` varchar(50) NOT NULL,
  `COD_CARRERA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`RUN`, `COD_CARRERA`) VALUES
('20.625.856-2', 401),
('20.216.400-5', 534),
('20.775.891-4', 534),
('20.820.467-k', 534);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionario`
--

CREATE TABLE `funcionario` (
  `RUN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funcionario`
--

INSERT INTO `funcionario` (`RUN`) VALUES
('0.000.000-0'),
('1.111.111-1'),
('10.000.000-0'),
('12345678-9'),
('2.222.222-2'),
('20.216.163-4'),
('3.333.333-3'),
('4.444.444-4'),
('5.555.555-5'),
('6.666.666-6'),
('7.777.777-7'),
('8.888.888-8'),
('9.999.999-9');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcmodbloq`
--

CREATE TABLE `funcmodbloq` (
  `Fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `RUNFuncionario` varchar(50) NOT NULL,
  `IDBloque` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencia`
--

CREATE TABLE `licencia` (
  `ID_LICENCIA` int(11) NOT NULL,
  `FECHA_INI` date NOT NULL,
  `FECHA_TER` date NOT NULL,
  `RUN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `licencia`
--

INSERT INTO `licencia` (`ID_LICENCIA`, `FECHA_INI`, `FECHA_TER`, `RUN`) VALUES
(6, '2024-11-29', '2024-11-30', '0.000.000-0'),
(7, '2024-12-06', '2024-12-07', '5.555.555-5'),
(8, '2024-12-06', '2024-12-06', '5.555.555-5'),
(9, '2024-12-06', '2024-12-06', '5.555.555-5'),
(10, '2024-12-06', '2024-12-06', '5.555.555-5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noestudiante`
--

CREATE TABLE `noestudiante` (
  `RUN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noestudiante`
--

INSERT INTO `noestudiante` (`RUN`) VALUES
('11.111.111-1'),
('19.458.684-4'),
('20.203.205-4'),
('20.216.186-4'),
('20820');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `RUN` varchar(50) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Telefono` varchar(50) NOT NULL,
  `Correo` varchar(50) NOT NULL,
  `Contraseña` varchar(200) NOT NULL,
  `Activo` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`RUN`, `Nombre`, `Apellido`, `Telefono`, `Correo`, `Contraseña`, `Activo`) VALUES
('0.000.000-0', 'Cintia', 'Vergara Bustos', '58-2205975', 'cvergarab@gestion.uta.cl', '$2y$10$sWfIYjjjnyh6.gYXiEY9oOdmRjFDVdob77Sh.g5B34pNB23GdhmaW', 1),
('1.111.111-1', 'Javiera', 'Araya Vergara', '58-2205577', 'jvarayav@gestion.uta.cl', '$2y$10$q.Fj6h642YeMIuHHwPKHg.zXTJ/b9N5JY/Jypnas/QBVy5mzZlrIi', 1),
('10.000.000-0', 'Víctor', 'Flores Mollo', '999999998', 'vfloresm@gestion.uta.cl', '$2y$10$5G1sCp5/DC8VR66xKbLcD.B9TKcRgWw5d3UYfeIl1tbpIiX6dOnBy', 1),
('11.111.111-1', 'random', 'random2', '1313131313', 'random@gmail.com', '$2y$10$8Y/TZ3NqVONx.b0bVhmTQeM0C4LJsExqDbkRQBt0pHqTPifsqgpaS', 1),
('12345678-9', 'Bastian', 'vega', '123456789', 'pruebadmin@gmail.com', '$2y$10$ecQXkeTH5B6nrxfGjZsaze6750QSGKaQKVqsCL4KmmyQZPb6SrW0W', 1),
('19.458.684-4', 'Scarlet', 'Gavia Mondaca', '56922885544', 'scarletgaviamondaca@gmail.com', '$2y$10$jWuYzhhejBuqGZhoGQtjturT3NIyXyAYtfFkq0sA5kdwrqRIHSoga', 1),
('2.222.222-2', 'Karina', 'Corrales García', '58-2205885', 'kcorrales@gestion.uta.cl', '$2y$10$dbUgYIjB6KXu.gCUcNCcdONjjqVKVcL66z1SFV.m8MKe79yK4J.a6', 1),
('20.203.205-4', 'Lyanna', 'Rios', '+56911111111', 'Lyanna@gmail.com', '$2y$10$TzI/8/saFuyHIs9zjZzrxuuKI22v0DWtUavq08l/.Ry7qA19HuA8K', 1),
('20.216.163-4', 'Gustavo', 'Rios Alvarez', '569-22188746', 'gustavoalexander.ra@gmail.com', '$2b$12$6GL1SdkL.lAetCox8fzrEOzVb7zT/H2oGiJJdbeIakBF4T94RFrx2', 1),
('20.216.186-4', 'Gustavo', 'Rios', '56922789852', 'akikazux1@gmail.com', '$2y$10$KGjEQtRHOVM3CDnxLujc2OOuMqL/gA6ftPIp4b.BVks8Kt5wuHOri', 1),
('20.216.400-5', 'Juan', 'Meneses muñoz', '56922188746', 'juan.meneses.munoz@alumnos.uta.cl', '$2y$10$BcM1uk5rvM4ualpjg2khGObPztM8qm5zqiejErViDQ.vGPIWICEva', 1),
('20.625.856-2', 'Estudiante', 'Prueba', '56898956', 'correo@prueba.cl', '$2y$10$5U.n.etnAbQXX5taCadyJ.blfy0eiAxPItA0QJolr0PgNvmK3N7dO', 1),
('20.775.891-4', 'Bastian', 'Mamani Yucra', '58-999999', 'bastian.mamani.yucra@alumnos.uta.cl', '$2y$10$vbSA2hn4A5umlKCyqN9f/ubrBkrAnF9Y4IURucJQJcsg4v6r4AMle', 1),
('20.820.467-k', 'Rodrigo', 'Torrez', '9999999999', 'rodrigo.torrez.zenis@alumnos.uta.cl', '$2y$10$sbxmwXWZxoD5VCsQUmnpXOcWUuO.1gxP3lj3FGrGPQEGOkHmhSr4e', 1),
('20820', 'mauri', 'torrez', '1234567890', 'mau@gmail.com', '$2y$10$s3vDPYpVH2miO73xS9CM3eWnN.7wVC/4.npuADDwqj01sS2/cEigm', 1),
('3.333.333-3', 'Maria', 'José Bernales Hurtado', '58-2205009', 'mbernales@gestion.uta.cl', '$2y$10$8Bcqad1nnCitJZEzj6T5DuRJ5WoZIAeALpTD.gPpggIZqd2.L8bIa', 1),
('4.444.444-4', 'Pamela', 'Alday Mamani', '58-2205127', 'palday@gestion.uta.cl', '$2y$10$LPOjWa0gl4Io0QtlYfyVceRWywRW2riIKH8EmaQOaU4VciZ14w2Ym', 1),
('5.555.555-5', 'Priscilla', 'Toro Blas', '58-2205910', 'petorob@gestion.uta.cl', '$2y$10$zCSgm5y5VBZJF6I0LjaODulqdIa2wEx2wS52eGThldxNme9ZdF4i2', 1),
('6.666.666-6', 'Nataly', 'Cornejo Araya', '58-2205906', 'ncornejoa@gestion.uta.cl', '$2y$10$MRTnRmgrNJpLw73iJ9PNzeU.5TBeakttXHnzEXtNonPkUy3tWuVeS', 1),
('7.777.777-7', 'Solange', 'Brizuela Soto', '58-2206064', 'ebrizuela@gestion.uta.cl', '$2y$10$glyQof5239fKUAXB2wgagOo9hoDED.xfOOxCkoH2uITJZvOavmUsO', 1),
('8.888.888-8', 'Vannessa', 'Villalobos Castro', '58-2205108', 'vvillalobos@gestion.uta.cl', '$2y$10$DkTN4/T580zT9WjCnopM5.iEVUUxuKDAo7Gm7CHvWzSesASTUN0A.', 1),
('9.999.999-9', 'Yasmín', 'Araya Riveros', '58-2205130', 'yarayar@gestion.uta.cl', '$2y$10$GSXkzWrhP18dM3g8FDLP7un1i.cFG/49UgckDlBK4v/0sUXT2kJIi', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadorsocial`
--

CREATE TABLE `trabajadorsocial` (
  `RUN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajadorsocial`
--

INSERT INTO `trabajadorsocial` (`RUN`) VALUES
('0.000.000-0'),
('1.111.111-1'),
('2.222.222-2'),
('3.333.333-3'),
('4.444.444-4'),
('5.555.555-5'),
('6.666.666-6'),
('7.777.777-7'),
('8.888.888-8'),
('9.999.999-9');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`RUN`);

--
-- Indices de la tabla `adminmodpers`
--
ALTER TABLE `adminmodpers`
  ADD PRIMARY KEY (`RUNAdmin`,`RUNPersona`),
  ADD KEY `RUNPersona` (`RUNPersona`);

--
-- Indices de la tabla `bloque`
--
ALTER TABLE `bloque`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FechaInicioSemana` (`FechaInicioSemana`,`RUNTS`);

--
-- Indices de la tabla `bloqueatencion`
--
ALTER TABLE `bloqueatencion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `RUNCliente` (`RUNCliente`);

--
-- Indices de la tabla `bloquebloqueado`
--
ALTER TABLE `bloquebloqueado`
  ADD PRIMARY KEY (`ID`,`fechainicio`);

--
-- Indices de la tabla `calendariosemanal`
--
ALTER TABLE `calendariosemanal`
  ADD PRIMARY KEY (`FechaInicioSemana`,`RUNTS`),
  ADD KEY `RUNTS` (`RUNTS`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`COD_CARRERA`),
  ADD KEY `RUNTS` (`RUNTS`),
  ADD KEY `ReemplazaRUNTS` (`ReemplazaRUNTS`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`RUN`);

--
-- Indices de la tabla `cookie`
--
ALTER TABLE `cookie`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Token` (`Token`),
  ADD KEY `RUN` (`RUN`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`RUN`),
  ADD KEY `COD_CARRERA` (`COD_CARRERA`);

--
-- Indices de la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`RUN`);

--
-- Indices de la tabla `funcmodbloq`
--
ALTER TABLE `funcmodbloq`
  ADD PRIMARY KEY (`RUNFuncionario`,`IDBloque`),
  ADD KEY `ID` (`IDBloque`);

--
-- Indices de la tabla `licencia`
--
ALTER TABLE `licencia`
  ADD PRIMARY KEY (`ID_LICENCIA`),
  ADD KEY `RUN` (`RUN`);

--
-- Indices de la tabla `noestudiante`
--
ALTER TABLE `noestudiante`
  ADD PRIMARY KEY (`RUN`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`RUN`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- Indices de la tabla `trabajadorsocial`
--
ALTER TABLE `trabajadorsocial`
  ADD PRIMARY KEY (`RUN`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bloque`
--
ALTER TABLE `bloque`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=887458;

--
-- AUTO_INCREMENT de la tabla `cookie`
--
ALTER TABLE `cookie`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT de la tabla `licencia`
--
ALTER TABLE `licencia`
  MODIFY `ID_LICENCIA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`RUN`) REFERENCES `funcionario` (`RUN`);

--
-- Filtros para la tabla `adminmodpers`
--
ALTER TABLE `adminmodpers`
  ADD CONSTRAINT `adminmodpers_ibfk_1` FOREIGN KEY (`RUNAdmin`) REFERENCES `administrador` (`RUN`),
  ADD CONSTRAINT `adminmodpers_ibfk_2` FOREIGN KEY (`RUNPersona`) REFERENCES `persona` (`RUN`);

--
-- Filtros para la tabla `bloque`
--
ALTER TABLE `bloque`
  ADD CONSTRAINT `bloque_ibfk_1` FOREIGN KEY (`FechaInicioSemana`,`RUNTS`) REFERENCES `calendariosemanal` (`FechaInicioSemana`, `RUNTS`);

--
-- Filtros para la tabla `bloqueatencion`
--
ALTER TABLE `bloqueatencion`
  ADD CONSTRAINT `bloqueatencion_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `bloque` (`ID`),
  ADD CONSTRAINT `bloqueatencion_ibfk_2` FOREIGN KEY (`RUNCliente`) REFERENCES `cliente` (`RUN`);

--
-- Filtros para la tabla `calendariosemanal`
--
ALTER TABLE `calendariosemanal`
  ADD CONSTRAINT `calendariosemanal_ibfk_1` FOREIGN KEY (`RUNTS`) REFERENCES `trabajadorsocial` (`RUN`);

--
-- Filtros para la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD CONSTRAINT `carrera_ibfk_1` FOREIGN KEY (`RUNTS`) REFERENCES `trabajadorsocial` (`RUN`),
  ADD CONSTRAINT `carrera_ibfk_2` FOREIGN KEY (`ReemplazaRUNTS`) REFERENCES `trabajadorsocial` (`RUN`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`RUN`) REFERENCES `persona` (`RUN`);

--
-- Filtros para la tabla `cookie`
--
ALTER TABLE `cookie`
  ADD CONSTRAINT `cookie_ibfk_1` FOREIGN KEY (`RUN`) REFERENCES `persona` (`RUN`);

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`RUN`) REFERENCES `cliente` (`RUN`),
  ADD CONSTRAINT `estudiante_ibfk_2` FOREIGN KEY (`COD_CARRERA`) REFERENCES `carrera` (`COD_CARRERA`);

--
-- Filtros para la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`RUN`) REFERENCES `persona` (`RUN`);

--
-- Filtros para la tabla `funcmodbloq`
--
ALTER TABLE `funcmodbloq`
  ADD CONSTRAINT `funcmodbloq_ibfk_1` FOREIGN KEY (`RUNFuncionario`) REFERENCES `funcionario` (`RUN`),
  ADD CONSTRAINT `funcmodbloq_ibfk_2` FOREIGN KEY (`IDBloque`) REFERENCES `bloque` (`ID`);

--
-- Filtros para la tabla `licencia`
--
ALTER TABLE `licencia`
  ADD CONSTRAINT `Licencia_ibfk_1` FOREIGN KEY (`RUN`) REFERENCES `persona` (`RUN`);

--
-- Filtros para la tabla `noestudiante`
--
ALTER TABLE `noestudiante`
  ADD CONSTRAINT `noestudiante_ibfk_1` FOREIGN KEY (`RUN`) REFERENCES `cliente` (`RUN`);

--
-- Filtros para la tabla `trabajadorsocial`
--
ALTER TABLE `trabajadorsocial`
  ADD CONSTRAINT `trabajadorsocial_ibfk_1` FOREIGN KEY (`RUN`) REFERENCES `funcionario` (`RUN`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
