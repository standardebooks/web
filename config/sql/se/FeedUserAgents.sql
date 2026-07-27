CREATE TABLE IF NOT EXISTS `FeedUserAgents` (
  `UserAgentId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserAgent` text NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`UserAgentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
