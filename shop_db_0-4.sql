-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Neděle 11. srpna 2013, 21:21
-- Verze MySQL: 5.1.66
-- Verze PHP: 5.3.3-7+squeeze16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `c301`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `BlogID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `BlogName` varchar(150) NOT NULL,
  `BlogContent` text,
  PRIMARY KEY (`BlogID`),
  KEY `ProductID` (`ProductID`),
  KEY `CategoryID` (`CategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `blog`
--


--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);
