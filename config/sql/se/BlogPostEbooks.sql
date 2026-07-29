CREATE TABLE IF NOT EXISTS `BlogPostEbooks` (
  `BlogPostId` int(10) unsigned NOT NULL,
  `EbookId` int(10) unsigned NOT NULL,
  `SortOrder` smallint(5) unsigned NOT NULL,
  KEY `BlogPostId` (`BlogPostId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
