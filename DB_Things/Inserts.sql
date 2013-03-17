INSERT INTO `addresses` (`AdressID`, `Street`, `HouseNumber`, `ZIPCode`, `City`, `State`) VALUES
(1,	'Vaclavska',	1,	11000,	'Praha',	'CZ'),
(2,	'Nova',	2222,	99999,	'Plzen',	'CZ'),
(3, 'Los Santos', 19238, 99923, 'Sao Paolo', 'Br');

INSERT INTO `currency` (`CurrencyID`, `CurrencyCode`, `CurrencyName`, `CurrencyRate`) VALUES
(1,	'CZK',	'Koruna èeská',	NULL),
(2, 'EUR', 'Euro', NULL);

INSERT INTO `price` (`PriceID`, `BuyingPrice`, `SellingPrice`, `TAX`, `SALE`, `FinalPrice`, `CurrencyID`) VALUES
(1,	0,	0,	0,	0,	NULL,	1),
(2,	0,	99,	1.2,	0,	99,	1),
(3,	5999,	7999,	1.2,	0,	8999,	1),
(4, 9999, 15000, 1.2, 0, 16999, 1),
(5, 8999, 13999, 1.2, 0, 15999, 1),
(6, 6666, 10000, 1.2, 0, 11999, 1);

INSERT INTO `category` (`CategoryID`, `CategoryName`, `CategoryDescription`, `HigherCategoryID`) VALUES
(1,	'Cellphone',	'Cellphone category',	NULL),
(2,	'Notebook',	'Notebooks category',	NULL),
(3,	'Smartphone',	'Smartphone category',	1),
(4, 'Tablets', 'Tablets category', NULL);

INSERT INTO `comment` (`CommentID`, `CommentTittle`, `CommentContent`, `DateOfAdded`, `AuthorID`, `PreviousCommentID`) VALUES
(1,	'First Comment',	'This is first comment ever',	'2012-03-20',	1,	NULL),
(2,	'Reakce na koment',	'Piš èesky pitomo',	'2012-03-20',	2,	1);

INSERT INTO `photoalbum` (`PhotoAlbumID`, `PhotoAlbumName`, `PhotoAlbumDescription`) VALUES
(1,	'Album GN',	'Galaxy Nexus album');

INSERT INTO `photo` (`PhotoID`, `PhotoName`, `PhotoURL`, `PhotoAlbumID`, `PhotoAltText`) VALUES
(1,	'Foto Galaxy Nexus 01',	'http://www.google.com/nexus/images/n4-product-hero.png',	1,	'Foto Galaxy Nexus');

INSERT INTO `delivery` (`DeliveryID`, `TypeOfDelivery`, `DeliveryDescription`, `PriceID`, `FreeFromPrice`) VALUES
(1,	'Personal pick up',	'Personal in the shop',	NULL,	NULL),
(2,	'Cash on delivery',	'Send by transport company',	1,	1000);

INSERT INTO `parametersalbum` (`ParametersAlbumID`) VALUES
(1),
(2),
(3);

INSERT INTO `paymentmethod` (`PaymentMethodID`, `PaymentMethodName`, `PriceID`) VALUES
(1,	'Cash',	1),
(2,	'Banwire',	2);

INSERT INTO `users` (`Login`, `Password`, `FirstName`, `SureName`, `Email`, `PhoneNumber`, `AddressID`, `CompanyName`, `TIN`, `Permission`) VALUES
('admin',	'admin',	'Admin',	'Admin',	'admin@admin.cz',	0,	1,	'0',	'0',	1),
('novak',	'novak',	'Jan',	'Novak',	'jan.novak@company.com',	999888777,	2,	'Company',	'819281293',	0),
('test',	'test',	'Testovaci',	'Subjekt',	'testovaci@subjekt.cz',	777888999,	1,	'0',	'0',	0);

INSERT INTO `product` (`ProductID`, `ProductName`, `Producer`, `PhotoAlbumID`, `ProductNumber`, `ProductDescription`, `ParametersAlbumID`, `ProductEAN`, `ProductQR`, `ProductWarranty`, `PiecesAvailable`, `CategoryID`, `PriceID`, `DateOfAvailable`, `ProductDateOfAdded`, `DocumentationID`, `CommentID`) VALUES
(1,	'Samsung Galaxy Nexus',	'Samsung',	1,	NULL,	'Smartphone ze serie Nexus',	1,	NULL,	NULL,	NULL,	10,	2,	3,	NULL,	NULL,	NULL,	1),
(2,	'Samsung Chromebook',	'Samsung',	NULL,	NULL,	'Chromebook od Samsungu',	2,	NULL,	NULL,	NULL,	4,	2,	3,	NULL,	NULL,	NULL,	NULL),
(3, 'Samsung Galaxy S4', 'Samsung', NULL, NULL, 'Hot news in smartphone world', 1, NULL, NULL, NULL, 99, 3, 4, NULL, NULL, NULL, NULL),
(4, 'Sony Xperia Z', 'Sony', NULL, NULL, 'Best smartphone of present smarthone world', 1, NULL, NULL, NULL, 40, 3, 5, NULL, NULL, NULL, NULL),
(5, 'Apple iPad', 'Apple Inc.', NULL, NULL, 'Tablet from company Apple', 3, NULL, NULL, NULL, 666, 4, 6, NULL, NULL, NULL, NULL);