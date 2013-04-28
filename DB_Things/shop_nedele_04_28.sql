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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

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
(66,	'michal@prosek.cz',	'This',	0,	'is',	NULL)
ON DUPLICATE KEY UPDATE `AddressID` = VALUES(`AddressID`), `UsersID` = VALUES(`UsersID`), `Street` = VALUES(`Street`), `ZIPCode` = VALUES(`ZIPCode`), `City` = VALUES(`City`), `State` = VALUES(`State`);

DROP TABLE IF EXISTS `attrib`;
CREATE TABLE `attrib` (
  `AttribID` int(11) NOT NULL AUTO_INCREMENT,
  `AttribName` varchar(255) NOT NULL,
  PRIMARY KEY (`AttribID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `attrib` (`AttribID`, `AttribName`) VALUES
(1,	'Weight'),
(2,	'Display size'),
(3,	'Display resolution'),
(4,	'Battery')
ON DUPLICATE KEY UPDATE `AttribID` = VALUES(`AttribID`), `AttribName` = VALUES(`AttribName`);

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(45) DEFAULT NULL,
  `CategoryDescription` longtext,
  `HigherCategoryID` int(11) DEFAULT NULL,
  PRIMARY KEY (`CategoryID`),
  KEY `HigherCategoryID_idx` (`HigherCategoryID`),
  CONSTRAINT `FKHigherCategory` FOREIGN KEY (`HigherCategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `category` (`CategoryID`, `CategoryName`, `CategoryDescription`, `HigherCategoryID`) VALUES
(1,	'Cellphone',	'<p>A mobile phone (also known as a cellular phone, cell phone, and a hand phone) is a device that can make and receive telephone calls over a radio link while moving around a wide geographic area. It does so by connecting to a cellular network provided by a mobile phone operator, allowing access to the public telephone network. By contrast, a cordless telephone is used only within the short range of a single, private base station. In addition to telephony, modern mobile phones also support a wide variety of other services such as text messaging, MMS, email, Internet access, short-range wireless communications (infrared, Bluetooth), business applications, gaming and photography. Mobile phones that offer these and more general computing capabilities are referred to as smartphones.</p>',	NULL),
(2,	'Notebook',	'<p>A laptop computer is a personal computer for mobile use.[1] A laptop has most of the same components as a desktop computer, inclu<strong>ding a display, a keyboard, a pointing device such as a touchpad (also k</strong>nown as a trackpad) and/or a pointing stick, and speakers into a single unit. A laptop is powered by mains electricity via an AC adapter, and can be used away from an outlet using a rechargeable battery. Laptops are also sometimes called notebook computers, notebooks, ultrabooks[2] or netbooks.&nbsp;</p>',	4),
(3,	'Smartphones-',	'<p>A smartphone is a mobile phone built on a mobile operating system, with more advanced computing capability connectivity than a feature phone. The first smartphones combined the functions of a personal digital assistant (PDA) with a mobile phone. Later models added the functionality of portable media players, low-end compact digital cameras, pocket video cameras, and GPS navigation units to form one multi-use device. Many modern smartphones also include high-resolution touchscreens and web browsers that display standard web pages as well as mobile-optimized sites.</p>',	2),
(4,	'Tablets',	'<p>A tablet computer, or simply tablet, is a one-piece mobile computer. Devices typically offer a touchscreen, with finger (or stylus) gestures acting as the primary means of control, though often supplemented by the use of one or more physical context sensitive buttons or the input from one or more accelerometers; an on-screen, hideable virtual keyboard is generally offered as the principal means of data input. Available in a variety of sizes, tablets customarily offer a screen diagonal greater than 7 inches (18 cm), differentiating themselves through size from functionally similar smart phones or personal digital assistants.</p>',	1)
ON DUPLICATE KEY UPDATE `CategoryID` = VALUES(`CategoryID`), `CategoryName` = VALUES(`CategoryName`), `CategoryDescription` = VALUES(`CategoryDescription`), `HigherCategoryID` = VALUES(`HigherCategoryID`);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `comment` (`CommentID`, `CommentTittle`, `CommentContent`, `DateOfAdded`, `UserID`, `PreviousCommentID`) VALUES
(1,	'First Comment',	'This is first comment ever',	'2012-03-20',	1,	NULL),
(2,	'Reakce na koment',	'Pi? ?esky pitomo',	'2012-03-20',	2,	1)
ON DUPLICATE KEY UPDATE `CommentID` = VALUES(`CommentID`), `CommentTittle` = VALUES(`CommentTittle`), `CommentContent` = VALUES(`CommentContent`), `DateOfAdded` = VALUES(`DateOfAdded`), `UserID` = VALUES(`UserID`), `PreviousCommentID` = VALUES(`PreviousCommentID`);

DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency` (
  `CurrencyID` int(11) NOT NULL AUTO_INCREMENT,
  `CurrencyCode` varchar(3) DEFAULT NULL,
  `CurrencyName` varchar(45) DEFAULT NULL,
  `CurrencyRate` float DEFAULT NULL,
  PRIMARY KEY (`CurrencyID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `currency` (`CurrencyID`, `CurrencyCode`, `CurrencyName`, `CurrencyRate`) VALUES
(1,	'CZK',	'Koruna ?esk?',	NULL),
(2,	'EUR',	'Euro',	NULL)
ON DUPLICATE KEY UPDATE `CurrencyID` = VALUES(`CurrencyID`), `CurrencyCode` = VALUES(`CurrencyCode`), `CurrencyName` = VALUES(`CurrencyName`), `CurrencyRate` = VALUES(`CurrencyRate`);

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE `delivery` (
  `DeliveryID` int(11) NOT NULL AUTO_INCREMENT,
  `DeliveryName` varchar(45) DEFAULT NULL,
  `DeliveryDescription` varchar(45) DEFAULT NULL,
  `DeliveryPrice` float DEFAULT NULL,
  `FreeFromPrice` float DEFAULT NULL,
  PRIMARY KEY (`DeliveryID`),
  KEY `PriceID_idx` (`DeliveryPrice`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `delivery` (`DeliveryID`, `DeliveryName`, `DeliveryDescription`, `DeliveryPrice`, `FreeFromPrice`) VALUES
(1,	'Personal pick up',	'Personal in the shop',	0,	NULL),
(2,	'Czech postal service',	'Send by transport company',	99,	1000),
(3,	'DPD',	'Curier express shipping!',	160,	10000)
ON DUPLICATE KEY UPDATE `DeliveryID` = VALUES(`DeliveryID`), `DeliveryName` = VALUES(`DeliveryName`), `DeliveryDescription` = VALUES(`DeliveryDescription`), `DeliveryPrice` = VALUES(`DeliveryPrice`), `FreeFromPrice` = VALUES(`FreeFromPrice`);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `documentation` (`DocumentID`, `DocumentName`, `DocumentDescription`, `DocumentURL`, `ProductID`) VALUES
(3,	'User Guide',	'Super User',	'vizitka_joo (1).pdf',	4)
ON DUPLICATE KEY UPDATE `DocumentID` = VALUES(`DocumentID`), `DocumentName` = VALUES(`DocumentName`), `DocumentDescription` = VALUES(`DocumentDescription`), `DocumentURL` = VALUES(`DocumentURL`), `ProductID` = VALUES(`ProductID`);

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

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
(25,	20,	6,	1,	11999),
(29,	17,	6,	1,	11999)
ON DUPLICATE KEY UPDATE `OrderDetailsID` = VALUES(`OrderDetailsID`), `OrderID` = VALUES(`OrderID`), `ProductID` = VALUES(`ProductID`), `Quantity` = VALUES(`Quantity`), `UnitPrice` = VALUES(`UnitPrice`);

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusID` int(11) DEFAULT '1',
  `UsersID` varchar(60) DEFAULT NULL,
  `ProductsPrice` float DEFAULT NULL,
  `DeliveryPaymentPrice` float DEFAULT NULL,
  `TaxPrice` float DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

INSERT INTO `orders` (`OrderID`, `StatusID`, `UsersID`, `ProductsPrice`, `DeliveryPaymentPrice`, `TaxPrice`, `TotalPrice`, `DateCreated`, `DateOfLastChange`, `DateFinished`, `DeliveryID`, `PaymentID`, `Note`, `IP`, `SessionID`) VALUES
(1,	1,	'aaaaaaa@aaa.aaa',	9098,	NULL,	90.98,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	2,	1,	NULL,	NULL,	NULL),
(2,	1,	'qwe@qrw.as',	99,	NULL,	0.99,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	2,	NULL,	NULL,	NULL,	NULL),
(3,	1,	'1234@1234.com',	11999,	NULL,	119.99,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	1,	NULL,	NULL,	NULL),
(4,	1,	'ppp@pppa.ppp',	15999,	NULL,	0,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	1,	NULL,	NULL,	NULL),
(5,	1,	'afaasasdsasds2134@asfew213.aff',	11999,	NULL,	2519.79,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	NULL,	NULL,	NULL,	NULL),
(6,	1,	'tdanek@atlas.cz',	32998,	NULL,	0,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	NULL,	NULL,	NULL,	NULL),
(7,	1,	'asda@asd.ads',	9098,	NULL,	0,	NULL,	'2013-04-07',	'2013-04-07',	NULL,	1,	1,	NULL,	NULL,	NULL),
(8,	1,	'rte@gdsaasasasasdaassf.fgd',	11999,	100,	2519.79,	14518.8,	'2013-04-07',	'2013-04-07',	NULL,	2,	NULL,	NULL,	NULL,	NULL),
(9,	2,	'asd@asfdg.dfg',	28998,	150,	6089.58,	35087.6,	'2013-04-07',	'2013-04-07',	NULL,	2,	1,	NULL,	NULL,	NULL),
(17,	1,	'12389@1234.xxx',	33997,	0,	2309.79,	11999,	'2013-04-17',	'2013-04-17',	NULL,	1,	1,	'Note',	NULL,	NULL),
(18,	2,	'admin@admin.com',	10998,	0,	2309,	13307,	'2013-04-17',	'2013-04-17',	NULL,	1,	1,	'wfevergverbr',	NULL,	NULL),
(19,	1,	'michal@prosek.cz',	5999,	149,	1259.79,	7258.79,	'2013-04-24',	'2013-04-24',	NULL,	1,	3,	'Chci to hned!',	NULL,	NULL),
(20,	2,	'michal@prosek.cz',	89994,	160,	2519.58,	72156,	'2013-04-24',	'2013-04-24',	NULL,	3,	1,	'',	NULL,	NULL)
ON DUPLICATE KEY UPDATE `OrderID` = VALUES(`OrderID`), `StatusID` = VALUES(`StatusID`), `UsersID` = VALUES(`UsersID`), `ProductsPrice` = VALUES(`ProductsPrice`), `DeliveryPaymentPrice` = VALUES(`DeliveryPaymentPrice`), `TaxPrice` = VALUES(`TaxPrice`), `TotalPrice` = VALUES(`TotalPrice`), `DateCreated` = VALUES(`DateCreated`), `DateOfLastChange` = VALUES(`DateOfLastChange`), `DateFinished` = VALUES(`DateFinished`), `DeliveryID` = VALUES(`DeliveryID`), `PaymentID` = VALUES(`PaymentID`), `Note` = VALUES(`Note`), `IP` = VALUES(`IP`), `SessionID` = VALUES(`SessionID`);

DROP TABLE IF EXISTS `orderstatus`;
CREATE TABLE `orderstatus` (
  `OrderStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusName` varchar(45) DEFAULT NULL,
  `StatusDescription` varchar(255) DEFAULT NULL,
  `StatusProgress` int(11) DEFAULT '0',
  PRIMARY KEY (`OrderStatusID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `orderstatus` (`OrderStatusID`, `StatusName`, `StatusDescription`, `StatusProgress`) VALUES
(0,	'CANCELED',	'order has been canceled',	0),
(1,	'Pending',	'Pending order',	1),
(2,	'Sending',	'sending order',	5),
(3,	'Done',	'done',	10)
ON DUPLICATE KEY UPDATE `OrderStatusID` = VALUES(`OrderStatusID`), `StatusName` = VALUES(`StatusName`), `StatusDescription` = VALUES(`StatusDescription`), `StatusProgress` = VALUES(`StatusProgress`);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `parameters` (`ParameterID`, `ProductID`, `AttribID`, `Val`, `UnitID`) VALUES
(1,	1,	1,	'169',	2),
(2,	1,	2,	'4,8',	5),
(3,	1,	3,	'1878x900',	4),
(4,	1,	4,	'1840',	3)
ON DUPLICATE KEY UPDATE `ParameterID` = VALUES(`ParameterID`), `ProductID` = VALUES(`ProductID`), `AttribID` = VALUES(`AttribID`), `Val` = VALUES(`Val`), `UnitID` = VALUES(`UnitID`);

DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `PaymentName` varchar(45) DEFAULT NULL,
  `PaymentPrice` float DEFAULT '1',
  PRIMARY KEY (`PaymentID`),
  KEY `PriceID` (`PaymentPrice`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `payment` (`PaymentID`, `PaymentName`, `PaymentPrice`) VALUES
(1,	'Cash',	0),
(3,	'Bankwire',	50)
ON DUPLICATE KEY UPDATE `PaymentID` = VALUES(`PaymentID`), `PaymentName` = VALUES(`PaymentName`), `PaymentPrice` = VALUES(`PaymentPrice`);

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

INSERT INTO `photo` (`PhotoID`, `PhotoName`, `PhotoURL`, `PhotoAlbumID`, `PhotoAltText`, `CoverPhoto`) VALUES
(1,	'Foto Galaxy Nexus 01',	'main.png',	1,	'Foto Galaxy Nexus',	1),
(2,	'Chromebook',	'main.jpg',	2,	'Chromebook',	1),
(3,	'Sony Xperia Z',	'sony.jpg',	3,	'Xperia Z',	1),
(5,	'6658.jpg',	'6658.jpg',	4,	's4',	1),
(6,	'Nexus',	'nexus2.jpg',	1,	'nexus',	0),
(7,	'Nexus',	'nexus3.jpg',	1,	'nexus',	0),
(8,	'nexus7.jpg',	'nexus7.jpg',	1,	's4',	0),
(9,	'nex.jpg',	'nex.jpg',	1,	's4',	0),
(11,	'Samsung-Galaxy-Nexus-Landscape.jpg',	'Samsung-Galaxy-Nexus-Landscape.jpg',	1,	's4',	0),
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
(37,	'name',	'nokia-case.jpeg',	6,	'name',	NULL)
ON DUPLICATE KEY UPDATE `PhotoID` = VALUES(`PhotoID`), `PhotoName` = VALUES(`PhotoName`), `PhotoURL` = VALUES(`PhotoURL`), `PhotoAlbumID` = VALUES(`PhotoAlbumID`), `PhotoAltText` = VALUES(`PhotoAltText`), `CoverPhoto` = VALUES(`CoverPhoto`);

DROP TABLE IF EXISTS `photoalbum`;
CREATE TABLE `photoalbum` (
  `PhotoAlbumID` int(11) NOT NULL AUTO_INCREMENT,
  `PhotoAlbumName` varchar(45) DEFAULT NULL,
  `PhotoAlbumDescription` varchar(500) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  PRIMARY KEY (`PhotoAlbumID`),
  KEY `ProductID` (`ProductID`),
  CONSTRAINT `photoalbum_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO `photoalbum` (`PhotoAlbumID`, `PhotoAlbumName`, `PhotoAlbumDescription`, `ProductID`) VALUES
(1,	'Album GN',	'Galaxy Nexus album',	1),
(2,	'Chromebook',	'Chromebook gallery',	2),
(3,	'Trojka',	'trojka',	3),
(4,	'ctyrka',	'ctyrka',	4),
(5,	'petka',	'petka',	5),
(6,	'Apple iPad',	'The best tablet EVER!',	6),
(7,	'Apple iPad',	'The best tablet EVER!',	9),
(8,	'Lenovo ThinkPad Tablet 2 64GB WiFi 3G 3679-4H',	'Tablet Lenovo ThinkPad skrývá v uhlazeném černém šasi velmi atraktivní hardwarovou výbavu pohánějící systém Microsoft Windows 8. Tablet je vhodný pro náročné uživatele na každodenních cestách, kteří potřebují především spolehlivě rychlý kancelářský software, přístup k internetu s nejrůznějšími aplikacemi včetně vysoce kvalitní videokomunikace a přehrávání zvuku i videa. S výdrží až 10 hodin na jedno nabití s vámi bude celý den připravený do služby.\r\n\r\nKe všem činnostem skvěle poslouží multidotyk',	10),
(9,	'Lenovo ThinkPad Edge E130 Arctic Blue 3358-8C',	'<p><span style=\"color: #1a171b; font-family: Verdana, sans-serif, Arial; font-size: 13px; line-height: 19.5px; background-color: #fffffd;\">Velmi mal&yacute; a tenk&yacute; cestovn&iacute; notebook LENOVO ThinkPad Edge E130 s nejnověj&scaron;&iacute;m procesorem a s&nbsp;</span><strong style=\"color: #1a171b; font-family: Verdana, sans-serif, Arial; font-size: 13px; line-height: 19.5px; background-color: #fffffd;\">3G připojen&iacute;m</strong><span style=\"color: #1a171b; font-family: Verdana, sans',	11),
(11,	'Sony Ericsson Xperia Mini',	'<p style=\"margin: 0px 0px 0.5em; padding: 0px; line-height: 1.5em; word-wrap: break-word; color: #333333; font-family: \'Arial CE\', Arial, \'Helvetica CE\', Helvetica, sans-serif; font-size: small;\"><strong style=\"margin: 0px; padding: 0px;\">Mal&yacute; stylov&yacute; smartphone Sony Ericsson Xperia Mini</strong>&nbsp;se může pochlubit pěkn&yacute;m designem, mal&yacute;mi rozměry i velmi dobrou funkčn&iacute; v&yacute;bavou. Telefonu s operačn&iacute;m syst&eacute;mem Google Android 2.3 Gingerbrea',	13)
ON DUPLICATE KEY UPDATE `PhotoAlbumID` = VALUES(`PhotoAlbumID`), `PhotoAlbumName` = VALUES(`PhotoAlbumName`), `PhotoAlbumDescription` = VALUES(`PhotoAlbumDescription`), `ProductID` = VALUES(`ProductID`);

DROP TABLE IF EXISTS `price`;
CREATE TABLE `price` (
  `PriceID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `SellingPrice` float DEFAULT NULL,
  `SALE` float DEFAULT '1',
  `FinalPrice` float DEFAULT '0',
  `CurrencyID` int(11) DEFAULT '1',
  PRIMARY KEY (`PriceID`),
  KEY `CurrencyID_idx` (`CurrencyID`),
  KEY `ProductID` (`ProductID`),
  CONSTRAINT `FKPriceCurrency` FOREIGN KEY (`CurrencyID`) REFERENCES `currency` (`CurrencyID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `price_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT INTO `price` (`PriceID`, `ProductID`, `SellingPrice`, `SALE`, `FinalPrice`, `CurrencyID`) VALUES
(1,	1,	10998,	0,	10998,	1),
(2,	2,	5999,	0,	5999,	1),
(3,	3,	7999,	0,	8999,	1),
(4,	4,	15000,	1500,	13500,	1),
(5,	5,	13999,	0,	15999,	1),
(6,	6,	10000,	0,	11999,	1),
(7,	9,	11999,	1,	13999,	1),
(8,	10,	20,	18,	2,	1),
(9,	11,	20999,	1,	23999,	1),
(10,	13,	NULL,	0,	3999,	1)
ON DUPLICATE KEY UPDATE `PriceID` = VALUES(`PriceID`), `ProductID` = VALUES(`ProductID`), `SellingPrice` = VALUES(`SellingPrice`), `SALE` = VALUES(`SALE`), `FinalPrice` = VALUES(`FinalPrice`), `CurrencyID` = VALUES(`CurrencyID`);

DROP TABLE IF EXISTS `producer`;
CREATE TABLE `producer` (
  `ProducerID` int(11) NOT NULL AUTO_INCREMENT,
  `ProducerName` varchar(255) NOT NULL,
  PRIMARY KEY (`ProducerID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `producer` (`ProducerID`, `ProducerName`) VALUES
(1,	'Samsung'),
(2,	'Sony'),
(3,	'Apple'),
(4,	'Lenovo'),
(5,	'Nokia')
ON DUPLICATE KEY UPDATE `ProducerID` = VALUES(`ProducerID`), `ProducerName` = VALUES(`ProducerName`);

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(255) DEFAULT NULL,
  `ProducerID` int(11) DEFAULT NULL,
  `ProductNumber` varchar(255) DEFAULT NULL,
  `ProductDescription` longtext,
  `ProductStatusID` int(11) DEFAULT NULL,
  `ProductEAN` varchar(45) DEFAULT NULL,
  `ProductQR` varchar(45) DEFAULT NULL,
  `ProductWarranty` varchar(45) DEFAULT NULL,
  `PiecesAvailable` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `DateOfAvailable` date DEFAULT NULL,
  `ProductDateOfAdded` int(11) DEFAULT NULL,
  `CommentID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ProductID`),
  KEY `CategoryID_idx` (`CategoryID`),
  KEY `CommentID_idx` (`CommentID`),
  KEY `ProductStatusID` (`ProductStatusID`),
  KEY `ProducerID` (`ProducerID`),
  CONSTRAINT `FKProductCategory` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKProductComment` FOREIGN KEY (`CommentID`) REFERENCES `comment` (`CommentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_ibfk_3` FOREIGN KEY (`ProductStatusID`) REFERENCES `productstatus` (`ProductStatusID`),
  CONSTRAINT `product_ibfk_4` FOREIGN KEY (`ProducerID`) REFERENCES `producer` (`ProducerID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO `product` (`ProductID`, `ProductName`, `ProducerID`, `ProductNumber`, `ProductDescription`, `ProductStatusID`, `ProductEAN`, `ProductQR`, `ProductWarranty`, `PiecesAvailable`, `CategoryID`, `DateOfAvailable`, `ProductDateOfAdded`, `CommentID`) VALUES
(1,	'Samsung Galaxy Nexus 2',	3,	'',	'<p>Smartphone ze serie Nexus</p>',	1,	'',	'',	'',	10,	2,	NULL,	NULL,	1),
(2,	'Samsung Chromebook',	1,	'',	'<p>An ultraportable, sleek laptop for everyday adventures. It weighs 2.4 pounds and has over 6.5 hours of battery life, so you can bring it anywhere and use it everywhere</p>',	NULL,	'',	'',	'',	1,	2,	NULL,	NULL,	NULL),
(3,	'Samsung Galaxy S4',	1,	'',	'Hot news in smartphone world',	2,	'',	'',	'',	99,	3,	NULL,	NULL,	NULL),
(4,	'Nokia 3355',	1,	'',	'<p>Best smartphone of present smarthone world. SUPERB!</p>',	2,	'',	'',	'',	40,	1,	NULL,	NULL,	NULL),
(5,	'Apple iPad',	3,	'',	'Tablet from company Apple',	NULL,	'',	'',	'',	666,	4,	NULL,	NULL,	NULL),
(6,	'Nokia 3310',	5,	'11111',	'desc',	NULL,	'123456',	'122',	'rok',	0,	2,	'0000-00-00',	0,	1),
(9,	'Apple iPad',	3,	'11111',	'The best tablet EVER!',	NULL,	'123456',	'122',	'rok',	9,	4,	'0000-00-00',	0,	1),
(10,	'Lenovo ThinkPad Tablet 2 64GB WiFi 3G 3679-4HG',	4,	'11111',	'<p>Tablet Lenovo ThinkPad skr&yacute;v&aacute; v uhlazen&eacute;m čern&eacute;m &scaron;asi velmi atraktivn&iacute; hardwarovou v&yacute;bavu poh&aacute;něj&iacute;c&iacute; syst&eacute;m Microsoft Windows 8. Tablet je vhodn&yacute; pro n&aacute;ročn&eacute; uživatele na každodenn&iacute;ch cest&aacute;ch, kteř&iacute; potřebuj&iacute; předev&scaron;&iacute;m spolehlivě rychl&yacute; kancel&aacute;řsk&yacute; software, př&iacute;stup k internetu s nejrůzněj&scaron;&iacute;mi aplikacemi včetně vysoce kvalitn&iacute; videokomunikace a přehr&aacute;v&aacute;n&iacute; zvuku i videa. S v&yacute;drž&iacute; až 10 hodin na jedno nabit&iacute; s v&aacute;mi bude cel&yacute; den připraven&yacute; do služby. Ke v&scaron;em činnostem skvěle poslouž&iacute; multidotykov&yacute; displej v rozli&scaron;en&iacute; HD Ready. Displej je typu IPS jako z&aacute;ruka prvotř&iacute;dn&iacute;ho barevn&eacute;ho pod&aacute;n&iacute; s jemn&yacute;mi odst&iacute;ny. Pro roz&scaron;&iacute;řen&iacute; plochy potě&scaron;&iacute; konektor Mini HDMI. Pressure Sensitivy se postar&aacute; o zabr&aacute;něn&iacute; n&aacute;hodn&yacute;ch dotyků při nečinnosti. Pro jemněj&scaron;&iacute; ovl&aacute;d&aacute;n&iacute; a ručn&iacute; psan&iacute; i kreslen&iacute; si lze dokoupit pero. Pomoc&iacute; USB či BlueTooth v&scaron;ak připoj&iacute;te i fyzickou kl&aacute;vesnici. Pro data je &uacute;loži&scaron;tě o kapacitě 64 GB. Oproti standardn&iacute;m diskům přinese daleko men&scaron;&iacute; energetickou spotřebu, rychlej&scaron;&iacute; př&iacute;stup a</p>',	NULL,	'123456',	'122',	'rok',	1,	4,	'0000-00-00',	0,	1),
(11,	'Lenovo ThinkPad Edge E130 Arctic Blue 3358-8CG',	4,	'11111',	'Velmi malý a tenký cestovní notebook LENOVO ThinkPad Edge E130 s nejnovějším procesorem a s <strong>3G připojením</strong> jako ideální každodenní parťák aktivních&nbsp;uživatelů. Notebook odpovídá displeji o kompromisní úhlopříčce <strong>11,6\"</strong>. Ten je v <strong>matné povrchové úpravě </strong>pro lepší viditelnost. Spolu s pokročilými funkcemi pro VoIP, ergonomickou klávesnicí a kombinací touchpadu s trackpointem nabízí komfort pro každodenní službu. Je vhodný pro pracovité jedince vyžadující flexibilní mobilní použití a dlouhou výdrž na baterií, která u tohoto modelu dosahuje neuvěřitelných <strong>8 hodin</strong>!<br><br>Pro vaše data je vložen <strong>nadstandardně rychlý disk o kapacitě 500 GB</strong>, takže s sebou budete mít vše důležité. Na disku je předinstalovaný operační systém <strong>Microsoft Windows 8</strong>, který přichází s přehledným dlaždicovým prostředím. Svižný chod tohoto lehce přenosného počítače je na procesoru se sníženou spotřebou z nejnovější série <strong>Ivy Bridge, Intel Core i3 3217U</strong>. Pokud si&nbsp;budete přát&nbsp;pustit například HD video, přijde vhod <strong>HDMI</strong> port, kterým můžete notebook připojit k televizím nebo projektorům ve velkém rozlišení. Další rozšíření lze uskutečnit přes vysokorychlostní <strong>UBS 3.0 </strong>či bezdrátový <strong>BlueTooth</strong>.<br><br>',	NULL,	'123456',	'122',	'rok',	1,	2,	'0000-00-00',	0,	1),
(13,	'Sony Ericsson Xperia Mini',	2,	'11111',	'<p>Uživatel se d&iacute;ky operačn&iacute;mu syst&eacute;mu Android může tě&scaron;it na &scaron;pičkovou funkčn&iacute; z&aacute;kladu. Ta je tvořena např. internetov&yacute;m prohl&iacute;žečem,<strong> e-mailov&yacute;m klientem</strong>, multimedi&aacute;ln&iacute;m přehr&aacute;vačem, bohat&yacute;mi možnostmi synchronizace, kvalitn&iacute;mu funkcemi pro organizaci času, prohl&iacute;žečem dokumentů, skvěl&yacute;m kalend&aacute;řem či aplikacemi pro př&iacute;stup na Facebook a Twitter. Př&iacute;stup ke katalogu Android Market pak umožn&iacute; instalaci dal&scaron;&iacute;ch tis&iacute;ců aplikac&iacute; a her. Samozřejmost&iacute; je tak&eacute; poveden&yacute; digit&aacute;ln&iacute; fotoapar&aacute;t s rozli&scaron;en&iacute;m pět megapixelů nebo vestavěn&aacute; satelitn&iacute; aGPS navigace. Telefonu nechyb&iacute; ani podpora technologi&iacute; Wi-Fi (včetně DLNA) a Bluetooth. Pro připojen&iacute; sluch&aacute;tek je k dispozici standardn&iacute; 3,5 mm jack. O nap&aacute;jen&iacute; se star&aacute; Li-Ion akumul&aacute;tor o kapacitě 1200 mAh, jenž by měl telefonu na jedno nabit&iacute; umožnit setrvat až 340 hodin v pohotovostn&iacute;m režimu nebo vykonat čtyři a půl hodiny hovoru.</p>',	2,	'123456',	'122',	'rok',	12,	3,	'0000-00-00',	2013,	1)
ON DUPLICATE KEY UPDATE `ProductID` = VALUES(`ProductID`), `ProductName` = VALUES(`ProductName`), `ProducerID` = VALUES(`ProducerID`), `ProductNumber` = VALUES(`ProductNumber`), `ProductDescription` = VALUES(`ProductDescription`), `ProductStatusID` = VALUES(`ProductStatusID`), `ProductEAN` = VALUES(`ProductEAN`), `ProductQR` = VALUES(`ProductQR`), `ProductWarranty` = VALUES(`ProductWarranty`), `PiecesAvailable` = VALUES(`PiecesAvailable`), `CategoryID` = VALUES(`CategoryID`), `DateOfAvailable` = VALUES(`DateOfAvailable`), `ProductDateOfAdded` = VALUES(`ProductDateOfAdded`), `CommentID` = VALUES(`CommentID`);

DROP TABLE IF EXISTS `productstatus`;
CREATE TABLE `productstatus` (
  `ProductStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductStatusName` varchar(100) NOT NULL,
  `ProductStatusDescription` varchar(255) NOT NULL,
  PRIMARY KEY (`ProductStatusID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `productstatus` (`ProductStatusID`, `ProductStatusName`, `ProductStatusDescription`) VALUES
(1,	'Concept',	'This product is in stae: Concept'),
(2,	'Visible',	'This product is visible')
ON DUPLICATE KEY UPDATE `ProductStatusID` = VALUES(`ProductStatusID`), `ProductStatusName` = VALUES(`ProductStatusName`), `ProductStatusDescription` = VALUES(`ProductStatusDescription`);

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `Name` varchar(100) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`Name`, `Value`) VALUES
('TAX',	'21')
ON DUPLICATE KEY UPDATE `Name` = VALUES(`Name`), `Value` = VALUES(`Value`);

DROP TABLE IF EXISTS `unit`;
CREATE TABLE `unit` (
  `UnitID` int(11) NOT NULL AUTO_INCREMENT,
  `UnitShort` varchar(50) NOT NULL,
  `UnitName` varchar(255) NOT NULL,
  PRIMARY KEY (`UnitID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `unit` (`UnitID`, `UnitShort`, `UnitName`) VALUES
(1,	' ',	' '),
(2,	'g',	'grams'),
(3,	'mAh',	'miliamperhours'),
(4,	'px',	'pixels'),
(5,	'\"',	'inches')
ON DUPLICATE KEY UPDATE `UnitID` = VALUES(`UnitID`), `UnitShort` = VALUES(`UnitShort`), `UnitName` = VALUES(`UnitName`);

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
('aaa@aaa.aaa',	'',	'AAA',	0,	'',	'',	'user'),
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
('asd@asd.ads',	'',	'asd',	0,	'',	'',	'user'),
('asd@asfdg.dfg',	'',	'sdfghjk',	0,	'',	'',	'user'),
('asda@asd.ads',	'',	'asd',	0,	'',	'',	'user'),
('hddd@dsd.cdd',	'',	'Petr',	0,	'',	'',	'user'),
('jan.novak@company.com',	'novak',	'Jan',	999888777,	'Company',	'819281293',	'0'),
('kifa@mail.com',	'$2a$07$$$$$$$$$$$$$$$$$$$$$$.Y',	'Kifa',	NULL,	'',	'',	'admin'),
('michal@prosek.cz',	NULL,	'Michal Prošáků',	0,	NULL,	NULL,	'user'),
('petan@petr.com',	'',	'Petr',	0,	'',	'',	'user'),
('ppp@ppp.ppp',	'',	'09u7',	0,	'',	'',	'user'),
('ppp@pppa.ppp',	'',	'09u7',	0,	'',	'',	'user'),
('qwe@qrw.as',	'',	'qwe',	0,	'',	'',	'user'),
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
('tomik@tomas.com',	'$2a$07$xshgrgluo88ug5qvohjvme0',	'Tomas',	NULL,	NULL,	NULL,	'0'),
('yetty@himalaja.tib',	NULL,	'Yetty',	0,	NULL,	NULL,	'user')
ON DUPLICATE KEY UPDATE `UsersID` = VALUES(`UsersID`), `Password` = VALUES(`Password`), `Name` = VALUES(`Name`), `PhoneNumber` = VALUES(`PhoneNumber`), `CompanyName` = VALUES(`CompanyName`), `TIN` = VALUES(`TIN`), `Permission` = VALUES(`Permission`);

-- 2013-04-28 21:21:14
