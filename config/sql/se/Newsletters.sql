CREATE TABLE IF NOT EXISTS `Newsletters` (
  `NewsletterId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(80) NOT NULL,
  `UrlName` varchar(80) NOT NULL,
  `Description` mediumtext DEFAULT NULL,
  `IsVisible` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `SortOrder` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`NewsletterId`),
  KEY `idxSort` (`IsVisible`, `SortOrder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
