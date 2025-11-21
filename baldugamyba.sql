-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 11:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baldugamyba`
--

-- --------------------------------------------------------

--
-- Table structure for table `būsena`
--

CREATE TABLE `būsena` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `būsena`
--

INSERT INTO `būsena` (`id`, `pavadinimas`) VALUES
(3, 'atšauktas'),
(6, 'išsiųstas'),
(2, 'laukia'),
(1, 'pateiktas'),
(4, 'patvirtintas'),
(7, 'pristatytas'),
(5, 'vykdomas');

-- --------------------------------------------------------

--
-- Table structure for table `detalė`
--

CREATE TABLE `detalė` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `detalė`
--

INSERT INTO `detalė` (`id`, `pavadinimas`) VALUES
(4, 'Fasado medžiaga: Ąžuolo Faneruotė'),
(3, 'Fasado medžiaga: Dažyta MDF Matinė'),
(2, 'Fasado medžiaga: MDF Blizgus'),
(1, 'Korpuso medžiaga: LMDP'),
(8, 'Spalva: Antracito Pilka'),
(7, 'Spalva: Balta'),
(9, 'Spalva: Natūrali Ąžuolo'),
(6, 'Stalčių bėgeliai: Hettich Quadro'),
(5, 'Vyriai: Blum su švelniu uždarymu');

-- --------------------------------------------------------

--
-- Table structure for table `naudotojas`
--

CREATE TABLE `naudotojas` (
  `id` int(11) NOT NULL,
  `vardas` varchar(100) NOT NULL,
  `pavardė` varchar(100) NOT NULL,
  `el_paštas` varchar(255) NOT NULL,
  `slaptažodis` char(60) NOT NULL,
  `tel_numeris` varchar(20) DEFAULT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `naudotojas`
--

INSERT INTO `naudotojas` (`id`, `vardas`, `pavardė`, `el_paštas`, `slaptažodis`, `tel_numeris`, `role_id`) VALUES
(1, 'Jonas', 'Petrauskas', 'jonas.p@mail.lt', 'hash_klientas123', '+37061234567', 1),
(2, 'Eglė', 'Kazlauskienė', 'egle.k@admin.lt', 'hash_admin123', '+37069876543', 3),
(3, 'Mindaugas', 'Varnas', 'mindaugas.v@vadyba.lt', 'hash_vadybininkas123', '+37065551111', 2),
(4, 'Laura', 'Baltrūnaitė', 'laura.b@mail.lt', 'hash_klientas456', '+37062223333', 1),
(5, 'admin', 'admin', 'admin@admin.lt', 'admin', '+04646460465', 3),
(6, 'gsege', 'segseg', 'afwe@g.ges', 'sdgsg', '464646654', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prekė`
--

CREATE TABLE `prekė` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(255) NOT NULL,
  `gamintojas` varchar(100) DEFAULT NULL,
  `aprašymas` text DEFAULT NULL,
  `garantija` int(11) DEFAULT NULL,
  `kiekis` int(11) NOT NULL,
  `kaina` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `prekė`
--

INSERT INTO `prekė` (`id`, `pavadinimas`, `gamintojas`, `aprašymas`, `garantija`, `kiekis`, `kaina`) VALUES
(1, 'Apatinė spintelė 60cm', 'Baldita', 'Standartinė apatinė spintelė su dviem stalčiais, balta matinė. Be stalviršio.', 24, 150, 299.99),
(2, 'Viršutinė spintelė 80cm', 'Baldita', 'Pakabinama spintelė su stiklinėmis durelėmis, pilka blizgi.', 36, 85, 499.50),
(3, 'Spintelė kriauklei 90cm', 'Medžio Stiliaus', 'Apatinė spintelė, pritaikyta kriauklei. Turi vietos vandens filtrams.', 24, 60, 699.00),
(4, 'Įmontuojama spinta šaldytuvui', 'LIT Baldai', 'Aukšta spinta, skirta pilnai įmontuoti šaldytuvą-šaldiklį. Ąžuolo faneruotė.', 60, 45, 899.90);

-- --------------------------------------------------------

--
-- Table structure for table `prekės_detalė`
--

CREATE TABLE `prekės_detalė` (
  `prekė_id` int(11) NOT NULL,
  `detalė_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `prekės_detalė`
--

INSERT INTO `prekės_detalė` (`prekė_id`, `detalė_id`) VALUES
(1, 1),
(1, 3),
(1, 6),
(1, 7),
(2, 1),
(2, 2),
(2, 5),
(2, 8),
(4, 1),
(4, 4),
(4, 5),
(4, 9);

-- --------------------------------------------------------

--
-- Table structure for table `rolė`
--

CREATE TABLE `rolė` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `rolė`
--

INSERT INTO `rolė` (`id`, `pavadinimas`) VALUES
(3, 'Administratorius'),
(1, 'Klientas'),
(2, 'Vadybininkas');

-- --------------------------------------------------------

--
-- Table structure for table `užsakymas`
--

CREATE TABLE `užsakymas` (
  `id` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `būsena_id` int(11) NOT NULL,
  `naudotojas_id` int(11) NOT NULL,
  `isdestymas` enum('kampu','linijoje') DEFAULT 'linijoje',
  `spalva_spinteliu` varchar(20) DEFAULT NULL,
  `spalva_stalvirsio` varchar(20) DEFAULT NULL,
  `buitine_technika` text DEFAULT NULL,
  `vizualizacija_paveikslelis` varchar(255) DEFAULT NULL,
  `kaina` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `užsakymas`
--

INSERT INTO `užsakymas` (`id`, `data`, `būsena_id`, `naudotojas_id`, `isdestymas`, `spalva_spinteliu`, `spalva_stalvirsio`, `buitine_technika`, `vizualizacija_paveikslelis`, `kaina`) VALUES
(1, '2025-11-12 00:09:20', 1, 1, 'linijoje', NULL, NULL, NULL, NULL, 0.00),
(2, '2025-11-07 00:09:20', 5, 4, 'linijoje', NULL, NULL, NULL, NULL, 0.00),
(3, '2025-11-12 18:49:01', 1, 1, 'kampu', '#f5deb3', '#a0522d', 'Šaldytuvas, Kaitlentė, Indaplovė', 'uploads/viz_1762966141.png', 0.00),
(4, '2025-11-12 18:54:50', 1, 1, 'kampu', '#f5deb3', '#a0522d', 'Šaldytuvas, Kaitlentė, Indaplovė', 'uploads/viz_1762966490.png', 0.00),
(5, '2025-11-12 18:59:58', 2, 1, 'linijoje', '#f5deb3', '#000000', 'Šaldytuvas, Orkaitė', 'uploads/viz_1762966798.png', 0.00),
(11, '2025-11-12 22:59:30', 1, 1, 'linijoje', '#deb887', '#deb887', 'Array', 'uploads/viz_1762981170.png', NULL),
(12, '2025-11-12 23:03:45', 1, 1, 'linijoje', '#deb887', '#deb887', 'Array', 'uploads/viz_1762981425.png', NULL),
(13, '2025-11-12 23:04:12', 1, 1, 'linijoje', '#deb887', '#deb887', 'Array', 'uploads/viz_1762981452.png', NULL),
(14, '2025-11-12 23:05:07', 1, 1, 'linijoje', '#deb887', '#deb887', 'Array', 'uploads/viz_1762981507.png', NULL),
(15, '2025-11-12 23:05:17', 1, 1, 'kampu', '#deb887', '#deb887', 'Array', 'uploads/viz_1762981517.png', NULL),
(16, '2025-11-12 23:09:49', 1, 1, 'kampu', '#deb887', '#deb887', 'Orkaitė', 'uploads/viz_1762981789.png', 600.00),
(17, '2025-11-12 23:09:59', 1, 1, 'kampu', '#f5deb3', '#f5deb3', 'Šaldytuvas, Orkaitė', 'uploads/viz_1762981799.png', 1100.00),
(18, '2025-11-12 23:10:09', 1, 1, 'kampu', '#8b4513', '#f5deb3', 'Šaldytuvas, Orkaitė', 'uploads/viz_1762981809.png', 1100.00),
(19, '2025-11-12 23:11:32', 1, 1, 'kampu', '#8b4513', '#f5deb3', 'Šaldytuvas, Orkaitė', 'uploads/viz_1762981892.png', 1100.00),
(20, '2025-11-13 00:27:20', 1, 1, 'kampu', '#ffffff', '#8b4513', 'Šaldytuvas, Orkaitė', NULL, 1100.00),
(21, '2025-11-13 00:30:23', 1, 1, 'linijoje', '#8b4513', '#deb887', 'Orkaitė, Kaitlentė, Indaplovė', 'uploads/viz_1762986623.png', 1100.00);

-- --------------------------------------------------------

--
-- Table structure for table `užsakymo_prekė`
--

CREATE TABLE `užsakymo_prekė` (
  `užsakymas_id` int(11) NOT NULL,
  `prekė_id` int(11) NOT NULL,
  `kiekis` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `užsakymo_prekė`
--

INSERT INTO `užsakymo_prekė` (`užsakymas_id`, `prekė_id`, `kiekis`) VALUES
(1, 1, 3),
(1, 3, 1),
(1, 4, 1),
(2, 2, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `būsena`
--
ALTER TABLE `būsena`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pavadinimas` (`pavadinimas`);

--
-- Indexes for table `detalė`
--
ALTER TABLE `detalė`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pavadinimas` (`pavadinimas`);

--
-- Indexes for table `naudotojas`
--
ALTER TABLE `naudotojas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `el_paštas` (`el_paštas`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `prekė`
--
ALTER TABLE `prekė`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prekės_detalė`
--
ALTER TABLE `prekės_detalė`
  ADD PRIMARY KEY (`prekė_id`,`detalė_id`),
  ADD KEY `detalė_id` (`detalė_id`);

--
-- Indexes for table `rolė`
--
ALTER TABLE `rolė`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pavadinimas` (`pavadinimas`);

--
-- Indexes for table `užsakymas`
--
ALTER TABLE `užsakymas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `būsena_id` (`būsena_id`),
  ADD KEY `naudotojas_id` (`naudotojas_id`);

--
-- Indexes for table `užsakymo_prekė`
--
ALTER TABLE `užsakymo_prekė`
  ADD PRIMARY KEY (`užsakymas_id`,`prekė_id`),
  ADD KEY `prekė_id` (`prekė_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `būsena`
--
ALTER TABLE `būsena`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `detalė`
--
ALTER TABLE `detalė`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `naudotojas`
--
ALTER TABLE `naudotojas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prekė`
--
ALTER TABLE `prekė`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rolė`
--
ALTER TABLE `rolė`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `užsakymas`
--
ALTER TABLE `užsakymas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `naudotojas`
--
ALTER TABLE `naudotojas`
  ADD CONSTRAINT `naudotojas_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `rolė` (`id`);

--
-- Constraints for table `prekės_detalė`
--
ALTER TABLE `prekės_detalė`
  ADD CONSTRAINT `prekės_detalė_ibfk_1` FOREIGN KEY (`prekė_id`) REFERENCES `prekė` (`id`),
  ADD CONSTRAINT `prekės_detalė_ibfk_2` FOREIGN KEY (`detalė_id`) REFERENCES `detalė` (`id`);

--
-- Constraints for table `užsakymas`
--
ALTER TABLE `užsakymas`
  ADD CONSTRAINT `užsakymas_ibfk_1` FOREIGN KEY (`būsena_id`) REFERENCES `būsena` (`id`),
  ADD CONSTRAINT `užsakymas_ibfk_2` FOREIGN KEY (`naudotojas_id`) REFERENCES `naudotojas` (`id`);

--
-- Constraints for table `užsakymo_prekė`
--
ALTER TABLE `užsakymo_prekė`
  ADD CONSTRAINT `užsakymo_prekė_ibfk_1` FOREIGN KEY (`užsakymas_id`) REFERENCES `užsakymas` (`id`),
  ADD CONSTRAINT `užsakymo_prekė_ibfk_2` FOREIGN KEY (`prekė_id`) REFERENCES `prekė` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
