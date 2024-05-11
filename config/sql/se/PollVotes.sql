CREATE TABLE `PollVotes` (
  `UserId` int(10) unsigned NOT NULL,
  `PollItemId` int(10) unsigned NOT NULL,
  `Created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  UNIQUE KEY `idxUnique` (`PollItemId`,`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
