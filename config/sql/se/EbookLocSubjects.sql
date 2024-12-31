CREATE TABLE IF NOT EXISTS `EbookLocSubjects` (
  `EbookId` int(10) unsigned NOT NULL,
  `LocSubjectId` int(10) unsigned NOT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  UNIQUE KEY `idxUnique` (`EbookId`,`LocSubjectId`),
  KEY `index1` (`LocSubjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
