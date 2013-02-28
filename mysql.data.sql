SET NAMES utf8;

INSERT INTO `list` (`id`, `title`) VALUES
(1,	'Work'),
(2,	'Home');

INSERT INTO `user` (`id`, `username`, `password`, `name`) VALUES
(1,	'admin',	'$2a$07$tf30578tqb8c8l8cqds8dumnHzjliPwfGKHqWtpzA.TkZQF/G9OWi',	'Administrator'),
(2,	'john',	'$2a$07$toucpxr7gu624ck50dplpekoq5n/1ihBRmURVyV1f0v2ugw8uhl32',	'John Doe'),
(3,	'jane',	'$2a$07$balo1h2actujxo0s9y1exeYfU1puldOfc7WDdfUsDLj.fOglirchO',	'Jane Doe');

INSERT INTO `task` (`id`, `text`, `created`, `done`, `user_id`, `list_id`) VALUES
(1,	'Get milk',	'2011-12-06 12:30:00',	1,	2,	2),
(2,	'Go to post office',	'2011-12-06 12:35:50',	0,	3,	1),
(3,	'Buy printer ink',	'2011-12-07 16:23:30',	0,	2,	1),
(4,	'Make dinner reservations',	'2011-12-10 16:10:40',	0,	3,	2),
(5,	'Return library books',	'2011-12-10 17:44:32',	0,	2,	2),
(6,	'Take out the trash',	'2011-12-12 10:42:31',	0,	2,	2),
(7,	'Buy gift for Jane',	'2011-12-12 10:53:13',	0,	2,	2);
