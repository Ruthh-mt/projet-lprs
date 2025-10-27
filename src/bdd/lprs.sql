-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 27 oct. 2025 à 20:28
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lprs`
--

-- --------------------------------------------------------

--
-- Structure de la table `alumni`
--

DROP TABLE IF EXISTS `alumni`;
CREATE TABLE IF NOT EXISTS `alumni` (
  `ref_user` int NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `annee_promo` varchar(50) NOT NULL,
  `poste` varchar(50) DEFAULT NULL,
  `ref_fiche_entreprise` int DEFAULT NULL,
  KEY `fk_utilisateur_alumni` (`ref_user`),
  KEY `fk_fiche_entreprise_alumni` (`ref_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `ref_user` int NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `annee_promo` varchar(50) NOT NULL,
  `ref_formation` int NOT NULL,
  KEY `fk_utilisateur_etudiant` (`ref_user`),
  KEY `fk_formation_etudiant` (`ref_formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `titre_eve` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type_eve` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lieu_eve` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `element_eve` varchar(50) NOT NULL,
  `nb_place` int NOT NULL,
  `desc_eve` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_evenement`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- --------------------------------------------------------

--
-- Structure de la table `fiche_entreprise`
--

DROP TABLE IF EXISTS `fiche_entreprise`;
CREATE TABLE IF NOT EXISTS `fiche_entreprise` (
  `id_fiche_entreprise` int NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(50) NOT NULL,
  `adresse_entreprise` varchar(50) NOT NULL,
  `adresse_web` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `id_formation` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `formation`
--


-- --------------------------------------------------------

--
-- Structure de la table `gestionnaire`
--

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE IF NOT EXISTS `gestionnaire` (
  `ref_user` int NOT NULL,
  KEY `fk_utilisateur_gestionaire` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mdp_reset`
--

DROP TABLE IF EXISTS `mdp_reset`;
CREATE TABLE IF NOT EXISTS `mdp_reset` (
  `id_mdp_reset` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `expire_a` datetime NOT NULL,
  `ref_user` int NOT NULL,
  PRIMARY KEY (`id_mdp_reset`),
  KEY `ref_user` (`ref_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `mdp_reset`
--


-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

DROP TABLE IF EXISTS `offre`;
CREATE TABLE IF NOT EXISTS `offre` (
  `id_offre` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `mission` varchar(50) NOT NULL,
  `salaire` decimal(15,2) NOT NULL,
  `type` varchar(50) NOT NULL,
  `etat` varchar(50) DEFAULT NULL,
  `ref_fiche` int NOT NULL,
  PRIMARY KEY (`id_offre`),
  KEY `fk_fiche_entreprise_offre` (`ref_fiche`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `partenaire`
--

DROP TABLE IF EXISTS `partenaire`;
CREATE TABLE IF NOT EXISTS `partenaire` (
  `ref_user` int NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `poste` varchar(50) NOT NULL,
  `ref_fiche_entreprise` int DEFAULT NULL,
  KEY `fk_utilisateur_partenaire` (`ref_user`),
  KEY `fk_fiche_entreprise_partenaire` (`ref_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id_post` int NOT NULL AUTO_INCREMENT,
  `canal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `titre_post` varchar(50) NOT NULL,
  `contenu_post` varchar(3000) NOT NULL,
  `date_heure_post` datetime NOT NULL,
  `ref_user` int NOT NULL,
  PRIMARY KEY (`id_post`),
  KEY `fk_utilisateur_post` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `postuler`
--

DROP TABLE IF EXISTS `postuler`;
CREATE TABLE IF NOT EXISTS `postuler` (
  `ref_user` int NOT NULL,
  `ref_offre` int NOT NULL,
  `motivation` varchar(1500) NOT NULL,
  `est_accepte` tinyint(1) DEFAULT NULL,
  KEY `fk_utilisateur_postuler` (`ref_user`),
  KEY `fk_offre_postuler` (`ref_offre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

DROP TABLE IF EXISTS `professeur`;
CREATE TABLE IF NOT EXISTS `professeur` (
  `ref_user` int NOT NULL,
  `specialite` varchar(50) NOT NULL,
  KEY `fk_utilisateur_professeur` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `professeur`
--

-- --------------------------------------------------------

--
-- Structure de la table `professeur_formation`
--

DROP TABLE IF EXISTS `professeur_formation`;
CREATE TABLE IF NOT EXISTS `professeur_formation` (
  `ref_user` int NOT NULL,
  `ref_formation` int NOT NULL,
  KEY `fk_professeur_formation_formation` (`ref_formation`),
  KEY `fk_professeur_formation_professeur` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
  `id_reponse` int NOT NULL AUTO_INCREMENT,
  `contenu_` varchar(50) NOT NULL,
  `date_heure` datetime NOT NULL,
  `ref_user` int NOT NULL,
  `ref_post` int NOT NULL,
  PRIMARY KEY (`id_reponse`),
  KEY `fk_utilisateur_reponse` (`ref_user`),
  KEY `fk_post_reponse` (`ref_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_evenement`
--

DROP TABLE IF EXISTS `user_evenement`;
CREATE TABLE IF NOT EXISTS `user_evenement` (
  `ref_user` int NOT NULL,
  `ref_evenement` int NOT NULL,
  `est_superviseur` tinyint(1) DEFAULT NULL,
  KEY `fk_user_evenement_evenement` (`ref_evenement`),
  KEY `fk_user_evenement_utilisateur` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_evenement`
--


-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `ref_validateur` int DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `fk_gestionaire_utilisateur` (`ref_validateur`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `alumni`
--
ALTER TABLE `alumni`
  ADD CONSTRAINT `fk_fiche_entreprise_alumni` FOREIGN KEY (`ref_fiche_entreprise`) REFERENCES `fiche_entreprise` (`id_fiche_entreprise`),
  ADD CONSTRAINT `fk_utilisateur_alumni` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `fk_formation_etudiant` FOREIGN KEY (`ref_formation`) REFERENCES `formation` (`id_formation`),
  ADD CONSTRAINT `fk_utilisateur_etudiant` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `gestionnaire`
--
ALTER TABLE `gestionnaire`
  ADD CONSTRAINT `fk_utilisateur_gestionaire` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `mdp_reset`
--
ALTER TABLE `mdp_reset`
  ADD CONSTRAINT `mdp_reset_ibfk_1` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `offre`
--
ALTER TABLE `offre`
  ADD CONSTRAINT `fk_fiche_entreprise_offre` FOREIGN KEY (`ref_fiche`) REFERENCES `fiche_entreprise` (`id_fiche_entreprise`);

--
-- Contraintes pour la table `partenaire`
--
ALTER TABLE `partenaire`
  ADD CONSTRAINT `fk_fiche_entreprise_partenaire` FOREIGN KEY (`ref_fiche_entreprise`) REFERENCES `fiche_entreprise` (`id_fiche_entreprise`),
  ADD CONSTRAINT `fk_utilisateur_partenaire` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_utilisateur_post` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `postuler`
--
ALTER TABLE `postuler`
  ADD CONSTRAINT `fk_offre_postuler` FOREIGN KEY (`ref_offre`) REFERENCES `offre` (`id_offre`),
  ADD CONSTRAINT `fk_utilisateur_postuler` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD CONSTRAINT `fk_utilisateur_professeur` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `professeur_formation`
--
ALTER TABLE `professeur_formation`
  ADD CONSTRAINT `fk_professeur_formation_formation` FOREIGN KEY (`ref_formation`) REFERENCES `formation` (`id_formation`),
  ADD CONSTRAINT `fk_professeur_formation_professeur` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `fk_post_reponse` FOREIGN KEY (`ref_post`) REFERENCES `post` (`id_post`),
  ADD CONSTRAINT `fk_utilisateur_reponse` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `user_evenement`
--
ALTER TABLE `user_evenement`
  ADD CONSTRAINT `fk_user_evenement_evenement` FOREIGN KEY (`ref_evenement`) REFERENCES `evenement` (`id_evenement`),
  ADD CONSTRAINT `fk_user_evenement_utilisateur` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
