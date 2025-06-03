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
-- Štruktúra tabuľky pre tabuľku `chapters_of_scout_path`
--

CREATE TABLE `chapters_of_scout_path` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `mandatory` tinyint(1) NOT NULL,
  `area_id` int(11) NOT NULL,
  `scout_path_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `chapters_of_scout_path`
--

INSERT INTO `chapters_of_scout_path` (`id`, `name`, `mandatory`, `area_id`, `scout_path_id`) VALUES
(1, 'Tímový duch', 1, 1, 1),
(2, 'Zodpovednosť a vytrvalosť', 1, 1, 1),
(3, 'Ľudia vo svete', 1, 1, 1),
(4, 'Môj názor', 1, 1, 1),
(5, 'Patrón skautov', 1, 1, 1),
(6, 'Čas stíšenia', 1, 1, 1),
(7, 'Som šťastný?', 1, 1, 1),
(8, 'Priateľstvo', 1, 1, 1),
(9, 'Zvyky našich predkov', 1, 2, 1),
(10, 'Moja rodina', 1, 2, 1),
(11, 'Dobré skutky denne', 1, 2, 1),
(12, 'Štátne symboly', 1, 2, 1),
(13, 'Myslím eko-logicky', 1, 2, 1),
(14, 'Základy slušnosti', 1, 2, 1),
(15, 'V meste', 1, 2, 1),
(16, 'Ideálny deň', 1, 3, 1),
(17, 'Čistota pol života', 1, 3, 1),
(18, 'V zdravom tele zdravý duch', 1, 3, 1),
(19, 'Ľudské telo a ja', 1, 3, 1),
(20, 'Pohyb v prírode', 1, 3, 1),
(21, 'Kreativita', 1, 4, 1),
(22, 'Objavovanie', 1, 4, 1),
(23, 'Pamäť', 1, 4, 1),
(24, 'Mapa a kompas', 1, 4, 1),
(25, 'Práca s nožom', 1, 4, 1),
(26, 'Stromy', 1, 4, 1),
(27, 'Základy rúbania a pílenia', 1, 4, 1),
(28, 'Keď dochádza dych', 1, 4, 1),
(29, 'Priatelia v oddiele', 1, 1, 2),
(30, 'Krajší svet medzi nami', 1, 1, 2),
(31, 'Čestný život', 1, 1, 2),
(32, 'Moje emócie', 1, 1, 2),
(33, 'Kritické myslenie', 1, 1, 2),
(34, 'Ovládam svoje pocity', 1, 1, 2),
(35, 'Hľadanie odpovedí', 1, 1, 2),
(36, 'Obdivuj a rešpektuj', 1, 2, 2),
(37, 'Rodinný kruh', 1, 2, 2),
(38, 'Život s prírodou', 1, 2, 2),
(39, 'Spoznávanie náboženstiev', 1, 2, 2),
(40, 'Na jednej vlne s ostanými', 1, 2, 2),
(41, 'Pripravený na každý deň', 1, 2, 2),
(42, 'Dedičstvo skautingu', 1, 2, 2),
(43, 'Táborový život', 1, 3, 2),
(44, 'Športovanie', 1, 3, 2),
(45, 'Na vrchole svojich síl', 1, 3, 2),
(46, 'Starostlivosť o zdravie', 1, 3, 2),
(47, 'Čítanie a denník', 1, 4, 2),
(48, 'Varenie v malíčku', 1, 4, 2),
(49, 'Slávnostný oheň', 1, 4, 2),
(50, 'Spánok v prírode', 1, 4, 2),
(51, 'Rastliny', 1, 4, 2),
(52, 'Šok a krvácanie', 1, 4, 2),
(53, 'Bezvedomie a resuscitácia', 1, 4, 2),
(54, 'Odhad počasia', 1, 4, 2),
(55, 'Tajné správy', 1, 4, 2),
(56, 'Odtlačky zvierat', 1, 4, 2),
(58, 'Priatelia pre život', 1, 1, 3),
(59, 'Empatia a taktnosť', 1, 1, 3),
(60, 'Zatiahnutie na hlbinu', 1, 1, 3),
(61, 'Slová vďaky', 1, 1, 3),
(62, 'Skauti vo svete', 0, 1, 3),
(63, 'Vedenie iných', 0, 1, 3),
(64, 'Pravda v spleti názorov', 0, 1, 3),
(65, 'Budúca rodina', 1, 2, 3),
(66, 'Tolerancia a rešpekt', 1, 2, 3),
(67, 'Mladý občan', 1, 2, 3),
(68, 'Práca a vzdelanie', 1, 2, 3),
(69, 'História magistra vitae', 0, 2, 3),
(70, 'Spoločnosť a náboženstvo', 0, 2, 3),
(71, 'život na úrovni', 0, 2, 3),
(72, 'Vo forme aj bez fitka', 1, 3, 3),
(73, 'Rovnakí a predsa rozdielni', 1, 3, 3),
(74, 'Zmeny prichádzajú', 1, 3, 3),
(75, 'Život bez závislosti', 1, 3, 3),
(76, 'S časom o preteky', 0, 3, 3),
(77, 'Šaty robia človeka', 0, 3, 3),
(78, 'Bežné ochorenia a lekárnička', 1, 4, 3),
(79, 'Krízové podmienky', 1, 4, 3),
(80, 'Starostlivosť o výstroj', 1, 4, 3),
(81, 'Pochúťky na úrovni', 1, 4, 3),
(82, 'Popáleniny a uhryznutia', 0, 4, 3),
(83, 'Úrazy a obväzovanie', 0, 4, 3),
(84, 'Zauzlené ruky', 0, 4, 3),
(85, 'Divadlo a hudba', 0, 4, 3),
(86, 'Zálesácke zručnosti', 0, 4, 3),
(87, 'Nočná obloha', 0, 4, 3),
(88, 'Huby', 0, 4, 3);

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `chapters_of_scout_path`
--
ALTER TABLE `chapters_of_scout_path`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `scouth_path_id` (`scout_path_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `chapters_of_scout_path`
--
ALTER TABLE `chapters_of_scout_path`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `chapters_of_scout_path`
--
ALTER TABLE `chapters_of_scout_path`
  ADD CONSTRAINT `chapters_of_scout_path_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `areas_of_progress` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chapters_of_scout_path_ibfk_2` FOREIGN KEY (`scout_path_id`) REFERENCES `scout_path` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
