
CREATE DATABASE IF NOT EXISTS `lprs` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `lprs`;

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
                                             `id_user` int NOT NULL,
                                             `nom` varchar(50) NOT NULL,
                                             `prenom` varchar(50) NOT NULL,
                                             `email` varchar(50) NOT NULL,
                                             `mdp` varchar(255) NOT NULL,
                                             `role` varchar(255) NOT NULL,
											 `ref_validateur` int DEFAULT NULL,
                                             PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `gestionnaire`;
CREATE TABLE IF NOT EXISTS `gestionnaire` (
                                              `ref_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `fiche_entreprise`;
CREATE TABLE IF NOT EXISTS `fiche_entreprise` (
                                                  `id_fiche_entreprise` int NOT NULL,
                                                  `nom_entreprise` varchar(50) NOT NULL,
                                                  `adresse_entreprise` varchar(50) NOT NULL,
												  `adresse_web` varchar(50) DEFAULT NULL,
                                                  PRIMARY KEY (`id_fiche_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `alumni`;
CREATE TABLE IF NOT EXISTS `alumni` (
                                        `ref_user` int NOT NULL,
                                        `cv` varchar(255) DEFAULT NULL,
                                        `annee_promo` varchar(50) NOT NULL,
                                        `poste` varchar(50) DEFAULT NULL,
                                        `ref_fiche_entreprise` int NULL												
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `partenaire`;
CREATE TABLE IF NOT EXISTS `partenaire` (
                                        `ref_user` int NOT NULL,
                                        `cv` varchar(255) DEFAULT NULL,
                                        `poste` varchar(50) NOT NULL,
                                        `ref_fiche_entreprise` int NULL										
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
                                          `ref_user` int NOT NULL,
                                          `cv` varchar(255) DEFAULT NULL,
                                          `annee_promo` varchar(50) NOT NULL,
                                          `ref_formation` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `professeur`;
CREATE TABLE IF NOT EXISTS `professeur` (
                                            `ref_user` int NOT NULL,
                                            `specialite` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
                                           `id_evenement` int NOT NULL,
                                           `type_eve` varchar(50) NOT NULL,
                                           `lieu_eve` varchar(50) NOT NULL,
                                           `element_eve` varchar(50) NOT NULL,
                                           `nb_place` int NOT NULL,
                                           `desc_eve` varchar(50) NOT NULL,
                                           `titre_eve` varchar(50) NOT NULL,
                                           PRIMARY KEY (`id_evenement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
                                           `id_formation` int NOT NULL,
                                           `nom` varchar(50) NOT NULL,
                                           PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `offre`;
CREATE TABLE IF NOT EXISTS `offre` (
                                       `id_offre` int NOT NULL,
                                       `titre` varchar(50) NOT NULL,
                                       `description` varchar(50) NOT NULL,
                                       `mission` varchar(50) NOT NULL,
                                       `salaire` decimal(15,2) NOT NULL,
                                       `type` varchar(50) NOT NULL,
                                       `etat` varchar(50) DEFAULT NULL,
									   `ref_fiche` int NOT NULL,
                                       PRIMARY KEY (`id_offre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
                                      `id_post` int NOT NULL,
                                      `canal` varchar(50) NOT NULL,
                                      `titre_post` varchar(50) NOT NULL,
                                      `date_heure_post` datetime NOT NULL,
									  `ref_user` int NOT NULL,
                                      PRIMARY KEY (`id_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `professeur_formation`;
CREATE TABLE IF NOT EXISTS `professeur_formation` (
                                                      `ref_user` int NOT NULL,
                                                      `ref_formation` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE IF NOT EXISTS `reponse` (
                                         `id_reponse` int NOT NULL,
                                         `contenu_` varchar(50) NOT NULL,
                                         `date_heure` datetime NOT NULL,
                                         `ref_user` int NOT NULL,
                                         `ref_post` int NOT NULL,
                                         PRIMARY KEY (`id_reponse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `user_evenement`;
CREATE TABLE IF NOT EXISTS `user_evenement` (
                                                           `ref_user` int NOT NULL,
                                                           `ref_evenement` int NOT NULL,
														   `est_superviseur` BOOLEAN DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `postuler`;
CREATE TABLE IF NOT EXISTS `postuler` (
                                            `ref_user` int NOT NULL,
                                            `ref_offre` int NOT NULL,
											`motivation`  varchar(1500) NOT NULL,
											`est_accepte` BOOLEAN DEFAULT NULL											
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `mdp_reset`;
CREATE TABLE IF NOT EXISTS `mdp_reset` (
  `id_mdp_reset` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE latin1_bin NOT NULL,
  `expire_a` datetime NOT NULL,
  `ref_user` int NOT NULL,
  PRIMARY KEY (`id_mdp_reset`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

ALTER TABLE `gestionnaire`
  ADD CONSTRAINT `fk_utilisateur_gestionaire` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `utilisateur`
  ADD CONSTRAINT `fk_gestionaire_utilisateur` FOREIGN KEY (`ref_validateur`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `professeur`
  ADD CONSTRAINT `fk_utilisateur_professeur` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `etudiant`
  ADD CONSTRAINT `fk_utilisateur_etudiant` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`),
  ADD CONSTRAINT `fk_formation_etudiant` FOREIGN KEY (`ref_formation`) REFERENCES `formation` (`id_formation`);  

ALTER TABLE `partenaire`
  ADD CONSTRAINT `fk_utilisateur_partenaire` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`),
  ADD CONSTRAINT `fk_fiche_entreprise_partenaire` FOREIGN KEY (`ref_fiche_entreprise`) REFERENCES  `fiche_entreprise` (`id_fiche_entreprise`);

ALTER TABLE `alumni`
  ADD CONSTRAINT `fk_utilisateur_alumni` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`),
  ADD CONSTRAINT `fk_fiche_entreprise_alumni` FOREIGN KEY (`ref_fiche_entreprise`) REFERENCES  `fiche_entreprise` (`id_fiche_entreprise`);

ALTER TABLE `offre`
  ADD CONSTRAINT `fk_fiche_entreprise_offre` FOREIGN KEY (`ref_fiche`) REFERENCES  `fiche_entreprise` (`id_fiche_entreprise`);
  
ALTER TABLE `postuler`
  ADD CONSTRAINT `fk_utilisateur_postuler` FOREIGN KEY (`ref_user`) REFERENCES  `utilisateur` (`id_user`),
  ADD CONSTRAINT `fk_offre_postuler` FOREIGN KEY (`ref_offre`) REFERENCES  `offre` (`id_offre`);

ALTER TABLE `post`
  ADD CONSTRAINT `fk_utilisateur_post` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`);

ALTER TABLE `reponse`
  ADD CONSTRAINT `fk_utilisateur_reponse` FOREIGN KEY (`ref_user`) REFERENCES  `utilisateur` (`id_user`),
  ADD CONSTRAINT `fk_post_reponse` FOREIGN KEY (`ref_post`) REFERENCES  `post` (`id_post`);  

ALTER TABLE `professeur_formation`
  ADD CONSTRAINT `fk_professeur_formation_formation` FOREIGN KEY (`ref_formation`) REFERENCES  `formation` (`id_formation`),
  ADD CONSTRAINT `fk_professeur_formation_professeur` FOREIGN KEY (`ref_user`) REFERENCES   `utilisateur` (`id_user`);   

ALTER TABLE `user_evenement`
  ADD CONSTRAINT `fk_user_evenement_evenement` FOREIGN KEY (`ref_evenement`) REFERENCES  `evenement` (`id_evenement`),
  ADD CONSTRAINT `fk_user_evenement_utilisateur` FOREIGN KEY (`ref_user`) REFERENCES  `utilisateur` (`id_user`);  

