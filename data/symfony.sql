-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 30 Mars 2013 à 15:47
-- Version du serveur: 5.5.29
-- Version de PHP: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `Categorie`
--

CREATE TABLE IF NOT EXISTS `Categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nb_max_coureurs` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Contenu de la table `Categorie`
--

INSERT INTO `Categorie` (`id`, `nom`, `nb_max_coureurs`) VALUES
(1, 'Solo', 1000),
(2, 'Equipe', 1000),
(3, 'Loisir', 1000),
(4, 'Hors Catégorie', 1000);

-- --------------------------------------------------------

--
-- Structure de la table `categorie_course`
--

CREATE TABLE IF NOT EXISTS `categorie_course` (
  `categorie_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`categorie_id`,`course_id`),
  KEY `IDX_90B8C580BCF5E72D` (`categorie_id`),
  KEY `IDX_90B8C580591CC992` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Course`
--

CREATE TABLE IF NOT EXISTS `Course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `edition_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `inscriptions_ouvertes` tinyint(1) NOT NULL,
  `datetime_debut` datetime NOT NULL,
  `datetime_fin` datetime NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_11326A8F74281A5E` (`edition_id`),
  KEY `IDX_11326A8FC54C8C93` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Contenu de la table `Course`
--

INSERT INTO `Course` (`id`, `edition_id`, `type_id`, `nom`, `inscriptions_ouvertes`, `datetime_debut`, `datetime_fin`, `url`, `description`) VALUES
(5, 1, 1, 'Velo-solo', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/course-cycliste/', ''),
(6, 1, 1, 'Velo-equipe', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/course-cycliste/', ''),
(7, 1, 1, 'Velo-loisir', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/course-cycliste/', ''),
(8, 1, 1, 'Velo-horscatégorie', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/velos-folklo/', 'Pour les plus originaux. Tandems, runbikes, vélo couchés, c''est dans cette catégorie que vous jouerez et participerez à mettre l''ambiance sur la course au coté des folklos et des autres coureurs.'),
(9, 1, 2, 'Pied-solo', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/course-a-pied/', ''),
(10, 1, 2, 'Pied-equipe', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/course-a-pied/', ''),
(11, 1, 2, 'Pied-loisir', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/course-a-pied/', ''),
(12, 1, 3, 'Triathlon-solo', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/24thlon-triathlon/', ''),
(13, 1, 3, 'Triathlon-equipe', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/24thlon-triathlon/', ''),
(14, 1, 3, 'Triathlon-loisir', 1, '2013-05-18 14:00:00', '2013-05-19 14:00:00', 'http://www.24heures.org/courses/24thlon-triathlon/', ''),
(15, 1, 4, 'Natation-solo', 1, '2013-05-18 14:00:00', '2013-05-18 18:00:00', 'http://www.24heures.org/courses/natation/', ''),
(16, 1, 4, 'Natation-equipe', 1, '2013-05-18 14:00:00', '2013-05-18 18:00:00', 'http://www.24heures.org/courses/natation/', ''),
(17, 1, 4, 'Natation-loisir', 1, '2013-05-18 14:00:00', '2013-05-18 18:00:00', 'http://www.24heures.org/courses/natation/', '');

-- --------------------------------------------------------

--
-- Structure de la table `Edition`
--

CREATE TABLE IF NOT EXISTS `Edition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_1` date NOT NULL,
  `date_2` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `Edition`
--

INSERT INTO `Edition` (`id`, `numero`, `date_1`, `date_2`) VALUES
(1, '39', '2013-05-17', '2013-05-19');

-- --------------------------------------------------------

--
-- Structure de la table `Equipe`
--

CREATE TABLE IF NOT EXISTS `Equipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `valide` tinyint(1) NOT NULL,
  `password` varchar(125) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_23E5BF23F85E0677` (`username`),
  KEY `IDX_23E5BF23591CC992` (`course_id`),
  KEY `IDX_23E5BF23BCF5E72D` (`categorie_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Structure de la table `Joueur`
--

CREATE TABLE IF NOT EXISTS `Joueur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equipe_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `etudiant` tinyint(1) NOT NULL,
  `taille_tshirt` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `papiers_ok` tinyint(1) NOT NULL,
  `paiement_ok` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FADDACF3E7927C74` (`email`),
  KEY `IDX_FADDACF36D861B89` (`equipe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Structure de la table `Tarif`
--

CREATE TABLE IF NOT EXISTS `Tarif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `prix` int(11) NOT NULL,
  `etudiant` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CFB0A6CD591CC992` (`course_id`),
  KEY `IDX_CFB0A6CDBCF5E72D` (`categorie_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

--
-- Contenu de la table `Tarif`
--

INSERT INTO `Tarif` (`id`, `course_id`, `categorie_id`, `prix`, `etudiant`) VALUES
(1, 5, 1, 20, 0),
(2, 5, 1, 15, 1),
(3, 6, 2, 20, 0),
(4, 6, 2, 15, 1),
(5, 7, 3, 5, 0),
(6, 7, 3, 5, 1),
(7, 8, 4, 0, 0),
(8, 8, 4, 0, 1),
(9, 9, 1, 20, 0),
(10, 9, 1, 15, 1),
(11, 10, 2, 20, 0),
(12, 10, 2, 15, 1),
(13, 11, 3, 5, 0),
(14, 11, 3, 5, 1),
(15, 12, 1, 20, 0),
(16, 12, 1, 15, 1),
(17, 13, 2, 20, 0),
(18, 13, 2, 15, 1),
(19, 14, 3, 5, 0),
(20, 14, 3, 5, 1),
(21, 15, 1, 20, 0),
(22, 15, 1, 15, 1),
(23, 16, 2, 20, 0),
(24, 16, 2, 15, 1),
(25, 17, 3, 5, 0),
(26, 17, 3, 5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Type`
--

CREATE TABLE IF NOT EXISTS `Type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Contenu de la table `Type`
--

INSERT INTO `Type` (`id`, `nom`) VALUES
(1, 'Velo'),
(2, 'Course à pied'),
(3, 'Triathlon'),
(4, 'Natation');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `categorie_course`
--
ALTER TABLE `categorie_course`
  ADD CONSTRAINT `FK_90B8C580591CC992` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_90B8C580BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `Categorie` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Course`
--
ALTER TABLE `Course`
  ADD CONSTRAINT `FK_11326A8F74281A5E` FOREIGN KEY (`edition_id`) REFERENCES `Edition` (`id`),
  ADD CONSTRAINT `FK_11326A8FC54C8C93` FOREIGN KEY (`type_id`) REFERENCES `Type` (`id`);

--
-- Contraintes pour la table `Equipe`
--
ALTER TABLE `Equipe`
  ADD CONSTRAINT `FK_23E5BF23591CC992` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`),
  ADD CONSTRAINT `FK_23E5BF23BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `Categorie` (`id`);

--
-- Contraintes pour la table `Joueur`
--
ALTER TABLE `Joueur`
  ADD CONSTRAINT `FK_FADDACF36D861B89` FOREIGN KEY (`equipe_id`) REFERENCES `Equipe` (`id`);

--
-- Contraintes pour la table `Tarif`
--
ALTER TABLE `Tarif`
  ADD CONSTRAINT `FK_CFB0A6CD591CC992` FOREIGN KEY (`course_id`) REFERENCES `Course` (`id`),
  ADD CONSTRAINT `FK_CFB0A6CDBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `Categorie` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
