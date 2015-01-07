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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`,`description`,`user_id`) values (5,'Регулярные выражения','Вопросы связанные с регулярными выражениями',38),(6,'Напишите за меня','Здесь можно просить о всем, что угодно',38),(7,'Функции PHP','',38),(8,'PHP и MySQL','Вопросы, проблемы, ошибки связанные с работой PHP и MySQL',38);

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `theme_id` int(11) unsigned NOT NULL,
  `hide_status` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `theme_id` (`theme_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`theme_id`) REFERENCES `themes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `messages` */

insert  into `messages`(`id`,`name`,`user_id`,`theme_id`,`hide_status`) values (14,'Проверка Email\r\n\'/^([a-z0-9_\\.-]+)@([a-z0-9_\\.-]+)\\.([a-z\\.]{2,6})$/\'<br><small>2015-01-08 01:00:48</small>',38,15,NULL),(15,'Проверка пароль \'/\\A(?=\\S*?[A-Z])(?=\\S*?[a-z])(?=\\S*?[0-9])\\S{6,}\\z/\'  при этом Пароль должен содержать хотя бы одну большую букву, маленькую букву и цифру и быть не меннее 6 символов<br><small>2015-01-08 01:02:03</small>',38,15,NULL),(16,'Напишите класс для обработки/перехвата ошибок<br><small>2015-01-08 01:03:30</small>',38,7,NULL),(17,'Как создать одно единственное подключение к базе данных?<br><small>2015-01-08 01:04:16</small>',38,18,NULL),(18,'Отличная программа для написания регулярных выражений  RegexBuddy 4. Будут вопросы пишите.<br><small>2015-01-08 01:05:40</small>',39,16,NULL),(19,'Помогите написать функцию проверки $_POST данных.<br><small>2015-01-08 01:06:50</small>',39,6,NULL),(20,'Появилась ошибка: Слишком много подключений к БД. Почему и как это исправить?<br><small>2015-01-08 01:08:02</small>',39,19,NULL),(21,'Кроме того RegexBuddy содержит в себе обширную библиотеку уже готовых регулярных выражений<br><small>2015-01-08 01:09:42</small>',39,16,NULL);

/*Table structure for table `themes` */

DROP TABLE IF EXISTS `themes`;

CREATE TABLE `themes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `tstatus_id` int(11) unsigned NOT NULL DEFAULT '1',
  `hide_status` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `tstatus_id` (`tstatus_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `themes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `themes_ibfk_2` FOREIGN KEY (`tstatus_id`) REFERENCES `tstatus` (`id`),
  CONSTRAINT `themes_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Data for the table `themes` */

insert  into `themes`(`id`,`name`,`user_id`,`category_id`,`tstatus_id`,`hide_status`) values (5,'Регулярное выражение',38,6,1,NULL),(6,'Функцию',38,6,1,NULL),(7,'Класс',38,6,1,NULL),(8,'Скрипт',38,6,1,NULL),(9,'Сайт',38,6,1,NULL),(10,'Функции для работы с массивами',38,7,1,NULL),(11,'Функции для обработки строк',38,7,1,NULL),(12,'Функции для работы с файловой системой',38,7,1,NULL),(13,'Функции класса PDO',38,7,1,NULL),(14,'Магические методы',38,7,1,NULL),(15,'Примеры работающих регулярных выражений',38,5,1,NULL),(16,'Программы помогающие писать регулярные выражения',38,5,1,NULL),(17,'Парсеры',38,5,1,NULL),(18,'Подключение к БД',38,8,1,NULL),(19,'Ошибки при подключении к БД',38,8,1,NULL),(20,'SQL запросы',38,8,1,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`about_me`,`activation`,`ustatus_id`) values (38,'Олеся','liskorzun@gmail.com','95462e888737d0184a48311362eb23c3','Я начинающий программист','481f3ef63949dc8af89e01303b36bd3d',1),(39,'Lisa','korzun@open.by','aae55d7b50de6d3aa6550e63c2f6bf74','Я программист PHP','dcf179bea5e7647cf4d75e6c61b27926',2),(53,'LisaAlisa','natusik.lis@yandex.ru','8a53c0d76958985364022bfe9184a93f','','db4377510642f6cccb8017860ba69215',2);

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
