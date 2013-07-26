-- Adminer 3.6.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `AddressID` int(11) NOT NULL AUTO_INCREMENT,
  `UsersID` varchar(60) DEFAULT NULL,
  `Street` varchar(100) DEFAULT NULL,
  `ZIPCode` int(11) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `State` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`AddressID`),
  KEY `UsersID` (`UsersID`),
  CONSTRAINT `address_ibfk_2` FOREIGN KEY (`UsersID`) REFERENCES `users` (`UsersID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `address` (`AddressID`, `UsersID`, `Street`, `ZIPCode`, `City`, `State`) VALUES
(56,	NULL,	'U hřbitova 105',	26751,	'Zdice',	NULL),
(57,	NULL,	'U hřbitova 105',	26751,	'Zdice',	NULL),
(58,	NULL,	'U hřbitova 105',	26751,	'Zdice',	NULL),
(59,	NULL,	'U hřbitova 105',	26751,	'Zdice',	NULL),
(60,	NULL,	'qwer',	0,	'qwr',	NULL),
(61,	NULL,	'qwer',	0,	'qwr',	NULL),
(62,	NULL,	'qwer',	0,	'qwr',	NULL),
(63,	NULL,	'qwer',	0,	'qwr',	NULL),
(64,	'12389@1234.xxx',	'qwer',	0,	'qwr',	NULL),
(65,	'admin@admin.com',	'asdasd',	0,	'vdsfs',	NULL),
(66,	'michal@prosek.cz',	'Zeleny pruha',	16000,	'Praha a',	NULL),
(67,	'xxx@xxx.xxx',	'213',	12311,	'123145',	NULL),
(68,	'as@asd.ca',	'AS',	1823,	'SAF',	NULL),
(69,	'asd@asda.sa',	'AS',	91827,	'AAA',	NULL),
(70,	'qwe@qwe.qwe',	'QWE',	123,	'qwe',	NULL),
(71,	'asd@qwe.wqe',	'AIDS',	0,	'qwe',	NULL),
(72,	'pepa@depa.zz',	'Pepikova',	12931,	'Josefov',	NULL),
(73,	'thinki@mail.com',	'thinkithinki',	18211,	'Thinki',	NULL),
(74,	'asus@vivo.boo',	'Asus street',	19299,	'Vivocity',	NULL);

DROP TABLE IF EXISTS `attrib`;
CREATE TABLE `attrib` (
  `AttribID` int(11) NOT NULL AUTO_INCREMENT,
  `AttribName` varchar(255) NOT NULL,
  PRIMARY KEY (`AttribID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
  `BlogID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `BlogName` varchar(150) NOT NULL,
  `BlogContent` text,
  PRIMARY KEY (`BlogID`),
  KEY `ProductID` (`ProductID`),
  KEY `CategoryID` (`CategoryID`),
  CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `blog` (`BlogID`, `ProductID`, `CategoryID`, `BlogName`, `BlogContent`) VALUES
(1,	1,	1001,	'Lorem Ipsum',	'Dolor sit amet'),
(2,	NULL,	99,	'Testovací post',	'<p>dadwd &scaron; ěe <strong>ě&scaron; ě&scaron;</strong></p>'),
(3,	NULL,	99,	'Testovací post',	'<p>dadwd &scaron; ěe <strong>ě&scaron; ě&scaron;</strong></p>'),
(4,	NULL,	99,	'Testovací post',	'<p>dadwd &scaron; ěe <strong>ě&scaron; ě&scaron;</strong></p>'),
(5,	NULL,	99,	'Testovací post',	'<p>dadwd &scaron; ěe <strong>ě&scaron; ě&scaron;</strong></p>'),
(6,	NULL,	1003,	'Testovací post',	'<p>dadwd &scaron; ěe <strong>ě&scaron; ě&scaron;</strong></p>'),
(7,	NULL,	1003,	'Testovaci funkcni',	'ASqwasdadsaf fa asd saf afds +<br>'),
(8,	NULL,	1003,	'Test :)',	'<p>dasdasdasda</p>'),
(9,	NULL,	99,	'Lorem Ipsum',	'<p>:) :) :) :D</p>'),
(10,	NULL,	99,	'Super nový příspěvek',	'');

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(45) DEFAULT NULL,
  `CategoryDescription` longtext,
  `CategoryStatus` int(11) NOT NULL DEFAULT '3',
  `HigherCategoryID` int(11) DEFAULT NULL,
  `CategoryPhoto` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`CategoryID`),
  KEY `HigherCategoryID_idx` (`HigherCategoryID`),
  KEY `CategoryStatus` (`CategoryStatus`),
  CONSTRAINT `category_ibfk_2` FOREIGN KEY (`HigherCategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `category_ibfk_3` FOREIGN KEY (`CategoryStatus`) REFERENCES `categorystatus` (`CategoryStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `category` (`CategoryID`, `CategoryName`, `CategoryDescription`, `CategoryStatus`, `HigherCategoryID`, `CategoryPhoto`) VALUES
(99,	'Blog',	'Blog full of wonderfull stories!',	4,	NULL,	NULL),
(100,	'Static Text',	NULL,	4,	NULL,	NULL),
(1001,	'Cellphone',	'<p>A mobile phone (also known as a cellular phone, cell phone, and a hand phone) is a device that can make and receive telephone calls over a radio link while moving around a wide geographic area.&nbsp;</p>',	1,	NULL,	'mobil.jpg'),
(1002,	'Notebook',	'<p>A laptop computer is a personal computer for mobile use.[1] A laptop has most of the same components as a desktop compu</p>',	1,	NULL,	NULL),
(1003,	'Smartphones',	NULL,	1,	1001,	NULL),
(1004,	'Ta-ble-ty',	'<p>A tablet computer, or simply tablet, is a one-piece mobile computer. Devices typically offer a touchscreen, with finger (or stylus) gestures acting as the primary means of control, though often supplemented by the use of one or more physical context sensitive buttons or the input from one or more accelerometers; an on-screen, hideable virtual keyboard is generally offered as the principal means of data input. Available in a variety of sizes, tablets customarily offer a screen diagonal greater than 7 inches (18 cm), differentiating themselves through size from functionally similar smart phones or personal digital assistants.</p>',	1,	1001,	NULL),
(1005,	'Washmashine',	'<p>:)</p>',	0,	NULL,	NULL);

DROP TABLE IF EXISTS `categorystatus`;
CREATE TABLE `categorystatus` (
  `CategoryStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryStatusName` varchar(190) NOT NULL,
  PRIMARY KEY (`CategoryStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categorystatus` (`CategoryStatusID`, `CategoryStatusName`) VALUES
(0,	'Draft'),
(1,	'Published'),
(2,	'Featured'),
(4,	'Blog');

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `CommentID` int(11) NOT NULL AUTO_INCREMENT,
  `CommentTittle` varchar(45) DEFAULT NULL,
  `CommentContent` text,
  `DateOfAdded` date DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `PreviousCommentID` int(11) DEFAULT '0',
  PRIMARY KEY (`CommentID`),
  KEY `CommentID_idx` (`PreviousCommentID`),
  CONSTRAINT `FKPreviousComment` FOREIGN KEY (`PreviousCommentID`) REFERENCES `comment` (`CommentID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comment` (`CommentID`, `CommentTittle`, `CommentContent`, `DateOfAdded`, `UserID`, `PreviousCommentID`) VALUES
(1,	'First Comment',	'This is first comment ever',	'2012-03-20',	1,	NULL),
(2,	'Reakce na koment',	'Pi? ?esky pitomo',	'2012-03-20',	2,	1);

DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency` (
  `CurrencyID` int(11) NOT NULL AUTO_INCREMENT,
  `CurrencyCode` varchar(3) DEFAULT NULL,
  `CurrencyName` varchar(45) DEFAULT NULL,
  `CurrencyRate` float DEFAULT NULL,
  PRIMARY KEY (`CurrencyID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `currency` (`CurrencyID`, `CurrencyCode`, `CurrencyName`, `CurrencyRate`) VALUES
(1,	'CZK',	'Koruna ?esk?',	NULL),
(2,	'EUR',	'Euro',	NULL);

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE `delivery` (
  `DeliveryID` int(11) NOT NULL AUTO_INCREMENT,
  `DeliveryName` varchar(45) DEFAULT NULL,
  `DeliveryDescription` varchar(45) DEFAULT NULL,
  `DeliveryPrice` float DEFAULT NULL,
  `FreeFromPrice` float DEFAULT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`DeliveryID`),
  KEY `PriceID_idx` (`DeliveryPrice`),
  KEY `StatusID` (`StatusID`),
  CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `delivery` (`DeliveryID`, `DeliveryName`, `DeliveryDescription`, `DeliveryPrice`, `FreeFromPrice`, `StatusID`) VALUES
(1,	'Personal pick up',	'Personal in the shop',	0,	NULL,	1),
(2,	'Czech postal service',	'Send by transport company',	99,	1000,	3),
(3,	'DPD',	'Curier express shipping!',	160,	10000,	3);

DROP TABLE IF EXISTS `documentation`;
CREATE TABLE `documentation` (
  `DocumentID` int(11) NOT NULL AUTO_INCREMENT,
  `DocumentName` varchar(45) DEFAULT NULL,
  `DocumentDescription` varchar(500) DEFAULT NULL,
  `DocumentURL` varchar(100) DEFAULT NULL,
  `ProductID` int(11) NOT NULL,
  PRIMARY KEY (`DocumentID`),
  KEY `ProductID_idx` (`ProductID`),
  CONSTRAINT `FKDocumentProduct` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `documentation` (`DocumentID`, `DocumentName`, `DocumentDescription`, `DocumentURL`, `ProductID`) VALUES
(3,	'User Guide',	'Super User',	'vizitka_joo (1).pdf',	4);

DROP TABLE IF EXISTS `mailcontent`;
CREATE TABLE `mailcontent` (
  `MailID` int(11) NOT NULL AUTO_INCREMENT,
  `MailSubject` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `MailContent` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`MailID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `NotesID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL,
  `NotesDate` datetime NOT NULL,
  `NotesName` varchar(150) NOT NULL,
  `NotesDescription` text NOT NULL,
  PRIMARY KEY (`NotesID`),
  KEY `OrderID` (`OrderID`),
  CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `notes` (`NotesID`, `OrderID`, `NotesDate`, `NotesName`, `NotesDescription`) VALUES
(1,	19,	'2013-05-19 21:11:51',	'Zákazník',	'Bez Ponožek!'),
(2,	19,	'2013-05-20 00:00:00',	'admin@admin.com',	'XXX'),
(3,	18,	'2013-05-20 00:00:00',	'admin@admin.com',	'ASd');

DROP TABLE IF EXISTS `orderdetails`;
CREATE TABLE `orderdetails` (
  `OrderDetailsID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `UnitPrice` float DEFAULT NULL,
  PRIMARY KEY (`OrderDetailsID`),
  KEY `OrderID_idx` (`OrderID`),
  KEY `ProductID_idx` (`ProductID`),
  CONSTRAINT `FKOrderDetailsOrder` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKOrderDetailsProduct` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `orderdetails` (`OrderDetailsID`, `OrderID`, `ProductID`, `Quantity`, `UnitPrice`) VALUES
(1,	1,	1,	1,	8999),
(2,	1,	6,	1,	99),
(3,	2,	6,	1,	99),
(4,	3,	5,	1,	11999),
(5,	4,	4,	1,	15999),
(6,	5,	5,	1,	11999),
(7,	6,	4,	1,	15999),
(8,	6,	3,	1,	16999),
(9,	7,	2,	1,	99),
(10,	7,	3,	1,	8999),
(11,	8,	6,	1,	11999),
(12,	9,	6,	1,	11999),
(13,	9,	4,	1,	16999),
(15,	18,	1,	1,	10998),
(16,	19,	2,	1,	5999),
(29,	17,	6,	1,	11999),
(31,	20,	13,	1,	3999),
(33,	20,	11,	1,	23999),
(34,	21,	15,	1,	19999),
(35,	22,	15,	1,	19999),
(36,	23,	15,	1,	19999),
(37,	24,	15,	1,	19999),
(38,	25,	15,	1,	19999),
(39,	26,	3,	1,	8999),
(40,	27,	16,	1,	1000),
(41,	28,	16,	1,	1000),
(42,	29,	16,	2,	1000),
(43,	30,	17,	1,	6999),
(44,	31,	15,	1,	10000),
(45,	32,	15,	1,	10000),
(46,	33,	16,	2,	400),
(47,	34,	15,	1,	10000);

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusID` int(11) DEFAULT '1',
  `UsersID` varchar(60) DEFAULT NULL,
  `ProductsPrice` float DEFAULT NULL,
  `DeliveryPaymentPrice` float DEFAULT NULL,
  `TaxPrice` float DEFAULT NULL,
  `PriceWithoutTax` float DEFAULT NULL,
  `TotalPrice` float DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateOfLastChange` date DEFAULT NULL,
  `DateFinished` date DEFAULT NULL,
  `DeliveryID` int(11) DEFAULT NULL,
  `PaymentID` int(11) DEFAULT NULL,
  `Note` longtext,
  `IP` varchar(15) DEFAULT NULL,
  `SessionID` int(11) DEFAULT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `StatusID_idx` (`StatusID`),
  KEY `DeliveryID_idx` (`DeliveryID`),
  KEY `PaymentMethodID_idx` (`PaymentID`),
  KEY `UserID` (`UsersID`),
  CONSTRAINT `FKOrderDelivery` FOREIGN KEY (`DeliveryID`) REFERENCES `delivery` (`DeliveryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKOrderStatus` FOREIGN KEY (`StatusID`) REFERENCES `orderstatus` (`OrderStatusID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`PaymentID`) REFERENCES `payment` (`PaymentID`) ON DELETE SET NULL,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`UsersID`) REFERENCES `users` (`UsersID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `orders` (`OrderID`, `StatusID`, `UsersID`, `ProductsPrice`, `DeliveryPaymentPrice`, `TaxPrice`, `PriceWithoutTax`, `TotalPrice`, `DateCreated`, `DateOfLastChange`, `DateFinished`, `DeliveryID`, `PaymentID`, `Note`, `IP`, `SessionID`) VALUES
(1,	1,	'aaaaaaa@aaa.aaa',	9098,	NULL,	90.98,	NULL,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	2,	1,	NULL,	NULL,	NULL),
(2,	1,	'qwe@qrw.as',	99,	NULL,	0.99,	NULL,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	2,	NULL,	NULL,	NULL,	NULL),
(3,	1,	'1234@1234.com',	11999,	NULL,	119.99,	NULL,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	1,	NULL,	NULL,	NULL),
(4,	1,	'ppp@pppa.ppp',	15999,	NULL,	0,	NULL,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	1,	NULL,	NULL,	NULL),
(5,	1,	'afaasasdsasds2134@asfew213.aff',	11999,	NULL,	2519.79,	NULL,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	NULL,	NULL,	NULL,	NULL),
(6,	1,	'tdanek@atlas.cz',	32998,	NULL,	0,	NULL,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	NULL,	NULL,	NULL,	NULL),
(7,	1,	'asda@asd.ads',	9098,	NULL,	0,	NULL,	NULL,	'2013-04-07',	'2013-04-07',	NULL,	1,	1,	NULL,	NULL,	NULL),
(8,	1,	'rte@gdsaasasasasdaassf.fgd',	11999,	100,	2519.79,	NULL,	14518.8,	'2013-04-07',	'2013-04-07',	NULL,	2,	NULL,	NULL,	NULL,	NULL),
(9,	2,	'asd@asfdg.dfg',	28998,	150,	6089.58,	NULL,	35087.6,	'2013-04-07',	'2013-04-07',	NULL,	2,	1,	NULL,	NULL,	NULL),
(17,	1,	'12389@1234.xxx',	33997,	0,	2309.79,	NULL,	11999,	'2013-04-17',	'2013-04-17',	NULL,	1,	1,	'Note',	NULL,	NULL),
(18,	2,	'admin@admin.com',	10998,	0,	2309,	NULL,	13307,	'2013-04-17',	'2013-04-17',	NULL,	1,	1,	'wfevergverbr',	NULL,	NULL),
(19,	1,	'michal@prosek.cz',	5999,	149,	1259.79,	NULL,	7258.79,	'2013-04-24',	'2013-04-24',	NULL,	1,	3,	'Chci to hned!',	NULL,	NULL),
(20,	2,	'michal@prosek.cz',	181987,	160,	2519.58,	NULL,	182147,	'2013-04-24',	'2013-04-24',	NULL,	3,	1,	'',	NULL,	NULL),
(21,	1,	'xxx@xxx.xxx',	19999,	99,	4199.79,	NULL,	24198.8,	'2013-06-30',	'2013-06-30',	NULL,	2,	1,	'',	NULL,	NULL),
(22,	1,	'xxx@xxx.xxx',	19999,	99,	4199.79,	NULL,	24198.8,	'2013-06-30',	'2013-06-30',	NULL,	2,	1,	'',	NULL,	NULL),
(23,	1,	'xxx@xxx.xxx',	19999,	99,	4199.79,	NULL,	24198.8,	'2013-06-30',	'2013-06-30',	NULL,	2,	1,	'',	NULL,	NULL),
(24,	1,	'xxx@xxx.xxx',	19999,	99,	4199.79,	NULL,	24198.8,	'2013-06-30',	'2013-06-30',	NULL,	2,	1,	'',	NULL,	NULL),
(25,	1,	'xxx@xxx.xxx',	19999,	0,	4199.79,	NULL,	24198.8,	'2013-06-30',	'2013-06-30',	NULL,	1,	1,	'',	NULL,	NULL),
(26,	2,	'as@asd.ca',	8999,	0,	1889.79,	NULL,	10888.8,	'2013-06-30',	'2013-06-30',	NULL,	1,	1,	'',	NULL,	NULL),
(27,	1,	'asd@asda.sa',	1000,	0,	210,	NULL,	1210,	'2013-06-30',	'2013-06-30',	NULL,	1,	1,	'',	NULL,	NULL),
(28,	1,	'qwe@qwe.qwe',	1000,	99,	210,	790,	1000,	'2013-06-30',	'2013-06-30',	NULL,	2,	1,	'',	NULL,	NULL),
(29,	1,	'asd@qwe.wqe',	2000,	99,	420,	1580,	2099,	'2013-06-30',	'2013-06-30',	NULL,	2,	1,	'',	NULL,	NULL),
(30,	1,	'pepa@depa.zz',	6999,	0,	1469.79,	NULL,	8468.79,	'2013-07-09',	'2013-07-09',	NULL,	1,	1,	'',	NULL,	NULL),
(31,	1,	'xxx@xxx.xxx',	10000,	0,	2100,	NULL,	12100,	'2013-07-09',	'2013-07-09',	NULL,	1,	1,	'',	NULL,	NULL),
(32,	1,	'aaa@aaa.aaa',	10000,	0,	2100,	7900,	10000,	'2013-07-09',	'2013-07-09',	NULL,	1,	1,	'',	NULL,	NULL),
(33,	1,	'thinki@mail.com',	800,	0,	168,	632,	800,	'2013-07-09',	'2013-07-09',	NULL,	1,	1,	'',	NULL,	NULL),
(34,	1,	'asus@vivo.boo',	10000,	160,	2100,	7900,	10160,	'2013-07-09',	'2013-07-19',	NULL,	3,	1,	'',	NULL,	NULL);

DROP TABLE IF EXISTS `orderstatus`;
CREATE TABLE `orderstatus` (
  `OrderStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusName` varchar(45) DEFAULT NULL,
  `StatusDescription` varchar(255) DEFAULT NULL,
  `StatusProgress` int(11) DEFAULT '0',
  PRIMARY KEY (`OrderStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `orderstatus` (`OrderStatusID`, `StatusName`, `StatusDescription`, `StatusProgress`) VALUES
(0,	'CANCELED',	'order has been canceled',	0),
(1,	'Pending',	'Pending order',	1),
(2,	'Sending',	'sending order',	5),
(3,	'Done',	'done',	10);

DROP TABLE IF EXISTS `parameters`;
CREATE TABLE `parameters` (
  `ParameterID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `AttribID` int(11) DEFAULT NULL,
  `Val` varchar(255) DEFAULT NULL,
  `UnitID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ParameterID`),
  KEY `ProductID` (`ProductID`),
  KEY `AttribID` (`AttribID`),
  KEY `UnitID` (`UnitID`),
  CONSTRAINT `parameters_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  CONSTRAINT `parameters_ibfk_2` FOREIGN KEY (`AttribID`) REFERENCES `attrib` (`AttribID`),
  CONSTRAINT `parameters_ibfk_3` FOREIGN KEY (`UnitID`) REFERENCES `unit` (`UnitID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `PaymentName` varchar(45) DEFAULT NULL,
  `PaymentPrice` float DEFAULT '1',
  `StatusID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`PaymentID`),
  KEY `PriceID` (`PaymentPrice`),
  KEY `StatusID` (`StatusID`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `payment` (`PaymentID`, `PaymentName`, `PaymentPrice`, `StatusID`) VALUES
(1,	'Cash',	0,	1),
(3,	'Bankwire',	50,	1);

DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `PhotoID` int(11) NOT NULL AUTO_INCREMENT,
  `PhotoName` varchar(45) DEFAULT NULL,
  `PhotoURL` varchar(100) DEFAULT NULL,
  `PhotoAlbumID` int(11) DEFAULT NULL,
  `PhotoAltText` varchar(100) DEFAULT NULL,
  `CoverPhoto` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`PhotoID`),
  KEY `PhotoAlbumID_idx` (`PhotoAlbumID`),
  CONSTRAINT `FKPhotoPhotoAlbum` FOREIGN KEY (`PhotoAlbumID`) REFERENCES `photoalbum` (`PhotoAlbumID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `photo` (`PhotoID`, `PhotoName`, `PhotoURL`, `PhotoAlbumID`, `PhotoAltText`, `CoverPhoto`) VALUES
(1,	'Foto Galaxy Nexus 01',	'main.png',	1,	'Foto Galaxy Nexus',	1),
(2,	'Chromebook',	'main.jpg',	2,	'Chromebook',	1),
(3,	'Sony Xperia Z',	'sony.jpg',	3,	'Xperia Z',	1),
(5,	'6658.jpg',	'6658.jpg',	4,	's4',	1),
(6,	'Nexus',	'nexus2.jpg',	1,	'nexus',	0),
(7,	'Nexus',	'nexus3.jpg',	1,	'nexus',	0),
(8,	'nexus7.jpg',	'nexus7.jpg',	1,	's4',	0),
(12,	'ipad-mini-scaled-1.jpg',	'ipad-mini-scaled-1.jpg',	6,	's4',	1),
(13,	'ipad-mini-scaled-1.jpg',	'ipad-mini-scaled-1.jpg',	7,	's4',	1),
(22,	'1416746-img-steve-jobs-apple-ipad.jpg',	'1416746-img-steve-jobs-apple-ipad.jpg',	7,	's4',	NULL),
(23,	'Taking-pictures-with-Nokia-3310.jpg',	'Taking-pictures-with-Nokia-3310.jpg',	4,	's4',	NULL),
(27,	'ImgW.jpg',	'ImgW.jpg',	8,	's4',	0),
(28,	'ImgW (1).jpg',	'ImgW (1).jpg',	8,	's4',	1),
(29,	'ImgW (2).jpg',	'ImgW (2).jpg',	8,	's4',	0),
(30,	'ImgW (3).jpg',	'ImgW (3).jpg',	8,	's4',	NULL),
(31,	'ImgW.jpg',	'ImgW.jpg',	9,	's4',	1),
(33,	'Sony Ericsson Xperia Mini',	'mini.jpg',	11,	'Sony Ericsson Xperia Mini',	1),
(34,	'Sony Ericsson Xperia Mini',	'mini2.jpg',	11,	'Sony Ericsson Xperia Mini',	NULL),
(35,	'name',	'1a6ccb602dfbb930b87c709644585f1d.jpg',	11,	'name',	NULL),
(36,	'name',	'mini.jpg',	4,	'name',	NULL),
(37,	'name',	'nokia-case.jpeg',	6,	'name',	NULL),
(38,	'Test :)',	'xxx.jpg',	12,	'Test :)',	1),
(46,	'Terms and condition',	'24.PNG',	15,	'Terms and condition',	1),
(47,	'About us',	'24.PNG',	16,	'About us',	1),
(48,	'Spanche Bob',	'images.jpg',	17,	'Spanche Bob',	1),
(49,	'Asus Vivobook',	'ImgW.jpg',	18,	'Asus Vivobook',	1),
(50,	'Thinkpad',	'220px-IBM_Thinkpad_R51.jpg',	19,	'Thinkpad',	1),
(51,	'L9',	'lg_optimus_l9-575x500.jpg',	20,	'L9',	1),
(52,	'name',	'osx.jpg',	2,	'name',	NULL),
(53,	'name',	'vylajkuj-si-slevu.jpg',	22,	'name',	NULL),
(54,	'name',	'chopn_2.PNG',	21,	'name',	NULL),
(55,	'name',	'chopn_2.PNG',	14,	'name',	NULL);

DROP TABLE IF EXISTS `photoalbum`;
CREATE TABLE `photoalbum` (
  `PhotoAlbumID` int(11) NOT NULL AUTO_INCREMENT,
  `PhotoAlbumName` varchar(45) DEFAULT NULL,
  `PhotoAlbumDescription` varchar(500) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `BlogID` int(11) DEFAULT NULL,
  `StaticTextID` int(11) DEFAULT NULL,
  PRIMARY KEY (`PhotoAlbumID`),
  KEY `ProductID` (`ProductID`),
  KEY `BlogID` (`BlogID`),
  KEY `StaticTextID` (`StaticTextID`),
  CONSTRAINT `photoalbum_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`),
  CONSTRAINT `photoalbum_ibfk_2` FOREIGN KEY (`BlogID`) REFERENCES `blog` (`BlogID`),
  CONSTRAINT `photoalbum_ibfk_3` FOREIGN KEY (`StaticTextID`) REFERENCES `statictext` (`StaticTextID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `photoalbum` (`PhotoAlbumID`, `PhotoAlbumName`, `PhotoAlbumDescription`, `ProductID`, `BlogID`, `StaticTextID`) VALUES
(0,	'Bez fotky',	'Pro příspěvky s prázdnými fotkami',	NULL,	NULL,	NULL),
(1,	'Album GN',	'Galaxy Nexus album',	1,	NULL,	NULL),
(2,	'Chromebook',	'Chromebook gallery',	2,	NULL,	NULL),
(3,	'Trojka',	'trojka',	3,	NULL,	NULL),
(4,	'ctyrka',	'ctyrka',	4,	NULL,	NULL),
(5,	'petka',	'petka',	5,	NULL,	NULL),
(6,	'Apple iPad',	'The best tablet EVER!',	6,	NULL,	NULL),
(7,	'Apple iPad',	'The best tablet EVER!',	9,	NULL,	NULL),
(8,	'Lenovo ThinkPad Tablet 2 64GB WiFi 3G 3679-4H',	'Tablet Lenovo ThinkPad skrývá v uhlazeném černém šasi velmi atraktivní hardwarovou výbavu pohánějící systém Microsoft Windows 8. Tablet je vhodný pro náročné uživatele na každodenních cestách, kteří potřebují především spolehlivě rychlý kancelářský software, přístup k internetu s nejrůznějšími aplikacemi včetně vysoce kvalitní videokomunikace a přehrávání zvuku i videa. S výdrží až 10 hodin na jedno nabití s vámi bude celý den připravený do služby.\r\n\r\nKe všem činnostem skvěle poslouží multidotyk',	10,	NULL,	NULL),
(9,	'Lenovo ThinkPad Edge E130 Arctic Blue 3358-8C',	'<p><span style=\"color: #1a171b; font-family: Verdana, sans-serif, Arial; font-size: 13px; line-height: 19.5px; background-color: #fffffd;\">Velmi mal&yacute; a tenk&yacute; cestovn&iacute; notebook LENOVO ThinkPad Edge E130 s nejnověj&scaron;&iacute;m procesorem a s&nbsp;</span><strong style=\"color: #1a171b; font-family: Verdana, sans-serif, Arial; font-size: 13px; line-height: 19.5px; background-color: #fffffd;\">3G připojen&iacute;m</strong><span style=\"color: #1a171b; font-family: Verdana, sans',	11,	NULL,	NULL),
(11,	'Sony Ericsson Xperia Mini',	'<p style=\"margin: 0px 0px 0.5em; padding: 0px; line-height: 1.5em; word-wrap: break-word; color: #333333; font-family: \'Arial CE\', Arial, \'Helvetica CE\', Helvetica, sans-serif; font-size: small;\"><strong style=\"margin: 0px; padding: 0px;\">Mal&yacute; stylov&yacute; smartphone Sony Ericsson Xperia Mini</strong>&nbsp;se může pochlubit pěkn&yacute;m designem, mal&yacute;mi rozměry i velmi dobrou funkčn&iacute; v&yacute;bavou. Telefonu s operačn&iacute;m syst&eacute;mem Google Android 2.3 Gingerbrea',	13,	NULL,	NULL),
(12,	'Test :)',	'<p>dasdasdasda</p>',	NULL,	7,	NULL),
(13,	'Test :)',	'<p>dasdasdasda</p>',	NULL,	8,	NULL),
(14,	'Lorem Ipsum',	'<p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sodales mi quis ligula posuere sit amet cursus metus commodo. Proin odio ante, euismod in pellentesque id, dignissim in lacus. Nullam ipsum nunc, tempor in volutpat a, semper vel eros. Maecenas quis lobortis lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac tur',	NULL,	9,	NULL),
(15,	'Terms and condition',	'<p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam non semper dui. Sed convallis dolor quis turpis auctor gravida. Phasellus et erat diam. Aliquam sed neque libero, eget bibendum eros. Quisque quis lacinia felis. Vestibulum eget turpis sem. Ut id ligula id orci eleifend adipiscing bibendum a velit. Curabitur consectetur enim ut leo auctor vitae ',	NULL,	NULL,	1),
(16,	'About us',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vel dui enim, vitae suscipit est. Donec enim justo, condimentum in dignissim sed, ultricies ut nisi. Nulla vel ligula turpis, pharetra rutrum magna. Maecenas faucibus dapibus nibh in euismod. Phasellus rutrum egestas dui eu fringilla. Nulla vitae sapien odio. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla et mi leo, vel tempus diam. Aenean bibendum, augue in imperdiet dignissim,',	NULL,	NULL,	2),
(17,	'Spanche Bob',	'',	14,	NULL,	NULL),
(18,	'Asus Vivobook',	'',	15,	NULL,	NULL),
(19,	'Thinkpad',	'',	16,	NULL,	NULL),
(20,	'L9',	'',	17,	NULL,	NULL),
(21,	'super text',	'',	NULL,	NULL,	4),
(22,	'New text',	'',	NULL,	NULL,	5),
(23,	'Super nový příspěvek',	'',	NULL,	10,	NULL);

DROP TABLE IF EXISTS `price`;
CREATE TABLE `price` (
  `PriceID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `SellingPrice` float NOT NULL,
  `SALE` float DEFAULT '1',
  `FinalPrice` float NOT NULL,
  `CurrencyID` int(11) DEFAULT '1',
  PRIMARY KEY (`PriceID`),
  KEY `CurrencyID_idx` (`CurrencyID`),
  KEY `ProductID` (`ProductID`),
  CONSTRAINT `FKPriceCurrency` FOREIGN KEY (`CurrencyID`) REFERENCES `currency` (`CurrencyID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `price_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `price` (`PriceID`, `ProductID`, `SellingPrice`, `SALE`, `FinalPrice`, `CurrencyID`) VALUES
(1,	1,	10998,	0,	10998,	1),
(2,	2,	59992,	0,	59992,	1),
(3,	3,	7999,	0,	8999,	1),
(4,	4,	15000,	1500,	13500,	1),
(5,	5,	13999,	0,	15999,	1),
(6,	6,	10000,	0,	11999,	1),
(7,	9,	11999,	1,	13999,	1),
(8,	10,	20,	18,	2,	1),
(9,	11,	20999,	1,	23999,	1),
(10,	13,	3999,	0,	3999,	1),
(11,	14,	9999,	0,	9999,	1),
(12,	15,	19999,	9999,	10000,	1),
(13,	16,	1000,	600,	400,	1),
(14,	17,	0,	0,	6999,	1);

DROP TABLE IF EXISTS `producer`;
CREATE TABLE `producer` (
  `ProducerID` int(11) NOT NULL AUTO_INCREMENT,
  `ProducerName` varchar(255) NOT NULL,
  PRIMARY KEY (`ProducerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `producer` (`ProducerID`, `ProducerName`) VALUES
(1,	'Samsung'),
(2,	'Sony'),
(3,	'Apple'),
(4,	'Lenovo'),
(5,	'Nokia');

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(255) DEFAULT NULL,
  `ProducerID` int(11) DEFAULT NULL,
  `ProductNumber` varchar(255) DEFAULT NULL,
  `ProductShort` varchar(255) DEFAULT NULL,
  `ProductDescription` longtext,
  `ProductStatusID` int(11) NOT NULL DEFAULT '1',
  `ProductEAN` varchar(45) DEFAULT NULL,
  `ProductQR` varchar(45) DEFAULT NULL,
  `ProductWarranty` varchar(45) DEFAULT NULL,
  `PiecesAvailable` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `DateOfAvailable` date NOT NULL DEFAULT '0000-00-00',
  `ProductDateOfAdded` date NOT NULL,
  `CommentID` int(11) DEFAULT NULL,
  `Video` text,
  PRIMARY KEY (`ProductID`),
  KEY `CategoryID_idx` (`CategoryID`),
  KEY `CommentID_idx` (`CommentID`),
  KEY `ProductStatusID` (`ProductStatusID`),
  KEY `ProducerID` (`ProducerID`),
  CONSTRAINT `FKProductCategory` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKProductComment` FOREIGN KEY (`CommentID`) REFERENCES `comment` (`CommentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_ibfk_3` FOREIGN KEY (`ProductStatusID`) REFERENCES `productstatus` (`ProductStatusID`),
  CONSTRAINT `product_ibfk_4` FOREIGN KEY (`ProducerID`) REFERENCES `producer` (`ProducerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `product` (`ProductID`, `ProductName`, `ProducerID`, `ProductNumber`, `ProductShort`, `ProductDescription`, `ProductStatusID`, `ProductEAN`, `ProductQR`, `ProductWarranty`, `PiecesAvailable`, `CategoryID`, `DateOfAvailable`, `ProductDateOfAdded`, `CommentID`, `Video`) VALUES
(1,	'Samsung Galaxy Nexus 2 RR',	1,	'',	'Smartphone ze serie NexusSmartphone ze serie NexusSmartphone ze serie NexusSmartphone ze serie NexusSmartphone ze serie NexusSmartphone ze serie NexusSmartphone ze serie NexusSmartphone ze serie Nexus',	'<p>Smartphone ze serie Nexus</p>',	1,	'',	'',	'',	6,	1002,	'0000-00-00',	'0000-00-00',	1,	NULL),
(2,	'Samsung Chromebooks',	1,	'',	'An ultraportable, sleek laptop for everyday adventures. It weighs 2.4 pounds and has over 6.5 hours of battery life, so you can bring it anywhere and use it everywheres',	'<p>An ultraportable, sleek laptop for everyday adventures. It weighs 2.4 pounds and has over 6.5 hours of battery life, so you can bring it anywhere and use it everywheres</p>',	3,	'',	'',	'',	3,	1002,	'0000-00-00',	'0000-00-00',	NULL,	NULL),
(3,	'Samsung Galaxy S4',	1,	'',	NULL,	'<div style=\"text-align: left;\"><img src=\"http://localhost/platon/www/images/3/300-sony.jpg\">Hot news in smartphone world</div>',	2,	'',	'',	'',	99,	1003,	'0000-00-00',	'0000-00-00',	NULL,	NULL),
(4,	'Nokia 3310 MM',	1,	'',	NULL,	'<p>Best smartphone of present smarthone world. SUPERB!!!</p>',	3,	'',	'',	'',	7,	1001,	'0000-00-00',	'0000-00-00',	NULL,	NULL),
(5,	'Apple iPad',	3,	'',	NULL,	'Tablet from company Apple',	2,	'',	'',	'',	666,	1004,	'0000-00-00',	'0000-00-00',	NULL,	NULL),
(6,	'Nokia 3310',	5,	'11111',	NULL,	'desc',	1,	'123456',	'122',	'rok',	1,	1002,	'0000-00-00',	'0000-00-00',	1,	NULL),
(9,	'Apple iPad',	3,	'11111',	NULL,	'The best tablet EVER!',	2,	'123456',	'122',	'rok',	9,	1004,	'0000-00-00',	'0000-00-00',	1,	NULL),
(10,	'Lenovo ThinkPad Tablet 2 64GB WiFi 3G 3679-4HG',	4,	'11111',	NULL,	'<p>Tablet Lenovo ThinkPad skr&yacute;v&aacute; v uhlazen&eacute;m čern&eacute;m &scaron;asi velmi atraktivn&iacute; hardwarovou v&yacute;bavu poh&aacute;něj&iacute;c&iacute; syst&eacute;m Microsoft Windows 8. Tablet je vhodn&yacute; pro n&aacute;ročn&eacute; uživatele na každodenn&iacute;ch cest&aacute;ch, kteř&iacute; potřebuj&iacute; předev&scaron;&iacute;m spolehlivě rychl&yacute; kancel&aacute;řsk&yacute; software, př&iacute;stup k internetu s nejrůzněj&scaron;&iacute;mi aplikacemi včetně vysoce kvalitn&iacute; videokomunikace a přehr&aacute;v&aacute;n&iacute; zvuku i videa. S v&yacute;drž&iacute; až 10 hodin na jedno nabit&iacute; s v&aacute;mi bude cel&yacute; den připraven&yacute; do služby. Ke v&scaron;em činnostem skvěle poslouž&iacute; multidotykov&yacute; displej v rozli&scaron;en&iacute; HD Ready. Displej je typu IPS jako z&aacute;ruka prvotř&iacute;dn&iacute;ho barevn&eacute;ho pod&aacute;n&iacute; s jemn&yacute;mi odst&iacute;ny. Pro roz&scaron;&iacute;řen&iacute; plochy potě&scaron;&iacute; konektor Mini HDMI. Pressure Sensitivy se postar&aacute; o zabr&aacute;něn&iacute; n&aacute;hodn&yacute;ch dotyků při nečinnosti. Pro jemněj&scaron;&iacute; ovl&aacute;d&aacute;n&iacute; a ručn&iacute; psan&iacute; i kreslen&iacute; si lze dokoupit pero. Pomoc&iacute; USB či BlueTooth v&scaron;ak připoj&iacute;te i fyzickou kl&aacute;vesnici. Pro data je &uacute;loži&scaron;tě o kapacitě 64 GB. Oproti standardn&iacute;m diskům přinese daleko men&scaron;&iacute; energetickou spotřebu, rychlej&scaron;&iacute; př&iacute;stup a</p>',	2,	'123456',	'122',	'rok',	1,	1004,	'0000-00-00',	'0000-00-00',	1,	NULL),
(11,	'Lenovo ThinkPad Edge E130 Arctic Blue 3358-8CG',	4,	'11111',	'Velmi malý a tenký cestovní notebook LENOVO ThinkPad Edge E130 s nejnovějším procesorem a s 3G připojením jako ideální každodenní parťák aktivních uživatelů. Notebook odpovídá displeji o kompromisní úhlopříčce 11,6\". Ten je v matné povrchové úpravě pro le',	'Velmi malý a tenký cestovní notebook LENOVO ThinkPad Edge E130 s nejnovějším procesorem a s <strong>3G připojením</strong> jako ideální každodenní parťák aktivních&nbsp;uživatelů. Notebook odpovídá displeji o kompromisní úhlopříčce <strong>11,6\"</strong>. Ten je v <strong>matné povrchové úpravě </strong>pro lepší viditelnost. Spolu s pokročilými funkcemi pro VoIP, ergonomickou klávesnicí a kombinací touchpadu s trackpointem nabízí komfort pro každodenní službu. Je vhodný pro pracovité jedince vyžadující flexibilní mobilní použití a dlouhou výdrž na baterií, která u tohoto modelu dosahuje neuvěřitelných <strong>8 hodin</strong>!<br><br>Pro vaše data je vložen <strong>nadstandardně rychlý disk o kapacitě 500 GB</strong>, takže s sebou budete mít vše důležité. Na disku je předinstalovaný operační systém <strong>Microsoft Windows 8</strong>, který přichází s přehledným dlaždicovým prostředím. Svižný chod tohoto lehce přenosného počítače je na procesoru se sníženou spotřebou z nejnovější série <strong>Ivy Bridge, Intel Core i3 3217U</strong>. Pokud si&nbsp;budete přát&nbsp;pustit například HD video, přijde vhod <strong>HDMI</strong> port, kterým můžete notebook připojit k televizím nebo projektorům ve velkém rozlišení. Další rozšíření lze uskutečnit přes vysokorychlostní <strong>UBS 3.0 </strong>či bezdrátový <strong>BlueTooth</strong>.<br><br>',	2,	'123456',	'122',	'rok',	0,	1002,	'0000-00-00',	'0000-00-00',	1,	NULL),
(13,	'Sony Ericsson Xperia Mini',	2,	'11111',	'Skvělý malý telefon vhodný do každé malé kapsy. QWERTY klávesnice!\r\n',	'<p>Uživatel se díky operačnímu systému Android může těšit na špičkovou funkční základu. Ta je tvořena např. internetovým prohlížečem,<strong> e-mailovým klientem</strong>, multimediálním přehrávačem, bohatými možnostmi synchronizace, kvalitnímu funkcemi pro organizaci času, prohlížečem dokumentů, skvělým kalendářem či aplikacemi pro přístup na Facebook a Twitter. Přístup ke katalogu Android Market pak umožní instalaci dalších tisíců aplikací a her. Samozřejmostí je také povedený digitální fotoaparát s rozlišením pět megapixelů nebo vestavěná satelitní aGPS navigace. Telefonu nechybí ani podpora technologií Wi-Fi (včetně DLNA) a Bluetooth. Pro připojení sluchátek je k dispozici standardní 3,5 mm jack. O napájení se stará Li-Ion akumulátor o kapacitě 1200 mAh, jenž by měl telefonu na jedno nabití umožnit setrvat až 340 hodin v pohotovostním režimu nebo vykonat čtyři a půl hodiny hovoru.s</p>',	2,	'123456',	'122',	'rok',	11,	1003,	'0000-00-00',	'0000-00-00',	1,	NULL),
(14,	'Spange Bob',	1,	'11111',	'Spange Bob je jediný chodící a mluvící keksík na celém širém světě.',	'',	2,	'123456',	'122',	'rok',	100,	1001,	'0000-00-00',	'0000-00-00',	1,	NULL),
(15,	'Asus Vivobook',	1,	'11111',	'',	'',	2,	'123456',	'122',	'rok',	8,	1002,	'0000-00-00',	'0000-00-00',	1,	NULL),
(16,	'Thinkpad',	1,	'11111',	'',	'',	2,	'123456',	'122',	'rok',	3,	1002,	'0000-00-00',	'0000-00-00',	1,	NULL),
(17,	'L9',	1,	'11111',	'',	'',	3,	'123456',	'122',	'rok',	10,	1003,	'0000-00-00',	'0000-00-00',	1,	NULL);

DROP TABLE IF EXISTS `productstatus`;
CREATE TABLE `productstatus` (
  `ProductStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductStatusName` varchar(100) NOT NULL,
  `ProductStatusDescription` varchar(255) NOT NULL,
  PRIMARY KEY (`ProductStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `productstatus` (`ProductStatusID`, `ProductStatusName`, `ProductStatusDescription`) VALUES
(1,	'Concept',	'This product is in stae: Concept'),
(2,	'Visible',	'This product is visible'),
(3,	'Featured',	'Featured product');

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `SettingID` int(11) NOT NULL AUTO_INCREMENT,
  `SettingName` varchar(100) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`SettingID`),
  UNIQUE KEY `Name` (`SettingName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`SettingID`, `SettingName`, `Value`) VALUES
(1,	'ShopLayout',	'layout'),
(2,	'Description',	'Ecommerce 2.0 that works almost automatically.'),
(3,	'HomepageLayout',	'default'),
(4,	'Logo',	'logo (1).png'),
(5,	'Name',	'Birneshop DEMO'),
(6,	'ProductLayout',	'product'),
(7,	'TAX',	'20'),
(8,	'OrderMail',	'luk.danek@gmail.com'),
(9,	'ContactMail',	'jiri.kifa@gmail.com'),
(10,	'ContactPhone',	'+420333444'),
(11,	'CompanyAddress',	'Zdice 18, U hřibitova 34567'),
(12,	'InvoicePrefix',	'2013'),
(13,	'ProductMiniLayout',	'ProductMini4'),
(14,	'GA',	'UA-42741537-1'),
(15,	'ShopURL',	'http://localhost');

DROP TABLE IF EXISTS `statictext`;
CREATE TABLE `statictext` (
  `StaticTextID` int(11) NOT NULL AUTO_INCREMENT,
  `StaticTextName` varchar(100) COLLATE utf8_czech_ci NOT NULL,
  `StaticTextContent` text COLLATE utf8_czech_ci NOT NULL,
  `StatusID` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`StaticTextID`),
  KEY `StatusID` (`StatusID`),
  CONSTRAINT `statictext_ibfk_1` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `statictext` (`StaticTextID`, `StaticTextName`, `StaticTextContent`, `StatusID`) VALUES
(1,	'Terms and condition',	'<p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Bouřkové pásmo spojené se silnými nárazy větru a místy i přívalovými dešti a kroupami po půlnoci přešlo přes střední Čechy a Ústecký kraj do kraje Libereckého.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">\"V okresu Hradec Králové se vyskytují velmi silné bouřky provázené krupobitím.<br>&nbsp;Bouřky postupují směrem k severovýchodu a v následujících hodinách postoupí<br>do okresů Trutnov a Náchod,\" varovali meteorologové v pátek ráno před půl sedmou.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Při bouřkách lidem hrozí&nbsp;zranění ulomenými větvemi a zásah bleskem.&nbsp;Těch bylo na obloze nejvíce hned na počátku, ve čtvrtek mezi 18. a&nbsp;19. hodinou. Tehdy byly bouřky na jihozápadě republiky a<a href=\"http://www.chmi.cz/\" target=\"_blank\" style=\"color: rgb(19, 55, 94);\">Český hydrometeorologický ústav</a>zaznamenal intenzitu až 156 blesků za minutu.</p><div class=\"imagelist imagelist-sp5 not4bbtext\" style=\"zoom: 1; margin: 0px 0px 0.5em; font-size: small; font-family: Arial, Helvetica, sans-serif; background-color: rgb(225, 225, 225);\"><div class=\"cell cell-first\" style=\"float: left; margin-left: 22px; width: 172px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c033e_mapa0023.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c033e_mapa0023.jpg\" width=\"172\" height=\"129\" alt=\"Přehled srážek v noci na pátek 21. června\" title=\"Přehled srážek v noci na pátek 21. června\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"cell\" style=\"float: left; width: 172px; margin-left: 22px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0340_mapa0024.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c0340_mapa0024.jpg\" width=\"172\" height=\"129\" alt=\"Přehled srážek v noci na pátek 21. června\" title=\"Přehled srážek v noci na pátek 21. června\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"cell\" style=\"float: left; width: 172px; margin-left: 22px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0336_mapa0001.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c0336_mapa0001.jpg\" width=\"172\" height=\"129\" alt=\"Přehled srážek v noci na pátek 21. června\" title=\"Přehled srážek v noci na pátek 21. června\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"fc0\" style=\"overflow: hidden; clear: both; float: none; line-height: 0; margin: 0px; padding: 0px; font-size: 0px; height: 0px;\"></div></div><div class=\"imagelist imagelist-sp5 not4bbtext\" style=\"zoom: 1; margin: 0px 0px 0.5em; font-size: small; font-family: Arial, Helvetica, sans-serif; background-color: rgb(225, 225, 225);\"><div class=\"cell cell-first\" style=\"float: left; margin-left: 22px; width: 172px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0336_mapa0002.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c0336_mapa0002.jpg\" width=\"172\" height=\"129\" alt=\"Přehled srážek v noci na pátek 21. června\" title=\"Přehled srážek v noci na pátek 21. června\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"cell\" style=\"float: left; width: 172px; margin-left: 22px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0337_mapa0003.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c0337_mapa0003.jpg\" width=\"172\" height=\"129\" alt=\"Přehled srážek v noci na pátek 21. června\" title=\"Přehled srážek v noci na pátek 21. června\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"cell\" style=\"float: left; width: 172px; margin-left: 22px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c0337_mapa0004.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c0337_mapa0004.jpg\" width=\"172\" height=\"129\" alt=\"Přehled srážek v noci na pátek 21. června\" title=\"Přehled srážek v noci na pátek 21. června\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"fc0\" style=\"overflow: hidden; clear: both; float: none; line-height: 0; margin: 0px; padding: 0px; font-size: 0px; height: 0px;\"></div><p style=\"margin: 0px; padding: 0.3em 0px 0px; clear: both; line-height: 1.33; font-size: 12px; color: rgb(102, 102, 102);\">Na mapách z ČHMÚ je vidět postup bouřek v noci na 21. června</p><div class=\"fc0\" style=\"overflow: hidden; clear: both; float: none; line-height: 0; margin: 0px; padding: 0px; font-size: 0px; height: 0px;\"></div></div><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">\"Druhá vlna přišla po půlnoci, trvala několik hodin a intenzita byla kolem 50 blesků za minutu. Kolem sedmé hodiny ráno, kdy byly bouřky&nbsp;na Liberecku, byla intenzita 60 blesků za minutu,\" řekl iDNES.cz mluvčí Českého hydrometeorologického ústavu Petr Dvořák.&nbsp;Za&nbsp;patnáct hodin, počínaje čtvrteční osmnáctou hodinou, zaznamenali meteorologové na území republiky 10 914 blesků.</p><h3 class=\"tit not4bbtext\" id=\"--padaly-kroupy-i-stromy\" style=\"margin: 0px; padding: 0px; font-size: 1em; font-weight: 400; font-family: Arial, Helvetica, sans-serif; line-height: 15px; background-color: rgb(225, 225, 225);\">Padaly kroupy i stromy</h3><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Už v podvečer bouřky silně zasáhly<b>Karlovarský kraj</b>.&nbsp;\"Ze všech okresů Karlovarského kraje máme hlášeny velmi silné bouřky. Evidujeme také nárazy větru a srážkové úhrny kolem 10&nbsp;mm, v ojedinělých případech dokonce 25 mm. Místy jsou srážky doprovázeny kroupami,\" uvedl pro iDNES.cz Josef Hanzlík z ČHMÚ.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Hasiči v Karlovarském kraji celkem vyjížděli k 75 událostem, odstraňovali padlé stromy, odčerpávali vodu i zachraňovali zvířata. Podle mluvčího krajských hasičů Martina Kasala největší problémy způsobily rozlité malé potoky nebo voda, která stekla z kopců.</p><table id=\"--konec-veder\" class=\"complete complete-half-r not4bbtext\" style=\"margin: 0px 0px 0.5em 10px; padding: 0px; font-size: 12px; position: relative; width: 192px; border: 0px; border-collapse: collapse; border-top-left-radius: 6px; border-top-right-radius: 6px; border-bottom-right-radius: 6px; border-bottom-left-radius: 6px; line-height: 15px; background-color: rgb(238, 238, 238); float: right; font-family: Arial, Helvetica, sans-serif;\"><tbody><tr><td style=\"margin: 0px; padding: 10px 10px 3px;\"><h3 style=\"margin: 0px 0px 0.1em; padding: 0px; font-size: 20px; color: rgb(185, 21, 28);\">Konec veder</h3><p class=\"title\" style=\"margin: 0px 0px 0.5em; padding: 0px; font-size: 16px; font-weight: 700; color: rgb(70, 70, 70);\">Bouřky přišly po vedrech, které přepisovaly rekordy</p><div><p style=\"margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;\"><b>PONDĚLÍ:&nbsp;</b><a href=\"http://zpravy.idnes.cz/padaji-rekordni-teploty-0wt-/domaci.aspx?c=A130617_163831_domaci_cen\" style=\"color: rgb(19, 55, 94);\">Začalo to třicítkami</a></p><p style=\"margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;\"><b>ÚTERÝ:</b>&nbsp;<a href=\"http://zpravy.idnes.cz/pocasi-vysoke-teploty-teplotni-rekordy-fd2-/domaci.aspx?c=A130618_150258_domaci_mzi\" style=\"color: rgb(19, 55, 94);\">Klementinum má nový rekord</a></p><p style=\"margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;\"><b>STŘEDA:&nbsp;</b><a href=\"http://zpravy.idnes.cz/teplotni-rekordy-klementinum-dkt-/domaci.aspx?c=A130619_165448_domaci_mzi\" style=\"color: rgb(19, 55, 94);\">Bylo 35,5 stupně</a></p><p style=\"margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;\"><b>ČTVRTEK:&nbsp;</b><a href=\"http://zpravy.idnes.cz/teplotni-rekordy-ve-ctvrtek-dg2-/domaci.aspx?c=A130620_165158_domaci_ert\" style=\"color: rgb(19, 55, 94);\">Ve stínu bylo až 38 stupňů</a></p><p style=\"margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;\"><b>PŘEDPOVĚĎ:&nbsp;</b><a href=\"http://zpravy.idnes.cz/predpoved-pocasi-pro-tyden-od-20-cervna-dxz-/domaci.aspx?c=A130620_074906_domaci_jpl\" style=\"color: rgb(19, 55, 94);\">Bude o 20 stupňů méně</a></p></div></td></tr></tbody></table><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">\"V první polovině noci, asi od 18:00 do 22:00, jsme jezdili hlavně ke spadaným stromům, které zablokovaly silnice. Později zase převažovalo odčerpávání z domů a sklepů,\" uvedl Kasal.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Nejhorší situace byla na Kynšpersku. V Lítově voda vyplavila halu firmy i penzion, v Nebanicích přívaly vod nezvládala kanalizace a voda vyhazovala kanálové poklopy.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">\"V Chotíkově a Liboci voda zaplavila návsi, bylo tam až metr vody,\" popsal Kasal. Hasiči tam museli na člunech zachraňovat domácí zvířata. \"Lidé se stihli dostat pryč, ale pro zvířata jsme museli na člunech. Asi polovinu se podařilo zachránit,\" uvedl Kasal.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Ve Stebnici u Lipové na Chebsku potok hrozil odříznutím dětského tábora. Hasiči proto celý tábor s 21 dětmi evakuovali do základní školy v Lipové. Krajem se okolo 4:45 přehnala druhá vlna bouře. Ačkoli ji také doprovázely blesky a silný déšť, větší škody už nenapáchala.&nbsp;</p><table id=\"--fotogalerie\" class=\"complete  not4bbtext\" style=\"margin: 0px 0px 1em; padding: 0px; font-size: 12px; position: relative; width: 560px; border: 0px; border-collapse: collapse; border-top-left-radius: 6px; border-top-right-radius: 6px; border-bottom-right-radius: 6px; border-bottom-left-radius: 6px; line-height: 15px; background-color: rgb(238, 238, 238); font-family: Arial, Helvetica, sans-serif;\"><tbody><tr><td style=\"margin: 0px; padding: 10px 10px 3px;\"><h3 style=\"margin: 0px 0px 0.1em; padding: 0px; font-size: 20px; color: rgb(185, 21, 28);\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert\" style=\"color: rgb(185, 21, 28); text-decoration: none;\">Fotogalerie</a></h3><div><div class=\"imagelist imagelist-sp5 not4bbtext\" style=\"zoom: 1; margin: 0px 0px 0.5em; font-size: small;\"><div class=\"cell cell-first\" style=\"float: left; margin-left: 12px; width: 172px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c02e8_20136DB3B3B4E18D88F4DCC94B5688BAFA2A.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c02e8_20136DB3B3B4E18D88F4DCC94B5688BAFA2A.jpg\" width=\"172\" height=\"129\" alt=\"Blesky v Lysé nad Labem\" title=\"Blesky v Lysé nad Labem\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"cell\" style=\"float: left; width: 172px; margin-left: 12px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c02e6_201326507345ED6283E28E1578036CFF5B21.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c02e6_201326507345ED6283E28E1578036CFF5B21.jpg\" width=\"172\" height=\"129\" alt=\"Blesky nad Olšanským náměstím v Praze\" title=\"Blesky nad Olšanským náměstím v Praze\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"cell\" style=\"float: left; width: 172px; margin-left: 12px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert&amp;foto=SKR4c033b_2013BC7B5356A24971D7B8DDAAA342CEC496.jpg\" style=\"color: rgb(19, 55, 94);\"><img src=\"http://oidnes.cz/13/063/sp5/SKR4c033b_2013BC7B5356A24971D7B8DDAAA342CEC496.jpg\" width=\"172\" height=\"129\" alt=\"Bouřka v Jiřetíně nad Jedlovou\" title=\"Bouřka v Jiřetíně nad Jedlovou\" style=\"border: 0px; vertical-align: middle;\"></a></div><div class=\"fc0\" style=\"overflow: hidden; clear: both; float: none; line-height: 0; margin: 0px; padding: 0px; font-size: 0px; height: 0px;\"></div></div><p style=\"margin: 0px 0px 0.5em; padding: 0px; font-size: 14px;\"><a href=\"http://zpravy.idnes.cz/foto.aspx?r=domaci&amp;c=A130620_185733_domaci_ert\" style=\"color: rgb(19, 55, 94);\">Zobrazit fotogalerii</a></p></div></td></tr></tbody></table><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Silná bouřka přešla také okolím<b>Rokycan v Plzeňském kraji</b>. Místní uvedli, že spolu s deštěm tam zaznamenali také kroupy o průměru až tři centimetry. Region pak hlásí také rozbité skleníky a poničené střechy několika automobilů. Přívaly vody zde naštěstí zatím nezpůsobily žádné bleskové povodně.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Bouřky&nbsp;směřovaly na severovýchod přes Chebsko, Sokolovsko a Karlovarsko. Velmi silné bouřky pak v noci zasáhly také Ústecký&nbsp;nebo Liberecký kraj.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Kolem půlnoci dorazila bouřka do Prahy a&nbsp;pak pokračovala přes Mělnicko, Mladoboleslavsko.<b>Středočeští</b>hasiči vyjížděli ke 40 výjezdům, blesk udeřil do domu na Kladensku a zapálil střechu.</p><h3 class=\"tit not4bbtext\" id=\"--voda-a-kroupy-zastavily-dopravu\" style=\"margin: 0px; padding: 0px; font-size: 1em; font-weight: 400; font-family: Arial, Helvetica, sans-serif; line-height: 15px; background-color: rgb(225, 225, 225);\">Voda a kroupy zastavily dopravu</h3><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Podle webu Českých drah musel být před 20. hodinou zastaven provoz na železnici v úseku mezi Tršnicí a Kynšperkem nad Ohří, kde byla podemletá trať a poškozené trakční vedení. Vlaky byly nahrazeny autobusovou dopravou, opravy by měly trvat asi dva dny.</p><div class=\"catchbox-l catchbox-vn not4bbtext\" style=\"position: relative; width: 192px; font-size: 12px; line-height: 15px; float: left; margin: 0px 15px 0.5em -70px; background-color: rgb(245, 245, 245); font-family: Arial, Helvetica, sans-serif;\"><div class=\"bg1\" style=\"background-image: url(http://gidnes.cz/u/n4/box-edge.png); background-position: initial initial; background-repeat: no-repeat no-repeat;\"><div class=\"bg2\" style=\"background-image: url(http://gidnes.cz/u/n4/box-edge.png); padding: 10px; background-position: -192px 100%; background-repeat: no-repeat no-repeat;\"><h3 style=\"margin: 0px 0px 0.6em; padding: 0px; font-size: 20px; color: rgb(185, 21, 28);\">Posílejte fotky a videa</h3><p style=\"margin: 0px 0px 1em; padding: 0px; font-size: 14px;\">Nafotili jste řádění bouřky?</p><p style=\"margin: 0px 0px 1em; padding: 0px; font-size: 14px;\"><b>Pošlete své záběry do rubriky&nbsp;<a href=\"http://zpravy.idnes.cz/ocima-ctenaru.asp\" style=\"color: rgb(19, 55, 94);\">Očima čtenářů</a>.</b></p><p class=\"f92\" style=\"margin: 0px 0px 1em; padding: 0px; font-size: 11px; color: rgb(102, 102, 102);\">Použité fotografie či videa odměníme honorářem. Podmínky použití fotografií a videozáznamů<a href=\"http://www.idnes.cz/pravidla\" style=\"color: rgb(19, 55, 94);\">najdete zde</a>.</p></div></div></div><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Večer spadly stromy na dvě regionální trati v Plzeňském kraji. Úseky z Kasejovic do Blatné a z Třemešné pod Přimdou do Bělé nad Radbuzou se podařilo po půlnoci zprovoznit.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Stromy padaly i na silnice, kolem půlnoci to bylo zejména na Lounsku, Chomutovsku, Kladensku a Českolipsku, vyplývá z informací policejního dopravního webu.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Na křižovatce u Horšovského Týna na Domažlicku silnici zaplavil proud vody o výšce asi 30 centimetrů, v Odravě na Chebsku musel být uzavřen zaplavený podjezd pod rychlostní silnicí R5. Na Sokolovsku voda zaplavila vozovku u obcí Dolní Nivy a Jindřichovice, u Kaceřova se na silnici sesunul svah.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Některé silnice jsou kvůli popadaným stromům neprůjezdné i ráno.&nbsp;Například na Lounsku silnice&nbsp;239&nbsp;mezi Slavětínem a Perucí nebo silnice 227 u obce Holedec.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Podle Radiožurnálu hasiči na Žatecku a Děčínsku vyjížděli k popadaným stromům, poškozeným střechám a uvolněné krytině. Kvůli silnému dešti musel být evakuován kemp v Lipové u Chebu. Místní potok se rozlil a hrozilo zatopení chatek.</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Vlna bouřek by měla udělat definitivní tečku za extrémními horky, které sužovaly Česko v uplynulých dnech. Na mnoha místech ve čtvrtek padaly i stoleté teplotní rekordy (<a href=\"http://zpravy.idnes.cz/teplotni-rekordy-ve-ctvrtek-dg2-/domaci.aspx?c=A130620_165158_domaci_ert\" style=\"color: rgb(19, 55, 94);\">více o nich čtěte zde</a>).</p><p style=\"margin: 0px; padding: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 15px; background-color: rgb(225, 225, 225);\">Zdroj:<a href=\"http://zpravy.idnes.cz/bourky-v-cesku-0pl-/domaci.aspx?c=A130620_185733_domaci_ert\" style=\"color: rgb(19, 55, 94);\">http://zpravy.idnes.cz/bourky-v-cesku-0pl-/domaci.aspx?c=A130620_185733_domaci_ert</a></p>',	1),
(2,	'About us',	'<p style=\"text-align: left; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut purus feugiat, tincidunt nibh nec, faucibus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed pulvinar risus quis diam tempor volutpat quis non lacus. Donec ullamcorper pellentesque lacinia. Integer lobortis tortor nec elit adipiscing suscipit. Nulla nec dolor ac lacus facilisis mollis at eu risus. Etiam ac congue mi. Nam nec malesuada lorem, a interdum risus. Aliquam at tellus ac nulla sodales&nbsp;<img src=\"http://localhost/platon/www/images/logo/birne_logo_web.png\" alt=\"Birneshop DEMO\" title=\"Birneshop DEMO\"><span style=\"font-size: 11px; line-height: 14px;\">ultricies eu c</span></p><hr><p style=\"text-align: left; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\"><span style=\"font-size: 11px; line-height: 14px;\">onvallis n</span><span style=\"font-size: 11px; line-height: 14px;\">isi. Quisque et magna bibendum, cursus purus suscipit, pellentesque libero. Proin ac lacinia augue, aliquet dictum metus. Nam non felis lectus. Donec a sapien eu nunc vestibulum varius vitae ac odio. Vestibulum fermentum sem ut dui blandit consequat.</span></p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Fusce placerat nulla tortor, id gravida lectus hendrerit ac. Sed mattis ultrices ornare. Nulla urna turpis, mattis vitae lectus nec, blandit consequat orci. Nullam mi neque, sodales vulputate venenatis rhoncus, rhoncus sit amet dui. Fusce et diam euismod, blandit metus eget, ornare purus. Nunc ut erat a justo consectetur accumsan vel et metus. Donec in porttitor nisl. Maecenas felis dolor, euismod nec gravida nec, hendrerit vel ligula. Ut dictum, diam ac sagittis commodo, quam metus volutpat ante, eget fringilla dolor massa a ligula. Aliquam sit amet euismod dui, vitae egestas elit. Donec scelerisque viverra condimentum. Aliquam felis ligula, tempus quis laoreet non, fringilla eget urna. Nulla at magna laoreet, viverra diam eget, mollis nisi.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Suspendisse eros felis, tincidunt ac congue at, scelerisque a lectus. Etiam sollicitudin magna ac sem faucibus mollis. Morbi viverra cursus elit nec luctus. Phasellus eget metus eu est tincidunt varius. Aliquam ullamcorper enim malesuada orci consectetur, vel ultricies massa facilisis. Quisque mi odio, blandit sed nisl non, dapibus ornare nibh. Integer ut erat sit amet urna&nbsp;<img src=\"http://localhost/platon/www/images/logo/birne_logo_web.png\" alt=\"Birneshop DEMO\" title=\"Birneshop DEMO\"><span style=\"font-size: 11px; line-height: 14px;\">accumsan porta ac non sapien. Vestibulum tellus eros, fermentum a rutrum non, semper a felis. Praesent venenatis lorem tincidunt, laoreet metus eget, dapibus ligula. Sed convallis sapien vel vestibulum semper. Cras rhoncus elementum sapien, vel porta dolor tristique at. Curabitur gravida id urna sit amet lobortis. Donec quis risus sapien.</span></p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">In mollis mauris ut massa fringilla, pretium tempor lorem imperdiet. Curabitur vel risus nulla. Aenean rutrum justo non laoreet vulputate. Etiam mattis laoreet lacus vitae venenatis. Cras varius pellentesque varius. Suspendisse potenti. Nunc eu erat sit amet lacus consequat faucibus quis in orci. Etiam cursus varius ipsum, vitae adipiscing risus egestas nec. Integer id convallis leo, iaculis bibendum tellus. Nam consectetur nibh gravida placerat bibendum.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Suspendisse pellentesque ut orci eget porttitor. Curabitur placerat odio et mauris consectetur elementum. Aliquam dictum eros id ultricies iaculis. Donec non orci facilisis, interdum nisi vel, imperdiet justo. Nam imperdiet diam eget quam vehicula, a mattis orci scelerisque. Morbi tincidunt molestie enim non malesuada. Aliquam scelerisque mi non mauris dictum viverra. In tincidunt, ante ac hendrerit scelerisque, odio quam suscipit nulla, sed tempor nisl purus at nisl. Ut blandit eu erat sed facilisis. Sed facilisis id ipsum ac placerat. Vivamus eu arcu tempus, vulputate massa vel, dignissim estLorem ipsum dolor sit amet, consectetur adipiscing elit. In ut purus feugiat, tincidunt nibh nec, faucibus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed pulvinar risus quis diam tempor volutpat quis non lacus. Donec ullamcorper pellentesque lacinia. Integer lobortis tortor nec elit adipiscing suscipit. Nulla nec dolor ac lacus facilisis mollis at eu risus. Etiam ac congue mi. Nam nec malesuada lorem, a interdum risus. Aliquam at tellus ac nulla sodales ultricies eu convallis nisi. Quisque et magna bibendum, cursus purus suscipit, pellentesque libero. Proin ac lacinia augue, aliquet dictum metus. Nam non felis lectus. Donec a sapien eu nunc vestibulum varius vitae ac odio. Vestibulum fermentum sem ut dui blandit consequat.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Fusce placerat nulla tortor, id gravida lectus hendrerit ac. Sed mattis ultrices ornare. Nulla urna turpis, mattis vitae lectus nec, blandit consequat orci. Nullam mi neque, sodales vulputate venenatis rhoncus, rhoncus sit amet dui. Fusce et diam euismod, blandit metus eget, ornare purus. Nunc ut erat a justo consectetur accumsan vel et metus. Donec in porttitor nisl. Maecenas felis dolor, euismod nec gravida nec, hendrerit vel ligula. Ut dictum, diam ac sagittis commodo, quam metus volutpat ante, eget fringilla dolor massa a ligula. Aliquam sit amet euismod dui, vitae egestas elit. Donec scelerisque viverra condimentum. Aliquam felis ligula, tempus quis laoreet non, fringilla eget urna. Nulla at magna laoreet, viverra diam eget, mollis nisi.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Suspendisse eros felis, tincidunt ac congue at, scelerisque a lectus. Etiam sollicitudin magna ac sem faucibus mollis. Morbi viverra cursus elit nec luctus. Phasellus eget metus eu est tincidunt varius. Aliquam ullamcorper enim malesuada orci consectetur, vel ultricies massa facilisis. Quisque mi odio, blandit sed nisl non, dapibus ornare nibh. Integer ut erat sit amet urna accumsan porta ac non sapien. Vestibulum tellus eros, fermentum a rutrum non, semper a felis. Praesent venenatis lorem tincidunt, laoreet metus eget, dapibus ligula. Sed convallis sapien vel vestibulum semper. Cras rhoncus elementum sapien, vel porta dolor tristique at. Curabitur gravida id urna sit amet lobortis. Donec quis risus sapien.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">In mollis mauris ut massa fringilla, pretium tempor lorem imperdiet. Curabitur vel risus nulla. Aenean rutrum justo non laoreet vulputate. Etiam mattis laoreet lacus vitae venenatis. Cras varius pellentesque varius. Suspendisse potenti. Nunc eu erat sit amet lacus consequat faucibus quis in orci. Etiam cursus varius ipsum, vitae adipiscing risus egestas nec. Integer id convallis leo, iaculis bibendum tellus. Nam consectetur nibh gravida placerat bibendum.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Suspendisse pellentesque ut orci eget porttitor. Curabitur placerat odio et mauris consectetur elementum. Aliquam dictum eros id ultricies iaculis. Donec non orci facilisis, interdum nisi vel, imperdiet justo. Nam imperdiet diam eget quam vehicula, a mattis orci scelerisque. Morbi tincidunt molestie enim non malesuada. Aliquam scelerisque mi non mauris dictum viverra. In tincidunt, ante ac hendrerit scelerisque, odio quam suscipit nulla, sed tempor nisl purus at nisl. Ut blandit eu erat sed facilisis. Sed facilisis id ipsum ac placerat. Vivamus eu arcu tempus, vulputate massa vel, dignissim estLorem ipsum dolor sit amet, consectetur adipiscing elit. In ut purus feugiat, tincidunt nibh nec, faucibus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed pulvinar risus quis diam tempor volutpat quis non lacus. Donec ullamcorper pellentesque lacinia. Integer lobortis tortor nec elit adipiscing suscipit. Nulla nec dolor ac lacus facilisis mollis at eu risus. Etiam ac congue mi. Nam nec malesuada lorem, a interdum risus. Aliquam at tellus ac nulla sodales ultricies eu convallis nisi. Quisque et magna bibendum, cursus purus suscipit, pellentesque libero. Proin ac lacinia augue, aliquet dictum metus. Nam non felis lectus. Donec a sapien eu nunc vestibulum varius vitae ac odio. Vestibulum fermentum sem ut dui blandit consequat.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Fusce placerat nulla tortor, id gravida lectus hendrerit ac. Sed mattis ultrices ornare. Nulla urna turpis, mattis vitae lectus nec, blandit consequat orci. Nullam mi neque, sodales vulputate venenatis rhoncus, rhoncus sit amet dui. Fusce et diam euismod, blandit metus eget, ornare purus. Nunc ut erat a justo consectetur accumsan vel et metus. Donec in porttitor nisl. Maecenas felis dolor, euismod nec gravida nec, hendrerit vel ligula. Ut dictum, diam ac sagittis commodo, quam metus volutpat ante, eget fringilla dolor massa a ligula. Aliquam sit amet euismod dui, vitae egestas elit. Donec scelerisque viverra condimentum. Aliquam felis ligula, tempus quis laoreet non, fringilla eget urna. Nulla at magna laoreet, viverra diam eget, mollis nisi.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Suspendisse eros felis, tincidunt ac congue at, scelerisque a lectus. Etiam sollicitudin magna ac sem faucibus mollis. Morbi viverra cursus elit nec luctus. Phasellus eget metus eu est tincidunt varius. Aliquam ullamcorper enim malesuada orci consectetur, vel ultricies massa facilisis. Quisque mi odio, blandit sed nisl non, dapibus ornare nibh. Integer ut erat sit amet urna accumsan porta ac non sapien. Vestibulum tellus eros, fermentum a rutrum non, semper a felis. Praesent venenatis lorem tincidunt, laoreet metus eget, dapibus ligula. Sed convallis sapien vel vestibulum semper. Cras rhoncus elementum sapien, vel porta dolor tristique at. Curabitur gravida id urna sit amet lobortis. Donec quis risus sapien.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">In mollis mauris ut massa fringilla, pretium tempor lorem imperdiet. Curabitur vel risus nulla. Aenean rutrum justo non laoreet vulputate. Etiam mattis laoreet lacus vitae venenatis. Cras varius pellentesque varius. Suspendisse potenti. Nunc eu erat sit amet lacus consequat faucibus quis in orci. Etiam cursus varius ipsum, vitae adipiscing risus egestas nec. Integer id convallis leo, iaculis bibendum tellus. Nam consectetur nibh gravida placerat bibendum.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Suspendisse pellentesque ut orci eget porttitor. Curabitur placerat odio et mauris consectetur elementum. Aliquam dictum eros id ultricies iaculis. Donec non orci facilisis, interdum nisi vel, imperdiet justo. Nam imperdiet diam eget quam vehicula, a mattis orci scelerisque. Morbi tincidunt molestie enim non malesuada. Aliquam scelerisque mi non mauris dictum viverra. In tincidunt, ante ac hendrerit scelerisque, odio quam suscipit nulla, sed tempor nisl purus at nisl. Ut blandit eu erat sed facilisis. Sed facilisis id ipsum ac placerat. Vivamus eu arcu tempus, vulputate massa vel, dignissim estLorem ipsum dolor sit amet, consectetur adipiscing elit. In ut purus feugiat, tincidunt nibh nec, faucibus massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Sed pulvinar risus quis diam tempor volutpat quis non lacus. Donec ullamcorper pellentesque lacinia. Integer lobortis tortor nec elit adipiscing suscipit. Nulla nec dolor ac lacus facilisis mollis at eu risus. Etiam ac congue mi. Nam nec malesuada lorem, a interdum risus. Aliquam at tellus ac nulla sodales ultricies eu convallis nisi. Quisque et magna bibendum, cursus purus suscipit, pellentesque libero. Proin ac lacinia augue, aliquet dictum metus. Nam non felis lectus. Donec a sapien eu nunc vestibulum varius vitae ac odio. Vestibulum fermentum sem ut dui blandit consequat.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Fusce placerat nulla tortor, id gravida lectus hendrerit ac. Sed mattis ultrices ornare. Nulla urna turpis, mattis vitae lectus nec, blandit consequat orci. Nullam mi neque, sodales vulputate venenatis rhoncus, rhoncus sit amet dui. Fusce et diam euismod, blandit metus eget, ornare purus. Nunc ut erat a justo consectetur accumsan vel et metus. Donec in porttitor nisl. Maecenas felis dolor, euismod nec gravida nec, hendrerit vel ligula. Ut dictum, diam ac sagittis commodo, quam metus volutpat ante, eget fringilla dolor massa a ligula. Aliquam sit amet euismod dui, vitae egestas elit. Donec scelerisque viverra condimentum. Aliquam felis ligula, tempus quis laoreet non, fringilla eget urna. Nulla at magna laoreet, viverra diam eget, mollis nisi.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Suspendisse eros felis, tincidunt ac congue at, scelerisque a lectus. Etiam sollicitudin magna ac sem faucibus mollis. Morbi viverra cursus elit nec luctus. Phasellus eget metus eu est tincidunt varius. Aliquam ullamcorper enim malesuada orci consectetur, vel ultricies massa facilisis. Quisque mi odio, blandit sed nisl non, dapibus ornare nibh. Integer ut erat sit amet urna accumsan porta ac non sapien. Vestibulum tellus eros, fermentum a rutrum non, semper a felis. Praesent venenatis lorem tincidunt, laoreet metus eget, dapibus ligula. Sed convallis sapien vel vestibulum semper. Cras rhoncus elementum sapien, vel porta dolor tristique at. Curabitur gravida id urna sit amet lobortis. Donec quis risus sapien.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">In mollis mauris ut massa fringilla, pretium tempor lorem imperdiet. Curabitur vel risus nulla. Aenean rutrum justo non laoreet vulputate. Etiam mattis laoreet lacus vitae venenatis. Cras varius pellentesque varius. Suspendisse potenti. Nunc eu erat sit amet lacus consequat faucibus quis in orci. Etiam cursus varius ipsum, vitae adipiscing risus egestas nec. Integer id convallis leo, iaculis bibendum tellus. Nam consectetur nibh gravida placerat bibendum.</p><p style=\"text-align: justify; font-size: 11px; line-height: 14px; margin: 0px 0px 14px; padding: 0px; font-family: Arial, Helvetica, sans;\">Suspendisse pellentesque ut orci eget porttitor. Curabitur placerat odio et mauris consectetur elementum. Aliquam dictum eros id ultricies iaculis. Donec non orci facilisis, interdum nisi vel, imperdiet justo. Nam imperdiet diam eget quam vehicula, a mattis orci scelerisque. Morbi tincidunt molestie enim non malesuada. Aliquam scelerisque mi non mauris dictum viverra. In tincidunt, ante ac hendrerit scelerisque, odio quam suscipit nulla, sed tempor nisl purus at nisl. Ut blandit eu erat sed facilisis. Sed facilisis id ipsum ac placerat. Vivamus eu arcu tempus, vulputate massa vel, dignissim est</p>',	1),
(3,	'or connect with us on social networks',	'<h2>\r\n        Wau, this could be <b>awesome contact text!\r\n        </b></h2>',	2),
(4,	'super text',	'',	2),
(5,	'New text',	'<br>',	2);

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `StatusID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusName` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`StatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `status` (`StatusID`, `StatusName`) VALUES
(1,	'Active'),
(2,	'Non-Active'),
(3,	'Archived');

DROP TABLE IF EXISTS `unit`;
CREATE TABLE `unit` (
  `UnitID` int(11) NOT NULL AUTO_INCREMENT,
  `UnitShort` varchar(50) NOT NULL,
  `UnitName` varchar(255) NOT NULL,
  PRIMARY KEY (`UnitID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `unit` (`UnitID`, `UnitShort`, `UnitName`) VALUES
(1,	' ',	' '),
(2,	'g',	'grams'),
(3,	'mAh',	'miliamperhours'),
(4,	'px',	'pixels'),
(5,	'\"',	'inches');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `UsersID` varchar(60) NOT NULL,
  `Password` varchar(60) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `PhoneNumber` int(11) DEFAULT NULL,
  `CompanyName` varchar(45) DEFAULT NULL,
  `TIN` varchar(45) DEFAULT NULL,
  `Permission` varchar(6) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`UsersID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`UsersID`, `Password`, `Name`, `PhoneNumber`, `CompanyName`, `TIN`, `Permission`) VALUES
('1234@1234.com',	'',	'1234',	0,	'',	'',	'user'),
('12389@1234.xxx',	NULL,	'XXX',	0,	NULL,	NULL,	'user'),
('aaa@aaa.aaa',	'',	'vivo',	1120120,	'',	'',	'user'),
('aaaa@aaa.aaa',	'',	'AAA',	0,	'',	'',	'user'),
('aaaaa@aaa.aaa',	'',	'AAA',	0,	'',	'',	'user'),
('aaaaaa@aaa.aaa',	'',	'AAA',	0,	'',	'',	'user'),
('aaaaaaa@aaa.aaa',	'',	'AAA',	0,	'',	'',	'user'),
('admin@admin.com',	'$2a$07$$$$$$$$$$$$$$$$$$$$$$.Yqgs4IC6X2x/em6hm8RFU1wBrsCvoAi',	'Lukkk',	0,	'0',	'0',	'admin'),
('ads@fgsw.sdg',	'',	'asdzx',	0,	'',	'',	'user'),
('ads@fgw.sdg',	'',	'asdzx',	0,	'',	'',	'user'),
('afaasasdsasds2134@asfew213.aff',	'',	'asd',	0,	'',	'',	'user'),
('afaasasdsasds@asfew.aff',	'',	'asd',	0,	'',	'',	'user'),
('afaasasdsasds@asfew213.aff',	'',	'asd',	0,	'',	'',	'user'),
('afaasasdss@asfew.aff',	'',	'asd',	0,	'',	'',	'user'),
('afaasss@asfew.aff',	'',	'asd',	0,	'',	'',	'user'),
('afas@asf.aff',	'',	'asd',	0,	'',	'',	'user'),
('afas@asfew.aff',	'',	'asd',	0,	'',	'',	'user'),
('afass@asfew.aff',	'',	'asd',	0,	'',	'',	'user'),
('as@asd.ca',	NULL,	'ASA',	76181812,	NULL,	NULL,	'user'),
('asd@asd.ads',	'',	'asd',	0,	'',	'',	'user'),
('asd@asda.sa',	NULL,	'AS',	191328,	NULL,	NULL,	'user'),
('asd@asfdg.dfg',	'',	'sdfghjk',	0,	'',	'',	'user'),
('asd@qwe.wqe',	NULL,	'AAA',	123,	NULL,	NULL,	'user'),
('asda@asd.ads',	'',	'asd',	0,	'',	'',	'user'),
('asus@vivo.boo',	NULL,	'Vivo2',	1920129,	NULL,	NULL,	'user'),
('hddd@dsd.cdd',	'',	'Petr',	0,	'',	'',	'user'),
('jan.novak@company.com',	'novak',	'Jan',	999888777,	'Company',	'819281293',	'0'),
('kifa@mail.com',	'$2a$07$$$$$$$$$$$$$$$$$$$$$$.Y',	'Kifa',	NULL,	'',	'',	'admin'),
('michal@prosek.cz',	NULL,	'Michal Prošáků',	0,	NULL,	NULL,	'user'),
('pepa@depa.zz',	NULL,	'Pepa Z Depa',	71812912,	NULL,	NULL,	'user'),
('petan@petr.com',	'',	'Petr',	0,	'',	'',	'user'),
('ppp@ppp.ppp',	'',	'09u7',	0,	'',	'',	'user'),
('ppp@pppa.ppp',	'',	'09u7',	0,	'',	'',	'user'),
('qwe@qrw.as',	'',	'qwe',	0,	'',	'',	'user'),
('qwe@qwe.qwe',	NULL,	'QWE',	9,	NULL,	NULL,	'user'),
('rte@gddf.fgd',	'',	'retr4et',	743,	'',	'',	'user'),
('rte@gdsaasasasasdaassf.fgd',	'',	'retr4et',	743,	'',	'',	'user'),
('rte@gdsaasasasdaassf.fgd',	'',	'retr4et',	743,	'',	'',	'user'),
('rte@gdsaasasdaassf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('rte@gdsaasasdssf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('rte@gdsaassdssf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('rte@gdsassdssf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('rte@gdsdf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('rte@gdsdsf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('rte@gdssdsf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('rte@gdssdssf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('rte@gdsssdssf.fgd',	NULL,	'retr4et',	743,	NULL,	NULL,	'user'),
('tdanek@atlas.cz',	NULL,	'Karel',	0,	NULL,	NULL,	'user'),
('testovaci@subjekt.cz',	'$2a$07$67p8256pml1lrn1a8d986eN',	'Testovaci',	777888999,	'0',	'0',	'0'),
('thinki@mail.com',	NULL,	'thinki',	1929912,	NULL,	NULL,	'user'),
('tomik@tomas.com',	'$2a$07$xshgrgluo88ug5qvohjvme0',	'Tomas',	NULL,	NULL,	NULL,	'0'),
('xxx@xxx.xxx',	NULL,	'testx',	21313123,	NULL,	NULL,	'user'),
('yetty@himalaja.tib',	NULL,	'Yetty',	0,	NULL,	NULL,	'user');

-- 2013-07-26 14:22:45
