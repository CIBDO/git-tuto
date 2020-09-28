-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 24, 2020 at 06:22 PM
-- Server version: 5.7.26
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `target_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `donnees_hopital`
--

CREATE TABLE `donnees_hopital`
(
    `id`          int(11) NOT NULL,
    `code_asc`    varchar(45) DEFAULT NULL,
    `commentaire` text,
    `date_rdv`    date        DEFAULT NULL,
    `user_id`     int(11) NOT NULL,
    `patients_id` int(11) NOT NULL,
    `created`     datetime    DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donnees_hopital`
--
ALTER TABLE `donnees_hopital`
    ADD PRIMARY KEY (`id`),
    ADD KEY `fk_donnees_hopital_user1_idx` (`user_id`),
    ADD KEY `fk_donnees_hopital_patients1_idx` (`patients_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donnees_hopital`
--
ALTER TABLE `donnees_hopital`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donnees_hopital`
--
ALTER TABLE `donnees_hopital`
    ADD CONSTRAINT `fk_donnees_hopital_patients1` FOREIGN KEY (`patients_id`) REFERENCES `patients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `fk_donnees_hopital_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
