CREATE TABLE IF NOT EXISTS `Contributors` (
  `ContributorId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `SortName` varchar(255) DEFAULT NULL,
  `WikipediaUrl` varchar(255) DEFAULT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `MetadataXmlFragment` text DEFAULT NULL,
  `NacoafUrl` varchar(255) DEFAULT NULL,
  `DeathYear` smallint(4) NULL,
  `IsDeathYearCirca` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ContributorId`),
  KEY `NacoafUrl` (`NacoafUrl`),
  KEY `WikipediaUrl` (`WikipediaUrl`),
  KEY `Name` (`Name`),
  KEY `UrlName` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
