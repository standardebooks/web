CREATE TABLE IF NOT EXISTS `NewsletterSubscriptions` (
  `UserId` int(10) unsigned NOT NULL,
  `NewsletterId` int(110) unsigned NOT NULL,
  `IsConfirmed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `IsVisible` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `idxUnique` (`UserId`,`NewsletterId`),
  KEY `newsletterid` (`NewsletterId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
