CREATE TABLE IF NOT EXISTS `PollVotes` (
  `UserId` int(10) unsigned NOT NULL,
  `PollItemId` int(10) unsigned NOT NULL,
  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `idxUnique` (`PollItemId`,`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
