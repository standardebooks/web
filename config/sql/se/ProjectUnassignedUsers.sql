CREATE TABLE `ProjectUnassignedUsers` (
  `UserId` int(10) unsigned NOT NULL,
  `Role` enum('manager','reviewer') NOT NULL,
  UNIQUE KEY `Role_UserId` (`Role`,`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
