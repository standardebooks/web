CREATE TABLE `GitCommits` (
  `EbookId` int(10) unsigned NOT NULL,
  `Created` datetime NOT NULL,
  `Message` text NOT NULL,
  `Hash` char(40) NOT NULL,
  KEY `index1` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
