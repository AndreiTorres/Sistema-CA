-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-01-2023 a las 00:31:33
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control_asistencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_becarios`
--

CREATE TABLE `asistencia_becarios` (
  `idasistencia` int(11) NOT NULL,
  `codigo_persona` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `entrada` timestamp NOT NULL DEFAULT current_timestamp(),
  `salida` timestamp NULL DEFAULT NULL,
  `horas` time DEFAULT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_cancilleria`
--

CREATE TABLE `asistencia_cancilleria` (
  `idasistencia` int(11) NOT NULL,
  `codigo_persona` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `entrada` timestamp NOT NULL DEFAULT current_timestamp(),
  `salida` timestamp NULL DEFAULT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('Falta','Retardo','Incidencia','Asistencia') NOT NULL DEFAULT 'Asistencia',
  `anotacion` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_tecno`
--

CREATE TABLE `asistencia_tecno` (
  `idasistencia` int(11) NOT NULL,
  `codigo_persona` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `entrada` timestamp NOT NULL DEFAULT current_timestamp(),
  `salida` timestamp NULL DEFAULT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('Retardo','Incidencia','Asistencia','Reingreso') NOT NULL DEFAULT 'Asistencia',
  `anotacion` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `iddepartamento` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_bin NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_bin NOT NULL,
  `fechacreada` datetime NOT NULL,
  `idusuario` varchar(45) COLLATE utf8_bin NOT NULL,
  `idtipousuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`iddepartamento`, `nombre`, `descripcion`, `fechacreada`, `idusuario`, `idtipousuario`) VALUES
(1, 'Universidad Autónoma de Yucatán', '', '2022-05-14 11:46:25', '1', 0),
(2, 'Escuela Bancaria y Comercial(EBC)', '', '2022-05-14 11:46:25', '1', 0),
(3, 'Instituto Comercial Bancarios', '', '2022-05-14 11:46:25', '1', 0),
(4, 'Universidad Anáhuac', '', '2022-05-14 11:46:25', '1', 0),
(5, 'Universidad del Valle de México', '', '2022-05-14 11:46:25', '1', 0),
(6, 'Universidad interamericana para el desarrollo', '', '2022-05-14 11:46:25', '1', 0),
(7, 'Universidad Modelo', '', '2022-05-14 11:46:25', '1', 0),
(8, 'Universidad Tecnológica Metropolitana', '', '2022-05-14 11:46:25', '1', 0),
(10, 'Escuela Internacional de Chef', '', '2022-06-13 10:27:54', '1', 0),
(11, 'Grupo Tecno', 'Personal de Grupo Tecno', '0000-00-00 00:00:00', '', 0),
(12, 'Academia Internacional de Yucatan', '', '2022-07-25 13:07:01', '15', 0),
(16, 'Director', '', '2022-10-12 19:11:20', '', 7),
(17, 'Subdirector', '', '2022-10-12 19:12:07', '', 7),
(19, 'Jefe de departamento', '', '2022-10-12 19:12:55', '', 7),
(20, 'Operativo', '', '2022-10-12 19:13:37', '', 7),
(21, 'Jurídico', '', '2022-10-12 19:13:56', '15', 7),
(22, 'Limpieza', '', '2022-10-17 17:14:25', '', 7),
(23, 'Visitante', '', '2022-10-17 17:53:10', '', 7),
(25, 'Supervisor', '', '2022-11-01 18:55:08', '', 8),
(26, 'Operativo', '', '2022-11-01 18:55:24', '', 8),
(27, 'Universidad Patria', '', '2022-11-09 08:39:55', '15', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `idhorario` int(11) NOT NULL,
  `codigo_persona` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `Monday` time DEFAULT NULL,
  `Tuesday` time DEFAULT NULL,
  `Wednesday` time DEFAULT NULL,
  `Thursday` time DEFAULT NULL,
  `Friday` time DEFAULT NULL,
  `Saturday` time DEFAULT NULL,
  `Sunday` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `idmensaje` int(11) NOT NULL,
  `idusuariomensaje` int(11) NOT NULL,
  `textomensaje` text COLLATE utf8_bin NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechamensaje` datetime NOT NULL,
  `fechacreada` datetime NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuario`
--

CREATE TABLE `tipousuario` (
  `idtipousuario` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_bin NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_bin NOT NULL,
  `fechacreada` datetime NOT NULL,
  `idusuario` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `tipousuario`
--

INSERT INTO `tipousuario` (`idtipousuario`, `nombre`, `descripcion`, `fechacreada`, `idusuario`) VALUES
(1, 'Administrador', 'Con priviliegios de gestionar todo el sistema', '2022-05-14 11:46:25', '1'),
(2, 'Practicante', 'Practicas profesionales', '2022-05-14 11:46:25', 'admin'),
(3, 'Servicio social', 'Personal de servicio social', '2022-05-14 11:46:25', 'admin'),
(4, 'Administrador Tecno', 'Supervisor', '0000-00-00 00:00:00', 'admin'),
(5, 'Visitante', '', '2022-08-01 13:29:33', '15'),
(6, 'Administrador Cancillería', 'Privilegios para gestionar cancilleria', '2022-09-30 16:58:14', ''),
(7, 'Cancillería', 'Personal de cancillería', '2022-09-30 17:02:28', ''),
(8, 'Tecno', 'Operativo', '2022-10-26 18:59:59', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `token` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tokens`
--

INSERT INTO `tokens` (`token`, `fecha`) VALUES
('4f32044a655f32e8528edea64dbfd11cba810b8790e6e6e23d28ad3a75980734', '2022-11-04 15:01:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `idturno` int(11) NOT NULL,
  `codigo_persona` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `turno` enum('Matutino','Vespertino') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`idturno`, `codigo_persona`, `turno`) VALUES
(1, 'nLr9YJ', 'Vespertino'),
(2, 'MIUNCt', 'Matutino'),
(3, 'VTTnaf', 'Matutino'),
(4, '1010', 'Matutino'),
(5, 'Gdaak3', 'Matutino'),
(6, 'PpHL42', 'Matutino'),
(7, 'bUYrnr', 'Matutino'),
(8, 'qqTcUe', 'Matutino'),
(9, 'yea3K8', 'Matutino'),
(10, 'nEpNQr', 'Matutino'),
(11, 'IAWLrI', 'Matutino'),
(12, 'z8Ze79', 'Matutino'),
(13, 'Bmf38r', 'Matutino'),
(14, 'MrpfAb', 'Matutino'),
(15, 'wPK3eQ', 'Matutino'),
(16, '7ErLRr', 'Matutino'),
(17, '3x2k3h', 'Matutino'),
(18, 'pbr7H3', 'Matutino'),
(19, 'KGrKed', 'Matutino'),
(20, 'cgJife', 'Matutino'),
(21, 'Bq7pmY', 'Matutino'),
(22, '2QCZ2D', 'Matutino'),
(24, 'cpaVph', 'Matutino'),
(26, 'eevKF9', 'Matutino'),
(28, 'LcNxZX', 'Vespertino'),
(29, 'qGVgxT', 'Vespertino'),
(31, 'TwFYp9', 'Vespertino'),
(32, 'TCqIgi', 'Vespertino'),
(33, 'Z4aHWW', 'Vespertino'),
(34, 'qJg7NM', 'Vespertino'),
(35, 'CFpx3C', 'Vespertino'),
(36, '4LIEQ7', 'Vespertino'),
(37, 'zRLft2', 'Vespertino'),
(38, 'wqX2Zv', 'Vespertino'),
(39, 'rjP7dM', 'Vespertino'),
(40, 'Vafjz4', 'Vespertino'),
(41, 'BBQeUU', 'Vespertino'),
(42, 'AhbE8y', 'Vespertino'),
(43, 'hWEwfr', 'Vespertino'),
(44, 'TZNuII', 'Vespertino'),
(45, 'KkyqE7', 'Vespertino'),
(46, 'HegNmu', 'Vespertino'),
(47, 'RbNA4R', 'Vespertino'),
(48, 'BY9hhX', 'Vespertino'),
(49, 'RfHzmF', 'Vespertino'),
(50, '1234', 'Vespertino'),
(51, 'CBjthv', 'Matutino'),
(52, 'bLwXVf', 'Vespertino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_bin NOT NULL,
  `apellidos` varchar(45) COLLATE utf8_bin NOT NULL,
  `login` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  `iddepartamento` int(11) NOT NULL,
  `idtipousuario` int(11) NOT NULL,
  `email` varchar(45) COLLATE utf8_bin NOT NULL,
  `password` varchar(64) COLLATE utf8_bin NOT NULL,
  `imagen` varchar(50) COLLATE utf8_bin NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechacreado` datetime NOT NULL,
  `usuariocreado` varchar(45) COLLATE utf8_bin NOT NULL,
  `codigo_persona` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `idmensaje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nombre`, `apellidos`, `login`, `iddepartamento`, `idtipousuario`, `email`, `password`, `imagen`, `estado`, `fechacreado`, `usuariocreado`, `codigo_persona`, `idmensaje`) VALUES
(1, 'admin', '', 'admin', 16, 1, '', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '', 1, '2023-01-23 00:28:34', '', NULL, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia_becarios`
--
ALTER TABLE `asistencia_becarios`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `codigo_persona` (`codigo_persona`);

--
-- Indices de la tabla `asistencia_cancilleria`
--
ALTER TABLE `asistencia_cancilleria`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `codigo_persona` (`codigo_persona`);

--
-- Indices de la tabla `asistencia_tecno`
--
ALTER TABLE `asistencia_tecno`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `codigo_persona` (`codigo_persona`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`iddepartamento`),
  ADD KEY `idtipousuario` (`idtipousuario`),
  ADD KEY `idtipousuario_2` (`idtipousuario`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`idhorario`),
  ADD KEY `codigo_persona` (`codigo_persona`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`idmensaje`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  ADD PRIMARY KEY (`idtipousuario`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`idturno`),
  ADD KEY `codigo_persona` (`codigo_persona`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `codigo_persona` (`codigo_persona`),
  ADD KEY `fk_departamento` (`iddepartamento`),
  ADD KEY `fk_tiposusario` (`idtipousuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia_becarios`
--
ALTER TABLE `asistencia_becarios`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia_cancilleria`
--
ALTER TABLE `asistencia_cancilleria`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia_tecno`
--
ALTER TABLE `asistencia_tecno`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `iddepartamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `idhorario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `idmensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  MODIFY `idtipousuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `idturno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia_becarios`
--
ALTER TABLE `asistencia_becarios`
  ADD CONSTRAINT `asistencia_becarios_ibfk_1` FOREIGN KEY (`codigo_persona`) REFERENCES `usuarios` (`codigo_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistencia_cancilleria`
--
ALTER TABLE `asistencia_cancilleria`
  ADD CONSTRAINT `asistencia_cancilleria_ibfk_1` FOREIGN KEY (`codigo_persona`) REFERENCES `usuarios` (`codigo_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistencia_tecno`
--
ALTER TABLE `asistencia_tecno`
  ADD CONSTRAINT `asistencia_tecno_ibfk_1` FOREIGN KEY (`codigo_persona`) REFERENCES `usuarios` (`codigo_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `horario_ibfk_1` FOREIGN KEY (`codigo_persona`) REFERENCES `usuarios` (`codigo_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`idtipousuario`) REFERENCES `tipousuario` (`idtipousuario`),
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`iddepartamento`) REFERENCES `departamento` (`iddepartamento`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
