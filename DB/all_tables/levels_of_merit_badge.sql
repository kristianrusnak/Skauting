-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost
-- Čas generovania: Út 03.Jún 2025, 17:16
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
-- Štruktúra tabuľky pre tabuľku `levels_of_merit_badge`
--

CREATE TABLE `levels_of_merit_badge` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(10) NOT NULL,
  `color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `levels_of_merit_badge`
--

INSERT INTO `levels_of_merit_badge` (`id`, `name`, `image`, `color`) VALUES
(1, 'Zelený stupeň', '_g', '#b3ffa3'),
(2, 'Červený stupeň', '_r', '#ff9c9c');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `levels_of_merit_badge`
--
ALTER TABLE `levels_of_merit_badge`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `levels_of_merit_badge`
--
ALTER TABLE `levels_of_merit_badge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
