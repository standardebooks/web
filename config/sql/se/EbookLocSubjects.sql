CREATE TABLE `EbookLocSubjects` (
  `EbookId` int(10) unsigned NOT NULL,
  `LocSubjectId` int(10) unsigned NOT NULL,
  UNIQUE KEY `idxUnique` (`EbookId`,`LocSubjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
