-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 02 déc. 2024 à 14:23
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `arcadia`
--

-- --------------------------------------------------------

--
-- Structure de la table `animal`
--

DROP TABLE IF EXISTS `animal`;
CREATE TABLE IF NOT EXISTS `animal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `race` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `habitat_id` int DEFAULT NULL,
  `etat` text COLLATE utf8mb4_general_ci,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `habitat_id` (`habitat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `animal`
--

INSERT INTO `animal` (`id`, `prenom`, `race`, `habitat_id`, `etat`, `image_url`) VALUES
(1, 'Simba', 'Lion', 2, 'En bonne santé', 'images/lion.jpg'),
(2, 'Melman', 'Girafe', 2, 'Fatigué mais stable', 'images/girafe.jpg'),
(3, 'Kaa', 'Serpent', 1, 'Vivace', 'images/serpent.jpeg'),
(4, 'Croco', 'Crocodile', 3, 'En observation vétérinaire', 'images/crocodile.jpg'),
(5, 'Rose', 'Flamant rose', 3, 'En bonne santé', 'images/flamant.jpg'),
(7, 'chien', 'berger', 2, 'bonne santer', 'images/images.jpeg'),
(8, 'le chat', 'chat', 1, 'bonne santer', '');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `utilisateur_id` int DEFAULT NULL,
  `contenu` text NOT NULL,
  `statut` enum('en_attente','valide','invalide') DEFAULT 'en_attente',
  `date` date NOT NULL,
  `valide` tinyint(1) DEFAULT '0',
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `pseudo`, `nom`, `email`, `utilisateur_id`, `contenu`, `statut`, `date`, `valide`, `commentaire`) VALUES
(1, '', 'yves', 'yves@gmail.com', NULL, '', 'en_attente', '0000-00-00', 1, 'tres beau'),
(2, '', 'yves1', 'yves1@gmail.com', NULL, '', 'en_attente', '0000-00-00', 1, 'tres tres bien '),
(3, '', 'milo', 'milo@gmail.com', NULL, '', 'en_attente', '0000-00-00', 1, 'tres jolis');

-- --------------------------------------------------------

--
-- Structure de la table `consommation`
--

DROP TABLE IF EXISTS `consommation`;
CREATE TABLE IF NOT EXISTS `consommation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `animal_id` int NOT NULL,
  `nourriture` varchar(100) NOT NULL,
  `grammage` int NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `animal_id` (`animal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `email`, `message`, `date`) VALUES
(1, 'yvesnet9@gmail.com', 'salut', '2024-11-16 17:24:02'),
(2, 'ami.toutenun@gmail.com', 'salut cava', '2024-11-16 17:25:03');

-- --------------------------------------------------------

--
-- Structure de la table `habitat`
--

DROP TABLE IF EXISTS `habitat`;
CREATE TABLE IF NOT EXISTS `habitat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `habitat`
--

INSERT INTO `habitat` (`id`, `nom`, `description`, `image_url`) VALUES
(1, 'Jungle', 'Découvrez nos animaux exotiques dans leur habitat naturel.', 'images/jungle.jpeg'),
(2, 'Savane', 'Habitat naturel des lions, girafes, et éléphants.', 'images/savane.jpg'),
(3, 'Marais', 'Lieu de vie des crocodiles, flamants roses, et tortues.', 'images/marais.jpg'),
(6, 'la faune', 'faune avec animaux exotique ', '');

-- --------------------------------------------------------

--
-- Structure de la table `habitat_comments`
--

DROP TABLE IF EXISTS `habitat_comments`;
CREATE TABLE IF NOT EXISTS `habitat_comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `habitat_id` int NOT NULL,
  `commentaire_habitat` text NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `habitat_id` (`habitat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `habitat_comments`
--

INSERT INTO `habitat_comments` (`id`, `habitat_id`, `commentaire_habitat`, `date`) VALUES
(1, 2, 'erer', '2024-11-29');

-- --------------------------------------------------------

--
-- Structure de la table `interactions`
--

DROP TABLE IF EXISTS `interactions`;
CREATE TABLE IF NOT EXISTS `interactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('animal','habitat') NOT NULL,
  `cible_id` int NOT NULL,
  `utilisateur_id` int DEFAULT NULL,
  `date_interaction` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nourriture`
--

DROP TABLE IF EXISTS `nourriture`;
CREATE TABLE IF NOT EXISTS `nourriture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `animal_id` int NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `quantite` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `animal_id` (`animal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `nourriture`
--

INSERT INTO `nourriture` (`id`, `animal_id`, `date`, `heure`, `quantite`) VALUES
(1, 7, '2024-11-30', '15:04:00', 122.00),
(2, 7, '2024-11-30', '15:04:00', 122.00),
(3, 8, '2024-11-29', '23:27:00', 1.00);

-- --------------------------------------------------------

--
-- Structure de la table `rapport_veterinaire`
--

DROP TABLE IF EXISTS `rapport_veterinaire`;
CREATE TABLE IF NOT EXISTS `rapport_veterinaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `animal_id` int NOT NULL,
  `date` date NOT NULL,
  `etat` varchar(255) NOT NULL,
  `nourriture` varchar(255) DEFAULT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `animal_id` (`animal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `rapport_veterinaire`
--

INSERT INTO `rapport_veterinaire` (`id`, `animal_id`, `date`, `etat`, `nourriture`, `details`, `date_creation`) VALUES
(1, 2, '2024-11-29', 'bonne santer ', 'dd', NULL, '2024-11-29 18:56:54'),
(2, 2, '2024-11-29', 'bonne santer ', 'dd', NULL, '2024-11-29 18:56:54'),
(3, 2, '2024-11-29', 'bonne santer ', 'dd', NULL, '2024-11-29 18:56:54'),
(4, 2, '2024-11-29', 'bonne santer ', 'dd', '', '2024-11-29 18:56:54'),
(5, 2, '2024-11-29', 'bonne santer ', 'dd', '', '2024-11-29 18:56:54'),
(6, 2, '2024-11-29', 'bonne santer ', 'dd', '', '2024-11-29 18:56:54'),
(7, 2, '2024-11-29', 'bonne santer ', 'dd', '', '2024-11-29 18:56:54'),
(8, 1, '2024-11-29', 'ccc', 'ccc', 'ccc', '2024-11-29 18:56:54'),
(9, 1, '2024-11-29', 'ccc', 'ccc', 'ccc', '2024-11-29 18:56:54'),
(10, 2, '2024-11-29', 'bone', 'grain', 'ccc', '2024-11-29 18:56:54'),
(11, 2, '2024-11-29', 'bo,', 'ggg', 'gggg', '2024-11-29 18:56:54');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `nom`, `description`) VALUES
(1, 'teste', 'tesste');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `role` enum('administrateur','employe','veterinaire') NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `username`, `role`, `password`) VALUES
(6, 'employe1', 'employe', 'arcadia'),
(4, 'admin@admin.com', 'administrateur', 'admin'),
(5, 'veterinaire@zoo.com', 'veterinaire', 'vet');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `animal_ibfk_1` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
