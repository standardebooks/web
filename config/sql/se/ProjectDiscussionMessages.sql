CREATE TABLE IF NOT EXISTS `ProjectDiscussionMessages` (
  `MessageId` varchar(255) NOT NULL,
  `ProjectId` int(10) unsigned NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`MessageId`),
  KEY `ProjectId` (`ProjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
