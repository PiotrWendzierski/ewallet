-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Maj 17, 2024 at 06:26 PM
-- Wersja serwera: 8.4.0
-- Wersja PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_docker`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `numer_kategorii` int NOT NULL COMMENT 'numer kategorii',
  `kategoria` text COLLATE utf8mb4_polish_ci NOT NULL COMMENT 'nazwa kategorii',
  `id` int NOT NULL COMMENT 'id uzytkownika czyli który uzytkownik ma taką kategorię w swoich wydatkach',
  `ilosc_transakcji` int NOT NULL COMMENT 'ile dany uzytkownik razy wydał pieniądze na daną kategorię',
  `wplywwyplyw` text COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `kategorie`
--

INSERT INTO `kategorie` (`numer_kategorii`, `kategoria`, `id`, `ilosc_transakcji`, `wplywwyplyw`) VALUES
(367, 'biedronka', 47, 6, 'wyplyw'),
(383, 'biedronka', 55, 3, 'wyplyw'),
(384, 'leclerc', 55, 1, 'wyplyw'),
(385, 'auto', 55, 1, 'wyplyw'),
(386, 'WYPŁATA', 55, 2, 'wplyw'),
(387, 'Pysia oddała', 55, 1, 'wplyw'),
(388, 'wypłata', 47, 4, 'wplyw'),
(389, 'Pysia oddała', 47, 1, 'wplyw'),
(390, 'auto', 47, 1, 'wyplyw'),
(392, 'auto', 59, 1, 'wyplyw'),
(393, 'czynsz', 59, 1, 'wyplyw'),
(394, 'ładowarka ', 55, 1, 'wyplyw'),
(395, 'Olejek do epeta', 55, 1, 'wyplyw'),
(396, 'ładowarka', 47, 1, 'wyplyw'),
(397, 'leclerc', 47, 1, 'wyplyw'),
(398, 'płyn do auta', 47, 1, 'wyplyw'),
(399, 'czynsz', 47, 1, 'wyplyw'),
(400, 'auto', 61, 1, 'wyplyw'),
(401, 'biedronka', 61, 2, 'wyplyw'),
(402, 'wypłata', 61, 2, 'wplyw'),
(403, 'leclerc', 61, 1, 'wyplyw'),
(404, 'biedronkaaaa', 61, 1, 'wyplyw'),
(405, 'kosmetycka', 61, 1, 'wyplyw'),
(406, 'il', 61, 1, 'wyplyw'),
(407, 'nowy laptop', 61, 1, 'wyplyw'),
(408, 'biedronka', 71, 1, 'wyplyw'),
(409, 'wypłata', 71, 1, 'wplyw'),
(411, 'az', 73, 1, 'wplyw');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `transakcje`
--

CREATE TABLE `transakcje` (
  `id_transakcji` int NOT NULL COMMENT 'numer transakcji',
  `id` int NOT NULL COMMENT 'id uzytkownika który dokonał transakcji',
  `kategoria` text CHARACTER SET utf8mb3 COLLATE utf8mb3_polish_ci NOT NULL COMMENT 'nazwa kategorii wydatku',
  `cena` float NOT NULL COMMENT 'cena wydatku',
  `data` date NOT NULL COMMENT 'data transakcji',
  `saldo` float NOT NULL COMMENT 'saldo po operacji',
  `wplywwyplyw` text COLLATE utf8mb4_polish_ci NOT NULL COMMENT 'info czy to wpływ czy wypływ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `transakcje`
--

INSERT INTO `transakcje` (`id_transakcji`, `id`, `kategoria`, `cena`, `data`, `saldo`, `wplywwyplyw`) VALUES
(680, 47, 'biedronka', -100, '2023-11-17', 0, 'wyplyw'),
(681, 47, 'biedronka', -100, '2023-11-16', 0, 'wyplyw'),
(685, 55, 'biedronka', -100, '2023-11-18', 0, 'wyplyw'),
(686, 55, 'leclerc', -100, '2023-11-17', 0, 'wyplyw'),
(687, 55, 'auto', -100, '2023-11-16', 0, 'wyplyw'),
(688, 55, 'biedronka', -100, '2023-11-15', 0, 'wyplyw'),
(689, 55, 'WYPŁATA', 4000, '2023-11-10', 0, 'wplyw'),
(690, 55, 'Pysia oddała', 200, '2023-11-19', 0, 'wplyw'),
(691, 47, 'wypłata', 4000, '2023-11-18', 0, 'wplyw'),
(692, 47, 'Pysia oddała', 1400, '2023-11-09', 0, 'wplyw'),
(693, 47, 'auto', -1000, '2023-11-23', 0, 'wyplyw'),
(695, 59, 'auto', -500, '2023-11-16', 0, 'wyplyw'),
(696, 59, 'czynsz', -1000, '2023-10-10', 0, 'wyplyw'),
(697, 55, 'biedronka', -100, '2023-10-05', 0, 'wyplyw'),
(698, 55, 'ładowarka ', -80, '2023-12-05', 0, 'wyplyw'),
(699, 55, 'Olejek do epeta', -22, '2023-12-04', 0, 'wyplyw'),
(700, 55, 'wypłata', 4000, '2023-12-01', 0, 'wplyw'),
(701, 47, 'wypłata', 4000, '2023-10-10', 0, 'wplyw'),
(702, 47, 'wypłata', 3500, '2023-12-08', 0, 'wplyw'),
(703, 47, 'ładowarka', -80, '2023-12-06', 0, 'wyplyw'),
(705, 47, 'leclerc', -50, '2023-12-05', 0, 'wyplyw'),
(706, 47, 'biedronka', -40, '2023-12-04', 0, 'wyplyw'),
(707, 47, 'płyn do auta', -30, '2023-12-07', 0, 'wyplyw'),
(708, 47, 'czynsz', -2850, '2023-12-08', 0, 'wyplyw'),
(709, 61, 'auto', -50, '2023-12-15', 0, 'wyplyw'),
(710, 61, 'biedronka', -5, '2023-12-16', 0, 'wyplyw'),
(711, 61, 'wypłata', 5000, '2023-08-11', 0, 'wplyw'),
(712, 61, 'wypłata', 500, '2023-12-17', 0, 'wplyw'),
(713, 61, 'leclerc', -53, '2023-12-15', 0, 'wyplyw'),
(714, 61, 'biedronkaaaa', -75, '2023-12-12', 0, 'wyplyw'),
(715, 61, 'biedronka', -75, '2023-12-06', 0, 'wyplyw'),
(716, 61, 'kosmetycka', -67, '2023-12-08', 0, 'wyplyw'),
(717, 61, 'il', -689, '2023-12-04', 0, 'wyplyw'),
(718, 61, 'nowy laptop', -1000, '2023-11-14', 0, 'wyplyw'),
(719, 71, 'biedronka', -100, '2024-01-02', 0, 'wyplyw'),
(720, 71, 'wypłata', 1000, '2024-01-04', 0, 'wplyw'),
(721, 47, 'biedronka', -11900, '2024-01-03', 0, 'wyplyw'),
(722, 47, 'biedronka', -4200, '2024-01-01', 0, 'wyplyw'),
(726, 47, 'wypłata', 4000, '2024-01-10', 0, 'wplyw'),
(727, 73, 'az', 344353, '2024-05-01', 0, 'wplyw'),
(728, 47, 'biedronka', -100, '2024-05-16', 0, 'wyplyw');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `transakcji_skarbonki`
--

CREATE TABLE `transakcji_skarbonki` (
  `id_transakcji` int NOT NULL,
  `id` int NOT NULL,
  `data_transakcji` date NOT NULL,
  `kwota_przeznaczona` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `transakcji_skarbonki`
--

INSERT INTO `transakcji_skarbonki` (`id_transakcji`, `id`, `data_transakcji`, `kwota_przeznaczona`) VALUES
(85, 56, '2023-10-04', 100),
(88, 47, '2023-10-19', 1000),
(89, 55, '2023-11-19', 100),
(93, 61, '2023-12-13', -8),
(94, 61, '2023-12-19', 10);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int NOT NULL,
  `email` text COLLATE utf8mb3_polish_ci NOT NULL,
  `login` text COLLATE utf8mb3_polish_ci NOT NULL,
  `haslo` text COLLATE utf8mb3_polish_ci NOT NULL,
  `stan_konta` float NOT NULL,
  `skarbonka` int NOT NULL,
  `cel_oszczednosci` text COLLATE utf8mb3_polish_ci NOT NULL,
  `potrzebna_ilosc` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_polish_ci;

--
-- Dumping data for table `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `email`, `login`, `haslo`, `stan_konta`, `skarbonka`, `cel_oszczednosci`, `potrzebna_ilosc`) VALUES
(47, 'piotrwendzierski@gmail.com', 'piotr', '$2y$10$wbCngasfhjEcsFp.nZnNSuT/vdGwwy.58aP6sT0VJ2.SCwKrkopmy', 6900, 1000, 'DOM', 5000),
(55, 'piotekrwendzierski@gmail.com', 'pysia', '$2y$10$Gicp27bGuYPCzgwZJqWPt.kmGbwApYrIcoSv8snybMnWfCdIXRvyi', 8698, 100, 'PS5', 2000),
(56, 'sfdd@gmail.com', 'piotr123', '$2y$10$ohp1eWS3xVpyxppyCh2vjOojllfn9mte0Jqfiz.220ePlW/s0R5xy', 200, 100, 'wakacje', 2000),
(57, 'jacek@gmail.com', 'jacek', '$2y$10$vqgGnpEui0b80p.uTXgi0uhPxDul.BxqOapOdnbtQ9JFOViNLGiIy', 3200, 0, 'brak', 0),
(58, 'piotr1234@gmail.com', 'piotr1234', '$2y$10$Mcx6VDAZ15abpGbkvjWRAOJ/YPjbWWweV5LdiCVGwgB.I24VPnNya', 3000, 0, 'brak', 0),
(59, 'yt123@gmail.om', 'krzys', '$2y$10$XdHmqQDVqPP0m.hVI9dcFeCpnNkFYzhOAc2f5b3tbFaQLb4bDgENi', 1500, 0, 'brak', 0),
(60, 'jop@gmail.com', 'jop', '$2y$10$AEZoLoLe847UqPmlyYlsruck2up39H/UyhfkY2tHqSNmNHS6XKiBS', 5000, 0, 'brak', 0),
(61, 'patrycja@gmail.com', 'pati', '$2y$10$NNLHn7HicAKZ6OeZFqthnOVx8moQNOcRUJiKq4ZkHJctaKOHJJIwK', 3584, 2, 'WESELE', 1000),
(62, 'john@gmail.com', 'john', '$2y$10$oatdqSEzr95Y.qngBn9SHOf5lvvTbG7sxxT2/WnHS68K07YepYVe2', 10000, 0, 'brak', 0),
(72, 'gandalf@gmail.com', 'gandalf', '$2y$10$TeqSWJHJ646lrMytXLqSCOTZu2rvxCBvAj8Ieyd3CvhxGMvAhcgni', 6300, 0, 'brak', 0),
(73, 'pati.1997.pf@gmail.com', 'pancia', '$2y$10$sNStbddgeT4Xd87rp0sUY.VR82suzbV7l7xkOAh2nY/EBt6LXm52a', 367797, 0, 'brak', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`numer_kategorii`);

--
-- Indeksy dla tabeli `transakcje`
--
ALTER TABLE `transakcje`
  ADD PRIMARY KEY (`id_transakcji`);

--
-- Indeksy dla tabeli `transakcji_skarbonki`
--
ALTER TABLE `transakcji_skarbonki`
  ADD PRIMARY KEY (`id_transakcji`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `numer_kategorii` int NOT NULL AUTO_INCREMENT COMMENT 'numer kategorii', AUTO_INCREMENT=412;

--
-- AUTO_INCREMENT for table `transakcje`
--
ALTER TABLE `transakcje`
  MODIFY `id_transakcji` int NOT NULL AUTO_INCREMENT COMMENT 'numer transakcji', AUTO_INCREMENT=729;

--
-- AUTO_INCREMENT for table `transakcji_skarbonki`
--
ALTER TABLE `transakcji_skarbonki`
  MODIFY `id_transakcji` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
