CREATE TABLE IF NOT EXISTS `Patrons` (
  `UserId` int(10) unsigned NOT NULL,
  `IsAnonymous` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `AlternateName` varchar(80) DEFAULT NULL,
  `BaseCost` decimal(5,2) unsigned DEFAULT NULL,
  `CycleType` enum('monthly','yearly','unlimited') DEFAULT NULL,
  `EndedAt` datetime DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `IsAnonymous_EndedAt` (`IsAnonymous`,`EndedAt`),
  KEY `UserId_EndedAt` (`UserId`,`EndedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
