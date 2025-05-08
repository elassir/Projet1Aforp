# Dossier d'Architecture - Système de Gestion des Systèmes et Documents Techniques

## Table des matières
1. Introduction
2. Vue d'ensemble de l'architecture
3. Organisation des dossiers
4. Architecture MVC
   - Modèles
   - Vues
   - Contrôleurs
5. Modèle de données
6. Interactions et flux de données
7. Sécurité
8. Technologies utilisées
9. Points d'extension potentiels
10. Conclusion

## 1. Introduction

Ce document présente l'architecture technique du système de gestion des systèmes techniques et documents associés. L'application est conçue pour permettre aux formateurs et apprentis de gérer des systèmes techniques, des documents techniques liés à ces systèmes, et des documents pédagogiques.

Le système est développé en PHP et utilise MySQL comme système de gestion de base de données. L'architecture suit le modèle MVC (Modèle-Vue-Contrôleur) pour une meilleure séparation des responsabilités et une maintenabilité accrue.

## 2. Vue d'ensemble de l'architecture

L'architecture globale du système repose sur le pattern MVC (Modèle-Vue-Contrôleur) qui sépare les données (modèles), l'interface utilisateur (vues) et la logique métier (contrôleurs). Ce découpage permet une meilleure organisation du code et facilite la maintenance et l'évolution du système.

Le système est accessible via un navigateur web et utilise une base de données MySQL pour stocker les informations sur les systèmes techniques, les documents, les utilisateurs, etc.

## 3. Organisation des dossiers

La structure des dossiers est organisée comme suit:

```
/
├── controlleur/       # Contient les contrôleurs de l'application
├── documents/         # Documents de référence et fichiers de base de données
├── model/            # Contient les modèles et repositories
├── test/             # Fichiers de test et prototypes
├── uploads/          # Stockage des fichiers téléversés par les utilisateurs
│   └── icon/         # Icônes utilisées dans l'application
└── vue/              # Contient les fichiers d'interface utilisateur (vues)
```

## 4. Architecture MVC

### Modèles (Model)

Les modèles représentent les données manipulées par l'application et contiennent la logique pour accéder à ces données. L'application utilise un pattern Repository pour séparer les modèles de la logique d'accès aux données.

**Classes de modèle:**
- `Systeme`: Représente un système technique
- `DocumentTechnique`: Représente un document technique lié à un système
- `DocumentPedago`: Représente un document pédagogique
- `Fabriquant`: Représente un fabricant de systèmes
- `Matiere`: Représente une matière d'enseignement

**Classes Repository:**
- `SystemeRepository`: Gère l'accès aux données des systèmes
- `DocumentTechniqueRepository`: Gère l'accès aux données des documents techniques
- `DocumentPedagoRepository`: Gère l'accès aux données des documents pédagogiques
- `FabriquantRepository`: Gère l'accès aux données des fabricants
- `MatiereRepository`: Gère l'accès aux données des matières

Les repositories implémentent des méthodes CRUD (Create, Read, Update, Delete) pour manipuler les données en base.

### Vues (View)

Les vues sont responsables de la présentation des données aux utilisateurs. Elles sont composées de fichiers PHP et CSS qui génèrent l'interface utilisateur.

**Principaux fichiers de vue:**
- `index.php`: Page d'accueil avec authentification
- `gestion_systemes.php`: Interface de gestion des systèmes
- `gestion_doc.php`: Interface de gestion des documents techniques
- `gestion_docPedago.php`: Interface de gestion des documents pédagogiques

**Fichiers CSS associés:**
- `style_index.css`: Styles pour la page d'accueil
- `styles.css`: Styles globaux
- `gestion_doc.css`: Styles pour la gestion des documents

### Contrôleurs (Controller)

Les contrôleurs gèrent la logique métier de l'application. Ils reçoivent les requêtes de l'utilisateur, interagissent avec les modèles pour traiter les données, et transmettent les résultats aux vues.

**Principaux contrôleurs:**
- `connexion.php`: Établit la connexion à la base de données
- `login_formateur.php` et `login_apprenti.php`: Gèrent l'authentification
- `logout.php`: Gère la déconnexion
- `enregistrerSysteme.php`: Traite l'enregistrement des systèmes
- `enregistrerDocTec.php`: Traite l'enregistrement des documents techniques
- `enregistrerDocPedago.php`: Traite l'enregistrement des documents pédagogiques

## 5. Modèle de données

+---------------+          +------------------+          +-----------------+

| FABRIQUANT    |          | SYSTEME          |          | MATIERE         |

+---------------+          +------------------+          +-----------------+

| Siret (PK)    |<---------| id_systeme (PK)  |--------->| id_matiere (PK) |

| Nom           |          | Nom_du_systeme   |          | Nom_matiere     |

| Tel           |          | Description      |          |                 |

| Adresse       |          | Date_fabrication |          |                 |

+---------------+          | Numero_de_serie  |          +-----------------+

                           | image_systeme    |                  ↑

                           +------------------+                  |

                                   ↑                            |

                                   |                            |

+---------------+          +------------------+          +-----------------+

| FORMATEUR     |          | DOCUMENT         |          | DOCUMENT        |

+---------------+          | TECHNIQUE        |          | PEDAGOGIQUE     |

| id_formateur  |<-------->| id_document (PK) |          | id_pedagogique  |

| Mail          |          | Version          |          | Type_document   |

| Mot_de_passe  |          | Date             |          | Date_Document   |

| Nom           |          | Categorie        |          | Doc_file        |

| Prenom        |          | Doc_file         |          | Systeme_concerne|

+---------------+          | Systeme_concerne |--------->| id_matiere      |

       ↑                   | Nom_doc_tech     |          +-----------------+

       |                   +------------------+                  ↑

       |                                                        |

+---------------+                                       +-----------------+

| APPRENTI      |                                       | FORMATEUR_DEVOIR|

+---------------+                                       +-----------------+

| id_apprenti   |<--------------------------------------| Formateur       |

| Mail          |                                       | Devoir          |

| Nom           |                                       +-----------------+

| Prenom        |                                               ↑

| Mot_de_passe  |                                               |

| Promotion     |                                       +-----------------+

+---------------+                                       | APPRENTI_DEVOIR |

                                                       +-----------------+

                                                       | Apprenti        |

                                                       | Devoir          |

                                                       +-----------------+

## Description des entités et relations

### Tables principales:

#### SYSTEME

| Attribut | Type | Description |
|----------|------|-------------|
| id_systeme | INT | Clé primaire |
| Nom_du_systeme | VARCHAR | Nom du système technique |
| Description | TEXT | Description du système |
| Date_fabrication | DATE | Date de fabrication |
| Numero_de_serie | VARCHAR | Numéro de série |
| image_systeme | VARCHAR | Chemin vers l'image du système |
| Fabriquant | VARCHAR | Clé étrangère (référence Fabriquant.Siret) |
| date_derniere_mise_a_jour | DATE | Date de dernière mise à jour |

#### DOCUMENT_TECHNIQUE

| Attribut | Type | Description |
|----------|------|-------------|
| id_document | INT | Clé primaire |
| Nom_doc_tech | VARCHAR | Nom du document |
| Doc_file | VARCHAR | Chemin vers le fichier |
| Systeme_concerne | INT | Clé étrangère (référence Systeme.id_systeme) |
| Date | INT | Année du document |
| Version | VARCHAR | Version du document |
| Categorie | ENUM | Catégorie ('Schema technique', 'Notices', 'Annexes', 'Presentation') |

#### DOCUMENT_PEDAGOGIQUE

| Attribut         | Type    | Description                                  |
| ---------------- | ------- | -------------------------------------------- |
| id_pedagogique   | INT     | Clé primaire                                 |
| Doc_file         | VARCHAR | Chemin vers le fichier                       |
| Systeme_concerne | INT     | Clé étrangère (référence Systeme.id_systeme) |
| Date_Document    | DATE    | Date du document                             |
| id_matiere       | INT     | Clé étrangère (référence Matiere.id_matiere) |
| Type_document    | ENUM    | Type de document ('DEVOIR', 'CONSIGNE')      |

#### FABRIQUANT

| Attribut | Type | Description |
|----------|------|-------------|
| Siret | VARCHAR | Clé primaire - Numéro SIRET du fabricant |
| Nom | VARCHAR | Nom du fabricant |
| Tel | INT | Numéro de téléphone |
| Adresse | VARCHAR | Adresse du fabricant |

#### MATIERE

| Attribut | Type | Description |
|----------|------|-------------|
| id_matiere | INT | Clé primaire |
| Nom_matiere | VARCHAR | Nom de la matière |

#### FORMATEUR

| Attribut     | Type    | Description         |
| ------------ | ------- | ------------------- |
| id_formateur | INT     | Clé primaire        |
| Nom          | VARCHAR | Nom du formateur    |
| Prenom       | VARCHAR | Prénom du formateur |
| Mail         | VARCHAR | Email du formateur  |
| Mot_de_passe | VARCHAR | Mot de passe (hash) |

#### APPRENTI

| Attribut | Type | Description |
|----------|------|-------------|
| id_apprenti | INT | Clé primaire |
| Nom | VARCHAR | Nom de l'apprenti |
| Prenom | VARCHAR | Prénom de l'apprenti |
| Mail | VARCHAR | Email de l'apprenti |
| Mot_de_passe | VARCHAR | Mot de passe (hash) |
| Promotion | INT | Année de promotion |

### Tables de liaison:

#### SYSTEME_MATIERE (liaison N:M entre Système et Matière)

| Attribut | Type | Description |
|----------|------|-------------|
| id_systeme | INT | Clé étrangère (référence Systeme.id_systeme) |
| id_matiere | INT | Clé étrangère (référence Matiere.id_matiere) |

#### APPRENTI_DEVOIR (liaison N:M entre Apprenti et Document pédagogique)

| Attribut | Type | Description |
|----------|------|-------------|
| Apprenti | INT | Clé étrangère (référence Apprenti.id_apprenti) |
| Devoir | INT | Clé étrangère (référence Document_pedagogique.id_pedagogique) |

#### FORMATEUR_DEVOIR (liaison N:M entre Formateur et Document pédagogique)

| Attribut | Type | Description |
|----------|------|-------------|
| Formateur | INT | Clé étrangère (référence Formateur.id_formateur) |
| Devoir | INT | Clé étrangère (référence Document_pedagogique.id_pedagogique) |

#### SYSTEME_FORMATEUR (liaison N:M entre Système et Formateur avec historique)

| Attribut | Type | Description |
|----------|------|-------------|
| Formateur | INT | Clé étrangère (référence Formateur.id_formateur) |
| Systeme | INT | Clé étrangère (référence Systeme.id_systeme) |
| Date_action | DATE | Date de l'action |
| Type_action | ENUM | Type d'action ('AJOUT', 'MODIFICATION', 'CONSULTATION') |

### Principales Relations:

1. Un **SYSTEME** est fabriqué par un **FABRIQUANT** (relation N:1)
2. Un **DOCUMENT_TECHNIQUE** est associé à un **SYSTEME** (relation N:1)
3. Un **DOCUMENT_PEDAGOGIQUE** est associé à un **SYSTEME** et à une **MATIERE** (relations N:1)
4. Un **SYSTEME** peut être associé à plusieurs **MATIERE** (relation N:M via SYSTEME_MATIERE)
5. Un **FORMATEUR** peut gérer plusieurs **DOCUMENT_PEDAGOGIQUE** (relation N:M via FORMATEUR_DEVOIR)
6. Un **APPRENTI** peut accéder à plusieurs **DOCUMENT_PEDAGOGIQUE** (relation N:M via APPRENTI_DEVOIR)
7. Un **FORMATEUR** peut effectuer des actions sur plusieurs **SYSTEME** avec historisation (relation N:M via SYSTEME_FORMATEUR)







## 6. Interactions et flux de données

### Authentification:
1. L'utilisateur accède à la page d'accueil (index.php)
2. Il choisit de se connecter en tant que formateur ou apprenti
3. Le contrôleur d'authentification (login_formateur.php ou login_apprenti.php) vérifie les identifiants
4. Une session est créée et l'utilisateur est redirigé vers l'interface appropriée

### Gestion des systèmes:
1. Le formateur accède à la page de gestion des systèmes
2. Il peut ajouter un nouveau système via un formulaire
3. Le contrôleur (enregistrerSysteme.php) traite la soumission du formulaire
4. SystemeRepository enregistre le système en base de données

### Gestion des documents techniques:
1. Le formateur accède à la page de gestion des documents techniques
2. Il sélectionne un système et téléverse un document
3. Le contrôleur (enregistrerDocTec.php) traite le téléversement
4. Le fichier est stocké dans le dossier uploads/
5. DocumentTechniqueRepository enregistre les métadonnées en base de données

### Gestion des documents pédagogiques:
1. Le formateur accède à la page de gestion des documents pédagogiques
2. Il sélectionne une matière et téléverse un document
3. Le contrôleur (enregistrerDocPedago.php) traite le téléversement
4. Le fichier est stocké dans le dossier uploads/
5. DocumentPedagoRepository enregistre les métadonnées en base de données

## 7. Sécurité

Le système intègre plusieurs mécanismes de sécurité:

- **Authentification**: Système de login/password pour différents types d'utilisateurs
- **Gestion des sessions**: Sessions PHP pour maintenir l'état de connexion et les droits
- **Contrôle d'accès**: Vérifications des droits avant d'autoriser les actions
- **Protection contre les injections SQL**: Utilisation de requêtes préparées dans les repositories

## 8. Technologies utilisées

- **Langage de programmation**: PHP
- **Base de données**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Stockage de fichiers**: Système de fichiers local


## 9. Conclusion

L'architecture MVC du système de gestion des systèmes et documents techniques offre une base solide pour le développement et la maintenance de l'application. La séparation claire des responsabilités entre les modèles, les vues et les contrôleurs facilite l'évolution du système et l'ajout de nouvelles fonctionnalités.

Le modèle de données est conçu pour répondre aux besoins spécifiques de gestion des systèmes techniques et des documents associés, tout en permettant une évolution future pour intégrer de nouveaux types de documents ou d'utilisateurs.

L'utilisation de repositories pour l'accès aux données offre une couche d'abstraction qui facilite les modifications de la logique d'accès aux données sans impacter le reste du système.