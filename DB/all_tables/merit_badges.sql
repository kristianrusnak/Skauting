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
-- Štruktúra tabuľky pre tabuľku `merit_badges`
--

CREATE TABLE `merit_badges` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(110) NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `additional_information_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `merit_badges`
--

INSERT INTO `merit_badges` (`id`, `name`, `image`, `color`, `category_id`, `additional_information_id`) VALUES
(1, 'Botanik', 'botanik', '#64b8e8', 1, NULL),
(2, 'Bylinkár', 'bylinkar', '#fd61ff', 1, NULL),
(3, 'Geológ', 'geolog', '#ff6920', 1, NULL),
(4, 'Herpetológ', 'herpetolog', '#ffed00', 1, NULL),
(5, 'Hubár', 'hubar', '#ffbf79', 1, NULL),
(6, 'Hvezdár', 'hvezdar', '#206efe', 1, NULL),
(7, 'Chovateľ', 'chovatel', '#d5ac79', 1, NULL),
(8, 'Lesník', 'lesnik', '#99ed5e', 1, NULL),
(9, 'Meteorológ', 'meteorolog', '#bbe1f8', 1, NULL),
(10, 'Ochranca prírody', 'ochranca_prirody', '#c88cff', 1, NULL),
(11, 'Ornitológ', 'ornitolog', '#bae1f8', 1, NULL),
(12, 'Rybár', 'rybar', '#d6e3c3', 1, NULL),
(13, 'Speleológ', 'speleolog', '#abbbc5', 1, NULL),
(14, 'Stopár', 'stopar', '#fdffed', 1, NULL),
(15, 'Táborník', 'tabornik', '#1ad864', 1, NULL),
(16, 'Včelár', 'vcelar', '#ff9421', 1, NULL),
(17, 'Záhradkár', 'zahradkar', '#009ee2', 1, NULL),
(18, 'Zálesák', 'zalesak', '#ffffff', 1, NULL),
(19, 'Zoológ', 'zoolog', '#ecc7ac', 1, NULL),
(20, 'Babysitter', 'babysitter', '#fa7e7f', 2, NULL),
(21, 'Koledník', 'kolednik', '#fdca15', 2, NULL),
(22, 'Hygienik', 'hygienik', '#57f14d', 2, NULL),
(23, 'Miništrant', 'ministrant', '#e6f973', 2, NULL),
(24, 'Novinár', 'novinar', '#f9f4d8', 2, NULL),
(25, 'Občan', 'obcan', '#d19f38', 2, NULL),
(26, 'Občan Európskej únie', 'obcan_europskej_unie', '#4e6aff', 2, NULL),
(27, 'Oddielový hospodár', 'oddielovy_hospodar', '#f7d745', 2, NULL),
(28, 'Oddielový tajomník', 'oddielovy_tajomnik', '#cddee6', 2, NULL),
(29, 'Organizátor hier', 'organizator_hier', '#ffffff', 2, NULL),
(30, 'Prvá pomoc', 'prva_pomoc', '#ff1c20', 2, NULL),
(31, 'Právnik', 'pravnik', '#fff8c8', 2, NULL),
(32, 'Skautská história', 'skautska_historia', '#d54eff', 2, NULL),
(33, 'Sprievodca', 'sprievodca', '#10a0ff', 2, NULL),
(34, 'Svetové priateľstvo', 'svetove_priatelstvo', '#8abe6d', 2, NULL),
(35, 'Tlmočník a prekladateľ', 'tlmocnik_a_prekladatel', '#80c3ff', 2, NULL),
(36, 'Zahraničný skauting', 'zahranicny_skauting', '#d584ff', 2, NULL),
(37, 'Znalec náboženstiev', 'znalec_nabozenstiev', '#fdd443', 2, NULL),
(38, 'Archeológ', 'archeolog', '#fcf7c8', 3, NULL),
(39, 'Cukrár', 'cukrar', '#ff7fc5', 3, NULL),
(40, 'Fyzik', 'fyzik', '#bfbfbd', 3, NULL),
(41, 'Gamer', 'gamer', '#ff9238', 3, NULL),
(42, 'Genealóg', 'genealog', '#86bb24', 3, NULL),
(43, 'Geocacher', 'geocacher', '#ff7476', 3, NULL),
(44, 'Historik', 'historik', '#e2d5c1', 3, NULL),
(45, 'Hráč spoločenských hier', 'hrac_spolocenskych_hier', '#44ad9e', 3, NULL),
(46, 'Chemik', 'chemik', '#86cef3', 3, NULL),
(47, 'Informatik', 'informatik', '#fbaf8c', 3, NULL),
(48, 'Kuchár', 'kuchar', '#fefefe', 3, NULL),
(49, 'Kutil', 'kutil', '#88b1bf', 3, NULL),
(50, 'Matematik', 'matematik', '#a6ff84', 3, NULL),
(51, 'Modelár', 'modelar', '#f5dea7', 3, NULL),
(52, 'Orientácia', 'orientacia', '#ff7566', 3, NULL),
(53, 'Práca s drevom', 'praca_s_drevom', '#ffb53d', 3, NULL),
(54, 'Ručné práce', 'rucne_prace', '#bbe2f9', 3, NULL),
(55, 'Smartfónista', 'smartfonista', '#e5bbf9', 3, NULL),
(56, 'Sociálne siete', 'socialne_siete', '#59f14f', 3, NULL),
(57, 'Viazanie', 'viazanie', '#efeab4', 3, NULL),
(58, 'Zberateľ', 'zberatel', '#e8e8e8', 3, NULL),
(59, 'Atlét', 'atlet', '#cae3ea', 4, NULL),
(60, 'Basketbalista', 'basketbalista', '#ff9a21', 4, NULL),
(61, 'Bedmintonista', 'bedmintonista', '#eba6a7', 4, NULL),
(62, 'Cyklista', 'cyklista', '#22e85a', 4, NULL),
(63, 'Florbalista', 'florbalista', '#599ddf', 4, NULL),
(64, 'Futbalista', 'futbalista', '#d6e3c4', 4, NULL),
(65, 'Hokejista', 'hokejista', '#8c98ff', 4, NULL),
(66, 'Jazdec', 'jazdec', '#e2edd8', 4, NULL),
(67, 'Korčuliar', 'korculiar', '#acccde', 4, NULL),
(68, 'Lezec', 'lezec', '#d8dedf', 4, NULL),
(69, 'Lukostrelec', 'lukostrelec', '#e9e85b', 4, NULL),
(70, 'Lyžiar', 'lyziar', '#74c7f9', 4, NULL),
(71, 'Plavec', 'plavec', '#f5f5f3', 4, NULL),
(72, 'Silové športy', 'silove_sporty', '#b6f4b3', 4, NULL),
(73, 'Skialpinista', 'skialpinista', '#c8e7fa', 4, NULL),
(74, 'Stolnotenista', 'stolnotenista', '#e7b72b', 4, NULL),
(75, 'Športovec', 'sportovec', '#ea8f4d', 4, NULL),
(76, 'Turista', 'turista', '#fffdd0', 4, NULL),
(77, 'Ultimate frisbee', 'ultimate_frisbee', '#fefefe', 4, NULL),
(78, 'Vodná turistika', 'vodna_turistika', '#c8a21a', 4, NULL),
(79, 'Volejbalista', 'volejbalista', '#88c6a0', 4, NULL),
(80, 'Cestovateľ', 'cestovatel', '#ff7072', 5, NULL),
(81, 'Čitateľ', 'citatel', '#d1eaf2', 5, NULL),
(82, 'Filmár', 'filmar', '#e99000', 5, NULL),
(83, 'Folklorista', 'folklorista', '#fdfffd', 5, NULL),
(84, 'Fotograf', 'fotograf', '#e3e3e3', 5, NULL),
(85, 'Herec', 'herec', '#8c88ff', 5, NULL),
(86, 'Hudobník', 'hudobnik', '$52b8d7', 5, NULL),
(87, 'Kronikár', 'kronikar', '#eec2b6', 5, NULL),
(88, 'Kultúra', 'kultura', '#4f9ff6', 5, NULL),
(89, 'Pútnik', 'putnik', '#17171a', 5, NULL),
(90, 'Rozprávkar', 'rozpravkar', '#fdcc00', 5, NULL),
(91, 'Slovenčinár', 'slovencinar', '#77b96e', 5, NULL),
(92, 'Spevák', 'spevak', '#ffed00', 5, NULL),
(93, 'Spisovateľ', 'spisovatel', '#ff6920', 5, NULL),
(94, 'Tanečník spoločenských tancov', 'tanecnik_spolocenskych_tancov', '#fd61ff', 5, NULL),
(95, 'Výtvarník', 'vytvarnik', '#64b8e8', 5, NULL);

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `merit_badges`
--
ALTER TABLE `merit_badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `additional_information_id` (`additional_information_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `merit_badges`
--
ALTER TABLE `merit_badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `merit_badges`
--
ALTER TABLE `merit_badges`
  ADD CONSTRAINT `merit_badges_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories_of_merit_badges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `merit_badges_ibfk_2` FOREIGN KEY (`additional_information_id`) REFERENCES `additional_information` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
