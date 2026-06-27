-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 04 mai 2026 à 12:51
-- Version du serveur : 8.0.30
-- Version de PHP : 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `guilde_seigneurs`
--

-- --------------------------------------------------------

--
-- Structure de la table `building`
--

CREATE TABLE `building` (
  `id` int NOT NULL,
  `identifier` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caste` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `strength` smallint NOT NULL,
  `image` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creation` datetime NOT NULL,
  `modification` datetime NOT NULL,
  `price` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `building`
--

INSERT INTO `building` (`id`, `identifier`, `name`, `slug`, `caste`, `strength`, `image`, `creation`, `modification`, `price`) VALUES
(14, '3cd67c2e1bd44977270fbaa753658520372e5903', 'Château Silken', 'chateau-silken', 'Archer', 1200, '/buildings/chateau-silken.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', 240),
(15, '0542d1a609300e34be0ffc19d948473378a6428e', 'Château Antoleme', 'chateau-antoleme', 'Chevalier', 1400, '/buildings/chateau-antoleme.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', 280),
(16, '12dd33b840c3a6cc8163ee623993787a3a32dabe', 'Château Unflune', 'chateau-unflune', 'Elfe', 1600, '/buildings/chateau-unflune.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', 320),
(17, '0ec4c8ad342ee2bb26958a9783a885c06cbaddf6', 'Château Stamlam', 'chateau-stamlam', 'Magicien', 1800, '/buildings/chateau-stamlam.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', 360),
(18, '972ea9d824ebfb6730d64b2c71b4d83c914c19b2', 'Château Oakenfield', 'chateau-oakenfield', 'Erudit', 2000, '/buildings/chateau-oakenfield.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', 400),
(20, '32368a8fe749236b779893aa4857a70a37b44c8c', 'Château Kaerndal', 'chateau-kaerndal', 'Bourreau', 10000, '/buildings/chateau-kaerndal.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', NULL),
(21, 'b0f08b146c2c19554975807494766fd01ca99ee3', 'Château Merclefield', 'chateau-merclefield', 'Enchanteur', 15000, '/buildings/chateau-merclefield.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', NULL),
(22, 'a9defc841c7559be7274bced84da044317c8470b', 'Château Direnwood', 'chateau-direnwood', 'ElfeNoir', 15000, '/buildings/chateau-direnwood.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', NULL),
(23, '17120554bacd7391f7be78fbf931890935d6e090', 'Château Dragonspire', 'chateau-dragonspire', 'Sorcier', 20000, '/buildings/chateau-dragonspire.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', NULL),
(24, '39b1dce8c12fac010d355084c603f477ccf51241', 'Château Perrigwyn', 'chateau-perrigwyn', 'Mage', 20000, '/buildings/chateau-perrigwyn.webp', '2026-05-04 12:31:54', '2026-05-04 12:31:54', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `character`
--

CREATE TABLE `character` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  `slug` varchar(20) NOT NULL,
  `kind` varchar(20) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `caste` varchar(20) DEFAULT NULL,
  `knowledge` varchar(20) DEFAULT NULL,
  `intelligence` smallint DEFAULT NULL,
  `strength` smallint DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `creation` datetime NOT NULL,
  `identifier` varchar(40) NOT NULL,
  `modification` datetime NOT NULL,
  `building_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `character`
--

INSERT INTO `character` (`id`, `name`, `slug`, `kind`, `surname`, `caste`, `knowledge`, `intelligence`, `strength`, `image`, `creation`, `identifier`, `modification`, `building_id`) VALUES
(2, 'Anardil0', 'anardil0', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 185, 123, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '3609c5cce365988b95b62f17f65688a2c6fb8acc', '2026-05-04 12:31:54', NULL),
(3, 'Anardil1', 'anardil1', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 182, 188, '/dames/dame.webp', '2026-05-04 12:31:54', '1c761156f8e9bc5183f450aa26189bb4959bbd4e', '2026-05-04 12:31:54', NULL),
(4, 'Anardil2', 'anardil2', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 120, 158, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '258a429a3df66475238fad2e527a3b0f93d291bb', '2026-05-04 12:31:54', NULL),
(5, 'Anardil3', 'anardil3', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 172, 134, '/dames/dame.webp', '2026-05-04 12:31:54', '9fa6c69ca6047fbdbb8e4a77795e16478adfe034', '2026-05-04 12:31:54', NULL),
(6, 'Anardil4', 'anardil4', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 170, 136, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '8f3e82bafd2848bc821dcb29580a367719fbc2b6', '2026-05-04 12:31:54', NULL),
(7, 'Anardil5', 'anardil5', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 145, 146, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '185e3e3d41a605c9aba433c7e5287632950c9141', '2026-05-04 12:31:54', NULL),
(8, 'Anardil6', 'anardil6', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 193, 179, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '5beebefa9ffc80d5f2e2016474813c17cb16de7c', '2026-05-04 12:31:54', NULL),
(9, 'Anardil7', 'anardil7', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 149, 160, '/dames/dame.webp', '2026-05-04 12:31:54', 'c1ae74428b0b400f1d6073084dfc8836818afc12', '2026-05-04 12:31:54', NULL),
(10, 'Anardil8', 'anardil8', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 199, 141, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '1456cb4c16587d72b20dda0076bcfe6d036f49e6', '2026-05-04 12:31:54', NULL),
(11, 'Anardil9', 'anardil9', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 175, 112, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '1502a4de1e4e9e68125960a0d228261e64805ec1', '2026-05-04 12:31:54', NULL),
(12, 'Anardil10', 'anardil10', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 172, 121, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '26f20173eaf6964c4038d15c22aeb25e29345018', '2026-05-04 12:31:54', NULL),
(13, 'Anardil11', 'anardil11', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 157, 114, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '0561cbeccae0fa6f5f0604e6842b28889a2bf43f', '2026-05-04 12:31:54', NULL),
(14, 'Anardil12', 'anardil12', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 111, 178, '/dames/dame.webp', '2026-05-04 12:31:54', '122dce83ee14f2b4c5cd1a1b1f446e68d4b8130b', '2026-05-04 12:31:54', NULL),
(15, 'Anardil13', 'anardil13', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 119, 190, '/dames/dame.webp', '2026-05-04 12:31:54', 'ad25ca8f57044b70756e1fd2e5b2492493ae1bc6', '2026-05-04 12:31:54', NULL),
(16, 'Anardil14', 'anardil14', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 153, 172, '/dames/dame.webp', '2026-05-04 12:31:54', '874de3639a0682687f9004179b70e942af232cc3', '2026-05-04 12:31:54', NULL),
(17, 'Anardil15', 'anardil15', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 151, 160, '/dames/dame.webp', '2026-05-04 12:31:54', '9bd3f20bfe873bfff0b82e7f1ffc9dc8659a7827', '2026-05-04 12:31:54', NULL),
(18, 'Anardil16', 'anardil16', 'Seigneur', 'Amie du soleil', 'Magicien', 'Sciences', 187, 187, '/seigneurs/seigneur.webp', '2026-05-04 12:31:54', '900a6c3a434c0067e53d6891a3d70bdd37aee715', '2026-05-04 12:31:54', NULL),
(19, 'Anardil17', 'anardil17', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 154, 177, '/dames/dame.webp', '2026-05-04 12:31:54', '80d589529df166b5b55ccb9a68c96edaee0aa8a0', '2026-05-04 12:31:54', NULL),
(20, 'Anardil18', 'anardil18', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 170, 107, '/dames/dame.webp', '2026-05-04 12:31:54', '168cba922b500de5aa12309d64e823fbd4a81e6b', '2026-05-04 12:31:54', NULL),
(21, 'Anardil19', 'anardil19', 'Dame', 'Amie du soleil', 'Magicien', 'Sciences', 185, 181, '/dames/dame.webp', '2026-05-04 12:31:54', '6642794c54a03db0e7efaaa885951ad0234613c7', '2026-05-04 12:31:54', NULL),
(22, 'Calimehtar', 'calimehtar', 'Seigneur', 'Guerrier lumière', 'Guerrier', 'Nombres', 100, 100, '/seigneurs/calimehtar.webp', '2026-05-04 12:31:54', '7c2b3bcb9867ca14e8c22e789f089088ec73595d', '2026-05-04 12:31:54', NULL),
(23, 'Celeborn', 'celeborn', 'Seigneur', 'Arbre d\'argent', 'Archer', 'Cartographie', 120, 120, '/seigneurs/celeborn.webp', '2026-05-04 12:31:54', '8e3b3ec4bb88df7f6443e08d1fef7aac4e72607b', '2026-05-04 12:31:54', 14),
(25, 'Elendur', 'elendur', 'Seigneur', 'Serviteur des étoiles', 'Elfe', 'Arts', 160, 160, '/seigneurs/elendur.webp', '2026-05-04 12:31:54', 'ade2cc7a995336ede0427f96cd3c48c06f34d47a', '2026-05-04 12:31:54', NULL),
(26, 'Anfauglith', 'anfauglith', 'Seigneur', 'Poussière d\'agonie', 'Magicien', 'Sciences', 180, 180, '/seigneurs/anfauglith.webp', '2026-05-04 12:31:54', '9339e1f6c53029ea69aeac7ff0d5303020a23a5f', '2026-05-04 12:31:54', NULL),
(27, 'Turambar', 'turambar', 'Seigneur', 'Maître du destin', 'Erudit', 'Lettres', 200, 200, '/seigneurs/turambar.webp', '2026-05-04 12:31:54', 'db38b5dac6c2246750e18392b9f2cd39977a29a8', '2026-05-04 12:31:54', NULL),
(28, 'Athelleen', 'athelleen', 'Dame', 'Guerrière flamme', 'Guerrier', 'Nombres', 100, 100, '/dames/athelleen.webp', '2026-05-04 12:31:54', '93e37e70a907d731b93060c1a739983248470a60', '2026-05-04 12:31:54', NULL),
(29, 'Maeglin', 'maeglin', 'Dame', 'Oeil vif', 'Archer', 'Cartographie', 120, 120, '/dames/maeglin.webp', '2026-05-04 12:31:54', 'bde5c4549d73e5b9f42eeb7f09b8124b0d7b6637', '2026-05-04 12:31:54', NULL),
(30, 'Ñolofinwë', 'nolofinwe', 'Dame', 'Sagesse', 'Chevalier', 'Diplomatie', 140, 140, '/dames/nolofinwe.webp', '2026-05-04 12:31:54', '2a3cdc619e8901b477ff81b7faaa3e78a97b413f', '2026-05-04 12:31:54', NULL),
(31, 'Eldalotë', 'eldalote', 'Dame', 'Fleur elfique', 'Elfe', 'Arts', 160, 160, '/dames/eldalote.webp', '2026-05-04 12:31:54', '030e8dfde25c034dff8a12e317810babe2bff6b2', '2026-05-04 12:31:54', NULL),
(33, 'Rúmil', 'rumil', 'Dame', 'Savante', 'Erudit', 'Lettres', 200, 200, '/dames/rumil.webp', '2026-05-04 12:31:54', 'f8a74f129d338b3407a0b4a63090e618ad09de0f', '2026-05-04 12:31:54', NULL),
(34, 'Urdak', 'urdak', 'Tourmenteur', 'Dépeçeur', 'Gobelin', 'Nombres', 100, 1000, '/tourmenteurs/urdak.webp', '2026-05-04 12:31:54', '5cba25e24318c0569d367fb9b85e85506d609566', '2026-05-04 12:31:54', NULL),
(35, 'Gurthang', 'gurthang', 'Tourmenteur', 'Fer de la mort', 'Bourreau', 'Cartographie', 120, 1200, '/tourmenteurs/gurthang.webp', '2026-05-04 12:31:54', '92c8aa92211a0a58dd6a21058f5f90c3a9f36edf', '2026-05-04 12:31:54', NULL),
(36, 'Curunir', 'curunir', 'Tourmenteur', 'Stratège', 'Enchanteur', 'Diplomatie', 140, 1400, '/tourmenteurs/curunir.webp', '2026-05-04 12:31:54', 'f05015fa41a213234a441811d187bbf530a35ddc', '2026-05-04 12:31:54', NULL),
(37, 'Dagnir', 'dagnir', 'Tourmenteur', 'Égorgeur', 'ElfeNoir', 'Arts', 160, 1600, '/tourmenteurs/dagnir.webp', '2026-05-04 12:31:54', '7959ba358de2a9783cc6b0d0cace1be685f5f97a', '2026-05-04 12:31:54', NULL),
(38, 'Aranrùth', 'aranruth', 'Tourmenteur', 'Colère du roi', 'Sorcier', 'Sciences', 180, 1800, '/tourmenteurs/aranruth.webp', '2026-05-04 12:31:54', '367a08b8c8926df32348ac0563aa475d21607a64', '2026-05-04 12:31:54', NULL),
(39, 'Valaraukar', 'valaraukar', 'Tourmenteur', 'Démon', 'Mage', 'Lettres', 200, 2000, '/tourmenteurs/valaraukar.webp', '2026-05-04 12:31:54', '507c4e379a44acaf30f828fc942e3b4f26d8cee3', '2026-05-04 12:31:54', NULL),
(40, 'Grubgut', 'grubgut', 'Tourmenteuse', 'Assoiffée', 'Gobelin', 'Nombres', 100, 1000, '/tourmenteuses/grubgut.webp', '2026-05-04 12:31:54', '7edee9f5103edbe1223f3278778c8c9f7008c871', '2026-05-04 12:31:54', NULL),
(41, 'Firiel', 'firiel', 'Tourmenteuse', 'Mortelle', 'Bourreau', 'Cartographie', 120, 1200, '/tourmenteuses/firiel.webp', '2026-05-04 12:31:54', '2c786b72bc5ec42430347f0215d310fa007951f5', '2026-05-04 12:31:54', NULL),
(42, 'Laurelin', 'laurelin', 'Tourmenteuse', 'Ensorceleuse', 'Enchanteur', 'Diplomatie', 140, 1400, '/tourmenteuses/laurelin.webp', '2026-05-04 12:31:54', '8d304f131278fa2ec781dfa7606c2eb504f5fe38', '2026-05-04 12:31:54', NULL),
(43, 'Fëanturi', 'feanturi', 'Tourmenteuse', 'Fascination', 'ElfeNoir', 'Arts', 160, 1600, '/tourmenteuses/feanturi.webp', '2026-05-04 12:31:54', 'c37faa0c9f27f6ff99af9c799d46c8dca8666e95', '2026-05-04 12:31:54', NULL),
(44, 'Tinúviel', 'tinuviel', 'Tourmenteuse', 'Crépuscule', 'Sorcier', 'Sciences', 180, 1800, '/tourmenteuses/tinuviel.webp', '2026-05-04 12:31:54', '0c3e4b4e72be8ebc365ff387d5454a3ffe9721a2', '2026-05-04 12:31:54', NULL),
(45, 'Kementari', 'kementari', 'Tourmenteuse', 'Reine noire', 'Mage', 'Lettres', 200, 2000, '/tourmenteuses/kementari.webp', '2026-05-04 12:31:54', '41805888ddb8e75d54e13cc46b8e4fc69bd0feb9', '2026-05-04 12:31:54', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260330113012', '2026-05-04 12:31:53', 11),
('DoctrineMigrations\\Version20260330140000', '2026-05-04 12:31:53', 2),
('DoctrineMigrations\\Version20260330153000', '2026-05-04 12:31:53', 1),
('DoctrineMigrations\\Version20260330170000', '2026-05-04 12:31:53', 58),
('DoctrineMigrations\\Version20260330180000', '2026-05-04 12:31:53', 27),
('DoctrineMigrations\\Version20260504000000', '2026-05-04 12:31:53', 83),
('DoctrineMigrations\\Version20260504120000', '2026-05-04 12:31:53', 16),
('DoctrineMigrations\\Version20260504200000', '2026-05-04 12:31:53', 62),
('DoctrineMigrations\\Version20260504210000', '2026-05-04 12:31:53', 11),
('DoctrineMigrations\\Version20260505140000', '2026-05-04 12:31:53', 21),
('DoctrineMigrations\\Version20260506100000', '2026-05-04 12:31:53', 110),
('DoctrineMigrations\\Version20260506104500', '2026-05-04 12:31:53', 19);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `building`
--
ALTER TABLE `building`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_BUILDING_IDENTIFIER` (`identifier`),
  ADD UNIQUE KEY `UNIQ_BUILDING_SLUG` (`slug`);

--
-- Index pour la table `character`
--
ALTER TABLE `character`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_CHARACTER_IDENTIFIER` (`identifier`),
  ADD UNIQUE KEY `UNIQ_CHARACTER_SLUG` (`slug`),
  ADD KEY `IDX_937AB0344D2A7E12` (`building_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `building`
--
ALTER TABLE `building`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `character`
--
ALTER TABLE `character`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `character`
--
ALTER TABLE `character`
  ADD CONSTRAINT `FK_CHARACTER_BUILDING` FOREIGN KEY (`building_id`) REFERENCES `building` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
