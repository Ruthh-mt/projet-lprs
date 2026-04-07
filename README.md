# 🌐 LPRS — Site Web PHP

> Application Web pour l'examen du BTS SIO 

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Licence](https://img.shields.io/badge/licence-MIT-green)
![Status](https://img.shields.io/badge/status-en%20développement-orange)

---

## 📋 Table des matières

- [Aperçu](#-aperçu)
- [Utilisateurs cibles](#-utilisateur-cibles)
- [Roles & Fonctionnalités](#-roles--fonctionnalités)
- [Stack technique](#-stack-technique)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Structure du projet](#-structure-du-projet)
- [Base de données](#-base-de-données)
- [Sécurité](#-sécurité)
- [Contribuer](#-contribuer)
- [Auteurs](#-auteurs)
- [Licence](#-licence)

---

## 🔍 Aperçu

La plateforme a pour but de réunir tous les acteurs d’un établissement d’enseignement supérieur sur une seule application web.

Elle permet de :

* faciliter la recherche de stages, alternances et emplois
* créer un réseau entre étudiants et anciens élèves
* renforcer les relations avec les entreprises partenaires
  
## 🎯 Utilisateurs cibles :

* Étudiants
* Alumni (anciens élèves)
* Entreprises partenaires
* Professeurs
* Administrateurs
---

## 👥✨ Roles & Fonctionnalités

### Côté etudiant
- ✅ Inscription / Connexion (validation par administrateur)
- ✅ Gestion du profil (formation, promo, CV)
- ✅ Accès à l’annuaire des alumni
- ✅ Consultation des offres (stage / alternance / emploi)
- ✅ Candidature aux offres (avec message de motivation)
- ✅ Participation aux événements
- ✅ Accès au forum (poster et répondre)

  ### Côté alumnis
- ✅ Inscription / Connexion
- ✅ Gestion du profil (promo, CV, poste actuel)
- ✅ Accès à l’annuaire des alumni
- ✅ Consultation et candidature aux offres
- ✅ Participation aux événements
- ✅ Interaction sur le forum
- ✅ Rattachement à une entreprise

### Côté entreprise
- ✅ Création et gestion d’une fiche entreprise
- ✅ Publication d’offres (stage, alternance, CDI, CDD)
- ✅ Réception des candidatures (notification par email)
- ✅ Consultation des profils des alumni
- ✅ Gestion des candidatures (refus avec email automatique)
- ✅ Création d’événements
- ✅ Gestion multi-utilisateurs (plusieurs membres)

### Côté professeur
- ✅ Inscription / Connexion
- ✅ Gestion du profil (matière, formations)
- ✅ Accès aux profils étudiants et alumni
- ✅ Consultation des offres
- ✅ Création d’événements
- ✅ Participation au forum (section étudiants)
  
### Côté administrateur
- ✅ Interface d'administration
- ✅ Validation des comptes utilisateurs
- ✅ Gestion des utilisateurs (CRUD)
- ✅ Gestion des formations
- ✅ Gestion des offres et événements
- ✅ Modération du forum

### Fonctionnalités globales

- 🔐 Authentification sécurisée
- 📧 Système de mot de passe oublié (email)
- 💬 Forum structuré (général, étudiants, alumni/entreprises)
- 📅 Gestion des événements (création, inscription, validation)
- 💼 Gestion des offres (publication, candidature, statut ouvert/fermé)

### Prévues
- 🔜 Securisation des sessions utilisateur
- 🔜 Ajouter une messagerie avec laquelle on pourrait discuter avec un utilisateur choisi
- 🔜 [Soon]

---

## 🛠 Stack technique

| Technologie | Version |
|-------------|---------|
| PHP | 8.2+ | Langage serveur |
| MySQL / MariaDB | 8.0 / 10.x |
| HTML5 + CSS3 | — |
| Bootstrap *(optionnel)* | 5.3 |
| Composer *(optionnel)* | 2.x |

---

## ✅ Prérequis

- [ ] PHP 8.2+ (`php -v`)
- [ ] MySQL 8.0+ ou MariaDB 10.6+
- [ ] Composer *(si dépendances PHP)*
- [ ] [XAMPP](https://www.apachefriends.org/) / [WAMP](https://www.wampserver.com/) / [Laragon](https://laragon.org/) *(développement local)*

---

## 📦 Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Ruthh-mt/projet-lprs.git
cd projet-lprs
```

### 2. Installer les dépendances PHP *(si Composer)*

```bash
composer install
```

### 3. Configurer le serveur local

#### Avec XAMPP / WAMP
Copiez le dossier du projet dans :
- XAMPP : `C:/xampp/htdocs/nom-du-projet/`
- WAMP : `C:/wamp64/www/nom-du-projet/`

Accédez ensuite à : `http://localhost/nom-du-projet`

#### Avec le serveur intégré PHP *(développement uniquement)*
```bash
php -S localhost:8000 -t public/
```

### 4. Importer la base de données

```bash
mysql -u root -p < database/schema.sql
# Si des données de test existent :
mysql -u root -p nom_base < database/seed.sql
```

---

## ⚙️ Configuration

Copiez le fichier d'exemple :

```bash
cp config/config.example.php config/config.php
```

Renseignez vos paramètres dans `src/bdd/config.php` 
Vous pouver prendre pour exemple le fichier configExemple

---

## 📁 Structure du projet

```
projet-LPRS/
├── api/
├── informations/            # Regroupe tous les deocument lier a la conception (MCD,MLD)
├── public/                  # Regroupe tout les media que l'utilisateur pourra voir                    
├── src/                     # Logique métier
│   ├── assets/              # Fichier CSS
│   ├── bdd/                 # fichiers de configuration de la bdd et des code SQL  
│   ├── models/              # Modèles  
│   ├── repository/          # Accès BDD
│   └── treatement/          # Logique derriere chaque formulaires  
│
├── vendor/
│   ├── composer             # requis pour installer php mailer 
│   └── phpmailer            # Fichier requis pour l'envoie d'email
│
├── view/                  # Affichage des pages
├── index.php
├── LICENSE
└── README.md
```

---

## 🗄 Base de données

Le Modele conceptuel et logique de la base de données peuvent etre retouvé dans le dossier Informations 
Vous pouvez aussi retrouvé dans le dossier src/BDD le fichier de configuration du projet 

---

## 🔒 Sécurité

Ce projet applique les bonnes pratiques de sécurité web :

- Requêtes préparées PDO (anti SQL injection)
- Protection XSS (`htmlspecialchars`)
- Hachage des mots de passe (`password_hash`)
- Validation des comptes par administrateur

---

## 🤝 Contribuer

1. Forkez le projet
2. Créez votre branche : `git checkout -b feature/ma-fonctionnalite`
3. Commitez : `git commit -m "feat: description de la fonctionnalité"`
4. Poussez : `git push origin feature/ma-fonctionnalite`
5. Ouvrez une Pull Request

---

## 👤 Auteurs

- **Agustin D'ERCEVILLE** — *Développeur principal* — [@A.Erceville](https://github.com/Augustin-Erceville)
- **Romario QUASHIE** — *Développeur principal* — [@R_QUASHIE](https://github.com/Rquashie)
- **Ruth Metayer** — *Développeur principal* — [@Ruthh-mt](https://github.com/Ruthh-mt)

---

## 📄 Licence

Ce projet est sous licence **MIT** — voir le fichier [LICENSE](LICENSE).

*Dernière mise à jour : 07/04/2026*
