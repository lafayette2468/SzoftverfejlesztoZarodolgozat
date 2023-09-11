-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Gép: localhost
-- Létrehozás ideje: 2020. Dec 20. 21:43
-- Kiszolgáló verziója: 8.0.17
-- PHP verzió: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `te`
--
CREATE DATABASE IF NOT EXISTS `te` DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci;
USE `te`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `assets`
--

CREATE TABLE `assets` (
  `asset_no` int(11) NOT NULL,
  `asset_ser` int(7) NOT NULL,
  `asset_name` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `co_id` int(11) NOT NULL,
  `gl_id` int(11) NOT NULL,
  `depr_percent` decimal(11,0) NOT NULL,
  `gr_value` int(11) NOT NULL,
  `cap_date` date NOT NULL,
  `decap_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `assets`
--

INSERT INTO `assets` (`asset_no`, `asset_ser`, `asset_name`, `co_id`, `gl_id`, `depr_percent`, `gr_value`, `cap_date`, `decap_date`) VALUES
(2, 1000001, 'Íróasztal B4', 7, 1430, '5', 150000, '2020-12-09', '2020-12-13'),
(3, 1000001, 'Személygk. ABC-123', 8, 1420, '33', 5000000, '2020-11-04', NULL),
(4, 1000002, 'Laptop Asus s15', 7, 1430, '33', 250000, '2020-12-13', '2021-08-01'),
(5, 1000003, 'Telek Mellék utca 2.', 7, 1210, '0', 62500000, '2021-08-31', NULL),
(6, 1000001, 'Windows 10', 10, 1130, '3', 25000, '2020-12-01', '2021-03-15'),
(7, 1000004, 'SQL licenc', 7, 1130, '33', 100000, '2021-08-01', NULL),
(8, 1000005, 'SQL licenc 2', 7, 1130, '33', 150000, '2021-10-01', NULL),
(9, 1000006, 'Fejlesztés - online program', 7, 1140, '20', 55000, '2021-10-01', NULL),
(10, 1000007, 'Pöttyös Boci', 7, 1510, '25', 300000, '2021-11-01', NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `booking`
--

CREATE TABLE `booking` (
  `doc_no` int(11) NOT NULL,
  `asset_no` int(11) NOT NULL,
  `month` date NOT NULL,
  `depr_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `booking`
--

INSERT INTO `booking` (`doc_no`, `asset_no`, `month`, `depr_amount`) VALUES
(1, 2, '2020-12-31', 102),
(2, 4, '2020-12-31', 4283),
(3, 4, '2021-01-31', 7007),
(4, 4, '2021-02-28', 6329),
(5, 4, '2021-03-31', 7007),
(6, 4, '2021-04-30', 6781),
(7, 4, '2021-05-31', 7007),
(8, 4, '2021-06-30', 6781),
(9, 4, '2021-07-31', 7007),
(10, 6, '2020-12-31', 64),
(11, 6, '2021-01-31', 64),
(12, 6, '2021-02-28', 58),
(13, 6, '2021-03-31', 24814),
(14, 4, '2021-08-31', 226),
(15, 5, '2021-08-31', 0),
(16, 7, '2021-08-31', 2803),
(17, 5, '2021-09-30', 0),
(18, 7, '2021-09-30', 2712),
(19, 5, '2021-10-31', 0),
(20, 7, '2021-10-31', 2803),
(21, 8, '2021-10-31', 4204),
(22, 9, '2021-10-31', 934),
(23, 5, '2021-11-30', 0),
(24, 7, '2021-11-30', 2712),
(25, 8, '2021-11-30', 4068),
(26, 9, '2021-11-30', 904),
(27, 10, '2021-11-30', 6164),
(28, 5, '2021-12-31', 0),
(29, 7, '2021-12-31', 2803),
(30, 8, '2021-12-31', 4204),
(31, 9, '2021-12-31', 934),
(32, 10, '2021-12-31', 6370),
(33, 5, '2022-01-31', 0),
(34, 7, '2022-01-31', 2803),
(35, 8, '2022-01-31', 4204),
(36, 9, '2022-01-31', 934),
(37, 10, '2022-01-31', 6370),
(38, 5, '2022-02-28', 0),
(39, 7, '2022-02-28', 2532),
(40, 8, '2022-02-28', 3797),
(41, 9, '2022-02-28', 844),
(42, 10, '2022-02-28', 5753),
(43, 5, '2022-03-31', 0),
(44, 7, '2022-03-31', 2803),
(45, 8, '2022-03-31', 4204),
(46, 9, '2022-03-31', 934),
(47, 10, '2022-03-31', 6370),
(48, 5, '2022-04-30', 0),
(49, 7, '2022-04-30', 2712),
(50, 8, '2022-04-30', 4068),
(51, 9, '2022-04-30', 904),
(52, 10, '2022-04-30', 6164),
(53, 5, '2022-05-31', 0),
(54, 7, '2022-05-31', 2803),
(55, 8, '2022-05-31', 4204),
(56, 9, '2022-05-31', 934),
(57, 10, '2022-05-31', 6370),
(58, 5, '2022-06-30', 0),
(59, 7, '2022-06-30', 2712),
(60, 8, '2022-06-30', 4068),
(61, 9, '2022-06-30', 904),
(62, 10, '2022-06-30', 6164),
(63, 5, '2022-07-31', 0),
(64, 7, '2022-07-31', 2803),
(65, 8, '2022-07-31', 4204),
(66, 9, '2022-07-31', 934),
(67, 10, '2022-07-31', 6370),
(68, 5, '2022-07-31', 0),
(69, 7, '2022-07-31', 2803),
(70, 8, '2022-07-31', 4204),
(71, 9, '2022-07-31', 934),
(72, 10, '2022-07-31', 6370);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `companies`
--

CREATE TABLE `companies` (
  `co_id` int(11) NOT NULL,
  `co_name` varchar(200) COLLATE utf8_hungarian_ci NOT NULL,
  `co_taxno` varchar(13) COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `companies`
--

INSERT INTO `companies` (`co_id`, `co_name`, `co_taxno`) VALUES
(7, 'ABC Kft.', '12345678'),
(8, 'CBA Zrt.', '87654321'),
(9, 'ZZZ Bt.', '11111111'),
(10, 'XXX Zrt.', '22222222');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `connect`
--

CREATE TABLE `connect` (
  `conn_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `co_id` int(11) NOT NULL,
  `role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `connect`
--

INSERT INTO `connect` (`conn_id`, `user_id`, `co_id`, `role`) VALUES
(2, 1, 7, 1),
(3, 1, 8, 1),
(4, 2, 9, 1),
(20, 1, 10, 1),
(24, 1, 9, 2),
(25, 4, 7, 2),
(26, 2, 10, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `depr`
--

CREATE TABLE `depr` (
  `depr_percent` decimal(10,0) NOT NULL,
  `depr_years` text COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `depr`
--

INSERT INTO `depr` (`depr_percent`, `depr_years`) VALUES
('0', 'nem amortizálódik'),
('1', '100 év'),
('2', '50 év'),
('3', '33 év'),
('4', '25 év'),
('5', '20 év'),
('6', '17 év'),
('8', '12,5 év'),
('10', '10 év'),
('14', '7 év'),
('20', '5 év'),
('25', '4 év'),
('33', '3 év'),
('50', '2 év'),
('100', '1 év');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `gl`
--

CREATE TABLE `gl` (
  `gl_id` int(4) NOT NULL,
  `gl_name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `gl`
--

INSERT INTO `gl` (`gl_id`, `gl_name`) VALUES
(1110, 'Alapítás-átszervezés aktivált értéke'),
(1120, 'Kísérleti fejlesztés aktivált értéke'),
(1130, 'Vagyoni értékű jogok'),
(1140, 'Szellemi termékek'),
(1210, 'Földterület'),
(1220, 'Telek, telkesítés'),
(1230, 'Épületek, épületrészek, tulajdoni hányadok'),
(1240, 'Egyéb építmények'),
(1250, 'Üzemkörön kívüli ingatlanok, épületek'),
(1260, 'Ingatlanokhoz kapcsolódó vagyoni értékű jogok'),
(1310, 'Termelő gépek, berendezések, szerszámok, gyártóeszközök'),
(1320, 'Termelésben közvetlenül résztvevő járművek'),
(1410, 'Üzemi (üzleti) gépek, berendezések, felszerelések'),
(1420, 'Egyéb járművek'),
(1430, 'Irodai, igazgatási berendezések és felszerelések'),
(1440, 'Üzemkörön kívüli berendezések, felszerelések, járművek'),
(1450, 'Jóléti berendezések, felszerelési tárgyak és képzőművészeti alkotások'),
(1510, 'Tenyészállatok'),
(1520, 'Igásállatok'),
(1530, 'Egyéb állatok'),
(1610, 'Befejezetlen beruházások');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reg_date` timestamp NOT NULL,
  `first_name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `last_login`, `reg_date`, `first_name`, `last_name`) VALUES
(1, 'a@a.hu', '0cc175b9c0f1b6a831c399e269772661', '2020-12-20 20:14:19', '2020-11-30 16:39:54', 'Adél', 'Aradi'),
(2, 'b@b.hu', '92eb5ffee6ae2fec3ad71c777531578f', '2020-12-20 17:39:24', '2020-11-30 16:46:11', 'Kata', 'Kiss'),
(4, 'c@c.hu', '4a8a08f09d37b73795649038408b5f33', '2020-12-20 17:39:53', '2020-12-20 15:14:49', '', ''),
(5, 'd@d.hu', '8277e0910d750195b448797616e091ad', '2020-12-20 17:35:07', '2020-12-20 17:35:07', '', '');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_no`),
  ADD KEY `co_id` (`co_id`),
  ADD KEY `gl_id` (`gl_id`),
  ADD KEY `depr_percent` (`depr_percent`);

--
-- A tábla indexei `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`doc_no`),
  ADD KEY `asset_no` (`asset_no`);

--
-- A tábla indexei `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`co_id`),
  ADD KEY `co_id` (`co_id`);

--
-- A tábla indexei `connect`
--
ALTER TABLE `connect`
  ADD PRIMARY KEY (`conn_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `connect_ibfk_1` (`co_id`);

--
-- A tábla indexei `depr`
--
ALTER TABLE `depr`
  ADD PRIMARY KEY (`depr_percent`);

--
-- A tábla indexei `gl`
--
ALTER TABLE `gl`
  ADD PRIMARY KEY (`gl_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `booking`
--
ALTER TABLE `booking`
  MODIFY `doc_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT a táblához `companies`
--
ALTER TABLE `companies`
  MODIFY `co_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `connect`
--
ALTER TABLE `connect`
  MODIFY `conn_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`co_id`) REFERENCES `companies` (`co_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`gl_id`) REFERENCES `gl` (`gl_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `assets_ibfk_3` FOREIGN KEY (`depr_percent`) REFERENCES `depr` (`depr_percent`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Megkötések a táblához `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`asset_no`) REFERENCES `assets` (`asset_no`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Megkötések a táblához `connect`
--
ALTER TABLE `connect`
  ADD CONSTRAINT `connect_ibfk_1` FOREIGN KEY (`co_id`) REFERENCES `companies` (`co_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `connect_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
