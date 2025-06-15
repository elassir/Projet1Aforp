Voici le modèle de **Cahier de Tests** pour le projet 1 destiné à tester les fonctionnalités de l'application web demandée :

---

# **Cahier de Tests : Application de Gestion des Documents de Maintenance**

**Nom du projet** : PROJET 1  
**Version** : 1.0  
**Responsable des tests** : EL Assir

---

## **1. Objectif des tests**
Le but de ce cahier de tests est de vérifier que l'application répond aux exigences fonctionnelles et non fonctionnelles définies dans le cahier des charges. Les tests couvrent :  
- La gestion des systèmes et des documents.  
- Les fonctionnalités destinées aux formateurs et aux apprentis.  
- L'ergonomie et l'interface utilisateur.  
- Les règles de gestion et la sécurité.  

---

## **2. Plan de tests**

### **2.1 Types de tests**
- **Tests fonctionnels** : Vérifier que chaque fonctionnalité fonctionne comme prévu.  
- **Tests d'interface utilisateur** : Vérifier l'ergonomie, la clarté et la conformité à la charte graphique.  
- **Tests de sécurité** : Vérifier les accès utilisateur (apprenti, formateur, administrateur).  
- **Tests de performance** : Vérifier que l'application répond rapidement et gère plusieurs utilisateurs simultanés.  

### **2.2 Environnement de test**
- **Système d'exploitation** : Windows 10, Ubuntu 20.04.  
- **Navigateurs** : Edge.  
- **Base de données** : MySQL 8.0.  
- **Serveur web** : Apache  avec PHP 8.  

---

## **3. Cas de tests**

### **3.1 Gestion des systèmes**
| ID Test | Description          | Étapes                                                                                                                                                         | Résultat attendu                                 | Statut |
| ------- | -------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ | ------ |
| T01     | Ajouter un système   | 1. Se connecter en tant qu'admin. <br> 2. Naviguer vers "Ajouter un système". <br> 3. Remplir les champs requis (nom, code, numéro de série). <br> 4. Valider. | Le système est ajouté et apparaît dans la liste. | ok     |
| T02     | Supprimer un système | 1. Se connecter en tant qu'admin. <br> 2. Sélectionner un système existant. <br> 3. Cliquer sur "Supprimer".                                                   | Le système est supprimé de la liste.             | ok     |

### **3.2 Gestion des documents**
| ID Test | Description           | Étapes                                                                                                                                                              | Résultat attendu                                            | Statut |
| ------- | --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------- | ------ |
| T03     | Ajouter un document   | 1. Se connecter en tant qu'admin. <br> 2. Naviguer vers un système. <br> 3. Cliquer sur "Ajouter un document". <br> 4. Uploader un fichier et définir la catégorie. | Le document est ajouté et visible sous le système concerné. | ok     |
| T04     | Supprimer un document | 1. Se connecter en tant qu'admin. <br> 2. Sélectionner un document. <br> 3. Cliquer sur "Supprimer".                                                                | Le document disparaît de la liste associée au système.      | ok     |

### **3.3 Fonctionnalités pour les formateurs**
| ID Test | Description                     | Étapes                                                                                                                     | Résultat attendu                                                  | Statut |
| ------- | ------------------------------- | -------------------------------------------------------------------------------------------------------------------------- | ----------------------------------------------------------------- | ------ |
| T05     | Déposer un document pédagogique | 1. Se connecter en tant que formateur. <br> 2. Cliquer sur "Déposer un document pédagogique". <br> 3. Uploader un fichier. | Le document est visible dans la section "Documents pédagogiques". | ok     |
| T06     | Consulter les comptes rendus    | 1. Se connecter en tant que formateur. <br> 2. Naviguer vers "Comptes rendus". <br> 3. Sélectionner un compte rendu.       | Le contenu du compte rendu s'affiche.                             | ok     |

### **3.4 Fonctionnalités pour les apprentis**
| ID Test | Description                     | Étapes                                                                                                                            | Résultat attendu                                        | Statut |
| ------- | ------------------------------- | --------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------------- | ------ |
| T07     | Consulter un document technique | 1. Se connecter en tant qu'apprenti. <br> 2. Sélectionner un système. <br> 3. Cliquer sur un document spécifique.                 | Le contenu du document s'affiche.                       | ok     |
| T08     | Déposer un compte rendu         | 1. Se connecter en tant qu'apprenti. <br> 2. Naviguer vers "Déposer un compte rendu". <br> 3. Remplir le formulaire et soumettre. | Le compte rendu est soumis et visible par le formateur. | ok     |

### **3.5 Règles de gestion**
| ID Test | Description                         | Étapes                                                                                                             | Résultat attendu                                              | Statut |
| ------- | ----------------------------------- | ------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------- | ------ |
| T09     | Association unique document/machine | 1. Ajouter un document pour une machine. <br> 2. Vérifier qu'il n'est pas associé à une autre machine.             | Le document est uniquement associé à la machine sélectionnée. | ok     |
| T10     | Catégorisation des documents        | 1. Ajouter un document en spécifiant une catégorie (électrique, pneumatique, etc.). <br> 2. Vérifier sa catégorie. | Le document apparaît sous la bonne catégorie.                 | ok     |

---

## **4. Critères de validation**
- **Fonctionnalité** : Toutes les fonctionnalités doivent être opérationnelles selon les besoins exprimés.  
- **Ergonomie** : L'interface utilisateur doit être claire, intuitive et respecter la charte graphique.  
- **Sécurité** : Les utilisateurs doivent avoir accès uniquement aux fonctionnalités de leur rôle.  
- **Performance** : Les temps de réponse doivent être inférieurs à 2 secondes pour les actions principales.  

