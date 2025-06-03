-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost
-- Čas generovania: Út 03.Jún 2025, 17:15
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
-- Štruktúra tabuľky pre tabuľku `areas_of_progress`
--

CREATE TABLE `areas_of_progress` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `color` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `areas_of_progress`
--

INSERT INTO `areas_of_progress` (`id`, `name`, `color`) VALUES
(1, 'Charakter', '#77A4F8'),
(2, 'Služba', '#f87777'),
(3, 'Zdravie a Sila', '#f8da77'),
(4, 'Schopnosti a Zručnosti', '#67df58');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `areas_of_progress`
--
ALTER TABLE `areas_of_progress`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `areas_of_progress`
--
ALTER TABLE `areas_of_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
