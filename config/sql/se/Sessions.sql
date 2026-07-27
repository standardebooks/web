CREATE TABLE IF NOT EXISTS `Sessions` (
  `UserId` int(10) unsigned NOT NULL,
  `SessionId` char(36) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `UserId` (`UserId`),
  KEY `SessionId` (`SessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
