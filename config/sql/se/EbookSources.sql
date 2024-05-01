CREATE TABLE `EbookSources` (
  `EbookSourceId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EbookId` int(10) unsigned NOT NULL,
  `Type` enum('project_gutenberg', 'project_gutenberg_australia', 'project_gutenberg_canada', 'internet_archive', 'hathi_trust', 'wikisource', 'google_books', 'faded_page', 'other') DEFAULT 'other',
  `Url` varchar(255) NOT NULL,
  PRIMARY KEY (`EbookSourceId`),
  KEY `index1` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
