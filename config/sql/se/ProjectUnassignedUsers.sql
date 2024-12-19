CREATE TABLE `ProjectUnassignedUsers` (
  `UserId` int(10) unsigned NOT NULL,
  `Role` enum('manager','reviewer') NOT NULL,
  UNIQUE KEY `idxUnique` (`Role`,`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
