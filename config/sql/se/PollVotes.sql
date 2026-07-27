CREATE TABLE IF NOT EXISTS `PollVotes` (
  `UserId` int(10) unsigned NOT NULL,
  `PollItemId` int(10) unsigned NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `PollItemId_UserId` (`PollItemId`,`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
