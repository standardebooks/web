CREATE TABLE IF NOT EXISTS `ProjectReminders` (
  `ProjectId` int(10) unsigned NOT NULL,
  `Type` enum('abandoned','stalled') NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
