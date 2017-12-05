-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  sam. 25 nov. 2017 à 19:51
-- Version du serveur :  10.1.28-MariaDB
-- Version de PHP :  7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `livewell`
--
CREATE DATABASE IF NOT EXISTS `livewell` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `livewell`;

-- --------------------------------------------------------

--
-- Structure de la table `actuators`
--

CREATE TABLE `actuators` (
  `id` int(11) NOT NULL,
  `action_type` enum('undefined') COLLATE utf8_unicode_ci NOT NULL,
  `last_action_started` datetime DEFAULT NULL,
  `current_situation` int(11) DEFAULT NULL,
  `peripheral_uuid` varchar(33) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('property','user','peripheral') COLLATE utf8_unicode_ci DEFAULT NULL,
  `severity` enum('DEBUG','INFO','WARNING','ERROR','FAILURE') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'DEBUG',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `filters`
--

CREATE TABLE `filters` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `sensor_id` int(11) NOT NULL,
  `actuator_id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `operator` enum('<','<=','>','>=','=','!=') COLLATE utf8_unicode_ci NOT NULL,
  `threshold` float NOT NULL,
  `actuator_params` float DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `measures`
--

CREATE TABLE `measures` (
  `id` int(11) NOT NULL,
  `type` enum('undefined') COLLATE utf8_unicode_ci NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `value` int(11) NOT NULL,
  `peripheral_uuid` varchar(33) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `destination_role_id` int(11) NOT NULL,
  `type` enum('','','','') COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `peripherals`
--

CREATE TABLE `peripherals` (
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `build_date` date NOT NULL,
  `add_date` datetime DEFAULT NULL,
  `public_key` varbinary(33) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `perms` enum('C-R-ADD','C-R-VIEW','C-R-MOD','C-S-VIEWRT','C-S-VIEWHIST','C-DASH','C-P-ADD','C-P-ID','C-P-ASSIGN','C-P-STATERT','C-P-STATEHIST','C-P-PARAMS','C-P-INTERNAL','C-A-PILOT','C-A-STATERT','C-A-STATEHIST','C-CAM-VIEWRT','C-CAM-VIEWHIST','C-CAM-MOVE','C-CAM-MOVE','C-FILTER-ADD','C-FILTER-MOD','C-FILTER-CRITDESACTIVATE','C-FILTER-ACT','C-FILTER-DEL','C-ACCOUNT-ADD','C-ACCOUNT-STATE','C-ACCOUNT-SHARE','C-ACCOUNT-MOD','C-ACCOUNT-DATAMOD','C-ACCOUNT-DATADOWN','C-ACCOUNT-DELEGATE','C-USER-ADD','C-USER-MOD','C-USER-RSTPWD','C-USER-DELEGATION','C-USER-2FA','C-USER-LOG','C-USER-CANCELSESS','C-USER-VIEWSESS','C-STORE-LIST','C-STORE-ADDCART','C-STORE-SELECTEXPEDITION','C-STORE-CHECKCOUT','C-STORE-TRACK','C-STORE-SUBSCRIBE','C-STORE-OPTIONS','C-MAINTENANCE-LOG','C-MAINTENANCE-CONTACT') COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `name` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sensors`
--

CREATE TABLE `sensors` (
  `id` int(11) NOT NULL,
  `sense_type` enum('undefined') COLLATE utf8_unicode_ci NOT NULL,
  `last_measure` int(11) DEFAULT NULL,
  `peripheral_uuid` varchar(33) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `started` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry` datetime NOT NULL,
  `canceled` tinyint(1) NOT NULL DEFAULT '0',
  `ip` varbinary(16) NOT NULL,
  `user_agent_txt` text COLLATE utf8_unicode_ci NOT NULL,
  `user_agent_hash` binary(32) NOT NULL COMMENT 'Hashed with SHA-256',
  `cookie` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cookie associated with this session',
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `command_id` int(11) DEFAULT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `display` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nick` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Via BCRYPT',
  `phone` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actuators`
--
ALTER TABLE `actuators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peripheral_id` (`peripheral_uuid`) USING BTREE;

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Index pour la table `filters`
--
ALTER TABLE `filters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `sensor_id` (`sensor_id`),
  ADD KEY `actuator_id` (`actuator_id`);

--
-- Index pour la table `measures`
--
ALTER TABLE `measures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peripheral_uuid` (`peripheral_uuid`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `DESTINATION` (`destination_role_id`);

--
-- Index pour la table `peripherals`
--
ALTER TABLE `peripherals`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Index pour la table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Index pour la table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PROPERTY` (`property_id`);

--
-- Index pour la table `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peripheral_uuid` (`peripheral_uuid`),
  ADD KEY `last_measure` (`last_measure`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cookie` (`cookie`),
  ADD KEY `user` (`user`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PROPERTY` (`property_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actuators`
--
ALTER TABLE `actuators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `filters`
--
ALTER TABLE `filters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `measures`
--
ALTER TABLE `measures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `sensors`
--
ALTER TABLE `sensors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `actuators`
--
ALTER TABLE `actuators`
  ADD CONSTRAINT `actuators_ibfk_1` FOREIGN KEY (`peripheral_uuid`) REFERENCES `peripherals` (`uuid`);

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Contraintes pour la table `filters`
--
ALTER TABLE `filters`
  ADD CONSTRAINT `filters_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Contraintes pour la table `measures`
--
ALTER TABLE `measures`
  ADD CONSTRAINT `measures_ibfk_1` FOREIGN KEY (`peripheral_uuid`) REFERENCES `peripherals` (`uuid`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`destination_role_id`) REFERENCES `roles` (`id`);

--
-- Contraintes pour la table `peripherals`
--
ALTER TABLE `peripherals`
  ADD CONSTRAINT `peripherals_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`),
  ADD CONSTRAINT `peripherals_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Contraintes pour la table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`),
  ADD CONSTRAINT `roles_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);

--
-- Contraintes pour la table `sensors`
--
ALTER TABLE `sensors`
  ADD CONSTRAINT `sensors_ibfk_1` FOREIGN KEY (`peripheral_uuid`) REFERENCES `peripherals` (`uuid`),
  ADD CONSTRAINT `sensors_ibfk_2` FOREIGN KEY (`last_measure`) REFERENCES `measures` (`id`);

--
-- Contraintes pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
