-- Adminer 3.6.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `shop`;
CREATE DATABASE `shop` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `shop`;

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `AdressID` int(11) NOT NULL AUTO_INCREMENT,
  `Street` varchar(50) DEFAULT NULL,
  `HouseNumber` int(11) DEFAULT NULL,
  `ZIPCode` int(11) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `State` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`AdressID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `addresses` (`AdressID`, `Street`, `HouseNumber`, `ZIPCode`, `City`, `State`) VALUES
(1,	'Vaclavska',	1,	11000,	'Praha',	'CZ'),
(2,	'Nova',	2222,	99999,	'Plzen',	'CZ'),
(3,	'Los Santos',	19238,	99923,	'Sao Paolo',	'Br');

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
  `AuthorID` int(11) DEFAULT NULL,
  `PreviousCommentID` int(11) DEFAULT '0',
  PRIMARY KEY (`CommentID`),
  KEY `CommentID_idx` (`PreviousCommentID`),
  CONSTRAINT `FKPreviousComment` FOREIGN KEY (`PreviousCommentID`) REFERENCES `comment` (`CommentID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `comment` (`CommentID`, `CommentTittle`, `CommentContent`, `DateOfAdded`, `AuthorID`, `PreviousCommentID`) VALUES
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


DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusID` int(11) DEFAULT '1',
  `UserID` varchar(30) DEFAULT NULL,
  `TotalPrice` float DEFAULT NULL,
  `TotalPriceTax` float DEFAULT NULL,
  `DateCreated` date DEFAULT NULL,
  `DateOfLastChange` date DEFAULT NULL,
  `DateFinished` date DEFAULT NULL,
  `DeliveryID` int(11) DEFAULT NULL,
  `PaymentID` int(11) DEFAULT NULL,
  `IP` varchar(15) DEFAULT NULL,
  `SessionID` int(11) DEFAULT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `UserID_idx` (`UserID`),
  KEY `StatusID_idx` (`StatusID`),
  KEY `DeliveryID_idx` (`DeliveryID`),
  KEY `PaymentMethodID_idx` (`PaymentID`),
  CONSTRAINT `FKOrderDelivery` FOREIGN KEY (`DeliveryID`) REFERENCES `delivery` (`DeliveryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKOrderStatus` FOREIGN KEY (`StatusID`) REFERENCES `orderstatus` (`OrderStatusID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKOrderUser` FOREIGN KEY (`UserID`) REFERENCES `users` (`Login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`PaymentID`) REFERENCES `payment` (`PaymentID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `order` (`OrderID`, `StatusID`, `UserID`, `TotalPrice`, `TotalPriceTax`, `DateCreated`, `DateOfLastChange`, `DateFinished`, `DeliveryID`, `PaymentID`, `IP`, `SessionID`) VALUES
(1,	1,	'novak',	8999,	89,	'0000-00-00',	'0000-00-00',	'0000-00-00',	1,	1,	NULL,	NULL),
(2,	1,	'novak',	15999,	89,	'0000-00-00',	'0000-00-00',	'0000-00-00',	1,	2,	NULL,	NULL),
(3,	1,	'novak',	39997,	89,	'0000-00-00',	'0000-00-00',	'0000-00-00',	2,	1,	NULL,	NULL),
(4,	1,	'novak',	78993,	89,	'2013-03-26',	'0000-00-00',	'0000-00-00',	1,	1,	NULL,	NULL),
(5,	1,	'novak',	11999,	89,	'2013-03-26',	'2013-03-26',	NULL,	2,	1,	NULL,	NULL),
(6,	1,	'novak',	15999,	89,	'2013-03-26',	'2013-03-26',	NULL,	2,	2,	NULL,	NULL),
(7,	1,	'novak',	11999,	14518.8,	'2013-03-26',	'2013-03-26',	NULL,	1,	1,	NULL,	NULL);

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
  CONSTRAINT `FKOrderDetailsOrder` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKOrderDetailsProduct` FOREIGN KEY (`ProductID`) REFERENCES `product` (`ProductID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `orderdetails` (`OrderDetailsID`, `OrderID`, `ProductID`, `Quantity`, `UnitPrice`) VALUES
(1,	1,	2,	1,	8999),
(2,	2,	4,	1,	15999),
(3,	3,	5,	2,	11999),
(4,	3,	4,	1,	15999),
(5,	4,	3,	2,	16999),
(6,	4,	2,	5,	8999),
(7,	5,	5,	1,	11999),
(8,	6,	4,	1,	15999),
(9,	7,	5,	1,	11999);

DROP TABLE IF EXISTS `orderstatus`;
CREATE TABLE `orderstatus` (
  `OrderStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusName` varchar(45) DEFAULT NULL,
  `StatusDescription` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`OrderStatusID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `orderstatus` (`OrderStatusID`, `StatusName`, `StatusDescription`) VALUES
(1,	'Pending',	'Pending order');

DROP TABLE IF EXISTS `parametersalbum`;
CREATE TABLE `parametersalbum` (
  `ParametersAlbumID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ParametersAlbumID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `parametersalbum` (`ParametersAlbumID`) VALUES
(1),
(2),
(3);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `photo` (`PhotoID`, `PhotoName`, `PhotoURL`, `PhotoAlbumID`, `PhotoAltText`, `CoverPhoto`) VALUES
(1,	'Foto Galaxy Nexus 01',	'main.png',	1,	'Foto Galaxy Nexus',	1),
(2,	'Chromebook',	'main.jpg',	2,	'Chromebook',	1),
(3,	'Sony Xperia Z',	'sony.jpg',	3,	'Xperia Z',	1),
(5,	'6658.jpg',	'6658.jpg',	4,	's4',	1);

DROP TABLE IF EXISTS `photoalbum`;
CREATE TABLE `photoalbum` (
  `PhotoAlbumID` int(11) NOT NULL AUTO_INCREMENT,
  `PhotoAlbumName` varchar(45) DEFAULT NULL,
  `PhotoAlbumDescription` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`PhotoAlbumID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `photoalbum` (`PhotoAlbumID`, `PhotoAlbumName`, `PhotoAlbumDescription`) VALUES
(1,	'Album GN',	'Galaxy Nexus album'),
(2,	'Chromebook',	'Chromebook gallery'),
(3,	'Trojka',	'trojka'),
(4,	'ctyrka',	'ctyrka'),
(5,	'petka',	'petka');

DROP TABLE IF EXISTS `price`;
CREATE TABLE `price` (
  `PriceID` int(11) NOT NULL AUTO_INCREMENT,
  `BuyingPrice` float DEFAULT NULL,
  `SellingPrice` float DEFAULT NULL,
  `TAX` float DEFAULT '1.2',
  `SALE` float DEFAULT '1',
  `FinalPrice` float DEFAULT '0',
  `CurrencyID` int(11) DEFAULT '1',
  PRIMARY KEY (`PriceID`),
  KEY `CurrencyID_idx` (`CurrencyID`),
  CONSTRAINT `FKPriceCurrency` FOREIGN KEY (`CurrencyID`) REFERENCES `currency` (`CurrencyID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `price` (`PriceID`, `BuyingPrice`, `SellingPrice`, `TAX`, `SALE`, `FinalPrice`, `CurrencyID`) VALUES
(1,	0,	0,	0,	0,	100,	1),
(2,	0,	99,	1.2,	0,	99,	1),
(3,	5999,	7999,	1.2,	0,	8999,	1),
(4,	9999,	15000,	1.2,	0,	16999,	1),
(5,	8999,	13999,	1.2,	0,	15999,	1),
(6,	6666,	10000,	1.2,	0,	11999,	1);

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductName` varchar(255) DEFAULT NULL,
  `Producer` varchar(255) DEFAULT NULL,
  `PhotoAlbumID` int(11) DEFAULT NULL,
  `ProductNumber` varchar(255) DEFAULT NULL,
  `ProductDescription` varchar(1000) DEFAULT NULL,
  `ProductStatusID` int(11) DEFAULT NULL,
  `ParametersAlbumID` int(11) DEFAULT NULL,
  `ProductEAN` varchar(45) DEFAULT NULL,
  `ProductQR` varchar(45) DEFAULT NULL,
  `ProductWarranty` varchar(45) DEFAULT NULL,
  `PiecesAvailable` int(11) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `PriceID` int(11) DEFAULT NULL,
  `DateOfAvailable` date DEFAULT NULL,
  `ProductDateOfAdded` int(11) DEFAULT NULL,
  `DocumentationID` int(11) DEFAULT NULL,
  `CommentID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ProductID`),
  KEY `PhotoAlbumID_idx` (`PhotoAlbumID`),
  KEY `CategoryID_idx` (`CategoryID`),
  KEY `CommentID_idx` (`CommentID`),
  KEY `ParametersAlbumID_idx` (`ParametersAlbumID`),
  KEY `FKProductPrice_idx` (`PriceID`),
  KEY `ProductStatusID` (`ProductStatusID`),
  CONSTRAINT `FKProductCategory` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKProductComment` FOREIGN KEY (`CommentID`) REFERENCES `comment` (`CommentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKProductPhotoAlbum` FOREIGN KEY (`PhotoAlbumID`) REFERENCES `photoalbum` (`PhotoAlbumID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FKProductPrice` FOREIGN KEY (`PriceID`) REFERENCES `price` (`PriceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_ibfk_2` FOREIGN KEY (`ParametersAlbumID`) REFERENCES `parametersalbum` (`ParametersAlbumID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_ibfk_3` FOREIGN KEY (`ProductStatusID`) REFERENCES `productstatus` (`ProductStatusID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `product` (`ProductID`, `ProductName`, `Producer`, `PhotoAlbumID`, `ProductNumber`, `ProductDescription`, `ProductStatusID`, `ParametersAlbumID`, `ProductEAN`, `ProductQR`, `ProductWarranty`, `PiecesAvailable`, `CategoryID`, `PriceID`, `DateOfAvailable`, `ProductDateOfAdded`, `DocumentationID`, `CommentID`) VALUES
(1,	'Samsung Galaxy Nexus',	'Samsung',	1,	NULL,	'Smartphone ze serie Nexus',	NULL,	1,	NULL,	NULL,	NULL,	10,	2,	3,	NULL,	NULL,	NULL,	1),
(2,	'Samsung Chromebook',	'Samsung',	2,	NULL,	'Chromebook od Samsungu',	NULL,	2,	NULL,	NULL,	NULL,	4,	2,	3,	NULL,	NULL,	NULL,	NULL),
(3,	'Samsung Galaxy S4',	'Samsung',	4,	NULL,	'Hot news in smartphone world',	NULL,	1,	NULL,	NULL,	NULL,	99,	3,	4,	NULL,	NULL,	NULL,	NULL),
(4,	'Sony Xperia Z',	'Sony',	3,	NULL,	'Best smartphone of present smarthone world',	NULL,	1,	NULL,	NULL,	NULL,	40,	3,	5,	NULL,	NULL,	NULL,	NULL),
(5,	'Apple iPad',	'Apple Inc.',	5,	NULL,	'Tablet from company Apple',	NULL,	3,	NULL,	NULL,	NULL,	666,	4,	6,	NULL,	NULL,	NULL,	NULL),
(6,	'Nokia 3310',	'neuvedeno',	4,	'11111',	'desc',	NULL,	1,	'123456',	'122',	'rok',	1,	2,	2,	'0000-00-00',	0,	1,	1);

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
  `Login` varchar(30) NOT NULL,
  `Password` varchar(30) DEFAULT NULL,
  `FirstName` varchar(45) DEFAULT NULL,
  `SureName` varchar(45) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `PhoneNumber` int(11) DEFAULT NULL,
  `AddressID` int(11) DEFAULT NULL,
  `CompanyName` varchar(45) DEFAULT NULL,
  `TIN` varchar(45) DEFAULT NULL,
  `Permission` varchar(6) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`Login`),
  KEY `Address_idx` (`AddressID`),
  CONSTRAINT `FKUserAddress` FOREIGN KEY (`AddressID`) REFERENCES `addresses` (`AdressID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`Login`, `Password`, `FirstName`, `SureName`, `Email`, `PhoneNumber`, `AddressID`, `CompanyName`, `TIN`, `Permission`) VALUES
('admin',	'$2a$07$nta28l2g7n9ld4le56xpgeq',	'Admin',	'Admin',	'admin@admin.cz',	0,	1,	'0',	'0',	'admin'),
('kifa',	'$2a$07$wllhgar90ofg24nsaywd3u2',	'Kifa',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'0'),
('novak',	'novak',	'Jan',	'Novak',	'jan.novak@company.com',	999888777,	2,	'Company',	'819281293',	'0'),
('test',	'$2a$07$67p8256pml1lrn1a8d986eN',	'Testovaci',	'Subjekt',	'testovaci@subjekt.cz',	777888999,	1,	'0',	'0',	'0'),
('tomik',	'$2a$07$xshgrgluo88ug5qvohjvme0',	'Tomas',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'0');

-- 2013-03-28 16:38:33
