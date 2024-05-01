CREATE TABLE `GitCommits` (
  `GitCommitId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EbookId` int(10) unsigned NOT NULL,
  `Created` datetime NOT NULL,
  `Message` text NOT NULL,
  `Hash` char(40) NOT NULL,
  PRIMARY KEY (`GitCommitId`),
  KEY `index1` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
