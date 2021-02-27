-- MySQL dump 10.13  Distrib 5.7.31, for Linux (x86_64)
--
-- Host: localhost    Database: mtarsa_board_games
-- ------------------------------------------------------
-- Server version	5.7.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `communication`
--

DROP TABLE IF EXISTS `communication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `communication` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(50) NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `message` text NOT NULL,
  `unread` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`),
  CONSTRAINT `communication_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `user_details` (`email`),
  CONSTRAINT `communication_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `user_details` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `communication`
--

LOCK TABLES `communication` WRITE;
/*!40000 ALTER TABLE `communication` DISABLE KEYS */;
INSERT INTO `communication` (`id`, `sender`, `receiver`, `timestamp`, `message`, `unread`) VALUES (1,'mike_a@mail.com','stephen_d@mail.com','2020-05-03 21:10:56','Hi Mike, I would like to borrow Splendor if possible?',0),(2,'stephen_d@mail.com','mike_a@mail.com','2020-05-03 21:11:22','Sure, can I borrow something as well?',0),(3,'stephen_d@mail.com','mike_a@mail.com','2020-05-03 21:12:10','Splendor sounds good.',0),(4,'mike_a@mail.com','stephen_d@mail.com','2020-05-03 21:12:31','Sure, shall we meet in the park in an hour?',0),(5,'stephen_d@mail.com','mike_a@mail.com','2020-05-03 21:12:57','Sounds good. No deposit, just exchange?',0),(6,'mike_a@mail.com','stephen_d@mail.com','2020-05-03 21:13:22','Deal. See you later!',0),(29,'mike_a@mail.com','peter_b@mail.com','2020-06-03 20:32:00','Hi Peter, let\'s talk about games.',0),(30,'peter_b@mail.com','mike_a@mail.com','2020-06-03 20:32:51','Ok, my favourite is Carcassone!',0),(31,'mike_a@mail.com','stephen_d@mail.com','2020-07-13 21:09:57','Hi, I just requested Jaipur starting from 2020-07-13. If you are happy for me to borrow it, please accept it and we can agree day and time to meet.',1),(33,'mike_a@mail.com','stephen_d@mail.com','2020-07-13 21:32:58','Blue Blue Blue',1),(34,'mike_a@mail.com','stephen_d@mail.com','2020-07-13 21:33:46','Blue blue blue',1),(35,'mike_a@mail.com','stephen_d@mail.com','2020-08-07 22:01:24','Hi, I just requested Jaipur starting from 2020-08-14. If you are happy for me to borrow it, please accept it and we can agree day and time to meet.',1),(36,'mike_a@mail.com','peter_b@mail.com','2020-08-07 22:01:47','Hiiii',1);
/*!40000 ALTER TABLE `communication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `feedbacker` varchar(50) NOT NULL,
  `feedbackee` varchar(50) NOT NULL,
  `loan` bigint(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stars` tinyint(4) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `feedbacker` (`feedbacker`),
  KEY `feedbackee` (`feedbackee`),
  KEY `loan` (`loan`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`feedbacker`) REFERENCES `user_details` (`email`),
  CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`feedbackee`) REFERENCES `user_details` (`email`),
  CONSTRAINT `feedback_ibfk_3` FOREIGN KEY (`loan`) REFERENCES `loan` (`loan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` (`id`, `feedbacker`, `feedbackee`, `loan`, `timestamp`, `stars`, `details`) VALUES (1,'mike_a@mail.com','stephen_d@mail.com',1,'2020-05-03 21:25:08',5,'Good exchange, no problems.'),(2,'stephen_d@mail.com','mike_a@mail.com',2,'2020-05-03 21:26:08',5,'Game returned in good condition, I would be happy to lend this game again.');
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game` (
  `game_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text,
  `value` decimal(10,2) NOT NULL,
  `email` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`game_id`),
  UNIQUE KEY `game_id` (`game_id`),
  KEY `email` (`email`),
  KEY `status` (`status`),
  CONSTRAINT `game_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user_details` (`email`),
  CONSTRAINT `game_ibfk_2` FOREIGN KEY (`status`) REFERENCES `game_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
INSERT INTO `game` (`game_id`, `name`, `description`, `value`, `email`, `status`) VALUES (1,'Tickets to Ride','A fun game for the whole family',20.00,'mike_a@mail.com','hidden'),(2,'Carcassone','Build a castle!',15.00,'mike_a@mail.com','visible'),(4,'Monopoly','A classic',10.00,'peter_b@mail.com','visible'),(5,'Uno','A quick card game',5.00,'peter_b@mail.com','visible'),(6,'Riverboat','Explore the rivers',25.00,'john_c@mail.com','visible'),(7,'Quacks of Quedlinburg','Become a magician',20.00,'john_c@mail.com','visible'),(8,'Splendor','Havent played yet',25.00,'stephen_d@mail.com','visible'),(9,'Jaipur','Jinn',15.00,'stephen_d@mail.com','visible'),(10,'Pandemics','Control the pandemic!',20.00,'stephen_d@mail.com','visible'),(36,'Blokus','A super fun game for the whole family',10.00,'mike_a@mail.com','visible'),(38,'Tickets to Ride: London','London version of Tickets to Ride',10.00,'mike_a@mail.com','visible');
/*!40000 ALTER TABLE `game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_status`
--

DROP TABLE IF EXISTS `game_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_status` (
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_status`
--

LOCK TABLES `game_status` WRITE;
/*!40000 ALTER TABLE `game_status` DISABLE KEYS */;
INSERT INTO `game_status` (`status`) VALUES ('hidden'),('visible');
/*!40000 ALTER TABLE `game_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan`
--

DROP TABLE IF EXISTS `loan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan` (
  `loan_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `game_id` bigint(20) NOT NULL,
  `borrower` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `duration` text NOT NULL,
  `deposit` tinyint(4) NOT NULL DEFAULT '1',
  `deposit_details` text NOT NULL,
  `comments` text NOT NULL,
  `end_date` date DEFAULT NULL,
  `deposit_return` text NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`loan_id`),
  UNIQUE KEY `loan_id` (`loan_id`),
  KEY `game_id` (`game_id`),
  KEY `borrower` (`borrower`),
  KEY `status` (`status`),
  CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`game_id`),
  CONSTRAINT `loan_ibfk_2` FOREIGN KEY (`borrower`) REFERENCES `user_details` (`email`),
  CONSTRAINT `loan_ibfk_3` FOREIGN KEY (`status`) REFERENCES `loan_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan`
--

LOCK TABLES `loan` WRITE;
/*!40000 ALTER TABLE `loan` DISABLE KEYS */;
INSERT INTO `loan` (`loan_id`, `game_id`, `borrower`, `start_date`, `duration`, `deposit`, `deposit_details`, `comments`, `end_date`, `deposit_return`, `status`) VALUES (1,1,'stephen_d@mail.com','2020-04-04','2 weeks',1,'cash handed over','game swam','2020-04-22','cash returned','returned'),(2,9,'mike_a@mail.com','2020-04-04','2 weeks',1,'cash handed over','game swap','2020-04-22','cash returned','returned'),(3,2,'stephen_d@mail.com','2020-05-02','2 weeks',0,'','game swap','2020-05-03','','returned'),(4,8,'mike_a@mail.com','2020-05-02','2 weeks',0,'','game swap',NULL,'','on_loan'),(5,9,'mike_a@mail.com','2020-07-13','2 weeks',1,'','bla',NULL,'','requested'),(6,9,'mike_a@mail.com','2020-08-14','2 weeks',1,'','Please',NULL,'','requested');
/*!40000 ALTER TABLE `loan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_status`
--

DROP TABLE IF EXISTS `loan_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_status` (
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_status`
--

LOCK TABLES `loan_status` WRITE;
/*!40000 ALTER TABLE `loan_status` DISABLE KEYS */;
INSERT INTO `loan_status` (`status`) VALUES ('on_loan'),('rejected'),('requested'),('returned');
/*!40000 ALTER TABLE `loan_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_details`
--

DROP TABLE IF EXISTS `user_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details` (
  `email` varchar(50) NOT NULL,
  `name` text NOT NULL,
  `address1` text NOT NULL,
  `address2` text,
  `town` text NOT NULL,
  `postcode` text NOT NULL,
  PRIMARY KEY (`email`),
  CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user_login` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_details`
--

LOCK TABLES `user_details` WRITE;
/*!40000 ALTER TABLE `user_details` DISABLE KEYS */;
INSERT INTO `user_details` (`email`, `name`, `address1`, `address2`, `town`, `postcode`) VALUES ('john_c@mail.com','John C','13 Orsett Terrace','','London','W2 6AJ'),('mike_a@mail.com','Mike Vende','200 Carat House','Ursula Gould Way','Lodnon','E14 7FZ'),('peter_b@mail.com','Peter B','130 Lexington Building','Bow Quarter','London','E3 2UE'),('stephen_d@mail.com','Stephen D','301 Riemann Court','44 Bow Common Lane','London','E3 4FU');
/*!40000 ALTER TABLE `user_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_login`
--

DROP TABLE IF EXISTS `user_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_login` (
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_login`
--

LOCK TABLES `user_login` WRITE;
/*!40000 ALTER TABLE `user_login` DISABLE KEYS */;
INSERT INTO `user_login` (`email`, `password`) VALUES ('john_c@mail.com','$2y$10$Hfp.L/WaBEClznVJWNOiBuOy3mZRiYvsV5B4Ts.7FCR393W0BkTA6'),('mike_a@mail.com','$2y$10$UcV.OmxQFR6iKKoJOeamqulZROvpmXLt6ZUlLRTTpd8AfO2LTvh4e'),('peter_b@mail.com','$2y$10$4gC9Ra443dArEdfLaj47MOKr/EpbOol9rEsPFVRcW4EzL37M13mRq'),('stephen_d@mail.com','$2y$10$ku6nd4NpaKAGCDpDJHJw/.T7gD65.VfP4pc1W51wO.v3BDxVa/Pg.');
/*!40000 ALTER TABLE `user_login` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'mtarsa_board_games'
--

--
-- Dumping routines for database 'mtarsa_board_games'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-21  9:27:34
