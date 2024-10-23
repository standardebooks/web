CREATE TABLE IF NOT EXISTS `ArtworkTags` (
  `ArtworkId` int(10) unsigned NOT NULL,
  `TagId` int(10) unsigned NOT NULL,
  UNIQUE KEY `idxUnique` (`ArtworkId`,`TagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
