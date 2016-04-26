-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.1.10-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura de base de datos para argenpersms
CREATE DATABASE IF NOT EXISTS `argenpersms` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `argenpersms`;


-- Volcando estructura para tabla argenpersms.argenper_cron
CREATE TABLE IF NOT EXISTS `argenper_cron` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `proceso` varchar(150) DEFAULT NULL,
  `estatus` char(1) DEFAULT 'E',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.argenper_logcron
CREATE TABLE IF NOT EXISTS `argenper_logcron` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_ref` int(10) DEFAULT NULL,
  `estado_origin` tinyint(4) DEFAULT NULL,
  `estado_fin` tinyint(4) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `response_api` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.argenper_login_logs
CREATE TABLE IF NOT EXISTS `argenper_login_logs` (
  `idlog` int(10) NOT NULL AUTO_INCREMENT,
  `iduser` int(10) NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`idlog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.argenper_processlog
CREATE TABLE IF NOT EXISTS `argenper_processlog` (
  `idlog` int(10) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `q` int(10) DEFAULT NULL,
  `ids` text,
  `ids_done` text,
  `ids_fail` text,
  `iduser` int(10) DEFAULT NULL,
  `estado` char(1) DEFAULT 'E',
  PRIMARY KEY (`idlog`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.argenper_sms
CREATE TABLE IF NOT EXISTS `argenper_sms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_template` int(10) DEFAULT NULL,
  `id_user` int(10) DEFAULT NULL,
  `nombres` varchar(150) DEFAULT NULL,
  `celular` char(15) DEFAULT NULL,
  `mensaje` text,
  `fecha` datetime DEFAULT NULL,
  `id_sms` varchar(30) DEFAULT NULL,
  `estatus_mensaje` tinyint(4) DEFAULT NULL,
  `log` text,
  `tipo` varchar(100) DEFAULT NULL,
  `mensaje_tipo` varchar(10) DEFAULT NULL,
  `id_ref` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.argenper_tblsms
CREATE TABLE IF NOT EXISTS `argenper_tblsms` (
  `id_argenpersms` int(10) NOT NULL AUTO_INCREMENT,
  `id_ref` int(18) NOT NULL,
  `numero_giro` varchar(20) NOT NULL,
  `nombre_cliente` varchar(50) NOT NULL,
  `nombre_oficina` varchar(50) NOT NULL,
  `celular_cliente` varchar(15) NOT NULL,
  `id_sms` varchar(100) DEFAULT NULL,
  `longitud_sms` int(10) DEFAULT NULL,
  `estado_envio` tinyint(4) DEFAULT '0',
  `fecha_ingreso` datetime DEFAULT NULL,
  `fecha_proceso` datetime DEFAULT NULL,
  `fecha_actualizacion_estado` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `num_dia_ee` int(11) DEFAULT NULL,
  `tip_entrega` char(1) DEFAULT NULL,
  `mensaje_cliente` text NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `ciudad_cliente` varchar(50) DEFAULT NULL,
  `respuesta_api` varchar(50) DEFAULT NULL,
  `telefono_oficina` varchar(30) DEFAULT NULL,
  `argenper_estado` char(2) NOT NULL,
  `fecha_giro` datetime DEFAULT NULL,
  `numero_envios` tinyint(4) DEFAULT '0',
  `cron_update` int(10) DEFAULT '0',
  `cron_intentos` int(10) DEFAULT '0',
  `tipo` varchar(10) DEFAULT NULL,
  `mensaje_tipo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_argenpersms`,`id_ref`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.argenper_template
CREATE TABLE IF NOT EXISTS `argenper_template` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `titulo` varchar(120) DEFAULT NULL,
  `sms` text,
  `iduser` int(10) DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT 'E',
  `url` varchar(150) DEFAULT NULL,
  `file` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.argenper_users
CREATE TABLE IF NOT EXISTS `argenper_users` (
  `iduser` int(10) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(60) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `nombre` varchar(60) DEFAULT NULL,
  `apellido` varchar(60) DEFAULT NULL,
  `telefono` varchar(60) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `tipo` char(1) DEFAULT 'A' COMMENT 'A=admin U=usuario',
  `estado` char(1) DEFAULT 'E' COMMENT 'E=enable D=disable',
  `intentos` int(10) DEFAULT '0',
  PRIMARY KEY (`iduser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.contactos
CREATE TABLE IF NOT EXISTS `contactos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_directorio` int(10) NOT NULL DEFAULT '0',
  `nombre` varchar(100) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `valor1` varchar(100) DEFAULT NULL,
  `valor2` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `estatus` char(1) DEFAULT 'E',
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_directorio` (`id_directorio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.directorio
CREATE TABLE IF NOT EXISTS `directorio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fecha_creacion` datetime DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `miembros` int(10) DEFAULT NULL,
  `estatus` char(1) DEFAULT 'E',
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.llamada
CREATE TABLE IF NOT EXISTS `llamada` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `id_grupos` varchar(50) DEFAULT NULL,
  `en_proceso` int(10) DEFAULT '0',
  `errores` int(10) DEFAULT '0',
  `id_template` int(10) NOT NULL DEFAULT '0',
  `correctos` int(10) DEFAULT '0',
  `estatus` char(1) DEFAULT 'E',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla argenpersms.llamada_contactos
CREATE TABLE IF NOT EXISTS `llamada_contactos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_llamada` int(10) NOT NULL,
  `estatus_api` int(10) NOT NULL DEFAULT '1',
  `id_contactos` int(10) NOT NULL,
  `mensaje` text,
  `estatus` char(1) DEFAULT 'E',
  `fecha_ingreso` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `fecha_proceso` datetime DEFAULT NULL,
  `id_referencia` varchar(50) DEFAULT NULL,
  `response_api` varchar(150) DEFAULT NULL,
  `numero_envios` int(11) DEFAULT '0',
  `id_sms` varchar(50) DEFAULT NULL,
  `cron_update` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- La exportación de datos fue deseleccionada.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
