-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: ankiety
-- ------------------------------------------------------
-- Server version	5.7.23-0ubuntu0.16.04.1

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
-- Table structure for table `questionnaire_anwers`
--

DROP TABLE IF EXISTS `questionnaire_anwers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire_anwers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questionnaire_id` int(10) unsigned DEFAULT NULL,
  `session_id` text,
  `line_id` int(10) unsigned DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `fk_questionnaire_anwers_questionnaire_idx` (`questionnaire_id`),
  KEY `fk_questionnaire_anwers_1_idx` (`line_id`),
  CONSTRAINT `fk_questionnaire_anwers_1` FOREIGN KEY (`line_id`) REFERENCES `questionnaire_page_lines` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_questionnaire_anwers_questionnaire` FOREIGN KEY (`questionnaire_id`) REFERENCES `questionnaires` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire_anwers`
--

LOCK TABLES `questionnaire_anwers` WRITE;
/*!40000 ALTER TABLE `questionnaire_anwers` DISABLE KEYS */;
INSERT INTO `questionnaire_anwers` VALUES (27,31,'q4ua85e1k05rq6gs3a8pk9d401',3,'Wojtek,'),(28,31,'q4ua85e1k05rq6gs3a8pk9d401',5,'M'),(29,31,'q4ua85e1k05rq6gs3a8pk9d401',6,'11-20'),(30,31,'g5dhjtmnofi3p4r32vtiiv5m17',3,',Wojtek'),(31,31,'g5dhjtmnofi3p4r32vtiiv5m17',5,'M'),(32,31,'g5dhjtmnofi3p4r32vtiiv5m17',6,'21-30'),(33,31,'v0v7pf79tecml4dab0v516trp5',3,',Wojtek'),(34,31,'v0v7pf79tecml4dab0v516trp5',5,'K'),(35,31,'v0v7pf79tecml4dab0v516trp5',6,'11-20'),(36,31,'r1k9p72rpq62hngknr5anteip4',3,'Krzysiek'),(37,31,'r1k9p72rpq62hngknr5anteip4',5,'K'),(38,31,'r1k9p72rpq62hngknr5anteip4',6,'11-20'),(39,31,'sjef46iksbcqujpt0m0q536596',3,'Krzysiek,Wojtek'),(40,31,'sjef46iksbcqujpt0m0q536596',5,'M'),(41,31,'sjef46iksbcqujpt0m0q536596',6,'11-20'),(42,31,'dpvhn6jdj760jaltfg1id6djna',3,'Wojtek'),(43,31,'dpvhn6jdj760jaltfg1id6djna',5,'M'),(44,31,'dpvhn6jdj760jaltfg1id6djna',6,'11-20'),(45,23,'pbovvkt85h8eilnfqfg1morpp9',36,'Jakaś kupa'),(46,23,'dcqsfadpp5fq47o70pbf7729p8',36,'Drugie pole: Checbox,Jakaś kupa');
/*!40000 ALTER TABLE `questionnaire_anwers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaire_page_line_fields`
--

DROP TABLE IF EXISTS `questionnaire_page_line_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire_page_line_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `type` varchar(45) DEFAULT NULL,
  `params` text,
  `line_id` int(11) unsigned DEFAULT NULL,
  `ordering` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_questionnaire_page_line_fields_1_idx` (`line_id`),
  CONSTRAINT `fk_questionnaire_page_line_fields_1` FOREIGN KEY (`line_id`) REFERENCES `questionnaire_page_lines` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire_page_line_fields`
--

LOCK TABLES `questionnaire_page_line_fields` WRITE;
/*!40000 ALTER TABLE `questionnaire_page_line_fields` DISABLE KEYS */;
INSERT INTO `questionnaire_page_line_fields` VALUES (2,'','text','\"\"',3,1),(4,'tt bb 55','text','',9,2),(5,'y mm',NULL,NULL,9,3),(6,'Text asrea','textarea','',9,4),(8,'ww 3',NULL,NULL,9,5),(9,'ffffffffffffff',NULL,NULL,9,6),(10,'789',NULL,NULL,9,7),(11,'xxxx','radio','',10,8),(12,'','','',10,9),(13,'Przedział wiekowy 0 - 10','radio','{\"value\":\"0-10\",\"name\":\"przedzial_wiekowy\"}',6,10),(18,'Męższczyzna','radio','{\"value\":\"M\",\"name\":\"sex\"}',5,11),(19,'Kobieta','radio','{\"value\":\"K\",\"name\":\"sex\"}',5,13),(22,'Kolejne imiona po przecinku','text','\"\"',3,15),(23,'Przedział wiekowy 11 - 20','radio','{\"value\":\"11-20\",\"name\":\"przedzial_wiekowy\"}',6,16),(24,'Przedział wiekowy 21 - 30','radio','{\"value\":\"21-30\",\"name\":\"przedzial_wiekowy\"}',6,17),(25,'bbb','textarea','\"\"',11,0),(26,'aaa','text','\"\"',11,0),(27,'aaa','text','\"\"',11,0),(28,'pierwsze pole: Text','text','\"\"',36,0),(29,'Drugie pole: Checbox','checkbox','\"\"',36,0);
/*!40000 ALTER TABLE `questionnaire_page_line_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaire_page_lines`
--

DROP TABLE IF EXISTS `questionnaire_page_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire_page_lines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `page_id` int(10) unsigned DEFAULT NULL,
  `required` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire_page_lines`
--

LOCK TABLES `questionnaire_page_lines` WRITE;
/*!40000 ALTER TABLE `questionnaire_page_lines` DISABLE KEYS */;
INSERT INTO `questionnaire_page_lines` VALUES (3,'aaa',1,0),(5,'bbb',1,1),(6,'ccc',1,0),(7,'Imię',7,0),(9,'dddf',4,NULL),(10,'',5,NULL),(11,'',8,NULL),(12,'aaa',NULL,0),(13,'bbb',NULL,1),(14,'ccc',NULL,0),(15,'Imię',NULL,0),(16,'xxx',NULL,0),(17,'',8,NULL),(18,'aaa',NULL,0),(19,'bbb',NULL,1),(20,'ccc',NULL,0),(21,'Imię',NULL,0),(22,'nowa linia',NULL,0),(23,'nowa linia 2',NULL,0),(24,'ccccccccccccccccccccccccccccccccccccccc',11,0),(25,'allllo',NULL,0),(26,'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',NULL,0),(27,'vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv',NULL,0),(28,'zxcvb',11,0),(29,'eeeeeeeeeeeeeeeeeeeeee',11,0),(30,'',11,0),(31,'bbbbbbbbbbbbbbb',11,0),(33,'',17,0),(34,'',17,0),(35,'undefined',17,0),(36,'q1',16,0);
/*!40000 ALTER TABLE `questionnaire_page_lines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaire_pages`
--

DROP TABLE IF EXISTS `questionnaire_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaire_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `questionnaire_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `desc_begin` text,
  `desc_end` text,
  PRIMARY KEY (`id`),
  KEY `fk_questionnaire_id_idx` (`questionnaire_id`),
  CONSTRAINT `fk_questionnaire_id` FOREIGN KEY (`questionnaire_id`) REFERENCES `questionnaires` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaire_pages`
--

LOCK TABLES `questionnaire_pages` WRITE;
/*!40000 ALTER TABLE `questionnaire_pages` DISABLE KEYS */;
INSERT INTO `questionnaire_pages` VALUES (1,31,'Rozpoczęcie','jjjjjjjjjjjjj jjjjjjjjjjjjjjjjjjjjjjjjjjj','fffff'),(4,26,'qwer',NULL,NULL),(5,26,'Opis osobowości',NULL,NULL),(7,31,'Piąta strona - podsumowanie',NULL,NULL),(8,31,'Szusta strona',NULL,NULL),(9,31,'Szusta strona','',''),(10,31,'Szusta strona','',''),(11,25,'b','x','bbbbbbbbbbbbb'),(12,25,'aqq','wwww','qqqqqqqqq'),(13,25,'jakaś nazwa strony','aaa','zzz'),(14,25,'Nazwa qqq','Opis początkowy 1','Opis końcow 1'),(15,25,'aaaaaaaaaaaaa','aaaaa','aaaaaaaaaaaaaaa'),(16,23,'aaa','aaa','aaa'),(17,23,'qqq 2','wqqqq 2','cqqqq 2');
/*!40000 ALTER TABLE `questionnaire_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questionnaires`
--

DROP TABLE IF EXISTS `questionnaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questionnaires` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `current` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questionnaires`
--

LOCK TABLES `questionnaires` WRITE;
/*!40000 ALTER TABLE `questionnaires` DISABLE KEYS */;
INSERT INTO `questionnaires` VALUES (10,'rrrrr',0),(11,'ttttttttttttttttt',0),(12,'ttttttttttttttttt',0),(13,'yyyyyyyyyyy',0),(14,'uuuuuuuuuuuuuuuuuuuuuu',0),(15,'Było puste',0),(16,'iiiiiiiiiiiiii ffffff',0),(17,'jjjjjjjjjjjjjjjjjjjj',0),(18,'kkkkk',0),(19,'llllllllllll',0),(20,'llllllllllll - test',0),(21,'zzzzzzzz',0),(22,'zzzzzzzz',0),(23,'xxxx 123',1),(24,'cccccccc lllllllllllllllllllllllllll',0),(25,'cccc',0),(26,'oooooooooooooooooooooooooooooo oooooooooooooooooooooooooooooooooo',0),(31,'Imiona i nazwiska',1);
/*!40000 ALTER TABLE `questionnaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin');
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

-- Dump completed on 2018-08-31 22:35:24
