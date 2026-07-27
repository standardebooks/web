CREATE TABLE IF NOT EXISTS `Users` (
  `UserId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Email` varchar(80) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Uuid` char(36) NOT NULL DEFAULT (uuid()),
  `PasswordHash` varchar(255) NULL,
  `CanReceiveEmail` boolean NOT NULL DEFAULT TRUE,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `Email_Uuid_UserId` (`Email`,`Uuid`,`UserId`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
