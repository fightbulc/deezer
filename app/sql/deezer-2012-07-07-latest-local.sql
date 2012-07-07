# ************************************************************
# Sequel Pro SQL dump
# Version 3710
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.25-log)
# Database: deezer
# Generation Time: 2012-07-07 13:38:13 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table moods
# ------------------------------------------------------------

DROP TABLE IF EXISTS `moods`;

CREATE TABLE `moods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `moods` WRITE;
/*!40000 ALTER TABLE `moods` DISABLE KEYS */;

INSERT INTO `moods` (`id`, `name`)
VALUES
	(1,'happy'),
	(2,'angry'),
	(3,'dreamy'),
	(4,'terrible'),
	(5,'sad'),
	(6,'cheezy');

/*!40000 ALTER TABLE `moods` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table memories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `memories`;

CREATE TABLE `memories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `track_id` bigint(20) NOT NULL,
  `mood_id` bigint(20) NOT NULL,
  `memory` text NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `memories` WRITE;
/*!40000 ALTER TABLE `memories` DISABLE KEYS */;

INSERT INTO `memories` (`id`, `user_id`, `track_id`, `mood_id`, `memory`, `created`)
VALUES
	(1,1,6461440,1,'Hello!',1341665124),
	(2,1,1109731,2,'FooBar',1341665124),
	(3,1,6026499,3,'Blaaaaaa',1341665124),
	(4,1,916424,2,'Sunday/Monday',1341665124),
	(5,1,916445,4,'Jaaaaaa',1341665124),
	(6,1,1109730,5,'FooBar',1341665124),
	(7,1,2951016,6,'FooBar',1341665124),
	(8,1,6461435,4,'FooBar',1341665124),
	(9,1,6461430,6,'FooBar',1341665124),
	(10,1,916427,1,'FooBar',1341665124),
	(14,2,6026499,2,'Jiaiaiaiaiaa',1341665124),
	(15,3,6026499,3,'Jiaiaiaiaiaa',1341665124),
	(16,4,6026499,1,'Jiaiaiaiaiaa',1341665124);

/*!40000 ALTER TABLE `memories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
