-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 28 mai 2020 à 21:13
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `secu_quizz`
--

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_answer` tinyint(1) NOT NULL,
  `questions_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `competences`
--

CREATE TABLE `competences` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `families_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `families`
--

CREATE TABLE `families` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `niveau_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200523224006', '2020-05-23 22:40:22'),
('20200523231422', '2020-05-23 23:14:37'),
('20200523234610', '2020-05-23 23:46:35'),
('20200525194506', '2020-05-25 19:45:25'),
('20200525204607', '2020-05-25 20:46:30'),
('20200526233738', '2020-05-26 23:37:59'),
('20200527225718', '2020-05-27 22:57:38'),
('20200527230412', '2020-05-27 23:04:46'),
('20200527232358', '2020-05-27 23:24:05'),
('20200527233133', '2020-05-27 23:31:57'),
('20200528094734', '2020-05-28 09:47:46'),
('20200528143812', '2020-05-28 14:38:20'),
('20200528145821', '2020-05-28 14:58:26'),
('20200528150152', '2020-05-28 15:02:01');

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

CREATE TABLE `niveau` (
  `id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `niveau`
--

INSERT INTO `niveau` (`id`, `title`, `ordre`) VALUES
(1, 'Service', 1),
(2, 'Famille', 2),
(3, 'Sous-famille', 3);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attached` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `texte_complementaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autre_texte` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `motif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_id` int(11) NOT NULL,
  `competences_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `questions_types`
--

CREATE TABLE `questions_types` (
  `questions_id` int(11) NOT NULL,
  `types_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activation_token` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `roles`, `password`, `activation_token`, `reset_token`) VALUES
(1, 'nico@tsu.mg', '{\"1\":\"ROLE_EDITOR\"}', '$argon2id$v=19$m=65536,t=4,p=1$MTdHT0xpM0dXOWpXN2lSeg$/5ZE83TGAy6lavqgSuuc4JSktnpSh6P4AvUgfnKUNMg', NULL, NULL),
(2, 'nico@nico.fr', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$aHRYUC9MUW1vV1ZrdDZ2Ug$gOqAbPLMqsbXYhRppsk2FfCCvwstv+YKeQ3E8q/EHX4', NULL, NULL),
(3, 'zah@zah.com', '{\"2\":\"ROLE_USER\"}', 'wxcvbn', NULL, NULL),
(4, 'test@test.fr', '[]', '$argon2id$v=19$m=65536,t=4,p=1$LllWYnkxWHUwbU9ocjRzZg$zyHi28P/6VtEwjqgxhAo/Ctx5XeS9/AYCQz6x9ekeqA', NULL, NULL),
(5, 'azerty@azerty.mg', '[]', '$argon2id$v=19$m=65536,t=4,p=1$SjFiajlSNU13bU9MMzFJNg$K1cd5IWdJTYMlAc5AeSQ7swNqk3IhsblF6RxDmwc0D4', NULL, NULL),
(6, 'admin@admin.fr', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$QmhOYWNobTdZY0JhRUxoWA$7+3Mrh19wvhKY1uec40BfJvlxp8u4GV5Z9S6Hn+XFDw', NULL, NULL),
(7, 'nico2@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$dUhua0UyMFc0V050OWp1NQ$XTuFdHAa4ueBPVPW92qqlP5RGPF0Hgtwuzh+RxR61p4', NULL, NULL),
(8, 'nico3@nico.fr', '[]', '$argon2id$v=19$m=65536,t=4,p=1$TTRST3RpR2E1Q1IvNmtwbw$v5UtXBqvulGatoDdmUB9LktXE+3Rsw1K5lEecNYTftI', '1fb88634b2dc43b048461a18a2d44346', NULL),
(9, 'nico4@nico.fr', '[\"ROLE_USER\",\"ROLE_EDITOR\"]', '$argon2id$v=19$m=65536,t=4,p=1$b2o4RmdDTU1ZZlVrMzh0Ng$uTuzfuGmOMkmPf0jCGoagQS58vjrgHCXb+5pfblQKAU', NULL, NULL),
(10, 'aurelie@gmail.com', '[\"ROLE_EDITOR\",\"ROLE_USER\",\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$UjNTLnljajRWUE5aWGYvdw$qWaUBTa1b8D5noRQMn1B/PUItuOmvm21UH1niPqdQOY', '31b7565c96232b72dc8b567f66e2657c', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users_niveau`
--

CREATE TABLE `users_niveau` (
  `users_id` int(11) NOT NULL,
  `niveau_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users_niveau`
--

INSERT INTO `users_niveau` (`users_id`, `niveau_id`) VALUES
(3, 3),
(9, 2),
(9, 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_50D0C606BCB134CE` (`questions_id`);

--
-- Index pour la table `competences`
--
ALTER TABLE `competences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DB2077CE5DFECCD4` (`families_id`);

--
-- Index pour la table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_995F3FCC67B3B43D` (`users_id`),
  ADD KEY `IDX_995F3FCC727ACA70` (`parent_id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `niveau`
--
ALTER TABLE `niveau`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8ADC54D567B3B43D` (`users_id`),
  ADD KEY `IDX_8ADC54D5A660B158` (`competences_id`);

--
-- Index pour la table `questions_types`
--
ALTER TABLE `questions_types`
  ADD PRIMARY KEY (`questions_id`,`types_id`),
  ADD KEY `IDX_DC48194CBCB134CE` (`questions_id`),
  ADD KEY `IDX_DC48194C8EB23357` (`types_id`);

--
-- Index pour la table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`);

--
-- Index pour la table `users_niveau`
--
ALTER TABLE `users_niveau`
  ADD PRIMARY KEY (`users_id`,`niveau_id`),
  ADD KEY `IDX_44387DFB67B3B43D` (`users_id`),
  ADD KEY `IDX_44387DFBB3E9C81` (`niveau_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `competences`
--
ALTER TABLE `competences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `families`
--
ALTER TABLE `families`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `niveau`
--
ALTER TABLE `niveau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `FK_50D0C606BCB134CE` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`);

--
-- Contraintes pour la table `competences`
--
ALTER TABLE `competences`
  ADD CONSTRAINT `FK_DB2077CE5DFECCD4` FOREIGN KEY (`families_id`) REFERENCES `families` (`id`);

--
-- Contraintes pour la table `families`
--
ALTER TABLE `families`
  ADD CONSTRAINT `FK_995F3FCC67B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_995F3FCC727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `families` (`id`);

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `FK_8ADC54D567B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_8ADC54D5A660B158` FOREIGN KEY (`competences_id`) REFERENCES `competences` (`id`);

--
-- Contraintes pour la table `questions_types`
--
ALTER TABLE `questions_types`
  ADD CONSTRAINT `FK_DC48194C8EB23357` FOREIGN KEY (`types_id`) REFERENCES `types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_DC48194CBCB134CE` FOREIGN KEY (`questions_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users_niveau`
--
ALTER TABLE `users_niveau`
  ADD CONSTRAINT `FK_44387DFB67B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_44387DFBB3E9C81` FOREIGN KEY (`niveau_id`) REFERENCES `niveau` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
