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
-- Štruktúra tabuľky pre tabuľku `scout_path`
--

CREATE TABLE `scout_path` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `required_points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `scout_path`
--

INSERT INTO `scout_path` (`id`, `name`, `image`, `color`, `required_points`) VALUES
(1, 'Prvý skaut', 'prvy_skaut', '#ffee53', 40),
(2, 'Neznáme cesty', 'nezname_cesty', '#ba4e3b', NULL),
(3, 'Posledný vrchol', 'posledny_vrchol', '#8ab35f', 2300);

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `scout_path`
--
ALTER TABLE `scout_path`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `scout_path`
--
ALTER TABLE `scout_path`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
