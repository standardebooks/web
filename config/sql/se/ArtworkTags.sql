CREATE TABLE IF NOT EXISTS `ArtworkTags` (
  `ArtworkId` int(10) unsigned NOT NULL,
  `TagId` int(10) unsigned NOT NULL,
  UNIQUE KEY `ArtworkId_TagId` (`ArtworkId`,`TagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
