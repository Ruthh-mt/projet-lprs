-- Migration : Ajout de la table avis_evenement
-- A executer sur la base de donnees lprs

CREATE TABLE IF NOT EXISTS `avis_evenement` (
    `id_avis` int NOT NULL AUTO_INCREMENT,
    `note` int NOT NULL CHECK (note BETWEEN 1 AND 5),
    `commentaire` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    `date_avis` datetime NOT NULL,
    `ref_user` int NOT NULL,
    `ref_evenement` int NOT NULL,
    PRIMARY KEY (`id_avis`),
    UNIQUE KEY `unique_avis_user_evenement` (`ref_user`, `ref_evenement`),
    KEY `fk_avis_utilisateur` (`ref_user`),
    KEY `fk_avis_evenement` (`ref_evenement`),
    CONSTRAINT `fk_avis_utilisateur` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_avis_evenement` FOREIGN KEY (`ref_evenement`) REFERENCES `evenement` (`id_evenement`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
