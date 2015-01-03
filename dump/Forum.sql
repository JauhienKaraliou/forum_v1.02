/*
SQLyog Ultimate v10.42 
MySQL - 5.5.40 : Database - forum
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`forum` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `forum`;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`,`description`,`user_id`) values (5,'Регулярные выражения','',38),(6,'Регулярные выражения 2','Вопросы связанные с регулярными выражениями',38),(7,'Регулярные выражения 3','Вопросы связанные с регулярными выражениями 3',38);

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `theme_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `theme_id` (`theme_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `messages` */

/*Table structure for table `themes` */

DROP TABLE IF EXISTS `themes`;

CREATE TABLE `themes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `tstatus_id` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `tstatus_id` (`tstatus_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `themes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `themes_ibfk_2` FOREIGN KEY (`tstatus_id`) REFERENCES `tstatus` (`id`),
  CONSTRAINT `themes_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `themes` */

insert  into `themes`(`id`,`name`,`user_id`,`category_id`,`tstatus_id`) values (1,'Новая тема в регулярных выражениях',38,5,1),(2,'Вторая тема',39,5,1),(3,'Третья тема',39,5,1);

/*Table structure for table `tstatus` */

DROP TABLE IF EXISTS `tstatus`;

CREATE TABLE `tstatus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tstatus` */

insert  into `tstatus`(`id`,`name`) values (1,'Открыта'),(2,'Закрыта');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `about_me` text,
  `activation` char(32) DEFAULT NULL,
  `ustatus_id` int(11) unsigned NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  KEY `ustatus_id` (`ustatus_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`ustatus_id`) REFERENCES `ustatus` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`about_me`,`activation`,`ustatus_id`) values (26,'LisaAlisa','natusik.lis@yandex.ru','800f0225f49152fc510ad22730fd40de','Я','db4377510642f6cccb8017860ba69215',2),(38,'Олеся','liskorzun@gmail.com','95462e888737d0184a48311362eb23c3','','481f3ef63949dc8af89e01303b36bd3d',1),(39,'Lisa','korzun@open.by','aae55d7b50de6d3aa6550e63c2f6bf74','Я продвинутый программист','dcf179bea5e7647cf4d75e6c61b27926',2);

/*Table structure for table `ustatus` */

DROP TABLE IF EXISTS `ustatus`;

CREATE TABLE `ustatus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `ustatus` */

insert  into `ustatus`(`id`,`name`) values (1,'Администратор'),(2,'Пользователь'),(3,'Не подтвержденный пользователь');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
