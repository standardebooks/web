CREATE TABLE IF NOT EXISTS `EbookSources` (
  `EbookId` int(10) unsigned NOT NULL,
  `Type` enum('project_gutenberg', 'project_gutenberg_australia', 'project_gutenberg_canada', 'internet_archive', 'hathi_trust', 'wikisource', 'google_books', 'faded_page', 'other') NOT NULL DEFAULT 'other',
  `Url` varchar(255) NOT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  KEY `index1` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
