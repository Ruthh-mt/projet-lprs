SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP DATABASE IF EXISTS `lprs`;
CREATE DATABASE IF NOT EXISTS `lprs`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE `lprs`;

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


INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `email`, `mdp`, `role`, `ref_validateur`) VALUES
                                                                                                     (9, 'Dupont', 'Marie', 'marie.dupont@email.com', '$2y$10$HASH_PROVISOIRE9', 'Étudiant', NULL),
                                                                                                     (10, 'Lefevre', 'Pierre', 'pierre.lefevre@email.com', '$2y$10$HASH_PROVISOIRE10', 'Alumni', NULL),
                                                                                                     (11, 'Martin', 'Sophie', 'sophie.martin@email.com', '$2y$10$HASH_PROVISOIRE11', 'Partenaire', NULL),
                                                                                                     (12, 'Dubois', 'Jean', 'jean.dubois@email.com', '$2y$10$HASH_PROVISOIRE12', 'Professeur', NULL),
                                                                                                     (13, 'Petit', 'Chloé', 'chloe.petit@email.com', '$2y$10$HASH_PROVISOIRE13', 'Gestionnaire', NULL),
                                                                                                     (14, 'Leroy', 'Marc', 'marc.leroy@email.com', '$2y$10$HASH_PROVISOIRE14', 'Étudiant', 3),
                                                                                                     (15, 'Moreau', 'Clara', 'clara.moreau@email.com', '$2y$10$HASH_PROVISOIRE15', 'Alumni', NULL);

INSERT INTO `formation` (`id_formation`, `nom`) VALUES
                                                    (3, 'Master 1 Info'),
                                                    (4, 'BTS SIO'),
                                                    (5, 'Licence Pro Web'),
                                                    (6, 'DUT GEII'),
                                                    (7, 'M2 Finance'),
                                                    (8, 'L3 Math'),
                                                    (9, 'M1 Droit'),
                                                    (10, 'L2 Économie');

INSERT INTO `fiche_entreprise` (`id_fiche_entreprise`, `nom_entreprise`, `adresse_entreprise`, `adresse_web`) VALUES
                                                                                                                  (1, 'Tech Solutions SARL', '15 Rue de la Tech, 75001 Paris', 'techsolutions.fr'),
                                                                                                                  (2, 'InnovFinance SA', '20 Bd Haussmann, 75009 Paris', 'innovfinance.com'),
                                                                                                                  (3, 'CyberSecur France', '8 Avenue des Champs, 69002 Lyon', 'cybersecure.fr'),
                                                                                                                  (4, 'GlobalSoft Corp', '5 Quai du Commerce, 33000 Bordeaux', 'globalsoft.com'),
                                                                                                                  (5, 'Marketing XYZ', '12 Rue de la Création, 13008 Marseille', 'marketingxyz.net'),
                                                                                                                  (6, 'Éditions Savoir', '3 Allée des Livres, 31000 Toulouse', 'editionssavoir.fr'),
                                                                                                                  (7, 'StartUp Agile', '40 Rue de l''Innovation, 44000 Nantes', 'startupagile.io'),
                                                                                                                  (8, 'BTP Construct', '2 Chemin des Chantiers, 59000 Lille', 'btpconstruct.fr'),
                                                                                                                  (9, 'Restauration Rapide', '9 Place de la Gare, 67000 Strasbourg', 'rapideresto.fr'),
                                                                                                                  (10, 'Consulting Pro', '70 Avenue des Affaires, 75017 Paris', 'consultingpro.com');

INSERT INTO `offre` (`id_offre`, `titre`, `description`, `mission`, `salaire`, `type`, `etat`, `ref_fiche`) VALUES
                                                                                                                (1, 'Développeur Full-Stack Junior', 'Développement d''une nouvelle application web', 'Implémenter de nouvelles fonctionnalités', 35000.00, 'CDI', 'Publiée', 1),
                                                                                                                (2, 'Stage Assistant Contrôleur de Gestion', 'Analyse et suivi budgétaire', 'Préparer les reportings mensuels', 800.00, 'Stage', 'Publiée', 2),
                                                                                                                (3, 'Alternance Ingénieur Sécurité', 'Participer à la sécurisation des SI', 'Veille sur les vulnérabilités', 1200.00, 'Alternance', 'Publiée', 3),
                                                                                                                (4, 'CDD Chef de Projet IT', 'Gestion d''un projet de migration Cloud', 'Coordonner les équipes techniques', 45000.00, 'CDD', 'Publiée', 4),
                                                                                                                (5, 'Stage Marketing Digital', 'Création de contenu pour les réseaux sociaux', 'Augmenter la visibilité de l''entreprise', 600.00, 'Stage', 'Archivée', 5),
                                                                                                                (6, 'CDI Commercial Junior', 'Vente de solutions logicielles', 'Acquérir de nouveaux clients', 30000.00, 'CDI', 'Publiée', 1),
                                                                                                                (7, 'Alternance UX/UI Designer', 'Concevoir l''expérience utilisateur', 'Réaliser des wireframes et prototypes', 1000.00, 'Alternance', 'Publiée', 7),
                                                                                                                (8, 'Stage RH - Recrutement', 'Assister le service recrutement', 'Trier les CV et planifier les entretiens', 750.00, 'Stage', 'Publiée', 2),
                                                                                                                (9, 'CDI Data Scientist Confirmé', 'Modélisation et analyse de grandes données', 'Développer des modèles prédictifs', 55000.00, 'CDI', 'Publiée', 4),
                                                                                                                (10, 'Stage Comptable', 'Aide à la gestion des écritures comptables', 'Saisir les factures et préparer les bilans', 550.00, 'Stage', 'Publiée', 10);

INSERT INTO `post` (`id_post`, `canal`, `titre_post`, `contenu_post`, `date_heure_post`, `ref_user`) VALUES
                                                                                                         (13, 'Carrières', 'Conseils pour entretien d''embauche', 'Quel est votre meilleur conseil pour réussir un entretien pour un poste de développeur junior ?', '2025-10-29 15:30:00', 9),
                                                                                                         (14, 'Réseau', 'Recherche partenaire projet', 'Je cherche un partenaire pour un projet de fin d''études en réalité augmentée. Quelqu''un intéressé ?', '2025-10-30 08:00:00', 14),
                                                                                                         (15, 'Actualités Générales', 'Éthique de la robotique', 'Quelles sont les implications éthiques à long terme de l''intégration des robots dans les services publics ?', '2025-10-30 11:45:00', 12),
                                                                                                         (16, 'Développement Web', 'Framework JS de l''année', 'Selon vous, quel framework JavaScript dominera en 2026 et pourquoi ?', '2025-10-30 16:20:00', 10),
                                                                                                         (17, 'Événements', 'Retour sur le Tech Summit', 'Quelqu''un a assisté au Tech Summit ? Quel a été le moment fort de la conférence pour vous ?', '2025-10-31 09:10:00', 11),
                                                                                                         (18, 'Formation', 'Avis sur la formation "M2 Finance"', 'Des anciens ou actuels étudiants du M2 Finance pour partager leur expérience ? Contenu, débouchés ?', '2025-10-31 14:00:00', 7),
                                                                                                         (19, 'Carrières', 'Télétravail : avantages et inconvénients', 'L''entreprise que j''ai rejointe propose 100% de télétravail. Quels sont vos retours d''expérience ?', '2025-11-01 10:00:00', 15),
                                                                                                         (20, 'Réseau', 'Besoin d''aide en LaTeX', 'Je galère avec la mise en page de mon mémoire en LaTeX. Un pro de la typographie dans le coin ?', '2025-11-01 14:30:00', 5);

INSERT INTO `alumni` (`ref_user`, `cv`, `annee_promo`, `poste`, `ref_fiche_entreprise`) VALUES
                                                                                            (10, 'cv_pierre_lefevre.pdf', '2019', 'Ingénieur Logiciel Senior', 4),
                                                                                            (15, NULL, '2017', 'Chef de Projet IT', 1),
                                                                                            (1, 'cv_testnom.pdf', '2020', 'Consultant Technique', 10),
                                                                                            (7, NULL, '2015', 'Directeur Marketing', 5),
                                                                                            (8, 'cv_capucine_lagrognasse.pdf', '2018', 'Développeur Mobile', 7),
                                                                                            (9, NULL, '2016', 'Data Scientist', 4),
                                                                                            (11, 'cv_sophie_martin.pdf', '2014', 'Architecte Cloud', 3),
                                                                                            (12, NULL, '2013', 'Responsable RH', 2),
                                                                                            (13, 'cv_chloe_petit.pdf', '2011', 'Expert Comptable', 9);

INSERT INTO `etudiant` (`ref_user`, `cv`, `annee_promo`, `ref_formation`) VALUES
                                                                              (9, 'cv_marie_dupont.pdf', '2025', 3),
                                                                              (14, NULL, '2024', 4),
                                                                              (1, 'cv_test.pdf', '2026', 5),
                                                                              (7, NULL, '2024', 6),
                                                                              (6, 'cv_mallory.pdf', '2025', 7),
                                                                              (5, NULL, '2024', 8),
                                                                              (3, 'cv_augustin.pdf', '2026', 9),
                                                                              (11, NULL, '2025', 10),
                                                                              (12, 'cv_jean_dubois.pdf', '2024', 1);

INSERT INTO `partenaire` (`ref_user`, `cv`, `poste`, `ref_fiche_entreprise`) VALUES
                                                                                 (11, 'cv_sophie_martin.pdf', 'Responsable Recrutement', 2),
                                                                                 (1, NULL, 'Chargé de Mission RH', 8),
                                                                                 (3, 'cv_augustin.pdf', 'Directeur de la Stratégie', 1),
                                                                                 (5, NULL, 'Expert en Formation', 6),
                                                                                 (6, 'cv_mallory.pdf', 'Commercial Senior', 5),
                                                                                 (8, NULL, 'Responsable Pédagogique', 3),
                                                                                 (9, 'cv_marie_dupont.pdf', 'Consultant Associé', 10),
                                                                                 (10, NULL, 'Chef de Produit', 4),
                                                                                 (12, 'cv_jean_dubois.pdf', 'Délégué Commercial', 7);

INSERT INTO `postuler` (`ref_user`, `ref_offre`, `motivation`, `est_accepte`) VALUES
                                                                                  (8, 1, 'Très motivée par le développement Full-Stack, mon stage de 3e pro m''a permis d''acquérir les bases.', 1),
                                                                                  (1, 2, 'Mes compétences en analyse financière et mon aisance avec Excel seront un atout pour ce stage en Contrôle de Gestion.', 0),
                                                                                  (9, 3, 'Passionnée par la cybersécurité, je souhaite m''investir pleinement dans cette alternance formatrice.', NULL),
                                                                                  (14, 4, 'Fort intérêt pour la gestion de projet et l''environnement Cloud. J''ai déjà suivi des MOOCs sur le sujet.', NULL),
                                                                                  (5, 5, 'Créative et à l''aise avec les réseaux sociaux, je suis convaincue de pouvoir dynamiser votre communication.', 1),
                                                                                  (8, 6, 'Bien que ma formation soit technique, le commercial m''attire. J''ai un très bon relationnel.', 0),
                                                                                  (1, 7, 'Mon portfolio témoigne de ma capacité à concevoir des interfaces utilisateur intuitives et modernes.', NULL),
                                                                                  (9, 8, 'Très organisée et rigoureuse, j''aimerais découvrir le processus de recrutement au sein d''une grande structure.', NULL),
                                                                                  (14, 9, 'Mon mémoire de Master portait sur la modélisation prédictive des séries temporelles. J''ai hâte d''appliquer ces connaissances.', 1),
                                                                                  (3, 10, 'J''ai déjà effectué un stage en cabinet comptable, je suis donc autonome sur la saisie des opérations courantes.', NULL);


INSERT INTO `professeur` (`ref_user`, `specialite`) VALUES
                                                        (12, 'Mathématiques'),
                                                        (1, 'Réseaux et Systèmes'),
                                                        (6, 'Finance d''Entreprise'),
                                                        (7, 'Droit des Affaires'),
                                                        (8, 'Marketing Digital'),
                                                        (9, 'Génie Électrique'),
                                                        (10, 'Physique Appliquée'),
                                                        (11, 'Langues Étrangères');

INSERT INTO `professeur_formation` (`ref_user`, `ref_formation`) VALUES
                                                                     (3, 1),
                                                                     (5, 1),
                                                                     (3, 2),
                                                                     (12, 3),
                                                                     (1, 4),
                                                                     (6, 5),
                                                                     (7, 6),
                                                                     (8, 7),
                                                                     (9, 8),
                                                                     (10, 9);

INSERT INTO `reponse` (`id_reponse`, `contenu_`, `date_heure`, `ref_user`, `ref_post`) VALUES
                                                                                           (27, 'Pour les entretiens juniors, insistez sur votre capacité à apprendre rapidement et montrez un petit projet personnel.', '2025-10-29 16:15:00', 10, 13),
                                                                                           (28, 'Je confirme, avoir un portfolio même minimal fait toute la différence !', '2025-10-29 17:00:00', 7, 13),
                                                                                           (29, 'Intéressé par votre projet en RA ! Je suis en BTS SIO et j''ai des compétences en Unity.', '2025-10-30 09:30:00', 9, 14),
                                                                                           (30, 'L''éthique de la robotique doit être encadrée par des lois claires, surtout dans le domaine de la santé et de la justice.', '2025-10-30 12:45:00', 13, 15),
                                                                                           (31, 'Vue.js continue de gagner du terrain pour sa simplicité et sa performance. React est toujours leader, mais c''est serré.', '2025-10-30 17:15:00', 1, 16),
                                                                                           (32, 'Le moment fort du Tech Summit pour moi, c''était la démo sur les Quantum Computers !', '2025-10-31 10:00:00', 6, 17),
                                                                                           (33, 'Le M2 Finance est très intensif mais offre d''excellents débouchés en banque d''investissement. Accrochez-vous !', '2025-10-31 15:30:00', 10, 18),
                                                                                           (34, 'Le télétravail total exige beaucoup de discipline pour séparer vie pro et vie perso, mais le gain de temps est énorme.', '2025-11-01 11:30:00', 3, 19),
                                                                                           (35, 'Pour le LaTeX, je peux vous aider ! Utilisez Overleaf, c''est beaucoup plus simple pour le travail collaboratif.', '2025-11-01 15:45:00', 12, 20),
                                                                                           (36, 'J''ai trouvé un package LaTeX pour les mémoires qui gère la table des matières automatiquement, je vous envoie le lien.', '2025-11-01 16:30:00', 15, 20);

INSERT INTO `user_evenement` (`ref_user`, `ref_evenement`, `est_superviseur`) VALUES
                                                                                  (1, 1, 0),
                                                                                  (5, 2, 0),
                                                                                  (6, 4, 0),
                                                                                  (7, 5, 1),
                                                                                  (8, 6, 0),
                                                                                  (9, 7, 0),
                                                                                  (10, 8, 1),
                                                                                  (14, 9, 0),
                                                                                  (15, 5, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
