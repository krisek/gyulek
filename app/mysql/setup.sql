-- MySQL dump 10.11
--
-- Host: localhost    Database: gyulek
-- ------------------------------------------------------
-- Server version	5.0.38-Ubuntu_0ubuntu1-log

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
-- Table structure for table `amounts`
--

DROP TABLE IF EXISTS `amounts`;
CREATE TABLE `amounts` (
  `id` int(11) NOT NULL auto_increment,
  `member_id` int(11) NOT NULL default '0',
  `ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `type` enum('fenntart','adomany','alapitvany') NOT NULL default 'fenntart',
  `amount` int(10) unsigned NOT NULL default '0',
  `dt` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `catalog`
--

DROP TABLE IF EXISTS `catalog`;
CREATE TABLE `catalog` (
  `catalog_name` varchar(30) NOT NULL default '',
  `member_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`catalog_name`,`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `diary`
--

DROP TABLE IF EXISTS `diary`;
CREATE TABLE `diary` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ts` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `tm` datetime default NULL,
  `ige` varchar(255) default NULL,
  `resztvevok` int(11) default NULL,
  `urvacsora` int(11) default NULL,
  `megjegyzes` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `hely_freetagged_objects`
--

DROP TABLE IF EXISTS `hely_freetagged_objects`;
CREATE TABLE `hely_freetagged_objects` (
  `tag_id` int(10) unsigned NOT NULL default '0',
  `tagger_id` int(10) unsigned NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `tagged_on` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`),
  KEY `tag_id_index` (`tag_id`),
  KEY `tagger_id_index` (`tagger_id`),
  KEY `object_id_index` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `hely_freetags`
--

DROP TABLE IF EXISTS `hely_freetags`;
CREATE TABLE `hely_freetags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` varchar(30) NOT NULL default '',
  `raw_tag` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `lelkesz_freetagged_objects`
--

DROP TABLE IF EXISTS `lelkesz_freetagged_objects`;
CREATE TABLE `lelkesz_freetagged_objects` (
  `tag_id` int(10) unsigned NOT NULL default '0',
  `tagger_id` int(10) unsigned NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `tagged_on` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`),
  KEY `tag_id_index` (`tag_id`),
  KEY `tagger_id_index` (`tagger_id`),
  KEY `object_id_index` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `lelkesz_freetags`
--

DROP TABLE IF EXISTS `lelkesz_freetags`;
CREATE TABLE `lelkesz_freetags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` varchar(30) NOT NULL default '',
  `raw_tag` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `id` int(11) NOT NULL auto_increment,
  `nev` varchar(100) NOT NULL default '',
  `leany_neve` varchar(100) NOT NULL default '',
  `anyja_neve` varchar(100) NOT NULL default '',
  `foglalkozas` varchar(100) NOT NULL default '',
  `ir_szam` varchar(100) NOT NULL default '',
  `varos` varchar(100) NOT NULL default '',
  `cim` varchar(100) NOT NULL default '',
  `telefon_mobil` VARCHAR(100) NOT NULL default '',
  `telefon` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `religion_id` varchar(100) NOT NULL default '',
  `member_id` int(11) NOT NULL default '0',
  `csal_all_id` varchar(100) NOT NULL default '',
  `szarm_hely` varchar(100) NOT NULL default '',
  `szul_hely` varchar(100) NOT NULL default '',
  `szul_datum` date NOT NULL default '0000-00-00',
  `ker_hely` varchar(100) NOT NULL default '',
  `ker_datum` date NOT NULL default '0000-00-00',
  `ker_ige` varchar(100) NOT NULL default '',
  `konf_ev` date default NULL,
  `konf_ige` varchar(100) NOT NULL default '',
  `polg_esk_h` varchar(100) NOT NULL default '',
  `polg_esk_datum` date NOT NULL default '0000-00-00',
  `egyh_esk_h` varchar(100) NOT NULL default '',
  `egyh_esk_datum` date NOT NULL default '0000-00-00',
  `egyh_esk_ige` varchar(100) NOT NULL default '',
  `hazastars_neve` varchar(100) NOT NULL default '',
  `hazastars` int(11) NOT NULL default '0',
  `halal_datum` date NOT NULL default '0000-00-00',
  `kepviselo` tinyint(1) NOT NULL default '0',
  `presbiter` tinyint(1) default '0',
  `megjegyzes` blob,
  `cimke` blob NOT NULL,
  `entry` date NOT NULL default '0000-00-00',
  `leave` date NOT NULL default '0000-00-00',
  `ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `religions`
--

DROP TABLE IF EXISTS `religions`;
CREATE TABLE `religions` (
  `id` int(11) NOT NULL auto_increment,
  `religion` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int(11) NOT NULL default '0',
  `status` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `szolgalat_freetagged_objects`
--

DROP TABLE IF EXISTS `szolgalat_freetagged_objects`;
CREATE TABLE `szolgalat_freetagged_objects` (
  `tag_id` int(10) unsigned NOT NULL default '0',
  `tagger_id` int(10) unsigned NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `tagged_on` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`),
  KEY `tag_id_index` (`tag_id`),
  KEY `tagger_id_index` (`tagger_id`),
  KEY `object_id_index` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `szolgalat_freetags`
--

DROP TABLE IF EXISTS `szolgalat_freetags`;
CREATE TABLE `szolgalat_freetags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` varchar(30) NOT NULL default '',
  `raw_tag` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `tag` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `tipus_freetagged_objects`
--

DROP TABLE IF EXISTS `tipus_freetagged_objects`;
CREATE TABLE `tipus_freetagged_objects` (
  `tag_id` int(10) unsigned NOT NULL default '0',
  `tagger_id` int(10) unsigned NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `tagged_on` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`),
  KEY `tag_id_index` (`tag_id`),
  KEY `tagger_id_index` (`tagger_id`),
  KEY `object_id_index` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `tipus_freetags`
--

DROP TABLE IF EXISTS `tipus_freetags`;
CREATE TABLE `tipus_freetags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` varchar(30) NOT NULL default '',
  `raw_tag` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `member_freetagged_objects`
--

DROP TABLE IF EXISTS `member_freetagged_objects`;
CREATE TABLE `member_freetagged_objects` (
  `tag_id` int(10) unsigned NOT NULL default '0',
  `tagger_id` int(10) unsigned NOT NULL default '0',
  `object_id` int(10) unsigned NOT NULL default '0',
  `tagged_on` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`),
  KEY `tag_id_index` (`tag_id`),
  KEY `tagger_id_index` (`tagger_id`),
  KEY `object_id_index` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `member_freetags`
--

DROP TABLE IF EXISTS `member_freetags`;
CREATE TABLE `member_freetags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` varchar(30) NOT NULL default '',
  `raw_tag` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-05-08 20:58:16
