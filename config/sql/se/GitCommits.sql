CREATE TABLE IF NOT EXISTS `GitCommits` (
  `EbookId` int(10) unsigned NOT NULL,
  `Message` text NOT NULL,
  `Hash` char(40) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `EbookId` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
