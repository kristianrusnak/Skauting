-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost
-- Čas generovania: Út 03.Jún 2025, 17:17
-- Verzia serveru: 10.4.28-MariaDB
-- Verzia PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `skaut`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `required_points`
--

CREATE TABLE `required_points` (
  `area_id` int(11) NOT NULL,
  `scout_path_id` int(11) NOT NULL,
  `required_points` int(11) DEFAULT NULL,
  `type_of_points` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `required_points`
--

INSERT INTO `required_points` (`area_id`, `scout_path_id`, `required_points`, `type_of_points`, `name`, `icon`) VALUES
(1, 2, 600, 'body', 'Sever cesta ľadu', 'ice-cube'),
(2, 2, 640, 'body', 'Východ cesta lúkou', 'flower'),
(3, 2, 440, 'body', 'Juh cesta púšťou', 'sand'),
(4, 2, 700, 'body', 'Západ cesta pralesom', 'leaves'),
(1, 1, NULL, 'Uloha', 'Dobrodružné detstvo', 'task'),
(2, 1, NULL, 'Uloha', 'Hindský chlapec', 'task'),
(3, 1, NULL, 'Uloha', 'Prvé pomenovanie skaut', 'task'),
(4, 1, NULL, 'Uloha', 'Boj o mafeking', 'task'),
(1, 3, NULL, 'metre', 'Búrka', 'up-arrow'),
(2, 3, NULL, 'metre', 'Dedina', 'up-arrow'),
(3, 3, NULL, 'metre', 'Previs', 'up-arrow'),
(4, 3, NULL, 'metre', 'Záchrana', 'up-arrow');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `required_points`
--
ALTER TABLE `required_points`
  ADD KEY `area_id` (`area_id`),
  ADD KEY `scouth_path_id` (`scout_path_id`);

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `required_points`
--
ALTER TABLE `required_points`
  ADD CONSTRAINT `required_points_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `areas_of_progress` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `required_points_ibfk_2` FOREIGN KEY (`scout_path_id`) REFERENCES `scout_path` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
