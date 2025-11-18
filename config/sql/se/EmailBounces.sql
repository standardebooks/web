CREATE TABLE IF NOT EXISTS `EmailBounces` (
  `Email` varchar(80) NOT NULL,
  `UserId` int(11) unsigned DEFAULT NULL,
  `Type` enum('account_deactivated','hard','invalid_address','isp_block','spam','soft') NOT NULL,
  `IsActive` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `Updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Created` timestamp NOT NULL DEFAULT current_timestamp(),
  `Source` enum('ses') NOT NULL,
  KEY `idxUserId` (`UserId`,`IsActive`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
