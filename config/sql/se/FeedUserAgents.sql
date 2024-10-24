CREATE TABLE IF NOT EXISTS `FeedUserAgents` (
  `UserAgentId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserAgent` text NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserAgentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
