CREATE TABLE `EbookLocSubjects` (
  `EbookLocSubjectId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `EbookId` int(10) unsigned NOT NULL,
  `LocSubjectId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`EbookLocSubjectId`),
  UNIQUE KEY `idxUnique` (`EbookId`,`LocSubjectId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
