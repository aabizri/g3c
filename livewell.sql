-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  mer. 27 déc. 2017 à 18:36
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

-- --------------------------------------------------------

--
-- Structure de la table `actuators`
--
-- Création :  mer. 27 déc. 2017 à 17:31
--

CREATE TABLE `actuators` (
  `id` int(11) NOT NULL,
  `action_type` enum('undefined') COLLATE utf8_unicode_ci NOT NULL,
  `last_action_started` timestamp(3) NULL DEFAULT NULL,
  `last_measure_id` int(11) DEFAULT NULL COMMENT 'A measure ID',
  `peripheral_uuid` varchar(33) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `actuators`:
--   `peripheral_uuid`
--       `peripherals` -> `uuid`
--   `last_measure_id`
--       `measures` -> `id`
--

-- --------------------------------------------------------

--
-- Structure de la table `events`
--
-- Création :  mar. 12 déc. 2017 à 13:59
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `creation_date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `type` enum('property','user','peripheral') COLLATE utf8_unicode_ci DEFAULT NULL,
  `severity` enum('DEBUG','INFO','WARNING','ERROR','FAILURE') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'DEBUG',
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `events`:
--   `property_id`
--       `properties` -> `id`
--

-- --------------------------------------------------------

--
-- Structure de la table `filters`
--
-- Création :  mar. 12 déc. 2017 à 13:59
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
  `creation_date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `filters`:
--   `property_id`
--       `properties` -> `id`
--

-- --------------------------------------------------------

--
-- Structure de la table `measures`
--
-- Création :  mer. 27 déc. 2017 à 17:29
--

CREATE TABLE `measures` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `date_time` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `value` double NOT NULL,
  `sensor_id` int(11) DEFAULT NULL,
  `actuator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `measures`:
--   `actuator_id`
--       `actuators` -> `id`
--   `sensor_id`
--       `sensors` -> `id`
--   `type_id`
--       `measure_types` -> `ID`
--

-- --------------------------------------------------------

--
-- Structure de la table `measure_types`
--
-- Création :  mer. 27 déc. 2017 à 17:29
--

CREATE TABLE `measure_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Par exemple: "Température °C"',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Par exemple: "Température en Celsius"',
  `unit_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Par exemple: "Celsius"',
  `unit_symbol` varchar(6) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Par exemple: "°C"',
  `min` double DEFAULT NULL COMMENT 'Valeur minimale (opt)',
  `max` double DEFAULT NULL COMMENT 'Valeur maximale (opt)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `measure_types`:
--

--
-- Déchargement des données de la table `measure_types`
--

INSERT INTO `measure_types` (`id`, `name`, `description`, `unit_name`, `unit_symbol`, `min`, `max`) VALUES
(1, 'Température (*C)', 'Température en degrés Celsius', 'Celsius', '°C', -274, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--
-- Création :  mar. 12 déc. 2017 à 13:58
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `destination_role_id` int(11) NOT NULL,
  `type` enum('','','','') COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `notifications`:
--   `destination_role_id`
--       `roles` -> `id`
--

-- --------------------------------------------------------

--
-- Structure de la table `peripherals`
--
-- Création :  mar. 12 déc. 2017 à 13:58
--

CREATE TABLE `peripherals` (
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `build_date` date DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `public_key` varbinary(33) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `peripherals`:
--   `property_id`
--       `properties` -> `id`
--   `room_id`
--       `rooms` -> `id`
--

--
-- Déchargement des données de la table `peripherals`
--

INSERT INTO `peripherals` (`uuid`, `display_name`, `build_date`, `add_date`, `public_key`, `property_id`, `room_id`, `last_updated`) VALUES
('017de9be-3fe8-4613-98b1-d0eeefbe4887', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:45:33.000'),
('02c7de4f-5bea-4694-86ba-90116acc8d19', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 11:53:45', NULL, 1, 1, '2017-12-05 10:53:45.000'),
('02effb34-ffd9-4e8b-88f5-a6e73d002b07', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:56:59.000'),
('042303f8-3ae7-49cd-851e-9b789523808e', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:32.000'),
('061ab7ac-abd8-445a-8e8b-d61dea5ff660', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:45:21.000'),
('06ac3b07-9383-433a-90fc-78a7f264f6cc', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:00:24.000'),
('0700b934-fee1-4241-a75b-7d3f5bda68b4', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:00.000'),
('0a7f9bb0-9f0f-44ee-93a5-19a267c4a90f', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:12.000'),
('0b4095e2-a49d-462a-9233-a4454717fd53', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:06.000'),
('0c89e5ba-661e-4844-b1d9-cf71217a2054', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:39:26.000'),
('0d2e680e-6138-492c-9542-387939ccb163', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:56:59.000'),
('0e14dd13-54ab-48a6-8e91-382a0e6cf2a5', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:55:50.000'),
('14cfe992-20e9-4907-8ebc-d2df09c08f12', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:14:50', NULL, 1, 1, '2017-12-04 23:14:50.000'),
('1759790b-94a9-4b2c-ac52-18f118d16939', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:56.000'),
('17a0b2b4-7d3c-4447-9524-e2cb5095a204', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:21:18', NULL, 1, 1, '2017-12-04 23:21:18.000'),
('187f2f65-4853-40d3-8e61-cd510a242d51', NULL, NULL, NULL, NULL, NULL, NULL, '2017-12-04 10:11:23.000'),
('193d596f-0f89-42c3-b10b-248ad6dc4601', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:57:04.000'),
('19aace54-c10b-4a13-af60-b0266e134b92', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:44:14.000'),
('1b5df48b-6262-489a-b326-0446cf3db7e3', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:46:37.000'),
('1d08bb7e-ffef-424a-87df-bdaa29b8e5f5', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 10:23:38.000'),
('1e45bbc1-24cd-414a-8ce3-bce2a8ed2c0e', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 10:24:54.000'),
('21f13780-0542-4e7d-82b1-68e281025ea4', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 13:07:18.000'),
('24af685b-a772-48b5-b74f-b307647aff1a', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:53:13.000'),
('263c6da4-be0f-42d1-b0c5-f0d2f8893bb4', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:25.000'),
('27a18572-eba0-4059-be6b-257ba81b1103', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:17:56', NULL, 1, 1, '2017-12-04 23:17:56.000'),
('28ec3922-6755-4909-8ed8-fe6430a24dd6', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:30.000'),
('29a63a6a-b518-479b-9952-3724cffd9dec', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:31:27.000'),
('29be10d1-b531-4800-a178-c4a165130601', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:17:38', NULL, 1, 1, '2017-12-04 23:17:38.000'),
('2aff0c9d-2f4d-4935-b1d7-b080cb41dd82', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:44.000'),
('2c2a4858-c98b-4323-a3e7-7353e986d566', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:49:44.000'),
('2ec3686d-d7fa-4c13-8ef9-4cc2ba2463da', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:13:18', NULL, 1, 1, '2017-12-04 23:13:18.000'),
('2f8cbee4-aaa1-4a39-9bf0-78e80ffd4a80', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:25.000'),
('2fb157df-6093-4c21-9966-347daf3c4043', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:39:15.000'),
('313ed8dc-132a-4b9f-96a2-584a5cde4210', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:06.000'),
('31474bb2-baa7-4349-8819-bf6701135848', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:44:34.000'),
('31808a60-be50-4f10-a062-7aaf68258a39', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:58:45.000'),
('31ebbc78-c2e0-43e5-aa07-515e6395735a', NULL, '2017-11-20', NULL, 0x4172726179, NULL, NULL, '2017-11-20 15:43:26.000'),
('3446197b-37ef-4b22-834f-74e66740a70c', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:12:25', NULL, 1, 1, '2017-12-04 23:12:25.000'),
('3587aa97-033f-4db2-a3b3-98d9e002f80c', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:30.000'),
('37923ac4-2a03-4dc5-bbc9-ecda889432f9', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-04 23:27:25', NULL, 1, 1, '2017-12-04 22:27:25.000'),
('37f3bc7b-a655-4867-acc5-14e06c16e4e6', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:23.000'),
('39573735-f342-4a04-931a-d398f961a824', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:02.000'),
('398582ad-a75f-4c2e-909e-b0a09d17c716', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:28:53.000'),
('4283b160-be44-4548-9344-1fd8368fbbd7', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:56.000'),
('42f1f84d-01b1-410c-9655-3155a7ff994e', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:44:57.000'),
('43769435-28dd-4ea8-a896-29865f780b8b', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:26.000'),
('44fd0002-1f58-471e-9ed7-85f89e00b8ad', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:58.000'),
('458336f0-e701-4db3-aedf-ccef8ebcb772', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:57:40.000'),
('4598d81c-3a22-4aaa-8351-d9abcc71234c', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:58:45.000'),
('461fa647-1e2d-472a-af0f-1fca4646b163', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:50:07.000'),
('467d5b8e-1631-460a-9c91-3a34c30a0cd6', NULL, '2017-11-20', NULL, '', 1, 1, '2017-11-20 16:52:48.000'),
('469160e9-11ff-43e5-be55-18d03aa1e5a3', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:27.000'),
('4797dfb7-403a-4bc5-83fe-534af4e537d0', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:26.000'),
('47e337ee-a96a-4c88-9277-308d39e35af2', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:31:08.000'),
('4b9828f1-47aa-49fe-8397-1bd677f3f02c', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 10:58:47.000'),
('4ba162e4-2442-4669-a02c-01ca27eecef2', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:44:13.000'),
('4beb4d63-24a7-429f-9dfb-57a9bdedcae3', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:13:42', NULL, 1, 1, '2017-12-04 23:13:42.000'),
('4c59f114-ea2f-49c6-bb13-6c748e3756f6', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:37:58.000'),
('4def2b70-de42-48ff-8bba-43deef49b970', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:39:57.000'),
('52adc478-b794-4083-93b9-012e5577b5cf', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:37:06.000'),
('53cd17da-1f44-418e-9d4b-1e5cdfa8eac6', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:58.000'),
('544afddd-7174-4aa5-8f5d-af7470486856', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:14.000'),
('5557b9fc-2338-425d-b93b-64b56a2f00ab', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:01.000'),
('56d5a87e-d3b0-4bd1-820b-a25bf1ebc462', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 10:58:29.000'),
('58e9bedd-caa9-4944-b11c-07970c4bc9e9', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:09.000'),
('59a5ca3f-fce8-44ed-95c1-29adeddc281c', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:29.000'),
('5aee36ce-9fef-4f2b-bab6-f1aab9426f0b', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:24.000'),
('5c2e81c2-3041-4b62-a6dc-ba5c0d9eb60a', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:47:06.000'),
('5d692d20-4081-41c4-bb9e-ed0be14d08df', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:55:50.000'),
('5de95a06-5fe0-4e8e-b89a-f0b0b27cd91b', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 10:24:24.000'),
('607ff0e5-6f00-484d-80db-09b828b9c367', NULL, '2017-11-20', NULL, '', 1, NULL, '2017-11-20 16:50:47.000'),
('60961638-2549-477b-aab7-263f671876c2', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:38:57.000'),
('61c77003-fb15-41b1-b6d9-73577ecab13a', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:16:21', NULL, 1, 1, '2017-12-04 23:16:21.000'),
('61f5dd5d-f153-40a1-858f-7e827c0ee1d2', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:06.000'),
('62c8b4d5-1a66-49f9-8d5b-cf954824dae1', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:55:51.000'),
('62e8a257-83fa-4970-9f64-469813b23991', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:26.000'),
('630ef55e-7cd3-4280-9a4a-82c9cd97648e', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:09.000'),
('66f91e96-8897-4159-a7b5-df55fd077ab4', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 11:54:09', NULL, 1, 1, '2017-12-05 10:54:09.000'),
('68cda48e-69f5-4035-85eb-9f3cfdd0c86b', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:44.000'),
('694b182e-56b9-41ae-bb9a-85455cc25304', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:36:55.000'),
('6a90feaf-ddd5-4d5b-8e2b-402cce460c5b', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:27.000'),
('6aa38647-1fe1-4b27-88b1-c439df0b1f39', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:04.000'),
('6aecdab8-128b-4503-a788-2ff5571b1d8d', NULL, '2017-11-20', NULL, '', 1, 1, '2017-11-20 16:53:06.000'),
('6d353e4a-11fe-485f-9a4e-7b01a1225627', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:50:00.000'),
('6e1da20b-4a3d-4f2b-988f-0903ea677fb6', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:02.000'),
('6eac4974-b297-4c17-ab0d-a000aa7149bd', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 11:58:34', NULL, 1, 1, '2017-12-05 10:58:34.000'),
('70215c46-8623-4667-af62-0590a383a753', NULL, '2017-11-20', NULL, '', 1, 1, '2017-11-21 14:18:19.000'),
('719be00c-121d-4172-8825-62ed246c237f', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:12.000'),
('72a6fb2d-1c98-48ac-9c35-8627a250ca83', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:12.000'),
('742649cd-afe7-40ae-b678-1d95b2932564', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:56:01.000'),
('747242be-f534-408c-af58-6696914a2384', NULL, '1997-04-09', '2017-12-04 15:16:08', NULL, 1, 1, '2017-12-04 14:16:08.000'),
('7473ef32-0755-421c-8340-efc6184e91f8', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:56.000'),
('7519f1ac-702d-45d9-b246-46c3cd27b610', NULL, '2017-11-20', NULL, '', 1, 1, '2017-11-21 14:17:13.000'),
('774fbc8c-45ee-4093-88b3-f51b0120d640', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:48:42.000'),
('77a7ab6f-6d3f-4f4d-b0fa-96fea69c5fa5', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:58.000'),
('77f1545e-e4a8-4019-b73a-661bcfc981b3', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 10:15:12.000'),
('79174582-bb9a-4f5c-bf06-48897aa2fb39', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:30.000'),
('7c14f296-012e-4f8c-b1c1-9a4313ee745a', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:49.000'),
('7c22f6e2-10c6-4e87-bd9d-52b9659d5f21', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:22:43', NULL, 1, 1, '2017-12-04 23:22:43.000'),
('80909c79-9b98-4ead-90f9-040b73545abd', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 11:54:34', NULL, 1, 1, '2017-12-05 10:54:34.000'),
('81d36be2-ee43-4b56-95cb-be4236850be1', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:05.000'),
('8356836d-106d-4db8-8def-8f872191348c', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:10:34', NULL, 1, 1, '2017-12-04 23:10:34.000'),
('83724a8c-d067-44a2-83ba-a5c12d58d704', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:58.000'),
('877a74b5-0b87-4ea7-9b94-4ef8cb84cf47', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:14.000'),
('88aa3299-2e18-4b0e-b452-c1751975f066', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-04 23:26:49', NULL, 1, 1, '2017-12-04 22:26:49.000'),
('89bf60d3-65a2-47b9-8647-206fff023b44', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:27:29.000'),
('8aa0fb8c-70b6-43b0-acae-61c1db8c2059', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:45:39.000'),
('8d2a93ea-166e-44db-af10-bdc145a44f28', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:31:36.000'),
('91528d09-70fe-4c3b-be50-0094634eaacc', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:29.000'),
('92c73f98-6945-4047-9445-9d425f11f629', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:02.000'),
('93f23d4c-a442-46f3-aff2-d290944df712', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:55:56.000'),
('9517e26f-7d00-4c17-9aa9-387da596a85f', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:27.000'),
('96653b94-f041-4953-b301-74fa2f336680', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:39:48.000'),
('975b4d29-031a-4541-932d-0392480d5f65', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:55:51.000'),
('987ba696-c999-4323-bf26-468a11c6d7c9', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:37:48.000'),
('989ace10-2c43-4c8c-97c9-4f1a9ecb81f4', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:12:35', NULL, 1, 1, '2017-12-04 23:12:36.000'),
('99b0d5de-b734-4f3b-8f2b-433868dc1fdf', NULL, '1997-04-09', '2017-12-04 15:16:37', NULL, 1, 1, '2017-12-04 14:16:37.000'),
('9b73e177-981f-44ba-a1ff-c03f14ca054e', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:04.000'),
('9bc07771-5920-4d8b-8406-9283bd151a72', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:56.000'),
('9d570bbe-eeda-4c71-82e6-0be2d50fff49', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:14.000'),
('9eec2b7f-75db-466e-8862-e65797dce06c', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 11:57:53', NULL, 1, 1, '2017-12-05 10:57:53.000'),
('9f71f2c5-3f14-410e-aefd-9da9c39b4be9', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:24.000'),
('a04bebd7-d859-429f-bc10-d23d0533aa9b', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:56.000'),
('a10eef24-6c9b-419f-a5ed-2a8830dff7b1', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:55:56.000'),
('a14e5fe0-0b68-441e-bcbc-1f690eb479b7', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:25.000'),
('a2d13140-55e2-4cdf-80b3-25b8c8b2d334', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:26.000'),
('a65fb12d-3840-4e93-a5b7-f4298e4adb07', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:23.000'),
('a67370ce-7e66-4ef0-a250-6bfdba62a0da', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:23.000'),
('a6882183-188b-46bf-b7ec-f771c4edb659', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:23.000'),
('a6a86816-7436-4c7c-ad58-40898ed06027', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:12.000'),
('a73ae41c-7617-4cd1-97d0-d89d79efe7c0', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:09.000'),
('a7681b0f-60b7-4674-a4ad-1e76b7e57e78', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:39:10.000'),
('a91271bb-f9d4-4521-81c4-44cb1b33a199', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 11:57:39', NULL, 1, 1, '2017-12-05 10:57:39.000'),
('b174c0a7-82e5-4ff3-8d76-865f34d1bcd3', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:32.000'),
('b27d64bc-dc39-47fd-b26c-18d40d28a430', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:12:55', NULL, 1, 1, '2017-12-04 23:12:55.000'),
('b582a0ef-034e-4e4a-8450-9bcd6c463578', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:23.000'),
('b5da6ce8-c4d6-4044-ae2a-8a4f8d20e418', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 12:19:32', NULL, 1, 1, '2017-12-05 11:19:32.000'),
('b7cc667c-a8fe-4ad2-97cc-9bc7e7e95db7', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 11:05:33.000'),
('b838f828-e1a5-44d6-bdf0-92c17b54d6ca', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-04 23:50:43', NULL, 1, 1, '2017-12-04 22:50:43.000'),
('b96d329c-b1ea-43cc-9eae-fec88fd9259e', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:47:02.000'),
('ba2c328e-997c-48e1-9611-0fc301d5475c', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:57:10.000'),
('bb3346ff-38d8-4674-9ead-dffbca7fa07d', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:12.000'),
('bbfe351e-bf05-4ce7-8bd5-058fd41161f1', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:58.000'),
('bc79de38-0f51-4c75-b792-402a977dfdca', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-04 15:26:40', NULL, 1, 1, '2017-12-04 14:26:40.000'),
('bcaaedec-eb28-403b-afb0-e5195e40891d', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:58:00.000'),
('bd04f0c8-e7c6-4812-9312-9ffbae5d464d', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:06.000'),
('bf1630b7-e623-4102-a05c-c0457e53cf9f', NULL, '2017-11-20', NULL, '', 1, NULL, '2017-11-20 16:51:07.000'),
('bf8a03f8-8b8d-4736-9b56-778abb3d9446', NULL, '0000-00-00', NULL, '', 1, 1, '2017-11-20 15:17:22.000'),
('c1420500-b656-4d0c-9b15-b3a3b0fcdc1e', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:00.000'),
('c189d857-a0d4-486c-b224-6ea5125f2bef', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:56:25.000'),
('c2e9678a-53fe-4741-b53d-2711161a5900', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-04 16:35:49', NULL, 1, 1, '2017-12-04 15:35:49.000'),
('c4294cfb-090a-4ebd-bf74-e0e278438ad6', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-04 23:25:55', NULL, 1, 1, '2017-12-04 22:25:55.000'),
('c5d45dff-d579-45ec-8026-c4416afa6f49', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:57:10.000'),
('c7ad3da0-245e-4a0b-b842-b51ad762dcf0', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:13:00', NULL, 1, 1, '2017-12-04 23:13:00.000'),
('c89de6df-d270-4bb0-b7b3-0d69883de87a', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:55:50.000'),
('c9ffec60-fd60-4838-ab41-cd0b830f739d', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-04 16:36:00', NULL, 1, 1, '2017-12-04 15:36:00.000'),
('cc7defd7-1c8e-4d63-b700-46e424e1aadc', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:00:25.000'),
('ce23225a-deca-4487-811d-54731d23b942', NULL, '1997-04-09', NULL, NULL, 1, 1, '2017-12-04 13:12:38.000'),
('cfc8d5d8-78dc-4ff9-abac-d710705d403f', NULL, '1997-04-09', '2017-12-04 15:19:08', NULL, 1, 1, '2017-12-04 14:19:08.000'),
('d745e790-c383-4313-9bc5-065d2514d18d', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:23.000'),
('d7ab8b4f-9e6f-4da2-b161-3978beb447d9', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:02.000'),
('d8485f2f-1ce9-4b31-aa81-f8e15c12e504', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:44.000'),
('d962ae67-312c-427d-8d20-b962e71ebf50', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:28:30.000'),
('dc57c964-3cb8-4b95-b359-7bdf4f5b2230', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:01.000'),
('dca663e9-5d3f-4f05-aae1-ba2ad4c45e13', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:56:59.000'),
('dce3588e-6828-42cb-9593-67a09c365bcb', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:00.000'),
('dd53d353-3cd8-499d-a02e-d4da7f394633', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:46:15.000'),
('dfab0f1a-b4b1-44d7-bf1f-317efbda3053', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:56:59.000'),
('e12b70c8-ff63-4c07-ac7c-5cf68a73532f', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:58:00.000'),
('e1f8528c-3ed3-4071-bc2b-0820f8b3aaa2', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:58:45.000'),
('e25bad87-817e-4283-a403-21b2df9b21ee', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:20:47', NULL, 1, 1, '2017-12-04 23:20:47.000'),
('e50322d3-eec0-47f1-9ac7-183cd3fa650d', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:32.000'),
('e6f6cf13-975c-4690-88af-ada9b98a2c03', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:04.000'),
('e787beb6-5b02-4e3e-8178-8fe315809393', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:58:00.000'),
('ea035a78-fb33-434b-b791-799401ac272b', NULL, '0000-00-00', NULL, '', NULL, NULL, '2017-11-20 15:17:22.000'),
('ea5e6fe2-7864-421a-82d3-4142c02c6c74', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:40:22.000'),
('eb38bbcf-ee86-49b6-a6aa-01a8106d4b6f', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:56.000'),
('ebc4a541-7417-4176-9e6e-b7b41e76160d', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:12.000'),
('ef05cb3e-ccb6-4001-85a6-b35179abb89e', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 10:26:56.000'),
('efa2e4fa-e9f6-4c54-a4ab-dc4d4492a64e', NULL, '1997-04-09', '2017-12-04 14:20:25', NULL, 1, 1, '2017-12-04 13:20:25.000'),
('efb51e9f-6422-459b-bb93-f1c3cea82c78', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:44.000'),
('f0018c70-dae1-47b4-94d2-a981c65864a0', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:00.000'),
('f193c816-b2ef-4973-90cf-5194a774529a', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 10:57:24.000'),
('f4af7f92-b2a5-4e66-b223-ac5aa67fe441', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:02.000'),
('f52e66c6-eed6-4018-b1e5-6dce4f94e567', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:57:10.000'),
('f542af49-504d-42ef-90d7-14eb3507eb0b', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:01.000'),
('f5db0957-79de-4a8e-822c-62b2dbaf23cf', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:36:23.000'),
('f5f34781-0269-4ff3-b4cb-537ee6a6e659', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:05.000'),
('f61c93f5-a031-4b47-a9a5-00aa6d6f4dc6', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:32.000'),
('f7ef7b2e-5e9d-4f68-8900-bde3a0f3fd8b', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:29.000'),
('fa246001-5442-422a-ac56-45a11d5b8058', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:24.000'),
('fa418c73-772c-4347-ac0e-94aa1f4d3baf', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:05.000'),
('fb12ef54-3ef8-4c1a-b1e0-7232577912aa', 'PÃ©riphÃ©rique de Test', '1997-04-09', '2017-12-05 00:17:06', NULL, 1, 1, '2017-12-04 23:17:06.000'),
('fbdf2ce7-1121-4087-b5ac-67394e609c60', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:46:23.000'),
('fd21c654-77eb-4ae8-8f1f-beed554d3496', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 13:08:10.000'),
('fd3dfb2d-c1f9-4277-b556-56994fc0a8b6', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 16:39:07.000'),
('fd5a43be-05b7-4897-921f-5643ca023812', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:43:59.000'),
('fdb213e4-0815-467c-91f0-1fcc0c826e76', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:33.000'),
('fe119240-f737-41c3-ae5e-eb6c166fc6dd', NULL, '2017-11-20', NULL, '', NULL, NULL, '2017-11-20 15:59:02.000'),
('ff469593-be63-4426-9237-ec418d16bb98', NULL, NULL, NULL, NULL, 1, 1, '2017-12-04 13:08:00.000');

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--
-- Création :  mar. 12 déc. 2017 à 13:58
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `permissions`:
--

-- --------------------------------------------------------

--
-- Structure de la table `properties`
--
-- Création :  mar. 12 déc. 2017 à 13:58
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `properties`:
--

--
-- Déchargement des données de la table `properties`
--

INSERT INTO `properties` (`id`, `name`, `address`, `creation_date`, `last_updated`) VALUES
(1, 'Chez Bizri', '54 Boulevard de Grenelle', '2017-11-20 14:20:18.000', '2017-11-20 15:17:55.000');

-- --------------------------------------------------------

--
-- Structure de la table `requests`
--
-- Création :  jeu. 07 déc. 2017 à 22:28
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL COMMENT 'IPv6 or IPv4',
  `user_agent_txt` text COLLATE utf8_unicode_ci NOT NULL,
  `user_agent_hash` binary(32) NOT NULL COMMENT 'Hashed with SHA-256',
  `session_id` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `started_processing` timestamp(3) NOT NULL DEFAULT '0000-00-00 00:00:00.000',
  `finished_processing` timestamp(3) NOT NULL DEFAULT '0000-00-00 00:00:00.000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `requests`:
--   `session_id`
--       `sessions` -> `id`
--

--
-- Déchargement des données de la table `requests`
--

INSERT INTO `requests` (`id`, `ip`, `user_agent_txt`, `user_agent_hash`, `session_id`, `controller`, `action`, `started_processing`, `finished_processing`) VALUES
(26, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:00:56.653', '2017-12-13 22:00:56.656'),
(27, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:02.581', '2017-12-13 22:01:02.584'),
(28, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:03.447', '2017-12-13 22:01:03.451'),
(29, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:04.128', '2017-12-13 22:01:04.132'),
(30, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:05.465', '2017-12-13 22:01:05.469'),
(31, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:07.212', '2017-12-13 22:01:07.215'),
(32, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:08.115', '2017-12-13 22:01:08.118'),
(33, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:09.084', '2017-12-13 22:01:09.088'),
(34, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:10.383', '2017-12-13 22:01:10.386'),
(35, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:11.590', '2017-12-13 22:01:11.593'),
(36, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:12.781', '2017-12-13 22:01:12.784'),
(37, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:13.879', '2017-12-13 22:01:13.882'),
(38, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:14.999', '2017-12-13 22:01:15.002'),
(39, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:01:15.961', '2017-12-13 22:01:15.964'),
(40, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '46defb11733028a6cab0a0c0c71c2ad7', 'User', 'connexion', '2017-12-13 22:03:00.854', '2017-12-13 22:03:00.858'),
(41, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '46defb11733028a6cab0a0c0c71c2ad7', 'User', 'connexion', '2017-12-13 22:03:07.472', '2017-12-13 22:03:07.475'),
(42, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '46defb11733028a6cab0a0c0c71c2ad7', 'User', 'connexion', '2017-12-13 22:03:08.517', '2017-12-13 22:03:08.520'),
(43, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '46defb11733028a6cab0a0c0c71c2ad7', 'User', 'connexion', '2017-12-13 22:03:09.546', '2017-12-13 22:03:09.549'),
(44, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '46defb11733028a6cab0a0c0c71c2ad7', 'User', 'connexion', '2017-12-13 22:03:12.032', '2017-12-13 22:03:12.035'),
(45, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:03:15.500', '2017-12-13 22:03:15.503'),
(46, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '46defb11733028a6cab0a0c0c71c2ad7', 'User', 'connexion', '2017-12-13 22:11:47.432', '2017-12-13 22:11:47.436'),
(47, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '4ac2ae2fa04b6ce3a2116a54efa9aa8d', 'User', 'connexion', '2017-12-13 22:23:43.281', '2017-12-13 22:23:43.377'),
(48, '::1', 'curl/7.55.1', 0x55703a1051fcff1102d749dc618e4fd784656b9525924fa29d7c216157d7b360, '8e0371c0e74883c4b688fea93a2d948c', 'User', 'connexion', '2017-12-13 22:23:47.416', '2017-12-13 22:23:47.450'),
(49, '::1', 'curl/7.55.1', 0x55703a1051fcff1102d749dc618e4fd784656b9525924fa29d7c216157d7b360, '5044a057e54984a00a15c9b64ddfdbd3', 'User', 'connexion', '2017-12-13 22:25:27.308', '2017-12-13 22:25:27.320'),
(50, '::1', 'curl/7.55.1', 0x55703a1051fcff1102d749dc618e4fd784656b9525924fa29d7c216157d7b360, 'a342ca783638b79eb63251bc8d108d96', 'User', 'connexion', '2017-12-13 22:26:17.509', '2017-12-13 22:26:17.513'),
(51, '::1', 'curl/7.55.1', 0x55703a1051fcff1102d749dc618e4fd784656b9525924fa29d7c216157d7b360, '2c1d80ba9f943e425eaa4c2426f43ff3', 'User', 'connexion', '2017-12-13 22:30:14.534', '2017-12-13 22:30:14.538'),
(52, '::1', 'curl/7.55.1', 0x55703a1051fcff1102d749dc618e4fd784656b9525924fa29d7c216157d7b360, '21d4d916a51f856e509dc87412aeb008', 'User', 'connexion', '2017-12-13 22:31:11.703', '2017-12-13 22:31:11.712'),
(53, '::1', 'curl/7.55.1', 0x55703a1051fcff1102d749dc618e4fd784656b9525924fa29d7c216157d7b360, '0f4b9ede16273eb72362f4339c326bcc', 'User', 'connexion', '2017-12-13 22:32:07.598', '2017-12-13 22:32:07.602'),
(54, '::1', 'curl/7.55.1', 0x55703a1051fcff1102d749dc618e4fd784656b9525924fa29d7c216157d7b360, 'c804bf64b05e0e3d30a04bbf1b89ba94', 'User', 'connexion', '2017-12-13 22:32:54.668', '2017-12-13 22:32:54.672'),
(55, '::1', 'curl/7.55.1', 0x55703a1051fcff1102d749dc618e4fd784656b9525924fa29d7c216157d7b360, 'db59bec4fff93cbf7d877899d380d1c1', 'User', 'connexion', '2017-12-13 22:33:18.808', '2017-12-13 22:33:18.888'),
(56, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-14 21:42:20.661', '2017-12-14 21:42:20.675'),
(57, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-14 21:42:25.723', '2017-12-14 21:42:25.728'),
(58, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-14 21:42:31.854', '2017-12-14 21:42:31.859'),
(59, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-14 21:43:17.003', '2017-12-14 21:43:17.017'),
(60, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-14 22:01:02.839', '2017-12-14 22:01:02.843'),
(61, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-14 22:01:16.987', '2017-12-14 22:01:16.991'),
(62, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'join', '2017-12-14 22:01:59.115', '2017-12-14 22:01:59.119'),
(63, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-14 22:03:47.876', '2017-12-14 22:03:47.879'),
(64, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-14 22:03:53.927', '2017-12-14 22:03:54.015'),
(65, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-14 22:05:42.243', '2017-12-14 22:05:42.247'),
(66, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-14 22:05:48.536', '2017-12-14 22:05:48.542'),
(67, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-15 13:25:30.058', '2017-12-15 13:25:30.067'),
(68, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-15 13:26:13.792', '2017-12-15 13:26:13.885'),
(69, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-15 13:26:24.215', '2017-12-15 13:26:24.299'),
(70, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'connexion', '2017-12-18 09:12:34.860', '2017-12-18 09:12:34.887'),
(71, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-18 09:12:40.532', '2017-12-18 09:12:40.549'),
(72, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'connexion', '2017-12-18 12:51:31.388', '2017-12-18 12:51:31.396'),
(73, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-18 12:51:36.203', '2017-12-18 12:51:36.219'),
(74, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-18 12:51:46.070', '2017-12-18 12:51:46.077'),
(75, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-18 12:51:49.963', '2017-12-18 12:51:49.966'),
(76, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-18 12:51:55.415', '2017-12-18 12:51:55.503'),
(77, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getAccountPage', '2017-12-18 12:51:57.491', '2017-12-18 12:51:57.500'),
(78, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'Room', 'getRoomsPage', '2017-12-18 12:51:58.287', '2017-12-18 12:51:58.297'),
(79, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'Peripheral', 'getPeripheralsPage', '2017-12-18 12:51:59.855', '2017-12-18 12:51:59.865'),
(80, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'Peripheral', 'getPeripheralsPage', '2017-12-18 12:52:03.091', '2017-12-18 12:52:03.094'),
(81, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'Peripheral', 'getPeripheralsPage', '2017-12-18 12:52:09.465', '2017-12-18 12:52:09.468'),
(82, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-18 12:56:34.645', '2017-12-18 12:56:34.649'),
(83, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-18 12:56:36.839', '2017-12-18 12:56:36.843'),
(84, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-18 12:56:43.011', '2017-12-18 12:56:43.091'),
(85, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getAccountPage', '2017-12-18 12:56:51.577', '2017-12-18 12:56:51.580'),
(86, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-18 14:18:37.342', '2017-12-18 14:18:37.368'),
(87, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-18 14:19:07.501', '2017-12-18 14:19:07.554'),
(88, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-18 14:19:12.747', '2017-12-18 14:19:12.879'),
(89, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'Peripheral', 'getPeripheralsPage', '2017-12-18 14:19:17.852', '2017-12-18 14:19:17.868'),
(90, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'Room', 'getRoomsPage', '2017-12-18 14:19:19.091', '2017-12-18 14:19:19.099'),
(91, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getAccountPage', '2017-12-18 14:19:19.989', '2017-12-18 14:19:19.996'),
(92, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'Room', 'getRoomsPage', '2017-12-18 14:19:20.733', '2017-12-18 14:19:20.736'),
(93, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'Room', 'getRoomsPage', '2017-12-18 14:19:29.218', '2017-12-18 14:19:29.247'),
(94, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-20 12:54:14.533', '2017-12-20 12:54:14.842'),
(95, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 12:55:43.859', '2017-12-20 12:55:44.009'),
(96, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 12:59:24.374', '2017-12-20 12:59:24.521'),
(97, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-20 12:59:39.875', '2017-12-20 12:59:40.070'),
(98, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 12:59:44.314', '2017-12-20 12:59:44.316'),
(99, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-20 12:59:47.447', '2017-12-20 12:59:47.451'),
(100, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 12:59:48.179', '2017-12-20 12:59:48.185'),
(101, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:01:53.281', '2017-12-20 13:01:53.304'),
(102, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:01:55.999', '2017-12-20 13:01:56.007'),
(103, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:03:32.953', '2017-12-20 13:03:33.126'),
(104, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:03:35.156', '2017-12-20 13:03:35.159'),
(105, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:03:36.556', '2017-12-20 13:03:36.560'),
(106, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:04:54.987', '2017-12-20 13:04:55.004'),
(107, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:04:58.845', '2017-12-20 13:04:58.848'),
(108, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:05:11.224', '2017-12-20 13:05:11.228'),
(109, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:05:11.959', '2017-12-20 13:05:11.964'),
(110, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:06:05.759', '2017-12-20 13:06:05.796'),
(111, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:06:12.868', '2017-12-20 13:06:12.872'),
(112, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:06:15.449', '2017-12-20 13:06:15.453'),
(113, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:06:21.608', '2017-12-20 13:06:21.634'),
(114, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:11:19.480', '2017-12-20 13:11:19.590'),
(115, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:11:27.877', '2017-12-20 13:11:28.048'),
(116, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:11:38.124', '2017-12-20 13:11:38.264'),
(117, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:11:41.410', '2017-12-20 13:11:41.420'),
(118, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:12:05.985', '2017-12-20 13:12:06.099'),
(119, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-20 13:12:13.392', '2017-12-20 13:12:13.488'),
(120, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getSubscriptionPage', '2017-12-20 13:13:07.824', '2017-12-20 13:13:07.956'),
(121, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:13:27.004', '2017-12-20 13:13:27.098'),
(122, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-20 13:13:47.925', '2017-12-20 13:13:47.928'),
(123, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:13:59.256', '2017-12-20 13:13:59.372'),
(124, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:14:32.241', '2017-12-20 13:14:32.322'),
(125, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:15:27.742', '2017-12-20 13:15:27.855'),
(126, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:15:35.996', '2017-12-20 13:15:36.087'),
(127, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:16:05.511', '2017-12-20 13:16:05.622'),
(128, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:16:11.286', '2017-12-20 13:16:11.370'),
(129, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:16:37.323', '2017-12-20 13:16:37.327'),
(130, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:16:40.230', '2017-12-20 13:16:40.233'),
(131, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postJoin', '2017-12-20 13:16:43.660', '2017-12-20 13:16:43.757'),
(132, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'getConnectionPage', '2017-12-20 13:16:46.361', '2017-12-20 13:16:46.365'),
(133, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '439d47c88eba6e7dcd526daa69615a00', 'User', 'postConnection', '2017-12-20 13:16:51.476', '2017-12-20 13:16:51.556'),
(134, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 18:23:43.907', '2017-12-24 18:23:43.912'),
(135, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 18:25:06.449', '2017-12-24 18:25:06.454'),
(136, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postConnection', '2017-12-24 21:32:49.855', '2017-12-24 21:32:50.339'),
(137, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 21:33:17.448', '2017-12-24 21:33:17.456'),
(138, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 21:33:58.211', '2017-12-24 21:33:58.215'),
(139, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postConnection', '2017-12-24 21:34:04.957', '2017-12-24 21:34:05.063'),
(140, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getAccountPage', '2017-12-24 21:34:07.454', '2017-12-24 21:34:07.459'),
(141, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'getRoomsPage', '2017-12-24 21:34:11.200', '2017-12-24 21:34:11.204'),
(142, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:54:58.080', '2017-12-24 22:54:58.100'),
(143, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:55:01.794', '2017-12-24 22:55:01.828'),
(144, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:55:03.924', '2017-12-24 22:55:03.927'),
(145, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:55:10.026', '2017-12-24 22:55:10.029'),
(146, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:55:13.625', '2017-12-24 22:55:13.641'),
(147, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:55:25.704', '2017-12-24 22:55:25.720'),
(148, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:55:33.821', '2017-12-24 22:55:33.825'),
(149, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:55:38.099', '2017-12-24 22:55:38.101'),
(150, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:56:02.962', '2017-12-24 22:56:02.971'),
(151, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:58:09.997', '2017-12-24 22:58:10.001'),
(152, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 22:59:08.056', '2017-12-24 22:59:08.060'),
(153, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'getRoomsPage', '2017-12-24 23:00:41.693', '2017-12-24 23:00:41.698'),
(154, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'getRoomsPage', '2017-12-24 23:01:03.184', '2017-12-24 23:01:03.187'),
(155, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'getRoomsPage', '2017-12-24 23:01:18.220', '2017-12-24 23:01:18.223'),
(156, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'getRoomsPage', '2017-12-24 23:01:31.754', '2017-12-24 23:01:31.758'),
(157, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:01:43.975', '2017-12-24 23:01:43.978'),
(158, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:03:43.163', '2017-12-24 23:03:43.237'),
(159, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:04:16.352', '2017-12-24 23:04:16.356'),
(160, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:04:24.859', '2017-12-24 23:04:24.863'),
(161, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:04:35.877', '2017-12-24 23:04:35.880'),
(162, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:06:38.522', '2017-12-24 23:06:38.529'),
(163, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:06:42.666', '2017-12-24 23:06:42.671'),
(164, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:07:14.955', '2017-12-24 23:07:14.958'),
(165, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:07:16.433', '2017-12-24 23:07:16.437'),
(166, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:07:26.249', '2017-12-24 23:07:26.253'),
(167, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:07:27.783', '2017-12-24 23:07:27.786'),
(168, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:07:30.218', '2017-12-24 23:07:30.220'),
(169, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:07:47.726', '2017-12-24 23:07:47.730'),
(170, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:09:06.111', '2017-12-24 23:09:06.115'),
(171, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:09:15.606', '2017-12-24 23:09:15.610'),
(172, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:09:41.283', '2017-12-24 23:09:41.286'),
(173, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:09:53.298', '2017-12-24 23:09:53.303'),
(174, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:10:03.981', '2017-12-24 23:10:03.984'),
(175, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:10:34.980', '2017-12-24 23:10:34.983'),
(176, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:11:24.336', '2017-12-24 23:11:24.339'),
(177, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:11:40.971', '2017-12-24 23:11:40.976'),
(178, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:11:43.225', '2017-12-24 23:11:43.247'),
(179, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:12:57.435', '2017-12-24 23:12:57.461'),
(180, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-24 23:16:24.541', '2017-12-24 23:16:24.550'),
(181, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:16:26.177', '2017-12-24 23:16:26.181'),
(182, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:16:26.897', '2017-12-24 23:16:26.900'),
(183, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:16:28.441', '2017-12-24 23:16:28.444'),
(184, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:16:29.324', '2017-12-24 23:16:29.327'),
(185, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:20:40.748', '2017-12-24 23:20:40.752'),
(186, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'Connection', '2017-12-24 23:20:44.229', '2017-12-24 23:20:44.332'),
(187, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'AccountPage', '2017-12-24 23:20:46.173', '2017-12-24 23:20:46.177'),
(188, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Peripheral', 'PeripheralsPage', '2017-12-24 23:20:47.461', '2017-12-24 23:20:47.465'),
(189, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Peripheral', 'PeripheralsPage', '2017-12-24 23:20:50.034', '2017-12-24 23:20:50.037'),
(190, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Peripheral', 'PeripheralsPage', '2017-12-24 23:20:52.193', '2017-12-24 23:20:52.196'),
(191, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:20:53.016', '2017-12-24 23:20:53.019'),
(192, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'AccountPage', '2017-12-24 23:20:54.280', '2017-12-24 23:20:54.285');
INSERT INTO `requests` (`id`, `ip`, `user_agent_txt`, `user_agent_hash`, `session_id`, `controller`, `action`, `started_processing`, `finished_processing`) VALUES
(193, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:20:54.952', '2017-12-24 23:20:54.955'),
(194, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:49:08.947', '2017-12-24 23:49:09.183'),
(195, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPaged', '2017-12-24 23:49:15.298', '2017-12-24 23:49:15.305'),
(196, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPaged', '2017-12-24 23:51:12.316', '2017-12-24 23:51:12.322'),
(197, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPaged', '2017-12-24 23:51:21.605', '2017-12-24 23:51:21.609'),
(198, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPaged', '2017-12-24 23:51:26.464', '2017-12-24 23:51:26.466'),
(199, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPaged', '2017-12-24 23:52:03.528', '2017-12-24 23:52:03.531'),
(200, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:52:11.728', '2017-12-24 23:52:11.750'),
(201, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:52:24.657', '2017-12-24 23:52:24.676'),
(202, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:52:26.357', '2017-12-24 23:52:26.361'),
(203, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPaged', '2017-12-24 23:52:29.225', '2017-12-24 23:52:29.228'),
(204, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPaged', '2017-12-24 23:53:04.108', '2017-12-24 23:53:04.112'),
(205, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPaged', '2017-12-24 23:53:07.253', '2017-12-24 23:53:07.256'),
(206, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPaged', '2017-12-24 23:53:25.237', '2017-12-24 23:53:25.241'),
(207, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPaged', '2017-12-24 23:54:59.532', '2017-12-24 23:54:59.535'),
(208, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'ConnectionPage', '2017-12-24 23:55:01.806', '2017-12-24 23:55:01.810'),
(209, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'Connection', '2017-12-24 23:55:05.907', '2017-12-24 23:55:06.003'),
(210, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Room', 'RoomsPage', '2017-12-24 23:55:07.399', '2017-12-24 23:55:07.404'),
(211, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'AccountPage', '2017-12-24 23:55:08.283', '2017-12-24 23:55:08.287'),
(212, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'AccountPage', '2017-12-24 23:55:13.876', '2017-12-24 23:55:13.880'),
(213, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'AccountPage', '2017-12-24 23:56:09.456', '2017-12-24 23:56:09.461'),
(214, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 23:56:14.424', '2017-12-24 23:56:14.435'),
(215, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 23:56:19.384', '2017-12-24 23:56:19.427'),
(216, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 23:58:24.117', '2017-12-24 23:58:24.205'),
(217, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 23:58:47.793', '2017-12-24 23:58:47.796'),
(218, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', '', '', '2017-12-24 23:58:55.988', '2017-12-24 23:58:56.004'),
(219, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'AccountPage', '2017-12-25 00:07:30.237', '2017-12-25 00:07:30.245'),
(220, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getAccountPage', '2017-12-25 00:07:33.213', '2017-12-25 00:07:34.050'),
(221, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getAccountPage', '2017-12-25 00:07:36.126', '2017-12-25 00:07:36.141'),
(222, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-25 00:07:37.993', '2017-12-25 00:07:37.997'),
(223, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-25 00:07:39.620', '2017-12-25 00:07:39.623'),
(224, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-25 00:11:08.891', '2017-12-25 00:11:08.900'),
(225, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-25 00:11:36.234', '2017-12-25 00:11:36.309'),
(226, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-25 00:11:46.688', '2017-12-25 00:11:46.696'),
(227, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-25 11:47:26.956', '2017-12-25 11:47:27.127'),
(228, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-25 11:47:31.007', '2017-12-25 11:47:31.015'),
(229, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-25 11:47:53.397', '2017-12-25 11:47:53.422'),
(230, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-25 11:48:03.527', '2017-12-25 11:48:03.546'),
(231, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-25 11:48:13.821', '2017-12-25 11:48:13.846'),
(232, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-25 12:04:59.030', '2017-12-25 12:04:59.154'),
(233, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-25 12:05:03.843', '2017-12-25 12:05:03.889'),
(234, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-25 12:05:13.372', '2017-12-25 12:05:13.404'),
(235, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-25 12:09:06.280', '2017-12-25 12:09:06.560'),
(236, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-25 12:12:12.473', '2017-12-25 12:12:12.559'),
(237, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-25 12:12:30.944', '2017-12-25 12:12:30.948'),
(238, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Maintenance', 'getMaintenancePage', '2017-12-26 17:01:03.842', '2017-12-26 17:01:03.919'),
(239, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Maintenance', 'postSendMail', '2017-12-26 17:01:10.919', '2017-12-26 17:01:10.936'),
(240, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Maintenance', 'postSendMail', '2017-12-26 17:01:22.365', '2017-12-26 17:01:22.395'),
(241, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Maintenance', 'postSendMail', '2017-12-26 17:01:30.479', '2017-12-26 17:01:30.513'),
(242, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-26 17:57:45.462', '2017-12-26 17:57:45.788'),
(243, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-26 18:00:23.860', '2017-12-26 18:00:23.890'),
(244, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:00:30.445', '2017-12-26 18:00:30.459'),
(245, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:00:36.725', '2017-12-26 18:00:36.743'),
(246, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:00:55.467', '2017-12-26 18:00:55.500'),
(247, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 18:01:16.163', '2017-12-26 18:01:16.172'),
(248, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 18:02:21.107', '2017-12-26 18:02:21.109'),
(249, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:03:10.850', '2017-12-26 18:03:10.859'),
(250, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:03:17.108', '2017-12-26 18:03:17.121'),
(251, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:03:18.530', '2017-12-26 18:03:18.537'),
(252, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:04:23.731', '2017-12-26 18:04:23.742'),
(253, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 18:04:40.835', '2017-12-26 18:04:40.842'),
(254, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:04:54.842', '2017-12-26 18:04:54.887'),
(255, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:04:56.386', '2017-12-26 18:04:56.449'),
(256, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:06:27.586', '2017-12-26 18:06:27.619'),
(257, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:06:43.481', '2017-12-26 18:06:43.501'),
(258, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:07:15.757', '2017-12-26 18:07:15.766'),
(259, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:07:32.004', '2017-12-26 18:07:32.008'),
(260, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:07:44.742', '2017-12-26 18:07:44.754'),
(261, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 18:08:00.330', '2017-12-26 18:08:00.334'),
(262, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:08:07.254', '2017-12-26 18:08:07.258'),
(263, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:08:35.305', '2017-12-26 18:08:35.310'),
(264, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:09:26.140', '2017-12-26 18:09:26.144'),
(265, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:09:48.566', '2017-12-26 18:09:48.569'),
(266, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:11:21.607', '2017-12-26 18:11:21.610'),
(267, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:12:44.475', '2017-12-26 18:12:44.484'),
(268, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:15:17.603', '2017-12-26 18:15:17.708'),
(269, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:15:41.606', '2017-12-26 18:15:41.612'),
(270, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:15:49.414', '2017-12-26 18:15:49.420'),
(271, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:16:07.781', '2017-12-26 18:16:07.786'),
(272, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:16:18.964', '2017-12-26 18:16:18.967'),
(273, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:16:36.730', '2017-12-26 18:16:36.735'),
(274, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:16:54.353', '2017-12-26 18:16:54.357'),
(275, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:17:42.515', '2017-12-26 18:17:42.519'),
(276, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:17:59.566', '2017-12-26 18:17:59.579'),
(277, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:18:46.141', '2017-12-26 18:18:46.144'),
(278, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:19:55.654', '2017-12-26 18:19:55.657'),
(279, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:20:23.682', '2017-12-26 18:20:23.686'),
(280, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:20:39.975', '2017-12-26 18:20:39.981'),
(281, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:20:51.396', '2017-12-26 18:20:51.401'),
(282, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:21:02.161', '2017-12-26 18:21:02.165'),
(283, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:21:08.237', '2017-12-26 18:21:08.246'),
(284, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:21:14.686', '2017-12-26 18:21:14.692'),
(285, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:21:42.646', '2017-12-26 18:21:42.652'),
(286, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:25:50.154', '2017-12-26 18:25:50.322'),
(287, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-26 18:25:56.443', '2017-12-26 18:25:56.472'),
(288, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:26:11.824', '2017-12-26 18:26:11.863'),
(289, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:26:16.955', '2017-12-26 18:26:16.960'),
(290, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:26:26.333', '2017-12-26 18:26:26.357'),
(291, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:26:52.612', '2017-12-26 18:26:52.653'),
(292, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:27:00.083', '2017-12-26 18:27:00.093'),
(293, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:27:11.557', '2017-12-26 18:27:11.561'),
(294, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:27:21.218', '2017-12-26 18:27:21.223'),
(295, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:27:27.715', '2017-12-26 18:27:27.750'),
(296, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:27:38.631', '2017-12-26 18:27:38.644'),
(297, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:27:45.205', '2017-12-26 18:27:45.211'),
(298, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:27:52.417', '2017-12-26 18:27:52.420'),
(299, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:28:10.751', '2017-12-26 18:28:10.757'),
(300, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:28:22.948', '2017-12-26 18:28:22.951'),
(301, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:28:36.232', '2017-12-26 18:28:36.235'),
(302, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:29:10.205', '2017-12-26 18:29:10.306'),
(303, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:29:27.555', '2017-12-26 18:29:27.559'),
(304, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:32:35.414', '2017-12-26 18:32:35.542'),
(305, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:34:07.917', '2017-12-26 18:34:07.921'),
(306, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:35:08.934', '2017-12-26 18:35:08.968'),
(307, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:35:32.438', '2017-12-26 18:35:32.452'),
(308, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:36:35.738', '2017-12-26 18:36:35.741'),
(309, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:36:48.254', '2017-12-26 18:36:48.259'),
(310, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:36:54.248', '2017-12-26 18:36:54.252'),
(311, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:37:09.071', '2017-12-26 18:37:09.075'),
(312, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:37:21.920', '2017-12-26 18:37:21.925'),
(313, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:37:25.105', '2017-12-26 18:37:25.108'),
(314, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:37:30.081', '2017-12-26 18:37:30.086'),
(315, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:37:35.824', '2017-12-26 18:37:35.828'),
(316, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:38:13.739', '2017-12-26 18:38:13.753'),
(317, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:38:52.839', '2017-12-26 18:38:52.848'),
(318, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:39:02.176', '2017-12-26 18:39:02.193'),
(319, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:39:24.761', '2017-12-26 18:39:24.769'),
(320, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:39:30.980', '2017-12-26 18:39:30.984'),
(321, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:39:35.952', '2017-12-26 18:39:35.957'),
(322, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:39:42.426', '2017-12-26 18:39:42.431'),
(323, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:39:47.885', '2017-12-26 18:39:47.889'),
(324, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:39:57.072', '2017-12-26 18:39:57.076'),
(325, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:48:06.042', '2017-12-26 18:48:07.873'),
(326, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:48:49.116', '2017-12-26 18:48:49.134'),
(327, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:48:59.709', '2017-12-26 18:48:59.712'),
(328, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:49:15.355', '2017-12-26 18:49:15.359'),
(329, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:49:28.755', '2017-12-26 18:49:28.758'),
(330, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:50:32.787', '2017-12-26 18:50:32.791'),
(331, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:52:41.005', '2017-12-26 18:52:41.009'),
(332, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 18:53:08.019', '2017-12-26 18:53:08.025'),
(333, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:53:49.856', '2017-12-26 18:53:49.860'),
(334, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 18:53:54.266', '2017-12-26 18:53:54.271'),
(335, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:53:55.937', '2017-12-26 18:53:55.941'),
(336, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:55:41.480', '2017-12-26 18:55:41.496'),
(337, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 18:57:37.235', '2017-12-26 18:57:37.239'),
(338, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 19:00:02.069', '2017-12-26 19:00:02.076'),
(339, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 19:01:04.652', '2017-12-26 19:01:04.675'),
(340, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 19:01:33.158', '2017-12-26 19:01:33.185'),
(341, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 19:05:54.942', '2017-12-26 19:05:54.965'),
(342, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 19:06:33.988', '2017-12-26 19:06:34.480'),
(343, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 19:08:20.319', '2017-12-26 19:08:20.347'),
(344, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 19:08:24.151', '2017-12-26 19:08:24.169'),
(345, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 19:11:25.384', '2017-12-26 19:11:25.399'),
(346, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'postJoin', '2017-12-26 19:47:30.517', '2017-12-26 19:47:30.674'),
(347, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getSubscriptionPage', '2017-12-26 20:52:15.646', '2017-12-26 20:52:15.906'),
(348, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'SubscriptionPage', '2017-12-26 20:52:26.286', '2017-12-26 20:52:26.297'),
(349, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'Join', '2017-12-26 20:52:54.808', '2017-12-26 20:52:56.301'),
(350, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'Maintenance', 'postSendMail', '2017-12-27 00:02:53.636', '2017-12-27 00:02:53.811'),
(351, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'patate', 'postSendMail', '2017-12-27 00:03:04.569', '2017-12-27 00:03:04.606'),
(352, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'patate', 'postSendMail', '2017-12-27 00:07:21.013', '2017-12-27 00:07:21.257'),
(353, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'patate', 'postSendMail', '2017-12-27 00:16:41.690', '2017-12-27 00:16:41.788'),
(354, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'patate', 'postSendMail', '2017-12-27 00:17:24.910', '2017-12-27 00:17:24.915');
INSERT INTO `requests` (`id`, `ip`, `user_agent_txt`, `user_agent_hash`, `session_id`, `controller`, `action`, `started_processing`, `finished_processing`) VALUES
(355, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'patate', 'postSendMail', '2017-12-27 00:17:55.282', '2017-12-27 00:17:55.314'),
(356, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'getConnectionPage', '2017-12-27 15:07:53.683', '2017-12-27 15:07:53.727'),
(357, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'SubscriptionPage', '2017-12-27 15:08:04.861', '2017-12-27 15:08:04.891'),
(358, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'SubscriptionPage', '2017-12-27 15:08:37.467', '2017-12-27 15:08:37.471'),
(359, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'SubscriptionPage', '2017-12-27 15:09:37.689', '2017-12-27 15:09:37.694'),
(360, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:09:43.432', '2017-12-27 15:09:43.437'),
(361, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:09:56.140', '2017-12-27 15:09:56.145'),
(362, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:09:57.145', '2017-12-27 15:09:57.150'),
(363, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:10:00.699', '2017-12-27 15:10:00.703'),
(364, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:10:18.294', '2017-12-27 15:10:18.297'),
(365, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:10:39.688', '2017-12-27 15:10:39.693'),
(366, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:10:44.137', '2017-12-27 15:10:44.140'),
(367, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'SubscriptionPage', '2017-12-27 15:10:48.574', '2017-12-27 15:10:48.577'),
(368, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:11:08.000', '2017-12-27 15:11:08.004'),
(369, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:11:09.078', '2017-12-27 15:11:09.082'),
(370, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, '09b7f22a4979b345d8a21b9b0ce9bb75', 'User', 'SubscriptionPage', '2017-12-27 15:11:14.042', '2017-12-27 15:11:14.046'),
(371, '::1', 'Mozilla/5.0 (X11; Linux x86_64; rv:52.0) Gecko/20100101 Firefox/52.0', 0x0345274e6d4821d7b7b729a592f5a2d724b0d38dd09c07f121e92b09a87781b6, '61274d212f1a6d84d9073dbf10d90189', 'User', 'SubscriptionPage', '2017-12-27 15:11:28.901', '2017-12-27 15:11:28.907'),
(372, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:11:45.747', '2017-12-27 15:11:45.752'),
(373, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ebfea88e9e2510746a8a89f7717fccf8', 'User', 'SubscriptionPage', '2017-12-27 15:14:27.129', '2017-12-27 15:14:27.296'),
(374, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36', 0x3d6caf08d6cd781d0e2ef5cd32c347ce19b5dd63a3540c1f7afa51d6fafd5b87, 'ecaf919fcbe330a80f400579b25c55f2', 'User', 'SubscriptionPage', '2017-12-27 15:14:38.321', '2017-12-27 15:14:38.325');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--
-- Création :  mar. 12 déc. 2017 à 14:00
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `creation_date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `roles`:
--   `property_id`
--       `properties` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Structure de la table `roles_permissions`
--
-- Création :  mar. 12 déc. 2017 à 14:00
--

CREATE TABLE `roles_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `creation_date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `roles_permissions`:
--

-- --------------------------------------------------------

--
-- Structure de la table `rooms`
--
-- Création :  mar. 12 déc. 2017 à 14:01
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `name` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `rooms`:
--   `property_id`
--       `properties` -> `id`
--

--
-- Déchargement des données de la table `rooms`
--

INSERT INTO `rooms` (`id`, `property_id`, `name`, `creation_date`, `last_updated`) VALUES
(1, 1, 'updated !', '2017-11-20 14:20:49.000', '2017-12-19 23:22:11.422'),
(4, 1, 'Inserted but not updated', '2017-12-19 23:22:11.416', '2017-12-19 23:22:11.416'),
(5, 1, 'Inserted but not updated', '2017-12-19 23:22:40.597', '2017-12-19 23:22:40.597');

-- --------------------------------------------------------

--
-- Structure de la table `sensors`
--
-- Création :  mer. 27 déc. 2017 à 17:30
--

CREATE TABLE `sensors` (
  `id` int(11) NOT NULL,
  `measure_type_id` int(11) NOT NULL,
  `last_measure_id` int(11) DEFAULT NULL,
  `peripheral_uuid` varchar(33) COLLATE utf8_unicode_ci NOT NULL,
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `sensors`:
--   `peripheral_uuid`
--       `peripherals` -> `uuid`
--   `last_measure_id`
--       `measures` -> `id`
--   `measure_type_id`
--       `measure_types` -> `ID`
--

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--
-- Création :  mar. 12 déc. 2017 à 14:01
--

CREATE TABLE `sessions` (
  `id` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'PHP Session ID',
  `user_id` int(11) DEFAULT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `started` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `expiry` timestamp(3) NOT NULL DEFAULT '0000-00-00 00:00:00.000',
  `canceled` tinyint(1) NOT NULL DEFAULT '0',
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `sessions`:
--   `user_id`
--       `users` -> `id`
--

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `value`, `started`, `expiry`, `canceled`, `last_updated`) VALUES
('09b7f22a4979b345d8a21b9b0ce9bb75', NULL, 'user_id|i:4;', '2017-12-24 18:21:38.683', '2017-12-31 18:21:38.683', 0, '2017-12-24 21:32:50.341'),
('0f4b9ede16273eb72362f4339c326bcc', NULL, '', '2017-12-13 22:32:07.602', '2017-12-20 22:32:07.602', 0, '2017-12-13 22:32:07.603'),
('21d4d916a51f856e509dc87412aeb008', NULL, '', '2017-12-13 22:31:11.712', '2017-12-20 22:31:11.712', 0, '2017-12-13 22:31:11.712'),
('2c1d80ba9f943e425eaa4c2426f43ff3', NULL, '', '2017-12-13 22:30:14.538', '2017-12-20 22:30:14.538', 0, '2017-12-13 22:30:14.538'),
('439d47c88eba6e7dcd526daa69615a00', NULL, 'user_id|i:9;', '2017-12-14 21:42:20.000', '2017-12-21 21:42:20.000', 0, '2017-12-20 13:16:51.556'),
('46defb11733028a6cab0a0c0c71c2ad7', NULL, '', '2017-12-13 22:03:00.858', '2017-12-20 22:03:00.858', 0, '2017-12-13 22:03:00.858'),
('4ac2ae2fa04b6ce3a2116a54efa9aa8d', NULL, '', '2017-12-13 22:00:56.656', '2017-12-20 22:00:56.656', 0, '2017-12-13 22:00:56.656'),
('5044a057e54984a00a15c9b64ddfdbd3', NULL, '', '2017-12-13 22:25:27.321', '2017-12-20 22:25:27.321', 0, '2017-12-13 22:25:27.321'),
('61274d212f1a6d84d9073dbf10d90189', NULL, '', '2017-12-27 15:11:28.907', '2018-01-03 15:11:28.907', 0, '2017-12-27 15:11:28.907'),
('8e0371c0e74883c4b688fea93a2d948c', NULL, '', '2017-12-13 22:23:47.450', '2017-12-20 22:23:47.450', 0, '2017-12-13 22:23:47.450'),
('a342ca783638b79eb63251bc8d108d96', NULL, '', '2017-12-13 22:26:17.513', '2017-12-20 22:26:17.513', 0, '2017-12-13 22:26:17.513'),
('c804bf64b05e0e3d30a04bbf1b89ba94', NULL, '', '2017-12-13 22:32:54.673', '2017-12-20 22:32:54.673', 0, '2017-12-13 22:32:54.673'),
('db59bec4fff93cbf7d877899d380d1c1', NULL, '', '2017-12-13 22:33:18.889', '2017-12-20 22:33:18.889', 0, '2017-12-13 22:33:18.889'),
('ebfea88e9e2510746a8a89f7717fccf8', NULL, '', '2017-12-27 15:09:43.438', '2018-01-03 15:09:43.438', 0, '2017-12-27 15:09:43.438'),
('ecaf919fcbe330a80f400579b25c55f2', NULL, '', '2017-12-27 15:14:38.325', '2018-01-03 15:14:38.325', 0, '2017-12-27 15:14:38.325');

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--
-- Création :  mar. 12 déc. 2017 à 14:02
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `command_id` int(11) DEFAULT NULL,
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `subscriptions`:
--   `property_id`
--       `properties` -> `id`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--
-- Création :  mar. 12 déc. 2017 à 14:04
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `display` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nick` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` date DEFAULT NULL,
  `creation_date` timestamp(3) NULL DEFAULT CURRENT_TIMESTAMP(3),
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(60) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT 'Via BCRYPT',
  `phone` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_updated` timestamp(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- RELATIONS POUR LA TABLE `users`:
--

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `display`, `nick`, `birth_date`, `creation_date`, `email`, `password`, `phone`, `last_updated`) VALUES
(4, 'Alexandre Bizri', 'aabizri', NULL, '2017-12-10 20:21:48.000', 'alexandre@bizri.fr', '$2y$10$5j4BCcVdU9im1OPoHH5as.B2m9W2TrjvZgRn5KpZ/qnjI2v21cg2q', '0651110253', '2017-12-10 20:21:48.000'),
(5, 'Dinesh Anth', 'drdidi', NULL, '2017-12-11 10:41:26.000', 'drdidi@didi.fr', '$2y$10$CkT0EGlHDH/rFerKmnvQmuixz0DBxlbYXM5Loh18iduubN/WOVSbq', '145771987', '2017-12-11 10:41:26.000'),
(6, 'Charles Hubert', 'chubert', NULL, '2017-12-14 22:03:54.008', 'chubert@gmail.com', '$2y$10$hXKZp.A3lO6iU6cmXj6xsOSREmX4wme.pbcTYMa3x1j6iwTzm7YXC', 'Non', '2017-12-14 22:03:54.008'),
(7, 'Phillipot Floriant', 'fphillipot', NULL, '2017-12-15 13:26:13.872', 'fphillipot@gmail.com', '$2y$10$CfLKF1h3NvNVDEJMyBcK4u5l/TZq5Hf0DhS2rqIXu4eEnsrJdPFcm', '0112234563', '2017-12-15 13:26:13.872'),
(8, 'Jean-Pierre Machin', 'jpmachin', NULL, '2017-12-20 13:12:06.082', 'jpmachin@machin.com', '$2y$10$Nu56S.z5y7nSNi9YFUHeXewv.ekdj22iRtRO4Fbadp7imYvdfgKse', '0984038403', '2017-12-20 13:12:06.082'),
(9, 'Jean-Pierre Bidule', 'jpbidule', NULL, '2017-12-20 13:16:43.743', 'jpmachin@bidule.com', '$2y$10$1H7eCkWEsh6FnHnYelYEze.NrfiybfF20ZyEh6Ep7QU6vLpWUkWuC', '0984038403', '2017-12-20 13:16:43.743'),
(10, 'testo sterone', 'testosterone', NULL, '2017-12-26 19:06:34.466', 'testo@testo.com', '$2y$10$D6LYVkQDFUF.GbOnaZpr4uoZb3h0FHlac5b5ek4QpCPlOA1Wp4Rae', '09999999', '2017-12-26 19:06:34.466');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actuators`
--
ALTER TABLE `actuators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peripheral_id` (`peripheral_uuid`) USING BTREE,
  ADD KEY `current_situation` (`last_measure_id`);

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
  ADD KEY `sensor_id` (`sensor_id`),
  ADD KEY `actuator_id` (`actuator_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Index pour la table `measure_types`
--
ALTER TABLE `measure_types`
  ADD PRIMARY KEY (`id`);

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
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Index pour la table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

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
  ADD KEY `last_measure` (`last_measure_id`),
  ADD KEY `measure_type_id` (`measure_type_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE;

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
-- AUTO_INCREMENT pour la table `measure_types`
--
ALTER TABLE `measure_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=375;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `sensors`
--
ALTER TABLE `sensors`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `actuators`
--
ALTER TABLE `actuators`
  ADD CONSTRAINT `actuators_ibfk_1` FOREIGN KEY (`peripheral_uuid`) REFERENCES `peripherals` (`uuid`),
  ADD CONSTRAINT `actuators_ibfk_2` FOREIGN KEY (`last_measure_id`) REFERENCES `measures` (`id`);

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
  ADD CONSTRAINT `measures_ibfk_2` FOREIGN KEY (`actuator_id`) REFERENCES `actuators` (`id`),
  ADD CONSTRAINT `measures_ibfk_3` FOREIGN KEY (`sensor_id`) REFERENCES `sensors` (`id`),
  ADD CONSTRAINT `measures_ibfk_4` FOREIGN KEY (`type_id`) REFERENCES `measure_types` (`ID`);

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
-- Contraintes pour la table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`);

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
  ADD CONSTRAINT `sensors_ibfk_2` FOREIGN KEY (`last_measure_id`) REFERENCES `measures` (`id`),
  ADD CONSTRAINT `sensors_ibfk_3` FOREIGN KEY (`measure_type_id`) REFERENCES `measure_types` (`ID`);

--
-- Contraintes pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`);


--
-- Métadonnées
--
USE `phpmyadmin`;

--
-- Métadonnées pour la table actuators
--

--
-- Métadonnées pour la table events
--

--
-- Métadonnées pour la table filters
--

--
-- Métadonnées pour la table measures
--

--
-- Métadonnées pour la table measure_types
--

--
-- Métadonnées pour la table notifications
--

--
-- Métadonnées pour la table peripherals
--

--
-- Déchargement des données de la table `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'livewell', 'peripherals', '{\"sorted_col\":\"`peripherals`.`display_name` ASC\"}', '2017-12-04 14:27:12');

--
-- Métadonnées pour la table permissions
--

--
-- Métadonnées pour la table properties
--

--
-- Métadonnées pour la table requests
--

--
-- Métadonnées pour la table roles
--

--
-- Métadonnées pour la table roles_permissions
--

--
-- Métadonnées pour la table rooms
--

--
-- Métadonnées pour la table sensors
--

--
-- Métadonnées pour la table sessions
--

--
-- Métadonnées pour la table subscriptions
--

--
-- Métadonnées pour la table users
--

--
-- Métadonnées pour la base de données livewell
--

--
-- Déchargement des données de la table `pma__central_columns`
--

INSERT INTO `pma__central_columns` (`db_name`, `col_name`, `col_type`, `col_length`, `col_collation`, `col_isNull`, `col_extra`, `col_default`) VALUES
('livewell', 'creation_date', 'timestamp', '', '', 0, ',', 'CURRENT_TIMESTAMP'),
('livewell', 'last_updated', 'datetime', '', '', 1, ',on update CURRENT_TIMESTAMP', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
