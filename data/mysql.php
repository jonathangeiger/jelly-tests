<?php

// Data for MySQL databases
return array
(
	"DROP TABLE IF EXISTS `authors`;",

	"CREATE TABLE `authors` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NULL,
	  `password` varchar(255) NULL,
	  `email` varchar(255) NULL,
	  `role_id` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;",

	"INSERT INTO `authors` (`id`,`name`,`email`,`role_id`)
	VALUES
		(1,'Jonathan Geiger','jonathan@jonathan-geiger.com', 1),
		(2,'Paul Banks','paul@banks.com', 0),
		(3,'Bobby Tables','bobby@sql-injection.com', 2);",

	"DROP TABLE IF EXISTS `categories`;",

	"CREATE TABLE `categories` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `parent_id` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;",

	"INSERT INTO `categories` (`id`,`name`,`parent_id`)
	VALUES
		(1,'Category One',0),
		(2,'Category Two',0),
		(3,'Category Three',1);",

	"DROP TABLE IF EXISTS `categories_posts`;",

	"CREATE TABLE `categories_posts` (
	  `category_id` int(11) NOT NULL,
	  `post_id` int(11) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

	"INSERT INTO `categories_posts` (`category_id`,`post_id`)
	VALUES
		(1,1),
		(2,1),
		(3,1),
		(2,2),
		(1,3),
		(2,3);",

	"DROP TABLE IF EXISTS `posts`;",

	"CREATE TABLE `posts` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `slug` varchar(255) NOT NULL,
	  `status` enum('published','draft','review') NOT NULL,
	  `created` int(11) DEFAULT NULL,
	  `updated` int(11) DEFAULT NULL,
	  `published` int(11) DEFAULT NULL,
	  `author_id` int(11) DEFAULT NULL,
	  `approved_by` int(11) DEFAULT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;",

	"INSERT INTO `posts` (`id`,`name`,`slug`,`status`,`created`,`updated`,`published`,`author_id`,`approved_by`)
	VALUES
		(1,'First Post','first-post','draft',1264985737,1264985737,1264985737,1,NULL),
		(2,'Second Post','second-post','review',1264985737,1264985737,1264985737,1,1);",

	"DROP TABLE IF EXISTS `roles`;",

	"CREATE TABLE `roles` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(32) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;",

	"INSERT INTO `roles` (`id`,`name`)
	VALUES
		(1,'Staff'),
		(2,'Freelancer');",
);