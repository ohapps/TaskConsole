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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`categories`
--

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
LOCK TABLES `categories` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`notes`
--

/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
LOCK TABLES `notes` WRITE;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`priorities`
--

/*!40000 ALTER TABLE `priorities` DISABLE KEYS */;
LOCK TABLES `priorities` WRITE;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`project_categories`
--

/*!40000 ALTER TABLE `project_categories` DISABLE KEYS */;
LOCK TABLES `project_categories` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `project_categories` ENABLE KEYS */;


--
-- Definition of table `taskconsole`.`project_notes`
--

DROP TABLE IF EXISTS `taskconsole`.`project_notes`;
CREATE TABLE  `taskconsole`.`project_notes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROJECT_ID` int(11) NOT NULL,
  `NOTE_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `project_notes_fk01` (`PROJECT_ID`),
  KEY `project_notes_fk02` (`NOTE_ID`),
  CONSTRAINT `project_notes_fk01` FOREIGN KEY (`PROJECT_ID`) REFERENCES `projects` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `project_notes_fk02` FOREIGN KEY (`NOTE_ID`) REFERENCES `notes` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`project_notes`
--

/*!40000 ALTER TABLE `project_notes` DISABLE KEYS */;
LOCK TABLES `project_notes` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `project_notes` ENABLE KEYS */;


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
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`projects`
--

/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
LOCK TABLES `projects` WRITE;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`task_categories`
--

/*!40000 ALTER TABLE `task_categories` DISABLE KEYS */;
LOCK TABLES `task_categories` WRITE;
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
  CONSTRAINT `tasks_fk01` FOREIGN KEY (`PROJECT_ID`) REFERENCES `projects` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `tasks_fk02` FOREIGN KEY (`PRIORITY_ID`) REFERENCES `priorities` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`tasks`
--

/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
LOCK TABLES `tasks` WRITE;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taskconsole`.`topics`
--

/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
LOCK TABLES `topics` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
