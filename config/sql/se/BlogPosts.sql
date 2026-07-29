CREATE TABLE IF NOT EXISTS `BlogPosts` (
  `BlogPostId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserId` int(10) unsigned NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Subtitle` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `UrlTitle` varchar(255) NOT NULL,
  `Body` longtext DEFAULT NULL,
  `PublishedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ImageCacheKey` char(6) DEFAULT NULL,
  `HeroImageCaption` varchar(255) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`BlogPostId`),
  UNIQUE KEY `UrlTitle` (`UrlTitle`),
  KEY `PublishedAt` (`PublishedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
