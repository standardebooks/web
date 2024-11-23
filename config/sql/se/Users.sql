CREATE TABLE IF NOT EXISTS `Users` (
  `UserId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(80) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated` timestamp NOT NULL DEFAULT current_timestamp() on update current_timestamp(),
  `Uuid` char(36) NOT NULL DEFAULT (uuid()),
  `PasswordHash` varchar(255) NULL,
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `idxEmail` (`Email`,`Uuid`,`UserId`),
  UNIQUE KEY `idxUniqueEmail` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `se`.`Users`
ADD INDEX `idxUniqueEmail` (`Email` ASC) VISIBLE;
;
