-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 03 mars 2023 à 09:33
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `annonceo`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

DROP TABLE IF EXISTS `annonce`;
CREATE TABLE IF NOT EXISTS `annonce` (
  `id_annonce` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description_courte` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description_longue` text COLLATE utf8mb4_general_ci NOT NULL,
  `prix` int NOT NULL,
  `photo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `pays` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ville` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `adresse` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `cp` int NOT NULL,
  `membre_id` int NOT NULL,
  `photo_id` int DEFAULT NULL,
  `categorie_id` int NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_annonce`),
  KEY `membre_id` (`membre_id`),
  KEY `photo_id` (`photo_id`),
  KEY `categorie_id` (`categorie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `motscles` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int NOT NULL AUTO_INCREMENT,
  `membre_id` int DEFAULT NULL,
  `annonce_id` int NOT NULL,
  `commentaire` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `membre_id` (`membre_id`),
  KEY `annonce_id` (`annonce_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mdp` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `civilite` enum('m','f') COLLATE utf8mb4_general_ci NOT NULL,
  `statut` int NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `id_note` int NOT NULL AUTO_INCREMENT,
  `membre_id1` int DEFAULT NULL,
  `membre_id2` int NOT NULL,
  `note` int NOT NULL,
  `avis` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_note`),
  KEY `membre_id1` (`membre_id1`),
  KEY `membre_id2` (`membre_id2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `id_photo` int NOT NULL AUTO_INCREMENT,
  `photo1` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `photo2` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `photo3` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `photo4` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `photo5` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_photo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD CONSTRAINT `annonce_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id_membre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `annonce_ibfk_2` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`id_photo`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `annonce_ibfk_3` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`membre_id`) REFERENCES `annonce` (`id_annonce`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`annonce_id`) REFERENCES `annonce` (`id_annonce`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`membre_id1`) REFERENCES `membre` (`id_membre`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`membre_id2`) REFERENCES `membre` (`id_membre`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
