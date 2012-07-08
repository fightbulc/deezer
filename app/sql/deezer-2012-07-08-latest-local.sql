# ************************************************************
# Sequel Pro SQL dump
# Version 3710
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.25-log)
# Database: deezer
# Generation Time: 2012-07-08 14:04:19 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table memories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `memories`;

CREATE TABLE `memories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `track_id` bigint(20) NOT NULL,
  `mood_tag` varchar(100) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Mood.getByTrackId` (`track_id`,`mood_tag`,`id`),
  FULLTEXT KEY `search` (`mood_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `memories` WRITE;
/*!40000 ALTER TABLE `memories` DISABLE KEYS */;

INSERT INTO `memories` (`id`, `user_id`, `track_id`, `mood_tag`, `created`)
VALUES
	(1,1,6461440,'happy',1341665124),
	(2,3,1109731,'sad',1341665124),
	(3,1,6026499,'love',1341665124),
	(4,1,916424,'angry',1341665124),
	(5,1,916445,'dreamy',1341665124),
	(6,4,1109730,'dreamy',1341665124),
	(7,1,2951016,'happy',1341665124),
	(8,1,6461435,'chilled',1341665124),
	(9,1,6461430,'thoughtful',1341665124),
	(10,1,916427,'sleepy',1341665124),
	(14,2,6026499,'love',1341665124),
	(15,3,6026499,'hate',1341665124),
	(16,4,6026499,'rage',1341665124),
	(17,1,6026499,'exhausted',1341675109),
	(18,1,6026499,'exhausted',1341675141),
	(19,1,6026499,'happy',1341675170),
	(20,1,6026499,'exhausted',1341675195),
	(21,45403071,6026499,'exhausted',1341696769),
	(22,45403071,6026499,'sunny',1341739052),
	(23,45403071,6026499,'sunny',1341744892),
	(24,45403071,6026499,'sunny',1341744902),
	(25,45403071,3147243,'growing grass',1341755116),
	(26,45403071,3147243,'growinggrass',1341755519),
	(27,45403071,3147243,'growing-grass',1341755578),
	(28,45403071,13236642,'good-times',1341755945),
	(29,45403071,2221576,'booyaaa',1341756223);

/*!40000 ALTER TABLE `memories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table story_votes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `story_votes`;

CREATE TABLE `story_votes` (
  `story_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `vote` tinyint(1) NOT NULL,
  KEY `byMemoryId` (`story_id`,`user_id`,`vote`),
  KEY `byUserId` (`user_id`,`story_id`,`vote`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `story_votes` WRITE;
/*!40000 ALTER TABLE `story_votes` DISABLE KEYS */;

INSERT INTO `story_votes` (`story_id`, `user_id`, `vote`)
VALUES
	(3,45403071,1);

/*!40000 ALTER TABLE `story_votes` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table stories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stories`;

CREATE TABLE `stories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `track_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `story` text NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `byTrackId` (`track_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `stories` WRITE;
/*!40000 ALTER TABLE `stories` DISABLE KEYS */;

INSERT INTO `stories` (`id`, `track_id`, `user_id`, `story`, `created`)
VALUES
	(1,6026499,45403071,'Foozle.',1341738968),
	(2,6026499,45403071,'Foozle.',1341739604),
	(3,6026499,45403071,'Foozle.',1341749535);

/*!40000 ALTER TABLE `stories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tracks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tracks`;

CREATE TABLE `tracks` (
  `id` bigint(20) unsigned NOT NULL,
  `artist` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  UNIQUE KEY `byId` (`id`,`artist`,`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tracks` WRITE;
/*!40000 ALTER TABLE `tracks` DISABLE KEYS */;

INSERT INTO `tracks` (`id`, `artist`, `title`)
VALUES
	(2221576,'Big Pun featuring Joe','Still Not A Player'),
	(3147243,'Radiohead','Creep (Acoustic)'),
	(13236642,'Plattenpapzt feat. Kool Savas','King Of Rap');

/*!40000 ALTER TABLE `tracks` ENABLE KEYS */;
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
