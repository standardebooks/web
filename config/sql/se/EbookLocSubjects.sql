CREATE TABLE IF NOT EXISTS `EbookLocSubjects` (
  `EbookId` int(10) unsigned NOT NULL,
  `LocSubjectId` int(10) unsigned NOT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `EbookId_LocSubjectId` (`EbookId`,`LocSubjectId`),
  KEY `LocSubjectId` (`LocSubjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
