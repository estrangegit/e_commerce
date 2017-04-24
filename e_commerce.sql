-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Lun 23 Janvier 2017 à 08:12
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `sil08`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`id`, `id_categorie`, `libelle`, `prix`, `stock`, `image`) VALUES
(16, 1, 'Jeu Jura - Coffret bois 200 jeux acidulé', '39.99', 0, 'f859ef1889b9d24aa4b229a30a6265a3.jpeg'),
(17, 1, 'Zig zag Classic : Malette 100 jeux en bois', '25.99', 44, '1c79359528d87d31e07b772a58f1758d.jpeg'),
(18, 1, 'Zig Zag Classic: Jeu du solitaire plateau bois', '14.99', 30, 'eec87232f149d846f77d73066d6c600b.jpeg'),
(19, 1, 'Wood N Play: Train en bois PM', '12.99', 56, '527e9c65dae4a8da429d3cbd0103e7b5.jpeg'),
(20, 1, 'DAM SPRL: Echiquier magnétique en bois', '34.60', 61, '400ec5261e7b1264c02695e428a78f65.jpeg'),
(21, 2, 'LEGO Centre ville', '143.00', 39, '1e3a816be6148681e7ef0f511b8483ef.jpeg'),
(23, 2, 'MEGA Block: Prise de Contrôle Assassin\'s Creed', '47.00', 48, '0910bd1041f7f63ca6dc21639f0b2c10.jpeg'),
(24, 2, 'Seau 150 pièces', '24.99', 25, '22a9d4c27ea60d4f509a471cfcede2af.jpeg'),
(25, 2, 'LEGO: Obi-Wan-Kenobi', '19.00', 63, '93298837cade4eda32464c441f5d945a.jpeg'),
(26, 2, 'Beywheelz Arena Pegasus Stunt Stadium', '16.99', 54, '9265896b3964cfe8aa6e651daf8fcead.jpeg'),
(27, 3, 'Excavation Smilodon', '10.00', 7, '248e850595261f447735ce429f99f48d.jpeg'),
(28, 3, 'Générateur moulin à vent', '8.00', 88, '7b8e973ebac999c34102f9936300ae36.jpeg'),
(29, 3, '4M torche dynamo', '7.00', 67, 'f7a1e334e31631a26c3ae42e79bf01be.jpeg'),
(30, 3, 'Grand memory monde de Dory', '7.00', 58, 'd41fe354a2eb1d977dd906da87d4edc5.jpeg'),
(31, 4, 'Pix Sancho le Cochon', '3.00', 179, '5f2feaba8f9f7c386ab82627025d6693.jpeg'),
(32, 4, 'Playmais livre numéro 2', '1.50', 189, '60e3e70789e4919f32da854d1cd90443.jpeg'),
(33, 1, 'JANOD: Voiture bois Story Racing Speed', '6.99', 67, '26e69b82f7aa8496f05198eab8517055.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `id_article` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `note` int(11) NOT NULL,
  `commentaire` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `avis`
--

INSERT INTO `avis` (`id`, `id_article`, `id_client`, `note`, `commentaire`) VALUES
(10, 16, 22, 4, 'Dommage qu\'il n\'y ait plus de ce produit en stock...'),
(11, 18, 23, 5, 'Le meilleur jeu solitaire du monde!');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `intitule` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`id`, `intitule`) VALUES
(1, 'Jeux de société'),
(2, 'Jeux de construction'),
(3, 'Jeux éducatifs'),
(4, 'Jeux créatifs et puzzles');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `prenom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pseudo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mot_de_passe` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` longtext COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `client`
--

INSERT INTO `client` (`id`, `prenom`, `nom`, `pseudo`, `mot_de_passe`, `adresse`, `email`, `admin`) VALUES
(21, 'Albert', 'Einstein', 'albert', '$2y$12$LzSbokr6geSsTb0riFrhOuKx/tqYbHBp47S6nOld.kzAgfr0U4soG', 'Rue de la science atomique', 'albert.einstein@gmail.com', 0),
(22, 'Amélie', 'Poulain', 'amelie', '$2y$12$0xyMTq6IEoT.qmXf6a06Eeavkw8MAydcQwCvFcxdny/aMA4yU0zR2', 'Rue du cinéma français', 'amelie.poulain@gmail.com', 1),
(23, 'Hubert', 'Reeves', 'hubert', '$2y$12$R75RjAnu3Ukj8Z8S.12wAeY.Du0MxGPCUQS5kIKpsi4GTpw38gnya', 'Rue de la science atomique', 'hubert.reeves@gmail.com', 0),
(26, 'Jim', 'Carrey', 'jim', '$2y$12$sOA7PvQVsrnBBs5JLCGEferm05a3FAmIWrgK6Kp64ALcpjfjWYUL2', 'Rue du cinéma américain\r\nHollywood', 'jim.carrey@gmail.com', 0);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `etat` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `commande`
--

INSERT INTO `commande` (`id`, `id_client`, `date`, `etat`) VALUES
(32, 22, '2017-01-13 21:53:05', 'En préparation'),
(33, 22, '2017-01-13 21:57:48', 'En préparation'),
(34, 23, '2017-01-13 21:59:01', 'Validée'),
(35, 23, '2017-01-13 21:59:39', 'En préparation');

-- --------------------------------------------------------

--
-- Structure de la table `commande_article`
--

CREATE TABLE `commande_article` (
  `id_article` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `commande_article`
--

INSERT INTO `commande_article` (`id_article`, `id_commande`, `quantite`, `prix`) VALUES
(17, 34, 1, '25.99'),
(18, 32, 1, '14.99'),
(18, 33, 2, '14.99'),
(18, 34, 1, '14.99'),
(18, 35, 1, '14.99'),
(23, 32, 1, '47.00'),
(27, 34, 1, '10.00'),
(28, 33, 1, '8.00');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_23A0E66C9486A13` (`id_categorie`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F91ABF0DCA7A716` (`id_article`),
  ADD KEY `IDX_8F91ABF0E173B1B8` (`id_client`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6EEAA67DE173B1B8` (`id_client`);

--
-- Index pour la table `commande_article`
--
ALTER TABLE `commande_article`
  ADD PRIMARY KEY (`id_article`,`id_commande`),
  ADD KEY `IDX_F4817CC6DCA7A716` (`id_article`),
  ADD KEY `IDX_F4817CC63E314AE8` (`id_commande`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E66C9486A13` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id`);

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `FK_8F91ABF0DCA7A716` FOREIGN KEY (`id_article`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_8F91ABF0E173B1B8` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67DE173B1B8` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `commande_article`
--
ALTER TABLE `commande_article`
  ADD CONSTRAINT `FK_F4817CC63E314AE8` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_F4817CC6DCA7A716` FOREIGN KEY (`id_article`) REFERENCES `article` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
