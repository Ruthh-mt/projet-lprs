CREATE TABLE utilisateur(id_user int PRIMARY KEY, nom VARCHAR(50), prenom VARCHAR(50), email VARCHAR(50), mdp VARCHAR(255), est_valide boolean);


CREATE TABLE  offre (id_offre INT, titre VARCHAR(50), description VARCHAR(50), mission VARCHAR(50), salaire DECIMAL(15,2), type VARCHAR(50), etat VARCHAR(50));

CREATE TABLE professeur (ref_user INT PRIMARY KEY, specialite VARCHAR(50));

CREATE TABLE formation (id_formation INT PRIMARY KEY, nom VARCHAR(50));

CREATE TABLE alumni (ref_user INT PRIMARY KEY, cv VARCHAR(255), annee_promo VARCHAR(50));

CREATE TABLE etudiant  (ref_user INT PRIMARY KEY, cv VARCHAR(255), annee_promo VARCHAR(50), ref_formation int );
ALTER TABLE etudiant
ADD FOREIGN KEY (ref_formation) REFERENCES formation(id_formation); 

CREATE TABLE fiche_entreprise  (id_fiche_entreprise INT PRIMARY KEY, nom_entreprise VARCHAR(50), adresse_entreprise VARCHAR(50) );

CREATE TABLE post (id_post INT PRIMARY KEY, canal VARCHAR(50), titre_post VARCHAR(50), date_heure_post DATETIME);

CREATE TABLE reponse  (id_reponse INT PRIMARY KEY, contenu_ VARCHAR(50), date_heure DATETIME, ref_user int, ref_post int);
ALTER TABLE reponse
ADD FOREIGN KEY (ref_user) REFERENCES utilisateur(ref_user),
ADD FOREIGN KEY (ref_post) REFERENCES post(id_post); 

CREATE TABLE Evenement  (id_evenement INT PRIMARY KEY, type_eve VARCHAR(50), lieu_eve VARCHAR(50), element_eve VARCHAR(50), nb_place INT, desc_eve VARCHAR(50), titre_eve VARCHAR(50));
                          
CREATE TABLE gestionnaire (ref_user INT PRIMARY KEY); 

CREATE TABLE user_offre (ref_user int, ref_offre int);
ALTER TABLE user_offre 
ADD FOREIGN KEY (ref_user) REFERENCES utilisateur(id_user),
ADD FOREIGN KEY (ref_offre) REFERENCES offre(id_offre); 

CREATE TABLE professeur_formation (ref_user int, ref_formation int);
ALTER TABLE professeur_formation
ADD FOREIGN KEY (ref_user) REFERENCES utilisateur(id_user),
ADD FOREIGN KEY (ref_formation) REFERENCES formation(id_formation); 

CREATE TABLE alumni_fiche_entreprise (ref_user int, ref_fiche_entreprise int);
ALTER TABLE alumni_fiche_entreprise
ADD FOREIGN KEY (ref_user) REFERENCES utilisateur(id_user),
ADD FOREIGN KEY (ref_fiche_entreprise) REFERENCES fiche_entreprise(id_fiche_entreprise); 

CREATE TABLE offre_fiche_entrepise  (ref_offre int , ref_fiche_entreprise int);
ALTER TABLE offre_fiche_entrepise 
ADD FOREIGN KEY (ref_offre) REFERENCES offre(id_offre),
ADD FOREIGN KEY (ref_fiche_entreprise) REFERENCES fiche_entreprise(id_fiche_entreprise); 

CREATE TABLE user_post  (ref_user int, ref_post int);
ALTER TABLE user_post
ADD FOREIGN KEY (ref_user) REFERENCES utilisateur(id_user),
ADD FOREIGN KEY (ref_post) REFERENCES post(id_post); 

CREATE TABLE user_evenement_gerer  (ref_user int, ref_evenement int);
ALTER TABLE user_evenement_gerer
ADD FOREIGN KEY (ref_user) REFERENCES utilisateur(id_user),
ADD FOREIGN KEY (ref_evenement) REFERENCES evenement(id_evenement); 

CREATE TABLE user_evenement_participer (ref_user int, ref_evenement int);
ALTER TABLE user_evenement_participer
ADD FOREIGN KEY (ref_user) REFERENCES utilisateur(id_user),
ADD FOREIGN KEY (ref_evenement) REFERENCES evenement(id_evenement); 



