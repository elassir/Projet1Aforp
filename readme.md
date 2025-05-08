# Système de Gestion des Documents de Maintenance

## Présentation du projet

Cette application web permet la gestion des systèmes techniques et des documents associés dans un contexte de formation technique. Elle offre différentes fonctionnalités selon les rôles d'utilisateurs (formateur ou apprenti).

### Fonctionnalités principales :
- Gestion des systèmes techniques
- Gestion des documents techniques par système
- Gestion des documents pédagogiques
- Interface différenciée selon le rôle (formateur/apprenti)
- Système d'authentification

## Documentation du projet

Pour comprendre l'architecture et les fonctionnalités du projet, consultez les documents suivants :

- **[Dossier d'architecture.md](Dossier%20d'architecture.md)** : Présentation détaillée de l'architecture technique du projet
- **[Reponse au cahier des charges.md](Reponse%20au%20cahier%20des%20charges.md)** : Spécifications fonctionnelles du projet
- **[Cahier de test.md](cahier%20de%20test.md)** : Scénarios de test pour valider le bon fonctionnement de l'application

## Structure du Projet

L'application suit une architecture MVC (Modèle-Vue-Contrôleur) :

### Modèles (model/)
- `systeme.php` : Représentation des systèmes techniques
- `DocumentTechnique.php` : Représentation des documents techniques
- `DocumentPedago.php` : Représentation des documents pédagogiques
- `fabriquant.php` : Représentation des fabricants
- `Matiere.php` : Représentation des matières d'enseignement

### Repositories (model/)
- `SystemeRepository.php` : Accès aux données des systèmes
- `DocumentTechniqueRepository.php` : Accès aux données des documents techniques
- `DocumentPedagoRepository.php` : Accès aux données des documents pédagogiques
- `fabriquantRepository.php` : Accès aux données des fabricants
- `MatiereRepository.php` : Accès aux données des matières

### Contrôleurs (controlleur/)
- `connexion.php` : Gestion de la connexion à la base de données
- `login_formateur.php` et `login_apprenti.php` : Authentification des utilisateurs
- `logout.php` : Déconnexion des utilisateurs
- `enregistrerSysteme.php` : Traitement de l'ajout/modification des systèmes
- `enregistrerDocTec.php` : Traitement de l'ajout des documents techniques
- `enregistrerDocPedago.php` : Traitement de l'ajout des documents pédagogiques

### Vues (vue/)
- `index.php` : Page d'accueil et choix du type de connexion
- `gestion_systemes.php` : Interface de gestion des systèmes
- `gestion_doc.php` : Interface de gestion des documents techniques
- `gestion_docPedago.php` : Interface de gestion des documents pédagogiques
- `styles.css`, `style_index.css`, `gestion_doc.css` : Feuilles de style CSS

### Base de données
- `documents/projet1 (5).sql` : Script SQL pour créer la structure de la base de données

## Installation

1. Clonez le dépôt dans votre répertoire web (www ou htdocs) :
   ```sh
   git clone <url-du-repo>
   ```

2. Importez la base de données en utilisant le fichier `documents/projet1 (5).sql`

3. Configurez la connexion à la base de données dans `controlleur/connexion.php` :
   ```php
   $host='localhost:3306';
   $bdd= 'projet1';
   $username ='root';
   $password = '';
   ```

4. Assurez-vous que les dossiers `uploads/` et `uploads/icon/` existent et sont accessibles en écriture.

5. Accédez à l'application via votre navigateur à l'adresse : `http://localhost/projet1.1 copy/vue/index.php`

## Comment utiliser l'application

### Connexion

1. Sur la page d'accueil, choisissez entre une connexion en tant que formateur ou apprenti
2. Pour les besoins de démonstration/test, utilisez :
   - Formateur : email = formateur@test.com, mot de passe = formateur123
   - Apprenti : email = apprenti@test.com, mot de passe = apprenti123

### Gestion des Systèmes

1. Après connexion, vous accédez à la page de gestion des systèmes
2. En tant que formateur, vous pouvez ajouter un nouveau système via le bouton "Ajouter un Système"
3. Cliquez sur l'image d'un système pour voir ses détails et accéder aux documents associés

### Gestion des Documents Techniques

1. Depuis la page de détail d'un système, cliquez sur "Document technique"
2. En tant que formateur, vous pouvez ajouter un nouveau document via le bouton "Ajouter un Document Technique"
3. Les documents sont classés par catégorie (Presentation, Schema technique, Notices, Annexes)

### Gestion des Documents Pédagogiques

1. Depuis la page de détail d'un système, cliquez sur "Document pédagogique"
2. En tant que formateur, vous pouvez ajouter un nouveau document via le bouton "Ajouter un Document Pédagogique"
3. Les documents sont classés par type (DEVOIR, CONSIGNE)

## Technologies utilisées

- Backend : PHP 8
- Base de données : MySQL 8
- Frontend : HTML5, CSS3, JavaScript
- Serveur web : Apache

