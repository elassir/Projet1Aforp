# Gestion des Systèmes et Documents Techniques

## Structure du Projet


## Installation

1. Clonez le dépôt :
    ```sh
    git clone <url-du-repo>
    ```

2. Configurez la base de données dans `controlleur/connexion.php` :
    ```php
    $host='localhost:3306';
    $bdd= 'projet1';
    $username ='root';
    $password = '';
    ```

3. Assurez-vous que les dossiers `uploads/` et `uploads/icon/` existent et sont accessibles en écriture.

## Utilisation

### Gestion des Systèmes

1. Accédez à `vue/gestion_systemes.php` pour gérer les systèmes.
2. Pour ajouter un nouveau système, cliquez sur le bouton "Ajouter un Système" et remplissez le formulaire.
3. Pour voir les détails d'un système, cliquez sur l'image du système.

### Gestion des Documents Techniques

1. Accédez à `vue/gestion_doc.php` pour gérer les documents techniques.
2. Pour ajouter un nouveau document technique, cliquez sur le bouton "Ajouter un Document Technique" et remplissez le formulaire.
3. Pour voir les documents techniques existants, parcourez la liste des documents.

## Développement

### Modèles

- `model/systeme.php` : Classe représentant un système.
- `model/DocumentTechnique.php` : Classe représentant un document technique.
- `model/fabriquant.php` : Classe représentant un fabriquant.

### Répertoires

- `model/SystemeRepository.php` : Gestion des opérations CRUD pour les systèmes.
- `model/DocumentTechniqueRepository.php` : Gestion des opérations CRUD pour les documents techniques.
- `model/fabriquantRepository.php` : Gestion des opérations CRUD pour les fabriquants.

### Contrôleurs

- `controlleur/enregistrerSysteme.php` : Enregistrement d'un nouveau système.
- `controlleur/enregistrerDocTec.php` : Enregistrement d'un nouveau document technique.

### Vues

- `vue/gestion_systemes.php` : Interface de gestion des systèmes.
- `vue/gestion_doc.php` : Interface de gestion des documents techniques.
- `vue/styles.css` : Styles CSS pour les interfaces.

