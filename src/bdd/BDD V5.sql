SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


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

INSERT INTO `alumni` (`ref_user`, `cv`, `annee_promo`, `poste`, `ref_fiche_entreprise`) VALUES
(6, NULL, '2012', NULL, NULL);

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `ref_user` int NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `annee_promo` varchar(50) NOT NULL,
  `ref_formation` int NOT NULL,
  KEY `fk_utilisateur_etudiant` (`ref_user`),
  KEY `fk_formation_etudiant` (`ref_formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `etudiant` (`ref_user`, `cv`, `annee_promo`, `ref_formation`) VALUES
(8, NULL, '2024', 2);

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `type_eve` varchar(50) NOT NULL,
  `lieu_eve` varchar(50) NOT NULL,
  `element_eve` varchar(50) NOT NULL,
  `nb_place` int NOT NULL,
  `desc_eve` varchar(50) NOT NULL,
  `titre_eve` varchar(50) NOT NULL,
  PRIMARY KEY (`id_evenement`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `evenement` (`id_evenement`, `type_eve`, `lieu_eve`, `element_eve`, `nb_place`, `desc_eve`, `titre_eve`) VALUES
(1, 'Sièste', '1600 Pennsylvania Ave NW, Washington, DC 20500, Ét', 'Aucun', 100, 'Petit dodo sur la pelouse', 'Bla bla bla');

DROP TABLE IF EXISTS `fiche_entreprise`;
CREATE TABLE IF NOT EXISTS `fiche_entreprise` (
  `id_fiche_entreprise` int NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(50) NOT NULL,
  `adresse_entreprise` varchar(50) NOT NULL,
  `adresse_web` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `id_formation` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `formation` (`id_formation`, `nom`) VALUES
(1, 'L1'),
(2, '3° Pro');

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE IF NOT EXISTS `gestionnaire` (
  `ref_user` int NOT NULL,
  KEY `fk_utilisateur_gestionaire` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `mdp_reset`;
CREATE TABLE IF NOT EXISTS `mdp_reset` (
  `id_mdp_reset` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `expire_a` datetime NOT NULL,
  `ref_user` int NOT NULL,
  PRIMARY KEY (`id_mdp_reset`),
  KEY `ref_user` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

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

DROP TABLE IF EXISTS `partenaire`;
CREATE TABLE IF NOT EXISTS `partenaire` (
  `ref_user` int NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `poste` varchar(50) NOT NULL,
  `ref_fiche_entreprise` int DEFAULT NULL,
  KEY `fk_utilisateur_partenaire` (`ref_user`),
  KEY `fk_fiche_entreprise_partenaire` (`ref_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `partenaire` (`ref_user`, `cv`, `poste`, `ref_fiche_entreprise`) VALUES
(7, NULL, 'Commercial', NULL);

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

DROP TABLE IF EXISTS `postuler`;
CREATE TABLE IF NOT EXISTS `postuler` (
  `ref_user` int NOT NULL,
  `ref_offre` int NOT NULL,
  `motivation` varchar(1500) NOT NULL,
  `est_accepte` tinyint(1) DEFAULT NULL,
  KEY `fk_utilisateur_postuler` (`ref_user`),
  KEY `fk_offre_postuler` (`ref_offre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `professeur`;
CREATE TABLE IF NOT EXISTS `professeur` (
  `ref_user` int NOT NULL,
  `specialite` varchar(50) NOT NULL,
  KEY `fk_utilisateur_professeur` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `professeur` (`ref_user`, `specialite`) VALUES
(3, 'Informatiques'),
(5, 'Anglais');

DROP TABLE IF EXISTS `professeur_formation`;
CREATE TABLE IF NOT EXISTS `professeur_formation` (
  `ref_user` int NOT NULL,
  `ref_formation` int NOT NULL,
  KEY `fk_professeur_formation_formation` (`ref_formation`),
  KEY `fk_professeur_formation_professeur` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
  `id_reponse` int NOT NULL AUTO_INCREMENT,
  `contenu_` varchar(3000) NOT NULL,
  `date_heure` datetime NOT NULL,
  `ref_user` int NOT NULL,
  `ref_post` int NOT NULL,
  PRIMARY KEY (`id_reponse`),
  KEY `fk_utilisateur_reponse` (`ref_user`),
  KEY `fk_post_reponse` (`ref_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `user_evenement`;
CREATE TABLE IF NOT EXISTS `user_evenement` (
  `ref_user` int NOT NULL,
  `ref_evenement` int NOT NULL,
  `est_superviseur` tinyint(1) DEFAULT NULL,
  KEY `fk_user_evenement_evenement` (`ref_evenement`),
  KEY `fk_user_evenement_utilisateur` (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `email`, `mdp`, `role`, `ref_validateur`) VALUES
(1, 'testnom', 'testprenom', 'test@adresse', '$2y$10$NiAZppMM1dU5w8EuZE32Led7.cLrSi10YM0M.1YWdbZDTr.OBmTP2', 'Étudiant', NULL),
(3, 'd&amp;#039;Erceville', 'Augustinvjlsjdklfjskl', 'a.erceville2000@gmail.com', '$2y$10$OhF/GqyNgq5Wy53gAtzmuOE6jHVq2Fj4GoJp3bm7BmVKludGKlFXa', 'Gestionnaire', NULL),
(5, 'Boivin', 'Felicienne', 'FelicienneBoivin@armyspy.com', '$2y$10$BERl52YY3srwYYF3CY0r4uSW8Ru9Bslrd1LbmQwTIqRTZmrfcEJRK', 'Professeur', NULL),
(6, 'Séguin', 'Mallory', 'MallorySeguin@dayrep.com', '$2y$10$vwDWyjuWoCwbIrQfh8xb4u9rccbZm.fygk86KJiNcAaN6EbRY63c.', 'Alumni', NULL),
(7, 'Huppé', 'Harcourt', 'HarcourtHuppe@dayrep.com', '$2y$10$C9s5xIphbCmV.9Ie/JZjMej86LYh.Lz5OUwl9aSu4.oFm8AlsmCgG', 'Partenaire', NULL),
(8, 'Lagrognasse', 'Capucine', 'CapucinePoissonnier@armyspy.com', '$2y$10$2GSLQkiFGvrQPp2GU84Jv.zcb22Zn/c2d5CmMGl1XG6sgBkmZ65Vu', 'Étudiant', NULL);


ALTER TABLE `alumni`
  ADD CONSTRAINT `fk_fiche_entreprise_alumni` FOREIGN KEY (`ref_fiche_entreprise`) REFERENCES `fiche_entreprise` (`id_fiche_entreprise`),
  ADD CONSTRAINT `fk_utilisateur_alumni` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `etudiant`
  ADD CONSTRAINT `fk_formation_etudiant` FOREIGN KEY (`ref_formation`) REFERENCES `formation` (`id_formation`),
  ADD CONSTRAINT `fk_utilisateur_etudiant` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `gestionnaire`
  ADD CONSTRAINT `fk_utilisateur_gestionaire` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `mdp_reset`
  ADD CONSTRAINT `mdp_reset_ibfk_1` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `offre`
  ADD CONSTRAINT `fk_fiche_entreprise_offre` FOREIGN KEY (`ref_fiche`) REFERENCES `fiche_entreprise` (`id_fiche_entreprise`);

ALTER TABLE `partenaire`
  ADD CONSTRAINT `fk_fiche_entreprise_partenaire` FOREIGN KEY (`ref_fiche_entreprise`) REFERENCES `fiche_entreprise` (`id_fiche_entreprise`),
  ADD CONSTRAINT `fk_utilisateur_partenaire` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `postuler`
  ADD CONSTRAINT `fk_offre_postuler` FOREIGN KEY (`ref_offre`) REFERENCES `offre` (`id_offre`),
  ADD CONSTRAINT `fk_utilisateur_postuler` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `professeur`
  ADD CONSTRAINT `fk_utilisateur_professeur` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `professeur_formation`
  ADD CONSTRAINT `fk_professeur_formation_formation` FOREIGN KEY (`ref_formation`) REFERENCES `formation` (`id_formation`),
  ADD CONSTRAINT `fk_professeur_formation_professeur` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `reponse`
  ADD CONSTRAINT `reponse_ibfk_1` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reponse_ibfk_2` FOREIGN KEY (`ref_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_evenement`
  ADD CONSTRAINT `fk_user_evenement_evenement` FOREIGN KEY (`ref_evenement`) REFERENCES `evenement` (`id_evenement`),
  ADD CONSTRAINT `fk_user_evenement_utilisateur` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
