-- Adminer 3.6.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `ModuleID` int(11) NOT NULL AUTO_INCREMENT,
  `ModuleName` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `CompModuleName` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `ModuleDescription` text COLLATE utf8_czech_ci,
  `ModuleType` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`ModuleID`),
  KEY `StatusID` (`StatusID`),
  CONSTRAINT `module_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `module` (`ModuleID`, `ModuleName`, `CompModuleName`, `ModuleDescription`, `ModuleType`, `StatusID`) VALUES
(1,	'Zásilkovna',	'zasilkovna',	'Osobní převzetí v síti Zásilkovna.cz',	'shipping',	1),
(2,	'Uloženka',	'ulozenka',	'Osobní odběr v síti Uloženka.cz',	'shipping',	2),
(3,	'Cash On Delivery',	'cod',	'Cash on delivery module',	'payment',	2),
(4,	'Comments',	'comment',	'Enable comments for products.',	'product',	1),
(5,	'Documents',	'document',	'Add documents to your products.',	'product',	1),
(6,	'Heureka',	'heureka',	'Heureka feed',	'order',	1),
(7,	'Gapi',	'gapi',	'jsuajisjaoi\r\n',	'gapi',	1);

-- 2013-08-18 00:06:40
