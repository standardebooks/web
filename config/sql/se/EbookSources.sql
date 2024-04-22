CREATE TABLE `EbookSources` (
  `EbookId` int(10) unsigned NOT NULL,
  `Type` enum('pg', 'pg_australia', 'pg_canada', 'ia', 'hathi_trust', 'wikisource', 'google_books', 'faded_page', 'other') DEFAULT 'other',
  `Url` varchar(255) NOT NULL,
  KEY `index1` (`EbookId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
