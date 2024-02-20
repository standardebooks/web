CREATE TABLE `Contributors` (
  `EbookId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `SortName` varchar(255) NULL,
  `WikipediaUrl` varchar(255) NULL,
  `MarcRole` varchar(10) NULL,
  `FullName` varchar(255) NULL,
  `NacoafUrl` varchar(255) NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  KEY `index1` (`EbookId`),
  KEY `index2` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
