CREATE TABLE IF NOT EXISTS `NewsletterSubscriptions` (
  `UserId` int(10) unsigned NOT NULL,
  `NewsletterId` int(110) unsigned NOT NULL,
  `IsConfirmed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `IsVisible` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `UserId_NewsletterId` (`UserId`,`NewsletterId`),
  KEY `NewsletterId` (`NewsletterId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
