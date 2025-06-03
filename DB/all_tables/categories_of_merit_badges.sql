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
-- Štruktúra tabuľky pre tabuľku `categories_of_merit_badges`
--

CREATE TABLE `categories_of_merit_badges` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `categories_of_merit_badges`
--

INSERT INTO `categories_of_merit_badges` (`id`, `name`) VALUES
(1, 'Príroda'),
(2, 'Dobrovoľníctvo a občianstvo'),
(3, 'Zručnosti'),
(4, 'Šport'),
(5, 'Umenie a kultúra');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `categories_of_merit_badges`
--
ALTER TABLE `categories_of_merit_badges`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `categories_of_merit_badges`
--
ALTER TABLE `categories_of_merit_badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
