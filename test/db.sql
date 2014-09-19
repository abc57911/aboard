-- Adminer 4.0.3 MySQL dump

SET NAMES utf8;

DROP TABLE IF EXISTS `album`;
CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `description` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `nick` varchar(128) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `email` (`email`),
  KEY `nick` (`nick`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `path` varchar(128) NOT NULL,
  `mime` varchar(128) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aid` int(11) NOT NULL,
  PRIMARY KEY (`path`),
  KEY `aid` (`aid`),
  KEY `title` (`title`),
  CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`aid`) REFERENCES `album` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `post_plug`;
CREATE TABLE `post_plug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `photo_id` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `photo_id` (`photo_id`),
  CONSTRAINT `post_plug_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`),
  KEY `tid` (`tid`),
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `thread` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `thread`;
CREATE TABLE `thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2014-08-28 14:25:42

