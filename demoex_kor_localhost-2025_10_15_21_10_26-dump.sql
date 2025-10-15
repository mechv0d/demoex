-- MySQL dump 10.13  Distrib 8.0.39, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: demoex_kor
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `edu_form`
--

DROP TABLE IF EXISTS `edu_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edu_form` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_name` tinytext NOT NULL,
  `user_feedback` text,
  `target_date` date NOT NULL,
  `pay_type` int NOT NULL DEFAULT '0' COMMENT '0 - cash, 1 - SBP',
  `view_status` int DEFAULT NULL COMMENT '0 - new, 1 - education is going, 2 - edu ended',
  `user_fk` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `edu_form_user_id_fk` (`user_fk`),
  CONSTRAINT `edu_form_user_id_fk` FOREIGN KEY (`user_fk`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edu_form`
--

LOCK TABLES `edu_form` WRITE;
/*!40000 ALTER TABLE `edu_form` DISABLE KEYS */;
INSERT INTO `edu_form` VALUES (1,'абоба пик',NULL,'2025-10-16',1,0,1),(3,'latinka form',NULL,'2025-05-15',1,1,5),(4,'ogo','гг тима раков я еб##','2024-05-10',0,2,5);
/*!40000 ALTER TABLE `edu_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` char(32) NOT NULL COMMENT '6 symbols or more',
  `passwd` tinytext NOT NULL,
  `fio` tinytext NOT NULL,
  `phonenum` char(10) NOT NULL,
  `email` tinytext NOT NULL,
  `admin_right` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Admin','KorokNET','Big Boss','9339337685','diamond.dogs@gmail.com',1),(3,'abcdef','477-6ZX-6Wr-92Z','123 123 123','1231231233','farmoff12@gmail.com',0),(5,'Latinka','KorokNET','123 123 123','9339334964','sergeireim06@yandex.ru',0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-15 21:10:26
