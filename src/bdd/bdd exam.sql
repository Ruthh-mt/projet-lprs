CREATE TABLE IF NOT EXISTS `satisfaction` (
    `ref_user`      INT            NOT NULL,
    `ref_event`     INT            NOT NULL,
    `note`          TINYINT        NOT NULL CHECK (`note` BETWEEN 1 AND 5),
    `commentaire`   VARCHAR(2048)  NOT NULL,
    `date`          DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`ref_user`, `ref_event`),
    CONSTRAINT `fk_satisfaction_utilisateur`
    FOREIGN KEY (`ref_user`)  REFERENCES `utilisateur` (`id_user`)
    ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_satisfaction_evenement`
    FOREIGN KEY (`ref_event`) REFERENCES `evenement` (`id_evenement`)
    ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE VIEW `note_moyenne` AS
SELECT
    e.id_evenement,
    e.titre_eve,
    ROUND(AVG(s.note), 2) AS moyenne_notes
FROM `evenement` e
         LEFT JOIN `satisfaction` s ON s.ref_event = e.id_evenement
GROUP BY e.id_evenement, e.titre_eve;