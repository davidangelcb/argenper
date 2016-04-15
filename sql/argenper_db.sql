-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: callvoice
-- ------------------------------------------------------
-- Server version	5.1.73

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `argenper_cron`
--

DROP TABLE IF EXISTS `argenper_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `argenper_cron` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `proceso` varchar(150) DEFAULT NULL,
  `estatus` char(1) DEFAULT 'E',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `argenper_cron`
--

LOCK TABLES `argenper_cron` WRITE;
/*!40000 ALTER TABLE `argenper_cron` DISABLE KEYS */;
/*!40000 ALTER TABLE `argenper_cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `argenper_logcron`
--

DROP TABLE IF EXISTS `argenper_logcron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `argenper_logcron` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_ref` int(10) DEFAULT NULL,
  `estado_origin` tinyint(4) DEFAULT NULL,
  `estado_fin` tinyint(4) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `response_api` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `argenper_logcron`
--

LOCK TABLES `argenper_logcron` WRITE;
/*!40000 ALTER TABLE `argenper_logcron` DISABLE KEYS */;
/*!40000 ALTER TABLE `argenper_logcron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `argenper_login_logs`
--

DROP TABLE IF EXISTS `argenper_login_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `argenper_login_logs` (
  `idlog` int(10) NOT NULL AUTO_INCREMENT,
  `iduser` int(10) NOT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`idlog`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `argenper_login_logs`
--

LOCK TABLES `argenper_login_logs` WRITE;
/*!40000 ALTER TABLE `argenper_login_logs` DISABLE KEYS */;
INSERT INTO `argenper_login_logs` VALUES (46,1,'192.168.1.140','2016-04-04 17:11:38');
/*!40000 ALTER TABLE `argenper_login_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `argenper_processlog`
--

DROP TABLE IF EXISTS `argenper_processlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `argenper_processlog` (
  `idlog` int(10) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `q` int(10) DEFAULT NULL,
  `ids` text,
  `ids_done` text,
  `ids_fail` text,
  `iduser` int(10) DEFAULT NULL,
  `estado` char(1) DEFAULT 'E',
  PRIMARY KEY (`idlog`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `argenper_processlog`
--

LOCK TABLES `argenper_processlog` WRITE;
/*!40000 ALTER TABLE `argenper_processlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `argenper_processlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `argenper_sms`
--

DROP TABLE IF EXISTS `argenper_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `argenper_sms` (
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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `argenper_sms`
--

LOCK TABLES `argenper_sms` WRITE;
/*!40000 ALTER TABLE `argenper_sms` DISABLE KEYS */;
/*!40000 ALTER TABLE `argenper_sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `argenper_tblSMS`
--

DROP TABLE IF EXISTS `argenper_tblSMS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `argenper_tblSMS` (
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
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `argenper_tblSMS`
--

LOCK TABLES `argenper_tblSMS` WRITE;
/*!40000 ALTER TABLE `argenper_tblSMS` DISABLE KEYS */;
/*!40000 ALTER TABLE `argenper_tblSMS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `argenper_template`
--

DROP TABLE IF EXISTS `argenper_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `argenper_template` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `titulo` varchar(120) DEFAULT NULL,
  `sms` text,
  `iduser` int(10) DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `estado` char(1) DEFAULT 'E',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `argenper_template`
--

LOCK TABLES `argenper_template` WRITE;
/*!40000 ALTER TABLE `argenper_template` DISABLE KEYS */;
/*!40000 ALTER TABLE `argenper_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `argenper_users`
--

DROP TABLE IF EXISTS `argenper_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `argenper_users` (
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `argenper_users`
--

LOCK TABLES `argenper_users` WRITE;
/*!40000 ALTER TABLE `argenper_users` DISABLE KEYS */;
INSERT INTO `argenper_users` VALUES (1,'admin','21232f297a57a5a743894a0e4a801fc3','Admin','.','987-897-987','info@mensajesonline.pe','A','E',0);
/*!40000 ALTER TABLE `argenper_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-05 21:54:09
