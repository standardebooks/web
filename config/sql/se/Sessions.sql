CREATE TABLE IF NOT EXISTS `Sessions` (
  `UserId` int(10) unsigned NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SessionId` char(36) NOT NULL,
  KEY `idxUserId` (`UserId`),
  KEY `idxSessionId` (`SessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
