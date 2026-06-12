CREATE TABLE IF NOT EXISTS `ProjectDiscussionMessages` (
  `MessageId` varchar(255) NOT NULL,
  `ProjectId` int(10) unsigned NOT NULL,
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`MessageId`),
  KEY `indexProjectId` (`ProjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
