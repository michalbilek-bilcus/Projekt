-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Sob 08. čen 2024, 18:47
-- Verze serveru: 10.4.32-MariaDB
-- Verze PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `projekt`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `chytanipredmetu`
--

CREATE TABLE `chytanipredmetu` (
  `id` int(11) NOT NULL,
  `highscore` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `chytanipredmetu`
--

INSERT INTO `chytanipredmetu` (`id`, `highscore`) VALUES
(1, 8);

-- --------------------------------------------------------

--
-- Struktura tabulky `flappybird`
--

CREATE TABLE `flappybird` (
  `id` int(11) NOT NULL,
  `highscore` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `flappybird`
--

INSERT INTO `flappybird` (`id`, `highscore`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `hledanimin`
--

CREATE TABLE `hledanimin` (
  `id` int(11) NOT NULL,
  `time` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `hledanimin`
--

INSERT INTO `hledanimin` (`id`, `time`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `klikacka`
--

CREATE TABLE `klikacka` (
  `id` int(11) NOT NULL,
  `highscore` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `klikacka`
--

INSERT INTO `klikacka` (`id`, `highscore`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `pexeso`
--

CREATE TABLE `pexeso` (
  `id` int(11) NOT NULL,
  `time` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `pexeso`
--

INSERT INTO `pexeso` (`id`, `time`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `piskvorky`
--

CREATE TABLE `piskvorky` (
  `id` int(11) NOT NULL,
  `score` int(11) DEFAULT 0,
  `botscore` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `piskvorky`
--

INSERT INTO `piskvorky` (`id`, `score`, `botscore`) VALUES
(1, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatele`
--

CREATE TABLE `uzivatele` (
  `id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `chytanipredmetu_id` int(11) DEFAULT NULL,
  `flappybird_id` int(11) DEFAULT NULL,
  `hledanimin_id` int(11) DEFAULT NULL,
  `klikacka_id` int(11) DEFAULT NULL,
  `pexeso_id` int(11) DEFAULT NULL,
  `piskvorky_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vypisuji data pro tabulku `uzivatele`
--

INSERT INTO `uzivatele` (`id`, `nickname`, `password`, `chytanipredmetu_id`, `flappybird_id`, `hledanimin_id`, `klikacka_id`, `pexeso_id`, `piskvorky_id`) VALUES
(1, 'michal', '$2y$10$9jaR3usu0a0H4r.43Kd7.uPh0/Z8Uf/1YKA3H.xBcpJc5zjZbElFu', 1, 1, 1, 1, 1, 1);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `chytanipredmetu`
--
ALTER TABLE `chytanipredmetu`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `flappybird`
--
ALTER TABLE `flappybird`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `hledanimin`
--
ALTER TABLE `hledanimin`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `klikacka`
--
ALTER TABLE `klikacka`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `pexeso`
--
ALTER TABLE `pexeso`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `piskvorky`
--
ALTER TABLE `piskvorky`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `chytanipredmetu`
--
ALTER TABLE `chytanipredmetu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `flappybird`
--
ALTER TABLE `flappybird`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `hledanimin`
--
ALTER TABLE `hledanimin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `klikacka`
--
ALTER TABLE `klikacka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `pexeso`
--
ALTER TABLE `pexeso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `piskvorky`
--
ALTER TABLE `piskvorky`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
