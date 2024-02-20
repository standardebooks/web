CREATE TABLE `LocSubjects` (
  `LocSubjectId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`LocSubjectId`),
  UNIQUE KEY `idxUnique` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
