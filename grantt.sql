-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 27 mars 2019 à 23:13
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `grantt`
--

-- --------------------------------------------------------

--
-- Structure de la table `etape`
--

DROP TABLE IF EXISTS `etape`;
CREATE TABLE IF NOT EXISTS `etape` (
  `id_etape` int(11) NOT NULL AUTO_INCREMENT,
  `titre_etape` varchar(200) NOT NULL,
  `dateDebut_etape` datetime NOT NULL,
  `dateFin_etape` datetime DEFAULT NULL,
  `pourcentage_etape` tinyint(4) NOT NULL,
  `description_etape` text NOT NULL,
  `id_objectif` int(11) NOT NULL,
  PRIMARY KEY (`id_etape`),
  KEY `Etape_Objectif_FK` (`id_objectif`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `etape`
--

INSERT INTO `etape` (`id_etape`, `titre_etape`, `dateDebut_etape`, `dateFin_etape`, `pourcentage_etape`, `description_etape`, `id_objectif`) VALUES
(1, 'test', '2019-03-05 00:00:00', '2019-03-13 00:00:00', 10, 'hjlkfh', 1),
(2, 'Test d\'une Ã©tape surprise ! ', '2019-01-12 00:00:00', '2019-01-13 00:00:00', 0, '', 1),
(3, 'Tes etape alle alle alle', '2019-01-08 00:00:00', '2019-01-24 00:00:00', 0, '', 1),
(4, 'etape last', '2019-01-20 00:00:00', '2019-02-04 00:00:00', 0, '', 2),
(5, 'etape last', '2019-01-20 00:00:00', '2019-02-04 00:00:00', 0, '', 2),
(6, 'Systeme de connexion', '2019-01-01 00:00:00', '2019-01-10 00:00:00', 0, '', 5),
(7, 'Systeme de connexion', '2019-01-01 00:00:00', '2019-01-10 00:00:00', 0, '', 5);

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
CREATE TABLE IF NOT EXISTS `groupe` (
  `id_groupe` int(11) NOT NULL AUTO_INCREMENT,
  `nom_groupe` varchar(100) NOT NULL,
  PRIMARY KEY (`id_groupe`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `groupe`
--

INSERT INTO `groupe` (`id_groupe`, `nom_groupe`) VALUES
(1, 'test'),
(2, 'test 2'),
(3, 'test groupe'),
(4, 'test groupe 2');

-- --------------------------------------------------------

--
-- Structure de la table `groupe_avoir_etape`
--

DROP TABLE IF EXISTS `groupe_avoir_etape`;
CREATE TABLE IF NOT EXISTS `groupe_avoir_etape` (
  `id_etape` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  PRIMARY KEY (`id_etape`,`id_groupe`),
  KEY `GROUPE_AVOIR_ETAPE_Groupe0_FK` (`id_groupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `objectif`
--

DROP TABLE IF EXISTS `objectif`;
CREATE TABLE IF NOT EXISTS `objectif` (
  `id_objectif` int(11) NOT NULL AUTO_INCREMENT,
  `nom_objectif` varchar(200) NOT NULL,
  `dateDebut_objectif` tinyint(4) NOT NULL,
  `id_projet` int(11) NOT NULL,
  PRIMARY KEY (`id_objectif`),
  KEY `Objectif_Projet_FK` (`id_projet`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `objectif`
--

INSERT INTO `objectif` (`id_objectif`, `nom_objectif`, `dateDebut_objectif`, `id_projet`) VALUES
(1, 'Test objectif prio 1', 1, 2),
(2, 'Test objectif prio 1', 1, 2),
(3, 'Test objectif prio 1', 1, 2),
(4, 'Test objectif prio 1', 1, 1),
(5, 'CrÃ©er le site html', 1, 3),
(6, 'Systeme de crÃ©ation de projet', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id_part` int(11) NOT NULL AUTO_INCREMENT,
  `nom_part` varchar(150) NOT NULL,
  `mail_part` varchar(150) NOT NULL,
  `password_part` varchar(100) NOT NULL,
  PRIMARY KEY (`id_part`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `participant`
--

INSERT INTO `participant` (`id_part`, `nom_part`, `mail_part`, `password_part`) VALUES
(1, 'jerome', 'h', 'h'),
(2, '2', '2', '2'),
(3, '3', '3', '3');

-- --------------------------------------------------------

--
-- Structure de la table `participant_avoir_etape`
--

DROP TABLE IF EXISTS `participant_avoir_etape`;
CREATE TABLE IF NOT EXISTS `participant_avoir_etape` (
  `id_etape` int(11) NOT NULL,
  `id_part` int(11) NOT NULL,
  PRIMARY KEY (`id_etape`,`id_part`),
  KEY `PARTICIPANT_AVOIR_ETAPE_Participant0_FK` (`id_part`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `participant_avoir_groupe`
--

DROP TABLE IF EXISTS `participant_avoir_groupe`;
CREATE TABLE IF NOT EXISTS `participant_avoir_groupe` (
  `id_part` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  PRIMARY KEY (`id_part`,`id_groupe`),
  KEY `PARTICIPANT_AVOIR_GROUPE_Groupe0_FK` (`id_groupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

DROP TABLE IF EXISTS `projet`;
CREATE TABLE IF NOT EXISTS `projet` (
  `id_projet` int(11) NOT NULL AUTO_INCREMENT,
  `nom_projet` varchar(100) NOT NULL,
  PRIMARY KEY (`id_projet`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `projet`
--

INSERT INTO `projet` (`id_projet`, `nom_projet`) VALUES
(1, 'projet 1'),
(2, 'projet 2'),
(3, 'Eclozion');

-- --------------------------------------------------------

--
-- Structure de la table `projet_avoir_groupe`
--

DROP TABLE IF EXISTS `projet_avoir_groupe`;
CREATE TABLE IF NOT EXISTS `projet_avoir_groupe` (
  `id_groupe` int(11) NOT NULL,
  `id_projet` int(11) NOT NULL,
  PRIMARY KEY (`id_groupe`,`id_projet`),
  KEY `PROJET_AVOIR_GROUPE_Projet0_FK` (`id_projet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `projet_avoir_groupe`
--

INSERT INTO `projet_avoir_groupe` (`id_groupe`, `id_projet`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(1, 2),
(3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `projet_avoir_participant`
--

DROP TABLE IF EXISTS `projet_avoir_participant`;
CREATE TABLE IF NOT EXISTS `projet_avoir_participant` (
  `id_projet` int(11) NOT NULL,
  `id_part` int(11) NOT NULL,
  PRIMARY KEY (`id_projet`,`id_part`),
  KEY `PROJET_AVOIR_PARTICIPANT_Participant0_FK` (`id_part`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `projet_avoir_participant`
--

INSERT INTO `projet_avoir_participant` (`id_projet`, `id_part`) VALUES
(1, 1),
(2, 1),
(3, 1),
(1, 2),
(1, 3);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etape`
--
ALTER TABLE `etape`
  ADD CONSTRAINT `Etape_Objectif_FK` FOREIGN KEY (`id_objectif`) REFERENCES `objectif` (`id_objectif`);

--
-- Contraintes pour la table `groupe_avoir_etape`
--
ALTER TABLE `groupe_avoir_etape`
  ADD CONSTRAINT `GROUPE_AVOIR_ETAPE_Etape_FK` FOREIGN KEY (`id_etape`) REFERENCES `etape` (`id_etape`),
  ADD CONSTRAINT `GROUPE_AVOIR_ETAPE_Groupe0_FK` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id_groupe`);

--
-- Contraintes pour la table `objectif`
--
ALTER TABLE `objectif`
  ADD CONSTRAINT `Objectif_Projet_FK` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id_projet`);

--
-- Contraintes pour la table `participant_avoir_etape`
--
ALTER TABLE `participant_avoir_etape`
  ADD CONSTRAINT `PARTICIPANT_AVOIR_ETAPE_Etape_FK` FOREIGN KEY (`id_etape`) REFERENCES `etape` (`id_etape`),
  ADD CONSTRAINT `PARTICIPANT_AVOIR_ETAPE_Participant0_FK` FOREIGN KEY (`id_part`) REFERENCES `participant` (`id_part`);

--
-- Contraintes pour la table `participant_avoir_groupe`
--
ALTER TABLE `participant_avoir_groupe`
  ADD CONSTRAINT `PARTICIPANT_AVOIR_GROUPE_Groupe0_FK` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id_groupe`),
  ADD CONSTRAINT `PARTICIPANT_AVOIR_GROUPE_Participant_FK` FOREIGN KEY (`id_part`) REFERENCES `participant` (`id_part`);

--
-- Contraintes pour la table `projet_avoir_groupe`
--
ALTER TABLE `projet_avoir_groupe`
  ADD CONSTRAINT `PROJET_AVOIR_GROUPE_Groupe_FK` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id_groupe`),
  ADD CONSTRAINT `PROJET_AVOIR_GROUPE_Projet0_FK` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id_projet`);

--
-- Contraintes pour la table `projet_avoir_participant`
--
ALTER TABLE `projet_avoir_participant`
  ADD CONSTRAINT `PROJET_AVOIR_PARTICIPANT_Participant0_FK` FOREIGN KEY (`id_part`) REFERENCES `participant` (`id_part`),
  ADD CONSTRAINT `PROJET_AVOIR_PARTICIPANT_Projet_FK` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id_projet`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
