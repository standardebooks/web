CREATE TABLE `FeedUserAgents` (
  `UserAgentId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserAgent` text NOT NULL,
  `Created` datetime NOT NULL,
  PRIMARY KEY (`UserAgentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
