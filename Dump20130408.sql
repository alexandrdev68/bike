CREATE DATABASE  IF NOT EXISTS `bike` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `bike`;
-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bike
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.04.2

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
-- Table structure for table `bikes`
--

DROP TABLE IF EXISTS `bikes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bikes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(45) DEFAULT '',
  `store_id` int(11) NOT NULL,
  `properties` text,
  `foto` varchar(128) DEFAULT '',
  `serial_id` varchar(128) DEFAULT '',
  `on_rent` varchar(45) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bikes`
--

LOCK TABLES `bikes` WRITE;
/*!40000 ALTER TABLE `bikes` DISABLE KEYS */;
INSERT INTO `bikes` VALUES (1,'Comanche Niagara Cross',1,NULL,'bike_1.jpg','G100110736','no'),(2,'Comanche Rio Grande',2,NULL,'bike_2.jpg','G110208188','no'),(3,'Magelan Hidra',3,NULL,'bike_3.jpg','B50531','no'),(5,'Lieder Fox 24\"',2,NULL,'bike_5.jpg','H06B0830','no'),(7,'Comanche Rio Grande L  (зеленый)',1,NULL,'bike_7.jpg','G120108100','no'),(8,'Comanche Holidey',3,NULL,'bike_8.jpg','G110208307','no'),(10,'Winner',1,NULL,'bike_10.jpg','OM61003779','no');
/*!40000 ALTER TABLE `bikes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rent`
--

DROP TABLE IF EXISTS `rent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bike_id` int(11) NOT NULL,
  `klient_id` int(11) NOT NULL,
  `time_start` int(11) NOT NULL,
  `time_end` int(11) DEFAULT '0',
  `project_time` int(11) NOT NULL DEFAULT '0',
  `amount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rent`
--

LOCK TABLES `rent` WRITE;
/*!40000 ALTER TABLE `rent` DISABLE KEYS */;
INSERT INTO `rent` VALUES (35,2,8,1365401099,1365401879,7200,2000),(36,3,13,1365401141,1365401867,10800,2000),(37,7,9,1365401482,1365401855,7200,2000);
/*!40000 ALTER TABLE `rent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adress` varchar(128) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store`
--

LOCK TABLES `store` WRITE;
/*!40000 ALTER TABLE `store` DISABLE KEYS */;
INSERT INTO `store` VALUES (1,'вул. Мічуріна 4'),(2,'вул. Хмельницьке шосе 123'),(3,'Лісопарк');
/*!40000 ALTER TABLE `store` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT '',
  `patronymic` varchar(45) DEFAULT '',
  `surname` varchar(45) DEFAULT '',
  `login` varchar(25) DEFAULT '',
  `password` varchar(512) DEFAULT NULL,
  `photo` varchar(512) DEFAULT '',
  `user_level` int(1) unsigned DEFAULT '3' COMMENT '552071- admin\\\\n1 - reception\\\\n2- user\\n3-klient',
  `properties` text,
  `email` varchar(45) DEFAULT '',
  `phone` varchar(45) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Олександр','Борисов','Володимирович','admin','$6$rounds=5000$nCehqmh/mLewAubm$mLEyCATDP4bLNH9aWIn7u7.eQlMd/4fOcUKxDnUFliSynPRXPtQXWvlvkUJeg8Ogk/9IvSWlG.LHEvJVZHXnx0','',552071,NULL,'',''),(4,'Петр','Петров','Петрович','Petro','$6$rounds=5000$cwmFBbwmVJHKEsiR$gki38tHxzfAhRGXhjhGXEpPjY/m0MRTRDvJEcyh3bTdTVbR7ojZOaBeL3479OCwFLbZ8VT7ocEXoiFsk98lq50','',2,NULL,'',''),(7,'','','','john',NULL,'klient_john.jpg',4,NULL,'','380998899999'),(8,'','','','tiffani',NULL,'klient_tiffani.jpg',4,NULL,'','304456745634'),(9,'','','','grant',NULL,'klient_grant.jpg',4,NULL,'','043289098984'),(10,'','','','hook',NULL,'klient_hook.jpg',4,NULL,'','066789873443'),(11,'olexandr','','','sasha','$6$rounds=5000$rKFAoLYdxYDZCIXU$6cj77AZOJ3jdhNtwY38Po9dr46.L3gDmpeYJLenzSjrJmV6kyJDhnESLylA1sc9VVjCB1rh64aSYYUEFVVSKU0','',1,'{\"store\":\"2\"}','',''),(12,'','','','george',NULL,'klient_george.jpg',4,NULL,'','380957896655'),(13,'','','','edward',NULL,'klient_edward.jpg',4,NULL,'','380447875566'),(14,'','','','gregory',NULL,'klient_gregory.jpg',4,NULL,'','380789557744');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-08  9:19:28
