CREATE TABLE `Ebooks` (
  `EbookId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AbsoluteUrl` varchar(255) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated` timestamp NOT NULL,
  PRIMARY KEY (`EbookId`),
  UNIQUE KEY `index1` (`AbsoluteUrl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
