CREATE TABLE IF NOT EXISTS `NewsletterSubscriptions` (
  `UserId` int(10) unsigned NOT NULL,
  `IsConfirmed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `IsSubscribedToNewsletter` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `IsSubscribedToSummary` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
