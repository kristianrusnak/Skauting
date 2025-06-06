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
-- Štruktúra tabuľky pre tabuľku `merit_badge_tasks`
--

CREATE TABLE `merit_badge_tasks` (
  `task_id` int(11) NOT NULL,
  `merit_badge_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `merit_badge_tasks`
--

INSERT INTO `merit_badge_tasks` (`task_id`, `merit_badge_id`, `level_id`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 1, 1),
(7, 1, 1),
(8, 1, 2),
(9, 1, 2),
(10, 1, 2),
(11, 1, 2),
(12, 1, 2),
(13, 1, 2),
(14, 2, 1),
(15, 2, 1),
(16, 2, 1),
(17, 2, 1),
(18, 2, 1),
(19, 2, 1),
(20, 2, 1),
(21, 2, 1),
(22, 2, 1),
(23, 2, 2),
(24, 2, 2),
(25, 2, 2),
(26, 2, 2),
(27, 2, 2),
(28, 2, 2),
(29, 2, 2),
(30, 2, 2),
(31, 2, 2),
(32, 2, 2),
(33, 3, 1),
(34, 3, 1),
(35, 3, 1),
(36, 3, 1),
(37, 3, 1),
(38, 3, 1),
(39, 3, 1),
(40, 3, 1),
(41, 3, 1),
(42, 3, 2),
(43, 3, 2),
(44, 3, 2),
(45, 3, 2),
(46, 3, 2),
(47, 3, 2),
(48, 3, 2),
(49, 4, 1),
(50, 4, 1),
(51, 4, 1),
(52, 4, 1),
(53, 4, 1),
(54, 4, 1),
(55, 4, 2),
(56, 4, 2),
(57, 4, 2),
(58, 4, 2),
(59, 4, 2),
(60, 4, 2),
(61, 5, 1),
(62, 5, 1),
(63, 5, 1),
(64, 5, 1),
(65, 5, 1),
(66, 5, 1),
(67, 5, 1),
(68, 5, 1),
(69, 5, 2),
(70, 5, 2),
(71, 5, 2),
(72, 5, 2),
(73, 5, 2),
(74, 5, 2),
(75, 5, 2),
(76, 5, 2),
(77, 6, 1),
(78, 6, 1),
(79, 6, 1),
(80, 6, 1),
(81, 6, 1),
(82, 6, 1),
(83, 6, 2),
(84, 6, 2),
(85, 6, 2),
(86, 6, 2),
(87, 6, 2),
(88, 6, 2),
(89, 6, 2),
(90, 6, 2),
(91, 7, 1),
(92, 7, 1),
(93, 7, 1),
(94, 7, 1),
(95, 7, 1),
(96, 7, 1),
(97, 7, 2),
(98, 7, 2),
(99, 7, 2),
(100, 7, 2),
(101, 7, 2),
(102, 7, 2),
(103, 8, 1),
(104, 8, 1),
(105, 8, 1),
(106, 8, 1),
(107, 8, 1),
(108, 8, 1),
(109, 8, 1),
(110, 8, 1),
(111, 8, 2),
(112, 8, 2),
(113, 8, 2),
(114, 8, 2),
(115, 8, 2),
(116, 8, 2),
(117, 8, 2),
(118, 8, 2),
(119, 9, 1),
(120, 9, 1),
(121, 9, 1),
(122, 9, 1),
(123, 9, 1),
(124, 9, 1),
(125, 9, 1),
(126, 9, 2),
(127, 9, 2),
(128, 9, 2),
(129, 9, 2),
(130, 9, 2),
(131, 9, 2),
(132, 9, 2),
(133, 9, 2),
(134, 10, 1),
(135, 11, 1),
(136, 11, 1),
(137, 11, 1),
(138, 11, 1),
(139, 11, 1),
(140, 11, 1),
(141, 11, 2),
(142, 11, 2),
(143, 11, 2),
(144, 11, 2),
(145, 11, 2),
(146, 11, 2),
(147, 12, 1),
(148, 12, 1),
(149, 12, 1),
(150, 12, 1),
(151, 12, 1),
(152, 12, 1),
(153, 12, 1),
(154, 12, 2),
(155, 12, 2),
(156, 12, 2),
(157, 12, 2),
(158, 12, 2),
(159, 12, 2),
(160, 12, 2),
(161, 13, 1),
(162, 13, 1),
(163, 13, 1),
(164, 13, 1),
(165, 13, 1),
(166, 13, 2),
(167, 13, 2),
(168, 13, 2),
(169, 13, 2),
(170, 13, 2),
(171, 13, 2),
(172, 13, 2),
(173, 13, 2),
(174, 14, 1),
(175, 14, 1),
(176, 14, 1),
(177, 14, 1),
(178, 14, 1),
(179, 14, 1),
(180, 14, 2),
(181, 14, 2),
(182, 14, 2),
(183, 14, 2),
(184, 14, 2),
(185, 14, 2),
(186, 15, 1),
(187, 15, 1),
(188, 15, 1),
(189, 15, 1),
(190, 15, 1),
(191, 15, 1),
(192, 15, 1),
(193, 15, 1),
(194, 15, 1),
(195, 15, 1),
(196, 15, 2),
(197, 15, 2),
(198, 15, 2),
(199, 15, 2),
(200, 15, 2),
(201, 15, 2),
(202, 15, 2),
(203, 15, 2),
(204, 15, 2),
(205, 15, 2),
(206, 16, 1),
(207, 16, 1),
(208, 16, 1),
(209, 16, 1),
(210, 16, 1),
(211, 16, 1),
(212, 16, 1),
(213, 16, 2),
(214, 16, 2),
(215, 16, 2),
(216, 16, 2),
(217, 16, 2),
(218, 16, 2),
(219, 16, 2),
(220, 16, 2),
(221, 17, 1),
(222, 17, 1),
(223, 17, 1),
(224, 17, 1),
(225, 17, 1),
(226, 17, 1),
(227, 17, 1),
(228, 17, 2),
(229, 17, 2),
(230, 17, 2),
(231, 17, 2),
(232, 17, 2),
(233, 17, 2),
(234, 17, 2),
(235, 18, 1),
(236, 18, 1),
(237, 18, 1),
(238, 18, 1),
(239, 18, 1),
(240, 18, 1),
(241, 18, 1),
(242, 18, 2),
(243, 18, 2),
(244, 18, 2),
(245, 18, 2),
(246, 18, 2),
(247, 18, 2),
(248, 18, 2),
(249, 18, 2),
(250, 19, 1),
(251, 19, 1),
(252, 19, 1),
(253, 19, 1),
(254, 19, 1),
(255, 19, 1),
(256, 19, 2),
(257, 19, 2),
(258, 19, 2),
(259, 19, 2),
(260, 19, 2),
(261, 19, 2),
(262, 19, 2),
(263, 20, 1),
(264, 20, 1),
(265, 20, 1),
(266, 20, 1),
(267, 20, 1),
(268, 20, 1),
(269, 20, 1),
(270, 20, 1),
(271, 20, 1),
(272, 20, 1),
(273, 20, 2),
(274, 20, 2),
(275, 20, 2),
(276, 20, 2),
(277, 20, 2),
(278, 20, 2),
(279, 20, 2),
(280, 21, 1),
(281, 21, 1),
(282, 21, 1),
(283, 21, 1),
(284, 21, 1),
(285, 21, 1),
(286, 21, 1),
(287, 21, 1),
(288, 21, 2),
(289, 21, 2),
(290, 22, 1),
(291, 22, 1),
(292, 22, 1),
(293, 22, 1),
(294, 22, 1),
(295, 22, 1),
(296, 22, 1),
(297, 22, 1),
(298, 22, 2),
(299, 22, 2),
(300, 22, 2),
(301, 22, 2),
(302, 22, 2),
(303, 22, 2),
(304, 23, 1),
(305, 23, 1),
(306, 23, 1),
(307, 23, 1),
(308, 23, 1),
(309, 23, 1),
(310, 23, 1),
(311, 23, 1),
(312, 23, 1),
(313, 23, 1),
(314, 23, 2),
(315, 23, 2),
(316, 23, 2),
(317, 23, 2),
(318, 23, 2),
(319, 23, 2),
(320, 23, 2),
(321, 23, 2),
(322, 23, 2),
(323, 24, 1),
(324, 24, 1),
(325, 24, 1),
(326, 24, 1),
(327, 24, 1),
(328, 24, 1),
(329, 24, 1),
(330, 24, 2),
(331, 24, 2),
(332, 24, 2),
(333, 24, 2),
(334, 24, 2),
(335, 24, 2),
(336, 24, 2),
(337, 24, 2),
(338, 24, 2),
(339, 25, 1),
(340, 25, 1),
(341, 25, 1),
(342, 25, 1),
(343, 25, 1),
(344, 25, 1),
(345, 25, 1),
(346, 25, 1),
(347, 25, 2),
(348, 25, 2),
(349, 25, 2),
(350, 25, 2),
(351, 25, 2),
(352, 25, 2),
(353, 25, 2),
(354, 25, 2),
(355, 26, 1),
(356, 26, 1),
(357, 26, 1),
(358, 26, 1),
(359, 26, 1),
(360, 26, 1),
(361, 26, 1),
(362, 26, 2),
(363, 26, 2),
(364, 26, 2),
(365, 26, 2),
(366, 26, 2),
(367, 26, 2),
(368, 26, 2),
(369, 27, 2),
(370, 27, 2),
(371, 27, 2),
(372, 27, 2),
(373, 28, 2),
(374, 28, 2),
(375, 28, 2),
(376, 28, 2),
(377, 28, 2),
(378, 28, 2),
(379, 29, 1),
(380, 29, 1),
(381, 29, 1),
(382, 29, 1),
(383, 29, 1),
(384, 29, 1),
(385, 29, 2),
(386, 29, 2),
(387, 29, 2),
(388, 29, 2),
(389, 29, 2),
(390, 29, 2),
(391, 29, 2),
(392, 30, 1),
(393, 30, 1),
(394, 30, 1),
(395, 30, 1),
(396, 30, 1),
(397, 30, 1),
(398, 30, 1),
(399, 30, 1),
(400, 30, 1),
(401, 30, 1),
(402, 30, 2),
(403, 30, 2),
(404, 30, 2),
(405, 30, 2),
(406, 30, 2),
(407, 30, 2),
(408, 30, 2),
(409, 30, 2),
(410, 30, 2),
(411, 30, 2),
(412, 31, 1),
(413, 31, 1),
(414, 31, 1),
(415, 31, 1),
(416, 31, 1),
(417, 31, 1),
(418, 31, 1),
(419, 31, 2),
(420, 31, 2),
(421, 31, 2),
(422, 31, 2),
(423, 31, 2),
(424, 31, 2),
(425, 31, 2),
(426, 32, 1),
(427, 32, 1),
(428, 32, 1),
(429, 32, 1),
(430, 32, 1),
(431, 32, 1),
(432, 32, 1),
(433, 32, 2),
(434, 32, 2),
(435, 32, 2),
(436, 32, 2),
(437, 32, 2),
(438, 32, 2),
(439, 32, 2),
(440, 32, 2),
(441, 33, 1),
(442, 33, 1),
(443, 33, 1),
(444, 33, 1),
(445, 33, 1),
(446, 33, 1),
(447, 33, 2),
(448, 33, 2),
(449, 33, 2),
(450, 33, 2),
(451, 33, 2),
(452, 33, 2),
(453, 34, 1),
(454, 34, 1),
(455, 34, 1),
(456, 34, 1),
(457, 34, 1),
(458, 34, 1),
(459, 35, 1),
(460, 35, 1),
(461, 35, 1),
(462, 35, 1),
(463, 35, 1),
(464, 35, 1),
(465, 35, 1),
(466, 35, 2),
(467, 35, 2),
(468, 35, 2),
(469, 35, 2),
(470, 35, 2),
(471, 35, 2),
(472, 35, 2),
(473, 35, 2),
(474, 36, 1),
(475, 36, 1),
(476, 36, 1),
(477, 36, 1),
(478, 36, 1),
(479, 36, 1),
(480, 36, 1),
(481, 36, 2),
(482, 36, 2),
(483, 36, 2),
(484, 36, 2),
(485, 36, 2),
(486, 36, 2),
(487, 36, 2),
(488, 37, 1),
(489, 37, 1),
(490, 37, 1),
(491, 37, 1),
(492, 37, 1),
(493, 37, 2),
(494, 37, 2),
(495, 37, 2),
(496, 38, 1),
(497, 38, 1),
(498, 38, 1),
(499, 38, 1),
(500, 38, 1),
(501, 38, 1),
(502, 38, 2),
(503, 38, 2),
(504, 38, 2),
(505, 38, 2),
(506, 38, 2),
(507, 38, 2),
(508, 38, 2),
(509, 39, 1),
(510, 39, 1),
(511, 39, 1),
(512, 39, 1),
(513, 39, 1),
(514, 39, 1),
(515, 39, 2),
(516, 39, 2),
(517, 39, 2),
(518, 39, 2),
(519, 39, 2),
(520, 39, 2),
(521, 39, 2),
(522, 40, 1),
(523, 40, 1),
(524, 40, 1),
(525, 40, 1),
(526, 40, 1),
(527, 40, 1),
(528, 40, 1),
(529, 40, 2),
(530, 40, 2),
(531, 40, 2),
(532, 40, 2),
(533, 40, 2),
(534, 40, 2),
(535, 40, 2),
(536, 40, 2),
(537, 41, 1),
(538, 41, 1),
(539, 41, 1),
(540, 41, 1),
(541, 41, 1),
(542, 41, 1),
(543, 41, 1),
(544, 41, 2),
(545, 41, 2),
(546, 41, 2),
(547, 41, 2),
(548, 41, 2),
(549, 41, 2),
(550, 41, 2),
(551, 41, 2),
(552, 41, 2),
(553, 42, 1),
(554, 42, 1),
(555, 42, 1),
(556, 42, 1),
(557, 42, 1),
(558, 42, 1),
(559, 42, 1),
(560, 42, 2),
(561, 42, 2),
(562, 42, 2),
(563, 42, 2),
(564, 42, 2),
(565, 42, 2),
(566, 42, 2),
(567, 43, 2),
(568, 43, 2),
(569, 43, 2),
(570, 43, 2),
(571, 43, 2),
(572, 43, 2),
(573, 43, 2),
(574, 43, 2),
(575, 43, 2),
(576, 44, 1),
(577, 44, 1),
(578, 44, 1),
(579, 44, 1),
(580, 44, 1),
(581, 44, 2),
(582, 44, 2),
(583, 44, 2),
(584, 44, 2),
(585, 44, 2),
(586, 44, 2),
(587, 44, 2),
(588, 45, 1),
(589, 45, 1),
(590, 45, 1),
(591, 45, 1),
(592, 45, 1),
(593, 45, 1),
(594, 45, 1),
(595, 45, 1),
(596, 45, 2),
(597, 45, 2),
(598, 45, 2),
(599, 45, 2),
(600, 45, 2),
(601, 45, 2),
(602, 45, 2),
(603, 45, 2),
(604, 45, 2),
(605, 46, 1),
(606, 46, 1),
(607, 46, 1),
(608, 46, 1),
(609, 46, 1),
(610, 46, 1),
(611, 46, 1),
(612, 46, 2),
(613, 46, 2),
(614, 46, 2),
(615, 46, 2),
(616, 46, 2),
(617, 46, 2),
(618, 46, 2),
(619, 46, 2),
(620, 47, 1),
(621, 47, 1),
(622, 47, 1),
(623, 47, 1),
(624, 47, 1),
(625, 47, 1),
(626, 47, 1),
(627, 47, 2),
(628, 47, 2),
(629, 47, 2),
(630, 47, 2),
(631, 47, 2),
(632, 47, 2),
(633, 47, 2),
(634, 48, 1),
(635, 48, 1),
(636, 48, 1),
(637, 48, 1),
(638, 48, 1),
(639, 48, 1),
(640, 48, 1),
(641, 48, 2),
(642, 48, 2),
(643, 48, 2),
(644, 48, 2),
(645, 48, 2),
(646, 48, 2),
(647, 48, 2),
(648, 48, 2),
(649, 49, 1),
(650, 49, 1),
(651, 49, 1),
(652, 49, 1),
(653, 49, 1),
(654, 49, 1),
(655, 49, 2),
(656, 49, 2),
(657, 49, 2),
(658, 49, 2),
(659, 49, 2),
(660, 49, 2),
(661, 49, 2),
(662, 49, 2),
(663, 50, 1),
(664, 50, 1),
(665, 50, 1),
(666, 50, 1),
(667, 50, 1),
(668, 50, 1),
(669, 50, 1),
(670, 50, 2),
(671, 50, 2),
(672, 50, 2),
(673, 50, 2),
(674, 50, 2),
(675, 50, 2),
(676, 50, 2),
(677, 51, 1),
(678, 51, 1),
(679, 51, 1),
(680, 51, 1),
(681, 51, 1),
(682, 51, 1),
(683, 51, 2),
(684, 51, 2),
(685, 51, 2),
(686, 51, 2),
(687, 51, 2),
(688, 51, 2),
(689, 51, 2),
(690, 52, 1),
(691, 52, 1),
(692, 52, 1),
(693, 52, 1),
(694, 52, 1),
(695, 52, 1),
(696, 52, 1),
(697, 52, 2),
(698, 52, 2),
(699, 52, 2),
(700, 52, 2),
(701, 52, 2),
(702, 52, 2),
(703, 52, 2),
(704, 52, 2),
(705, 53, 1),
(706, 53, 1),
(707, 53, 1),
(708, 53, 1),
(709, 53, 1),
(710, 53, 1),
(711, 53, 1),
(712, 53, 2),
(713, 53, 2),
(714, 53, 2),
(715, 53, 2),
(716, 53, 2),
(717, 53, 2),
(718, 53, 2),
(719, 53, 2),
(720, 53, 2),
(721, 54, 1),
(722, 54, 1),
(723, 54, 1),
(724, 54, 1),
(725, 54, 1),
(726, 54, 1),
(727, 54, 1),
(728, 54, 1),
(729, 54, 2),
(730, 54, 2),
(731, 54, 2),
(732, 54, 2),
(733, 54, 2),
(734, 54, 2),
(735, 54, 2),
(736, 54, 2),
(737, 55, 1),
(738, 55, 1),
(739, 55, 1),
(740, 55, 1),
(741, 55, 1),
(742, 55, 1),
(743, 55, 1),
(744, 55, 1),
(745, 55, 1),
(746, 55, 1),
(747, 55, 2),
(748, 55, 2),
(749, 55, 2),
(750, 55, 2),
(751, 55, 2),
(752, 55, 2),
(753, 55, 2),
(754, 55, 2),
(755, 55, 2),
(756, 55, 2),
(757, 55, 2),
(758, 56, 1),
(759, 56, 1),
(760, 56, 1),
(761, 56, 1),
(762, 56, 1),
(763, 56, 1),
(764, 56, 1),
(765, 56, 2),
(766, 56, 2),
(767, 56, 2),
(768, 56, 2),
(769, 56, 2),
(770, 56, 2),
(771, 56, 2),
(772, 57, 1),
(773, 57, 1),
(774, 57, 1),
(775, 57, 1),
(776, 57, 1),
(777, 57, 1),
(778, 57, 1),
(779, 57, 2),
(780, 57, 2),
(781, 57, 2),
(782, 57, 2),
(783, 57, 2),
(784, 57, 2),
(785, 57, 2),
(786, 58, 1),
(787, 58, 1),
(788, 58, 1),
(789, 58, 1),
(790, 58, 1),
(791, 59, 1),
(792, 59, 1),
(793, 59, 1),
(794, 59, 1),
(795, 59, 1),
(796, 59, 1),
(797, 59, 2),
(798, 59, 2),
(799, 59, 2),
(800, 59, 2),
(801, 59, 2),
(802, 59, 2),
(803, 59, 2),
(804, 60, 1),
(805, 60, 1),
(806, 60, 1),
(807, 60, 1),
(808, 60, 1),
(809, 60, 1),
(810, 60, 1),
(811, 60, 2),
(812, 60, 2),
(813, 60, 2),
(814, 60, 2),
(815, 60, 2),
(816, 60, 2),
(817, 60, 2),
(818, 61, 1),
(819, 61, 1),
(820, 61, 1),
(821, 61, 1),
(822, 61, 1),
(823, 61, 1),
(824, 61, 1),
(825, 61, 1),
(826, 61, 2),
(827, 61, 2),
(828, 61, 2),
(829, 61, 2),
(830, 61, 2),
(831, 61, 2),
(832, 61, 2),
(833, 62, 1),
(834, 62, 1),
(835, 62, 1),
(836, 62, 1),
(837, 62, 1),
(838, 62, 1),
(839, 62, 2),
(840, 62, 2),
(841, 62, 2),
(842, 62, 2),
(843, 62, 2),
(844, 62, 2),
(845, 62, 2),
(846, 62, 2),
(847, 62, 2),
(848, 63, 1),
(849, 63, 1),
(850, 63, 1),
(851, 63, 1),
(852, 63, 1),
(853, 63, 1),
(854, 63, 1),
(855, 63, 1),
(856, 63, 2),
(857, 63, 2),
(858, 63, 2),
(859, 63, 2),
(860, 63, 2),
(861, 63, 2),
(862, 63, 2),
(863, 64, 1),
(864, 64, 1),
(865, 64, 1),
(866, 64, 1),
(867, 64, 1),
(868, 64, 1),
(869, 64, 1),
(870, 64, 2),
(871, 64, 2),
(872, 64, 2),
(873, 64, 2),
(874, 64, 2),
(875, 64, 2),
(876, 64, 2),
(877, 65, 1),
(878, 65, 1),
(879, 65, 1),
(880, 65, 1),
(881, 65, 1),
(882, 65, 1),
(883, 65, 1),
(884, 65, 2),
(885, 65, 2),
(886, 65, 2),
(887, 65, 2),
(888, 65, 2),
(889, 65, 2),
(890, 65, 2),
(891, 66, 1),
(892, 66, 1),
(893, 66, 1),
(894, 66, 1),
(895, 66, 1),
(896, 66, 1),
(897, 66, 1),
(898, 66, 1),
(899, 66, 1),
(900, 66, 2),
(901, 66, 2),
(902, 66, 2),
(903, 66, 2),
(904, 66, 2),
(905, 66, 2),
(906, 66, 2),
(907, 66, 2),
(908, 66, 2),
(909, 66, 2),
(910, 67, 1),
(911, 67, 1),
(912, 67, 1),
(913, 67, 1),
(914, 67, 1),
(915, 67, 1),
(916, 67, 1),
(917, 67, 1),
(918, 67, 1),
(919, 67, 2),
(920, 67, 2),
(921, 67, 2),
(922, 67, 2),
(923, 67, 2),
(924, 67, 2),
(925, 67, 2),
(926, 67, 2),
(927, 67, 2),
(928, 67, 2),
(929, 68, 1),
(930, 68, 1),
(931, 68, 1),
(932, 68, 1),
(933, 68, 1),
(934, 68, 1),
(935, 68, 1),
(936, 68, 1),
(937, 68, 2),
(938, 68, 2),
(939, 68, 2),
(940, 68, 2),
(941, 68, 2),
(942, 68, 2),
(943, 68, 2),
(944, 68, 2),
(945, 68, 2),
(946, 69, 1),
(947, 69, 1),
(948, 69, 1),
(949, 69, 1),
(950, 69, 1),
(951, 69, 1),
(952, 69, 1),
(953, 69, 2),
(954, 69, 2),
(955, 69, 2),
(956, 69, 2),
(957, 69, 2),
(958, 69, 2),
(959, 69, 2),
(960, 70, 1),
(961, 70, 1),
(962, 70, 1),
(963, 70, 1),
(964, 70, 1),
(965, 70, 1),
(966, 70, 1),
(967, 70, 1),
(968, 70, 2),
(969, 70, 2),
(970, 70, 2),
(971, 70, 2),
(972, 70, 2),
(973, 70, 2),
(974, 70, 2),
(975, 70, 2),
(976, 70, 2),
(977, 71, 1),
(978, 71, 1),
(979, 71, 1),
(980, 71, 1),
(981, 71, 1),
(982, 71, 1),
(983, 71, 2),
(984, 71, 2),
(985, 71, 2),
(986, 71, 2),
(987, 71, 2),
(988, 71, 2),
(989, 71, 2),
(990, 72, 1),
(991, 72, 1),
(992, 72, 1),
(993, 72, 1),
(994, 72, 1),
(995, 72, 1),
(996, 72, 1),
(997, 72, 2),
(998, 72, 2),
(999, 72, 2),
(1000, 72, 2),
(1001, 72, 2),
(1002, 72, 2),
(1003, 72, 2),
(1004, 72, 2),
(1005, 73, 1),
(1006, 73, 1),
(1007, 73, 1),
(1008, 73, 1),
(1009, 73, 1),
(1010, 73, 1),
(1011, 73, 1),
(1012, 73, 1),
(1013, 73, 1),
(1014, 73, 1),
(1015, 73, 2),
(1016, 73, 2),
(1017, 73, 2),
(1018, 73, 2),
(1019, 73, 2),
(1020, 73, 2),
(1021, 73, 2),
(1022, 73, 2),
(1023, 73, 2),
(1024, 73, 2),
(1025, 73, 2),
(1026, 73, 2),
(1027, 73, 2),
(1028, 73, 2),
(1029, 73, 2),
(1030, 73, 2),
(1031, 73, 2),
(1032, 73, 2),
(1033, 74, 1),
(1034, 74, 1),
(1035, 74, 1),
(1036, 74, 1),
(1037, 74, 1),
(1038, 74, 1),
(1039, 74, 1),
(1040, 74, 1),
(1041, 74, 2),
(1042, 74, 2),
(1043, 74, 2),
(1044, 74, 2),
(1045, 74, 2),
(1046, 74, 2),
(1047, 74, 2),
(1048, 75, 1),
(1049, 75, 1),
(1050, 75, 1),
(1051, 75, 1),
(1052, 75, 1),
(1053, 75, 1),
(1054, 75, 2),
(1055, 75, 2),
(1056, 75, 2),
(1057, 75, 2),
(1058, 75, 2),
(1059, 76, 1),
(1060, 76, 1),
(1061, 76, 1),
(1062, 76, 1),
(1063, 76, 1),
(1064, 76, 1),
(1065, 76, 1),
(1066, 76, 1),
(1067, 76, 1),
(1068, 76, 1),
(1069, 76, 2),
(1070, 76, 2),
(1071, 76, 2),
(1072, 76, 2),
(1073, 76, 2),
(1074, 76, 2),
(1075, 76, 2),
(1076, 76, 2),
(1077, 76, 2),
(1078, 76, 2),
(1079, 77, 1),
(1080, 77, 1),
(1081, 77, 1),
(1082, 77, 1),
(1083, 77, 1),
(1084, 77, 1),
(1085, 77, 2),
(1086, 77, 2),
(1087, 77, 2),
(1088, 77, 2),
(1089, 77, 2),
(1090, 77, 2),
(1091, 77, 2),
(1092, 78, 1),
(1093, 78, 1),
(1094, 78, 1),
(1095, 78, 1),
(1096, 78, 1),
(1097, 78, 1),
(1098, 78, 1),
(1099, 78, 1),
(1100, 78, 1),
(1101, 78, 1),
(1102, 78, 1),
(1103, 78, 2),
(1104, 78, 2),
(1105, 78, 2),
(1106, 78, 2),
(1107, 78, 2),
(1108, 78, 2),
(1109, 78, 2),
(1110, 78, 2),
(1111, 78, 2),
(1112, 79, 1),
(1113, 79, 1),
(1114, 79, 1),
(1115, 79, 1),
(1116, 79, 1),
(1117, 79, 1),
(1118, 79, 1),
(1119, 79, 1),
(1120, 79, 2),
(1121, 79, 2),
(1122, 79, 2),
(1123, 79, 2),
(1124, 79, 2),
(1125, 79, 2),
(1126, 79, 2),
(1127, 80, 1),
(1128, 80, 1),
(1129, 80, 1),
(1130, 80, 1),
(1131, 80, 1),
(1132, 80, 1),
(1133, 80, 1),
(1134, 80, 1),
(1135, 80, 2),
(1136, 80, 2),
(1137, 80, 2),
(1138, 80, 2),
(1139, 80, 2),
(1140, 80, 2),
(1141, 80, 2),
(1142, 80, 2),
(1143, 80, 2),
(1144, 81, 1),
(1145, 81, 1),
(1146, 81, 1),
(1147, 81, 1),
(1148, 81, 1),
(1149, 81, 1),
(1150, 81, 2),
(1151, 81, 2),
(1152, 81, 2),
(1153, 81, 2),
(1154, 81, 2),
(1155, 81, 2),
(1156, 81, 2),
(1157, 82, 1),
(1158, 82, 1),
(1159, 82, 1),
(1160, 82, 1),
(1161, 82, 1),
(1162, 82, 1),
(1163, 82, 1),
(1164, 82, 1),
(1165, 82, 1),
(1166, 82, 1),
(1167, 82, 2),
(1168, 82, 2),
(1169, 82, 2),
(1170, 82, 2),
(1171, 82, 2),
(1172, 82, 2),
(1173, 82, 2),
(1174, 82, 2),
(1175, 82, 2),
(1176, 82, 2),
(1177, 82, 2),
(1178, 83, 1),
(1179, 83, 1),
(1180, 83, 1),
(1181, 83, 1),
(1182, 83, 1),
(1183, 83, 1),
(1184, 83, 2),
(1185, 83, 2),
(1186, 83, 2),
(1187, 83, 2),
(1188, 83, 2),
(1189, 83, 2),
(1190, 83, 2),
(1191, 84, 1),
(1192, 84, 1),
(1193, 84, 1),
(1194, 84, 1),
(1195, 84, 1),
(1196, 84, 1),
(1197, 84, 2),
(1198, 84, 2),
(1199, 84, 2),
(1200, 84, 2),
(1201, 84, 2),
(1202, 84, 2),
(1203, 84, 2),
(1204, 84, 2),
(1205, 84, 2),
(1206, 85, 1),
(1207, 85, 1),
(1208, 85, 1),
(1209, 85, 1),
(1210, 85, 1),
(1211, 85, 2),
(1212, 85, 2),
(1213, 85, 2),
(1214, 85, 2),
(1215, 85, 2),
(1216, 85, 2),
(1217, 86, 1),
(1218, 86, 1),
(1219, 86, 1),
(1220, 86, 1),
(1221, 86, 1),
(1222, 86, 1),
(1223, 86, 2),
(1224, 86, 2),
(1225, 86, 2),
(1226, 86, 2),
(1227, 86, 2),
(1228, 86, 2),
(1229, 87, 1),
(1230, 87, 1),
(1231, 87, 1),
(1232, 87, 1),
(1233, 87, 1),
(1234, 87, 1),
(1235, 88, 1),
(1236, 88, 1),
(1237, 88, 1),
(1238, 88, 1),
(1239, 88, 1),
(1240, 88, 1),
(1241, 88, 2),
(1242, 88, 2),
(1243, 88, 2),
(1244, 88, 2),
(1245, 88, 2),
(1246, 88, 2),
(1247, 88, 2),
(1248, 88, 2),
(1249, 89, 1),
(1250, 89, 1),
(1251, 89, 1),
(1252, 89, 1),
(1253, 89, 1),
(1254, 89, 1),
(1255, 89, 1),
(1256, 89, 2),
(1257, 89, 2),
(1258, 89, 2),
(1259, 89, 2),
(1260, 89, 2),
(1261, 89, 2),
(1262, 89, 2),
(1263, 89, 2),
(1264, 90, 1),
(1265, 90, 1),
(1266, 90, 1),
(1267, 90, 1),
(1268, 90, 1),
(1269, 90, 1),
(1270, 90, 1),
(1271, 90, 1),
(1272, 90, 2),
(1273, 90, 2),
(1274, 90, 2),
(1275, 90, 2),
(1276, 90, 2),
(1277, 90, 2),
(1278, 90, 2),
(1279, 90, 2),
(1280, 91, 1),
(1281, 91, 1),
(1282, 91, 1),
(1283, 91, 1),
(1284, 91, 1),
(1285, 91, 1),
(1286, 91, 1),
(1287, 91, 2),
(1288, 91, 2),
(1289, 91, 2),
(1290, 91, 2),
(1291, 91, 2),
(1292, 91, 2),
(1293, 91, 2),
(1294, 91, 2),
(1295, 92, 1),
(1296, 92, 1),
(1297, 92, 1),
(1298, 92, 1),
(1299, 92, 1),
(1300, 92, 1),
(1301, 92, 2),
(1302, 92, 2),
(1303, 92, 2),
(1304, 92, 2),
(1305, 92, 2),
(1306, 92, 2),
(1307, 92, 2),
(1308, 93, 1),
(1309, 93, 1),
(1310, 93, 1),
(1311, 93, 1),
(1312, 93, 1),
(1313, 93, 1),
(1314, 93, 2),
(1315, 93, 2),
(1316, 93, 2),
(1317, 93, 2),
(1318, 93, 2),
(1319, 93, 2),
(1320, 93, 2),
(1321, 93, 2),
(1322, 93, 2),
(1323, 94, 1),
(1324, 94, 1),
(1325, 94, 1),
(1326, 94, 1),
(1327, 94, 1),
(1328, 94, 1),
(1329, 94, 1),
(1330, 94, 2),
(1331, 94, 2),
(1332, 94, 2),
(1333, 94, 2),
(1334, 94, 2),
(1335, 94, 2),
(1336, 94, 2),
(1337, 94, 2),
(1338, 94, 2),
(1339, 95, 1),
(1340, 95, 1),
(1341, 95, 1),
(1342, 95, 1),
(1343, 95, 1),
(1344, 95, 1),
(1345, 95, 1),
(1346, 95, 2),
(1347, 95, 2),
(1348, 95, 2),
(1349, 95, 2),
(1350, 95, 2),
(1351, 95, 2),
(1352, 95, 2),
(1353, 95, 2);

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `merit_badge_tasks`
--
ALTER TABLE `merit_badge_tasks`
  ADD UNIQUE KEY `task_id` (`task_id`) USING BTREE,
  ADD KEY `level_id` (`level_id`),
  ADD KEY `merit_bedge_id` (`merit_badge_id`);

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `merit_badge_tasks`
--
ALTER TABLE `merit_badge_tasks`
  ADD CONSTRAINT `merit_badge_tasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `merit_badge_tasks_ibfk_2` FOREIGN KEY (`level_id`) REFERENCES `levels_of_merit_badge` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `merit_badge_tasks_ibfk_3` FOREIGN KEY (`merit_badge_id`) REFERENCES `merit_badges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
