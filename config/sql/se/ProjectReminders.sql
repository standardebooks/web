CREATE TABLE IF NOT EXISTS `se`.`ProjectReminders` (
  `ProjectId` INT UNSIGNED NOT NULL,
  `Created` TIMESTAMP NOT NULL,
  `Type` ENUM('abandoned', 'stalled') NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
