-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: bsmma
-- ------------------------------------------------------
-- Server version	5.5.46-0ubuntu0.14.04.2

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
-- Table structure for table `bogo_promos_to_users`
--

DROP TABLE IF EXISTS `bogo_promos_to_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bogo_promos_to_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bogo_promo_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `entered_code_on` datetime DEFAULT NULL,
  `entered_paid_contest_on` datetime DEFAULT NULL,
  `entered_free_contest_on` datetime DEFAULT NULL,
  `is_complete` tinyint(1) DEFAULT '0',
  `completed_paid_contest_on` datetime DEFAULT NULL,
  `paid_contest_id` int(10) unsigned DEFAULT NULL,
  `free_contest_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `paid_contest_entry_fee` int(3) DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bogo_promos_to_users`
--

LOCK TABLES `bogo_promos_to_users` WRITE;
/*!40000 ALTER TABLE `bogo_promos_to_users` DISABLE KEYS */;
INSERT INTO `bogo_promos_to_users` VALUES (1,1,22,'2017-04-04 21:55:27','2017-04-04 22:07:52','2017-04-04 22:11:59',0,'2017-04-04 22:08:27',44,47,'2017-04-04 21:55:27','2017-04-04 22:11:59',1);
/*!40000 ALTER TABLE `bogo_promos_to_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-04 18:19:17
