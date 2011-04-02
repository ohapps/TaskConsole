-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.49-1ubuntu8.1


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema taskconsole
--

CREATE DATABASE IF NOT EXISTS taskconsole;
USE taskconsole;

--
-- Definition of table `taskconsole`.`categories`
--

DROP TABLE IF EXISTS `taskconsole`.`categories`;
CREATE TABLE  `taskconsole`.`categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `DESCRIPTION` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`categories`
--

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
LOCK TABLES `categories` WRITE;
INSERT INTO `taskconsole`.`categories` VALUES  (1,1,'personal'),
 (2,1,'work'),
 (5,1,'to buy'),
 (6,1,'auto repair'),
 (7,1,'home maintenance');
UNLOCK TABLES;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`log`
--

DROP TABLE IF EXISTS `taskconsole`.`log`;
CREATE TABLE  `taskconsole`.`log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LVL` int(11) NOT NULL,
  `MSG` text NOT NULL,
  `LOG_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`log`
--

/*!40000 ALTER TABLE `log` DISABLE KEYS */;
LOCK TABLES `log` WRITE;
INSERT INTO `taskconsole`.`log` VALUES  (1,3,'Invalid controller specified (asdfasd)#0 /var/www/html/TaskConsole/application/library/Zend/Controller/Front.php(954): Zend_Controller_Dispatcher_Standard->dispatch(Object(Zend_Controller_Request_Http), Object(Zend_Controller_Response_Http))\n#1 /var/www/html/TaskConsole/application/library/Zend/Application/Bootstrap/Bootstrap.php(97): Zend_Controller_Front->dispatch()\n#2 /var/www/html/TaskConsole/application/library/Zend/Application.php(366): Zend_Application_Bootstrap_Bootstrap->run()\n#3 /var/www/html/TaskConsole/index.php(31): Zend_Application->run()\n#4 {main}','2011-03-18 00:01:25'),
 (2,3,'Invalid controller specified (asfd)#0 /var/www/html/TaskConsole/application/library/Zend/Controller/Front.php(954): Zend_Controller_Dispatcher_Standard->dispatch(Object(Zend_Controller_Request_Http), Object(Zend_Controller_Response_Http))\n#1 /var/www/html/TaskConsole/application/library/Zend/Application/Bootstrap/Bootstrap.php(97): Zend_Controller_Front->dispatch()\n#2 /var/www/html/TaskConsole/application/library/Zend/Application.php(366): Zend_Application_Bootstrap_Bootstrap->run()\n#3 /var/www/html/TaskConsole/index.php(31): Zend_Application->run()\n#4 {main}','2011-03-24 22:41:41');
UNLOCK TABLES;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`notes`
--

DROP TABLE IF EXISTS `taskconsole`.`notes`;
CREATE TABLE  `taskconsole`.`notes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `TOPIC_ID` int(11) DEFAULT NULL,
  `CONTENTS` text,
  `DESCRIPTION` varchar(200) NOT NULL,
  `LAST_UPDATE` datetime DEFAULT NULL,
  `LAST_VIEWED` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `notes_fk01` (`TOPIC_ID`),
  CONSTRAINT `notes_fk01` FOREIGN KEY (`TOPIC_ID`) REFERENCES `topics` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`notes`
--

/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
LOCK TABLES `notes` WRITE;
INSERT INTO `taskconsole`.`notes` VALUES  (2,1,1,'â€‹test','test','2011-03-20 00:00:00','2011-03-23 00:00:00');
UNLOCK TABLES;
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`priorities`
--

DROP TABLE IF EXISTS `taskconsole`.`priorities`;
CREATE TABLE  `taskconsole`.`priorities` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DESCRIPTION` varchar(25) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`priorities`
--

/*!40000 ALTER TABLE `priorities` DISABLE KEYS */;
LOCK TABLES `priorities` WRITE;
INSERT INTO `taskconsole`.`priorities` VALUES  (1,'high'),
 (2,'normal'),
 (3,'low');
UNLOCK TABLES;
/*!40000 ALTER TABLE `priorities` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`project_categories`
--

DROP TABLE IF EXISTS `taskconsole`.`project_categories`;
CREATE TABLE  `taskconsole`.`project_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CATEGORY_ID` int(11) NOT NULL,
  `PROJECT_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `project_categories_fk01` (`CATEGORY_ID`),
  KEY `project_categories_fk02` (`PROJECT_ID`),
  CONSTRAINT `project_categories_fk01` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `project_categories_fk02` FOREIGN KEY (`PROJECT_ID`) REFERENCES `projects` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`project_categories`
--

/*!40000 ALTER TABLE `project_categories` DISABLE KEYS */;
LOCK TABLES `project_categories` WRITE;
INSERT INTO `taskconsole`.`project_categories` VALUES  (2,1,6),
 (3,1,7),
 (5,2,8),
 (8,1,4),
 (10,1,13);
UNLOCK TABLES;
/*!40000 ALTER TABLE `project_categories` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`projects`
--

DROP TABLE IF EXISTS `taskconsole`.`projects`;
CREATE TABLE  `taskconsole`.`projects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `DESCRIPTION` varchar(100) NOT NULL,
  `COMMENTS` text,
  `COMPLETED` datetime DEFAULT NULL,
  `AUTO_COMPLETE` int(1) DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`projects`
--

/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
LOCK TABLES `projects` WRITE;
INSERT INTO `taskconsole`.`projects` VALUES  (4,1,'update taskconsole to version 2.1','',NULL,1),
 (6,1,'test complete','test complete','2011-03-05 00:00:00',1),
 (7,1,'test project','this is a test','2011-03-08 00:00:00',1),
 (8,1,'asdf','asdf','2011-03-14 00:00:00',1),
 (11,1,'test','',NULL,1),
 (12,1,'test2','',NULL,1),
 (13,1,'test project 1','',NULL,1),
 (14,1,'test','test',NULL,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`task_categories`
--

DROP TABLE IF EXISTS `taskconsole`.`task_categories`;
CREATE TABLE  `taskconsole`.`task_categories` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CATEGORY_ID` int(11) NOT NULL,
  `TASK_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `task_categories_fk01` (`CATEGORY_ID`),
  KEY `task_categories_fk02` (`TASK_ID`),
  CONSTRAINT `task_categories_fk01` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `task_categories_fk02` FOREIGN KEY (`TASK_ID`) REFERENCES `tasks` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`task_categories`
--

/*!40000 ALTER TABLE `task_categories` DISABLE KEYS */;
LOCK TABLES `task_categories` WRITE;
INSERT INTO `taskconsole`.`task_categories` VALUES  (1,1,1),
 (2,1,26),
 (4,2,26),
 (7,1,13),
 (8,1,2),
 (9,2,25),
 (10,1,25),
 (11,2,13),
 (13,1,15),
 (14,1,14),
 (16,1,27),
 (17,1,28),
 (18,2,29),
 (19,2,2),
 (20,1,30),
 (21,7,35);
UNLOCK TABLES;
/*!40000 ALTER TABLE `task_categories` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`tasks`
--

DROP TABLE IF EXISTS `taskconsole`.`tasks`;
CREATE TABLE  `taskconsole`.`tasks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `PROJECT_ID` int(11) DEFAULT NULL,
  `DESCRIPTION` text NOT NULL,
  `PRIORITY_ID` int(11) NOT NULL,
  `DUE_DATE` datetime DEFAULT NULL,
  `COMPLETED` datetime DEFAULT NULL,
  `RECUR_UNIT_TYPE` varchar(25) DEFAULT NULL,
  `RECUR_UNITS` int(11) DEFAULT NULL,
  `DISPLAY_DATE` datetime DEFAULT NULL,
  `ORIG_ID` int(11) DEFAULT NULL,
  `GCAL_ID` varchar(255) DEFAULT NULL,
  `QUEUE_ORDER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `tasks_fk01` (`PROJECT_ID`),
  KEY `tasks_fk02` (`PRIORITY_ID`),
  CONSTRAINT `tasks_fk02` FOREIGN KEY (`PRIORITY_ID`) REFERENCES `priorities` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`tasks`
--

/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
LOCK TABLES `tasks` WRITE;
INSERT INTO `taskconsole`.`tasks` VALUES  (1,1,NULL,'test aaaaa',1,'2011-01-25 00:00:00','2011-01-31 00:00:00','days',30,NULL,NULL,NULL,1),
 (2,1,NULL,'test normal task',2,'2011-02-16 00:00:00','2011-03-14 00:00:00','',0,NULL,NULL,NULL,7),
 (3,1,NULL,'test complete task',1,NULL,'2010-12-15 00:00:00',NULL,NULL,NULL,NULL,NULL,NULL),
 (4,1,NULL,'test pending task',1,NULL,'2011-02-01 00:00:00',NULL,NULL,'2011-01-20 00:00:00',NULL,NULL,NULL),
 (5,1,NULL,'test',2,'2011-02-16 00:00:00','2011-02-02 00:00:00','days',10,NULL,NULL,NULL,4),
 (7,1,NULL,'test',2,'2011-01-19 00:00:00','2011-02-01 00:00:00','days',0,NULL,NULL,NULL,2),
 (13,1,NULL,'insert 2',2,NULL,NULL,'days',0,NULL,NULL,NULL,5),
 (14,1,NULL,'test asfdasdfasfd',2,'2011-01-31 00:00:00','2011-02-02 00:00:00','days',50,NULL,NULL,NULL,6),
 (15,1,NULL,'test 123',2,NULL,'2011-02-02 00:00:00','days',0,NULL,NULL,NULL,NULL),
 (17,1,NULL,'test aaaaa',1,NULL,'2011-01-31 00:00:00','days',30,NULL,1,NULL,NULL),
 (19,1,NULL,'test aaaaa',1,NULL,'2011-01-31 00:00:00','days',30,NULL,1,NULL,NULL),
 (20,1,NULL,'test aaaaa',1,NULL,'2011-01-31 00:00:00','days',30,NULL,17,NULL,NULL),
 (25,1,NULL,'new task',2,NULL,NULL,'days',0,NULL,NULL,NULL,6),
 (26,1,NULL,'high priority',1,NULL,NULL,'days',0,NULL,NULL,NULL,3),
 (27,1,NULL,'test asfdasdfasfd',2,NULL,NULL,'days',50,'2011-03-24 00:00:00',14,NULL,NULL),
 (28,1,NULL,'test1',2,NULL,NULL,'days',0,NULL,NULL,NULL,NULL),
 (29,1,7,'test',2,NULL,'2011-03-08 00:00:00','days',0,NULL,NULL,NULL,NULL),
 (30,1,NULL,'test',2,NULL,'2011-03-14 00:00:00','days',0,NULL,NULL,NULL,NULL),
 (31,1,NULL,'test',2,NULL,'2011-03-14 00:00:00','days',0,NULL,NULL,NULL,NULL),
 (32,1,NULL,'test',2,NULL,'2011-03-14 00:00:00','days',0,NULL,NULL,NULL,NULL),
 (33,1,12,'test',2,NULL,NULL,'days',0,NULL,NULL,NULL,NULL),
 (34,1,13,'test task 1',2,NULL,NULL,'days',0,NULL,NULL,NULL,7),
 (35,1,11,'test',2,NULL,NULL,'days',0,NULL,NULL,NULL,8);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`topics`
--

DROP TABLE IF EXISTS `taskconsole`.`topics`;
CREATE TABLE  `taskconsole`.`topics` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `DESCRIPTION` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`topics`
--

/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
LOCK TABLES `topics` WRITE;
INSERT INTO `taskconsole`.`topics` VALUES  (1,1,'PHP5');
UNLOCK TABLES;
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`users`
--

DROP TABLE IF EXISTS `taskconsole`.`users`;
CREATE TABLE  `taskconsole`.`users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FIRST_NAME` varchar(45) NOT NULL,
  `LAST_NAME` varchar(45) NOT NULL,
  `USERNAME` varchar(45) NOT NULL,
  `PASSWORD` varchar(45) NOT NULL,
  `ACTIVE` int(11) NOT NULL,
  `CREATED` datetime NOT NULL,
  `GDATA_USER` varchar(100) NOT NULL,
  `GDATA_PASS` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
LOCK TABLES `users` WRITE;
INSERT INTO `taskconsole`.`users` VALUES  (1,'Craig','Hausner','housedaddy','bc78c173621fc78a2101ea6a11da2a0f',1,'2008-02-28 00:00:00','1KPSmk/v4N8','HLtI/ysClrmoD50Z0jRf1ujr1QIlNeD');
UNLOCK TABLES;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;