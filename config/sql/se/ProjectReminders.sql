CREATE TABLE IF NOT EXISTS `se`.`ProjectReminders` (
  `ProjectId` INT UNSIGNED NOT NULL,
  `Created` TIMESTAMP NOT NULL,
  `Type` ENUM('abandoned', 'stalled') NOT NULL);
