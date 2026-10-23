CREATE TABLE IF NOT EXISTS `SessionLoginCodes` (
  `Code` uuid NOT NULL DEFAULT uuid_v7(),
  `UserId` int(10) unsigned NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `ExpiresAt` datetime NOT NULL,
  `EnteredAt` datetime DEFAULT NULL,
  `IpAddress` inet6 DEFAULT NULL,
  UNIQUE KEY `Code` (`Code`),
  KEY `UserId_CreatedAt` (`UserId`,`CreatedAt`),
  KEY `CreatedAt` (`CreatedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
