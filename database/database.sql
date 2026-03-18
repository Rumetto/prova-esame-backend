-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 18, 2026 alle 15:39
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prova_esame`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `eventi`
--

CREATE TABLE `eventi` (
  `evento_id` int(11) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `data_evento` datetime NOT NULL,
  `descrizione` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `eventi`
--

INSERT INTO `eventi` (`evento_id`, `titolo`, `data_evento`, `descrizione`, `created_at`) VALUES
(1, 'Corso Excel Avanzato', '2026-03-19 09:30:00', 'Formazione avanzata su formule, tabelle pivot e dashboard Excel.', '2026-03-18 10:20:58'),
(2, 'Introduzione alla Cybersecurity', '2026-04-15 14:30:00', 'Principi base di sicurezza informatica per dipendenti.', '2026-03-18 10:20:58'),
(3, 'Public Speaking per Team Leader', '2026-04-22 10:00:00', 'Tecniche di comunicazione efficace in ambito aziendale.', '2026-03-18 10:20:58'),
(4, 'Gestione del tempo', '2026-04-28 11:00:00', 'Metodi pratici per migliorare produttività e organizzazione personale.', '2026-03-18 10:20:58'),
(5, 'Workshop AI in azienda', '2026-02-10 15:00:00', 'Uso pratico dell’intelligenza artificiale nei processi aziendali.fsthve', '2026-03-18 10:20:58'),
(6, 'corso di javascript', '2026-04-04 09:03:00', 'corso di js', '2026-03-18 10:20:58'),
(7, 'Benessere sul lavoro', '2026-02-25 16:00:00', 'Consigli su postura, stress management e benessere psicofisico.', '2026-03-18 10:20:58'),
(8, 'Corso PHP SUPER Avanzato', '2026-06-26 11:00:00', 'Evento aggiornato', '2026-03-18 11:22:16'),
(9, 'corso di uncinetto', '2026-04-16 13:54:00', 'vi insegnero tutti i nodi', '2026-03-18 12:52:05'),
(10, 'fyubjr', '2026-03-27 14:15:00', 'jnm', '2026-03-18 13:16:05');

-- --------------------------------------------------------

--
-- Struttura della tabella `iscrizioni`
--

CREATE TABLE `iscrizioni` (
  `iscrizione_id` int(11) NOT NULL,
  `utente_id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `checkin_effettuato` tinyint(1) NOT NULL DEFAULT 0,
  `ora_checkin` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `iscrizioni`
--

INSERT INTO `iscrizioni` (`iscrizione_id`, `utente_id`, `evento_id`, `checkin_effettuato`, `ora_checkin`, `created_at`) VALUES
(2, 4, 1, 1, '2026-03-18 14:07:33', '2026-03-18 10:21:18'),
(3, 5, 2, 0, NULL, '2026-03-18 10:21:18'),
(4, 6, 3, 1, '2026-03-18 14:17:55', '2026-03-18 10:21:18'),
(5, 3, 5, 1, '2026-02-10 14:57:00', '2026-03-18 10:21:18'),
(6, 4, 5, 1, '2026-02-10 14:59:00', '2026-03-18 10:21:18'),
(7, 5, 5, 0, NULL, '2026-03-18 10:21:18'),
(9, 4, 6, 1, '2026-01-20 08:57:00', '2026-03-18 10:21:18'),
(10, 6, 6, 1, '2026-01-20 08:59:00', '2026-03-18 10:21:18'),
(11, 5, 7, 1, '2026-03-18 14:38:53', '2026-03-18 10:21:18'),
(12, 6, 7, 1, '2026-02-25 15:58:00', '2026-03-18 10:21:18'),
(17, 8, 4, 0, NULL, '2026-03-18 12:48:30'),
(18, 8, 3, 0, NULL, '2026-03-18 12:48:33'),
(19, 8, 2, 0, NULL, '2026-03-18 12:48:35'),
(24, 8, 8, 1, '2026-03-18 14:28:27', '2026-03-18 13:16:32');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `utente_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ruolo` enum('dipendente','organizzatore') NOT NULL DEFAULT 'dipendente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`utente_id`, `nome`, `cognome`, `email`, `password`, `ruolo`, `created_at`) VALUES
(1, 'Mario', 'Rossi', 'organizer1@azienda.it', '$2y$10$z8wwSiV.IpUugtaRQzNuZe4YIOu7K1OBLqIToHzxFBlJ4.SYz1bQK', 'organizzatore', '2026-03-18 10:20:34'),
(2, 'Lucia', 'Bianchi', 'organizer2@azienda.it', '$2y$10$z8wwSiV.IpUugtaRQzNuZe4YIOu7K1OBLqIToHzxFBlJ4.SYz1bQK', 'organizzatore', '2026-03-18 10:20:34'),
(3, 'Anna', 'Verdi', 'anna@azienda.it', '$2y$10$z8wwSiV.IpUugtaRQzNuZe4YIOu7K1OBLqIToHzxFBlJ4.SYz1bQK', 'dipendente', '2026-03-18 10:20:34'),
(4, 'Paolo', 'Neri', 'paolo@azienda.it', '$2y$10$z8wwSiV.IpUugtaRQzNuZe4YIOu7K1OBLqIToHzxFBlJ4.SYz1bQK', 'dipendente', '2026-03-18 10:20:34'),
(5, 'Sara', 'Gialli', 'sara@azienda.it', '$2y$10$z8wwSiV.IpUugtaRQzNuZe4YIOu7K1OBLqIToHzxFBlJ4.SYz1bQK', 'dipendente', '2026-03-18 10:20:34'),
(6, 'Luca', 'Blu', 'luca@azienda.it', '$2y$10$z8wwSiV.IpUugtaRQzNuZe4YIOu7K1OBLqIToHzxFBlJ4.SYz1bQK', 'dipendente', '2026-03-18 10:20:34'),
(7, 'Test', 'Utente', 'testutente@azienda.it', '$2y$10$0DcCKi7kWq7FJJar76r1/OS8LV0kmD/NZNij4ywWnPQfVnfUW5BaK', 'dipendente', '2026-03-18 11:28:38'),
(8, 'rumen', 'bortoletto', 'rumen130601@gmail.com', '$2y$10$wcF2Ntevxr5u8NX7Mh8UlOQHON0Ys431jl/jLZugTLfbM5MKIPrwe', 'dipendente', '2026-03-18 12:48:00'),
(9, 'genoveffa', 'gianferrazzi', 'gianferrazzigenoveffa@gmail.com', '$2y$10$ka8wHqjkPXYIY.bSZicPqusBOGxVE0KM7JeaLwnS61ARbLwWvbytC', 'organizzatore', '2026-03-18 12:50:17');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `eventi`
--
ALTER TABLE `eventi`
  ADD PRIMARY KEY (`evento_id`);

--
-- Indici per le tabelle `iscrizioni`
--
ALTER TABLE `iscrizioni`
  ADD PRIMARY KEY (`iscrizione_id`),
  ADD UNIQUE KEY `uq_utente_evento` (`utente_id`,`evento_id`),
  ADD KEY `fk_iscrizioni_evento` (`evento_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`utente_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `eventi`
--
ALTER TABLE `eventi`
  MODIFY `evento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `iscrizioni`
--
ALTER TABLE `iscrizioni`
  MODIFY `iscrizione_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `utente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `iscrizioni`
--
ALTER TABLE `iscrizioni`
  ADD CONSTRAINT `fk_iscrizioni_evento` FOREIGN KEY (`evento_id`) REFERENCES `eventi` (`evento_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_iscrizioni_utente` FOREIGN KEY (`utente_id`) REFERENCES `utenti` (`utente_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
