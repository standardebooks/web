CREATE TABLE IF NOT EXISTS `EmailBounces` (
  `Email` varchar(80) NOT NULL,
  `UserId` int(11) unsigned DEFAULT NULL,
  `Type` enum('account_deactivated','hard','invalid_address','isp_block','spam','soft') NOT NULL,
  `IsActive` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `Source` enum('ses') NOT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `UserId_IsActive` (`UserId`,`IsActive`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
