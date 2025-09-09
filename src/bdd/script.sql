SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `lprs` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `lprs`;

DROP TABLE IF EXISTS `alumni`;
CREATE TABLE IF NOT EXISTS `alumni` (
                                        `ref_user` int NOT NULL,
                                        `cv` varchar(255) DEFAULT NULL,
                                        `annee_promo` varchar(50) DEFAULT NULL,
                                        PRIMARY KEY (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `alumni_fiche_entreprise`;
CREATE TABLE IF NOT EXISTS `alumni_fiche_entreprise` (
                                                         `ref_user` int DEFAULT NULL,
                                                         `ref_fiche_entreprise` int DEFAULT NULL,
                                                         KEY `ref_user` (`ref_user`),
                                                         KEY `ref_fiche_entreprise` (`ref_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
                                          `ref_user` int NOT NULL,
                                          `cv` varchar(255) DEFAULT NULL,
                                          `annee_promo` varchar(50) DEFAULT NULL,
                                          `ref_formation` int DEFAULT NULL,
                                          PRIMARY KEY (`ref_user`),
                                          KEY `ref_formation` (`ref_formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
                                           `id_evenement` int NOT NULL,
                                           `type_eve` varchar(50) DEFAULT NULL,
                                           `lieu_eve` varchar(50) DEFAULT NULL,
                                           `element_eve` varchar(50) DEFAULT NULL,
                                           `nb_place` int DEFAULT NULL,
                                           `desc_eve` varchar(50) DEFAULT NULL,
                                           `titre_eve` varchar(50) DEFAULT NULL,
                                           PRIMARY KEY (`id_evenement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `fiche_entreprise`;
CREATE TABLE IF NOT EXISTS `fiche_entreprise` (
                                                  `id_fiche_entreprise` int NOT NULL,
                                                  `nom_entreprise` varchar(50) DEFAULT NULL,
                                                  `adresse_entreprise` varchar(50) DEFAULT NULL,
                                                  PRIMARY KEY (`id_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
                                           `id_formation` int NOT NULL,
                                           `nom` varchar(50) DEFAULT NULL,
                                           PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE IF NOT EXISTS `gestionnaire` (
                                              `ref_user` int NOT NULL,
                                              PRIMARY KEY (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `offre`;
CREATE TABLE IF NOT EXISTS `offre` (
                                       `id_offre` int DEFAULT NULL,
                                       `titre` varchar(50) DEFAULT NULL,
                                       `description` varchar(50) DEFAULT NULL,
                                       `mission` varchar(50) DEFAULT NULL,
                                       `salaire` decimal(15,2) DEFAULT NULL,
                                       `type` varchar(50) DEFAULT NULL,
                                       `etat` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `offre_fiche_entrepise`;
CREATE TABLE IF NOT EXISTS `offre_fiche_entrepise` (
                                                       `ref_offre` int DEFAULT NULL,
                                                       `ref_fiche_entreprise` int DEFAULT NULL,
                                                       KEY `ref_offre` (`ref_offre`),
                                                       KEY `ref_fiche_entreprise` (`ref_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
                                      `id_post` int NOT NULL,
                                      `canal` varchar(50) DEFAULT NULL,
                                      `titre_post` varchar(50) DEFAULT NULL,
                                      `date_heure_post` datetime DEFAULT NULL,
                                      PRIMARY KEY (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `professeur`;
CREATE TABLE IF NOT EXISTS `professeur` (
                                            `ref_user` int NOT NULL,
                                            `specialite` varchar(50) DEFAULT NULL,
                                            PRIMARY KEY (`ref_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `professeur_formation`;
CREATE TABLE IF NOT EXISTS `professeur_formation` (
                                                      `ref_user` int DEFAULT NULL,
                                                      `ref_formation` int DEFAULT NULL,
                                                      KEY `ref_user` (`ref_user`),
                                                      KEY `ref_formation` (`ref_formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
                                         `id_reponse` int NOT NULL,
                                         `contenu_` varchar(50) DEFAULT NULL,
                                         `date_heure` datetime DEFAULT NULL,
                                         `ref_user` int DEFAULT NULL,
                                         `ref_post` int DEFAULT NULL,
                                         PRIMARY KEY (`id_reponse`),
                                         KEY `ref_user` (`ref_user`),
                                         KEY `ref_post` (`ref_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `user_evenement_gerer`;
CREATE TABLE IF NOT EXISTS `user_evenement_gerer` (
                                                      `ref_user` int DEFAULT NULL,
                                                      `ref_evenement` int DEFAULT NULL,
                                                      KEY `ref_user` (`ref_user`),
                                                      KEY `ref_evenement` (`ref_evenement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `user_evenement_participer`;
CREATE TABLE IF NOT EXISTS `user_evenement_participer` (
                                                           `ref_user` int DEFAULT NULL,
                                                           `ref_evenement` int DEFAULT NULL,
                                                           KEY `ref_user` (`ref_user`),
                                                           KEY `ref_evenement` (`ref_evenement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `user_offre`;
CREATE TABLE IF NOT EXISTS `user_offre` (
                                            `ref_user` int DEFAULT NULL,
                                            `ref_offre` int DEFAULT NULL,
                                            KEY `ref_user` (`ref_user`),
                                            KEY `ref_offre` (`ref_offre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `user_post`;
CREATE TABLE IF NOT EXISTS `user_post` (
                                           `ref_user` int DEFAULT NULL,
                                           `ref_post` int DEFAULT NULL,
                                           KEY `ref_user` (`ref_user`),
                                           KEY `ref_post` (`ref_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
                                             `id_user` int NOT NULL,
                                             `nom` varchar(50) DEFAULT NULL,
                                             `prenom` varchar(50) DEFAULT NULL,
                                             `email` varchar(50) DEFAULT NULL,
                                             `mdp` varchar(255) DEFAULT NULL,
                                             `est_valide` tinyint(1) DEFAULT NULL,
                                             PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
