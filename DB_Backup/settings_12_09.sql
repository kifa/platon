-- Adminer 3.6.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `SettingID` int(11) NOT NULL AUTO_INCREMENT,
  `SettingName` varchar(100) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`SettingID`),
  UNIQUE KEY `Name` (`SettingName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`SettingID`, `SettingName`, `Value`) VALUES
(1,	'ShopLayout',	'default'),
(2,	'Description',	'Ecommerce 2.0 that works almost automatically.'),
(3,	'HomepageLayout',	'default'),
(4,	'Logo',	'logo (1).png'),
(5,	'Name',	'Birneshop DEMOtime'),
(6,	'ProductLayout',	'default'),
(7,	'TAX',	'20'),
(8,	'OrderMail',	'luk.danek@gmail.com'),
(9,	'ContactMail',	'jiri.kifa@gmail.com'),
(10,	'ContactPhone',	'+420333444'),
(11,	'CompanyAddress',	'Zdice 18, U hřibitova 34567'),
(12,	'InvoicePrefix',	'i-2013'),
(13,	'ProductMiniLayout',	'fourinline'),
(14,	'GA',	'UA-42741537-1'),
(15,	'ShopURL',	'http://localhost'),
(27,	'zasilkovnaAPI',	'66fbbe30bb36aee4'),
(28,	'zasilkovnaID',	'353'),
(29,	'Salt',	'Salt'),
(30,	'gapiAPI',	'4/BdPpTGmoBcA6YXzCNLQrKDIHfTd6.Qi927urqOpsVOl05ti8ZT3axvvIhgQI'),
(31,	'gapiTOKEN',	'{\"access_token\":\"ya29.AHES6ZRUGTx_HsylhCX4s5f4W-d2Rn2HS3I2c8kvdTi2y1L1Kglrsobd\",\"token_type\":\"Bearer\",\"expires_in\":3600,\"refresh_token\":\"1\\/gWO9VgJWOC1YngESadNHDhAFsO2YioYyGFcc3mTY6Uw\",\"created\":1376821668}'),
(32,	'bankwireID',	'7'),
(33,	'Account',	''),
(34,	'slider',	'unnamed.png'),
(35,	'TopMenu',	'Category'),
(36,	'SideMenu',	'Category'),
(37,	'FooterMenu',	'Info');

-- 2013-09-12 21:42:58
