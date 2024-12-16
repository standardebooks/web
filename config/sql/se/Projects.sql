CREATE TABLE IF NOT EXISTS `Projects` (
  `ProjectId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Status` enum('in_progress','stalled','completed','abandoned') NOT NULL DEFAULT 'in_progress',
  `EbookId` int(11) NOT NULL,
  `ProducerName` varchar(151) NOT NULL DEFAULT '',
  `ProducerEmail` varchar(80) DEFAULT NULL,
  `DiscussionUrl` varchar(255) DEFAULT NULL,
  `VcsUrl` varchar(255) NOT NULL,
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Started` datetime NOT NULL,
  `Ended` datetime DEFAULT NULL,
  `ManagerUserId` int(11) NOT NULL,
  `ReviewerUserId` int(11) NOT NULL,
  `LastCommitTimestamp` DATETIME NULL DEFAULT NULL,
  `LastDiscussionTimestamp` DATETIME NULL DEFAULT NULL,
  `IsStatusAutomaticallyUpdated` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ProjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
