-- MySQL dump 10.9
--
-- Host: localhost    Database: isd
-- West Point IS450, 2007
-- ------------------------------------------------------
-- Server version	4.1.8-nt
--
-- Fixes applied: corrected table names to match PHP code, added missing
-- cadetTable and instructorTable, added role column to personTable,
-- fixed syntax errors in original dump.

CREATE DATABASE IF NOT EXISTS `isd`;
USE `isd`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;

--
-- Table structure for table `departmentTable`
--

DROP TABLE IF EXISTS `departmentTable`;
CREATE TABLE `departmentTable` (
  `department` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `departmentTable` DISABLE KEYS */;
LOCK TABLES `departmentTable` WRITE;
INSERT INTO `departmentTable` VALUES ('BTD'),('DFL'),('DPE'),('EECS'),('USCC');
UNLOCK TABLES;
/*!40000 ALTER TABLE `departmentTable` ENABLE KEYS */;

--
-- Table structure for table `personTable`
-- (role column added to support determineRole() factory method)
--

DROP TABLE IF EXISTS `personTable`;
CREATE TABLE `personTable` (
  `userID` varchar(6) NOT NULL default '',
  `lastName` varchar(30) NOT NULL default '',
  `firstName` varchar(30) NOT NULL default '',
  `email` varchar(30) NOT NULL default '',
  `department` varchar(10) NOT NULL default '',
  `phoneNumber` int(11) NOT NULL default '0',
  `role` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`userID`),
  KEY `department` (`department`),
  CONSTRAINT `personTable_ibfk_1` FOREIGN KEY (`department`) REFERENCES `departmentTable` (`department`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `personTable` DISABLE KEYS */;
LOCK TABLES `personTable` WRITE;
INSERT INTO `personTable` VALUES ('g11111','Hooah','Sir','g11111@usma.edu','BTD',11,'instructor'),('r12345','Me','Follow','r12345@usma.edu','DPE',15,'instructor'),('x11111','Smith','John','x11111@usma.edu','USCC',0,'cadet'),('x22222','Williams','Anna','x22222@usma.edu','USCC',1,'cadet'),('x33333','Jones','Bob','x33333@usma.edu','USCC',2,'cadet');
UNLOCK TABLES;
/*!40000 ALTER TABLE `personTable` ENABLE KEYS */;

--
-- Table structure for table `authenticationTable`
--

DROP TABLE IF EXISTS `authenticationTable`;
CREATE TABLE `authenticationTable` (
  `password` varchar(10) NOT NULL default '',
  `userID` varchar(6) NOT NULL default '',
  KEY `userID` (`userID`),
  CONSTRAINT `authenticationTable_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `personTable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `authenticationTable` DISABLE KEYS */;
LOCK TABLES `authenticationTable` WRITE;
INSERT INTO `authenticationTable` VALUES ('abc','x11111'),('abc','x22222'),('abc','x33333'),('abc','g11111'),('abc','r12345');
UNLOCK TABLES;
/*!40000 ALTER TABLE `authenticationTable` ENABLE KEYS */;

--
-- Table structure for table `cadetTable`
--

DROP TABLE IF EXISTS `cadetTable`;
CREATE TABLE `cadetTable` (
  `userID` varchar(6) NOT NULL default '',
  `instructor` varchar(6) default '',
  `phoneNum` varchar(15) default '',
  `company` varchar(10) default '',
  `year` varchar(4) default '',
  PRIMARY KEY  (`userID`),
  CONSTRAINT `cadetTable_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `personTable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `cadetTable` DISABLE KEYS */;
LOCK TABLES `cadetTable` WRITE;
INSERT INTO `cadetTable` VALUES ('x11111','g11111','5551234','A1','2007'),('x22222','g11111','5555678','B2','2008'),('x33333','r12345','5559012','C3','2009');
UNLOCK TABLES;
/*!40000 ALTER TABLE `cadetTable` ENABLE KEYS */;

--
-- Table structure for table `instructorTable`
--

DROP TABLE IF EXISTS `instructorTable`;
CREATE TABLE `instructorTable` (
  `userID` varchar(6) NOT NULL default '',
  `course` varchar(20) default '',
  `phoneNum` varchar(15) default '',
  PRIMARY KEY  (`userID`),
  CONSTRAINT `instructorTable_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `personTable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `instructorTable` DISABLE KEYS */;
LOCK TABLES `instructorTable` WRITE;
INSERT INTO `instructorTable` VALUES ('g11111','IS450','5550001'),('r12345','DPE101','5550002');
UNLOCK TABLES;
/*!40000 ALTER TABLE `instructorTable` ENABLE KEYS */;

--
-- Table structure for table `equipmentTable`
--

DROP TABLE IF EXISTS `equipmentTable`;
CREATE TABLE `equipmentTable` (
  `serialNumber` varchar(10) NOT NULL default '',
  `availability` tinyint(1) NOT NULL default '0',
  `dateAdded` date NOT NULL default '0000-00-00',
  `workingStatus` varchar(30) default '',
  `role` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`serialNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `equipmentTable` DISABLE KEYS */;
LOCK TABLES `equipmentTable` WRITE;
INSERT INTO `equipmentTable` VALUES ('000111',1,'2007-01-01','works fine','laptop'),('000112',1,'2007-01-01',NULL,'laptop'),('000113',1,'2007-01-01',NULL,'laptop'),('000114',0,'2007-01-01',NULL,'laptop'),('000115',1,'2007-01-01',NULL,'laptop'),('000116',0,'2007-01-01','slight damage on side','projector'),('000117',0,'2007-01-01',NULL,'projector'),('000118',0,'2007-01-01',NULL,'projector'),('000119',0,'2007-01-01',NULL,'projector'),('000120',0,'2007-01-01',NULL,'projector');
UNLOCK TABLES;
/*!40000 ALTER TABLE `equipmentTable` ENABLE KEYS */;

--
-- Table structure for table `laptops`
--

DROP TABLE IF EXISTS `laptops`;
CREATE TABLE `laptops` (
  `serialNumber` varchar(10) NOT NULL default '',
  `image` varchar(20) default '',
  PRIMARY KEY  (`serialNumber`),
  CONSTRAINT `laptops_ibfk_1` FOREIGN KEY (`serialNumber`) REFERENCES `equipmentTable` (`serialNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `laptops` DISABLE KEYS */;
LOCK TABLES `laptops` WRITE;
INSERT INTO `laptops` VALUES ('000111','Linux'),('000112','Base'),('000113','Base'),('000114','Base'),('000115','Linux');
UNLOCK TABLES;
/*!40000 ALTER TABLE `laptops` ENABLE KEYS */;

--
-- Table structure for table `projectors`
--

DROP TABLE IF EXISTS `projectors`;
CREATE TABLE `projectors` (
  `serialNumber` varchar(10) NOT NULL default '',
  `connector` varchar(10) default '',
  KEY `serialNumber` (`serialNumber`),
  CONSTRAINT `projectors_ibfk_1` FOREIGN KEY (`serialNumber`) REFERENCES `equipmentTable` (`serialNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `projectors` DISABLE KEYS */;
LOCK TABLES `projectors` WRITE;
INSERT INTO `projectors` VALUES ('000116','wireless'),('000117','wired'),('000118','wired'),('000119','wireless'),('000120','wireless');
UNLOCK TABLES;
/*!40000 ALTER TABLE `projectors` ENABLE KEYS */;

--
-- Table structure for table `submitReservationTable`
--

DROP TABLE IF EXISTS `submitReservationTable`;
CREATE TABLE `submitReservationTable` (
  `dateOut` date NOT NULL default '0000-00-00',
  `dateIn` date NOT NULL default '0000-00-00',
  `serialNumber` varchar(10) NOT NULL default '',
  `userID` varchar(6) NOT NULL default '',
  KEY `serialNumber` (`serialNumber`),
  KEY `userID` (`userID`),
  CONSTRAINT `submitReservationTable_ibfk_1` FOREIGN KEY (`serialNumber`) REFERENCES `equipmentTable` (`serialNumber`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `submitReservationTable_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `personTable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `submitReservationTable` DISABLE KEYS */;
LOCK TABLES `submitReservationTable` WRITE;
INSERT INTO `submitReservationTable` VALUES ('2007-01-01','2007-01-02','000111','x11111'),('2007-01-01','2007-01-02','000112','r12345'),('2007-01-01','2007-01-02','000120','g11111'),('2007-01-01','2007-01-03','000113','x22222'),('2007-01-01','2007-02-22','000114','x33333');
UNLOCK TABLES;
/*!40000 ALTER TABLE `submitReservationTable` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
