CREATE TABLE IF NOT EXISTS `se`.`ProjectReminders` (
  `ProjectId` INT UNSIGNED NOT NULL,
  `Type` ENUM('abandoned', 'stalled') NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
