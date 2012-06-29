-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.92-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema smsfoursquare
--

CREATE DATABASE IF NOT EXISTS smsfoursquare;
USE smsfoursquare;

--
-- Definition of table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id` bigint(20) unsigned NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `twitter` varchar(30) NOT NULL,
  `facebook` bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Definition of table `mentions`
--

DROP TABLE IF EXISTS `mentions`;
CREATE TABLE `mentions` (
  `id` bigint(20) unsigned NOT NULL,
  `twitter_id` bigint(20) unsigned NOT NULL,
  `screen_name` varchar(30) NOT NULL,
  `checkin_id` varchar(50) NOT NULL,
  `vanue_id` varchar(50) NOT NULL,
  `twitter_text` varchar(255) NOT NULL,
  `registered` datetime NOT NULL,
  `twitter_id_response` bigint(20) unsigned NOT NULL,
  `response_text` varchar(140) NOT NULL,
  PRIMARY KEY  USING BTREE (`id`,`twitter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Definition of table `scores`
--

DROP TABLE IF EXISTS `scores`;
CREATE TABLE `scores` (
  `id` bigint(20) unsigned NOT NULL,
  `recent` int(10) unsigned NOT NULL,
  `max` int(10) unsigned NOT NULL,
  `checkinsCount` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Definition of table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `id` bigint(20) unsigned NOT NULL,
  `token` varchar(255) NOT NULL,
  `registered` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY  USING BTREE (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Definition of table `twitter`
--

DROP TABLE IF EXISTS `twitter`;
CREATE TABLE `twitter` (
  `id` int(10) unsigned NOT NULL,
  `oauth_token` varchar(255) NOT NULL,
  `registered` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  `oauth_token_secret` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `screen_name` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15590586 DEFAULT CHARSET=utf8;


--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `gender` varchar(45) NOT NULL,
  `homeCity` varchar(255) NOT NULL,
  `canonicalUrl` varchar(255) NOT NULL,
  `relationship` varchar(150) NOT NULL,
  `type` varchar(30) NOT NULL,
  `pings` varchar(45) NOT NULL,
  `badges` int(10) unsigned NOT NULL,
  `mayorships` int(10) unsigned NOT NULL,
  `checkins` int(10) unsigned NOT NULL,
  `friends` int(10) unsigned NOT NULL,
  `following` int(10) unsigned NOT NULL,
  `requests` int(10) unsigned NOT NULL,
  `tips` int(10) unsigned NOT NULL,
  `todos` int(10) unsigned NOT NULL,
  `photos` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



--
-- Definition of table `venues`
--

DROP TABLE IF EXISTS `venues`;
CREATE TABLE `venues` (
  `id` varchar(24) NOT NULL,
  `name` varchar(120) NOT NULL,
  `registered` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Definition of table `venues_name`
--

DROP TABLE IF EXISTS `venues_name`;
CREATE TABLE `venues_name` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `venues_name`
--

/*!40000 ALTER TABLE `venues_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `venues_name` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
