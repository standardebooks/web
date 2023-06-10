CREATE TABLE `ArtworkTags` (
  `ArtworkId` int(11) unsigned NOT NULL,
  `TagId` int(10) unsigned NOT NULL,
  PRIMARY KEY `idxUnique` (`ArtworkId`,`TagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
