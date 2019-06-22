-- MySQL dump 10.9
--
-- Host: localhost    Database: isd2
-- ------------------------------------------------------
-- Server version	4.1.8-nt

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="NO_AUTO_VALUE_ON_ZERO" */;

--
-- Table structure for table `authenticationtable`
--

DROP TABLE IF EXISTS `authenticationtable`;
CREATE TABLE `authenticationtable` (
  `password` varchar(10) NOT NULL default '',
  `userID` varchar(6) NOT NULL default '',
  KEY `userID` (`userID`),
  CONSTRAINT `authenticationtable_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `persontable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authenticationtable`
--


/*!40000 ALTER TABLE `authenticationtable` DISABLE KEYS */;
LOCK TABLES `authenticationtable` WRITE;
INSERT INTO `authenticationtable` VALUES ('abc','x11111'),('abc','x22222'),('abc','x33333'),('abc','g11111'),('abc','r12345');
UNLOCK TABLES;
/*!40000 ALTER TABLE `authenticationtable` ENABLE KEYS */;

--
-- Table structure for table `departmenttable`
--

DROP TABLE IF EXISTS `departmenttable`;
CREATE TABLE `departmenttable` (
  `department` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `departmenttable`
--


/*!40000 ALTER TABLE `departmenttable` DISABLE KEYS */;
LOCK TABLES `departmenttable` WRITE;
INSERT INTO `departmenttable` VALUES ('BTD'),('DFL'),('DPE'),('EECS'),('USCC');
UNLOCK TABLES;
/*!40000 ALTER TABLE `departmenttable` ENABLE KEYS */;

--
-- Table structure for table `equipmenttable`
--

DROP TABLE IF EXISTS `equipmenttable`;
CREATE TABLE `equipmenttable` (
  `serialNumber` varchar(10) NOT NULL default '',
  `availability` tinyint(1) NOT NULL default '0',
  `dateAdded` date NOT NULL default '0000-00-00',
  `workingStatus` varchar(30) default '',
  `role` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`serialNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `equipmenttable`
--


/*!40000 ALTER TABLE `equipmenttable` DISABLE KEYS */;
LOCK TABLES `equipmenttable` WRITE;
INSERT INTO `equipmenttable` VALUES ('000111',1,'2007-01-01','works fine','laptop'),('000112',1,'2007-01-01',NULL,'laptop'),('000113',1,'2007-01-01',NULL,'laptop),('000114',0,'2007-01-01',NULL,'laptop'),('000115',1,'2007-01-01',NULL,'laptop'),('000116',0,'2007-01-01','slight damage on side','projector'),('000117',0,'2007-01-01',NULL,'projector'),('000118',0,'2007-01-01',NULL,'projector'),('000119',0,'2007-01-01',NULL,'projector'),('000120',0,'2007-01-01',NULL,'projector');
UNLOCK TABLES;
/*!40000 ALTER TABLE `equipmenttable` ENABLE KEYS */;

--
-- Table structure for table `laptops`
--

DROP TABLE IF EXISTS `laptops`;
CREATE TABLE `laptops` (
  `serialNumber` varchar(10) NOT NULL default '',
  `image` varchar(20) default '',
  KEY `serialNumber` (`serialNumber`),
  CONSTRAINT `laptops_ibfk_1` FOREIGN KEY (`serialNumber`) REFERENCES `equipmenttable` (`serialNumber`) ON DELETE CASCADE ON UPDATE CASCADE
  PRIMARY KEY  (`serialNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `laptops`
--


/*!40000 ALTER TABLE `laptops` DISABLE KEYS */;
LOCK TABLES `laptops` WRITE;
INSERT INTO `laptops` VALUES ('000111','Linux'),('000112','Base'),('000113','Base'),('000114','Base'),('000115','Linux');
UNLOCK TABLES;
/*!40000 ALTER TABLE `laptops` ENABLE KEYS */;

--
-- Table structure for table `persontable`
--

DROP TABLE IF EXISTS `persontable`;
CREATE TABLE `persontable` (
  `userID` varchar(6) NOT NULL default '',
  `lastName` varchar(30) NOT NULL default '',
  `firstName` varchar(30) NOT NULL default '',
  `email` varchar(30) NOT NULL default '',
  `department` varchar(10) NOT NULL default '',
  `phoneNumber` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userID`),
  KEY `department` (`department`),
  CONSTRAINT `persontable_ibfk_1` FOREIGN KEY (`department`) REFERENCES `departmenttable` (`department`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `persontable`
--


/*!40000 ALTER TABLE `persontable` DISABLE KEYS */;
LOCK TABLES `persontable` WRITE;
INSERT INTO `persontable` VALUES ('g11111','Hooah','Sir','g11111@usma.edu','BTD',11),('r12345','Me','Follow','r12345@usma.edu','DPE',15),('x11111','Smith','John','x11111@usma.edu','USCC',0),('x22222','Williams','Anna','x22222@usma.edu','USCC',1),('x33333','Jones','Bob','x33333@usma.edu','USCC',2);
UNLOCK TABLES;
/*!40000 ALTER TABLE `persontable` ENABLE KEYS */;

--
-- Table structure for table `projectors`
--

DROP TABLE IF EXISTS `projectors`;
CREATE TABLE `projectors` (
  `serialNumber` varchar(10) NOT NULL default '',
  `connector` varchar(10) default '',
  KEY `serialNumber` (`serialNumber`),
  CONSTRAINT `projectors_ibfk_1` FOREIGN KEY (`serialNumber`) REFERENCES `equipmenttable` (`serialNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projectors`
--


/*!40000 ALTER TABLE `projectors` DISABLE KEYS */;
LOCK TABLES `projectors` WRITE;
INSERT INTO `projectors` VALUES ('000116','wireless'),('000117','wired'),('000118','wired'),('000119','wireless'),('000120','wireless');
UNLOCK TABLES;
/*!40000 ALTER TABLE `projectors` ENABLE KEYS */;

--
-- Table structure for table `submitreservationtable`
--

DROP TABLE IF EXISTS `submitreservationtable`;
CREATE TABLE `submitreservationtable` (
  `dateOut` date NOT NULL default '0000-00-00',
  `dateIn` date NOT NULL default '0000-00-00',
  `serialNumber` varchar(10) NOT NULL default '',
  `userID` varchar(6) NOT NULL default '',
  KEY `serialNumber` (`serialNumber`),
  KEY `userID` (`userID`),
  CONSTRAINT `submitreservationtable_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `persontable` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `submitreservationtable_ibfk_1` FOREIGN KEY (`serialNumber`) REFERENCES `equipmenttable` (`serialNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `submitreservationtable`
--


/*!40000 ALTER TABLE `submitreservationtable` DISABLE KEYS */;
LOCK TABLES `submitreservationtable` WRITE;
INSERT INTO `submitreservationtable` VALUES ('2007-01-01','2007-01-02','000111','x11111'),('2007-01-01','2007-01-02','000112','r12345'),('2007-01-01','2007-01-02','000120','g11111'),('2007-01-01','2007-01-03','000113','x22222'),('2007-01-01','2007-02-22','000114','x33333');
UNLOCK TABLES;
/*!40000 ALTER TABLE `submitreservationtable` ENABLE KEYS */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

