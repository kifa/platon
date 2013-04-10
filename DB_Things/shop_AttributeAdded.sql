-- Adminer 3.6.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `shop`;
CREATE DATABASE `shop` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `shop`;

DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `AddressID` int(11) NOT NULL AUTO_INCREMENT,
  `Street` varchar(100) DEFAULT NULL,
  `ZIPCode` int(11) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `State` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`AddressID`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

INSERT INTO `address` (`AddressID`, `Street`, `ZIPCode`, `City`, `State`) VALUES
(1,	'Vaclavska',	11000,	'Praha',	'CZ'),
(2,	'Nova',	99999,	'Plzen',	'CZ'),
(3,	'Los Santos',	99923,	'Sao Paolo',	'Br'),
(4,	'ppp',	0,	'ppp',	NULL),
(5,	'gyuoi',	0,	'ijoi',	NULL),
(6,	'gyuoi',	0,	'ijoi',	NULL),
(7,	'gyuoi',	0,	'ijoi',	NULL),
(8,	'gyuoi',	0,	'ijoi',	NULL),
(9,	'gyuoi',	0,	'ijoi',	NULL),
(10,	'PPP',	18273,	'PPP',	NULL),
(11,	'PPP',	18273,	'PPP',	NULL),
(12,	'PPP',	18273,	'PPP',	NULL),
(13,	'PPP',	18273,	'PPP',	NULL),
(14,	'PPP',	18273,	'PPP',	NULL),
(15,	'PPP',	18273,	'PPP',	NULL),
(16,	'PPP',	18273,	'PPP',	NULL),
(17,	'qwe',	12342,	'qrw',	NULL),
(18,	'123142',	12311,	'2143124',	NULL),
(19,	'pqweoq',	12831,	'pqwieruq u',	NULL),
(20,	'pqweoq',	12831,	'pqwieruq u',	NULL),
(21,	'pqweoq',	12831,	'pqwieruq u',	NULL),
(22,	'asdow',	10291,	'asdkj',	NULL),
(23,	'asdow',	10291,	'asdkj',	NULL),
(24,	'asdow',	10291,	'asdkj',	NULL),
(25,	'asdow',	10291,	'asdkj',	NULL),
(26,	'asdow',	10291,	'asdkj',	NULL),
(27,	'asdow',	10291,	'asdkj',	NULL),
(28,	'asdow',	10291,	'asdkj',	NULL),
(29,	'asdow',	10291,	'asdkj',	NULL),
(30,	'asdow',	10291,	'asdkj',	NULL),
(31,	'Na Paloučku 10',	999,	'Šumava',	NULL),
(32,	'Na Paloučku 10',	999,	'Šumava',	NULL),
(33,	'Na Paloučku 10',	999,	'Šumava',	NULL),
(34,	'asfd',	123123,	'afd',	NULL),
(35,	'asfd',	123123,	'afd',	NULL),
(36,	'asfd',	123123,	'afd',	NULL),
(37,	'wertwe',	21344,	'wertw',	NULL),
(38,	'wertwe',	21344,	'wertw',	NULL),
(39,	'wertwe',	21344,	'wertw',	NULL),
(40,	'wertwe',	21344,	'wertw',	NULL),
(41,	'ois',	3219847,	's;oihj',	NULL),
(42,	'ois',	3219847,	's;oihj',	NULL),
(43,	'ois',	3219847,	's;oihj',	NULL),
(44,	'ois',	3219847,	's;oihj',	NULL),
(45,	'ois',	3219847,	's;oihj',	NULL),
(46,	'ois',	3219847,	's;oihj',	NULL),
(47,	'ois',	3219847,	's;oihj',	NULL),
(48,	'ois',	3219847,	's;oihj',	NULL),
(49,	'ois',	3219847,	's;oihj',	NULL),
(50,	'ois',	3219847,	's;oihj',	NULL),
(51,	'ois',	3219847,	's;oihj',	NULL),
(52,	'ois',	3219847,	's;oihj',	NULL),
(53,	'ois',	3219847,	's;oihj',	NULL),
(54,	'ois',	3219847,	's;oihj',	NULL),
(55,	';lkjhgf',	32982,	'p;lkjhg',	NULL);

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE `attributes` (
  `AttributeID` int(11) NOT NULL AUTO_INCREMENT,
  `AttributeName` varchar(255) NOT NULL,
  PRIMARY KEY (`AttributeID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `attributes` (`AttributeID`, `AttributeName`) VALUES
(1,	'Weight'),
(2,	'Display size'),
(3,	'Display resolution'),
(4,	'Battery');

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(45) DEFAULT NULL,
  `CategoryDescription` varchar(45) DEFAULT NULL,
  `HigherCategoryID` int(11) DEFAULT NULL,
  PRIMARY KEY (`CategoryID`),
  KEY `HigherCategoryID_idx` (`HigherCategoryID`),
  CONSTRAINT `FKHigherCategory` FOREIGN KEY (`HigherCategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `category` (`CategoryID`, `CategoryName`, `CategoryDescription`, `HigherCategoryID`) VALUES
(1,	'Cellphone',	'Cellphone category',	NULL),
(2,	'Notebook',	'Notebooks category',	NULL),
(3,	'Smartphone',	'Smartphone category',	1),
(4,	'Tablets',	'Tablets category',	NULL);

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
(2,	'Reakce na koment',	'Pi? ?esky pitomo',	'2012-03-20',	2,	1);

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
(2,	'EUR',	'Euro',	NULL);

DROP TABLE IF EXISTS `delivery`;
CREATE TABLE `delivery` (
  `DeliveryID` int(11) NOT NULL AUTO_INCREMENT,
  `DeliveryName` varchar(45) DEFAULT NULL,
  `DeliveryDescription` varchar(45) DEFAULT NULL,
  `DeliveryPrice` float DEFAULT NULL,
  `FreeFromPrice` float DEFAULT NULL,
  PRIMARY KEY (`DeliveryID`),
  KEY `PriceID_idx` (`DeliveryPrice`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `delivery` (`DeliveryID`, `DeliveryName`, `DeliveryDescription`, `DeliveryPrice`, `FreeFromPrice`) VALUES
(1,	'Personal pick up',	'Personal in the shop',	0,	NULL),
(2,	'Cash on delivery',	'Send by transport company',	150,	1000);

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

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
(13,	9,	4,	1,	16999);

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `orders` (`OrderID`, `StatusID`, `UsersID`, `ProductsPrice`, `DeliveryPaymentPrice`, `TaxPrice`, `TotalPrice`, `DateCreated`, `DateOfLastChange`, `DateFinished`, `DeliveryID`, `PaymentID`, `IP`, `SessionID`) VALUES
(1,	1,	'aaaaaaa@aaa.aaa',	9098,	NULL,	90.98,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	2,	1,	NULL,	NULL),
(2,	1,	'qwe@qrw.as',	99,	NULL,	0.99,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	2,	2,	NULL,	NULL),
(3,	1,	'1234@1234.com',	11999,	NULL,	119.99,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	1,	NULL,	NULL),
(4,	1,	'ppp@pppa.ppp',	15999,	NULL,	0,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	1,	NULL,	NULL),
(5,	1,	'afaasasdsasds2134@asfew213.aff',	11999,	NULL,	2519.79,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	2,	NULL,	NULL),
(6,	1,	'tdanek@atlas.cz',	32998,	NULL,	0,	NULL,	'2013-03-30',	'2013-03-30',	NULL,	1,	2,	NULL,	NULL),
(7,	1,	'asda@asd.ads',	9098,	NULL,	0,	NULL,	'2013-04-07',	'2013-04-07',	NULL,	1,	1,	NULL,	NULL),
(8,	1,	'rte@gdsaasasasasdaassf.fgd',	11999,	100,	2519.79,	14518.8,	'2013-04-07',	'2013-04-07',	NULL,	2,	2,	NULL,	NULL),
(9,	2,	'asd@asfdg.dfg',	28998,	150,	6089.58,	35087.6,	'2013-04-07',	'2013-04-07',	NULL,	2,	1,	NULL,	NULL);

DROP TABLE IF EXISTS `orderstatus`;
CREATE TABLE `orderstatus` (
  `OrderStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusName` varchar(45) DEFAULT NULL,
  `StatusDescription` varchar(255) DEFAULT NULL,
  `StatusProgress` int(11) DEFAULT '0',
  PRIMARY KEY (`OrderStatusID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `orderstatus` (`OrderStatusID`, `StatusName`, `StatusDescription`, `StatusProgress`) VALUES
(1,	'Pending',	'Pending order',	1),
(2,	'Sending',	'sending order',	5),
(3,	'Done',	'done',	10);

DROP TABLE IF EXISTS `parameters`;
CREATE TABLE `parameters` (
  `ParameterID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `AttributeID` int(11) DEFAULT NULL,
  `Val` varchar(255) DEFAULT NULL,
  `Unit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ParameterID`),
  KEY `ProductID` (`ProductID`),
  KEY `AttributeID` (`AttributeID`),
  CONSTRAINT `parameters_ibfk_2` FOREIGN KEY (`AttributeID`) REFERENCES `attributes` (`AttributeID`) ON DELETE CASCADE,
  CONSTRAINT `parameters_ibfk_1` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `parameters` (`ParameterID`, `ProductID`, `AttributeID`, `Val`, `Unit`) VALUES
(1,	1,	1,	'169',	'g'),
(2,	1,	2,	'4,8',	'inch'),
(3,	1,	3,	'1878x900',	'px'),
(4,	1,	4,	'1850',	'mAh');

DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `PaymentName` varchar(45) DEFAULT NULL,
  `PaymentPrice` float DEFAULT '1',
  PRIMARY KEY (`PaymentID`),
  KEY `PriceID` (`PaymentPrice`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `payment` (`PaymentID`, `PaymentName`, `PaymentPrice`) VALUES
(1,	'Cash',	0),
(2,	'Banwire',	-50);

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

INSERT INTO `photo` (`PhotoID`, `PhotoName`, `PhotoURL`, `PhotoAlbumID`, `PhotoAltText`, `CoverPhoto`) VALUES
(1,	'Foto Galaxy Nexus 01',	'main.png',	1,	'Foto Galaxy Nexus',	1),
(2,	'Chromebook',	'main.jpg',	2,	'Chromebook',	1),
(3,	'Sony Xperia Z',	'sony.jpg',	3,	'Xperia Z',	1),
(5,	'6658.jpg',	'6658.jpg',	4,	's4',	1),
(6,	'Nexus',	'nexus2.jpg',	1,	'nexus',	0),
(7,	'Nexus',	'nexus3.jpg',	1,	'nexus',	0),
(8,	'nexus7.jpg',	'nexus7.jpg',	1,	's4',	0),
(9,	'nex.jpg',	'nex.jpg',	1,	's4',	0),
(10,	'samsung-galaxy-nexus_2.jpg',	'samsung-galaxy-nexus_2.jpg',	1,	's4',	0),
(11,	'Samsung-Galaxy-Nexus-Landscape.jpg',	'Samsung-Galaxy-Nexus-Landscape.jpg',	1,	's4',	0),
(12,	'ipad-mini-scaled-1.jpg',	'ipad-mini-scaled-1.jpg',	6,	's4',	1),
(13,	'ipad-mini-scaled-1.jpg',	'ipad-mini-scaled-1.jpg',	7,	's4',	1),
(22,	'1416746-img-steve-jobs-apple-ipad.jpg',	'1416746-img-steve-jobs-apple-ipad.jpg',	7,	's4',	NULL),
(23,	'Taking-pictures-with-Nokia-3310.jpg',	'Taking-pictures-with-Nokia-3310.jpg',	4,	's4',	NULL),
(27,	'ImgW.jpe',	'ImgW.jpe',	8,	's4',	1),
(28,	'ImgW (1).jpe',	'ImgW (1).jpe',	8,	's4',	NULL),
(29,	'ImgW (2).jpe',	'ImgW (2).jpe',	8,	's4',	NULL),
(30,	'ImgW (3).jpe',	'ImgW (3).jpe',	8,	's4',	NULL),
(31,	'ImgW.jpe',	'ImgW.jpe',	9,	's4',	1),
(33,	'Sony Ericsson Xperia Mini',	'mini.jpg',	11,	'Sony Ericsson Xperia Mini',	1),
(34,	'Sony Ericsson Xperia Mini',	'mini2.jpg',	11,	'Sony Ericsson Xperia Mini',	NULL),
(35,	'name',	'1a6ccb602dfbb930b87c709644585f1d.jpg',	11,	'name',	NULL),
(36,	'name',	'mini.jpg',	4,	'name',	NULL);

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
(11,	'Sony Ericsson Xperia Mini',	'<p style=\"margin: 0px 0px 0.5em; padding: 0px; line-height: 1.5em; word-wrap: break-word; color: #333333; font-family: \'Arial CE\', Arial, \'Helvetica CE\', Helvetica, sans-serif; font-size: small;\"><strong style=\"margin: 0px; padding: 0px;\">Mal&yacute; stylov&yacute; smartphone Sony Ericsson Xperia Mini</strong>&nbsp;se může pochlubit pěkn&yacute;m designem, mal&yacute;mi rozměry i velmi dobrou funkčn&iacute; v&yacute;bavou. Telefonu s operačn&iacute;m syst&eacute;mem Google Android 2.3 Gingerbrea',	13);

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
(2,	2,	5999,	10,	5989,	1),
(3,	3,	7999,	0,	8999,	1),
(4,	4,	15000,	0,	16999,	1),
(5,	5,	13999,	0,	15999,	1),
(6,	6,	10000,	0,	11999,	1),
(7,	9,	11999,	1,	13999,	1),
(8,	10,	9999,	1,	10999,	1),
(9,	11,	20999,	1,	23999,	1),
(10,	13,	NULL,	0,	3999,	1);

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(255) DEFAULT NULL,
  `Producer` varchar(255) DEFAULT NULL,
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
  CONSTRAINT `FKProductCategory` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKProductComment` FOREIGN KEY (`CommentID`) REFERENCES `comment` (`CommentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_ibfk_3` FOREIGN KEY (`ProductStatusID`) REFERENCES `productstatus` (`ProductStatusID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO `product` (`ProductID`, `ProductName`, `Producer`, `ProductNumber`, `ProductDescription`, `ProductStatusID`, `ProductEAN`, `ProductQR`, `ProductWarranty`, `PiecesAvailable`, `CategoryID`, `DateOfAvailable`, `ProductDateOfAdded`, `CommentID`) VALUES
(1,	'Samsung Galaxy Nexus',	'Samsung',	NULL,	'Smartphone ze serie Nexus',	NULL,	NULL,	NULL,	NULL,	10,	2,	NULL,	NULL,	1),
(2,	'Samsung Chromebook',	'Samsung',	NULL,	'<p>Chromebook od Samsungu. Ten nej notebook ... pokud m&aacute;te mobiln&iacute; internet v&scaron;ude.</p>',	NULL,	NULL,	NULL,	NULL,	4,	2,	NULL,	NULL,	NULL),
(3,	'Samsung Galaxy S4',	'Samsung',	NULL,	'Hot news in smartphone world',	NULL,	NULL,	NULL,	NULL,	99,	3,	NULL,	NULL,	NULL),
(4,	'Sony Xperia Z',	'Sony',	NULL,	'<p>Best smartphone of present smarthone world. SUPERB!</p>',	NULL,	NULL,	NULL,	NULL,	40,	3,	NULL,	NULL,	NULL),
(5,	'Apple iPad',	'Apple Inc.',	NULL,	'Tablet from company Apple',	NULL,	NULL,	NULL,	NULL,	666,	4,	NULL,	NULL,	NULL),
(6,	'Nokia 3310',	'neuvedeno',	'11111',	'desc',	NULL,	'123456',	'122',	'rok',	1,	2,	'0000-00-00',	0,	1),
(9,	'Apple iPad',	'neuvedeno',	'11111',	'The best tablet EVER!',	NULL,	'123456',	'122',	'rok',	3,	4,	'0000-00-00',	0,	1),
(10,	'Lenovo ThinkPad Tablet 2 64GB WiFi 3G 3679-4HG',	'neuvedeno',	'11111',	'Tablet Lenovo ThinkPad skrývá v uhlazeném černém šasi velmi atraktivní hardwarovou výbavu pohánějící systém Microsoft Windows 8. Tablet je vhodný pro náročné uživatele na každodenních cestách, kteří potřebují především spolehlivě rychlý kancelářský software, přístup k internetu s nejrůznějšími aplikacemi včetně vysoce kvalitní videokomunikace a přehrávání zvuku i videa. S výdrží až 10 hodin na jedno nabití s vámi bude celý den připravený do služby.\r\n\r\nKe všem činnostem skvěle poslouží multidotykový displej v rozlišení HD Ready. Displej je typu IPS jako záruka prvotřídního barevného podání s jemnými odstíny. Pro rozšíření plochy potěší konektor Mini HDMI. Pressure Sensitivy se postará o zabránění náhodných dotyků při nečinnosti. Pro jemnější ovládání a ruční psaní i kreslení si lze dokoupit pero. Pomocí USB či BlueTooth však připojíte i fyzickou klávesnici.\r\n\r\nPro data je úložiště o kapacitě 64 GB. Oproti standardním diskům přinese daleko menší energetickou spotřebu, rychlejší přístup a',	NULL,	'123456',	'122',	'rok',	1,	4,	'0000-00-00',	0,	1),
(11,	'Lenovo ThinkPad Edge E130 Arctic Blue 3358-8CG',	'neuvedeno',	'11111',	'Velmi malý a tenký cestovní notebook LENOVO ThinkPad Edge E130 s nejnovějším procesorem a s <strong>3G připojením</strong> jako ideální každodenní parťák aktivních&nbsp;uživatelů. Notebook odpovídá displeji o kompromisní úhlopříčce <strong>11,6\"</strong>. Ten je v <strong>matné povrchové úpravě </strong>pro lepší viditelnost. Spolu s pokročilými funkcemi pro VoIP, ergonomickou klávesnicí a kombinací touchpadu s trackpointem nabízí komfort pro každodenní službu. Je vhodný pro pracovité jedince vyžadující flexibilní mobilní použití a dlouhou výdrž na baterií, která u tohoto modelu dosahuje neuvěřitelných <strong>8 hodin</strong>!<br><br>Pro vaše data je vložen <strong>nadstandardně rychlý disk o kapacitě 500 GB</strong>, takže s sebou budete mít vše důležité. Na disku je předinstalovaný operační systém <strong>Microsoft Windows 8</strong>, který přichází s přehledným dlaždicovým prostředím. Svižný chod tohoto lehce přenosného počítače je na procesoru se sníženou spotřebou z nejnovější série <strong>Ivy Bridge, Intel Core i3 3217U</strong>. Pokud si&nbsp;budete přát&nbsp;pustit například HD video, přijde vhod <strong>HDMI</strong> port, kterým můžete notebook připojit k televizím nebo projektorům ve velkém rozlišení. Další rozšíření lze uskutečnit přes vysokorychlostní <strong>UBS 3.0 </strong>či bezdrátový <strong>BlueTooth</strong>.<br><br>',	NULL,	'123456',	'122',	'rok',	1,	2,	'0000-00-00',	0,	1),
(13,	'Sony Ericsson Xperia Mini',	'neuvedeno',	'11111',	'<p>Uživatel se d&iacute;ky operačn&iacute;mu syst&eacute;mu Android může tě&scaron;it na &scaron;pičkovou funkčn&iacute; z&aacute;kladu. Ta je tvořena např. internetov&yacute;m prohl&iacute;žečem,<strong> e-mailov&yacute;m klientem</strong>, multimedi&aacute;ln&iacute;m přehr&aacute;vačem, bohat&yacute;mi možnostmi synchronizace, kvalitn&iacute;mu funkcemi pro organizaci času, prohl&iacute;žečem dokumentů, skvěl&yacute;m kalend&aacute;řem či aplikacemi pro př&iacute;stup na Facebook a Twitter. Př&iacute;stup ke katalogu Android Market pak umožn&iacute; instalaci dal&scaron;&iacute;ch tis&iacute;ců aplikac&iacute; a her. Samozřejmost&iacute; je tak&eacute; poveden&yacute; digit&aacute;ln&iacute; fotoapar&aacute;t s rozli&scaron;en&iacute;m pět megapixelů nebo vestavěn&aacute; satelitn&iacute; aGPS navigace. Telefonu nechyb&iacute; ani podpora technologi&iacute; Wi-Fi (včetně DLNA) a Bluetooth. Pro připojen&iacute; sluch&aacute;tek je k dispozici standardn&iacute; 3,5 mm jack. O nap&aacute;jen&iacute; se star&aacute; Li-Ion akumul&aacute;tor o kapacitě 1200 mAh, jenž by měl telefonu na jedno nabit&iacute; umožnit setrvat až 340 hodin v pohotovostn&iacute;m režimu nebo vykonat čtyři a půl hodiny hovoru.</p>',	NULL,	'123456',	'122',	'rok',	12,	3,	'0000-00-00',	2013,	1);

DROP TABLE IF EXISTS `productstatus`;
CREATE TABLE `productstatus` (
  `ProductStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductStatusName` varchar(100) NOT NULL,
  `ProductStatusDescription` varchar(255) NOT NULL,
  PRIMARY KEY (`ProductStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `Name` varchar(100) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`Name`, `Value`) VALUES
('TAX',	'21');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `UsersID` varchar(60) NOT NULL,
  `Password` varchar(60) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `PhoneNumber` int(11) DEFAULT NULL,
  `AddressID` int(11) DEFAULT NULL,
  `CompanyName` varchar(45) DEFAULT NULL,
  `TIN` varchar(45) DEFAULT NULL,
  `Permission` varchar(6) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`UsersID`),
  KEY `AddressID` (`AddressID`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`UsersID`, `Password`, `Name`, `PhoneNumber`, `AddressID`, `CompanyName`, `TIN`, `Permission`) VALUES
('1234@1234.com',	NULL,	'1234',	0,	1,	NULL,	NULL,	'user'),
('aaa@aaa.aaa',	NULL,	'AAA',	0,	1,	NULL,	NULL,	'user'),
('aaaa@aaa.aaa',	NULL,	'AAA',	0,	1,	NULL,	NULL,	'user'),
('aaaaa@aaa.aaa',	NULL,	'AAA',	0,	1,	NULL,	NULL,	'user'),
('aaaaaa@aaa.aaa',	NULL,	'AAA',	0,	1,	NULL,	NULL,	'user'),
('aaaaaaa@aaa.aaa',	NULL,	'AAA',	0,	1,	NULL,	NULL,	'user'),
('admin@admin.com',	'$2a$07$$$$$$$$$$$$$$$$$$$$$$.Y',	'Admin',	0,	1,	'0',	'0',	'admin'),
('ads@fgsw.sdg',	NULL,	'asdzx',	0,	1,	NULL,	NULL,	'user'),
('ads@fgw.sdg',	NULL,	'asdzx',	0,	1,	NULL,	NULL,	'user'),
('afaasasdsasds2134@asfew213.aff',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('afaasasdsasds@asfew.aff',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('afaasasdsasds@asfew213.aff',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('afaasasdss@asfew.aff',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('afaasss@asfew.aff',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('afas@asf.aff',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('afas@asfew.aff',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('afass@asfew.aff',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('asd@asd.ads',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('asd@asfdg.dfg',	NULL,	'sdfghjk',	0,	1,	NULL,	NULL,	'user'),
('asda@asd.ads',	NULL,	'asd',	0,	1,	NULL,	NULL,	'user'),
('hddd@dsd.cdd',	NULL,	'Petr',	0,	1,	NULL,	NULL,	'user'),
('jan.novak@company.com',	'novak',	'Jan',	999888777,	1,	'Company',	'819281293',	'0'),
('kifa@mail.com',	'$2a$07$$$$$$$$$$$$$$$$$$$$$$.Y',	'Kifa',	NULL,	1,	NULL,	NULL,	'admin'),
('petan@petr.com',	NULL,	'Petr',	0,	1,	NULL,	NULL,	'user'),
('ppp@ppp.ppp',	NULL,	'09u7',	0,	1,	NULL,	NULL,	'user'),
('ppp@pppa.ppp',	NULL,	'09u7',	0,	1,	NULL,	NULL,	'user'),
('qwe@qrw.as',	NULL,	'qwe',	0,	1,	NULL,	NULL,	'user'),
('rte@gddf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsaasasasasdaassf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsaasasasdaassf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsaasasdaassf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsaasasdssf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsaassdssf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsassdssf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsdf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsdsf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdssdsf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdssdssf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('rte@gdsssdssf.fgd',	NULL,	'retr4et',	743,	1,	NULL,	NULL,	'user'),
('tdanek@atlas.cz',	NULL,	'Karel',	0,	1,	NULL,	NULL,	'user'),
('testovaci@subjekt.cz',	'$2a$07$67p8256pml1lrn1a8d986eN',	'Testovaci',	777888999,	1,	'0',	'0',	'0'),
('tomik@tomas.com',	'$2a$07$xshgrgluo88ug5qvohjvme0',	'Tomas',	NULL,	1,	NULL,	NULL,	'0'),
('yetty@himalaja.tib',	NULL,	'Yetty',	0,	1,	NULL,	NULL,	'user');

-- 2013-04-09 23:54:35
