-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `Article`;
CREATE TABLE `Article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8_unicode_ci NOT NULL,
  `publication` tinyint(1) NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `dateEdition` date DEFAULT NULL,
  `auteur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_CD8737FA989D9B62` (`slug`),
  UNIQUE KEY `UNIQ_CD8737FA3DA5256D` (`image_id`),
  CONSTRAINT `FK_CD8737FA3DA5256D` FOREIGN KEY (`image_id`) REFERENCES `Image` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `Article` (`id`, `image_id`, `date`, `titre`, `contenu`, `publication`, `slug`, `dateEdition`, `auteur`) VALUES
(2,	2,	'2014-08-20 00:00:00',	'Test Ben 122',	'blablabla',	1,	'test-ben-122',	NULL,	'Benjamin'),
(3,	3,	'2014-08-20 00:00:00',	'Test 2 de Ben',	'Test test',	1,	'test-2-de-ben',	NULL,	'Benjamin');

DROP TABLE IF EXISTS `ArticleCompetence`;
CREATE TABLE `ArticleCompetence` (
  `article_id` int(11) NOT NULL,
  `competence_id` int(11) NOT NULL,
  `niveau` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`article_id`,`competence_id`),
  KEY `IDX_6283A86C7294869C` (`article_id`),
  KEY `IDX_6283A86C15761DAB` (`competence_id`),
  CONSTRAINT `FK_6283A86C15761DAB` FOREIGN KEY (`competence_id`) REFERENCES `Competence` (`id`),
  CONSTRAINT `FK_6283A86C7294869C` FOREIGN KEY (`article_id`) REFERENCES `Article` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `article_categorie`;
CREATE TABLE `article_categorie` (
  `article_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`categorie_id`),
  KEY `IDX_934886107294869C` (`article_id`),
  KEY `IDX_93488610BCF5E72D` (`categorie_id`),
  CONSTRAINT `FK_934886107294869C` FOREIGN KEY (`article_id`) REFERENCES `Article` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_93488610BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `Categorie` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `article_categorie` (`article_id`, `categorie_id`) VALUES
(2,	9),
(3,	8);

DROP TABLE IF EXISTS `Categorie`;
CREATE TABLE `Categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `Categorie` (`id`, `nom`) VALUES
(7,	'Symfony2'),
(8,	'Doctrine2'),
(9,	'Tutoriel'),
(10,	'Évènement');

DROP TABLE IF EXISTS `Commentaire`;
CREATE TABLE `Commentaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `auteur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E16CE76B7294869C` (`article_id`),
  CONSTRAINT `FK_E16CE76B7294869C` FOREIGN KEY (`article_id`) REFERENCES `Article` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `Competence`;
CREATE TABLE `Competence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `Competence` (`id`, `nom`) VALUES
(4,	'Doctrine'),
(5,	'Formulaire'),
(6,	'Twig');

DROP TABLE IF EXISTS `Image`;
CREATE TABLE `Image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `Image` (`id`, `url`, `alt`) VALUES
(1,	'png',	'Capture d’écran 2014-07-29 à 15.02.23.png'),
(2,	'jpeg',	'Moi.jpg'),
(3,	'jpeg',	'Moi.jpg');

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA1797792FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_2DA17977A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `User` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(1,	'ben',	'ben',	'benjamin.ghenne@gmail.com',	'benjamin.ghenne@gmail.com',	1,	'80mtwretdwg0o4ggok8og4g40wgw8ws',	'1oxYTejM7fKN3jZ8vHEYkRXbq7U+0cis10rK6QXVsNNOkj8trEdZh62MB6YaYgubon7zOkU/DWNWMnZbgZ9d8g==',	'2014-08-20 15:00:47',	0,	0,	NULL,	NULL,	NULL,	'a:0:{}',	0,	NULL);

-- 2014-08-20 13:47:23
