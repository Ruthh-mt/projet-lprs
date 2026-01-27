# Projet LPRS
## Présentation
Le projet **LPRS** est une application web développée en **PHP** suivant une architecture proche du **MVC**. Elle vise à centraliser la gestion d'une plateforme scolaire et professionnelle incluant :
* Utilisateurs (étudiants, professeurs, gestionnaires, partenaires, alumni)
* Offres (stages, emplois)
* Candidatures
* Événements
* Formations
* Forum et publications  
Le projet intègre une base de données relationnelle et un système d'authentification complet avec validation des comptes et gestion des rôles.
---
## Technologies utilisées
* **PHP**
* **MySQL**
* **HTML/CSS**
* **Composer** (gestion des dépendances)
* **PHPMailer** (envoi d'e-mails)
---
## Architecture du projet
```
projet-lprs/
│── index.php
│── composer.json
│── api/
│── public/
│   └── uploads/
│── src/
│   ├── bdd/
│   ├── modele/
│   ├── repository/
│   └── treatment/
│── view/
│── vendor/
```
### Détails des dossiers
* **api/** : Endpoints API (ex: récupération des formations)
* **public/** : Fichiers accessibles publiquement (uploads CV, avatars)
* **src/bdd/** : Scripts SQL et configuration de la base de données
* **src/modele/** : Modèles représentant les entités métier
* **src/repository/** : Accès aux données et requêtes SQL
* **src/treatment/** : Logique métier et traitements (CRUD, authentification, validation)
* **view/** : Vues PHP (interfaces utilisateur)
* **vendor/** : Dépendances installées via Composer
---
## Installation
### Prérequis
* Serveur web (Apache recommandé)
* PHP >= 8.0
* MySQL
* Composer installé
### Étapes d'installation
1. Cloner le projet :
```bash
git clone https://github.com/Ruthh-mt/projet-lprs.git
```
2. Installer les dépendances :
```bash
composer install
```
3. Créer la base de données :
* Ouvrir **phpMyAdmin**
* Créer une base de données (ex: `lprs`)
* Importer le fichier :
```
src/bdd/BDDV6.sql
```
4. Configurer la connexion à la base de données :  
  Modifier le fichier :
```
src/bdd/config.php
```
avec vos identifiants MySQL.
5. Lancer le projet :
* Accéder au projet via :
```
http://localhost/projet-lprs/index.php
```
---
## Fonctionnalités principales
* Authentification et inscription des utilisateurs
* Validation des comptes par un gestionnaire
* Gestion des offres et candidatures
* Gestion des événements et inscriptions
* Gestion des entreprises partenaires
* Upload de CV et avatars
* Envoi d'e-mails (confirmation, réinitialisation mot de passe)
* Forum et publications
---
## Rôles utilisateurs
* **Étudiant** : consulter offres, postuler, participer aux événements
* **Professeur** : gestion pédagogique
* **Gestionnaire** : administration globale
* **Partenaire** : dépôt et gestion d'offres d'emplois
* **Alumni** : accès réseau et offres des anciens élèves
---
## Sécurité
* Hashage des mots de passe
* Validation par e-mail
* Gestion des sessions
* Contrôle des accès par rôle
* Inscription validée par un gestionnaire
---
## Base de données
Les différentes versions de la base sont disponibles dans :
```
src/bdd/
```
La version recommandée est **BDDV6.sql**.
---
## Auteurs
Projet réalisé dans un cadre pédagogique.
* Chef de projet : [Ruth Metayer](https://github.com/Ruthh-mt)
* Assistant de projet : [Augustin d'Erceville](https://github.com/Augustin-Erceville)
* Assistant de projet : [Romario Quashie](https://github.com/Rquashie)
---
## Licence
Projet à usage éducatif uniquement.