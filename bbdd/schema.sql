-- MySQL dump 10.13  Distrib 5.5.34, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: descubre2
-- ------------------------------------------------------
-- Server version	5.5.34-0ubuntu0.12.04.1

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
-- Table structure for table `ACTIVATION_2`
--

DROP TABLE IF EXISTS `ACTIVATION_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ACTIVATION_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER` int(11) NOT NULL,
  `DEADLINE` datetime NOT NULL,
  `MD5_KEY` varchar(255) NOT NULL DEFAULT '',
  `USED` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7549 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ADMIN_2`
--

DROP TABLE IF EXISTS `ADMIN_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADMIN_2` (
  `USER` int(11) NOT NULL,
  UNIQUE KEY `USER` (`USER`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CC_PROGRAM_2`
--

DROP TABLE IF EXISTS `CC_PROGRAM_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CC_PROGRAM_2` (
  `CONTEST_CHALLENGE` int(11) NOT NULL,
  `USER` int(11) NOT NULL,
  `PROGRAM` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CC_TRY_2`
--

DROP TABLE IF EXISTS `CC_TRY_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CC_TRY_2` (
  `CONTEST_CHALLENGE` int(11) NOT NULL DEFAULT '-1',
  `CONTEST` int(11) NOT NULL DEFAULT '-1',
  `CONTEST_ID` int(11) NOT NULL,
  `CHALLENGE` int(11) NOT NULL DEFAULT '-1',
  `CHALLENGE_ID` varchar(255) NOT NULL,
  `USER` int(11) NOT NULL DEFAULT '-1',
  `USER_ID` varchar(255) NOT NULL,
  `PROGRAM` int(11) NOT NULL DEFAULT '-1',
  `PROGRAM_ID` varchar(255) NOT NULL,
  `SOURCE_CODE` int(11) NOT NULL DEFAULT '-1',
  `SOURCE_CODE_ID` varchar(255) NOT NULL,
  `DATE` datetime NOT NULL,
  `SUCCESS` tinyint(4) NOT NULL DEFAULT '0',
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=18463 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CHALLENGE_2`
--

DROP TABLE IF EXISTS `CHALLENGE_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CHALLENGE_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MD5_KEY` varchar(255) NOT NULL DEFAULT '',
  `CHALLENGE_ID` varchar(255) NOT NULL,
  `TITLE` varchar(255) NOT NULL,
  `STATEMENT` longtext NOT NULL,
  `CODE` longtext NOT NULL,
  `TEMPLATE` longtext NOT NULL,
  `DATE` datetime NOT NULL,
  `DIFFICULTY` int(11) NOT NULL,
  `OWNER` int(11) DEFAULT NULL,
  `OWNER_ID` varchar(255) NOT NULL,
  `IMAGE` longblob,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=350 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `COACHING_2`
--

DROP TABLE IF EXISTS `COACHING_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `COACHING_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `COACH` int(11) NOT NULL DEFAULT '-1',
  `STUDENT` int(11) NOT NULL DEFAULT '-1',
  `OLD_COACH` varchar(255) NOT NULL,
  `OLD_STUDENT` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2429 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `CONTEST_2`
--

DROP TABLE IF EXISTS `CONTEST_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONTEST_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OLD_ID` int(11) DEFAULT '-1',
  `MD5_KEY` varchar(255) NOT NULL DEFAULT '',
  `TITLE` varchar(255) NOT NULL,
  `DESCRIPTION` longtext NOT NULL,
  `START` datetime NOT NULL,
  `END` datetime NOT NULL,
  `VISIBILITY` int(1) NOT NULL,
  `RANKING` tinyint(1) NOT NULL DEFAULT '0',
  `OWNER` int(11) DEFAULT NULL,
  `OWNER_ID` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `REWARD` varchar(255) NOT NULL,
  `GROUP_ID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=237 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `CONTEST_CHALLENGE_2`
--

DROP TABLE IF EXISTS `CONTEST_CHALLENGE_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONTEST_CHALLENGE_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MD5_KEY` varchar(255) NOT NULL DEFAULT '',
  `CONTEST` int(11) NOT NULL DEFAULT '-1',
  `CHALLENGE` varchar(255) NOT NULL DEFAULT '',
  `CONTEST_ID` int(11) NOT NULL,
  `CHALLENGE_ID` varchar(255) NOT NULL,
  `POSITION` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1577 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `CONTEST_USER_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CONTEST_USER_2` (
  `CONTEST` int(11) NOT NULL,
  `USER` int(11) NOT NULL,
  UNIQUE KEY `CONTEST` (`CONTEST`,`USER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `EVENT_2`
--

DROP TABLE IF EXISTS `EVENT_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EVENT_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER` int(11) NOT NULL DEFAULT '-1',
  `PROGRAM` int(11) NOT NULL DEFAULT '-1',
  `OLD_USER` varchar(255) NOT NULL,
  `OLD_PROGRAM` varchar(255) NOT NULL DEFAULT '',
  `MOMENT` datetime DEFAULT NULL,
  `WHAT` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=970725 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `GROUP_2`
--

DROP TABLE IF EXISTS `GROUP_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GROUP_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MD5_KEY` varchar(255) NOT NULL DEFAULT '',
  `NAME` varchar(255) NOT NULL,
  `OWNER` int(11) NOT NULL DEFAULT '-1',
  `PASSWORD` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=260 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `GROUP_USER_2`
--

DROP TABLE IF EXISTS `GROUP_USER_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `GROUP_USER_2` (
  `GROUP_ID` int(11) NOT NULL,
  `USER` int(11) NOT NULL,
  UNIQUE KEY `GROUP_ID` (`GROUP_ID`,`USER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `KEY_POINTS`
--

DROP TABLE IF EXISTS `KEY_POINTS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `KEY_POINTS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CREATE_FUNCTION` tinyint(1) DEFAULT '0',
  `CREATE_OBJECTS` tinyint(1) DEFAULT '0',
  `DEFINE_CLASSES` tinyint(1) DEFAULT '0',
  `DEFINE_METHODS` tinyint(1) DEFAULT '0',
  `HAS_ARITHMETIC_EXPRESSION` tinyint(1) DEFAULT '0',
  `HAS_ASSIGMENT` tinyint(1) DEFAULT '0',
  `HAS_BOOLEAN_EXPRESSION` tinyint(1) DEFAULT '0',
  `HAS_CONSTANT` tinyint(1) DEFAULT '0',
  `HAS_DECLARATION` tinyint(1) DEFAULT '0',
  `HAS_DO_WHILE` tinyint(1) DEFAULT '0',
  `HAS_ELSE` tinyint(1) DEFAULT '0',
  `HAS_FOR` tinyint(1) DEFAULT '0',
  `HAS_IF` tinyint(1) DEFAULT '0',
  `HAS_RECURSIVE_FUNCTION` tinyint(1) DEFAULT '0',
  `HAS_SWITCH` tinyint(1) DEFAULT '0',
  `HAS_WHILE` tinyint(1) DEFAULT '0',
  `RETURN_ARRAY` tinyint(1) DEFAULT '0',
  `USE_ARRAY` tinyint(1) DEFAULT '0',
  `USE_ARRAY_AS_PARAMETER` tinyint(1) DEFAULT '0',
  `USE_FUNCTION` tinyint(1) DEFAULT '0',
  `USE_LITERAL` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=484209 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `PROGRAM_2`
--

DROP TABLE IF EXISTS `PROGRAM_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PROGRAM_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PROGRAM_ID` varchar(255) NOT NULL,
  `MD5_KEY` varchar(255) NOT NULL DEFAULT '',
  `CURRENT_VERSION` int(11) DEFAULT '-1',
  `OLD_CURRENT_VERSION` int(11) DEFAULT '-1',
  `DATE` datetime DEFAULT NULL,
  `TITLE` varchar(255) NOT NULL,
  `VIEWS` int(11) NOT NULL,
  `VISIBILITY` int(11) NOT NULL DEFAULT '1',
  `OWNER` int(11) DEFAULT '-1',
  `OWNER_ID` varchar(255) DEFAULT NULL,
  `ROOT` int(11) DEFAULT '-1',
  `OLD_ROOT` varchar(255) DEFAULT NULL,
  `ICON` int(11) NOT NULL DEFAULT '0',
  `VOTES` int(11) NOT NULL DEFAULT '0',
  `RUNS` int(11) NOT NULL,
  `TYPING` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `constr_ID` (`MD5_KEY`),
  KEY `PROGRAM_ID` (`PROGRAM_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=47254 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `SOURCE_CODE_2`
--

DROP TABLE IF EXISTS `SOURCE_CODE_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SOURCE_CODE_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SOURCE_CODE_ID` varchar(255) NOT NULL,
  `CODE` longtext NOT NULL,
  `DATE` datetime NOT NULL,
  `PROGRAM` int(11) DEFAULT '-1',
  `PROGRAM_ID` varchar(255) NOT NULL,
  `VERSION` int(11) NOT NULL,
  `KEYPOINTS` int(11) DEFAULT '-1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=162198 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `TESTCASE_2`
--

DROP TABLE IF EXISTS `TESTCASE_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TESTCASE_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CHALLENGE` int(11) NOT NULL DEFAULT '-1',
  `CHALLENGE_ID` varchar(255) NOT NULL,
  `INPUT` varchar(4096) DEFAULT NULL,
  `OUTPUT` longtext,
  `OUTPUT_IMAGE` longblob,
  `HASH` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=986 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `USER_2`
--

DROP TABLE IF EXISTS `USER_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `MD5_KEY` varchar(255) NOT NULL DEFAULT '',
  `USER_ID` varchar(255) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `EXPERIENCE` int(11) NOT NULL DEFAULT '0',
  `SIGNUPDATE` date DEFAULT NULL,
  `BIRTHYEAR` year(4) DEFAULT NULL,
  `GENDER` char(1) DEFAULT NULL,
  `CENTRE` varchar(100) DEFAULT NULL,
  `ROLE` varchar(10) DEFAULT NULL,
  `ACTIVE` tinyint(4) NOT NULL DEFAULT '0',
  `AGREED` int(4) NOT NULL,
  `LASTLOGIN` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `USER_ID` (`USER_ID`),
  UNIQUE KEY `constr_md5_key` (`MD5_KEY`)
) ENGINE=MyISAM AUTO_INCREMENT=8830 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `VOTES_2`
--

DROP TABLE IF EXISTS `VOTES_2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VOTES_2` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER` int(11) NOT NULL DEFAULT '-1',
  `PROGRAM` int(11) NOT NULL DEFAULT '-1',
  `OLD_USER` varchar(255) NOT NULL,
  `OLD_PROGRAM` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4778 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-10-07 12:30:41
