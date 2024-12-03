-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 03 déc. 2024 à 10:13
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet1`
--

-- --------------------------------------------------------

--
-- Structure de la table `apprenti`
--

CREATE TABLE `apprenti` (
  `Mail` varchar(50) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `Mot_de_passe` varchar(255) NOT NULL,
  `Promotion` int(11) NOT NULL,
  `id_apprenti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `apprenti_devoir`
--

CREATE TABLE `apprenti_devoir` (
  `Apprenti` int(11) NOT NULL,
  `Devoir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `document_pedagogique`
--

CREATE TABLE `document_pedagogique` (
  `id_pedagogique` int(11) NOT NULL,
  `Systeme_concerne` int(11) NOT NULL,
  `Date_Document` date NOT NULL,
  `Type_document` enum('DEVOIR','CONSIGNE') NOT NULL,
  `Doc_file` varchar(50) NOT NULL,
  `id_matiere` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `document_pedagogique`
--

INSERT INTO `document_pedagogique` (`id_pedagogique`, `Systeme_concerne`, `Date_Document`, `Type_document`, `Doc_file`, `id_matiere`) VALUES
(6, 4, '2024-11-15', 'DEVOIR', 'TP1 - connectivité.pdf', 3),
(7, 4, '2024-11-13', 'DEVOIR', 'bts sio cv final.pdf', 1);

-- --------------------------------------------------------

--
-- Structure de la table `document_technique`
--

CREATE TABLE `document_technique` (
  `Version` varchar(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `Categorie` enum('Schema technique','Notices','Annexes','Presentation') NOT NULL,
  `id_document` int(11) NOT NULL,
  `Doc_file` varchar(50) NOT NULL,
  `Systeme_concerne` int(11) NOT NULL,
  `Nom_doc_tech` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `document_technique`
--

INSERT INTO `document_technique` (`Version`, `Date`, `Categorie`, `id_document`, `Doc_file`, `Systeme_concerne`, `Nom_doc_tech`) VALUES
('5', 2024, 'Notices', 5, 'memento_python_2.pdf', 4, 'la notice'),
('5', 2024, 'Presentation', 6, '02-Operateurs_et_Conditionnelles (1).pdf', 4, 'presentest'),
('5', 2024, 'Annexes', 8, 'TD5_GrapheL2 corrige.pdf', 4, 'azertyu'),
('5', 2024, 'Presentation', 9, '02-Operateurs_et_Conditionnelles (1).pdf', 4, 'azertyuy'),
('5', 2024, 'Schema technique', 12, 'memento_python_2.pdf', 4, 'dxdgcfj'),
('5', 2024, 'Presentation', 13, 'transparents_calculatricePython.pdf', 5, 'dxdgcfj');

-- --------------------------------------------------------

--
-- Structure de la table `fabriquant`
--

CREATE TABLE `fabriquant` (
  `Nom` varchar(50) NOT NULL,
  `Tel` int(11) NOT NULL,
  `Adresse` varchar(50) NOT NULL,
  `Siret` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fabriquant`
--

INSERT INTO `fabriquant` (`Nom`, `Tel`, `Adresse`, `Siret`) VALUES
('Fabriquant Test', 1234567890, '123 Rue de Test', '12345678901234');

-- --------------------------------------------------------

--
-- Structure de la table `formateur`
--

CREATE TABLE `formateur` (
  `Mail` varchar(50) NOT NULL,
  `Mot_de_passe` varchar(255) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `id_formateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `formateur_devoir`
--

CREATE TABLE `formateur_devoir` (
  `Formateur` int(11) NOT NULL,
  `Devoir` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `id_matiere` int(11) NOT NULL,
  `Nom_matiere` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`id_matiere`, `Nom_matiere`) VALUES
(1, 'Mathématiques'),
(2, 'Physique'),
(3, 'Informatique');

-- --------------------------------------------------------

--
-- Structure de la table `systeme`
--

CREATE TABLE `systeme` (
  `Nom_du_systeme` varchar(30) NOT NULL,
  `id_systeme` int(11) NOT NULL,
  `date_derniere_mise_a_jour` date NOT NULL,
  `image_systeme` varchar(50) NOT NULL,
  `Numero_de_serie` varchar(30) NOT NULL,
  `Fabriquant` varchar(50) NOT NULL,
  `Date_fabrication` date NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `systeme`
--

INSERT INTO `systeme` (`Nom_du_systeme`, `id_systeme`, `date_derniere_mise_a_jour`, `image_systeme`, `Numero_de_serie`, `Fabriquant`, `Date_fabrication`, `Description`) VALUES
('test2', 3, '2024-11-04', 'Capture d’écran errreur 2023-01-24 100534.png', '2024', '12345678901234', '2024-11-04', 'AZERTYUI'),
('test3', 4, '2024-11-04', 'Capture d’écran (2).png', '2024678', '12345678901234', '2024-11-04', 'azertyui'),
('test 4', 5, '2024-11-04', 'Capture d’écran (8).png', '2024678', '12345678901234', '2024-11-04', 'azertyuhgfd'),
('test 5', 6, '2024-11-06', 'Capture d’écran (5).png', '202467834', '12345678901234', '2024-11-06', 'azerfdszefdsdzdezezfsqzefzeffzfzef');

-- --------------------------------------------------------

--
-- Structure de la table `systeme_formateur`
--

CREATE TABLE `systeme_formateur` (
  `Formateur` int(11) NOT NULL,
  `Systeme` int(11) NOT NULL,
  `Date_action` date NOT NULL,
  `Type_action` enum('AJOUT','MODIFICATION','CONSULTATION') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `systeme_matiere`
--

CREATE TABLE `systeme_matiere` (
  `id_systeme` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `apprenti`
--
ALTER TABLE `apprenti`
  ADD PRIMARY KEY (`id_apprenti`);

--
-- Index pour la table `apprenti_devoir`
--
ALTER TABLE `apprenti_devoir`
  ADD KEY `Apprenti` (`Apprenti`),
  ADD KEY `Devoir` (`Devoir`);

--
-- Index pour la table `document_pedagogique`
--
ALTER TABLE `document_pedagogique`
  ADD PRIMARY KEY (`id_pedagogique`),
  ADD KEY `Systeme_concerne` (`Systeme_concerne`),
  ADD KEY `id_matiere` (`id_matiere`);

--
-- Index pour la table `document_technique`
--
ALTER TABLE `document_technique`
  ADD PRIMARY KEY (`id_document`),
  ADD KEY `Systeme_concerne` (`Systeme_concerne`);

--
-- Index pour la table `fabriquant`
--
ALTER TABLE `fabriquant`
  ADD PRIMARY KEY (`Siret`);

--
-- Index pour la table `formateur`
--
ALTER TABLE `formateur`
  ADD PRIMARY KEY (`id_formateur`);

--
-- Index pour la table `formateur_devoir`
--
ALTER TABLE `formateur_devoir`
  ADD KEY `Devoir` (`Devoir`),
  ADD KEY `Formateur` (`Formateur`);

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`id_matiere`);

--
-- Index pour la table `systeme`
--
ALTER TABLE `systeme`
  ADD PRIMARY KEY (`id_systeme`),
  ADD KEY `Fabriquant` (`Fabriquant`);

--
-- Index pour la table `systeme_formateur`
--
ALTER TABLE `systeme_formateur`
  ADD KEY `Formateur` (`Formateur`),
  ADD KEY `Systeme` (`Systeme`);

--
-- Index pour la table `systeme_matiere`
--
ALTER TABLE `systeme_matiere`
  ADD PRIMARY KEY (`id_systeme`,`id_matiere`),
  ADD KEY `id_matiere` (`id_matiere`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `apprenti`
--
ALTER TABLE `apprenti`
  MODIFY `id_apprenti` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `document_pedagogique`
--
ALTER TABLE `document_pedagogique`
  MODIFY `id_pedagogique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `document_technique`
--
ALTER TABLE `document_technique`
  MODIFY `id_document` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `formateur`
--
ALTER TABLE `formateur`
  MODIFY `id_formateur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matiere`
--
ALTER TABLE `matiere`
  MODIFY `id_matiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `systeme`
--
ALTER TABLE `systeme`
  MODIFY `id_systeme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `apprenti_devoir`
--
ALTER TABLE `apprenti_devoir`
  ADD CONSTRAINT `apprenti_devoir_ibfk_1` FOREIGN KEY (`Apprenti`) REFERENCES `apprenti` (`id_apprenti`),
  ADD CONSTRAINT `apprenti_devoir_ibfk_2` FOREIGN KEY (`Devoir`) REFERENCES `document_pedagogique` (`id_pedagogique`);

--
-- Contraintes pour la table `document_pedagogique`
--
ALTER TABLE `document_pedagogique`
  ADD CONSTRAINT `document_pedagogique_ibfk_1` FOREIGN KEY (`Systeme_concerne`) REFERENCES `systeme` (`id_systeme`),
  ADD CONSTRAINT `document_pedagogique_ibfk_2` FOREIGN KEY (`id_matiere`) REFERENCES `matiere` (`id_matiere`);

--
-- Contraintes pour la table `document_technique`
--
ALTER TABLE `document_technique`
  ADD CONSTRAINT `document_technique_ibfk_1` FOREIGN KEY (`Systeme_concerne`) REFERENCES `systeme` (`id_systeme`);

--
-- Contraintes pour la table `formateur_devoir`
--
ALTER TABLE `formateur_devoir`
  ADD CONSTRAINT `formateur_devoir_ibfk_1` FOREIGN KEY (`Devoir`) REFERENCES `document_pedagogique` (`id_pedagogique`),
  ADD CONSTRAINT `formateur_devoir_ibfk_2` FOREIGN KEY (`Formateur`) REFERENCES `formateur` (`id_formateur`);

--
-- Contraintes pour la table `systeme`
--
ALTER TABLE `systeme`
  ADD CONSTRAINT `systeme_ibfk_1` FOREIGN KEY (`Fabriquant`) REFERENCES `fabriquant` (`Siret`);

--
-- Contraintes pour la table `systeme_formateur`
--
ALTER TABLE `systeme_formateur`
  ADD CONSTRAINT `systeme_formateur_ibfk_1` FOREIGN KEY (`Formateur`) REFERENCES `formateur` (`id_formateur`),
  ADD CONSTRAINT `systeme_formateur_ibfk_2` FOREIGN KEY (`Systeme`) REFERENCES `systeme` (`id_systeme`);

--
-- Contraintes pour la table `systeme_matiere`
--
ALTER TABLE `systeme_matiere`
  ADD CONSTRAINT `systeme_matiere_ibfk_1` FOREIGN KEY (`id_systeme`) REFERENCES `systeme` (`id_systeme`),
  ADD CONSTRAINT `systeme_matiere_ibfk_2` FOREIGN KEY (`id_matiere`) REFERENCES `matiere` (`id_matiere`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
