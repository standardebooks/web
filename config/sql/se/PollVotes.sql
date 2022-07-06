CREATE TABLE `PollVotes` (
  `UserId` int(11) unsigned NOT NULL,
  `PollItemId` int(11) unsigned NOT NULL,
  `Created` datetime NOT NULL,
  UNIQUE KEY `idxUnique` (`PollItemId`,`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
