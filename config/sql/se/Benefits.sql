CREATE TABLE `Benefits` (
  `UserId` int(10) unsigned NOT NULL,
  `CanAccessFeeds` tinyint(1) unsigned NOT NULL,
  `CanVote` tinyint(1) unsigned NOT NULL,
  `CanBulkDownload` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`UserId`),
  KEY `idxBenefits` (`CanAccessFeeds`,`CanVote`,`CanBulkDownload`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
