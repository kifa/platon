-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vygenerováno: Úte 10. zář 2013, 00:23
-- Verze MySQL: 5.5.27
-- Verze PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `shop`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `AddressID` int(11) NOT NULL AUTO_INCREMENT,
  `UsersID` varchar(60) DEFAULT NULL,
  `Street` varchar(100) DEFAULT NULL,
  `ZIPCode` int(11) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `State` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`AddressID`),
  KEY `UsersID` (`UsersID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `attrib`
--

CREATE TABLE IF NOT EXISTS `attrib` (
  `AttribID` int(11) NOT NULL AUTO_INCREMENT,
  `AttribName` varchar(255) NOT NULL,
  PRIMARY KEY (`AttribID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `attrib`
--

INSERT INTO `attrib` (`AttribID`, `AttribName`) VALUES
(1, 'Roast'),
(2, 'Body'),
(3, 'Acidity'),
(4, 'Taste');

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

-- --------------------------------------------------------

--
-- Struktura tabulky `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(45) DEFAULT NULL,
  `CategoryDescription` longtext,
  `CategoryStatus` int(11) NOT NULL DEFAULT '3',
  `HigherCategoryID` int(11) DEFAULT NULL,
  `CategoryPhoto` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`CategoryID`),
  KEY `HigherCategoryID_idx` (`HigherCategoryID`),
  KEY `CategoryStatus` (`CategoryStatus`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1010 ;

--
-- Vypisuji data pro tabulku `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`, `CategoryDescription`, `CategoryStatus`, `HigherCategoryID`, `CategoryPhoto`) VALUES
(99, 'Blog', 'Blog full of wonderfull stories!', 4, NULL, NULL),
(100, 'Static Text', NULL, 4, NULL, NULL),
(1001, 'Coffee by Roast', '<p>Select any of our coffee ordered by roast.</p>', 1, NULL, NULL),
(1002, 'Coffee by region', NULL, 1, NULL, NULL),
(1003, 'Light', '<span style="font-family: sans-serif; font-size: 13px; line-height: 19.1875px; background-color: rgb(249, 249, 249);">A very light roast level, immediately before first crack. Light brown, toasted grain flavors with sharp acidic tones, almost tea-like in character.</span>', 1, 1001, NULL),
(1004, 'Medium', '<span style="font-family: sans-serif; font-size: 13px; line-height: 19.1875px; background-color: rgb(249, 249, 249);">Medium brown, common for most specialty coffee. Good for tasting the varietal character of a bean.</span>', 1, 1001, NULL),
(1005, 'Washmashine', '<p>:)</p>', 0, NULL, NULL),
(1006, 'Dark', '<span style="font-family: sans-serif; font-size: 13px; line-height: 19.1875px; background-color: rgb(249, 249, 249);">Dark brown, shiny with oil, burnt undertones, acidity diminished. At the end of second crack. Roast character is dominant at this level. Little, if any, of the inherent flavors of the coffee remain.&nbsp;</span><sup id="cite_ref-6" class="reference" style="line-height: 1em; unicode-bidi: -webkit-isolate; font-family: sans-serif; background-color: rgb(249, 249, 249);"><a href="http://en.wikipedia.org/wiki/Coffee_roasting#cite_note-6" style="text-decoration: none; color: rgb(11, 0, 128); background-image: none; white-space: nowrap; background-position: initial initial; background-repeat: initial initial;">[6]</a></sup>', 1, 1001, NULL),
(1007, 'Africa', NULL, 1, 1002, NULL),
(1008, 'Indonesia', NULL, 1, 1002, NULL),
(1009, 'Central America', NULL, 1, 1002, NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `categorystatus`
--

CREATE TABLE IF NOT EXISTS `categorystatus` (
  `CategoryStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryStatusName` varchar(190) NOT NULL,
  PRIMARY KEY (`CategoryStatusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `categorystatus`
--

INSERT INTO `categorystatus` (`CategoryStatusID`, `CategoryStatusName`) VALUES
(0, 'Draft'),
(1, 'Published'),
(2, 'Featured'),
(4, 'Blog');

-- --------------------------------------------------------

--
-- Struktura tabulky `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `CommentID` int(11) NOT NULL AUTO_INCREMENT,
  `CommentTitle` varchar(45) DEFAULT NULL,
  `CommentContent` text,
  `DateOfAdded` datetime DEFAULT NULL,
  `Author` varchar(60) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `PreviousCommentID` int(11) DEFAULT '0',
  PRIMARY KEY (`CommentID`),
  KEY `CommentID_idx` (`PreviousCommentID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `currency`
--

CREATE TABLE IF NOT EXISTS `currency` (
  `CurrencyID` int(11) NOT NULL AUTO_INCREMENT,
  `CurrencyCode` varchar(3) DEFAULT NULL,
  `CurrencyName` varchar(45) DEFAULT NULL,
  `CurrencyRate` float DEFAULT NULL,
  PRIMARY KEY (`CurrencyID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Vypisuji data pro tabulku `currency`
--

INSERT INTO `currency` (`CurrencyID`, `CurrencyCode`, `CurrencyName`, `CurrencyRate`) VALUES
(1, 'CZK', 'Koruna ?esk?', NULL),
(2, 'EUR', 'Euro', NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `delivery`
--

CREATE TABLE IF NOT EXISTS `delivery` (
  `DeliveryID` int(11) NOT NULL AUTO_INCREMENT,
  `DeliveryName` varchar(45) DEFAULT NULL,
  `DeliveryDescription` varchar(45) DEFAULT NULL,
  `DeliveryPrice` float DEFAULT NULL,
  `FreeFromPrice` float DEFAULT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2',
  `HigherDelivery` int(11) DEFAULT NULL,
  PRIMARY KEY (`DeliveryID`),
  KEY `PriceID_idx` (`DeliveryPrice`),
  KEY `StatusID` (`StatusID`),
  KEY `HigherDelivery` (`HigherDelivery`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=451 ;

--
-- Vypisuji data pro tabulku `delivery`
--

INSERT INTO `delivery` (`DeliveryID`, `DeliveryName`, `DeliveryDescription`, `DeliveryPrice`, `FreeFromPrice`, `StatusID`, `HigherDelivery`) VALUES
(1, 'Personal pick up', 'Personal in the shop', 0, NULL, 1, NULL),
(2, 'Czech postal service', 'Send by transport company', 99, 1000, 1, NULL),
(3, 'DPD', 'Curier express shipping! :)', 160, 10000, 1, NULL),
(353, 'Zásilkovna', 'osobní převzetí v síti Zásilkovna.cz', 49, NULL, 1, 353),
(354, 'cz - Beroun', 'Oční optika M. Ečerová', 49, NULL, 1, 353),
(355, 'cz - Brno, Jiráskova', 'Marty''s clean garage', 49, NULL, 1, 353),
(356, 'cz - Brno, Komín - Závist', 'ShopGSM.cz', 49, NULL, 1, 353),
(357, 'cz - Brno, Královo Pole', 'MobilMax', 49, NULL, 1, 353),
(358, 'cz - Brno, Mendlovo náměstí', 'MobilMax', 49, NULL, 1, 353),
(359, 'cz - Brno, Merhautova', 'Gabriela', 49, NULL, 1, 353),
(360, 'cz - Brno, Nové Sady', 'Motocyklové díly.cz', 49, NULL, 1, 353),
(361, 'cz - České Budějovice, Otakarova', 'Tvořilka', 49, NULL, 1, 353),
(362, 'cz - Frýdek-Místek', 'Dvěděti.cz - dřevěné hračky', 49, NULL, 1, 353),
(363, 'cz - Havířov, Ostravská', 'Shell', 49, NULL, 1, 353),
(364, 'cz - Holešov', 'MAVEX, spol. s r.o.', 49, NULL, 1, 353),
(365, 'cz - Hradec Králové', 'Kancelářský servis HK', 49, NULL, 1, 353),
(366, 'cz - Chrudim', 'Bytový textil', 49, NULL, 1, 353),
(367, 'cz - Jihlava', 'Svět hor', 49, NULL, 1, 353),
(368, 'cz - Karlovy Vary', 'Logistic centre - NEW WAVE CZ, a.s.', 49, NULL, 1, 353),
(369, 'cz - Kladno', 'OptikDoDomu', 49, NULL, 1, 353),
(370, 'cz - Kolín', 'Vzorkovna na Zahradní', 49, NULL, 1, 353),
(371, 'cz - Kroměříž', 'Hlaváč CZ', 49, NULL, 1, 353),
(372, 'cz - Kutná Hora', 'iBoom s.r.o.', 49, NULL, 1, 353),
(373, 'cz - Liberec, 5. května', 'nStore.cz s.r.o.', 49, NULL, 1, 353),
(374, 'cz - Mladá Boleslav', 'Relax solární studio', 49, NULL, 1, 353),
(375, 'cz - Most, J. Skupy', 'MobilMax - DobírkaMobil', 49, NULL, 1, 353),
(376, 'cz - Náchod', 'OptikDoDomu', 49, NULL, 1, 353),
(377, 'cz - Olomouc, centrum', 'Optika Mgr. Eva Bazgerová', 49, NULL, 1, 353),
(378, 'cz - Olomouc, Nová Ulice', 'JV-OL', 49, NULL, 1, 353),
(379, 'cz - Ostrava, Mariánské Hory', 'Outlet Rocca Intimo', 49, NULL, 1, 353),
(380, 'cz - Ostrava, Nádražní', 'Le Boutique Gourmet', 49, NULL, 1, 353),
(381, 'cz - Ostrava, Poruba', 'Lahůdky z Itálie', 49, NULL, 1, 353),
(382, 'cz - Pardubice', 'Aneco v.o.s.', 49, NULL, 1, 353),
(383, 'cz - Písek', 'AKY chovatelské potřeby a krmiva', 49, NULL, 1, 353),
(384, 'cz - Plzeň, u centra Plaza', 'Tvořilka', 49, NULL, 1, 353),
(385, 'cz - Praha 1, Klimentská', 'SKETA Shop', 49, NULL, 1, 353),
(386, 'cz - Praha 1, Národní třída', 'Stáčírna', 49, NULL, 1, 353),
(387, 'cz - Praha 10, Starostrašnická', 'Dia-Bio-Racio-Bezlepek', 49, NULL, 1, 353),
(388, 'cz - Praha 14, Černý Most', 'Farní charita', 49, NULL, 1, 353),
(389, 'cz - Praha 2, Hlavní nádraží', 'Bohemia Wine', 49, NULL, 1, 353),
(390, 'cz - Praha 3, Vinohrady', 'Stáčírna s.r.o.', 49, NULL, 1, 353),
(391, 'cz - Praha 3, Želivského', 'Barvy&laky', 49, NULL, 1, 353),
(392, 'cz - Praha 4, Braník', 'GAW-Net ', 49, NULL, 1, 353),
(393, 'cz - Praha 4, Pražského Povstání', 'Pilulky24.cz', 49, NULL, 1, 353),
(394, 'cz - Praha 4, U Vyšehradu', 'Autodoplňky Ascartuning', 49, NULL, 1, 353),
(395, 'cz - Praha 5, Smíchovské nádraží', 'Zlatá Tečka', 49, NULL, 1, 353),
(396, 'cz - Praha 6, Evropská', 'Shell', 49, NULL, 1, 353),
(397, 'cz - Praha 6, Hradčanská', 'VIDEOTECH.CZ', 49, NULL, 1, 353),
(398, 'cz - Praha 6, Řepy', 'Fotomobil', 49, NULL, 1, 353),
(399, 'cz - Praha 7, Jateční', 'Direct, spol. s r.o.', 49, NULL, 1, 353),
(400, 'cz - Praha 8,  Florenc', 'Perfect Print s.r.o.', 49, NULL, 1, 353),
(401, 'cz - Praha 8, Ládví', 'HESTIA s.r.o.', 49, NULL, 1, 353),
(402, 'cz - Praha 8, Sokolovská', 'TisknuLevne.cz', 49, NULL, 1, 353),
(403, 'cz - Praha 9, Hloubětín', 'Sun Way', 49, NULL, 1, 353),
(404, 'cz - Praha 9, Ocelářská (Depo)', 'Zásilkovna s.r.o.', 49, NULL, 1, 353),
(405, 'cz - Rakovník', 'OptikDoDomu', 49, NULL, 1, 353),
(406, 'cz - Rudná', 'Oční optika M. Ečerová', 49, NULL, 1, 353),
(407, 'cz - Teplice, Masarykova', 'Shell', 49, NULL, 1, 353),
(408, 'cz - Trutnov', 'OptikDoDomu', 49, NULL, 1, 353),
(409, 'cz - Třebíč', 'Mgr. Jitka Švaříčková - DEDRA Třebíč', 49, NULL, 1, 353),
(410, 'cz - Třinec', 'Pochutnej si!', 49, NULL, 1, 353),
(411, 'cz - Uherské Hradiště', 'Penzion Na Stavidle', 49, NULL, 1, 353),
(412, 'cz - Uherský Brod', 'CA Pencil Travel', 49, NULL, 1, 353),
(413, 'cz - Ústí nad Labem, Revoluční', 'Pekárna Chabařovice', 49, NULL, 1, 353),
(414, 'cz - Vyškov', 'Dárkové zboží - Pěkný-Domov.cz', 49, NULL, 1, 353),
(415, 'cz - Zlín, T. G. Masaryka', 'Residence Park-in', 49, NULL, 1, 353),
(416, 'cz - Znojmo', 'Supraton', 49, NULL, 1, 353),
(417, 'cz - Žatec', 'DĚTSKÝ SVĚT', 49, NULL, 1, 353),
(418, 'de - Zittau (DE)', 'Triloxx GmbH', 49, NULL, 1, 353),
(419, 'sk - Banská Bystrica, Švermova', 'CATUS Slovakia', 49, NULL, 1, 353),
(420, 'sk - Banská Bystrica, Tajovského', 'Lešenia', 49, NULL, 1, 353),
(421, 'sk - Bratislava, Cyprichová', 'Kníhkupectvo, antikvariát', 49, NULL, 1, 353),
(422, 'sk - Bratislava, Petržalka', 'Zásilkovna', 49, NULL, 1, 353),
(423, 'sk - Bratislava, Podunajské Biskupice', 'Naše Elektro - Nel.sk', 49, NULL, 1, 353),
(424, 'sk - Bratislava, Vajnorská', 'AUTOTEL', 49, NULL, 1, 353),
(425, 'sk - Dunajská Streda', 'Rempack', 49, NULL, 1, 353),
(426, 'sk - Galanta', 'Halens', 49, NULL, 1, 353),
(427, 'sk - Komárno', 'Záložňa VictoryTrade', 49, NULL, 1, 353),
(428, 'sk - Košice, Toryská', 'Kvetinárstvo ', 49, NULL, 1, 353),
(429, 'sk - Liptovský Mikuláš', 'Zbrane a strelivo', 49, NULL, 1, 353),
(430, 'sk - Martin', 'Jadran', 49, NULL, 1, 353),
(431, 'sk - Nitra, Mlynská', 'Nitra, Mlynská', 49, NULL, 1, 353),
(432, 'sk - Prešov', 'PC herňa & Internetová čitáreň', 49, NULL, 1, 353),
(433, 'sk - Ružomberok', 'CK Darka Tour', 49, NULL, 1, 353),
(434, 'sk - Spišská Nová Ves', 'Diskontná predajna hračiek', 49, NULL, 1, 353),
(435, 'sk - Trenčín', 'FourBrain s.r.o.', 49, NULL, 1, 353),
(436, 'sk - Trnava', 'Obchodík "U chorej vrany"', 49, NULL, 1, 353),
(437, 'sk - Žilina, Rosinská', 'RUNO spol. s r.o.', 49, NULL, 1, 353),
(438, 'Uloženka', 'osobní převzetí v síti Uloženka.cz', 39, NULL, 3, 438),
(439, 'Pobočka Praha 4', '5. května 1109/63', 49, NULL, 3, 438),
(440, 'Pobočka Brno', 'Václavská 237/6, areál ICB vchod č. 9', 49, NULL, 3, 438),
(441, 'Pobočka Ostrava', '28.října 1422/299', 49, NULL, 3, 438),
(442, 'Pobočka Hradec Králové', 'Pražská třída 293', 49, NULL, 3, 438),
(443, 'Pobočka Praha 9', 'Kolbenova 931/40b', 49, NULL, 3, 438),
(444, 'Pobočka Brno 2', 'Černopolní 54/245', 49, NULL, 3, 438),
(445, 'Pobočka Olomouc', 'Wellnerova 1322/3c', 49, NULL, 3, 438),
(446, 'Pobočka Plzeň', 'Tovární 280/7', 49, NULL, 3, 438),
(447, 'Pobočka České Budějovice', 'Novohradská 736/36', 49, NULL, 3, 438),
(448, 'Pobočka Ústí nad Labem', 'Dlouhá 1/12', 49, NULL, 3, 438),
(449, 'Pobočka SK, Bratislava', 'Chorvátska 1', 49, NULL, 3, 438),
(450, 'Nejlevnější delivery', 'Nejlevnější delivery', 19, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `documentation`
--

CREATE TABLE IF NOT EXISTS `documentation` (
  `DocumentID` int(11) NOT NULL AUTO_INCREMENT,
  `DocumentName` varchar(45) DEFAULT NULL,
  `DocumentDescription` varchar(500) DEFAULT NULL,
  `DocumentURL` varchar(100) DEFAULT NULL,
  `ProductID` int(11) NOT NULL,
  PRIMARY KEY (`DocumentID`),
  KEY `ProductID_idx` (`ProductID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `mailcontent`
--

CREATE TABLE IF NOT EXISTS `mailcontent` (
  `MailID` int(11) NOT NULL AUTO_INCREMENT,
  `MailSubject` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `MailContent` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`MailID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `ModuleID` int(11) NOT NULL AUTO_INCREMENT,
  `ModuleName` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `CompModuleName` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `ModuleDescription` text COLLATE utf8_czech_ci,
  `ModuleType` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`ModuleID`),
  KEY `StatusID` (`StatusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=10 ;

--
-- Vypisuji data pro tabulku `module`
--

INSERT INTO `module` (`ModuleID`, `ModuleName`, `CompModuleName`, `ModuleDescription`, `ModuleType`, `StatusID`) VALUES
(1, 'Zásilkovna', 'zasilkovna', 'Osobní převzetí v síti Zásilkovna.cz', 'shipping', 1),
(2, 'Uloženka', 'ulozenka', 'Osobní odběr v síti Uloženka.cz', 'shipping', 2),
(3, 'Cash On Delivery', 'cod', 'Cash on delivery module', 'payment', 2),
(4, 'Comments', 'comment', 'Enable comments for products.', 'product', 1),
(5, 'Documents', 'document', 'Add documents to your products.', 'product', 1),
(6, 'Heureka', 'heureka', 'Heureka feed', 'order', 1),
(7, 'Gapi', 'gapi', 'jsuajisjaoi\r\n', 'gapi', 1),
(8, 'Bankwire', 'bankwire', 'Bankwire payment', 'payment', 1),
(9, 'XML feed generator', 'xmlfeed', 'Generating XML for product search engines like Google Products, etc.', 'product', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `NotesID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL,
  `NotesDate` datetime NOT NULL,
  `NotesName` varchar(150) NOT NULL,
  `NotesDescription` text NOT NULL,
  PRIMARY KEY (`NotesID`),
  KEY `OrderID` (`OrderID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `orderdetails`
--

CREATE TABLE IF NOT EXISTS `orderdetails` (
  `OrderDetailsID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `UnitPrice` float DEFAULT NULL,
  PRIMARY KEY (`OrderDetailsID`),
  KEY `OrderID_idx` (`OrderID`),
  KEY `ProductID_idx` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusID` int(11) DEFAULT '1',
  `UsersID` varchar(60) DEFAULT NULL,
  `ProductsPrice` float DEFAULT NULL,
  `DeliveryPaymentPrice` float DEFAULT NULL,
  `TaxPrice` float DEFAULT NULL,
  `PriceWithoutTax` float DEFAULT NULL,
  `TotalPrice` float DEFAULT NULL,
  `DateCreated` datetime DEFAULT NULL,
  `DateOfLastChange` datetime DEFAULT NULL,
  `DateFinished` datetime DEFAULT NULL,
  `DeliveryID` int(11) DEFAULT NULL,
  `PaymentID` int(11) DEFAULT NULL,
  `Note` longtext,
  `IP` varchar(15) DEFAULT NULL,
  `SessionID` int(11) DEFAULT NULL,
  `Read` bit(1) DEFAULT b'0',
  PRIMARY KEY (`OrderID`),
  KEY `StatusID_idx` (`StatusID`),
  KEY `DeliveryID_idx` (`DeliveryID`),
  KEY `PaymentMethodID_idx` (`PaymentID`),
  KEY `UserID` (`UsersID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `orderstatus`
--

CREATE TABLE IF NOT EXISTS `orderstatus` (
  `OrderStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusName` varchar(45) DEFAULT NULL,
  `StatusDescription` varchar(255) DEFAULT NULL,
  `StatusProgress` int(11) DEFAULT '0',
  PRIMARY KEY (`OrderStatusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Vypisuji data pro tabulku `orderstatus`
--

INSERT INTO `orderstatus` (`OrderStatusID`, `StatusName`, `StatusDescription`, `StatusProgress`) VALUES
(0, 'CANCELED', 'order has been canceled', 0),
(1, 'Pending', 'Pending order', 1),
(2, 'Sending', 'sending order', 5),
(3, 'Done', 'done', 10);

-- --------------------------------------------------------

--
-- Struktura tabulky `parameters`
--

CREATE TABLE IF NOT EXISTS `parameters` (
  `ParameterID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `AttribID` int(11) DEFAULT NULL,
  `Val` varchar(255) DEFAULT NULL,
  `UnitID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ParameterID`),
  KEY `ProductID` (`ProductID`),
  KEY `AttribID` (`AttribID`),
  KEY `UnitID` (`UnitID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Vypisuji data pro tabulku `parameters`
--

INSERT INTO `parameters` (`ParameterID`, `ProductID`, `AttribID`, `Val`, `UnitID`) VALUES
(1, 3, 1, 'Light', 1),
(2, 3, 2, 'Syrupy', 1),
(3, 3, 3, 'Bittersweet', 1),
(4, 3, 4, 'Bergamot, floral, jasmine, juicy, intense.', 1),
(5, 13, 1, 'Light', 1),
(6, 13, 2, 'Medium', 1),
(7, 13, 3, 'Clean', 1),
(8, 13, 4, 'Berries, stone fruit, deep floral', 1),
(9, 4, 1, 'Light', 1),
(10, 4, 2, 'medium light', 1),
(11, 4, 3, 'Sweet toned and balanced', 1),
(12, 4, 4, 'Flavors of stone fruit, vanilla and brown sugar', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `PaymentName` varchar(45) DEFAULT NULL,
  `PaymentPrice` float DEFAULT '1',
  `StatusID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`PaymentID`),
  KEY `PriceID` (`PaymentPrice`),
  KEY `StatusID` (`StatusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Vypisuji data pro tabulku `payment`
--

INSERT INTO `payment` (`PaymentID`, `PaymentName`, `PaymentPrice`, `StatusID`) VALUES
(1, 'Cash', 0, 1),
(3, 'Bankwire', 50, 1),
(4, 'Cash on delivery', 0, 3),
(5, 'Cash on delivery', 0, 3),
(6, 'Cash on delivery', 0, 3);

-- --------------------------------------------------------

--
-- Struktura tabulky `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `PhotoID` int(11) NOT NULL AUTO_INCREMENT,
  `PhotoName` varchar(45) DEFAULT NULL,
  `PhotoURL` varchar(100) DEFAULT NULL,
  `PhotoAlbumID` int(11) DEFAULT NULL,
  `PhotoAltText` varchar(100) DEFAULT NULL,
  `CoverPhoto` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`PhotoID`),
  KEY `PhotoAlbumID_idx` (`PhotoAlbumID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Vypisuji data pro tabulku `photo`
--

INSERT INTO `photo` (`PhotoID`, `PhotoName`, `PhotoURL`, `PhotoAlbumID`, `PhotoAltText`, `CoverPhoto`) VALUES
(56, 'Ethiopia Yirgacheffe Kochere Gr 1', 'Temple-Coffee-Ethiopia-Yirgacheffe-Kochere-Gr-1.3022.lg.jpg', 3, 'Ethiopia Yirgacheffe Kochere Gr 1', 1),
(57, 'Ardi Ethiopia', 'MadCap-Coffee-Ardi-Ethiopia.2038.lg.jpg', 11, 'Ardi Ethiopia', 1),
(58, 'Nokia 3310 ', 'Equator-Coffees-Costa-Rica-La-Perla-Del-Cafe-Red-Honey.2946.lg.jpg', 4, 'Nokia 3310 ', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `photoalbum`
--

CREATE TABLE IF NOT EXISTS `photoalbum` (
  `PhotoAlbumID` int(11) NOT NULL AUTO_INCREMENT,
  `PhotoAlbumName` varchar(45) DEFAULT NULL,
  `PhotoAlbumDescription` varchar(500) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `BlogID` int(11) DEFAULT NULL,
  `StaticTextID` int(11) DEFAULT NULL,
  PRIMARY KEY (`PhotoAlbumID`),
  KEY `ProductID` (`ProductID`),
  KEY `BlogID` (`BlogID`),
  KEY `StaticTextID` (`StaticTextID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Vypisuji data pro tabulku `photoalbum`
--

INSERT INTO `photoalbum` (`PhotoAlbumID`, `PhotoAlbumName`, `PhotoAlbumDescription`, `ProductID`, `BlogID`, `StaticTextID`) VALUES
(3, 'Trojka', 'trojka', 3, NULL, NULL),
(4, 'ctyrka', 'ctyrka', 4, NULL, NULL),
(11, 'Sony Ericsson Xperia Mini', '<p style="margin: 0px 0px 0.5em; padding: 0px; line-height: 1.5em; word-wrap: break-word; color: #333333; font-family: ''Arial CE'', Arial, ''Helvetica CE'', Helvetica, sans-serif; font-size: small;"><strong style="margin: 0px; padding: 0px;">Mal&yacute; stylov&yacute; smartphone Sony Ericsson Xperia Mini</strong>&nbsp;se může pochlubit pěkn&yacute;m designem, mal&yacute;mi rozměry i velmi dobrou funkčn&iacute; v&yacute;bavou. Telefonu s operačn&iacute;m syst&eacute;mem Google Android 2.3 Gingerbrea', 13, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `price`
--

CREATE TABLE IF NOT EXISTS `price` (
  `PriceID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `SellingPrice` float NOT NULL,
  `SALE` float DEFAULT '1',
  `FinalPrice` float NOT NULL,
  `CurrencyID` int(11) DEFAULT '1',
  PRIMARY KEY (`PriceID`),
  KEY `CurrencyID_idx` (`CurrencyID`),
  KEY `ProductID` (`ProductID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Vypisuji data pro tabulku `price`
--

INSERT INTO `price` (`PriceID`, `ProductID`, `SellingPrice`, `SALE`, `FinalPrice`, `CurrencyID`) VALUES
(3, 3, 15.99, 0, 15.99, 1),
(4, 4, 15000, 1500, 13500, 1),
(10, 13, 18, 0, 18, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `producer`
--

CREATE TABLE IF NOT EXISTS `producer` (
  `ProducerID` int(11) NOT NULL AUTO_INCREMENT,
  `ProducerName` varchar(255) NOT NULL,
  PRIMARY KEY (`ProducerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `producer`
--

INSERT INTO `producer` (`ProducerID`, `ProducerName`) VALUES
(1, 'BNT coffee Ethiopia');

-- --------------------------------------------------------

--
-- Struktura tabulky `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(255) DEFAULT NULL,
  `ProductVariants` int(11) DEFAULT NULL,
  `ProductVariantName` text,
  `ProducerID` int(11) DEFAULT NULL,
  `ProductNumber` varchar(255) DEFAULT NULL,
  `ProductShort` varchar(500) DEFAULT NULL,
  `ProductDescription` longtext,
  `ProductStatusID` int(11) NOT NULL DEFAULT '1',
  `ProductEAN` varchar(45) DEFAULT NULL,
  `ProductQR` varchar(45) DEFAULT NULL,
  `ProductWarranty` varchar(45) DEFAULT NULL,
  `PiecesAvailable` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `DateOfAvailable` date NOT NULL DEFAULT '0000-00-00',
  `ProductDateOfAdded` datetime NOT NULL,
  `Video` text,
  PRIMARY KEY (`ProductID`),
  KEY `CategoryID_idx` (`CategoryID`),
  KEY `ProductStatusID` (`ProductStatusID`),
  KEY `ProducerID` (`ProducerID`),
  KEY `ProductVariants` (`ProductVariants`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Vypisuji data pro tabulku `product`
--

INSERT INTO `product` (`ProductID`, `ProductName`, `ProductVariants`, `ProductVariantName`, `ProducerID`, `ProductNumber`, `ProductShort`, `ProductDescription`, `ProductStatusID`, `ProductEAN`, `ProductQR`, `ProductWarranty`, `PiecesAvailable`, `CategoryID`, `DateOfAvailable`, `ProductDateOfAdded`, `Video`) VALUES
(3, 'Ethiopia Yirgacheffe Kochere Gr 1', NULL, NULL, 1, '', 'This lot of Yirgacheffe, or more specifically, Kochere is an exceptional example of what washed coffees from the region should taste like. This coffee is a non-auction lot coffee selected for us by our friends at BNT Coffee in Ethiopia. This is the first coffee of two which we will be offering this year from BNT.', '<div style="text-align: left;"><span style="color: rgb(62, 30, 18); font-family: georgia, arial, verdana, sans-serif; font-size: 16px; background-color: rgb(255, 255, 255);">We hope to have more direct, producer specific coffees with BNT in&nbsp;</span><span id="dots" style="color: rgb(62, 30, 18); font-family: georgia, arial, verdana, sans-serif; font-size: 16px; background-color: rgb(255, 255, 255);">the future, however this coffee is an outstanding example of what is to come. Enjoy! From CoffeeReview.Com Blind Assessment: Rich, zesty, bright. Night-blooming flowers, honey, lime, cinnamon in aroma and cup. Sweetly tart, bittersweet acidity; lively, lightly syrupy mouthfeel. Rather drying in the finish but profoundly flavor-saturated. Notes: Yirgacheffe is a coffee region in southern Ethiopia that produces distinctive coffees from traditional varieties of Arabica long grown in the region. Yirgacheffe coffees like this one processed by the wet or washed method (fruit skin and pulp are removed before drying) typically express great aromatic complexity and intensity with a particular emphasis on citrus and floral notes. Like virtually all Ethiopia coffees, this coffee is produced by villagers on small, garden plots interplanted with food and other subsistence crops. Temple is a quality-focused retail and wholesale specialty roaster active in Sacramento, California since 2005. Committed to sourcing, roasting and brewing the finest coffees, Temple features coffee from distinguished single estates and cooperatives around the world. Who Should Drink It: Those who enjoy the sweet, acidy brightness of a high-grown, light-roasted cup with an intense, original aromatic bonus.</span><br></div>', 3, '', '', '', 98, 1003, '0000-00-00', '0000-00-00 00:00:00', NULL),
(4, 'Costa Rica La Perla Del Cafe Red Honey', NULL, NULL, 1, '', 'This spring we presented a special lot of SL28 variety coffee from Carlos Barrantes’ farm La Perla del Café. This is the second year in a row we are offering coffee from his farm and we are excited to expand our partnership with such a quality-minded coffee producer. ', '<p><span style="color: rgb(62, 30, 18); font-family: georgia, arial, verdana, sans-serif; font-size: 16px; background-color: rgb(255, 255, 255);">In addition to the tiny plot of coveted SL28 variety coffee, there are larger plantings of Villa Sarchi, a variety</span><span id="dots" style="color: rgb(62, 30, 18); font-family: georgia, arial, verdana, sans-serif; font-size: 16px; background-color: rgb(255, 255, 255);">of coffee which naturally mutated from the Bourbon variety in Costa Rica over a half century ago. We secured two small lots of Villa Sarchi variety coffee from La Perla, each processed by different method. This lot was processed by the so-called Red Honey method, a variation of the honey or pulped natural process where a specific percentage of fruit mucilage is allowed to dry on the bean. To be classified as Red Honey (as opposed to yellow honey) the majority of fruit mucilage is left clinging to the bean after pulping. Because of the high percentage of mucilage, the beans take on a reddish cast during the drying stage. The flavor of this coffee is distinctly sweet and fruity, contrasting well with the Fully Washed version of Villa Sarchi we are offering from the same farm. We are happy to announce that Equator is the sloe roaster of this coffee! Although Carlos has been growing coffee for quite sometime, he only recently established his own micro mill, which he named La Perla del Café. Previously he used his family mill, La Planada. We asked if he would process a small lot of Red Honey coffee exclusively for Equator. This is the first lot he has processed using this method and we are very pleased with the results.</span><br></p>', 3, '', '', '', 54, 1001, '0000-00-00', '0000-00-00 00:00:00', NULL),
(13, 'Ardi Ethiopia', NULL, NULL, 1, '11111', 'In Sidamo, Ethiopia it is common to have dry-processed coffee that presents itself with a burst of flavor. But rarely is a particular coffee so sweet, so balanced and yet so clean. The Ardi is dry processed coffee (often referred to as natural processed), meaning the beans are dried with the coffee fruit still intact, allowing the natural flavors of the coffee cherry to soak into the bean.', '<p><span style="color: rgb(62, 30, 18); font-family: georgia, arial, verdana, sans-serif; font-size: 16px; background-color: rgb(255, 255, 255);">The mill is managed carefully assuring the beans never touch the ground and are turned regularly to achieve even drying. This past year, Ryan had the opportunity to visit the cooperative and see the process first hand. He found the process to be quite meticulous; Ripe cherries are picked, and then dried on raised beds for the next 3 weeks. During the entire 3 weeks of drying, workers sort through the beds every single day, assuring that underipe coffee cherries don''t make their way into the final product. During this time the coffee is raked constantly for even drying and also covered by tarps in the evening and when threatened by rain to assure the drying cycle is not disturbed. Since 2010 we have purchased the Ardi and it continues to be a huge hit. This year we are proud to be offering an exclusive small selection that was specially prepared just for us at Madcap from the Kercha Cooperative. Perfecting the dry processed coffee is certainly the focus at Kercha and that focus is apparent in the cup. We have found the Ardi from Kercha to offer a burst of flavor and aromatics containing strawberry, honey and milk chocolate.</span><br></p>', 2, '123456', '122', 'rok', 11, 1003, '0000-00-00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `productstatus`
--

CREATE TABLE IF NOT EXISTS `productstatus` (
  `ProductStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductStatusName` varchar(100) NOT NULL,
  `ProductStatusDescription` varchar(255) NOT NULL,
  PRIMARY KEY (`ProductStatusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Vypisuji data pro tabulku `productstatus`
--

INSERT INTO `productstatus` (`ProductStatusID`, `ProductStatusName`, `ProductStatusDescription`) VALUES
(1, 'Concept', 'This product is in stae: Concept'),
(2, 'Visible', 'This product is visible'),
(3, 'Featured', 'Featured product');

-- --------------------------------------------------------

--
-- Struktura tabulky `productvariants`
--

CREATE TABLE IF NOT EXISTS `productvariants` (
  `VariantID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) NOT NULL,
  `VariantName` text COLLATE utf8_czech_ci,
  `AttribID` int(11) NOT NULL,
  `Val` int(11) NOT NULL,
  `UnitID` int(11) NOT NULL,
  PRIMARY KEY (`VariantID`),
  KEY `ProductID` (`ProductID`),
  KEY `AttribID` (`AttribID`),
  KEY `Val` (`Val`),
  KEY `UnitID` (`UnitID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `SettingID` int(11) NOT NULL AUTO_INCREMENT,
  `SettingName` varchar(100) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`SettingID`),
  UNIQUE KEY `Name` (`SettingName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Vypisuji data pro tabulku `settings`
--

INSERT INTO `settings` (`SettingID`, `SettingName`, `Value`) VALUES
(1, 'ShopLayout', 'layout'),
(2, 'Description', 'Ecommerce 2.0 that works almost automatically.'),
(3, 'HomepageLayout', 'default'),
(4, 'Logo', 'logo (1).png'),
(5, 'Name', 'Birneshop DEMOtime'),
(6, 'ProductLayout', 'product1'),
(7, 'TAX', '20'),
(8, 'OrderMail', 'luk.danek@gmail.com'),
(9, 'ContactMail', 'jiri.kifa@gmail.com'),
(10, 'ContactPhone', '+420333444'),
(11, 'CompanyAddress', 'Zdice 18, U hřibitova 34567'),
(12, 'InvoicePrefix', 'i-2013'),
(13, 'ProductMiniLayout', 'ProductMini'),
(14, 'GA', 'UA-42741537-1'),
(15, 'ShopURL', 'http://localhost'),
(27, 'zasilkovnaAPI', '66fbbe30bb36aee4'),
(28, 'zasilkovnaID', '353'),
(29, 'Salt', 'Salt'),
(30, 'gapiAPI', '4/BdPpTGmoBcA6YXzCNLQrKDIHfTd6.Qi927urqOpsVOl05ti8ZT3axvvIhgQI'),
(31, 'gapiTOKEN', '{"access_token":"ya29.AHES6ZRUGTx_HsylhCX4s5f4W-d2Rn2HS3I2c8kvdTi2y1L1Kglrsobd","token_type":"Bearer","expires_in":3600,"refresh_token":"1\\/gWO9VgJWOC1YngESadNHDhAFsO2YioYyGFcc3mTY6Uw","created":1376821668}'),
(32, 'bankwireID', '7'),
(33, 'Account', ''),
(34, 'slider', 'unnamed.png');

-- --------------------------------------------------------

--
-- Struktura tabulky `statictext`
--

CREATE TABLE IF NOT EXISTS `statictext` (
  `StaticTextID` int(11) NOT NULL AUTO_INCREMENT,
  `StaticTextName` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `StaticTextContent` text COLLATE utf8_czech_ci NOT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`StaticTextID`),
  KEY `StatusID` (`StatusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `statictext`
--

INSERT INTO `statictext` (`StaticTextID`, `StaticTextName`, `StaticTextContent`, `StatusID`) VALUES
(1, 'Terms and condition', '<p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Bouřkové pásmo spojené se silnými nárazy větru a místy i přívalovými dešti a kroupami po půlnoci přešlo přes střední Čechy a Ústecký kraj do kraje Libereckého.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">"V okresu Hradec Králové se vyskytují velmi silné bouřky provázené krupobitím.<br>&nbsp;Bouřky postupují směrem k severovýchodu a v následujících hodinách postoupí<br>do okresů Trutnov a Náchod," varovali meteorologové v pátek ráno před půl sedmou.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Při bouřkách lidem hrozí&nbsp;zranění ulomenými větvemi a zásah bleskem.&nbsp;Těch bylo na obloze nejvíce hned na počátku, ve čtvrtek mezi 18. a&nbsp;19. hodinou. Tehdy byly bouřky na jihozápadě republiky a<a href="http://www.chmi.cz/" target="_blank" style="color: rgb(19, 55, 94);">Český hydrometeorologický ústav</a>zaznamenal intenzitu až 156 blesků za minutu.</p><div class="imagelist imagelist-sp5 not4bbtext" style="zoom: 1; margin: 0px 0px 0.5em; font-size: small; font-family: Arial, Helvetica, sans-serif; background-color: rgb(225, 225, 225);"><div class="cell cell-first" style="float: left; margin-left: 22px; width: 172px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c033e_mapa0023.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c033e_mapa0023.jpg" width="172" height="129" alt="Přehled srážek v noci na pátek 21. června" title="Přehled srážek v noci na pátek 21. června" style="border: 0px; vertical-align: middle;"></a></div><div class="cell" style="float: left; width: 172px; margin-left: 22px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0340_mapa0024.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c0340_mapa0024.jpg" width="172" height="129" alt="Přehled srážek v noci na pátek 21. června" title="Přehled srážek v noci na pátek 21. června" style="border: 0px; vertical-align: middle;"></a></div><div class="cell" style="float: left; width: 172px; margin-left: 22px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0336_mapa0001.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c0336_mapa0001.jpg" width="172" height="129" alt="Přehled srážek v noci na pátek 21. června" title="Přehled srážek v noci na pátek 21. června" style="border: 0px; vertical-align: middle;"></a></div><div class="fc0" style="overflow: hidden; clear: both; float: none; line-height: 0; margin: 0px; padding: 0px; font-size: 0px; height: 0px;"></div></div><div class="imagelist imagelist-sp5 not4bbtext" style="zoom: 1; margin: 0px 0px 0.5em; font-size: small; font-family: Arial, Helvetica, sans-serif; background-color: rgb(225, 225, 225);"><div class="cell cell-first" style="float: left; margin-left: 22px; width: 172px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0336_mapa0002.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c0336_mapa0002.jpg" width="172" height="129" alt="Přehled srážek v noci na pátek 21. června" title="Přehled srážek v noci na pátek 21. června" style="border: 0px; vertical-align: middle;"></a></div><div class="cell" style="float: left; width: 172px; margin-left: 22px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0337_mapa0003.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c0337_mapa0003.jpg" width="172" height="129" alt="Přehled srážek v noci na pátek 21. června" title="Přehled srážek v noci na pátek 21. června" style="border: 0px; vertical-align: middle;"></a></div><div class="cell" style="float: left; width: 172px; margin-left: 22px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0337_mapa0004.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c0337_mapa0004.jpg" width="172" height="129" alt="Přehled srážek v noci na pátek 21. června" title="Přehled srážek v noci na pátek 21. června" style="border: 0px; vertical-align: middle;"></a></div><div class="fc0" style="overflow: hidden; clear: both; float: none; line-height: 0; margin: 0px; padding: 0px; font-size: 0px; height: 0px;"></div><p style="margin: 0px; padding: 0.3em 0px 0px; clear: both; line-height: 1.33; font-size: 12px; color: rgb(102, 102, 102);">Na mapách z ČHMÚ je vidět postup bouřek v noci na 21. června</p><div class="fc0" style="overflow: hidden; clear: both; float: none; line-height: 0; margin: 0px; padding: 0px; font-size: 0px; height: 0px;"></div></div><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">"Druhá vlna přišla po půlnoci, trvala několik hodin a intenzita byla kolem 50 blesků za minutu. Kolem sedmé hodiny ráno, kdy byly bouřky&nbsp;na Liberecku, byla intenzita 60 blesků za minutu," řekl iDNES.cz mluvčí Českého hydrometeorologického ústavu Petr Dvořák.&nbsp;Za&nbsp;patnáct hodin, počínaje čtvrteční osmnáctou hodinou, zaznamenali meteorologové na území republiky 10 914 blesků.</p><h3 class="tit not4bbtext" id="--padaly-kroupy-i-stromy" style="margin: 0px; padding: 0px; font-size: 1em; font-weight: 400; font-family: Arial, Helvetica, sans-serif; line-height: 15px; background-color: rgb(225, 225, 225);">Padaly kroupy i stromy</h3><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Už v podvečer bouřky silně zasáhly<b>Karlovarský kraj</b>.&nbsp;"Ze všech okresů Karlovarského kraje máme hlášeny velmi silné bouřky. Evidujeme také nárazy větru a srážkové úhrny kolem 10&nbsp;mm, v ojedinělých případech dokonce 25 mm. Místy jsou srážky doprovázeny kroupami," uvedl pro iDNES.cz Josef Hanzlík z ČHMÚ.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Hasiči v Karlovarském kraji celkem vyjížděli k 75 událostem, odstraňovali padlé stromy, odčerpávali vodu i zachraňovali zvířata. Podle mluvčího krajských hasičů Martina Kasala největší problémy způsobily rozlité malé potoky nebo voda, která stekla z kopců.</p><table id="--konec-veder" class="complete complete-half-r not4bbtext" style="margin: 0px 0px 0.5em 10px; padding: 0px; font-size: 12px; position: relative; width: 192px; border: 0px; border-collapse: collapse; border-top-left-radius: 6px; border-top-right-radius: 6px; border-bottom-right-radius: 6px; border-bottom-left-radius: 6px; line-height: 15px; background-color: rgb(238, 238, 238); float: right; font-family: Arial, Helvetica, sans-serif;"><tbody><tr><td style="margin: 0px; padding: 10px 10px 3px;"><h3 style="margin: 0px 0px 0.1em; padding: 0px; font-size: 20px; color: rgb(185, 21, 28);">Konec veder</h3><p class="title" style="margin: 0px 0px 0.5em; padding: 0px; font-size: 16px; font-weight: 700; color: rgb(70, 70, 70);">Bouřky přišly po vedrech, které přepisovaly rekordy</p><div><p style="margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;"><b>PONDĚLÍ:&nbsp;</b><a href="http://zpravy.idnes.cz/padaji-rekordni-teploty-0wt-/domaci.aspx?c=A130617_163831_domaci_cen" style="color: rgb(19, 55, 94);">Začalo to třicítkami</a></p><p style="margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;"><b>ÚTERÝ:</b>&nbsp;<a href="http://zpravy.idnes.cz/pocasi-vysoke-teploty-teplotni-rekordy-fd2-/domaci.aspx?c=A130618_150258_domaci_mzi" style="color: rgb(19, 55, 94);">Klementinum má nový rekord</a></p><p style="margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;"><b>STŘEDA:&nbsp;</b><a href="http://zpravy.idnes.cz/teplotni-rekordy-klementinum-dkt-/domaci.aspx?c=A130619_165448_domaci_mzi" style="color: rgb(19, 55, 94);">Bylo 35,5 stupně</a></p><p style="margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;"><b>ČTVRTEK:&nbsp;</b><a href="http://zpravy.idnes.cz/teplotni-rekordy-ve-ctvrtek-dg2-/domaci.aspx?c=A130620_165158_domaci_ert" style="color: rgb(19, 55, 94);">Ve stínu bylo až 38 stupňů</a></p><p style="margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;"><b>PŘEDPOVĚĎ:&nbsp;</b><a href="http://zpravy.idnes.cz/predpoved-pocasi-pro-tyden-od-20-cervna-dxz-/domaci.aspx?c=A130620_074906_domaci_jpl" style="color: rgb(19, 55, 94);">Bude o 20 stupňů méně</a></p></div></td></tr></tbody></table><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">"V první polovině noci, asi od 18:00 do 22:00, jsme jezdili hlavně ke spadaným stromům, které zablokovaly silnice. Později zase převažovalo odčerpávání z domů a sklepů," uvedl Kasal.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Nejhorší situace byla na Kynšpersku. V Lítově voda vyplavila halu firmy i penzion, v Nebanicích přívaly vod nezvládala kanalizace a voda vyhazovala kanálové poklopy.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">"V Chotíkově a Liboci voda zaplavila návsi, bylo tam až metr vody," popsal Kasal. Hasiči tam museli na člunech zachraňovat domácí zvířata. "Lidé se stihli dostat pryč, ale pro zvířata jsme museli na člunech. Asi polovinu se podařilo zachránit," uvedl Kasal.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Ve Stebnici u Lipové na Chebsku potok hrozil odříznutím dětského tábora. Hasiči proto celý tábor s 21 dětmi evakuovali do základní školy v Lipové. Krajem se okolo 4:45 přehnala druhá vlna bouře. Ačkoli ji také doprovázely blesky a silný déšť, větší škody už nenapáchala.&nbsp;</p><table id="--fotogalerie" class="complete  not4bbtext" style="margin: 0px 0px 1em; padding: 0px; font-size: 12px; position: relative; width: 560px; border: 0px; border-collapse: collapse; border-top-left-radius: 6px; border-top-right-radius: 6px; border-bottom-right-radius: 6px; border-bottom-left-radius: 6px; line-height: 15px; background-color: rgb(238, 238, 238); font-family: Arial, Helvetica, sans-serif;"><tbody><tr><td style="margin: 0px; padding: 10px 10px 3px;"><h3 style="margin: 0px 0px 0.1em; padding: 0px; font-size: 20px; color: rgb(185, 21, 28);"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert" style="color: rgb(185, 21, 28); text-decoration: none;">Fotogalerie</a></h3><div><div class="imagelist imagelist-sp5 not4bbtext" style="zoom: 1; margin: 0px 0px 0.5em; font-size: small;"><div class="cell cell-first" style="float: left; margin-left: 12px; width: 172px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c02e8_20136DB3B3B4E18D88F4DCC94B5688BAFA2A.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c02e8_20136DB3B3B4E18D88F4DCC94B5688BAFA2A.jpg" width="172" height="129" alt="Blesky v Lysé nad Labem" title="Blesky v Lysé nad Labem" style="border: 0px; vertical-align: middle;"></a></div><div class="cell" style="float: left; width: 172px; margin-left: 12px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c02e6_201326507345ED6283E28E1578036CFF5B21.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c02e6_201326507345ED6283E28E1578036CFF5B21.jpg" width="172" height="129" alt="Blesky nad Olšanským náměstím v Praze" title="Blesky nad Olšanským náměstím v Praze" style="border: 0px; vertical-align: middle;"></a></div><div class="cell" style="float: left; width: 172px; margin-left: 12px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c033b_2013BC7B5356A24971D7B8DDAAA342CEC496.jpg" style="color: rgb(19, 55, 94);"><img src="http://oidnes.cz/13/063/sp5/SKR4c033b_2013BC7B5356A24971D7B8DDAAA342CEC496.jpg" width="172" height="129" alt="Bouřka v Jiřetíně nad Jedlovou" title="Bouřka v Jiřetíně nad Jedlovou" style="border: 0px; vertical-align: middle;"></a></div><div class="fc0" style="overflow: hidden; clear: both; float: none; line-height: 0; margin: 0px; padding: 0px; font-size: 0px; height: 0px;"></div></div><p style="margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;"><a href="http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert" style="color: rgb(19, 55, 94);">Zobrazit fotogalerii</a></p></div></td></tr></tbody></table><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Silná bouřka přešla také okolím<b>Rokycan v Plzeňském kraji</b>. Místní uvedli, že spolu s deštěm tam zaznamenali také kroupy o průměru až tři centimetry. Region pak hlásí také rozbité skleníky a poničené střechy několika automobilů. Přívaly vody zde naštěstí zatím nezpůsobily žádné bleskové povodně.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Bouřky&nbsp;směřovaly na severovýchod přes Chebsko, Sokolovsko a Karlovarsko. Velmi silné bouřky pak v noci zasáhly také Ústecký&nbsp;nebo Liberecký kraj.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Kolem půlnoci dorazila bouřka do Prahy a&nbsp;pak pokračovala přes Mělnicko, Mladoboleslavsko.<b>Středočeští</b>hasiči vyjížděli ke 40 výjezdům, blesk udeřil do domu na Kladensku a zapálil střechu.</p><h3 class="tit not4bbtext" id="--voda-a-kroupy-zastavily-dopravu" style="margin: 0px; padding: 0px; font-size: 1em; font-weight: 400; font-family: Arial, Helvetica, sans-serif; line-height: 15px; background-color: rgb(225, 225, 225);">Voda a kroupy zastavily dopravu</h3><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Podle webu Českých drah musel být před 20. hodinou zastaven provoz na železnici v úseku mezi Tršnicí a Kynšperkem nad Ohří, kde byla podemletá trať a poškozené trakční vedení. Vlaky byly nahrazeny autobusovou dopravou, opravy by měly trvat asi dva dny.</p><div class="catchbox-l catchbox-vn not4bbtext" style="position: relative; width: 192px; font-size: 12px; line-height: 15px; float: left; margin: 0px 15px 0.5em -70px; background-color: rgb(245, 245, 245); font-family: Arial, Helvetica, sans-serif;"><div class="bg1" style="background-image: url(http://gidnes.cz/u/n4/box-edge.png); background-position: initial initial; background-repeat: no-repeat no-repeat;"><div class="bg2" style="background-image: url(http://gidnes.cz/u/n4/box-edge.png); padding: 10px; background-position: -192px 100%; background-repeat: no-repeat no-repeat;"><h3 style="margin: 0px 0px 0.6em; padding: 0px; font-size: 20px; color: rgb(185, 21, 28);">Posílejte fotky a videa</h3><p style="margin: 0px 0px 1em; padding: 0px; font-size: 14px;">Nafotili jste řádění bouřky?</p><p style="margin: 0px 0px 1em; padding: 0px; font-size: 14px;"><b>Pošlete své záběry do rubriky&nbsp;<a href="http://zpravy.idnes.cz/ocima-ctenaru.asp" style="color: rgb(19, 55, 94);">Očima čtenářů</a>.</b></p><p class="f92" style="margin: 0px 0px 1em; padding: 0px; font-size: 11px; color: rgb(102, 102, 102);">Použité fotografie či videa odměníme honorářem. Podmínky použití fotografií a videozáznamů<a href="http://www.idnes.cz/pravidla" style="color: rgb(19, 55, 94);">najdete zde</a>.</p></div></div></div><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Večer spadly stromy na dvě regionální trati v Plzeňském kraji. Úseky z Kasejovic do Blatné a z Třemešné pod Přimdou do Bělé nad Radbuzou se podařilo po půlnoci zprovoznit.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Stromy padaly i na silnice, kolem půlnoci to bylo zejména na Lounsku, Chomutovsku, Kladensku a Českolipsku, vyplývá z informací policejního dopravního webu.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Na křižovatce u Horšovského Týna na Domažlicku silnici zaplavil proud vody o výšce asi 30 centimetrů, v Odravě na Chebsku musel být uzavřen zaplavený podjezd pod rychlostní silnicí R5. Na Sokolovsku voda zaplavila vozovku u obcí Dolní Nivy a Jindřichovice, u Kaceřova se na silnici sesunul svah.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Některé silnice jsou kvůli popadaným stromům neprůjezdné i ráno.&nbsp;Například na Lounsku silnice&nbsp;239&nbsp;mezi Slavětínem a Perucí nebo silnice 227 u obce Holedec.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Podle Radiožurnálu hasiči na Žatecku a Děčínsku vyjížděli k popadaným stromům, poškozeným střechám a uvolněné krytině. Kvůli silnému dešti musel být evakuován kemp v Lipové u Chebu. Místní potok se rozlil a hrozilo zatopení chatek.</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Vlna bouřek by měla udělat definitivní tečku za extrémními horky, které sužovaly Česko v uplynulých dnech. Na mnoha místech ve čtvrtek padaly i stoleté teplotní rekordy (<a href="http://zpravy.idnes.cz/teplotni-rekordy-ve-ctvrtek-dg2-/domaci.aspx?c=A130620_165158_domaci_ert" style="color: rgb(19, 55, 94);">více o nich čtěte zde</a>).</p><p style="margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);">Zdroj:<a href="http://zpravy.idnes.cz/bourky-v-cesku-0pl-/domaci.aspx?c=A130620_185733_domaci_ert" style="color: rgb(19, 55, 94);">http://zpravy.idnes.cz/bourky-v-cesku-0pl-/domaci.aspx?c=A130620_185733_domaci_ert</a></p>', 1),
(2, 'About us', '<p style="text-align: left; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut purus feugiat, tincidunt nibh nec, faucibus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed pulvinar risus quis diam tempor volutpat quis non lacus. Donec ullamcorper pellentesque lacinia. Integer lobortis tortor nec elit adipiscing suscipit. Nulla nec dolor ac lacus facilisis mollis at eu risus. Etiam ac congue mi. Nam nec malesuada lorem, a interdum risus. Aliquam at tellus ac nulla sodales&nbsp;<img src="http://localhost/platon/www/images/logo/birne_logo_web.png" alt="Birneshop DEMO" title="Birneshop DEMO"><span style="font-size: 11px; line-height: 14px;">ultricies eu c</span></p><hr><p style="text-align: left; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;"><span style="font-size: 11px; line-height: 14px;">onvallis n</span><span style="font-size: 11px; line-height: 14px;">isi. Quisque et magna bibendum, cursus purus suscipit, pellentesque libero. Proin ac lacinia augue, aliquet dictum metus. Nam non felis lectus. Donec a sapien eu nunc vestibulum varius vitae ac odio. Vestibulum fermentum sem ut dui blandit consequat.</span></p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Fusce placerat nulla tortor, id gravida lectus hendrerit ac. Sed mattis ultrices ornare. Nulla urna turpis, mattis vitae lectus nec, blandit consequat orci. Nullam mi neque, sodales vulputate venenatis rhoncus, rhoncus sit amet dui. Fusce et diam euismod, blandit metus eget, ornare purus. Nunc ut erat a justo consectetur accumsan vel et metus. Donec in porttitor nisl. Maecenas felis dolor, euismod nec gravida nec, hendrerit vel ligula. Ut dictum, diam ac sagittis commodo, quam metus volutpat ante, eget fringilla dolor massa a ligula. Aliquam sit amet euismod dui, vitae egestas elit. Donec scelerisque viverra condimentum. Aliquam felis ligula, tempus quis laoreet non, fringilla eget urna. Nulla at magna laoreet, viverra diam eget, mollis nisi.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Suspendisse eros felis, tincidunt ac congue at, scelerisque a lectus. Etiam sollicitudin magna ac sem faucibus mollis. Morbi viverra cursus elit nec luctus. Phasellus eget metus eu est tincidunt varius. Aliquam ullamcorper enim malesuada orci consectetur, vel ultricies massa facilisis. Quisque mi odio, blandit sed nisl non, dapibus ornare nibh. Integer ut erat sit amet urna&nbsp;<img src="http://localhost/platon/www/images/logo/birne_logo_web.png" alt="Birneshop DEMO" title="Birneshop DEMO"><span style="font-size: 11px; line-height: 14px;">accumsan porta ac non sapien. Vestibulum tellus eros, fermentum a rutrum non, semper a felis. Praesent venenatis lorem tincidunt, laoreet metus eget, dapibus ligula. Sed convallis sapien vel vestibulum semper. Cras rhoncus elementum sapien, vel porta dolor tristique at. Curabitur gravida id urna sit amet lobortis. Donec quis risus sapien.</span></p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">In mollis mauris ut massa fringilla, pretium tempor lorem imperdiet. Curabitur vel risus nulla. Aenean rutrum justo non laoreet vulputate. Etiam mattis laoreet lacus vitae venenatis. Cras varius pellentesque varius. Suspendisse potenti. Nunc eu erat sit amet lacus consequat faucibus quis in orci. Etiam cursus varius ipsum, vitae adipiscing risus egestas nec. Integer id convallis leo, iaculis bibendum tellus. Nam consectetur nibh gravida placerat bibendum.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Suspendisse pellentesque ut orci eget porttitor. Curabitur placerat odio et mauris consectetur elementum. Aliquam dictum eros id ultricies iaculis. Donec non orci facilisis, interdum nisi vel, imperdiet justo. Nam imperdiet diam eget quam vehicula, a mattis orci scelerisque. Morbi tincidunt molestie enim non malesuada. Aliquam scelerisque mi non mauris dictum viverra. In tincidunt, ante ac hendrerit scelerisque, odio quam suscipit nulla, sed tempor nisl purus at nisl. Ut blandit eu erat sed facilisis. Sed facilisis id ipsum ac placerat. Vivamus eu arcu tempus, vulputate massa vel, dignissim estLorem ipsum dolor sit amet, consectetur adipiscing elit. In ut purus feugiat, tincidunt nibh nec, faucibus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed pulvinar risus quis diam tempor volutpat quis non lacus. Donec ullamcorper pellentesque lacinia. Integer lobortis tortor nec elit adipiscing suscipit. Nulla nec dolor ac lacus facilisis mollis at eu risus. Etiam ac congue mi. Nam nec malesuada lorem, a interdum risus. Aliquam at tellus ac nulla sodales ultricies eu convallis nisi. Quisque et magna bibendum, cursus purus suscipit, pellentesque libero. Proin ac lacinia augue, aliquet dictum metus. Nam non felis lectus. Donec a sapien eu nunc vestibulum varius vitae ac odio. Vestibulum fermentum sem ut dui blandit consequat.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Fusce placerat nulla tortor, id gravida lectus hendrerit ac. Sed mattis ultrices ornare. Nulla urna turpis, mattis vitae lectus nec, blandit consequat orci. Nullam mi neque, sodales vulputate venenatis rhoncus, rhoncus sit amet dui. Fusce et diam euismod, blandit metus eget, ornare purus. Nunc ut erat a justo consectetur accumsan vel et metus. Donec in porttitor nisl. Maecenas felis dolor, euismod nec gravida nec, hendrerit vel ligula. Ut dictum, diam ac sagittis commodo, quam metus volutpat ante, eget fringilla dolor massa a ligula. Aliquam sit amet euismod dui, vitae egestas elit. Donec scelerisque viverra condimentum. Aliquam felis ligula, tempus quis laoreet non, fringilla eget urna. Nulla at magna laoreet, viverra diam eget, mollis nisi.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Suspendisse eros felis, tincidunt ac congue at, scelerisque a lectus. Etiam sollicitudin magna ac sem faucibus mollis. Morbi viverra cursus elit nec luctus. Phasellus eget metus eu est tincidunt varius. Aliquam ullamcorper enim malesuada orci consectetur, vel ultricies massa facilisis. Quisque mi odio, blandit sed nisl non, dapibus ornare nibh. Integer ut erat sit amet urna accumsan porta ac non sapien. Vestibulum tellus eros, fermentum a rutrum non, semper a felis. Praesent venenatis lorem tincidunt, laoreet metus eget, dapibus ligula. Sed convallis sapien vel vestibulum semper. Cras rhoncus elementum sapien, vel porta dolor tristique at. Curabitur gravida id urna sit amet lobortis. Donec quis risus sapien.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">In mollis mauris ut massa fringilla, pretium tempor lorem imperdiet. Curabitur vel risus nulla. Aenean rutrum justo non laoreet vulputate. Etiam mattis laoreet lacus vitae venenatis. Cras varius pellentesque varius. Suspendisse potenti. Nunc eu erat sit amet lacus consequat faucibus quis in orci. Etiam cursus varius ipsum, vitae adipiscing risus egestas nec. Integer id convallis leo, iaculis bibendum tellus. Nam consectetur nibh gravida placerat bibendum.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Suspendisse pellentesque ut orci eget porttitor. Curabitur placerat odio et mauris consectetur elementum. Aliquam dictum eros id ultricies iaculis. Donec non orci facilisis, interdum nisi vel, imperdiet justo. Nam imperdiet diam eget quam vehicula, a mattis orci scelerisque. Morbi tincidunt molestie enim non malesuada. Aliquam scelerisque mi non mauris dictum viverra. In tincidunt, ante ac hendrerit scelerisque, odio quam suscipit nulla, sed tempor nisl purus at nisl. Ut blandit eu erat sed facilisis. Sed facilisis id ipsum ac placerat. Vivamus eu arcu tempus, vulputate massa vel, dignissim estLorem ipsum dolor sit amet, consectetur adipiscing elit. In ut purus feugiat, tincidunt nibh nec, faucibus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed pulvinar risus quis diam tempor volutpat quis non lacus. Donec ullamcorper pellentesque lacinia. Integer lobortis tortor nec elit adipiscing suscipit. Nulla nec dolor ac lacus facilisis mollis at eu risus. Etiam ac congue mi. Nam nec malesuada lorem, a interdum risus. Aliquam at tellus ac nulla sodales ultricies eu convallis nisi. Quisque et magna bibendum, cursus purus suscipit, pellentesque libero. Proin ac lacinia augue, aliquet dictum metus. Nam non felis lectus. Donec a sapien eu nunc vestibulum varius vitae ac odio. Vestibulum fermentum sem ut dui blandit consequat.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Fusce placerat nulla tortor, id gravida lectus hendrerit ac. Sed mattis ultrices ornare. Nulla urna turpis, mattis vitae lectus nec, blandit consequat orci. Nullam mi neque, sodales vulputate venenatis rhoncus, rhoncus sit amet dui. Fusce et diam euismod, blandit metus eget, ornare purus. Nunc ut erat a justo consectetur accumsan vel et metus. Donec in porttitor nisl. Maecenas felis dolor, euismod nec gravida nec, hendrerit vel ligula. Ut dictum, diam ac sagittis commodo, quam metus volutpat ante, eget fringilla dolor massa a ligula. Aliquam sit amet euismod dui, vitae egestas elit. Donec scelerisque viverra condimentum. Aliquam felis ligula, tempus quis laoreet non, fringilla eget urna. Nulla at magna laoreet, viverra diam eget, mollis nisi.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Suspendisse eros felis, tincidunt ac congue at, scelerisque a lectus. Etiam sollicitudin magna ac sem faucibus mollis. Morbi viverra cursus elit nec luctus. Phasellus eget metus eu est tincidunt varius. Aliquam ullamcorper enim malesuada orci consectetur, vel ultricies massa facilisis. Quisque mi odio, blandit sed nisl non, dapibus ornare nibh. Integer ut erat sit amet urna accumsan porta ac non sapien. Vestibulum tellus eros, fermentum a rutrum non, semper a felis. Praesent venenatis lorem tincidunt, laoreet metus eget, dapibus ligula. Sed convallis sapien vel vestibulum semper. Cras rhoncus elementum sapien, vel porta dolor tristique at. Curabitur gravida id urna sit amet lobortis. Donec quis risus sapien.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">In mollis mauris ut massa fringilla, pretium tempor lorem imperdiet. Curabitur vel risus nulla. Aenean rutrum justo non laoreet vulputate. Etiam mattis laoreet lacus vitae venenatis. Cras varius pellentesque varius. Suspendisse potenti. Nunc eu erat sit amet lacus consequat faucibus quis in orci. Etiam cursus varius ipsum, vitae adipiscing risus egestas nec. Integer id convallis leo, iaculis bibendum tellus. Nam consectetur nibh gravida placerat bibendum.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Suspendisse pellentesque ut orci eget porttitor. Curabitur placerat odio et mauris consectetur elementum. Aliquam dictum eros id ultricies iaculis. Donec non orci facilisis, interdum nisi vel, imperdiet justo. Nam imperdiet diam eget quam vehicula, a mattis orci scelerisque. Morbi tincidunt molestie enim non malesuada. Aliquam scelerisque mi non mauris dictum viverra. In tincidunt, ante ac hendrerit scelerisque, odio quam suscipit nulla, sed tempor nisl purus at nisl. Ut blandit eu erat sed facilisis. Sed facilisis id ipsum ac placerat. Vivamus eu arcu tempus, vulputate massa vel, dignissim estLorem ipsum dolor sit amet, consectetur adipiscing elit. In ut purus feugiat, tincidunt nibh nec, faucibus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed pulvinar risus quis diam tempor volutpat quis non lacus. Donec ullamcorper pellentesque lacinia. Integer lobortis tortor nec elit adipiscing suscipit. Nulla nec dolor ac lacus facilisis mollis at eu risus. Etiam ac congue mi. Nam nec malesuada lorem, a interdum risus. Aliquam at tellus ac nulla sodales ultricies eu convallis nisi. Quisque et magna bibendum, cursus purus suscipit, pellentesque libero. Proin ac lacinia augue, aliquet dictum metus. Nam non felis lectus. Donec a sapien eu nunc vestibulum varius vitae ac odio. Vestibulum fermentum sem ut dui blandit consequat.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Fusce placerat nulla tortor, id gravida lectus hendrerit ac. Sed mattis ultrices ornare. Nulla urna turpis, mattis vitae lectus nec, blandit consequat orci. Nullam mi neque, sodales vulputate venenatis rhoncus, rhoncus sit amet dui. Fusce et diam euismod, blandit metus eget, ornare purus. Nunc ut erat a justo consectetur accumsan vel et metus. Donec in porttitor nisl. Maecenas felis dolor, euismod nec gravida nec, hendrerit vel ligula. Ut dictum, diam ac sagittis commodo, quam metus volutpat ante, eget fringilla dolor massa a ligula. Aliquam sit amet euismod dui, vitae egestas elit. Donec scelerisque viverra condimentum. Aliquam felis ligula, tempus quis laoreet non, fringilla eget urna. Nulla at magna laoreet, viverra diam eget, mollis nisi.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Suspendisse eros felis, tincidunt ac congue at, scelerisque a lectus. Etiam sollicitudin magna ac sem faucibus mollis. Morbi viverra cursus elit nec luctus. Phasellus eget metus eu est tincidunt varius. Aliquam ullamcorper enim malesuada orci consectetur, vel ultricies massa facilisis. Quisque mi odio, blandit sed nisl non, dapibus ornare nibh. Integer ut erat sit amet urna accumsan porta ac non sapien. Vestibulum tellus eros, fermentum a rutrum non, semper a felis. Praesent venenatis lorem tincidunt, laoreet metus eget, dapibus ligula. Sed convallis sapien vel vestibulum semper. Cras rhoncus elementum sapien, vel porta dolor tristique at. Curabitur gravida id urna sit amet lobortis. Donec quis risus sapien.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">In mollis mauris ut massa fringilla, pretium tempor lorem imperdiet. Curabitur vel risus nulla. Aenean rutrum justo non laoreet vulputate. Etiam mattis laoreet lacus vitae venenatis. Cras varius pellentesque varius. Suspendisse potenti. Nunc eu erat sit amet lacus consequat faucibus quis in orci. Etiam cursus varius ipsum, vitae adipiscing risus egestas nec. Integer id convallis leo, iaculis bibendum tellus. Nam consectetur nibh gravida placerat bibendum.</p><p style="text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;">Suspendisse pellentesque ut orci eget porttitor. Curabitur placerat odio et mauris consectetur elementum. Aliquam dictum eros id ultricies iaculis. Donec non orci facilisis, interdum nisi vel, imperdiet justo. Nam imperdiet diam eget quam vehicula, a mattis orci scelerisque. Morbi tincidunt molestie enim non malesuada. Aliquam scelerisque mi non mauris dictum viverra. In tincidunt, ante ac hendrerit scelerisque, odio quam suscipit nulla, sed tempor nisl purus at nisl. Ut blandit eu erat sed facilisis. Sed facilisis id ipsum ac placerat. Vivamus eu arcu tempus, vulputate massa vel, dignissim est</p>', 1),
(3, 'or connect with us on social networks', ':)', 2),
(4, 'super text', '', 2),
(5, 'New text', '<br>', 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `StatusID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusName` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`StatusID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=4 ;

--
-- Vypisuji data pro tabulku `status`
--

INSERT INTO `status` (`StatusID`, `StatusName`) VALUES
(1, 'Active'),
(2, 'Non-Active'),
(3, 'Archived');

-- --------------------------------------------------------

--
-- Struktura tabulky `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
  `UnitID` int(11) NOT NULL AUTO_INCREMENT,
  `UnitShort` varchar(50) NOT NULL,
  `UnitName` varchar(255) NOT NULL,
  PRIMARY KEY (`UnitID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Vypisuji data pro tabulku `unit`
--

INSERT INTO `unit` (`UnitID`, `UnitShort`, `UnitName`) VALUES
(1, ' ', ' '),
(2, 'g', 'grams'),
(3, 'mAh', 'miliamperhours'),
(4, 'px', 'pixels'),
(5, '"', 'inches');

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `UsersID` varchar(60) NOT NULL,
  `Password` varchar(60) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `PhoneNumber` int(11) DEFAULT NULL,
  `CompanyName` varchar(45) DEFAULT NULL,
  `TIN` varchar(45) DEFAULT NULL,
  `Permission` varchar(6) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`UsersID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`UsersID`, `Password`, `Name`, `PhoneNumber`, `CompanyName`, `TIN`, `Permission`) VALUES
('admin@admin.com', '$2a$07$$$$$$$$$$$$$$$$$$$$$$.Yqgs4IC6X2x/em6hm8RFU1wBrsCvoAi', 'Lukkk', 0, '0', '0', 'admin');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_2` FOREIGN KEY (`UsersID`) REFERENCES `users` (`UsersID`);

--
-- Omezení pro tabulku `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);

--
-- Omezení pro tabulku `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_2` FOREIGN KEY (`HigherCategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `category_ibfk_3` FOREIGN KEY (`CategoryStatus`) REFERENCES `categorystatus` (`CategoryStatusID`);

--
-- Omezení pro tabulku `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `FKPreviousComment` FOREIGN KEY (`PreviousCommentID`) REFERENCES `comment` (`CommentID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`),
  ADD CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`HigherDelivery`) REFERENCES `delivery` (`DeliveryID`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Omezení pro tabulku `documentation`
--
ALTER TABLE `documentation`
  ADD CONSTRAINT `FKDocumentProduct` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `module_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`);

--
-- Omezení pro tabulku `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`);

--
-- Omezení pro tabulku `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `FKOrderDetailsOrder` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FKOrderDetailsProduct` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FKOrderDelivery` FOREIGN KEY (`DeliveryID`) REFERENCES `delivery` (`DeliveryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FKOrderStatus` FOREIGN KEY (`StatusID`) REFERENCES `orderstatus` (`OrderStatusID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`PaymentID`) REFERENCES `payment` (`PaymentID`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`UsersID`) REFERENCES `users` (`UsersID`);

--
-- Omezení pro tabulku `parameters`
--
ALTER TABLE `parameters`
  ADD CONSTRAINT `parameters_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `parameters_ibfk_2` FOREIGN KEY (`AttribID`) REFERENCES `attrib` (`AttribID`),
  ADD CONSTRAINT `parameters_ibfk_3` FOREIGN KEY (`UnitID`) REFERENCES `unit` (`UnitID`);

--
-- Omezení pro tabulku `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`);

--
-- Omezení pro tabulku `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `FKPhotoPhotoAlbum` FOREIGN KEY (`PhotoAlbumID`) REFERENCES `photoalbum` (`PhotoAlbumID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `photoalbum`
--
ALTER TABLE `photoalbum`
  ADD CONSTRAINT `photoalbum_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `photoalbum_ibfk_2` FOREIGN KEY (`BlogID`) REFERENCES `blog` (`BlogID`),
  ADD CONSTRAINT `photoalbum_ibfk_3` FOREIGN KEY (`StaticTextID`) REFERENCES `statictext` (`StaticTextID`);

--
-- Omezení pro tabulku `price`
--
ALTER TABLE `price`
  ADD CONSTRAINT `FKPriceCurrency` FOREIGN KEY (`CurrencyID`) REFERENCES `currency` (`CurrencyID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `price_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`);

--
-- Omezení pro tabulku `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FKProductCategory` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`ProductStatusID`) REFERENCES `productstatus` (`ProductStatusID`),
  ADD CONSTRAINT `product_ibfk_4` FOREIGN KEY (`ProducerID`) REFERENCES `producer` (`ProducerID`),
  ADD CONSTRAINT `product_ibfk_6` FOREIGN KEY (`ProductVariants`) REFERENCES `product` (`ProductID`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `productvariants`
--
ALTER TABLE `productvariants`
  ADD CONSTRAINT `productvariants_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  ADD CONSTRAINT `productvariants_ibfk_2` FOREIGN KEY (`AttribID`) REFERENCES `attrib` (`AttribID`),
  ADD CONSTRAINT `productvariants_ibfk_3` FOREIGN KEY (`Val`) REFERENCES `attrib` (`AttribID`),
  ADD CONSTRAINT `productvariants_ibfk_4` FOREIGN KEY (`UnitID`) REFERENCES `unit` (`UnitID`);

--
-- Omezení pro tabulku `statictext`
--
ALTER TABLE `statictext`
  ADD CONSTRAINT `statictext_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
